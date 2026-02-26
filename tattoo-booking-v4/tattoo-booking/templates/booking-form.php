<?php
/**
 * Template: Formulario de reservaciones
 * Variables disponibles:
 *   $forced_branch (int)
 *   $settings      (array) — controles de Elementor o []
 *   $show_pain_bar (bool)
 */
$zones         = tb_zones_list();
$pain_map      = tb_pain_map();
$branches      = tb_get_active_branches();
$show_pain_bar = $show_pain_bar ?? true;

$single_branch = null;
if ( $forced_branch ) {
    foreach ( $branches as $b ) {
        if ( (int)$b->id === (int)$forced_branch ) { $single_branch = $b; break; }
    }
} elseif ( count($branches) === 1 ) {
    $single_branch = $branches[0];
}

// Unique ID por instancia (soporte múltiples widgets en la misma página)
$uid = 'tb-' . uniqid();
?>

<div class="tbw-wrap" id="<?= $uid ?>" data-uid="<?= $uid ?>" 
     data-img-male="<?= esc_url($settings['resolved_img_male'] ?? '') ?>" 
     data-img-female="<?= esc_url($settings['resolved_img_female'] ?? '') ?>">

    <?php if ($show_pain_bar): ?>
    <!-- BARRA DE DOLOR — igual a la captura: verde / amarillo / naranja / rojo -->
    <div class="tbw-pain-header">
        <div class="tbw-pain-bar"></div>
        <div class="tbw-pain-labels">
            <span>POCO DOLOR</span>
            <span>MUCHO DOLOR</span>
        </div>
        <!-- Cursor animado sobre la barra -->
        <div class="tbw-pain-cursor-row" id="<?= $uid ?>-cursor-row" style="display:none">
            <div class="tbw-pain-cursor-track">
                <div class="tbw-pain-cursor" id="<?= $uid ?>-cursor"></div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- LAYOUT: formulario izquierda / imagen derecha -->
    <div class="tbw-layout">

        <!-- ── COLUMNA IZQUIERDA: FORMULARIO ── -->
        <div class="tbw-col-form">

            <div class="tbw-form-top">
                <h2 class="tbw-title"><?= esc_html($settings['form_title'] ?? 'Agenda tu Cita') ?></h2>
                <?php if (!empty($settings['form_subtitle'])): ?>
                    <p class="tbw-subtitle"><?= esc_html($settings['form_subtitle']) ?></p>
                <?php endif; ?>
            </div>

            <!-- Mensajes -->
            <div class="tbw-msg tbw-msg-ok" id="<?= $uid ?>-ok" style="display:none">
                <svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                <p id="<?= $uid ?>-ok-text">¡Cita agendada!</p>
            </div>
            <div class="tbw-msg tbw-msg-err" id="<?= $uid ?>-err" style="display:none">
                <p id="<?= $uid ?>-err-text">Error.</p>
            </div>

            <form class="tbw-form" id="<?= $uid ?>-form" novalidate>

                <!-- SUCURSAL -->
                <?php if ($single_branch): ?>
                    <input type="hidden" name="branch_id" value="<?= esc_attr($single_branch->id) ?>">
                    <div class="tbw-branch-tag">
                        <svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                        <span><?= esc_html($single_branch->name) ?><?= $single_branch->city ? ' · '.esc_html($single_branch->city) : '' ?></span>
                    </div>
                <?php elseif (count($branches) > 0): ?>
                    <div class="tbw-field">
                        <label class="tbw-label">Sucursal</label>
                        <select name="branch_id" class="tbw-control" required>
                            <option value="">— Selecciona —</option>
                            <?php foreach ($branches as $b): ?>
                                <option value="<?= esc_attr($b->id) ?>"><?= esc_html($b->name) ?><?= $b->city ? ' · '.esc_html($b->city) : '' ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                <?php else: ?>
                    <p class="tbw-no-branches">Sin sucursales disponibles.</p>
                <?php endif; ?>

                <!-- NOMBRE + EMAIL -->
                <div class="tbw-row2">
                    <div class="tbw-field">
                        <label class="tbw-label">Nombre *</label>
                        <input type="text" name="name" class="tbw-control" placeholder="Tu nombre completo" required>
                    </div>
                    <div class="tbw-field">
                        <label class="tbw-label">Email</label>
                        <input type="email" name="email" class="tbw-control" placeholder="tu@email.com">
                    </div>
                </div>

                <!-- TELÉFONO + GÉNERO -->
                <div class="tbw-row2">
                    <div class="tbw-field">
                        <label class="tbw-label">Teléfono</label>
                        <input type="tel" name="phone" class="tbw-control" placeholder="+52 555 000 0000">
                    </div>
                    <div class="tbw-field">
                        <label class="tbw-label">Género</label>
                        <div class="tbw-gender">
                            <button type="button" class="tbw-gender-pill active" data-gender="male">♂ Masculino</button>
                            <button type="button" class="tbw-gender-pill" data-gender="female">♀ Femenino</button>
                            <input type="hidden" name="gender" class="tbw-gender-val" value="male">
                        </div>
                    </div>
                </div>

                <!-- ZONA -->
                <div class="tbw-field">
                    <label class="tbw-label">Zona del Tatuaje *</label>
                    <select name="zone" class="tbw-control tbw-zone-select" required>
                        <option value="">— Selecciona la zona —</option>
                        <?php foreach ($zones as $group => $opts): ?>
                            <optgroup label="<?= esc_attr($group) ?>">
                                <?php foreach ($opts as $val => $label): ?>
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
                            <?php for ($h=10; $h<=19; $h++): foreach (['00','30'] as $m):
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

                <!-- BOTÓN — exactamente como la captura: "Agendar Ahora ↗" -->
                <div class="tbw-submit-row">
                    <button type="submit" class="tbw-submit" id="<?= $uid ?>-btn">
                        <span class="tbw-btn-text"><?= esc_html($settings['btn_text'] ?? 'Agendar Ahora') ?></span>
                        <span class="tbw-btn-icon">↗</span>
                        <span class="tbw-btn-loading" style="display:none">Enviando…</span>
                    </button>
                </div>

            </form>
        </div>

        <!-- ── COLUMNA DERECHA: IMAGEN + MAPA DE ZONA ── -->
        <div class="tbw-col-body" id="<?= $uid ?>-body-col">

            <!-- Placeholder mientras no hay imagen ni selección -->
            <div class="tbw-idle" id="<?= $uid ?>-idle">
                <div class="tbw-idle-hint">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10l-4 4m0 0l-4-4m4 4V3m0 14a9 9 0 110-18 9 9 0 010 18z"/></svg>
                    <p>Selecciona tu género<br>y zona del tatuaje</p>
                </div>
            </div>

            <!-- Imagen con overlays de zona estilo captura: recuadros con líneas -->
            <div class="tbw-viewer" id="<?= $uid ?>-viewer" style="display:none">
                <div class="tbw-img-container" id="<?= $uid ?>-img-container">
                    <img class="tb-body-photo" id="<?= $uid ?>-img" src="" alt="">
                    <!-- Canvas para recuadros estilo captura -->
                    <canvas class="tbw-canvas" id="<?= $uid ?>-canvas"></canvas>
                    <!-- Etiqueta de zona flotante al lado -->
                    <div class="tbw-zone-callout" id="<?= $uid ?>-callout" style="display:none">
                        <div class="tbw-callout-line"></div>
                        <div class="tbw-callout-box">
                            <span class="tbw-callout-name" id="<?= $uid ?>-callout-name"></span>
                            <span class="tbw-callout-pain" id="<?= $uid ?>-callout-pain"></span>
                        </div>
                    </div>
                </div>

                <!-- Panel dolor debajo de imagen -->
                <div class="tbw-pain-panel" id="<?= $uid ?>-pain-panel" style="display:none">
                    <div class="tbw-pain-row">
                        <span class="tbw-pain-zone" id="<?= $uid ?>-pzone"></span>
                        <span class="tbw-pain-num" id="<?= $uid ?>-pnum"></span>
                    </div>
                    <div class="tbw-pain-track">
                        <div class="tbw-pain-fill" id="<?= $uid ?>-pfill"></div>
                    </div>
                </div>

                <!-- Leyenda colores -->
                <div class="tbw-legend">
                    <span><i style="background:#22c55e"></i>Bajo</span>
                    <span><i style="background:#eab308"></i>Medio</span>
                    <span><i style="background:#f97316"></i>Alto</span>
                    <span><i style="background:#ef4444"></i>Extremo</span>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
(function(){
    var uid = '<?= $uid ?>';
    // Registrar instancia para que script.js la inicialice
    window.TB_INSTANCES = window.TB_INSTANCES || [];
    window.TB_INSTANCES.push(uid);
})();
</script>
