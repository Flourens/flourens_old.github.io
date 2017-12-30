$(document).ready(function () {
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
        tid = setTimeout(function () {
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


    $(".navigation li a, .slide-button").on("click", function (e) {
        e.preventDefault();
        var hrefval = $(this).attr("href");
        if (hrefval == ".about") {
            var distance = $('.mainpage').css('right');

            if (distance == "auto" || distance == "0px") {
                $(this).addClass("open");
                openSidepage();
            } else {
                closeSidepage();
            }
        }
    }); // end click event handler

    $(".closebtn").on("click", function (e) {
        e.preventDefault();
        closeSidepage();
    }); // end close button event handler

    $(".upload-btn").on("click", function () {
        $('.overlay').css('display', 'block');
        $('#file-input').trigger('click');
    });

    $("#file-input").on("change", function (e) {
        readfiles(this.files);
    });

    $(".overlay").on("click", function () {
        $('.overlay').css('display', 'none');
    });


    function openSidepage() {
        $('#slide').addClass("slide");
        $('.right').addClass("right-slide");
        var winwidth = $(window).width() - 10;
        if ($(window).width() > 968) {
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

    function closeSidepage() {
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
$(window).resize(function () {
    if ($(window).width() > 985) {
        $('.aboutright').css('width', 985);
    } else {
        $('.aboutright').css('width', $(window).width());

    }
});

function readfiles(files) {
    var options = {
        maxFilesize: 20, // MB
        acceptedFiles: 'image/,application/pdf',
    }
    var theFile = files[0];
    if (theFile.size <= (options.maxFilesize * 1024 * 1024)) {
        var afAry = options.acceptedFiles.split(',');
        var found = false;
        for (var i = 0; i < afAry.length; i++) {
            if (theFile.type.substr(0, afAry[i].length) === afAry[i]) {
                found = true;
            }
        }
        if (found) {
            var formImage = new FormData();
            formImage.append('file', theFile);
            $.ajax({
                url: "/upload",
                type: "POST",
                data: formImage,
                contentType: false,
                cache: false,
                processData: false,
                success: function (resp) {
                    var rUrl = window.location.protocol + '//' + window.location.host + '/' + resp.hash;
                    window.location.href = rUrl;
                },
            });
        }
    }
}
