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
            
            $this->add_control(
                'hr_button',
                [
                    'type' => \Elementor\Controls_Manager::DIVIDER,
                ]
            );

            $this->add_control(
                'button_text',
                [
                    'label' => __('Texto del Botón', 'eventos-carrusel'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'default' => __('RSVP', 'eventos-carrusel'),
                    'placeholder' => __('RSVP', 'eventos-carrusel'),
                ]
            );

            $this->add_control(
                'link_type',
                [
                    'label' => __('Enlace del Botón', 'eventos-carrusel'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => 'custom',
                    'options' => [
                        'permalink' => __('Detalle del Evento', 'eventos-carrusel'),
                        'custom' => __('Personalizado / Popup', 'eventos-carrusel'),
                    ],
                ]
            );

            $this->add_control(
                'button_link',
                [
                    'label' => __('Enlace', 'eventos-carrusel'),
                    'type' => \Elementor\Controls_Manager::URL,
                    'placeholder' => __('https://tursitio.com', 'eventos-carrusel'),
                    'show_external' => true,
                    'default' => [
                        'url' => '#',
                        'is_external' => false,
                        'nofollow' => false,
                    ],
                    'condition' => [
                        'link_type' => 'custom',
                    ],
                    'dynamic' => [
                        'active' => true,
                    ],
                ]
            );
            
            $this->end_controls_section();

            // ========================================
            // SECCIÓN: SLIDES POR VISTA
            // ========================================

            $this->start_controls_section(
                'slides_per_view_section',
                [
                    'label' => __('Slides por Vista', 'eventos-carrusel'),
                    'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
                ]
            );

            $this->add_control(
                'slides_desktop',
                [
                    'label'   => __('Slides en Desktop (≥1024px)', 'eventos-carrusel'),
                    'type'    => \Elementor\Controls_Manager::NUMBER,
                    'default' => 3,
                    'min'     => 1,
                    'max'     => 6,
                    'step'    => 1,
                ]
            );

            $this->add_control(
                'slides_tablet',
                [
                    'label'   => __('Slides en Tablet (≥768px)', 'eventos-carrusel'),
                    'type'    => \Elementor\Controls_Manager::NUMBER,
                    'default' => 2,
                    'min'     => 1,
                    'max'     => 4,
                    'step'    => 1,
                ]
            );

            $this->add_control(
                'slides_mobile',
                [
                    'label'   => __('Slides en Móvil (<768px)', 'eventos-carrusel'),
                    'type'    => \Elementor\Controls_Manager::NUMBER,
                    'default' => 1,
                    'min'     => 1,
                    'max'     => 3,
                    'step'    => 1,
                ]
            );

            $this->add_control(
                'space_between',
                [
                    'label'   => __('Espacio entre Slides (px)', 'eventos-carrusel'),
                    'type'    => \Elementor\Controls_Manager::NUMBER,
                    'default' => 24,
                    'min'     => 0,
                    'max'     => 80,
                    'step'    => 2,
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

            $this->add_responsive_control(
                'title_font_size',
                [
                    'label'          => __('Tamaño de Texto', 'eventos-carrusel'),
                    'type'           => \Elementor\Controls_Manager::SLIDER,
                    'size_units'     => [ 'px', 'em', 'rem', 'vw' ],
                    'range'          => [
                        'px'  => [ 'min' => 12, 'max' => 120, 'step' => 1 ],
                        'em'  => [ 'min' => 0.5, 'max' => 8,  'step' => 0.1 ],
                        'rem' => [ 'min' => 0.5, 'max' => 8,  'step' => 0.1 ],
                        'vw'  => [ 'min' => 1,   'max' => 15, 'step' => 0.1 ],
                    ],
                    'default'        => [ 'unit' => 'px', 'size' => 40 ],
                    'tablet_default' => [ 'unit' => 'px', 'size' => 30 ],
                    'mobile_default' => [ 'unit' => 'px', 'size' => 24 ],
                    'selectors'      => [
                        '{{WRAPPER}} .ec-title' => 'font-size: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'title_align',
                [
                    'label' => __('Alineación', 'eventos-carrusel'),
                    'type' => \Elementor\Controls_Manager::CHOOSE,
                    'options' => [
                        'left'   => [ 'title' => __('Izquierda', 'eventos-carrusel'), 'icon' => 'eicon-text-align-left' ],
                        'center' => [ 'title' => __('Centro', 'eventos-carrusel'),    'icon' => 'eicon-text-align-center' ],
                        'right'  => [ 'title' => __('Derecha', 'eventos-carrusel'),   'icon' => 'eicon-text-align-right' ],
                    ],
                    'default' => 'left',
                    'selectors' => [
                        '{{WRAPPER}} .ec-title' => 'text-align: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'title_text_shadow_toggle',
                [
                    'label'        => __('Sombra de Texto', 'eventos-carrusel'),
                    'type'         => \Elementor\Controls_Manager::SWITCHER,
                    'label_on'     => __('Sí', 'eventos-carrusel'),
                    'label_off'    => __('No', 'eventos-carrusel'),
                    'return_value' => 'yes',
                    'default'      => 'yes',
                ]
            );

            $this->add_group_control(
                \Elementor\Group_Control_Text_Shadow::get_type(),
                [
                    'name'      => 'title_text_shadow',
                    'selector'  => '{{WRAPPER}} .ec-title',
                    'condition' => [ 'title_text_shadow_toggle' => 'yes' ],
                ]
            );

            $this->add_responsive_control(
                'title_padding',
                [
                    'label'      => __('Padding', 'eventos-carrusel'),
                    'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors'  => [
                        '{{WRAPPER}} .ec-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'title_margin',
                [
                    'label'      => __('Margen', 'eventos-carrusel'),
                    'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors'  => [
                        '{{WRAPPER}} .ec-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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

            $this->add_responsive_control(
                'button_font_size',
                [
                    'label'          => __('Tamaño de Texto', 'eventos-carrusel'),
                    'type'           => \Elementor\Controls_Manager::SLIDER,
                    'size_units'     => [ 'px', 'em', 'rem' ],
                    'range'          => [
                        'px'  => [ 'min' => 8,  'max' => 48, 'step' => 1 ],
                        'em'  => [ 'min' => 0.5, 'max' => 3, 'step' => 0.05 ],
                        'rem' => [ 'min' => 0.5, 'max' => 3, 'step' => 0.05 ],
                    ],
                    'default'        => [ 'unit' => 'px', 'size' => 10 ],
                    'tablet_default' => [ 'unit' => 'px', 'size' => 12 ],
                    'mobile_default' => [ 'unit' => 'px', 'size' => 10 ],
                    'selectors'      => [
                        '{{WRAPPER}} .ec-rsvp-btn' => 'font-size: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );
            
            $this->add_control(
                'button_width_type',
                [
                    'label' => __('Ancho del Botón', 'eventos-carrusel'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => '100%',
                    'options' => [
                        '100%' => __('Ancho Completo', 'eventos-carrusel'),
                        'auto' => __('Auto (Inline)', 'eventos-carrusel'),
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .ec-rsvp-btn' => 'width: {{VALUE}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'button_align',
                [
                    'label' => __('Alineación', 'eventos-carrusel'),
                    'type' => \Elementor\Controls_Manager::CHOOSE,
                    'options' => [
                        'left' => [
                            'title' => __('Izquierda', 'eventos-carrusel'),
                            'icon' => 'eicon-text-align-left',
                        ],
                        'center' => [
                            'title' => __('Centro', 'eventos-carrusel'),
                            'icon' => 'eicon-text-align-center',
                        ],
                        'right' => [
                            'title' => __('Derecha', 'eventos-carrusel'),
                            'icon' => 'eicon-text-align-right',
                        ],
                    ],
                    'default' => 'center',
                    'condition' => [
                        'button_width_type' => 'auto',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .ec-rsvp-container' => 'text-align: {{VALUE}};',
                    ],
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

            $this->add_responsive_control(
                'button_margin',
                [
                    'label'      => __('Margen', 'eventos-carrusel'),
                    'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors'  => [
                        '{{WRAPPER}} .ec-rsvp-btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
            
            // Atributos del botón
            $shortcode_atts[] = 'button_text="' . esc_attr($settings['button_text']) . '"';
            $shortcode_atts[] = 'link_type="' . esc_attr($settings['link_type']) . '"';
            
            if ($settings['link_type'] === 'custom' && !empty($settings['button_link']['url'])) {
                $this->add_link_attributes('button_custom_link', $settings['button_link']);
                // Encodificar atributos para pasarlos seguros por shortcode
                $link_attrs = base64_encode($this->get_render_attribute_string('button_custom_link'));
                $shortcode_atts[] = 'link_attrs="' . $link_attrs . '"';
            }

            // Slides por vista (responsive)
            $slides_desktop = !empty($settings['slides_desktop']) ? intval($settings['slides_desktop']) : 3;
            $slides_tablet  = !empty($settings['slides_tablet'])  ? intval($settings['slides_tablet'])  : 2;
            $slides_mobile  = !empty($settings['slides_mobile'])  ? intval($settings['slides_mobile'])  : 1;
            $space_between  = isset($settings['space_between'])   ? intval($settings['space_between'])  : 24;

            $shortcode_atts[] = 'slides_desktop="' . $slides_desktop . '"';
            $shortcode_atts[] = 'slides_tablet="'  . $slides_tablet  . '"';
            $shortcode_atts[] = 'slides_mobile="'  . $slides_mobile  . '"';
            $shortcode_atts[] = 'space_between="'  . $space_between  . '"';
            
            $shortcode = '[carrusel_eventos ' . implode(' ', $shortcode_atts) . ']';
            
            echo do_shortcode($shortcode);
        }
    }
    
    // Register widget
    $widgets_manager->register( new \EC_Carrusel_Eventos_Widget() );
}
add_action( 'elementor/widgets/register', 'ec_register_elementor_widget' );
