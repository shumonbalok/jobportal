(function($) {
  
  "use strict";

  // Preloader 
  function stylePreloader() {
    $('body').addClass('preloader-deactive');
  }

  // Background Image
  $('[data-bg-img]').each(function() {
    $(this).css('background-image', 'url(' + $(this).data("bg-img") + ')');
  });

  // Off Canvas JS
  var canvasWrapper = $(".off-canvas-wrapper");
  $(".btn-menu").on('click', function() {
      canvasWrapper.addClass('active');
      $("body").addClass('fix');
  });

  $(".close-action > .btn-close, .off-canvas-overlay").on('click', function() {
      canvasWrapper.removeClass('active');
      $("body").removeClass('fix');
  });

  //Responsive Slicknav JS
  $('.main-menu').slicknav({
      appendTo: '.res-mobile-menu',
      closeOnClick: true,
      removeClasses: true,
      closedSymbol: '<i class="icon-arrows-plus"></i>',
      openedSymbol: '<i class="icon-arrows-minus"></i>'
  });

  // Team Hover Active
  $('.team-btn-active').on('click', function(){
    var teamHoveractive = $(".team-btn-active");
    $(this).addClass('active');
  });

  $('.team-btn-close').on('click', function(){
    var teamHoverclose = $(".team-btn-active");
    teamHoverclose.removeClass('active');
  });

  // OwlCarousel JS

    // Features List Slider JS
    $('.features-slider').owlCarousel({
      autoplay: 2000,
      autoplayHoverPause: true,
      smartSpeed: 500,
      items: 4,
      margin: 30,
      loop: true,
      dots: false,
      nav: false,
      responsiveClass:true,
      responsive:{
          0:{
              items: 1
          },
          480:{
              items: 2
          },
          768:{
              items: 3
          },
          1200:{
              items: 4
          }
      }
    });

    // Team Slider JS
    $('.team-col3-carousel').owlCarousel({
      autoplay: 2000,
      autoplayHoverPause: true,
      smartSpeed: 500,
      items: 3,
      margin: 40,
      loop: true,
      dots: false,
      nav: true,
      navText: [
        '<i class="lnr lnr-arrow-left"></i>',
        '<i class="lnr lnr-arrow-right"></i>'
      ],
      responsiveClass:true,
      responsive:{
          0:{
              items: 1
          },
          480:{
              items: 2,
              margin: 20
          },
          576:{
              items: 2,
              margin: 40
          },
          992:{
              items: 3
          },
          1200:{
              items: 3
          }
      }
    });

    // Portfolio Slider JS
    $('.portfolio-slider-items').owlCarousel({
      autoplay: 2000,
      autoplayHoverPause: true,
      smartSpeed: 500,
      items: 3,
      margin: 55,
      loop: true,
      dots: true,
      nav: true,
      navText: [
        '<i class="lnr lnr-arrow-left"></i>',
        '<i class="lnr lnr-arrow-right"></i>'
      ],
      responsiveClass:true,
      responsive:{
          0:{
              items: 1,
              margin: 30
          },
          620:{
              items: 2,
              margin: 30
          },
          992:{
              items: 3,
              margin: 30
          },
          1200:{
              items: 3
          }
      }
    });

    // Portfolio Slider JS
    $('.portfolio-slider-items-three').owlCarousel({
      autoplay: 2000,
      autoplayHoverPause: true,
      smartSpeed: 500,
      items: 3,
      margin: 40,
      loop: true,
      dots: false,
      nav: true,
      navText: [
        '<i class="lnr lnr-arrow-left"></i>',
        '<i class="lnr lnr-arrow-right"></i>'
      ],
      responsiveClass:true,
      responsive:{
          0:{
              items: 1
          },
          480:{
              items: 2
          },
          768:{
              items: 3
          },
          1200:{
              items: 3
          }
      }
    });

    // Portfolio SEO Slider JS
    $('.portfolio-slider-seo').owlCarousel({
      autoplay: 2000,
      autoplayHoverPause: true,
      smartSpeed: 500,
      items: 3,
      margin: 45,
      loop: true,
      dots: false,
      nav: true,
      navText: [
        '<i class="lnr lnr-arrow-left"></i>',
        '<i class="lnr lnr-arrow-right"></i>'
      ],
      responsiveClass:true,
      responsive:{
          0:{
              items: 1
          },
          480:{
              items: 2,
              margin: 30
          },
          576:{
            items: 2,
            margin: 30
          },
          992:{
              items: 3
          },
          1500:{
            stagePadding: 255
          }
      }
    });

    // Testimonial Slider Style Two JS
    $('.testimonial-slider-two').owlCarousel({
      autoplay: 2000,
      autoplayHoverPause: true,
      smartSpeed: 500,
      items: 2,
      margin: 50,
      loop: true,
      dots: false,
      nav: true,
      navText: [
        '<i class="lnr lnr-arrow-left"></i>',
        '<i class="lnr lnr-arrow-right"></i>'
      ],
      responsiveClass:true,
      responsive:{
          0:{
              items: 1
          },
          480:{
              items: 1,
              stagePadding: 15
          },
          768:{
              items: 2,
              stagePadding: 15,
              margin: 30
          },
          1200:{
            items: 2,
            stagePadding: 197
          }
      }
    });

    // Testimonial Single Slider Style Two JS
    $('.testimonial-single-slider-two').owlCarousel({
      autoplay: 2000,
      autoplayHoverPause: true,
      smartSpeed: 500,
      items: 1,
      margin: 0,
      loop: true,
      dots: false,
      nav: true,
      navText: [
        '<i class="lnr lnr-arrow-left"></i>',
        '<i class="lnr lnr-arrow-right"></i>'
      ]
    });

    // Brand Slider JS
    $('.brand-col5-carousel').owlCarousel({
      autoplay: 2000,
      autoplayHoverPause: true,
      smartSpeed: 500,
      items: 5,
      margin: 30,
      loop: true,
      dots: false,
      nav: true,
      navText: [
        '<i class="lnr lnr-arrow-left"></i>',
        '<i class="lnr lnr-arrow-right"></i>'
      ],
      responsiveClass:true,
      responsive:{
          0:{
              items: 2
          },
          480:{
              items: 3
          },
          768:{
              items: 5
          },
          992:{
              items: 5
          }
      }
    });

    // Blog Slider JS
    $('.post-items-slider').owlCarousel({
      autoplay: 2000,
      autoplayHoverPause: true,
      smartSpeed: 500,
      items: 3,
      margin: 40,
      loop: true,
      dots: false,
      nav: true,
      navText: [
        '<i class="lnr lnr-arrow-left"></i>',
        '<i class="lnr lnr-arrow-right"></i>'
      ],
      responsiveClass:true,
      responsive:{
          0:{
              items: 1
          },
          480:{
              items: 2
          },
          768:{
              items: 2
          },
          992:{
              items: 3
          }
      }
    });

    // Blog Slider Two JS
    $('.post-items-slider-two').owlCarousel({
      autoplay: 2000,
      autoplayHoverPause: true,
      smartSpeed: 500,
      items: 3,
      margin: 40,
      loop: true,
      dots: false,
      nav: true,
      navText: [
        '<i class="lnr lnr-arrow-left"></i>',
        '<i class="lnr lnr-arrow-right"></i>'
      ],
      responsiveClass:true,
      responsive:{
          0:{
              items: 1
          },
          768:{
              items: 2,
              margin: 30
          },
          992:{
              items: 3
          },
          1200:{
              items: 3
          }
      }
    });

    // Blog Slider Three JS
    $('.post-items-slider-three').owlCarousel({
      autoplay: 2000,
      autoplayHoverPause: true,
      smartSpeed: 500,
      items: 3,
      margin: 40,
      loop: true,
      dots: false,
      nav: true,
      navText: [
        '<i class="lnr lnr-arrow-left"></i>',
        '<i class="lnr lnr-arrow-right"></i>'
      ],
      responsiveClass:true,
      responsive:{
          0:{
              items: 1
          },
          768:{
              items: 2
          },
          992:{
              items: 2
          },
          1200:{
              items: 3
          }
      }
    });

    // Blog Slider Four JS
    $('.post-items-slider-four').owlCarousel({
      autoplay: 2000,
      autoplayHoverPause: true,
      smartSpeed: 500,
      items: 2,
      margin: 40,
      loop: true,
      dots: false,
      nav: true,
      navText: [
        '<i class="lnr lnr-arrow-left"></i>',
        '<i class="lnr lnr-arrow-right"></i>'
      ],
      responsiveClass:true,
      responsive:{
          0:{
              items: 1
          },
          480:{
              items: 1,
              margin: 30
          },
          768:{
              items: 2
          },
          1200:{
              items: 2
          }
      }
    });

    // Blog Slider Five JS
    $('.post-items-slider-five').owlCarousel({
      autoplay: 2000,
      autoplayHoverPause: true,
      smartSpeed: 500,
      items: 3,
      margin: 30,
      loop: true,
      dots: false,
      nav: true,
      navText: [
        '<i class="lnr lnr-arrow-left"></i>',
        '<i class="lnr lnr-arrow-right"></i>'
      ],
      responsiveClass:true,
      responsive:{
          0:{
              items: 1
          },
          480:{
              items: 2
          },
          768:{
              items: 2
          },
          1200:{
              items: 3
          }
      }
    });

    // Blog Details Gallery Slider JS
    $('.post-details-gallery-slider').owlCarousel({
      autoplay: 2000,
      autoplayHoverPause: true,
      smartSpeed: 500,
      items: 1,
      margin: 30,
      loop: true,
      dots: true,
      nav: true,
      navText: [
        '<i class="lnr lnr-arrow-left"></i>',
        '<i class="lnr lnr-arrow-right"></i>'
      ]
    });

    // Related Products Slider JS
    $('.related-products-slider').owlCarousel({
      autoplay: 2000,
      autoplayHoverPause: true,
      smartSpeed: 500,
      items: 4,
      margin: 30,
      loop: true,
      dots: true,
      nav: false,
      responsiveClass:true,
      responsive:{
          0:{
              items: 1
          },
          480:{
              items: 2
          },
          768:{
              items: 3
          },
          1200:{
              items: 4
          }
      }
    });

    // Related Products Slider JS
    $('.brand-logo-slider').owlCarousel({
      autoplay: false,
      autoplayHoverPause: true,
      smartSpeed: 500,
      items: 5,
      margin: 30,
      loop: true,
      dots: false,
      nav: false,
      responsiveClass:true,
      responsive:{
          0:{
              items: 2,
              margin: 0
          },
          480:{
              items: 3,
              margin: 0
          },
          768:{
              items: 4
          },
          1200:{
              items: 5
          }
      }
    });


  // Slick Slider JS

    // Testimonial Single Slider JS
    $('.testimonial-single-slider').slick({
      infinite: true,
      speed: 500,
      autoplay: true,
      autoplaySpeed: 4000,
      arrows: true,
      fade: true
    });

    // Testimonial Slider JS
    $('.testimonial-slider').slick({
      slidesToShow: 1,
      slidesToScroll: 1,
      autoplay: true,
      autoplaySpeed: 2000,
      arrows: false,
      dots: true,
      fade: true,
      responsive: [
        {
          breakpoint: 576,
          setting: {
            slidesToShow: 1
          }
        },
        {
          breakpoint: 1200,
          setting: {
            slidesToShow: 1
          }
        }
      ]
    });

    // Blog Slider JS
    $('.post-items-slick-slider').slick({
      infinite: true,
      slidesToShow: 3,
      slidesToScroll: 1
    });

    // Single Product Thumb JS
    $('.single-product-thumb').slick({
      slidesToShow: 1,
      slidesToScroll: 1,
      arrows: false,
      fade: true,
      asNavFor: '.single-product-nav'
    });
    $('.single-product-nav').slick({
      slidesToShow: 4,
      slidesToScroll: 1,
      asNavFor: '.single-product-thumb',
      dots: false,
      centerMode: true,
      centerPadding: '0',
      focusOnSelect: true
    });


  // Animated Typed JS
  if ($("#typed").length > 0) {
    var typed = new Typed('#typed', {
      stringsElement: '#typed-strings',
      typeSpeed: 100,
      loop: true
    });
  }

  // Isotope and data filter
  function isotopePortfolio() {
    var $grid = $('.portfolio-grid').isotope({
      itemSelector: '.portfolio-item',
      masonry: {
        columnWidth: 1
      }
    })
    // Isotope Masonry
    var $gridMasonry = $('.portfolio-masonry').isotope({
      itemSelector: '.portfolio-item'
    })
    // Isotope filter Menu
    $('.portfolio-filter-menu').on( 'click', 'button', function() {
      var filterValue = $(this).attr('data-filter');
      $grid.isotope({ filter: filterValue });
      $gridMasonry.isotope({ filter: filterValue });
      var filterMenuactive = $(".portfolio-filter-menu button");
      filterMenuactive.removeClass('active');
      $(this).addClass('active');
    });
    // Portfolio photogrhaper hover
    var photogrhaperportfolio = $(".portfolio-photographer .inner-content");
    photogrhaperportfolio.on('mouseover', function() {
        photogrhaperportfolio.addClass('active');
        $(".portfolio-masonry").addClass('hover');
    });
    photogrhaperportfolio.on('mouseout', function() {
        photogrhaperportfolio.removeClass('active');
        $(".portfolio-masonry").removeClass('hover');
    });
    // Portfolio text interactive hover
    var activeIdinteractive = $(".portfolio-text-interactive .portfolio-filter-menu button");
    $(".portfolio-text-interactive .portfolio-masonry").isotope();
    activeIdinteractive.on('mouseover', function() {
        var $this = $(this),
            filterValue = $this.data('filter');

        $(".portfolio-masonry").isotope({
            filter: filterValue,

            visibleStyle: {
                opacity: 1,
                top: 0,
                transform: 'translate3d(0)',
            },

            hiddenStyle: {
                opacity: 0,
                top: 0,
                transform: 'translate3d(0)',
            }
        });

        activeIdinteractive.removeClass('active');
        $this.addClass('active');
    });

    // Masonry Grid
    $(".masonryGrid").isotope({
      itemSelector: '.masonry-item'
    });
  }

  // Fancybox Js
  $('.lightbox-image').fancybox();

  //Video Popup
  $('.play-video-popup').fancybox();

  // MatchHeight Js
  $('.equal-height').matchHeight();

  // Wow Js
  if ($('.wow').length) {
    var wow = new WOW ({
      boxClass: 'wow',
      animateClass: 'animated',
      offset: 0,
      mobile: true,
      live: true
    });
    wow.init();
  }

  // Pricing Tab Js
  if ($('#pricing-tab-style').length) {
    var tabSwitch = $('#pricing-tab-style label.switch');
    var TabTitle = $('#pricing-tab-style li');
    var monthTabTitle = $('#pricing-tab-style li.month');
    var annualyTabTitle = $('#pricing-tab-style li.annualy');
    var monthTabContent = $('#month');
    var annualyTabContent = $('#annualy');
    monthTabContent.fadeIn();
    annualyTabContent.fadeOut();
    function toggleHandle() {
      if (tabSwitch.hasClass('on')) {
        annualyTabContent.fadeOut();
        monthTabContent.fadeIn();
        monthTabTitle.addClass('active');
        annualyTabTitle.removeClass('active');
      } else {
        monthTabContent.fadeOut();
        annualyTabContent.fadeIn();
        annualyTabTitle.addClass('active');
        monthTabTitle.removeClass('active');
      }
    };
    monthTabTitle.on('click', function () {
      tabSwitch.addClass('on').removeClass('off');
      toggleHandle();
      return false;
    });
    annualyTabTitle.on('click', function () {
      tabSwitch.addClass('off').removeClass('on');
      toggleHandle();
      return false;
    });
    tabSwitch.on('click', function () {
      tabSwitch.toggleClass('on off');
      toggleHandle();
    });
  }

  //Counter JS
  var counterId = $('.counter-animate');
  if (counterId.length) {
    counterId.counterUp({
      delay: 10,
      time: 1000
    });
  }

  // Scroll Top Hide Show
  $(window).on('scroll', function(){
    if ($(this).scrollTop() > 250) {
      $('.scroll-to-top').fadeIn();
    } else {
      $('.scroll-to-top').fadeOut();
    }

    // Sticky Header
    if($('.sticky-header').length){
      var windowpos = $(this).scrollTop();
      if (windowpos >= 80) {
        $('.sticky-header').addClass('sticky');
      } else {
        $('.sticky-header').removeClass('sticky');
      }
    }

  });

  jQuery(document).ready(function($) {
    // Contact Map JS
    var map_id = $('#map_content');
    if (map_id.length > 0) {
        var $lat = map_id.data('lat'),
            $lng = map_id.data('lng'),
            $zoom = map_id.data('zoom'),
            $maptitle = map_id.data('maptitle'),
            $mapaddress = map_id.data('mapaddress'),
            mymap = L.map('map_content').setView([$lat, $lng], $zoom);

        L.tileLayer('http://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: 'Map',
            maxZoom: 22,
            minZoom: 2,
            id: 'mapbox.streets',
            scrollWheelZoom: false
        }).addTo(mymap);

        var marker = L.marker([$lat, $lng]).addTo(mymap);
        mymap.zoomControl.setPosition('bottomright');
        mymap.scrollWheelZoom.disable();
    }

    // Ajax Contact Form JS
    var form = $('#contact-form');
    var formMessages = $('.form-message');

    $(form).submit(function(e) {
        e.preventDefault();
        var formData = form.serialize();
        $.ajax({
            type: 'POST',
            url: form.attr('action'),
            data: formData
        }).done(function(response) {
            // Make sure that the formMessages div has the 'success' class.
            $(formMessages).removeClass('alert alert-danger');
            $(formMessages).addClass('alert alert-success fade show');

            // Set the message text.
            formMessages.html("<button type='button' class='close' data-dismiss='alert'>&times;</button>");
            formMessages.append(response);

            // Clear the form.
            $('#contact-form input,#contact-form textarea').val('');
        }).fail(function(data) {
            // Make sure that the formMessages div has the 'error' class.
            $(formMessages).removeClass('alert alert-success');
            $(formMessages).addClass('alert alert-danger fade show');

            // Set the message text.
            if (data.responseText !== '') {
                formMessages.html("<button type='button' class='close' data-dismiss='alert'>&times;</button>");
                formMessages.append(data.responseText);
            } else {
                $(formMessages).text('Oops! An error occurred and your message could not be sent.');
            }
        });
    });
  
  });

  // Search Box  JS
  $(".icon-search").on('click', function() {
    $(".btn-search").addClass('show');
    $(".btn-search-content").addClass("show").focus();
  });

  $(".icon-search-close").on('click', function() {
      $(".btn-search").removeClass("show");
      $(".btn-search-content").removeClass("show");
  });

  // Nice Select
  $(".niceselect").niceSelect();

  $("#ship_to_different").on("change", function() {
    $(".ship-to-different").slideToggle("100");
  });

  //Click event to scroll to top
  $('.scroll-to-top').on('click', function(){
    $('html, body').animate({scrollTop : 0},800);
    return false;
  });

  // Reveal Footer JS
  let revealId = $(".reveal-footer"),
    footerHeight = revealId.outerHeight(),
    windowWidth = $(window).width(),
    windowHeight = $(window).outerHeight();

  if (windowWidth > 991 && windowHeight > footerHeight) {
    $(".site-wrapper-reveal").css({
      'margin-bottom': footerHeight + 'px'
    });
  }
  
  
/* ==========================================================================
   When document is loading, do
   ========================================================================== */
  
  $(window).on('load', function() {
    stylePreloader();
    isotopePortfolio();
  });

/* ==========================================================================
   When document is Scrollig, do
   ========================================================================== */
  
  $(window).on('scroll', function() {
  });
  

/* ==========================================================================
   When Window is resizing, do
   ========================================================================== */
  
  $(window).on('resize', function() {
  });
  

})(window.jQuery);