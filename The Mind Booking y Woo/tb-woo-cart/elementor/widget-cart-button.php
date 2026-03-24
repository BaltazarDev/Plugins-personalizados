<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class TBWC_Cart_Button_Widget extends \Elementor\Widget_Base {

    public function get_name()           { return 'tbwc_cart_button'; }
    public function get_title()          { return 'Botón Agregar al Carrito'; }
    public function get_icon()           { return 'eicon-cart'; }
    public function get_categories()     { return ['tattoo-booking', 'general']; }
    public function get_script_depends() { return ['tbwc-script']; }
    public function get_style_depends()  { return ['tbwc-style']; }

    protected function register_controls() {

        $this->start_controls_section('sec_content', [
            'label' => 'Producto',
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
        ]);
        $this->add_control('product_source', [
            'label'   => 'Fuente del producto',
            'type'    => \Elementor\Controls_Manager::SELECT,
            'options' => [
                'auto'   => 'Automatico (loop / plantilla de producto)',
                'manual' => 'Manual (ID fijo)',
            ],
            'default' => 'auto',
        ]);
        $this->add_control('product_id', [
            'label'     => 'ID del Producto',
            'type'      => \Elementor\Controls_Manager::NUMBER,
            'min'       => 1,
            'default'   => '',
            'condition' => ['product_source' => 'manual'],
        ]);
        $this->add_control('btn_text', [
            'label'   => 'Texto del boton',
            'type'    => \Elementor\Controls_Manager::TEXT,
            'default' => '+ Agregar',
        ]);
        $this->add_control('btn_text_added', [
            'label'   => 'Texto al agregar',
            'type'    => \Elementor\Controls_Manager::TEXT,
            'default' => 'Agregado',
        ]);
        $this->add_control('show_price', [
            'label'        => 'Mostrar precio',
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);
        $this->add_control('open_drawer', [
            'label'        => 'Abrir carrito al agregar',
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);
        $this->add_control('btn_width', [
            'label'   => 'Ancho',
            'type'    => \Elementor\Controls_Manager::SELECT,
            'options' => ['auto' => 'Automatico', 'full' => 'Completo'],
            'default' => 'full',
        ]);
        $this->end_controls_section();

        $this->start_controls_section('sec_btn_style', [
            'label' => 'Estilo Boton',
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ]);
        $this->add_control('btn_bg', [
            'label'     => 'Fondo',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => '#111827',
            'selectors' => ['{{WRAPPER}} .tbwc-add-btn' => 'background:{{VALUE}};'],
        ]);
        $this->add_control('btn_color', [
            'label'     => 'Color texto',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => '#ffffff',
            'selectors' => ['{{WRAPPER}} .tbwc-add-btn' => 'color:{{VALUE}};'],
        ]);
        $this->add_control('btn_bg_hover', [
            'label'     => 'Fondo hover',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => '#374151',
            'selectors' => ['{{WRAPPER}} .tbwc-add-btn:hover' => 'background:{{VALUE}};'],
        ]);
        $this->add_control('btn_bg_added', [
            'label'     => 'Fondo al agregar',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => '#059669',
            'selectors' => ['{{WRAPPER}} .tbwc-add-btn.added' => 'background:{{VALUE}};'],
        ]);
        $this->add_responsive_control('btn_radius', [
            'label'      => 'Radio bordes (px)',
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 0, 'max' => 50]],
            'default'    => ['unit' => 'px', 'size' => 8],
            'selectors'  => ['{{WRAPPER}} .tbwc-add-btn' => 'border-radius:{{SIZE}}{{UNIT}};'],
        ]);
        $this->add_responsive_control('btn_padding', [
            'label'      => 'Padding',
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px'],
            'default'    => ['top'=>'12','right'=>'20','bottom'=>'12','left'=>'20','unit'=>'px','isLinked'=>false],
            'selectors'  => ['{{WRAPPER}} .tbwc-add-btn' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);
        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name'     => 'btn_typo',
            'selector' => '{{WRAPPER}} .tbwc-add-btn',
        ]);
        $this->end_controls_section();

        $this->start_controls_section('sec_price_style', [
            'label' => 'Precio',
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ]);
        $this->add_control('price_color', [
            'label'     => 'Color precio',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => '#111827',
            'selectors' => ['{{WRAPPER}} .tbwc-price' => 'color:{{VALUE}};'],
        ]);
        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name'     => 'price_typo',
            'selector' => '{{WRAPPER}} .tbwc-price',
        ]);
        $this->end_controls_section();
    }

    protected function get_product_id_from_context( $s ) {
        if ( ( $s['product_source'] ?? 'auto' ) === 'manual' ) {
            return intval( $s['product_id'] ?? 0 );
        }
        global $product, $post;
        if ( ! empty( $product ) && is_a( $product, 'WC_Product' ) ) {
            return $product->get_id();
        }
        if ( ! empty( $post ) && get_post_type( $post->ID ) === 'product' ) {
            return $post->ID;
        }
        $id = get_the_ID();
        if ( $id && get_post_type( $id ) === 'product' ) {
            return $id;
        }
        return 0;
    }

    protected function render() {
        $s          = $this->get_settings_for_display();
        $product_id = $this->get_product_id_from_context( $s );
        $btn_text   = esc_html( $s['btn_text']       ?? '+ Agregar' );
        $text_added = esc_html( $s['btn_text_added'] ?? 'Agregado' );
        $open       = ( $s['open_drawer'] ?? 'yes' ) === 'yes' ? 'yes' : 'no';
        $full       = ( $s['btn_width']   ?? 'full' ) === 'full' ? 'tbwc-btn-full' : '';
        $show_price = ( $s['show_price']  ?? 'yes' ) === 'yes';
        $product    = $product_id ? wc_get_product( $product_id ) : null;

        echo '<div class="tbwc-btn-wrap ' . esc_attr( $full ) . '">';

        if ( $product && $show_price ) {
            echo '<div class="tbwc-price">' . $product->get_price_html() . '</div>';
        }

        echo '<button'
            . ' class="tbwc-add-btn ' . esc_attr( $full ) . '"'
            . ' data-product-id="' . esc_attr( $product_id ) . '"'
            . ' data-text-default="' . esc_attr( $btn_text ) . '"'
            . ' data-text-added="' . esc_attr( $text_added ) . '"'
            . ' data-open-drawer="' . esc_attr( $open ) . '"'
            . ( ! $product_id ? ' disabled' : '' )
            . '>'
            . '<span class="tbwc-btn-inner">' . $btn_text . '</span>'
            . '<span class="tbwc-btn-spinner" style="display:none">...</span>'
            . '</button>';

        if ( ! $product_id ) {
            echo '<p style="color:#9ca3af;font-size:.75rem;margin:4px 0 0">Widget activo: se vinculara al producto en el frontend.</p>';
        }

        echo '</div>';
    }
}
