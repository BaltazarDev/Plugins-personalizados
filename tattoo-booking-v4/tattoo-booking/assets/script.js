/* global TB_AJAX, TB_INSTANCES, TB_WIDGET_SETTINGS, jQuery */
(function ($) {
  'use strict';

  /* ── ZONAS: coordenadas en % sobre la imagen ──────────────────────────
     [x%, y%, w%, h%]  = rectángulo (como la captura: cajas con líneas)
     Ajusta estos valores según tus fotos reales.
  ─────────────────────────────────────────────────────────────────────── */
  var ZONE_RECTS = {
    cabeza: { x: 38, y: 1, w: 24, h: 13, label: 'Cabeza / Cráneo' },
    cuello: { x: 42, y: 13, w: 16, h: 7, label: 'Cuello' },
    hombro: { x: 16, y: 17, w: 20, h: 10, label: 'Hombros' },
    espalda_alta: { x: 28, y: 18, w: 44, h: 14, label: 'Espalda Alta' },
    espalda_baja: { x: 30, y: 32, w: 40, h: 12, label: 'Espalda Baja' },
    columna: { x: 45, y: 18, w: 10, h: 26, label: 'Columna' },
    costillas: { x: 14, y: 22, w: 16, h: 16, label: 'Costillas / Lateral' },
    pecho: { x: 28, y: 18, w: 44, h: 14, label: 'Pecho' },
    abdomen: { x: 32, y: 32, w: 36, h: 12, label: 'Abdomen' },
    gluteo: { x: 28, y: 44, w: 44, h: 14, label: 'Glúteos' },
    brazo_superior: { x: 9, y: 20, w: 14, h: 18, label: 'Brazo Superior' },
    codo: { x: 9, y: 37, w: 14, h: 7, label: 'Codo' },
    antebrazo: { x: 10, y: 44, w: 12, h: 16, label: 'Antebrazo' },
    muneca: { x: 10, y: 59, w: 11, h: 5, label: 'Muñeca' },
    mano: { x: 10, y: 64, w: 11, h: 10, label: 'Mano / Dedos' },
    muslo: { x: 29, y: 57, w: 42, h: 16, label: 'Muslo' },
    rodilla: { x: 32, y: 72, w: 36, h: 8, label: 'Rodilla' },
    pantorrilla: { x: 30, y: 79, w: 40, h: 13, label: 'Pantorrilla' },
    tobillo: { x: 33, y: 91, w: 34, h: 5, label: 'Tobillo' },
    pie: { x: 30, y: 95, w: 40, h: 5, label: 'Pie / Dedos' },
  };

  var PAIN_COLORS = {
    1: '#4ade80', 2: '#4ade80', 3: '#4ade80',
    4: '#facc15', 5: '#facc15', 6: '#facc15',
    7: '#fb923c', 8: '#fb923c',
    9: '#ef4444', 10: '#ef4444'
  };
  var PAIN_LABELS_ES = {
    1: 'Casi nada', 2: 'Muy leve', 3: 'Leve', 4: 'Moderado', 5: 'Notable',
    6: 'Considerable', 7: 'Intenso', 8: 'Muy intenso', 9: 'Severo', 10: 'Extremo'
  };

  var SETTINGS = window.TB_WIDGET_SETTINGS || { zone_style: 'rect', zone_border_w: 2, zone_opacity: .30 };

  /* ══ INICIALIZAR CADA INSTANCIA ══════════════════════════════════════ */
  function initInstance(uid) {
    var $wrap = $('#' + uid);
    var $form = $('#' + uid + '-form');
    var $img = $('#' + uid + '-img');
    var canvas = document.getElementById(uid + '-canvas');
    var $idle = $('#' + uid + '-idle');
    var $viewer = $('#' + uid + '-viewer');
    var $ok = $('#' + uid + '-ok');
    var $err = $('#' + uid + '-err');
    var $cursorR = $('#' + uid + '-cursor-row');
    var $cursor = $('#' + uid + '-cursor');
    var $callout = $('#' + uid + '-callout');
    var $pPanel = $('#' + uid + '-pain-panel');
    var $pZone = $('#' + uid + '-pzone');
    var $pNum = $('#' + uid + '-pnum');
    var $pFill = $('#' + uid + '-pfill');

    var ctx = canvas ? canvas.getContext('2d') : null;
    var imgLoaded = false;
    var curGender = 'male';
    var curZone = '';

    if (!$wrap.length) return;

    /* ── GÉNERO ──────────────────────────────────────────────────── */
    $wrap.on('click', '.tbw-gender-pill', function () {
      $wrap.find('.tbw-gender-pill').removeClass('active');
      $(this).addClass('active');
      curGender = $(this).data('gender');
      $wrap.find('.tbw-gender-val').val(curGender);
      loadBodyImage(curGender);
    });

    function loadBodyImage(gender) {
      var src = gender === 'female' ? ($wrap.attr('data-img-female') || TB_AJAX.img_female) : ($wrap.attr('data-img-male') || TB_AJAX.img_male);
      if (!src) return;
      $idle.hide();
      $viewer.show();
      imgLoaded = false;
      $img.off('load error').attr('src', src)
        .on('load', function () { imgLoaded = true; syncCanvas(); if (curZone) drawZone(curZone); })
        .on('error', function () { imgLoaded = false; });
      if ($img[0].complete && $img[0].naturalWidth > 0) {
        imgLoaded = true; syncCanvas(); if (curZone) drawZone(curZone);
      }
    }

    /* ── ZONA ────────────────────────────────────────────────────── */
    $wrap.on('change', '.tbw-zone-select', function () {
      curZone = $(this).val();
      if (!curZone) { clearCanvas(); $pPanel.hide(); $cursorR.hide(); $callout.hide(); return; }
      var pain = parseInt($(this).find(':selected').data('pain')) || 5;
      loadBodyImage(curGender);
      if (imgLoaded) drawZone(curZone);
      updatePainPanel(curZone, pain);
      updatePainCursor(pain);
    });

    /* ── CANVAS ──────────────────────────────────────────────────── */
    function syncCanvas() {
      if (!canvas || !$img[0]) return;

      // Asegurarnos que la imagen tenga dimensiones reales
      var imgW = $img[0].offsetWidth;
      var imgH = $img[0].offsetHeight;

      if (imgW === 0 || imgH === 0) return;

      canvas.width = imgW;
      canvas.height = imgH;
      canvas.style.width = imgW + 'px';
      canvas.style.height = imgH + 'px';
      canvas.style.left = $img[0].offsetLeft + 'px';
      canvas.style.top = $img[0].offsetTop + 'px';

      if (curZone) drawZone(curZone);
    }

    // Usar ResizeObserver para detectar cambios de tamaño del contenedor (p. ej. en editor Elementor)
    if (window.ResizeObserver && $img[0]) {
      var ro = new ResizeObserver(function () {
        if (imgLoaded) syncCanvas();
      });
      ro.observe($img[0].parentElement);
    }

    $(window).on('resize', function () { if (imgLoaded) syncCanvas(); });

    function drawZone(zone) {
      var z = ZONE_RECTS[zone];
      if (!z || !ctx || !canvas) return;
      var w = canvas.width, h = canvas.height;
      ctx.clearRect(0, 0, w, h);
      var pain = parseInt($wrap.find('.tbw-zone-select option:selected').data('pain')) || 5;
      var color = PAIN_COLORS[pain] || '#fb923c';
      var bw = SETTINGS.zone_border_w || 2;
      var op = SETTINGS.zone_opacity || 0.30;
      var style = SETTINGS.zone_style || 'rect';

      var px = (z.x / 100) * w, py = (z.y / 100) * h;
      var pw = (z.w / 100) * w, ph = (z.h / 100) * h;

      if (style === 'ellipse') {
        var cx = px + pw / 2, cy = py + ph / 2;
        // Relleno
        ctx.save(); ctx.globalAlpha = op; ctx.fillStyle = color;
        ctx.beginPath(); ctx.ellipse(cx, cy, pw / 2, ph / 2, 0, 0, Math.PI * 2); ctx.fill(); ctx.restore();
        // Borde
        ctx.save(); ctx.globalAlpha = .85; ctx.strokeStyle = color; ctx.lineWidth = bw;
        ctx.beginPath(); ctx.ellipse(cx, cy, pw / 2, ph / 2, 0, 0, Math.PI * 2); ctx.stroke(); ctx.restore();
      } else {
        // RECTÁNGULO — estilo de la captura
        // Relleno suave
        ctx.save(); ctx.globalAlpha = op * 0.7; ctx.fillStyle = color;
        ctx.fillRect(px, py, pw, ph); ctx.restore();
        // Borde con color según dolor
        ctx.save(); ctx.globalAlpha = .9; ctx.strokeStyle = color; ctx.lineWidth = bw;
        ctx.strokeRect(px + bw / 2, py + bw / 2, pw - bw, ph - bw); ctx.restore();
      }

      // Callout flotante (línea + caja) — estilo de la captura
      showCallout(z, px, py, pw, ph, color, pain);
    }

    function showCallout(z, px, py, pw, ph, color, pain) {
      if (!$callout.length) return;
      var imgEl = $img[0];
      if (!imgEl) return;

      // Posición relativa al contenedor, sumando el offset de la imagen/canvas
      var imgL = imgEl.offsetLeft;
      var imgT = imgEl.offsetTop;

      var cLeft = imgL + px + pw + 8;
      var cTop = imgT + py + ph / 2 - 16;

      var fromRight = (cLeft + 120) > (imgL + canvas.width);
      if (fromRight) cLeft = imgL + px - 128;

      $callout.css({ display: 'block', left: cLeft + 'px', top: cTop + 'px', color: color });
      $callout.find('#' + uid + '-callout-name').text(z.label);
      $callout.find('#' + uid + '-callout-pain').text(pain + '/10 — ' + (PAIN_LABELS_ES[pain] || ''));
      $callout.find('#' + uid + '-callout-pain').css('color', color);
    }

    function clearCanvas() {
      if (!ctx || !canvas) return;
      ctx.clearRect(0, 0, canvas.width, canvas.height);
      $callout.hide();
    }

    /* ── PANEL DOLOR ─────────────────────────────────────────────── */
    function updatePainPanel(zone, pain) {
      var z = ZONE_RECTS[zone];
      var color = PAIN_COLORS[pain] || '#fb923c';
      $pZone.text(z ? z.label : zone);
      $pNum.text(pain + '/10').css('color', color);
      $pFill.css({ width: (pain / 10 * 100) + '%', background: color });
      $pPanel.show();
    }

    /* ── CURSOR EN BARRA DE DOLOR ────────────────────────────────── */
    function updatePainCursor(pain) {
      if (!$cursorR.length) return;
      var pct = ((pain - 1) / 9) * 100;
      $cursorR.show();
      $cursor.css('left', pct + '%');
    }

    /* ── SUBMIT ──────────────────────────────────────────────────── */
    $form.on('submit', function (e) {
      e.preventDefault();
      var $btn = $('#' + uid + '-btn');
      $btn.prop('disabled', true);
      $btn.find('.tbw-btn-text,.tbw-btn-icon').hide();
      $btn.find('.tbw-btn-loading').show();
      $ok.hide(); $err.hide();

      var data = {
        action: 'tb_save_booking',
        nonce: TB_AJAX.nonce,
        name: $form.find('[name="name"]').val(),
        email: $form.find('[name="email"]').val(),
        phone: $form.find('[name="phone"]').val(),
        gender: $form.find('.tbw-gender-val').val(),
        zone: $form.find('[name="zone"]').val(),
        date: $form.find('[name="date"]').val(),
        time: $form.find('[name="time"]').val(),
        notes: $form.find('[name="notes"]').val(),
        branch_id: $form.find('[name="branch_id"]').val(),
      };

      $.post(TB_AJAX.url, data)
        .done(function (res) {
          if (res.success) {
            $form[0].reset();
            $wrap.find('.tbw-gender-pill').removeClass('active');
            $wrap.find('.tbw-gender-pill[data-gender="male"]').addClass('active');
            $wrap.find('.tbw-gender-val').val('male');
            curGender = 'male'; curZone = '';
            clearCanvas(); $pPanel.hide(); $cursorR.hide();
            $idle.show(); $viewer.hide();
            $('#' + uid + '-ok-text').text(res.data.msg || '¡Cita agendada!');
            $ok.fadeIn();
            setTimeout(function () { $ok.fadeOut(); }, 7000);
          } else {
            $('#' + uid + '-err-text').text(res.data.msg || 'Error. Inténtalo de nuevo.');
            $err.show();
          }
        })
        .fail(function () {
          $('#' + uid + '-err-text').text('Error de conexión.');
          $err.show();
        })
        .always(function () {
          $btn.prop('disabled', false);
          $btn.find('.tbw-btn-text,.tbw-btn-icon').show();
          $btn.find('.tbw-btn-loading').hide();
        });
    });
  }

  /* ══ INICIALIZAR CUANDO EL DOM ESTÁ LISTO ═══════════════════════════ */
  $(document).ready(function () {
    var instances = window.TB_INSTANCES || [];
    instances.forEach(function (uid) { initInstance(uid); });
  });

  // Soporte para Elementor frontend editor (re-renderizado)
  $(document).on('elementor/frontend/init', function () {
    if (window.elementorFrontend) {
      elementorFrontend.hooks.addAction('frontend/element_ready/tattoo_booking.default', function ($el) {
        var uid = $el.find('[id^="tb-"]').first().attr('id');
        if (uid) { uid = uid.replace(/-form$|-ok$|-err$/, ''); initInstance(uid); }
      });
    }
  });

})(jQuery);
