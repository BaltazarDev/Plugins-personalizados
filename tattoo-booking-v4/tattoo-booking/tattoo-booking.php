<?php
/**
 * Plugin Name: Tattoo Booking & CRM
 * Description: Sistema de reservaciones con widget de Elementor, mapa corporal de dolor, sucursales y CRM.
 * Version: 4.0.0
 * Author: Tu Estudio
 * Text Domain: tattoo-booking
 */

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'TB_VER',  '4.0.0' );
define( 'TB_PATH', plugin_dir_path( __FILE__ ) );
define( 'TB_URL',  plugin_dir_url( __FILE__ ) );

// ═══════════════════════════════════════════════════════
// ACTIVACIÓN — tablas sin FOREIGN KEY (compatibilidad dbDelta)
// ═══════════════════════════════════════════════════════
register_activation_hook( __FILE__, 'tb_activate' );
function tb_activate() {
    global $wpdb;
    $c = $wpdb->get_charset_collate();
    require_once ABSPATH . 'wp-admin/includes/upgrade.php';

    dbDelta( "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}tb_branches (
        id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
        name VARCHAR(150) NOT NULL,
        address VARCHAR(255) DEFAULT '',
        phone VARCHAR(50) DEFAULT '',
        email VARCHAR(150) DEFAULT '',
        city VARCHAR(100) DEFAULT '',
        active TINYINT(1) NOT NULL DEFAULT 1,
        created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id)
    ) $c;" );

    dbDelta( "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}tb_clients (
        id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
        name VARCHAR(150) NOT NULL,
        email VARCHAR(150) DEFAULT '',
        phone VARCHAR(50) DEFAULT '',
        gender VARCHAR(10) NOT NULL DEFAULT 'male',
        notes TEXT,
        created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id)
    ) $c;" );

    dbDelta( "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}tb_appointments (
        id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
        client_id BIGINT UNSIGNED NOT NULL DEFAULT 0,
        branch_id BIGINT UNSIGNED NOT NULL DEFAULT 0,
        appt_date DATE NOT NULL,
        appt_time TIME NOT NULL,
        zone VARCHAR(100) DEFAULT '',
        pain_level TINYINT NOT NULL DEFAULT 1,
        status VARCHAR(20) NOT NULL DEFAULT 'pending',
        notes TEXT,
        created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        KEY idx_branch (branch_id),
        KEY idx_client (client_id),
        KEY idx_date (appt_date)
    ) $c;" );

    dbDelta( "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}tb_branch_users (
        id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
        wp_user_id BIGINT UNSIGNED NOT NULL DEFAULT 0,
        branch_id BIGINT UNSIGNED NOT NULL DEFAULT 0,
        PRIMARY KEY (id),
        UNIQUE KEY uniq_ub (wp_user_id, branch_id)
    ) $c;" );
}

// ═══════════════════════════════════════════════════════
// DATOS
// ═══════════════════════════════════════════════════════
function tb_pain_map() {
    return [
        'cabeza'=>7,'cuello'=>6,'hombro'=>4,'pecho'=>6,'costillas'=>9,
        'abdomen'=>7,'brazo_superior'=>3,'codo'=>7,'antebrazo'=>4,
        'muneca'=>6,'mano'=>8,'espalda_alta'=>4,'espalda_baja'=>6,
        'columna'=>8,'gluteo'=>5,'muslo'=>4,'rodilla'=>7,
        'pantorrilla'=>5,'tobillo'=>7,'pie'=>8,
    ];
}
function tb_zones_list() {
    return [
        'Cabeza & Cara' => ['cabeza'=>'Cabeza / Cráneo','cuello'=>'Cuello'],
        'Torso'         => ['pecho'=>'Pecho','costillas'=>'Costillas / Lateral','abdomen'=>'Abdomen',
                            'espalda_alta'=>'Espalda Alta','espalda_baja'=>'Espalda Baja','columna'=>'Columna','gluteo'=>'Glúteo'],
        'Brazos'        => ['hombro'=>'Hombro','brazo_superior'=>'Brazo Superior','codo'=>'Codo',
                            'antebrazo'=>'Antebrazo','muneca'=>'Muñeca','mano'=>'Mano / Dedos'],
        'Piernas'       => ['muslo'=>'Muslo','rodilla'=>'Rodilla','pantorrilla'=>'Pantorrilla','tobillo'=>'Tobillo','pie'=>'Pie / Dedos'],
    ];
}

// ═══════════════════════════════════════════════════════
// HELPERS SUCURSALES
// ═══════════════════════════════════════════════════════
function tb_is_admin() { return current_user_can('manage_options'); }
function tb_get_active_branches() {
    global $wpdb;
    return $wpdb->get_results("SELECT * FROM {$wpdb->prefix}tb_branches WHERE active=1 ORDER BY name");
}
function tb_get_user_branch_ids() {
    global $wpdb;
    if ( tb_is_admin() ) return array_map('intval', $wpdb->get_col("SELECT id FROM {$wpdb->prefix}tb_branches WHERE active=1"));
    return array_map('intval', $wpdb->get_col($wpdb->prepare(
        "SELECT branch_id FROM {$wpdb->prefix}tb_branch_users WHERE wp_user_id=%d", get_current_user_id()
    )));
}
function tb_can_access_branch($bid) { return tb_is_admin() || in_array(intval($bid), tb_get_user_branch_ids(), true); }
function tb_get_user_branches() {
    global $wpdb;
    $ids = tb_get_user_branch_ids();
    if (empty($ids)) return [];
    return $wpdb->get_results("SELECT * FROM {$wpdb->prefix}tb_branches WHERE id IN(".implode(',',$ids).") AND active=1 ORDER BY name");
}

// ═══════════════════════════════════════════════════════
// SHORTCODE (legacy / sin Elementor)
// ═══════════════════════════════════════════════════════
add_shortcode('tattoo_booking', 'tb_booking_shortcode');
function tb_booking_shortcode($atts=[]) {
    $atts          = shortcode_atts(['branch'=>''], $atts);
    $forced_branch = intval($atts['branch']);
    $settings      = []; // sin settings de Elementor
    ob_start();
    tb_enqueue_frontend_assets($settings);
    include TB_PATH . 'templates/booking-form.php';
    return ob_get_clean();
}

// ═══════════════════════════════════════════════════════
// ASSETS FRONTEND
// ═══════════════════════════════════════════════════════
function tb_enqueue_frontend_assets($settings=[]) {
    wp_enqueue_style('tb-style', TB_URL.'assets/style.css', [], TB_VER);
    wp_enqueue_script('tb-script', TB_URL.'assets/script.js', ['jquery'], TB_VER, true);
    
    // Usar URLs resueltas si vienen de Elementor, sino las de la configuración global
    $img_male   = ! empty($settings['resolved_img_male'])   ? $settings['resolved_img_male']   : ( ! empty($settings['img_male']['url']) ? $settings['img_male']['url'] : get_option('tb_img_male','') );
    $img_female = ! empty($settings['resolved_img_female']) ? $settings['resolved_img_female'] : ( ! empty($settings['img_female']['url']) ? $settings['img_female']['url'] : get_option('tb_img_female','') );

    if (!$img_male)   $img_male   = TB_URL.'images/placeholder-male.svg';
    if (!$img_female) $img_female = TB_URL.'images/placeholder-female.svg';
    
    wp_localize_script('tb-script','TB_AJAX',[
        'url'        => admin_url('admin-ajax.php'),
        'nonce'      => wp_create_nonce('tb_nonce'),
        'img_male'   => esc_url($img_male),
        'img_female' => esc_url($img_female),
    ]);
}

// ═══════════════════════════════════════════════════════
// AJAX FRONTEND
// ═══════════════════════════════════════════════════════
add_action('wp_ajax_tb_save_booking',        'tb_save_booking');
add_action('wp_ajax_nopriv_tb_save_booking', 'tb_save_booking');
function tb_save_booking() {
    check_ajax_referer('tb_nonce','nonce');
    global $wpdb;
    $name      = sanitize_text_field($_POST['name']??'');
    $email     = sanitize_email($_POST['email']??'');
    $phone     = sanitize_text_field($_POST['phone']??'');
    $gender    = in_array($_POST['gender']??'',['male','female'])?$_POST['gender']:'male';
    $zone      = sanitize_text_field($_POST['zone']??'');
    $date      = sanitize_text_field($_POST['date']??'');
    $time      = sanitize_text_field($_POST['time']??'');
    $notes     = sanitize_textarea_field($_POST['notes']??'');
    $branch_id = intval($_POST['branch_id']??0);
    if (!$name||!$date||!$time||!$zone||!$branch_id) wp_send_json_error(['msg'=>'Completa todos los campos requeridos.']);
    $branch = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}tb_branches WHERE id=%d AND active=1",$branch_id));
    if (!$branch) wp_send_json_error(['msg'=>'Sucursal no válida.']);
    $client_id = 0;
    if ($email) { $ex=$wpdb->get_var($wpdb->prepare("SELECT id FROM {$wpdb->prefix}tb_clients WHERE email=%s LIMIT 1",$email)); if($ex) $client_id=intval($ex); }
    if (!$client_id) { $wpdb->insert("{$wpdb->prefix}tb_clients",compact('name','email','phone','gender','notes')); $client_id=intval($wpdb->insert_id); }
    $pain_level = tb_pain_map()[$zone]??5;
    $wpdb->insert("{$wpdb->prefix}tb_appointments",['client_id'=>$client_id,'branch_id'=>$branch_id,'appt_date'=>$date,'appt_time'=>$time,'zone'=>$zone,'pain_level'=>$pain_level,'notes'=>$notes,'status'=>'pending']);
    wp_send_json_success(['msg'=>'¡Cita agendada en '.esc_html($branch->name).'! Te contactaremos pronto.']);
}

// ═══════════════════════════════════════════════════════
// ELEMENTOR WIDGET
// ═══════════════════════════════════════════════════════
add_action('elementor/widgets/register', 'tb_register_elementor_widget');
function tb_register_elementor_widget($widgets_manager) {
    require_once TB_PATH.'elementor/widget.php';
    $widgets_manager->register(new TB_Elementor_Widget());
}

add_action('elementor/elements/categories_registered', 'tb_add_elementor_category');
function tb_add_elementor_category($elements_manager) {
    $elements_manager->add_category('tattoo-booking', ['title'=>'Tattoo Booking','icon'=>'fa fa-calendar']);
}

// ═══════════════════════════════════════════════════════
// MENÚ ADMIN
// ═══════════════════════════════════════════════════════
add_action('admin_menu','tb_admin_menu');
function tb_admin_menu() {
    $cap = 'edit_posts';
    add_menu_page('Tattoo Booking','Tattoo Booking',$cap,'tattoo-booking','tb_admin_dashboard','dashicons-calendar-alt',30);
    add_submenu_page('tattoo-booking','Dashboard',   'Dashboard',   $cap,'tattoo-booking',     'tb_admin_dashboard');
    add_submenu_page('tattoo-booking','Calendario',  'Calendario',  $cap,'tb-calendar',        'tb_admin_calendar');
    add_submenu_page('tattoo-booking','Clientes CRM','Clientes CRM',$cap,'tb-clients',         'tb_admin_clients');
    add_submenu_page('tattoo-booking','Citas',       'Citas',       $cap,'tb-appointments',    'tb_admin_appointments');
    add_submenu_page('tattoo-booking','Nueva Cita',  'Nueva Cita',  $cap,'tb-new-appointment', 'tb_admin_new_appointment');
    if (tb_is_admin()) {
        add_submenu_page('tattoo-booking','Sucursales','🏪 Sucursales','manage_options','tb-branches','tb_admin_branches');
    }
}


// ═══════════════════════════════════════════════════════
// ADMIN: DASHBOARD
// ═══════════════════════════════════════════════════════
function tb_admin_dashboard() {
    global $wpdb;
    $bids = tb_get_user_branch_ids();
    $fb   = intval($_GET['branch']??0);
    $brs  = tb_get_user_branches();
    if (empty($bids)&&!tb_is_admin()) { tb_admin_styles(); echo '<div class="tb-admin-wrap"><div class="tb-notice-warn">⚠️ Sin sucursales asignadas.</div></div>'; return; }
    $in  = $bids?implode(',',$bids):'0';
    $wb  = $fb&&tb_can_access_branch($fb)?$wpdb->prepare("AND a.branch_id=%d",$fb):($bids?"AND a.branch_id IN($in)":'');
    $tc  = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}tb_clients");
    $ta  = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}tb_appointments a WHERE 1=1 $wb");
    $pe  = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}tb_appointments a WHERE status='pending' $wb");
    $td  = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM {$wpdb->prefix}tb_appointments a WHERE appt_date=%s $wb",date('Y-m-d')));
    tb_admin_styles(); ?>
    <div class="tb-admin-wrap">
        <div class="tb-admin-topbar">
            <h1 class="tb-admin-title">Tattoo Booking Studio</h1>
            <?php if(count($brs)>1||tb_is_admin()): ?>
            <form method="get"><input type="hidden" name="page" value="tattoo-booking">
                <select name="branch" class="tb-select-sm" onchange="this.form.submit()">
                    <option value="">Todas las sucursales</option>
                    <?php foreach($brs as $b): ?><option value="<?=$b->id?>" <?=$fb==$b->id?'selected':''?>><?=esc_html($b->name)?></option><?php endforeach; ?>
                </select>
            </form>
            <?php endif; ?>
        </div>
        <div class="tb-stats-row">
            <div class="tb-stat"><span class="tb-stat-n" style="color:#3b82f6"><?=$tc?></span><span class="tb-stat-l">Clientes</span></div>
            <div class="tb-stat"><span class="tb-stat-n" style="color:#10b981"><?=$ta?></span><span class="tb-stat-l">Citas</span></div>
            <div class="tb-stat"><span class="tb-stat-n" style="color:#f59e0b"><?=$pe?></span><span class="tb-stat-l">Pendientes</span></div>
            <div class="tb-stat"><span class="tb-stat-n" style="color:#ef4444"><?=$td?></span><span class="tb-stat-l">Hoy</span></div>
        </div>
        <?php if(tb_is_admin()&&count($brs)): ?>
        <h3 class="tb-section-h">Por Sucursal</h3>
        <div class="tb-branches-row">
            <?php foreach($brs as $b):
                $ba=$wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM {$wpdb->prefix}tb_appointments WHERE branch_id=%d",$b->id));
                $bp=$wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM {$wpdb->prefix}tb_appointments WHERE branch_id=%d AND status='pending'",$b->id));
                $bt=$wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM {$wpdb->prefix}tb_appointments WHERE branch_id=%d AND appt_date=%s",$b->id,date('Y-m-d')));
            ?>
            <div class="tb-branch-stat-card">
                <div class="tb-bsc-name"><?=esc_html($b->name)?><?php if($b->city): ?><span><?=esc_html($b->city)?></span><?php endif; ?></div>
                <div class="tb-bsc-nums"><div><strong><?=$ba?></strong><small>Total</small></div><div><strong style="color:#f59e0b"><?=$bp?></strong><small>Pend.</small></div><div><strong style="color:#10b981"><?=$bt?></strong><small>Hoy</small></div></div>
                <a href="?page=tb-calendar&branch=<?=$b->id?>">Ver calendario →</a>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
        <p style="color:#9ca3af;font-size:.78rem;margin-top:24px">Shortcode: <code>[tattoo_booking]</code> · Con sucursal: <code>[tattoo_booking branch="ID"]</code></p>
    </div>
    <?php
}

// ═══════════════════════════════════════════════════════
// ADMIN: SUCURSALES
// ═══════════════════════════════════════════════════════
function tb_admin_branches() {
    if (!tb_is_admin()) wp_die('No autorizado.');
    global $wpdb; $msg='';
    if ($_SERVER['REQUEST_METHOD']==='POST'&&isset($_POST['tb_branch_nonce'])&&wp_verify_nonce($_POST['tb_branch_nonce'],'tb_branch_action')) {
        $data=['name'=>sanitize_text_field($_POST['bname']??''),'address'=>sanitize_text_field($_POST['baddress']??''),'phone'=>sanitize_text_field($_POST['bphone']??''),'email'=>sanitize_email($_POST['bemail']??''),'city'=>sanitize_text_field($_POST['bcity']??''),'active'=>isset($_POST['bactive'])?1:0];
        $eid=intval($_POST['edit_id']??0);
        if ($eid) { $wpdb->update("{$wpdb->prefix}tb_branches",$data,['id'=>$eid]); $msg='<div class="tb-admin-notice tb-n-ok">✅ Actualizada.</div>'; }
        elseif($data['name']) { $wpdb->insert("{$wpdb->prefix}tb_branches",$data); $msg='<div class="tb-admin-notice tb-n-ok">✅ Sucursal creada. ID: '.$wpdb->insert_id.'</div>'; }
    }
    if (isset($_POST['tb_assign_nonce'])&&wp_verify_nonce($_POST['tb_assign_nonce'],'tb_assign')) {
        $uid=intval($_POST['assign_user_id']??0); $bid=intval($_POST['assign_branch_id']??0);
        if ($uid&&$bid) { $wpdb->replace("{$wpdb->prefix}tb_branch_users",['wp_user_id'=>$uid,'branch_id'=>$bid]); $msg='<div class="tb-admin-notice tb-n-ok">✅ Usuario asignado.</div>'; }
    }
    if (isset($_GET['unassign_user'],$_GET['unassign_branch'])) $wpdb->delete("{$wpdb->prefix}tb_branch_users",['wp_user_id'=>intval($_GET['unassign_user']),'branch_id'=>intval($_GET['unassign_branch'])]);
    if (isset($_GET['delete_branch'])) $wpdb->delete("{$wpdb->prefix}tb_branches",['id'=>intval($_GET['delete_branch'])]);
    if (isset($_GET['toggle_branch'])) { $bid=intval($_GET['toggle_branch']); $cur=intval($wpdb->get_var($wpdb->prepare("SELECT active FROM {$wpdb->prefix}tb_branches WHERE id=%d",$bid))); $wpdb->update("{$wpdb->prefix}tb_branches",['active'=>$cur?0:1],['id'=>$bid]); }
    $eb = isset($_GET['edit_branch'])?$wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}tb_branches WHERE id=%d",intval($_GET['edit_branch']))):null;
    $branches=$wpdb->get_results("SELECT * FROM {$wpdb->prefix}tb_branches ORDER BY name");
    $users=get_users(['fields'=>['ID','display_name','user_email']]);
    $assigns=$wpdb->get_results("SELECT bu.*,b.name as bname,u.display_name,u.user_email FROM {$wpdb->prefix}tb_branch_users bu JOIN {$wpdb->prefix}tb_branches b ON b.id=bu.branch_id JOIN {$wpdb->prefix}users u ON u.ID=bu.wp_user_id ORDER BY b.name,u.display_name");
    tb_admin_styles(); echo $msg; ?>
    <div class="tb-admin-wrap">
        <h1 class="tb-admin-title">🏪 Sucursales</h1>
        <div class="tb-two-col">
            <div>
                <div class="tb-card">
                    <h3><?=$eb?'✏️ Editar':'➕ Nueva'?> Sucursal</h3>
                    <form method="post"><?php wp_nonce_field('tb_branch_action','tb_branch_nonce'); ?><?php if($eb): ?><input type="hidden" name="edit_id" value="<?=$eb->id?>"><?php endif; ?>
                        <div class="tb-fr"><label>Nombre *</label><input type="text" name="bname" class="tb-input" required value="<?=esc_attr($eb->name??'')?>"></div>
                        <div class="tb-fr"><label>Ciudad</label><input type="text" name="bcity" class="tb-input" value="<?=esc_attr($eb->city??'')?>"></div>
                        <div class="tb-fr"><label>Dirección</label><input type="text" name="baddress" class="tb-input" value="<?=esc_attr($eb->address??'')?>"></div>
                        <div class="tb-fr"><label>Teléfono</label><input type="text" name="bphone" class="tb-input" value="<?=esc_attr($eb->phone??'')?>"></div>
                        <div class="tb-fr"><label>Email</label><input type="email" name="bemail" class="tb-input" value="<?=esc_attr($eb->email??'')?>"></div>
                        <div class="tb-fr"><label style="display:flex;gap:6px;align-items:center"><input type="checkbox" name="bactive" value="1" <?=(!$eb||$eb->active)?'checked':''?>> Activa</label></div>
                        <button type="submit" class="tb-btn tb-btn-primary"><?=$eb?'Actualizar':'Crear'?></button>
                        <?php if($eb): ?><a href="?page=tb-branches" class="tb-btn">Cancelar</a><?php endif; ?>
                    </form>
                </div>
                <div class="tb-card">
                    <h3>👤 Asignar Usuario</h3>
                    <form method="post"><?php wp_nonce_field('tb_assign','tb_assign_nonce'); ?>
                        <div class="tb-fr"><label>Usuario</label><select name="assign_user_id" class="tb-select"><?php foreach($users as $u): ?><option value="<?=$u->ID?>"><?=esc_html($u->display_name)?></option><?php endforeach; ?></select></div>
                        <div class="tb-fr"><label>Sucursal</label><select name="assign_branch_id" class="tb-select"><?php foreach($branches as $b): ?><option value="<?=$b->id?>"><?=esc_html($b->name)?></option><?php endforeach; ?></select></div>
                        <button type="submit" class="tb-btn">Asignar</button>
                    </form>
                    <?php if($assigns): ?><table class="tb-table" style="margin-top:12px"><thead><tr><th>Usuario</th><th>Sucursal</th><th></th></tr></thead><tbody><?php foreach($assigns as $a): ?><tr><td><?=esc_html($a->display_name)?></td><td><?=esc_html($a->bname)?></td><td><a href="?page=tb-branches&unassign_user=<?=$a->wp_user_id?>&unassign_branch=<?=$a->branch_id?>" class="tb-btn-sm tb-btn-del" onclick="return confirm('¿Desasignar?')">✕</a></td></tr><?php endforeach; ?></tbody></table><?php endif; ?>
                </div>
            </div>
            <div class="tb-card">
                <h3>Lista (<?=count($branches)?>)</h3>
                <?php foreach($branches as $b): $cnt=$wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM {$wpdb->prefix}tb_appointments WHERE branch_id=%d",$b->id)); ?>
                <div class="tb-branch-row <?=$b->active?'':'tb-inactive'?>">
                    <div><strong><?=esc_html($b->name)?></strong><?php if($b->city): ?> <span style="color:#6b7280;font-size:.8rem">· <?=esc_html($b->city)?></span><?php endif; ?><br><small style="color:#9ca3af"><?=$cnt?> citas · ID: <?=$b->id?></small></div>
                    <div style="display:flex;gap:4px;align-items:center">
                        <span class="tb-badge <?=$b->active?'tb-badge-ok':'tb-badge-off'?>"><?=$b->active?'Activa':'Inactiva'?></span>
                        <a href="?page=tb-branches&edit_branch=<?=$b->id?>" class="tb-btn-sm">✏️</a>
                        <a href="?page=tb-branches&toggle_branch=<?=$b->id?>" class="tb-btn-sm"><?=$b->active?'⏸':'▶'?></a>
                        <a href="?page=tb-branches&delete_branch=<?=$b->id?>" class="tb-btn-sm tb-btn-del" onclick="return confirm('¿Eliminar?')">🗑</a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php
}

// ═══════════════════════════════════════════════════════
// ADMIN: CALENDARIO
// ═══════════════════════════════════════════════════════
function tb_admin_calendar() {
    global $wpdb;
    $month=intval($_GET['month']??date('n')); $year=intval($_GET['year']??date('Y'));
    if($month<1){$month=12;$year--;} if($month>12){$month=1;$year++;}
    $bids=tb_get_user_branch_ids(); $fb=intval($_GET['branch']??0); $brs=tb_get_user_branches();
    if($fb&&!tb_can_access_branch($fb))$fb=0;
    $wb=$fb?$wpdb->prepare("AND a.branch_id=%d",$fb):(!tb_is_admin()&&$bids?"AND a.branch_id IN(".implode(',',$bids).")":'');
    $appts=$wpdb->get_results($wpdb->prepare("SELECT a.*,c.name as cn,b.name as bn FROM {$wpdb->prefix}tb_appointments a JOIN {$wpdb->prefix}tb_clients c ON c.id=a.client_id JOIN {$wpdb->prefix}tb_branches b ON b.id=a.branch_id WHERE MONTH(a.appt_date)=%d AND YEAR(a.appt_date)=%d $wb ORDER BY a.appt_time",$month,$year));
    $bd=[]; foreach($appts as $a) $bd[(int)date('j',strtotime($a->appt_date))][]=$a;
    $days=cal_days_in_month(CAL_GREGORIAN,$month,$year); $fdow=(int)date('w',mktime(0,0,0,$month,1,$year));
    $mn=['','Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
    $sc=['pending'=>'#f59e0b','confirmed'=>'#10b981','cancelled'=>'#ef4444','done'=>'#8b5cf6'];
    tb_admin_styles(); ?>
    <div class="tb-admin-wrap">
        <div class="tb-admin-topbar"><h1 class="tb-admin-title">📅 Calendario</h1>
            <?php if(count($brs)>1||tb_is_admin()): ?><form method="get"><input type="hidden" name="page" value="tb-calendar"><input type="hidden" name="month" value="<?=$month?>"><input type="hidden" name="year" value="<?=$year?>"><select name="branch" class="tb-select-sm" onchange="this.form.submit()"><option value="">Todas</option><?php foreach($brs as $b): ?><option value="<?=$b->id?>" <?=$fb==$b->id?'selected':''?>><?=esc_html($b->name)?></option><?php endforeach; ?></select></form><?php endif; ?>
        </div>
        <div class="tb-cal-nav"><a href="?page=tb-calendar&month=<?=$month-1?>&year=<?=$year?>&branch=<?=$fb?>" class="tb-btn-nav">← Ant.</a><span class="tb-cal-month-title"><?=$mn[$month].' '.$year?></span><a href="?page=tb-calendar&month=<?=$month+1?>&year=<?=$year?>&branch=<?=$fb?>" class="tb-btn-nav">Sig. →</a></div>
        <div class="tb-calendar">
            <?php foreach(['Dom','Lun','Mar','Mié','Jue','Vie','Sáb'] as $d): ?><div class="tb-cal-hd"><?=$d?></div><?php endforeach; ?>
            <?php for($i=0;$i<$fdow;$i++): ?><div class="tb-cal-cell tb-cal-blank"></div><?php endfor; ?>
            <?php for($d=1;$d<=$days;$d++): $ot=($d==date('j')&&$month==date('n')&&$year==date('Y')); ?>
                <div class="tb-cal-cell <?=$ot?'tb-cal-today':''?>"><span class="tb-cal-dn"><?=$d?></span>
                    <?php if(!empty($bd[$d])): foreach($bd[$d] as $a): ?>
                        <div class="tb-cal-ev" style="border-left:3px solid <?=$sc[$a->status]??'#888'?>">
                            <?=date('H:i',strtotime($a->appt_time))?> <?=esc_html($a->cn)?>
                            <?php if(!$fb): ?><span style="color:#9ca3af;font-size:.62rem;display:block">🏪 <?=esc_html($a->bn)?></span><?php endif; ?>
                        </div>
                    <?php endforeach; endif; ?>
                </div>
            <?php endfor; ?>
        </div>
    </div>
    <?php
}

// ═══════════════════════════════════════════════════════
// ADMIN: CLIENTES CRM
// ═══════════════════════════════════════════════════════
function tb_admin_clients() {
    global $wpdb;
    if(isset($_GET['export'])&&$_GET['export']==='csv'){tb_export_clients_csv();exit;}
    if(isset($_GET['delete_client'])&&tb_is_admin()) $wpdb->delete("{$wpdb->prefix}tb_clients",['id'=>intval($_GET['delete_client'])]);
    $bids=tb_get_user_branch_ids(); $fb=intval($_GET['branch']??0); $brs=tb_get_user_branches(); $s=sanitize_text_field($_GET['s']??'');
    if($fb&&!tb_can_access_branch($fb))$fb=0;
    $sc=$s?$wpdb->prepare("AND (c.name LIKE %s OR c.email LIKE %s OR c.phone LIKE %s)","%$s%","%$s%","%$s%"):'';
    $bc=$fb?$wpdb->prepare("AND a.branch_id=%d",$fb):(!tb_is_admin()&&$bids?"AND a.branch_id IN(".implode(',',$bids).")":'');
    if($bc) $clients=$wpdb->get_results("SELECT DISTINCT c.*,(SELECT COUNT(*) FROM {$wpdb->prefix}tb_appointments WHERE client_id=c.id) as ta,(SELECT b2.name FROM {$wpdb->prefix}tb_appointments a2 JOIN {$wpdb->prefix}tb_branches b2 ON b2.id=a2.branch_id WHERE a2.client_id=c.id ORDER BY a2.created_at DESC LIMIT 1) as lb FROM {$wpdb->prefix}tb_clients c JOIN {$wpdb->prefix}tb_appointments a ON a.client_id=c.id WHERE 1=1 $bc $sc ORDER BY c.created_at DESC");
    else $clients=$wpdb->get_results("SELECT c.*,(SELECT COUNT(*) FROM {$wpdb->prefix}tb_appointments WHERE client_id=c.id) as ta,(SELECT b2.name FROM {$wpdb->prefix}tb_appointments a2 JOIN {$wpdb->prefix}tb_branches b2 ON b2.id=a2.branch_id WHERE a2.client_id=c.id ORDER BY a2.created_at DESC LIMIT 1) as lb FROM {$wpdb->prefix}tb_clients c WHERE 1=1 $sc ORDER BY c.created_at DESC");
    tb_admin_styles(); ?>
    <div class="tb-admin-wrap">
        <div class="tb-admin-topbar"><h1 class="tb-admin-title">👥 Clientes CRM</h1>
            <?php if(count($brs)>1||tb_is_admin()): ?><form method="get"><input type="hidden" name="page" value="tb-clients"><?php if($s): ?><input type="hidden" name="s" value="<?=esc_attr($s)?>"><?php endif; ?><select name="branch" class="tb-select-sm" onchange="this.form.submit()"><option value="">Todas las sucursales</option><?php foreach($brs as $b): ?><option value="<?=$b->id?>" <?=$fb==$b->id?'selected':''?>><?=esc_html($b->name)?></option><?php endforeach; ?></select></form><?php endif; ?>
        </div>
        <div class="tb-toolbar"><form method="get" style="display:flex;gap:8px"><input type="hidden" name="page" value="tb-clients"><?php if($fb): ?><input type="hidden" name="branch" value="<?=$fb?>"><?php endif; ?><input type="text" name="s" value="<?=esc_attr($s)?>" placeholder="Buscar…" class="tb-input-sm"><button class="tb-btn">Buscar</button></form><a href="?page=tb-clients&export=csv<?=$fb?"&branch=$fb":''?><?=$s?"&s=".urlencode($s):''?>" class="tb-btn tb-btn-green">⬇ CSV</a><span style="color:#9ca3af;font-size:.8rem"><?=count($clients)?> clientes</span></div>
        <table class="tb-table"><thead><tr><th>Nombre</th><th>Email</th><th>Teléfono</th><th>Género</th><th>Citas</th><th>Última sucursal</th><th>Fecha</th><th></th></tr></thead><tbody>
        <?php if(empty($clients)): ?><tr><td colspan="8" style="text-align:center;padding:30px;color:#9ca3af">Sin clientes.</td></tr><?php endif; ?>
        <?php foreach($clients as $c): ?><tr><td><strong><?=esc_html($c->name)?></strong></td><td><?=esc_html($c->email)?></td><td><?=esc_html($c->phone)?></td><td><?=$c->gender==='female'?'♀':'♂'?></td><td><span class="tb-badge tb-badge-n"><?=$c->ta?></span></td><td><?=$c->lb?'<span class="tb-badge tb-badge-br">'.esc_html($c->lb).'</span>':'—'?></td><td><?=date('d/m/Y',strtotime($c->created_at))?></td><td><a href="?page=tb-new-appointment&client_id=<?=$c->id?>" class="tb-btn-sm">+Cita</a><?php if(tb_is_admin()): ?><a href="?page=tb-clients&delete_client=<?=$c->id?>" class="tb-btn-sm tb-btn-del" onclick="return confirm('¿Eliminar?')">✕</a><?php endif; ?></td></tr><?php endforeach; ?>
        </tbody></table>
    </div>
    <?php
}

// ═══════════════════════════════════════════════════════
// ADMIN: CITAS
// ═══════════════════════════════════════════════════════
function tb_admin_appointments() {
    global $wpdb;
    if(isset($_GET['export'])&&$_GET['export']==='csv'){tb_export_appointments_csv();exit;}
    $bids=tb_get_user_branch_ids(); $fb=intval($_GET['branch']??0); $fs=sanitize_text_field($_GET['status']??''); $brs=tb_get_user_branches();
    if($fb&&!tb_can_access_branch($fb))$fb=0;
    if(isset($_GET['set_status'],$_GET['appt_id'])){$aid=intval($_GET['appt_id']);$stat=sanitize_text_field($_GET['set_status']);$row=$wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}tb_appointments WHERE id=%d",$aid));if($row&&tb_can_access_branch($row->branch_id)&&in_array($stat,['pending','confirmed','cancelled','done'],true))$wpdb->update("{$wpdb->prefix}tb_appointments",['status'=>$stat],['id'=>$aid]);}
    $w='WHERE 1=1'; if($fb)$w.=$wpdb->prepare(" AND a.branch_id=%d",$fb); elseif(!tb_is_admin()&&$bids)$w.=" AND a.branch_id IN(".implode(',',$bids).")"; if($fs)$w.=$wpdb->prepare(" AND a.status=%s",$fs);
    $appts=$wpdb->get_results("SELECT a.*,c.name as cn,c.phone as cp,b.name as bn FROM {$wpdb->prefix}tb_appointments a JOIN {$wpdb->prefix}tb_clients c ON c.id=a.client_id JOIN {$wpdb->prefix}tb_branches b ON b.id=a.branch_id $w ORDER BY a.appt_date DESC,a.appt_time ASC");
    $sl=['pending'=>'Pendiente','confirmed'=>'Confirmada','cancelled'=>'Cancelada','done'=>'Completada'];
    $sc=['pending'=>'#f59e0b','confirmed'=>'#10b981','cancelled'=>'#ef4444','done'=>'#8b5cf6'];
    tb_admin_styles(); ?>
    <div class="tb-admin-wrap">
        <h1 class="tb-admin-title">📋 Citas</h1>
        <div class="tb-toolbar"><form method="get" style="display:flex;gap:8px;flex-wrap:wrap"><input type="hidden" name="page" value="tb-appointments"><?php if(count($brs)>1||tb_is_admin()): ?><select name="branch" class="tb-select-sm"><option value="">Todas</option><?php foreach($brs as $b): ?><option value="<?=$b->id?>" <?=$fb==$b->id?'selected':''?>><?=esc_html($b->name)?></option><?php endforeach; ?></select><?php endif; ?><select name="status" class="tb-select-sm"><option value="">Todos los estados</option><?php foreach($sl as $v=>$l): ?><option value="<?=$v?>" <?=$fs===$v?'selected':''?>><?=$l?></option><?php endforeach; ?></select><button class="tb-btn">Filtrar</button></form><a href="?page=tb-new-appointment" class="tb-btn">+ Nueva</a><a href="?page=tb-appointments&export=csv<?=$fb?"&branch=$fb":''?>" class="tb-btn tb-btn-green">⬇ CSV</a><span style="color:#9ca3af;font-size:.8rem"><?=count($appts)?> citas</span></div>
        <table class="tb-table"><thead><tr><th>Cliente</th><th>Sucursal</th><th>Fecha</th><th>Hora</th><th>Zona</th><th>Dolor</th><th>Status</th><th></th></tr></thead><tbody>
        <?php if(empty($appts)): ?><tr><td colspan="8" style="text-align:center;padding:30px;color:#9ca3af">Sin citas.</td></tr><?php endif; ?>
        <?php foreach($appts as $a): $p=$a->pain_level;$pc=$p<=3?'#10b981':($p<=6?'#f59e0b':($p<=8?'#f97316':'#ef4444')); ?>
            <tr><td><strong><?=esc_html($a->cn)?></strong><br><small style="color:#9ca3af"><?=esc_html($a->cp)?></small></td><td><span class="tb-badge tb-badge-br"><?=esc_html($a->bn)?></span></td><td><?=date('d/m/Y',strtotime($a->appt_date))?></td><td><?=date('H:i',strtotime($a->appt_time))?></td><td><?=esc_html($a->zone)?></td><td><span class="tb-badge" style="background:<?=$pc?>;color:#fff"><?=$p?>/10</span></td><td><span class="tb-badge" style="background:<?=$sc[$a->status]??'#888'?>;color:#fff"><?=$sl[$a->status]??$a->status?></span></td>
            <td style="white-space:nowrap"><?php foreach(['confirmed'=>'✓','cancelled'=>'✕','done'=>'★'] as $s=>$ic): if($a->status!==$s): ?><a href="?page=tb-appointments&appt_id=<?=$a->id?>&set_status=<?=$s?>&branch=<?=$fb?>&status=<?=$fs?>" class="tb-btn-sm" title="<?=$sl[$s]?>"><?=$ic?></a><?php endif; endforeach; ?></td></tr>
        <?php endforeach; ?></tbody></table>
    </div>
    <?php
}

// ═══════════════════════════════════════════════════════
// ADMIN: NUEVA CITA
// ═══════════════════════════════════════════════════════
function tb_admin_new_appointment() {
    global $wpdb; $msg='';
    $pre=isset($_GET['client_id'])?$wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}tb_clients WHERE id=%d",intval($_GET['client_id']))):null;
    if($_SERVER['REQUEST_METHOD']==='POST'&&isset($_POST['tb_new_appt_nonce'])&&wp_verify_nonce($_POST['tb_new_appt_nonce'],'tb_new_appt')){
        $cid=intval($_POST['client_id']??0); $bid=intval($_POST['branch_id']??0);
        if(!$bid||!tb_can_access_branch($bid)) { $msg='<div class="tb-admin-notice tb-n-err">Sucursal inválida.</div>'; }
        else {
            if(!$cid){$n=sanitize_text_field($_POST['name']??'');$e=sanitize_email($_POST['email']??'');$ph=sanitize_text_field($_POST['phone']??'');$g=in_array($_POST['gender']??'',['male','female'])?$_POST['gender']:'male';$wpdb->insert("{$wpdb->prefix}tb_clients",['name'=>$n,'email'=>$e,'phone'=>$ph,'gender'=>$g]);$cid=intval($wpdb->insert_id);}
            $z=sanitize_text_field($_POST['zone']??''); $pl=tb_pain_map()[$z]??5;
            $wpdb->insert("{$wpdb->prefix}tb_appointments",['client_id'=>$cid,'branch_id'=>$bid,'appt_date'=>sanitize_text_field($_POST['date']??''),'appt_time'=>sanitize_text_field($_POST['time']??''),'zone'=>$z,'pain_level'=>$pl,'notes'=>sanitize_textarea_field($_POST['notes']??''),'status'=>'confirmed']);
            $msg='<div class="tb-admin-notice tb-n-ok">✅ Cita creada.</div>';
        }
    }
    $clients=$wpdb->get_results("SELECT * FROM {$wpdb->prefix}tb_clients ORDER BY name"); $brs=tb_get_user_branches(); $zones=tb_zones_list();
    tb_admin_styles(); echo $msg; ?>
    <div class="tb-admin-wrap">
        <h1 class="tb-admin-title">➕ Nueva Cita</h1>
        <form method="post" class="tb-form-admin" style="max-width:520px"><?php wp_nonce_field('tb_new_appt','tb_new_appt_nonce'); ?>
            <div class="tb-card"><h3>📍 Sucursal</h3><div class="tb-fr"><label>Sucursal *</label><select name="branch_id" class="tb-select" required><option value="">— Seleccionar —</option><?php foreach($brs as $b): ?><option value="<?=$b->id?>"><?=esc_html($b->name)?><?=$b->city?" — ".esc_html($b->city):''?></option><?php endforeach; ?></select></div></div>
            <div class="tb-card"><h3>👤 Cliente</h3><div class="tb-fr"><label>Existente</label><select name="client_id" id="ac_sel" class="tb-select"><option value="">— Nuevo cliente —</option><?php foreach($clients as $c): ?><option value="<?=$c->id?>" <?=($pre&&$pre->id==$c->id)?'selected':''?>><?=esc_html($c->name)?> (<?=esc_html($c->email)?>)</option><?php endforeach; ?></select></div>
            <div id="nc_fields" style="<?=$pre?'display:none':''?>"><div class="tb-fr"><label>Nombre</label><input type="text" name="name" class="tb-input"></div><div class="tb-fr"><label>Email</label><input type="email" name="email" class="tb-input"></div><div class="tb-fr"><label>Teléfono</label><input type="text" name="phone" class="tb-input"></div><div class="tb-fr"><label>Género</label><select name="gender" class="tb-select"><option value="male">♂ Masculino</option><option value="female">♀ Femenino</option></select></div></div></div>
            <div class="tb-card"><h3>🗓 Cita</h3><div class="tb-fr"><label>Fecha</label><input type="date" name="date" class="tb-input" required min="<?=date('Y-m-d')?>"></div><div class="tb-fr"><label>Hora</label><input type="time" name="time" class="tb-input" required></div><div class="tb-fr"><label>Zona</label><select name="zone" class="tb-select" required><option value="">— Zona —</option><?php foreach($zones as $g=>$os): ?><optgroup label="<?=esc_attr($g)?>"><?php foreach($os as $v=>$l): ?><option value="<?=esc_attr($v)?>"><?=esc_html($l)?></option><?php endforeach; ?></optgroup><?php endforeach; ?></select></div><div class="tb-fr"><label>Notas</label><textarea name="notes" class="tb-input" rows="2"></textarea></div></div>
            <button type="submit" class="tb-btn tb-btn-primary">Crear Cita</button>
        </form>
    </div>
    <script>document.getElementById('ac_sel').addEventListener('change',function(){document.getElementById('nc_fields').style.display=this.value?'none':''});</script>
    <?php
}

// ═══════════════════════════════════════════════════════
// EXPORTAR CSV
// ═══════════════════════════════════════════════════════
function tb_export_clients_csv(){global $wpdb;$fb=isset($_GET['branch'])&&tb_can_access_branch(intval($_GET['branch']))?intval($_GET['branch']):0;$s=sanitize_text_field($_GET['s']??'');header('Content-Type: text/csv; charset=utf-8');header('Content-Disposition: attachment; filename=clientes-'.date('Y-m-d').'.csv');$out=fopen('php://output','w');fprintf($out,chr(0xEF).chr(0xBB).chr(0xBF));fputcsv($out,['ID','Nombre','Email','Teléfono','Género','Citas','Última Sucursal','Registrado']);$sc=$s?$wpdb->prepare("AND (c.name LIKE %s OR c.email LIKE %s)","%$s%","%$s%"):'';$rows=$fb?$wpdb->get_results("SELECT DISTINCT c.*,(SELECT COUNT(*) FROM {$wpdb->prefix}tb_appointments WHERE client_id=c.id) as t,(SELECT b2.name FROM {$wpdb->prefix}tb_appointments a2 JOIN {$wpdb->prefix}tb_branches b2 ON b2.id=a2.branch_id WHERE a2.client_id=c.id ORDER BY a2.created_at DESC LIMIT 1) as lb FROM {$wpdb->prefix}tb_clients c JOIN {$wpdb->prefix}tb_appointments a ON a.client_id=c.id WHERE a.branch_id=$fb $sc ORDER BY c.id"):$wpdb->get_results("SELECT c.*,(SELECT COUNT(*) FROM {$wpdb->prefix}tb_appointments WHERE client_id=c.id) as t,(SELECT b2.name FROM {$wpdb->prefix}tb_appointments a2 JOIN {$wpdb->prefix}tb_branches b2 ON b2.id=a2.branch_id WHERE a2.client_id=c.id ORDER BY a2.created_at DESC LIMIT 1) as lb FROM {$wpdb->prefix}tb_clients c WHERE 1=1 $sc ORDER BY c.id");foreach($rows as $r)fputcsv($out,[$r->id,$r->name,$r->email,$r->phone,$r->gender,$r->t,$r->lb,$r->created_at]);fclose($out);}
function tb_export_appointments_csv(){global $wpdb;$fb=isset($_GET['branch'])&&tb_can_access_branch(intval($_GET['branch']))?intval($_GET['branch']):0;$fs=sanitize_text_field($_GET['status']??'');$bids=tb_get_user_branch_ids();header('Content-Type: text/csv; charset=utf-8');header('Content-Disposition: attachment; filename=citas-'.date('Y-m-d').'.csv');$out=fopen('php://output','w');fprintf($out,chr(0xEF).chr(0xBB).chr(0xBF));fputcsv($out,['ID','Sucursal','Cliente','Email','Teléfono','Fecha','Hora','Zona','Nivel Dolor','Status','Notas']);$w='WHERE 1=1';if($fb)$w.=$wpdb->prepare(" AND a.branch_id=%d",$fb);elseif(!tb_is_admin()&&$bids)$w.=" AND a.branch_id IN(".implode(',',$bids).")";if($fs)$w.=$wpdb->prepare(" AND a.status=%s",$fs);$rows=$wpdb->get_results("SELECT a.id,b.name as branch,c.name,c.email,c.phone,a.appt_date,a.appt_time,a.zone,a.pain_level,a.status,a.notes FROM {$wpdb->prefix}tb_appointments a JOIN {$wpdb->prefix}tb_clients c ON c.id=a.client_id JOIN {$wpdb->prefix}tb_branches b ON b.id=a.branch_id $w ORDER BY a.appt_date DESC",ARRAY_A);foreach($rows as $r)fputcsv($out,$r);fclose($out);}

// ═══════════════════════════════════════════════════════
// ESTILOS ADMIN — colores oscuros con texto legible en tema default WP
// ═══════════════════════════════════════════════════════
function tb_admin_styles(){?>
<style>
.tb-admin-wrap{font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;max-width:1280px;padding:20px;color:#1f2937}
.tb-admin-topbar{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;margin-bottom:4px}
.tb-admin-title{font-size:1.5rem;font-weight:700;color:#111827;margin:0 0 20px;padding-bottom:12px;border-bottom:2px solid #e5e7eb}
.tb-section-h{font-size:.85rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#6b7280;margin:20px 0 10px}
.tb-stats-row{display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-bottom:24px}
@media(max-width:600px){.tb-stats-row{grid-template-columns:1fr 1fr}}
.tb-stat{background:#fff;border:1px solid #e5e7eb;border-radius:10px;padding:18px;text-align:center}
.tb-stat-n{display:block;font-size:2.2rem;font-weight:700;line-height:1}
.tb-stat-l{display:block;font-size:.75rem;color:#9ca3af;margin-top:4px;text-transform:uppercase;letter-spacing:.06em}
.tb-branches-row{display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:10px;margin-bottom:20px}
.tb-branch-stat-card{background:#fff;border:1px solid #e5e7eb;border-radius:9px;padding:14px}
.tb-bsc-name{font-weight:600;color:#111827;font-size:.9rem;margin-bottom:8px}.tb-bsc-name span{color:#9ca3af;font-weight:400;font-size:.78rem;display:block}
.tb-bsc-nums{display:flex;gap:0;border:1px solid #f3f4f6;border-radius:7px;overflow:hidden;margin-bottom:8px}
.tb-bsc-nums>div{flex:1;text-align:center;padding:6px 4px;border-right:1px solid #f3f4f6}.tb-bsc-nums>div:last-child{border-right:none}
.tb-bsc-nums strong{display:block;font-size:1.1rem;font-weight:700}.tb-bsc-nums small{font-size:.62rem;color:#9ca3af;text-transform:uppercase}
.tb-branch-stat-card a{font-size:.75rem;color:#3b82f6;text-decoration:none}.tb-branch-stat-card a:hover{color:#1d4ed8}
.tb-notice-info{background:#eff6ff;border:1px solid #bfdbfe;border-radius:9px;padding:14px 16px;margin-bottom:20px;color:#1e40af;font-size:.88rem;line-height:1.6}
.tb-notice-info strong{display:block;color:#1e3a8a;margin-bottom:4px}
.tb-notice-warn{background:#fffbeb;border:1px solid #fde68a;border-radius:8px;padding:10px 14px;color:#92400e;font-size:.88rem}
.tb-admin-notice{padding:10px 16px;border-radius:7px;margin-bottom:16px;font-size:.88rem}
.tb-n-ok{background:#ecfdf5;border:1px solid #a7f3d0;color:#065f46}
.tb-n-err{background:#fef2f2;border:1px solid #fecaca;color:#991b1b}
.tb-two-col{display:grid;grid-template-columns:1fr 1fr;gap:16px}
@media(max-width:900px){.tb-two-col{grid-template-columns:1fr}}
.tb-card{background:#fff;border:1px solid #e5e7eb;border-radius:10px;padding:18px;margin-bottom:16px}
.tb-card h3{font-size:.85rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#6b7280;margin:0 0 14px;padding-bottom:10px;border-bottom:1px solid #f3f4f6}
.tb-fr{margin-bottom:12px}
.tb-fr>label{display:block;font-size:.75rem;font-weight:600;color:#374151;margin-bottom:4px;text-transform:uppercase;letter-spacing:.05em}
.tb-input,.tb-select{width:100%;background:#f9fafb;border:1px solid #d1d5db;color:#111827;padding:8px 11px;border-radius:7px;font-size:.88rem;font-family:inherit}
.tb-input:focus,.tb-select:focus{outline:none;border-color:#3b82f6;box-shadow:0 0 0 3px rgba(59,130,246,.1)}
.tb-select-sm{background:#f9fafb;border:1px solid #d1d5db;color:#374151;padding:6px 10px;border-radius:6px;font-size:.82rem;min-width:160px}
.tb-input-sm{background:#f9fafb;border:1px solid #d1d5db;color:#111827;padding:7px 11px;border-radius:6px;font-size:.85rem;min-width:180px}
.tb-toolbar{display:flex;gap:8px;margin-bottom:16px;align-items:center;flex-wrap:wrap}
.tb-btn{display:inline-block;background:#f9fafb;color:#374151;border:1px solid #d1d5db;padding:7px 14px;border-radius:7px;text-decoration:none;font-size:.83rem;cursor:pointer;font-family:inherit;transition:all .15s}
.tb-btn:hover{background:#f3f4f6;color:#111827}
.tb-btn-primary{background:#111827;border-color:#111827;color:#fff}.tb-btn-primary:hover{background:#1f2937}
.tb-btn-green{background:#ecfdf5;border-color:#a7f3d0;color:#065f46}.tb-btn-green:hover{background:#d1fae5}
.tb-btn-sm{display:inline-block;background:#f9fafb;border:1px solid #e5e7eb;color:#6b7280;padding:2px 8px;border-radius:5px;font-size:.73rem;text-decoration:none;margin-right:2px}
.tb-btn-sm:hover{background:#f3f4f6;color:#111827}
.tb-btn-del{border-color:#fecaca;color:#dc2626}.tb-btn-del:hover{background:#fef2f2}
.tb-table{width:100%;border-collapse:collapse;font-size:.85rem;background:#fff;border-radius:10px;overflow:hidden;border:1px solid #e5e7eb}
.tb-table thead th{background:#f9fafb;padding:10px 13px;text-align:left;color:#6b7280;font-weight:600;font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e5e7eb}
.tb-table tbody td{padding:10px 13px;border-bottom:1px solid #f3f4f6;color:#374151}
.tb-table tbody tr:last-child td{border-bottom:none}
.tb-table tbody tr:hover td{background:#f9fafb}
.tb-badge{display:inline-block;padding:2px 9px;border-radius:999px;font-size:.72rem;font-weight:600}
.tb-badge-ok{background:#ecfdf5;color:#065f46;border:1px solid #a7f3d0}
.tb-badge-off{background:#f9fafb;color:#9ca3af;border:1px solid #e5e7eb}
.tb-badge-br{background:#eff6ff;color:#1d4ed8;border:1px solid #bfdbfe}
.tb-badge-n{background:#f3f4f6;color:#374151;border:1px solid #e5e7eb}
.tb-branch-row{display:flex;justify-content:space-between;align-items:center;padding:10px 12px;border-bottom:1px solid #f3f4f6;gap:8px;flex-wrap:wrap}
.tb-branch-row:last-child{border-bottom:none}
.tb-inactive{opacity:.5}
.tb-cal-nav{display:flex;align-items:center;gap:12px;margin-bottom:16px}
.tb-cal-month-title{font-size:1.1rem;font-weight:700;color:#111827}
.tb-btn-nav{background:#f9fafb;border:1px solid #d1d5db;color:#374151;padding:5px 12px;border-radius:6px;text-decoration:none;font-size:.82rem}
.tb-btn-nav:hover{background:#f3f4f6}
.tb-calendar{display:grid;grid-template-columns:repeat(7,1fr);gap:1px;background:#e5e7eb;border:1px solid #e5e7eb;border-radius:10px;overflow:hidden}
.tb-cal-hd{background:#f9fafb;text-align:center;padding:8px;font-size:.72rem;font-weight:600;color:#9ca3af;text-transform:uppercase;letter-spacing:.06em}
.tb-cal-cell{background:#fff;min-height:80px;padding:5px;vertical-align:top}
.tb-cal-blank{background:#f9fafb}
.tb-cal-today{background:#eff6ff}
.tb-cal-dn{display:block;font-weight:700;color:#9ca3af;font-size:.75rem;margin-bottom:3px}
.tb-cal-today .tb-cal-dn{color:#2563eb}
.tb-cal-ev{font-size:.68rem;color:#374151;background:#f3f4f6;border-radius:3px;padding:2px 5px;margin-top:2px;line-height:1.5}
.tb-img-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px}
@media(max-width:700px){.tb-img-grid{grid-template-columns:1fr}}
.tb-img-panel{background:#fff;border:1px solid #e5e7eb;border-radius:10px;padding:18px}
.tb-img-panel-head{display:flex;align-items:center;gap:10px;margin-bottom:14px}
.tb-gender-dot{width:34px;height:34px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:1.1rem;font-weight:700;background:#1f2937;color:#fff}
.tb-gender-dot.female{background:#9d174d;color:#fff}
.tb-img-panel-head h3{margin:0;font-size:.88rem;font-weight:700;color:#111827;text-transform:uppercase;letter-spacing:.06em}
.tb-img-panel-head p{margin:2px 0 0;font-size:.73rem;color:#9ca3af}
.tb-img-preview-box{background:#f9fafb;border:2px dashed #e5e7eb;border-radius:8px;min-height:200px;display:flex;align-items:center;justify-content:center;overflow:hidden;margin-bottom:12px}
.tb-img-preview-box img{max-width:100%;max-height:240px;display:block}
.tb-img-empty{color:#d1d5db;font-size:.82rem;text-align:center;padding:20px}
.tb-btn-upload{background:#eff6ff;border-color:#bfdbfe;color:#1d4ed8}.tb-btn-upload:hover{background:#dbeafe}
</style>
<?php }
