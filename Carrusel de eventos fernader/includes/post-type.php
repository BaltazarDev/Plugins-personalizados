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
        'supports' => array('title', 'editor', 'thumbnail'),
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
