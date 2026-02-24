<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Elementor Widget - Galería Eventos Parallax
 */
class Galeria_Eventos_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'galeria_eventos_parallax';
    }

    public function get_title() {
        return __('Galería Eventos Parallax', 'galeria-eventos-parallax');
    }

    public function get_icon() {
        return 'eicon-gallery-grid';
    }

    public function get_categories() {
        return ['general'];
    }

    public function get_keywords() {
        return ['galeria', 'eventos', 'parallax', 'sticky', 'fernader'];
    }

    /**
     * get_script_depends() — API oficial de Elementor para dependencias JS.
     *
     * Elementor solo encola estos scripts cuando el widget está presente
     * en la página que se está renderizando. En páginas sin este widget,
     * los scripts NO se cargan → el scroll nativo móvil no se ve afectado.
     */
    public function get_script_depends() {
        return ['gsap-scroll-trigger']; // gsap-scroll-trigger depende de gsap,
                                        // WordPress los cargará ambos en el footer.
    }

    protected function register_controls() {
        
        // Sección de Contenido
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Contenido', 'galeria-eventos-parallax'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'titulo',
            [
                'label' => __('Título', 'galeria-eventos-parallax'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'BEHIND<br>THE<br>AGENCY',
                'placeholder' => __('Escribe el título', 'galeria-eventos-parallax'),
                'description' => __('Usa <br> para saltos de línea', 'galeria-eventos-parallax'),
            ]
        );

        $this->add_control(
            'subtitulo',
            [
                'label' => __('Subtítulo', 'galeria-eventos-parallax'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Conoce los eventos que hemos cubierto',
                'placeholder' => __('Escribe el subtítulo', 'galeria-eventos-parallax'),
            ]
        );

        $this->end_controls_section();

        // Sección de Selección de Posts
        $this->start_controls_section(
            'posts_section',
            [
                'label' => __('Selección de Eventos', 'galeria-eventos-pasados'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'ubicacion',
            [
                'label' => __('Ubicación', 'galeria-eventos-pasados'),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => $this->get_event_ubicaciones(),
                'label_block' => true,
                'description' => __('Selecciona una o más ubicaciones. Deja vacío para mostrar todas.', 'galeria-eventos-pasados'),
            ]
        );

        $this->add_control(
            'numero_posts',
            [
                'label' => __('Número de Eventos', 'galeria-eventos-pasados'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 9,
                'min' => 1,
                'max' => 50,
                'step' => 1,
            ]
        );

        $this->add_control(
            'orderby',
            [
                'label' => __('Ordenar por', 'galeria-eventos-parallax'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'date',
                'options' => [
                    'date' => __('Fecha', 'galeria-eventos-parallax'),
                    'title' => __('Título', 'galeria-eventos-parallax'),
                    'rand' => __('Aleatorio', 'galeria-eventos-parallax'),
                    'menu_order' => __('Orden del menú', 'galeria-eventos-parallax'),
                ],
            ]
        );

        $this->add_control(
            'order',
            [
                'label' => __('Orden', 'galeria-eventos-parallax'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'DESC',
                'options' => [
                    'ASC' => __('Ascendente', 'galeria-eventos-parallax'),
                    'DESC' => __('Descendente', 'galeria-eventos-parallax'),
                ],
            ]
        );

        $this->add_control(
            'mostrar_todos',
            [
                'label' => __('Mostrar Todos los Eventos', 'galeria-eventos-pasados'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Sí', 'galeria-eventos-pasados'),
                'label_off' => __('No', 'galeria-eventos-pasados'),
                'return_value' => 'yes',
                'default' => '',
                'description' => __('Activar para mostrar todos los eventos sin filtrar por fecha (incluye eventos futuros).', 'galeria-eventos-pasados'),
            ]
        );

        $this->end_controls_section();

        // Sección de Estilos
        $this->start_controls_section(
            'style_section',
            [
                'label' => __('Estilos', 'galeria-eventos-parallax'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'titulo_color',
            [
                'label' => __('Color del Título', 'galeria-eventos-parallax'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .gep-bodoni-title' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'linea_color',
            [
                'label' => __('Color de la Línea', 'galeria-eventos-parallax'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .gep-divider-line' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'subtitulo_color',
            [
                'label' => __('Color del Subtítulo', 'galeria-eventos-parallax'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#6B7280',
                'selectors' => [
                    '{{WRAPPER}} .gep-open-sans-text' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'bg_color',
            [
                'label' => __('Color de Fondo', 'galeria-eventos-parallax'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .gep-gallery-section' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_section();
    }

    private function get_event_ubicaciones() {
        $ubicaciones = get_terms([
            'taxonomy' => 'ubicacion_evento',
            'hide_empty' => false
        ]);
        $options = [];
        if (!empty($ubicaciones) && !is_wp_error($ubicaciones)) {
            foreach ($ubicaciones as $ubicacion) {
                $options[$ubicacion->term_id] = $ubicacion->name;
            }
        }
        return $options;
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        
        // ID único por instancia del widget para evitar conflictos si hay múltiples en la página
        $widget_id = 'gep-' . $this->get_id();

        // Query de eventos pasados
        $args = [
            'post_type' => 'eventos',
            'posts_per_page' => $settings['numero_posts'],
            'orderby' => $settings['orderby'],
            'order' => $settings['order'],
            'post_status' => 'publish',
        ];

        // Filtrar por ubicación si se especifica
        if (!empty($settings['ubicacion'])) {
            $args['tax_query'] = [
                [
                    'taxonomy' => 'ubicacion_evento',
                    'field' => 'term_id',
                    'terms' => $settings['ubicacion'],
                ]
            ];
        }

        // Filtrar solo eventos pasados (anteriores a hoy) SI NO está activado "mostrar_todos"
        if ($settings['mostrar_todos'] !== 'yes') {
            $args['meta_query'] = [
                [
                    'key' => '_evento_fecha',
                    'value' => date('Y-m-d'),
                    'compare' => '<',
                    'type' => 'DATE'
                ]
            ];

            // Ordenar por fecha del evento si orderby es 'date'
            if ($settings['orderby'] === 'date') {
                $args['meta_key'] = '_evento_fecha';
                $args['orderby'] = 'meta_value';
                $args['order'] = 'DESC';
            }
        }

        $query = new WP_Query($args);
        ?>

        <?php
        // Solo cargar Google Fonts una vez (evitar duplicados si hay múltiples widgets)
        if (!wp_style_is('gep-google-fonts', 'enqueued')) {
            wp_enqueue_style(
                'gep-google-fonts',
                'https://fonts.googleapis.com/css2?family=Bodoni+Moda:wght@400;700;900&family=Open+Sans:wght@300;400;600&display=swap&display=optional',
                [],
                null
            );
        }
        ?>

        <style>
            /*
             * =====================================================
             * GALERÍA EVENTOS PARALLAX - Estilos optimizados
             * Sin Tailwind CDN — CSS puro para máximo rendimiento
             * =====================================================
             */

            /* ---- Reset de caja para el widget ---- */
            .gep-wrapper *,
            .gep-wrapper *::before,
            .gep-wrapper *::after {
                box-sizing: border-box;
            }

            /* ---- Sección principal ---- */
            .gep-gallery-section {
                min-height: 100vh;
                padding: 5rem 1.5rem;
                background-color: #ffffff;
                overflow: hidden; /* contiene los overflows del parallax */
            }

            .gep-inner {
                max-width: 1600px;
                margin: 0 auto;
            }

            /* ---- Grid principal ---- */
            .gep-grid {
                display: grid;
                grid-template-columns: 1fr;
                gap: 3rem;
            }

            @media (min-width: 1024px) {
                .gep-gallery-section {
                    padding: 5rem 2rem;
                }
                .gep-grid {
                    grid-template-columns: 3fr 9fr;
                    gap: 2rem;
                }
            }

            @media (min-width: 1280px) {
                .gep-gallery-section {
                    padding: 5rem 3rem;
                }
                .gep-grid {
                    grid-template-columns: 4fr 8fr;
                    gap: 3rem;
                }
            }

            @media (min-width: 1536px) {
                .gep-gallery-section {
                    padding: 5rem 5rem;
                }
                .gep-grid {
                    gap: 5rem;
                }
            }

            /* ---- Texto sticky ---- */
            .gep-sticky-text {
                position: relative;
            }

            @media (min-width: 1024px) {
                .gep-sticky-text {
                    position: sticky;
                    top: 120px;
                    align-self: flex-start;
                }
            }

            /* ---- Tipografía ---- */
            .gep-bodoni-title {
                font-family: 'Bodoni Moda', Georgia, serif;
                letter-spacing: -0.03em;
                line-height: 0.85;
                font-weight: 700;
                font-size: clamp(3rem, 5vw, 6rem);
                margin: 0 0 2rem;
                color: #000000;
            }

            .gep-divider-line {
                width: 5rem;
                height: 4px;
                background-color: #000000;
                margin-bottom: 1.5rem;
                border: none;
            }

            .gep-open-sans-text {
                font-family: 'Open Sans', Arial, sans-serif;
                letter-spacing: 0.05em;
                color: #6B7280;
                font-size: 0.875rem;
                text-transform: uppercase;
                font-weight: 300;
                margin: 0;
            }

            @media (min-width: 1024px) {
                .gep-open-sans-text {
                    font-size: 1rem;
                }
            }

            /* ---- Grid de imágenes ---- */
            .gep-images-grid {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 1rem;
            }

            @media (min-width: 1024px) {
                .gep-images-grid {
                    grid-template-columns: repeat(3, 1fr);
                    gap: 1.5rem;
                }
            }

            /* ---- Columnas parallax ---- */
            .gep-parallax-col {
                display: flex;
                flex-direction: column;
                gap: 1rem;
                /*
                 * NOTA DE RENDIMIENTO:
                 * will-change se activa SOLO cuando GSAP lo necesita (via JS)
                 * y se elimina cuando la animación termina. No se declara aquí
                 * de forma estática para evitar el overhead permanente en FF/Safari.
                 */
            }

            @media (min-width: 1024px) {
                .gep-parallax-col {
                    gap: 1.5rem;
                }
            }

            /* Columna 2: offset vertical inicial */
            .gep-parallax-col--2 {
                margin-top: 0;
            }

            @media (min-width: 1024px) {
                .gep-parallax-col--2 {
                    margin-top: -150px;
                }
            }

            /* Columna 3: solo desktop */
            .gep-parallax-col--3 {
                display: none;
            }

            @media (min-width: 1024px) {
                .gep-parallax-col--3 {
                    display: flex;
                }
            }

            /* ---- Contenedor de imagen ---- */
            .gep-img-container {
                position: relative;
                overflow: hidden;
                box-shadow: 0 10px 20px -5px rgba(0, 0, 0, 0.1);
                /*
                 * backface-visibility: hidden fuerza compositing layer en FF/Safari
                 * sin el overhead de will-change permanente
                 */
                -webkit-backface-visibility: hidden;
                backface-visibility: hidden;
                border-radius: 0;
            }

            .gep-img-container img {
                width: 100%;
                height: auto;
                display: block;
                /*
                 * ELIMINADO: transition de transform en hover
                 * Combinarlo con GSAP translate causaba doble compositing en Safari.
                 * El hover ahora usa una clase CSS que GSAP puede ignorar.
                 */
                -webkit-backface-visibility: hidden;
                backface-visibility: hidden;
            }

            /* Hover: solo en dispositivos que soportan hover real (no touch) */
            @media (hover: hover) and (pointer: fine) {
                .gep-img-container img {
                    transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
                    will-change: transform;
                }
                .gep-img-container:hover img {
                    transform: scale(1.05);
                }
            }

            /* ---- Mensaje sin posts ---- */
            .gep-no-posts {
                grid-column: 1 / -1;
                text-align: center;
                color: #6B7280;
                padding: 2rem;
            }
        </style>

        <!-- Sección Principal con Texto Sticky y Galería Parallax -->
        <section id="<?php echo esc_attr($widget_id); ?>" class="gep-gallery-section gep-wrapper">
            <div class="gep-inner">
                <div class="gep-grid">

                    <!-- TEXTO STICKY A LA IZQUIERDA -->
                    <div class="gep-sticky-text">
                        <h2 class="gep-bodoni-title">
                            <?php echo wp_kses_post($settings['titulo']); ?>
                        </h2>

                        <div class="gep-divider-line"></div>

                        <p class="gep-open-sans-text">
                            <?php echo esc_html($settings['subtitulo']); ?>
                        </p>
                    </div>

                    <!-- GALERÍA DE IMÁGENES CON PARALLAX -->
                    <div class="gep-gallery-right">
                        <div class="gep-images-grid">

                            <?php
                            if ($query->have_posts()) {
                                // Agrupar posts en 3 columnas
                                $posts_array = [];
                                while ($query->have_posts()) {
                                    $query->the_post();
                                    $thumbnail_url = get_the_post_thumbnail_url(get_the_ID(), 'large');
                                    if ($thumbnail_url) {
                                        $posts_array[] = [
                                            'url'   => $thumbnail_url,
                                            'title' => get_the_title()
                                        ];
                                    }
                                }
                                wp_reset_postdata();

                                // Dividir en 3 columnas
                                $col1 = [];
                                $col2 = [];
                                $col3 = [];
                                
                                foreach ($posts_array as $index => $post) {
                                    $col_num = $index % 3;
                                    if ($col_num === 0) {
                                        $col1[] = $post;
                                    } elseif ($col_num === 1) {
                                        $col2[] = $post;
                                    } else {
                                        $col3[] = $post;
                                    }
                                }

                                // Columna 1
                                if (!empty($col1)) { ?>
                                    <div id="<?php echo esc_attr($widget_id); ?>-col-1" class="gep-parallax-col gep-parallax-col--1">
                                        <?php foreach ($col1 as $post) { ?>
                                            <div class="gep-img-container">
                                                <img src="<?php echo esc_url($post['url']); ?>"
                                                     alt="<?php echo esc_attr($post['title']); ?>"
                                                     loading="lazy"
                                                     decoding="async">
                                            </div>
                                        <?php } ?>
                                    </div>
                                <?php }

                                // Columna 2
                                if (!empty($col2)) { ?>
                                    <div id="<?php echo esc_attr($widget_id); ?>-col-2" class="gep-parallax-col gep-parallax-col--2">
                                        <?php foreach ($col2 as $post) { ?>
                                            <div class="gep-img-container">
                                                <img src="<?php echo esc_url($post['url']); ?>"
                                                     alt="<?php echo esc_attr($post['title']); ?>"
                                                     loading="lazy"
                                                     decoding="async">
                                            </div>
                                        <?php } ?>
                                    </div>
                                <?php }

                                // Columna 3 (solo desktop)
                                if (!empty($col3)) { ?>
                                    <div id="<?php echo esc_attr($widget_id); ?>-col-3" class="gep-parallax-col gep-parallax-col--3">
                                        <?php foreach ($col3 as $post) { ?>
                                            <div class="gep-img-container">
                                                <img src="<?php echo esc_url($post['url']); ?>"
                                                     alt="<?php echo esc_attr($post['title']); ?>"
                                                     loading="lazy"
                                                     decoding="async">
                                            </div>
                                        <?php } ?>
                                    </div>
                                <?php }
                            } else {
                                echo '<p class="gep-no-posts">No se encontraron eventos.</p>';
                            }
                            ?>

                        </div>
                    </div>

                </div>
            </div>
        </section>

        <script>
        (function() {
            'use strict';

            var WIDGET_ID = '<?php echo esc_js($widget_id); ?>';
            var section   = document.getElementById(WIDGET_ID);
            var col1      = document.getElementById(WIDGET_ID + '-col-1');
            var col2      = document.getElementById(WIDGET_ID + '-col-2');
            var col3      = document.getElementById(WIDGET_ID + '-col-3');

            // Salir si no existen los elementos necesarios
            if (!section || !col1 || !col2) return;

            var parallaxInitialized = false;

            // ─────────────────────────────────────────────────────────────
            // initParallax: registra ScrollTrigger y crea las animaciones.
            // Se llama UNA sola vez, justo cuando la sección entra en el
            // rango del IntersectionObserver (ver abajo).
            // ─────────────────────────────────────────────────────────────
            function initParallax() {
                if (parallaxInitialized) return;
                if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') return;

                parallaxInitialized = true;
                gsap.registerPlugin(ScrollTrigger);

                var w        = window.innerWidth;
                var isMobile = w < 768;
                var isTablet = w >= 768 && w < 1024;

                var cfg = isMobile
                    ? { c1: -60,  c2s: -40, c2: 90,  c3: -80,  scrub: 1.2, start: 'top 85%',   end: 'bottom 15%' }
                    : isTablet
                    ? { c1: -100, c2s: -50, c2: 130, c3: -130, scrub: 1.5, start: 'top 75%',   end: 'bottom 25%' }
                    : { c1: -150, c2s: -80, c2: 200, c3: -200, scrub: 2,   start: 'top bottom', end: 'bottom top' };

                // will-change gestionado de forma más eficiente (vía IntersectionObserver)
                
                // Una sola línea de tiempo y un solo ScrollTrigger para todo el widget
                // Esto es mucho más eficiente que tener 3 Triggers separados.
                var tl = gsap.timeline({
                    scrollTrigger: {
                        trigger: section,
                        start: cfg.start,
                        end: cfg.end,
                        scrub: cfg.scrub
                    }
                });

                tl.to(col1, { y: cfg.c1, ease: 'none' }, 0);
                
                tl.fromTo(col2, { y: cfg.c2s }, { 
                    y: cfg.c2, ease: 'none' 
                }, 0);

                if (!isMobile && col3) {
                    tl.to(col3, { y: cfg.c3, ease: 'none' }, 0);
                }

                // GSAP ya gestiona el refresco en resize de forma automática y optimizada.
            }

            // ─────────────────────────────────────────────────────────────
            // INICIALIZACIÓN LAZY con IntersectionObserver
            //
            // ¿Por qué? ScrollTrigger añade listeners de scroll GLOBALES
            // desde el momento en que se registra. Eso hace que capture los
            // eventos de scroll de toda la página e interfiera con el scroll
            // inercial (momentum) del navegador ANTES de que el usuario
            // llegue a la galería.
            //
            // Solución: esperamos a que la sección esté a 300 px del
            // viewport para inicializar ScrollTrigger. Hasta ese momento,
            // el scroll nativo de la página no se ve afectado en absoluto.
            // ─────────────────────────────────────────────────────────────
            function waitForGSAPAndInit() {
                if (typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined') {
                    initParallax();
                } else {
                    // GSAP carga con defer; puede no estar listo aún.
                    // Polling ligero, máx 10 intentos (~5 s).
                    var attempts = 0;
                    var poll = setInterval(function() {
                        attempts++;
                        if (typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined') {
                            clearInterval(poll);
                            initParallax();
                        } else if (attempts >= 10) {
                            clearInterval(poll);
                        }
                    }, 500);
                }
            }

            if ('IntersectionObserver' in window) {
                // rootMargin: '300px' → empieza a inicializar cuando la
                // sección está a 300px de entrar en la pantalla.
                var observer = new IntersectionObserver(function(entries) {
                    entries.forEach(function(entry) {
                        if (entry.isIntersecting) {
                            observer.disconnect(); // un solo disparo
                            
                            // Activar will-change una sola vez cuando el widget está cerca
                            // Esto prepara la GPU sin causar parones justo cuando empieza el scroll.
                            if (col1) col1.style.willChange = 'transform';
                            if (col2) col2.style.willChange = 'transform';
                            if (col3) col3.style.willChange = 'transform';
                            
                            waitForGSAPAndInit();
                        }
                    });
                }, { rootMargin: '400px 0px' });

                observer.observe(section);
            } else {
                // Fallback para navegadores sin IntersectionObserver (muy raros)
                waitForGSAPAndInit();
            }

        })();
        </script>

        <?php
    }
}
