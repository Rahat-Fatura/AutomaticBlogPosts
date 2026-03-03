(function($) {
	"use strict";
	
	gsap.utils.toArray(".compare-images__comparisonSection").forEach(section => {
	let tl = gsap.timeline({
			scrollTrigger: {
				trigger: section,
				start: "center center",
				end: () => "+=" + section.offsetWidth, 
				scrub: true,
				pin: true,
        anticipatePin: 1
			},
			defaults: {ease: "none"}
		});
	tl.fromTo(section.querySelector(".compare-images__afterImage"), { xPercent: 100, x: 0}, {xPercent: 0})
	  .fromTo(section.querySelector(".compare-images__afterImage img"), {xPercent: -100, x: 0}, {xPercent: 0}, 0);
	});
        
})(jQuery);