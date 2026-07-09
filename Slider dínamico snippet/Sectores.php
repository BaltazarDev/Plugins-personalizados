
/**
 * SNIPPET — SLIDER 3 VISTAS SECTORES CANON
 * Shortcode: [canon_slider_sectores]
 * Centro completo, lados cortados, texto solo en activo, botones abajo derecha
 */
function canon_slider_sectores_shortcode(){
ob_start();

$base = get_site_url();
$url_base = $base . '/soluciones-por-industria/';

$slides = [
    [
        'img'    => $base . '/wp-content/uploads/2026/04/Banner_Salud.webp',
        'titulo' => 'Salud',
        'texto'  => 'Integre impresión y digitalización segura en su ecosistema TI, garantizando confidencialidad, trazabilidad y continuidad operativa en entornos clínicos y administrativos.',
        'btn'    => 'CONOCER',
        'url'    => $url_base . '#salud',
    ],
    [
        'img'    => $base . '/wp-content/uploads/2026/04/Banner_Manufactura.webp',
        'titulo' => 'Manufactura',
        'texto'  => 'Estandarice flujos documentales entre planta, logística y corporativo con visibilidad, control de costos y mínima interrupción operativa.',
        'btn'    => 'CONOCER',
        'url'    => $url_base . '#manufactura',
    ],
    [
        'img'    => $base . '/wp-content/uploads/2026/04/Banner_Legal.webp',
        'titulo' => 'Legal',
        'texto'  => 'Centralice contratos y expedientes con políticas de seguridad adaptativas, autenticación robusta y control granular de accesos.',
        'btn'    => 'CONOCER',
        'url'    => $url_base . '#legal',
    ],
    [
        'img'    => $base . '/wp-content/uploads/2026/04/Banner_Finanzas.webp',
        'titulo' => 'Finanzas',
        'texto'  => 'Gestione documentos sensibles bajo estándares de seguridad avanzados, auditoría trazable e integración con su ecosistema de monitoreo.',
        'btn'    => 'CONOCER',
        'url'    => $url_base . '#finanzas',
    ],
    [
        'img'    => $base . '/wp-content/uploads/2026/04/Banner_Retail.webp',
        'titulo' => 'Comercio',
        'texto'  => 'Optimice la gestión documental multisucursal con administración centralizada y monitoreo en tiempo real.',
        'btn'    => 'CONOCER',
        'url'    => $url_base . '#retail',
    ],
    [
        'img'    => $base . '/wp-content/uploads/2026/04/Banner_ServiciosEspe.webp',
        'titulo' => 'Servicios Especializados',
        'texto'  => 'Soporte documental adaptable a entornos colaborativos, con seguridad integrada y control presupuestal.',
        'btn'    => 'CONOCER',
        'url'    => $url_base . '#servicios',
    ],
];

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

/* ── TRACK: muestra 3 slides, el central completo, los laterales cortados ── */
.csect__viewport{
    width:100%;
    overflow:hidden;
    position:relative;
}

.csect__track{
    display:flex;
    align-items:stretch;
    transition:transform 0.75s cubic-bezier(0.4,0,0.2,1);
    will-change:transform;
}

/* Cada slide ocupa ~66% del viewport → los laterales asoman ~17% cada uno */
.csect__slide{
    flex:0 0 56.666%;
    position:relative;
    transition:opacity 0.5s;
    opacity:0.45;
    padding:0 8px;
}

.csect__slide.csect__active{
    opacity:1;
}

/* Imagen */
/* Máxima especificidad para ganar a Elementor */
.csect__outer .csect__slide .csect__img{
    width:100%!important;
    height:auto!important;
    max-width:100%!important;
    object-position:center!important;
    display:block!important;
    border:none!important;
    border-radius:0!important;
    box-shadow:none!important;
}

/* ── PANEL INFERIOR (gris oscuro) — solo visible en activo ── */
.csect__panel{
    background:transparent;
    padding:24px 32px 28px;
    display:flex;
    flex-direction:column;
    align-items:center;
    gap:12px;
    visibility:hidden;
    opacity:0;
    transition:opacity 0.45s, visibility 0.45s;
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

/* ── BOTONES NAV — abajo a la derecha ── */
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

/* ── RESPONSIVE: 1 slide con fade, sin track translate ── */
@media(max-width:768px){
    /* El track ya no hace translate en móvil */
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
        transition:opacity 0.55s ease;
    }
    .csect__viewport{
        position:relative;
    }
    /* Slide "fantasma" que da altura al viewport */
    .csect__slide.csect__active{
        position:relative;
        opacity:1;
        pointer-events:auto;
        z-index:2;
    }
    /* Ocultar los clones en móvil - no los necesitamos */
    .csect__slide.csect__clone{
        display:none!important;
    }
    .csect__panel{opacity:1!important;min-height:auto;visibility:visible;}
    .csect__outer .csect__slide .csect__img{height:300px!important;min-height:300px!important;}
    .csect__panel__titulo{font-size:16px!important;}
    .csect__panel__texto{font-size:15px!important;}
}
</style>

<div class="csect__outer" id="csect__outer">

    <div class="csect__viewport" id="csect__viewport">
        <div class="csect__track" id="csect__track">
            <?php
            // Render slide helper
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
            // Clon último (marcado como clone para ocultarlo en móvil)
            csect_slide($slides[$total-1], $total-1, false, true);
            // Slides reales
            foreach($slides as $i=>$slide) csect_slide($slide, $i, $i===0, false);
            // Clon primero (marcado como clone)
            csect_slide($slides[0], 0, false, true);
            ?>
        </div>
    </div>

    <!-- BOTONES NAV -->
    <div class="csect__nav">
        <button class="csect__nav__btn" id="csect__prev" aria-label="Anterior">
            <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 36 36" fill="none">
                <path d="M20 25C19.6162 25 19.2324 24.8535 18.9395 24.5605L13.4395 19.0605C12.8536 18.4751 12.8536 17.5249 13.4395 16.9394L18.9395 11.4394C19.5254 10.8535 20.4747 10.8535 21.0606 11.4394C21.6465 12.0248 21.6465 12.975 21.0606 13.5605L16.6211 18L21.0606 22.4395C21.6465 23.0249 21.6465 23.9751 21.0606 24.5606C20.7676 24.8536 20.3837 25 20 25Z" fill="#D2D2D7" fill-opacity="0.64"/>
            </svg>
        </button>
        <button class="csect__nav__btn" id="csect__next" aria-label="Siguiente">
            <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 36 36" fill="none">
                <path d="M16 11C16.3838 11 16.7676 11.1465 17.0605 11.4395L22.5605 16.9395C23.1464 17.5249 23.1464 18.4751 22.5605 19.0606L17.0605 24.5606C16.4746 25.1465 15.5253 25.1465 14.9394 24.5606C14.3535 23.9752 14.3535 23.025 14.9394 22.4395L19.3789 18L14.9394 13.5605C14.3535 12.9751 14.3535 12.0249 14.9394 11.4394C15.2324 11.1464 15.6163 11 16 11Z" fill="#D2D2D7" fill-opacity="0.64"/>
            </svg>
        </button>
    </div>

</div>

<script>
(function(){
    var TOTAL       = <?php echo $total; ?>;
    var track       = document.getElementById('csect__track');
    var vp          = document.getElementById('csect__viewport');
    /* DOM desktop: [clone_last] [s0] [s1] [s2] [clone_first]
       domCurrent=1 → s0 centrado al inicio */
    var domCurrent  = 1;
    var mobileIndex = 0; /* índice real 0..TOTAL-1 en móvil */
    var isAnimating = false;
    var animTimeout;

    /* Breakpoint unificado con el CSS (768px) */
    function isMobile(){ return window.innerWidth <= 768; }

    /* ───── MÓVIL: fade entre slides reales, sin clones ───── */
    function mobileGoTo(idx){
        /* Normalizar a rango 0..TOTAL-1 (loop infinito real) */
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

    /* ───── DESKTOP: track con translate + clones ───── */
    function setSlideWidthsDesktop(){
        var slides = track.querySelectorAll('.csect__slide');
        slides.forEach(function(s){
            s.style.flex = s.style.width = s.style.minWidth = '';
        });
    }

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
            ? 'transform 0.75s cubic-bezier(0.4,0,0.2,1)' : 'none';
        track.style.transform = 'translateX(' + calcOffsetDesktop(domIdx) + 'px)';
        domCurrent = domIdx;
        updateActiveDesktop();
    }

    /* transitionend: SOLO desktop, propertyName=transform */
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
        if(isMobile()){
            /* Móvil: loop real sin clones */
            mobileGoTo(mobileIndex + direction);
            return;
        }
        /* Desktop: sistema de clones */
        if(isAnimating) return;
        isAnimating = true;
        moveToDesktop(domCurrent + direction, true);
        clearTimeout(animTimeout);
        animTimeout = setTimeout(function(){
            if(!isAnimating) return;
            isAnimating = false;
            if(domCurrent === 0)              moveToDesktop(TOTAL, false);
            else if(domCurrent === TOTAL + 1) moveToDesktop(1,     false);
        }, 900);
    }

    /* Init según dispositivo */
    function initLayout(){
        if(isMobile()){
            /* Limpiar transform del track por si venía de desktop */
            track.style.transform = '';
            track.style.transition = '';
            mobileGoTo(mobileIndex);
        } else {
            setSlideWidthsDesktop();
            moveToDesktop(1, false);
        }
    }
    initLayout();

    /* Igualar alturas de paneles al mayor (solo desktop) */
    function equalizePanels(){
        if(isMobile()) return;
        var panels = track.querySelectorAll('.csect__panel');
        var maxH = 0;
        panels.forEach(function(p){
            p.style.minHeight = '';
            if(p.offsetHeight > maxH) maxH = p.offsetHeight;
        });
        panels.forEach(function(p){ p.style.minHeight = maxH + 'px'; });
    }
    equalizePanels();

    /* Autoplay */
    var autoPlay;
    function startAuto(){ autoPlay = setInterval(function(){ goTo(1); }, 9000); }
    function stopAuto() { clearInterval(autoPlay); }

    startAuto();

    /* Pausar al hover */
    var outer = document.getElementById('csect__outer');
    outer.addEventListener('mouseenter', stopAuto);
    outer.addEventListener('mouseleave', startAuto);

    document.getElementById('csect__prev').addEventListener('click', function(){ stopAuto(); goTo(-1); startAuto(); });
    document.getElementById('csect__next').addEventListener('click', function(){ stopAuto(); goTo(1);  startAuto(); });

    /* Swipe táctil */
    var startX = 0;
    track.addEventListener('touchstart', function(e){ startX = e.touches[0].clientX; }, {passive:true});
    track.addEventListener('touchend', function(e){
        var diff = startX - e.changedTouches[0].clientX;
        if(Math.abs(diff) > 40){ stopAuto(); goTo(diff > 0 ? 1 : -1); startAuto(); }
    });

    /* Recalcular al resize */
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
add_shortcode('canon_slider_sectores','canon_slider_sectores_shortcode');