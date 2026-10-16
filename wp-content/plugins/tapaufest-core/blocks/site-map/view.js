/**
 * Interactive site map: hover (mouse), focus (keyboard) or tap (touch) a spot
 * to open its card. On wide screens the card floats beside the spot; on
 * phones it sits under the map. Escape or tapping the open spot again closes it.
 */
( function () {
	'use strict';
	function init( map ) {
		var stage = map.querySelector( '.tf-map__stage' );
		var spots = map.querySelectorAll( '.tf-map__spot' );
		var cards = map.querySelectorAll( '.tf-map__card' );
		var current = null;
		var pinned = false;
		var openedAt = 0;
		var wide = window.matchMedia( '(min-width: 900px) and (hover: hover)' );

		map.classList.add( 'is-ready' );

		function cardFor( key ) {
			return map.querySelector( '.tf-map__card[data-card="' + key + '"]' );
		}
		function place( spot, card ) {
			if ( ! wide.matches ) {
				card.style.left = '';
				card.style.top = '';
				return;
			}
			var s = spot.getBoundingClientRect();
			var m = stage.getBoundingClientRect();
			var cw = card.offsetWidth || 300;
			var ch = card.offsetHeight || 260;
			var left = s.right - m.left + 14;
			if ( left + cw > m.width ) {
				left = s.left - m.left - cw - 14;
			}
			left = Math.max( 8, Math.min( left, m.width - cw - 8 ) );
			var top = s.top - m.top + s.height / 2 - ch / 2;
			top = Math.max( 8, Math.min( top, m.height - ch - 8 ) );
			card.style.left = left + 'px';
			card.style.top = top + 'px';
		}
		function open( spot, pin ) {
			var key = spot.getAttribute( 'data-spot' );
			var card = cardFor( key );
			if ( ! card ) {
				return;
			}
			if ( current && current !== spot ) {
				close();
			}
			if ( current !== spot ) {
				openedAt = Date.now();
			}
			current = spot;
			pinned = !! pin;
			spot.classList.add( 'is-active' );
			spot.setAttribute( 'aria-expanded', 'true' );
			Array.prototype.forEach.call( map.querySelectorAll( '[data-chip]' ), function ( c ) {
				c.setAttribute( 'aria-pressed', String( c.getAttribute( 'data-chip' ) === key ) );
			} );
			map.classList.add( 'has-active' );
			Array.prototype.forEach.call( cards, function ( c ) {
				c.classList.toggle( 'is-open', c === card );
			} );
			place( spot, card );
		}
		function close() {
			if ( ! current ) {
				return;
			}
			current.classList.remove( 'is-active' );
			current.setAttribute( 'aria-expanded', 'false' );
			map.classList.remove( 'has-active' );
			Array.prototype.forEach.call( cards, function ( c ) {
				c.classList.remove( 'is-open' );
			} );
			current = null;
			pinned = false;
		}

		Array.prototype.forEach.call( spots, function ( spot ) {
			spot.addEventListener( 'mouseenter', function () {
				if ( wide.matches && ! pinned ) {
					open( spot, false );
				}
			} );
			spot.addEventListener( 'focus', function () {
				open( spot, true );
			} );
			spot.addEventListener( 'click', function ( e ) {
				e.stopPropagation();
				// A tap focuses then clicks: don't let the click undo the focus open.
				if ( current === spot && pinned && Date.now() - openedAt > 400 ) {
					close();
				} else {
					open( spot, true );
				}
			} );
			spot.addEventListener( 'keydown', function ( e ) {
				if ( 'Enter' === e.key || ' ' === e.key ) {
					e.preventDefault();
					open( spot, true );
				}
			} );
		} );
		Array.prototype.forEach.call( map.querySelectorAll( '[data-chip]' ), function ( chip ) {
			chip.addEventListener( 'click', function ( e ) {
				e.stopPropagation();
				var spot = map.querySelector( '.tf-map__spot[data-spot="' + chip.getAttribute( 'data-chip' ) + '"]' );
				if ( spot ) {
					open( spot, true );
				}
			} );
		} );
		map.addEventListener( 'mouseleave', function () {
			if ( ! pinned ) {
				close();
			}
		} );
		document.addEventListener( 'keydown', function ( e ) {
			if ( 'Escape' === e.key && current ) {
				var s = current;
				close();
				s.focus();
			}
		} );
		document.addEventListener( 'click', function ( e ) {
			if ( current && ! map.contains( e.target ) ) {
				close();
			}
		} );
		window.addEventListener( 'resize', function () {
			if ( current ) {
				place( current, cardFor( current.getAttribute( 'data-spot' ) ) );
			}
		} );
		// Phones: open PENTAS by default so the card area is never empty.
		if ( ! wide.matches && spots[ 0 ] ) {
			open( spots[ 0 ], true );
		}
	}
	function boot() {
		document.querySelectorAll( '[data-tf-map]' ).forEach( init );
	}
	if ( 'loading' === document.readyState ) {
		document.addEventListener( 'DOMContentLoaded', boot );
	} else {
		boot();
	}
}() );
