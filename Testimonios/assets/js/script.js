jQuery(window).on('elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction('frontend/element_ready/the_mind_testimonials.default', function ($scope, $) {
        var $carouselContainer = $scope.find('.tm-testimonials-swiper');
        var $modalOverlay = $scope.find('.tm-modal-overlay');
        var $trigger = $scope.find('.tm-trigger-modal');
        var slideCount = $carouselContainer.data('slide-count') || 1;

        // Ensure Modal is in Body
        if ($modalOverlay.length) {
            if (!$modalOverlay.parent().is('body')) {
                $modalOverlay.appendTo(document.body);
            }

            // Unbind previous events to avoid duplicates if re-init
            $trigger.off('click.tm').on('click.tm', function (e) {
                e.preventDefault();
                $modalOverlay.fadeIn(300).css('display', 'flex');
            });

            $modalOverlay.find('.tm-modal-close').off('click.tm').on('click.tm', function () {
                $modalOverlay.fadeOut(300);
            });

            $modalOverlay.off('click.tm').on('click.tm', function (e) {
                if ($(e.target).is('.tm-modal-overlay')) {
                    $modalOverlay.fadeOut(300);
                }
            });
        }

        if (!$carouselContainer.length) {
            return;
        }

        var slidesPerView = $carouselContainer.data('slides-per-view') || 1;
        var slidesPerViewTablet = $carouselContainer.data('slides-per-view-tablet') || slidesPerView;
        var slidesPerViewMobile = $carouselContainer.data('slides-per-view-mobile') || 1;

        // Swiper Options
        const swiperOptions = {
            slidesPerView: parseInt(slidesPerViewMobile),
            spaceBetween: 30,
            loop: parseInt(slideCount) > 1,
            breakpoints: {
                768: {
                    slidesPerView: parseInt(slidesPerViewTablet),
                },
                1024: {
                    slidesPerView: parseInt(slidesPerView),
                }
            },
            navigation: {
                nextEl: $scope.find('.tm-arrow-next')[0],
                prevEl: $scope.find('.tm-arrow-prev')[0],
            },
            pagination: {
                clickable: true,
            },
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
                pauseOnMouseEnter: true,
            },
            effect: 'slide',
            speed: 600,
            // autoHeight: true,  // Removed - causes layout issues
            observer: true,
            observeParents: true,
            resizeObserver: true,
            updateOnImagesReady: true,
            grabCursor: true,
            watchSlidesProgress: true,
            watchSlidesVisibility: true,
        };

        const initSwiper = function () {
            // Destroy existing instance if any
            if ($carouselContainer[0] && $carouselContainer[0].swiper) {
                $carouselContainer[0].swiper.destroy(true, true);
            }

            if ('undefined' === typeof Swiper) {
                const asyncSwiper = elementorFrontend.utils.swiper;
                new asyncSwiper($carouselContainer, swiperOptions).then((newSwiperInstance) => {
                    if (newSwiperInstance) {
                        newSwiperInstance.update();
                    }
                });
            } else {
                var swiper = new Swiper($carouselContainer[0], swiperOptions);
                swiper.update();
            }
        };

        initSwiper();

    });
});
