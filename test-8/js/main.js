	$(document).ready(function(){
		//iterate through each textboxes and add keyup
		//handler to trigger sum event
		
		$(".value-1, .value-2, .value-3").each(function() {
			$(this).keyup(function(){
				calculateSum();
			});
		});

	});

	function calculateSum() {

		var sum1 = 0;
		var sum2 = 0;
		var sum3 = 0;
		var sum4 = 0;
		//iterate through each textboxes and add the values
		$(".value-1").each(function() {

			//add only if the value is number
			if(!isNaN(this.value) && this.value.length!=0) {
				sum1 += parseFloat(this.value);
			}

		});
		$(".value-2").each(function() {

			//add only if the value is number
			if(!isNaN(this.value) && this.value.length!=0) {
				sum2 += parseFloat(this.value);
			}

		});
		$(".value-3").each(function() {

			//add only if the value is number
			if(!isNaN(this.value) && this.value.length!=0) {
				sum3 += parseFloat(this.value);
			}

		});
		$(".value-1, .value-2, .value-3").each(function() {
			//add only if the value is number
			if(!isNaN(this.value) && this.value.length!=0) {
				sum4 += parseFloat(this.value);
			}

		});
		//.toFixed() method will roundoff the final sum to 2 decimal places
		$("#cbox-1").html(sum1.toFixed(1));
		$("#cbox-2").html(sum2.toFixed(1));
		$("#cbox-3").html(sum3.toFixed(1));
		$("#sum").html(sum4.toFixed(1));
	}

window.sr = ScrollReveal();
sr.reveal('.reveal', {
    duration: 2000
});
