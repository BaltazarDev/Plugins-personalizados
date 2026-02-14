<?php
/**
 * Plugin Name: Carrusel de Servicios
 * Plugin URI: 
 * Description: Carrusel dinámico para mostrar posts de servicios
 * Version: 1.0.9
 * Author: BGDEVSOFT
 * License: GPL v2 or later
 * Text Domain: servicios-carrusel
 */

// Evitar acceso directo
defined('ABSPATH') || exit;

// Definir constantes del plugin
define('SC_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('SC_PLUGIN_URL', plugin_dir_url(__FILE__));
define('SC_VERSION', '1.0.9');

// Incluir archivos necesarios
require_once SC_PLUGIN_PATH . 'includes/post-type.php';
require_once SC_PLUGIN_PATH . 'includes/carrusel-shortcode.php';
require_once SC_PLUGIN_PATH . 'includes/elementor-widget.php';

// Registrar estilos y scripts para el frontend
add_action('wp_enqueue_scripts', 'sc_enqueue_scripts');
function sc_enqueue_scripts() {
    // Swiper JS (para el carrusel)
    wp_enqueue_script('swiper-js', 'https://unpkg.com/swiper@8/swiper-bundle.min.js', array(), '8.4.5', true);
    wp_enqueue_style('swiper-css', 'https://unpkg.com/swiper@8/swiper-bundle.min.css', array(), '8.4.5');
    
    // Estilos y scripts personalizados
    wp_enqueue_style('sc-carrusel-style', SC_PLUGIN_URL . 'assets/css/carrusel-style.css', array(), SC_VERSION);
    wp_enqueue_script('sc-carrusel-script', SC_PLUGIN_URL . 'assets/js/carrusel-script.js', array('jquery', 'swiper-js'), SC_VERSION, true);
}

// Registrar estilos y scripts para el editor de Elementor
add_action('elementor/editor/before_enqueue_scripts', 'sc_enqueue_editor_scripts');
function sc_enqueue_editor_scripts() {
    // Swiper JS (para el carrusel en el editor)
    wp_enqueue_script('swiper-js', 'https://unpkg.com/swiper@8/swiper-bundle.min.js', array(), '8.4.5', true);
    wp_enqueue_style('swiper-css', 'https://unpkg.com/swiper@8/swiper-bundle.min.css', array(), '8.4.5');
    
    // Estilos y scripts personalizados
    wp_enqueue_style('sc-carrusel-style', SC_PLUGIN_URL . 'assets/css/carrusel-style.css', array(), SC_VERSION);
    wp_enqueue_script('sc-carrusel-script', SC_PLUGIN_URL . 'assets/js/carrusel-script.js', array('jquery', 'swiper-js'), SC_VERSION, true);
}
