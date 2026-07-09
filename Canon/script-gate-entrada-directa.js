(function () {
  var MODAL_SELECTOR = '#ccr-gate-modal';
  var DEFAULT_FALLBACK_URL = '/centro-de-recursos/';

  function uniqueKeys(list) {
    return list.filter(Boolean).filter(function (v, i, arr) {
      return arr.indexOf(v) === i;
    });
  }

  function getAccessKeys() {
    var primary = window.ccrGateStorageKey || 'canon_centro_recursos_form_sent';
    var keys = [
      primary,
      'gate_posts_authorized',
      'validacion_usuario',
      'canon_gate_authorized',
      'formulario_enviado',
      'usuario_registrado'
    ];

    if (Array.isArray(window.ccrGateStorageKeys)) {
      keys = keys.concat(window.ccrGateStorageKeys);
    }

    return uniqueKeys(keys);
  }

  function readCookie(name) {
    try {
      var escaped = name.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
      var pattern = new RegExp('(?:^|; )' + escaped + '=([^;]*)');
      var match = document.cookie.match(pattern);
      return match ? decodeURIComponent(match[1]) : '';
    } catch (e) {
      return '';
    }
  }

  function isTruthy(value) {
    if (value == null) return false;
    return /^(1|true|yes|ok)$/i.test(String(value).trim());
  }

  function hasAccess() {
    if (typeof window.ccrHasGateAccess === 'function') {
      try {
        return !!window.ccrHasGateAccess();
      } catch (e) {
        // Fallback to local validation below
      }
    }

    var keys = getAccessKeys();

    return keys.some(function (key) {
      var lsValue = '';
      try {
        lsValue = localStorage.getItem(key) || '';
      } catch (e) {
        lsValue = '';
      }

      var ckValue = readCookie(key);
      return isTruthy(lsValue) || isTruthy(ckValue);
    });
  }

  function isDirectEntryGateEnabled() {
    if (window.ccrDirectEntryGateEnabled === false) {
      return false;
    }

    // Se aplica por defecto en posts single para cubrir trafico de Google/Facebook.
    return document.body.classList.contains('single-post');
  }

  function openGateModal() {
    var modal = document.querySelector(MODAL_SELECTOR);
    if (!modal) return false;

    window.ccep_dest_url = window.location.href.split('#')[0];

    modal.classList.add('is-open');
    modal.setAttribute('aria-hidden', 'false');
    document.body.classList.add('ccrb-gate-modal-open');
    document.body.classList.add('ccr-gate-modal-open');
    return true;
  }

  function closeGateModal() {
    var modal = document.querySelector(MODAL_SELECTOR);
    if (!modal) return;

    modal.classList.remove('is-open');
    modal.setAttribute('aria-hidden', 'true');
    document.body.classList.remove('ccrb-gate-modal-open');
    document.body.classList.remove('ccr-gate-modal-open');
  }

  function fallbackExit() {
    var target = window.ccrGateFallbackUrl || DEFAULT_FALLBACK_URL;
    if (target) {
      window.location.href = target;
    }
  }

  function enforceGate() {
    if (!isDirectEntryGateEnabled()) return;
    if (hasAccess()) return;

    if (!openGateModal()) return;

    var modal = document.querySelector(MODAL_SELECTOR);
    if (!modal) return;

    // Si intenta cerrar sin autorizacion, lo sacamos al listado para evitar bypass.
    var overlay = modal.querySelector('.ccrb-gate-modal__overlay, .ccr-gate-modal__overlay');
    var closeBtn = modal.querySelector('.ccrb-gate-modal__close, .ccr-gate-modal__close');

    function closeHandler(e) {
      if (hasAccess()) return;
      if (e) {
        e.preventDefault();
        e.stopPropagation();
      }
      fallbackExit();
    }

    if (overlay && !overlay.dataset.directGateBound) {
      overlay.addEventListener('click', closeHandler);
      overlay.dataset.directGateBound = '1';
    }

    if (closeBtn && !closeBtn.dataset.directGateBound) {
      closeBtn.addEventListener('click', closeHandler);
      closeBtn.dataset.directGateBound = '1';
    }

    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && !hasAccess()) {
        closeHandler(e);
      }
    }, { once: true });
  }

  // Cuando el formulario se envia correctamente, deja navegar normal.
  window.addEventListener('ccr:form-sent', function () {
    closeGateModal();
  });

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', enforceGate);
  } else {
    enforceGate();
  }
})();
