/**
 * TAPAUfest front end: listen sheet, lineup filters, timetable tabs,
 * Jadual Aku (personal schedule kept in this browser), mobile ticket bar.
 *
 * Plain script, no dependencies. Every feature checks for its markup first,
 * and the page reads fine with this script off.
 */
( function () {
	'use strict';

	var cfg = window.tapaufestView || { i18n: {} };
	var t = function ( key, fallback ) {
		return ( cfg.i18n && cfg.i18n[ key ] ) || fallback;
	};
	var $ = function ( sel, root ) {
		return ( root || document ).querySelector( sel );
	};
	var $$ = function ( sel, root ) {
		return Array.prototype.slice.call( ( root || document ).querySelectorAll( sel ) );
	};

	/* ---------- toast ---------- */
	var toastTimer;
	function toast( text ) {
		var el = $( '.tf-toast' );
		if ( ! el ) {
			return;
		}
		el.textContent = text;
		el.hidden = false;
		clearTimeout( toastTimer );
		toastTimer = setTimeout( function () {
			el.hidden = true;
		}, 2400 );
	}

	/* ---------- sheet ---------- */
	var sheet = $( '#tf-sheet' );
	var lastFocus = null;
	function openSheet( title, node ) {
		if ( ! sheet ) {
			return;
		}
		lastFocus = document.activeElement;
		$( '.tf-sheet__title', sheet ).textContent = title;
		var body = $( '.tf-sheet__body', sheet );
		body.innerHTML = '';
		body.appendChild( node );
		if ( typeof sheet.showModal === 'function' ) {
			sheet.showModal();
		} else {
			sheet.setAttribute( 'open', '' );
		}
	}
	function closeSheet() {
		if ( ! sheet ) {
			return;
		}
		$( '.tf-sheet__body', sheet ).innerHTML = '';
		if ( typeof sheet.close === 'function' ) {
			sheet.close();
		} else {
			sheet.removeAttribute( 'open' );
		}
		if ( lastFocus && lastFocus.focus ) {
			lastFocus.focus();
		}
	}
	if ( sheet ) {
		sheet.addEventListener( 'click', function ( e ) {
			if ( e.target === sheet || e.target.closest( '[data-tf-close]' ) ) {
				closeSheet();
			}
		} );
		sheet.addEventListener( 'close', function () {
			$( '.tf-sheet__body', sheet ).innerHTML = '';
		} );
	}

	/* ---------- Jadual Aku store ---------- */
	var KEY = 'tapaufest-jadual';
	var mine = [];
	try {
		mine = JSON.parse( window.localStorage.getItem( KEY ) || '[]' ).map( String );
	} catch ( err ) {
		mine = [];
	}
	function save() {
		try {
			window.localStorage.setItem( KEY, JSON.stringify( mine ) );
		} catch ( err ) {}
	}
	function has( id ) {
		return mine.indexOf( String( id ) ) !== -1;
	}

	function sessions() {
		var seen = {};
		return $$( '[data-tf-session]' )
			.map( function ( node ) {
				return {
					id: node.getAttribute( 'data-tf-session' ),
					day: node.getAttribute( 'data-day' ),
					dayLabel: node.getAttribute( 'data-day-label' ),
					start: parseInt( node.getAttribute( 'data-start' ), 10 ),
					end: parseInt( node.getAttribute( 'data-end' ), 10 ),
					title: node.getAttribute( 'data-title' ),
					stage: node.getAttribute( 'data-stage' ),
					time: node.getAttribute( 'data-time' ),
					node: node
				};
			} )
			.filter( function ( s ) {
				if ( seen[ s.id ] ) {
					return false;
				}
				seen[ s.id ] = true;
				return true;
			} );
	}

	function clashes() {
		var picked = sessions().filter( function ( s ) {
			return has( s.id );
		} );
		var out = {};
		picked.forEach( function ( a ) {
			picked.forEach( function ( b ) {
				if ( a.id !== b.id && a.day === b.day && a.start < b.end && b.start < a.end ) {
					out[ a.id ] = true;
				}
			} );
		} );
		return out;
	}

	// Icon from the shared sprite (printed once in the footer).
	function icon( name ) {
		return '<svg class="tf-icon tf-icon--' + name + '" aria-hidden="true" focusable="false"><use href="#tf-i-' + name + '"></use></svg>';
	}

	function render() {
		var clash = clashes();
		$$( '[data-tf-star]' ).forEach( function ( btn ) {
			var id = btn.getAttribute( 'data-tf-star' );
			var on = has( id );
			btn.setAttribute( 'aria-pressed', on ? 'true' : 'false' );
			if ( btn.hasAttribute( 'data-tf-star-label' ) ) {
				btn.innerHTML = icon( on ? 'star-filled' : 'star' ) + ' ' + ( on ? 'In your Jadual Aku' : 'Add to Jadual Aku' );
				btn.classList.toggle( 'tf-btn--cili', ! on );
			} else {
				var glyph = btn.querySelector( 'span' );
				if ( glyph ) {
					glyph.innerHTML = icon( on ? 'star-filled' : 'star' );
				}
			}
		} );
		$$( '.tf-slot[data-tf-session]' ).forEach( function ( slot ) {
			var id = slot.getAttribute( 'data-tf-session' );
			slot.classList.toggle( 'is-mine', has( id ) );
			slot.classList.toggle( 'is-clash', !! clash[ id ] );
			var note = slot.querySelector( '.tf-slot__clash' );
			if ( note ) {
				note.hidden = ! clash[ id ];
			}
		} );
		renderMine( clash );
	}

	function renderMine( clash ) {
		var list = $( '[data-tf-mine-list]' );
		if ( ! list ) {
			return;
		}
		var picked = sessions()
			.filter( function ( s ) {
				return has( s.id );
			} )
			.sort( function ( a, b ) {
				return a.day === b.day ? a.start - b.start : a.day < b.day ? -1 : 1;
			} );
		$$( '[data-tf-count]' ).forEach( function ( n ) {
			n.textContent = String( picked.length );
		} );
		$( '[data-tf-mine-empty]' ).hidden = picked.length > 0;
		$( '[data-tf-mine-actions]' ).hidden = picked.length === 0;
		list.innerHTML = '';
		picked.forEach( function ( s ) {
			var li = document.createElement( 'li' );
			li.className = 'tf-mine__item' + ( clash[ s.id ] ? ' is-clash' : '' );
			var when = document.createElement( 'time' );
			when.textContent = s.dayLabel + ', ' + s.time;
			var what = document.createElement( 'p' );
			var b = document.createElement( 'b' );
			b.textContent = s.title;
			var sub = document.createElement( 'span' );
			sub.textContent = s.stage + ( clash[ s.id ] ? ' · Clashes' : '' );
			what.appendChild( b );
			what.appendChild( sub );
			var btn = document.createElement( 'button' );
			btn.type = 'button';
			btn.className = 'tf-star';
			btn.setAttribute( 'data-tf-star', s.id );
			btn.setAttribute( 'aria-pressed', 'true' );
			btn.setAttribute( 'aria-label', 'Remove ' + s.title + ' from Jadual Aku' );
			btn.innerHTML = '<span aria-hidden="true">' + icon( 'star-filled' ) + '</span>';
			li.appendChild( when );
			li.appendChild( what );
			li.appendChild( btn );
			list.appendChild( li );
		} );
		var ics = $( '[data-tf-ics]' );
		if ( ics && cfg.icsUrl ) {
			ics.href = cfg.icsUrl + ( cfg.icsUrl.indexOf( '?' ) === -1 ? '?' : '&' ) + 'ids=' + mine.join( ',' );
		}
	}

	function toggle( id ) {
		id = String( id );
		if ( has( id ) ) {
			mine = mine.filter( function ( x ) {
				return x !== id;
			} );
			save();
			render();
			toast( t( 'removed', 'Removed from Jadual Aku' ) );
			return;
		}
		mine.push( id );
		save();
		render();
		toast( clashes()[ id ] ? t( 'clash', 'Added. Heads up, it clashes with another pick.' ) : t( 'added', 'Added to Jadual Aku' ) );
	}

	/* ---------- clicks ---------- */
	document.addEventListener( 'click', function ( e ) {
		var el;
		if ( ( el = e.target.closest( '[data-tf-listen]' ) ) ) {
			e.preventDefault();
			var tpl = document.getElementById( 'tf-listen-' + el.getAttribute( 'data-tf-listen' ) );
			if ( tpl ) {
				openSheet( tpl.getAttribute( 'data-title' ), tpl.content.cloneNode( true ) );
			}
			return;
		}
		if ( ( el = e.target.closest( '[data-tf-star]' ) ) ) {
			e.preventDefault();
			toggle( el.getAttribute( 'data-tf-star' ) );
			return;
		}
		if ( ( el = e.target.closest( '[data-tf-tab]' ) ) ) {
			var root = el.closest( '[data-tf-timetable]' );
			var target = el.getAttribute( 'data-tf-tab' );
			$$( '[data-tf-tab]', root ).forEach( function ( b ) {
				b.setAttribute( 'aria-selected', b === el ? 'true' : 'false' );
			} );
			$$( '[role="tabpanel"]', root ).forEach( function ( p ) {
				p.hidden = p.id !== 'tf-panel-' + target;
			} );
			return;
		}
		if ( ( el = e.target.closest( '[data-tf-share]' ) ) ) {
			var url = ( cfg.pageUrl || window.location.href.split( '?' )[ 0 ] ) + '?jadual=' + mine.join( '.' );
			var box = $( '[data-tf-share-url]' );
			if ( box ) {
				box.textContent = url;
				box.hidden = false;
			}
			if ( navigator.clipboard && navigator.clipboard.writeText ) {
				navigator.clipboard.writeText( url ).then(
					function () {
						toast( t( 'copied', 'Link copied. Send it to your geng.' ) );
					},
					function () {}
				);
			}
			return;
		}
		if ( ( el = e.target.closest( '[data-tf-country], [data-tf-day]' ) ) ) {
			var lineup = el.closest( '[data-tf-lineup]' );
			var attr = el.hasAttribute( 'data-tf-country' ) ? 'data-tf-country' : 'data-tf-day';
			$$( '[' + attr + ']', lineup ).forEach( function ( b ) {
				b.setAttribute( 'aria-pressed', b === el ? 'true' : 'false' );
			} );
			filterLineup( lineup );
			return;
		}
		if ( ( el = e.target.closest( '[data-tf-clear]' ) ) ) {
			var lu = el.closest( '[data-tf-lineup]' );
			$$( '[data-tf-country], [data-tf-day]', lu ).forEach( function ( b ) {
				b.setAttribute( 'aria-pressed', b.getAttribute( b.hasAttribute( 'data-tf-country' ) ? 'data-tf-country' : 'data-tf-day' ) === '' ? 'true' : 'false' );
			} );
			filterLineup( lu );
			return;
		}
		if ( ( el = e.target.closest( '[data-tf-view]' ) ) ) {
			var box2 = el.closest( '[data-tf-lineup]' );
			var poster = el.getAttribute( 'data-tf-view' ) === 'poster';
			$$( '[data-tf-view]', box2 ).forEach( function ( b ) {
				b.setAttribute( 'aria-pressed', b === el ? 'true' : 'false' );
			} );
			$( '[data-tf-grid]', box2 ).hidden = poster;
			$( '[data-tf-poster]', box2 ).hidden = ! poster;
			if ( poster ) {
				$( '[data-tf-noresults]', box2 ).hidden = true;
			} else {
				filterLineup( box2 );
			}
		}
	} );

	function filterLineup( root ) {
		var countryBtn = $( '[data-tf-country][aria-pressed="true"]', root );
		var country = countryBtn ? countryBtn.getAttribute( 'data-tf-country' ) : '';
		var dayBtn = $( '[data-tf-day][aria-pressed="true"]', root );
		var day = dayBtn ? dayBtn.getAttribute( 'data-tf-day' ) : '';
		var filtered = !! ( country || day );
		var shown = 0;
		$$( '.tf-artist', $( '[data-tf-grid]', root ) ).forEach( function ( card ) {
			var ok = ( ! country || card.getAttribute( 'data-country' ) === country ) && ( ! day || card.getAttribute( 'data-day' ) === day );
			card.hidden = ! ok;
			card.classList.toggle( 'is-filtered', filtered );
			if ( ok ) {
				shown++;
			}
		} );
		$( '[data-tf-noresults]', root ).hidden = shown > 0;
	}

	/* ---------- shared Jadual Aku link ---------- */
	var params = new URLSearchParams( window.location.search );
	var shared = ( params.get( 'jadual' ) || '' ).split( '.' ).filter( function ( x ) {
		return /^\d+$/.test( x );
	} );
	var importBox = $( '[data-tf-import]' );
	if ( shared.length && importBox ) {
		importBox.hidden = false;
		var clearParam = function () {
			importBox.hidden = true;
			params.delete( 'jadual' );
			var q = params.toString();
			window.history.replaceState( null, '', window.location.pathname + ( q ? '?' + q : '' ) + window.location.hash );
		};
		$( '[data-tf-import-yes]', importBox ).addEventListener( 'click', function () {
			shared.forEach( function ( id ) {
				if ( ! has( id ) ) {
					mine.push( id );
				}
			} );
			save();
			render();
			clearParam();
			toast( t( 'imported', 'Your geng’s picks are in your Jadual Aku.' ) );
		} );
		$( '[data-tf-import-no]', importBox ).addEventListener( 'click', clearParam );
	}

	/* ---------- current page in the main menu ---------- */
	var here = window.location.pathname.replace( /\/+$/, '' );
	$$( '.tf-nav a.wp-block-navigation-item__content' ).forEach( function ( a ) {
		var path = a.pathname.replace( /\/+$/, '' );
		if ( path && ( path === here || ( path === '/lineup' && here.indexOf( '/artist/' ) === 0 ) ) ) {
			a.setAttribute( 'aria-current', 'page' );
		}
	} );

	/* ---------- mobile ticket bar ---------- */
	var dock = $( '[data-tf-dock]' );
	var tickets = $( '[data-tf-tickets]' );
	if ( dock && tickets && 'IntersectionObserver' in window ) {
		new IntersectionObserver(
			function ( entries ) {
				entries.forEach( function ( entry ) {
					dock.classList.toggle( 'is-away', entry.isIntersecting );
				} );
			},
			{ threshold: 0.1 }
		).observe( tickets );
	}

	render();
} )();
