(function () {
  function ensureZoomStage(container) {
    var existingStage = container.querySelector('.hz-zoom-stage');
    if (existingStage) return existingStage;

    var stage = document.createElement('div');
    stage.className = 'hz-zoom-stage';

    var nodesToMove = [];
    Array.prototype.forEach.call(container.children, function (child) {
      if (!child.classList.contains('hz-zoom-controls')) {
        nodesToMove.push(child);
      }
    });

    nodesToMove.forEach(function (node) {
      stage.appendChild(node);
    });

    container.insertBefore(stage, container.firstChild);
    return stage;
  }

  function createControlButton(label, className, onClick) {
    var button = document.createElement('button');
    button.type = 'button';
    button.className = className;
    button.textContent = label;
    button.addEventListener('click', function (e) {
      e.preventDefault();
      e.stopPropagation();
      onClick();
    });
    return button;
  }

  function createZoomControls(container, panzoom) {
    if (container.querySelector('.hz-zoom-controls')) return;

    var controls = document.createElement('div');
    controls.className = 'hz-zoom-controls';

    var zoomIn = createControlButton('+', 'hz-zoom-btn hz-zoom-in', function () {
      panzoom.zoomIn();
    });

    var zoomOut = createControlButton('-', 'hz-zoom-btn hz-zoom-out', function () {
      panzoom.zoomOut();
    });

    var reset = createControlButton('Reset', 'hz-zoom-btn hz-zoom-reset', function () {
      panzoom.reset();
    });

    controls.appendChild(zoomIn);
    controls.appendChild(zoomOut);
    controls.appendChild(reset);
    container.appendChild(controls);
  }

  function initHotspotZoom(scope) {
    var container = scope.querySelector('.elementor-widget-container');
    if (!container || container.dataset.zoomReady === '1') return;

    container.dataset.zoomReady = '1';

    var stage = ensureZoomStage(container);

    function start() {
      var panzoom = Panzoom(stage, {
        maxScale: 4,
        minScale: 1,
        contain: 'outside',
        canvas: true
      });

      createZoomControls(container, panzoom);

      container.addEventListener(
        'wheel',
        function (e) {
          e.preventDefault();
          var delta = e.deltaY < 0 ? 1.1 : 0.9;
          panzoom.zoomWithWheel(e, { step: delta });
        },
        { passive: false }
      );

      container.addEventListener('pointerdown', function () {
        container.classList.add('is-dragging');
      });

      window.addEventListener('pointerup', function () {
        container.classList.remove('is-dragging');
      });

      container.addEventListener('dblclick', function () {
        panzoom.reset();
      });
    }

    if (window.Panzoom) {
      start();
      return;
    }

    var script = document.createElement('script');
    script.src = 'https://unpkg.com/@panzoom/panzoom/dist/panzoom.min.js';
    script.onload = start;
    document.head.appendChild(script);
  }

  function bootAll() {
    document
      .querySelectorAll('.elementor-widget-hotspot.hz-hotspot-zoom')
      .forEach(initHotspotZoom);
  }

  document.addEventListener('DOMContentLoaded', bootAll);

  if (window.jQuery && window.elementorFrontend) {
    jQuery(window).on('elementor/frontend/init', function () {
      elementorFrontend.hooks.addAction(
        'frontend/element_ready/hotspot.default',
        function ($scope) {
          initHotspotZoom($scope[0]);
        }
      );
    });
  }
})();
