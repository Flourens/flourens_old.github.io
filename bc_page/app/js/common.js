	$(document).ready(function () {

		$('.owl-carousel').owlCarousel({
			loop: true,
			items: 5,
			nav: true,
			dots: false,
			responsive: {
				0: {
					items: 1
				},
				600: {
					items: 3
				},
				1000: {
					items: 5
				}
			}
		});

		(function (b, i, t, C, O, I, N) {
			window.addEventListener('load', function () {
				if (b.getElementById(C)) return;
				I = b.createElement(i), N = b.getElementsByTagName(i)[0];
				I.src = t;
				I.id = C;
				N.parentNode.insertBefore(I, N);
			}, false)
		})(document, 'script', 'https://widgets.bitcoin.com/widget.js', 'btcwdgt');


		$(function () {
			createSticky($(".nav-wrap"));
		});

		function createSticky(sticky) {
			if (typeof sticky !== "undefined") {
				var pos = sticky.offset().top,
					win = $(window),
					section = $(".s-header"),
					navHeight = $(".navigation").outerHeight();
				win.on("scroll", function () {

					if (win.scrollTop() >= pos) {
						sticky.addClass("fixed-top");
						section.css('margin-top', navHeight);
					} else {
						sticky.removeClass("fixed-top");
						section.css('margin-top', '0');
					}
				});
			}
		}
		
	});

