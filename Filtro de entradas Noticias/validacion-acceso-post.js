<script>
  (function () {
  var BUTTON_SELECTOR = '.validacion_usuario';
  var MODAL_SELECTOR = '#ccr-gate-modal';
  var LS_KEY = 'usuario_registrado';
  var COOKIE_KEY = 'usuario_registrado';
  var pendingUrl = null;
  var DEBUG = true;
  var BOOT_LOG = true;

  function isTruthyValue(value) {
    if (value == null) return false;
    return /^(1|true|yes|ok)$/i.test(String(value).trim());
  }

  function log() {
    if (!DEBUG || !window.console) return;
    var args = Array.prototype.slice.call(arguments);
    args.unshift('[GatePosts]');
    console.log.apply(console, args);
  }

  if (BOOT_LOG && window.console) {
    console.info('[GatePosts] validacion-acceso-post.js cargado');
  }

  function getCookie(name) {
    try {
      var escaped = name.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
      var pattern = new RegExp('(?:^|; )' + escaped + '=([^;]*)');
      var match = document.cookie.match(pattern);
      return match ? decodeURIComponent(match[1]) : null;
    } catch (e) {
      return null;
    }
  }

  function isAuthorized() {
    var localValue = null;
    try {
      localValue = localStorage.getItem(LS_KEY);
    } catch (e) {
      localValue = null;
    }

    if (isTruthyValue(localValue)) return true;
    return isTruthyValue(getCookie(COOKIE_KEY));
  }

  function setAuthorized(value) {
    var normalized = value ? 'true' : 'false';

    try {
      localStorage.setItem(LS_KEY, normalized);
      log('localStorage:', LS_KEY, normalized);
    } catch (e) {
      log('No se pudo escribir localStorage', e);
    }

    try {
      document.cookie = COOKIE_KEY + '=' + encodeURIComponent(normalized) + '; path=/; SameSite=Lax';
      log('cookie:', COOKIE_KEY, normalized);
    } catch (e) {
      log('No se pudo escribir cookie', e);
    }
  }

  function resolveUrlFromTrigger(trigger) {
    var direct =
      trigger.getAttribute('href') ||
      trigger.dataset.url ||
      trigger.dataset.href ||
      trigger.getAttribute('data-link');
    if (direct) return direct;

    var link = trigger.closest('a[href]');

    if (!link && trigger.parentElement && typeof trigger.parentElement.closest === 'function') {
      link = trigger.parentElement.closest('a[href]');
    }

    if (!link) {
      var card = trigger.closest('[data-url], [data-href], [data-link]');
      if (card) {
        return card.getAttribute('data-url') || card.getAttribute('data-href') || card.getAttribute('data-link') || '';
      }
    }

    if (!link) {
      link = trigger.querySelector('a[href]');
    }

    if (link) return link.getAttribute('href') || '';

    return '';
  }

  function getModal() {
    return document.querySelector(MODAL_SELECTOR);
  }

  function openModal() {
    var modal = getModal();
    if (!modal) {
      log('No se encontro modal propio:', MODAL_SELECTOR);
      return false;
    }

    modal.classList.add('is-open');
    modal.setAttribute('aria-hidden', 'false');
    document.body.classList.add('ccr-gate-modal-open');
    log('Modal propio abierto');
    return true;
  }

  function closeModal() {
    var modal = getModal();
    if (!modal) return;
    modal.classList.remove('is-open');
    modal.setAttribute('aria-hidden', 'true');
    document.body.classList.remove('ccr-gate-modal-open');
    log('Modal propio cerrado');
  }

  function bindModalEvents() {
    var modal = getModal();
    if (!modal || modal.dataset.bound === '1') return;

    var overlay = modal.querySelector('.ccr-gate-modal__overlay');
    var closeBtn = modal.querySelector('.ccr-gate-modal__close');

    if (overlay) {
      overlay.addEventListener('click', function () {
        closeModal();
      });
    }

    if (closeBtn) {
      closeBtn.addEventListener('click', function () {
        closeModal();
      });
    }

    modal.addEventListener('click', function (e) {
      if (e.target === modal) closeModal();
    });

    modal.dataset.bound = '1';
  }

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
      closeModal();
    }
  });

  function tryOpenGate() {
    bindModalEvents();
    return openModal();
  }

  document.addEventListener('click', function (e) {
    var trigger = e.target.closest(BUTTON_SELECTOR);
    if (!trigger) return;

    var authorized = isAuthorized();
    var clickedLink = e.target.closest('a[href]');
    var url = resolveUrlFromTrigger(trigger);
    log('Click detectado. URL:', url || '(vacia)', 'authorized:', authorized);

    if (authorized) {
      log('Autorizado=true, navegacion normal');
      if (!clickedLink && url && url !== '#') {
        window.location.href = url;
        return;
      }
      if (!url || url === '#') {
        log('Autorizado pero sin URL detectada; se mantiene flujo nativo del sitio');
      }
      return;
    }

    e.preventDefault();
    e.stopPropagation();
    if (typeof e.stopImmediatePropagation === 'function') e.stopImmediatePropagation();

    pendingUrl = url && url !== '#' ? url : null;

    if (!tryOpenGate()) {
      log('No se pudo abrir el modal propio. Verifica que exista #ccr-gate-modal en el DOM.');
    }
  }, true);

  window.addEventListener('autorizacionPost:ok', function () {
    log('autorizacionPost:ok recibido');
    setAuthorized(true);
    closeModal();
    if (pendingUrl) {
      log('Redirigiendo a:', pendingUrl);
      window.location.href = pendingUrl;
    }
  });

  window.GatePostsDebug = {
    openModal: openModal,
    isAuthorized: isAuthorized,
    setAuthorized: setAuthorized,
    selector: BUTTON_SELECTOR,
    modalSelector: MODAL_SELECTOR
  };

  bindModalEvents();
  log('Script cargado. Modal:', MODAL_SELECTOR, 'Selector:', BUTTON_SELECTOR);
})();

</script>