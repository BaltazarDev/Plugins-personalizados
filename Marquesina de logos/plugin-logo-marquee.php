<?php
/**
 * Plugin Name: Plugin Logo Marquee
 * Description: Widget de Elementor que muestra logos en una marquesina infinita personalizable.
 * Version: 1.0.0
 * Author: Antigravity
 * Text Domain: plugin-logo-marquee
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Register Logo Marquee Widget.
 */
function register_logo_marquee_widget($widgets_manager)
{
    require_once(__DIR__ . '/widgets/logo-marquee-widget.php');
    $widgets_manager->register(new \Logo_Marquee_Widget());
}
add_action('elementor/widgets/register', 'register_logo_marquee_widget');
