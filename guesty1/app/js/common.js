$(document).ready(function(){
	
	// SLick Slider
	
  $('.team').slick({
    slidesToShow: 5,
		slidesToScroll: 1,
		autoplay: true,
		autoplaySpeed: 2000,
		infinite: true,
		arrows: true,
		prevArrow: $('.prev'),
		nextArrow: $('.next'),
		responsive: [
    {
      breakpoint: 1440,
      settings: {
        slidesToShow: 4
      }
    },
    {
      breakpoint: 768,
      settings: {
        slidesToShow: 2
      }
    },
			{
      breakpoint: 576,
      settings: {
        slidesToShow: 1
      }
    }
  ]
  });
	
});

