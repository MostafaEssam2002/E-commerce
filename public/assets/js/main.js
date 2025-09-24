(function ($) {
    "use strict";

    $(document).ready(function($){

        // testimonial sliders
        $(".testimonial-sliders").owlCarousel({
            items: 1,
            loop: true,
            autoplay: true,
            responsive:{
                0:{
                    items:1,
                    nav:false
                },
                600:{
                    items:1,
                    nav:false
                },
                1000:{
                    items:1,
                    nav:false,
                    loop:true
                }
            }
        });

        // homepage slider
        $(".homepage-slider").owlCarousel({
            items: 1,
            loop: true,
            autoplay: true,
            nav: true,
            dots: false,
            navText: ['<i class="fas fa-angle-left"></i>', '<i class="fas fa-angle-right"></i>'],
            responsive:{
                0:{
                    items:1,
                    nav:false,
                    loop:true
                },
                600:{
                    items:1,
                    nav:true,
                    loop:true
                },
                1000:{
                    items:1,
                    nav:true,
                    loop:true
                }
            }
        });

        // logo carousel
        $(".logo-carousel-inner").owlCarousel({
            items: 4,
            loop: true,
            autoplay: true,
            margin: 30,
            responsive:{
                0:{
                    items:1,
                    nav:false
                },
                600:{
                    items:3,
                    nav:false
                },
                1000:{
                    items:4,
                    nav:false,
                    loop:true
                }
            }
        });

        // count down
        if($('.time-countdown').length){
            $('.time-countdown').each(function() {
            var $this = $(this), finalDate = $(this).data('countdown');
            $this.countdown(finalDate, function(event) {
                var $this = $(this).html(event.strftime('' + '<div class="counter-column"><div class="inner"><span class="count">%D</span>Days</div></div> ' + '<div class="counter-column"><div class="inner"><span class="count">%H</span>Hours</div></div>  ' + '<div class="counter-column"><div class="inner"><span class="count">%M</span>Mins</div></div>  ' + '<div class="counter-column"><div class="inner"><span class="count">%S</span>Secs</div></div>'));
            });
         });
        }

        // projects filters isotop
        $(".product-filters li").on('click', function () {

            $(".product-filters li").removeClass("active");
            $(this).addClass("active");

            var selector = $(this).attr('data-filter');

            $(".product-lists").isotope({
                filter: selector,
            });

        });

        // isotop inner
        $(".product-lists").isotope();

        // magnific popup
        $('.popup-youtube').magnificPopup({
            disableOn: 700,
            type: 'iframe',
            mainClass: 'mfp-fade',
            removalDelay: 160,
            preloader: false,
            fixedContentPos: false
        });

        // light box
        $('.image-popup-vertical-fit').magnificPopup({
            type: 'image',
            closeOnContentClick: true,
            mainClass: 'mfp-img-mobile',
            image: {
                verticalFit: true
            }
        });

        // homepage slides animations
        $(".homepage-slider").on("translate.owl.carousel", function(){
            $(".hero-text-tablecell .subtitle").removeClass("animated fadeInUp").css({'opacity': '0'});
            $(".hero-text-tablecell h1").removeClass("animated fadeInUp").css({'opacity': '0', 'animation-delay' : '0.3s'});
            $(".hero-btns").removeClass("animated fadeInUp").css({'opacity': '0', 'animation-delay' : '0.5s'});
        });

        $(".homepage-slider").on("translated.owl.carousel", function(){
            $(".hero-text-tablecell .subtitle").addClass("animated fadeInUp").css({'opacity': '0'});
            $(".hero-text-tablecell h1").addClass("animated fadeInUp").css({'opacity': '0', 'animation-delay' : '0.3s'});
            $(".hero-btns").addClass("animated fadeInUp").css({'opacity': '0', 'animation-delay' : '0.5s'});
        });

        // stikcy js
        $("#sticker").sticky({
            topSpacing: 0
        });

        //mean menu
        $('.main-menu').meanmenu({
            meanMenuContainer: '.mobile-menu',
            meanScreenWidth: "992"
        });

         // search form
        $(".search-bar-icon").on("click", function(){
            $(".search-area").addClass("search-active");
        });

        $(".close-btn").on("click", function() {
            $(".search-area").removeClass("search-active");
        });

    });

    jQuery(window).on("load",function(){
        jQuery(".loader").fadeOut(1000);
    });

}(jQuery));

// Remove the duplicate Add to Cart code from main.js since it's handled in individual pages
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$(document).ready(function() {
    'use strict';

    // Initialize Product Image Slider
    initProductSlider();

    function initProductSlider() {
        const slider = $('.product-slider');
        const slides = $('.slider-item');
        const dots = $('.dot');
        const prevBtn = $('.slider-prev');
        const nextBtn = $('.slider-next');

        let currentSlide = 0;
        const totalSlides = slides.length;

        // إذا كان هناك شريحة واحدة فقط، لا نحتاج للتنقل
        if (totalSlides <= 1) {
            prevBtn.hide();
            nextBtn.hide();
            return;
        }

        // إظهار الشريحة الأولى
        showSlide(0);

        // زر التالي
        nextBtn.on('click', function(e) {
            e.preventDefault();
            currentSlide = (currentSlide + 1) % totalSlides;
            showSlide(currentSlide);
        });

        // زر السابق
        prevBtn.on('click', function(e) {
            e.preventDefault();
            currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
            showSlide(currentSlide);
        });

        // النقاط السفلية
        dots.on('click', function(e) {
            e.preventDefault();
            currentSlide = parseInt($(this).data('slide'));
            showSlide(currentSlide);
        });

        // Auto-play مع إمكانية الإيقاف
        let autoPlay = setInterval(function() {
            currentSlide = (currentSlide + 1) % totalSlides;
            showSlide(currentSlide);
        }, 5000);

        // إيقاف Auto-play عند التفاعل مع الـ slider
        $('.product-slider-container').on('mouseenter', function() {
            clearInterval(autoPlay);
        }).on('mouseleave', function() {
            autoPlay = setInterval(function() {
                currentSlide = (currentSlide + 1) % totalSlides;
                showSlide(currentSlide);
            }, 5000);
        });

        // دالة إظهار الشريحة
        function showSlide(index) {
            slides.removeClass('active');
            dots.removeClass('active');
            slides.eq(index).addClass('active');
            dots.eq(index).addClass('active');
        }

        // Touch/Swipe support
        let touchStartX = 0;
        let touchEndX = 0;

        slider.on('touchstart', function(e) {
            touchStartX = e.originalEvent.touches[0].clientX;
        });

        slider.on('touchend', function(e) {
            touchEndX = e.originalEvent.changedTouches[0].clientX;
            handleSwipe();
        });

        function handleSwipe() {
            const swipeThreshold = 50;
            const swipeDistance = touchEndX - touchStartX;

            if (Math.abs(swipeDistance) > swipeThreshold) {
                if (swipeDistance > 0) {
                    currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
                } else {
                    currentSlide = (currentSlide + 1) % totalSlides;
                }
                showSlide(currentSlide);
            }
        }

        // Keyboard navigation
        $(document).on('keydown', function(e) {
            if (e.keyCode === 37) { // Left arrow
                currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
                showSlide(currentSlide);
            } else if (e.keyCode === 39) { // Right arrow
                currentSlide = (currentSlide + 1) % totalSlides;
                showSlide(currentSlide);
            }
        });
    }

    // CSRF Token setup
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Add to Cart functionality
    $('.add-to-cart-btn').on('click', function(e) {
        e.preventDefault();

        const button = $(this);
        const productId = button.data('product-id');

        // Disable button during request
        button.prop('disabled', true);
        button.html('<i class="fas fa-spinner fa-spin"></i> Adding...');

        $.ajax({
            url: '/add_to_cart',
            type: 'POST',
            data: {
                product_id: productId,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    // Show success message (you can customize this)
                    console.log(response.message);

                    // Update cart count if function exists
                    if (typeof window.loadCartCount === 'function') {
                        window.loadCartCount();
                    }
                } else {
                    console.log(response.message || 'Something went wrong!');
                }
            },
            error: function(xhr) {
                let errorMessage = 'Something went wrong!';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                    const errors = Object.values(xhr.responseJSON.errors).flat();
                    errorMessage = errors.join(', ');
                }
                console.log(errorMessage);
            },
            complete: function() {
                // Re-enable button
                button.prop('disabled', false);
                button.html('<i class="fas fa-shopping-cart"></i> Add to Cart');
            }
        });
    });
});
