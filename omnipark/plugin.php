<?php
/**
 * Plugin Name: OmniPark Diagonal Images
 * Plugin URI: https://omnipark.es
 * Description: Widget profesional de Elementor para crear imágenes con corte diagonal. Personalizable: textos, colores, tipografías, tamaños.
 * Version: 2.0.6
 * Author: OmniPark Dev
 * Author URI: https://omnipark.es
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: omnipark-diagonal
 * Domain Path: /languages
 * Requires at least: 5.0
 * Requires PHP: 7.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Define plugin constants
define( 'OMNIPARK_DIAGONAL_VERSION', '2.0.6' );
define( 'BI_PLUGIN_VERSION', '2.0.6' ); // Para compatibilidad con preferencias del usuario
define( 'OMNIPARK_DIAGONAL_PATH', plugin_dir_path( __FILE__ ) );
define( 'OMNIPARK_DIAGONAL_URL', plugin_dir_url( __FILE__ ) );
define( 'OMNIPARK_DIAGONAL_FILE', __FILE__ );

/**
 * Enqueue plugin styles and scripts
 */
function omnipark_diagonal_enqueue_assets() {
    // Enqueue CSS
    wp_enqueue_style(
        'omnipark-diagonal-styles',
        OMNIPARK_DIAGONAL_URL . 'diagonal-images.css',
        array(),
        OMNIPARK_DIAGONAL_VERSION
    );
    
    // Enqueue frontend JS
    wp_enqueue_script(
        'omnipark-diagonal-frontend',
        OMNIPARK_DIAGONAL_URL . 'js/frontend.js',
        array( 'jquery' ),
        OMNIPARK_DIAGONAL_VERSION,
        true
    );
}
add_action( 'wp_enqueue_scripts', 'omnipark_diagonal_enqueue_assets' );

/**
 * Load Elementor Widget
 */
function omnipark_diagonal_load_elementor_widget() {
    if ( ! did_action( 'elementor/loaded' ) ) {
        return;
    }

    require_once OMNIPARK_DIAGONAL_PATH . 'includes/elementor-widget.php';
    
    // Hook para registrar el widget
    add_action( 'elementor/widgets/register', 'omnipark_diagonal_register_elementor_widget' );
}
add_action( 'plugins_loaded', 'omnipark_diagonal_load_elementor_widget' );

/**
 * Registrar el widget de Elementor
 */
function omnipark_diagonal_register_elementor_widget( $widgets_manager ) {
    $widgets_manager->register( new \Omnipark_Diagonal_Elementor_Widget() );
}

/**
 * Register the diagonal_images shortcode (backward compatibility)
 */
add_shortcode( 'diagonal_images', 'omnipark_diagonal_images_shortcode' );

/**
 * Diagonal Images Shortcode Handler
 */
function omnipark_diagonal_images_shortcode( $atts, $content = null ) {
    $atts = shortcode_atts( array(
        'ids'       => '',
        'images'    => '',
        'gap'       => '0',
        'height'    => '300',
        'class'     => '',
    ), $atts, 'diagonal_images' );

    $gap = absint( $atts['gap'] );
    $height = absint( $atts['height'] );
    $custom_class = sanitize_html_class( $atts['class'] );

    $image_array = array();
    
    if ( ! empty( $atts['ids'] ) ) {
        $ids = array_map( 'intval', explode( ',', $atts['ids'] ) );
        foreach ( $ids as $id ) {
            $image_url = wp_get_attachment_image_url( $id, 'full' );
            if ( $image_url ) {
                $image_array[] = array(
                    'url' => $image_url,
                    'alt' => get_post_meta( $id, '_wp_attachment_image_alt', true ),
                );
            }
        }
    } elseif ( ! empty( $atts['images'] ) ) {
        $urls = array_map( 'trim', explode( ',', $atts['images'] ) );
        foreach ( $urls as $url ) {
            if ( ! empty( $url ) ) {
                $image_array[] = array(
                    'url' => esc_url( $url ),
                    'alt' => 'Image',
                );
            }
        }
    }

    if ( empty( $image_array ) ) {
        return '';
    }

    $container_class = 'diagonal-images-container';
    if ( ! empty( $custom_class ) ) {
        $container_class .= ' ' . $custom_class;
    }

    $html = '<div class="' . esc_attr( $container_class ) . '" style="--gap:' . esc_attr( $gap ) . 'px;--height:' . esc_attr( $height ) . 'px;">';
    
    foreach ( $image_array as $image ) {
        $html .= '<div class="diagonal-image-wrapper"><img src="' . esc_url( $image['url'] ) . '" alt="' . esc_attr( $image['alt'] ) . '" /></div>';
    }
    
    $html .= '</div>';

    return $html;
}

/**
 * Add plugin action links
 */
function omnipark_diagonal_plugin_links( $links ) {
    $settings_link = '<a href="https://omnipark.es">Documentación</a>';
    array_unshift( $links, $settings_link );
    return $links;
}
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'omnipark_diagonal_plugin_links' );

/**
 * Plugin activation hook
 */
function omnipark_diagonal_activate() {
    // Nothing to do on activation
}
register_activation_hook( __FILE__, 'omnipark_diagonal_activate' );

/**
 * Plugin deactivation hook
 */
function omnipark_diagonal_deactivate() {
    // Nothing to do on deactivation
}
register_deactivation_hook( __FILE__, 'omnipark_diagonal_deactivate' );
