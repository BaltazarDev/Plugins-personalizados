<?php
/**
 * Plugin Name: Hide Options
 * Plugin URI: https://baltazarg.xyz
 * Description: Plugin para ocultar opciones del menú de WordPress a todos los roles, incluyendo administrador, con opción para activar/desactivar.
 * Version: 1.0
 * Author: Baltazar Dev
 * Author URI: https://baltazarg.xyz
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

// Evitar acceso directo
if (!defined('ABSPATH')) {
    exit;
}

// Ocultar notificaciones de plugins
add_action('in_admin_header', 'dcms_remove_notices', 100);
function dcms_remove_notices(){
    remove_all_actions( 'user_admin_notices' );
    remove_all_actions( 'admin_notices' );
}

// Agregar menú de opciones
add_action('admin_menu', 'hide_options_menu', 999);
function hide_options_menu() {
    global $menu, $hide_options_available_menus;
    
    // Obtener menús disponibles
    $hide_options_available_menus = array();
    foreach ($menu as $item) {
        if (!empty($item[2]) && !empty($item[0])) {
            $hide_options_available_menus[$item[2]] = $item[0];
        }
    }
    
    // Agregar página de opciones
    add_menu_page(
        'Opciones de Ocultar Menús',
        'Ocultar Menús',
        'manage_options',
        'hide-options',
        'hide_options_page',
        'dashicons-hidden',
        30
    );
    
    // Registrar configuraciones
    hide_options_settings();
    
    // Ocultar menús seleccionados
    $options = get_option('hide_options_menus', array());
    foreach ($options as $menu_slug) {
        if ($menu_slug !== 'hide-options') {
            remove_menu_page($menu_slug);
        }
    }
}

// Página de opciones
function hide_options_page() {
    ?>
    <div class="wrap">
        <h1>Opciones de Ocultar Menús</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('hide_options_group');
            do_settings_sections('hide-options');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

// Registrar configuraciones
function hide_options_settings() {
    register_setting('hide_options_group', 'hide_options_menus');

    add_settings_section(
        'hide_options_section',
        'Seleccionar menús a ocultar',
        'hide_options_section_callback',
        'hide-options'
    );

    global $hide_options_available_menus;
    $menus = $hide_options_available_menus;

    foreach ($menus as $slug => $name) {
        add_settings_field(
            'hide_' . $slug,
            $name,
            'hide_options_checkbox_callback',
            'hide-options',
            'hide_options_section',
            array('slug' => $slug, 'name' => $name)
        );
    }
}

function hide_options_section_callback() {
    echo '<p>Selecciona los menús que deseas ocultar para todos los usuarios, incluyendo administradores.</p>';
}

function hide_options_checkbox_callback($args) {
    $options = get_option('hide_options_menus', array());
    $checked = in_array($args['slug'], $options) ? 'checked' : '';
    echo '<input type="checkbox" name="hide_options_menus[]" value="' . esc_attr($args['slug']) . '" ' . $checked . ' />';
}

// Ocultar menús seleccionados
add_action('admin_menu', 'hide_selected_menus', 999);
function hide_selected_menus() {
    $options = get_option('hide_options_menus', array());
    $plugin_menu_slug = 'hide-options'; // nunca ocultar el propio menú del plugin

    foreach ($options as $menu) {
        if ($menu === $plugin_menu_slug) {
            continue;
        }
        remove_menu_page($menu);
    }
}

?>