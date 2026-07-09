
<?php
/**
 * FORMULARIO CENTRO DE RECURSOS — CANON
 * Shortcode : [canon-formulario-centro-recursos]
 * Alias     : [canon-formulario-blog]
 * Prefijo   : ccep_
 * Sin BD    — solo envía correo vía SendGrid
 * Destinos  : usuario + leonardo.garcia@canteradigital.mx + fernando481917@gmail.com
 *
 * v6:
 *  - Reemplaza url_to_postid() por WP_Query con el slug extraído de la URL
 *  - Asunto del correo: solo "Nueva solicitud Centro de Recursos — Canon Mexico", sin URL ni titulo
 *  - Tabla origen: Sección | Categoría | Artículo | Link
 */

/* ══════════════════════════════════════════════════════════════
   HELPER — obtener datos del post desde una URL
══════════════════════════════════════════════════════════════ */
if ( ! function_exists( 'ccep_resolver_post_desde_url' ) ) {
function ccep_resolver_post_desde_url( $url ) {

    $resultado = [
        'post_titulo' => '',
        'post_url'    => $url,
        'seccion'     => '',
        'categoria'   => '',
    ];

    if ( empty( $url ) ) return $resultado;

    /* ── Extraer el slug: último segmento no vacío de la ruta ── */
    $path  = parse_url( $url, PHP_URL_PATH );
    $path  = trim( $path, '/' );
    $partes = array_filter( explode( '/', $path ) );
    $slug   = end( $partes );

    if ( empty( $slug ) ) return $resultado;

    /* ── Buscar el post por slug ── */
    $query = new WP_Query( [
        'name'           => $slug,
        'post_type'      => 'post',
        'post_status'    => 'publish',
        'posts_per_page' => 1,
        'no_found_rows'  => true,
    ] );

    if ( ! $query->have_posts() ) {
        wp_reset_postdata();
        return $resultado;
    }

    $post = $query->posts[0];
    wp_reset_postdata();

    $resultado['post_titulo'] = get_the_title( $post->ID );
    $resultado['post_url']    = get_permalink( $post->ID );

    /* ── Categorías ── */
    $categorias = get_the_category( $post->ID );
    $hijas_arr  = [];

    foreach ( $categorias as $cat ) {
        if ( $cat->parent == 0 ) {
            $resultado['seccion'] = $cat->name;   /* categoría padre */
        } else {
            $hijas_arr[] = $cat->name;             /* categorías hijas */
        }
    }

    $resultado['categoria'] = implode( ', ', $hijas_arr );

    return $resultado;
}
}


/* ══════════════════════════════════════════════════════════════
   SHORTCODE
══════════════════════════════════════════════════════════════ */
if ( ! function_exists( 'ccep_formulario_shortcode' ) ) {
function ccep_formulario_shortcode() {

    $base           = get_site_url();
    $icon_url       = $base . '/wp-content/uploads/2026/03/Icon.png';
    $url_terminos   = $base . '/terminos-y-condiciones/';
    $url_privacidad = $base . '/politicas-de-privacidad/';

    ob_start();
?>
<style>
.ccep_wrapper { width: 100%; font-family: 'proxima-nova', Arial, sans-serif; }
.ccep_title {
    font-family: 'proxima-nova', Arial, sans-serif;
    font-size: 32px; font-weight: 600; line-height: 1.2;
    color: #000000; margin: 0 0 8px 0;
}
.ccep_subtitle {
    font-family: 'proxima-nova', Arial, sans-serif;
    font-size: 18px; font-weight: 400; line-height: 1.5;
    color: #626262; margin: 0 0 24px 0;
}
.ccep_grid      { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
.ccep_grid_full { grid-column: 1 / -1; }
.ccep_input     { display: flex; flex-direction: column; }
.ccep_input input,
.ccep_input select,
.ccep_input textarea {
    width: 100%; padding: 14px 16px;
    border: 1px solid #e0e0e0; border-radius: 0;
    background: #ffffff; font-size: 15px; font-weight: 400;
    font-family: 'proxima-nova', Arial, sans-serif; color: #000000;
    appearance: none; -webkit-appearance: none;
    box-sizing: border-box; outline: none; transition: border-color 0.15s;
}
.ccep_input input::placeholder,
.ccep_input textarea::placeholder { color: #999999; }
.ccep_input input:focus,
.ccep_input select:focus,
.ccep_input textarea:focus { border-color: #CC0000; outline: none; }
.ccep_input select         { color: #999999; cursor: pointer; }
.ccep_input select.ccep_ok { color: #000000; }
.ccep_input select option  { color: #000000; }
.ccep_input select {
    background-image: url('<?php echo esc_url( $icon_url ); ?>');
    background-repeat: no-repeat;
    background-position: right 14px center;
    background-size: 18px 18px;
    padding-right: 44px;
}
.ccep_input textarea { resize: vertical; min-height: 110px; }
.ccep_input input:-webkit-autofill,
.ccep_input input:-webkit-autofill:hover,
.ccep_input input:-webkit-autofill:focus {
    -webkit-box-shadow: 0 0 0 1000px #ffffff inset !important;
    -webkit-text-fill-color: #000000 !important;
}
.ccep_input input.ccep_err,
.ccep_input select.ccep_err,
.ccep_input textarea.ccep_err { border: 2px solid #CC0000 !important; }
.ccep_field_error {
    color: #CC0000; font-size: 11px;
    font-family: 'proxima-nova', Arial, sans-serif;
    margin-top: 3px; display: none;
}
.ccep_check_wrap {
    display: flex; align-items: flex-start; gap: 8px;
    font-family: 'proxima-nova', Arial, sans-serif;
}
.ccep_check_wrap label {
    font-size: 16px; font-weight: 400; color: #626262;
    cursor: pointer; line-height: 1.4;
}
.ccep_check_wrap input[type="checkbox"] {
    margin-top: 2px; width: 15px; height: 15px;
    accent-color: #CC0000; flex-shrink: 0; cursor: pointer;
}
.ccep_check_wrap a       { color: #CC0000; text-decoration: none; }
.ccep_check_wrap a:hover { text-decoration: underline; }
.ccep_check_error {
    color: #CC0000; font-size: 11px;
    font-family: 'proxima-nova', Arial, sans-serif;
    margin-top: 3px; display: none;
}
.ccep_bottom_row {
    display: flex; align-items: center;
    justify-content: space-between; gap: 20px; margin-top: 20px;
}
.ccep_checks_col { display: flex; flex-direction: column; gap: 10px; flex: 1; }
.ccep_submit_btn {
    padding: 0 28px; height: 50px;
    background: #CC0000; color: #fff; border: none; cursor: pointer;
    font-size: 13px; font-weight: 400;
    font-family: 'proxima-nova', Arial, sans-serif;
    letter-spacing: 1px; text-transform: uppercase; border-radius: 0;
    flex-shrink: 0; transition: background 0.15s; white-space: nowrap;
    display: inline-flex; align-items: center; justify-content: center;
}
.ccep_submit_btn:hover,
.ccep_submit_btn:focus,
.ccep_submit_btn:active { background: #CC0000; color: #fff; box-shadow: none; outline: none; }
.ccep_submit_btn:disabled { background: #999 !important; cursor: not-allowed; }
.ccep_server_error {
    background: #fff0f0; border: 1px solid #CC0000; color: #CC0000;
    font-size: 13px; padding: 10px 14px; margin-top: 12px;
    display: none; text-align: center;
    font-family: 'proxima-nova', Arial, sans-serif;
}
.ccep_success {
    display: none; text-align: center; padding: 30px 20px;
    font-family: 'proxima-nova', Arial, sans-serif;
}
.ccep_success h3 { font-size: 20px; font-weight: 700; color: #000; margin-bottom: 8px; }
.ccep_success p  { font-size: 14px; color: #555; }
@media (max-width: 600px) {
    .ccep_grid       { grid-template-columns: 1fr; }
    .ccep_bottom_row { flex-direction: column; }
    .ccep_submit_btn { width: 100%; height: 48px; }
    .ccep_title      { font-size: 26px; }
    .ccep_subtitle   { font-size: 15px; }
}
</style>

<div class="ccep_wrapper">
    <div id="ccep_form_wrap">
       

        <form id="ccep_form" novalidate>
            <div class="ccep_grid">

                <div class="ccep_input">
                    <input type="text" name="ccep_nombre" placeholder="Nombre completo*" maxlength="80">
                    <span class="ccep_field_error" id="ccep_err_nombre">Este campo es obligatorio.</span>
                </div>
                <div class="ccep_input">
                    <input type="text" name="ccep_correo" placeholder="Email corporativo*" maxlength="100">
                    <span class="ccep_field_error" id="ccep_err_correo">Ingresa un correo electrónico válido.</span>
                </div>

                <div class="ccep_input">
                    <input type="text" name="ccep_telefono" placeholder="Teléfono*" maxlength="20">
                    <span class="ccep_field_error" id="ccep_err_telefono">Este campo es obligatorio.</span>
                </div>
                <div class="ccep_input">
                    <input type="text" name="ccep_compania" placeholder="Compañía" maxlength="100">
                </div>

                <div class="ccep_input">
                    <select name="ccep_estado" id="ccep_estado">
                        <option value="">Estado</option>
                        <option>Aguascalientes</option><option>Baja California</option>
                        <option>Baja California Sur</option><option>Campeche</option>
                        <option>Chiapas</option><option>Chihuahua</option>
                        <option>Ciudad de México</option><option>Coahuila</option>
                        <option>Colima</option><option>Durango</option>
                        <option>Estado de México</option><option>Guanajuato</option>
                        <option>Guerrero</option><option>Hidalgo</option>
                        <option>Jalisco</option><option>Michoacán</option>
                        <option>Morelos</option><option>Nayarit</option>
                        <option>Nuevo León</option><option>Oaxaca</option>
                        <option>Puebla</option><option>Querétaro</option>
                        <option>Quintana Roo</option><option>San Luis Potosí</option>
                        <option>Sinaloa</option><option>Sonora</option>
                        <option>Tabasco</option><option>Tamaulipas</option>
                        <option>Tlaxcala</option><option>Veracruz</option>
                        <option>Yucatán</option><option>Zacatecas</option>
                    </select>
                </div>
                <div class="ccep_input">
                    <input type="text" name="ccep_cargo" placeholder="Cargo" maxlength="80">
                </div>

                <div class="ccep_input ccep_grid_full">
                    <select name="ccep_tamano" id="ccep_tamano">
                        <option value="">Tamaño de la empresa</option>
                        <option value="Micro (0 a 10 empleados)">Micro (0 a 10 empleados)</option>
                        <option value="Pequeña (11 a 50 empleados)">Pequeña (11 a 50 empleados)</option>
                        <option value="Mediana (51 a 250 empleados)">Mediana (51 a 250 empleados)</option>
                        <option value="Grande (más de 250 empleados)">Grande (más de 250 empleados)</option>
                    </select>
                </div>

                <div class="ccep_input ccep_grid_full">
                    <textarea name="ccep_mensaje" placeholder="Mensaje adicional"></textarea>
                </div>

                <div class="ccep_grid_full">
                    <div class="ccep_bottom_row">
                        <div class="ccep_checks_col">
                            <div>
                                <div class="ccep_check_wrap">
                                    <input type="checkbox" id="ccep_terminos">
                                    <label for="ccep_terminos">He leído y acepto los <a href="<?php echo esc_url( $url_terminos ); ?>" target="_blank">Términos y Condiciones</a> y el <a href="<?php echo esc_url( $url_privacidad ); ?>" target="_blank">Aviso de Privacidad</a>.</label>
                                </div>
                                <span class="ccep_check_error" id="ccep_err_terminos">Debe aceptar los términos y condiciones para continuar.</span>
                            </div>
                            <div class="ccep_check_wrap">
                                <input type="checkbox" id="ccep_promo">
                                <label for="ccep_promo">Deseo recibir promociones, ofertas y noticias de Canon Mexicana.</label>
                            </div>
                        </div>
                        <button type="submit" class="ccep_submit_btn" id="ccep_btn_enviar">ENVIAR SOLICITUD</button>
                    </div>
                    <div class="ccep_server_error" id="ccep_server_error"></div>
                </div>

            </div>
        </form>
    </div>

    <div class="ccep_success" id="ccep_success">
        <h3>¡Solicitud enviada con éxito!</h3>
        <p>Un especialista Canon se pondrá en contacto contigo a la brevedad.</p>
    </div>
</div>

<script>
(function($){
    $(function(){
        var $form = $('#ccep_form');

        $form.find('#ccep_estado, #ccep_tamano').on('change', function(){
            $(this).toggleClass('ccep_ok', !!$(this).val());
        });

        $form.on('input change', '.ccep_err', function(){
            $(this).removeClass('ccep_err');
            $(this).closest('.ccep_input').find('.ccep_field_error').hide();
        });

        function validar() {
            var ok = true;
            var emailReg = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            var nombre = $form.find('[name="ccep_nombre"]').val().trim();
            if ( !nombre ) {
                $form.find('[name="ccep_nombre"]').addClass('ccep_err');
                $('#ccep_err_nombre').text('Este campo es obligatorio.').show();
                ok = false;
            } else {
                $form.find('[name="ccep_nombre"]').removeClass('ccep_err');
                $('#ccep_err_nombre').hide();
            }

            var correo = $form.find('[name="ccep_correo"]').val().trim();
            if ( !correo ) {
                $form.find('[name="ccep_correo"]').addClass('ccep_err');
                $('#ccep_err_correo').text('Este campo es obligatorio.').show();
                ok = false;
            } else if ( !emailReg.test(correo) || correo.indexOf('..') !== -1 ) {
                $form.find('[name="ccep_correo"]').addClass('ccep_err');
                $('#ccep_err_correo').text('Ingresa un correo electrónico válido.').show();
                ok = false;
            } else {
                $form.find('[name="ccep_correo"]').removeClass('ccep_err');
                $('#ccep_err_correo').hide();
            }

            var tel = $form.find('[name="ccep_telefono"]').val().trim();
            if ( !tel ) {
                $form.find('[name="ccep_telefono"]').addClass('ccep_err');
                $('#ccep_err_telefono').text('Este campo es obligatorio.').show();
                ok = false;
            } else {
                $form.find('[name="ccep_telefono"]').removeClass('ccep_err');
                $('#ccep_err_telefono').hide();
            }

            if ( !$('#ccep_terminos').is(':checked') ) {
                $('#ccep_err_terminos').show();
                ok = false;
            } else {
                $('#ccep_err_terminos').hide();
            }

            if ( !ok ) {
                var $first = $form.find('.ccep_err').first();
                if ( $first.length ) {
                    $('html, body').animate({ scrollTop: $first.offset().top - 100 }, 300);
                }
            }
            return ok;
        }

        $form.on('submit', function(e){
            e.preventDefault();
            if ( !validar() ) return;

            var $btn = $('#ccep_btn_enviar');
            if ( $btn.prop('disabled') ) return;

            $('#ccep_server_error').hide().text('');
            $btn.prop('disabled', true).text('ENVIANDO...');

            /* URL destino:
             * - Si el usuario hizo clic en una card de Centro de Recursos,
             *   window.ccep_dest_url contiene la URL del articulo destino.
             * - Si no (formulario de Centro de Recursos normal), usa la URL actual.
             */
            var cleanUrl = ( window.ccep_dest_url && window.ccep_dest_url !== '' )
                ? window.ccep_dest_url
                : window.location.href.split('?')[0].split('#')[0];

            var fd = new FormData();
            fd.append('action',         'ccep_enviar');
            fd.append('nombre',         $form.find('[name="ccep_nombre"]').val().trim());
            fd.append('correo',         $form.find('[name="ccep_correo"]').val().trim());
            fd.append('telefono',       $form.find('[name="ccep_telefono"]').val().trim());
            fd.append('compania',       $form.find('[name="ccep_compania"]').val().trim());
            fd.append('estado',         $form.find('[name="ccep_estado"]').val());
            fd.append('cargo',          $form.find('[name="ccep_cargo"]').val().trim());
            fd.append('tamano_empresa', $form.find('[name="ccep_tamano"]').val());
            fd.append('mensaje',        $form.find('[name="ccep_mensaje"]').val().trim());
            fd.append('promo',          $('#ccep_promo').is(':checked') ? '1' : '0');
            fd.append('page_url',       cleanUrl);

            $.ajax({
                url:         '<?php echo admin_url( 'admin-ajax.php' ); ?>',
                type:        'POST',
                data:        fd,
                processData: false,
                contentType: false,
                success: function(res){
                    if ( res && res.success === false ) {
                        $('#ccep_server_error').text(res.data || 'Ocurrió un error. Intenta de nuevo.').show();
                        $btn.prop('disabled', false).text('ENVIAR SOLICITUD');
                    } else {
                        $('#ccep_form_wrap').hide();
                        $('#ccep_success').show();
                    }
                },
                error: function(){
                    $('#ccep_server_error').text('Error de conexión. Por favor intenta de nuevo.').show();
                    $btn.prop('disabled', false).text('ENVIAR SOLICITUD');
                }
            });
        });
    });
})(jQuery);
</script>
<?php
    return ob_get_clean();
}
}

if ( ! shortcode_exists( 'canon-formulario-centro-recursos' ) ) {
    add_shortcode( 'canon-formulario-centro-recursos', 'ccep_formulario_shortcode' );
}

if ( ! shortcode_exists( 'canon-formulario-blog' ) ) {
    add_shortcode( 'canon-formulario-blog', 'ccep_formulario_shortcode' );
}


/* ══════════════════════════════════════════════════════════════
   AJAX
══════════════════════════════════════════════════════════════ */
add_action( 'wp_ajax_nopriv_ccep_enviar', 'ccep_enviar' );
add_action( 'wp_ajax_ccep_enviar',        'ccep_enviar' );

if ( ! function_exists( 'ccep_enviar' ) ) {
function ccep_enviar() {

    $nombre         = sanitize_text_field(    $_POST['nombre']         ?? '' );
    $correo         = sanitize_email(         $_POST['correo']         ?? '' );
    $telefono       = sanitize_text_field(    $_POST['telefono']       ?? '' );
    $compania       = sanitize_text_field(    $_POST['compania']       ?? '' );
    $estado         = sanitize_text_field(    $_POST['estado']         ?? '' );
    $cargo          = sanitize_text_field(    $_POST['cargo']          ?? '' );
    $tamano_empresa = sanitize_text_field(    $_POST['tamano_empresa'] ?? '' );
    $mensaje        = sanitize_textarea_field($_POST['mensaje']        ?? '' );
    $page_url       = sanitize_text_field(    $_POST['page_url']       ?? '' );

    if ( empty( $nombre ) )                          { wp_send_json_error( 'El nombre es obligatorio.' );            return; }
    if ( empty( $correo ) || ! is_email( $correo ) ) { wp_send_json_error( 'El correo electrónico no es válido.' ); return; }
    if ( empty( $telefono ) )                        { wp_send_json_error( 'El teléfono es obligatorio.' );          return; }

    /* ── Resolver post desde URL usando WP_Query por slug ── */
    $info_post = ccep_resolver_post_desde_url( $page_url );

    $data = [
        'nombre'         => $nombre,
        'correo'         => $correo,
        'telefono'       => $telefono,
        'compania'       => $compania,
        'estado'         => $estado,
        'cargo'          => $cargo,
        'tamano_empresa' => $tamano_empresa,
        'mensaje'        => $mensaje,
        'post_titulo'    => $info_post['post_titulo'],
        'post_url'       => $info_post['post_url'],
        'seccion'        => $info_post['seccion'],
        'categoria'      => $info_post['categoria'],
    ];

    ccep_enviar_sendgrid( $data );
    wp_send_json_success( 'ok' );
}
}


/* ══════════════════════════════════════════════════════════════
   SENDGRID
══════════════════════════════════════════════════════════════ */
if ( ! function_exists( 'ccep_enviar_sendgrid' ) ) {
function ccep_enviar_sendgrid( $data ) {

    $SENDGRID_API_KEY = 'CLAVE';
    $from_email       = 'canoncanterapruebas@gmail.com';

    $copias = [
        'leonardo.garcia@canteradigital.mx',
        'fernando481917@gmail.com',
    ];

    /* ── Correo al USUARIO ── */
    $html_usuario = function_exists( 'ccep_email_template_blog' )
        ? ccep_email_template_blog( $data, false )
        : '<p>Hemos recibido tu solicitud. Un especialista Canon se pondrá en contacto contigo.</p>';

    wp_remote_post( 'https://api.sendgrid.com/v3/mail/send', [
        'headers' => [
            'Authorization' => 'Bearer ' . $SENDGRID_API_KEY,
            'Content-Type'  => 'application/json',
        ],
        'body' => json_encode( [
            'personalizations' => [ [ 'to' => [ [ 'email' => $data['correo'] ] ] ] ],
            'from'    => [ 'email' => $from_email, 'name' => 'Canon México' ],
            'subject' => 'Hemos recibido tu solicitud — Canon México',
            'content' => [ [ 'type' => 'text/html', 'value' => $html_usuario ] ],
        ] ),
        'method'  => 'POST',
        'timeout' => 20,
    ] );

    /* ── Correo a COPIAS ── */
    $html_copias = function_exists( 'ccep_email_template_blog' )
        ? ccep_email_template_blog( $data, true )
        : '<p>Nueva solicitud Centro de Recursos — Canon Mexico</p>';

    /* Asunto limpio: solo texto fijo, sin URL ni titulo */
    $asunto_copias = 'Nueva solicitud Centro de Recursos — Canon Mexico';

    $tos_copias = array_map( function( $e ) { return [ 'email' => $e ]; }, $copias );

    wp_remote_post( 'https://api.sendgrid.com/v3/mail/send', [
        'headers' => [
            'Authorization' => 'Bearer ' . $SENDGRID_API_KEY,
            'Content-Type'  => 'application/json',
        ],
        'body' => json_encode( [
            'personalizations' => [ [ 'to' => $tos_copias ] ],
            'from'    => [ 'email' => $from_email, 'name' => 'Canon México' ],
            'subject' => $asunto_copias,
            'content' => [ [ 'type' => 'text/html', 'value' => $html_copias ] ],
        ] ),
        'method'  => 'POST',
        'timeout' => 20,
    ] );
}
}