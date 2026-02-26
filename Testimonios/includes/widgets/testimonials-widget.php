<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class The_Mind_Testimonials_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'the_mind_testimonials';
	}

	public function get_title() {
		return esc_html__( 'The Mind Testimonials', 'the-mind-testimonials' );
	}

	public function get_icon() {
		return 'eicon-testimonial';
	}

	public function get_categories() {
		return [ 'general' ];
	}

    public function get_script_depends() {
		return [ 'the-mind-testimonials-script', 'swiper' ];
	}

    public function get_style_depends() {
		return [ 'the-mind-testimonials-style' ];
	}

	protected function register_controls() {

		// Content Section
		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'Testimonials', 'the-mind-testimonials' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

        $this->add_control(
			'source_type',
			[
				'label' => esc_html__( 'Source Type', 'the-mind-testimonials' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'manual',
				'options' => [
					'manual' => esc_html__( 'Manual (Repeater)', 'the-mind-testimonials' ),
					'dynamic' => esc_html__( 'Dynamic (WP Comments/Reviews)', 'the-mind-testimonials' ),
				],
			]
		);
        
        $this->add_control(
			'dynamic_limit',
			[
				'label' => esc_html__( 'Limit', 'the-mind-testimonials' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'default' => 5,
                'condition' => [
                    'source_type' => 'dynamic',
                ],
			]
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'image',
			[
				'label' => esc_html__( 'Image', 'the-mind-testimonials' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
			]
		);

		$repeater->add_control(
			'content',
			[
				'label' => esc_html__( 'Testimonial', 'the-mind-testimonials' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'rows' => 4,
				'default' => esc_html__( 'Esto es un testimonio de ejemplo. El servicio fue increíble.', 'the-mind-testimonials' ),
			]
		);

        $repeater->add_control(
			'rating',
			[
				'label' => esc_html__( 'Rating', 'the-mind-testimonials' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 0,
				'max' => 5,
				'step' => 0.5,
				'default' => 5,
			]
		);

		$this->add_control(
			'testimonials',
			[
				'label' => esc_html__( 'Testimonials List', 'the-mind-testimonials' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'content' => esc_html__( 'Muy buen lugar para realizarse tatuajes, artistas super profesionales y la atención de todos super bien.', 'the-mind-testimonials' ),
                        'rating' => 5,
					],
                    [
						'content' => esc_html__( 'El lugar cómodo amplio y decoración agradable. Lo único es que en el centro está difícil encontrar estacionamiento.', 'the-mind-testimonials' ),
                        'rating' => 4,
					],
				],
				'title_field' => '{{{ content }}}',
                'condition' => [
                    'source_type' => 'manual',
                ],
			]
		);

        $this->add_responsive_control(
			'slides_per_view',
			[
				'label' => esc_html__( 'Slides Per View', 'the-mind-testimonials' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '1',
				'options' => [
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
				],
                'frontend_available' => true,
			]
		);

		$this->end_controls_section();

        // Button Section
        $this->start_controls_section(
			'button_section',
			[
				'label' => esc_html__( 'Button & Form', 'the-mind-testimonials' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

        $this->add_control(
			'button_text',
			[
				'label' => esc_html__( 'Button Text', 'the-mind-testimonials' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Comparte la tuya', 'the-mind-testimonials' ),
			]
		);

        $this->add_control(
			'button_link',
			[
				'label' => esc_html__( 'Link / Modal Trigger', 'the-mind-testimonials' ),
				'type' => \Elementor\Controls_Manager::URL,
				'placeholder' => esc_html__( 'https://your-link.com', 'the-mind-testimonials' ),
				'default' => [
					'url' => '#',
				],
                'description' => 'Use a link to a page or #elementor-action for popups if you are using Elementor Pro.',
			]
		);

         $this->add_control(
			'use_builtin_modal',
			[
				'label' => esc_html__( 'Use Built-in Modal', 'the-mind-testimonials' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'the-mind-testimonials' ),
				'label_off' => esc_html__( 'No', 'the-mind-testimonials' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

         $this->add_control(
			'modal_id',
			[
				'label' => esc_html__( 'Alternative: Modal ID', 'the-mind-testimonials' ),
				'type' => \Elementor\Controls_Manager::TEXT,
                'description' => 'If disabled above, enter ID for external modal (e.g., #my-modal).',
                'condition' => [
                    'use_builtin_modal!' => 'yes',
                ],
			]
		);

        $this->end_controls_section();

		// Style Section
		$this->start_controls_section(
			'style_section',
			[
				'label' => esc_html__( 'Style', 'the-mind-testimonials' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

        $this->add_control(
			'background_color',
			[
				'label' => esc_html__( 'Background Color', 'the-mind-testimonials' ),
				'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#000000',
				'selectors' => [
					'{{WRAPPER}} .tm-testimonials-container' => 'background-color: {{VALUE}};',
				],
			]
		);

        $this->add_responsive_control(
			'container_padding',
			[
				'label' => esc_html__( 'Container Padding', 'the-mind-testimonials' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .tm-testimonials-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'text_color',
			[
				'label' => esc_html__( 'Text Color', 'the-mind-testimonials' ),
				'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .tm-testimonial-content' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'content_typography',
				'selector' => '{{WRAPPER}} .tm-testimonial-content',
			]
		);

        $this->add_responsive_control(
			'content_max_width',
			[
				'label' => esc_html__( 'Content Max Width', 'the-mind-testimonials' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'vw' ],
				'range' => [
					'px' => [
						'min' => 200,
						'max' => 1200,
					],
					'%' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .tm-testimonial-slide' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

        $this->add_control(
			'star_color',
			[
				'label' => esc_html__( 'Star Color', 'the-mind-testimonials' ),
				'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .tm-testimonial-rating i' => 'color: {{VALUE}};',
				],
			]
		);

        $this->add_responsive_control(
			'star_size',
			[
				'label' => esc_html__( 'Star Size', 'the-mind-testimonials' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .tm-testimonial-rating i' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

        // Arrows Style
        $this->add_control(
			'arrow_heading',
			[
				'label' => esc_html__( 'Arrows', 'the-mind-testimonials' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

        $this->add_control(
			'arrow_color',
			[
				'label' => esc_html__( 'Arrow Color', 'the-mind-testimonials' ),
				'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#000000',
				'selectors' => [
					'{{WRAPPER}} .tm-testimonial-arrow' => 'color: {{VALUE}};',
				],
			]
		);

        $this->add_control(
			'arrow_bg_color',
			[
				'label' => esc_html__( 'Arrow Background', 'the-mind-testimonials' ),
				'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .tm-testimonial-arrow' => 'background-color: {{VALUE}};',
				],
			]
		);

        $this->add_responsive_control(
			'arrow_size',
			[
				'label' => esc_html__( 'Arrow Size', 'the-mind-testimonials' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 20,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .tm-testimonial-arrow' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tm-testimonial-arrow i' => 'font-size: calc({{SIZE}}{{UNIT}} / 2.5);',
				],
			]
		);

        $this->add_responsive_control(
			'arrow_vertical_position',
			[
				'label' => esc_html__( 'Vertical Position (%)', 'the-mind-testimonials' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ '%' ],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 50,
				],
				'selectors' => [
					'{{WRAPPER}} .tm-testimonial-arrow' => 'top: {{SIZE}}{{UNIT}};',
				],
			]
		);

        $this->add_responsive_control(
			'arrow_horizontal_position',
			[
				'label' => esc_html__( 'Horizontal Separation', 'the-mind-testimonials' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => -200,
						'max' => 200,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .tm-arrow-prev' => 'left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tm-arrow-next' => 'right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

        // Button Style Section
        $this->start_controls_section(
			'button_style_section',
			[
				'label' => esc_html__( 'Button Style', 'the-mind-testimonials' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

        $this->add_control(
			'button_color',
			[
				'label' => esc_html__( 'Button Color', 'the-mind-testimonials' ),
				'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .tm-testimonial-btn' => 'background-color: {{VALUE}};',
				],
			]
		);

        $this->add_control(
			'button_text_color',
			[
				'label' => esc_html__( 'Button Text Color', 'the-mind-testimonials' ),
				'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#000000',
				'selectors' => [
					'{{WRAPPER}} .tm-testimonial-btn' => 'color: {{VALUE}};',
				],
			]
		);

        $this->add_control(
			'button_hover_color',
			[
				'label' => esc_html__( 'Button Hover Color', 'the-mind-testimonials' ),
				'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#cccccc',
				'selectors' => [
					'{{WRAPPER}} .tm-testimonial-btn:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

        $this->add_control(
			'button_hover_text_color',
			[
				'label' => esc_html__( 'Button Hover Text Color', 'the-mind-testimonials' ),
				'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#000000',
				'selectors' => [
					'{{WRAPPER}} .tm-testimonial-btn:hover' => 'color: {{VALUE}};',
				],
			]
		);

        $this->add_control(
			'button_rounded',
			[
				'label' => esc_html__( 'Rounded Corners', 'the-mind-testimonials' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'the-mind-testimonials' ),
				'label_off' => esc_html__( 'No', 'the-mind-testimonials' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

        $this->add_responsive_control(
			'button_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'the-mind-testimonials' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 30,
				],
				'selectors' => [
					'{{WRAPPER}} .tm-testimonial-btn' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
                'condition' => [
                    'button_rounded' => 'yes',
                ],
			]
		);

        $this->add_responsive_control(
			'button_padding',
			[
				'label' => esc_html__( 'Padding', 'the-mind-testimonials' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'default' => [
					'top' => 15,
					'right' => 30,
					'bottom' => 15,
					'left' => 30,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .tm-testimonial-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'button_typography',
				'selector' => '{{WRAPPER}} .tm-testimonial-btn',
			]
		);

        $this->end_controls_section();

        // Modal Style Section
        $this->start_controls_section(
			'modal_style_section',
			[
				'label' => esc_html__( 'Modal Style', 'the-mind-testimonials' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'use_builtin_modal' => 'yes',
                ],
			]
		);

        $this->add_control(
			'modal_bg_color',
			[
				'label' => esc_html__( 'Modal Background', 'the-mind-testimonials' ),
				'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .tm-modal-container' => 'background-color: {{VALUE}};',
				],
			]
		);

        $this->add_control(
			'modal_overlay_color',
			[
				'label' => esc_html__( 'Overlay Color', 'the-mind-testimonials' ),
				'type' => \Elementor\Controls_Manager::COLOR,
                'default' => 'rgba(0, 0, 0, 0.85)',
				'selectors' => [
					'{{WRAPPER}} .tm-modal-overlay' => 'background-color: {{VALUE}};',
				],
			]
		);

        $this->add_control(
			'modal_title_color',
			[
				'label' => esc_html__( 'Title Color', 'the-mind-testimonials' ),
				'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#000000',
				'selectors' => [
					'{{WRAPPER}} .tm-modal-container h3' => 'color: {{VALUE}} !important;',
				],
			]
		);

        $this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'modal_title_typography',
				'selector' => '{{WRAPPER}} .tm-modal-container h3',
			]
		);

        $this->add_control(
			'modal_close_color',
			[
				'label' => esc_html__( 'Close Button Color', 'the-mind-testimonials' ),
				'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .tm-modal-close' => 'color: {{VALUE}};',
				],
			]
		);

        $this->add_control(
			'modal_form_heading',
			[
				'label' => esc_html__( 'Form Fields', 'the-mind-testimonials' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

        $this->add_control(
			'modal_label_color',
			[
				'label' => esc_html__( 'Label Color', 'the-mind-testimonials' ),
				'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .tm-modal-container .tm-form-group label' => 'color: {{VALUE}};',
				],
			]
		);

        $this->add_control(
			'modal_form_star_color',
			[
				'label' => esc_html__( 'Form Star Color', 'the-mind-testimonials' ),
				'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffcc00',
				'selectors' => [
					'{{WRAPPER}} .tm-rating-inputs input:checked ~ label, {{WRAPPER}} .tm-rating-inputs label:hover, {{WRAPPER}} .tm-rating-inputs label:hover ~ label' => 'color: {{VALUE}} !important;',
				],
			]
		);

        $this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'modal_label_typography',
				'selector' => '{{WRAPPER}} .tm-modal-container .tm-form-group label',
			]
		);

        $this->add_control(
			'modal_input_bg_color',
			[
				'label' => esc_html__( 'Input Background', 'the-mind-testimonials' ),
				'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#f5f5f5',
				'selectors' => [
					'{{WRAPPER}} .tm-modal-container .tm-form-group input, {{WRAPPER}} .tm-modal-container .tm-form-group textarea' => 'background-color: {{VALUE}};',
				],
			]
		);

        $this->add_control(
			'modal_input_text_color',
			[
				'label' => esc_html__( 'Input Text Color', 'the-mind-testimonials' ),
				'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .tm-modal-container .tm-form-group input, {{WRAPPER}} .tm-modal-container .tm-form-group textarea' => 'color: {{VALUE}};',
				],
			]
		);

        $this->add_control(
			'modal_submit_heading',
			[
				'label' => esc_html__( 'Submit Button', 'the-mind-testimonials' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

        $this->add_control(
			'modal_submit_bg_color',
			[
				'label' => esc_html__( 'Button Background', 'the-mind-testimonials' ),
				'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#000000',
				'selectors' => [
					'{{WRAPPER}} .tm-modal-container .tm-submit-btn' => 'background-color: {{VALUE}};',
				],
			]
		);

        $this->add_control(
			'modal_submit_text_color',
			[
				'label' => esc_html__( 'Button Text Color', 'the-mind-testimonials' ),
				'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .tm-modal-container .tm-submit-btn' => 'color: {{VALUE}};',
				],
			]
		);

        $this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'modal_submit_typography',
				'selector' => '{{WRAPPER}} .tm-modal-container .tm-submit-btn',
			]
		);

        $this->end_controls_section();

	}

	protected function render() {
		$settings = $this->get_settings_for_display();
        $use_modal = 'yes' === $settings['use_builtin_modal'];

        $modal_trigger = '';
        if ( $use_modal ) {
            $modal_trigger = 'class="tm-testimonial-btn tm-trigger-modal"';
        } elseif ( ! empty( $settings['modal_id'] ) ) {
            $modal_trigger = 'class="tm-testimonial-btn" data-modal-target="' . esc_attr( $settings['modal_id'] ) . '"';
        } else {
             $this->add_render_attribute( 'button', 'class', 'tm-testimonial-btn' );
             if ( ! empty( $settings['button_link']['url'] ) ) {
                $this->add_link_attributes( 'button', $settings['button_link'] );
            }
        }
        
        $testimonials_data = [];
        
        if ( 'dynamic' === $settings['source_type'] ) {
            // Fetch Comments
            $args = [
                'status' => 'approve',
                'number' => $settings['dynamic_limit'],
                // 'type' => 'review', // Optional: filter by review type if using only custom reviews
            ];
            $comments = get_comments( $args );
            
            foreach ( $comments as $comment ) {
                $rating = get_comment_meta( $comment->comment_ID, 'rating', true );
                if ( ! $rating ) $rating = 5; // Default if no rating
                
                $testimonials_data[] = [
                    'content' => $comment->comment_content,
                    'image' => [ 'url' => get_avatar_url( $comment->comment_author_email ) ],
                    'rating' => $rating,
                ];
            }
        } else {
            $testimonials_data = $settings['testimonials'];
        }

        // Pass settings to JS via data attributes
        $slides_to_show = $settings['slides_per_view'] ? $settings['slides_per_view'] : 1;
        $slides_to_show_tablet = $settings['slides_per_view_tablet'] ? $settings['slides_per_view_tablet'] : $slides_to_show;
        $slides_to_show_mobile = $settings['slides_per_view_mobile'] ? $settings['slides_per_view_mobile'] : 1;

		?>
		<div class="tm-testimonials-container">
            <div class="tm-testimonials-swiper swiper swiper-container" 
                data-slide-count="<?php echo count( $testimonials_data ); ?>"
                data-slides-per-view="<?php echo esc_attr( $slides_to_show ); ?>"
                data-slides-per-view-tablet="<?php echo esc_attr( $slides_to_show_tablet ); ?>"
                data-slides-per-view-mobile="<?php echo esc_attr( $slides_to_show_mobile ); ?>">
                <div class="swiper-wrapper">
                    <?php if ( ! empty( $testimonials_data ) ) : ?>
                        <?php foreach ( $testimonials_data as $testimonial ) : ?>
                            <div class="swiper-slide">
                                <div class="tm-testimonial-slide">
                                    <?php if ( ! empty( $testimonial['image']['url'] ) ) : ?>
                                        <div class="tm-testimonial-image">
                                            <img src="<?php echo esc_url( $testimonial['image']['url'] ); ?>" alt="User">
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="tm-testimonial-content">
                                        <?php echo wp_kses_post( $testimonial['content'] ); ?>
                                    </div>
                                    
                                    <div class="tm-testimonial-rating">
                                        <?php
                                        $rating = floatval( $testimonial['rating'] );
                                        for ( $i = 1; $i <= 5; $i++ ) {
                                            if ( $i <= $rating ) {
                                                echo '<i class="fas fa-star"></i>'; // Full star
                                            } elseif ( $i - 0.5 <= $rating ) {
                                                echo '<i class="fas fa-star-half-alt"></i>'; // Half star
                                            } else {
                                                echo '<i class="far fa-star"></i>'; // Empty star
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <div class="swiper-slide"><p><?php esc_html_e( 'No testimonials found.', 'the-mind-testimonials' ); ?></p></div>
                    <?php endif; ?>
                </div>
                
                <!-- Add Arrows -->
                <div class="swiper-button-next tm-testimonial-arrow tm-arrow-next"><i class="fas fa-chevron-right"></i></div>
                <div class="swiper-button-prev tm-testimonial-arrow tm-arrow-prev"><i class="fas fa-chevron-left"></i></div>

            </div>

             <div class="tm-testimonial-button-wrapper">
                <?php if ( $use_modal ) : ?>
                    <button type="button" <?php echo $modal_trigger; ?>>
                        <?php echo esc_html( $settings['button_text'] ); ?>
                        <i class="fas fa-arrow-up" style="transform: rotate(45deg); margin-left: 5px;"></i>
                    </button>
                    
                    <!-- Built-in Modal -->
                    <div class="tm-modal-overlay" style="display: none;">
                        <div class="tm-modal-container">
                            <span class="tm-modal-close">&times;</span>
                            <h3 style="color: #000; text-align: center; margin-bottom: 20px;"><?php esc_html_e( 'Comparte tu opinión', 'the-mind-testimonials' ); ?></h3>
                            <?php echo do_shortcode( '[tm_testimonial_form]' ); ?>
                        </div>
                    </div>

                <?php else : ?>
                    <a <?php echo $this->get_render_attribute_string( 'button' ); ?> <?php echo $modal_trigger; ?>>
                        <?php echo esc_html( $settings['button_text'] ); ?>
                        <i class="fas fa-arrow-up" style="transform: rotate(45deg); margin-left: 5px;"></i>
                    </a>
                <?php endif; ?>
            </div>
		</div>
		<?php
	}
}
