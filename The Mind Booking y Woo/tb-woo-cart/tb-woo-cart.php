<?php
/**
 * Plugin Name: Tattoo Booking — WooCommerce Cart
 * Plugin URI: https://baltazarg.xyz
 * Description: Extiende Tattoo Booking con carrito lateral, botón "Agregar al carrito" para Elementor, modal post-reserva y creación de órdenes WooCommerce on-hold vinculadas a citas.
 * Version: 1.0.0
 * Author: Baltazar Dev
 * Author URI: https://baltazarg.xyz
 * Requires Plugins: tattoo-booking, woocommerce
 * Text Domain: tb-woo-cart
 */

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'TBWC_VER',  '1.0.0' );
define( 'TBWC_PATH', plugin_dir_path( __FILE__ ) );
define( 'TBWC_URL',  plugin_dir_url( __FILE__ ) );

// ═══════════════════════════════════════════════════════
// GUARDIA — requiere ambos plugins activos
// ═══════════════════════════════════════════════════════
add_action( 'plugins_loaded', 'tbwc_check_dependencies' );
function tbwc_check_dependencies() {
    if ( ! function_exists( 'WC' ) ) {
        add_action( 'admin_notices', function() {
            echo '<div class="notice notice-error"><p><strong>Tattoo Booking — WooCommerce Cart</strong> requiere que <strong>WooCommerce</strong> esté instalado y activo.</p></div>';
        });
        return;
    }
    if ( ! defined( 'TB_VER' ) ) {
        add_action( 'admin_notices', function() {
            echo '<div class="notice notice-error"><p><strong>Tattoo Booking — WooCommerce Cart</strong> requiere que el plugin <strong>Tattoo Booking & CRM</strong> esté instalado y activo.</p></div>';
        });
        return;
    }
    tbwc_init();
}

function tbwc_init() {
    // Migraciones de BD
    add_action( 'init', 'tbwc_maybe_migrate' );

    // Assets frontend
    add_action( 'wp_enqueue_scripts', 'tbwc_enqueue' );

    // Drawer (mini cart) global en el footer
    add_action( 'wp_footer', 'tbwc_render_drawer' );

    // AJAX endpoints
    add_action( 'wp_ajax_tbwc_add_to_cart',        'tbwc_ajax_add_to_cart' );
    add_action( 'wp_ajax_nopriv_tbwc_add_to_cart', 'tbwc_ajax_add_to_cart' );

    add_action( 'wp_ajax_tbwc_remove_from_cart',        'tbwc_ajax_remove' );
    add_action( 'wp_ajax_nopriv_tbwc_remove_from_cart', 'tbwc_ajax_remove' );

    add_action( 'wp_ajax_tbwc_update_qty',        'tbwc_ajax_update_qty' );
    add_action( 'wp_ajax_nopriv_tbwc_update_qty', 'tbwc_ajax_update_qty' );

    add_action( 'wp_ajax_tbwc_get_cart',        'tbwc_ajax_get_cart' );
    add_action( 'wp_ajax_nopriv_tbwc_get_cart', 'tbwc_ajax_get_cart' );

    add_action( 'wp_ajax_tbwc_get_modal_products',        'tbwc_ajax_get_modal_products' );
    add_action( 'wp_ajax_nopriv_tbwc_get_modal_products', 'tbwc_ajax_get_modal_products' );

    add_action( 'wp_ajax_tbwc_create_order',        'tbwc_ajax_create_order' );
    add_action( 'wp_ajax_nopriv_tbwc_create_order', 'tbwc_ajax_create_order' );

    // Hook al guardar cita: activa el modal
    add_filter( 'tb_booking_success_data', 'tbwc_inject_modal_flag', 10, 2 );

    // Panel en admin de WooCommerce
    add_action( 'woocommerce_admin_order_data_after_order_details', 'tbwc_order_meta_panel' );

    // Al completar orden WC → actualizar cita si está configurado
    add_action( 'woocommerce_order_status_changed', 'tbwc_order_status_sync', 10, 3 );

    // Elementor — registrado dentro de init para asegurar que WC y TB están listos
    add_action( 'elementor/widgets/register', 'tbwc_register_widget' );
}

// ═══════════════════════════════════════════════════════
// MIGRACIÓN BD — columna wc_order_id en tb_appointments
// ═══════════════════════════════════════════════════════
function tbwc_maybe_migrate() {
    if ( get_option( 'tbwc_migrated_v1' ) ) return;
    global $wpdb;
    $cols = $wpdb->get_col( "SHOW COLUMNS FROM {$wpdb->prefix}tb_appointments" );
    if ( ! in_array( 'wc_order_id', $cols ) ) {
        $wpdb->query( "ALTER TABLE {$wpdb->prefix}tb_appointments ADD COLUMN wc_order_id BIGINT UNSIGNED NOT NULL DEFAULT 0" );
    }
    update_option( 'tbwc_migrated_v1', true );
}

// ═══════════════════════════════════════════════════════
// ASSETS
// ═══════════════════════════════════════════════════════
function tbwc_enqueue() {
    if ( ! function_exists( 'WC' ) ) return;
    wp_enqueue_style(  'tbwc-style',  TBWC_URL . 'assets/cart.css',  [], TBWC_VER );
    wp_enqueue_script( 'tbwc-script', TBWC_URL . 'assets/cart.js', ['jquery'], TBWC_VER, true );
    wp_localize_script( 'tbwc-script', 'TBWC', [
        'url'          => admin_url( 'admin-ajax.php' ),
        'nonce'        => wp_create_nonce( 'tbwc_nonce' ),
        'currency'     => get_woocommerce_currency_symbol(),
        'reserve_url'  => get_option( 'tbwc_reserve_page_url', home_url( '/' ) ),
        'cat_id'       => intval( get_option( 'tbwc_category_id', 0 ) ),
        'complete_sync'=> get_option( 'tbwc_complete_sync', '1' ),
    ]);
}

// ═══════════════════════════════════════════════════════
// FILTER — inyectar flag modal en respuesta de reserva
// ═══════════════════════════════════════════════════════
function tbwc_inject_modal_flag( $data, $appointment_id ) {
    $cat_id = intval( get_option( 'tbwc_category_id', 0 ) );
    if ( ! $cat_id ) return $data;

    // Verificar que la categoría tenga productos
    $count = wc_get_products([
        'category' => [ get_term( $cat_id )->slug ?? '' ],
        'limit'    => 1,
        'return'   => 'ids',
    ]);
    if ( empty( $count ) ) return $data;

    $data['show_products']  = true;
    $data['appointment_id'] = $appointment_id;
    $data['modal_title']    = get_option( 'tbwc_modal_title', '¿Agregar algo para tu cita?' );
    $data['modal_subtitle'] = get_option( 'tbwc_modal_subtitle', 'Pagarás cuando llegues al estudio.' );
    $data['modal_confirm']  = get_option( 'tbwc_modal_confirm', 'Confirmar productos' );
    $data['modal_skip']     = get_option( 'tbwc_modal_skip', 'Solo mi cita, gracias' );
    return $data;
}

// ═══════════════════════════════════════════════════════
// AJAX — Agregar al carrito
// ═══════════════════════════════════════════════════════
function tbwc_ajax_add_to_cart() {
    check_ajax_referer( 'tbwc_nonce', 'nonce' );
    $product_id = intval( $_POST['product_id'] ?? 0 );
    $qty        = max( 1, intval( $_POST['qty'] ?? 1 ) );

    if ( ! $product_id ) wp_send_json_error( ['msg' => 'Producto no válido.'] );

    $product = wc_get_product( $product_id );
    if ( ! $product || ! $product->is_purchasable() ) {
        wp_send_json_error( ['msg' => 'Producto no disponible.'] );
    }

    WC()->cart->add_to_cart( $product_id, $qty );
    WC()->cart->calculate_totals();

    wp_send_json_success([
        'cart'  => tbwc_get_cart_data(),
        'count' => WC()->cart->get_cart_contents_count(),
    ]);
}

// ═══════════════════════════════════════════════════════
// AJAX — Eliminar del carrito
// ═══════════════════════════════════════════════════════
function tbwc_ajax_remove() {
    check_ajax_referer( 'tbwc_nonce', 'nonce' );
    $cart_key = sanitize_text_field( $_POST['cart_key'] ?? '' );
    if ( $cart_key ) {
        WC()->cart->remove_cart_item( $cart_key );
        WC()->cart->calculate_totals();
    }
    wp_send_json_success([
        'cart'  => tbwc_get_cart_data(),
        'count' => WC()->cart->get_cart_contents_count(),
    ]);
}

// ═══════════════════════════════════════════════════════
// AJAX — Actualizar cantidad
// ═══════════════════════════════════════════════════════
function tbwc_ajax_update_qty() {
    check_ajax_referer( 'tbwc_nonce', 'nonce' );
    $cart_key = sanitize_text_field( $_POST['cart_key'] ?? '' );
    $qty      = max( 0, intval( $_POST['qty'] ?? 1 ) );

    if ( $cart_key ) {
        if ( $qty === 0 ) {
            WC()->cart->remove_cart_item( $cart_key );
        } else {
            WC()->cart->set_quantity( $cart_key, $qty );
        }
        WC()->cart->calculate_totals();
    }
    wp_send_json_success([
        'cart'  => tbwc_get_cart_data(),
        'count' => WC()->cart->get_cart_contents_count(),
    ]);
}

// ═══════════════════════════════════════════════════════
// AJAX — Obtener carrito completo
// ═══════════════════════════════════════════════════════
function tbwc_ajax_get_cart() {
    check_ajax_referer( 'tbwc_nonce', 'nonce' );
    wp_send_json_success([
        'cart'  => tbwc_get_cart_data(),
        'count' => WC()->cart->get_cart_contents_count(),
    ]);
}

// ═══════════════════════════════════════════════════════
// AJAX — Productos para el modal post-reserva
// ═══════════════════════════════════════════════════════
function tbwc_ajax_get_modal_products() {
    check_ajax_referer( 'tbwc_nonce', 'nonce' );
    $cat_id = intval( get_option( 'tbwc_category_id', 0 ) );
    if ( ! $cat_id ) wp_send_json_error( ['msg' => 'Sin categoría configurada.'] );

    $term = get_term( $cat_id, 'product_cat' );
    if ( ! $term || is_wp_error( $term ) ) wp_send_json_error( ['msg' => 'Categoría inválida.'] );

    $products = wc_get_products([
        'category' => [ $term->slug ],
        'limit'    => 20,
        'status'   => 'publish',
        'orderby'  => 'menu_order',
        'order'    => 'ASC',
    ]);

    $data = [];
    foreach ( $products as $product ) {
        $img_id = $product->get_image_id();
        $img    = $img_id ? wp_get_attachment_image_url( $img_id, 'thumbnail' ) : wc_placeholder_img_src( 'thumbnail' );
        $data[] = [
            'id'          => $product->get_id(),
            'name'        => $product->get_name(),
            'price'       => $product->get_price(),
            'price_html'  => $product->get_price_html(),
            'image'       => $img,
            'description' => wp_trim_words( $product->get_short_description() ?: $product->get_description(), 12 ),
        ];
    }
    wp_send_json_success( $data );
}

// ═══════════════════════════════════════════════════════
// AJAX — Crear orden WooCommerce on-hold
// ═══════════════════════════════════════════════════════
function tbwc_ajax_create_order() {
    check_ajax_referer( 'tbwc_nonce', 'nonce' );

    $appointment_id = intval( $_POST['appointment_id'] ?? 0 );
    $items_raw      = $_POST['items'] ?? [];
    $client         = [
        'name'  => sanitize_text_field( $_POST['name']  ?? '' ),
        'email' => sanitize_email(      $_POST['email'] ?? '' ),
        'phone' => sanitize_text_field( $_POST['phone'] ?? '' ),
    ];

    if ( ! $appointment_id || empty( $items_raw ) ) {
        wp_send_json_error( ['msg' => 'Datos incompletos.'] );
    }

    // Datos de la cita
    global $wpdb;
    $appt = $wpdb->get_row( $wpdb->prepare(
        "SELECT a.*, b.name as branch_name FROM {$wpdb->prefix}tb_appointments a
         LEFT JOIN {$wpdb->prefix}tb_branches b ON b.id = a.branch_id
         WHERE a.id = %d", $appointment_id
    ));
    if ( ! $appt ) wp_send_json_error( ['msg' => 'Cita no encontrada.'] );
    if ( $appt->wc_order_id ) wp_send_json_error( ['msg' => 'Esta cita ya tiene una orden vinculada.'] );

    // Crear o encontrar cliente WooCommerce
    $wc_user_id = 0;
    if ( $client['email'] ) {
        $user = get_user_by( 'email', $client['email'] );
        if ( $user ) {
            $wc_user_id = $user->ID;
        } else {
            // No crear usuario WP, la orden irá como guest
        }
    }

    // Crear la orden
    $order = wc_create_order([
        'customer_id' => $wc_user_id,
        'status'      => 'on-hold',
    ]);

    if ( is_wp_error( $order ) ) {
        wp_send_json_error( ['msg' => 'Error al crear la orden: ' . $order->get_error_message() ] );
    }

    // Agregar productos
    $total_added = 0;
    foreach ( $items_raw as $item ) {
        $prod_id = intval( $item['id'] ?? 0 );
        $qty     = max( 1, intval( $item['qty'] ?? 1 ) );
        $product = wc_get_product( $prod_id );
        if ( $product && $product->is_purchasable() ) {
            $order->add_product( $product, $qty );
            $total_added++;
        }
    }

    if ( ! $total_added ) {
        wp_delete_post( $order->get_id(), true );
        wp_send_json_error( ['msg' => 'No se pudo agregar ningún producto válido.'] );
    }

    // Dirección del cliente
    $name_parts = explode( ' ', $client['name'], 2 );
    $addr = [
        'first_name' => $name_parts[0] ?? '',
        'last_name'  => $name_parts[1] ?? '',
        'email'      => $client['email'],
        'phone'      => $client['phone'],
    ];
    $order->set_address( $addr, 'billing' );

    // Método de pago: en tienda
    $order->set_payment_method( 'cod' );
    $order->set_payment_method_title( 'Pago en tienda al llegar a tu cita' );

    // Metas vinculando la cita
    $appt_date_fmt = date( 'd/m/Y', strtotime( $appt->appt_date ) );
    $appt_time_fmt = date( 'H:i',   strtotime( $appt->appt_time ) );
    $order->update_meta_data( '_tb_appointment_id',   $appointment_id );
    $order->update_meta_data( '_tb_branch_id',        $appt->branch_id );
    $order->update_meta_data( '_tb_appointment_date', $appt_date_fmt . ' a las ' . $appt_time_fmt );
    $order->update_meta_data( '_tb_client_name',      $client['name'] );

    // Notas
    $order->add_order_note( sprintf(
        'Pedido vinculado a cita #%d — %s el %s a las %s en %s',
        $appointment_id,
        esc_html( $client['name'] ),
        $appt_date_fmt,
        $appt_time_fmt,
        esc_html( $appt->branch_name )
    ));
    $order->add_order_note(
        'Nota al cliente: Tus productos estarán listos para tu cita. Pagarás cuando llegues.',
        true // nota visible al cliente
    );

    $order->calculate_totals();
    $order->save();

    // Vincular la orden en la cita
    $wpdb->update(
        "{$wpdb->prefix}tb_appointments",
        ['wc_order_id' => $order->get_id()],
        ['id' => $appointment_id]
    );

    wp_send_json_success([
        'order_id' => $order->get_id(),
        'total'    => $order->get_formatted_order_total(),
        'msg'      => '¡Listo! Tus productos estarán esperándote. Pagas cuando llegues a tu cita.',
    ]);
}

// ═══════════════════════════════════════════════════════
// HELPER — Datos del carrito para el frontend
// ═══════════════════════════════════════════════════════
function tbwc_get_cart_data() {
    $cart  = WC()->cart;
    $items = [];
    foreach ( $cart->get_cart() as $key => $item ) {
        $product = $item['data'];
        $img_id  = $product->get_image_id();
        $img     = $img_id
            ? wp_get_attachment_image_url( $img_id, 'thumbnail' )
            : wc_placeholder_img_src( 'thumbnail' );
        $items[] = [
            'key'       => $key,
            'id'        => $product->get_id(),
            'name'      => $product->get_name(),
            'price'     => floatval( $product->get_price() ),
            'price_fmt' => wc_price( $product->get_price() ),
            'qty'       => $item['quantity'],
            'subtotal'  => floatval( $product->get_price() * $item['quantity'] ),
            'sub_fmt'   => wc_price( $product->get_price() * $item['quantity'] ),
            'image'     => $img,
        ];
    }
    return [
        'items'     => $items,
        'total'     => floatval( $cart->get_cart_total() ),
        'total_fmt' => $cart->get_cart_total(),
        'count'     => $cart->get_cart_contents_count(),
        'empty'     => $cart->is_empty(),
    ];
}

// ═══════════════════════════════════════════════════════
// PANEL EN ADMIN DE WOOCOMMERCE
// ═══════════════════════════════════════════════════════
function tbwc_order_meta_panel( $order ) {
    $appt_id   = $order->get_meta( '_tb_appointment_id' );
    $appt_date = $order->get_meta( '_tb_appointment_date' );
    $appt_name = $order->get_meta( '_tb_client_name' );
    if ( ! $appt_id ) return;
    $edit_url = admin_url( "admin.php?page=tb-appointments&appt_id={$appt_id}" );
    ?>
    <div class="tbwc-order-panel">
        <h3>🖋 Datos de la Cita Vinculada</h3>
        <table class="tbwc-meta-table">
            <tr><th>Cita #</th><td><?= esc_html($appt_id) ?> <a href="<?= esc_url($edit_url) ?>" target="_blank">Ver en CRM →</a></td></tr>
            <tr><th>Fecha/Hora</th><td><?= esc_html($appt_date) ?></td></tr>
            <tr><th>Cliente</th><td><?= esc_html($appt_name) ?></td></tr>
        </table>
    </div>
    <style>
    .tbwc-order-panel { background:#f8f9fa; border:1px solid #e5e7eb; border-radius:6px; padding:16px; margin:16px 0; }
    .tbwc-order-panel h3 { margin:0 0 10px; font-size:13px; color:#1a1a2e; }
    .tbwc-meta-table { width:100%; border-collapse:collapse; font-size:13px; }
    .tbwc-meta-table th { width:100px; color:#6b7280; font-weight:600; text-align:left; padding:4px 8px 4px 0; }
    .tbwc-meta-table td { color:#111827; padding:4px 0; }
    .tbwc-meta-table a { color:#2563eb; text-decoration:none; }
    </style>
    <?php
}

// ═══════════════════════════════════════════════════════
// SYNC: completar orden WC → cita a "done"
// ═══════════════════════════════════════════════════════
function tbwc_order_status_sync( $order_id, $old_status, $new_status ) {
    if ( $new_status !== 'completed' ) return;
    if ( get_option( 'tbwc_complete_sync', '1' ) !== '1' ) return;
    $order   = wc_get_order( $order_id );
    $appt_id = $order ? intval( $order->get_meta( '_tb_appointment_id' ) ) : 0;
    if ( ! $appt_id ) return;
    global $wpdb;
    $wpdb->update(
        "{$wpdb->prefix}tb_appointments",
        ['status' => 'done'],
        ['id' => $appt_id]
    );
}

// ═══════════════════════════════════════════════════════
// AJUSTES DEL PLUGIN (página simple en admin)
// ═══════════════════════════════════════════════════════
add_action( 'admin_menu', 'tbwc_admin_menu' );
function tbwc_admin_menu() {
    add_submenu_page( 'tattoo-booking', 'Tienda WooCommerce', '🛒 Tienda', 'manage_options', 'tb-woo-settings', 'tbwc_settings_page' );
}

function tbwc_settings_page() {
    if ( isset($_POST['tbwc_save']) && check_admin_referer('tbwc_settings') ) {
        update_option( 'tbwc_category_id',      intval( $_POST['tbwc_category_id'] ) );
        update_option( 'tbwc_reserve_page_id',  intval( $_POST['tbwc_reserve_page_id'] ) );
        update_option( 'tbwc_reserve_section',  sanitize_text_field( $_POST['tbwc_reserve_section'] ) );
        update_option( 'tbwc_modal_title',      sanitize_text_field( $_POST['tbwc_modal_title'] ) );
        update_option( 'tbwc_modal_subtitle',   sanitize_text_field( $_POST['tbwc_modal_subtitle'] ) );
        update_option( 'tbwc_modal_confirm',    sanitize_text_field( $_POST['tbwc_modal_confirm'] ) );
        update_option( 'tbwc_modal_skip',       sanitize_text_field( $_POST['tbwc_modal_skip'] ) );
        update_option( 'tbwc_complete_sync',    isset($_POST['tbwc_complete_sync']) ? '1' : '0' );

        // Construir y guardar la URL final
        $page_id = intval( $_POST['tbwc_reserve_page_id'] );
        $section = sanitize_text_field( $_POST['tbwc_reserve_section'] );
        $url     = $page_id ? get_permalink( $page_id ) : home_url('/');
        if ( $section ) $url = trailingslashit( $url ) . '#' . ltrim( $section, '#' );
        update_option( 'tbwc_reserve_page_url', $url );

        echo '<div class="notice notice-success is-dismissible"><p>✅ Ajustes guardados.</p></div>';
    }

    // Obtener valores actuales
    $saved_page_id = intval( get_option('tbwc_reserve_page_id', 0) );
    $saved_section = get_option('tbwc_reserve_section', '');
    $saved_url     = get_option('tbwc_reserve_page_url', '');

    $cats  = get_terms(['taxonomy'=>'product_cat','hide_empty'=>false]);
    $pages = get_pages(['post_status'=>'publish','sort_column'=>'post_title']);
    ?>
    <div class="wrap">
        <h1>🛒 Tattoo Booking — Tienda WooCommerce</h1>
        <form method="post">
            <?php wp_nonce_field('tbwc_settings'); ?>
            <table class="form-table">
                <tr><th>Categoría de productos para citas</th><td>
                    <select name="tbwc_category_id">
                        <option value="0">— Selecciona —</option>
                        <?php foreach($cats as $cat): ?>
                            <option value="<?=$cat->term_id?>" <?=selected(get_option('tbwc_category_id'),$cat->term_id,false)?>>
                                <?=esc_html($cat->name)?> (<?=$cat->count?> productos)
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <p class="description">Los productos de esta categoría aparecerán en el modal post-reserva y en el widget de botón.</p>
                </td></tr>

                <tr><th colspan="2"><hr><h2>Botón "Reservar mi Cita" del carrito</h2></th></tr>

                <tr><th>Página de reserva</th><td>
                    <select name="tbwc_reserve_page_id" style="min-width:300px">
                        <option value="0">— Selecciona una página —</option>
                        <?php foreach($pages as $p): ?>
                            <option value="<?=$p->ID?>" <?=selected($saved_page_id,$p->ID,false)?>>
                                <?=esc_html($p->post_title)?> &mdash; <small><?=esc_html(get_permalink($p->ID))?></small>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <p class="description">Página donde está el formulario de reservas.</p>
                </td></tr>

                <tr><th>ID de sección <span style="font-weight:400">(opcional)</span></th><td>
                    <input type="text" name="tbwc_reserve_section" value="<?=esc_attr($saved_section)?>" class="regular-text" placeholder="ej: reservar  o  seccion-formulario">
                    <p class="description">Si el formulario está dentro de una sección con ID en Elementor, escríbelo aquí <strong>sin el #</strong>.<br>
                    El botón llevará directo a esa sección con scroll automático.<br>
                    Para encontrar el ID: en Elementor selecciona la sección → Avanzado → CSS ID.</p>
                    <?php if($saved_url): ?>
                    <p style="margin-top:8px;padding:8px 12px;background:#f0fdf4;border:1px solid #bbf7d0;border-radius:6px;font-size:.82rem;color:#166534">
                        ✅ URL actual del botón: <strong><?=esc_html($saved_url)?></strong>
                    </p>
                    <?php endif; ?>
                </td></tr>

                <tr><th colspan="2"><hr><h2>Textos del Modal Post-Reserva</h2></th></tr>
                <tr><th>Título del modal</th><td><input type="text" name="tbwc_modal_title" value="<?=esc_attr(get_option('tbwc_modal_title','¿Agregar algo para tu cita?'))?>" class="regular-text"></td></tr>
                <tr><th>Subtítulo / nota de pago</th><td><input type="text" name="tbwc_modal_subtitle" value="<?=esc_attr(get_option('tbwc_modal_subtitle','Pagarás cuando llegues al estudio.'))?>" class="regular-text"></td></tr>
                <tr><th>Texto botón confirmar</th><td><input type="text" name="tbwc_modal_confirm" value="<?=esc_attr(get_option('tbwc_modal_confirm','Confirmar productos'))?>" class="regular-text"></td></tr>
                <tr><th>Texto botón omitir</th><td><input type="text" name="tbwc_modal_skip" value="<?=esc_attr(get_option('tbwc_modal_skip','Solo mi cita, gracias'))?>" class="regular-text"></td></tr>
                <tr><th colspan="2"><hr><h2>Integración</h2></th></tr>
                <tr><th>Sincronizar estado</th><td>
                    <label><input type="checkbox" name="tbwc_complete_sync" <?=checked(get_option('tbwc_complete_sync','1'),'1',false)?>>
                    Al marcar la orden como <strong>Completada</strong> en WooCommerce, cambiar la cita a <strong>Realizada</strong> automáticamente.</label>
                </td></tr>
            </table>
            <p><input type="submit" name="tbwc_save" class="button button-primary" value="Guardar ajustes"></p>
        </form>
    </div>
    <?php
}

// ═══════════════════════════════════════════════════════
// ELEMENTOR WIDGET
// ═══════════════════════════════════════════════════════
function tbwc_register_widget( $widgets_manager ) {
    if ( ! class_exists('\Elementor\Widget_Base') ) return;
    require_once TBWC_PATH . 'elementor/widget-cart-button.php';
    $widgets_manager->register( new TBWC_Cart_Button_Widget() );
}

// ═══════════════════════════════════════════════════════
// DRAWER HTML — inyectado en footer
// ═══════════════════════════════════════════════════════
function tbwc_render_drawer() {
    if ( ! function_exists('WC') ) return;
    include TBWC_PATH . 'templates/mini-cart.php';
    include TBWC_PATH . 'templates/modal-products.php';
}
