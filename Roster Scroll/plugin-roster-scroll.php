<?php
/**
 * Plugin Name: Plugin Roster Scroll
 * Description: Widget de Elementor que muestra talentos con efecto parallax scroll dinámico y personalizable.
 * Version: 1.0.0
 * Author: Antigravity
 * Text Domain: plugin-roster-scroll
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Register Custom Post Type: Talento
 */
function prs_register_talento_post_type() {
	$labels = array(
		'name'                  => _x( 'Talentos', 'Post Type General Name', 'plugin-roster-scroll' ),
		'singular_name'         => _x( 'Talento', 'Post Type Singular Name', 'plugin-roster-scroll' ),
		'menu_name'             => __( 'Talentos', 'plugin-roster-scroll' ),
		'name_admin_bar'        => __( 'Talento', 'plugin-roster-scroll' ),
		'archives'              => __( 'Archivo de Talentos', 'plugin-roster-scroll' ),
		'attributes'            => __( 'Atributos de Talento', 'plugin-roster-scroll' ),
		'parent_item_colon'     => __( 'Talento Padre:', 'plugin-roster-scroll' ),
		'all_items'             => __( 'Todos los Talentos', 'plugin-roster-scroll' ),
		'add_new_item'          => __( 'Agregar Nuevo Talento', 'plugin-roster-scroll' ),
		'add_new'               => __( 'Agregar Nuevo', 'plugin-roster-scroll' ),
		'new_item'              => __( 'Nuevo Talento', 'plugin-roster-scroll' ),
		'edit_item'             => __( 'Editar Talento', 'plugin-roster-scroll' ),
		'update_item'           => __( 'Actualizar Talento', 'plugin-roster-scroll' ),
		'view_item'             => __( 'Ver Talento', 'plugin-roster-scroll' ),
		'view_items'            => __( 'Ver Talentos', 'plugin-roster-scroll' ),
		'search_items'          => __( 'Buscar Talento', 'plugin-roster-scroll' ),
		'not_found'             => __( 'No encontrado', 'plugin-roster-scroll' ),
		'not_found_in_trash'    => __( 'No encontrado en papelera', 'plugin-roster-scroll' ),
	);
	
	$args = array(
		'label'                 => __( 'Talento', 'plugin-roster-scroll' ),
		'description'           => __( 'Talentos para mostrar en el roster', 'plugin-roster-scroll' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'thumbnail', 'editor' ),
		'taxonomies'            => array( 'categoria_talento' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'menu_icon'             => 'dashicons-star-filled',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'post',
		'show_in_rest'          => true,
	);
	
	register_post_type( 'talento', $args );
}
add_action( 'init', 'prs_register_talento_post_type', 0 );

/**
 * Register Custom Taxonomy: Categoría Talento
 */
function prs_register_categoria_talento_taxonomy() {
	$labels = array(
		'name'                       => _x( 'Categorías de Talento', 'Taxonomy General Name', 'plugin-roster-scroll' ),
		'singular_name'              => _x( 'Categoría de Talento', 'Taxonomy Singular Name', 'plugin-roster-scroll' ),
		'menu_name'                  => __( 'Categorías', 'plugin-roster-scroll' ),
		'all_items'                  => __( 'Todas las Categorías', 'plugin-roster-scroll' ),
		'parent_item'                => __( 'Categoría Padre', 'plugin-roster-scroll' ),
		'parent_item_colon'          => __( 'Categoría Padre:', 'plugin-roster-scroll' ),
		'new_item_name'              => __( 'Nueva Categoría', 'plugin-roster-scroll' ),
		'add_new_item'               => __( 'Agregar Nueva Categoría', 'plugin-roster-scroll' ),
		'edit_item'                  => __( 'Editar Categoría', 'plugin-roster-scroll' ),
		'update_item'                => __( 'Actualizar Categoría', 'plugin-roster-scroll' ),
		'view_item'                  => __( 'Ver Categoría', 'plugin-roster-scroll' ),
		'separate_items_with_commas' => __( 'Separar categorías con comas', 'plugin-roster-scroll' ),
		'add_or_remove_items'        => __( 'Agregar o remover categorías', 'plugin-roster-scroll' ),
		'choose_from_most_used'      => __( 'Elegir de las más usadas', 'plugin-roster-scroll' ),
		'popular_items'              => __( 'Categorías Populares', 'plugin-roster-scroll' ),
		'search_items'               => __( 'Buscar Categorías', 'plugin-roster-scroll' ),
		'not_found'                  => __( 'No Encontrado', 'plugin-roster-scroll' ),
	);
	
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => false, // Invisible en frontend
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => false,
		'show_tagcloud'              => false,
		'show_in_rest'               => true,
	);
	
	register_taxonomy( 'categoria_talento', array( 'talento' ), $args );
}
add_action( 'init', 'prs_register_categoria_talento_taxonomy', 0 );

/**
 * Create default "talentos" category on plugin activation
 */
function prs_create_default_category() {
	// Check if the category already exists
	$term = term_exists( 'talentos', 'categoria_talento' );
	
	if ( ! $term ) {
		wp_insert_term(
			'Talentos',
			'categoria_talento',
			array(
				'description' => 'Categoría invisible para talentos del roster',
				'slug'        => 'talentos',
			)
		);
	}
}
register_activation_hook( __FILE__, 'prs_create_default_category' );

/**
 * Register Roster Scroll Widget.
 */
function register_roster_scroll_widget( $widgets_manager ) {
	require_once( __DIR__ . '/widgets/roster-scroll-widget.php' );
	$widgets_manager->register( new \Roster_Scroll_Widget() );
}
add_action( 'elementor/widgets/register', 'register_roster_scroll_widget' );

/**
 * Enqueue Google Fonts
 */
function prs_enqueue_fonts() {
	wp_enqueue_style( 
		'google-fonts-inter', 
		'https://fonts.googleapis.com/css2?family=Inter:wght@400;600;900&display=swap', 
		array(), 
		null 
	);
}
add_action( 'wp_enqueue_scripts', 'prs_enqueue_fonts' );
