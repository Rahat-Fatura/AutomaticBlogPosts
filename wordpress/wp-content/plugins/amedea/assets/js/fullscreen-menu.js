(function($) {
	"use strict";

	// Preload images
	const preloadImages = (selector = 'img') => {
		return new Promise((resolve) => {
			imagesLoaded(document.querySelectorAll(selector), {background: true}, resolve);
		});
	};

	//fullscreen menu
	const fullscreenMenu = document.querySelector('.fullscreen-menu');
	
	// frame element
	const frame = document.querySelector('.fullscreen-menu__frame');

	// overlay (SVG path element)
	const overlayPath = document.querySelector('.fullscreen-menu__overlay__path');

	// menu (wrap) element
	const menuWrap = document.querySelector('.fullscreen-menu__menu-wrap');

	// menu items
	const menuItems = menuWrap.querySelectorAll('.fullscreen-menu__menu__item--selector');
	
	// menu items
	const socialItems = menuWrap.querySelector('.fullscreen-menu__social');

	// open menu button
	const openMenuCtrl = document.querySelector('button.fullscreen-menu__button-menu');

	// close menu button
	const closeMenuCtrl = menuWrap.querySelector('.fullscreen-menu__button-close');


	let isAnimating = false;

	// opens the menu
	const openMenu = ()  => {
		
		if ( isAnimating ) return;
		isAnimating = true;
			
		gsap.timeline({
				onComplete: () => isAnimating = false
			})
			.set(overlayPath, {
				attr: { d: 'M 0 100 V 100 Q 50 100 100 100 V 100 z' }
			})
			.to(overlayPath, { 
				duration: 0.8,
				ease: 'power4.in',
				attr: { d: 'M 0 100 V 50 Q 50 0 100 50 V 100 z' }
			}, 0)
			.to(overlayPath, { 
				duration: 0.3,
				ease: 'power2',
				attr: { d: 'M 0 100 V 0 Q 50 0 100 0 V 100 z' },
				onComplete: () => {
					frame.classList.add('frame--menu-open');
					menuWrap.classList.add('menu-wrap--open');
				}
			})

			// now reveal
			.set(menuItems, { 
				opacity: 0
			})
			.set(socialItems, { 
				opacity: 0
			})
			.set(overlayPath, { 
				attr: { d: 'M 0 0 V 100 Q 50 100 100 100 V 0 z' }
			})
			.to(overlayPath, { 
				duration: 0.3,
				ease: 'power2.in',
				attr: { d: 'M 0 0 V 50 Q 50 0 100 50 V 0 z' }
			})
			.to(overlayPath, { 
				duration: 0.8,
				ease: 'power4',
				attr: { d: 'M 0 0 V 0 Q 50 0 100 0 V 0 z' }
			})
			// menu items translate animation
			.to(menuItems, { 
				duration: 1.1,
				ease: 'power4',
				startAt: {y: 150},
				y: 0,
				opacity: 1,
				stagger: 0.05
			}, '>-=1.1')
			.to(socialItems, { 
				duration: 1.1,
				ease: 'power4',
				startAt: {y: 150},
				y: 0,
				opacity: 1,
				stagger: 0.05
			}, '>-=1.1');
			
			setTimeout(function () {
				fullscreenMenu.classList.add('z-index--fix');
			}, 10);

	}

	// closes the menu
	const closeMenu = ()  => {
		
		if ( isAnimating ) return;
		isAnimating = true;
		gsap.timeline({
				onComplete: () => isAnimating = false
			})
			.set(overlayPath, {
				attr: { d: 'M 0 0 V 0 Q 50 0 100 0 V 0 z' }
			})
			.to(overlayPath, { 
				duration: 0.8,
				ease: 'power4.in',
				attr: { d: 'M 0 0 V 50 Q 50 100 100 50 V 0 z' }
			}, 0)
			.to(overlayPath, { 
				duration: 0.3,
				ease: 'power2',
				attr: { d: 'M 0 0 V 100 Q 50 100 100 100 V 0 z' },
				onComplete: () => {
					frame.classList.remove('frame--menu-open');
					menuWrap.classList.remove('menu-wrap--open');
				}
			})
			// now reveal
			.set(overlayPath, { 
				attr: { d: 'M 0 100 V 0 Q 50 0 100 0 V 100 z' }
			})
			.to(overlayPath, { 
				duration: 0.3,
				ease: 'power2.in',
				attr: { d: 'M 0 100 V 50 Q 50 100 100 50 V 100 z' }
			})
			.to(overlayPath, { 
				duration: 0.8,
				ease: 'power4',
				attr: { d: 'M 0 100 V 100 Q 50 100 100 100 V 100 z' }
			})

			// menu items translate animation
			.to(menuItems, { 
				duration: 0.8,
				ease: 'power2.in',
				y: 100,
				opacity: 0,
				stagger: -0.05
			}, 0)
			
			.to(socialItems, { 
				duration: 0.8,
				ease: 'power2.in',
				y: 100,
				opacity: 0,
				stagger: -0.05
			}, 0)
			
			setTimeout(function () {
				fullscreenMenu.classList.remove('z-index--fix');
			}, 1000);
	}


	// click on menu button
	openMenuCtrl.addEventListener('click', openMenu);
	// click on close menu button
	closeMenuCtrl.addEventListener('click', closeMenu);
	
})(jQuery);