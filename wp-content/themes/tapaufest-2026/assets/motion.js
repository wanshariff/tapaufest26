/**
 * TAPAUfest 2026 motion layer.
 *
 * Adds `tf-motion` to <html> only when JavaScript runs and the visitor has not
 * asked for reduced motion, so the page is complete and still without it.
 * Everything here is decoration: reveal on scroll, a count-up on the
 * countdown, and pausing the ticker when it is off screen.
 */
( function () {
	'use strict';

	var reduce = window.matchMedia && window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;
	if ( reduce || ! ( 'IntersectionObserver' in window ) ) {
		return;
	}
	var root = document.documentElement;
	root.classList.add( 'tf-motion' );

	/* Reveal on scroll. Siblings stagger by 60ms, capped so long grids don't drag. */
	var selectors = [
		'.tf-section-head',
		'.tf-page-head .tf-section-head',
		'.tf-artist',
		'.tf-tier',
		'.is-style-tf-ink-card',
		'.tf-strip-photo',
		'.tf-split-photo',
		'.tf-wide-photo',
		'.tf-band-photo',
		'.tf-faq__item',
		'.tf-reel',
		'.tf-facts li',
		'.tf-stagecol',
		'.tf-vendor',
		'.tf-archive-media figure'
	];
	var els = document.querySelectorAll( selectors.join( ',' ) );
	var io = new IntersectionObserver(
		function ( entries ) {
			entries.forEach( function ( entry ) {
				if ( entry.isIntersecting ) {
					entry.target.classList.add( 'is-in' );
					io.unobserve( entry.target );
				}
			} );
		},
		{ rootMargin: '0px 0px -8% 0px', threshold: 0.08 }
	);
	Array.prototype.forEach.call( els, function ( el ) {
		// Skip anything inside the hero: it has its own entrance.
		if ( el.closest( '.tf-hero' ) ) {
			return;
		}
		// Skip cards in sideways scrollers (phone lineup strip, ticket row): they'd fade in mid-swipe.
		var parent = el.parentElement;
		if ( parent && parent.scrollWidth > parent.clientWidth + 4 ) {
			return;
		}
		var index = 0;
		var sib = el.previousElementSibling;
		while ( sib && index < 6 ) {
			if ( sib.matches && sib.matches( selectors.join( ',' ) ) ) {
				index++;
			}
			sib = sib.previousElementSibling;
		}
		el.style.setProperty( '--tf-delay', index * 60 + 'ms' );
		el.classList.add( 'tf-reveal' );
		// Already on screen at load: show without waiting for a scroll.
		var r = el.getBoundingClientRect();
		if ( r.top < window.innerHeight && r.bottom > 0 ) {
			requestAnimationFrame( function () {
				el.classList.add( 'is-in' );
			} );
		} else {
			io.observe( el );
		}
	} );

	/* Countdown counts up to the real number once. */
	var count = document.querySelector( '[data-tf-count]' );
	if ( count ) {
		var target = parseInt( count.getAttribute( 'data-tf-count' ), 10 ) || 0;
		var start = null;
		var dur = 900;
		var step = function ( t ) {
			if ( ! start ) {
				start = t;
			}
			var p = Math.min( 1, ( t - start ) / dur );
			var eased = 1 - Math.pow( 1 - p, 3 );
			count.textContent = String( Math.round( target * eased ) );
			if ( p < 1 ) {
				requestAnimationFrame( step );
			}
		};
		setTimeout( function () {
			requestAnimationFrame( step );
		}, 450 );
	}

	/* Pause the ticker while it is off screen. */
	var tickers = document.querySelectorAll( '.tf-ticker' );
	if ( tickers.length ) {
		var tio = new IntersectionObserver( function ( entries ) {
			entries.forEach( function ( entry ) {
				entry.target.classList.toggle( 'is-paused', ! entry.isIntersecting );
			} );
		} );
		Array.prototype.forEach.call( tickers, function ( t ) {
			tio.observe( t );
		} );
	}
}() );
