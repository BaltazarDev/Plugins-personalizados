<?php
/**
 * Plugin Name: Roster Scroll Fernader
 * Description: Widget de Elementor con efecto parallax scroll para roster de talentos. Imágenes y nombres configurables directamente desde el editor, con controles de tipografía, colores, tamaños y estilos completos.
 * Version: 1.0.0
 * Author: Baltazar Dev
 * Text Domain: roster-scroll-fernader
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Registrar el widget de Elementor.
 */
function rsf_register_widget( $widgets_manager ) {
    require_once __DIR__ . '/widgets/roster-scroll-fernader-widget.php';
    $widgets_manager->register( new \Roster_Scroll_Fernader_Widget() );
}
add_action( 'elementor/widgets/register', 'rsf_register_widget' );
