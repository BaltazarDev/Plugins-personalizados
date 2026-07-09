/**
 * SNIPPET — SLIDER 3 VISTAS SECTORES CANON
 * Shortcode: [slider_servicios_financieros_final_2]
 * Centro completo, lados cortados, texto solo en activo, botones abajo derecha
 * Compatible con widgets de pestañas de Elementor
 */
function slider_servicios_financieros_final_2_shortcode(){
ob_start();

$base = get_site_url();
$btn_url = $base . '/soluciones-por-segmento/';

$slides = [
    [
        'img'    => $base . '/wp-content/uploads/2026/04/Frame-427321133.png',
        'titulo' => 'Arquitectura, Ingeniería y Construcción (AEC)',
        'texto'  => 'Las soluciones Canon permiten producir dibujos CAD, planos técnicos, mapas GIS, renders AIC, carteles técnicos y materiales de construcción con alta fidelidad y rapidez.',
        'btn'    => 'CONOCER',
        'url'    => $url_base . '/soluciones-por-segmento/#arquitectura'
    ],
    [
        'img'    => $base . '/wp-content/uploads/2026/04/Frame-427321133-1.png',
        'titulo' => 'Artes Gráficas y Comunicación Visual',
        'texto'  => 'Canon impulsa la producción de banners, carteles, pancartas, backlit, lienzo, gráficos de ventana, revestimientos de pared y gráficos de pisos.',
        'btn'    => 'CONOCER',
        'url'    => $url_base . '/soluciones-por-segmento/#artes'
    ],
    [
        'img'    => $base . '/wp-content/uploads/2026/04/Frame-427321133-2.png',
        'titulo' => 'Producción Editorial',
        'texto'  => 'Canon facilita la producción de libros, revistas, periódicos, manuales, folletos, volantes y calendarios, con estabilidad de color y optimización en tirajes cortos y medianos.',
        'btn'    => 'CONOCER',
        'url'    => $url_base . '/soluciones-por-segmento/#produccion'
    ],
    [
        'img'    => $base . '/wp-content/uploads/2026/04/Frame-427321133-3.png',
        'titulo' => 'Comunicación Comercial e Impresión Promocional',
        'texto'  => 'Canon habilita la producción eficiente de correo directo, transaccional, transpromo, materiales corporativos y piezas promocionales.',
        'btn'    => 'CONOCER',
        'url'    => $url_base . '/soluciones-por-segmento/#comunicacion'
    ],
    [
        'img'    => $base . '/wp-content/uploads/2026/04/Frame-427321133-4.png',
        'titulo' => 'Marketing y Comunicación Transaccional',
        'texto'  => 'Canon optimiza la impresión de documentos transaccionales, estados de cuenta, comunicaciones personalizadas y piezas transpromo.',
        'btn'    => 'CONOCER',
        'url'    => $url_base . '/soluciones-por-segmento/#marketing'
    ],
    [
        'img'    => $base . '/wp-content/uploads/2026/04/Frame-427321133-5.png',
        'titulo' => 'Retail, Empaque y Señalización Comercial',
        'texto'  => 'Canon permite producir punto de venta, embalaje y bolsas, señalización blanda, paneles de madera, carteles comerciales y gráficos promocionales.',
        'btn'    => 'CONOCER',
        'url'    => $url_base . '/soluciones-por-segmento/#retail'
    ],
    [
        'img'    => $base . '/wp-content/uploads/2026/04/Frame-427321133-6.png',
        'titulo' => 'Fotografía Profesional y Productos Premium',
        'texto'  => 'Canon ofrece soluciones para photo books, impresión fotográfica de alta calidad, lienzo y backlit premium, con precisión de color y acabado profesional.',
        'btn'    => 'CONOCER',
        'url'    => $url_base . '/soluciones-por-segmento/#fotografia'
    ],
];

// ID único para permitir múltiples instancias (una por pestaña)
$uid = 'csect_' . substr(md5(uniqid('', true)), 0, 8);

$total = count($slides);
?>
<style>
.csect__outer *,.csect__outer *::before,.csect__outer *::after{box-sizing:border-box;margin:0;padding:0;}

.csect__outer{
    width:100%;
    font-family:'Proxima Nova',Arial,sans-serif;
    overflow:hidden;
    position:relative;
    background:transparent;
}

.csect__viewport{
    width:100%;
    overflow:hidden;
    position:relative;
}

.csect__track{
    display:flex;
    align-items:stretch;
    transition:transform 0.55s cubic-bezier(0.4,0,0.2,1);
    will-change:transform;
}

.csect__slide{
    flex:0 0 56.666%;
    position:relative;
    transition:opacity 0.4s;
    opacity:0.45;
    padding:0 8px;
}

.csect__slide.csect__active{
    opacity:1;
}

.csect__outer .csect__slide .csect__img{
    width:100%!important;
    max-width:100%!important;
    object-position:center!important;
    display:block!important;
    border:none!important;
    border-radius:0!important;
    box-shadow:none!important;
}

.csect__panel{
    background:transparent;
    padding:24px 32px 28px;
    display:flex;
    flex-direction:column;
    align-items:center;
    gap:12px;
    visibility:hidden;
    opacity:0;
    transition:opacity 0.35s, visibility 0.35s;
    min-height:180px;
    justify-content:center;
}

.csect__slide.csect__active .csect__panel{
    visibility:visible;
    opacity:1;
}

.csect__panel__titulo{
    color:#F5F5F7!important;
    text-align:center!important;
    font-family:'Proxima Nova',Arial,sans-serif!important;
    font-size:18px!important;
    font-weight:600!important;
    line-height:normal!important;
}

.csect__panel__texto{
    color:#F5F5F7!important;
    text-align:center!important;
    font-family:'Proxima Nova',Arial,sans-serif!important;
    font-size:18px!important;
    font-weight:400!important;
    line-height:23.5px!important;
    max-width:480px;
}

.csect__panel__btn{
    display:inline-flex;
    width:223px;
    height:48px;
    padding:0 24px;
    flex-direction:row;
    justify-content:center;
    align-items:center;
    color:#FFFFFF!important;
    text-align:center!important;
    font-family:'Proxima Nova',Arial,sans-serif!important;
    font-size:14px!important;
    font-weight:600!important;
    line-height:1!important;
    letter-spacing:1.4px!important;
    text-transform:uppercase!important;
    border:1px solid #CC0000!important;
    border-radius:0!important;
    background:#CC0000!important;
    cursor:pointer;
    transition:background 0.15s;
    text-decoration:none;
    margin-top:4px;
}
.csect__panel__btn:hover{background:#aa0000!important;border-color:#aa0000!important;}

.csect__nav{
    display:flex;
    justify-content:flex-end;
    align-items:center;
    gap:8px;
    padding:14px 16px 14px 0;
    background:transparent;
}

/* ── BOTÓN NAV: estado normal con fondo gris ── */
.csect__nav__btn{
    width:36px;height:36px;
    border-radius:50%;
    border:none!important;
    background:rgba(0,0,0,0.4)!important;
    display:flex;align-items:center;justify-content:center;
    cursor:pointer;
    transition:background 0.2s;
    padding:0;flex-shrink:0;
    outline:none!important;
    box-shadow:none!important;
    -webkit-appearance:none;
}

.csect__nav__btn:hover{
    background:#000!important;
    outline:none!important;
}

/* ── BOTÓN NAV: focus/active mantiene mismo color que inactivo ── */
.csect__nav__btn:focus,
.csect__nav__btn:focus-visible,
.csect__nav__btn:active{
    background:rgba(0,0,0,0.4)!important;
    outline:none!important;
    box-shadow:none!important;
    border:none!important;
}

.csect__nav__btn svg path{
    transition:fill 0.2s,fill-opacity 0.2s;
}

.csect__nav__btn:hover svg path{
    fill:#fff;
    fill-opacity:1;
}

@media(max-width:768px){
    .csect__track{
        display:block!important;
        transform:none!important;
        transition:none!important;
    }
    .csect__slide{
        flex:none;
        width:100%!important;
        max-width:100%!important;
        padding:0;
        opacity:0;
        position:absolute;
        top:0;
        left:0;
        pointer-events:none;
        transition:opacity 0.45s ease;
    }
    .csect__viewport{
        position:relative;
    }
    .csect__slide.csect__active{
        position:relative;
        opacity:1;
        pointer-events:auto;
        z-index:2;
    }
    .csect__slide.csect__clone{
        display:none!important;
    }
    .csect__panel{opacity:1!important;min-height:auto;visibility:visible;}
    .csect__outer .csect__slide .csect__img{height:300px!important;min-height:300px!important;}
    .csect__panel__titulo{font-size:16px!important;}
    .csect__panel__texto{font-size:16px!important;}
}
</style>

<div class="csect__outer" id="<?php echo $uid; ?>">

    <div class="csect__viewport">
        <div class="csect__track">
            <?php
            if(!function_exists('csect_slide')){
                function csect_slide($slide, $real, $active, $clone = false){
                    $cls = 'csect__slide';
                    if($active) $cls .= ' csect__active';
                    if($clone)  $cls .= ' csect__clone';
                    $url = !empty($slide['url']) ? $slide['url'] : '#';
                    echo '<div class="'.$cls.'" data-real="'.$real.'">';
                    echo '<img class="csect__img" src="'.esc_url($slide['img']).'" alt="'.esc_attr($slide['titulo']).'">';
                    echo '<div class="csect__panel">';
                    echo '<p class="csect__panel__titulo">'.esc_html($slide['titulo']).'</p>';
                    echo '<p class="csect__panel__texto">'.esc_html($slide['texto']).'</p>';
                    echo '<a class="csect__panel__btn" href="'.esc_url($url).'">'.esc_html($slide['btn']).'</a>';
                    echo '</div></div>';
                }
            }
            csect_slide($slides[$total-1], $total-1, false, true);
            foreach($slides as $i=>$slide) csect_slide($slide, $i, $i===0, false);
            csect_slide($slides[0], 0, false, true);
            ?>
        </div>
    </div>

    <div class="csect__nav">
        <button class="csect__nav__btn csect__prev" aria-label="Anterior">
            <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 36 36" fill="none">
                <path d="M20 25C19.6162 25 19.2324 24.8535 18.9395 24.5605L13.4395 19.0605C12.8536 18.4751 12.8536 17.5249 13.4395 16.9394L18.9395 11.4394C19.5254 10.8535 20.4747 10.8535 21.0606 11.4394C21.6465 12.0248 21.6465 12.975 21.0606 13.5605L16.6211 18L21.0606 22.4395C21.6465 23.0249 21.6465 23.9751 21.0606 24.5606C20.7676 24.8536 20.3837 25 20 25Z" fill="#D2D2D7" fill-opacity="0.64"/>
            </svg>
        </button>
        <button class="csect__nav__btn csect__next" aria-label="Siguiente">
            <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 36 36" fill="none">
                <path d="M16 11C16.3838 11 16.7676 11.1465 17.0605 11.4395L22.5605 16.9395C23.1464 17.5249 23.1464 18.4751 22.5605 19.0606L17.0605 24.5606C16.4746 25.1465 15.5253 25.1465 14.9394 24.5606C14.3535 23.9752 14.3535 23.025 14.9394 22.4395L19.3789 18L14.9394 13.5605C14.3535 12.9751 14.3535 12.0249 14.9394 11.4394C15.2324 11.1464 15.6163 11 16 11Z" fill="#D2D2D7" fill-opacity="0.64"/>
            </svg>
        </button>
    </div>

</div>

<script>
(function(){
    var TOTAL       = <?php echo $total; ?>;
    var outer       = document.getElementById('<?php echo $uid; ?>');
    if(!outer) return;
    var track       = outer.querySelector('.csect__track');
    var vp          = outer.querySelector('.csect__viewport');
    var domCurrent  = 1;
    var mobileIndex = 0;
    var isAnimating = false;
    var animTimeout;
    var lastKnownWidth = 0;

    function isMobile(){ return window.innerWidth <= 768; }

    /* El viewport es "medible" solo si tiene un ancho real > 0.
       Dentro de tabs ocultos, offsetWidth = 0. */
    function isMeasurable(){
        return vp.offsetWidth > 0;
    }

    /* ───── MÓVIL ───── */
    function mobileGoTo(idx){
        if(idx < 0) idx = TOTAL - 1;
        if(idx >= TOTAL) idx = 0;
        mobileIndex = idx;
        track.querySelectorAll('.csect__slide').forEach(function(s){
            if(s.classList.contains('csect__clone')){
                s.classList.remove('csect__active');
                return;
            }
            var real = parseInt(s.getAttribute('data-real'));
            s.classList.toggle('csect__active', real === mobileIndex);
        });
    }

    /* ───── DESKTOP ───── */
    function slideW(){
        var s = track.querySelector('.csect__slide');
        return s ? s.offsetWidth : vp.offsetWidth * 0.66666;
    }

    function updateActiveDesktop(){
        var real = domCurrent - 1;
        if(domCurrent === 0)          real = TOTAL - 1;
        if(domCurrent === TOTAL + 1)  real = 0;
        track.querySelectorAll('.csect__slide').forEach(function(s){
            s.classList.toggle('csect__active',
                parseInt(s.getAttribute('data-real')) === real);
        });
    }

    function calcOffsetDesktop(domIdx){
        var vw = vp.offsetWidth, sw = slideW();
        return (vw - sw) / 2 - domIdx * sw;
    }

    function moveToDesktop(domIdx, animate){
        track.style.transition = animate
            ? 'transform 0.55s cubic-bezier(0.4,0,0.2,1)' : 'none';
        track.style.transform = 'translateX(' + calcOffsetDesktop(domIdx) + 'px)';
        domCurrent = domIdx;
        updateActiveDesktop();
    }

    track.addEventListener('transitionend', function(e){
        if(isMobile()) return;
        if(e.target !== track || e.propertyName !== 'transform') return;
        clearTimeout(animTimeout);
        isAnimating = false;
        if(domCurrent === 0){
            moveToDesktop(TOTAL, false);
        } else if(domCurrent === TOTAL + 1){
            moveToDesktop(1, false);
        }
    });

    function goTo(direction){
        if(!isMeasurable()) return; /* No hacer nada si el slider está oculto */
        if(isMobile()){
            mobileGoTo(mobileIndex + direction);
            return;
        }
        if(isAnimating) return;
        isAnimating = true;
        moveToDesktop(domCurrent + direction, true);
        clearTimeout(animTimeout);
        animTimeout = setTimeout(function(){
            if(!isAnimating) return;
            isAnimating = false;
            if(domCurrent === 0)              moveToDesktop(TOTAL, false);
            else if(domCurrent === TOTAL + 1) moveToDesktop(1,     false);
        }, 700);
    }

    /* Reposicionar el track en la posición actual SIN animar.
       Clave al volver de un tab oculto. */
    function reposition(){
        if(!isMeasurable()) return;
        if(isMobile()){
            track.style.transform = '';
            track.style.transition = '';
            mobileGoTo(mobileIndex);
        } else {
            moveToDesktop(domCurrent, false);
        }
        lastKnownWidth = vp.offsetWidth;
    }

    function initLayout(){
        if(isMobile()){
            track.style.transform = '';
            track.style.transition = '';
            mobileGoTo(mobileIndex);
        } else {
            if(isMeasurable()){
                moveToDesktop(1, false);
                lastKnownWidth = vp.offsetWidth;
            }
        }
    }

    function equalizePanels(){
        if(isMobile()) return;
        if(!isMeasurable()) return;
        var panels = track.querySelectorAll('.csect__panel');
        var maxH = 0;
        panels.forEach(function(p){
            p.style.minHeight = '';
            if(p.offsetHeight > maxH) maxH = p.offsetHeight;
        });
        panels.forEach(function(p){ p.style.minHeight = maxH + 'px'; });
    }

    /* ───── DETECCIÓN DE VISIBILIDAD (tabs, acordeones, etc.) ─────
       Cualquier cambio de ancho del viewport = recalibrar. */
    if(typeof ResizeObserver !== 'undefined'){
        var ro = new ResizeObserver(function(entries){
            var w = vp.offsetWidth;
            if(w > 0 && w !== lastKnownWidth){
                /* Cambió el ancho real → reposicionar */
                if(!isMobile() && lastKnownWidth === 0){
                    /* Primera vez que se vuelve visible */
                    moveToDesktop(domCurrent, false);
                } else {
                    reposition();
                }
                equalizePanels();
                lastKnownWidth = w;
            }
        });
        ro.observe(vp);
    }

    /* IntersectionObserver como respaldo (algunos themes no disparan resize) */
    if(typeof IntersectionObserver !== 'undefined'){
        var io = new IntersectionObserver(function(entries){
            entries.forEach(function(entry){
                if(entry.isIntersecting && isMeasurable()){
                    /* Doble rAF para esperar a que el navegador termine el layout */
                    requestAnimationFrame(function(){
                        requestAnimationFrame(function(){
                            reposition();
                            equalizePanels();
                        });
                    });
                }
            });
        }, {threshold: 0.1});
        io.observe(outer);
    }

    /* Hook específico de Elementor: tab activado */
    if(typeof jQuery !== 'undefined'){
        jQuery(window).on('elementor/frontend/init', function(){
            if(window.elementorFrontend && elementorFrontend.hooks){
                elementorFrontend.hooks.addAction('frontend/element_ready/tabs.default', function(){
                    setTimeout(reposition, 100);
                    setTimeout(equalizePanels, 150);
                });
            }
        });
        /* Listener genérico para clicks en títulos de tabs Elementor */
        jQuery(document).on('click', '.elementor-tab-title, .elementor-tab-mobile-title', function(){
            setTimeout(function(){
                reposition();
                equalizePanels();
            }, 50);
            setTimeout(function(){
                reposition();
                equalizePanels();
            }, 350);
        });
    }

    /* Init */
    initLayout();
    equalizePanels();

    /* Si al cargar el slider está oculto, reintentar hasta que sea medible */
    if(!isMeasurable()){
        var retries = 0;
        var retryInterval = setInterval(function(){
            retries++;
            if(isMeasurable()){
                reposition();
                equalizePanels();
                clearInterval(retryInterval);
            } else if(retries > 40){ /* 40 * 250ms = 10s */
                clearInterval(retryInterval);
            }
        }, 250);
    }

    /* Autoplay */
    var autoPlay;
    function startAuto(){
        stopAuto();
        autoPlay = setInterval(function(){ goTo(1); }, 6000);
    }
    function stopAuto() { if(autoPlay){ clearInterval(autoPlay); autoPlay = null; } }

    startAuto();

    outer.addEventListener('mouseenter', stopAuto);
    outer.addEventListener('mouseleave', startAuto);

    outer.querySelector('.csect__prev').addEventListener('click', function(){ stopAuto(); goTo(-1); startAuto(); });
    outer.querySelector('.csect__next').addEventListener('click', function(){ stopAuto(); goTo(1);  startAuto(); });

    var startX = 0;
    track.addEventListener('touchstart', function(e){ startX = e.touches[0].clientX; }, {passive:true});
    track.addEventListener('touchend', function(e){
        var diff = startX - e.changedTouches[0].clientX;
        if(Math.abs(diff) > 40){ stopAuto(); goTo(diff > 0 ? 1 : -1); startAuto(); }
    });

    var rt;
    window.addEventListener('resize', function(){
        clearTimeout(rt);
        rt = setTimeout(function(){
            initLayout();
            equalizePanels();
        }, 100);
    });
})();
</script>
<?php
return ob_get_clean();
}
add_shortcode('slider_servicios_financieros_final_2','slider_servicios_financieros_final_2_shortcode');