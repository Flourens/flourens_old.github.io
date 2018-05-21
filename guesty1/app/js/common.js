$(document).ready(function(){
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
      breakpoint: 768,
      settings: {
        centerMode: true,
        slidesToShow: 3
      }
    },
    {
      breakpoint: 480,
      settings: {
        arrows: false,
        centerMode: true,
        centerPadding: '40px',
        slidesToShow: 1
      }
    }
  ]
		
		
  });
});

var $grid = $('.grid').masonry({
  itemSelector: '.grid-item',
  percentPosition: true,
  columnWidth: '.grid-sizer',
	gutter: 10
});
// layout Masonry after each image loads
$grid.imagesLoaded().progress( function() {
  $grid.masonry();
});  