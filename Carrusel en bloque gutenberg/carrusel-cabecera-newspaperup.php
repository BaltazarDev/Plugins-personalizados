<?php
/**
 * Plugin Name: Carrusel en Cabecera para Newspaperup
 * Plugin URI: https://wordpress.org/
 * Description: Convierte el banner estático del header del tema Newspaperup en una zona de widgets (slider) dinámica.
 * Version: 1.0.0
 * Author: BaltazarDev
 * License: GPL2
 */

// Evitar acceso directo
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// 1. Registrar el área de widgets para el Slider en el Header
function cp_registrar_publicidad_cabecera_slider() {
    register_sidebar( array(
        'name'          => 'Publicidad de Cabecera (Slider - Plugin)',
        'id'            => 'header-ad-slider-plugin',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title sr-only">',
        'after_title'   => '</h3>',
    ) );
}
add_action( 'widgets_init', 'cp_registrar_publicidad_cabecera_slider' );

// 2. Sobrescribir la función pluggable del tema Newspaperup
if ( ! function_exists( 'newspaperup_banner_advertisement' ) ) :
    function newspaperup_banner_advertisement() {
        if ( is_active_sidebar( 'header-ad-slider-plugin' ) ) :
            ?>
            <div class="advertising-banner"> 
                <?php dynamic_sidebar( 'header-ad-slider-plugin' ); ?>
            </div>
            <?php
        else :
            // Fallback original
            if ( '' != newspaperup_get_option( 'banner_ad_image' ) ) {
                $newspaperup_banner_advertisement = newspaperup_get_option( 'banner_ad_image' );
                $newspaperup_banner_advertisement = absint( $newspaperup_banner_advertisement );
                $newspaperup_banner_advertisement = wp_get_attachment_image( $newspaperup_banner_advertisement, 'full' );
                $banner_ad_url = newspaperup_get_option( 'banner_ad_url' );
                $banner_open_on_new_tab = newspaperup_get_option( 'banner_open_on_new_tab' );
                $banner_open_on_new_tab = ( '' != $banner_open_on_new_tab ) ? '_blank' : '';
                ?>
                <div class="advertising-banner"> 
                    <a class="pull-right img-fluid" href="<?php echo esc_url( $banner_ad_url ); ?>" target="<?php echo esc_attr( $banner_open_on_new_tab ); ?>">
                        <?php echo $newspaperup_banner_advertisement; ?>
                    </a>  
                </div>
                <?php
            }
        endif;
    }
endif;
