$(document).ready(function () {
	$(function () {
		$("#slider-range").slider({
			range: true,
			min: 1,
			max: 999,
			classes: {
				"ui-slider": "new-slider",
				"ui-slider-handle": "new-handle",
  			"ui-slider-range": "new-range"
			},
			values: [1, 999],
			slide: function (event, ui) {
				$("#amount").val(ui.values[0] + "pt" + "      " + ui.values[1] + "pt");
				$(".target-value-min-1").text(ui.values[0] + "pt");
				$(".target-value-max-1").text(ui.values[1] + "pt");
			}
		});
		$("#amount").val($("#slider-range").slider("values", 0) + "pt      " + $("#slider-range").slider("values", 1) + "pt");
		$(".target-value-min-1").text($("#slider-range").slider("values", 0) + "pt");
		$(".target-value-max-1").text($("#slider-range").slider("values", 1) + "pt");
	});
	
	$(function () {
		$("#slider-range-2").slider({
			range: true,
			min: 1,
			max: 999,
			classes: {
				"ui-slider": "new-slider",
				"ui-slider-handle": "new-handle",
  			"ui-slider-range": "new-range"
			},
			values: [1, 999],
			slide: function (event, ui) {
				$("#amount-2").val(ui.values[0] + "stuk (s)" + "      " + ui.values[1] + "stuk (s)");
				$(".target-value-min-2").text(ui.values[0] + "stuk (s)");
				$(".target-value-max-2").text(ui.values[1] + "stuk (s)");
			}
		});
		$("#amount-2").val($("#slider-range-2").slider("values", 0) + "stuk (s)      " + $("#slider-range").slider("values", 1) + "stuk (s)");
		$(".target-value-min-2").text($("#slider-range-2").slider("values", 0) + "stuk (s)");
		$(".target-value-max-2").text($("#slider-range-2").slider("values", 1) + "stuk (s)");
	});
	
	$(function() {
		$(".color-selector__item").click(function() {
			$(".color-active").removeClass("color-active");
			$(this).addClass("color-active");
		});
	});
	
	$(function() {
		$(".link-to-page-1").click(function() {
			$(".section").removeClass("enabled");
			$(".s-stap-1").addClass("enabled");
		});
	})
	$(function() {
		$(".link-to-page-2").click(function() {
			$(".section").removeClass("enabled");
			$(".s-stap-2").addClass("enabled");
		});
	});
	$(function() {
		$(".link-to-page-3").click(function() {
			$(".section").removeClass("enabled");
			$(".s-stap-3").addClass("enabled");
		});
	});
	$(function() {
		$(".link-to-page-4").click(function() {
			$(".section").removeClass("enabled");
			$(".s-stap-4").addClass("enabled");
		});
	});
	
});