<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Elementor Widget: Roster Scroll Fernader
 * Basado en roster-scroll-elementor.html
 * – Repeater de imágenes + nombre/etiqueta configurable desde el editor
 * – Tipografía, color, tamaño, padding/margin para título y subtítulo
 * – Tamaño de imágenes responsive
 * – Optimizado para Safari iOS (lerp + rAF + GPU layers)
 */
class Roster_Scroll_Fernader_Widget extends \Elementor\Widget_Base {

    public function get_name()       { return 'roster_scroll_fernader'; }
    public function get_title()      { return esc_html__( 'Roster Scroll', 'roster-scroll-fernader' ); }
    public function get_icon()       { return 'eicon-gallery-grid'; }
    public function get_categories() { return [ 'general' ]; }
    public function get_keywords()   { return [ 'roster', 'parallax', 'scroll', 'talentos', 'galería' ]; }

    // =========================================================================
    //  CONTROLS
    // =========================================================================
    protected function register_controls() {

        // ─── CONTENT ▸ GENERAL ───────────────────────────────────────────────
        $this->start_controls_section( 'section_general', [
            'label' => esc_html__( 'General', 'roster-scroll-fernader' ),
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
        ] );

        $this->add_control( 'titulo_text', [
            'label'       => esc_html__( 'Texto Título (Marquesina)', 'roster-scroll-fernader' ),
            'type'        => \Elementor\Controls_Manager::TEXT,
            'default'     => 'ROSTER',
            'placeholder' => 'ROSTER',
            'label_block' => true,
        ] );

        $this->add_control( 'show_subtitulo', [
            'label'        => esc_html__( 'Mostrar Subtítulo', 'roster-scroll-fernader' ),
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'label_on'     => esc_html__( 'Sí', 'roster-scroll-fernader' ),
            'label_off'    => esc_html__( 'No', 'roster-scroll-fernader' ),
            'return_value' => 'yes',
            'default'      => 'no',
        ] );

        $this->add_control( 'subtitulo_text', [
            'label'       => esc_html__( 'Subtítulo', 'roster-scroll-fernader' ),
            'type'        => \Elementor\Controls_Manager::TEXT,
            'default'     => 'Talentos y Figuras Públicas',
            'label_block' => true,
            'condition'   => [ 'show_subtitulo' => 'yes' ],
        ] );

        $this->add_control( 'scroll_height', [
            'label'       => esc_html__( 'Altura de Scroll (vh)', 'roster-scroll-fernader' ),
            'type'        => \Elementor\Controls_Manager::NUMBER,
            'min'         => 150,
            'max'         => 800,
            'step'        => 25,
            'default'     => 500,
            'description' => esc_html__( '500 = 5 veces la altura de pantalla. Se reduce automáticamente en móvil.', 'roster-scroll-fernader' ),
        ] );

        $this->end_controls_section();

        // ─── CONTENT ▸ ROSTER (Repeater) ─────────────────────────────────────
        $this->start_controls_section( 'section_roster', [
            'label' => esc_html__( 'Imágenes del Roster', 'roster-scroll-fernader' ),
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
        ] );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control( 'item_image', [
            'label'   => esc_html__( 'Imagen', 'roster-scroll-fernader' ),
            'type'    => \Elementor\Controls_Manager::MEDIA,
            'default' => [ 'url' => \Elementor\Utils::get_placeholder_image_src() ],
        ] );

        $repeater->add_control( 'item_name', [
            'label'       => esc_html__( 'Nombre / Etiqueta', 'roster-scroll-fernader' ),
            'type'        => \Elementor\Controls_Manager::TEXT,
            'default'     => 'Nombre del Talento',
            'label_block' => true,
        ] );

        $repeater->add_control( 'item_label_bg', [
            'label'       => esc_html__( 'Color de fondo (etiqueta)', 'roster-scroll-fernader' ),
            'type'        => \Elementor\Controls_Manager::COLOR,
            'default'     => '',
            'description' => esc_html__( 'Deja vacío para usar el color global.', 'roster-scroll-fernader' ),
        ] );

        $repeater->add_control( 'item_label_color', [
            'label'       => esc_html__( 'Color de texto (etiqueta)', 'roster-scroll-fernader' ),
            'type'        => \Elementor\Controls_Manager::COLOR,
            'default'     => '',
            'description' => esc_html__( 'Deja vacío para usar el color global.', 'roster-scroll-fernader' ),
        ] );

        $this->add_control( 'roster_items', [
            'label'       => esc_html__( 'Agregar personas al Roster', 'roster-scroll-fernader' ),
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

        // ─── STYLE ▸ FONDO ────────────────────────────────────────────────────
        $this->start_controls_section( 'style_fondo', [
            'label' => esc_html__( 'Fondo', 'roster-scroll-fernader' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ] );

        $this->add_control( 'background_color', [
            'label'     => esc_html__( 'Color de Fondo', 'roster-scroll-fernader' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => 'transparent',
            'selectors' => [ '{{WRAPPER}} .rsf-root' => 'background-color: {{VALUE}};' ],
        ] );

        $this->end_controls_section();

        // ─── STYLE ▸ TÍTULO (Marquesina) ──────────────────────────────────────
        $this->start_controls_section( 'style_titulo', [
            'label' => esc_html__( 'Título (Marquesina)', 'roster-scroll-fernader' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ] );

        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [
            'name'     => 'titulo_typography',
            'label'    => esc_html__( 'Tipografía', 'roster-scroll-fernader' ),
            'selector' => '{{WRAPPER}} .rsf-titulo-h2',
        ] );

        $this->add_responsive_control( 'titulo_font_size', [
            'label'          => esc_html__( 'Tamaño de Texto', 'roster-scroll-fernader' ),
            'type'           => \Elementor\Controls_Manager::SLIDER,
            'size_units'     => [ 'px', 'vw', 'em', 'rem' ],
            'range'          => [
                'px'  => [ 'min' => 20,  'max' => 300, 'step' => 1   ],
                'vw'  => [ 'min' => 5,   'max' => 80,  'step' => 0.5 ],
                'em'  => [ 'min' => 1,   'max' => 20,  'step' => 0.5 ],
                'rem' => [ 'min' => 1,   'max' => 20,  'step' => 0.5 ],
            ],
            'default'        => [ 'unit' => 'vw', 'size' => 42 ],
            'tablet_default' => [ 'unit' => 'vw', 'size' => 55 ],
            'mobile_default' => [ 'unit' => 'vw', 'size' => 70 ],
            'selectors'      => [ '{{WRAPPER}} .rsf-titulo-h2' => 'font-size: {{SIZE}}{{UNIT}};' ],
        ] );

        $this->add_control( 'titulo_color', [
            'label'     => esc_html__( 'Color', 'roster-scroll-fernader' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => '#000000',
            'selectors' => [ '{{WRAPPER}} .rsf-titulo-h2' => 'color: {{VALUE}};' ],
        ] );

        $this->add_control( 'titulo_opacity', [
            'label'     => esc_html__( 'Opacidad', 'roster-scroll-fernader' ),
            'type'      => \Elementor\Controls_Manager::SLIDER,
            'range'     => [ 'px' => [ 'min' => 0, 'max' => 1, 'step' => 0.05 ] ],
            'default'   => [ 'size' => 0.3 ],
            'selectors' => [ '{{WRAPPER}} .rsf-titulo-h2' => 'opacity: {{SIZE}};' ],
        ] );

        $this->add_control( 'titulo_blend_mode', [
            'label'     => esc_html__( 'Modo de Mezcla', 'roster-scroll-fernader' ),
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
            'selectors' => [ '{{WRAPPER}} .rsf-titulo-h2' => 'mix-blend-mode: {{VALUE}};' ],
        ] );

        $this->add_responsive_control( 'titulo_padding', [
            'label'      => esc_html__( 'Padding', 'roster-scroll-fernader' ),
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', 'em', '%' ],
            'selectors'  => [ '{{WRAPPER}} .rsf-titulo-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
        ] );

        $this->add_responsive_control( 'titulo_margin', [
            'label'      => esc_html__( 'Margen', 'roster-scroll-fernader' ),
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', 'em', '%' ],
            'selectors'  => [ '{{WRAPPER}} .rsf-titulo-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
        ] );

        $this->end_controls_section();

        // ─── STYLE ▸ SUBTÍTULO ────────────────────────────────────────────────
        $this->start_controls_section( 'style_subtitulo', [
            'label'     => esc_html__( 'Subtítulo', 'roster-scroll-fernader' ),
            'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
            'condition' => [ 'show_subtitulo' => 'yes' ],
        ] );

        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [
            'name'     => 'subtitulo_typography',
            'label'    => esc_html__( 'Tipografía', 'roster-scroll-fernader' ),
            'selector' => '{{WRAPPER}} .rsf-subtitulo',
        ] );

        $this->add_responsive_control( 'subtitulo_font_size', [
            'label'          => esc_html__( 'Tamaño de Texto', 'roster-scroll-fernader' ),
            'type'           => \Elementor\Controls_Manager::SLIDER,
            'size_units'     => [ 'px', 'vw', 'em', 'rem' ],
            'range'          => [
                'px'  => [ 'min' => 8,  'max' => 80,  'step' => 1   ],
                'vw'  => [ 'min' => 0.5,'max' => 5,   'step' => 0.1 ],
                'em'  => [ 'min' => 0.5,'max' => 5,   'step' => 0.1 ],
                'rem' => [ 'min' => 0.5,'max' => 5,   'step' => 0.1 ],
            ],
            'default'        => [ 'unit' => 'px', 'size' => 13 ],
            'tablet_default' => [ 'unit' => 'px', 'size' => 12 ],
            'mobile_default' => [ 'unit' => 'px', 'size' => 11 ],
            'selectors'      => [ '{{WRAPPER}} .rsf-subtitulo' => 'font-size: {{SIZE}}{{UNIT}};' ],
        ] );

        $this->add_control( 'subtitulo_color', [
            'label'     => esc_html__( 'Color', 'roster-scroll-fernader' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => '#000000',
            'selectors' => [ '{{WRAPPER}} .rsf-subtitulo' => 'color: {{VALUE}};' ],
        ] );

        $this->add_responsive_control( 'subtitulo_align', [
            'label'     => esc_html__( 'Alineación', 'roster-scroll-fernader' ),
            'type'      => \Elementor\Controls_Manager::CHOOSE,
            'options'   => [
                'left'   => [ 'title' => esc_html__( 'Izquierda', 'roster-scroll-fernader' ), 'icon' => 'eicon-text-align-left' ],
                'center' => [ 'title' => esc_html__( 'Centro', 'roster-scroll-fernader' ),    'icon' => 'eicon-text-align-center' ],
                'right'  => [ 'title' => esc_html__( 'Derecha', 'roster-scroll-fernader' ),   'icon' => 'eicon-text-align-right' ],
            ],
            'default'   => 'center',
            'selectors' => [ '{{WRAPPER}} .rsf-subtitulo' => 'text-align: {{VALUE}};' ],
        ] );

        $this->add_responsive_control( 'subtitulo_padding', [
            'label'      => esc_html__( 'Padding', 'roster-scroll-fernader' ),
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', 'em', '%' ],
            'selectors'  => [ '{{WRAPPER}} .rsf-subtitulo' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
        ] );

        $this->add_responsive_control( 'subtitulo_margin', [
            'label'      => esc_html__( 'Margen', 'roster-scroll-fernader' ),
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', 'em', '%' ],
            'selectors'  => [ '{{WRAPPER}} .rsf-subtitulo' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
        ] );

        $this->end_controls_section();

        // ─── STYLE ▸ IMÁGENES ─────────────────────────────────────────────────
        $this->start_controls_section( 'style_imagenes', [
            'label' => esc_html__( 'Imágenes', 'roster-scroll-fernader' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ] );

        $this->add_responsive_control( 'image_width', [
            'label'          => esc_html__( 'Ancho de Imagen', 'roster-scroll-fernader' ),
            'type'           => \Elementor\Controls_Manager::SLIDER,
            'size_units'     => [ 'vw', 'px', '%' ],
            'range'          => [
                'vw' => [ 'min' => 8,   'max' => 60  ],
                'px' => [ 'min' => 80, 'max' => 600  ],
                '%'  => [ 'min' => 8,   'max' => 60  ],
            ],
            'default'        => [ 'unit' => 'vw', 'size' => 18 ],
            'tablet_default' => [ 'unit' => 'vw', 'size' => 25 ],
            'mobile_default' => [ 'unit' => 'vw', 'size' => 42 ],
            'selectors'      => [ '{{WRAPPER}} .rsf-card' => 'width: {{SIZE}}{{UNIT}} !important;' ],
        ] );

        $this->add_control( 'image_border_radius', [
            'label'      => esc_html__( 'Radio del Borde', 'roster-scroll-fernader' ),
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'px', '%' ],
            'range'      => [ 'px' => [ 'min' => 0, 'max' => 40 ] ],
            'default'    => [ 'unit' => 'px', 'size' => 2 ],
            'selectors'  => [ '{{WRAPPER}} .rsf-card-image-wrapper' => 'border-radius: {{SIZE}}{{UNIT}};' ],
        ] );

        $this->add_control( 'image_grayscale', [
            'label'        => esc_html__( 'Escala de Grises', 'roster-scroll-fernader' ),
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'label_on'     => esc_html__( 'Sí', 'roster-scroll-fernader' ),
            'label_off'    => esc_html__( 'No', 'roster-scroll-fernader' ),
            'return_value' => 'yes',
            'default'      => 'yes',
        ] );

        $this->add_group_control( \Elementor\Group_Control_Box_Shadow::get_type(), [
            'name'     => 'image_shadow',
            'label'    => esc_html__( 'Sombra', 'roster-scroll-fernader' ),
            'selector' => '{{WRAPPER}} .rsf-card-image-wrapper',
        ] );

        $this->end_controls_section();

        // ─── STYLE ▸ ETIQUETAS ────────────────────────────────────────────────
        $this->start_controls_section( 'style_etiquetas', [
            'label' => esc_html__( 'Etiquetas (Nombres)', 'roster-scroll-fernader' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ] );

        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [
            'name'     => 'etiqueta_typography',
            'label'    => esc_html__( 'Tipografía', 'roster-scroll-fernader' ),
            'selector' => '{{WRAPPER}} .rsf-card-label',
        ] );

        $this->add_responsive_control( 'etiqueta_font_size', [
            'label'          => esc_html__( 'Tamaño de Texto', 'roster-scroll-fernader' ),
            'type'           => \Elementor\Controls_Manager::SLIDER,
            'size_units'     => [ 'px', 'em', 'rem' ],
            'range'          => [ 'px' => [ 'min' => 6, 'max' => 24 ] ],
            'default'        => [ 'unit' => 'px', 'size' => 9 ],
            'tablet_default' => [ 'unit' => 'px', 'size' => 8 ],
            'mobile_default' => [ 'unit' => 'px', 'size' => 7 ],
            'selectors'      => [ '{{WRAPPER}} .rsf-card-label' => 'font-size: {{SIZE}}{{UNIT}};' ],
        ] );

        $this->add_control( 'etiqueta_text_color', [
            'label'     => esc_html__( 'Color de Texto', 'roster-scroll-fernader' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => '#ffffff',
            'selectors' => [ '{{WRAPPER}} .rsf-card-label' => 'color: {{VALUE}};' ],
        ] );

        $this->add_control( 'etiqueta_bg_color', [
            'label'     => esc_html__( 'Color de Fondo', 'roster-scroll-fernader' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => '#18181b',
            'selectors' => [ '{{WRAPPER}} .rsf-card-label' => 'background-color: {{VALUE}};' ],
        ] );

        $this->add_responsive_control( 'etiqueta_padding', [
            'label'      => esc_html__( 'Padding', 'roster-scroll-fernader' ),
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', 'em' ],
            'default'    => [ 'top' => 8, 'right' => 20, 'bottom' => 8, 'left' => 20, 'unit' => 'px', 'isLinked' => false ],
            'selectors'  => [ '{{WRAPPER}} .rsf-card-label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
        ] );

        $this->add_control( 'etiqueta_border_radius', [
            'label'      => esc_html__( 'Radio del Borde', 'roster-scroll-fernader' ),
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'px', '%' ],
            'range'      => [ 'px' => [ 'min' => 0, 'max' => 99 ] ],
            'default'    => [ 'unit' => 'px', 'size' => 99 ],
            'selectors'  => [
                '{{WRAPPER}} .rsf-card-label' => 'border-top-left-radius: {{SIZE}}{{UNIT}}; border-bottom-left-radius: {{SIZE}}{{UNIT}};',
            ],
        ] );

        $this->add_responsive_control( 'etiqueta_pos_bottom', [
            'label'      => esc_html__( 'Posición Vertical (bottom)', 'roster-scroll-fernader' ),
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'px', 'rem' ],
            'range'      => [
                'px'  => [ 'min' => -40, 'max' => 40 ],
                'rem' => [ 'min' => -4,  'max' => 4, 'step' => 0.25 ],
            ],
            'default'    => [ 'unit' => 'rem', 'size' => -1 ],
            'selectors'  => [ '{{WRAPPER}} .rsf-card-label' => 'bottom: {{SIZE}}{{UNIT}};' ],
        ] );

        $this->add_responsive_control( 'etiqueta_pos_left', [
            'label'      => esc_html__( 'Posición Horizontal (left)', 'roster-scroll-fernader' ),
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'px', 'rem' ],
            'range'      => [
                'px'  => [ 'min' => -40, 'max' => 40 ],
                'rem' => [ 'min' => -4,  'max' => 4, 'step' => 0.25 ],
            ],
            'default'    => [ 'unit' => 'rem', 'size' => -1 ],
            'selectors'  => [ '{{WRAPPER}} .rsf-card-label' => 'left: {{SIZE}}{{UNIT}};' ],
        ] );

        $this->end_controls_section();
    }

    // =========================================================================
    //  RENDER
    // =========================================================================
    protected function render() {
        $settings        = $this->get_settings_for_display();
        $titulo_text     = $settings['titulo_text'];
        $subtitulo_text  = $settings['subtitulo_text'];
        $show_subtitulo  = $settings['show_subtitulo'];
        $scroll_height   = intval( $settings['scroll_height'] );
        $image_grayscale = $settings['image_grayscale'];
        $roster_items    = $settings['roster_items'];

        $uid = 'rsf-' . $this->get_id();

        // Posiciones desktop escalonadas — 3 zonas (izq / centro / der)
        $pos_desktop = [
            [ 'left' => '5%',  'y' => '10%', 'speed' => 0.8, 'z' => 20 ],
            [ 'left' => '75%', 'y' => '5%',  'speed' => 1.0, 'z' => 10 ],
            [ 'left' => '38%', 'y' => '50%', 'speed' => 0.6, 'z' => 5  ],
            [ 'left' => '8%',  'y' => '65%', 'speed' => 1.1, 'z' => 15 ],
            [ 'left' => '68%', 'y' => '55%', 'speed' => 1.2, 'z' => 20 ],
            [ 'left' => '42%', 'y' => '15%', 'speed' => 0.7, 'z' => 8  ],
            [ 'left' => '82%', 'y' => '70%', 'speed' => 0.9, 'z' => 12 ],
        ];
        // Móvil: 2 columnas alternadas
        $pos_mobile_x = [ '5%', '55%', '5%', '55%', '5%', '55%', '5%' ];
        $total = count( $pos_desktop );
        ?>

        <style>
            /* ── Root ────────────────────────────────────────────────────────── */
            #<?php echo esc_attr( $uid ); ?>.rsf-root {
                position: relative;
                height: <?php echo esc_attr( $scroll_height ); ?>vh;
                overflow: visible;
                margin: 0; padding: 0;
            }
            @media (max-width: 768px) {
                #<?php echo esc_attr( $uid ); ?>.rsf-root {
                    height: <?php echo esc_attr( round( $scroll_height * 0.6 ) ); ?>vh;
                }
            }
            @media (max-width: 480px) {
                #<?php echo esc_attr( $uid ); ?>.rsf-root {
                    height: <?php echo esc_attr( round( $scroll_height * 0.5 ) ); ?>vh;
                }
            }

            /* ── Sticky ──────────────────────────────────────────────────────── */
            #<?php echo esc_attr( $uid ); ?> .rsf-sticky {
                position: -webkit-sticky;
                position: sticky;
                top: 0;
                height: 100vh;
                overflow: hidden;
                display: flex;
                align-items: center;
                justify-content: center;
                -webkit-transform: translateZ(0);
                transform: translateZ(0);
            }

            /* ── Título (Marquesina) ─────────────────────────────────────────── */
            #<?php echo esc_attr( $uid ); ?> .rsf-titulo-wrap {
                position: absolute;
                white-space: nowrap;
                pointer-events: none;
                user-select: none;
                z-index: 10;
                -webkit-transform: translateX(40%);
                transform: translateX(40%);
            }
            #<?php echo esc_attr( $uid ); ?> .rsf-titulo-h2 {
                font-size: 42vw;
                font-weight: 900;
                text-transform: uppercase;
                margin: 0;
                line-height: 0.85;
                letter-spacing: -0.05em;
                opacity: 0.3;
            }
            @media (max-width: 768px) {
                #<?php echo esc_attr( $uid ); ?> .rsf-titulo-h2 { font-size: 65vw; }
            }
            @media (max-width: 480px) {
                #<?php echo esc_attr( $uid ); ?> .rsf-titulo-h2 { font-size: 80vw; }
            }

            /* will-change solo en desktop con mouse */
            @media (min-width: 769px) and (hover: hover) {
                #<?php echo esc_attr( $uid ); ?> .rsf-titulo-wrap,
                #<?php echo esc_attr( $uid ); ?> .rsf-card { will-change: transform; }
            }

            /* ── Subtítulo ───────────────────────────────────────────────────── */
            #<?php echo esc_attr( $uid ); ?> .rsf-subtitulo {
                position: absolute;
                bottom: 8%;
                left: 50%;
                -webkit-transform: translateX(-50%);
                transform: translateX(-50%);
                z-index: 30;
                pointer-events: none;
                user-select: none;
                white-space: nowrap;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: 0.2em;
                text-transform: uppercase;
            }

            /* ── Cards container ─────────────────────────────────────────────── */
            #<?php echo esc_attr( $uid ); ?> .rsf-cards-container {
                position: absolute;
                inset: 0;
                width: 100%;
                height: 100%;
                pointer-events: none;
            }

            /* ── Card ────────────────────────────────────────────────────────── */
            #<?php echo esc_attr( $uid ); ?> .rsf-card {
                position: absolute;
                pointer-events: auto;
                width: 18vw;
                min-width: 150px;
                aspect-ratio: 3/4;
                overflow: visible; /* Permite que la etiqueta sobresalga sin cortarse */
                -webkit-transform: translateY(0);
                transform: translateY(0);
            }
            @media (max-width: 1024px) {
                #<?php echo esc_attr( $uid ); ?> .rsf-card { width: 25vw; min-width: 130px; }
            }
            @media (max-width: 768px) {
                #<?php echo esc_attr( $uid ); ?> .rsf-card { width: 42vw !important; min-width: 0; }
            }

            /* ── Card image wrapper ──────────────────────────────────────────── */
            #<?php echo esc_attr( $uid ); ?> .rsf-card-image-wrapper {
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
            @media (hover: hover) and (pointer: fine) {
                #<?php echo esc_attr( $uid ); ?> .rsf-card-image-wrapper { transition: transform 0.7s ease-out; }
                #<?php echo esc_attr( $uid ); ?> .rsf-card:hover .rsf-card-image-wrapper { transform: scale(1.05); }
            }

            /* ── Card image ──────────────────────────────────────────────────── */
            #<?php echo esc_attr( $uid ); ?> .rsf-card-image {
                width: 100%; height: 100%;
                object-fit: cover; display: block;
                <?php if ( $image_grayscale === 'yes' ) : ?>filter: grayscale(100%);<?php endif; ?>
            }
            @media (hover: hover) and (pointer: fine) {
                #<?php echo esc_attr( $uid ); ?> .rsf-card-image { transition: filter 1s ease; }
                #<?php echo esc_attr( $uid ); ?> .rsf-card:hover .rsf-card-image { filter: grayscale(0%); }
            }

            /* ── Label ───────────────────────────────────────────────────────── */
            #<?php echo esc_attr( $uid ); ?> .rsf-card-label {
                position: absolute;
                bottom: -1rem; left: -1rem;
                padding: 0.5rem 1.25rem;
                font-size: 9px;
                font-weight: 900;
                letter-spacing: 0.2em;
                text-transform: uppercase;
                background-color: #18181b;
                color: #fff;
                border-top-left-radius: 99px;
                border-bottom-left-radius: 99px;
                box-shadow: 0 8px 24px rgba(0,0,0,0.3);
                white-space: nowrap;
            }
            @media (hover: hover) and (pointer: fine) {
                #<?php echo esc_attr( $uid ); ?> .rsf-card-label { transition: transform 0.3s ease; }
                #<?php echo esc_attr( $uid ); ?> .rsf-card:hover .rsf-card-label { transform: translateY(-0.5rem); }
            }
            @media (max-width: 768px) {
                #<?php echo esc_attr( $uid ); ?> .rsf-card-label {
                    font-size: 7px; padding: 0.3rem 0.8rem;
                    bottom: -0.75rem; left: -0.75rem;
                }
            }
        </style>

        <div id="<?php echo esc_attr( $uid ); ?>" class="rsf-root">
            <div class="rsf-sticky">

                <!-- Título / Marquesina -->
                <div class="rsf-titulo-wrap" id="<?php echo esc_attr( $uid ); ?>-titulo">
                    <h2 class="rsf-titulo-h2"><?php echo esc_html( $titulo_text ); ?></h2>
                </div>

                <!-- Subtítulo (opcional) -->
                <?php if ( $show_subtitulo === 'yes' && ! empty( $subtitulo_text ) ) : ?>
                <div class="rsf-subtitulo">
                    <?php echo esc_html( $subtitulo_text ); ?>
                </div>
                <?php endif; ?>

                <!-- Cards -->
                <div class="rsf-cards-container">
                    <?php foreach ( $roster_items as $i => $item ) :
                        $pos       = $pos_desktop[ $i % $total ];
                        $left_mob  = $pos_mobile_x[ $i % count( $pos_mobile_x ) ];
                        $img_url   = ! empty( $item['item_image']['url'] ) ? $item['item_image']['url'] : '';
                        $name      = $item['item_name'];

                        $label_style = '';
                        if ( ! empty( $item['item_label_bg'] ) )    $label_style .= 'background-color:' . esc_attr( $item['item_label_bg'] ) . ';';
                        if ( ! empty( $item['item_label_color'] ) )  $label_style .= 'color:' . esc_attr( $item['item_label_color'] ) . ';';
                    ?>
                    <div class="rsf-card"
                         data-speed="<?php echo esc_attr( $pos['speed'] ); ?>"
                         data-x-desktop="<?php echo esc_attr( $pos['left'] ); ?>"
                         data-x-mobile="<?php echo esc_attr( $left_mob ); ?>"
                         style="left:<?php echo esc_attr( $pos['left'] ); ?>;top:<?php echo esc_attr( $pos['y'] ); ?>;z-index:<?php echo esc_attr( $pos['z'] ); ?>;">
                        <div class="rsf-card-image-wrapper">
                            <?php if ( $img_url ) : ?>
                            <img src="<?php echo esc_url( $img_url ); ?>"
                                 alt="<?php echo esc_attr( $name ); ?>"
                                 class="rsf-card-image"
                                 loading="lazy">
                            <?php endif; ?>
                        </div>
                        <!-- Etiqueta fuera del wrapper para no ser recortada por overflow:hidden -->
                        <div class="rsf-card-label"<?php echo $label_style ? ' style="' . $label_style . '"' : ''; ?>>
                            <?php echo esc_html( $name ); ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

            </div>
        </div>

        <script>
        (function () {
            'use strict';

            var uid     = '<?php echo esc_js( $uid ); ?>';
            var root    = document.getElementById(uid);
            var titulo  = document.getElementById(uid + '-titulo');
            if (!root || !titulo) return;

            var cards = root.querySelectorAll('.rsf-card');

            /* ── Detección iOS / Safari ──────────────────────────────────── */
            var isIOS       = /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;
            var isSafariBr  = /^((?!chrome|android).)*safari/i.test(navigator.userAgent);
            var isIOSSafari = isIOS || (isSafariBr && 'ontouchstart' in window);

            var INTENSITY = isIOSSafari ? 0.5  : 1.0;
            var LERP      = isIOSSafari ? 0.07 : 0.12;

            /* ── Posiciones responsive ───────────────────────────────────── */
            function applyPositions() {
                var mobile = window.innerWidth <= 768;
                cards.forEach(function (c) {
                    c.style.left = mobile ? c.dataset.xMobile : c.dataset.xDesktop;
                });
            }
            applyPositions();

            /* ── Progreso del scroll ─────────────────────────────────────── */
            function getProgress() {
                var rect = root.getBoundingClientRect();
                var scrollable = root.offsetHeight - window.innerHeight;
                if (scrollable <= 0) return 0;
                return Math.max(0, Math.min(1, -rect.top / scrollable));
            }

            /* ── Aplicar parallax ────────────────────────────────────────── */
            function applyParallax(p) {
                var rect = root.getBoundingClientRect();
                if (rect.bottom <= 0 || rect.top >= window.innerHeight) return;

                var mobile = window.innerWidth <= 768;

                /* Título: de 40% → -120% */
                var xT = 40 + p * -160;
                titulo.style.transform       = 'translateX(' + xT + '%)';
                titulo.style.webkitTransform = 'translateX(' + xT + '%)';

                /* Cards: parallax vertical */
                cards.forEach(function (c) {
                    var speed = parseFloat(c.dataset.speed) || 1;
                    var rf    = mobile ? 0.5 : 1.0;
                    var yS    =  220 * speed * rf * INTENSITY;
                    var yE    = -280 * speed * rf * INTENSITY;
                    var y     = yS + p * (yE - yS);
                    c.style.transform       = 'translateY(' + y + 'px)';
                    c.style.webkitTransform = 'translateY(' + y + 'px)';
                });
            }

            /* ── Loop rAF con lerp ───────────────────────────────────────── */
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
