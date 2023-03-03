$(document).ready(function() {

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


	//  woo phone mask
	$(function(){
	   $('#billing_phone').mask("+7(999) 999-99-99");
	});



	// faq
	$(".faq_tabs .title").on("click", function() {
		$(this).toggleClass('active');
  		$(this).next('.tab_content').slideToggle(100);
	});



	$(document).ready(function(){ 
		if (window.location.href.indexOf("?link_thanks") > -1) { 
		 	$.fancybox.open({
			    src: '#modal_thanks', 
			});
			window.history.replaceState(null, null, window.location.pathname);
		} 
	});
	$(document).ready(function(){ 
		if (window.location.href.indexOf("?link_ok") > -1) { 
		 	$.fancybox.open({
			    src: '#modal_ok', 
			});
			window.history.replaceState(null, null, window.location.pathname);
		} 
	});


	// //SMOOTH SCROLL TO ID
	$(document).on('click', '.sm-scroll a[href^="#"]', function(event){
		event.preventDefault();
		var body_width = $(window).width();
        $('html, body').animate({
		scrollTop: $( $.attr(this, 'href') ).offset().top -10
		}, 500);
	});


	//  load more btn
	var items = $('#response .product__item'),
	per = 4, //display default
	i = 1,
	total = 0;
	$('.btn-more').on('click', function(){
	 	total = per * (i++);
	 	items.slice(0, total).fadeIn( "slow" ).css({
	  		'display' : 'flex'
	 });
	$(this)[total >= items.length ? 'hide' : 'show']();
	}).click();


	$(function($){
		$('#filter input').click(function(){
			$('#filter input').not(this).each(function(){
		        $(this).removeClass('active');
		    });
		    $(this).addClass('active');

			var filter = $('#filter');
			$.ajax({
				url:filter.attr('action'),
				data:filter.serialize(), // form data
				type:filter.attr('method'), // POST
				beforeSend:function(xhr){
					// $('#filter').css({
					// 	'opacity': '.5'
					// })
					$("#filter input").prop('disabled', true);
				},
				success:function(data){
					$("#filter input").prop('disabled', false);
					// $('#filter').css({
					// 	'opacity': '1'
					// })
					$('#response').html(data); // insert data

					//  load more btn
					var items = $('#response .product__item'),
					per = 4, //display default
					i = 1,
					total = 0;
					$('.btn-more').on('click', function(){
					 	total = per * (i++);
					 	items.slice(0, total).fadeIn( "slow" ).css({
					  		'display' : 'flex'
					 });
					$(this)[total >= items.length ? 'hide' : 'show']();
					}).click();

				},
				complete: function(data) {
					$("#filter input").prop('disabled', false);
	 		 		// $('#filter').css({
	 		 		// 	'opacity': '1'
	 		 		// })
	 		 	}
			});
			return false;
		});
	});




});


