jQuery(document).ready(function ($) {

    function initEventosCarrusel($scope) {
        var $container = $scope.find('.ec-carrusel-container');
        var $scrollContainer = $scope.find('.ec-scroll-container');
        var $prevBtn = $scope.find('.ec-nav-btn.swiper-prev-custom');
        var $nextBtn = $scope.find('.ec-nav-btn.swiper-next-custom');

        if (!$scrollContainer.length || !$container.length) {
            return;
        }

        // Update button states based on scroll position
        function updateButtons() {
            var tolerance = 5;
            var scrollLeft = $scrollContainer.scrollLeft();
            var maxScroll = $scrollContainer[0].scrollWidth - $scrollContainer[0].clientWidth;

            // Disable/enable buttons
            $prevBtn.prop('disabled', scrollLeft <= 0);
            $nextBtn.prop('disabled', scrollLeft >= maxScroll - tolerance);

            // Update opacity
            $prevBtn.css('opacity', $prevBtn.prop('disabled') ? '0.3' : '1');
            $nextBtn.css('opacity', $nextBtn.prop('disabled') ? '0.3' : '1');
        }

        // Scroll function
        function scroll(direction) {
            var $card = $scrollContainer.find('.ec-card').first();
            var cardWidth = $card.outerWidth();
            var gap = 24; // gap-6 = 24px
            var scrollAmount = cardWidth + gap;

            var currentScroll = $scrollContainer.scrollLeft();
            var target = direction === 'left'
                ? currentScroll - scrollAmount
                : currentScroll + scrollAmount;

            $scrollContainer.animate({
                scrollLeft: target
            }, 300);
        }

        // Event listeners
        $prevBtn.on('click', function () {
            scroll('left');
        });

        $nextBtn.on('click', function () {
            scroll('right');
        });

        $scrollContainer.on('scroll', updateButtons);
        $(window).on('resize', updateButtons);

        // Initial check
        setTimeout(updateButtons, 100);
    }

    // Elementor Frontend (for published pages and preview)
    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/ec_carrusel_eventos.default', function ($scope) {
            initEventosCarrusel($scope);
        });
    });

    // Fallback for non-Elementor pages (shortcode usage)
    if (typeof elementorFrontend === 'undefined') {
        $('.ec-carrusel-container').each(function () {
            initEventosCarrusel($(this));
        });
    }
});
