$(document).ready(function(){
	$(window).bind('dragover', dragover);
		$(window).bind('drop', drop);
		$('.overlay').bind('dragleave', dragleave);
		var tid;

		function dragover(event) {
				clearTimeout(tid);
				event.stopPropagation();
				event.preventDefault();
				$('.overlay').css('display', 'block');

		}

		function dragleave(event) {
				tid = setTimeout(function(){
				event.stopPropagation();
				$('.overlay').css('display', 'none');
				}, 100);
		}

		function drop(event) {
				readfiles(event.originalEvent.dataTransfer.files);
				event.stopPropagation();
				event.preventDefault();
				$('.overlay').css('display', 'none');
		}
	
	
	
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
		$('.overlay').css('display', 'block');
  });	
	$(".overlay").on("click", function(){
		$('.overlay').css('display', 'none');
  }); 
	
	
  function openSidepage() {
		$('#slide').addClass("slide");
		$('.right').addClass("right-slide");
		var winwidth = $(window).width() - 10;
		if ($(window).width() > 1200) {
			$('.aboutright').css('width', 985);
			$('.mainpage').animate({
				right: 968
			}, 400, 'easeOutBack'); 
		} else {
			$('.aboutright').css('width', $(window).width());
			$('.mainpage').animate({
				right: $(window).width()
			}, 400, 'easeOutBack'); 
		}
	}
  
  function closeSidepage(){
    $(".navigation li a").removeClass("open");
		$("#slide").removeClass("slide");
		$('.right').removeClass("right-slide");
    $('.mainpage').animate({
      right: '0px'
    }, 400, 'easeOutQuint');  
  }
	
	
//	$('.mainpage, .aboutright').equalHeights();
//	window.addEventListener('resize', function () {
//		$('.mainpage, .aboutright').equalHeights();
//	})
});