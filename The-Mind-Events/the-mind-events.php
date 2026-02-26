<?php
/**
 * Plugin Name: The Mind Events Slider
 * Description: Custom Event Slider with CPT integration and Elementor widget.
 * Version: 1.0.1
 * Author: The Mind
 * Text Domain: the-mind-events
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

final class The_Mind_Events {

	const VERSION = '1.0.1';
	const MINIMUM_ELEMENTOR_VERSION = '3.0.0';
	const MINIMUM_PHP_VERSION = '7.3';

	private static $_instance = null;

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function __construct() {
		add_action( 'init', [ $this, 'i18n' ] );
		add_action( 'plugins_loaded', [ $this, 'init' ] );
	}

	public function i18n() {
		load_plugin_textdomain( 'the-mind-events' );
	}

	public function init() {
		if ( ! did_action( 'elementor/loaded' ) ) {
			return;
		}

		// Register Custom Post Type
		require_once( __DIR__ . '/includes/cpt-events.php' );
		
		add_action( 'elementor/widgets/register', [ $this, 'register_widgets' ] );
        add_action( 'elementor/frontend/after_enqueue_styles', [ $this, 'register_styles' ] );
	    add_action( 'elementor/frontend/after_enqueue_scripts', [ $this, 'register_scripts' ] );
	}

	public function register_widgets( $widgets_manager ) {
		require_once( __DIR__ . '/includes/widgets/events-widget.php' );
		$widgets_manager->register( new \The_Mind_Events_Widget() );
	}

    public function register_styles() {
        wp_enqueue_style( 'the-mind-events-style', plugins_url( 'assets/css/style.css', __FILE__ ), [], self::VERSION );
    }

    public function register_scripts() {
        wp_enqueue_script( 'the-mind-events-script', plugins_url( 'assets/js/script.js', __FILE__ ), [ 'jquery', 'elementor-frontend' ], self::VERSION, true );
    }
}

The_Mind_Events::instance();
