<?php
/**
 * Registra el Custom Post Type 'servicios'
 */
add_action('init', 'sc_register_servicios_post_type');
function sc_register_servicios_post_type() {
    $labels = array(
        'name'               => __('Servicios', 'servicios-carrusel'),
        'singular_name'      => __('Servicio', 'servicios-carrusel'),
        'menu_name'          => __('Servicios', 'servicios-carrusel'),
        'name_admin_bar'     => __('Servicio', 'servicios-carrusel'),
        'add_new'            => __('Agregar Nuevo', 'servicios-carrusel'),
        'add_new_item'       => __('Agregar Nuevo Servicio', 'servicios-carrusel'),
        'new_item'           => __('Nuevo Servicio', 'servicios-carrusel'),
        'edit_item'          => __('Editar Servicio', 'servicios-carrusel'),
        'view_item'          => __('Ver Servicio', 'servicios-carrusel'),
        'all_items'          => __('Todos los Servicios', 'servicios-carrusel'),
        'search_items'       => __('Buscar Servicios', 'servicios-carrusel'),
        'not_found'          => __('No se encontraron servicios.', 'servicios-carrusel'),
        'not_found_in_trash' => __('No hay servicios en la papelera.', 'servicios-carrusel')
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'servicio'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 5,
        'menu_icon'          => 'dashicons-admin-generic',
        'supports'           => array('title', 'editor', 'thumbnail', 'excerpt'),
        'show_in_rest'       => true
    );

    register_post_type('servicios', $args);
}

// Crear taxonomía para categorías de servicios
add_action('init', 'sc_register_servicios_taxonomy');
function sc_register_servicios_taxonomy() {
    $labels = array(
        'name'              => __('Categorías de Servicios', 'servicios-carrusel'),
        'singular_name'     => __('Categoría de Servicio', 'servicios-carrusel'),
        'search_items'      => __('Buscar Categorías', 'servicios-carrusel'),
        'all_items'         => __('Todas las Categorías', 'servicios-carrusel'),
        'parent_item'       => __('Categoría Padre', 'servicios-carrusel'),
        'parent_item_colon' => __('Categoría Padre:', 'servicios-carrusel'),
        'edit_item'         => __('Editar Categoría', 'servicios-carrusel'),
        'update_item'       => __('Actualizar Categoría', 'servicios-carrusel'),
        'add_new_item'      => __('Agregar Nueva Categoría', 'servicios-carrusel'),
        'new_item_name'     => __('Nuevo Nombre de Categoría', 'servicios-carrusel'),
        'menu_name'         => __('Categorías', 'servicios-carrusel'),
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'categoria-servicio'),
        'show_in_rest'      => true,
    );

    register_taxonomy('categoria_servicio', array('servicios'), $args);
}