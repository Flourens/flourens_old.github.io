	$(document).ready(function () {
		
		$('.point-2').click(function (){
			$('.bubble-1').addClass('buble-on');
			$(this).addClass('point-animation');
		});
		$('.point-1').click(function () {
			$('.bubble-2').addClass('buble-on');
			$(this).addClass('point-animation');
		});
		$('.point-3').click(function () {
			$('.bubble-3').addClass('buble-on');
			$(this).addClass('point-animation');
		});
		
		$('.point').click(function () {
			$('.logo-animation').css("background-image", "none");
			$('.point').addClass('point-on');
			$('.options').addClass('options-pt');
		});
		
	});

	window.sr = ScrollReveal();
	sr.reveal('.reveal', {
		duration: 2000
	});


$(document).scroll(function() {
  var y = $(window).scrollTop() + $(window).height();
  if (y > $('.header').position().top + 200) {
    $('.options').css('display', 'block');
  }
});