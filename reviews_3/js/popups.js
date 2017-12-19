$(function() {                       //run when the DOM is ready
  $(".close-btn, .button-nein").click(function() {  //use a class, since your ID gets mangled
    $(".rewiev-popup").fadeOut( "slow", function() {
    	$(".rewiev-popup").addClass("rewiev-popup-disabled");
  	});
  });
});