<?php
/**
 * Shortcode para mostrar el carrusel de eventos
 */
add_shortcode('carrusel_eventos', 'ec_carrusel_eventos_shortcode');
function ec_carrusel_eventos_shortcode($atts) {
    // Atributos del shortcode
    $atts = shortcode_atts(array(
        'ubicacion' => '',
        'posts_per_page' => 6,
        'orderby' => 'date',
        'order' => 'DESC',
        'mostrar_todos' => '',
        'button_text' => 'RSVP',
        'link_type' => 'custom',
        'link_attrs' => '',
    ), $atts, 'carrusel_eventos');
    
    // Argumentos de la consulta
    $args = array(
        'post_type' => 'eventos',
        'posts_per_page' => intval($atts['posts_per_page']),
        'orderby' => $atts['orderby'],
        'order' => $atts['order'],
    );
    
    // Filtrar por ubicación si se especifica
    if (!empty($atts['ubicacion'])) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'ubicacion_evento',
                'field' => 'slug',
                'terms' => $atts['ubicacion'],
            ),
        );
    }
    
    // Filtrar solo eventos futuros (incluyendo hoy) SI NO está activado "mostrar_todos"
    if ($atts['mostrar_todos'] !== 'yes') {
        $args['meta_query'] = array(
            array(
                'key' => '_evento_fecha',
                'value' => date('Y-m-d'), // Fecha de hoy
                'compare' => '>=',
                'type' => 'DATE'
            )
        );
        
        // Ordenar por fecha del evento si orderby es 'date'
        if ($atts['orderby'] === 'date') {
            $args['meta_key'] = '_evento_fecha';
            $args['orderby'] = 'meta_value';
            $args['order'] = 'ASC'; // Eventos más próximos primero
        }
    }
    
    // Ejecutar la consulta
    $eventos_query = new WP_Query($args);
    
    // Si no hay eventos, retornar vacío
    if (!$eventos_query->have_posts()) {
        return '<p>' . __('No se encontraron eventos.', 'eventos-carrusel') . '</p>';
    }
    
    // Iniciar el buffer de salida
    ob_start();
    ?>
    
    <div class="ec-carrusel-container">
        
        <!-- Custom Nav -->
        <div class="ec-nav-buttons">
            <div class="ec-nav-btn swiper-prev-custom">&lt;</div>
            <div class="ec-nav-btn swiper-next-custom">&gt;</div>
        </div>
        
        <!-- Scroll Container -->
        <div class="ec-scroll-container">
            
            <?php while ($eventos_query->have_posts()) : $eventos_query->the_post(); ?>
                
                <div class="ec-card">
                    
                    <!-- Background Image -->
                    <?php if (has_post_thumbnail()) : ?>
                        <?php the_post_thumbnail('large', array('class' => 'ec-bg-image')); ?>
                    <?php else : ?>
                        <img src="<?php echo EC_PLUGIN_URL; ?>assets/images/placeholder.jpg" alt="<?php the_title_attribute(); ?>" class="ec-bg-image">
                    <?php endif; ?>
                    
                    <!-- Overlay -->
                    <div class="ec-overlay"></div>
                    
                    <!-- Content -->
                    <div class="ec-content">
                        <div class="ec-content-inner">
                            <!-- Location -->
                            <?php
                            $ubicaciones = get_the_terms(get_the_ID(), 'ubicacion_evento');
                            if ($ubicaciones && !is_wp_error($ubicaciones)) :
                                $ubicacion_name = $ubicaciones[0]->name;
                            ?>
                                <div class="ec-location">
                                    <svg class="ec-pin" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                        <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                                    </svg>
                                    <span class="ec-location-text"><?php echo esc_html(strtoupper($ubicacion_name)); ?></span>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Title -->
                            <h3 class="ec-title"><?php echo esc_html(strtoupper(get_the_title())); ?></h3>
                        </div>
                        
                        <!-- RSVP Button -->
                        <div class="ec-rsvp-container">
                            <?php
                            $link_attributes = '';
                            if ($atts['link_type'] === 'permalink') {
                                $link_attributes = 'href="' . esc_url(get_permalink()) . '"';
                            } else {
                                if (!empty($atts['link_attrs'])) {
                                    $link_attributes = base64_decode($atts['link_attrs']);
                                } else {
                                    $link_attributes = 'href="#"';
                                }
                            }
                            ?>
                            <a class="ec-rsvp-btn" <?php echo $link_attributes; ?>>
                                <?php echo esc_html($atts['button_text']); ?>
                            </a>
                        </div>
                    </div>
                    
                </div>
                
            <?php endwhile; ?>
            
        </div>
    </div>
    
    <?php
    wp_reset_postdata();
    
    return ob_get_clean();
}
