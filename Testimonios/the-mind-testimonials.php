<?php
/**
 * Plugin Name: The Mind Testimonials
 * Description: Custom Elementor widget for testimonials with a specific design and submission form integration.
 * Version: 1.0.0
 * Author: The Mind
 * Text Domain: the-mind-testimonials
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Main Plugin Class
 */
final class The_Mind_Testimonials {

	/**
	 * Plugin Version
	 */
	const VERSION = '1.0.0';

	/**
	 * Minimum Elementor Version
	 */
	const MINIMUM_ELEMENTOR_VERSION = '3.0.0';

	/**
	 * Minimum PHP Version
	 */
	const MINIMUM_PHP_VERSION = '7.3';

	/**
	 * Instance
	 */
	private static $_instance = null;

	/**
	 * Instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'init', [ $this, 'i18n' ] );
		add_action( 'plugins_loaded', [ $this, 'init' ] );
	}

	/**
	 * Load Textdomain
	 */
	public function i18n() {
		load_plugin_textdomain( 'the-mind-testimonials' );
	}

	/**
	 * Initialize the plugin
	 */
	public function init() {
		// Check if Elementor is installed and active
		if ( ! did_action( 'elementor/loaded' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_missing_main_plugin' ] );
			return;
		}

		// Check for required Elementor version
		if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_minimum_elementor_version' ] );
			return;
		}

		// Check for required PHP version
		if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_minimum_php_version' ] );
			return;
		}

		// Register Widget
		add_action( 'elementor/widgets/register', [ $this, 'register_widgets' ] );
        
        // Register Scripts and Styles
        add_action( 'elementor/frontend/after_enqueue_styles', [ $this, 'register_styles' ] );
	    add_action( 'elementor/frontend/after_enqueue_scripts', [ $this, 'register_scripts' ] );

        // Include Form Shortcode
        require_once( __DIR__ . '/includes/shortcodes/form-shortcode.php' );

	}

	/**
	 * Admin Notice: Missing Main Plugin
	 */
	public function admin_notice_missing_main_plugin() {
		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );
		$message = sprintf(
			esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'the-mind-testimonials' ),
			'<strong>' . esc_html__( 'The Mind Testimonials', 'the-mind-testimonials' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'the-mind-testimonials' ) . '</strong>'
		);
		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
	}

	/**
	 * Admin Notice: Minimum Elementor Version
	 */
	public function admin_notice_minimum_elementor_version() {
		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );
		$message = sprintf(
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'the-mind-testimonials' ),
			'<strong>' . esc_html__( 'The Mind Testimonials', 'the-mind-testimonials' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'the-mind-testimonials' ) . '</strong>',
			self::MINIMUM_ELEMENTOR_VERSION
		);
		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
	}

	/**
	 * Admin Notice: Minimum PHP Version
	 */
	public function admin_notice_minimum_php_version() {
		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );
		$message = sprintf(
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'the-mind-testimonials' ),
			'<strong>' . esc_html__( 'The Mind Testimonials', 'the-mind-testimonials' ) . '</strong>',
			'<strong>' . esc_html__( 'PHP', 'the-mind-testimonials' ) . '</strong>',
			self::MINIMUM_PHP_VERSION
		);
		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
	}

	/**
	 * Register Widgets
	 */
	public function register_widgets( $widgets_manager ) {
		require_once( __DIR__ . '/includes/widgets/testimonials-widget.php' );
		$widgets_manager->register( new \The_Mind_Testimonials_Widget() );
	}

    /**
     * Register Styles
     */
    public function register_styles() {
        wp_register_style( 'the-mind-testimonials-style', plugins_url( 'assets/css/style.css', __FILE__ ) );
        wp_enqueue_style( 'the-mind-testimonials-style' );
    }

    /**
     * Register Scripts
     */
    public function register_scripts() {
        // Enqueue Swiper (built-in to Elementor)
        // Note: Elementor 3.1+ loads swiper only when needed, usually by detecting 'swiper' dependency or via widgets.
        // We will add it as dependency in the widget script if needed, or rely on Elementor's loading.
        // But for custom script:
        wp_register_script( 'the-mind-testimonials-script', plugins_url( 'assets/js/script.js', __FILE__ ), [ 'jquery', 'elementor-frontend' ], '1.0.0', true );
        wp_enqueue_script( 'the-mind-testimonials-script' );
    }

}

The_Mind_Testimonials::instance();
