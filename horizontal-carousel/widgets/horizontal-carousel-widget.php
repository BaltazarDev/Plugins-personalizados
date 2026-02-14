<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Horizontal Carousel Widget
 */
class Horizontal_Carousel_Widget extends \Elementor\Widget_Base {

    /**
     * Get widget name
     */
    public function get_name() {
        return 'horizontal-carousel';
    }

    /**
     * Get widget title
     */
    public function get_title() {
        return esc_html__('Horizontal Carousel', 'horizontal-carousel');
    }

    /**
     * Get widget icon
     */
    public function get_icon() {
        return 'eicon-slider-push';
    }

    /**
     * Get widget categories
     */
    public function get_categories() {
        return ['general'];
    }

    /**
     * Get widget keywords
     */
    public function get_keywords() {
        return ['carousel', 'slider', 'horizontal', 'scroll', 'servicios'];
    }

    /**
     * Get script dependencies
     */
    public function get_script_depends() {
        return ['horizontal-carousel'];
    }

    /**
     * Register widget controls
     */
    protected function register_controls() {
        
        // Content Section
        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__('Contenido', 'horizontal-carousel'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        // Category Selection (usar la taxonomía categoria_servicio)
        $this->add_control(
            'servicio_category',
            [
                'label' => esc_html__('Categoría de Servicio', 'horizontal-carousel'),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'options' => $this->get_servicio_categories(),
                'default' => '',
                'multiple' => false,
                'label_block' => true,
                'description' => esc_html__('Selecciona la categoría de servicios a mostrar', 'horizontal-carousel'),
            ]
        );

        // Number of Posts
        $this->add_control(
            'posts_per_page',
            [
                'label' => esc_html__('Número de Servicios', 'horizontal-carousel'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 10,
                'min' => 1,
                'max' => 50,
                'step' => 1,
            ]
        );

        // Order By
        $this->add_control(
            'orderby',
            [
                'label' => esc_html__('Ordenar Por', 'horizontal-carousel'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'date' => esc_html__('Fecha', 'horizontal-carousel'),
                    'title' => esc_html__('Título', 'horizontal-carousel'),
                    'rand' => esc_html__('Aleatorio', 'horizontal-carousel'),
                    'menu_order' => esc_html__('Orden del Menú', 'horizontal-carousel'),
                ],
                'default' => 'date',
            ]
        );

        // Order
        $this->add_control(
            'order',
            [
                'label' => esc_html__('Orden', 'horizontal-carousel'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'DESC' => esc_html__('Descendente', 'horizontal-carousel'),
                    'ASC' => esc_html__('Ascendente', 'horizontal-carousel'),
                ],
                'default' => 'DESC',
                'condition' => [
                    'orderby!' => 'rand',
                ],
            ]
        );

        $this->end_controls_section();

        // Settings Section
        $this->start_controls_section(
            'settings_section',
            [
                'label' => esc_html__('Configuración', 'horizontal-carousel'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        // Section Height (scroll distance)
        $this->add_control(
            'section_height',
            [
                'label' => esc_html__('Altura de Sección (vh)', 'horizontal-carousel'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 400,
                'min' => 200,
                'max' => 800,
                'step' => 50,
                'description' => esc_html__('Altura de la sección en vh. Mayor valor = scroll más lento', 'horizontal-carousel'),
            ]
        );

        $this->end_controls_section();

        // Style Section
        $this->start_controls_section(
            'style_section',
            [
                'label' => esc_html__('Estilo', 'horizontal-carousel'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        // Title Color
        $this->add_control(
            'title_color',
            [
                'label' => esc_html__('Color del Título', 'horizontal-carousel'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .slide-title' => 'color: {{VALUE}}',
                ],
            ]
        );

        // Button Color
        $this->add_control(
            'button_color',
            [
                'label' => esc_html__('Color del Botón', 'horizontal-carousel'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .consultar-btn' => 'color: {{VALUE}}; border-bottom-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Get servicio categories list
     */
    private function get_servicio_categories() {
        $terms = get_terms([
            'taxonomy' => 'categoria_servicio',
            'hide_empty' => false,
        ]);

        $options = ['' => esc_html__('Todas las categorías', 'horizontal-carousel')];

        if (!is_wp_error($terms) && !empty($terms)) {
            foreach ($terms as $term) {
                $options[$term->term_id] = $term->name;
            }
        }

        return $options;
    }

    /**
     * Render widget output
     */
    protected function render() {
        $settings = $this->get_settings_for_display();

        // Query arguments para Custom Post Type 'servicio'
        $args = [
            'post_type' => 'servicio',
            'posts_per_page' => $settings['posts_per_page'],
            'orderby' => $settings['orderby'],
            'order' => $settings['order'],
            'post_status' => 'publish',
        ];

        // Add category filter if selected (usar categoria_servicio taxonomy)
        if (!empty($settings['servicio_category'])) {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'categoria_servicio',
                    'field'    => 'term_id',
                    'terms'    => $settings['servicio_category'],
                ),
            );
        }

        $query = new WP_Query($args);

        if (!$query->have_posts()) {
            echo '<div style="padding: 2rem; text-align: center; background: #f0f0f0;">';
            echo '<p>' . esc_html__('No se encontraron servicios. Por favor crea servicios en el menú "Servicios" del panel de WordPress.', 'horizontal-carousel') . '</p>';
            echo '</div>';
            return;
        }

        // Generate unique ID for this carousel instance
        $carousel_id = 'carousel-' . $this->get_id();
        $section_height = $settings['section_height'] . 'vh';
        ?>

        <div class="horizontal-carousel-section" id="<?php echo esc_attr($carousel_id); ?>" style="height: <?php echo esc_attr($section_height); ?>;">
            <div class="horizontal-sticky-wrapper">
                <!-- Navigation Arrows -->
                <div class="horizontal-nav-arrows">
                    <div class="horizontal-nav-arrow horizontal-prev-btn" data-carousel="<?php echo esc_attr($carousel_id); ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                        </svg>
                    </div>
                    <div class="horizontal-nav-arrow horizontal-next-btn" data-carousel="<?php echo esc_attr($carousel_id); ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                        </svg>
                    </div>
                </div>

                <!-- Horizontal Track -->
                <div class="horizontal-track" data-carousel="<?php echo esc_attr($carousel_id); ?>">
                    <?php
                    while ($query->have_posts()) : $query->the_post();
                        $post_title = get_the_title();
                        $post_url = get_permalink();
                        $thumbnail_url = get_the_post_thumbnail_url(get_the_ID(), 'full');
                        
                        // Fallback image if no featured image
                        if (!$thumbnail_url) {
                            $thumbnail_url = 'https://images.unsplash.com/photo-1497366216548-37526070297c?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80';
                        }
                        ?>
                        <div class="horizontal-slide">
                            <img src="<?php echo esc_url($thumbnail_url); ?>" alt="<?php echo esc_attr($post_title); ?>">
                            <div class="horizontal-slide-content">
                                <h2 class="slide-title"><?php echo esc_html($post_title); ?></h2>
                                <a href="<?php echo esc_url($post_url); ?>" class="consultar-btn">
                                    <?php echo esc_html__('Consultar', 'horizontal-carousel'); ?> <span class="text-lg">→</span>
                                </a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                    <?php wp_reset_postdata(); ?>
                </div>
            </div>
        </div>

        <?php
    }
}
