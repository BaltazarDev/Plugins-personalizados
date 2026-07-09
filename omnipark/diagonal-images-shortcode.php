<?php
/**
 * Diagonal Images Shortcode for OmniPark
 * Shortcode: [diagonal_images]
 */

// Enqueue CSS
function diagonal_images_enqueue_styles() {
    wp_enqueue_style( 'diagonal-images-style', plugins_url( 'diagonal-images.css', __FILE__ ) );
}
add_action( 'wp_enqueue_scripts', 'diagonal_images_enqueue_styles' );

// Register Shortcode
add_shortcode( 'diagonal_images', 'diagonal_images_shortcode' );

function diagonal_images_shortcode( $atts, $content = null ) {
    $atts = shortcode_atts( array(
        'ids'    => '', // IDs de imagenes separadas por comas
        'images' => '', // URLs de imagenes separadas por comas (alternativa)
        'labels' => '', // Textos separados por |
        'gap'    => '0', // Espacio entre imagenes en px
        'height' => '300', // Altura en px
    ), $atts );

    // Obtener imagenes
    $image_array = array();

    if ( ! empty( $atts['ids'] ) ) {
        // De attachment IDs
        $ids = array_map( 'intval', explode( ',', $atts['ids'] ) );
        foreach ( $ids as $id ) {
            $image_url = wp_get_attachment_image_url( $id, 'full' );
            if ( $image_url ) {
                $image_array[] = $image_url;
            }
        }
    } elseif ( ! empty( $atts['images'] ) ) {
        // De URLs
        $image_array = array_map( 'trim', explode( ',', $atts['images'] ) );
    }

    if ( empty( $image_array ) ) {
        return '<p style="color:red;">Por favor proporciona imagenes (ids o images)</p>';
    }

    $labels = array();
    if ( ! empty( $atts['labels'] ) ) {
        $labels = array_map( 'trim', explode( '|', $atts['labels'] ) );
    }

    $html = '<div class="diagonal-images-container" style="--gap: ' . absint( $atts['gap'] ) . 'px; --height: ' . absint( $atts['height'] ) . 'px;">';

    foreach ( $image_array as $index => $image ) {
        $label_text = isset( $labels[ $index ] ) ? $labels[ $index ] : '';

        $html .= '<div class="diagonal-image-wrapper">';
        $html .= '<div class="diagonal-image-media">';
        $html .= '<img src="' . esc_url( $image ) . '" alt="Imagen" />';
        $html .= '</div>';
        $html .= '<div class="diagonal-label">';
        $html .= '<span class="diagonal-label-icon" aria-hidden="true">+</span>';
        $html .= '<span class="diagonal-label-text">' . esc_html( $label_text ) . '</span>';
        $html .= '</div>';
        $html .= '</div>';
    }

    $html .= '</div>';

    return $html;
}
