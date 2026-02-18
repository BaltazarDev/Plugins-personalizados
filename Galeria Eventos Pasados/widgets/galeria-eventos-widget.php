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
                    '{{WRAPPER}} .bodoni-title' => 'color: {{VALUE}}',
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
                    '{{WRAPPER}} .divider-line' => 'background-color: {{VALUE}}',
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
                    '{{WRAPPER}} .open-sans-text' => 'color: {{VALUE}}',
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
                    '{{WRAPPER}} #gallery-section' => 'background-color: {{VALUE}}',
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
                $args['order'] = 'DESC'; // Eventos más recientes primero
            }
        }

        $query = new WP_Query($args);
        
        ?>
        <!-- Tailwind CSS -->
        <script src="https://cdn.tailwindcss.com"></script>

        <!-- Google Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Bodoni+Moda:wght@400;500;600;700;800;900&family=Open+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">

        <!-- GSAP para animaciones -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>

        <style>
            .bodoni-title {
                font-family: 'Bodoni Moda', serif;
                letter-spacing: -0.03em;
                line-height: 0.85;
            }

            .open-sans-text {
                font-family: 'Open Sans', sans-serif;
                letter-spacing: 0.05em;
            }

            .parallax-column {
                will-change: transform;
            }

            .img-container {
                position: relative;
                overflow: hidden;
                border-radius: 1rem;
                box-shadow: 0 10px 20px -5px rgb(0 0 0 / 0.1);
            }

            .img-container img {
                width: 100%;
                height: auto;
                display: block;
                transition: transform 0.7s cubic-bezier(0.4, 0, 0.2, 1);
            }

            .img-container:hover img {
                transform: scale(1.05);
            }

            .sticky-text {
                position: sticky;
                top: 120px;
                align-self: flex-start;
            }

            @media (max-width: 1023px) {
                .sticky-text {
                    position: relative;
                    top: 0;
                }
            }
        </style>

        <!-- Sección Principal con Texto Sticky y Galería Parallax -->
        <section id="gallery-section" class="min-h-screen py-20 px-6 lg:px-8 xl:px-12 2xl:px-20">
            <div class="max-w-[1600px] mx-auto">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-8 xl:gap-12 2xl:gap-20">

                    <!-- TEXTO STICKY A LA IZQUIERDA -->
                    <div class="lg:col-span-3 xl:col-span-4 sticky-text">
                        <h1 class="bodoni-title text-6xl lg:text-6xl xl:text-7xl 2xl:text-8xl font-bold mb-8">
                            <?php echo wp_kses_post($settings['titulo']); ?>
                        </h1>

                        <div class="divider-line w-20 h-1 bg-black mb-6"></div>

                        <p class="open-sans-text text-gray-600 text-sm lg:text-base uppercase font-light">
                            <?php echo esc_html($settings['subtitulo']); ?>
                        </p>
                    </div>

                    <!-- GALERÍA DE IMÁGENES CON PARALLAX -->
                    <div class="lg:col-span-9 xl:col-span-8">
                        <div class="grid grid-cols-2 lg:grid-cols-3 gap-4 lg:gap-6">


                            <?php
                            if ($query->have_posts()) {
                                // Agrupar posts en 3 columnas
                                $posts_array = [];
                                while ($query->have_posts()) {
                                    $query->the_post();
                                    $thumbnail_url = get_the_post_thumbnail_url(get_the_ID(), 'large');
                                    if ($thumbnail_url) {
                                        $posts_array[] = [
                                            'url' => $thumbnail_url,
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
                                    if ($col_num == 0) {
                                        $col1[] = $post;
                                    } elseif ($col_num == 1) {
                                        $col2[] = $post;
                                    } else {
                                        $col3[] = $post;
                                    }
                                }

                                // Columna 1
                                if (!empty($col1)) {
                                    ?>
                                    <div id="col-1" class="parallax-column flex flex-col gap-4 lg:gap-6">
                                        <?php foreach ($col1 as $post) { ?>
                                            <div class="img-container">
                                                <img src="<?php echo esc_url($post['url']); ?>" 
                                                     alt="<?php echo esc_attr($post['title']); ?>" 
                                                     loading="lazy">
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <?php
                                }

                                // Columna 2
                                if (!empty($col2)) {
                                    ?>
                                    <div id="col-2" class="parallax-column flex flex-col gap-4 lg:gap-6 mt-0 lg:mt-[-150px]">
                                        <?php foreach ($col2 as $post) { ?>
                                            <div class="img-container">
                                                <img src="<?php echo esc_url($post['url']); ?>" 
                                                     alt="<?php echo esc_attr($post['title']); ?>" 
                                                     loading="lazy">
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <?php
                                }

                                // Columna 3 (solo desktop)
                                if (!empty($col3)) {
                                    ?>
                                    <div id="col-3" class="parallax-column hidden lg:flex flex-col gap-4 lg:gap-6">
                                        <?php foreach ($col3 as $post) { ?>
                                            <div class="img-container">
                                                <img src="<?php echo esc_url($post['url']); ?>" 
                                                     alt="<?php echo esc_attr($post['title']); ?>" 
                                                     loading="lazy">
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <?php
                                }
                            } else {
                                echo '<p class="col-span-full text-center text-gray-500">No se encontraron posts.</p>';
                            }
                            ?>


                        </div>
                    </div>

                </div>
            </div>
        </section>

        <script>
            (function() {
                // Registrar ScrollTrigger
                gsap.registerPlugin(ScrollTrigger);

                // Detectar si es móvil
                const isMobile = window.innerWidth < 768;
                const isTablet = window.innerWidth >= 768 && window.innerWidth < 1024;

                // Configuración específica por dispositivo
                const config = {
                    mobile: {
                        col1Y: -100,
                        col2Start: -50,
                        col2Y: 150,
                        col3Y: -150,
                        scrub: 0.5,
                        start: "top 80%",
                        end: "bottom 20%"
                    },
                    tablet: {
                        col1Y: -150,
                        col2Start: -75,
                        col2Y: 200,
                        col3Y: -200,
                        scrub: 0.8,
                        start: "top 70%",
                        end: "bottom 30%"
                    },
                    desktop: {
                        col1Y: -250,
                        col2Start: -100,
                        col2Y: 300,
                        col3Y: -350,
                        scrub: 1,
                        start: "top bottom",
                        end: "bottom top"
                    }
                };

                // Seleccionar configuración según dispositivo
                const settings = isMobile ? config.mobile : (isTablet ? config.tablet : config.desktop);

                // Animación Columna 1 (Sube suavemente)
                gsap.to("#col-1", {
                    y: settings.col1Y,
                    ease: "none",
                    scrollTrigger: {
                        trigger: "#gallery-section",
                        start: settings.start,
                        end: settings.end,
                        scrub: settings.scrub
                    }
                });

                // Animación Columna 2 (Baja desde arriba)
                gsap.fromTo("#col-2",
                    { y: settings.col2Start },
                    {
                        y: settings.col2Y,
                        ease: "none",
                        scrollTrigger: {
                            trigger: "#gallery-section",
                            start: settings.start,
                            end: settings.end,
                            scrub: settings.scrub * 1.2
                        }
                    }
                );

                // Animación Columna 3 (Sube más rápido - solo desktop)
                if (!isMobile) {
                    gsap.to("#col-3", {
                        y: settings.col3Y,
                        ease: "none",
                        scrollTrigger: {
                            trigger: "#gallery-section",
                            start: settings.start,
                            end: settings.end,
                            scrub: settings.scrub * 1.1
                        }
                    });
                }

                // Refrescar ScrollTrigger al cambiar tamaño de ventana
                window.addEventListener('resize', () => {
                    ScrollTrigger.refresh();
                });
            })();
        </script>
        <?php
    }
}
