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
        // parallax controls (allow user to boost separation/intensity)
        $this->add_control( 'parallax_desktop_start', [
            'label'   => esc_html__( 'Recorrido párallax desktop (inicio px)', 'roster-scroll-fernader' ),
            'type'    => \Elementor\Controls_Manager::NUMBER,
            'min'     => 0,
            'max'     => 2000,
            'step'    => 10,
            'default' => 220,
        ]);
        $this->add_control( 'parallax_desktop_end', [
            'label'   => esc_html__( 'Recorrido párallax desktop (fin px)', 'roster-scroll-fernader' ),
            'type'    => \Elementor\Controls_Manager::NUMBER,
            'min'     => 0,
            'max'     => 2000,
            'step'    => 10,
            'default' => 280,
        ]);
        $this->add_control( 'parallax_mobile_start', [
            'label'   => esc_html__( 'Recorrido párallax móvil (inicio px)', 'roster-scroll-fernader' ),
            'type'    => \Elementor\Controls_Manager::NUMBER,
            'min'     => 0,
            'max'     => 2000,
            'step'    => 10,
            'default' => 180,
            'description' => esc_html__( 'Valores mayores aumentan la separación vertical en móviles.', 'roster-scroll-fernader' ),
        ]);
        $this->add_control( 'parallax_mobile_end', [
            'label'   => esc_html__( 'Recorrido párallax móvil (fin px)', 'roster-scroll-fernader' ),
            'type'    => \Elementor\Controls_Manager::NUMBER,
            'min'     => 0,
            'max'     => 2000,
            'step'    => 10,
            'default' => 180,
        ]);
        $this->add_control( 'parallax_intensity', [
            'label'   => esc_html__( 'Intensidad general', 'roster-scroll-fernader' ),
            'type'    => \Elementor\Controls_Manager::SLIDER,
            'range'   => [ 'px' => [ 'min' => 0.1, 'max' => 3, 'step' => 0.1 ] ],
            'default' => [ 'size' => 1 ],
            'description' => esc_html__( 'Aumenta este valor para intensificar el parallax (más separación).', 'roster-scroll-fernader' ),
        ]);

        // factor de separación vertical en desktop
        $this->add_control( 'desktop_spacing_factor', [
            'label'       => esc_html__( 'Factor de separación desktop', 'roster-scroll-fernader' ),
            'type'        => \Elementor\Controls_Manager::SLIDER,
            'range'       => [ 'px' => [ 'min' => 0.5, 'max' => 3, 'step' => 0.1 ] ],
            'default'     => [ 'size' => 2 ],
            'description' => esc_html__( 'Multiplica la separación vertical entre tarjetas en pantallas grandes.', 'roster-scroll-fernader' ),
        ]);

        // reducción de altura final para recortar espacio en blanco al terminar
        $this->add_control( 'scroll_end_reduction', [
            'label'       => esc_html__( 'Reducir espacio final (vh)', 'roster-scroll-fernader' ),
            'type'        => \Elementor\Controls_Manager::NUMBER,
            'min'         => 0,
            'max'         => 500,
            'step'        => 25,
            'default'     => 0,
            'description' => esc_html__( 'Resta esta cantidad al alto total en vh para disminuir el espacio en blanco después de hacer scroll.', 'roster-scroll-fernader' ),
        ]);

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

        $this->add_responsive_control( 'image_height', [
            'label'          => esc_html__( 'Altura de Imagen', 'roster-scroll-fernader' ),
            'type'           => \Elementor\Controls_Manager::SLIDER,
            'size_units'     => [ 'vw', 'px', '%' ],
            'range'          => [
                'vw' => [ 'min' => 8,   'max' => 80  ],
                'px' => [ 'min' => 80, 'max' => 800  ],
                '%'  => [ 'min' => 8,   'max' => 100 ],
            ],
            'default'        => [ 'unit' => 'vw', 'size' => 24 ],
            'tablet_default' => [ 'unit' => 'vw', 'size' => 33 ],
            'mobile_default' => [ 'unit' => 'vw', 'size' => 56 ],
            'description'    => esc_html__( 'Valores por defecto mantienen proporción 3/4. Deja el campo vacío para usar aspect-ratio automático.', 'roster-scroll-fernader' ),
            'selectors'      => [ '{{WRAPPER}} .rsf-card' => 'height: {{SIZE}}{{UNIT}} !important;' ],
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
            // use 0 by default so labels start square and the editor can remove rounding
            'default'    => [ 'unit' => 'px', 'size' => 0 ],
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

        // parámetros nuevos
        $desktop_spacing     = floatval( $settings['desktop_spacing_factor']['size'] ?? 1 );
        $scroll_end_reduction = intval( $settings['scroll_end_reduction'] );

        $uid = 'rsf-' . $this->get_id();

        // calcular altura efectiva del contenedor, restando la reducción opcional.
        // nunca dejamos menos de 100 vh para que el sticky tenga al menos un
        // viewport de recorrido.
        $root_height = max( 100, $scroll_height - $scroll_end_reduction );

        // Posiciones desktop escalonadas — 3 zonas (izq / centro / der)
        // speeds in the desktop layout have been expanded to span a wider interval
        $pos_desktop = [
            [ 'left' => '5%',  'y' => '5%',  'speed' => 0.5, 'z' => 20 ],
            [ 'left' => '75%', 'y' => '15%', 'speed' => 1.5, 'z' => 10 ],
            [ 'left' => '38%', 'y' => '32%', 'speed' => 0.7, 'z' => 5  ],
            [ 'left' => '8%',  'y' => '50%', 'speed' => 1.3, 'z' => 15 ],
            [ 'left' => '68%', 'y' => '65%', 'speed' => 1.1, 'z' => 20 ],
            [ 'left' => '42%', 'y' => '78%', 'speed' => 0.6, 'z' => 8  ],
            [ 'left' => '82%', 'y' => '88%', 'speed' => 1.4, 'z' => 12 ],
        ];
        // Móvil: 2 columnas alternadas, verticalmente repartidas a lo largo de la altura
        // Calcularemos x e y al vuelo en el bucle usando número total de elementos.
        // aplicar factor de separación vertical si se especificó
        if ( $desktop_spacing !== 1 ) {
            foreach ( $pos_desktop as &$p ) {
                // conservamos porcentaje
                $p['y'] = (floatval( $p['y'] ) * $desktop_spacing) . '%';
            }
            unset( $p );
        }

        $total = count( $pos_desktop );
        // filas en cada columna (ceil para manejar impares)
        $rows_per_column = (int) ceil( $total / 2 );
        // paso vertical (%) para distribuir con márgenes superior e inferior
        $step_v = 100 / ( $rows_per_column + 1 );
        ?>

        <style>
            /* ── Root ────────────────────────────────────────────────────────── */
            #<?php echo esc_attr( $uid ); ?>.rsf-root {
                position: relative;
                height: <?php echo esc_attr( $root_height ); ?>vh;
                overflow: visible;
                margin: 0; padding: 0;
            }
            @media (max-width: 768px) {
                /* mantenemos casi toda la altura para aprovechar el parallax en vertical */
                #<?php echo esc_attr( $uid ); ?>.rsf-root {
                    height: <?php echo esc_attr( round( $root_height * 0.9 ) ); ?>vh;
                }
            }
            @media (max-width: 480px) {
                #<?php echo esc_attr( $uid ); ?>.rsf-root {
                    height: <?php echo esc_attr( round( $root_height * 0.85 ) ); ?>vh;
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
                /* base padding/spacing – overridden by control selectors */
                padding: 0.5rem 1.25rem;
                /* visual properties are managed by Elementor controls (typography, colors, radius) */
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
                        $pos      = $pos_desktop[ $i % $total ];

                    // columnas alternas a ambos lados en móvil
                    $col      = $i % 2;
                    $left_mob = $col === 0 ? '4%' : '52%';

                    // fila dentro de la columna (0,1,2...)
                    $row      = (int) floor( $i / 2 );
                    $top_mob  = ($step_v * ($row + 1)) . '%';
                    // ajustes móviles para primeras tarjetas para ocupar mejor
                    // el espacio vertical inicial en pantallas pequeñas.
                    // el índice 0 (talento 1) sube bastante, el índice 1 un poco,
                    // el índice 2 también se eleva ligeramente.
                    if ( in_array( $i, [0,1,2], true ) ) {
                        $offset = 0;
                        if ( 0 === $i ) {
                            $offset = 20; // mover la primera tarjeta hacia arriba
                        } elseif ( 1 === $i ) {
                            $offset = 10; // ya estaba definida antes
                        } elseif ( 2 === $i ) {
                            $offset = 15; // tercer talento también sube
                        }
                        $top_mob = max(0, ($step_v * ($row + 1) - $offset)) . '%';
                    }
                    $img_url  = ! empty( $item['item_image']['url'] ) ? $item['item_image']['url'] : '';
                    $name     = $item['item_name'];

                    $label_style = '';
                    if ( ! empty( $item['item_label_bg'] ) )   $label_style .= 'background-color:' . esc_attr( $item['item_label_bg'] ) . ';';
                    if ( ! empty( $item['item_label_color'] ) ) $label_style .= 'color:' . esc_attr( $item['item_label_color'] ) . ';';
                    ?>
                    <div class="rsf-card"
                         data-speed="<?php echo esc_attr( $pos['speed'] ); ?>"
                         data-x-desktop="<?php echo esc_attr( $pos['left'] ); ?>"
                         data-x-mobile="<?php echo esc_attr( $left_mob ); ?>"
                         data-y-desktop="<?php echo esc_attr( $pos['y'] ); ?>"
                         data-y-mobile="<?php echo esc_attr( $top_mob ); ?>"
                         style="left:<?php echo esc_attr( $pos['left'] ); ?>;top:<?php echo esc_attr( $pos['y'] ); ?>;z-index:<?php echo esc_attr( $pos['z'] + $i ); ?>;">
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

            // valores procedentes de los controles
            var BASE_INTENSITY = <?php echo floatval( $settings['parallax_intensity']['size'] ?? 1 ); ?>;
            var PAR_DESK_START  = <?php echo intval( $settings['parallax_desktop_start'] ); ?>;
            var PAR_DESK_END    = <?php echo intval( $settings['parallax_desktop_end'] ); ?>;
            var PAR_MOB_START   = <?php echo intval( $settings['parallax_mobile_start'] ); ?>;
            var PAR_MOB_END     = <?php echo intval( $settings['parallax_mobile_end'] ); ?>;

            var INTENSITY = BASE_INTENSITY * (isIOSSafari ? 0.5  : 1.0);
            var LERP      = isIOSSafari ? 0.07 : 0.12;

            /* ── Posiciones responsive ───────────────────────────────────── */
            function applyPositions() {
                var mobile = window.innerWidth <= 768;
                var containerH = root.clientHeight;

                // margen inferior seguro para la etiqueta/sombra
                var safeMargin = 12;

                // helper para convertir valores porcentaje/px a px
                function parseToPx(raw) {
                    var topPx = 0;
                    if (typeof raw === 'string' && raw.indexOf('%') !== -1) {
                        var pct = parseFloat(raw);
                        topPx = (pct / 100) * containerH;
                    } else if (typeof raw === 'string' && raw.indexOf('px') !== -1) {
                        topPx = parseFloat(raw);
                    } else {
                        topPx = parseFloat(raw) || 0;
                    }
                    return topPx;
                }

                // Móvil: comportamiento simple y directo (como antes)
                if (mobile) {
                    cards.forEach(function (c) {
                        c.style.left = c.dataset.xMobile;
                        var topPx = parseToPx(c.dataset.yMobile);
                        var maxTop = Math.max(0, containerH - (c.offsetHeight || (containerH * 0.25)) - safeMargin);
                        if (topPx > maxTop) topPx = maxTop;
                        if (topPx < 0) topPx = 0;
                        c.style.top = Math.round(topPx) + 'px';
                    });
                    return;
                }

                // Desktop: recogemos posiciones deseadas y hacemos una pasada
                // para evitar solapamientos y distribuir el overflow de forma
                // proporcional si fuera necesario.
                var list = [];
                cards.forEach(function (c) {
                    c.style.left = c.dataset.xDesktop;
                    var topPx = parseToPx(c.dataset.yDesktop);
                    list.push({ el: c, top: topPx, h: c.offsetHeight || (containerH * 0.25) });
                });

                // ordenamos por top
                list.sort(function (a, b) { return a.top - b.top; });

                // ancho mínimo entre tarjetas para evitar solape (50% de altura de card)
                var avgH = 0;
                list.forEach(function (it) { avgH += it.h; });
                avgH = list.length ? (avgH / list.length) : (containerH * 0.25);
                var minGap = Math.max(12, Math.round(avgH * 0.5));

                // forward pass: asegurar separación mínima
                for (var i = 1; i < list.length; i++) {
                    var need = list[i - 1].top + minGap;
                    if (list[i].top < need) list[i].top = need;
                }

                // límite inferior
                var maxTop = Math.max(0, containerH - Math.round(avgH) - safeMargin);
                var lastTop = list.length ? list[list.length - 1].top : 0;

                if (lastTop > maxTop) {
                    var overflow = lastTop - maxTop;
                    // intenta desplazar todo hacia arriba
                    for (var j = 0; j < list.length; j++) { list[j].top = list[j].top - overflow; }

                    // si todavía hay índices negativos, comprimimos proporcionalmente
                    if (list.length && list[0].top < 0) {
                        var scale = maxTop / lastTop;
                        if (!isFinite(scale) || scale <= 0) scale = 0.5;
                        for (var k = 0; k < list.length; k++) {
                            list[k].top = Math.max(0, list[k].top * scale);
                        }
                        // y volvemos a garantizar separación mínima (segunda pasada)
                        for (var m = 1; m < list.length; m++) {
                            var need2 = list[m - 1].top + minGap;
                            if (list[m].top < need2) list[m].top = need2;
                        }
                    }
                }

                // aplicamos posiciones calculadas
                list.forEach(function (it) {
                    // último chequeo para no salirnos
                    var t = Math.round(Math.max(0, Math.min(it.top, maxTop)));
                    it.el.style.top = t + 'px';
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
                // siempre aplicamos el parallax, incluso si el contenedor
                // todavía no está dentro del viewport. El chequeo anterior
                // causaba que la primera vez que entrabas en el área no se
                // estableciesen las transformaciones y luego saltaran de golpe.
                //if (rect.bottom <= 0 || rect.top >= window.innerHeight) return;

                var mobile = window.innerWidth <= 768;

                /* Título: de 40% → -120% */
                var xT = 40 + p * -160;
                titulo.style.transform       = 'translateX(' + xT + '%)';
                titulo.style.webkitTransform = 'translateX(' + xT + '%)';

                /* Cards: parallax vertical */
                cards.forEach(function (c) {
                    var speed = parseFloat(c.dataset.speed) || 1;
                    var yS, yE;
                    if (mobile) {
                        // móviles usan los valores configurables y se multiplica por la intensidad
                        yS =  PAR_MOB_START * speed * INTENSITY;
                        yE = -PAR_MOB_END   * speed * INTENSITY;
                    } else {
                        yS =  PAR_DESK_START * speed * INTENSITY;
                        yE = -PAR_DESK_END   * speed * INTENSITY;
                    }
                    var y = yS + p * (yE - yS);
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

            /* helper que coloca el estado actual y satisface el primer render */
            function initParallax() {
                applyPositions();
                current = target = getProgress();
                applyParallax(current);
            }

            // si el widget se carga fuera de pantalla, algunos navegadores
            // podrían ejecutar este script pero nunca hacer scroll hasta ahí,
            // de modo que el primer parallax se da al llegar, provocando un
            // salto visible. una observer nos asegura que al entrar ya
            // tendremos todo listo.
            if ('IntersectionObserver' in window) {
                var observer = new IntersectionObserver(function(entries) {
                    entries.forEach(function(e) {
                        if (e.isIntersecting) {
                            initParallax();
                            observer.unobserve(root);
                        }
                    });
                }, { threshold: 0 });
                observer.observe(root);
            }

            /* Estado inicial */
            initParallax();
        })();
        </script>
        <?php
    }
}
