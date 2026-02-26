<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class The_Mind_Events_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'the_mind_events';
	}

	public function get_title() {
		return esc_html__( 'The Mind Events Slider', 'the-mind-events' );
	}

	public function get_icon() {
		return 'eicon-post-slider';
	}

	public function get_categories() {
		return [ 'general' ];
	}

    public function get_script_depends() {
		return [ 'the-mind-events-script', 'swiper' ];
	}

    public function get_style_depends() {
		return [ 'the-mind-events-style' ];
	}

	protected function register_controls() {

		// Content Section
		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'Settings', 'the-mind-events' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

        $this->add_control(
			'posts_per_page',
			[
				'label' => esc_html__( 'Limit', 'the-mind-events' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'default' => 6,
			]
		);

        $this->add_responsive_control(
			'slides_per_view',
			[
				'label' => esc_html__( 'Slides Per View', 'the-mind-events' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '1',
				'options' => [
					'1' => '1',
					'2' => '2',
					'3' => '3',
				],
                'frontend_available' => true,
			]
		);

        $this->add_control(
			'button_text',
			[
				'label' => esc_html__( 'Button Text', 'the-mind-events' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Más info...', 'the-mind-events' ),
			]
		);

		$image_sizes = get_intermediate_image_sizes();
		$image_size_options = [ 'full' => esc_html__( 'Full', 'the-mind-events' ) ];
		foreach ( $image_sizes as $size ) {
			$image_size_options[ $size ] = ucfirst( str_replace( '-', ' ', $size ) );
		}

		$this->add_control(
			'image_size',
			[
				'label' => esc_html__( 'Image Size', 'the-mind-events' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'large',
				'options' => $image_size_options,
			]
		);

		$this->end_controls_section();

		// Style Section - Slider Container & Image
		$this->start_controls_section(
			'slider_style_section',
			[
				'label' => esc_html__( 'Slider & Image', 'the-mind-events' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

        $this->add_responsive_control(
			'container_border_radius',
			[
				'label' => esc_html__( 'Container Border Radius', 'the-mind-events' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .tm-events-slider-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->add_responsive_control(
			'image_border_radius',
			[
				'label' => esc_html__( 'Image Border Radius', 'the-mind-events' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .tm-event-slide' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; border: 0px solid transparent; overflow: hidden;',
				],
			]
		);

		$this->end_controls_section();

		// Style Section - Arrows
		$this->start_controls_section(
			'arrows_style_section',
			[
				'label' => esc_html__( 'Arrows', 'the-mind-events' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

        $this->add_control(
			'arrow_color',
			[
				'label' => esc_html__( 'Color', 'the-mind-events' ),
				'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#000000',
				'selectors' => [
					'{{WRAPPER}} .tm-event-arrow' => 'color: {{VALUE}};',
				],
			]
		);

        $this->add_control(
			'arrow_bg_color',
			[
				'label' => esc_html__( 'Background', 'the-mind-events' ),
				'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .tm-event-arrow' => 'background-color: {{VALUE}};',
				],
			]
		);

        $this->add_responsive_control(
			'arrow_size',
			[
				'label' => esc_html__( 'Size', 'the-mind-events' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [ 'min' => 20, 'max' => 100 ],
				],
				'selectors' => [
					'{{WRAPPER}} .tm-event-arrow' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tm-event-arrow i' => 'font-size: calc({{SIZE}}{{UNIT}} / 2.5);',
				],
			]
		);

        $this->add_responsive_control(
			'arrow_horizontal_pos',
			[
				'label' => esc_html__( 'Horizontal Position', 'the-mind-events' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [ 'min' => 0, 'max' => 100 ],
				],
				'selectors' => [
					'{{WRAPPER}} .tm-event-prev' => 'left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tm-event-next' => 'right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

        // Style Section - Button
        $this->start_controls_section(
			'button_style_section',
			[
				'label' => esc_html__( 'Button', 'the-mind-events' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

        $this->add_control(
			'btn_color',
			[
				'label' => esc_html__( 'Background Color', 'the-mind-events' ),
				'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .tm-event-btn' => 'background-color: {{VALUE}};',
				],
			]
		);

        $this->add_control(
			'btn_text_color',
			[
				'label' => esc_html__( 'Text Color', 'the-mind-events' ),
				'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#000000',
				'selectors' => [
					'{{WRAPPER}} .tm-event-btn' => 'color: {{VALUE}};',
				],
			]
		);

        $this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'btn_typography',
				'selector' => '{{WRAPPER}} .tm-event-btn',
			]
		);

        $this->add_responsive_control(
			'btn_padding',
			[
				'label' => esc_html__( 'Padding', 'the-mind-events' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .tm-event-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->add_responsive_control(
			'btn_position_top',
			[
				'label' => esc_html__( 'Vertical Position (%)', 'the-mind-events' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'default' => [ 'unit' => '%', 'size' => 20 ],
				'selectors' => [
					'{{WRAPPER}} .tm-event-btn-wrapper' => 'top: {{SIZE}}{{UNIT}};',
				],
			]
		);

        $this->add_responsive_control(
			'btn_position_left',
			[
				'label' => esc_html__( 'Horizontal Position (%)', 'the-mind-events' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'default' => [ 'unit' => '%', 'size' => 20 ],
				'selectors' => [
					'{{WRAPPER}} .tm-event-btn-wrapper' => 'left: {{SIZE}}{{UNIT}};',
				],
			]
		);

        $this->end_controls_section();

        // Style Section - Modal
        $this->start_controls_section(
			'modal_style_section',
			[
				'label' => esc_html__( 'Modal', 'the-mind-events' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

        $this->add_control(
			'modal_bg',
			[
				'label' => esc_html__( 'Background Color', 'the-mind-events' ),
				'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .tm-event-modal-content' => 'background-color: {{VALUE}};',
				],
			]
		);

        $this->add_control(
			'modal_title_color',
			[
				'label' => esc_html__( 'Title Color', 'the-mind-events' ),
				'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#000000',
				'selectors' => [
					'{{WRAPPER}} .tm-event-modal-content h3' => 'color: {{VALUE}};',
				],
			]
		);

        $this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'modal_title_typography',
				'selector' => '{{WRAPPER}} .tm-event-modal-content h3',
			]
		);

        $this->add_control(
			'modal_text_color',
			[
				'label' => esc_html__( 'Text Color', 'the-mind-events' ),
				'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .tm-event-modal-description' => 'color: {{VALUE}};',
				],
			]
		);

        $this->add_control(
			'modal_date_color',
			[
				'label' => esc_html__( 'Date Color', 'the-mind-events' ),
				'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#777777',
				'selectors' => [
					'{{WRAPPER}} .tm-event-modal-date' => 'color: {{VALUE}};',
				],
			]
		);

        $this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'modal_date_typography',
				'selector' => '{{WRAPPER}} .tm-event-modal-date',
			]
		);

        $this->end_controls_section();

	}

	protected function render() {
		$settings = $this->get_settings_for_display();

        $args = [
            'post_type' => 'tm_event',
            'posts_per_page' => $settings['posts_per_page'],
        ];

        $query = new \WP_Query( $args );

        $slides_to_show = $settings['slides_per_view'] ? $settings['slides_per_view'] : 1;
        $slides_to_show_tablet = $settings['slides_per_view_tablet'] ? $settings['slides_per_view_tablet'] : $slides_to_show;
        $slides_to_show_mobile = $settings['slides_per_view_mobile'] ? $settings['slides_per_view_mobile'] : 1;
		$image_size = ! empty( $settings['image_size'] ) ? $settings['image_size'] : 'large';
		$total_slides = (int) $query->post_count;

		?>
		<div class="tm-events-slider-container">
            <div class="tm-events-swiper swiper" 
                data-slides-per-view="<?php echo esc_attr( $slides_to_show ); ?>"
                data-slides-per-view-tablet="<?php echo esc_attr( $slides_to_show_tablet ); ?>"
                data-slides-per-view-mobile="<?php echo esc_attr( $slides_to_show_mobile ); ?>"
                data-total-slides="<?php echo esc_attr( $total_slides ); ?>">
                <div class="swiper-wrapper">
                    <?php if ( $query->have_posts() ) : ?>
                        <?php while ( $query->have_posts() ) : $query->the_post(); 
                                $event_date = get_post_meta( get_the_ID(), '_tm_event_date', true );
                                if ( $event_date ) {
                                    $event_date = date_i18n( get_option( 'date_format' ), strtotime( $event_date ) );
                                }
								$thumbnail_id = get_post_thumbnail_id();
								$event_content = apply_filters( 'the_content', get_the_content() );
                        ?>
                            <div class="swiper-slide">
                                <div class="tm-event-slide">
                                    <div class="tm-event-image">
                                        <?php if ( $thumbnail_id ) : ?>
                                            <?php echo wp_get_attachment_image( $thumbnail_id, $image_size, false, [ 'loading' => 'lazy', 'decoding' => 'async' ] ); ?>
                                        <?php else : ?>
                                            <img src="<?php echo esc_url( \Elementor\Utils::get_placeholder_image_src() ); ?>" alt="Placeholder" loading="lazy" decoding="async">
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="tm-event-btn-wrapper">
                                        <button type="button" class="tm-event-btn tm-open-modal" 
                                                data-title="<?php echo esc_attr( get_the_title() ); ?>"
                                                data-date="<?php echo esc_attr( $event_date ); ?>"
                                                data-event-id="<?php echo esc_attr( get_the_ID() ); ?>">
                                            <?php echo esc_html( $settings['button_text'] ); ?>
                                            <span class="tm-btn-icon"><i class="fas fa-arrow-up" style="transform: rotate(45deg);"></i></span>
                                        </button>
                                    </div>
									<div class="tm-event-content-source" data-event-id="<?php echo esc_attr( get_the_ID() ); ?>" style="display: none;">
										<?php echo wp_kses_post( $event_content ); ?>
									</div>
                                </div>
                            </div>
                        <?php endwhile; wp_reset_postdata(); ?>
                    <?php else : ?>
                        <div class="swiper-slide"><p><?php esc_html_e( 'No events found.', 'the-mind-events' ); ?></p></div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Arrows: fuera del .swiper para evitar conflictos con el layout interno de Swiper -->
            <div class="swiper-button-prev tm-event-arrow tm-event-prev"><i class="fas fa-chevron-left"></i></div>
            <div class="swiper-button-next tm-event-arrow tm-event-next"><i class="fas fa-chevron-right"></i></div>

            <!-- Global Modal for the Widget Instance -->
            <div class="tm-event-modal-overlay" style="display: none;">
                <div class="tm-event-modal-container">
                    <span class="tm-event-modal-close">&times;</span>
                    <div class="tm-event-modal-content">
                        <!-- Dynamic Content via JS -->
                        <h3 class="tm-event-modal-title"></h3>
                        <div class="tm-event-modal-date" style="margin-bottom: 15px; font-weight: bold;"></div>
                        <div class="tm-event-modal-description"></div>
                    </div>
                </div>
            </div>
		</div>
		<?php
	}
}

