jQuery(document).ready(function($) { 


	// mobile menu opener
	$('.nav-opener').each(function(){
		var $this = $(this);

		$this.on('close', function() {
			$('.nav-mobile').removeClass('open');
			$('body').removeClass('nav-mobile-open');
			return $('.nav-opener').removeClass('open');
		});

		$this.on('open', function() {
			$('.nav-mobile').addClass('open');
			$('body').addClass('nav-mobile-open');
			return $('.nav-opener').addClass('open');
		});
		
		$this.click(function(e){
			e.preventDefault();

			if ($this.hasClass('open')){
				$(this).triggerHandler('close');
			} else {
				$(this).triggerHandler('open');
			}
		});

	});
	

	// multiselect
	$('.multiselect').multiSelect({ 
		keepOrder: true
	});


	// custom select
	$('select.custom-select').each(function() {
		var $this = $(this);

		if ($this.val() === 'placeholder') {
			$this.addClass('state-placeholder');
		} else {
			$this.removeClass('state-placeholder');
		}

		$this.change(function(e) {
			if ($this.val() === 'placeholder') {
				$this.addClass('state-placeholder');
			} else {
				$this.removeClass('state-placeholder');
			}
		});

	});


	// popup
	if ($().magnificPopup) {
		$('.popup-opener').magnificPopup({
			preloader: false,
			closeBtnInside: true,
			midClick: true,
			closeMarkup: '<div class="popup-close mfp-close">&times;</div>'
		});
	}


});
