/**
 * Card-to-detail view transitions (cross-document).
 *
 * The CSS opts every same-site navigation into a view transition. This script
 * gives the clicked card's photo and title the shared names "tf-vt-img" and
 * "tf-vt-title", which the detail page's photo and title also carry, so the
 * card grows into the page. Going back, the matching card on the list page is
 * named again so the page shrinks back into it.
 *
 * Loaded in <head> so the pagereveal listener is ready before first render.
 * Browsers without cross-document view transitions just navigate as usual.
 */
( function () {
	'use strict';

	if ( ! ( 'onpagereveal' in window ) ) {
		return;
	}
	var CARDS = '.tf-artist, .tf-vendor, .tf-slot';

	function clear() {
		document.querySelectorAll( '[data-tf-vt]' ).forEach( function ( el ) {
			el.style.viewTransitionName = '';
			el.removeAttribute( 'data-tf-vt' );
		} );
		document.querySelectorAll( '.tf-vt-active' ).forEach( function ( c ) {
			c.classList.remove( 'tf-vt-active' );
		} );
	}

	function name( el, n ) {
		if ( el ) {
			el.style.viewTransitionName = n;
			el.setAttribute( 'data-tf-vt', '' );
		}
	}

	function tag( card, link ) {
		clear();
		name( card.querySelector( 'img, .tf-card__ph' ), 'tf-vt-img' );
		name( card.querySelector( '.tf-artist__name, .tf-vendor__name' ) || link, 'tf-vt-title' );
		card.classList.add( 'tf-vt-active' );
	}

	function cardLink( path ) {
		var links = document.querySelectorAll( 'a[href]' );
		for ( var i = 0; i < links.length; i++ ) {
			if ( links[ i ].pathname === path && links[ i ].closest( CARDS ) ) {
				return links[ i ];
			}
		}
		return null;
	}

	// Forward: name the card that was clicked.
	document.addEventListener( 'click', function ( e ) {
		if ( e.defaultPrevented || e.button !== 0 || e.metaKey || e.ctrlKey || e.shiftKey || e.altKey ) {
			return;
		}
		var a = e.target.closest && e.target.closest( 'a[href]' );
		if ( ! a || a.target === '_blank' || a.origin !== window.location.origin ) {
			return;
		}
		var card = a.closest( CARDS );
		if ( ! card ) {
			clear();
			return;
		}
		tag( card, a );
		try {
			sessionStorage.setItem( 'tf-vt', a.pathname );
		} catch ( err ) {}
	} );

	// Back: name the card for the page we came from.
	window.addEventListener( 'pagereveal', function ( e ) {
		if ( ! e.viewTransition ) {
			return;
		}
		var from = null;
		try {
			if ( window.navigation && window.navigation.activation && window.navigation.activation.from ) {
				from = new URL( window.navigation.activation.from.url ).pathname;
			} else {
				from = sessionStorage.getItem( 'tf-vt' );
			}
		} catch ( err ) {}
		var link = from && from !== window.location.pathname ? cardLink( from ) : null;
		if ( link ) {
			tag( link.closest( CARDS ), link );
		}
		e.viewTransition.finished.finally( clear );
	} );

	// Coming back from the bfcache: drop stale names.
	window.addEventListener( 'pageshow', function ( e ) {
		if ( e.persisted ) {
			clear();
		}
	} );
} )();
