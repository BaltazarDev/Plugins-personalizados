<?php
/**
 * Plugin Name: Plugin Eventos Anteriores
 * Description: Muestra una lista de eventos anteriores utilizando un widget de Elementor.
 * Version: 1.0.0
 * Author: Antigravity
 * Text Domain: plugin-eventos-anteriores
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Register Eventos Anteriores Widget.
 *
 * Include widget file and register widget class.
 *
 * @since 1.0.0
 * @param \Elementor\Widgets_Manager $widgets_manager Elementor widgets manager.
 * @return void
 */
function register_past_events_widget( $widgets_manager ) {

	require_once( __DIR__ . '/widgets/past-events-widget.php' );

	$widgets_manager->register( new \Past_Events_Widget() );

}
add_action( 'elementor/widgets/register', 'register_past_events_widget' );

/**
 * Enqueue styles
 */
function pea_enqueue_styles() {
    wp_enqueue_style( 'google-fonts-montserrat', 'https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap', array(), null );
    // Tailwind is large to enqueue for just a widget, so we'll use inline styles or a small custom CSS for the widget
    // For this implementation, I will include a small CSS file or inline styles in the widget render for simplicity 
    // to match the exact Tailwind classes used in the design without requiring the full library.
    // However, since the design is simple, custom CSS is better than full Tailwind CDN in production.
}
add_action( 'wp_enqueue_scripts', 'pea_enqueue_styles' );
