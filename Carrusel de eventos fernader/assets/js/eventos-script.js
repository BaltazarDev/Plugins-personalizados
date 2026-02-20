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
