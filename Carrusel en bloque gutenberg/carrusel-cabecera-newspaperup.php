<?php
/**
 * Plugin Name: Carrusel en Cabecera para Newspaperup
 * Plugin URI: https://wordpress.org/
 * Description: Convierte el banner estático del header del tema Newspaperup en una zona de widgets (slider) dinámica.
 * Version: 1.0.0
 * Author: BaltazarDev
 * License: GPL2
 */

// Evitar acceso directo
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// 1. Registrar el área de widgets para el Slider en el Header
function cp_registrar_publicidad_cabecera_slider() {
    register_sidebar( array(
        'name'          => 'Publicidad de Cabecera (Slider - Plugin)',
        'id'            => 'header-ad-slider-plugin',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title sr-only">',
        'after_title'   => '</h3>',
    ) );
}
add_action( 'widgets_init', 'cp_registrar_publicidad_cabecera_slider' );

// 2. Sobrescribir la función pluggable del tema Newspaperup
if ( ! function_exists( 'newspaperup_banner_advertisement' ) ) :
    function newspaperup_banner_advertisement() {
        if ( is_active_sidebar( 'header-ad-slider-plugin' ) ) :
            ?>
            <div class="advertising-banner"> 
                <?php dynamic_sidebar( 'header-ad-slider-plugin' ); ?>
            </div>
            <?php
        else :
            // Fallback original
            if ( '' != newspaperup_get_option( 'banner_ad_image' ) ) {
                $newspaperup_banner_advertisement = newspaperup_get_option( 'banner_ad_image' );
                $newspaperup_banner_advertisement = absint( $newspaperup_banner_advertisement );
                $newspaperup_banner_advertisement = wp_get_attachment_image( $newspaperup_banner_advertisement, 'full' );
                $banner_ad_url = newspaperup_get_option( 'banner_ad_url' );
                $banner_open_on_new_tab = newspaperup_get_option( 'banner_open_on_new_tab' );
                $banner_open_on_new_tab = ( '' != $banner_open_on_new_tab ) ? '_blank' : '';
                ?>
                <div class="advertising-banner"> 
                    <a class="pull-right img-fluid" href="<?php echo esc_url( $banner_ad_url ); ?>" target="<?php echo esc_attr( $banner_open_on_new_tab ); ?>">
                        <?php echo $newspaperup_banner_advertisement; ?>
                    </a>  
                </div>
                <?php
            }
        endif;
    }
endif;

// 3. Registrar controles en el Personalizador (Customizer) para el Carrusel
function cp_registrar_customizer_carrusel( $wp_customize ) {
    // Añadir sección
    $wp_customize->add_section( 'carrusel_cabecera_section', array(
        'title'       => __( 'Ajustes del Carrusel de Cabecera', 'carrusel-cabecera' ),
        'priority'    => 30,
        'description' => __( 'Personaliza el diseño, tamaño y alineación del carrusel de cabecera en PC, Tablet y Móvil.', 'carrusel-cabecera' ),
    ) );

    // ---- CONFIGURACIÓN DE ANCHO ----
    // PC Ancho
    $wp_customize->add_setting( 'carrusel_width_desktop', array(
        'default'           => '100%',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'carrusel_width_desktop', array(
        'label'       => __( 'Ancho en PC (Escritorio)', 'carrusel-cabecera' ),
        'description' => __( 'Ej: 100%, 800px, 70vw', 'carrusel-cabecera' ),
        'section'     => 'carrusel_cabecera_section',
        'type'        => 'text',
    ) );

    // Tablet Ancho
    $wp_customize->add_setting( 'carrusel_width_tablet', array(
        'default'           => '100%',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'carrusel_width_tablet', array(
        'label'       => __( 'Ancho en Tablet', 'carrusel-cabecera' ),
        'section'     => 'carrusel_cabecera_section',
        'type'        => 'text',
    ) );

    // Móvil Ancho
    $wp_customize->add_setting( 'carrusel_width_mobile', array(
        'default'           => '100%',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'carrusel_width_mobile', array(
        'label'       => __( 'Ancho en Móvil', 'carrusel-cabecera' ),
        'section'     => 'carrusel_cabecera_section',
        'type'        => 'text',
    ) );


    // ---- CONFIGURACIÓN DE ALTURA ----
    // PC Altura
    $wp_customize->add_setting( 'carrusel_height_desktop', array(
        'default'           => 'auto',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'carrusel_height_desktop', array(
        'label'       => __( 'Altura en PC (Escritorio)', 'carrusel-cabecera' ),
        'description' => __( 'Ej: auto, 150px, 12vw', 'carrusel-cabecera' ),
        'section'     => 'carrusel_cabecera_section',
        'type'        => 'text',
    ) );

    // Tablet Altura
    $wp_customize->add_setting( 'carrusel_height_tablet', array(
        'default'           => 'auto',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'carrusel_height_tablet', array(
        'label'       => __( 'Altura en Tablet', 'carrusel-cabecera' ),
        'section'     => 'carrusel_cabecera_section',
        'type'        => 'text',
    ) );

    // Móvil Altura
    $wp_customize->add_setting( 'carrusel_height_mobile', array(
        'default'           => 'auto',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'carrusel_height_mobile', array(
        'label'       => __( 'Altura en Móvil', 'carrusel-cabecera' ),
        'section'     => 'carrusel_cabecera_section',
        'type'        => 'text',
    ) );


    // ---- RELACIÓN DE ASPECTO (Altura Proporcional) ----
    // PC Relación de Aspecto
    $wp_customize->add_setting( 'carrusel_aspect_desktop', array(
        'default'           => 'auto',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'carrusel_aspect_desktop', array(
        'label'       => __( 'Relación de Aspecto en PC', 'carrusel-cabecera' ),
        'description' => __( 'Ej: auto, 21/9, 16/9, 3/1. Se usa cuando la Altura es "auto".', 'carrusel-cabecera' ),
        'section'     => 'carrusel_cabecera_section',
        'type'        => 'text',
    ) );

    // Tablet Relación de Aspecto
    $wp_customize->add_setting( 'carrusel_aspect_tablet', array(
        'default'           => 'auto',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'carrusel_aspect_tablet', array(
        'label'       => __( 'Relación de Aspecto en Tablet', 'carrusel-cabecera' ),
        'section'     => 'carrusel_cabecera_section',
        'type'        => 'text',
    ) );

    // Móvil Relación de Aspecto
    $wp_customize->add_setting( 'carrusel_aspect_mobile', array(
        'default'           => 'auto',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'carrusel_aspect_mobile', array(
        'label'       => __( 'Relación de Aspecto en Móvil', 'carrusel-cabecera' ),
        'section'     => 'carrusel_cabecera_section',
        'type'        => 'text',
    ) );


    // ---- CONFIGURACIÓN DE ALINEACIÓN ----
    // PC Alineación
    $wp_customize->add_setting( 'carrusel_align_desktop', array(
        'default'           => 'right',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'carrusel_align_desktop', array(
        'label'   => __( 'Alineación en PC (Escritorio)', 'carrusel-cabecera' ),
        'section' => 'carrusel_cabecera_section',
        'type'    => 'select',
        'choices' => array(
            'left'   => __( 'Izquierda', 'carrusel-cabecera' ),
            'center' => __( 'Centro', 'carrusel-cabecera' ),
            'right'  => __( 'Derecha', 'carrusel-cabecera' ),
        ),
    ) );

    // Tablet Alineación
    $wp_customize->add_setting( 'carrusel_align_tablet', array(
        'default'           => 'center',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'carrusel_align_tablet', array(
        'label'   => __( 'Alineación en Tablet', 'carrusel-cabecera' ),
        'section' => 'carrusel_cabecera_section',
        'type'    => 'select',
        'choices' => array(
            'left'   => __( 'Izquierda', 'carrusel-cabecera' ),
            'center' => __( 'Centro', 'carrusel-cabecera' ),
            'right'  => __( 'Derecha', 'carrusel-cabecera' ),
        ),
    ) );

    // Móvil Alineación
    $wp_customize->add_setting( 'carrusel_align_mobile', array(
        'default'           => 'center',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'carrusel_align_mobile', array(
        'label'   => __( 'Alineación en Móvil', 'carrusel-cabecera' ),
        'section' => 'carrusel_cabecera_section',
        'type'    => 'select',
        'choices' => array(
            'left'   => __( 'Izquierda', 'carrusel-cabecera' ),
            'center' => __( 'Centro', 'carrusel-cabecera' ),
            'right'  => __( 'Derecha', 'carrusel-cabecera' ),
        ),
    ) );


    // ---- AJUSTES DE IMAGEN (OBJECT FIT) ----
    $wp_customize->add_setting( 'carrusel_img_fit', array(
        'default'           => 'cover',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'carrusel_img_fit', array(
        'label'       => __( 'Ajuste de Imagen (Object Fit)', 'carrusel-cabecera' ),
        'description' => __( 'Define cómo se adaptan las imágenes al contenedor del carrusel.', 'carrusel-cabecera' ),
        'section'     => 'carrusel_cabecera_section',
        'type'        => 'select',
        'choices'     => array(
            'cover'   => __( 'Cover (Recortar para llenar sin deformar)', 'carrusel-cabecera' ),
            'contain' => __( 'Contain (Mostrar entera con espacios)', 'carrusel-cabecera' ),
            'fill'    => __( 'Fill (Estirar para llenar)', 'carrusel-cabecera' ),
            'none'    => __( 'Original (Sin ajuste especial)', 'carrusel-cabecera' ),
        ),
    ) );


    // ---- FORZAR TAMAÑO HIJOS ----
    $wp_customize->add_setting( 'carrusel_force_children', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ) );
    $wp_customize->add_control( 'carrusel_force_children', array(
        'label'       => __( 'Forzar tamaño en contenedores internos', 'carrusel-cabecera' ),
        'description' => __( 'Obliga a los sliders y widgets internos a llenar el 100% del alto y ancho configurado (Recomendado).', 'carrusel-cabecera' ),
        'section'     => 'carrusel_cabecera_section',
        'type'        => 'checkbox',
    ) );
}
add_action( 'customize_register', 'cp_registrar_customizer_carrusel' );


// 4. Inyectar CSS Dinámico basado en las opciones del Personalizador
function cp_inyectar_css_carrusel() {
    $width_desktop  = get_theme_mod( 'carrusel_width_desktop', '100%' );
    $width_tablet   = get_theme_mod( 'carrusel_width_tablet', '100%' );
    $width_mobile   = get_theme_mod( 'carrusel_width_mobile', '100%' );

    $height_desktop = get_theme_mod( 'carrusel_height_desktop', 'auto' );
    $height_tablet  = get_theme_mod( 'carrusel_height_tablet', 'auto' );
    $height_mobile  = get_theme_mod( 'carrusel_height_mobile', 'auto' );

    $aspect_desktop = get_theme_mod( 'carrusel_aspect_desktop', 'auto' );
    $aspect_tablet  = get_theme_mod( 'carrusel_aspect_tablet', 'auto' );
    $aspect_mobile  = get_theme_mod( 'carrusel_aspect_mobile', 'auto' );

    $align_desktop  = get_theme_mod( 'carrusel_align_desktop', 'right' );
    $align_tablet   = get_theme_mod( 'carrusel_align_tablet', 'center' );
    $align_mobile   = get_theme_mod( 'carrusel_align_mobile', 'center' );

    $img_fit        = get_theme_mod( 'carrusel_img_fit', 'cover' );
    $force_children = get_theme_mod( 'carrusel_force_children', true );
    ?>
    <style id="carrusel-cabecera-newspaperup-dinamico">
        /* Estilos base para el contenedor del Banner de Publicidad */
        .advertising-banner {
            display: flex !important;
            flex-direction: column !important;
            box-sizing: border-box !important;
        }

        /* --- ESCRITORIO (PC) --- */
        @media (min-width: 992px) {
            .advertising-banner {
                width: <?php echo esc_html( $width_desktop ); ?> !important;
                height: <?php echo esc_html( $height_desktop ); ?> !important;
                aspect-ratio: <?php echo esc_html( $aspect_desktop ); ?> !important;
                <?php if ( $align_desktop === 'left' ) : ?>
                    margin-left: 0 !important;
                    margin-right: auto !important;
                    align-items: flex-start !important;
                <?php elseif ( $align_desktop === 'center' ) : ?>
                    margin-left: auto !important;
                    margin-right: auto !important;
                    align-items: center !important;
                <?php else : ?>
                    margin-left: auto !important;
                    margin-right: 0 !important;
                    align-items: flex-end !important;
                <?php endif; ?>
            }
        }

        /* --- TABLET --- */
        @media (max-width: 991px) and (min-width: 768px) {
            .advertising-banner {
                width: <?php echo esc_html( $width_tablet ); ?> !important;
                height: <?php echo esc_html( $height_tablet ); ?> !important;
                aspect-ratio: <?php echo esc_html( $aspect_tablet ); ?> !important;
                <?php if ( $align_tablet === 'left' ) : ?>
                    margin-left: 0 !important;
                    margin-right: auto !important;
                    align-items: flex-start !important;
                <?php elseif ( $align_tablet === 'center' ) : ?>
                    margin-left: auto !important;
                    margin-right: auto !important;
                    align-items: center !important;
                <?php else : ?>
                    margin-left: auto !important;
                    margin-right: 0 !important;
                    align-items: flex-end !important;
                <?php endif; ?>
            }
        }

        /* --- MÓVIL --- */
        @media (max-width: 767px) {
            .advertising-banner {
                width: <?php echo esc_html( $width_mobile ); ?> !important;
                height: <?php echo esc_html( $height_mobile ); ?> !important;
                aspect-ratio: <?php echo esc_html( $aspect_mobile ); ?> !important;
                <?php if ( $align_mobile === 'left' ) : ?>
                    margin-left: 0 !important;
                    margin-right: auto !important;
                    align-items: flex-start !important;
                <?php elseif ( $align_mobile === 'center' ) : ?>
                    margin-left: auto !important;
                    margin-right: auto !important;
                    align-items: center !important;
                <?php else : ?>
                    margin-left: auto !important;
                    margin-right: 0 !important;
                    align-items: flex-end !important;
                <?php endif; ?>
            }
        }

        <?php if ( $force_children ) : ?>
        /* Forzar que los widgets y sliders internos ocupen el 100% de la caja adaptada */
        .advertising-banner > *,
        .advertising-banner .widget,
        .advertising-banner .widget > div,
        .advertising-banner .widget > div > div,
        .advertising-banner .swiper-container,
        .advertising-banner .swiper-wrapper,
        .advertising-banner .swiper-slide {
            width: 100% !important;
            height: 100% !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
        }
        <?php endif; ?>

        /* Forzar visualización de imágenes según object-fit */
        .advertising-banner img {
            width: 100% !important;
            height: 100% !important;
            max-width: 100% !important;
            object-fit: <?php echo esc_html( $img_fit ); ?> !important;
        }
    </style>
    <?php
}
add_action( 'wp_head', 'cp_inyectar_css_carrusel', 100 );

