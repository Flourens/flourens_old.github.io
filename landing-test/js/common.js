$( document ).ready(function() {
	
	$(function(){
		var headertopoption = $(window);
		var headTop = $('.header-navbar');
		headertopoption.on('scroll', function () {
		if (headertopoption.scrollTop() > 20) {
			headTop.addClass('header-navbar__scrolled');
			$(".header-navbar-image").attr("src", "img/invoicemap-logo-color.png")
		} else {
				headTop.removeClass('header-navbar__scrolled');
				$(".header-navbar-image").attr("src", "img/invoicemap-logo-white.png")
			}
		
		});
		
	});
	
});


