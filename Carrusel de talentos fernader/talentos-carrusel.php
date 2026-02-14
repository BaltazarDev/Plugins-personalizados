<?php
/**
 * Plugin Name: Carrusel de Talentos
 * Plugin URI: 
 * Description: Carrusel dinámico para mostrar posts de talentos y figuras
 * Version: 1.0.9
 * Author: BGDEVSOFT
 * License: GPL v2 or later
 * Text Domain: talentos-carrusel
 */

// Evitar acceso directo
defined('ABSPATH') || exit;

// Definir constantes del plugin
define('TC_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('TC_PLUGIN_URL', plugin_dir_url(__FILE__));
define('TC_VERSION', '1.0.9');

// Incluir archivos necesarios
require_once TC_PLUGIN_PATH . 'includes/post-type.php';
require_once TC_PLUGIN_PATH . 'includes/carrusel-shortcode.php';
require_once TC_PLUGIN_PATH . 'includes/elementor-widget.php';

// Registrar estilos y scripts para el frontend
add_action('wp_enqueue_scripts', 'tc_enqueue_scripts');
function tc_enqueue_scripts() {
    // Swiper JS (para el carrusel) - Usamos el mismo que el otro plugin si ya está cargado, pero WP maneja dependencias.
    // Si ambos plugins están activos, wp_enqueue_script evitará duplicados si el handle es el mismo.
    wp_enqueue_script('swiper-js', 'https://unpkg.com/swiper@8/swiper-bundle.min.js', array(), '8.4.5', true);
    wp_enqueue_style('swiper-css', 'https://unpkg.com/swiper@8/swiper-bundle.min.css', array(), '8.4.5');
    
    // Estilos y scripts personalizados
    wp_enqueue_style('tc-talentos-style', TC_PLUGIN_URL . 'assets/css/talentos-style.css', array(), TC_VERSION);
    wp_enqueue_script('tc-talentos-script', TC_PLUGIN_URL . 'assets/js/talentos-script.js', array('jquery', 'swiper-js'), TC_VERSION, true);
}

// Registrar estilos y scripts para el editor de Elementor
add_action('elementor/editor/before_enqueue_scripts', 'tc_enqueue_editor_scripts');
function tc_enqueue_editor_scripts() {
    // Swiper JS (para el carrusel en el editor)
    wp_enqueue_script('swiper-js', 'https://unpkg.com/swiper@8/swiper-bundle.min.js', array(), '8.4.5', true);
    wp_enqueue_style('swiper-css', 'https://unpkg.com/swiper@8/swiper-bundle.min.css', array(), '8.4.5');
    
    // Estilos y scripts personalizados
    wp_enqueue_style('tc-talentos-style', TC_PLUGIN_URL . 'assets/css/talentos-style.css', array(), TC_VERSION);
    wp_enqueue_script('tc-talentos-script', TC_PLUGIN_URL . 'assets/js/talentos-script.js', array('jquery', 'swiper-js'), TC_VERSION, true);
}
