<?php
/**
 * Plugin Name: Browser Recommendation Banner
 * Plugin URI: https://baltazarg.xyz
 * Description: Muestra un banner personalizable recomendando Chrome o Edge a usuarios de Safari y Firefox.
 * Version:     2.0.0
 * Author: Baltazar Dev
 * Author URI: https://baltazarg.xyz
 * License:     GPL2
 * Text Domain: browser-rec
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// ──────────────────────────────────────────────
// 1. DEFAULTS
// ──────────────────────────────────────────────
function brec_defaults() {
    return [
        'enabled'            => 1,
        'title'              => '¡Mejora tu experiencia!',
        'message'            => 'Parece que estás usando un navegador que puede no ofrecer la mejor experiencia en este sitio. Te recomendamos usar <strong>Google Chrome</strong> o <strong>Microsoft Edge</strong> para disfrutar de todas las funcionalidades.',
        'btn_chrome_text'    => 'Abrir en Chrome',
        'btn_chrome_url'     => 'https://www.google.com/chrome/',
        'btn_edge_text'      => 'Abrir en Edge',
        'btn_edge_url'       => 'https://www.microsoft.com/edge',
        'btn_dismiss_text'   => 'Continuar de todos modos',
        'position'           => 'top',
        'detect_safari'      => 1,
        'detect_firefox'     => 1,
        // Device targeting
        'show_on_mobile'     => 1,
        'show_on_tablet'     => 1,
        'show_on_desktop'    => 1,
        // Deep links
        'deeplink_enabled'   => 1,
        // Colors
        'bg_color'           => '#0f172a',
        'text_color'         => '#e2e8f0',
        'title_color'        => '#f8fafc',
        'accent_color'       => '#6366f1',
        'btn_primary_bg'     => '#6366f1',
        'btn_primary_text'   => '#ffffff',
        'btn_secondary_bg'   => '#1e293b',
        'btn_secondary_text' => '#94a3b8',
        'overlay_color'      => 'rgba(0,0,0,0.7)',
        // Typography
        'font_family'        => 'inherit',
        'title_size'         => '22',
        'message_size'       => '15',
        'btn_size'           => '14',
        // Misc
        'border_radius'      => '12',
        'show_icon'          => 1,
        'animation'          => 'slide',
        'cookie_days'        => '7',
        'zindex'             => '2147483647',
        'delay_ms'           => '1500',   // ms to wait after preloader gone
    ];
}

// ──────────────────────────────────────────────
// 2. SANITIZE & OPTIONS
// ──────────────────────────────────────────────
add_action( 'admin_init', function () {
    register_setting( 'brec_group', 'brec_options', [ 'sanitize_callback' => 'brec_sanitize' ] );
} );

function brec_sanitize( $input ) {
    $defaults  = brec_defaults();
    $clean     = [];
    $bool_keys = [ 'enabled','detect_safari','detect_firefox','show_icon','show_on_mobile','show_on_tablet','show_on_desktop','deeplink_enabled' ];
    $color_keys= [ 'bg_color','text_color','title_color','accent_color','btn_primary_bg','btn_primary_text','btn_secondary_bg','btn_secondary_text' ];

    foreach ( $defaults as $key => $default ) {
        if ( in_array( $key, $bool_keys ) ) {
            $clean[ $key ] = isset( $input[ $key ] ) ? 1 : 0;
        } elseif ( in_array( $key, $color_keys ) ) {
            $clean[ $key ] = sanitize_hex_color( $input[ $key ] ?? $default ) ?: $default;
        } else {
            $clean[ $key ] = sanitize_text_field( $input[ $key ] ?? $default );
        }
    }
    if ( isset( $input['message'] ) ) {
        $clean['message'] = wp_kses( $input['message'], [
            'strong' => [], 'em' => [], 'a' => [ 'href' => [], 'target' => [] ]
        ] );
    }
    return $clean;
}

function brec_opt( $key = null ) {
    $opts = wp_parse_args( get_option( 'brec_options', [] ), brec_defaults() );
    return $key ? ( $opts[ $key ] ?? null ) : $opts;
}

// ──────────────────────────────────────────────
// 3. ADMIN MENU
// ──────────────────────────────────────────────
add_action( 'admin_menu', function () {
    add_options_page(
        'Browser Recommendation',
        'Browser Recommendation',
        'manage_options',
        'browser-rec',
        'brec_settings_page'
    );
} );

function brec_settings_page() {
    $o = brec_opt();
    ?>
    <div class="wrap" id="brec-admin">
    <h1 style="display:flex;align-items:center;gap:10px;">
        <span style="font-size:28px;">🌐</span> Browser Recommendation Banner
        <span style="background:#6366f1;color:#fff;font-size:12px;padding:2px 8px;border-radius:20px;font-weight:500;">v2.0</span>
    </h1>
    <p style="color:#666;">Muestra un aviso personalizado a usuarios de Safari y Firefox sugiriendo Chrome o Edge.</p>

    <form method="post" action="options.php">
        <?php settings_fields( 'brec_group' ); ?>

        <div class="brec-tabs">
            <button type="button" class="brec-tab active" data-tab="general">⚙️ General</button>
            <button type="button" class="brec-tab" data-tab="devices">📱 Dispositivos</button>
            <button type="button" class="brec-tab" data-tab="content">✏️ Contenido</button>
            <button type="button" class="brec-tab" data-tab="design">🎨 Diseño</button>
            <button type="button" class="brec-tab" data-tab="preview">👁 Vista previa</button>
        </div>

        <!-- ══ GENERAL ══ -->
        <div class="brec-panel active" id="tab-general">
            <table class="form-table">
                <tr>
                    <th>Activar banner</th>
                    <td><label><input type="checkbox" name="brec_options[enabled]" value="1" <?php checked($o['enabled'],1); ?>> Mostrar el banner</label></td>
                </tr>
                <tr>
                    <th>Detectar Safari</th>
                    <td><label><input type="checkbox" name="brec_options[detect_safari]" value="1" <?php checked($o['detect_safari'],1); ?>> Mostrar aviso a usuarios de Safari</label></td>
                </tr>
                <tr>
                    <th>Detectar Firefox</th>
                    <td><label><input type="checkbox" name="brec_options[detect_firefox]" value="1" <?php checked($o['detect_firefox'],1); ?>> Mostrar aviso a usuarios de Firefox</label></td>
                </tr>
                <tr>
                    <th>Posición</th>
                    <td>
                        <select name="brec_options[position]">
                            <option value="top"    <?php selected($o['position'],'top'); ?>>Barra superior</option>
                            <option value="bottom" <?php selected($o['position'],'bottom'); ?>>Barra inferior</option>
                            <option value="modal"  <?php selected($o['position'],'modal'); ?>>Modal centrado</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th>Animación</th>
                    <td>
                        <select name="brec_options[animation]">
                            <option value="slide" <?php selected($o['animation'],'slide'); ?>>Deslizar</option>
                            <option value="fade"  <?php selected($o['animation'],'fade'); ?>>Desvanecer</option>
                            <option value="none"  <?php selected($o['animation'],'none'); ?>>Sin animación</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th>Días antes de volver a mostrar</th>
                    <td>
                        <input type="number" name="brec_options[cookie_days]" value="<?php echo esc_attr($o['cookie_days']); ?>" min="0" max="365" style="width:80px;">
                        días <span class="description">(0 = mostrar siempre)</span>
                    </td>
                </tr>
                <tr>
                    <th>Retraso para mostrar el banner</th>
                    <td>
                        <input type="number" name="brec_options[delay_ms]" value="<?php echo esc_attr($o['delay_ms']); ?>" min="0" max="15000" step="500" style="width:90px;">
                        ms
                        <p class="description">
                            Tiempo de espera <strong>después de que el preloader desaparezca</strong> antes de mostrar el banner.<br>
                            <code>0</code> = inmediato &nbsp;|&nbsp; <code>1500</code> = 1,5 s &nbsp;|&nbsp; <code>5000</code> = 5 s<br>
                            <em>Recomendado para iPhone / Safari: entre 1000 y 2000 ms.</em>
                        </p>
                    </td>
                </tr>
                <tr>
                    <th>Mostrar icono</th>
                    <td><label><input type="checkbox" name="brec_options[show_icon]" value="1" <?php checked($o['show_icon'],1); ?>> Mostrar icono de navegador 🌐</label></td>
                </tr>
                <tr>
                    <th>Z-index (fallback)</th>
                    <td>
                        <input type="number" name="brec_options[zindex]" value="<?php echo esc_attr($o['zindex']); ?>" min="1" max="2147483647" style="width:140px;">
                        <p class="description">
                            El banner detecta automáticamente el z-index del preloader de Alobaidi y se coloca justo <strong>debajo</strong> de él.<br>
                            Este valor se usa solo si el preloader no se encuentra en el DOM (por ejemplo, en páginas donde no aparece).
                        </p>
                    </td>
                </tr>
            </table>
        </div>

        <!-- ══ DISPOSITIVOS ══ -->
        <div class="brec-panel" id="tab-devices">
            <h3>¿En qué dispositivos mostrar el banner?</h3>
            <p style="color:#666;margin-top:-8px;">Puedes combinarlo como quieras. Si no marcas ninguno el banner no aparecerá.</p>

            <div class="brec-device-grid">
                <label class="brec-device-card <?php echo $o['show_on_mobile']  ? 'brec-checked' : ''; ?>">
                    <input type="checkbox" name="brec_options[show_on_mobile]" value="1" <?php checked($o['show_on_mobile'],1); ?>>
                    <span class="brec-device-icon">📱</span>
                    <span class="brec-device-label">Móvil</span>
                    <span class="brec-device-sub">≤ 767 px</span>
                </label>
                <label class="brec-device-card <?php echo $o['show_on_tablet']  ? 'brec-checked' : ''; ?>">
                    <input type="checkbox" name="brec_options[show_on_tablet]" value="1" <?php checked($o['show_on_tablet'],1); ?>>
                    <span class="brec-device-icon">🖥</span>
                    <span class="brec-device-label">Tablet</span>
                    <span class="brec-device-sub">768 – 1024 px</span>
                </label>
                <label class="brec-device-card <?php echo $o['show_on_desktop'] ? 'brec-checked' : ''; ?>">
                    <input type="checkbox" name="brec_options[show_on_desktop]" value="1" <?php checked($o['show_on_desktop'],1); ?>>
                    <span class="brec-device-icon">💻</span>
                    <span class="brec-device-label">PC / Desktop</span>
                    <span class="brec-device-sub">≥ 1025 px</span>
                </label>
            </div>

            <hr style="margin:28px 0 20px;">

            <h3>🔗 Deep links en móvil</h3>
            <p style="color:#666;margin-top:-6px;">
                Cuando el usuario está en un <strong>teléfono</strong>, los botones intentarán <strong>abrir Chrome o Edge directamente</strong>
                en la app ya instalada. Si la app no está, redirige a la tienda automáticamente.
            </p>

            <table class="form-table">
                <tr>
                    <th>Activar deep links</th>
                    <td>
                        <label>
                            <input type="checkbox" name="brec_options[deeplink_enabled]" value="1" <?php checked($o['deeplink_enabled'],1); ?>>
                            Abrir la app del navegador directamente (solo móviles)
                        </label>
                    </td>
                </tr>
            </table>

            <div class="brec-info-box">
                <strong>ℹ️ ¿Cómo funcionan los deep links?</strong>
                <ul>
                    <li><strong>iOS (Safari)</strong> — Abre <code>googlechrome://</code> o <code>microsoft-edge://</code>. Si la app está instalada se abre al instante; si no, tras 1,5 s redirige a la App Store.</li>
                    <li><strong>Android (Firefox)</strong> — Usa el esquema <code>intent://</code> de Android. Si Chrome/Edge no está instalado, Android lleva directamente a la Play Store.</li>
                    <li>En <strong>PC y Tablet</strong> siempre se usa la URL de descarga normal aunque los deep links estén activados.</li>
                </ul>
            </div>
        </div>

        <!-- ══ CONTENIDO ══ -->
        <div class="brec-panel" id="tab-content">
            <table class="form-table">
                <tr>
                    <th>Título</th>
                    <td><input type="text" name="brec_options[title]" value="<?php echo esc_attr($o['title']); ?>" class="large-text"></td>
                </tr>
                <tr>
                    <th>Mensaje</th>
                    <td>
                        <textarea name="brec_options[message]" rows="4" class="large-text"><?php echo esc_textarea($o['message']); ?></textarea>
                        <p class="description">Puedes usar &lt;strong&gt;, &lt;em&gt; y &lt;a href=""&gt;</p>
                    </td>
                </tr>

                <tr><th colspan="2"><h3 style="margin:10px 0 0">Botón Chrome</h3></th></tr>
                <tr>
                    <th>Texto del botón</th>
                    <td><input type="text" name="brec_options[btn_chrome_text]" value="<?php echo esc_attr($o['btn_chrome_text']); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th>URL de descarga <small>(PC / Tablet)</small></th>
                    <td>
                        <input type="url" name="brec_options[btn_chrome_url]" value="<?php echo esc_attr($o['btn_chrome_url']); ?>" class="large-text">
                        <p class="description">En móvil con deep links activos se usa solo si la app no está instalada.</p>
                    </td>
                </tr>

                <tr><th colspan="2"><h3 style="margin:10px 0 0">Botón Edge</h3></th></tr>
                <tr>
                    <th>Texto del botón</th>
                    <td><input type="text" name="brec_options[btn_edge_text]" value="<?php echo esc_attr($o['btn_edge_text']); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th>URL de descarga <small>(PC / Tablet)</small></th>
                    <td><input type="url" name="brec_options[btn_edge_url]" value="<?php echo esc_attr($o['btn_edge_url']); ?>" class="large-text"></td>
                </tr>

                <tr>
                    <th>Texto "Cerrar"</th>
                    <td><input type="text" name="brec_options[btn_dismiss_text]" value="<?php echo esc_attr($o['btn_dismiss_text']); ?>" class="regular-text"></td>
                </tr>
            </table>
        </div>

        <!-- ══ DISEÑO ══ -->
        <div class="brec-panel" id="tab-design">
            <table class="form-table">
                <tr><th colspan="2"><h3 style="margin:0">Colores</h3></th></tr>
                <tr><th>Fondo del banner</th>       <td><input type="color" name="brec_options[bg_color]"            value="<?php echo esc_attr($o['bg_color']); ?>"></td></tr>
                <tr><th>Color del texto</th>         <td><input type="color" name="brec_options[text_color]"          value="<?php echo esc_attr($o['text_color']); ?>"></td></tr>
                <tr><th>Color del título</th>        <td><input type="color" name="brec_options[title_color]"         value="<?php echo esc_attr($o['title_color']); ?>"></td></tr>
                <tr><th>Color de acento</th>         <td><input type="color" name="brec_options[accent_color]"        value="<?php echo esc_attr($o['accent_color']); ?>"></td></tr>
                <tr><th>Botones primarios — fondo</th><td><input type="color" name="brec_options[btn_primary_bg]"     value="<?php echo esc_attr($o['btn_primary_bg']); ?>"></td></tr>
                <tr><th>Botones primarios — texto</th><td><input type="color" name="brec_options[btn_primary_text]"   value="<?php echo esc_attr($o['btn_primary_text']); ?>"></td></tr>
                <tr><th>Botón "Continuar" — fondo</th><td><input type="color" name="brec_options[btn_secondary_bg]"   value="<?php echo esc_attr($o['btn_secondary_bg']); ?>"></td></tr>
                <tr><th>Botón "Continuar" — texto</th><td><input type="color" name="brec_options[btn_secondary_text]" value="<?php echo esc_attr($o['btn_secondary_text']); ?>"></td></tr>
                <tr>
                    <th>Overlay <small>(solo modal)</small></th>
                    <td><input type="text" name="brec_options[overlay_color]" value="<?php echo esc_attr($o['overlay_color']); ?>" class="regular-text" placeholder="rgba(0,0,0,0.7)"></td>
                </tr>

                <tr><th colspan="2"><h3 style="margin:16px 0 0">Tipografía</h3></th></tr>
                <tr>
                    <th>Familia de fuente</th>
                    <td>
                        <select name="brec_options[font_family]">
                            <option value="inherit"                    <?php selected($o['font_family'],'inherit'); ?>>Heredar del tema</option>
                            <option value="'Segoe UI',sans-serif"      <?php selected($o['font_family'],"'Segoe UI',sans-serif"); ?>>Segoe UI</option>
                            <option value="Georgia,serif"              <?php selected($o['font_family'],'Georgia,serif'); ?>>Georgia</option>
                            <option value="'Courier New',monospace"    <?php selected($o['font_family'],"'Courier New',monospace"); ?>>Courier New</option>
                            <option value="'Trebuchet MS',sans-serif"  <?php selected($o['font_family'],"'Trebuchet MS',sans-serif"); ?>>Trebuchet MS</option>
                        </select>
                    </td>
                </tr>
                <tr><th>Tamaño del título (px)</th> <td><input type="number" name="brec_options[title_size]"   value="<?php echo esc_attr($o['title_size']); ?>"   min="12" max="60" style="width:80px;"></td></tr>
                <tr><th>Tamaño del mensaje (px)</th><td><input type="number" name="brec_options[message_size]" value="<?php echo esc_attr($o['message_size']); ?>" min="10" max="40" style="width:80px;"></td></tr>
                <tr><th>Tamaño de botones (px)</th> <td><input type="number" name="brec_options[btn_size]"     value="<?php echo esc_attr($o['btn_size']); ?>"     min="10" max="30" style="width:80px;"></td></tr>
                <tr><th>Border radius (px)</th>      <td><input type="number" name="brec_options[border_radius]" value="<?php echo esc_attr($o['border_radius']); ?>" min="0" max="40" style="width:80px;"></td></tr>
            </table>
        </div>

        <!-- ══ PREVIEW ══ -->
        <div class="brec-panel" id="tab-preview">
            <p style="color:#888;">Vista previa con los valores guardados actualmente.</p>
            <div id="brec-live-preview" style="margin-top:16px;border:2px dashed #ddd;border-radius:8px;padding:20px;background:#f9f9f9;">
                <?php brec_render_preview( $o ); ?>
            </div>
            <hr>
            <h3>🧪 Probar en tu navegador</h3>
            <p style="color:#666;">Para ver el banner en Chrome cambia el User-Agent:<br>
            <strong>DevTools (F12) → menú ⋮ → More tools → Network conditions → User agent → desmarca "Use default" → elige Safari o Firefox → recarga.</strong></p>
        </div>

        <?php submit_button( 'Guardar cambios' ); ?>
    </form>
    </div>

    <style>
    #brec-admin { max-width: 920px; }

    /* Tabs */
    .brec-tabs  { display:flex; gap:4px; border-bottom:2px solid #ddd; flex-wrap:wrap; }
    .brec-tab   { background:none; border:none; padding:10px 16px; cursor:pointer; font-size:13px;
                  color:#555; border-radius:6px 6px 0 0; border:1px solid transparent; margin-bottom:-2px; }
    .brec-tab.active { background:#fff; border-color:#ddd #ddd #fff; color:#2271b1; font-weight:600; }
    .brec-panel { display:none; background:#fff; border:1px solid #ddd; border-top:none;
                  padding:24px; border-radius:0 6px 6px 6px; }
    .brec-panel.active { display:block; }

    /* Device cards */
    .brec-device-grid { display:flex; gap:16px; flex-wrap:wrap; margin:16px 0; }
    .brec-device-card {
        display:flex; flex-direction:column; align-items:center; gap:6px;
        padding:20px 28px; border:2px solid #ddd; border-radius:12px;
        cursor:pointer; transition:all .2s; background:#fafafa; min-width:130px; position:relative;
    }
    .brec-device-card input[type=checkbox] { position:absolute; top:10px; right:10px; }
    .brec-device-card:hover     { border-color:#6366f1; background:#f5f3ff; }
    .brec-device-card.brec-checked { border-color:#6366f1; background:#ede9fe; }
    .brec-device-icon  { font-size:32px; }
    .brec-device-label { font-weight:600; font-size:14px; color:#333; }
    .brec-device-sub   { font-size:11px; color:#888; }

    /* Info box */
    .brec-info-box {
        background:#f0f9ff; border-left:4px solid #0ea5e9;
        padding:14px 18px; border-radius:0 8px 8px 0; margin-top:16px;
    }
    .brec-info-box ul { margin:8px 0 0 16px; }
    .brec-info-box li { margin-bottom:6px; font-size:13px; color:#444; }
    </style>

    <script>
    document.querySelectorAll('.brec-tab').forEach(function(btn){
        btn.addEventListener('click', function(){
            document.querySelectorAll('.brec-tab').forEach(function(b){ b.classList.remove('active'); });
            document.querySelectorAll('.brec-panel').forEach(function(p){ p.classList.remove('active'); });
            btn.classList.add('active');
            document.getElementById('tab-' + btn.dataset.tab).classList.add('active');
        });
    });
    document.querySelectorAll('.brec-device-card input').forEach(function(cb){
        cb.addEventListener('change', function(){
            cb.closest('.brec-device-card').classList.toggle('brec-checked', cb.checked);
        });
    });
    </script>
    <?php
}

// ──────────────────────────────────────────────
// 4. ADMIN PREVIEW RENDER
// ──────────────────────────────────────────────
function brec_render_preview( $o ) {
    $is_modal = $o['position'] === 'modal';
    $style = "background:{$o['bg_color']};color:{$o['text_color']};font-family:{$o['font_family']};
              border-radius:{$o['border_radius']}px;padding:24px 28px;position:relative;
              " . ($is_modal ? "max-width:480px;margin:0 auto;" : "width:100%;box-sizing:border-box;");
    $btn_r = min( (int)$o['border_radius'], 8 );
    echo "<div style='{$style}'>";
    if ( $o['show_icon'] ) echo "<div style='font-size:36px;margin-bottom:12px;'>🌐</div>";
    echo "<div style='font-size:{$o['title_size']}px;font-weight:700;color:{$o['title_color']};margin-bottom:10px;'>" . esc_html($o['title']) . "</div>";
    echo "<div style='font-size:{$o['message_size']}px;line-height:1.6;margin-bottom:20px;'>" . wp_kses_post($o['message']) . "</div>";
    echo "<div style='display:flex;flex-wrap:wrap;gap:10px;align-items:center;'>";
    echo "<a href='#' style='background:{$o['btn_primary_bg']};color:{$o['btn_primary_text']};font-size:{$o['btn_size']}px;padding:10px 20px;border-radius:{$btn_r}px;text-decoration:none;font-weight:600;'>🟢 " . esc_html($o['btn_chrome_text']) . "</a>";
    echo "<a href='#' style='background:{$o['btn_primary_bg']};color:{$o['btn_primary_text']};font-size:{$o['btn_size']}px;padding:10px 20px;border-radius:{$btn_r}px;text-decoration:none;font-weight:600;'>🔵 " . esc_html($o['btn_edge_text']) . "</a>";
    echo "<a href='#' style='background:{$o['btn_secondary_bg']};color:{$o['btn_secondary_text']};font-size:{$o['btn_size']}px;padding:8px 16px;border-radius:{$btn_r}px;text-decoration:none;'>" . esc_html($o['btn_dismiss_text']) . "</a>";
    echo "</div>";
    echo "<div style='position:absolute;top:12px;right:14px;font-size:20px;opacity:.4;'>×</div>";
    echo "</div>";
}

// ──────────────────────────────────────────────
// 5. FRONTEND OUTPUT
// ──────────────────────────────────────────────
add_action( 'wp_footer', 'brec_frontend_output' );
function brec_frontend_output() {
    $o = brec_opt();
    if ( ! $o['enabled'] ) return;

    $config = [
        'detectSafari'    => (bool) $o['detect_safari'],
        'detectFirefox'   => (bool) $o['detect_firefox'],
        'showOnMobile'    => (bool) $o['show_on_mobile'],
        'showOnTablet'    => (bool) $o['show_on_tablet'],
        'showOnDesktop'   => (bool) $o['show_on_desktop'],
        'deeplinkEnabled' => (bool) $o['deeplink_enabled'],
        'position'        => $o['position'],
        'animation'       => $o['animation'],
        'cookieDays'      => (int)  $o['cookie_days'],
        'showIcon'        => (bool) $o['show_icon'],
        'title'           => $o['title'],
        'message'         => $o['message'],
        'btnChromeText'   => $o['btn_chrome_text'],
        'chromeUrl'       => $o['btn_chrome_url'],
        'btnEdgeText'     => $o['btn_edge_text'],
        'edgeUrl'         => $o['btn_edge_url'],
        'btnDismiss'      => $o['btn_dismiss_text'],
        'zindex'          => (int) $o['zindex'],
        'delayMs'         => (int) $o['delay_ms'],
    ];

    $css_vars = "
        --brec-bg:       {$o['bg_color']};
        --brec-text:     {$o['text_color']};
        --brec-title:    {$o['title_color']};
        --brec-accent:   {$o['accent_color']};
        --brec-btn-pbg:  {$o['btn_primary_bg']};
        --brec-btn-ptx:  {$o['btn_primary_text']};
        --brec-btn-sbg:  {$o['btn_secondary_bg']};
        --brec-btn-stx:  {$o['btn_secondary_text']};
        --brec-overlay:  {$o['overlay_color']};
        --brec-font:     {$o['font_family']};
        --brec-title-sz: {$o['title_size']}px;
        --brec-msg-sz:   {$o['message_size']}px;
        --brec-btn-sz:   {$o['btn_size']}px;
        --brec-radius:   {$o['border_radius']}px;
    ";
    ?>
<style id="brec-css">
:root { <?php echo $css_vars; ?> }

/* ══ COOKIE BAR — fixed bottom, card style ══ */
#brec-wrap {
    font-family: var(--brec-font);
    box-sizing: border-box;
    position: fixed;
    bottom: 16px;
    left: 50%;
    transform: translateX(-50%);
    width: calc(100% - 32px);
    max-width: 560px;
    background: var(--brec-bg);
    color: var(--brec-text);
    border-radius: var(--brec-radius);
    padding: 18px 20px 16px;
    box-shadow: 0 8px 40px rgba(0,0,0,.45), 0 2px 8px rgba(0,0,0,.2);
    border-top: 3px solid var(--brec-accent);
    /* z-index set inline via JS */
}

/* ── Header row: icon + title + close ── */
.brec-header {
    display: flex;
    align-items: center;
    gap: 9px;
    margin-bottom: 8px;
}
.brec-icon {
    font-size: 20px;
    line-height: 1;
    flex-shrink: 0;
}
.brec-title {
    font-size: var(--brec-title-sz);
    font-weight: 700;
    color: var(--brec-title);
    line-height: 1.2;
    flex: 1;
    margin: 0;
}
.brec-close {
    background: none;
    border: none;
    color: var(--brec-text);
    font-size: 20px;
    line-height: 1;
    cursor: pointer;
    opacity: .45;
    padding: 2px 4px;
    transition: opacity .15s;
    flex-shrink: 0;
    margin-left: auto;
}
.brec-close:hover { opacity: 1; }

/* ── Message ── */
.brec-msg {
    font-size: var(--brec-msg-sz);
    line-height: 1.55;
    margin: 0 0 14px;
    opacity: .88;
}
.brec-msg strong { color: var(--brec-title); opacity: 1; }

/* ── Buttons row ── */
.brec-actions {
    display: flex;
    gap: 8px;
    align-items: center;
    flex-wrap: wrap;
}
.brec-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: var(--brec-btn-sz);
    font-weight: 600;
    padding: 9px 16px;
    border-radius: calc(var(--brec-radius) * .55);
    text-decoration: none;
    cursor: pointer;
    border: none;
    white-space: nowrap;
    transition: filter .18s, transform .12s;
    -webkit-tap-highlight-color: transparent;
}
.brec-btn:hover  { filter: brightness(1.1); transform: translateY(-1px); }
.brec-btn:active { transform: scale(.97); }
.brec-btn-primary { background: var(--brec-btn-pbg); color: var(--brec-btn-ptx); }
.brec-btn-dismiss {
    background: none;
    color: var(--brec-btn-stx);
    font-weight: 400;
    font-size: calc(var(--brec-btn-sz) * .9);
    padding: 9px 4px;
    opacity: .7;
    margin-left: auto;
}
.brec-btn-dismiss:hover { opacity: 1; transform: none; filter: none; }

/* ── Entrance animation ── */
@keyframes brec-cookie-in {
    from { transform: translateX(-50%) translateY(120%); opacity: 0; }
    to   { transform: translateX(-50%) translateY(0);    opacity: 1; }
}
@keyframes brec-cookie-fade {
    from { opacity: 0; transform: translateX(-50%) translateY(10px); }
    to   { opacity: 1; transform: translateX(-50%) translateY(0); }
}
#brec-wrap.brec-anim-slide { animation: brec-cookie-in  .45s cubic-bezier(.22,1,.36,1) forwards; }
#brec-wrap.brec-anim-fade  { animation: brec-cookie-fade .4s ease forwards; }

/* ── Mobile: full width, flush to bottom ── */
@media (max-width: 600px) {
    #brec-wrap {
        bottom: 0;
        left: 0;
        right: 0;
        width: 100%;
        max-width: 100%;
        transform: none;
        border-radius: var(--brec-radius) var(--brec-radius) 0 0;
        border-top-width: 3px;
        padding: 14px 16px 20px; /* extra bottom pad for iPhone home bar */
    }
    @keyframes brec-cookie-in-mobile {
        from { transform: translateY(110%); opacity: 0; }
        to   { transform: translateY(0);    opacity: 1; }
    }
    @keyframes brec-cookie-fade-mobile {
        from { opacity: 0; transform: translateY(8px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    #brec-wrap.brec-anim-slide { animation: brec-cookie-in-mobile  .42s cubic-bezier(.22,1,.36,1) forwards; }
    #brec-wrap.brec-anim-fade  { animation: brec-cookie-fade-mobile .38s ease forwards; }

    .brec-title   { font-size: calc(var(--brec-title-sz) * .88); }
    .brec-msg     { font-size: calc(var(--brec-msg-sz)   * .92); margin-bottom: 12px; }
    .brec-actions { gap: 7px; }
    .brec-btn-primary { flex: 1; justify-content: center; }
    .brec-btn-dismiss { margin-left: 0; flex-basis: 100%; text-align: center; padding: 6px 4px; }
}
</style>

<script id="brec-js">
(function(){
    var C = <?php echo wp_json_encode( $config ); ?>;

    /* ── Browser detection ── */
    function isSafari()  { var ua = navigator.userAgent; return /Safari/.test(ua) && !/Chrome|CriOS|FxiOS/.test(ua); }
    function isFirefox() { return /Firefox|FxiOS/.test(navigator.userAgent); }

    /* ── Device type by viewport width ── */
    function getDevice() {
        var w = window.innerWidth || document.documentElement.clientWidth;
        if (w <= 767)  return 'mobile';
        if (w <= 1024) return 'tablet';
        return 'desktop';
    }
    function deviceAllowed() {
        var d = getDevice();
        return (d === 'mobile'  && C.showOnMobile)  ||
               (d === 'tablet'  && C.showOnTablet)  ||
               (d === 'desktop' && C.showOnDesktop);
    }
    function isMobileDevice() { return getDevice() === 'mobile'; }

    /* ── OS detection ── */
    function isIOS()     { return /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream; }
    function isAndroid() { return /Android/.test(navigator.userAgent); }

    /* ── Cookie helpers ── */
    function getCookie(n) { var v = document.cookie.match('(^|;)\\s*' + n + '\\s*=\\s*([^;]+)'); return v ? v.pop() : null; }
    function setCookie(n, days) { var d = new Date(); d.setTime(d.getTime() + days * 864e5); document.cookie = n + '=1;expires=' + d.toUTCString() + ';path=/'; }

    /* ── Deep link builders ── */
    function pageHost() { return window.location.href.replace(/^https?:\/\//, ''); }

    function chromeDeepLink() {
        if (!C.deeplinkEnabled || !isMobileDevice()) return C.chromeUrl;
        if (isIOS())     return 'googlechrome://' + pageHost();
        if (isAndroid()) return 'intent://' + pageHost() + '#Intent;scheme=https;package=com.android.chrome;S.browser_fallback_url=' + encodeURIComponent('https://play.google.com/store/apps/details?id=com.android.chrome') + ';end';
        return C.chromeUrl;
    }
    function edgeDeepLink() {
        if (!C.deeplinkEnabled || !isMobileDevice()) return C.edgeUrl;
        if (isIOS())     return 'microsoft-edge://' + pageHost();
        if (isAndroid()) return 'intent://' + pageHost() + '#Intent;scheme=https;package=com.microsoft.emmx;S.browser_fallback_url=' + encodeURIComponent('https://play.google.com/store/apps/details?id=com.microsoft.emmx') + ';end';
        return C.edgeUrl;
    }

    /* iOS needs timer-based App Store fallback */
    function iosOpen(scheme, storeUrl) {
        var t0 = Date.now();
        window.location.href = scheme;
        setTimeout(function() {
            /* Only redirect to store if the page is still visible (app didn't open) */
            if (!document.hidden && Date.now() - t0 < 2500) {
                window.location.href = storeUrl;
            }
        }, 1500);
    }

    function handleChrome(e) {
        if (!C.deeplinkEnabled || !isMobileDevice()) return; /* href handled by browser */
        e.preventDefault();
        if (isIOS()) {
            iosOpen('googlechrome://' + pageHost(), 'https://apps.apple.com/app/google-chrome/id535886823');
        } else if (isAndroid()) {
            window.location.href = chromeDeepLink();
        } else {
            window.open(C.chromeUrl, '_blank');
        }
    }
    function handleEdge(e) {
        if (!C.deeplinkEnabled || !isMobileDevice()) return;
        e.preventDefault();
        if (isIOS()) {
            iosOpen('microsoft-edge://' + pageHost(), 'https://apps.apple.com/app/microsoft-edge/id1288723196');
        } else if (isAndroid()) {
            window.location.href = edgeDeepLink();
        } else {
            window.open(C.edgeUrl, '_blank');
        }
    }

    /* ── Z-index: sit BELOW the Alobaidi preloader ──
       We read the preloader's actual z-index from the DOM so this works
       regardless of the preloader version or any future changes.
       Fallback: use the admin-configured value if preloader is not found.    */
    function getBannerZIndex() {
        /* Alobaidi preloader uses #the_preloader or #preloader as its wrapper */
        var selectors = ['#the_preloader', '#preloader', '.the-preloader', '.preloader-wrap', '[id*="preloader"]'];
        for (var i = 0; i < selectors.length; i++) {
            var el = document.querySelector(selectors[i]);
            if (el) {
                var z = parseInt(window.getComputedStyle(el).zIndex, 10);
                if (!isNaN(z) && z > 0) {
                    return z - 1;   /* one step below the preloader */
                }
            }
        }
        return C.zindex;            /* fallback to admin-configured value */
    }

    /* ── Dismiss ── */
    function dismiss() {
        if (C.cookieDays > 0) setCookie('brec_dismissed', C.cookieDays);
        var el = document.getElementById('brec-wrap');
        if (!el) return;
        var isMob = window.innerWidth <= 600;
        el.style.transition = 'transform .35s cubic-bezier(.4,0,1,1), opacity .3s ease';
        el.style.transform  = isMob ? 'translateY(110%)' : 'translateX(-50%) translateY(120%)';
        el.style.opacity    = '0';
        setTimeout(function(){ el.remove(); }, 370);
    }

    /* ── Guard checks ── */
    var browserMatch = (C.detectSafari && isSafari()) || (C.detectFirefox && isFirefox());
    if (!browserMatch)  return;
    if (!deviceAllowed()) return;
    if (C.cookieDays > 0 && getCookie('brec_dismissed')) return;

    /* ── Show banner ── */
    function showBanner() {
        if (document.getElementById('brec-wrap')) return;

        var mob        = isMobileDevice() && C.deeplinkEnabled;
        var chromeHref = chromeDeepLink();
        var edgeHref   = edgeDeepLink();
        var extAttr    = (!mob) ? ' target="_blank" rel="noopener"' : '';
        var animCls    = C.animation === 'none' ? '' : (' brec-anim-' + C.animation);

        var iconHtml = C.showIcon ? '<span class="brec-icon">🌐</span>' : '';

        var inner =
            '<div class="brec-header">' +
                iconHtml +
                '<span class="brec-title">' + C.title + '</span>' +
                '<button class="brec-close" aria-label="Cerrar">&times;</button>' +
            '</div>' +
            '<div class="brec-msg">' + C.message + '</div>' +
            '<div class="brec-actions">' +
                '<a href="' + chromeHref + '" class="brec-btn brec-btn-primary brec-chrome"' + extAttr + '>🟢 ' + C.btnChromeText + '</a>' +
                '<a href="' + edgeHref   + '" class="brec-btn brec-btn-primary brec-edge"'   + extAttr + '>🔵 ' + C.btnEdgeText   + '</a>' +
                '<button class="brec-btn brec-btn-dismiss">' + C.btnDismiss + '</button>' +
            '</div>';

        var wrap = document.createElement('div');
        wrap.id        = 'brec-wrap';
        wrap.className = animCls.trim();
        wrap.style.zIndex = getBannerZIndex();
        wrap.innerHTML = inner;
        document.body.appendChild(wrap);

        var chromeBtn = wrap.querySelector('.brec-chrome');
        var edgeBtn   = wrap.querySelector('.brec-edge');
        if (chromeBtn) chromeBtn.addEventListener('click', handleChrome);
        if (edgeBtn)   edgeBtn.addEventListener('click', handleEdge);
        wrap.querySelectorAll('.brec-close, .brec-btn-dismiss').forEach(function(el){
            el.addEventListener('click', function(e){ e.preventDefault(); dismiss(); });
        });
    }

    /* ── Wait for Alobaidi preloader to finish, then wait delayMs, then show ──
       MutationObserver detects the exact moment the preloader disappears
       (removed from DOM, display:none, visibility:hidden or opacity:0).
       After that we wait C.delayMs before injecting — gives Safari/iPhone
       time to finish any CSS transitions and settle the layout.
       Safety net: if the observer never fires (unusual preloader), we trigger
       at window load + delayMs so the banner always appears eventually.         */
    function waitForPreloaderThenShow() {
        var PRELOADER_SELECTORS = [
            '#the_preloader', '#preloader', '.the-preloader',
            '.preloader-wrap', '#tp-preloader', '[id*="preloader"]:not(#brec-wrap)'
        ];

        var preloaderEl = null;
        for (var i = 0; i < PRELOADER_SELECTORS.length; i++) {
            var found = document.querySelector(PRELOADER_SELECTORS[i]);
            if (found) { preloaderEl = found; break; }
        }

        /* No preloader in DOM at all → wait delayMs then show */
        if (!preloaderEl) {
            setTimeout(showBanner, C.delayMs);
            return;
        }

        var triggered = false;
        function trigger() {
            if (triggered) return;
            triggered = true;
            if (observer) observer.disconnect();
            /* Wait the configured delay AFTER the preloader is gone */
            setTimeout(showBanner, C.delayMs);
        }

        function isGone(el) {
            if (!document.body.contains(el)) return true;
            var s = window.getComputedStyle(el);
            return s.display === 'none' ||
                   s.visibility === 'hidden' ||
                   parseFloat(s.opacity) === 0;
        }

        var observer = new MutationObserver(function() {
            if (isGone(preloaderEl)) trigger();
        });
        observer.observe(document.body, {
            childList: true, subtree: true,
            attributes: true, attributeFilter: ['style', 'class']
        });

        /* Safety net: window load + delayMs, so banner always appears */
        window.addEventListener('load', function() {
            setTimeout(trigger, C.delayMs + 500);
        });
    }

    waitForPreloaderThenShow();
})();
</script>
    <?php
}
