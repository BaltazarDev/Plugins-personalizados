<?php
/**
 * Modal de acceso a contenido - Canon (nuevo)
 * Shortcode modal: [ccr_gate_modal]
 * Shortcode boton: [ccr_gate_modal_trigger text="Descargar" url="https://..." class="mi-clase"]
 * Formulario dentro del modal: [canon-formulario-centro-recursos]
 */

/* ══════════════════════════════════════════════════════════════
   FORMULARIO + AJAX + SENDGRID INTEGRADOS EN ESTE MISMO SNIPPET
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

    $path   = parse_url( $url, PHP_URL_PATH );
    $path   = trim( $path, '/' );
    $partes = array_filter( explode( '/', $path ) );
    $slug   = end( $partes );

    if ( empty( $slug ) ) return $resultado;

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

    $categorias = get_the_category( $post->ID );
    $hijas_arr  = [];

    foreach ( $categorias as $cat ) {
        if ( $cat->parent == 0 ) {
            $resultado['seccion'] = $cat->name;
        } else {
            $hijas_arr[] = $cat->name;
        }
    }

    $resultado['categoria'] = implode( ', ', $hijas_arr );

    return $resultado;
}
}

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
                    <span class="ccep_field_error" id="ccep_err_correo">Ingresa un correo electronico valido.</span>
                </div>

                <div class="ccep_input">
                    <input type="text" name="ccep_telefono" placeholder="Telefono*" maxlength="20">
                    <span class="ccep_field_error" id="ccep_err_telefono">Este campo es obligatorio.</span>
                </div>
                <div class="ccep_input">
                    <input type="text" name="ccep_compania" placeholder="Compania" maxlength="100">
                </div>

                <div class="ccep_input">
                    <select name="ccep_estado" id="ccep_estado">
                        <option value="">Estado</option>
                        <option>Aguascalientes</option><option>Baja California</option>
                        <option>Baja California Sur</option><option>Campeche</option>
                        <option>Chiapas</option><option>Chihuahua</option>
                        <option>Ciudad de Mexico</option><option>Coahuila</option>
                        <option>Colima</option><option>Durango</option>
                        <option>Estado de Mexico</option><option>Guanajuato</option>
                        <option>Guerrero</option><option>Hidalgo</option>
                        <option>Jalisco</option><option>Michoacan</option>
                        <option>Morelos</option><option>Nayarit</option>
                        <option>Nuevo Leon</option><option>Oaxaca</option>
                        <option>Puebla</option><option>Queretaro</option>
                        <option>Quintana Roo</option><option>San Luis Potosi</option>
                        <option>Sinaloa</option><option>Sonora</option>
                        <option>Tabasco</option><option>Tamaulipas</option>
                        <option>Tlaxcala</option><option>Veracruz</option>
                        <option>Yucatan</option><option>Zacatecas</option>
                    </select>
                </div>
                <div class="ccep_input">
                    <input type="text" name="ccep_cargo" placeholder="Cargo" maxlength="80">
                </div>

                <div class="ccep_input ccep_grid_full">
                    <select name="ccep_tamano" id="ccep_tamano">
                        <option value="">Tamano de la empresa</option>
                        <option value="Micro (0 a 10 empleados)">Micro (0 a 10 empleados)</option>
                        <option value="Pequena (11 a 50 empleados)">Pequena (11 a 50 empleados)</option>
                        <option value="Mediana (51 a 250 empleados)">Mediana (51 a 250 empleados)</option>
                        <option value="Grande (mas de 250 empleados)">Grande (mas de 250 empleados)</option>
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
                                    <label for="ccep_terminos">He leido y acepto los <a href="<?php echo esc_url( $url_terminos ); ?>" target="_blank">Terminos y Condiciones</a> y el <a href="<?php echo esc_url( $url_privacidad ); ?>" target="_blank">Aviso de Privacidad</a>.</label>
                                </div>
                                <span class="ccep_check_error" id="ccep_err_terminos">Debe aceptar los terminos y condiciones para continuar.</span>
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
        <h3>Solicitud enviada con exito!</h3>
        <p>Un especialista Canon se pondra en contacto contigo a la brevedad.</p>
    </div>
</div>

<script>
(function($){
    $(function(){
        var $form = $('#ccep_form');
        var accessStorageKey = window.ccrGateStorageKey || 'canon_centro_recursos_form_sent';

        function getAccessKeys(){
            var keys = [
                accessStorageKey,
                'gate_posts_authorized',
                'validacion_usuario',
                'canon_gate_authorized',
                'formulario_enviado'
            ];

            if (Array.isArray(window.ccrGateStorageKeys)) {
                keys = keys.concat(window.ccrGateStorageKeys);
            }

            return keys.filter(Boolean).filter(function(v, i, arr){ return arr.indexOf(v) === i; });
        }

        function persistInCookie(key, value){
            document.cookie = key + '=' + value + '; path=/; max-age=' + (60 * 60 * 24 * 30) + '; SameSite=Lax';
        }

        function persistAccessGranted(){
            var keys = getAccessKeys();

            try {
                keys.forEach(function(key){
                    localStorage.setItem(key, 'true');
                    localStorage.setItem(key, '1');
                });
            } catch (e) {}

            // Respaldo en cookie por 30 dias para flujos que leen cookie en vez de localStorage.
            keys.forEach(function(key){
                persistInCookie(key, 'true');
                persistInCookie(key, '1');
            });

            if (typeof window.CustomEvent === 'function') {
                window.dispatchEvent(new CustomEvent('ccr:form-sent', {
                    detail: { keys: keys, value: true }
                }));
            }
        }

        function resolveTargetUrl(){
            var raw = (window.ccep_dest_url && window.ccep_dest_url !== '')
                ? window.ccep_dest_url
                : window.location.href;

            return String(raw).split('#')[0];
        }

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
                $('#ccep_err_correo').text('Ingresa un correo electronico valido.').show();
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
                        $('#ccep_server_error').text(res.data || 'Ocurrio un error. Intenta de nuevo.').show();
                        $btn.prop('disabled', false).text('ENVIAR SOLICITUD');
                    } else {
                        persistAccessGranted();
                        var redirectUrl = resolveTargetUrl();

                        if (redirectUrl && redirectUrl !== window.location.href.split('#')[0]) {
                            window.location.href = redirectUrl;
                            return;
                        }

                        $('#ccep_form_wrap').hide();
                        $('#ccep_success').show();
                    }
                },
                error: function(){
                    $('#ccep_server_error').text('Error de conexion. Por favor intenta de nuevo.').show();
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

    if ( empty( $nombre ) )                          { wp_send_json_error( 'El nombre es obligatorio.' ); return; }
    if ( empty( $correo ) || ! is_email( $correo ) ) { wp_send_json_error( 'El correo electronico no es valido.' ); return; }
    if ( empty( $telefono ) )                        { wp_send_json_error( 'El telefono es obligatorio.' ); return; }

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

if ( ! function_exists( 'ccep_enviar_sendgrid' ) ) {
function ccep_enviar_sendgrid( $data ) {

    $SENDGRID_API_KEY = 'CLAVE';
    $from_email       = 'canoncanterapruebas@gmail.com';

    $copias = [
        'leonardo.garcia@canteradigital.mx',
        'fernando481917@gmail.com',
    ];

    $html_usuario = function_exists( 'ccep_email_template_blog' )
        ? ccep_email_template_blog( $data, false )
        : '<p>Hemos recibido tu solicitud. Un especialista Canon se pondra en contacto contigo.</p>';

    wp_remote_post( 'https://api.sendgrid.com/v3/mail/send', [
        'headers' => [
            'Authorization' => 'Bearer ' . $SENDGRID_API_KEY,
            'Content-Type'  => 'application/json',
        ],
        'body' => json_encode( [
            'personalizations' => [ [ 'to' => [ [ 'email' => $data['correo'] ] ] ] ],
            'from'    => [ 'email' => $from_email, 'name' => 'Canon Mexico' ],
            'subject' => 'Hemos recibido tu solicitud - Canon Mexico',
            'content' => [ [ 'type' => 'text/html', 'value' => $html_usuario ] ],
        ] ),
        'method'  => 'POST',
        'timeout' => 20,
    ] );

    $html_copias = function_exists( 'ccep_email_template_blog' )
        ? ccep_email_template_blog( $data, true )
        : '<p>Nueva solicitud Centro de Recursos - Canon Mexico</p>';

    $asunto_copias = 'Nueva solicitud Centro de Recursos - Canon Mexico';

    $tos_copias = array_map( function( $e ) { return [ 'email' => $e ]; }, $copias );

    wp_remote_post( 'https://api.sendgrid.com/v3/mail/send', [
        'headers' => [
            'Authorization' => 'Bearer ' . $SENDGRID_API_KEY,
            'Content-Type'  => 'application/json',
        ],
        'body' => json_encode( [
            'personalizations' => [ [ 'to' => $tos_copias ] ],
            'from'    => [ 'email' => $from_email, 'name' => 'Canon Mexico' ],
            'subject' => $asunto_copias,
            'content' => [ [ 'type' => 'text/html', 'value' => $html_copias ] ],
        ] ),
        'method'  => 'POST',
        'timeout' => 20,
    ] );
}
}

if ( ! function_exists( 'ccrb_render_gate_modal_blog' ) ) {
function ccrb_render_gate_modal_blog() {
    static $printed = false;

    if ( $printed || is_admin() ) {
        return;
    }

    $printed = true;

?>
<style>
#ccr-gate-modal{position:fixed;inset:0;z-index:99999;display:none;align-items:center;justify-content:center;padding:24px;box-sizing:border-box}
#ccr-gate-modal.is-open{display:flex}
.ccrb-gate-modal__overlay{position:absolute;inset:0;background:rgba(0,0,0,.55)}
.ccrb-gate-modal__dialog{position:relative;max-width:700px;width:min(94vw,700px);max-height:min(92vh,900px);overflow:auto;margin:0;background:#ffffff;border-radius:14px;padding:28px 28px 24px;box-sizing:border-box}
.ccrb-gate-modal__close{position:absolute;right:16px;top:12px;border:none;background:transparent;color:#111;font-size:32px;line-height:1;cursor:pointer;padding:0 6px}
.ccrb-gate-modal__close:hover,.ccrb-gate-modal__close:focus,.ccrb-gate-modal__close:active{background:transparent !important;box-shadow:none !important;outline:none;color:#111}
.ccrb-gate-modal__title{margin:0 42px 8px 0;font-family:'proxima-nova',Arial,sans-serif;font-size:32px;line-height:1.2;font-weight:700;color:#000000}
.ccrb-gate-modal__desc{margin:0 0 28px 0;font-family:'proxima-nova',Arial,sans-serif;font-size:14px;line-height:1.5;font-weight:400;color:#000000;max-width:none}
body.ccrb-gate-modal-open{overflow:hidden}
@media(max-width:1100px){.ccrb-gate-modal__dialog{width:min(96vw,700px);padding:24px 20px 20px}}
@media(max-width:700px){
    #ccr-gate-modal{padding:12px}
    .ccrb-gate-modal__dialog{width:95vw;padding:20px 16px 18px}
    .ccrb-gate-modal__title{font-size:28px;margin-right:28px}
    .ccrb-gate-modal__close{font-size:28px;right:10px;top:8px}
}
</style>
<div id="ccr-gate-modal" aria-hidden="true" role="dialog" aria-modal="true" aria-label="Formulario de acceso a contenido">
    <div class="ccrb-gate-modal__overlay"></div>
    <div class="ccrb-gate-modal__dialog">
        <button type="button" class="ccrb-gate-modal__close" aria-label="Cerrar">&times;</button>
        <h2 class="ccrb-gate-modal__title">Quieres acceder al Centro de Recursos?</h2>
        <p class="ccrb-gate-modal__desc">Dejanos tus datos y un especialista Canon se pondra en contacto contigo para compartir informacion detallada y opciones recomendadas para tu necesidad.</p>
        <?php
        if ( function_exists( 'ccep_formulario_shortcode' ) ) {
            echo ccep_formulario_shortcode();
        } else if ( shortcode_exists( 'canon-formulario-centro-recursos' ) ) {
            echo do_shortcode( '[canon-formulario-centro-recursos]' );
        } else {
            echo '<p style="margin:0;color:#cc0000;font-family:Arial,sans-serif;font-size:14px;">No esta registrado el shortcode [canon-formulario-centro-recursos] en esta carga de pagina.</p>';
        }
        ?>
    </div>
</div>
<script>
(function(){
    var accessStorageKey = window.ccrGateStorageKey || 'canon_centro_recursos_form_sent';

    function getAccessKeys(){
        var keys = [
            accessStorageKey,
            'gate_posts_authorized',
            'validacion_usuario',
            'canon_gate_authorized',
            'formulario_enviado'
        ];

        if (Array.isArray(window.ccrGateStorageKeys)) {
            keys = keys.concat(window.ccrGateStorageKeys);
        }

        return keys.filter(Boolean).filter(function(v, i, arr){ return arr.indexOf(v) === i; });
    }

    function readCookie(name){
        var escaped = name.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
        var match = document.cookie.match(new RegExp('(?:^|; )' + escaped + '=([^;]*)'));
        return match ? decodeURIComponent(match[1]) : '';
    }

    function hasAccess(){
        var keys = getAccessKeys();

        return keys.some(function(key){
            var lsValue = '';
            try {
                lsValue = localStorage.getItem(key) || '';
            } catch (e) {}

            var ckValue = readCookie(key);
            lsValue = String(lsValue).toLowerCase();
            ckValue = String(ckValue).toLowerCase();

            return lsValue === 'true' || lsValue === '1' || ckValue === 'true' || ckValue === '1';
        });
    }

    // API publica para scripts externos (ej. validacion-acceso-post.js)
    window.ccrHasGateAccess = hasAccess;

    function openModal(destUrl){
        var modal = document.getElementById('ccr-gate-modal');
        if (!modal) return;

        window.ccep_dest_url = destUrl || window.location.href.split('?')[0].split('#')[0];
        modal.classList.add('is-open');
        modal.setAttribute('aria-hidden', 'false');
        document.body.classList.add('ccrb-gate-modal-open');
    }

    function closeModal(){
        var modal = document.getElementById('ccr-gate-modal');
        if (!modal) return;

        modal.classList.remove('is-open');
        modal.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('ccrb-gate-modal-open');
    }

    function getTriggerUrl(trigger){
        if (!trigger) return '';

        var dataUrl = trigger.getAttribute('data-dest-url') || '';
        if (dataUrl) return dataUrl;

        var href = trigger.getAttribute('href') || '';
        if (href && href !== '#') return href;

        var parentLink = trigger.closest('a[href]');
        if (parentLink) {
            var parentHref = parentLink.getAttribute('href') || '';
            if (parentHref && parentHref !== '#') return parentHref;
        }

        var card = trigger.closest('[data-url], [data-href], [data-link]');
        if (card) {
            return card.getAttribute('data-url') || card.getAttribute('data-href') || card.getAttribute('data-link') || '';
        }

        return '';
    }

    document.addEventListener('click', function(e){
        var trigger = e.target.closest('.validacion_usuario, [data-ccrb-open-modal]');
        if (trigger) {
            var authorized = hasAccess();
            var targetUrl = getTriggerUrl(trigger);

            if (typeof window.CustomEvent === 'function') {
                window.dispatchEvent(new CustomEvent('ccr:trigger-click', {
                    detail: {
                        authorized: authorized,
                        targetUrl: targetUrl,
                        triggerClass: trigger.className || ''
                    }
                }));
            }

            if (authorized) {
                if (targetUrl) {
                    e.preventDefault();
                    window.location.href = targetUrl;
                }
                return;
            }

            e.preventDefault();
            openModal(targetUrl);
            return;
        }

        if (e.target.closest('.ccrb-gate-modal__close') || e.target.classList.contains('ccrb-gate-modal__overlay')) {
            e.preventDefault();
            closeModal();
        }
    });

    document.addEventListener('keydown', function(e){
        if (e.key === 'Escape') {
            closeModal();
        }
    });

    // Auto-open opcional: solo si se define window.ccrGateAutoOpen = true.
    if (window.ccrGateAutoOpen === true && !hasAccess()) {
        openModal('');
    }

    // Si otro script marca acceso completado, cierra el modal.
    window.addEventListener('ccr:form-sent', function(){
        closeModal();
    });
})();
</script>
<?php
}
}

add_action( 'wp_footer', 'ccrb_render_gate_modal_blog' );

if ( ! function_exists( 'ccrb_gate_modal_blog_shortcode' ) ) {
function ccrb_gate_modal_blog_shortcode() {
    ob_start();
    ccrb_render_gate_modal_blog();
    return ob_get_clean();
}
}

if ( ! shortcode_exists( 'ccr_gate_modal' ) ) {
    add_shortcode( 'ccr_gate_modal', 'ccrb_gate_modal_blog_shortcode' );
}

if ( ! function_exists( 'ccrb_gate_modal_blog_trigger_shortcode' ) ) {
function ccrb_gate_modal_blog_trigger_shortcode( $atts ) {
    $atts = shortcode_atts( [
        'text'  => 'Abrir formulario',
        'url'   => '',
        'class' => '',
    ], $atts, 'ccr_gate_modal_trigger' );

    $text  = esc_html( $atts['text'] );
    $url   = esc_url( $atts['url'] );
    $class_input = trim( (string) $atts['class'] );
    $class_parts = preg_split( '/\s+/', $class_input );
    $class_parts = array_filter( array_map( 'sanitize_html_class', $class_parts ) );
    $class_parts[] = 'validacion_usuario';
    $class = implode( ' ', array_unique( $class_parts ) );

    if ( empty( $url ) ) {
        $url = '';
    }

    return sprintf(
        '<a href="#" class="%1$s" data-ccrb-open-modal="1" data-dest-url="%2$s">%3$s</a>',
        esc_attr( $class ),
        esc_attr( $url ),
        $text
    );
}
}

if ( ! shortcode_exists( 'ccr_gate_modal_trigger' ) ) {
    add_shortcode( 'ccr_gate_modal_trigger', 'ccrb_gate_modal_blog_trigger_shortcode' );
}
