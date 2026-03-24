/* global TB_AJAX, TB_INSTANCES, TB_WIDGET_SETTINGS, jQuery */
(function ($) {
  'use strict';

  /* ══ ZONAS POR GÉNERO ═══════════════════════════════════════════════ */

  // MUJER — de frente
  var ZONES_FEMALE = {
    cabeza:         { x:32,  y: 2,   w:36,  h:10,  label:'Cabeza / Cráneo' },
    cuello:         { x:40,  y:11.5, w:20,  h: 5,  label:'Cuello' },
    hombro:         { x:14,  y:15,   w:20,  h: 7,  label:'Hombros' },
    espalda_alta:   { x:27,  y:18,   w:46,  h:11,  label:'Pecho / Torso Alto' },
    pecho:          { x:27,  y:18,   w:46,  h:11,  label:'Pecho' },
    costillas:      { x:17,  y:27,   w:16,  h:10,  label:'Costillas / Lateral' },
    abdomen:        { x:30,  y:29,   w:40,  h:11,  label:'Abdomen' },
    espalda_baja:   { x:30,  y:29,   w:40,  h:11,  label:'Abdomen / Espalda Baja' },
    columna:        { x:44,  y:18,   w:12,  h:22,  label:'Columna / Centro' },
    gluteo:         { x:28,  y:40,   w:44,  h:11,  label:'Cadera / Glúteo' },
    brazo_superior: { x:12,  y:19,   w:13,  h:16,  label:'Brazo Superior' },
    codo:           { x:11,  y:34,   w:13,  h: 7,  label:'Codo' },
    antebrazo:      { x:10,  y:40,   w:13,  h:13,  label:'Antebrazo' },
    muneca:         { x:10,  y:52,   w:12,  h: 5,  label:'Muñeca' },
    mano:           { x: 9,  y:56,   w:14,  h:10,  label:'Mano / Dedos' },
    muslo:          { x:28,  y:50,   w:44,  h:17,  label:'Muslo' },
    rodilla:        { x:29,  y:66,   w:42,  h: 7,  label:'Rodilla' },
    pantorrilla:    { x:28,  y:73,   w:44,  h:15,  label:'Pantorrilla' },
    tobillo:        { x:30,  y:87,   w:40,  h: 5,  label:'Tobillo' },
    pie:            { x:28,  y:92,   w:44,  h: 7,  label:'Pie / Dedos' },
  };

  // HOMBRE — de espaldas
  var ZONES_MALE = {
    cabeza:         { x:34,  y: 2,   w:30,  h:10,  label:'Cabeza / Cráneo' },
    cuello:         { x:39,  y:11.5, w:22,  h: 6,  label:'Cuello' },
    hombro:         { x:14,  y:16,   w:24,  h: 8,  label:'Hombros' },
    espalda_alta:   { x:25,  y:17,   w:50,  h:16,  label:'Espalda Alta / Trapecios' },
    columna:        { x:44,  y:17,   w:12,  h:26,  label:'Columna' },
    espalda_baja:   { x:27,  y:33,   w:46,  h:11,  label:'Espalda Baja / Lumbar' },
    costillas:      { x:14,  y:22,   w:16,  h:14,  label:'Costillas / Lateral' },
    pecho:          { x:25,  y:17,   w:50,  h:16,  label:'Espalda Alta (vista trasera)' },
    abdomen:        { x:27,  y:33,   w:46,  h:11,  label:'Espalda Baja (vista trasera)' },
    gluteo:         { x:28,  y:44,   w:44,  h:10,  label:'Glúteo' },
    brazo_superior: { x:14,  y:19,   w:14,  h:18,  label:'Brazo Superior' },
    codo:           { x:12,  y:36,   w:13,  h: 7,  label:'Codo' },
    antebrazo:      { x:11,  y:42,   w:13,  h:13,  label:'Antebrazo' },
    muneca:         { x:10,  y:54,   w:12,  h: 5,  label:'Muñeca' },
    mano:           { x: 9,  y:58,   w:14,  h: 9,  label:'Mano / Dedos' },
    muslo:          { x:26,  y:53,   w:48,  h:15,  label:'Muslo' },
    rodilla:        { x:28,  y:67,   w:44,  h: 8,  label:'Rodilla' },
    pantorrilla:    { x:27,  y:75,   w:46,  h:13,  label:'Pantorrilla' },
    tobillo:        { x:28,  y:87,   w:44,  h: 5,  label:'Tobillo' },
    pie:            { x:26,  y:92,   w:48,  h: 7,  label:'Pie / Dedos' },
  };

  function getZones(gender) {
    return gender === 'female' ? ZONES_FEMALE : ZONES_MALE;
  }

  /* ══ COLORES Y DESCRIPCIONES DE DOLOR ══════════════════════════════ */
  var PAIN_COLORS = {
    1:'#4ade80', 2:'#4ade80', 3:'#4ade80',
    4:'#facc15', 5:'#facc15', 6:'#facc15',
    7:'#fb923c', 8:'#fb923c',
    9:'#ef4444', 10:'#ef4444',
  };
  var PAIN_DESC = {
    1:'Casi nada', 2:'Muy leve', 3:'Leve',
    4:'Moderado',  5:'Notable',  6:'Considerable',
    7:'Intenso',   8:'Muy intenso', 9:'Severo', 10:'Extremo',
  };

  /* ══ CONFIGURACIÓN ELEMENTOR ════════════════════════════════════════ */
  var CFG = window.TB_WIDGET_SETTINGS || { zone_style:'rect', zone_border_w:2, zone_opacity:.28 };

  /* ══ DETECTOR DE MÓVIL ══════════════════════════════════════════════ */
  function isMobile() { return window.innerWidth <= 600; }

  /* ══ INICIALIZAR INSTANCIA ══════════════════════════════════════════ */
  function init(uid) {
    var $wrap = $('#' + uid);
    if (!$wrap.length) return;

    var $form     = $('#' + uid + '-form');
    var $img      = $('#' + uid + '-img');
    var $imgc     = $('#' + uid + '-imgc');
    var $idle     = $('#' + uid + '-idle');
    var $viewer   = $('#' + uid + '-viewer');
    var $ok       = $('#' + uid + '-ok');
    var $err      = $('#' + uid + '-err');
    var $callout  = $('#' + uid + '-callout');
    var $cname    = $('#' + uid + '-cname');
    var $clevel   = $('#' + uid + '-clevel');
    // Barra de dolor — zona activa
    var $paz      = $('#' + uid + '-paz');
    var $pazBar   = $('#' + uid + '-paz-bar');
    var $pazZone  = $('#' + uid + '-paz-zone');
    var $pazDesc  = $('#' + uid + '-paz-desc');
    var $pazNum   = $('#' + uid + '-paz-num');
    var $pazFill  = $('#' + uid + '-paz-fill');
    var canvasEl  = document.getElementById(uid + '-canvas');
    var ctx       = canvasEl ? canvasEl.getContext('2d') : null;

    var imgLoaded = false;
    var curGender = 'male';
    var curZone   = '';

    /* ── GÉNERO ──────────────────────────────────────────────────── */
    $wrap.on('click', '.tbw-gender-pill', function () {
      $wrap.find('.tbw-gender-pill').removeClass('active');
      $(this).addClass('active');
      curGender = $(this).data('gender');
      $wrap.find('.tbw-gender-val').val(curGender);
      loadImage(curGender);
    });

    function loadImage(gender) {
      var src = gender === 'female' ? TB_AJAX.img_female : TB_AJAX.img_male;
      if (!src) return;
      $idle.hide();
      $viewer.show();
      imgLoaded = false;
      var imgEl = $img[0];
      $img.off('load error')
        .on('load', function () {
          imgLoaded = true;
          syncCanvas();
          if (curZone) {
            drawZone(curZone);
            var p = parseInt($wrap.find('.tbw-zone-sel option:selected').data('pain')) || 5;
            showPainZone(curZone, p);
            placeCallout(curZone, p);
          }
        })
        .on('error', function () { imgLoaded = false; })
        .attr('src', src);
      if (imgEl.complete && imgEl.naturalWidth > 0) {
        imgLoaded = true;
        syncCanvas();
        if (curZone) {
          drawZone(curZone);
          var p2 = parseInt($wrap.find('.tbw-zone-sel option:selected').data('pain')) || 5;
          showPainZone(curZone, p2);
          placeCallout(curZone, p2);
        }
      }
    }

    /* ── ZONA SELECT ─────────────────────────────────────────────── */
    $wrap.on('change', '.tbw-zone-sel', function () {
      curZone = $(this).val();
      if (!curZone) {
        clearCanvas();
        $paz.removeClass('visible');
        $callout.hide().removeClass('mobile-visible');
        return;
      }
      var pain = parseInt($(this).find(':selected').data('pain')) || 5;
      loadImage(curGender);
      if (imgLoaded) {
        drawZone(curZone);
        placeCallout(curZone, pain);
      }
      showPainZone(curZone, pain);
    });

    /* ── CANVAS ──────────────────────────────────────────────────── */
    function syncCanvas() {
      var imgEl = $img[0];
      if (!canvasEl || !imgEl) return;
      var iw = imgEl.offsetWidth  || imgEl.naturalWidth;
      var ih = imgEl.offsetHeight || imgEl.naturalHeight;
      canvasEl.width        = iw;
      canvasEl.height       = ih;
      canvasEl.style.width  = iw + 'px';
      canvasEl.style.height = ih + 'px';
      canvasEl.style.left   = imgEl.offsetLeft + 'px';
      canvasEl.style.top    = imgEl.offsetTop  + 'px';
    }

    $(window).on('resize.tbw' + uid, function () {
      if (imgLoaded) {
        syncCanvas();
        if (curZone) {
          drawZone(curZone);
          var p = parseInt($wrap.find('.tbw-zone-sel option:selected').data('pain')) || 5;
          placeCallout(curZone, p);
        }
      }
    });

    function drawZone(zone) {
      var zoneMap = getZones(curGender);
      var z = zoneMap[zone];
      if (!z || !ctx || !canvasEl) return;
      var W = canvasEl.width, H = canvasEl.height;
      ctx.clearRect(0, 0, W, H);

      var pain  = parseInt($wrap.find('.tbw-zone-sel option:selected').data('pain')) || 5;
      var color = PAIN_COLORS[pain] || '#fb923c';
      var bw    = CFG.zone_border_w || 2;
      var op    = CFG.zone_opacity  || 0.28;
      var style = CFG.zone_style    || 'rect';

      var px = (z.x / 100) * W, py = (z.y / 100) * H;
      var pw = (z.w / 100) * W, ph = (z.h / 100) * H;

      if (style === 'ellipse') {
        var cx = px + pw/2, cy = py + ph/2;
        ctx.save(); ctx.globalAlpha = op; ctx.fillStyle = color;
        ctx.beginPath(); ctx.ellipse(cx, cy, pw/2, ph/2, 0, 0, Math.PI*2); ctx.fill(); ctx.restore();
        ctx.save(); ctx.globalAlpha = .85; ctx.strokeStyle = color; ctx.lineWidth = bw;
        ctx.beginPath(); ctx.ellipse(cx, cy, pw/2, ph/2, 0, 0, Math.PI*2); ctx.stroke(); ctx.restore();
      } else {
        ctx.save(); ctx.globalAlpha = op; ctx.fillStyle = color;
        ctx.fillRect(px, py, pw, ph); ctx.restore();
        ctx.save(); ctx.globalAlpha = .9; ctx.strokeStyle = color; ctx.lineWidth = bw;
        ctx.strokeRect(px + bw/2, py + bw/2, pw - bw, ph - bw); ctx.restore();
      }
    }

    /* ── CALLOUT LATERAL (solo desktop >600px) ───────────────────────
       En móvil el CSS lo oculta con !important, no hace falta lógica JS.
    ─────────────────────────────────────────────────────────────────── */
    function placeCallout(zone, pain) {
      var zoneMap = getZones(curGender);
      var z       = zoneMap[zone];
      if (!z) return;

      var color = PAIN_COLORS[pain] || '#fb923c';
      $cname.text(z.label);
      $clevel.text(pain + '/10 — ' + (PAIN_DESC[pain] || '')).css('color', color);
      $callout.css('border-color', color);

      // En móvil el CSS lo oculta — no posicionamos
      if (isMobile()) return;

      // Desktop: position:absolute dentro del contenedor de imagen
      var imgEl = $img[0];
      if (!imgEl || !canvasEl) return;

      var W  = canvasEl.width;
      var H  = canvasEl.height;
      var px = (z.x / 100) * W;
      var py = (z.y / 100) * H;
      var pw = (z.w / 100) * W;
      var ph = (z.h / 100) * H;

      var calloutW = 130;
      var calloutH = 48;

      var spaceRight = W - (px + pw);
      var spaceLeft  = px;
      var cl, ct;

      if (spaceRight >= calloutW + 8) {
        cl = px + pw + 8;
      } else if (spaceLeft >= calloutW + 8) {
        cl = px - calloutW - 8;
      } else {
        cl = Math.max(0, Math.min(px, W - calloutW));
        ct = (py > calloutH + 8) ? (py - calloutH - 6) : (py + ph + 6);
        ct = Math.max(0, Math.min(ct, H - calloutH));
        $callout.css({ display:'flex', position:'absolute', left: cl + 'px', top: ct + 'px' });
        return;
      }

      ct = py + ph / 2 - calloutH / 2;
      ct = Math.max(2, Math.min(ct, H - calloutH - 2));

      $callout.css({
        display:  'flex',
        position: 'absolute',
        left:     cl + 'px',
        top:      ct + 'px',
      });
    }

    function clearCanvas() {
      if (ctx && canvasEl) ctx.clearRect(0, 0, canvasEl.width, canvasEl.height);
      $callout.css('display','none').removeClass('mobile-visible');
    }

    /* ── ZONA ACTIVA EN LA BARRA DE DOLOR ───────────────────────────
       Muestra un bloque grande y prominente debajo de la barra
       con el nombre de la zona, nivel de dolor (número grande)
       y una mini barra de progreso de color.
    ─────────────────────────────────────────────────────────────────── */
    function showPainZone(zone, pain) {
      var zoneMap = getZones(curGender);
      var z       = zoneMap[zone];
      var color   = PAIN_COLORS[pain] || '#fb923c';
      var label   = z ? z.label : zone;

      $pazZone.text(label);
      $pazDesc.text(PAIN_DESC[pain] || '');
      $pazNum.text(pain).css('color', color);
      $pazBar.css('background', color);
      $pazFill.css({ width: (pain / 10 * 100) + '%', background: color });
      $paz.css('border-color', color).addClass('visible');
    }

    /* ── SUBMIT ──────────────────────────────────────────────────── */
    $form.on('submit', function (e) {
      e.preventDefault();
      var $btn = $('#' + uid + '-btn');
      var $submitBtns = $form.find('button[type="submit"]');
      $submitBtns.prop('disabled', true);
      $btn.find('.tbw-btn-text').hide();
      $btn.find('.tbw-btn-loading').show();
      $ok.hide(); $err.hide();

      $.post(TB_AJAX.url, {
        action:    'tb_save_booking',
        nonce:     TB_AJAX.nonce,
        name:      $form.find('[name="name"]').val(),
        email:     $form.find('[name="email"]').val(),
        phone:     $form.find('[name="phone"]').val(),
        gender:    $form.find('.tbw-gender-val').val(),
        zone:      $form.find('[name="zone"]').val(),
        date:      $form.find('[name="date"]').val(),
        time:      $form.find('[name="time"]').val(),
        notes:     $form.find('[name="notes"]').val(),
        branch_id: $form.find('[name="branch_id"]').val(),
      })
      .done(function (res) {
        if (res.success) {
          // Guardar datos del form antes de resetear
          var formData = {
            name:  $form.find('[name="name"]').val(),
            email: $form.find('[name="email"]').val(),
            phone: $form.find('[name="phone"]').val(),
          };

          $form[0].reset();
          $wrap.find('.tbw-gender-pill').removeClass('active');
          $wrap.find('.tbw-gender-pill[data-gender="male"]').addClass('active');
          $wrap.find('.tbw-gender-val').val('male');
          curGender = 'male'; curZone = '';
          clearCanvas();
          $paz.removeClass('visible');
          $idle.show(); $viewer.hide();

          // Disparar evento global para plugins externos (ej: tb-woo-cart)
          $(document).trigger('tb:booking:success', [res.data, formData]);

          // Solo mostrar mensaje de éxito si el plugin externo no muestra el modal
          if (!res.data.show_products) {
            $('#' + uid + '-ok-text').text(res.data.msg || '¡Cita agendada!');
            $ok.fadeIn();
            setTimeout(function () { $ok.fadeOut(); }, 7000);
          }
        } else {
          $('#' + uid + '-err-text').text(res.data.msg || 'Error.');
          $err.show();
        }
      })
      .fail(function () {
        $('#' + uid + '-err-text').text('Error de conexión.');
        $err.show();
      })
      .always(function () {
        $submitBtns.prop('disabled', false);
        $btn.find('.tbw-btn-text').show();
        $btn.find('.tbw-btn-loading').hide();
      });
    });
  }

  /* ══ ARRANQUE ════════════════════════════════════════════════════ */
  $(function () {
    (window.TB_INSTANCES || []).forEach(init);
  });

  $(document).on('elementor/frontend/init', function () {
    if (window.elementorFrontend) {
      elementorFrontend.hooks.addAction('frontend/element_ready/tattoo_booking.default', function ($el) {
        var uid = $el.find('[data-uid]').first().attr('data-uid');
        if (uid) init(uid);
      });
    }
  });

})(jQuery);
