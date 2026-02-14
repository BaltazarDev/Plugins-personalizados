<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Elementor Widget: Logo Marquee
 */
class Logo_Marquee_Widget extends \Elementor\Widget_Base
{
    public function get_name()
    {
        return 'logo_marquee';
    }

    public function get_title()
    {
        return esc_html__('Logo Marquee', 'plugin-logo-marquee');
    }

    public function get_icon()
    {
        return 'eicon-slider-push';
    }

    public function get_categories()
    {
        return ['general'];
    }

    public function get_script_depends()
    {
        return [];
    }

    protected function register_controls()
    {
        // ===== CONTENT SECTION =====
        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__('Contenido', 'plugin-logo-marquee'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'gallery',
            [
                'label' => esc_html__('Seleccionar Imágenes', 'plugin-logo-marquee'),
                'type' => \Elementor\Controls_Manager::GALLERY,
                'default' => [],
                'show_label' => true,
            ]
        );

        $this->add_control(
            'direction',
            [
                'label' => esc_html__('Dirección', 'plugin-logo-marquee'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'left',
                'options' => [
                    'left' => esc_html__('Izquierda', 'plugin-logo-marquee'),
                    'right' => esc_html__('Derecha', 'plugin-logo-marquee'),
                ],
            ]
        );

        $this->add_control(
            'duplicate',
            [
                'label' => esc_html__('Duplicar Imágenes', 'plugin-logo-marquee'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Sí', 'plugin-logo-marquee'),
                'label_off' => esc_html__('No', 'plugin-logo-marquee'),
                'return_value' => 'yes',
                'default' => 'yes',
                'description' => 'Duplica las imágenes para crear un loop infinito sin interrupciones',
            ]
        );

        $this->add_control(
            'speed',
            [
                'label' => esc_html__('Velocidad', 'plugin-logo-marquee'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 30,
                ],
                'description' => 'Velocidad de la animación (1 = muy lento, 100 = muy rápido)',
            ]
        );

        $this->end_controls_section();

        // ===== STYLE SECTION - IMAGES =====
        $this->start_controls_section(
            'style_images',
            [
                'label' => esc_html__('Imágenes', 'plugin-logo-marquee'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'image_height',
            [
                'label' => esc_html__('Altura de Imagen', 'plugin-logo-marquee'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'vh', 'em'],
                'range' => [
                    'px' => [
                        'min' => 20,
                        'max' => 500,
                    ],
                    'vh' => [
                        'min' => 5,
                        'max' => 50,
                    ],
                    'em' => [
                        'min' => 1,
                        'max' => 20,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 80,
                ],
                'selectors' => [
                    '{{WRAPPER}} .logo-marquee-item img' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_width',
            [
                'label' => esc_html__('Ancho de Imagen', 'plugin-logo-marquee'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'auto'],
                'range' => [
                    'px' => [
                        'min' => 20,
                        'max' => 500,
                    ],
                ],
                'default' => [
                    'unit' => 'auto',
                ],
                'selectors' => [
                    '{{WRAPPER}} .logo-marquee-item img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'object_fit',
            [
                'label' => esc_html__('Object Fit', 'plugin-logo-marquee'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'contain',
                'options' => [
                    'contain' => esc_html__('Contain', 'plugin-logo-marquee'),
                    'cover' => esc_html__('Cover', 'plugin-logo-marquee'),
                    'fill' => esc_html__('Fill', 'plugin-logo-marquee'),
                    'scale-down' => esc_html__('Scale Down', 'plugin-logo-marquee'),
                    'none' => esc_html__('None', 'plugin-logo-marquee'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .logo-marquee-item img' => 'object-fit: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_gap',
            [
                'label' => esc_html__('Espaciado entre Imágenes', 'plugin-logo-marquee'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 10,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 40,
                ],
                'selectors' => [
                    '{{WRAPPER}} .logo-marquee-track' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'image_border_radius',
            [
                'label' => esc_html__('Radio del Borde', 'plugin-logo-marquee'),
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
                'default' => [
                    'unit' => 'px',
                    'size' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .logo-marquee-item img' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'image_grayscale',
            [
                'label' => esc_html__('Escala de Grises', 'plugin-logo-marquee'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Sí', 'plugin-logo-marquee'),
                'label_off' => esc_html__('No', 'plugin-logo-marquee'),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );

        $this->add_control(
            'image_opacity',
            [
                'label' => esc_html__('Opacidad', 'plugin-logo-marquee'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1,
                        'step' => 0.1,
                    ],
                ],
                'default' => [
                    'size' => 1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .logo-marquee-item img' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // ===== STYLE SECTION - CONTAINER =====
        $this->start_controls_section(
            'style_container',
            [
                'label' => esc_html__('Contenedor', 'plugin-logo-marquee'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'background_color',
            [
                'label' => esc_html__('Color de Fondo', 'plugin-logo-marquee'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => 'transparent',
                'selectors' => [
                    '{{WRAPPER}} .logo-marquee-container' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'container_padding',
            [
                'label' => esc_html__('Padding', 'plugin-logo-marquee'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'default' => [
                    'top' => 20,
                    'right' => 0,
                    'bottom' => 20,
                    'left' => 0,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .logo-marquee-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $gallery = $settings['gallery'];
        $direction = $settings['direction'];
        $duplicate = $settings['duplicate'];
        $speed = $settings['speed']['size'];
        $grayscale = $settings['image_grayscale'];

        if (empty($gallery)) {
            if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                echo '<div style="padding: 20px; text-align: center; background: #f0f0f0;">Por favor selecciona imágenes en la galería.</div>';
            }
            return;
        }

        // Generate unique ID for this widget instance
        $widget_id = 'marquee-' . $this->get_id();

        // Calculate animation duration based on speed (inverse relationship)
        // Speed 1 = 100s (very slow), Speed 100 = 10s (very fast)
        $duration = max(10, 110 - $speed);

        // Animation direction
        $animation_name = $direction === 'left' ? 'marquee-left' : 'marquee-right';

        ?>
        <style>
            #<?php echo esc_attr($widget_id); ?> .logo-marquee-container {
                width: 100%;
                overflow: hidden;
                position: relative;
            }

            #<?php echo esc_attr($widget_id); ?> .logo-marquee-track {
                display: flex;
                align-items: center;
                width: fit-content;
                animation: <?php echo esc_attr($animation_name); ?> <?php echo esc_attr($duration); ?>s linear infinite;
            }

            #<?php echo esc_attr($widget_id); ?> .logo-marquee-item {
                flex-shrink: 0;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            #<?php echo esc_attr($widget_id); ?> .logo-marquee-item img {
                display: block;
                <?php if ($grayscale === 'yes') : ?>
                filter: grayscale(100%);
                <?php endif; ?>
                transition: filter 0.3s ease;
            }

            #<?php echo esc_attr($widget_id); ?> .logo-marquee-item img:hover {
                filter: grayscale(0%);
            }

            @keyframes marquee-left {
                from {
                    transform: translateX(0);
                }
                to {
                    transform: translateX(-50%);
                }
            }

            @keyframes marquee-right {
                from {
                    transform: translateX(-50%);
                }
                to {
                    transform: translateX(0);
                }
            }
        </style>

        <div id="<?php echo esc_attr($widget_id); ?>">
            <div class="logo-marquee-container">
                <div class="logo-marquee-track">
                    <?php
                    // First set of images
                    foreach ($gallery as $image) {
                        $image_url = wp_get_attachment_image_url($image['id'], 'full');
                        if (!$image_url) {
                            continue;
                        }
                        $alt = get_post_meta($image['id'], '_wp_attachment_image_alt', true);
                        ?>
                        <div class="logo-marquee-item">
                            <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($alt); ?>">
                        </div>
                        <?php
                    }

                    // Duplicate images if enabled
                    if ($duplicate === 'yes') {
                        foreach ($gallery as $image) {
                            $image_url = wp_get_attachment_image_url($image['id'], 'full');
                            if (!$image_url) {
                                continue;
                            }
                            $alt = get_post_meta($image['id'], '_wp_attachment_image_alt', true);
                            ?>
                            <div class="logo-marquee-item">
                                <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($alt); ?>">
                            </div>
                            <?php
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
        <?php
    }
}
