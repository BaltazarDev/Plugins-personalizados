jQuery(document).ready(function ($) {

    function initEventosCarrusel($scope) {
        var $container;

        // Handle case where $scope is the container itself (shortcode fallback)
        if ($scope.hasClass('ec-carrusel-container')) {
            $container = $scope;
        } else {
            $container = $scope.find('.ec-carrusel-container');
        }

        if (!$container.length) return;

        // Avoid double initialization
        if ($container[0].swiper) return;

        var containerEl = $container[0];
        var instanceId = containerEl.id; // e.g. "ec-carrusel-abc123"

        // Use .attr() (not .data()) to always read direct from HTML, avoiding jQuery cache
        var slidesDesktop = parseInt($container.attr('data-slides-desktop'), 10);
        var slidesTablet = parseInt($container.attr('data-slides-tablet'), 10);
        var slidesMobile = parseInt($container.attr('data-slides-mobile'), 10);
        var spaceBetween = parseInt($container.attr('data-space-between'), 10);

        // Sanitize / fallback defaults
        if (isNaN(slidesDesktop) || slidesDesktop < 1) slidesDesktop = 3;
        if (isNaN(slidesTablet) || slidesTablet < 1) slidesTablet = 2;
        if (isNaN(slidesMobile) || slidesMobile < 1) slidesMobile = 1;
        if (isNaN(spaceBetween) || spaceBetween < 0) spaceBetween = 24;

        // Build navigation selectors using unique IDs (more reliable than DOM refs)
        var prevSelector = instanceId ? '#' + instanceId + '-prev' : null;
        var nextSelector = instanceId ? '#' + instanceId + '-next' : null;

        // Initialize Swiper
        var swiper = new Swiper(containerEl, {
            slidesPerView: slidesMobile,
            spaceBetween: spaceBetween,

            grabCursor: true,
            a11y: true,
            roundLengths: true,
            watchOverflow: true,

            // Auto-update when Swiper container or parent changes dimensions
            // (critical for Elementor which builds layout progressively)
            observer: true,
            observeParents: true,
            observeSlideChildren: true,

            navigation: {
                nextEl: nextSelector,
                prevEl: prevSelector,
                disabledClass: 'swiper-nav-disabled',
            },

            preventClicks: false,
            preventClicksPropagation: false,
            slideToClickedSlide: false,

            breakpoints: {
                // >= 768px → tablet
                768: {
                    slidesPerView: slidesTablet,
                    spaceBetween: spaceBetween,
                },
                // >= 1024px → desktop
                1024: {
                    slidesPerView: slidesDesktop,
                    spaceBetween: spaceBetween,
                },
            },

            on: {
                // Force recalculation once Swiper finishes init
                afterInit: function () {
                    this.update();
                },
            },
        });

        // Secondary safety update after short delay
        // (catches cases where parent container finalizes size after init)
        setTimeout(function () {
            if (swiper && !swiper.destroyed) {
                swiper.update();
            }
        }, 300);
    }

    function ec_create_popup_modal() {
        if ($('#ec-evento-popup-overlay').length) {
            return;
        }

        var modalHtml = `
            <div id="ec-evento-popup-overlay" class="ec-evento-popup-overlay" aria-hidden="true">
                <div class="ec-evento-popup-wrap" role="dialog" aria-modal="true" aria-labelledby="ec-evento-popup-title">
                    <button type="button" class="ec-evento-popup-close" aria-label="Cerrar">&times;</button>
                    <div class="ec-evento-popup-content"></div>
                </div>
            </div>
        `;

        $('body').append(modalHtml);

        $(document).on('click', '.ec-evento-popup-close, #ec-evento-popup-overlay', function (e) {
            if (e.target !== this) {
                return;
            }
            ec_close_popup();
        });

        $(document).on('keyup', function (e) {
            if (e.key === 'Escape') {
                ec_close_popup();
            }
        });
    }

    function ec_open_popup(contentHtml) {
        ec_create_popup_modal();
        var $overlay = $('#ec-evento-popup-overlay');
        $overlay.find('.ec-evento-popup-content').html(contentHtml);
        $overlay.attr('aria-hidden', 'false').fadeIn(200);
        $('body').addClass('ec-evento-popup-active');
    }

    function ec_close_popup() {
        var $overlay = $('#ec-evento-popup-overlay');
        $overlay.attr('aria-hidden', 'true').fadeOut(200, function () {
            $overlay.find('.ec-evento-popup-content').empty();
            $('body').removeClass('ec-evento-popup-active');
        });
    }

    // Click on card opens the popup (except al hacer click en el boton RSVP)
    $(document).on('click', '.ec-card', function (e) {
        if ($(e.target).closest('.ec-rsvp-btn').length) {
            return; // keep RSVP button behavior
        }

        var $data   = $(this).find('.ec-popup-data');
        var title   = $data.attr('data-title') || '';
        var date    = $data.attr('data-date')  || '';
        var content = $data.html()             || '';

        var html = '<div class="ec-popup-header">';
        if (title) {
            html += '<h2 class="ec-popup-title">' + $('<div>').text(title).html() + '</h2>';
        }
        if (date) {
            html += '<p class="ec-popup-date">' + $('<div>').text(date).html() + '</p>';
        }
        html += '</div>';

        if (content && content.trim().length > 0) {
            html += '<div class="ec-popup-body">' + content + '</div>';
        }

        ec_open_popup(html);
    });

    // ── Elementor Frontend ──────────────────────────────────────────────────
    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction(
            'frontend/element_ready/ec_carrusel_eventos.default',
            function ($scope) {
                initEventosCarrusel($scope);
            }
        );
    });

    // ── Fallback: shortcode on non-Elementor pages ──────────────────────────
    if (typeof elementorFrontend === 'undefined') {
        $(window).on('load', function () {
            // Use 'load' instead of 'ready' so all images/fonts are resolved
            // and the container has its final dimensions
            $('.ec-carrusel-container').each(function () {
                initEventosCarrusel($(this));
            });
        });
    }
});
