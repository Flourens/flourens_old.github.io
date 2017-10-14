$(document).ready(function(){
  $(".navigation li a, .slide-button").on("click", function(e){
    e.preventDefault();
    var hrefval = $(this).attr("href");
    
    if(hrefval == ".about") {
      var distance = $('.mainpage').css('right');
      
      if(distance == "auto" || distance == "0px") {
        $(this).addClass("open");
        openSidepage();
      } else {
        closeSidepage();
      }
    }
  }); // end click event handler
  
  $(".closebtn").on("click", function(e){
    e.preventDefault();
    closeSidepage();
  }); // end close button event handler
	
	$(".upload-btn").on("click", function(){
		$('.overlay').addClass("overlay-on");
  });	
	$(".overlay").on("click", function(){
		$('.overlay').removeClass("overlay-on");
  }); 
	
  function openSidepage() {
		$('#slide').addClass("slide");
		$('.right').addClass("right-slide");
    $('.mainpage').animate({
      right: '985px'
    }, 400, 'easeOutBack'); 
  }
  
  function closeSidepage(){
    $(".navigation li a").removeClass("open");
		$("#slide").removeClass("slide");
		$('.right').removeClass("right-slide");
    $('.mainpage').animate({
      right: '0px'
    }, 400, 'easeOutQuint');  
  }
	
	
	$('.mainpage, .aboutright').equalHeights();
	window.addEventListener('resize', function () {
		$('.mainpage, .aboutright').equalHeights();
	})
});