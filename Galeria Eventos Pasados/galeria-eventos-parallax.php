<?php
/**
 * Plugin Name: Galería Eventos Pasados
 * Plugin URI: 
 * Description: Widget de Elementor con galería parallax sticky para mostrar eventos pasados. Se integra con el plugin "Carrusel de Eventos" usando el mismo CPT.
 * Version: 1.0.0
 * Author: Baltazar Dev
 * Author URI: https://baltazarg.xyz/plugins
 * Text Domain: galeria-eventos-pasados
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Main Plugin Class
 */
final class Galeria_Eventos_Parallax {

    const VERSION = '1.0.0';
    const MINIMUM_ELEMENTOR_VERSION = '3.0.0';
    const MINIMUM_PHP_VERSION = '7.4';

    private static $_instance = null;

    public static function instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct() {
        add_action('plugins_loaded', [$this, 'init']);
    }

    public function init() {
        // Check if Elementor is installed and activated
        if (!did_action('elementor/loaded')) {
            add_action('admin_notices', [$this, 'admin_notice_missing_elementor']);
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

        // Register Widget
        add_action('elementor/widgets/register', [$this, 'register_widgets']);
    }

    public function admin_notice_missing_elementor() {
        if (isset($_GET['activate'])) unset($_GET['activate']);
        $message = sprintf(
            esc_html__('"%1$s" requiere que "%2$s" esté instalado y activado.', 'galeria-eventos-parallax'),
            '<strong>' . esc_html__('Galería Eventos Parallax', 'galeria-eventos-parallax') . '</strong>',
            '<strong>' . esc_html__('Elementor', 'galeria-eventos-parallax') . '</strong>'
        );
        printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
    }

    public function admin_notice_minimum_elementor_version() {
        if (isset($_GET['activate'])) unset($_GET['activate']);
        $message = sprintf(
            esc_html__('"%1$s" requiere "%2$s" versión %3$s o superior.', 'galeria-eventos-parallax'),
            '<strong>' . esc_html__('Galería Eventos Parallax', 'galeria-eventos-parallax') . '</strong>',
            '<strong>' . esc_html__('Elementor', 'galeria-eventos-parallax') . '</strong>',
            self::MINIMUM_ELEMENTOR_VERSION
        );
        printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
    }

    public function admin_notice_minimum_php_version() {
        if (isset($_GET['activate'])) unset($_GET['activate']);
        $message = sprintf(
            esc_html__('"%1$s" requiere PHP versión %2$s o superior.', 'galeria-eventos-parallax'),
            '<strong>' . esc_html__('Galería Eventos Parallax', 'galeria-eventos-parallax') . '</strong>',
            self::MINIMUM_PHP_VERSION
        );
        printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
    }

    public function register_widgets($widgets_manager) {
        require_once(__DIR__ . '/widgets/galeria-eventos-widget.php');
        $widgets_manager->register(new \Galeria_Eventos_Widget());
    }
}

Galeria_Eventos_Parallax::instance();
