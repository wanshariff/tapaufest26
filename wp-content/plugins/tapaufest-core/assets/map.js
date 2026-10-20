/**
 * TAPAUfest interactive site map.
 *
 * Leaflet in CRS.Simple over our own designed basemap (an inline SVG drawn by
 * the server), so it pans and zooms like a street map without third-party
 * tiles. Pins open a place card: on hover with a mouse, on tap or Enter
 * elsewhere. Filters show one category at a time.
 */
( function () {
	'use strict';

	var W = 1000;
	var H = 640;

	function init( root ) {
		if ( ! window.L ) {
			return;
		}
		var canvas = root.querySelector( '.tf-map__canvas' );
		var svg = canvas.querySelector( 'svg' );
		var pins = JSON.parse( root.getAttribute( 'data-pins' ) || '[]' );
		var hoverable = window.matchMedia( '(hover: hover) and (pointer: fine)' ).matches;
		var reduce = window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;

		root.classList.add( 'is-ready' );
		svg.parentNode.removeChild( svg );

		var bounds = [ [ 0, 0 ], [ H, W ] ];
		var map = L.map( canvas, {
			crs: L.CRS.Simple,
			minZoom: -2,
			maxZoom: 2,
			zoomSnap: 0.25,
			zoomDelta: 0.5,
			scrollWheelZoom: false,
			attributionControl: false,
			zoomAnimation: ! reduce,
			fadeAnimation: ! reduce,
			markerZoomAnimation: ! reduce
		} );
		// The basemap extends past the festival site so panning never hits a blank edge.
		var ext = ( svg.getAttribute( 'data-extent' ) || '0 0 ' + W + ' ' + H ).split( ' ' ).map( Number );
		var outer = [ [ H - ( ext[ 1 ] + ext[ 3 ] ), ext[ 0 ] ], [ H - ext[ 1 ], ext[ 0 ] + ext[ 2 ] ] ];
		map.setMaxBounds( outer );
		L.svgOverlay( svg, outer, { interactive: false } ).addTo( map );
		// Fill the frame (no empty margins), then let people zoom out a little.
		function frame() {
			var cover = map.getBoundsZoom( bounds, true );
			map.setMinZoom( cover - 0.5 );
			map.setView( [ H / 2, W / 2 ], cover, { animate: false } );
			return { center: map.getCenter(), zoom: map.getZoom() };
		}
		var home = frame();

		// Reset view control.
		var Reset = L.Control.extend( {
			options: { position: 'topleft' },
			onAdd: function () {
				var b = L.DomUtil.create( 'button', 'tf-map__reset' );
				b.type = 'button';
				b.title = 'Show the whole site';
				b.setAttribute( 'aria-label', 'Show the whole site' );
				b.innerHTML = '<svg viewBox="0 0 18 18" aria-hidden="true"><path d="M2 7V2h5M16 7V2h-5M2 11v5h5M16 11v5h-5" fill="none" stroke="currentColor" stroke-width="2"/></svg>';
				L.DomEvent.on( b, 'click', function ( e ) {
					L.DomEvent.stop( e );
					map.closePopup();
					map.setView( home.center, home.zoom );
				} );
				return b;
			}
		} );
		map.addControl( new Reset() );

		function cardHtml( key ) {
			// The short card: photo, category, name and a link to more.
			var pop = root.querySelector( '.tf-map__pop[data-pop="' + key + '"]' );
			if ( ! pop ) {
				return '';
			}
			var el = pop.cloneNode( true );
			el.removeAttribute( 'hidden' );
			return el.outerHTML;
		}

		var markers = {};
		var closeTimer = null;

		// Touch screens get a card docked at the bottom of the map instead of popups.
		var useSheet = ! hoverable;
		var sheet = null;
		var activeMarker = null;
		function closeSheet() {
			if ( ! sheet ) {
				return;
			}
			sheet.hidden = true;
			root.classList.remove( 'has-open' );
			if ( activeMarker && activeMarker._icon ) {
				activeMarker._icon.classList.remove( 'is-active' );
			}
			activeMarker = null;
		}
		function openSheet( m, key ) {
			if ( ! sheet ) {
				sheet = L.DomUtil.create( 'div', 'tf-map__sheet', map.getContainer() );
				sheet.hidden = true;
				sheet.setAttribute( 'role', 'dialog' );
				sheet.setAttribute( 'aria-live', 'polite' );
				L.DomEvent.disableClickPropagation( sheet );
				L.DomEvent.disableScrollPropagation( sheet );
			}
			if ( activeMarker && activeMarker._icon ) {
				activeMarker._icon.classList.remove( 'is-active' );
			}
			activeMarker = m;
			sheet.innerHTML = cardHtml( key ) + '<button type="button" class="tf-map__sheet-close" aria-label="Close">' + ( root.getAttribute( 'data-close-icon' ) || '×' ) + '</button>';
			sheet.setAttribute( 'aria-label', m.options.title );
			sheet.querySelector( '.tf-map__sheet-close' ).addEventListener( 'click', closeSheet );
			sheet.hidden = false;
			root.classList.add( 'has-open' );
			if ( m._icon ) {
				m._icon.classList.add( 'is-active' );
			}
			// Keep the pin visible above the card.
			var size = map.getSize();
			var pt = map.latLngToContainerPoint( m.getLatLng() );
			var room = size.y - sheet.offsetHeight - 30;
			if ( pt.y > room || pt.y < 40 ) {
				map.panBy( [ 0, pt.y - room / 2 ], { animate: ! reduce } );
			}
		}
		if ( useSheet ) {
			map.on( 'click', closeSheet );
		}
		pins.forEach( function ( p ) {
			var icon = L.divIcon( {
				className: 'tf-pin-wrap',
				html: '<span class="tf-pin' + ( p.small ? ' is-small' : '' ) + '" style="--pin:' + p.color + '">' + p.icon + '</span><span class="tf-pin__label">' + p.label + '</span>',
				iconSize: p.small ? [ 32, 32 ] : [ 42, 50 ],
				iconAnchor: p.small ? [ 16, 16 ] : [ 21, 48 ],
				popupAnchor: p.small ? [ 0, -14 ] : [ 0, -44 ]
			} );
			var m = L.marker( [ H - p.y, p.x ], { icon: icon, title: p.label, alt: p.label, riseOnHover: true, keyboard: true } );
			m.tfCat = p.cat;
			// Pages with a side panel (wide screens) listen for this.
			m.on( 'click', function () {
				root.dispatchEvent( new CustomEvent( 'tf:map-pick', { bubbles: true, detail: { key: p.key } } ) );
			} );
			if ( useSheet ) {
				m.on( 'click', function () {
					openSheet( m, p.key );
				} );
				m.addTo( map );
				markers[ p.key ] = m;
				return;
			}
			m.bindPopup( cardHtml( p.key ), {
				className: 'tf-map__popup',
				maxWidth: 240,
				minWidth: 220,
				autoPanPaddingTopLeft: [ 60, 16 ],
				autoPanPaddingBottomRight: [ 16, 16 ],
				closeButton: true
			} );
			m.tfCat = p.cat;
			if ( hoverable ) {
				m.on( 'mouseover', function () {
					clearTimeout( closeTimer );
					if ( ! m.isPopupOpen() ) {
						m.openPopup();
					}
				} );
				m.on( 'mouseout', function () {
					closeTimer = setTimeout( function () {
						var el = m.getPopup() && m.getPopup().getElement();
						if ( ! el || ! el.matches( ':hover' ) ) {
							m.closePopup();
						}
					}, 220 );
				} );
			}
			m.on( 'popupopen', function () {
				var el = m.getPopup().getElement();
				root.classList.add( 'has-open' );
				if ( m._icon ) {
					m._icon.classList.add( 'is-active' );
				}
				if ( hoverable && el ) {
					el.addEventListener( 'mouseenter', function () {
						clearTimeout( closeTimer );
					} );
					el.addEventListener( 'mouseleave', function () {
						closeTimer = setTimeout( function () {
							m.closePopup();
						}, 220 );
					} );
				}
			} );
			m.on( 'popupclose', function () {
				root.classList.remove( 'has-open' );
				if ( m._icon ) {
					m._icon.classList.remove( 'is-active' );
				}
			} );
			m.addTo( map );
			markers[ p.key ] = m;
		} );

		// Filters.
		var filters = root.querySelectorAll( '.tf-map__filter' );
		Array.prototype.forEach.call( filters, function ( btn ) {
			btn.addEventListener( 'click', function () {
				var cat = btn.getAttribute( 'data-cat' );
				Array.prototype.forEach.call( filters, function ( b ) {
					b.setAttribute( 'aria-pressed', String( b === btn ) );
				} );
				map.closePopup();
				var shown = [];
				Object.keys( markers ).forEach( function ( k ) {
					var m = markers[ k ];
					var on = ! cat || m.tfCat === cat;
					if ( on ) {
						m.addTo( map );
						shown.push( m.getLatLng() );
					} else {
						m.remove();
					}
				} );
				if ( cat && shown.length ) {
					map.flyToBounds( L.latLngBounds( shown ).pad( 0.6 ), { maxZoom: 1, duration: reduce ? 0 : 0.5 } );
				} else {
					map.setView( home.center, home.zoom );
				}
			} );
		} );

		var resizeTimer;
		window.addEventListener( 'resize', function () {
			clearTimeout( resizeTimer );
			resizeTimer = setTimeout( function () {
				map.invalidateSize();
				home = frame();
			}, 150 );
		} );
	}

	window.addEventListener( 'load', function () {
		document.querySelectorAll( '[data-tf-map]' ).forEach( init );
	} );
}() );
