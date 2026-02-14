jQuery(document).ready(function ($) {

    var initCarrusel = function ($scope, $) {
        // Find the container first to get data attributes
        var $container = $scope.find('.sc-carrusel-container');
        var $carrusel = $scope.find('.sc-carrusel');

        if (!$carrusel.length || !$container.length) {
            return;
        }

        // Get dynamic values from data attributes
        var slidesDesktop = parseFloat($container.attr('data-slides-desktop')) || 3.5;
        var slidesTablet = parseFloat($container.attr('data-slides-tablet')) || 2.5;
        var slidesMobile = parseFloat($container.attr('data-slides-mobile')) || 1.5;

        // Get autoplay settings
        var autoplayEnabled = $container.attr('data-autoplay') === 'yes';
        var autoplayDelay = parseInt($container.attr('data-autoplay-delay')) || 3000;

        // Destroy existing Swiper instance if it exists (important for Elementor editor)
        if ($carrusel[0].swiper) {
            $carrusel[0].swiper.destroy(true, true);
        }

        // Initialize Swiper with dynamic values
        const swiper = new Swiper($carrusel[0], {
            loop: true,
            spaceBetween: 30,
            autoplay: autoplayEnabled ? {
                delay: autoplayDelay,
                disableOnInteraction: false,
            } : false,
            navigation: {
                nextEl: $scope.find('.swiper-next-custom')[0],
                prevEl: $scope.find('.swiper-prev-custom')[0],
            },
            breakpoints: {
                // Mobile (Default)
                0: {
                    slidesPerView: slidesMobile,
                    spaceBetween: 20
                },
                // Tablet
                768: {
                    slidesPerView: slidesTablet,
                    spaceBetween: 30
                },
                // Desktop
                1024: {
                    slidesPerView: slidesDesktop,
                    spaceBetween: 30
                }
            }
        });
    };

    // Elementor Frontend (for published pages and preview)
    $(window).on('elementor/frontend/init', function () {
        // Initialize on widget load
        elementorFrontend.hooks.addAction('frontend/element_ready/sc_carrusel_servicios.default', initCarrusel);
    });

    // Fallback for non-Elementor pages (shortcode usage)
    if (typeof elementorFrontend === 'undefined') {
        $('.sc-carrusel-container').each(function () {
            var $container = $(this);
            var $carrusel = $container.find('.sc-carrusel');

            if (!$carrusel.length) {
                return;
            }

            // Get dynamic values from data attributes
            var slidesDesktop = parseFloat($container.attr('data-slides-desktop')) || 3.5;
            var slidesTablet = parseFloat($container.attr('data-slides-tablet')) || 2.5;
            var slidesMobile = parseFloat($container.attr('data-slides-mobile')) || 1.5;

            // Get autoplay settings
            var autoplayEnabled = $container.attr('data-autoplay') === 'yes';
            var autoplayDelay = parseInt($container.attr('data-autoplay-delay')) || 3000;

            new Swiper($carrusel[0], {
                loop: true,
                spaceBetween: 30,
                autoplay: autoplayEnabled ? {
                    delay: autoplayDelay,
                    disableOnInteraction: false,
                } : false,
                navigation: {
                    nextEl: $container.find('.swiper-next-custom')[0],
                    prevEl: $container.find('.swiper-prev-custom')[0],
                },
                breakpoints: {
                    0: {
                        slidesPerView: slidesMobile,
                        spaceBetween: 20
                    },
                    768: {
                        slidesPerView: slidesTablet,
                        spaceBetween: 30
                    },
                    1024: {
                        slidesPerView: slidesDesktop,
                        spaceBetween: 30
                    }
                }
            });
        });
    }
});