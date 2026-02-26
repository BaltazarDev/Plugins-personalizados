jQuery(window).on('elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction('frontend/element_ready/the_mind_events.default', function ($scope) {

        const $swiperContainer = $scope.find('.tm-events-swiper');
        if (!$swiperContainer.length) return;

        // Swiper Settings from Data Attributes
        const slidesPerView = $swiperContainer.data('slides-per-view') || 1;
        const slidesPerViewTablet = $swiperContainer.data('slides-per-view-tablet') || slidesPerView;
        const slidesPerViewMobile = $swiperContainer.data('slides-per-view-mobile') || 1;
        const totalSlides = parseInt($swiperContainer.data('total-slides'), 10) || 0;
        const loopEnabled = totalSlides > slidesPerViewMobile;

        const swiper = new Swiper($swiperContainer[0], {
            slidesPerView: slidesPerViewMobile,
            spaceBetween: 20,
            loop: loopEnabled,
            watchSlidesProgress: true,
            preloadImages: false,
            navigation: {
                nextEl: $scope.find('.tm-event-next')[0],
                prevEl: $scope.find('.tm-event-prev')[0],
            },
            breakpoints: {
                768: {
                    slidesPerView: slidesPerViewTablet,
                },
                1024: {
                    slidesPerView: slidesPerView,
                }
            },
            on: {
                init: function () {
                    // Force arrows to be correctly positioned
                    $scope.find('.tm-event-arrow').css('display', 'flex');
                }
            }
        });

        // Modal Logic
        const $modalOverlay = $scope.find('.tm-event-modal-overlay');
        const $modalTitle = $modalOverlay.find('.tm-event-modal-title');
        const $modalContent = $modalOverlay.find('.tm-event-modal-description');

        $scope.on('click', '.tm-open-modal', function (e) {
            e.preventDefault();
            const $trigger = jQuery(this);
            const title = $trigger.data('title');
            const date = $trigger.data('date');
            const eventId = $trigger.data('event-id');

            // Try to find content in the current slide or global fallback
            let $source = $trigger.closest('.swiper-slide').find('.tm-event-content-source');

            // Fallback for loop mode clones
            if (!$source.length && eventId) {
                $source = $scope.find('.tm-event-content-source[data-event-id="' + eventId + '"]').first();
            }

            const content = $source.html() || '<p>No content available.</p>';

            $modalTitle.text(title);
            $modalOverlay.find('.tm-event-modal-date').text(date);
            $modalContent.html(content);

            $modalOverlay.fadeIn(300);
            jQuery('body').css('overflow', 'hidden');
        });

        $scope.on('click', '.tm-event-modal-close, .tm-event-modal-overlay', function (e) {
            if (e.target !== this) return;
            $modalOverlay.fadeOut(300);
            jQuery('body').css('overflow', '');
        });

        // Ensure close button sempre works
        $modalOverlay.find('.tm-event-modal-close').on('click', function () {
            $modalOverlay.fadeOut(300);
            jQuery('body').css('overflow', '');
        });

    });
});
