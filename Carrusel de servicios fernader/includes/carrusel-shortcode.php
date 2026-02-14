<?php
/**
 * Shortcode para mostrar el carrusel
 */
add_shortcode('carrusel_servicios', 'sc_carrusel_servicios_shortcode');
function sc_carrusel_servicios_shortcode($atts) {
    // Atributos del shortcode
    $atts = shortcode_atts(array(
        'categoria' => '',
        'posts_per_page' => -1,
        'orderby' => 'date',
        'order' => 'DESC',
        'slides_desktop' => '3.5',
        'slides_tablet' => '2.5',
        'slides_mobile' => '1.5',
        'excerpt_length' => '20',
        'autoplay' => 'no',
        'autoplay_delay' => '3',
    ), $atts, 'carrusel_servicios');
    
    // Argumentos de la consulta
    $args = array(
        'post_type' => 'servicios',
        'posts_per_page' => $atts['posts_per_page'],
        'orderby' => $atts['orderby'],
        'order' => $atts['order'],
        'post_status' => 'publish'
    );
    
    // Si se especifica una categoría
    if (!empty($atts['categoria'])) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'categoria_servicio',
                'field' => 'slug',
                'terms' => $atts['categoria']
            )
        );
    }
    
    $servicios_query = new WP_Query($args);
    
    if (!$servicios_query->have_posts()) {
        return '<p>No hay servicios para mostrar.</p>';
    }
    
    ob_start();
    ?>
    <div class="sc-carrusel-container" 
         data-slides-desktop="<?php echo esc_attr($atts['slides_desktop']); ?>" 
         data-slides-tablet="<?php echo esc_attr($atts['slides_tablet']); ?>" 
         data-slides-mobile="<?php echo esc_attr($atts['slides_mobile']); ?>"
         data-autoplay="<?php echo esc_attr($atts['autoplay']); ?>"
         data-autoplay-delay="<?php echo esc_attr($atts['autoplay_delay'] * 1000); ?>">
        <!-- Custom Nav -->
        <div class="sc-nav-buttons">
            <div class="sc-nav-btn swiper-prev-custom">&lt;</div>
            <div class="sc-nav-btn swiper-next-custom">&gt;</div>
        </div>

        <div class="swiper sc-carrusel">
            <div class="swiper-wrapper">
                <?php while ($servicios_query->have_posts()) : $servicios_query->the_post(); ?>
                    <div class="swiper-slide sc-slide">
                        <div class="sc-card">
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="sc-card-image">
                                    <?php the_post_thumbnail('medium_large', array('class' => 'sc-image')); ?>
                                </div>
                            <?php endif; ?>
                            
                            <div class="sc-card-content">
                                <h3 class="sc-card-title"><?php the_title(); ?></h3>
                                
                                <div class="sc-card-excerpt">
                                    <?php 
                                    $length = intval($atts['excerpt_length']);
                                    
                                    if (has_excerpt()) {
                                        // Si hay extracto manual, lo respetamos
                                        $excerpt = get_the_excerpt();
                                    } else {
                                        // Si no, generamos uno del contenido controlando la longitud
                                        $content = get_the_content();
                                        if ($length > 0) {
                                            $excerpt = wp_trim_words($content, $length, '...');
                                        } else {
                                            // Si es 0, mostramos todo el contenido sin recortar
                                            $excerpt = $content;
                                        }
                                    }
                                    
                                    echo wp_kses_post($excerpt);
                                    ?>
                                </div>
                                
                                <div class="sc-card-footer">
                                    <a href="<?php the_permalink(); ?>" class="sc-card-button">
                                        <?php echo esc_html__('CONSULTAR →', 'servicios-carrusel'); ?>
                                    </a>
                                    <div class="sc-card-line"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>
            
            <!-- Paginación -->
            <div class="swiper-pagination"></div>
        </div>
    </div>
    <?php
    
    return ob_get_clean();
}