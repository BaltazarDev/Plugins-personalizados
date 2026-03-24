<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class TB_Elementor_Widget extends \Elementor\Widget_Base {

    public function get_name()  { return 'tattoo_booking'; }
    public function get_title() { return 'Tattoo Booking'; }
    public function get_icon()  { return 'eicon-calendar'; }
    public function get_categories() { return [ 'tattoo-booking', 'general' ]; }

    protected function register_controls() {

        /* ══ CONTENIDO ══════════════════════════════════════════ */
        $this->start_controls_section('sec_content', ['label'=>'📝 Contenido','tab'=>\Elementor\Controls_Manager::TAB_CONTENT]);
        $this->add_control('form_title',    ['label'=>'Título',          'type'=>\Elementor\Controls_Manager::TEXT,'default'=>'Agenda tu Cita','label_block'=>true]);
        $this->add_control('form_subtitle', ['label'=>'Subtítulo',       'type'=>\Elementor\Controls_Manager::TEXT,'default'=>'Reserva en línea · Sin esperas','label_block'=>true]);
        $this->add_control('btn_text',      ['label'=>'Texto botón',     'type'=>\Elementor\Controls_Manager::TEXT,'default'=>'Agendar Ahora']);
        $this->add_control('forced_branch', ['label'=>'Forzar sucursal (ID)','type'=>\Elementor\Controls_Manager::NUMBER,'min'=>0,'default'=>0,'description'=>'0 = todas las sucursales activas.']);
        $this->end_controls_section();

        /* ══ IMÁGENES ══════════════════════════════════════════ */
        $this->start_controls_section('sec_images', ['label'=>'🖼 Imágenes','tab'=>\Elementor\Controls_Manager::TAB_CONTENT]);
        $this->add_control('img_male',   ['label'=>'Masculino','type'=>\Elementor\Controls_Manager::MEDIA,'default'=>['url'=>''],'description'=>'PNG fondo transparente recomendado.']);
        $this->add_control('img_female', ['label'=>'Femenino', 'type'=>\Elementor\Controls_Manager::MEDIA,'default'=>['url'=>'']]);
        $this->add_responsive_control('img_height',    ['label'=>'Alto máx. imagen','type'=>\Elementor\Controls_Manager::SLIDER,'size_units'=>['px','vh'],'range'=>['px'=>['min'=>150,'max'=>800],'vh'=>['min'=>10,'max'=>90]],'default'=>['unit'=>'px','size'=>400],'selectors'=>['{{WRAPPER}} .tb-body-photo'=>'max-height: {{SIZE}}{{UNIT}};']]);
        $this->add_responsive_control('img_max_width', ['label'=>'Ancho máx. imagen','type'=>\Elementor\Controls_Manager::SLIDER,'size_units'=>['px','%'],'range'=>['px'=>['min'=>80,'max'=>600],'%'=>['min'=>20,'max'=>100]],'default'=>['unit'=>'%','size'=>100],'selectors'=>['{{WRAPPER}} .tbw-img-container'=>'max-width: {{SIZE}}{{UNIT}};']]);
        $this->end_controls_section();

        /* ══ ESTILO: LAYOUT ═════════════════════════════════════ */
        $this->start_controls_section('sec_layout', ['label'=>'📐 Layout','tab'=>\Elementor\Controls_Manager::TAB_STYLE]);

        $this->add_control('form_boxed', ['label'=>'Formulario encajonado','type'=>\Elementor\Controls_Manager::SWITCHER,'label_on'=>'Sí','label_off'=>'No','return_value'=>'yes','default'=>'','description'=>'Activa para agregar borde, sombra y radio al contenedor del formulario.']);
        $this->add_responsive_control('form_max_width', ['label'=>'Ancho máximo widget','type'=>\Elementor\Controls_Manager::SLIDER,'size_units'=>['px','%'],'range'=>['px'=>['min'=>300,'max'=>1400],'%'=>['min'=>30,'max'=>100]],'default'=>['unit'=>'%','size'=>100],'selectors'=>['{{WRAPPER}} .tbw-wrap'=>'max-width: {{SIZE}}{{UNIT}}; margin-left:auto; margin-right:auto;']]);
        $this->add_control('box_bg',     ['label'=>'Fondo contenedor form','type'=>\Elementor\Controls_Manager::COLOR,'default'=>'transparent','selectors'=>['{{WRAPPER}} .tbw-col-form'=>'background: {{VALUE}};']]);
        $this->add_group_control(\Elementor\Group_Control_Border::get_type(),    ['name'=>'box_border',  'selector'=>'{{WRAPPER}} .tbw-col-form','condition'=>['form_boxed'=>'yes']]);
        $this->add_group_control(\Elementor\Group_Control_Box_Shadow::get_type(),['name'=>'box_shadow',  'selector'=>'{{WRAPPER}} .tbw-col-form','condition'=>['form_boxed'=>'yes']]);
        $this->add_control('box_radius', ['label'=>'Radio borde contenedor','type'=>\Elementor\Controls_Manager::SLIDER,'size_units'=>['px'],'range'=>['px'=>['min'=>0,'max'=>40]],'default'=>['unit'=>'px','size'=>0],'selectors'=>['{{WRAPPER}} .tbw-col-form'=>'border-radius: {{SIZE}}{{UNIT}};'],'condition'=>['form_boxed'=>'yes']]);
        $this->add_responsive_control('form_padding', ['label'=>'Padding formulario','type'=>\Elementor\Controls_Manager::DIMENSIONS,'size_units'=>['px','em'],'default'=>['top'=>'32','right'=>'0','bottom'=>'32','left'=>'0','unit'=>'px','isLinked'=>false],'selectors'=>['{{WRAPPER}} .tbw-col-form'=>'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']]);
        $this->add_responsive_control('col_gap',      ['label'=>'Separación columnas','type'=>\Elementor\Controls_Manager::SLIDER,'size_units'=>['px'],'range'=>['px'=>['min'=>0,'max'=>100]],'default'=>['unit'=>'px','size'=>40],'selectors'=>['{{WRAPPER}} .tbw-layout'=>'gap: {{SIZE}}{{UNIT}};']]);
        $this->add_control('body_col_bg', ['label'=>'Fondo columna imagen','type'=>\Elementor\Controls_Manager::COLOR,'default'=>'transparent','selectors'=>['{{WRAPPER}} .tbw-col-body'=>'background: {{VALUE}};']]);
        $this->end_controls_section();

        /* ══ ESTILO: BARRA DE DOLOR ══════════════════════════════ */
        $this->start_controls_section('sec_pain_bar', ['label'=>'🟢 Barra de Dolor','tab'=>\Elementor\Controls_Manager::TAB_STYLE]);
        $this->add_control('pain_bar_show',   ['label'=>'Mostrar','type'=>\Elementor\Controls_Manager::SWITCHER,'label_on'=>'Sí','label_off'=>'No','return_value'=>'yes','default'=>'yes']);
        $this->add_responsive_control('pain_bar_height', ['label'=>'Altura','type'=>\Elementor\Controls_Manager::SLIDER,'size_units'=>['px'],'range'=>['px'=>['min'=>3,'max'=>32]],'default'=>['unit'=>'px','size'=>12],'selectors'=>['{{WRAPPER}} .tbw-pain-bar'=>'height: {{SIZE}}{{UNIT}};']]);
        $this->add_control('pain_label_color',['label'=>'Color etiquetas','type'=>\Elementor\Controls_Manager::COLOR,'default'=>'#9ca3af','selectors'=>['{{WRAPPER}} .tbw-pain-labels span'=>'color: {{VALUE}};']]);
        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(),['name'=>'pain_label_typo','label'=>'Tipografía','selector'=>'{{WRAPPER}} .tbw-pain-labels span']);
        $this->end_controls_section();

        /* ══ ESTILO: TIPOGRAFÍA ══════════════════════════════════ */
        $this->start_controls_section('sec_typo', ['label'=>'🔤 Tipografía','tab'=>\Elementor\Controls_Manager::TAB_STYLE]);
        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(),['name'=>'title_typo',   'label'=>'Título',          'selector'=>'{{WRAPPER}} .tbw-title']);
        $this->add_control('title_color',   ['label'=>'Color título',   'type'=>\Elementor\Controls_Manager::COLOR,'default'=>'#111827','selectors'=>['{{WRAPPER}} .tbw-title'=>'color: {{VALUE}};']]);
        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(),['name'=>'subtitle_typo','label'=>'Subtítulo',       'selector'=>'{{WRAPPER}} .tbw-subtitle']);
        $this->add_control('subtitle_color',['label'=>'Color subtítulo','type'=>\Elementor\Controls_Manager::COLOR,'default'=>'#9ca3af','selectors'=>['{{WRAPPER}} .tbw-subtitle'=>'color: {{VALUE}};']]);
        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(),['name'=>'label_typo',   'label'=>'Etiquetas campo', 'selector'=>'{{WRAPPER}} .tbw-label']);
        $this->add_control('label_color',   ['label'=>'Color etiquetas','type'=>\Elementor\Controls_Manager::COLOR,'default'=>'#6b7280','selectors'=>['{{WRAPPER}} .tbw-label'=>'color: {{VALUE}};']]);
        $this->end_controls_section();

        /* ══ ESTILO: CAMPOS ══════════════════════════════════════ */
        $this->start_controls_section('sec_fields', ['label'=>'📋 Campos','tab'=>\Elementor\Controls_Manager::TAB_STYLE]);
        $this->add_control('field_bg',               ['label'=>'Fondo',               'type'=>\Elementor\Controls_Manager::COLOR,'default'=>'#f9fafb','selectors'=>['{{WRAPPER}} .tbw-control'=>'background-color: {{VALUE}};']]);
        $this->add_control('field_text_color',       ['label'=>'Color texto',         'type'=>\Elementor\Controls_Manager::COLOR,'default'=>'#111827','selectors'=>['{{WRAPPER}} .tbw-control'=>'color: {{VALUE}};']]);
        $this->add_control('field_placeholder_color',['label'=>'Color placeholder',   'type'=>\Elementor\Controls_Manager::COLOR,'default'=>'#9ca3af','selectors'=>['{{WRAPPER}} .tbw-control::placeholder'=>'color: {{VALUE}};','{{WRAPPER}} .tbw-control::-webkit-input-placeholder'=>'color: {{VALUE}};']]);
        $this->add_control('field_border_color',     ['label'=>'Color borde',         'type'=>\Elementor\Controls_Manager::COLOR,'default'=>'#e5e7eb','selectors'=>['{{WRAPPER}} .tbw-control'=>'border-color: {{VALUE}};']]);
        $this->add_control('field_focus_color',      ['label'=>'Color borde al enfocar','type'=>\Elementor\Controls_Manager::COLOR,'default'=>'#111827','selectors'=>['{{WRAPPER}} .tbw-control:focus'=>'border-color: {{VALUE}};']]);
        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(),['name'=>'field_typo','label'=>'Tipografía','selector'=>'{{WRAPPER}} .tbw-control']);
        $this->add_control('field_radius_type',['label'=>'Esquinas','type'=>\Elementor\Controls_Manager::SELECT,'options'=>['custom'=>'Personalizado','round'=>'Redondeado (pill)','square'=>'Cuadrado (0px)'],'default'=>'custom']);
        $this->add_responsive_control('field_radius',['label'=>'Radio (px)','type'=>\Elementor\Controls_Manager::SLIDER,'size_units'=>['px'],'range'=>['px'=>['min'=>0,'max'=>50]],'default'=>['unit'=>'px','size'=>8],'condition'=>['field_radius_type'=>'custom'],'selectors'=>['{{WRAPPER}} .tbw-control'=>'border-radius: {{SIZE}}{{UNIT}};']]);
        $this->add_responsive_control('field_padding',['label'=>'Padding','type'=>\Elementor\Controls_Manager::DIMENSIONS,'size_units'=>['px'],'default'=>['top'=>'10','right'=>'14','bottom'=>'10','left'=>'14','unit'=>'px','isLinked'=>false],'selectors'=>['{{WRAPPER}} .tbw-control'=>'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']]);
        $this->add_responsive_control('field_gap',['label'=>'Espacio entre campos','type'=>\Elementor\Controls_Manager::SLIDER,'size_units'=>['px'],'range'=>['px'=>['min'=>0,'max'=>48]],'default'=>['unit'=>'px','size'=>14],'selectors'=>['{{WRAPPER}} .tbw-field'=>'margin-bottom: {{SIZE}}{{UNIT}};']]);
        $this->end_controls_section();

        /* ══ ESTILO: GÉNERO ══════════════════════════════════════ */
        $this->start_controls_section('sec_gender', ['label'=>'⚥ Botones Género','tab'=>\Elementor\Controls_Manager::TAB_STYLE]);
        $this->add_control('gender_bg',           ['label'=>'Fondo inactivo',  'type'=>\Elementor\Controls_Manager::COLOR,'default'=>'#f9fafb','selectors'=>['{{WRAPPER}} .tbw-gender-pill'=>'background: {{VALUE}};']]);
        $this->add_control('gender_color',        ['label'=>'Texto inactivo',  'type'=>\Elementor\Controls_Manager::COLOR,'default'=>'#6b7280','selectors'=>['{{WRAPPER}} .tbw-gender-pill'=>'color: {{VALUE}};']]);
        $this->add_control('gender_active_bg',    ['label'=>'Fondo activo',    'type'=>\Elementor\Controls_Manager::COLOR,'default'=>'#111827','selectors'=>['{{WRAPPER}} .tbw-gender-pill.active'=>'background: {{VALUE}};']]);
        $this->add_control('gender_active_color', ['label'=>'Texto activo',    'type'=>\Elementor\Controls_Manager::COLOR,'default'=>'#ffffff','selectors'=>['{{WRAPPER}} .tbw-gender-pill.active'=>'color: {{VALUE}};']]);
        $this->add_control('gender_border_color', ['label'=>'Color borde',     'type'=>\Elementor\Controls_Manager::COLOR,'default'=>'#e5e7eb','selectors'=>['{{WRAPPER}} .tbw-gender'=>'border-color: {{VALUE}};']]);
        $this->add_responsive_control('gender_height',['label'=>'Altura','type'=>\Elementor\Controls_Manager::SLIDER,'size_units'=>['px'],'range'=>['px'=>['min'=>28,'max'=>64]],'default'=>['unit'=>'px','size'=>40],'selectors'=>['{{WRAPPER}} .tbw-gender'=>'height: {{SIZE}}{{UNIT}};']]);
        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(),['name'=>'gender_typo','selector'=>'{{WRAPPER}} .tbw-gender-pill']);
        $this->end_controls_section();

        /* ══ ESTILO: BOTÓN AGENDAR ═══════════════════════════════ */
        $this->start_controls_section('sec_btn', ['label'=>'🔘 Botón Agendar','tab'=>\Elementor\Controls_Manager::TAB_STYLE]);

        $this->add_control('btn_layout', [
            'label'   => 'Estilo',
            'type'    => \Elementor\Controls_Manager::SELECT,
            'options' => ['split'=>'Split — Texto | Ícono ↗ (como captura)','unified'=>'Unificado — Un bloque'],
            'default' => 'split',
        ]);
        $this->add_control('btn_position', [
            'label'   => 'Alineación',
            'type'    => \Elementor\Controls_Manager::SELECT,
            'options' => ['right'=>'Derecha','left'=>'Izquierda','center'=>'Centro','full'=>'Ancho completo'],
            'default' => 'right',
        ]);
        $this->add_responsive_control('btn_margin', [
            'label'      => 'Márgenes (posicionar entre inputs)',
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px'],
            'default'    => ['top'=>'6','right'=>'0','bottom'=>'0','left'=>'0','unit'=>'px','isLinked'=>false],
            'selectors'  => ['{{WRAPPER}} .tbw-submit-row'=>'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        // Área texto
        $this->add_control('btn_label_bg',   ['label'=>'Fondo texto','type'=>\Elementor\Controls_Manager::COLOR,'default'=>'#ffffff','selectors'=>['{{WRAPPER}} .tbw-btn-label'=>'background: {{VALUE}};']]);
        $this->add_control('btn_label_color',['label'=>'Color texto','type'=>\Elementor\Controls_Manager::COLOR,'default'=>'#111827','selectors'=>['{{WRAPPER}} .tbw-btn-label'=>'color: {{VALUE}};']]);
        $this->add_responsive_control('btn_label_padding',['label'=>'Padding área texto','type'=>\Elementor\Controls_Manager::DIMENSIONS,'size_units'=>['px'],'default'=>['top'=>'11','right'=>'18','bottom'=>'11','left'=>'18','unit'=>'px','isLinked'=>false],'selectors'=>['{{WRAPPER}} .tbw-btn-label'=>'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']]);
        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(),['name'=>'btn_typo','selector'=>'{{WRAPPER}} .tbw-btn-label']);

        // Área ícono (solo split)
        $this->add_control('btn_icon_bg',   ['label'=>'Fondo ícono ↗','type'=>\Elementor\Controls_Manager::COLOR,'default'=>'#111827','selectors'=>['{{WRAPPER}} .tbw-btn-icon'=>'background: {{VALUE}};'],'condition'=>['btn_layout'=>'split']]);
        $this->add_control('btn_icon_color',['label'=>'Color ícono ↗','type'=>\Elementor\Controls_Manager::COLOR,'default'=>'#ffffff','selectors'=>['{{WRAPPER}} .tbw-btn-icon'=>'color: {{VALUE}};'],'condition'=>['btn_layout'=>'split']]);
        $this->add_responsive_control('btn_icon_size',['label'=>'Tamaño celda ícono','type'=>\Elementor\Controls_Manager::SLIDER,'size_units'=>['px'],'range'=>['px'=>['min'=>24,'max'=>80]],'default'=>['unit'=>'px','size'=>44],'selectors'=>['{{WRAPPER}} .tbw-btn-icon'=>'width: {{SIZE}}{{UNIT}}; min-height: {{SIZE}}{{UNIT}};'],'condition'=>['btn_layout'=>'split']]);

        // Borde + esquinas
        $this->add_control('btn_border_color',['label'=>'Color borde','type'=>\Elementor\Controls_Manager::COLOR,'default'=>'#e5e7eb','selectors'=>['{{WRAPPER}} .tbw-submit'=>'border-color: {{VALUE}};']]);
        $this->add_control('btn_radius_type', ['label'=>'Esquinas','type'=>\Elementor\Controls_Manager::SELECT,'options'=>['custom'=>'Personalizado','round'=>'Redondeado (pill)','square'=>'Cuadrado (0px)'],'default'=>'custom']);
        $this->add_responsive_control('btn_radius',['label'=>'Radio (px)','type'=>\Elementor\Controls_Manager::SLIDER,'size_units'=>['px'],'range'=>['px'=>['min'=>0,'max'=>50]],'default'=>['unit'=>'px','size'=>6],'condition'=>['btn_radius_type'=>'custom'],'selectors'=>['{{WRAPPER}} .tbw-submit'=>'border-radius: {{SIZE}}{{UNIT}}; overflow: hidden;']]);

        // Hover
        $this->add_control('btn_hover_label_bg',['label'=>'Fondo hover texto','type'=>\Elementor\Controls_Manager::COLOR,'default'=>'#f3f4f6','selectors'=>['{{WRAPPER}} .tbw-submit:hover .tbw-btn-label'=>'background: {{VALUE}};']]);
        $this->add_control('btn_hover_icon_bg', ['label'=>'Fondo hover ícono','type'=>\Elementor\Controls_Manager::COLOR,'default'=>'#374151','selectors'=>['{{WRAPPER}} .tbw-submit:hover .tbw-btn-icon'=>'background: {{VALUE}};'],'condition'=>['btn_layout'=>'split']]);

        $this->end_controls_section();

        /* ══ ESTILO: ZONA HIGHLIGHT ══════════════════════════════ */
        $this->start_controls_section('sec_zone', ['label'=>'🎯 Resaltado de Zona','tab'=>\Elementor\Controls_Manager::TAB_STYLE]);
        $this->add_control('zone_style',   ['label'=>'Estilo','type'=>\Elementor\Controls_Manager::SELECT,'options'=>['rect'=>'Rectángulo (como captura)','ellipse'=>'Elipse'],'default'=>'rect']);
        $this->add_control('zone_border_w',['label'=>'Grosor borde','type'=>\Elementor\Controls_Manager::SLIDER,'size_units'=>['px'],'range'=>['px'=>['min'=>1,'max'=>6]],'default'=>['unit'=>'px','size'=>2]]);
        $this->add_control('zone_opacity', ['label'=>'Opacidad relleno (%)','type'=>\Elementor\Controls_Manager::SLIDER,'range'=>['px'=>['min'=>0,'max'=>100,'step'=>5]],'default'=>['size'=>25]]);
        $this->end_controls_section();
    }

    protected function render() {
        $s = $this->get_settings_for_display();

        $forced_branch = intval( $s['forced_branch'] ?? 0 );
        $settings      = $s;
        $show_pain_bar = ( $s['pain_bar_show'] ?? 'yes' ) === 'yes';
        $btn_layout    = $s['btn_layout']   ?? 'split';
        $btn_pos_class = 'tbw-btn-pos-' . ( $s['btn_position'] ?? 'right' );
        $btn_text      = esc_html( $s['btn_text'] ?? 'Agendar Ahora' );
        $wrap_class    = 'tbw-wrap' . ( ( $s['form_boxed'] ?? '' ) === 'yes' ? ' tbw-boxed' : '' );

        // Radio dinámico campos
        $eid = 'elementor-' . esc_attr( $this->get_id() );
        $rt  = $s['field_radius_type'] ?? 'custom';
        $brt = $s['btn_radius_type']   ?? 'custom';
        if ( $rt !== 'custom' || $brt !== 'custom' ) {
            $css = '';
            if ( $rt === 'round' )  $css .= "#{$eid} .tbw-control{border-radius:999px!important;}";
            if ( $rt === 'square' ) $css .= "#{$eid} .tbw-control{border-radius:0!important;}";
            if ( $brt === 'round' ) $css .= "#{$eid} .tbw-submit{border-radius:999px!important;overflow:hidden;}#{$eid} .tbw-btn-icon{border-radius:0!important;}";
            if ( $brt === 'square') $css .= "#{$eid} .tbw-submit{border-radius:0!important;}";
            echo '<style>' . $css . '</style>';
        }

        tb_enqueue_frontend_assets( $s );
        wp_add_inline_script( 'tb-script',
            'window.TB_WIDGET_SETTINGS='.json_encode([
                'zone_style'    => $s['zone_style']    ?? 'rect',
                'zone_border_w' => $s['zone_border_w']['size'] ?? 2,
                'zone_opacity'  => ($s['zone_opacity']['size'] ?? 25) / 100,
            ]).';', 'before'
        );

        include TB_PATH . 'templates/booking-form.php';
    }
}
