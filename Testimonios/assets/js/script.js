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

jQuery(function ($) {
    $('.tm-testimonial-submission-form').attr('novalidate', 'novalidate');

    function getFieldErrorMessage(fieldName, isInvalidEmail) {
        if (fieldName === 'tm_email' && isInvalidEmail) {
            return 'Ingresa un correo electronico valido.';
        }

        switch (fieldName) {
            case 'tm_name':
                return 'El nombre es obligatorio.';
            case 'tm_email':
                return 'El email es obligatorio.';
            case 'tm_rating':
                return 'La calificacion es obligatoria.';
            case 'tm_content':
                return 'El testimonio es obligatorio.';
            default:
                return 'Este campo es obligatorio.';
        }
    }

    function clearFieldError($form, fieldName) {
        var $error = $form.find('.tm-field-error[data-for="' + fieldName + '"]');
        var $field = $form.find('[name="' + fieldName + '"]');

        $error.text('');
        $field.removeClass('tm-invalid').attr('aria-invalid', 'false');
    }

    function setFieldError($form, fieldName, message) {
        var $error = $form.find('.tm-field-error[data-for="' + fieldName + '"]');
        var $field = $form.find('[name="' + fieldName + '"]');

        $error.text(message);
        $field.addClass('tm-invalid').attr('aria-invalid', 'true');
    }

    $(document).on('submit', '.tm-testimonial-submission-form', function (e) {
        var $form = $(this);
        var hasErrors = false;

        ['tm_name', 'tm_email', 'tm_rating', 'tm_content'].forEach(function (fieldName) {
            clearFieldError($form, fieldName);
        });

        var $name = $form.find('[name="tm_name"]');
        var $email = $form.find('[name="tm_email"]');
        var $content = $form.find('[name="tm_content"]');
        var $ratingChecked = $form.find('[name="tm_rating"]:checked');

        if (!$name.val() || !$name.val().trim()) {
            setFieldError($form, 'tm_name', getFieldErrorMessage('tm_name'));
            hasErrors = true;
        }

        if (!$email.val() || !$email.val().trim()) {
            setFieldError($form, 'tm_email', getFieldErrorMessage('tm_email'));
            hasErrors = true;
        } else {
            var emailValue = $email.val().trim();
            var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(emailValue)) {
                setFieldError($form, 'tm_email', getFieldErrorMessage('tm_email', true));
                hasErrors = true;
            }
        }

        if (!$content.val() || !$content.val().trim()) {
            setFieldError($form, 'tm_content', getFieldErrorMessage('tm_content'));
            hasErrors = true;
        }

        if (!$ratingChecked.length) {
            setFieldError($form, 'tm_rating', getFieldErrorMessage('tm_rating'));
            hasErrors = true;
        }

        if (hasErrors) {
            e.preventDefault();
        }
    });

    $(document).on('input change', '.tm-testimonial-submission-form [name="tm_name"], .tm-testimonial-submission-form [name="tm_email"], .tm-testimonial-submission-form [name="tm_content"], .tm-testimonial-submission-form [name="tm_rating"]', function () {
        var $field = $(this);
        var $form = $field.closest('.tm-testimonial-submission-form');
        var fieldName = $field.attr('name');

        if (!fieldName) {
            return;
        }

        clearFieldError($form, fieldName);
    });
});
