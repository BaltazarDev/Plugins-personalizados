<?php
/**
 * Registro del Custom Post Type y Taxonomía
 */

// Evitar acceso directo
if (!defined('ABSPATH')) {
    exit;
}

// Registrar Custom Post Type: Eventos
function ec_register_post_type() {
    $labels = array(
        'name' => __('Eventos', 'eventos-carrusel'),
        'singular_name' => __('Evento', 'eventos-carrusel'),
        'menu_name' => __('Eventos', 'eventos-carrusel'),
        'add_new' => __('Agregar Nuevo', 'eventos-carrusel'),
        'add_new_item' => __('Agregar Nuevo Evento', 'eventos-carrusel'),
        'edit_item' => __('Editar Evento', 'eventos-carrusel'),
        'new_item' => __('Nuevo Evento', 'eventos-carrusel'),
        'view_item' => __('Ver Evento', 'eventos-carrusel'),
        'search_items' => __('Buscar Eventos', 'eventos-carrusel'),
        'not_found' => __('No se encontraron eventos', 'eventos-carrusel'),
        'not_found_in_trash' => __('No hay eventos en la papelera', 'eventos-carrusel'),
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'has_archive' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
        'show_in_admin_bar' => true,
        'menu_position' => 5,
        'menu_icon' => 'dashicons-calendar-alt',
        'supports' => array('title', 'editor', 'thumbnail', 'custom-fields'),
        'rewrite' => array('slug' => 'eventos'),
        'capability_type' => 'post',
    );

    register_post_type('eventos', $args);
}
add_action('init', 'ec_register_post_type');

// Registrar Taxonomía: Ubicación (Etiquetas)
function ec_register_taxonomy() {
    $labels = array(
        'name' => __('Ubicaciones', 'eventos-carrusel'),
        'singular_name' => __('Ubicación', 'eventos-carrusel'),
        'search_items' => __('Buscar Ubicaciones', 'eventos-carrusel'),
        'all_items' => __('Todas las Ubicaciones', 'eventos-carrusel'),
        'edit_item' => __('Editar Ubicación', 'eventos-carrusel'),
        'update_item' => __('Actualizar Ubicación', 'eventos-carrusel'),
        'add_new_item' => __('Agregar Nueva Ubicación', 'eventos-carrusel'),
        'new_item_name' => __('Nombre de Nueva Ubicación', 'eventos-carrusel'),
        'menu_name' => __('Ubicaciones', 'eventos-carrusel'),
    );

    $args = array(
        'labels' => $labels,
        'hierarchical' => false, // Como etiquetas (tags)
        'public' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'show_in_nav_menus' => true,
        'show_tagcloud' => true,
        'rewrite' => array('slug' => 'ubicacion-evento'),
    );

    register_taxonomy('ubicacion_evento', array('eventos'), $args);
}
add_action('init', 'ec_register_taxonomy');

// Agregar Metabox para Fecha del Evento
function ec_add_evento_fecha_metabox() {
    add_meta_box(
        'ec_evento_fecha',
        __('Fecha del Evento', 'eventos-carrusel'),
        'ec_evento_fecha_callback',
        'eventos',
        'side',
        'high'
    );
}
add_action('add_meta_boxes', 'ec_add_evento_fecha_metabox');

// Callback del Metabox
function ec_evento_fecha_callback($post) {
    wp_nonce_field('ec_save_evento_fecha', 'ec_evento_fecha_nonce');
    $fecha = get_post_meta($post->ID, '_evento_fecha', true);
    ?>
    <p>
        <label for="evento_fecha"><?php _e('Fecha del evento:', 'eventos-carrusel'); ?></label><br>
        <input type="date" id="evento_fecha" name="evento_fecha" value="<?php echo esc_attr($fecha); ?>" style="width: 100%;">
    </p>
    <p class="description">
        <?php _e('Selecciona la fecha en que se realizará o se realizó el evento.', 'eventos-carrusel'); ?>
    </p>
    <?php
}

// Guardar la Fecha del Evento
function ec_save_evento_fecha($post_id) {
    // Verificar nonce
    if (!isset($_POST['ec_evento_fecha_nonce']) || !wp_verify_nonce($_POST['ec_evento_fecha_nonce'], 'ec_save_evento_fecha')) {
        return;
    }

    // Verificar autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Verificar permisos
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Guardar la fecha
    if (isset($_POST['evento_fecha'])) {
        update_post_meta($post_id, '_evento_fecha', sanitize_text_field($_POST['evento_fecha']));
    }
}
add_action('save_post_eventos', 'ec_save_evento_fecha');

