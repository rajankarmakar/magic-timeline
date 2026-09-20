/**
 * Magic Timeline — progressive-enhancement scroll animation.
 * Falls back silently (items simply stay visible) if IntersectionObserver
 * is unavailable, so the widget never depends on JS to be usable.
 */
( function () {
	'use strict';

	var MOBILE_BREAKPOINT = 767;

	function isMobile() {
		return window.innerWidth <= MOBILE_BREAKPOINT;
	}

	function initTimeline( timeline ) {
		// Idempotent: safe to call again for a timeline that's already animating
		// (e.g. from more than one init trigger firing for the same element).
		if ( timeline.classList.contains( 'mtl-js-animating' ) ) {
			return;
		}

		var disableOnMobile = timeline.getAttribute( 'data-mtl-animate-mobile' ) === 'no';

		if ( disableOnMobile && isMobile() ) {
			return;
		}

		if ( typeof window.IntersectionObserver === 'undefined' ) {
			return;
		}

		var items = timeline.querySelectorAll( '.mtl-item' );

		if ( ! items.length ) {
			return;
		}

		timeline.classList.add( 'mtl-js-animating' );

		var observer = new IntersectionObserver(
			function ( entries ) {
				entries.forEach( function ( entry ) {
					if ( entry.isIntersecting ) {
						entry.target.classList.add( 'mtl-in-view' );
						observer.unobserve( entry.target );
					}
				} );
			},
			{
				root: null,
				rootMargin: '0px 0px -10% 0px',
				threshold: 0.1,
			}
		);

		items.forEach( function ( item ) {
			observer.observe( item );
		} );
	}

	function init() {
		var timelines = document.querySelectorAll( '.mtl-timeline[data-mtl-animate="yes"]' );
		timelines.forEach( initTimeline );
	}

	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', init );
	} else {
		init();
	}

	// Fallback for contexts where widget markup is injected after
	// DOMContentLoaded has already fired (e.g. Elementor's editor preview
	// iframe). initTimeline() is idempotent, so re-running init() here is safe.
	window.addEventListener( 'load', init );

	// Re-init when Elementor rebuilds the preview inside the editor.
	if ( window.elementorFrontend && window.elementorFrontend.hooks ) {
		window.elementorFrontend.hooks.addAction( 'frontend/element_ready/magic-timeline.default', function ( $scope ) {
			var timeline = $scope[ 0 ].querySelector( '.mtl-timeline[data-mtl-animate="yes"]' );
			if ( timeline ) {
				initTimeline( timeline );
			}
		} );
	}
} )();
