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
	
	function scrollNav() {
  $('.nav a, a.btn, .footer__menu a').click(function(){  
    //Toggle Class
    $(".active").removeClass("active");      
    $(this).addClass("active");
    var theClass = $(this).attr("class");
    $('.'+theClass).parent('li').addClass('active');
    //Animate
    $('html, body').stop().animate({
        scrollTop: $( $(this).attr('href') ).offset().top - 60
    }, 400);
    return false;
  });
		
  $('.scrollTop a').scrollTop();
	}
	scrollNav();
	
});


