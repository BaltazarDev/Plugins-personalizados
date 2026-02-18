<?php
/**
 * Widget de Elementor para el Carrusel de Eventos
 */

// Evitar acceso directo
if (!defined('ABSPATH')) {
    exit;
}

// Registrar el widget
function ec_register_elementor_widget($widgets_manager) {
    
    class EC_Carrusel_Eventos_Widget extends \Elementor\Widget_Base {
        
        public function get_name() {
            return 'ec_carrusel_eventos';
        }
        
        public function get_title() {
            return __('Carrusel de Eventos', 'eventos-carrusel');
        }
        
        public function get_icon() {
            return 'eicon-posts-carousel';
        }
        
        public function get_categories() {
            return ['general'];
        }
        
        protected function register_controls() {
            
            // ========================================
            // SECCIÓN: CONTENIDO
            // ========================================
            
            $this->start_controls_section(
                'content_section',
                [
                    'label' => __('Contenido', 'eventos-carrusel'),
                    'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                ]
            );
            
            // Selector de ubicación
            $ubicaciones = get_terms(array(
                'taxonomy' => 'ubicacion_evento',
                'hide_empty' => false,
            ));
            
            $ubicacion_options = array('' => __('Todas', 'eventos-carrusel'));
            if (!empty($ubicaciones) && !is_wp_error($ubicaciones)) {
                foreach ($ubicaciones as $ubicacion) {
                    $ubicacion_options[$ubicacion->slug] = $ubicacion->name;
                }
            }
            
            $this->add_control(
                'ubicacion',
                [
                    'label' => __('Ubicación', 'eventos-carrusel'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'options' => $ubicacion_options,
                    'default' => '',
                ]
            );
            

            
            $this->add_control(
                'posts_per_page',
                [
                    'label' => __('Límite de posts', 'eventos-carrusel'),
                    'type' => \Elementor\Controls_Manager::NUMBER,
                    'default' => 6,
                    'min' => -1,
                    'max' => 50,
                    'description' => __('Total de eventos a mostrar (-1 para todos)', 'eventos-carrusel'),
                ]
            );
            
            $this->add_control(
                'orderby',
                [
                    'label' => __('Ordenar por', 'eventos-carrusel'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => 'date',
                    'options' => [
                        'date' => __('Fecha', 'eventos-carrusel'),
                        'title' => __('Título', 'eventos-carrusel'),
                        'rand' => __('Aleatorio', 'eventos-carrusel'),
                        'menu_order' => __('Orden personalizado', 'eventos-carrusel'),
                    ],
                ]
            );
            
            $this->add_control(
                'order',
                [
                    'label' => __('Orden', 'eventos-carrusel'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => 'DESC',
                    'options' => [
                        'ASC' => __('Ascendente', 'eventos-carrusel'),
                        'DESC' => __('Descendente', 'eventos-carrusel'),
                    ],
                ]
            );
            
            $this->add_control(
                'mostrar_todos',
                [
                    'label' => __('Mostrar Todos los Eventos', 'eventos-carrusel'),
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                    'label_on' => __('Sí', 'eventos-carrusel'),
                    'label_off' => __('No', 'eventos-carrusel'),
                    'return_value' => 'yes',
                    'default' => '',
                    'description' => __('Activar para mostrar todos los eventos sin filtrar por fecha (incluye eventos pasados).', 'eventos-carrusel'),
                ]
            );
            

            
            $this->end_controls_section();
            
            // ========================================
            // SECCIÓN: ESTILO - IMAGEN
            // ========================================
            
            $this->start_controls_section(
                'style_image_section',
                [
                    'label' => __('Imagen', 'eventos-carrusel'),
                    'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                ]
            );
            
            $this->add_responsive_control(
                'image_width',
                [
                    'label' => __('Ancho de Imagen', 'eventos-carrusel'),
                    'type' => \Elementor\Controls_Manager::SLIDER,
                    'size_units' => ['px', '%', 'vw'],
                    'range' => [
                        'px' => [
                            'min' => 200,
                            'max' => 800,
                            'step' => 10,
                        ],
                        '%' => [
                            'min' => 10,
                            'max' => 100,
                            'step' => 1,
                        ],
                        'vw' => [
                            'min' => 10,
                            'max' => 100,
                            'step' => 1,
                        ],
                    ],
                    'default' => [
                        'unit' => 'px',
                        'size' => 400,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .ec-card' => 'width: {{SIZE}}{{UNIT}}; max-width: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );
            
            $this->add_responsive_control(
                'image_height',
                [
                    'label' => __('Altura de Imagen', 'eventos-carrusel'),
                    'type' => \Elementor\Controls_Manager::SLIDER,
                    'size_units' => ['px', 'vh'],
                    'range' => [
                        'px' => [
                            'min' => 300,
                            'max' => 1000,
                            'step' => 10,
                        ],
                        'vh' => [
                            'min' => 30,
                            'max' => 100,
                            'step' => 1,
                        ],
                    ],
                    'default' => [
                        'unit' => 'px',
                        'size' => 600,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .ec-card' => 'height: {{SIZE}}{{UNIT}}; aspect-ratio: auto;',
                    ],
                ]
            );
            
            $this->end_controls_section();
            
            // ========================================
            // SECCIÓN: ESTILO - UBICACIÓN
            // ========================================
            
            $this->start_controls_section(
                'style_location_section',
                [
                    'label' => __('Ubicación', 'eventos-carrusel'),
                    'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                ]
            );
            
            $this->add_group_control(
                \Elementor\Group_Control_Typography::get_type(),
                [
                    'name' => 'location_typography',
                    'selector' => '{{WRAPPER}} .ec-location-text',
                ]
            );
            
            $this->add_control(
                'location_color',
                [
                    'label' => __('Color de Texto', 'eventos-carrusel'),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .ec-location-text' => 'color: {{VALUE}}',
                    ],
                ]
            );
            
            $this->add_control(
                'pin_color',
                [
                    'label' => __('Color del Pin', 'eventos-carrusel'),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .ec-pin' => 'fill: {{VALUE}}',
                    ],
                ]
            );
            
            $this->end_controls_section();
            
            // ========================================
            // SECCIÓN: ESTILO - TÍTULO
            // ========================================
            
            $this->start_controls_section(
                'style_title_section',
                [
                    'label' => __('Título', 'eventos-carrusel'),
                    'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                ]
            );
            
            $this->add_group_control(
                \Elementor\Group_Control_Typography::get_type(),
                [
                    'name' => 'title_typography',
                    'selector' => '{{WRAPPER}} .ec-title',
                ]
            );
            
            $this->add_control(
                'title_color',
                [
                    'label' => __('Color', 'eventos-carrusel'),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .ec-title' => 'color: {{VALUE}}',
                    ],
                ]
            );
            
            $this->end_controls_section();
            
            // ========================================
            // SECCIÓN: ESTILO - BOTÓN
            // ========================================
            
            $this->start_controls_section(
                'style_button_section',
                [
                    'label' => __('Botón RSVP', 'eventos-carrusel'),
                    'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                ]
            );
            
            $this->add_group_control(
                \Elementor\Group_Control_Typography::get_type(),
                [
                    'name' => 'button_typography',
                    'selector' => '{{WRAPPER}} .ec-rsvp-btn',
                ]
            );
            
            $this->start_controls_tabs('button_style_tabs');
            
            // Tab: Normal
            $this->start_controls_tab(
                'button_normal',
                [
                    'label' => __('Normal', 'eventos-carrusel'),
                ]
            );
            
            $this->add_control(
                'button_text_color',
                [
                    'label' => __('Color de Texto', 'eventos-carrusel'),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .ec-rsvp-btn' => 'color: {{VALUE}}',
                    ],
                ]
            );
            
            $this->add_control(
                'button_bg_color',
                [
                    'label' => __('Color de Fondo', 'eventos-carrusel'),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .ec-rsvp-btn' => 'background-color: {{VALUE}}',
                    ],
                ]
            );
            
            $this->add_control(
                'button_border_color',
                [
                    'label' => __('Color de Borde', 'eventos-carrusel'),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .ec-rsvp-btn' => 'border-color: {{VALUE}}',
                    ],
                ]
            );
            
            $this->end_controls_tab();
            
            // Tab: Hover
            $this->start_controls_tab(
                'button_hover',
                [
                    'label' => __('Hover', 'eventos-carrusel'),
                ]
            );
            
            $this->add_control(
                'button_text_color_hover',
                [
                    'label' => __('Color de Texto', 'eventos-carrusel'),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .ec-rsvp-btn:hover' => 'color: {{VALUE}}',
                    ],
                ]
            );
            
            $this->add_control(
                'button_bg_color_hover',
                [
                    'label' => __('Color de Fondo', 'eventos-carrusel'),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .ec-rsvp-btn:hover' => 'background-color: {{VALUE}}',
                    ],
                ]
            );
            
            $this->add_control(
                'button_border_color_hover',
                [
                    'label' => __('Color de Borde', 'eventos-carrusel'),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .ec-rsvp-btn:hover' => 'border-color: {{VALUE}}',
                    ],
                ]
            );
            
            $this->end_controls_tab();
            
            $this->end_controls_tabs();
            
            $this->add_control(
                'button_border_width',
                [
                    'label' => __('Ancho de Borde', 'eventos-carrusel'),
                    'type' => \Elementor\Controls_Manager::SLIDER,
                    'size_units' => ['px'],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 10,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .ec-rsvp-btn' => 'border-width: {{SIZE}}{{UNIT}}',
                    ],
                    'separator' => 'before',
                ]
            );
            
            $this->add_control(
                'button_border_radius',
                [
                    'label' => __('Radio de Borde', 'eventos-carrusel'),
                    'type' => \Elementor\Controls_Manager::SLIDER,
                    'size_units' => ['px', '%'],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 50,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 50,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .ec-rsvp-btn' => 'border-radius: {{SIZE}}{{UNIT}}',
                    ],
                ]
            );
            
            $this->add_responsive_control(
                'button_padding',
                [
                    'label' => __('Padding', 'eventos-carrusel'),
                    'type' => \Elementor\Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', 'em', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .ec-rsvp-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                    ],
                ]
            );
            
            $this->end_controls_section();
        }
        
        protected function render() {
            $settings = $this->get_settings_for_display();
            
            // Construir el shortcode con los atributos
            $shortcode_atts = array();
            
            if (!empty($settings['ubicacion'])) {
                $shortcode_atts[] = 'ubicacion="' . esc_attr($settings['ubicacion']) . '"';
            }
            
            if ($settings['posts_per_page'] != -1) {
                $shortcode_atts[] = 'posts_per_page="' . intval($settings['posts_per_page']) . '"';
            }
            
            $shortcode_atts[] = 'orderby="' . esc_attr($settings['orderby']) . '"';
            $shortcode_atts[] = 'order="' . esc_attr($settings['order']) . '"';
            
            if ($settings['mostrar_todos'] === 'yes') {
                $shortcode_atts[] = 'mostrar_todos="yes"';
            }
            
            $shortcode = '[carrusel_eventos ' . implode(' ', $shortcode_atts) . ']';
            
            echo do_shortcode($shortcode);
        }
    }
    
    // Register widget
    $widgets_manager->register( new \EC_Carrusel_Eventos_Widget() );
}
add_action( 'elementor/widgets/register', 'ec_register_elementor_widget' );
