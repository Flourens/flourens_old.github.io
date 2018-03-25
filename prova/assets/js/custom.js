
	// Rollover submenus on desktop
function addActive(idToActivate) {
	document.getElementById(idToActivate).classList.add('active');
}

function removeActive(idToDeactivate) {
	document.getElementById(idToDeactivate).classList.remove('active');
}

var mobileMenuDisplayed = false;

function openMobileMenu(buttonId, menuId) {
	if (mobileMenuDisplayed === false) {
		document.getElementById(buttonId).classList.add('open');
		document.getElementById(menuId).classList.add('open');
		document.getElementsByTagName('BODY')[0].classList.add('mobilemenu-open');
		document.getElementsByTagName('HTML')[0].classList.add('mobilemenu-open');
		mobileMenuDisplayed = true;
	} else {
		document.getElementById(buttonId).classList.remove('open');
		document.getElementById(menuId).classList.remove('open');
		document.getElementsByTagName('BODY')[0].classList.remove('mobilemenu-open');
		document.getElementsByTagName('HTML')[0].classList.remove('mobilemenu-open');
		mobileMenuDisplayed = false;
	}
}


if (document.getElementById('runtime-main')) {
	var flkty = new Flickity('.quickstart-carousel-nav', {
		pageDots: false,
		prevNextButtons: false,
		contain: true,
		asNavFor: '.quickstart-carousel'
	});

	var flkty2 = new Flickity('.quickstart-carousel', {
		pageDots: false,
		prevNextButtons: false,
		wrapAround: true,
		imagesLoaded: true,
		draggable: false
	});

	var flkty3 = new Flickity('.features-carousel-nav', {
		pageDots: false,
		prevNextButtons: false,
		contain: true,
		asNavFor: '.features-carousel'
	});

	var flkty4 = new Flickity('.features-carousel', {
		pageDots: false,
		prevNextButtons: false,
		wrapAround: true,
		imagesLoaded: true
	});
}

if (document.getElementById('enterprise-main')) {
	var flkty5 = new Flickity('.benefits-carousel', {
		watchCSS: true,
		pageDots: true,
		prevNextButtons: false,
		wrapAround: true,
		imagesLoaded: true
	});

	var flkty6 = new Flickity('.customers-carousel', {
		pageDots: false,
		prevNextButtons: true,
		wrapAround: true,
		imagesLoaded: true
	});

	var utils = window.fizzyUIUtils;

	// elements
	var cellsButtonGroup = document.querySelector('.carroussel-buttons');
	var cellsButtons = utils.makeArray(cellsButtonGroup.children);

	// update buttons on select
	flkty6.on('select', function() {
		var previousSelectedButton = cellsButtonGroup.querySelector('.is-selected');
		var selectedButton = cellsButtonGroup.children[flkty6.selectedIndex];
		previousSelectedButton.classList.remove('is-selected');
		selectedButton.classList.add('is-selected');
	});

	// cell select
	cellsButtonGroup.addEventListener('click', function(event) {
		if (!matchesSelector(event.target, '.button') && !matchesSelector(event.target, '.brand')) {
			return;
		}

		if (matchesSelector(event.target, '.brand')) {
			var index = event.target.getAttribute("data-button") - 1;
		} else {
			var index = cellsButtons.indexOf(event.target);
		}
		
		flkty6.select(index);
	});

	var flkty7 = new Flickity('.install-carousel', {
		pageDots: true,
		prevNextButtons: true,
		wrapAround: true,
		imagesLoaded: true
	});
}

if (document.getElementById('monitoring-main')) {
	var flkty8 = new Flickity('.monitor-nav-carousel', {
		pageDots: true,
		prevNextButtons: false,
		contain: true,
		asNavFor: '.monitor-carousel'
	});

	var flkty9 = new Flickity('.monitor-carousel', {
		pageDots: false,
		prevNextButtons: false,
		wrapAround: true,
		imagesLoaded: true
	});

	var flkty10 = new Flickity('.debug-nav-carousel', {
		pageDots: true,
		prevNextButtons: false,
		contain: true,
		asNavFor: '.debug-carousel'
	});

	var flkty11 = new Flickity('.debug-carousel', {
		pageDots: false,
		prevNextButtons: false,
		wrapAround: true,
		imagesLoaded: true
	});

	var flkty12 = new Flickity('.extra-feature-nav-carousel', {
		pageDots: true,
		prevNextButtons: false,
		contain: true,
		asNavFor: '.extra-feature-carousel'
	});

	var flkty13 = new Flickity('.extra-feature-carousel', {
		pageDots: false,
		prevNextButtons: false,
		wrapAround: true,
		imagesLoaded: true
	});
}
	
jQuery(document).ready(function($) {

			var sum1 = 0;
			var sum2 = 0;
			var sum3 = 0;
			var sum4 = 0;
			var sum5 = 0;
			var sum6 = 0;
			var sum7 = 0;
			var sum8 = 0;
			var sum9 = 0;
			var sum10 = 0;
			var sum11 = 0;
			var sum12 = 0;
	
			$("#form1 .cell-check").on("click", function(){
			if($(this).is(":checked")) {
				sum1 = $(this).val();
				$("#form1 .input-score").val(sum1);
			}
			})
			
			$("#form2 .cell-check").on("click", function(){
			if($(this).is(":checked")) {
				sum2 = $(this).val();
				$("#form2 .input-score").val(sum2);
			}
			})
	
			$("#form3 .cell-check").on("click", function(){
			if($(this).is(":checked")) {
				sum3 = $(this).val();
				$("#form3 .input-score").val(sum3);
			}
			})
	
		$("#form4 .cell-check").on("click", function(){
			if($(this).is(":checked")) {
				sum4 = $(this).val();
				$("#form4 .input-score").val(sum4);
			}
			})
	
		$("#form5 .cell-check").on("click", function(){
			if($(this).is(":checked")) {
				sum5 = $(this).val();
				$("#form5 .input-score").val(sum5);
			}
			})
	
	$("#form6 .cell-check").on("click", function(){
			if($(this).is(":checked")) {
				sum6 = $(this).val();
				$("#form6 .input-score").val(sum6);
			}
			})
	
	$("#form7 .cell-check").on("click", function(){
			if($(this).is(":checked")) {
				sum7 = $(this).val();
				$("#form7 .input-score").val(sum7);
			}
			})
	
	$("#form8 .cell-check").on("click", function(){
			if($(this).is(":checked")) {
				sum8 = $(this).val();
				$("#form8 .input-score").val(sum8);
			}
			})
	
	$("#form9 .cell-check").on("click", function(){
			if($(this).is(":checked")) {
				sum9 = $(this).val();
				$("#form9 .input-score").val(sum9);
			}
			})
	
	$("#form10 .cell-check").on("click", function(){
			if($(this).is(":checked")) {
				sum10 = $(this).val();
				$("#form10 .input-score").val(sum10);
			}
			})
	
	$("#form11 .cell-check").on("click", function(){
			if($(this).is(":checked")) {
				sum11 = $(this).val();
				$("#form11 .input-score").val(sum11);
			}
			})
	
	$("#form12 .cell-check").on("click", function(){
			if($(this).is(":checked")) {
				sum12 = $(this).val();
				$("#form12 .input-score").val(sum12);
			}
			})
	
	
	
		$(".cell-check").on("click", function() {
    var sum = 0;
    $(".input-score").each(function(){
        sum += +$(this).val();
				console.log(sum)
    });
    $(".total-score-value").val(sum);
	
});

});


