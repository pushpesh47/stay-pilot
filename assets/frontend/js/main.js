(function ($) {
  "use strict";
  var windowOn = $(window);


    /*-----------------------------------------------------------------------------------

        Template Name: boostay - Hotel booking HTML5 Template
        Author: sTheme-IT
        Support: mailto:saadalam291@gmail.com
        Description: boostay - Hotel booking HTML5 Template
        Version: 1.0
        Developer: Soukhin khan (https://github.com/Soukhinkhan)

    -----------------------------------------------------------------------------------

      /*======================================
        Preloader activation
        ========================================*/




function handleQuantityButtons() {
    // Handle the minus button click
    $('.quantity__group .minus').click(function() {
        var input = $(this).closest('.quantity__group').find('input.qty');
        var currentValue = parseInt(input.val());
        if (currentValue > 1) {
            input.val(currentValue - 1).change();
        }
    });

    // Handle the plus button click
    $('.quantity__group .plus').click(function() {
        var input = $(this).closest('.quantity__group').find('input.qty');
        var currentValue = parseInt(input.val());
        input.val(currentValue + 1).change();
    });
}

handleQuantityButtons();

$(document.body).on('updated_cart_totals', function() {
    handleQuantityButtons();
});

var slider = new Swiper ('.banner__slider', {
    slidesPerView: 1,
    centeredSlides: true,
    loop: true,
    loopedSlides: 8, 
    navigation: {
        prevEl: ".banner__slider__arrow-prev",
        nextEl: ".banner__slider__arrow-next",
    },
});

var thumbs = new Swiper ('.gallery-thumbs', {
    slidesPerView: 'auto',
    spaceBetween: 20,
    centeredSlides: true,
    loop: true,
    slideToClickedSlide: true,
});

slider.controller.control = thumbs;
thumbs.controller.control = slider;

        function resourcesHubMasonry() {
            var $grid = $('.resources-hub__masonry');
            $grid.masonry({
              itemSelector: '.col-item',
              columnWidth: '.col-lg-4',
              horizontalOrder: false,
              isAnimated: true,
              // percentPosition: true,
            });
        
            $grid.masonry('reloadItems');
            $grid.masonry('layout');
        
            // layout Masonry after each image loads
            $grid.imagesLoaded().progress(function () {
              $grid.masonry('layout');
            });
          }
        
          resourcesHubMasonry();


	$(window).on('load', function (event) {
		$('#preloader').delay(1500).fadeOut(500);

        $('.odometer').waypoint(function(direction) {
            if (direction === 'down') {
                let countNumber = $(this.element).attr("data-count");
                $(this.element).html(countNumber);
            }
        }, {
            offset: '80%'
        });
	});


    $(".preloader-close").on("click", function () {
        $('#preloader').delay(0).fadeOut(500);

        $('.odometer').waypoint(function(direction) {
            if (direction === 'down') {
                let countNumber = $(this.element).attr("data-count");
                $(this.element).html(countNumber);
            }
        }, {
            offset: '80%'
        });
    })

    /*======================================
   Data Css js
   ========================================*/
    $("[data-background]").each(function() {
        $(this).css(
            "background-image",
            "url( " + $(this).attr("data-background") + "  )"
        );
    });

    $("[data-width]").each(function() {
        $(this).css("width", $(this).attr("data-width"));
    });

    $("[data-bg-color]").each(function() {
        $(this).css("background-color", $(this).attr("data-bg-color"));
    });

  /*======================================
	Mobile Menu Js
	========================================*/
  $("#mobile-menu").meanmenu({
    meanMenuContainer: ".mobile-menu",
    meanScreenWidth: "1200",
    meanExpand: ['<i class="fa-regular fa-angle-right"></i>'],
  });

  /*======================================
	Sidebar Toggle
	========================================*/
  $(".offcanvas__close,.offcanvas__overlay").on("click", function () {
    $(".offcanvas__area").removeClass("info-open");
    $(".offcanvas__overlay").removeClass("overlay-open");
  });
  // Scroll to bottom then close navbar
  $(window).scroll(function(){
    if($("body").scrollTop() > 0 || $("html").scrollTop() > 0) {
        $(".offcanvas__area").removeClass("info-open");
        $(".offcanvas__overlay").removeClass("overlay-open");
    }
  });
  $(".sidebar__toggle").on("click", function () {
    $(".offcanvas__area").addClass("info-open");
    $(".offcanvas__overlay").addClass("overlay-open");
  });
   
  /*======================================
	Body overlay Js
	========================================*/
  $(".body-overlay").on("click", function () {
    $(".offcanvas__area").removeClass("opened");
    $(".body-overlay").removeClass("opened");
  });

  /*======================================
	Sticky Header Js
	========================================*/

  $(window).scroll(function () {
    if ($(this).scrollTop() > 250) {
      $("#header-sticky").addClass("rs-sticky");
    } else {
      $("#header-sticky").removeClass("rs-sticky");
    }
  });

  /*======================================
	MagnificPopup image view
	========================================*/
  $(".popup-image").magnificPopup({
    type: "image",
    gallery: {
      enabled: true,
    },
  });

  /*======================================
	MagnificPopup video view
	========================================*/
  $(".popup-video").magnificPopup({
    type: "iframe",
  });


  /*======================================
	Wow Js
	========================================*/
    if ($('.wow').length) {
        var wow = new WOW({
            boxClass: 'wow', // animated element css class (default is wow)
            animateClass: 'animated', // animation css class (default is animated)
            offset: 0, // distance to the element when triggering the animation (default is 0)
            mobile: false, // trigger animations on mobile devices (default is true)
            live: true // act on asynchronously loaded content (default is true)
        });
        wow.init();
    }

  /*======================================
	Button scroll up js
	========================================*/
    
    /*======================================
	One Page Scroll Js
	========================================*/
    /*** Scroll Nav */
    var link = $('#mobile-menu ul li a, .mean-nav ul li a');

    link.on('click', function(e) {
        var target = $($(this).attr('href'));
        $('html, body').animate({
            scrollTop: target.offset().top - 76
        }, 600);
        $(this).parent().addClass('active');
        e.preventDefault();
    });

    $(window).on('scroll', function(){
        scrNav();
    });

    function scrNav() {
        var sTop = $(window).scrollTop();
        $('section').each(function() {
            var id = $(this).attr('id'),
                offset = $(this).offset().top-1,
                height = $(this).height();
            if(sTop >= offset && sTop < offset + height) {
                link.parent().removeClass('active');
                $('.main-menu').find('[href="#' + id + '"]').parent().addClass('active');
            }
        });
    }
    scrNav();

    /*======================================
	Smoth animatio Js
	========================================*/
    $(document).on('click', '.smoth-animation', function (event) {
        event.preventDefault();
        $('html, body').animate({
            scrollTop: $($.attr(this, 'href')).offset().top - 50
        }, 300);
    });



    $('.grid').isotope({
        itemSelector: '.grid-item',
        percentPosition: true,
        masonry: {
          columnWidth: 1
        }
      })

      var swiper = new Swiper(".resturent-slide", {
        slidesPerView: 3,
        spaceBetween: 30,
        loop: true,
        navigation: {
            prevEl: ".resturent__slider-button-prev",
            nextEl: ".resturent__slider-button-next",
        },
        pagination: {
          el: ".swiper-pagination",
          clickable: true,
        },
        breakpoints: {
            1201: {
                slidesPerView: 3,
            },
            716: {
                slidesPerView: 2,
            },
            0: {
                slidesPerView: 1,
            },
        },
      });

      $('.col-custom').on("click", function () {
		$('#features-item-thumb').removeClass().addClass($(this).attr('rel'));
		$(this).addClass('active').siblings().removeClass('active');
	});

    // Testimonial 
    var swiper = new Swiper(".testi-content-carousel", {
        slidesPerView: 1,
        spaceBetween: 0,
        navigation: {
            nextEl: ".testimonial-next",
            prevEl: ".testimonial-prev",
        },
        loop: true,
        breakpoints: {
        1920: {
            slidesPerView: 1,
            },
        1400: {
            slidesPerView: 1,
        },
        992: {
            slidesPerView: 1,
        },
        768: {
            slidesPerView: 1,
        },
        0: {
            slidesPerView: 1,
        },
        },
    });
    // Testimonial Two
    var swiper = new Swiper(".testi-carousel", {
        slidesPerView: 1,
        spaceBetween: 30,
        loop: true,
        breakpoints: {
        1920: {
            slidesPerView: 1,
            },
        1400: {
            slidesPerView: 1,
        },
        992: {
            slidesPerView: 1,
        },
        768: {
            slidesPerView: 1,
        },
        0: {
            slidesPerView: 1,
        },
        },
    });
    // Testimonial four
    var swiper = new Swiper(".card-testimonial", {
        slidesPerView: 1,
        spaceBetween: 30,
        loop: true,
        breakpoints: {
        1920: {
            slidesPerView: 1,
            },
        1400: {
            slidesPerView: 1,
        },
        992: {
            slidesPerView: 1,
        },
        768: {
            slidesPerView: 1,
        },
        0: {
            slidesPerView: 1,
        },
        },
    });

    // Slider Five
    var swiper = new Swiper(".card-slider", {
        slidesPerView: 4,
        spaceBetween: 30,
        loop: true,
        navigation: {
          prevEl: ".si-button-prev",
          nextEl: ".si-button-next",
        },
        breakpoints: {
        1920: {
            slidesPerView: 1,
            },
          1400: {
            slidesPerView: 1,
          },	  
          992: {
            slidesPerView: 1,
          },
          768: {
            slidesPerView: 1,
          },
          0: {
            slidesPerView: 1,
          },
        },
      });

    // Room Five
    var swiper = new Swiper(".card-room", {
        slidesPerView: 4,
        spaceBetween: 30,
        loop: true,
        navigation: {
          prevEl: ".si-button-prev",
          nextEl: ".si-button-next",
        },
        breakpoints: {
        1920: {
            slidesPerView: 3,
            },
          1400: {
            slidesPerView: 3,
          },	  
          992: {
            slidesPerView: 3,
          },
          768: {
            slidesPerView: 2,
          },
          0: {
            slidesPerView: 1,
          },
        },
      });
    // Accommodations
    var swiper = new Swiper(".card-accommodations", {
        slidesPerView: 4,
        spaceBetween: 30,
        center:true,
        loop: true,
        navigation: {
          prevEl: ".si-button-prev",
          nextEl: ".si-button-next",
        },
        breakpoints: {
        1920: {
            slidesPerView: 3,
            },
          1400: {
            slidesPerView: 3,
          },	  
          992: {
            slidesPerView: 2,
          },
          768: {
            slidesPerView: 2,
          },
          0: {
            slidesPerView: 1,
          },
        },
      });

    // Room Six
    var swiper = new Swiper(".card-room-six", {
        slidesPerView: 4,
        spaceBetween: 30,
        center:true,
        loop: true,
        navigation: {
          prevEl: ".si-button-prev",
          nextEl: ".si-button-next",
        },
        breakpoints: {
        1920: {
            slidesPerView: 2,
            },
          1400: {
            slidesPerView: 2,
          },	  
          992: {
            slidesPerView: 1,
          },
          768: {
            slidesPerView: 1,
          },
          0: {
            slidesPerView: 1,
          },
        },
      });

      // Slider Six
    var swiper = new Swiper(".card-slider-six", {
        slidesPerView: 4,
        spaceBetween: 30,
        center:true,
        loop: true,
      pagination: {
        el: ".swiper-pagination",
        clickable: true,
      },
        breakpoints: {
        1920: {
            slidesPerView: 1,
            },
          1400: {
            slidesPerView: 1,
          },	  
          992: {
            slidesPerView: 1,
          },
          768: {
            slidesPerView: 1,
          },
          0: {
            slidesPerView: 1,
          },
        },
      });

      // Testimnoial Six
    var swiper = new Swiper(".card-testimonial-six", {
        slidesPerView: 4,
        spaceBetween: 30,
        center:true,
        loop: true,
      pagination: {
        el: ".swiper-pagination",
        clickable: true,
      },
        breakpoints: {
        1920: {
            slidesPerView: 1,
            },
          1400: {
            slidesPerView: 1,
          },	  
          992: {
            slidesPerView: 1,
          },
          768: {
            slidesPerView: 1,
          },
          0: {
            slidesPerView: 1,
          },
        },
      });

      // Brand Six
    var swiper = new Swiper(".card-brand-six", {
        slidesPerView: 4,
        spaceBetween: 30,
        center:true,
        loop: true,
      pagination: {
        el: ".swiper-pagination",
        clickable: true,
      },
        breakpoints: {
        1920: {
            slidesPerView: 5,
            },
          1400: {
            slidesPerView: 5,
          },	  
          992: {
            slidesPerView: 4,
          },
          768: {
            slidesPerView: 3,
          },
          0: {
            slidesPerView: 1,
          },
        },
      });

    // Popup Search Box
    $(function () {
        $("#popup-search-box").removeClass("toggled");

        $(".dl-search-icon").on("click", function (e) {
            e.stopPropagation();
            $("#popup-search-box").toggleClass("toggled");
            $("#popup-search").focus();
        });

        $("#popup-search-box input").on("click", function (e) {
            e.stopPropagation();
        });

        $("#popup-search-box, body").on("click", function () {
            $("#popup-search-box").removeClass("toggled");
        });
    });

    var bannerSlider;
    var bannerSliderOptions = {
        parallax: true,
        loop: true,
    };
    bannerSlider = new Swiper(
        ".banner_parallax-slider",
        bannerSliderOptions
    );

    // $('.lan-select select, .nice-select-select select').niceSelect();
    $('.lan-select-2 select, .room-seclect select, .room-details-seclect select, .blog-details-list select, .nice-select-select select').niceSelect();
    $( "#datepicker" ).datepicker({
        dateFormat: "mm/dd/yy" 
    });  
    $( "#datepicker1" ).datepicker({
        dateFormat: "mm/dd/yy" 
    });

    $('#getting-started').countdown('2025/01/01', function(event) {
        $(this).html(event.strftime(' <div><span>%D</span></div>  <div><span>%H</span></div> <div><span>%M</span></div> <div><span>%S</span></div>'));
      });


      /*** lastNobullet */
    function lastNobullet() {
        var lastElement = false;
        $(".footer__copyright-menu ul li, .last_item_not_horizental_bar .col-lg-4").each(function() {
            if (lastElement && lastElement.offset().top != $(this).offset().top) {
                $(lastElement).addClass("no_bullet");
            } else {
                $(lastElement).removeClass("no_bullet");
            }
            lastElement = $(this);
        }).last().addClass("no_bullet");
    };
    lastNobullet();

    $(window).resize(function(){
        lastNobullet();
    });

    $('#contact-us__form').submit(function(event) {
        event.preventDefault();
        var form = $(this);
        $('.loading-form').show();

        setTimeout(function() { 
            $.ajax({
            type: form.attr('method'),
            url: form.attr('action'),
            data: form.serialize()
            }).done(function(data) {
                $('.loading-form').hide();
                $('.contact-us__form').append('<p class="success-message mt-3 mb-0">Your message has been sent successfully.</p>');
            }).fail(function(data) {
                $('.loading-form').hide();
                $('.contact-us__form').append('<p class="error-message mt-3 mb-0">Something went wrong. Please try again later.</p>');

            });
        }, 1000);
      });

    $('#showlogin').on('click', function () {
        $('#checkout-login').slideToggle(400);
    });
    $('#showcoupon').on('click', function () {
        $('#checkout_coupon').slideToggle(400);
    });

    

    // Custom Cursor
    $("body").append('<div class="mt-cursor"></div>');
    var cursor = $(".mt-cursor"),
        linksCursor = $("a, .swiper-nav, button, .cursor-effect"),
        crossCursor = $(".cross-cursor");

    $(window).on("mousemove", function (e) {
        cursor.css({
            transform: "translate(" + (e.clientX - 15) + "px," + (e.clientY - 15) + "px)",
            visibility: "inherit",
        });
    });

        /*-----------------------
		Price Range Slider
	------------------------ */
    var rangeSlider = $(".price-range"),
        minamount = $("#minamount"),
        maxamount = $("#maxamount"),
        minPrice = rangeSlider.data('min'),
        maxPrice = rangeSlider.data('max');
    rangeSlider.slider({
        range: true,
        min: minPrice,
        max: maxPrice,
        values: [minPrice, maxPrice],
        slide: function (event, ui) {
            minamount.val('$' + ui.values[0]);
            maxamount.val('$' + ui.values[1]);
        }
    });
    minamount.val('$' + rangeSlider.slider("values", 0));
    maxamount.val('$' + rangeSlider.slider("values", 1));

    // Page Scroll Percentage
    function scrollTopPercentage() {
        const scrollPercentage = () => {
            const scrollTopPos = document.documentElement.scrollTop;
            const calcHeight = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            const scrollValue = Math.round((scrollTopPos / calcHeight) * 100);
            const scrollElementWrap = $("#scroll-percentage");

            scrollElementWrap.css("background", `conic-gradient( var(--rr-theme-primary) ${scrollValue}%, var(--rr-common-white) ${scrollValue}%)`);
            
            // ScrollProgress
            if ( scrollTopPos > 100 ) {
                scrollElementWrap.addClass("active");
            } else {
                scrollElementWrap.removeClass("active");
            }

            if( scrollValue < 96 ) {
                $("#scroll-percentage-value").text(`${scrollValue}%`);
            } else {
                $("#scroll-percentage-value").html('<i class="fa-sharp fa-regular fa-arrow-up-long"></i>');
            }
        }
        window.onscroll = scrollPercentage;
        window.onload = scrollPercentage;

        // Back to Top
        function scrollToTop() {
            document.documentElement.scrollTo({
                top: 0,
                behavior: "smooth"
            });
        }
        
        $("#scroll-percentage").on("click", scrollToTop);
    }

    scrollTopPercentage();

    /* Portfolio Isotope  */
	$('.image_load').imagesLoaded(function() {

		if ($.fn.isotope) {

			var $portfolio = $('.image_load');

			$portfolio.isotope({

				itemSelector: '.grid-item',

				filter: '*',

				resizesContainer: true,

				layoutMode: 'masonry',

				transitionDuration: '0.8s'

			});
			$('.si__project__five__menu li').on('click', function() {

				$('.si__project__five__menu li').removeClass('active');

				$(this).addClass('active');

				var selector = $(this).attr('data-filter');

				$portfolio.isotope({

					filter: selector,

				});
			});
		};
	});



})(jQuery);

$(function() {
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    // isLocal: false
  });
});


function showToast(message, type = "success") {
    let background =
        type === "success"
            ? "linear-gradient(to right, #00b09b, #96c93d)" // green for success
            : "linear-gradient(to right, #ff5f6d, #ffc371)"; // red/orange for error

    Toastify({
        text: message,
        duration: 4000,
        gravity: "top",
        position: "right",
        backgroundColor: background,
    }).showToast();
}

function calculateBookingDays(checkIn, checkOut, checkOutTime) {
  const start = new Date(checkIn);
  const end = new Date(checkOut);

  if (end <= start) {
      return 0;
  }

  // Difference in calendar dates only
  const startDate = new Date(start.getFullYear(), start.getMonth(), start.getDate());
  const endDate   = new Date(end.getFullYear(), end.getMonth(), end.getDate());

  let days = Math.round((endDate - startDate) / (1000 * 60 * 60 * 24));

  // Checkout after branch checkout time?
  const checkoutMinutes = end.getHours() * 60 + end.getMinutes();
  const branchCheckout = checkOutTime.split(':').map(Number);
  const branchCheckoutMinutes = branchCheckout[0] * 60 + branchCheckout[1];

  if (checkoutMinutes > branchCheckoutMinutes) {
      days++;
  }

  return Math.max(1, days);
}