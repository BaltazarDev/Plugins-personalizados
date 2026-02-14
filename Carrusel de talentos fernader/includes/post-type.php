<?php
/**
 * Registra el Custom Post Type 'talentos'
 */
add_action('init', 'tc_register_talentos_post_type');
function tc_register_talentos_post_type() {
    $labels = array(
        'name'               => __('Talentos', 'talentos-carrusel'),
        'singular_name'      => __('Talento', 'talentos-carrusel'),
        'menu_name'          => __('Talentos', 'talentos-carrusel'),
        'name_admin_bar'     => __('Talento', 'talentos-carrusel'),
        'add_new'            => __('Agregar Nuevo', 'talentos-carrusel'),
        'add_new_item'       => __('Agregar Nuevo Talento', 'talentos-carrusel'),
        'new_item'           => __('Nuevo Talento', 'talentos-carrusel'),
        'edit_item'          => __('Editar Talento', 'talentos-carrusel'),
        'view_item'          => __('Ver Talento', 'talentos-carrusel'),
        'all_items'          => __('Todos los Talentos', 'talentos-carrusel'),
        'search_items'       => __('Buscar Talentos', 'talentos-carrusel'),
        'not_found'          => __('No se encontraron talentos.', 'talentos-carrusel'),
        'not_found_in_trash' => __('No hay talentos en la papelera.', 'talentos-carrusel')
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'talento'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 5,
        'menu_icon'          => 'dashicons-groups',
        'supports'           => array('title', 'editor', 'thumbnail', 'excerpt'),
        'show_in_rest'       => true
    );

    register_post_type('talentos', $args);
}

// Crear taxonomía para categorías de talentos
add_action('init', 'tc_register_talentos_taxonomy');
function tc_register_talentos_taxonomy() {
    $labels = array(
        'name'              => __('Categorías de Talentos', 'talentos-carrusel'),
        'singular_name'     => __('Categoría de Talento', 'talentos-carrusel'),
        'search_items'      => __('Buscar Categorías', 'talentos-carrusel'),
        'all_items'         => __('Todas las Categorías', 'talentos-carrusel'),
        'parent_item'       => __('Categoría Padre', 'talentos-carrusel'),
        'parent_item_colon' => __('Categoría Padre:', 'talentos-carrusel'),
        'edit_item'         => __('Editar Categoría', 'talentos-carrusel'),
        'update_item'       => __('Actualizar Categoría', 'talentos-carrusel'),
        'add_new_item'      => __('Agregar Nueva Categoría', 'talentos-carrusel'),
        'new_item_name'     => __('Nuevo Nombre de Categoría', 'talentos-carrusel'),
        'menu_name'         => __('Categorías', 'talentos-carrusel'),
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'categoria-talento'),
        'show_in_rest'      => true,
    );

    register_taxonomy('categoria_talento', array('talentos'), $args);
}
