<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class TB_Elementor_Widget extends \Elementor\Widget_Base {

    public function get_name()  { return 'tattoo_booking'; }
    public function get_title() { return 'Tattoo Booking'; }
    public function get_icon()  { return 'eicon-calendar'; }
    public function get_categories() { return ['tattoo-booking','general']; }

    protected function register_controls() {

        // ── CONTENIDO ─────────────────────────────────────────────
        $this->start_controls_section('sec_content', ['label'=>'Contenido','tab'=>\Elementor\Controls_Manager::TAB_CONTENT]);

        $this->add_control('form_title',['label'=>'Título','type'=>\Elementor\Controls_Manager::TEXT,'default'=>'Agenda tu Cita','label_block'=>true]);
        $this->add_control('form_subtitle',['label'=>'Subtítulo','type'=>\Elementor\Controls_Manager::TEXT,'default'=>'Reserva en línea · Sin esperas']);
        $this->add_control('btn_text',['label'=>'Texto del botón','type'=>\Elementor\Controls_Manager::TEXT,'default'=>'Agendar Ahora ↗']);
        $this->add_control('forced_branch',['label'=>'Forzar sucursal (ID)','type'=>\Elementor\Controls_Manager::NUMBER,'min'=>0,'default'=>0,'description'=>'Deja en 0 para mostrar todas las sucursales activas.']);

        $this->end_controls_section();

        // ── IMÁGENES ──────────────────────────────────────────────
        $this->start_controls_section('sec_images', ['label'=>'Imágenes Corporales','tab'=>\Elementor\Controls_Manager::TAB_CONTENT]);

        $this->add_control('img_male',['label'=>'Imagen Masculino','type'=>\Elementor\Controls_Manager::MEDIA,'default'=>['url'=>''],'description'=>'PNG fondo transparente recomendado.']);
        $this->add_group_control(\Elementor\Group_Control_Image_Size::get_type(), ['name'=>'img_male_size','default'=>'large','separator'=>'none']);
        
        $this->add_control('img_female',['label'=>'Imagen Femenino','type'=>\Elementor\Controls_Manager::MEDIA,'default'=>['url'=>'']]);
        $this->add_group_control(\Elementor\Group_Control_Image_Size::get_type(), ['name'=>'img_female_size','default'=>'large','separator'=>'none']);

        $this->add_responsive_control('img_height',['label'=>'Alto de imagen','type'=>\Elementor\Controls_Manager::SLIDER,'size_units'=>['px','vh'],'range'=>['px'=>['min'=>200,'max'=>1000],'vh'=>['min'=>20,'max'=>100]],'default'=>['unit'=>'px','size'=>380],'selectors'=>['{{WRAPPER}} .tb-body-photo'=>'max-height: {{SIZE}}{{UNIT}};']]);
        $this->add_responsive_control('img_width',['label'=>'Ancho máx. imagen','type'=>\Elementor\Controls_Manager::SLIDER,'size_units'=>['px','%'],'range'=>['px'=>['min'=>100,'max'=>1000],'%'=>['min'=>20,'max'=>100]],'default'=>['unit'=>'%','size'=>100],'selectors'=>['{{WRAPPER}} .tb-img-container'=>'max-width: {{SIZE}}{{UNIT}};']]);

        $this->end_controls_section();

        // ── ESTILO: LAYOUT ────────────────────────────────────────
        $this->start_controls_section('sec_layout', ['label'=>'Layout','tab'=>\Elementor\Controls_Manager::TAB_STYLE]);

        $this->add_control('bg_color',['label'=>'Fondo del widget','type'=>\Elementor\Controls_Manager::COLOR,'default'=>'transparent','selectors'=>['{{WRAPPER}} .tbw-wrap'=>'background: {{VALUE}};']]);
        $this->add_control('body_col_bg',['label'=>'Fondo columna imagen','type'=>\Elementor\Controls_Manager::COLOR,'default'=>'transparent','selectors'=>['{{WRAPPER}} .tbw-col-body'=>'background: {{VALUE}};']]);
        $this->add_responsive_control('col_gap',['label'=>'Separación columnas','type'=>\Elementor\Controls_Manager::SLIDER,'size_units'=>['px'],'range'=>['px'=>['min'=>0,'max'=>100]],'default'=>['unit'=>'px','size'=>40],'selectors'=>['{{WRAPPER}} .tbw-layout'=>'gap: {{SIZE}}{{UNIT}};']]);
        $this->add_responsive_control('form_padding', ['label'=>'Padding formulario','type'=>\Elementor\Controls_Manager::DIMENSIONS,'size_units'=>['px','em','%'],'selectors'=>['{{WRAPPER}} .tbw-col-form'=>'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']]);
        $this->add_control('border_radius',['label'=>'Radio borde widget','type'=>\Elementor\Controls_Manager::SLIDER,'size_units'=>['px'],'range'=>['px'=>['min'=>0,'max'=>100]],'default'=>['unit'=>'px','size'=>0],'selectors'=>['{{WRAPPER}} .tbw-wrap'=>'border-radius: {{SIZE}}{{UNIT}};']]);

        $this->end_controls_section();

        // ── ESTILO: BARRA DE DOLOR ────────────────────────────────
        $this->start_controls_section('sec_pain_bar', ['label'=>'Barra de Dolor','tab'=>\Elementor\Controls_Manager::TAB_STYLE]);

        $this->add_control('pain_bar_show',['label'=>'Mostrar barra','type'=>\Elementor\Controls_Manager::SWITCHER,'label_on'=>'Sí','label_off'=>'No','return_value'=>'yes','default'=>'yes']);
        $this->add_responsive_control('pain_bar_height',['label'=>'Altura barra','type'=>\Elementor\Controls_Manager::SLIDER,'size_units'=>['px'],'range'=>['px'=>['min'=>4,'max'=>50]],'default'=>['unit'=>'px','size'=>12],'selectors'=>['{{WRAPPER}} .tbw-pain-bar'=>'height: {{SIZE}}{{UNIT}};']]);
        $this->add_control('pain_label_color',['label'=>'Color etiquetas','type'=>\Elementor\Controls_Manager::COLOR,'default'=>'#6b7280','selectors'=>['{{WRAPPER}} .tbw-pain-labels span'=>'color: {{VALUE}};']]);
        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(),['name'=>'pain_label_typo','label'=>'Tipografía etiquetas','selector'=>'{{WRAPPER}} .tbw-pain-labels span']);

        $this->end_controls_section();

        // ── ESTILO: TIPOGRAFÍA ────────────────────────────────────
        $this->start_controls_section('sec_typo', ['label'=>'Tipografía y Colores','tab'=>\Elementor\Controls_Manager::TAB_STYLE]);

        $this->add_control('head_title', ['label'=>'TÍTULO FORMULARIO','type'=>\Elementor\Controls_Manager::HEADING]);
        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(),['name'=>'title_typo','selector'=>'{{WRAPPER}} .tbw-title']);
        $this->add_control('title_color',['label'=>'Color título','type'=>\Elementor\Controls_Manager::COLOR,'default'=>'#111827','selectors'=>['{{WRAPPER}} .tbw-title'=>'color: {{VALUE}};']]);

        $this->add_control('head_subtitle', ['label'=>'SUBTÍTULO','type'=>\Elementor\Controls_Manager::HEADING,'separator'=>'before']);
        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(),['name'=>'subtitle_typo','selector'=>'{{WRAPPER}} .tbw-subtitle']);
        $this->add_control('subtitle_color',['label'=>'Color subtítulo','type'=>\Elementor\Controls_Manager::COLOR,'default'=>'#9ca3af','selectors'=>['{{WRAPPER}} .tbw-subtitle'=>'color: {{VALUE}};']]);

        $this->add_control('head_labels', ['label'=>'ETIQUETAS DE CAMPO','type'=>\Elementor\Controls_Manager::HEADING,'separator'=>'before']);
        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(),['name'=>'label_typo','selector'=>'{{WRAPPER}} .tbw-label']);
        $this->add_control('label_color',['label'=>'Color etiquetas','type'=>\Elementor\Controls_Manager::COLOR,'default'=>'#6b7280','selectors'=>['{{WRAPPER}} .tbw-label'=>'color: {{VALUE}};']]);

        $this->end_controls_section();

        // ── ESTILO: CAMPOS ────────────────────────────────────────
        $this->start_controls_section('sec_fields', ['label'=>'Campos del formulario','tab'=>\Elementor\Controls_Manager::TAB_STYLE]);

        $this->add_control('field_bg',['label'=>'Fondo','type'=>\Elementor\Controls_Manager::COLOR,'default'=>'#f9fafb','selectors'=>['{{WRAPPER}} .tbw-control'=>'background: {{VALUE}};']]);
        $this->add_control('field_border_color',['label'=>'Color borde','type'=>\Elementor\Controls_Manager::COLOR,'default'=>'#d1d5db','selectors'=>['{{WRAPPER}} .tbw-control'=>'border-color: {{VALUE}};']]);
        $this->add_control('field_text_color',['label'=>'Color texto','type'=>\Elementor\Controls_Manager::COLOR,'default'=>'#111827','selectors'=>['{{WRAPPER}} .tbw-control'=>'color: {{VALUE}};']]);
        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(),['name'=>'field_typo','label'=>'Tipografía campos','selector'=>'{{WRAPPER}} .tbw-control']);
        $this->add_control('field_radius',['label'=>'Radio borde','type'=>\Elementor\Controls_Manager::SLIDER,'size_units'=>['px'],'range'=>['px'=>['min'=>0,'max'=>50]],'default'=>['unit'=>'px','size'=>8],'selectors'=>['{{WRAPPER}} .tbw-control'=>'border-radius: {{SIZE}}{{UNIT}};']]);
        $this->add_responsive_control('field_padding',['label'=>'Padding campos','type'=>\Elementor\Controls_Manager::DIMENSIONS,'size_units'=>['px'],'default'=>['top'=>'9','right'=>'13','bottom'=>'9','left'=>'13','unit'=>'px','isLinked'=>false],'selectors'=>['{{WRAPPER}} .tbw-control'=>'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']]);

        $this->end_controls_section();

        // ── ESTILO: GÉNERO TOGGLE ─────────────────────────────────
        $this->start_controls_section('sec_gender', ['label'=>'Botones de Género','tab'=>\Elementor\Controls_Manager::TAB_STYLE]);

        $this->add_control('gender_bg',['label'=>'Fondo inactivo','type'=>\Elementor\Controls_Manager::COLOR,'default'=>'#f9fafb','selectors'=>['{{WRAPPER}} .tbw-gender-pill'=>'background: {{VALUE}};']]);
        $this->add_control('gender_color',['label'=>'Texto inactivo','type'=>\Elementor\Controls_Manager::COLOR,'default'=>'#6b7280','selectors'=>['{{WRAPPER}} .tbw-gender-pill'=>'color: {{VALUE}};']]);
        $this->add_control('gender_active_bg',['label'=>'Fondo activo','type'=>\Elementor\Controls_Manager::COLOR,'default'=>'#111827','selectors'=>['{{WRAPPER}} .tbw-gender-pill.active'=>'background: {{VALUE}};']]);
        $this->add_control('gender_active_color',['label'=>'Texto activo','type'=>\Elementor\Controls_Manager::COLOR,'default'=>'#ffffff','selectors'=>['{{WRAPPER}} .tbw-gender-pill.active'=>'color: {{VALUE}};']]);
        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(),['name'=>'gender_typo','selector'=>'{{WRAPPER}} .tbw-gender-pill']);
        $this->add_control('gender_radius',['label'=>'Radio borde','type'=>\Elementor\Controls_Manager::SLIDER,'size_units'=>['px'],'range'=>['px'=>['min'=>0,'max'=>50]],'default'=>['unit'=>'px','size'=>8],'selectors'=>['{{WRAPPER}} .tbw-gender'=>'border-radius: {{SIZE}}{{UNIT}};']]);

        $this->end_controls_section();

        // ── ESTILO: BOTÓN ENVIAR ──────────────────────────────────
        $this->start_controls_section('sec_btn', ['label'=>'Botón Agendar','tab'=>\Elementor\Controls_Manager::TAB_STYLE]);

        $this->add_responsive_control('btn_align',['label'=>'Alineación','type'=>\Elementor\Controls_Manager::CHOOSE,'options'=>['left'=>['title'=>'Izquierda','icon'=>'eicon-text-align-left'],'center'=>['title'=>'Centro','icon'=>'eicon-text-align-center'],'right'=>['title'=>'Derecha','icon'=>'eicon-text-align-right']],'default'=>'right','selectors'=>['{{WRAPPER}} .tbw-submit-row'=>'justify-content: {{VALUE === "left" ? "flex-start" : (VALUE === "right" ? "flex-end" : "center")}};']]);
        $this->add_control('btn_full_width', ['label'=>'Ancho completo','type'=>\Elementor\Controls_Manager::SWITCHER,'label_on'=>'Sí','label_off'=>'No','return_value'=>'yes','default'=>'no','selectors'=>['{{WRAPPER}} .tbw-submit'=>'width: 100%; justify-content: center;']]);

        $this->add_control('btn_bg',['label'=>'Fondo','type'=>\Elementor\Controls_Manager::COLOR,'default'=>'#111827','selectors'=>['{{WRAPPER}} .tbw-submit'=>'background: {{VALUE}};']]);
        $this->add_control('btn_color',['label'=>'Color texto','type'=>\Elementor\Controls_Manager::COLOR,'default'=>'#ffffff','selectors'=>['{{WRAPPER}} .tbw-submit'=>'color: {{VALUE}};']]);
        $this->add_control('btn_bg_hover',['label'=>'Fondo hover','type'=>\Elementor\Controls_Manager::COLOR,'default'=>'#374151','selectors'=>['{{WRAPPER}} .tbw-submit:hover'=>'background: {{VALUE}};']]);
        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(),['name'=>'btn_typo','selector'=>'{{WRAPPER}} .tbw-submit']);
        $this->add_control('btn_radius',['label'=>'Radio borde','type'=>\Elementor\Controls_Manager::SLIDER,'size_units'=>['px'],'range'=>['px'=>['min'=>0,'max'=>50]],'default'=>['unit'=>'px','size'=>8],'selectors'=>['{{WRAPPER}} .tbw-submit'=>'border-radius: {{SIZE}}{{UNIT}};']]);
        $this->add_responsive_control('btn_padding',['label'=>'Padding','type'=>\Elementor\Controls_Manager::DIMENSIONS,'size_units'=>['px'],'default'=>['top'=>'13','right'=>'20','bottom'=>'13','left'=>'20','unit'=>'px','isLinked'=>false],'selectors'=>['{{WRAPPER}} .tbw-submit'=>'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']]);

        $this->end_controls_section();

        // ── ESTILO: CALLOUT (ZONA) ────────────────────────────────
        $this->start_controls_section('sec_callout', ['label'=>'Etiqueta flotante (Zona)','tab'=>\Elementor\Controls_Manager::TAB_STYLE]);

        $this->add_control('callout_bg',['label'=>'Fondo','type'=>\Elementor\Controls_Manager::COLOR,'default'=>'rgba(255,255,255,0.95)','selectors'=>['{{WRAPPER}} .tbw-callout-box'=>'background: {{VALUE}};']]);
        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(),['name'=>'callout_name_typo','label'=>'Tipografía nombre','selector'=>'{{WRAPPER}} .tbw-callout-name']);
        $this->add_control('callout_name_color',['label'=>'Color nombre','type'=>\Elementor\Controls_Manager::COLOR,'default'=>'#111827','selectors'=>['{{WRAPPER}} .tbw-callout-name'=>'color: {{VALUE}};']]);
        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(),['name'=>'callout_pain_typo','label'=>'Tipografía dolor','selector'=>'{{WRAPPER}} .tbw-callout-pain']);
        $this->add_control('callout_radius',['label'=>'Radio borde','type'=>\Elementor\Controls_Manager::SLIDER,'size_units'=>['px'],'range'=>['px'=>['min'=>0,'max'=>20]],'default'=>['unit'=>'px','size'=>4],'selectors'=>['{{WRAPPER}} .tbw-callout-box'=>'border-radius: {{SIZE}}{{UNIT}};']]);

        $this->end_controls_section();

        // ── ESTILO: ZONA HIGHLIGHT ────────────────────────────────
        $this->start_controls_section('sec_zone', ['label'=>'Resaltado de Zona','tab'=>\Elementor\Controls_Manager::TAB_STYLE]);

        $this->add_control('zone_style',['label'=>'Estilo del recuadro','type'=>\Elementor\Controls_Manager::SELECT,'options'=>['rect'=>'Rectángulo (como captura)','ellipse'=>'Elipse'],'default'=>'rect']);
        $this->add_control('zone_border_w',['label'=>'Grosor borde','type'=>\Elementor\Controls_Manager::SLIDER,'size_units'=>['px'],'range'=>['px'=>['min'=>1,'max'=>10]],'default'=>['unit'=>'px','size'=>2]]);
        $this->add_control('zone_opacity',['label'=>'Opacidad relleno','type'=>\Elementor\Controls_Manager::SLIDER,'range'=>['px'=>['min'=>0,'max'=>100,'step'=>5]],'default'=>['size'=>30]]);

        $this->end_controls_section();

    }

    protected function render() {
        $s             = $this->get_settings_for_display();
        $forced_branch = intval($s['forced_branch'] ?? 0);
        
        // Resolver URLs de imagen según el tamaño seleccionado
        $img_male_url   = \Elementor\Group_Control_Image_Size::get_attachment_image_src($s['img_male']['id'] ?? 0, 'img_male_size', $s);
        $img_female_url = \Elementor\Group_Control_Image_Size::get_attachment_image_src($s['img_female']['id'] ?? 0, 'img_female_size', $s);

        if ( ! $img_male_url )   $img_male_url   = $s['img_male']['url'];
        if ( ! $img_female_url ) $img_female_url = $s['img_female']['url'];

        // Enviar URLs resueltas a tb_enqueue_frontend_assets
        $s['resolved_img_male']   = $img_male_url;
        $s['resolved_img_female'] = $img_female_url;

        tb_enqueue_frontend_assets($s);

        // Pasar config de zona al JS
        wp_add_inline_script('tb-script',
            'var TB_WIDGET_SETTINGS = '.json_encode([
                'zone_style'   => $s['zone_style']   ?? 'rect',
                'zone_border_w'=> $s['zone_border_w']['size'] ?? 2,
                'zone_opacity' => ($s['zone_opacity']['size'] ?? 30) / 100,
            ]).';', 'before'
        );

        $show_pain_bar = ($s['pain_bar_show'] ?? 'yes') === 'yes';
        $settings      = $s; // para el template
        include TB_PATH . 'templates/booking-form.php';
    }
}
