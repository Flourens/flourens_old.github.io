$(document).ready(function(){
	
	// SLick Slider
	
  $('.vslider').slick({
		variableWidth: true,
		centerMode: true,
    slidesToShow: 1,
		slidesToScroll: 1,
//		autoplay: true,
		autoplaySpeed: 5000,
		infinite: true,
		arrows: true,
		prevArrow: $('.prev'),
		nextArrow: $('.next'),
		focusOnSelect: true
  });
});

