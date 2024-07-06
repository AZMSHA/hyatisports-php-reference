/**
*
* -----------------------------------------------------------------------------
*
* Template : Gimnas | Gym Responsive HTML5 Template 
* Author : rs-theme
* Author URI : http://www.rstheme.com/
*
* -----------------------------------------------------------------------------
*
**/

(function($) {
    "use strict";

    // sticky menu
    var header = $('.menu-sticky');
    var win = $(window);
    win.on('scroll', function() {
       var scroll = win.scrollTop();
       if (scroll < 30) {
           header.removeClass("sticky");
       } else {
           header.addClass("sticky");
       }
    });

	//window load
	$(window).on( 'load', function() {
		//rs menu
		if($(window).width() < 992) {
		  $('.rs-menu').css('height', '0');
		  $('.rs-menu').css('opacity', '0');
		  $('.rs-menu-toggle').on( 'click', function(){
			 $('.rs-menu').css('opacity', '1');
		 });
		}
	})


	// Smooth Scroll
    $(".onepage .nav-menu").on('click', 'a', function(e){
        var anchor = $(this);
        $('html, body').stop().animate({
            scrollTop: $(anchor.attr('href')).offset().top - 20
        }, 1000);
        e.preventDefault();
    });
		
	/*-------------------------------------
	       Home page Slider 
     -------------------------------------*/
    var owl = $('#home-slider');
        owl.owlCarousel({
          loop:true,
          margin:0,
          items:1,
          autoplay:true,
          navSpeed: 1200,
          smartSpeed: 2000, 
          dots: false,
          nav:true,
          navText: ["<i class='fa fa-angle-left'></i>", "<i class='fa fa-angle-right'></i>"],
        });
        // add animate.css class(es) to the elements to be animated
        function setAnimation ( _elem, _InOut ) {
        // Store all animationend event name in a string.
        // cf animate.css documentation
        var animationEndEvent = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';

        _elem.each ( function () {
          var $elem = $(this);
          var $animationType = 'animated ' + $elem.data( 'animation-' + _InOut );
          $elem.addClass($animationType).one(animationEndEvent, function () {
            $elem.removeClass($animationType); // remove animate.css Class at the end of the animations
          });
        });
        }

        // Fired before current slide change
        owl.on('change.owl.carousel', function(event) {
          var $currentItem = $('.owl-item', owl).eq(event.item.index);
          var $elemsToanim = $currentItem.find("[data-animation-out]");
          setAnimation ($elemsToanim, 'out');
        });


        // Fired after current slide has been changed
        owl.on('changed.owl.carousel', function(event) {
          var $currentItem = $('.owl-item', owl).eq(event.item.index);
          var $elemsToanim = $currentItem.find("[data-animation-in]");
          setAnimation ($elemsToanim, 'in');
        })

	/*-------------------------------------
    OwlCarousel
    -------------------------------------*/
	$('.rs-carousel').each(function() {
		var owlCarousel = $(this),
		loop = owlCarousel.data('loop'),
		items = owlCarousel.data('items'),
		margin = owlCarousel.data('margin'),
		stagePadding = owlCarousel.data('stage-padding'),
		autoplay = owlCarousel.data('autoplay'),
		autoplayTimeout = owlCarousel.data('autoplay-timeout'),
		smartSpeed = owlCarousel.data('smart-speed'),
		dots = owlCarousel.data('dots'),
		nav = owlCarousel.data('nav'),
		navSpeed = owlCarousel.data('nav-speed'),
		xsDevice = owlCarousel.data('mobile-device'),
		xsDeviceNav = owlCarousel.data('mobile-device-nav'),
		xsDeviceDots = owlCarousel.data('mobile-device-dots'),
		smDevice = owlCarousel.data('ipad-device'),
		smDeviceNav = owlCarousel.data('ipad-device-nav'),
		smDeviceDots = owlCarousel.data('ipad-device-dots'),
		smDevice2 = owlCarousel.data('ipad-device2'),
		smDeviceNav2 = owlCarousel.data('ipad-device-nav2'),
		smDeviceDots2 = owlCarousel.data('ipad-device-dots2'),
		mdDevice = owlCarousel.data('md-device'),
		mdDeviceNav = owlCarousel.data('md-device-nav'),
		mdDeviceDots = owlCarousel.data('md-device-dots');
		owlCarousel.owlCarousel({
			loop: (loop ? true : false),
			items: (items ? items : 4),
			lazyLoad: true,
			margin: (margin ? margin : 0),
			//stagePadding: (stagePadding ? stagePadding : 0),
			autoplay: (autoplay ? true : false),
			autoplayTimeout: (autoplayTimeout ? autoplayTimeout : 1000),
			smartSpeed: (smartSpeed ? smartSpeed : 250),
			dots: (dots ? true : false),
			nav: (nav ? true : false),
			navText: ["<i class='fa fa-angle-left'></i>", "<i class='fa fa-angle-right'></i>"],
			navSpeed: (navSpeed ? true : false),
			responsiveClass: true,
			responsive: {
				0: {
					items: (xsDevice ? xsDevice : 1),
					nav: (xsDeviceNav ? true : false),
					dots: (xsDeviceDots ? true : false)
				},
				768: {
					items: (smDevice2 ? smDevice : 2),
					nav: (smDeviceNav2 ? true : false),
					dots: (smDeviceDots2 ? true : false)
				},
				768: {
					items: (smDevice ? smDevice : 3),
					nav: (smDeviceNav ? true : false),
					dots: (smDeviceDots ? true : false)
				},
				992: {
					items: (mdDevice ? mdDevice : 4),
					nav: (mdDeviceNav ? true : false),
					dots: (mdDeviceDots ? true : false)
				}
			}
		});
	});
    
    
    $('#team-list').owlCarousel({
        loop:true,
        margin:30,
        items:3,
        autoplay:true,
        navSpeed: 1200,
        smartSpeed: 2000,
        center: true,
        dots: false,
        nav:true,
        navText: ["<i class='fa fa-angle-left'></i>", "<i class='fa fa-angle-right'></i>"],
        responsive : {
            0 : {
                 items:1,
            },
            480 : {
                 items:1,
            },
            768 : {
                 items:3,
            },
            992 : {
                 items:3,
            }
        }
    });

	// Skill bar
    var skillbar = $('.skillbar');
    if(skillbar.length){	
		$('.skillbar').skillBars({	
			from: 0,	
			speed: 4000, 	
			interval: 100,	
			decimals: 0,	
		});
	}
	

	 
	// video 
    if ($('.player').length) {
        $(".player").YTPlayer();
    }

    // wow init
    new WOW().init();
    
    // image loaded portfolio init
	var gridfilter = $('.grid');
		if(gridfilter.length){
		$('.grid').imagesLoaded(function() {
			$('.gridFilter').on('click', 'button', function() {
				var filterValue = $(this).attr('data-filter');
				$grid.isotope({
					filter: filterValue
				});
			});
			var $grid = $('.grid').isotope({
				itemSelector: '.grid-item',
				percentPosition: true,
				masonry: {
					columnWidth: '.grid-item',
				}
			});
		});
	}	
        
    // project Filter
    if ($('.gridFilter button').length) {
		var projectfiler = $('.gridFilter button');
			if(projectfiler.length){
			$('.gridFilter button').on('click', function(event) {
				$(this).siblings('.active').removeClass('active');
				$(this).addClass('active');
				event.preventDefault();
			});
		}
	}

    // image popup
	var imaggepoppup = $('.image-popup');
	if(imaggepoppup.length){
		$('.image-popup').magnificPopup({
			type: 'image',
			callbacks: {
				beforeOpen: function() {
				   this.st.image.markup = this.st.image.markup.replace('mfp-figure', 'mfp-figure animated zoomInDown');
				}
			},
			gallery: {
				enabled: true
			}
		});
	}
    
	// video popup
	var popupyoutube = $('.popup-youtube');
	if(popupyoutube.length){
		$('.popup-youtube').magnificPopup({
			disableOn: 700,
			type: 'iframe',
			mainClass: 'mfp-fade',
			removalDelay: 160,
			preloader: false,
			fixedContentPos: false								
		});	
	}
	
	/* animate filter */
	var owlAnimateFilter = function(even) {
		$(this)
		.addClass('__loading')
		.delay(70 * $(this).parent().index())
		.queue(function() {
			$(this).dequeue().removeClass('__loading')
		})
	}
	$('.btn-filter-wrap').on('click', '.btn-filter', function(e) {
		var filter_data = $(this).data('filter');
		/* return if current */
		if($(this).hasClass('btn-active')) return;

		/* active current */
		$(this).addClass('btn-active').siblings().removeClass('btn-active');

		/* Filter */
		owl.owlFilter(filter_data, function(_owl) { 
			$(_owl).find('.item').each(owlAnimateFilter); 
		});
	})
    

	/*-------------------------------------
	Preloder Js here
	---------------------------------------*/
	//preloader
	$(window).on( 'load', function() {
		$(".preloader-area").delay(2000).fadeOut(500);
		$(".box").on('click', function() {
		$(".loader8").fadeOut(500);
		})
	})

    // Counter Up
    if($('.counter-number').length){	
		$('.counter-number').counterUp({
			delay: 20,
			time: 1500
		});
	}
    // scrollTop init
    var totop = $('#scrollUp'); 
    if(totop.length){	
		win.on('scroll', function() {
			if (win.scrollTop() > 150) {
				totop.fadeIn();
			} else {
				totop.fadeOut();
			}
		});
		totop.on('click', function() {
			$("html,body").animate({
				scrollTop: 0
			}, 500)
		});
	}

	$(".form-btn .BMI-btn-calculate").on('click', function(){
		var weight = $( "#weight").val();
		if(weight != 0){
		    $(".BMI-result").show(500);
		}
	});
	
	/*----------------------------
    	single-productjs active
    ------------------------------ */
	var singleproduct = $('.single-product');
	if(singleproduct.length){
		$('.single-product').slick({
			slidesToShow: 1,
			slidesToScroll: 1,
			arrows: false,
			fade: true,
			asNavFor: '.single-product-nav'
		});
	}
	var singleproductnav = $('.single-product-nav');
	if(singleproductnav.length){
		$('.single-product-nav').slick({
			slidesToShow: 4,
			slidesToScroll: 1,
			asNavFor: '.single-product',
			dots: false,
			focusOnSelect: true,
			centerMode:true,
		});
	}
	//pagination blog
	var paginationitams = $('.pagination-itams'); 
    if(paginationitams.length){
		$('.pagination-itams').paginate({
	    	pagination_class: "pagination",
		    items_per_page  : 6,
		    prev_next       : true,
		    prev_text       : '<i class="fa fa-angle-left"></i>',
		    next_text       : '<i class="fa fa-angle-right"></i>'
		 } );
	}
	//pagination galery 
	var paginationgallery = $('.pagination-gallery'); 
    if(paginationgallery.length){
		$('.pagination-gallery').paginate({
	    	pagination_class: "pagination",
		    items_per_page  : 12,
		    prev_next       : true,
		    prev_text       : '<i class="fa fa-angle-left"></i>',
		    next_text       : '<i class="fa fa-angle-right"></i>'
		 } );
	}
	//pagination galery 
	var paginationproduct = $('.pagination-product'); 
    if(paginationproduct.length){
		$('.pagination-product').paginate({
	    	pagination_class: "pagination",
		    items_per_page  : 12,
		    prev_next       : true,
		    prev_text       : '<i class="fa fa-angle-left"></i>',
		    next_text       : '<i class="fa fa-angle-right"></i>'
		 } );
	}
	// Google Map
    if ($('#googleMap').length) {
        var initialize = function() {
            var mapOptions = {
                zoom: 10,
                scrollwheel: false,
                center: new google.maps.LatLng(40.837936, -73.412551),
                styles: [{
                    stylers: [{
                        saturation: -100
                    }]
                }]
            };
            var map = new google.maps.Map(document.getElementById("googleMap"),
                mapOptions);
            var marker = new google.maps.Marker({
                position: map.getCenter(),
                animation: google.maps.Animation.BOUNCE,
                icon: 'images/map-marker.png',
                map: map
            });
        }
        // Add the map initialize function to the window load function
        google.maps.event.addDomListener(window, "load", initialize);
    }
    
   //Calculate form
   var form = $("form");
	
	form.on("suBMIt", function(e){
		e.preventDefault();
		var resultDiv = $(".result .title");
		
		function calcBMI(){
			var weight = $("#weight").val();
	    var height = $("#height").val();
			var BMI = weight / (height * height);
			return BMI.toFixed(2);
			
		}
		function BMIState(){
			if(calcBMI() < 16.9 ){
				return "Very underweight";
			}
			if(calcBMI() > 17 && calcBMI() < 18.4 ){
				return "Under weight";
			}
			if(calcBMI() > 18.5 && calcBMI() < 24.9 ){
				return "Normal weight";
			}
			if(calcBMI() > 25 && calcBMI() < 29.9 ){
				return "Overweight";
			}
			if(calcBMI() > 30 && calcBMI() < 34.9 ){
				return "Overweight class 1";
			}
			if(calcBMI() > 35 && calcBMI() < 40 ){
				return "Overweight class 2";
			}
			if(calcBMI() > 40){
				return "Overweight class 3";
			}
		}
		resultDiv.html(calcBMI() + " > " + BMIState());
	});

})(jQuery);