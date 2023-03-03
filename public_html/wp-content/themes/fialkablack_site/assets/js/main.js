$(document).ready(function() {


	//  preloader
	// $(window).on('load', function () {
	// 	var $preloader = $('#page-preloader'),
 //    	$spinner   = $preloader.find('.spinner');
	// 	$spinner.fadeOut();
	// 	$preloader.delay(350).fadeOut('slow');
	// });


	// wow = new WOW(
 //      {
 //      boxClass:     'wow',      // default
 //      animateClass: 'animated', // default
 //      offset:       0,          // default
 //      mobile:       false,       // default
 //      live:         true        // default
 //    }
 //    )
 //    wow.init();



	// svg sprite all browser
	svg4everybody();


	//  header top menu
	$('.burger_button').click(function() {
		$('.menu_toggle').slideToggle("fast");
		$('.burger_button').toggleClass('open');
		$('body').toggleClass('stop-scrolling');
		return false;
	});
	$('body').on('click', '.menu_toggle a', function(event) {
		$('.menu_toggle').slideUp();
		$('.burger_button').removeClass('open');
		$('body').toggleClass('stop-scrolling');
	});


	//  filter slider
	$(window).on('load resize', function () {
       if ($(window).width() <=576) {
           $('.filter form').slick({
               	infinite: false,
               	autoSlidesToShow: true,
               	variableWidth: true,
               	dots: false,
               	fade: false,
               	arrows: false,
               	autoplay: false,
               	pauseOnFocus: false,
               	speed: 100,
           });
       } 
   	});


	// Phone mask
	$(function(){
	   $(".phone-mask").mask("+7(999) 999-99-99");
	});


	// faq
	$(".faq_tabs .title").on("click", function() {
		$(this).toggleClass('active');
  		$(this).next('.tab_content').slideToggle(100);
	});



	//  slider
	// $('.header_slick').slick({
	// 	slidesToShow: 1,
	// 	dots: false,
	// 	fade: false,
	// 	arrows: false,
	// 	autoplay: false,
	// 	// adaptiveHeight: true,
	// 	autoplaySpeed: 3000,
	// 	pauseOnFocus: false,
 //        pauseOnHover: false,
 //        pauseOnDotsHover: false,
 //        prevArrow: '<div class="slick-prev"></div>',
 //        nextArrow: '<div class="slick-next"></div>',
 //        responsive: [
 //    		{
 //    			breakpoint: 768,
 //    			settings: {
    				
 //    			}
 //    		},
 //    		{		
 //    			breakpoint: 576,
 //    			settings: {
    				
 //    			}
 //    		}	
 //    	]
	// });



	// //SMOOTH SCROLL TO ID
	$(document).on('click', '.sm-scroll a[href^="#"]', function(event){
		event.preventDefault();
		var body_width = $(window).width();
        $('html, body').animate({
		scrollTop: $( $.attr(this, 'href') ).offset().top -10
		}, 500);
	});







});


