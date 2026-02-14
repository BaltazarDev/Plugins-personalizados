<?php
/**
 * Widget de Elementor para el carrusel de talentos
 */

// Evitar acceso directo
if ( ! defined( 'ABSPATH' ) ) {
    exit; 
}

/**
 * Register Elementor Widget.
 *
 * @param \Elementor\Widgets_Manager $widgets_manager Elementor widgets manager.
 * @return void
 */
function tc_register_elementor_widget( $widgets_manager ) {

    // Define the widget class inside the registration function to ensure Elementor is loaded
    // and to avoid redeclaration issues.
    class TC_Carrusel_Talentos_Widget extends \Elementor\Widget_Base {
        
        public function get_name() {
            return 'tc_carrusel_talentos';
        }
        
        public function get_title() {
            return __('Carrusel de Talentos', 'talentos-carrusel');
        }
        
        public function get_icon() {
            return 'eicon-person';
        }
        
        public function get_categories() {
            return ['general'];
        }
        
        protected function register_controls() {
            // Sección de contenido
            $this->start_controls_section(
                'content_section',
                [
                    'label' => __('Contenido', 'talentos-carrusel'),
                    'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                ]
            );
            
            $this->add_control(
                'categoria',
                [
                    'label' => __('Categoría', 'talentos-carrusel'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'description' => __('Deja vacío para mostrar todos los talentos', 'talentos-carrusel'),
                    'placeholder' => __('slug-de-la-categoria', 'talentos-carrusel'),
                ]
            );
            
            $this->add_responsive_control(
                'slides_per_view',
                [
                    'label' => __('Columnas', 'talentos-carrusel'),
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
                    'label' => __('Límite de posts', 'talentos-carrusel'),
                    'type' => \Elementor\Controls_Manager::NUMBER,
                    'default' => 6,
                    'min' => -1,
                    'max' => 50,
                    'description' => __('Total de talentos a mostrar (-1 para todos)', 'talentos-carrusel'),
                ]
            );
            
            $this->add_control(
                'orderby',
                [
                    'label' => __('Ordenar por', 'talentos-carrusel'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => 'date',
                    'options' => [
                        'date' => __('Fecha', 'talentos-carrusel'),
                        'title' => __('Título', 'talentos-carrusel'),
                        'rand' => __('Aleatorio', 'talentos-carrusel'),
                        'menu_order' => __('Orden personalizado', 'talentos-carrusel'),
                    ],
                ]
            );
            
            $this->add_control(
                'order',
                [
                    'label' => __('Orden', 'talentos-carrusel'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => 'DESC',
                    'options' => [
                        'ASC' => __('Ascendente', 'talentos-carrusel'),
                        'DESC' => __('Descendente', 'talentos-carrusel'),
                    ],
                ]
            );
            
            $this->add_control(
                'autoplay',
                [
                    'label' => __('Autoplay', 'talentos-carrusel'),
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                    'label_on' => __('Sí', 'talentos-carrusel'),
                    'label_off' => __('No', 'talentos-carrusel'),
                    'return_value' => 'yes',
                    'default' => 'no',
                ]
            );

            $this->add_control(
                'autoplay_delay',
                [
                    'label' => __('Delay (segundos)', 'talentos-carrusel'),
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
                    'label' => __('Estilo', 'talentos-carrusel'),
                    'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                ]
            );
            
            $this->add_control(
                'image_options',
                [
                    'label' => __('Imagen', 'talentos-carrusel'),
                    'type' => \Elementor\Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_responsive_control(
                'image_height',
                [
                    'label' => __('Altura de Imagen', 'talentos-carrusel'),
                    'type' => \Elementor\Controls_Manager::SLIDER,
                    'size_units' => ['px', 'vh'],
                    'range' => [
                        'px' => [
                            'min' => 200,
                            'max' => 800,
                            'step' => 10,
                        ],
                        'vh' => [
                            'min' => 20,
                            'max' => 100,
                            'step' => 1,
                        ],
                    ],
                    'default' => [
                        'unit' => 'px',
                        'size' => '',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .tc-card-image' => 'height: {{SIZE}}{{UNIT}}; aspect-ratio: auto;',
                    ],
                    'description' => __('Deja vacío para usar el ratio 4:5 por defecto', 'talentos-carrusel'),
                ]
            );
            
            $this->add_control(
                'title_options',
                [
                    'label' => __('Título', 'talentos-carrusel'),
                    'type' => \Elementor\Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_group_control(
                \Elementor\Group_Control_Typography::get_type(),
                [
                    'name' => 'title_typography',
                    'selector' => '{{WRAPPER}} .tc-card-title',
                ]
            );

            $this->add_control(
                'title_color',
                [
                    'label' => __('Color del Título', 'talentos-carrusel'),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .tc-card-title' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_control(
                'description_options',
                [
                    'label' => __('Descripción / Rol', 'talentos-carrusel'),
                    'type' => \Elementor\Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_group_control(
                \Elementor\Group_Control_Typography::get_type(),
                [
                    'name' => 'description_typography',
                    'selector' => '{{WRAPPER}} .tc-card-excerpt, {{WRAPPER}} .tc-card-excerpt p',
                ]
            );

            $this->add_control(
                'description_color',
                [
                    'label' => __('Color de la Descripción', 'talentos-carrusel'),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .tc-card-excerpt' => 'color: {{VALUE}}',
                        '{{WRAPPER}} .tc-card-excerpt p' => 'color: {{VALUE}}',
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
            
            $shortcode = '[carrusel_talentos ' . implode(' ', $shortcode_atts) . ']';
            
            echo do_shortcode($shortcode);
        }
    }
    
    // Register widget
    $widgets_manager->register( new \TC_Carrusel_Talentos_Widget() );
}
add_action( 'elementor/widgets/register', 'tc_register_elementor_widget' );
