$( document ).ready(function() {
                     
		$(".hamburger").click(function() { 
			$(this).toggleClass("is-active");
			$(".mobile-menu").toggleClass("active");
		});
	
		$(".mobile-menu").click(function() { 
			$(".hamburger").toggleClass("is-active");
			$(this).toggleClass("active");
		});
	
		$(".load-more").click(function() { 
			$(".story-block").removeClass("hiden-story");
		});
	
		var owl = $('.owl-carousel');
		owl.owlCarousel({
				items:1,
				loop:true,
				margin:200,
				autoplay:true,
				autoplayTimeout:10000,
				autoplayHoverPause:true
		});
	
		var line = $('.line-top');
		
});

