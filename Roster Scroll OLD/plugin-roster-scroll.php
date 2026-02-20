<?php
/**
 * Plugin Name: Plugin Roster Scroll
 * Description: Widget de Elementor con efecto parallax scroll. Gestiona imágenes, nombres, tipografía, colores y estilos directamente desde el editor de Elementor.
 * Version: 2.0.0
 * Author: Antigravity
 * Text Domain: plugin-roster-scroll
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Register the Elementor Widget.
 */
function prs_register_widget( $widgets_manager ) {
    require_once __DIR__ . '/widgets/roster-scroll-widget.php';
    $widgets_manager->register( new \Roster_Scroll_Widget() );
}
add_action( 'elementor/widgets/register', 'prs_register_widget' );

/**
 * Enqueue Google Fonts (Inter as fallback; user can override via Elementor typography controls).
 */
function prs_enqueue_fonts() {
    wp_enqueue_style(
        'prs-google-fonts',
        'https://fonts.googleapis.com/css2?family=Inter:wght@400;600;900&display=swap',
        [],
        null
    );
}
add_action( 'wp_enqueue_scripts', 'prs_enqueue_fonts' );
