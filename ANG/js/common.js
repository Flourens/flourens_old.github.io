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
	
		$(document).ready(function() {
    // Configure/customize these variables.
    var showChar = 350;  // How many characters are shown by default
    var ellipsestext = "...";
    var moretext = "(read more)";
    var lesstext = "(show less)";
    

    $('.more').each(function() {
        var content = $(this).html();
 
        if(content.length > showChar) {
 
            var c = content.substr(0, showChar);
            var h = content.substr(showChar, content.length - showChar);
 
            var html = c + '<span class="moreellipses">' + ellipsestext+ '&nbsp;</span><span class="morecontent"><span>' + h + '</span>&nbsp;&nbsp;<a href="" class="morelink">' + moretext + '</a></span>';
 
            $(this).html(html);
        }
 
    });
 
    $(".morelink").click(function(){
        if($(this).hasClass("less")) {
            $(this).removeClass("less");
            $(this).html(moretext);
        } else {
            $(this).addClass("less");
            $(this).html(lesstext);
        }
        $(this).parent().prev().toggle();
        $(this).prev().toggle();
        return false;
    });
});
	// ------- end click event handler ----
		
});