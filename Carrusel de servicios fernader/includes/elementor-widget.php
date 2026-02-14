<?php
/**
 * Widget de Elementor para el carrusel
 */
add_action('elementor/widgets/widgets_registered', 'sc_register_elementor_widget');
function sc_register_elementor_widget() {
    if (!class_exists('\Elementor\Widget_Base')) {
        return;
    }
    
    class SC_Carrusel_Servicios_Widget extends \Elementor\Widget_Base {
        
        public function get_name() {
            return 'sc_carrusel_servicios';
        }
        
        public function get_title() {
            return __('Carrusel de Servicios', 'servicios-carrusel');
        }
        
        public function get_icon() {
            return 'eicon-slider-push';
        }
        
        public function get_categories() {
            return ['general'];
        }
        
        protected function _register_controls() {
            // Sección de contenido
            $this->start_controls_section(
                'content_section',
                [
                    'label' => __('Contenido', 'servicios-carrusel'),
                    'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                ]
            );
            
            $this->add_control(
                'categoria',
                [
                    'label' => __('Categoría', 'servicios-carrusel'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'description' => __('Deja vacío para mostrar todos los servicios', 'servicios-carrusel'),
                    'placeholder' => __('slug-de-la-categoria', 'servicios-carrusel'),
                ]
            );
            
            $this->add_responsive_control(
                'slides_per_view',
                [
                    'label' => __('Columnas', 'servicios-carrusel'),
                    'type' => \Elementor\Controls_Manager::NUMBER,
                    'min' => 1,
                    'max' => 6,
                    'step' => 0.1,
                    'default' => 3.5,
                    'devices' => [ 'desktop', 'tablet', 'mobile' ],
                    'desktop_default' => 3.5,
                    'tablet_default' => 2.5,
                    'mobile_default' => 1.5,
                ]
            );

            $this->add_control(
                'posts_per_page',
                [
                    'label' => __('Límite de posts', 'servicios-carrusel'),
                    'type' => \Elementor\Controls_Manager::NUMBER,
                    'default' => 6,
                    'min' => -1,
                    'max' => 50,
                    'description' => __('Total de servicios a mostrar (-1 para todos)', 'servicios-carrusel'),
                ]
            );

            $this->add_control(
                'excerpt_length',
                [
                    'label' => __('Longitud del extracto (palabras)', 'servicios-carrusel'),
                    'type' => \Elementor\Controls_Manager::NUMBER,
                    'default' => 20,
                    'min' => 0,
                    'max' => 200,
                    'step' => 1,
                    'description' => __('Número de palabras a mostrar. Deja en 0 para mostrar todo el contenido.', 'servicios-carrusel'),
                ]
            );
            
            $this->add_control(
                'orderby',
                [
                    'label' => __('Ordenar por', 'servicios-carrusel'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => 'date',
                    'options' => [
                        'date' => __('Fecha', 'servicios-carrusel'),
                        'title' => __('Título', 'servicios-carrusel'),
                        'rand' => __('Aleatorio', 'servicios-carrusel'),
                        'menu_order' => __('Orden personalizado', 'servicios-carrusel'),
                    ],
                ]
            );
            
            $this->add_control(
                'order',
                [
                    'label' => __('Orden', 'servicios-carrusel'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => 'DESC',
                    'options' => [
                        'ASC' => __('Ascendente', 'servicios-carrusel'),
                        'DESC' => __('Descendente', 'servicios-carrusel'),
                    ],
                ]
            );
            
            $this->add_control(
                'autoplay',
                [
                    'label' => __('Autoplay', 'servicios-carrusel'),
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                    'label_on' => __('Sí', 'servicios-carrusel'),
                    'label_off' => __('No', 'servicios-carrusel'),
                    'return_value' => 'yes',
                    'default' => 'no',
                ]
            );

            $this->add_control(
                'autoplay_delay',
                [
                    'label' => __('Delay (segundos)', 'servicios-carrusel'),
                    'type' => \Elementor\Controls_Manager::NUMBER,
                    'min' => 1,
                    'max' => 10,
                    'step' => 0.5,
                    'default' => 3,
                    'condition' => ['autoplay' => 'yes'],
                ]
            );
            
            $this->end_controls_section();
            
            // Sección de estilo
            $this->start_controls_section(
                'style_section',
                [
                    'label' => __('Estilo', 'servicios-carrusel'),
                    'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                ]
            );
            
            $this->add_control(
                'image_options',
                [
                    'label' => __('Imagen', 'servicios-carrusel'),
                    'type' => \Elementor\Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_responsive_control(
                'image_height',
                [
                    'label' => __('Altura de Imagen', 'servicios-carrusel'),
                    'type' => \Elementor\Controls_Manager::SLIDER,
                    'size_units' => ['px', 'vh'],
                    'range' => [
                        'px' => [
                            'min' => 150,
                            'max' => 600,
                            'step' => 10,
                        ],
                        'vh' => [
                            'min' => 15,
                            'max' => 80,
                            'step' => 1,
                        ],
                    ],
                    'default' => [
                        'unit' => 'px',
                        'size' => '',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .sc-card-image' => 'height: {{SIZE}}{{UNIT}}; aspect-ratio: auto;',
                    ],
                    'description' => __('Deja vacío para usar el ratio 16:9 por defecto', 'servicios-carrusel'),
                ]
            );
            
            $this->add_control(
                'title_options',
                [
                    'label' => __('Título', 'servicios-carrusel'),
                    'type' => \Elementor\Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_group_control(
                \Elementor\Group_Control_Typography::get_type(),
                [
                    'name' => 'title_typography',
                    'selector' => '{{WRAPPER}} .sc-card-title',
                ]
            );

            $this->add_control(
                'title_color',
                [
                    'label' => __('Color del Título', 'servicios-carrusel'),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .sc-card-title' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_control(
                'description_options',
                [
                    'label' => __('Descripción', 'servicios-carrusel'),
                    'type' => \Elementor\Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_group_control(
                \Elementor\Group_Control_Typography::get_type(),
                [
                    'name' => 'description_typography',
                    'selector' => '{{WRAPPER}} .sc-card-excerpt, {{WRAPPER}} .sc-card-excerpt p',
                ]
            );

            $this->add_control(
                'description_color',
                [
                    'label' => __('Color de la Descripción', 'servicios-carrusel'),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .sc-card-excerpt' => 'color: {{VALUE}}',
                        '{{WRAPPER}} .sc-card-excerpt p' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->end_controls_section();
        }
        
        protected function render() {
            $settings = $this->get_settings_for_display();
            
            // Construir el shortcode con los atributos
            $shortcode_atts = array();
            
            if (!empty($settings['categoria'])) {
                $shortcode_atts[] = 'categoria="' . esc_attr($settings['categoria']) . '"';
            }
            
            if ($settings['posts_per_page'] != -1) {
                $shortcode_atts[] = 'posts_per_page="' . intval($settings['posts_per_page']) . '"';
            }
            
            if (isset($settings['excerpt_length'])) {
                $shortcode_atts[] = 'excerpt_length="' . intval($settings['excerpt_length']) . '"';
            }
            
            // Responsive slides
            // Desktop
            $slides_desktop = !empty($settings['slides_per_view']) ? $settings['slides_per_view'] : 3.5;
            $shortcode_atts[] = 'slides_desktop="' . esc_attr($slides_desktop) . '"';

            // Tablet
            $slides_tablet = !empty($settings['slides_per_view_tablet']) ? $settings['slides_per_view_tablet'] : 2.5;
            $shortcode_atts[] = 'slides_tablet="' . esc_attr($slides_tablet) . '"';

            // Mobile
            $slides_mobile = !empty($settings['slides_per_view_mobile']) ? $settings['slides_per_view_mobile'] : 1.5;
            $shortcode_atts[] = 'slides_mobile="' . esc_attr($slides_mobile) . '"';
            
            // Autoplay
            $autoplay = !empty($settings['autoplay']) && $settings['autoplay'] === 'yes' ? 'yes' : 'no';
            $shortcode_atts[] = 'autoplay="' . esc_attr($autoplay) . '"';
            
            if ($autoplay === 'yes') {
                $autoplay_delay = !empty($settings['autoplay_delay']) ? $settings['autoplay_delay'] : 3;
                $shortcode_atts[] = 'autoplay_delay="' . esc_attr($autoplay_delay) . '"';
            }
            
            $shortcode_atts[] = 'orderby="' . esc_attr($settings['orderby']) . '"';
            $shortcode_atts[] = 'order="' . esc_attr($settings['order']) . '"';
            
            $shortcode = '[carrusel_servicios ' . implode(' ', $shortcode_atts) . ']';
            
            echo do_shortcode($shortcode);
        }
    }
    
    // Registrar el widget
    \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new SC_Carrusel_Servicios_Widget());
}