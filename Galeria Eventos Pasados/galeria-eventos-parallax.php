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

        // Registrar scripts de GSAP en WordPress (sin encolarlos globalmente).
        // Solo se cargarán en las páginas que contengan el widget,
        // gracias a get_script_depends() en el widget.
        add_action('wp_loaded', [$this, 'register_scripts']);

        // Atributo defer: no bloquear el render en páginas que sí usen el widget
        add_filter('script_loader_tag', [$this, 'add_defer_to_gsap'], 10, 2);
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

    /**
     * REGISTRAR (no encolar) GSAP + ScrollTrigger en WordPress.
     *
     * Con wp_register_script los scripts quedan disponibles en el
     * registro de WordPress, pero NO se insertan en el HTML hasta que
     * algo los solicite explícitamente (wp_enqueue_script) o hasta que
     * Elementor los pida a través de get_script_depends() del widget.
     *
     * Resultado: en páginas sin el widget, GSAP no existe en absoluto
     * → el scroll nativo móvil no se ve afectado.
     */
    public function register_scripts() {
        wp_register_script(
            'gsap',
            'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js',
            [],
            '3.12.2',
            true // en el footer
        );

        wp_register_script(
            'gsap-scroll-trigger',
            'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js',
            ['gsap'],
            '3.12.2',
            true // en el footer
        );
    }

    /**
     * Añadir atributo defer a los scripts de GSAP.
     * "defer" = el script se descarga en paralelo pero se ejecuta
     * DESPUÉS de que el HTML esté parseado → sin bloqueo del hilo principal.
     */
    public function add_defer_to_gsap($tag, $handle) {
        if (in_array($handle, ['gsap', 'gsap-scroll-trigger'], true)) {
            // Reemplazar <script src= por <script defer src=
            return str_replace(' src=', ' defer src=', $tag);
        }
        return $tag;
    }
}

Galeria_Eventos_Parallax::instance();
