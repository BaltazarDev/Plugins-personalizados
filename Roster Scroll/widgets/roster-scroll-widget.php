<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Elementor Widget: Roster Scroll con Parallax
 */
class Roster_Scroll_Widget extends \Elementor\Widget_Base
{
    public function get_name()
    {
        return 'roster_scroll';
    }

    public function get_title()
    {
        return esc_html__('Roster Scroll Parallax', 'plugin-roster-scroll');
    }

    public function get_icon()
    {
        return 'eicon-gallery-grid';
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
                'label' => esc_html__('Contenido', 'plugin-roster-scroll'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'posts_per_page',
            [
                'label' => esc_html__('Cantidad de Talentos', 'plugin-roster-scroll'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 20,
                'step' => 1,
                'default' => 6,
            ]
        );

        $this->add_control(
            'marquee_text',
            [
                'label' => esc_html__('Texto Marquesina', 'plugin-roster-scroll'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'ROSTER',
                'placeholder' => 'ROSTER',
            ]
        );

        $this->add_control(
            'scroll_height',
            [
                'label' => esc_html__('Altura de Scroll (vh)', 'plugin-roster-scroll'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 200,
                'max' => 800,
                'step' => 50,
                'default' => 400,
                'description' => 'Altura del contenedor en viewport height (100vh = altura de pantalla)',
            ]
        );

        $this->end_controls_section();

        // ===== STYLE SECTION - BACKGROUND =====
        $this->start_controls_section(
            'style_background',
            [
                'label' => esc_html__('Fondo', 'plugin-roster-scroll'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'background_color',
            [
                'label' => esc_html__('Color de Fondo', 'plugin-roster-scroll'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => 'transparent',
            ]
        );

        $this->end_controls_section();

        // ===== STYLE SECTION - MARQUEE TEXT =====
        $this->start_controls_section(
            'style_marquee',
            [
                'label' => esc_html__('Texto Marquesina', 'plugin-roster-scroll'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'marquee_typography',
                'label' => esc_html__('Tipografía', 'plugin-roster-scroll'),
                'selector' => '{{WRAPPER}} .wp-marquee-h1',
            ]
        );

        $this->add_control(
            'marquee_color',
            [
                'label' => esc_html__('Color', 'plugin-roster-scroll'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .wp-marquee-h1' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'marquee_blend_mode',
            [
                'label' => esc_html__('Modo de Mezcla', 'plugin-roster-scroll'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'difference',
                'options' => [
                    'normal' => 'Normal',
                    'multiply' => 'Multiply',
                    'screen' => 'Screen',
                    'overlay' => 'Overlay',
                    'difference' => 'Difference',
                    'exclusion' => 'Exclusion',
                ],
                'selectors' => [
                    '{{WRAPPER}} .wp-marquee-h1' => 'mix-blend-mode: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // ===== STYLE SECTION - IMAGES =====
        $this->start_controls_section(
            'style_images',
            [
                'label' => esc_html__('Imágenes', 'plugin-roster-scroll'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'image_width',
            [
                'label' => esc_html__('Ancho de Imagen', 'plugin-roster-scroll'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['vw', '%', 'px'],
                'range' => [
                    'vw' => [
                        'min' => 10,
                        'max' => 50,
                    ],
                    '%' => [
                        'min' => 10,
                        'max' => 50,
                    ],
                    'px' => [
                        'min' => 100,
                        'max' => 500,
                    ],
                ],
                'default' => [
                    'unit' => 'vw',
                    'size' => 20,
                ],
                'selectors' => [
                    '{{WRAPPER}} .wp-parallax-item' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'image_border_radius',
            [
                'label' => esc_html__('Radio del Borde', 'plugin-roster-scroll'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 12,
                ],
                'selectors' => [
                    '{{WRAPPER}} .wp-parallax-inner' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'image_grayscale',
            [
                'label' => esc_html__('Escala de Grises', 'plugin-roster-scroll'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Sí', 'plugin-roster-scroll'),
                'label_off' => esc_html__('No', 'plugin-roster-scroll'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();

        // ===== STYLE SECTION - BADGES =====
        $this->start_controls_section(
            'style_badges',
            [
                'label' => esc_html__('Etiquetas (Badges)', 'plugin-roster-scroll'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'badge_typography',
                'label' => esc_html__('Tipografía', 'plugin-roster-scroll'),
                'selector' => '{{WRAPPER}} .wp-badge',
            ]
        );

        $this->add_control(
            'badge_text_color',
            [
                'label' => esc_html__('Color de Texto', 'plugin-roster-scroll'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .wp-badge' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'badge_bg_color',
            [
                'label' => esc_html__('Color de Fondo', 'plugin-roster-scroll'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#2563eb',
                'selectors' => [
                    '{{WRAPPER}} .wp-badge' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'badge_padding',
            [
                'label' => esc_html__('Padding', 'plugin-roster-scroll'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'default' => [
                    'top' => 8,
                    'right' => 16,
                    'bottom' => 8,
                    'left' => 16,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .wp-badge' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $posts_per_page = $settings['posts_per_page'];
        $marquee_text = $settings['marquee_text'];
        $scroll_height = $settings['scroll_height'];
        $background_color = $settings['background_color'];
        $image_grayscale = $settings['image_grayscale'];

        // Query talentos from the invisible category
        $args = array(
            'post_type' => 'talento',
            'posts_per_page' => $posts_per_page,
            'post_status' => 'publish',
            'tax_query' => array(
                array(
                    'taxonomy' => 'categoria_talento',
                    'field' => 'slug',
                    'terms' => 'talentos',
                ),
            ),
        );

        $query = new \WP_Query($args);

        if (!$query->have_posts()) {
            if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                echo '<div style="padding: 20px; text-align: center; background: #f0f0f0;">No hay talentos para mostrar. Crea posts de tipo "Talento" y asígnalos a la categoría "Talentos".</div>';
            }
            return;
        }

        // Generate unique ID for this widget instance
        $widget_id = 'roster-' . $this->get_id();

        // Predefined positions for parallax items (cycling through these)
        $positions = [
            ['left' => '3%', 'start' => 1, 'z-index' => 30],
            ['left' => '53%', 'start' => 1, 'z-index' => 10],
            ['left' => '28%', 'start' => 200, 'z-index' => 10],
            ['left' => '78%', 'start' => 85, 'z-index' => 30],
            ['left' => '28%', 'start' => 145, 'z-index' => 30],
            ['left' => '78%', 'start' => 60, 'z-index' => 10],
        ];

        $badge_colors = ['wp-bg-blue', 'wp-bg-pink', 'wp-bg-orange', 'wp-bg-purple', 'wp-bg-green', 'wp-bg-yellow'];

        ?>
        <style>
            /* Contenedor principal */
            #<?php echo esc_attr($widget_id); ?>-root {
                position: relative;
                height: <?php echo esc_attr($scroll_height); ?>vh;
                background-color: <?php echo esc_attr($background_color); ?>;
                overflow: visible;
                margin: 0;
                padding: 0;
            }

            #<?php echo esc_attr($widget_id); ?>-root .wp-sticky-wrapper {
                position: sticky;
                top: 0;
                height: 100vh;
                overflow: hidden;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            #<?php echo esc_attr($widget_id); ?>-root .wp-marquee-text {
                position: absolute;
                white-space: nowrap;
                z-index: 20;
                pointer-events: none;
                will-change: transform;
                left: 50%;
                transform: translateX(0%);
            }

            #<?php echo esc_attr($widget_id); ?>-root .wp-marquee-h1 {
                font-size: 20vw;
                font-weight: 900;
                text-transform: uppercase;
                margin: 0;
                line-height: 1;
                letter-spacing: -0.05em;
            }

            @media (min-width: 768px) {
                #<?php echo esc_attr($widget_id); ?>-root .wp-marquee-h1 { 
                    font-size: 25vw; 
                }
            }

            #<?php echo esc_attr($widget_id); ?>-root .wp-images-container {
                position: absolute;
                inset: 0;
                width: 100%;
                height: 100%;
                max-width: 1600px;
                margin: 0 auto;
                pointer-events: none;
            }

            #<?php echo esc_attr($widget_id); ?>-root .wp-parallax-item {
                position: absolute;
                top: 0;
                aspect-ratio: 3/4;
                will-change: transform;
                display: flex;
                flex-direction: column;
            }

            #<?php echo esc_attr($widget_id); ?>-root .wp-parallax-inner {
                position: relative;
                width: 100%;
                height: 100%;
                overflow: hidden;
                box-shadow: 0 20px 50px rgba(0,0,0,0.5);
                background-color: #222;
            }

            #<?php echo esc_attr($widget_id); ?>-root .wp-parallax-inner img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                <?php if ($image_grayscale === 'yes') : ?>
                filter: grayscale(100%);
                <?php endif; ?>
                transition: filter 0.5s ease;
            }

            #<?php echo esc_attr($widget_id); ?>-root .wp-parallax-inner:hover img {
                filter: grayscale(0%);
            }

            #<?php echo esc_attr($widget_id); ?>-root .wp-badge {
                position: absolute;
                bottom: 20px;
                right: -10px;
                border-top-left-radius: 99px;
                border-bottom-left-radius: 99px;
                box-shadow: 0 4px 6px rgba(0,0,0,0.3);
            }

            /* Badge color utilities */
            .wp-bg-blue { background-color: #2563eb; }
            .wp-bg-pink { background-color: #db2777; }
            .wp-bg-orange { background-color: #f97316; }
            .wp-bg-purple { background-color: #9333ea; }
            .wp-bg-green { background-color: #16a34a; }
            .wp-bg-yellow { background-color: #eab308; }
        </style>

        <div id="<?php echo esc_attr($widget_id); ?>-root">
            <div class="wp-sticky-wrapper">
                
                <!-- Texto Marquesina -->
                <div class="wp-marquee-text" id="<?php echo esc_attr($widget_id); ?>-marquee">
                    <h1 class="wp-marquee-h1"><?php echo esc_html($marquee_text); ?></h1>
                </div>

                <!-- Imágenes Parallax -->
                <div class="wp-images-container">
                    <?php
                    $index = 0;
                    while ($query->have_posts()) {
                        $query->the_post();
                        $image_url = get_the_post_thumbnail_url(get_the_ID(), 'full');
                        if (!$image_url) {
                            $image_url = 'https://placehold.co/400x600/e2e8f0/1e293b?text=' . urlencode(get_the_title());
                        }
                        $title = get_the_title();
                        
                        // Get position from predefined array (cycle through)
                        $pos = $positions[$index % count($positions)];
                        $badge_color = $badge_colors[$index % count($badge_colors)];
                        ?>
                        <div class="wp-parallax-item" 
                             data-start="<?php echo esc_attr($pos['start']); ?>" 
                             style="left: <?php echo esc_attr($pos['left']); ?>; z-index: <?php echo esc_attr($pos['z-index']); ?>;">
                            <div class="wp-parallax-inner">
                                <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($title); ?>" />
                                <div class="wp-badge <?php echo esc_attr($badge_color); ?>">
                                    <?php echo esc_html($title); ?>
                                </div>
                            </div>
                        </div>
                        <?php
                        $index++;
                    }
                    wp_reset_postdata();
                    ?>
                </div>
            </div>
        </div>

        <script>
        (function() {
            const root = document.getElementById('<?php echo esc_js($widget_id); ?>-root');
            const text = document.getElementById('<?php echo esc_js($widget_id); ?>-marquee');
            const items = root.querySelectorAll('.wp-parallax-item');

            function onScroll() {
                if (!root) return;

                const rect = root.getBoundingClientRect();
                const viewportHeight = window.innerHeight;
                const scrollableDistance = root.offsetHeight - viewportHeight;
                const scrolled = -rect.top;
                
                let progress = 0;
                if (scrollableDistance > 0) {
                    progress = scrolled / scrollableDistance;
                }
                
                if (progress < 0) progress = 0;
                if (progress > 1) progress = 1;

                // Animación del texto marquesina
                const startX = 50;
                const endX = -100;
                const currentX = startX + (progress * (endX - startX));
                text.style.transform = `translateX(${currentX}%)`;

                // Animación de imágenes parallax
                items.forEach(item => {
                    const startOffset = parseFloat(item.getAttribute('data-start')) || 0;
                    const startY = 100 + startOffset;
                    const endY = -(100 + startOffset + 50);
                    const currentY = startY + (progress * (endY - startY));
                    item.style.transform = `translateY(${currentY}%)`;
                });
            }

            window.addEventListener('scroll', onScroll, { passive: true });
            window.addEventListener('resize', onScroll);
            onScroll();
        })();
        </script>
        <?php
    }
}
