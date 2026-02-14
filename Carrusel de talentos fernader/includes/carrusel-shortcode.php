<?php
/**
 * Shortcode para mostrar el carrusel de talentos
 */
add_shortcode('carrusel_talentos', 'tc_carrusel_talentos_shortcode');
function tc_carrusel_talentos_shortcode($atts) {
    // Atributos del shortcode
    $atts = shortcode_atts(array(
        'categoria' => '',
        'posts_per_page' => -1,
        'orderby' => 'date',
        'order' => 'DESC',
        'slides_desktop' => '3.5',
        'slides_tablet' => '2.5',
        'slides_mobile' => '1.5',
        'autoplay' => 'no',
        'autoplay_delay' => '3',
    ), $atts, 'carrusel_talentos');
    
    // Argumentos de la consulta
    $args = array(
        'post_type' => 'talentos',
        'posts_per_page' => $atts['posts_per_page'],
        'orderby' => $atts['orderby'],
        'order' => $atts['order'],
        'post_status' => 'publish'
    );
    
    // Si se especifica una categoría
    if (!empty($atts['categoria'])) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'categoria_talento',
                'field' => 'slug',
                'terms' => $atts['categoria']
            )
        );
    }
    
    $talentos_query = new WP_Query($args);
    
    if (!$talentos_query->have_posts()) {
        return '<p>No hay talentos para mostrar.</p>';
    }
    
    ob_start();
    ?>
    <div class="tc-carrusel-container" 
         data-slides-desktop="<?php echo esc_attr($atts['slides_desktop']); ?>" 
         data-slides-tablet="<?php echo esc_attr($atts['slides_tablet']); ?>" 
         data-slides-mobile="<?php echo esc_attr($atts['slides_mobile']); ?>"
         data-autoplay="<?php echo esc_attr($atts['autoplay']); ?>"
         data-autoplay-delay="<?php echo esc_attr($atts['autoplay_delay'] * 1000); ?>">
        
        <!-- Custom Nav -->
        <div class="tc-nav-buttons">
            <div class="tc-nav-btn swiper-prev-custom">&lt;</div>
            <div class="tc-nav-btn swiper-next-custom">&gt;</div>
        </div>

        <div class="swiper tc-carrusel">
            <div class="swiper-wrapper">
                <?php while ($talentos_query->have_posts()) : $talentos_query->the_post(); ?>
                    <div class="swiper-slide tc-slide">
                        <div class="tc-card">
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="tc-card-image">
                                    <?php the_post_thumbnail('medium_large', array('class' => 'tc-image')); ?>
                                </div>
                            <?php endif; ?>
                            
                            <div class="tc-card-content">
                                <h3 class="tc-card-title"><?php the_title(); ?></h3>
                                
                                <div class="tc-card-excerpt">
                                    <?php 
                                    // Mostramos el extracto tal cual, pensado para "ROLES" (ej: BIENESTAR | PODCAST)
                                    $excerpt = get_the_excerpt();
                                    if (empty($excerpt)) {
                                        $excerpt = ''; // Si no hay extracto, mejor no mostrar nada automático para no romper diseño
                                    }
                                    echo wp_kses_post($excerpt);
                                    ?>
                                </div>
                                <!-- Sin footer ni botón -->
                            </div>
                        </div>
                    </div>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>
            
        </div>
    </div>
    <?php
    
    return ob_get_clean();
}
