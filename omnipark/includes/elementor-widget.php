<?php
/**
 * OmniPark Diagonal Images - Elementor Widget
 * Widget personalizado para Elementor con soporte completo de personalización
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Omnipark_Diagonal_Elementor_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'omnipark-diagonal-images';
    }

    public function get_title() {
        return __( 'OmniPark Diagonal Images', 'omnipark-diagonal' );
    }

    public function get_icon() {
        return 'eicon-image-box';
    }

    public function get_categories() {
        return array( 'basic', 'media' );
    }

    public function get_keywords() {
        return array( 'image', 'diagonal', 'gallery', 'omnipark' );
    }

    protected function register_controls() {
        // SECCIÓN: IMÁGENES
        $this->start_controls_section(
            'section_images',
            array(
                'label' => __( '🖼️ Imágenes', 'omnipark-diagonal' ),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            )
        );

        // Repeater para múltiples imágenes
        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'image',
            array(
                'label'   => __( 'Imagen', 'omnipark-diagonal' ),
                'type'    => \Elementor\Controls_Manager::MEDIA,
                'default' => array(
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ),
            )
        );

        $repeater->add_control(
            'image_text',
            array(
                'label'       => __( 'Texto sobre la imagen', 'omnipark-diagonal' ),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'default'     => '',
                'placeholder' => __( 'Ej: Inversión segura', 'omnipark-diagonal' ),
            )
        );

        $repeater->add_control(
            'text_color',
            array(
                'label'     => __( 'Color del texto', 'omnipark-diagonal' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#FFFFFF',
                'selectors' => array(
                    '{{WRAPPER}} .diagonal-item-{{_id}} .diagonal-label' => 'color: {{VALUE}}',
                ),
            )
        );

        $repeater->add_control(
            'text_bg_color',
            array(
                'label'     => __( 'Color de fondo del texto', 'omnipark-diagonal' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => 'rgba(0, 0, 0, 0.6)',
                'selectors' => array(
                    '{{WRAPPER}} .diagonal-item-{{_id}} .diagonal-label' => 'background: {{VALUE}}',
                ),
            )
        );

        $this->add_control(
            'items',
            array(
                'label'       => __( 'Imágenes', 'omnipark-diagonal' ),
                'type'        => \Elementor\Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => array(
                    array(
                        'image_text' => __( 'Inversión segura', 'omnipark-diagonal' ),
                    ),
                    array(
                        'image_text' => __( 'Plusvalía acelerada', 'omnipark-diagonal' ),
                    ),
                    array(
                        'image_text' => __( 'Business Hub', 'omnipark-diagonal' ),
                    ),
                    array(
                        'image_text' => __( 'Bodegas modulares', 'omnipark-diagonal' ),
                    ),
                    array(
                        'image_text' => __( 'Naves premium', 'omnipark-diagonal' ),
                    ),
                    array(
                        'image_text' => __( 'Ubicación estratégica', 'omnipark-diagonal' ),
                    ),
                ),
                'title_field' => '{{{ image_text }}}',
            )
        );

        $this->end_controls_section();

        // SECCIÓN: LAYOUT
        $this->start_controls_section(
            'section_layout',
            array(
                'label' => __( '📐 Diseño', 'omnipark-diagonal' ),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            )
        );

        $this->add_control(
            'gap',
            array(
                'label'       => __( 'Separación adicional (px)', 'omnipark-diagonal' ),
                'type'        => \Elementor\Controls_Manager::SLIDER,
                'default'     => array(
                    'size' => 0,
                ),
                'range'       => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 50,
                    ),
                ),
                'description' => __( 'Si las quieres más separadas, usa este valor junto con Solape en 0 o mayor.', 'omnipark-diagonal' ),
                'selectors'   => array(
                    '{{WRAPPER}} .diagonal-images-container' => '--gap: {{SIZE}}px;',
                ),
            )
        );

        $this->add_control(
            'image_overlap',
            array(
                'label'       => __( 'Solape entre imágenes (px)', 'omnipark-diagonal' ),
                'type'        => \Elementor\Controls_Manager::SLIDER,
                'default'     => array(
                    'size' => -20,
                ),
                'range'       => array(
                    'px' => array(
                        'min' => -40,
                        'max' => 30,
                    ),
                ),
                'description' => __( 'Negativo une las imágenes, 0 las deja normales y positivo las separa.', 'omnipark-diagonal' ),
                'selectors'   => array(
                    '{{WRAPPER}} .diagonal-image-wrapper + .diagonal-image-wrapper' => 'margin-left: {{SIZE}}px;',
                ),
            )
        );

        $this->add_control(
            'height',
            array(
                'label'       => __( 'Altura de las imágenes (px)', 'omnipark-diagonal' ),
                'type'        => \Elementor\Controls_Manager::SLIDER,
                'default'     => array(
                    'size' => 300,
                ),
                'range'       => array(
                    'px' => array(
                        'min' => 100,
                        'max' => 600,
                    ),
                ),
                'selectors'   => array(
                    '{{WRAPPER}} .diagonal-images-container' => '--height: {{SIZE}}px;',
                ),
            )
        );

        $this->add_control(
            'image_min_width',
            array(
                'label'       => __( 'Ancho mínimo por imagen (px)', 'omnipark-diagonal' ),
                'type'        => \Elementor\Controls_Manager::SLIDER,
                'default'     => array(
                    'size' => 200,
                ),
                'range'       => array(
                    'px' => array(
                        'min' => 120,
                        'max' => 500,
                    ),
                ),
                'selectors'   => array(
                    '{{WRAPPER}} .diagonal-images-container' => '--item-min-width: {{SIZE}}px;',
                ),
            )
        );

        $this->add_control(
            'image_max_width',
            array(
                'label'       => __( 'Ancho máximo por imagen (px)', 'omnipark-diagonal' ),
                'type'        => \Elementor\Controls_Manager::SLIDER,
                'default'     => array(
                    'size' => 300,
                ),
                'range'       => array(
                    'px' => array(
                        'min' => 160,
                        'max' => 700,
                    ),
                ),
                'selectors'   => array(
                    '{{WRAPPER}} .diagonal-images-container' => '--item-max-width: {{SIZE}}px;',
                ),
            )
        );

        $this->add_control(
            'clip_path_angle',
            array(
                'label'       => __( 'Ángulo del corte diagonal', 'omnipark-diagonal' ),
                'type'        => \Elementor\Controls_Manager::SELECT,
                'options'     => array(
                    '10' => __( 'Muy suave (10°)', 'omnipark-diagonal' ),
                    '15' => __( 'Suave (15°)', 'omnipark-diagonal' ),
                    '20' => __( 'Moderado (20°)', 'omnipark-diagonal' ),
                    '25' => __( 'Pronunciado (25°)', 'omnipark-diagonal' ),
                    '30' => __( 'Muy pronunciado (30°)', 'omnipark-diagonal' ),
                ),
                'default'     => '15',
                'selectors'   => array(
                    '{{WRAPPER}} .diagonal-image-wrapper' => '--clip-path-angle: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'alignment',
            array(
                'label'     => __( 'Alineación', 'omnipark-diagonal' ),
                'type'      => \Elementor\Controls_Manager::CHOOSE,
                'options'   => array(
                    'flex-start'   => array(
                        'title' => __( 'Izquierda', 'omnipark-diagonal' ),
                        'icon'  => 'eicon-h-align-left',
                    ),
                    'center'       => array(
                        'title' => __( 'Centro', 'omnipark-diagonal' ),
                        'icon'  => 'eicon-h-align-center',
                    ),
                    'flex-end'     => array(
                        'title' => __( 'Derecha', 'omnipark-diagonal' ),
                        'icon'  => 'eicon-h-align-right',
                    ),
                ),
                'default'   => 'center',
                'selectors' => array(
                    '{{WRAPPER}} .diagonal-images-container' => 'justify-content: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'show_image_text',
            array(
                'label'        => __( 'Mostrar texto sobre imágenes', 'omnipark-diagonal' ),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'default'      => '',
                'label_on'     => __( 'Sí', 'omnipark-diagonal' ),
                'label_off'    => __( 'No', 'omnipark-diagonal' ),
                'return_value' => 'yes',
                'prefix_class' => 'show-image-text-',
            )
        );

        $this->end_controls_section();

        // SECCIÓN: ESTILOS - IMAGEN
        $this->start_controls_section(
            'section_image_style',
            array(
                'label' => __( '🎨 Estilos de Imagen', 'omnipark-diagonal' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_control(
            'image_border_radius',
            array(
                'label'      => __( 'Radio de borde', 'omnipark-diagonal' ),
                'type'       => \Elementor\Controls_Manager::SLIDER,
                'default'    => array(
                    'size' => 0,
                ),
                'range'      => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 50,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .diagonal-image-wrapper' => 'border-radius: {{SIZE}}px;',
                ),
            )
        );

        $this->add_control(
            'image_shadow',
            array(
                'label'      => __( 'Sombra', 'omnipark-diagonal' ),
                'type'       => \Elementor\Controls_Manager::BOX_SHADOW,
                'default'    => array(
                    'horizontal' => 0,
                    'vertical'   => 10,
                    'blur'       => 30,
                    'spread'     => 0,
                    'color'      => 'rgba(0, 0, 0, 0.15)',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .diagonal-image-wrapper' => 'box-shadow: {{HORIZONTAL}}px {{VERTICAL}}px {{BLUR}}px {{SPREAD}}px {{COLOR}};',
                ),
            )
        );

        $this->add_control(
            'hover_effect',
            array(
                'label'     => __( 'Efecto al pasar el mouse', 'omnipark-diagonal' ),
                'type'      => \Elementor\Controls_Manager::SELECT,
                'options'   => array(
                    'none'     => __( 'Sin efecto', 'omnipark-diagonal' ),
                    'zoom'     => __( 'Zoom', 'omnipark-diagonal' ),
                    'lift'     => __( 'Elevación', 'omnipark-diagonal' ),
                    'grayscale' => __( 'Escala de grises', 'omnipark-diagonal' ),
                ),
                'default'   => 'zoom',
                'prefix_class' => 'hover-effect-',
            )
        );

        $this->end_controls_section();

        // SECCIÓN: ESTILOS - TEXTO
        $this->start_controls_section(
            'section_text_style',
            array(
                'label' => __( '✍️ Estilos de Texto', 'omnipark-diagonal' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            array(
                'name'           => 'text_typography',
                'label'          => __( 'Tipografía', 'omnipark-diagonal' ),
                'selector'       => '{{WRAPPER}} .diagonal-label',
                'fields_options' => array(
                    'font_size' => array(
                        'default' => array(
                            'unit' => 'px',
                            'size' => 16,
                        ),
                    ),
                ),
            )
        );

        $this->add_control(
            'text_alignment',
            array(
                'label'     => __( 'Alineación del texto', 'omnipark-diagonal' ),
                'type'      => \Elementor\Controls_Manager::CHOOSE,
                'options'   => array(
                    'left'   => array(
                        'title' => __( 'Izquierda', 'omnipark-diagonal' ),
                        'icon'  => 'eicon-text-align-left',
                    ),
                    'center' => array(
                        'title' => __( 'Centro', 'omnipark-diagonal' ),
                        'icon'  => 'eicon-text-align-center',
                    ),
                    'right'  => array(
                        'title' => __( 'Derecha', 'omnipark-diagonal' ),
                        'icon'  => 'eicon-text-align-right',
                    ),
                ),
                'default'   => 'center',
                'selectors' => array(
                    '{{WRAPPER}} .diagonal-label' => 'text-align: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'text_padding',
            array(
                'label'      => __( 'Espaciado interno', 'omnipark-diagonal' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'default'    => array(
                    'top'      => 20,
                    'right'    => 15,
                    'bottom'   => 20,
                    'left'     => 15,
                    'unit'     => 'px',
                    'isLinked' => false,
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .diagonal-label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->end_controls_section();

        // SECCIÓN: ESTILOS - CONTENEDOR
        $this->start_controls_section(
            'section_container_style',
            array(
                'label' => __( '📦 Estilos del Contenedor', 'omnipark-diagonal' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_control(
            'container_background',
            array(
                'label'     => __( 'Color de fondo', 'omnipark-diagonal' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => array(
                    '{{WRAPPER}} .diagonal-images-container' => 'background-color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'container_padding',
            array(
                'label'      => __( 'Espaciado', 'omnipark-diagonal' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'default'    => array(
                    'top'      => 20,
                    'right'    => 20,
                    'bottom'   => 20,
                    'left'     => 20,
                    'unit'     => 'px',
                    'isLinked' => true,
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .diagonal-images-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $clip_path_angle = intval( $settings['clip_path_angle'] );
        $items = $settings['items'];

        if ( empty( $items ) ) {
            echo '<p>' . esc_html__( 'Por favor agrega imágenes', 'omnipark-diagonal' ) . '</p>';
            return;
        }

        // Calcular el clip-path basado en el ángulo
        $left_percent = $clip_path_angle;
        $right_percent = 100 - $clip_path_angle;
        $clip_path = sprintf(
            'polygon(%d%% 0%%, 100%% 0%%, %d%% 100%%, 0%% 100%%)',
            $left_percent,
            $right_percent
        );

        ?>
        <style>
            .elementor-element-<?php echo esc_attr( $this->get_id() ); ?> .diagonal-image-wrapper {
                clip-path: <?php echo esc_attr( $clip_path ); ?>;
            }
        </style>

        <div class="diagonal-images-container">
            <?php foreach ( $items as $index => $item ) : ?>
                <div class="diagonal-image-wrapper diagonal-item-<?php echo esc_attr( $item['_id'] ); ?>">
                    <div class="diagonal-image-media">
                        <?php if ( ! empty( $item['image']['url'] ) ) : ?>
                            <img src="<?php echo esc_url( $item['image']['url'] ); ?>" alt="<?php echo esc_attr( $item['image_text'] ); ?>" />
                        <?php endif; ?>
                    </div>
                    <?php if ( ! empty( $item['image_text'] ) ) : ?>
                        <div class="diagonal-label">
                            <span class="diagonal-label-icon" aria-hidden="true">+</span>
                            <span class="diagonal-label-text"><?php echo esc_html( $item['image_text'] ); ?></span>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
        <?php
    }

    protected function content_template() {
        ?>
        <style>
            .elementor-element-{{ view.el.dataset.elementorId }} .diagonal-image-wrapper {
                clip-path: polygon({{ clipPathLeft }}% 0%, 100% 0%, {{ clipPathRight }}% 100%, 0% 100%);
            }
        </style>

        <div class="diagonal-images-container">
            <# _.each( settings.items, function( item, index ) { #>
                <div class="diagonal-image-wrapper diagonal-item-{{ item._id }}">
                    <div class="diagonal-image-media">
                        <# if ( item.image.url ) { #>
                            <img src="{{ item.image.url }}" alt="{{ item.image_text }}" />
                        <# } #>
                    </div>
                    <# if ( item.image_text ) { #>
                        <div class="diagonal-label">
                            <span class="diagonal-label-icon" aria-hidden="true">+</span>
                            <span class="diagonal-label-text">{{ item.image_text }}</span>
                        </div>
                    <# } #>
                </div>
            <# }); #>
        </div>
        <?php
    }
}
