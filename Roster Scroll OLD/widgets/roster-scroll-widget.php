<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Elementor Widget: Roster Scroll Parallax v2
 * – Repeater de imágenes + nombre/etiqueta gestionable desde el editor
 * – Controles completos de tipografía, color, tamaño, padding/margin
 * – Optimizado para Safari iOS (lerp + rAF + GPU layers)
 */
class Roster_Scroll_Widget extends \Elementor\Widget_Base {

    public function get_name()       { return 'roster_scroll'; }
    public function get_title()      { return esc_html__( 'Roster Scroll Parallax', 'plugin-roster-scroll' ); }
    public function get_icon()       { return 'eicon-gallery-grid'; }
    public function get_categories() { return [ 'general' ]; }
    public function get_keywords()   { return [ 'roster', 'parallax', 'scroll', 'galería', 'talentos' ]; }

    // =========================================================================
    //  CONTROLS
    // =========================================================================
    protected function register_controls() {

        // ── CONTENT ▸ GENERAL ────────────────────────────────────────────────
        $this->start_controls_section( 'section_general', [
            'label' => esc_html__( 'General', 'plugin-roster-scroll' ),
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
        ] );

        $this->add_control( 'marquee_text', [
            'label'       => esc_html__( 'Texto Marquesina', 'plugin-roster-scroll' ),
            'type'        => \Elementor\Controls_Manager::TEXT,
            'default'     => 'ROSTER',
            'placeholder' => 'ROSTER',
            'label_block' => true,
        ] );

        $this->add_control( 'show_subtitle', [
            'label'        => esc_html__( 'Mostrar Subtítulo', 'plugin-roster-scroll' ),
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'label_on'     => esc_html__( 'Sí', 'plugin-roster-scroll' ),
            'label_off'    => esc_html__( 'No', 'plugin-roster-scroll' ),
            'return_value' => 'yes',
            'default'      => 'no',
        ] );

        $this->add_control( 'subtitle_text', [
            'label'       => esc_html__( 'Subtítulo', 'plugin-roster-scroll' ),
            'type'        => \Elementor\Controls_Manager::TEXT,
            'default'     => 'Talentos y Figuras Públicas',
            'label_block' => true,
            'condition'   => [ 'show_subtitle' => 'yes' ],
        ] );

        $this->add_control( 'scroll_height', [
            'label'       => esc_html__( 'Altura de Scroll (vh)', 'plugin-roster-scroll' ),
            'type'        => \Elementor\Controls_Manager::NUMBER,
            'min'         => 150,
            'max'         => 800,
            'step'        => 25,
            'default'     => 500,
            'description' => esc_html__( '500 = 5 alturas de pantalla de scroll. En móvil se reduce automáticamente.', 'plugin-roster-scroll' ),
        ] );

        $this->end_controls_section();

        // ── CONTENT ▸ ROSTER (Repeater) ──────────────────────────────────────
        $this->start_controls_section( 'section_roster', [
            'label' => esc_html__( 'Imágenes del Roster', 'plugin-roster-scroll' ),
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
        ] );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control( 'item_image', [
            'label'   => esc_html__( 'Imagen', 'plugin-roster-scroll' ),
            'type'    => \Elementor\Controls_Manager::MEDIA,
            'default' => [ 'url' => \Elementor\Utils::get_placeholder_image_src() ],
        ] );

        $repeater->add_control( 'item_name', [
            'label'       => esc_html__( 'Nombre / Etiqueta', 'plugin-roster-scroll' ),
            'type'        => \Elementor\Controls_Manager::TEXT,
            'default'     => 'Nombre del Talento',
            'label_block' => true,
        ] );

        $repeater->add_control( 'item_label_bg', [
            'label'   => esc_html__( 'Color de fondo de etiqueta', 'plugin-roster-scroll' ),
            'type'    => \Elementor\Controls_Manager::COLOR,
            'default' => '',
            'description' => esc_html__( 'Deja vacío para usar el color global.', 'plugin-roster-scroll' ),
        ] );

        $repeater->add_control( 'item_label_color', [
            'label'   => esc_html__( 'Color de texto de etiqueta', 'plugin-roster-scroll' ),
            'type'    => \Elementor\Controls_Manager::COLOR,
            'default' => '',
            'description' => esc_html__( 'Deja vacío para usar el color global.', 'plugin-roster-scroll' ),
        ] );

        $this->add_control( 'roster_items', [
            'label'       => esc_html__( 'Agregar Personas', 'plugin-roster-scroll' ),
            'type'        => \Elementor\Controls_Manager::REPEATER,
            'fields'      => $repeater->get_controls(),
            'default'     => [
                [ 'item_name' => 'Talento 1' ],
                [ 'item_name' => 'Talento 2' ],
                [ 'item_name' => 'Talento 3' ],
                [ 'item_name' => 'Talento 4' ],
                [ 'item_name' => 'Talento 5' ],
                [ 'item_name' => 'Talento 6' ],
            ],
            'title_field' => '{{{ item_name }}}',
        ] );

        $this->end_controls_section();

        // ── STYLE ▸ FONDO ────────────────────────────────────────────────────
        $this->start_controls_section( 'style_background', [
            'label' => esc_html__( 'Fondo', 'plugin-roster-scroll' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ] );

        $this->add_control( 'background_color', [
            'label'     => esc_html__( 'Color de Fondo', 'plugin-roster-scroll' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => 'transparent',
            'selectors' => [ '{{WRAPPER}} .rsp-root' => 'background-color: {{VALUE}};' ],
        ] );

        $this->end_controls_section();

        // ── STYLE ▸ MARQUEE ──────────────────────────────────────────────────
        $this->start_controls_section( 'style_marquee', [
            'label' => esc_html__( 'Texto Marquesina', 'plugin-roster-scroll' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ] );

        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [
            'name'     => 'marquee_typography',
            'label'    => esc_html__( 'Tipografía', 'plugin-roster-scroll' ),
            'selector' => '{{WRAPPER}} .rsp-marquee-h2',
        ] );

        $this->add_control( 'marquee_color', [
            'label'     => esc_html__( 'Color', 'plugin-roster-scroll' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => '#000000',
            'selectors' => [ '{{WRAPPER}} .rsp-marquee-h2' => 'color: {{VALUE}};' ],
        ] );

        $this->add_control( 'marquee_opacity', [
            'label'   => esc_html__( 'Opacidad', 'plugin-roster-scroll' ),
            'type'    => \Elementor\Controls_Manager::SLIDER,
            'range'   => [ 'px' => [ 'min' => 0, 'max' => 1, 'step' => 0.05 ] ],
            'default' => [ 'size' => 0.3 ],
            'selectors' => [ '{{WRAPPER}} .rsp-marquee-h2' => 'opacity: {{SIZE}};' ],
        ] );

        $this->add_control( 'marquee_blend_mode', [
            'label'     => esc_html__( 'Modo de Mezcla (Blend Mode)', 'plugin-roster-scroll' ),
            'type'      => \Elementor\Controls_Manager::SELECT,
            'default'   => 'normal',
            'options'   => [
                'normal'     => 'Normal',
                'multiply'   => 'Multiply',
                'screen'     => 'Screen',
                'overlay'    => 'Overlay',
                'difference' => 'Difference',
                'exclusion'  => 'Exclusion',
            ],
            'selectors' => [ '{{WRAPPER}} .rsp-marquee-h2' => 'mix-blend-mode: {{VALUE}};' ],
        ] );

        $this->add_responsive_control( 'marquee_padding', [
            'label'      => esc_html__( 'Padding', 'plugin-roster-scroll' ),
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', 'em', '%' ],
            'selectors'  => [ '{{WRAPPER}} .rsp-marquee-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
        ] );

        $this->add_responsive_control( 'marquee_margin', [
            'label'      => esc_html__( 'Margen', 'plugin-roster-scroll' ),
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', 'em', '%' ],
            'selectors'  => [ '{{WRAPPER}} .rsp-marquee-text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
        ] );

        $this->end_controls_section();

        // ── STYLE ▸ SUBTÍTULO ────────────────────────────────────────────────
        $this->start_controls_section( 'style_subtitle', [
            'label'     => esc_html__( 'Subtítulo', 'plugin-roster-scroll' ),
            'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
            'condition' => [ 'show_subtitle' => 'yes' ],
        ] );

        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [
            'name'     => 'subtitle_typography',
            'label'    => esc_html__( 'Tipografía', 'plugin-roster-scroll' ),
            'selector' => '{{WRAPPER}} .rsp-subtitle',
        ] );

        $this->add_control( 'subtitle_color', [
            'label'     => esc_html__( 'Color', 'plugin-roster-scroll' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => '#000000',
            'selectors' => [ '{{WRAPPER}} .rsp-subtitle' => 'color: {{VALUE}};' ],
        ] );

        $this->add_control( 'subtitle_align', [
            'label'     => esc_html__( 'Alineación', 'plugin-roster-scroll' ),
            'type'      => \Elementor\Controls_Manager::CHOOSE,
            'options'   => [
                'left'   => [ 'title' => esc_html__( 'Izquierda', 'plugin-roster-scroll' ), 'icon' => 'eicon-text-align-left' ],
                'center' => [ 'title' => esc_html__( 'Centro', 'plugin-roster-scroll' ),    'icon' => 'eicon-text-align-center' ],
                'right'  => [ 'title' => esc_html__( 'Derecha', 'plugin-roster-scroll' ),   'icon' => 'eicon-text-align-right' ],
            ],
            'default'   => 'center',
            'selectors' => [ '{{WRAPPER}} .rsp-subtitle' => 'text-align: {{VALUE}};' ],
        ] );

        $this->add_responsive_control( 'subtitle_padding', [
            'label'      => esc_html__( 'Padding', 'plugin-roster-scroll' ),
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', 'em', '%' ],
            'selectors'  => [ '{{WRAPPER}} .rsp-subtitle' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
        ] );

        $this->add_responsive_control( 'subtitle_margin', [
            'label'      => esc_html__( 'Margen', 'plugin-roster-scroll' ),
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', 'em', '%' ],
            'selectors'  => [ '{{WRAPPER}} .rsp-subtitle' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
        ] );

        $this->end_controls_section();

        // ── STYLE ▸ IMÁGENES ─────────────────────────────────────────────────
        $this->start_controls_section( 'style_images', [
            'label' => esc_html__( 'Imágenes', 'plugin-roster-scroll' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ] );

        $this->add_responsive_control( 'image_width', [
            'label'          => esc_html__( 'Ancho de Imagen', 'plugin-roster-scroll' ),
            'type'           => \Elementor\Controls_Manager::SLIDER,
            'size_units'     => [ 'vw', '%', 'px' ],
            'range'          => [
                'vw' => [ 'min' => 8,   'max' => 60 ],
                '%'  => [ 'min' => 8,   'max' => 60 ],
                'px' => [ 'min' => 80, 'max' => 600 ],
            ],
            'default'        => [ 'unit' => 'vw', 'size' => 18 ],
            'tablet_default' => [ 'unit' => 'vw', 'size' => 25 ],
            'mobile_default' => [ 'unit' => 'vw', 'size' => 42 ],
            'selectors'      => [ '{{WRAPPER}} .rsp-card' => 'width: {{SIZE}}{{UNIT}} !important;' ],
        ] );

        $this->add_control( 'image_border_radius', [
            'label'      => esc_html__( 'Radio del Borde', 'plugin-roster-scroll' ),
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'px', '%' ],
            'range'      => [ 'px' => [ 'min' => 0, 'max' => 40 ] ],
            'default'    => [ 'unit' => 'px', 'size' => 2 ],
            'selectors'  => [ '{{WRAPPER}} .rsp-card-image-wrapper' => 'border-radius: {{SIZE}}{{UNIT}};' ],
        ] );

        $this->add_control( 'image_grayscale', [
            'label'        => esc_html__( 'Escala de Grises inicial', 'plugin-roster-scroll' ),
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'label_on'     => esc_html__( 'Sí', 'plugin-roster-scroll' ),
            'label_off'    => esc_html__( 'No', 'plugin-roster-scroll' ),
            'return_value' => 'yes',
            'default'      => 'yes',
        ] );

        $this->add_group_control( \Elementor\Group_Control_Box_Shadow::get_type(), [
            'name'     => 'image_shadow',
            'label'    => esc_html__( 'Sombra', 'plugin-roster-scroll' ),
            'selector' => '{{WRAPPER}} .rsp-card-image-wrapper',
        ] );

        $this->end_controls_section();

        // ── STYLE ▸ ETIQUETAS ────────────────────────────────────────────────
        $this->start_controls_section( 'style_labels', [
            'label' => esc_html__( 'Etiquetas (Nombres)', 'plugin-roster-scroll' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ] );

        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [
            'name'     => 'label_typography',
            'label'    => esc_html__( 'Tipografía', 'plugin-roster-scroll' ),
            'selector' => '{{WRAPPER}} .rsp-card-label',
        ] );

        $this->add_control( 'label_text_color', [
            'label'     => esc_html__( 'Color de Texto', 'plugin-roster-scroll' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => '#ffffff',
            'selectors' => [ '{{WRAPPER}} .rsp-card-label' => 'color: {{VALUE}};' ],
        ] );

        $this->add_control( 'label_bg_color', [
            'label'     => esc_html__( 'Color de Fondo', 'plugin-roster-scroll' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => '#18181b',
            'selectors' => [ '{{WRAPPER}} .rsp-card-label' => 'background-color: {{VALUE}};' ],
        ] );

        $this->add_responsive_control( 'label_padding', [
            'label'      => esc_html__( 'Padding', 'plugin-roster-scroll' ),
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', 'em' ],
            'default'    => [ 'top' => 8, 'right' => 20, 'bottom' => 8, 'left' => 20, 'unit' => 'px', 'isLinked' => false ],
            'selectors'  => [ '{{WRAPPER}} .rsp-card-label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
        ] );

        $this->add_control( 'label_border_radius', [
            'label'      => esc_html__( 'Radio del Borde (lados)', 'plugin-roster-scroll' ),
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'px', '%' ],
            'range'      => [ 'px' => [ 'min' => 0, 'max' => 99 ] ],
            'default'    => [ 'unit' => 'px', 'size' => 99 ],
            'selectors'  => [
                '{{WRAPPER}} .rsp-card-label' => 'border-top-left-radius: {{SIZE}}{{UNIT}}; border-bottom-left-radius: {{SIZE}}{{UNIT}};',
            ],
        ] );

        $this->add_responsive_control( 'label_offset_bottom', [
            'label'      => esc_html__( 'Posición vertical (bottom)', 'plugin-roster-scroll' ),
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'px', 'rem', 'em' ],
            'range'      => [ 'px' => [ 'min' => -40, 'max' => 40 ], 'rem' => [ 'min' => -4, 'max' => 4, 'step' => 0.25 ] ],
            'default'    => [ 'unit' => 'rem', 'size' => -1 ],
            'selectors'  => [ '{{WRAPPER}} .rsp-card-label' => 'bottom: {{SIZE}}{{UNIT}};' ],
        ] );

        $this->add_responsive_control( 'label_offset_left', [
            'label'      => esc_html__( 'Posición horizontal (left)', 'plugin-roster-scroll' ),
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'px', 'rem', 'em' ],
            'range'      => [ 'px' => [ 'min' => -40, 'max' => 40 ], 'rem' => [ 'min' => -4, 'max' => 4, 'step' => 0.25 ] ],
            'default'    => [ 'unit' => 'rem', 'size' => -1 ],
            'selectors'  => [ '{{WRAPPER}} .rsp-card-label' => 'left: {{SIZE}}{{UNIT}};' ],
        ] );

        $this->end_controls_section();
    }

    // =========================================================================
    //  RENDER
    // =========================================================================
    protected function render() {
        $settings       = $this->get_settings_for_display();
        $marquee_text   = $settings['marquee_text'];
        $subtitle_text  = $settings['subtitle_text'];
        $show_subtitle  = $settings['show_subtitle'];
        $scroll_height  = intval( $settings['scroll_height'] );
        $image_grayscale = $settings['image_grayscale'];
        $roster_items   = $settings['roster_items'];

        $widget_id = 'rsp-' . $this->get_id();

        // Predefined staggered positions (desktop x, y) y mobile x (2 columnas)
        $desktop_positions = [
            [ 'left' => '5%',  'y' => '10%', 'speed' => 0.8, 'z' => 20 ],
            [ 'left' => '75%', 'y' => '5%',  'speed' => 1.0, 'z' => 10 ],
            [ 'left' => '38%', 'y' => '50%', 'speed' => 0.6, 'z' => 5  ],
            [ 'left' => '8%',  'y' => '65%', 'speed' => 1.1, 'z' => 15 ],
            [ 'left' => '68%', 'y' => '55%', 'speed' => 1.2, 'z' => 20 ],
            [ 'left' => '42%', 'y' => '15%', 'speed' => 0.7, 'z' => 8  ],
            [ 'left' => '82%', 'y' => '70%', 'speed' => 0.9, 'z' => 12 ],
        ];
        $mobile_lefts = [ '5%', '55%', '5%', '55%', '5%', '55%', '5%' ];

        ?>
        <style>
            /* ── Root ──────────────────────────────────────────────────────── */
            #<?php echo esc_attr( $widget_id ); ?>.rsp-root {
                position: relative;
                height: <?php echo esc_attr( $scroll_height ); ?>vh;
                overflow: visible;
                margin: 0;
                padding: 0;
            }

            /* Altura reducida en móvil */
            @media (max-width: 768px) {
                #<?php echo esc_attr( $widget_id ); ?>.rsp-root {
                    height: <?php echo esc_attr( round( $scroll_height * 0.6 ) ); ?>vh;
                }
            }
            @media (max-width: 480px) {
                #<?php echo esc_attr( $widget_id ); ?>.rsp-root {
                    height: <?php echo esc_attr( round( $scroll_height * 0.5 ) ); ?>vh;
                }
            }

            /* ── Sticky wrapper ─────────────────────────────────────────────── */
            #<?php echo esc_attr( $widget_id ); ?> .rsp-sticky {
                position: -webkit-sticky;
                position: sticky;
                top: 0;
                height: 100vh;
                overflow: hidden;
                display: flex;
                align-items: center;
                justify-content: center;
                /* GPU layer propio → evita repaints */
                -webkit-transform: translateZ(0);
                transform: translateZ(0);
            }

            /* ── Marquee ────────────────────────────────────────────────────── */
            #<?php echo esc_attr( $widget_id ); ?> .rsp-marquee-text {
                position: absolute;
                white-space: nowrap;
                pointer-events: none;
                user-select: none;
                z-index: 10;
                /* Posición inicial: JS la mueve */
                -webkit-transform: translateX(40%);
                transform: translateX(40%);
            }

            #<?php echo esc_attr( $widget_id ); ?> .rsp-marquee-h2 {
                font-size: 42vw;
                font-weight: 900;
                text-transform: uppercase;
                margin: 0;
                line-height: 0.85;
                letter-spacing: -0.05em;
                opacity: 0.3;
            }

            @media (max-width: 768px) {
                #<?php echo esc_attr( $widget_id ); ?> .rsp-marquee-h2 { font-size: 65vw; }
            }
            @media (max-width: 480px) {
                #<?php echo esc_attr( $widget_id ); ?> .rsp-marquee-h2 { font-size: 80vw; }
            }

            /* will-change solo en desktop con hover */
            @media (min-width: 769px) and (hover: hover) {
                #<?php echo esc_attr( $widget_id ); ?> .rsp-marquee-text,
                #<?php echo esc_attr( $widget_id ); ?> .rsp-card {
                    will-change: transform;
                }
            }

            /* ── Subtítulo ──────────────────────────────────────────────────── */
            #<?php echo esc_attr( $widget_id ); ?> .rsp-subtitle {
                position: absolute;
                bottom: 8%;
                left: 50%;
                -webkit-transform: translateX(-50%);
                transform: translateX(-50%);
                z-index: 30;
                pointer-events: none;
                user-select: none;
                white-space: nowrap;
                font-weight: 600;
                letter-spacing: 0.15em;
                text-transform: uppercase;
                font-size: clamp(10px, 1.2vw, 16px);
            }

            /* ── Cards container ────────────────────────────────────────────── */
            #<?php echo esc_attr( $widget_id ); ?> .rsp-cards-container {
                position: absolute;
                inset: 0;
                width: 100%;
                height: 100%;
                pointer-events: none;
            }

            /* ── Card ───────────────────────────────────────────────────────── */
            #<?php echo esc_attr( $widget_id ); ?> .rsp-card {
                position: absolute;
                pointer-events: auto;
                width: 18vw;
                min-width: 150px;
                aspect-ratio: 3/4;
                -webkit-transform: translateY(0);
                transform: translateY(0);
            }

            @media (max-width: 1024px) {
                #<?php echo esc_attr( $widget_id ); ?> .rsp-card {
                    width: 25vw;
                    min-width: 130px;
                }
            }

            @media (max-width: 768px) {
                #<?php echo esc_attr( $widget_id ); ?> .rsp-card {
                    width: 42vw !important;
                    min-width: 0;
                }
            }

            /* ── Card image wrapper ─────────────────────────────────────────── */
            #<?php echo esc_attr( $widget_id ); ?> .rsp-card-image-wrapper {
                position: relative;
                width: 100%;
                height: 100%;
                overflow: hidden;
                border-radius: 2px;
                background: #18181b;
                box-shadow: 0 25px 50px -12px rgba(0,0,0,0.4);
                -webkit-transform: translateZ(0);
                transform: translateZ(0);
            }

            /* Hover zoom — solo dispositivos con mouse */
            @media (hover: hover) and (pointer: fine) {
                #<?php echo esc_attr( $widget_id ); ?> .rsp-card-image-wrapper {
                    transition: transform 0.7s ease-out;
                }
                #<?php echo esc_attr( $widget_id ); ?> .rsp-card:hover .rsp-card-image-wrapper {
                    transform: scale(1.05);
                }
            }

            /* ── Card image ─────────────────────────────────────────────────── */
            #<?php echo esc_attr( $widget_id ); ?> .rsp-card-image {
                width: 100%;
                height: 100%;
                object-fit: cover;
                display: block;
                <?php if ( $image_grayscale === 'yes' ) : ?>filter: grayscale(100%);<?php endif; ?>
            }

            /* Color al hover — solo mouse */
            @media (hover: hover) and (pointer: fine) {
                #<?php echo esc_attr( $widget_id ); ?> .rsp-card-image {
                    transition: filter 1s ease;
                }
                #<?php echo esc_attr( $widget_id ); ?> .rsp-card:hover .rsp-card-image {
                    filter: grayscale(0%);
                }
            }

            /* ── Label ──────────────────────────────────────────────────────── */
            #<?php echo esc_attr( $widget_id ); ?> .rsp-card-label {
                position: absolute;
                bottom: -1rem;
                left: -1rem;
                padding: 0.5rem 1.25rem;
                font-size: 9px;
                font-weight: 900;
                letter-spacing: 0.2em;
                text-transform: uppercase;
                background-color: #18181b;
                color: #ffffff;
                border-top-left-radius: 99px;
                border-bottom-left-radius: 99px;
                box-shadow: 0 8px 24px rgba(0,0,0,0.3);
                white-space: nowrap;
            }

            @media (hover: hover) and (pointer: fine) {
                #<?php echo esc_attr( $widget_id ); ?> .rsp-card-label {
                    transition: transform 0.3s ease;
                }
                #<?php echo esc_attr( $widget_id ); ?> .rsp-card:hover .rsp-card-label {
                    transform: translateY(-0.5rem);
                }
            }

            @media (max-width: 768px) {
                #<?php echo esc_attr( $widget_id ); ?> .rsp-card-label {
                    font-size: 7px;
                    padding: 0.3rem 0.8rem;
                    bottom: -0.75rem;
                    left: -0.75rem;
                }
            }
        </style>

        <div id="<?php echo esc_attr( $widget_id ); ?>" class="rsp-root">
            <div class="rsp-sticky">

                <?php /* ── Marquee ── */ ?>
                <div class="rsp-marquee-text" id="<?php echo esc_attr( $widget_id ); ?>-marquee">
                    <h2 class="rsp-marquee-h2"><?php echo esc_html( $marquee_text ); ?></h2>
                </div>

                <?php /* ── Subtítulo (opcional) ── */ ?>
                <?php if ( $show_subtitle === 'yes' && ! empty( $subtitle_text ) ) : ?>
                <div class="rsp-subtitle">
                    <?php echo esc_html( $subtitle_text ); ?>
                </div>
                <?php endif; ?>

                <?php /* ── Cards ── */ ?>
                <div class="rsp-cards-container">
                    <?php
                    $total = count( $positions = $desktop_positions );
                    foreach ( $roster_items as $i => $item ) :
                        $pos        = $desktop_positions[ $i % $total ];
                        $left_mob   = $mobile_lefts[ $i % count( $mobile_lefts ) ];
                        $image_url  = ! empty( $item['item_image']['url'] ) ? $item['item_image']['url'] : '';
                        $name       = $item['item_name'];

                        // Inline overrides por ítem
                        $label_style = '';
                        if ( ! empty( $item['item_label_bg'] ) )    $label_style .= 'background-color:' . esc_attr( $item['item_label_bg'] ) . ';';
                        if ( ! empty( $item['item_label_color'] ) )  $label_style .= 'color:' . esc_attr( $item['item_label_color'] ) . ';';
                    ?>
                    <div class="rsp-card"
                         data-speed="<?php echo esc_attr( $pos['speed'] ); ?>"
                         data-x-desktop="<?php echo esc_attr( $pos['left'] ); ?>"
                         data-x-mobile="<?php echo esc_attr( $left_mob ); ?>"
                         style="left:<?php echo esc_attr( $pos['left'] ); ?>; top:<?php echo esc_attr( $pos['y'] ); ?>; z-index:<?php echo esc_attr( $pos['z'] ); ?>;">
                        <div class="rsp-card-image-wrapper">
                            <?php if ( $image_url ) : ?>
                            <img src="<?php echo esc_url( $image_url ); ?>"
                                 alt="<?php echo esc_attr( $name ); ?>"
                                 class="rsp-card-image"
                                 loading="lazy">
                            <?php endif; ?>
                            <div class="rsp-card-label"<?php echo $label_style ? ' style="' . $label_style . '"' : ''; ?>>
                                <?php echo esc_html( $name ); ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

            </div>
        </div>

        <script>
        (function () {
            'use strict';

            var id      = '<?php echo esc_js( $widget_id ); ?>';
            var root    = document.getElementById(id);
            var marquee = document.getElementById(id + '-marquee');
            if (!root || !marquee) return;

            var cards = root.querySelectorAll('.rsp-card');

            /* ── iOS / Safari detection ──────────────────────────────────── */
            var isIOS      = /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;
            var isSafariBr = /^((?!chrome|android).)*safari/i.test(navigator.userAgent);
            var isIOSSafari = isIOS || (isSafariBr && 'ontouchstart' in window);

            var INTENSITY  = isIOSSafari ? 0.5  : 1.0;
            var LERP       = isIOSSafari ? 0.07 : 0.12;

            /* ── Responsive positions ───────────────────────────────────── */
            function applyPositions() {
                var mobile = window.innerWidth <= 768;
                cards.forEach(function (card) {
                    card.style.left = mobile ? card.dataset.xMobile : card.dataset.xDesktop;
                });
            }
            applyPositions();

            /* ── Scroll progress ────────────────────────────────────────── */
            function getProgress() {
                var rect       = root.getBoundingClientRect();
                var scrollable = root.offsetHeight - window.innerHeight;
                if (scrollable <= 0) return 0;
                return Math.max(0, Math.min(1, -rect.top / scrollable));
            }

            /* ── Apply parallax ─────────────────────────────────────────── */
            function applyParallax(p) {
                var rect = root.getBoundingClientRect();
                if (rect.bottom <= 0 || rect.top >= window.innerHeight) return;

                var mobile = window.innerWidth <= 768;

                /* Marquee: translateX de 40% → -120% */
                var xM = 40 + p * -160;
                marquee.style.transform        = 'translateX(' + xM + '%)';
                marquee.style.webkitTransform  = 'translateX(' + xM + '%)';

                /* Cards: translateY con rango suavizado */
                cards.forEach(function (card) {
                    var speed = parseFloat(card.dataset.speed) || 1;
                    var rf    = mobile ? 0.5 : 1.0;
                    var yS    =  220 * speed * rf * INTENSITY;
                    var yE    = -280 * speed * rf * INTENSITY;
                    var y     = yS + p * (yE - yS);
                    card.style.transform       = 'translateY(' + y + 'px)';
                    card.style.webkitTransform = 'translateY(' + y + 'px)';
                });
            }

            /* ── rAF loop with lerp ─────────────────────────────────────── */
            var current = 0, target = 0, rafId = null, last = -1;

            function lerp(a, b, t) { return a + (b - a) * t; }

            function loop() {
                current = lerp(current, target, LERP);
                var d = Math.abs(current - last);
                if (d > 0.0001) { applyParallax(current); last = current; }
                if (d < 0.00005) { rafId = null; return; }
                rafId = requestAnimationFrame(loop);
            }

            function onScroll() {
                target = getProgress();
                if (!rafId) rafId = requestAnimationFrame(loop);
            }

            function onResize() {
                applyPositions();
                current = target = getProgress();
                applyParallax(current);
            }

            window.addEventListener('scroll', onScroll,  { passive: true });
            window.addEventListener('resize', onResize, { passive: true });

            /* Estado inicial */
            current = target = getProgress();
            applyParallax(current);
        })();
        </script>
        <?php
    }
}
