<?php

/**
 * SNIPPET — FORMULARIO CENTRO DE RECURSOS Y BLOG CANON
 * Shortcode: [canon_formulario_centro_recursos producto="Nombre del recurso"]
 * Prefijo: ccr_
 * Sin base de datos — solo envía correo con retícula estándar
 */

function ccr_formulario_shortcode( $atts ){
    $atts = shortcode_atts([
        'producto' => 'Centro de Recursos Canon',
    ], $atts, 'canon_formulario_centro_recursos');
    $producto = sanitize_text_field( $atts['producto'] );

    $icon_url       = get_site_url() . '/wp-content/uploads/2026/03/Icon.png';
    $url_terminos   = get_site_url() . '/terminos-y-condiciones/';
    $url_privacidad = get_site_url() . '/politicas-de-privacidad/';

ob_start();
?>
<style>
.ccr_wrapper { width: 100%; font-family: 'proxima-nova', Arial, sans-serif; }
.ccr_grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
.ccr_grid_full { grid-column: 1 / -1; }
.ccr_input { display: flex; flex-direction: column; gap: 0; }
.ccr_input input,
.ccr_input select,
.ccr_input textarea {
    width: 100%; padding: 14px 16px;
    border: 1px solid #e0e0e0; border-radius: 0;
    background: #ffffff; font-size: 15px; font-weight: 400;
    font-family: 'proxima-nova', Arial, sans-serif; color: #000000;
    appearance: none; -webkit-appearance: none;
    box-sizing: border-box; outline: none; transition: border-color 0.15s;
}
.ccr_input input::placeholder,
.ccr_input textarea::placeholder { color: #999999; }
.ccr_input select { color: #999999; cursor: pointer; }
.ccr_input select.ccr_selected { color: #000000; }
.ccr_input select {
    background-image: url('<?php echo esc_url($icon_url); ?>');
    background-repeat: no-repeat;
    background-position: right 14px center;
    background-size: 18px 18px;
    padding-right: 44px;
}
.ccr_input input:focus,
.ccr_input select:focus,
.ccr_input textarea:focus { border-color: #CC0000; outline: none; }
.ccr_input textarea { resize: vertical; min-height: 100px; }
.ccr_input input.ccr_error,
.ccr_input select.ccr_error,
.ccr_input textarea.ccr_error { border: 2px solid #CC0000 !important; }
.ccr_input input:-webkit-autofill,
.ccr_input input:-webkit-autofill:hover,
.ccr_input input:-webkit-autofill:focus {
    -webkit-box-shadow: 0 0 0 1000px #ffffff inset !important;
    -webkit-text-fill-color: #000000 !important;
}
.ccr_field_error {
    color: #CC0000; font-size: 11px;
    font-family: 'proxima-nova', Arial, sans-serif;
    margin-top: 3px; display: none;
}
.ccr_check_wrap {
    display: flex; align-items: flex-start; gap: 8px;
    font-size: 13px; font-family: 'proxima-nova', Arial, sans-serif;
    color: #555; margin-top: 4px;
}
.ccr_check_wrap input[type="checkbox"] {
    margin-top: 2px; width: 15px; height: 15px;
    accent-color: #CC0000; flex-shrink: 0; cursor: pointer;
}
.ccr_check_wrap a { color: #CC0000; text-decoration: none; }
.ccr_check_error {
    color: #CC0000; font-size: 11px;
    font-family: 'proxima-nova', Arial, sans-serif;
    margin-top: 3px; display: none;
}
.ccr_bottom_row {
    display: flex; align-items: flex-start;
    justify-content: space-between; gap: 20px; margin-top: 20px;
}
.ccr_checks_col { display: flex; flex-direction: column; gap: 8px; flex: 1; }
.ccr_submit_btn {
    padding: 0 28px; height: 50px;
    background: #CC0000; color: #fff;
    border: none; cursor: pointer;
    font-size: 13px; font-weight: 400;
    font-family: 'proxima-nova', Arial, sans-serif;
    letter-spacing: 1px; text-transform: uppercase;
    border-radius: 0; flex-shrink: 0;
    transition: background 0.15s; white-space: nowrap;
    display: inline-flex; align-items: center; justify-content: center;
}
.ccr_submit_btn:hover,
.ccr_submit_btn:focus,
.ccr_submit_btn:active { background: #CC0000; color: #fff; box-shadow: none; outline: none; }
.ccr_submit_btn:disabled { background: #999 !important; cursor: not-allowed; }
.ccr_server_error {
    background: #fff0f0; border: 1px solid #CC0000; color: #CC0000;
    font-size: 13px; padding: 10px 14px; margin-top: 12px;
    display: none; text-align: center;
    font-family: 'proxima-nova', Arial, sans-serif;
}
.ccr_success {
    display: none; text-align: center; padding: 30px 20px;
    font-family: 'proxima-nova', Arial, sans-serif;
}
.ccr_success h3 { font-size: 20px; font-weight: 700; color: #000; margin-bottom: 8px; }
.ccr_success p  { font-size: 14px; color: #555; }
@media (max-width: 600px) {
    .ccr_grid { grid-template-columns: 1fr; }
    .ccr_bottom_row { flex-direction: column; }
    .ccr_submit_btn { width: 100%; height: 48px; }
}
</style>

<div class="ccr_wrapper">
    <div id="ccr_form_wrap">
        <form id="ccr_form" novalidate>
            <input type="hidden" name="producto" value="<?php echo esc_attr($producto); ?>">
            <div class="ccr_grid">

                <div class="ccr_input">
                    <input type="text" name="nombre" placeholder="Nombre completo*" maxlength="80">
                    <span class="ccr_field_error" id="ccr_err_nombre">Este campo es obligatorio.</span>
                </div>

                <div class="ccr_input">
                    <input type="text" name="correo" placeholder="Email corporativo*" maxlength="100">
                    <span class="ccr_field_error" id="ccr_err_correo">Ingresa un correo electrónico válido.</span>
                </div>

                <div class="ccr_input">
                    <input type="text" name="telefono" placeholder="Teléfono*" maxlength="20">
                    <span class="ccr_field_error" id="ccr_err_telefono">Este campo es obligatorio.</span>
                </div>

                <div class="ccr_input">
                    <input type="text" name="compania" placeholder="Compañía" maxlength="100">
                </div>

                <div class="ccr_input">
                    <select name="estado" id="ccr_estado">
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

                <div class="ccr_input">
                    <input type="text" name="cargo" placeholder="Cargo" maxlength="80">
                </div>

                <div class="ccr_input ccr_grid_full">
                    <select name="tamano_empresa" id="ccr_tamano">
                        <option value="">Tamaño de la empresa</option>
                        <option value="Micro (0 a 10 empleados)">Micro (0 a 10 empleados)</option>
                        <option value="Pequeña (11 a 50 empleados)">Pequeña (11 a 50 empleados)</option>
                        <option value="Mediana (51 a 250 empleados)">Mediana (51 a 250 empleados)</option>
                        <option value="Grande (más de 250 empleados)">Grande (más de 250 empleados)</option>
                    </select>
                </div>

                <div class="ccr_input ccr_grid_full">
                    <textarea name="mensaje" placeholder="Mensaje adicional"></textarea>
                </div>

                <div class="ccr_grid_full">
                    <div class="ccr_bottom_row">
                        <div class="ccr_checks_col">
                            <div>
                                <div class="ccr_check_wrap">
                                    <input type="checkbox" id="ccr_terminos">
                                    <label for="ccr_terminos">He leído y acepto los <a href="<?php echo esc_url($url_terminos); ?>" target="_blank">Términos y Condiciones</a> y el <a href="<?php echo esc_url($url_privacidad); ?>" target="_blank">Aviso de Privacidad</a>.</label>
                                </div>
                                <span class="ccr_check_error" id="ccr_err_terminos">Debe aceptar los términos y condiciones para continuar.</span>
                            </div>
                            <div class="ccr_check_wrap">
                                <input type="checkbox" id="ccr_promo">
                                <label for="ccr_promo">Deseo recibir promociones, ofertas y noticias de Canon Mexicana.</label>
                            </div>
                        </div>
                        <button type="submit" class="ccr_submit_btn" id="ccr_btn_enviar">ENVIAR SOLICITUD</button>
                    </div>
                    <div class="ccr_server_error" id="ccr_server_error"></div>
                </div>

            </div>
        </form>
    </div>

    <div class="ccr_success" id="ccr_success">
        <h3>¡Solicitud enviada con éxito!</h3>
        <p>Un especialista Canon se pondrá en contacto contigo a la brevedad.</p>
    </div>
</div>

<script>
jQuery(function($){
    $('#ccr_success').hide();

    $('#ccr_estado, #ccr_tamano').on('change', function(){
        $(this).toggleClass('ccr_selected', !!$(this).val());
    });

    $(document).on('input change', '.ccr_error', function(){
        $(this).removeClass('ccr_error');
        $(this).closest('.ccr_input').find('.ccr_field_error').hide();
    });

    function validar(){
        let ok = true;
        const emailReg = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        const nombre = $('[name="nombre"]').val().trim();
        if(!nombre){ $('[name="nombre"]').addClass('ccr_error'); $('#ccr_err_nombre').text('Este campo es obligatorio.').show(); ok=false; }
        else { $('[name="nombre"]').removeClass('ccr_error'); $('#ccr_err_nombre').hide(); }

        const correo = $('[name="correo"]').val().trim();
        if(!correo){ $('[name="correo"]').addClass('ccr_error'); $('#ccr_err_correo').text('Este campo es obligatorio.').show(); ok=false; }
        else if(!emailReg.test(correo) || correo.indexOf('..') !== -1){ $('[name="correo"]').addClass('ccr_error'); $('#ccr_err_correo').text('Ingresa un correo electrónico válido.').show(); ok=false; }
        else { $('[name="correo"]').removeClass('ccr_error'); $('#ccr_err_correo').hide(); }

        const telefono = $('[name="telefono"]').val().trim();
        if(!telefono){ $('[name="telefono"]').addClass('ccr_error'); $('#ccr_err_telefono').text('Este campo es obligatorio.').show(); ok=false; }
        else { $('[name="telefono"]').removeClass('ccr_error'); $('#ccr_err_telefono').hide(); }

        if(!$('#ccr_terminos').is(':checked')){ $('#ccr_err_terminos').show(); ok=false; }
        else { $('#ccr_err_terminos').hide(); }

        return ok;
    }

    $('#ccr_form').submit(function(e){
        e.preventDefault();
        if(!validar()) return;
        const btn = $('#ccr_btn_enviar');
        if(btn.prop('disabled')) return;
        $('#ccr_server_error').hide().text('');
        btn.prop('disabled', true).text('ENVIANDO...');

        const fd = new FormData();
        fd.append('action',         'ccr_enviar');
        fd.append('nombre',         $('[name="nombre"]').val().trim());
        fd.append('correo',         $('[name="correo"]').val().trim());
        fd.append('telefono',       $('[name="telefono"]').val().trim());
        fd.append('compania',       $('[name="compania"]').val().trim());
        fd.append('estado',         $('[name="estado"]').val());
        fd.append('cargo',          $('[name="cargo"]').val().trim());
        fd.append('tamano_empresa', $('[name="tamano_empresa"]').val());
        fd.append('mensaje',        $('[name="mensaje"]').val().trim());
        fd.append('producto',       $('[name="producto"]').val());
        fd.append('promo',          $('#ccr_promo').is(':checked') ? '1' : '0');

        $.ajax({
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            type: 'POST', data: fd, processData: false, contentType: false,
            success: function(res){
                if(res && res.success === false){
                    $('#ccr_server_error').text(res.data || 'Ocurrió un error. Intenta de nuevo.').show();
                    btn.prop('disabled', false).text('ENVIAR SOLICITUD');
                } else {
                    try {
                        localStorage.setItem('usuario_registrado', 'true');
                    } catch (err) {}
                    window.dispatchEvent(new CustomEvent('autorizacionPost:ok'));
                    $('#ccr_form_wrap').hide();
                    $('#ccr_success').show();
                }
            },
            error: function(){
                $('#ccr_server_error').text('Error de conexión. Por favor intenta de nuevo.').show();
                btn.prop('disabled', false).text('ENVIAR SOLICITUD');
            }
        });
    });
});
</script>

<?php
    return ob_get_clean();
}
add_shortcode('canon_formulario_centro_recursos', 'ccr_formulario_shortcode');

/* ══════════════════════════════════════
   AJAX — SOLO ENVÍA CORREO, SIN BD
══════════════════════════════════════ */
add_action('wp_ajax_nopriv_ccr_enviar', 'ccr_enviar');
add_action('wp_ajax_ccr_enviar',        'ccr_enviar');

if(!function_exists('ccr_enviar')){
function ccr_enviar(){

    $nombre   = sanitize_text_field($_POST['nombre']         ?? '');
    $correo   = sanitize_email($_POST['correo']              ?? '');
    $telefono = sanitize_text_field($_POST['telefono']       ?? '');
    $compania = sanitize_text_field($_POST['compania']       ?? '');
    $estado   = sanitize_text_field($_POST['estado']         ?? '');
    $cargo    = sanitize_text_field($_POST['cargo']          ?? '');
    $tamano   = sanitize_text_field($_POST['tamano_empresa'] ?? '');
    $mensaje  = sanitize_textarea_field($_POST['mensaje']    ?? '');
    $producto = sanitize_text_field($_POST['producto']       ?? '');

    if(empty($nombre)){ wp_send_json_error('El nombre es obligatorio.'); return; }
    if(empty($correo) || !is_email($correo)){ wp_send_json_error('El correo electrónico no es válido.'); return; }
    if(empty($telefono)){ wp_send_json_error('El teléfono es obligatorio.'); return; }

    $data = [
        'nombre'         => $nombre,
        'correo'         => $correo,
        'telefono'       => $telefono,
        'compania'       => $compania,
        'estado'         => $estado,
        'cargo'          => $cargo,
        'tamano_empresa' => $tamano,
        'mensaje'        => $mensaje,
        'producto'       => $producto,
    ];

    ccr_enviar_sendgrid($data);
    wp_send_json_success('ok');
}
}

/* ══════════════════════════════════════
   SENDGRID
══════════════════════════════════════ */
if(!function_exists('ccr_enviar_sendgrid')){
function ccr_enviar_sendgrid($data){
    $SENDGRID_API_KEY = getenv('SENDGRID_API_KEY');
    if ( empty($SENDGRID_API_KEY) && defined('SENDGRID_API_KEY') ) {
        $SENDGRID_API_KEY = SENDGRID_API_KEY;
    }
    if ( empty($SENDGRID_API_KEY) ) {
        return;
    }
    $from_email = 'canoncanterapruebas@gmail.com';

    $copias = [
        'leonardo.garcia@canteradigital.mx',
        'fernando481917@gmail.com',
        
    ];
    $todos = array_unique(array_merge([$data['correo']], $copias));
    $tos   = array_values(array_map(function($e){ return ['email' => $e]; }, $todos));

    // Usar retícula estándar
    if(function_exists('canon_reticula_email')){
        $html = canon_reticula_email([
            'nombre'  => $data['nombre'],
            'campos'  => [
                'Nombre completo'      => $data['nombre'],
                'Email corporativo'    => $data['correo'],
                'Teléfono'             => $data['telefono'],
                'Compañía'             => !empty($data['compania'])       ? $data['compania']       : '—',
                'Estado'               => !empty($data['estado'])         ? $data['estado']         : '—',
                'Cargo'                => !empty($data['cargo'])          ? $data['cargo']          : '—',
                'Tamaño de la empresa' => !empty($data['tamano_empresa']) ? $data['tamano_empresa'] : '—',
            ],
            'mensaje' => $data['mensaje'],
        ]);
    } else {
        // Fallback por si la retícula no está activa
        $html = '<p>Nueva solicitud — Centro de Recursos Canon.</p>'
              . '<p><strong>Nombre:</strong> '        . esc_html($data['nombre'])        . '</p>'
              . '<p><strong>Correo:</strong> '         . esc_html($data['correo'])         . '</p>'
              . '<p><strong>Teléfono:</strong> '       . esc_html($data['telefono'])       . '</p>'
              . '<p><strong>Compañía:</strong> '       . esc_html($data['compania'])       . '</p>'
              . '<p><strong>Estado:</strong> '         . esc_html($data['estado'])         . '</p>'
              . '<p><strong>Cargo:</strong> '          . esc_html($data['cargo'])          . '</p>'
              . '<p><strong>Tamaño empresa:</strong> ' . esc_html($data['tamano_empresa']) . '</p>'
              . '<p><strong>Mensaje:</strong><br>'     . nl2br(esc_html($data['mensaje'])) . '</p>';
    }

    $body = [
        'personalizations' => [['to' => $tos]],
        'from'    => ['email' => $from_email, 'name' => 'Canon México'],
        'subject' => 'Nueva solicitud — Centro de Recursos Canon' . (!empty($data['producto']) ? ' | ' . $data['producto'] : ''),
        'content' => [['type' => 'text/html', 'value' => $html]],
    ];

    wp_remote_post('https://api.sendgrid.com/v3/mail/send', [
        'headers' => ['Authorization' => 'Bearer ' . $SENDGRID_API_KEY, 'Content-Type' => 'application/json'],
        'body'    => json_encode($body),
        'method'  => 'POST',
        'timeout' => 20,
    ]);
}
}

/* ══════════════════════════════════════
   MODAL PROPIO (SIN ELEMENTOR POPUP)
══════════════════════════════════════ */
if (!function_exists('ccr_render_gate_modal')) {
function ccr_render_gate_modal() {
    static $printed = false;

    if ($printed) {
        return;
    }

    if (is_admin()) {
        return;
    }

    $printed = true;
    ?>
        <style>
        #ccr-gate-modal{position:fixed;inset:0;z-index:99999;display:none;align-items:center;justify-content:center;padding:24px;box-sizing:border-box}
        #ccr-gate-modal.is-open{display:flex}
        .ccr-gate-modal__overlay{position:absolute;inset:0;background:rgba(0,0,0,.55)}
        .ccr-gate-modal__dialog{position:relative;max-width:700px;width:min(94vw,700px);max-height:min(92vh,900px);overflow:auto;margin:0;background:#ffffff;border-radius:14px;padding:28px 28px 24px;box-sizing:border-box}
        .ccr-gate-modal__close{position:absolute;right:16px;top:12px;border:none;background:transparent;color:#111;font-size:32px;line-height:1;cursor:pointer;padding:0 6px}
        .ccr-gate-modal__close:hover,.ccr-gate-modal__close:focus,.ccr-gate-modal__close:active{background:transparent !important;box-shadow:none !important;outline:none;color:#111}
        .ccr-gate-modal__title{margin:0 42px 8px 0;font-family:'proxima-nova',Arial,sans-serif;font-size:32px;line-height:1.2;font-weight:700;color:#000000}
        .ccr-gate-modal__desc{margin:0 0 28px 0;font-family:'proxima-nova',Arial,sans-serif;font-size:14px;line-height:1.5;font-weight:400;color:#000000;max-width:none}
        body.ccr-gate-modal-open{overflow:hidden}
        @media (max-width: 1100px){
            .ccr-gate-modal__dialog{width:min(96vw,700px);padding:24px 20px 20px}
        }
        @media (max-width: 700px){
            #ccr-gate-modal{padding:12px}
            .ccr-gate-modal__dialog{width:95vw;padding:20px 16px 18px}
            .ccr-gate-modal__title{font-size:28px;margin-right:28px}
            .ccr-gate-modal__desc{font-size:14px}
            .ccr-gate-modal__close{font-size:28px;right:10px;top:8px}
        }
        </style>

        <div id="ccr-gate-modal" aria-hidden="true" role="dialog" aria-modal="true" aria-label="Formulario de acceso a contenido">
            <div class="ccr-gate-modal__overlay"></div>
            <div class="ccr-gate-modal__dialog">
                <button type="button" class="ccr-gate-modal__close" aria-label="Cerrar">&times;</button>
                <h2 class="ccr-gate-modal__title">¿Quieres saber más sobre este producto?</h2>
                <p class="ccr-gate-modal__desc">Déjanos tus datos y un especialista Canon se pondrá en contacto contigo para brindarte información detallada, disponibilidad y opciones recomendadas según tus necesidades.</p>
                <?php echo do_shortcode('[canon_formulario_centro_recursos producto="Centro de Recursos Canon"]'); ?>
            </div>
        </div>
        <?php
}
}
add_action('wp_footer', 'ccr_render_gate_modal');

if (!function_exists('ccr_gate_modal_shortcode')) {
function ccr_gate_modal_shortcode() {
    ob_start();
    ccr_render_gate_modal();
    return ob_get_clean();
}
}
add_shortcode('ccr_gate_modal', 'ccr_gate_modal_shortcode');
