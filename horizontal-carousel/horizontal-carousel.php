<?php
/**
 * Plugin Name: Horizontal Carousel
 * Plugin URI: https://tu-sitio.com
 * Description: Carrusel horizontal con scroll para Elementor con Custom Post Type "Servicios"
 * Version: 1.0.1
 * Author: Tu Nombre
 * Author URI: https://tu-sitio.com
 * Text Domain: horizontal-carousel
 * Domain Path: /languages
 * Elementor tested up to: 3.20.0
 * Elementor Pro tested up to: 3.20.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Main Horizontal Carousel Class
 */
final class Horizontal_Carousel {

    /**
     * Plugin Version
     */
    const VERSION = '1.0.1';

    /**
     * Minimum Elementor Version
     */
    const MINIMUM_ELEMENTOR_VERSION = '3.0.0';

    /**
     * Minimum PHP Version
     */
    const MINIMUM_PHP_VERSION = '7.4';

    /**
     * Instance
     */
    private static $_instance = null;

    /**
     * Instance
     */
    public static function instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Constructor
     */
    public function __construct() {
        add_action('plugins_loaded', [$this, 'on_plugins_loaded']);
        add_action('init', [$this, 'register_servicios_post_type']);
        add_action('init', [$this, 'register_servicios_taxonomy']);
    }

    /**
     * Register Custom Post Type: Servicios
     */
    public function register_servicios_post_type() {
        $labels = array(
            'name'                  => _x('Servicios', 'Post Type General Name', 'horizontal-carousel'),
            'singular_name'         => _x('Servicio', 'Post Type Singular Name', 'horizontal-carousel'),
            'menu_name'             => __('Servicios', 'horizontal-carousel'),
            'name_admin_bar'        => __('Servicio', 'horizontal-carousel'),
            'archives'              => __('Archivo de Servicios', 'horizontal-carousel'),
            'attributes'            => __('Atributos del Servicio', 'horizontal-carousel'),
            'parent_item_colon'     => __('Servicio Padre:', 'horizontal-carousel'),
            'all_items'             => __('Todos los Servicios', 'horizontal-carousel'),
            'add_new_item'          => __('Agregar Nuevo Servicio', 'horizontal-carousel'),
            'add_new'               => __('Agregar Nuevo', 'horizontal-carousel'),
            'new_item'              => __('Nuevo Servicio', 'horizontal-carousel'),
            'edit_item'             => __('Editar Servicio', 'horizontal-carousel'),
            'update_item'           => __('Actualizar Servicio', 'horizontal-carousel'),
            'view_item'             => __('Ver Servicio', 'horizontal-carousel'),
            'view_items'            => __('Ver Servicios', 'horizontal-carousel'),
            'search_items'          => __('Buscar Servicio', 'horizontal-carousel'),
            'not_found'             => __('No se encontraron servicios', 'horizontal-carousel'),
            'not_found_in_trash'    => __('No se encontraron servicios en la papelera', 'horizontal-carousel'),
            'featured_image'        => __('Imagen Destacada', 'horizontal-carousel'),
            'set_featured_image'    => __('Establecer imagen destacada', 'horizontal-carousel'),
            'remove_featured_image' => __('Quitar imagen destacada', 'horizontal-carousel'),
            'use_featured_image'    => __('Usar como imagen destacada', 'horizontal-carousel'),
            'insert_into_item'      => __('Insertar en servicio', 'horizontal-carousel'),
            'uploaded_to_this_item' => __('Subido a este servicio', 'horizontal-carousel'),
            'items_list'            => __('Lista de servicios', 'horizontal-carousel'),
            'items_list_navigation' => __('Navegación de lista de servicios', 'horizontal-carousel'),
            'filter_items_list'     => __('Filtrar lista de servicios', 'horizontal-carousel'),
        );

        $args = array(
            'label'                 => __('Servicio', 'horizontal-carousel'),
            'description'           => __('Servicios para el carrusel horizontal', 'horizontal-carousel'),
            'labels'                => $labels,
            'supports'              => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
            'taxonomies'            => array('categoria_servicio'),
            'hierarchical'          => false,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_position'         => 5,
            'menu_icon'             => 'dashicons-portfolio',
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => true,
            'exclude_from_search'   => false,
            'publicly_queryable'    => true,
            'capability_type'       => 'post',
            'show_in_rest'          => true,
        );

        register_post_type('servicio', $args);
    }

    /**
     * Register Taxonomy: Categoría de Servicio
     */
    public function register_servicios_taxonomy() {
        $labels = array(
            'name'                       => _x('Categorías de Servicio', 'Taxonomy General Name', 'horizontal-carousel'),
            'singular_name'              => _x('Categoría de Servicio', 'Taxonomy Singular Name', 'horizontal-carousel'),
            'menu_name'                  => __('Categorías', 'horizontal-carousel'),
            'all_items'                  => __('Todas las Categorías', 'horizontal-carousel'),
            'parent_item'                => __('Categoría Padre', 'horizontal-carousel'),
            'parent_item_colon'          => __('Categoría Padre:', 'horizontal-carousel'),
            'new_item_name'              => __('Nombre de Nueva Categoría', 'horizontal-carousel'),
            'add_new_item'               => __('Agregar Nueva Categoría', 'horizontal-carousel'),
            'edit_item'                  => __('Editar Categoría', 'horizontal-carousel'),
            'update_item'                => __('Actualizar Categoría', 'horizontal-carousel'),
            'view_item'                  => __('Ver Categoría', 'horizontal-carousel'),
            'separate_items_with_commas' => __('Separar categorías con comas', 'horizontal-carousel'),
            'add_or_remove_items'        => __('Agregar o quitar categorías', 'horizontal-carousel'),
            'choose_from_most_used'      => __('Elegir de las más usadas', 'horizontal-carousel'),
            'popular_items'              => __('Categorías Populares', 'horizontal-carousel'),
            'search_items'               => __('Buscar Categorías', 'horizontal-carousel'),
            'not_found'                  => __('No se encontraron categorías', 'horizontal-carousel'),
            'no_terms'                   => __('Sin categorías', 'horizontal-carousel'),
            'items_list'                 => __('Lista de categorías', 'horizontal-carousel'),
            'items_list_navigation'      => __('Navegación de lista de categorías', 'horizontal-carousel'),
        );

        $args = array(
            'labels'                     => $labels,
            'hierarchical'               => true,
            'public'                     => true,
            'show_ui'                    => true,
            'show_admin_column'          => true,
            'show_in_nav_menus'          => true,
            'show_tagcloud'              => true,
            'show_in_rest'               => true,
        );

        register_taxonomy('categoria_servicio', array('servicio'), $args);
    }

    /**
     * Load Localization files
     */
    public function i18n() {
        load_plugin_textdomain('horizontal-carousel');
    }

    /**
     * On Plugins Loaded
     */
    public function on_plugins_loaded() {
        // Check if Elementor installed and activated
        if (!did_action('elementor/loaded')) {
            add_action('admin_notices', [$this, 'admin_notice_missing_main_plugin']);
            return;
        }

        // Check for required Elementor version
        if (!version_compare(ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=')) {
            add_action('admin_notices', [$this, 'admin_notice_minimum_elementor_version']);
            return;
        }

        // Check for required PHP version
        if (version_compare(PHP_VERSION, self::MINIMUM_PHP_VERSION, '<')) {
            add_action('admin_notices', [$this, 'admin_notice_minimum_php_version']);
            return;
        }

        // Add Plugin actions
        add_action('elementor/widgets/register', [$this, 'register_widgets']);
        add_action('elementor/frontend/after_enqueue_styles', [$this, 'enqueue_styles']);
        add_action('elementor/frontend/after_register_scripts', [$this, 'enqueue_scripts']);
    }

    /**
     * Admin notice - Missing main plugin
     */
    public function admin_notice_missing_main_plugin() {
        if (isset($_GET['activate'])) unset($_GET['activate']);

        $message = sprintf(
            esc_html__('"%1$s" requiere "%2$s" para funcionar. Por favor instala y activa Elementor.', 'horizontal-carousel'),
            '<strong>' . esc_html__('Horizontal Carousel', 'horizontal-carousel') . '</strong>',
            '<strong>' . esc_html__('Elementor', 'horizontal-carousel') . '</strong>'
        );

        printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
    }

    /**
     * Admin notice - Minimum Elementor version
     */
    public function admin_notice_minimum_elementor_version() {
        if (isset($_GET['activate'])) unset($_GET['activate']);

        $message = sprintf(
            esc_html__('"%1$s" requiere la versión "%2$s" o superior de Elementor.', 'horizontal-carousel'),
            '<strong>' . esc_html__('Horizontal Carousel', 'horizontal-carousel') . '</strong>',
            self::MINIMUM_ELEMENTOR_VERSION
        );

        printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
    }

    /**
     * Admin notice - Minimum PHP version
     */
    public function admin_notice_minimum_php_version() {
        if (isset($_GET['activate'])) unset($_GET['activate']);

        $message = sprintf(
            esc_html__('"%1$s" requiere la versión "%2$s" o superior de PHP.', 'horizontal-carousel'),
            '<strong>' . esc_html__('Horizontal Carousel', 'horizontal-carousel') . '</strong>',
            self::MINIMUM_PHP_VERSION
        );

        printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
    }

    /**
     * Register Widgets
     */
    public function register_widgets($widgets_manager) {
        require_once(__DIR__ . '/widgets/horizontal-carousel-widget.php');
        $widgets_manager->register(new \Horizontal_Carousel_Widget());
    }

    /**
     * Enqueue Styles
     */
    public function enqueue_styles() {
        wp_enqueue_style(
            'horizontal-carousel',
            plugins_url('assets/css/carousel.css', __FILE__),
            [],
            self::VERSION
        );

        // Enqueue Google Fonts
        wp_enqueue_style(
            'horizontal-carousel-fonts',
            'https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap',
            [],
            null
        );
    }

    /**
     * Enqueue Scripts
     */
    public function enqueue_scripts() {
        wp_register_script(
            'horizontal-carousel',
            plugins_url('assets/js/carousel.js', __FILE__),
            [], // No jQuery dependency
            self::VERSION,
            true
        );
    }
}

Horizontal_Carousel::instance();
