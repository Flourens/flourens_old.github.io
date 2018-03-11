$(document).ready(function () {
		
	//--------- Click Event--------------
		
	$(".navigation").on("click", function (e) {
		e.preventDefault();
		$('.overlay').toggleClass('active');
		
	});
	
	var myIndex = 0;
		carousel();

		function carousel() {
				var i;
				var x = document.getElementsByClassName("mySlides");
				for (i = 0; i < x.length; i++) {
					 x[i].style.display = "none";  
				}
				myIndex++;
				if (myIndex > x.length) {myIndex = 1}    
				x[myIndex-1].style.display = "block";  
				setTimeout(carousel, 4000); // Change image every 2 seconds
		}

});

	$(window).scroll(function() {    
			var scroll = $(window).scrollTop();

			 //>=, not <=
			if (scroll >= 50) {
					//clearHeader, not clearheader - caps H
					$(".w3-top").addClass("w3-top-bg");
					$(".sh-top").addClass("sh-top-scrolled");
					$(".w3-bar-block").addClass("w3-bar-block-scrolled");
			} else {
				$(".w3-top").removeClass("w3-top-bg");
				$(".sh-top").removeClass("sh-top-scrolled");
				$(".w3-bar-block").removeClass("w3-bar-block-scrolled");
			}
		
		
});

