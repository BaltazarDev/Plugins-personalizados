/**
 * Horizontal Carousel - JavaScript (Vanilla JS - No jQuery dependency)
 * Fixes scroll positioning for embedded contexts (WordPress/Elementor)
 */

(function () {
    'use strict';

    /**
     * Initialize carousels when DOM is ready
     */
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initializeCarousels);
    } else {
        initializeCarousels();
    }

    /**
     * Initialize all carousels on the page
     */
    function initializeCarousels() {
        const carouselSections = document.querySelectorAll('.horizontal-carousel-section');

        carouselSections.forEach(function (section) {
            const carouselId = section.getAttribute('id');
            if (carouselId) {
                new HorizontalCarousel(section, carouselId);
            }
        });
    }

    /**
     * Horizontal Carousel Class
     */
    function HorizontalCarousel(section, carouselId) {
        this.section = section;
        this.carouselId = carouselId;
        this.track = section.querySelector('.horizontal-track[data-carousel="' + carouselId + '"]');
        this.prevBtn = document.querySelector('.horizontal-prev-btn[data-carousel="' + carouselId + '"]');
        this.nextBtn = document.querySelector('.horizontal-next-btn[data-carousel="' + carouselId + '"]');

        if (!this.track) {
            console.error('Horizontal carousel track not found for:', carouselId);
            return;
        }

        this.isAnimating = false;
        this.rafId = null;

        this.init();
    }

    HorizontalCarousel.prototype.init = function () {
        // Bind scroll handler
        this.boundHandleScroll = this.handleScroll.bind(this);

        // Use scroll event with throttling for performance
        window.addEventListener('scroll', this.boundHandleScroll, { passive: true });
        window.addEventListener('resize', this.boundHandleScroll, { passive: true });

        // Button navigation
        if (this.prevBtn) {
            this.prevBtn.addEventListener('click', this.scrollPrev.bind(this));
        }

        if (this.nextBtn) {
            this.nextBtn.addEventListener('click', this.scrollNext.bind(this));
        }

        // Initial calculation
        this.handleScroll();

        console.log('Carousel initialized:', this.carouselId);
    };

    /**
     * CRITICAL FIX: Handle scroll with proper calculation for embedded contexts
     */
    HorizontalCarousel.prototype.handleScroll = function () {
        if (this.isAnimating) return;

        this.isAnimating = true;

        const self = this;
        this.rafId = requestAnimationFrame(function () {
            self.updateCarousel();
            self.isAnimating = false;
        });
    };

    HorizontalCarousel.prototype.updateCarousel = function () {
        // Get section position relative to viewport
        const rect = this.section.getBoundingClientRect();
        const sectionHeight = this.section.offsetHeight;
        const viewportHeight = window.innerHeight;

        // Calculate when section enters and exits viewport
        const sectionTop = rect.top;
        const sectionBottom = rect.bottom;

        // Calculate scroll progress through the section
        const scrollStart = -sectionTop;
        const scrollEnd = sectionHeight - viewportHeight;

        // Only animate when section is in viewport
        if (sectionBottom > 0 && sectionTop < viewportHeight) {
            // Calculate progress (0 to 1)
            let progress = scrollStart / scrollEnd;

            // Clamp progress between 0 and 1
            progress = Math.max(0, Math.min(1, progress));

            // Calculate horizontal translation
            const trackWidth = this.track.scrollWidth;
            const maxTranslate = trackWidth - window.innerWidth;
            const translateX = progress * maxTranslate;

            // Apply transform
            this.track.style.transform = 'translateX(-' + translateX + 'px)';
        } else if (sectionTop >= viewportHeight) {
            // Section is below viewport - reset to start
            this.track.style.transform = 'translateX(0px)';
        } else if (sectionBottom <= 0) {
            // Section is above viewport - set to end
            const trackWidth = this.track.scrollWidth;
            const maxTranslate = trackWidth - window.innerWidth;
            this.track.style.transform = 'translateX(-' + maxTranslate + 'px)';
        }
    };

    /**
     * Scroll to previous slide
     */
    HorizontalCarousel.prototype.scrollPrev = function () {
        const currentScroll = window.pageYOffset || document.documentElement.scrollTop;
        const scrollAmount = window.innerHeight * 0.8;

        this.smoothScrollTo(currentScroll - scrollAmount);
    };

    /**
     * Scroll to next slide
     */
    HorizontalCarousel.prototype.scrollNext = function () {
        const currentScroll = window.pageYOffset || document.documentElement.scrollTop;
        const scrollAmount = window.innerHeight * 0.8;

        this.smoothScrollTo(currentScroll + scrollAmount);
    };

    /**
     * Smooth scroll helper
     */
    HorizontalCarousel.prototype.smoothScrollTo = function (target) {
        window.scrollTo({
            top: target,
            behavior: 'smooth'
        });
    };

    /**
     * Destroy carousel (cleanup)
     */
    HorizontalCarousel.prototype.destroy = function () {
        window.removeEventListener('scroll', this.boundHandleScroll);
        window.removeEventListener('resize', this.boundHandleScroll);

        if (this.rafId) {
            cancelAnimationFrame(this.rafId);
        }
    };

    // Make it available globally
    window.HorizontalCarousel = HorizontalCarousel;

    /**
     * Reinitialize on Elementor preview (if Elementor is available)
     */
    if (typeof elementorFrontend !== 'undefined') {
        elementorFrontend.hooks.addAction('frontend/element_ready/horizontal-carousel.default', function ($scope) {
            initializeCarousels();
        });
    }

})();
