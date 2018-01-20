	$(document).ready(function () {
		
		$('.point-2').click(function (){
			$('.bubble-1').addClass('buble-on');
		});
		$('.point-1').click(function () {
			$('.bubble-2').addClass('buble-on')});
		$('.point-3').click(function () {
			$('.bubble-3').addClass('buble-on')});
		
	});


	window.sr = ScrollReveal();
	sr.reveal('.reveal', {
		duration: 2000
	});