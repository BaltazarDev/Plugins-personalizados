/**
 * OmniPark Diagonal Images - Frontend Scripts
 */

(function($) {
    'use strict';

    /**
     * Inicializar imágenes del contenedor diagonal
     */
    function initDiagonalImages() {
        var $containers = $('.diagonal-images-container');

        $containers.each(function() {
            var $container = $(this);
            var $images = $container.find('img');

            // Agregar evento de carga
            $images.on('load', function() {
                $(this).parent().removeClass('loading');
            });

            // Agregar evento de error
            $images.on('error', function() {
                $(this).parent().addClass('error').removeClass('loading');
            });

            // Lazy loading
            if ('IntersectionObserver' in window) {
                var observer = new IntersectionObserver(function(entries) {
                    entries.forEach(function(entry) {
                        if (entry.isIntersecting) {
                            var $img = $(entry.target).find('img');
                            if ($img.data('src')) {
                                $img.attr('src', $img.data('src')).removeData('src');
                            }
                            observer.unobserve(entry.target);
                        }
                    });
                });

                $container.find('.diagonal-image-wrapper').each(function() {
                    observer.observe(this);
                });
            }
        });
    }

    /**
     * Manejar redimensionamiento de ventana
     */
    function handleResize() {
        var resizeTimer;

        $(window).on('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
                // Re-inicializar si es necesario
                initDiagonalImages();
            }, 250);
        });
    }

    /**
     * Soporte para AJAX
     */
    $(document).on('elementor/popup/show', function() {
        initDiagonalImages();
    });

    /**
     * Inicializar al cargar el documento
     */
    $(document).ready(function() {
        initDiagonalImages();
        handleResize();
    });

    /**
     * Reinicializar cuando Elementor actualiza widgets
     */
    $(window).on('elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/omnipark-diagonal-images.default', function($scope) {
            initDiagonalImages();
        });
    });

})(jQuery);
