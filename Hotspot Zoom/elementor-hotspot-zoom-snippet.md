# Zoom para Elementor Hotspot (imagen + puntos)

## 1) Clase CSS en el widget Hotspot
En Elementor, al widget Hotspot agrega esta clase en Avanzado > Clases CSS:

hz-hotspot-zoom

## 2) CSS global
Pega este CSS en Elementor > Custom Code (CSS) o en el CSS del tema hijo:

```css
.hz-hotspot-zoom .elementor-widget-container {
  overflow: hidden;
  position: relative;
  touch-action: none;
  cursor: grab;
}

.hz-hotspot-zoom .elementor-widget-container.is-dragging {
  cursor: grabbing;
}

.hz-hotspot-zoom .e-hotspot {
  transform-origin: 0 0;
  will-change: transform;
}
```

## 3) JavaScript
Pega este JS en Elementor > Custom Code y cargalo al final del body:

```javascript
(function () {
  function initHotspotZoom(scope) {
    var container = scope.querySelector('.elementor-widget-container');
    var stage = scope.querySelector('.e-hotspot');
    if (!container || !stage || stage.dataset.zoomReady === '1') return;

    stage.dataset.zoomReady = '1';

    // Carga Panzoom desde CDN si no existe
    function start() {
      var panzoom = Panzoom(stage, {
        maxScale: 4,
        minScale: 1,
        contain: 'outside',
        canvas: true
      });

      // Zoom con rueda del mouse
      container.addEventListener('wheel', function (e) {
        e.preventDefault();
        var delta = e.deltaY < 0 ? 1.1 : 0.9;
        panzoom.zoomWithWheel(e, { step: delta });
      }, { passive: false });

      // Soporte de arrastre visual
      container.addEventListener('pointerdown', function () {
        container.classList.add('is-dragging');
      });
      window.addEventListener('pointerup', function () {
        container.classList.remove('is-dragging');
      });

      // Doble clic para reset
      container.addEventListener('dblclick', function () {
        panzoom.reset();
      });
    }

    if (window.Panzoom) {
      start();
    } else {
      var s = document.createElement('script');
      s.src = 'https://unpkg.com/@panzoom/panzoom/dist/panzoom.min.js';
      s.onload = start;
      document.head.appendChild(s);
    }
  }

  function bootAll() {
    document.querySelectorAll('.elementor-widget-hotspot.hz-hotspot-zoom').forEach(initHotspotZoom);
  }

  // Carga normal
  document.addEventListener('DOMContentLoaded', bootAll);

  // Carga dinamica de Elementor
  if (window.jQuery && window.elementorFrontend) {
    jQuery(window).on('elementor/frontend/init', function () {
      elementorFrontend.hooks.addAction('frontend/element_ready/hotspot.default', function ($scope) {
        initHotspotZoom($scope[0]);
      });
    });
  }
})();
```

## Notas
- El zoom mantiene la alineacion de los puntos porque se escala todo el bloque .e-hotspot.
- Funciona con carga normal y con render dinamico de Elementor.
- Doble clic hace reset del zoom.
