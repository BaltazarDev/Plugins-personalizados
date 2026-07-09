<?php
/**
 * Snippet Puro - Copiar en functions.php o usar Code Snippets
 * 
 * OPCIÓN: Copiar SOLO este contenido en Code Snippets (plugin)
 * o agregar a functions.php de tu tema
 */

// Enqueue CSS
function omnipark_diagonal_enqueue() {
    wp_register_style( 'omnipark-diagonal', false );
    wp_enqueue_style( 'omnipark-diagonal' );
    
    $css = "
    .diagonal-images-container {
        display: flex;
        gap: var(--gap, 0px);
        overflow: hidden;
        align-items: center;
        justify-content: center;
        flex-wrap: wrap;
    }
    
    .diagonal-image-wrapper {
        height: var(--height, 300px);
        aspect-ratio: 1 / 1;
        overflow: hidden;
        position: relative;
        flex: 1;
        min-width: 200px;
        max-width: 300px;
        clip-path: polygon(15% 0%, 100% 0%, 85% 100%, 0% 100%);
    }
    
    .diagonal-image-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }
    
    @media (max-width: 768px) {
        .diagonal-image-wrapper {
            min-width: 150px;
            max-width: 200px;
            height: 200px;
        }
    }
    
    @media (max-width: 480px) {
        .diagonal-images-container {
            flex-direction: column;
        }
        .diagonal-image-wrapper {
            min-width: 100%;
            max-width: 100%;
            height: 250px;
        }
    }
    ";
    
    wp_add_inline_style( 'omnipark-diagonal', $css );
}
add_action( 'wp_enqueue_scripts', 'omnipark_diagonal_enqueue' );

// Shortcode
add_shortcode( 'diagonal_images', function( $atts ) {
    $atts = shortcode_atts( array(
        'ids'    => '',
        'images' => '',
        'gap'    => '0',
        'height' => '300',
    ), $atts );

    $image_array = array();
    
    if ( ! empty( $atts['ids'] ) ) {
        $ids = array_map( 'intval', explode( ',', $atts['ids'] ) );
        foreach ( $ids as $id ) {
            if ( $url = wp_get_attachment_image_url( $id, 'full' ) ) {
                $image_array[] = $url;
            }
        }
    } elseif ( ! empty( $atts['images'] ) ) {
        $image_array = array_map( 'trim', explode( ',', $atts['images'] ) );
    }

    if ( empty( $image_array ) ) {
        return '';
    }

    $html = '<div class="diagonal-images-container" style="--gap:' . absint( $atts['gap'] ) . 'px;--height:' . absint( $atts['height'] ) . 'px;">';
    foreach ( $image_array as $image ) {
        $html .= '<div class="diagonal-image-wrapper"><img src="' . esc_url( $image ) . '" alt=""/></div>';
    }
    $html .= '</div>';

    return $html;
});
