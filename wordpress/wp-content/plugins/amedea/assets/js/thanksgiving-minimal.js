(function($) {
	"use strict";

	$("h1").lettering();
	setup();

	$("#thanksgiving-css__replay").click(function () {
		refresh();
		setup();
	});

	$("#thanksgiving-css__color-toggle").click(function () {
		$(".thanksgiving--css__minimal").toggleClass("dark-mode");
	});

	$("#thanksgiving-css__turkey > *, #thanksgiving-css__leaf > *:not(.thanksgiving-css__dot), #thanksgiving-css__rays > *").each(function () {
		var length_stroke = $(this).get(0).getTotalLength();
		$(this).css({
			"stroke-dasharray": length_stroke + " " + length_stroke,
			"stroke-dashoffset": length_stroke
		});
	});

	$.fn.parallax = function (resistance, mouse) {
		TweenLite.to('#thanksgiving-css__minimal-container', 0.2, {
			x: -((mouse.clientX - window.innerWidth / 2) / resistance),
			y: -((mouse.clientY - window.innerHeight / 2) / resistance)
		});
	};

	function refresh() {
		$("#thanksgiving-css__sun-container").removeClass("thanksgiving-css__sun-animation");
		$("#thanksgiving-css__rays-container").removeClass("thanksgiving-css__ray-animation");
		$("#thanksgiving-css__leaf-container").removeClass("thanksgiving-css__leaf-animation");
		$("#thanksgiving-css__turkey > *, #thanksgiving-css__leaf > *:not(.thanksgiving-css__dot)").removeClass("thanksgiving-css__stroke-animation");
		$("#thanksgiving-css__rays > *").removeClass("thanksgiving-css__ray-stroke-animation");
		$("#thanksgiving-css__leaf > .thanksgiving-css__dot").removeClass("thanksgiving-css__leaf-dot-animation");
		$("#thanksgiving-css__leaf-container span").removeClass("thanksgiving-css__leaf-shadow-animation");
		$("#thanksgiving-css__text-container thanksgiving-css__h2").removeClass("thanksgiving-css__h2-animation");
		$("#thanksgiving-css__text-container thanksgiving-css__h1 span").removeClass("thanksgiving-css__h1-animation");
	}

	function animations() {
		$("#thanksgiving-css__sun-container").addClass("thanksgiving-css__sun-animation");
		$("#thanksgiving-css__rays-container").addClass("thanksgiving-css__ray-animation");
		$("#thanksgiving-css__leaf-container").addClass("thanksgiving-css__leaf-animation");
		$("#thanksgiving-css__turkey > *, #thanksgiving-css__leaf > *:not(.thanksgiving-css__dot)").addClass("thanksgiving-css__stroke-animation");
		$("#thanksgiving-css__rays > *").addClass("thanksgiving-css__ray-stroke-animation");
		$("#thanksgiving-css__leaf > .thanksgiving-css__dot").addClass("thanksgiving-css__leaf-dot-animation");
		$("#thanksgiving-css__leaf-container span").addClass("thanksgiving-css__leaf-shadow-animation");
		$("#thanksgiving-css__text-container .thanksgiving-css__h2").addClass("thanksgiving-css__h2-animation");
		$("#thanksgiving-css__text-container .thanksgiving-css__h1 span").addClass("thanksgiving-css__h1-animation");
	}

	function setup() {
		$("#thanksgiving-css__minimal-container").hide(0).fadeIn(800);

		animations();

		setTimeout(function () {
			$(document).mousemove(function (e) {
				$("#thanksgiving-css__sun-container #thanksgiving-css__sun").parallax(150, e);
				$(".thanksgiving-css__h1, .thanksgiving-css__h2, #thanksgiving-css__rays-container").parallax(350, e);
				$("#thanksgiving-css__turkey-container").parallax(60, e);
				$("#thanksgiving-css__leaf-container #thanksgiving-css__leaf").parallax(200, e);
			});
		}, 2250);

		$("button#thanksgiving-css__replay").hide().delay(2500).fadeIn(800);
	}


})(jQuery);