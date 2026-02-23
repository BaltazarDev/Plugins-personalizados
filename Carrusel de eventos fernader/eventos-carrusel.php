<?php
/**
 * Plugin Name: Carrusel de Eventos
 * Plugin URI: 
 * Description: Plugin para mostrar eventos en un carrusel con Swiper y Elementor
 * Version: 1.0.6
 * Author: Baltazar Dev
 * Text Domain: eventos-carrusel
 */

// Evitar acceso directo
if (!defined('ABSPATH')) {
    exit;
}

// Definir constantes
define('EC_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('EC_PLUGIN_URL', plugin_dir_url(__FILE__));
define('EC_VERSION', '1.0.6');

// Incluir archivos necesarios
require_once EC_PLUGIN_PATH . 'includes/post-type.php';
require_once EC_PLUGIN_PATH . 'includes/carrusel-shortcode.php';
require_once EC_PLUGIN_PATH . 'includes/elementor-widget.php';

// Enqueue scripts y estilos
function ec_enqueue_scripts() {
    // Swiper CSS
    wp_enqueue_style('swiper-css', 'https://cdn.jsdelivr.net/npm/swiper@8.4.5/swiper-bundle.min.css', array(), '8.4.5');
    
    // Plugin CSS
    wp_enqueue_style('eventos-carrusel-style', EC_PLUGIN_URL . 'assets/css/eventos-style.css', array(), EC_VERSION);
    
    // Swiper JS
    wp_enqueue_script('swiper-js', 'https://cdn.jsdelivr.net/npm/swiper@8.4.5/swiper-bundle.min.js', array(), '8.4.5', true);
    
    // Plugin JS
    wp_enqueue_script('eventos-carrusel-script', EC_PLUGIN_URL . 'assets/js/eventos-script.js', array('jquery', 'swiper-js'), EC_VERSION, true);
}
add_action('wp_enqueue_scripts', 'ec_enqueue_scripts');

// Enqueue scripts para el editor de Elementor
function ec_enqueue_editor_scripts() {
    // Swiper CSS
    wp_enqueue_style('swiper-css', 'https://cdn.jsdelivr.net/npm/swiper@8.4.5/swiper-bundle.min.css', array(), '8.4.5');
    
    // Plugin CSS
    wp_enqueue_style('eventos-carrusel-style', EC_PLUGIN_URL . 'assets/css/eventos-style.css', array(), EC_VERSION);
    
    // Swiper JS
    wp_enqueue_script('swiper-js', 'https://cdn.jsdelivr.net/npm/swiper@8.4.5/swiper-bundle.min.js', array(), '8.4.5', true);
    
    // Plugin JS
    wp_enqueue_script('eventos-carrusel-script', EC_PLUGIN_URL . 'assets/js/eventos-script.js', array('jquery', 'swiper-js'), EC_VERSION, true);
}
add_action('elementor/editor/before_enqueue_scripts', 'ec_enqueue_editor_scripts');
