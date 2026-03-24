<?php
/**
 * Template: Formulario de reservaciones
 * Variables desde el widget/shortcode:
 *   $forced_branch  int
 *   $settings       array  (controles de Elementor o [])
 *   $show_pain_bar  bool
 *   $btn_layout     string  'split' | 'unified'
 *   $btn_pos_class  string  'tbw-btn-pos-right' | ...
 *   $btn_text       string
 *   $wrap_class     string
 */

// Defaults seguros para uso vía shortcode (sin Elementor)
$show_pain_bar = $show_pain_bar ?? true;
$btn_layout    = $btn_layout    ?? 'split';
$btn_pos_class = $btn_pos_class ?? 'tbw-btn-pos-right';
$btn_text      = $btn_text      ?? 'Agendar Ahora';
$wrap_class    = $wrap_class    ?? 'tbw-wrap';

// Prellenado desde URL (ej: ?reagendar=1&nombre=Juan&email=...&zona=...)
$prefill_reagendar = !empty($_GET['reagendar']);
$prefill_nombre    = sanitize_text_field($_GET['nombre']   ?? '');
$prefill_email     = sanitize_email(     $_GET['email']    ?? '');
$prefill_telefono  = sanitize_text_field($_GET['telefono'] ?? '');
$prefill_zona      = sanitize_text_field($_GET['zona']     ?? '');

$zones         = tb_zones_list();
$pain_map      = tb_pain_map();
$branches      = tb_get_active_branches();

// Sucursal única
$single_branch = null;
if ( $forced_branch ) {
    foreach ( $branches as $b ) {
        if ( (int)$b->id === (int)$forced_branch ) { $single_branch = $b; break; }
    }
} elseif ( count($branches) === 1 ) {
    $single_branch = $branches[0];
}

// ID único por instancia
$uid = 'tb-' . uniqid();
?>

<div class="<?= esc_attr($wrap_class) ?>" id="<?= $uid ?>" data-uid="<?= $uid ?>">

    <?php if ( $show_pain_bar ): ?>
    <!-- ══ BARRA DE DOLOR + ZONA ACTIVA DESTACADA ══ -->
    <div class="tbw-pain-header">
        <div class="tbw-pain-bar"></div>
        <div class="tbw-pain-labels">
            <span>POCO DOLOR</span>
            <span>MUCHO DOLOR</span>
        </div>
        <!-- Zona activa: aparece debajo de la barra cuando el usuario elige zona -->
        <div class="tbw-pain-active-zone" id="<?= $uid ?>-paz">
            <div class="tbw-paz-bar" id="<?= $uid ?>-paz-bar"></div>
            <div class="tbw-paz-info">
                <div class="tbw-paz-label">
                    <span class="tbw-paz-zone"  id="<?= $uid ?>-paz-zone">—</span>
                    <span class="tbw-paz-desc"  id="<?= $uid ?>-paz-desc"></span>
                    <div class="tbw-paz-track">
                        <div class="tbw-paz-fill" id="<?= $uid ?>-paz-fill"></div>
                    </div>
                </div>
                <div class="tbw-paz-score">
                    <span class="tbw-paz-num"   id="<?= $uid ?>-paz-num">—</span>
                    <span class="tbw-paz-of10">/ 10</span>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- ══ LAYOUT PRINCIPAL ══ -->
    <div class="tbw-layout">

        <!-- ── COLUMNA FORMULARIO ── -->
        <div class="tbw-col-form">

            <div class="tbw-form-head">
                <h2 class="tbw-title"><?= esc_html( $settings['form_title'] ?? 'Agenda tu Cita' ) ?></h2>
                <?php if ( !empty($settings['form_subtitle']) ): ?>
                    <p class="tbw-subtitle"><?= esc_html( $settings['form_subtitle'] ) ?></p>
                <?php endif; ?>
            </div>

            <?php if ($prefill_reagendar): ?>
            <div class="tbw-alert" style="background:#fffbeb;border:1px solid #fde68a;color:#92400e;border-radius:8px;padding:12px 16px;margin-bottom:12px;font-size:.85rem;display:flex;align-items:center;gap:8px">
                <span>&#x1F501;</span>
                <span>Tus datos ya est&aacute;n prellenados &mdash; solo elige un nuevo <strong>fecha y hora</strong>.</span>
            </div>
            <?php endif; ?>
            <!-- Alertas -->
            <div class="tbw-alert tbw-alert-ok" id="<?= $uid ?>-ok" style="display:none">
                <svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                <span id="<?= $uid ?>-ok-text">¡Cita agendada!</span>
            </div>
            <div class="tbw-alert tbw-alert-err" id="<?= $uid ?>-err" style="display:none">
                <span id="<?= $uid ?>-err-text">Error. Inténtalo de nuevo.</span>
            </div>

            <form class="tbw-form" id="<?= $uid ?>-form" novalidate>

                <!-- SUCURSAL -->
                <?php if ( $single_branch ): ?>
                    <input type="hidden" name="branch_id" value="<?= esc_attr($single_branch->id) ?>">
                    <div class="tbw-branch-pill">
                        <svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                        <?= esc_html($single_branch->name) ?><?= $single_branch->city ? ' · '.esc_html($single_branch->city) : '' ?>
                    </div>
                <?php elseif ( count($branches) > 0 ): ?>
                    <div class="tbw-field">
                        <label class="tbw-label">Sucursal</label>
                        <select name="branch_id" class="tbw-control" required>
                            <option value="">— Selecciona tu sucursal —</option>
                            <?php foreach ( $branches as $b ): ?>
                                <option value="<?= esc_attr($b->id) ?>"><?= esc_html($b->name) ?><?= $b->city ? ' · '.esc_html($b->city) : '' ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                <?php else: ?>
                    <p style="color:#9ca3af;font-size:.85rem">Sin sucursales disponibles.</p>
                <?php endif; ?>

                <!-- NOMBRE + EMAIL -->
                <div class="tbw-row2">
                    <div class="tbw-field">
                        <label class="tbw-label">Nombre *</label>
                        <input type="text" name="name" class="tbw-control" placeholder="Tu nombre completo" required value="<?=esc_attr($prefill_nombre)?>">
                    </div>
                    <div class="tbw-field">
                        <label class="tbw-label">Email</label>
                        <input type="email" name="email" class="tbw-control" placeholder="tu@email.com" value="<?=esc_attr($prefill_email)?>">
                    </div>
                </div>

                <!-- TELÉFONO + GÉNERO -->
                <div class="tbw-row2">
                    <div class="tbw-field">
                        <label class="tbw-label">Teléfono</label>
                        <input type="tel" name="phone" class="tbw-control" placeholder="+52 555 000 0000" value="<?=esc_attr($prefill_telefono)?>">
                    </div>
                    <div class="tbw-field">
                        <label class="tbw-label">Género</label>
                        <div class="tbw-gender" style="border-radius:0">
                            <button type="button" class="tbw-gender-pill active" data-gender="male" style="border-radius:0">♂ Masculino</button>
                            <button type="button" class="tbw-gender-pill"        data-gender="female" style="border-radius:0">♀ Femenino</button>
                            <input type="hidden" name="gender" class="tbw-gender-val" value="male">
                        </div>
                    </div>
                </div>

                <!-- ZONA -->
                <div class="tbw-field">
                    <label class="tbw-label">Zona del Tatuaje *</label>
                    <select name="zone" class="tbw-control tbw-zone-sel" required>
                        <option value="">— Selecciona la zona —</option>
                        <?php foreach ( $zones as $group => $opts ): ?>
                            <optgroup label="<?= esc_attr($group) ?>">
                                <?php foreach ( $opts as $val => $label ): ?>
                                    <option value="<?= esc_attr($val) ?>" data-pain="<?= $pain_map[$val] ?? 5 ?>">
                                        <?= esc_html($label) ?>
                                    </option>
                                <?php endforeach; ?>
                            </optgroup>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- FECHA + HORA -->
                <div class="tbw-row2">
                    <div class="tbw-field">
                        <label class="tbw-label">Fecha *</label>
                        <input type="date" name="date" class="tbw-control" required min="<?= date('Y-m-d') ?>">
                    </div>
                    <div class="tbw-field">
                        <label class="tbw-label">Hora *</label>
                        <select name="time" class="tbw-control" required>
                            <option value="">— Hora —</option>
                            <?php for ( $h = 10; $h <= 19; $h++ ): foreach ( ['00','30'] as $m ):
                                $t = sprintf('%02d:%s',$h,$m); ?>
                                <option value="<?= $t ?>"><?= $t ?></option>
                            <?php endforeach; endfor; ?>
                        </select>
                    </div>
                </div>

                <!-- NOTAS -->
                <div class="tbw-field">
                    <label class="tbw-label">Notas / Idea del diseño</label>
                    <textarea name="notes" class="tbw-control" rows="3" placeholder="Describe tu idea, tamaño, colores, referencias…"></textarea>
                </div>

                <!-- ══ BOTÓN AGENDAR ══
                     Dos estilos:
                     - split:    [ Agendar Ahora ][ ↗ ]   (exacto a la captura)
                     - unified:  [   Agendar Ahora ↗   ]
                -->
                <div class="tbw-submit-row <?= esc_attr($btn_pos_class) ?>">

                    <?php if ( $btn_layout === 'split' ): ?>
                        <div class="tbw-submit-group">
                            <button type="submit" class="tbw-submit tbw-submit-main" id="<?= $uid ?>-btn" style="border-radius:0">
                                <span class="tbw-btn-label">
                                    <span class="tbw-btn-text"><?= $btn_text ?></span>
                                    <span class="tbw-btn-loading" style="display:none">Enviando…</span>
                                </span>
                            </button>
                            <button type="submit" class="tbw-submit tbw-submit-icon-only" aria-label="<?= esc_attr($btn_text) ?>" style="border-radius:0;background:#fff">
                                <span class="tbw-btn-icon" aria-hidden="true" style="background:#fff;color:#4b5563;border-radius:0">↗</span>
                            </button>
                        </div>
                    <?php else: ?>
                        <button type="submit" class="tbw-submit tbw-submit-unified" id="<?= $uid ?>-btn">
                            <span class="tbw-btn-label">
                                <span class="tbw-btn-text"><?= $btn_text ?></span>
                                <span class="tbw-btn-loading" style="display:none">Enviando…</span>
                            </span>
                            <span class="tbw-btn-icon" aria-hidden="true">↗</span>
                        </button>
                    <?php endif; ?>

                </div>

            </form>
        </div>

        <!-- ── COLUMNA IMAGEN / MAPA ── -->
        <div class="tbw-col-body" id="<?= $uid ?>-body">

            <!-- Idle -->
            <div class="tbw-idle" id="<?= $uid ?>-idle">
                <div class="tbw-idle-inner">
                    <div class="tbw-idle-icon">⚡</div>
                    <p>Selecciona tu género<br>y zona del tatuaje</p>
                </div>
            </div>

            <!-- Viewer: imagen + canvas + callout -->
            <div class="tbw-viewer" id="<?= $uid ?>-viewer" style="display:none">
                <div class="tbw-img-container" id="<?= $uid ?>-imgc">
                    <img class="tb-body-photo" id="<?= $uid ?>-img" src="" alt="Mapa corporal">
                    <canvas class="tbw-canvas" id="<?= $uid ?>-canvas"></canvas>
                    <!-- Callout absoluto en desktop, estático en móvil via JS -->
                    <div class="tbw-callout" id="<?= $uid ?>-callout">
                        <span class="tbw-callout-name"  id="<?= $uid ?>-cname"></span>
                        <span class="tbw-callout-level" id="<?= $uid ?>-clevel"></span>
                    </div>
                </div>

                <!-- Leyenda -->
                <div class="tbw-legend">
                    <span><i style="background:#4ade80"></i>Bajo</span>
                    <span><i style="background:#facc15"></i>Medio</span>
                    <span><i style="background:#fb923c"></i>Alto</span>
                    <span><i style="background:#ef4444"></i>Extremo</span>
                </div>
            </div>

        </div>
    </div>
</div>

<script>(function(){window.TB_INSTANCES=window.TB_INSTANCES||[];window.TB_INSTANCES.push('<?= $uid ?>');})();</script>
<?php if ($prefill_zona): ?>
<script>
(function(){
    // Preseleccionar zona desde URL al iniciar
    var prefillZona = <?=json_encode($prefill_zona)?>;
    document.addEventListener('DOMContentLoaded', function(){
        var sel = document.querySelector('#<?= $uid ?>-form select[name="zone"]');
        if (sel && prefillZona) {
            sel.value = prefillZona;
            sel.dispatchEvent(new Event('change'));
        }
    });
})();
</script>
<?php endif; ?>
