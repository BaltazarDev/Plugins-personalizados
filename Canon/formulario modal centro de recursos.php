<?php
/**
 * Modal de acceso a contenido - Canon
 * Shortcode de apertura: [ccr_gate_modal]
 * Formulario renderizado dentro del modal: [canon-formulario-blog]
 */

if ( ! function_exists( 'ccr_render_gate_modal' ) ) {
function ccr_render_gate_modal() {
    static $printed = false;

    if ( $printed || is_admin() ) {
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
@media(max-width:1100px){.ccr-gate-modal__dialog{width:min(96vw,700px);padding:24px 20px 20px}}
@media(max-width:700px){
    #ccr-gate-modal{padding:12px}
    .ccr-gate-modal__dialog{width:95vw;padding:20px 16px 18px}
    .ccr-gate-modal__title{font-size:28px;margin-right:28px}
    .ccr-gate-modal__close{font-size:28px;right:10px;top:8px}
}
</style>
<div id="ccr-gate-modal" aria-hidden="true" role="dialog" aria-modal="true" aria-label="Formulario de acceso a contenido">
    <div class="ccr-gate-modal__overlay"></div>
    <div class="ccr-gate-modal__dialog">
        <button type="button" class="ccr-gate-modal__close" aria-label="Cerrar">&times;</button>
        <h2 class="ccr-gate-modal__title">¿Quieres saber más sobre este producto?</h2>
        <p class="ccr-gate-modal__desc">Déjanos tus datos y un especialista Canon se pondrá en contacto contigo para brindarte información detallada, disponibilidad y opciones recomendadas según tus necesidades.</p>
        <?php
        if ( shortcode_exists( 'canon-formulario-blog' ) ) {
            echo do_shortcode( '[canon-formulario-blog]' );
        } else {
            echo '<p style="margin:0;color:#cc0000;font-family:Arial,sans-serif;font-size:14px;">El shortcode [canon-formulario-blog] no está registrado en esta carga de página.</p>';
        }
        ?>
    </div>
</div>
<?php
}
}

add_action( 'wp_footer', 'ccr_render_gate_modal' );

if ( ! function_exists( 'ccr_gate_modal_shortcode' ) ) {
function ccr_gate_modal_shortcode() {
    ob_start();
    ccr_render_gate_modal();
    return ob_get_clean();
}
}

if ( ! shortcode_exists( 'ccr_gate_modal' ) ) {
    add_shortcode( 'ccr_gate_modal', 'ccr_gate_modal_shortcode' );
}
