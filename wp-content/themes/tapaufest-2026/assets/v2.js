/**
 * TAPAUfest screens: the interactive parts of the festival pages.
 *
 * Offline banner, toasts, lineup filters, Jadual Aku (list, clashes, time
 * changes, shared plans), ticket order panel, checkout hand-off, route tabs,
 * pack list, FAQ topics and search, vendor tabs, the stall form, countdown
 * and the first-visit loader.
 *
 * Plain script. Every part checks for its markup first and the pages read
 * fine without it. Picks live in this browser (localStorage), same key as
 * the plugin's script, so stars stay in step everywhere.
 */
( function () {
	'use strict';

	var cfg = window.tapaufestScreen || { i18n: {} };
	var t = function ( k, f ) {
		return ( cfg.i18n && cfg.i18n[ k ] ) || f;
	};
	var $ = function ( s, r ) {
		return ( r || document ).querySelector( s );
	};
	var $$ = function ( s, r ) {
		return Array.prototype.slice.call( ( r || document ).querySelectorAll( s ) );
	};
	var store = {
		get: function ( k, d ) {
			try {
				var v = window.localStorage.getItem( k );
				return v === null ? d : JSON.parse( v );
			} catch ( e ) {
				return d;
			}
		},
		set: function ( k, v ) {
			try {
				window.localStorage.setItem( k, JSON.stringify( v ) );
			} catch ( e ) {}
		}
	};
	var reduced = window.matchMedia && window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;
	function icon( name ) {
		return '<svg class="tf-icon tf-icon--' + name + '" aria-hidden="true" focusable="false"><use href="#tf-i-' + name + '"></use></svg>';
	}
	function esc( s ) {
		var d = document.createElement( 'div' );
		d.textContent = s == null ? '' : String( s );
		return d.innerHTML;
	}
	function mine() {
		return ( store.get( 'tapaufest-jadual', [] ) || [] ).map( String );
	}
	function setMine( list ) {
		document.dispatchEvent( new CustomEvent( 'tf:jadual-set', { detail: { mine: list } } ) );
		store.set( 'tapaufest-jadual', list );
	}

	/* ---------- toasts: bottom right, one at a time per message ---------- */
	var stack = null;
	function toast( tone, ic, html, ms ) {
		if ( ! stack ) {
			stack = document.createElement( 'div' );
			stack.className = 'tf2-toasts';
			stack.setAttribute( 'aria-live', 'polite' );
			document.body.appendChild( stack );
		}
		var el = document.createElement( 'div' );
		el.className = 'tf2-alert tf2-alert--toast tf2-alert--' + tone;
		el.innerHTML = icon( ic ) + '<div>' + html + '</div><button type="button" aria-label="Dismiss">' + icon( 'close' ) + '</button>';
		el.querySelector( 'button' ).addEventListener( 'click', function () {
			el.remove();
		} );
		stack.appendChild( el );
		if ( ms !== 0 ) {
			setTimeout( function () {
				el.remove();
			}, ms || 6000 );
		}
		return el;
	}

	/* ---------- offline: service worker + banner ---------- */
	var path = window.location.pathname;
	if ( navigator.onLine ) {
		store.set( 'tf-saved:' + path, new Date().toISOString() );
	}
	if ( 'serviceWorker' in navigator && cfg.sw && window.location.protocol === 'https:' ) {
		window.addEventListener( 'load', function () {
			navigator.serviceWorker.register( cfg.sw, { scope: '/' } ).catch( function () {} );
		} );
	}
	var banner = null;
	function offline( on ) {
		if ( on && ! banner ) {
			var saved = store.get( 'tf-saved:' + path, '' );
			var when = saved ? new Date( saved ).toLocaleTimeString( [], { hour: 'numeric', minute: '2-digit' } ) : '';
			banner = document.createElement( 'div' );
			banner.className = 'tf2-offline';
			banner.setAttribute( 'role', 'status' );
			banner.innerHTML = '<div class="tf2-offline__inner">' + icon( 'offline' ) + '<span>' + esc( t( 'offline', 'Offline. Showing what you saved' ) ) + ( when ? ' at ' + esc( when ) : '' ) + '</span></div>';
			var header = $( '.tf-site-header' );
			if ( header && header.parentNode ) {
				header.parentNode.insertBefore( banner, header.nextSibling );
			} else {
				document.body.insertBefore( banner, document.body.firstChild );
			}
		} else if ( ! on && banner ) {
			banner.remove();
			banner = null;
			toast( 'blue', 'check', esc( t( 'online', 'Back online.' ) ), 2500 );
		}
	}
	window.addEventListener( 'offline', function () {
		offline( true );
	} );
	window.addEventListener( 'online', function () {
		offline( false );
	} );
	if ( ! navigator.onLine ) {
		offline( true );
	}

	/* ---------- first-visit loader ---------- */
	var loader = $( '.tf2-loader' );
	if ( loader ) {
		if ( store.get( 'tf-seen', false ) || reduced ) {
			loader.remove();
		} else {
			store.set( 'tf-seen', true );
			loader.hidden = false;
			var start = Date.now();
			var done = function () {
				setTimeout( function () {
					loader.classList.add( 'is-gone' );
					setTimeout( function () {
						loader.remove();
					}, 400 );
				}, Math.max( 0, 1100 - ( Date.now() - start ) ) );
			};
			if ( document.readyState === 'complete' ) {
				done();
			} else {
				window.addEventListener( 'load', done );
			}
			loader.addEventListener( 'click', function () {
				loader.remove();
			} );
		}
	}

	/* ---------- countdown ---------- */
	$$( '[data-tf2-count]' ).forEach( function ( box ) {
		var end = Date.parse( box.getAttribute( 'data-tf2-count' ) );
		function tick() {
			var ms = Math.max( 0, end - Date.now() );
			var vals = { d: Math.floor( ms / 864e5 ), h: Math.floor( ms / 36e5 ) % 24, m: Math.floor( ms / 6e4 ) % 60 };
			$$( '[data-unit]', box ).forEach( function ( n ) {
				n.textContent = vals[ n.getAttribute( 'data-unit' ) ];
			} );
		}
		tick();
		setInterval( tick, 30000 );
	} );

	/* ---------- lineup filters (day, country, my stars) ---------- */
	$$( '[data-tf2-lineup]' ).forEach( function ( root ) {
		var cards = $$( '.tf2-artist', root );
		var count = $( '[data-tf2-count-line]', root );
		var empty = $( '[data-tf2-none]', root );
		var noStars = $( '[data-tf2-nostars]', root );
		var only = $( '[data-tf2-only]', root );
		function val( attr ) {
			var on = $( '[' + attr + '][aria-pressed="true"]', root );
			return on ? on.getAttribute( attr ) : '';
		}
		function apply() {
			var day = val( 'data-tf2-day' );
			var cc = val( 'data-tf2-cc' );
			var starOnly = only && only.checked;
			var picks = mine();
			var shown = 0;
			cards.forEach( function ( c ) {
				var ok = ( ! day || c.getAttribute( 'data-day' ) === day ) &&
					( ! cc || c.getAttribute( 'data-country' ) === cc ) &&
					( ! starOnly || picks.indexOf( c.getAttribute( 'data-session' ) ) !== -1 );
				c.hidden = ! ok;
				if ( ok ) {
					shown++;
				}
			} );
			if ( count ) {
				count.textContent = 'Showing ' + shown + ' of ' + cards.length + ' acts';
			}
			var filtered = !! ( day || cc );
			if ( noStars ) {
				noStars.hidden = ! ( starOnly && picks.length === 0 );
			}
			if ( empty ) {
				empty.hidden = shown > 0 || ( noStars && ! noStars.hidden ) || ! ( filtered || starOnly );
			}
		}
		root.addEventListener( 'click', function ( e ) {
			var b = e.target.closest( '[data-tf2-day], [data-tf2-cc]' );
			if ( b ) {
				var attr = b.hasAttribute( 'data-tf2-day' ) ? 'data-tf2-day' : 'data-tf2-cc';
				$$( '[' + attr + ']', root ).forEach( function ( x ) {
					x.setAttribute( 'aria-pressed', x === b ? 'true' : 'false' );
				} );
				apply();
				return;
			}
			if ( e.target.closest( '[data-tf2-clear]' ) ) {
				$$( '[data-tf2-day], [data-tf2-cc]', root ).forEach( function ( x ) {
					var a = x.hasAttribute( 'data-tf2-day' ) ? 'data-tf2-day' : 'data-tf2-cc';
					x.setAttribute( 'aria-pressed', x.getAttribute( a ) === '' ? 'true' : 'false' );
				} );
				if ( only ) {
					only.checked = false;
				}
				apply();
			}
		} );
		if ( only ) {
			only.addEventListener( 'change', apply );
		}
		document.addEventListener( 'tf:jadual', function () {
			if ( only && only.checked ) {
				apply();
			}
		} );
		apply();
	} );

	/* ---------- Jadual Aku: snapshot of times, for change notices ---------- */
	var sessionsEl = document.getElementById( 'tf2-sessions' );
	var ALL = [];
	if ( sessionsEl ) {
		try {
			ALL = JSON.parse( sessionsEl.textContent ) || [];
		} catch ( e ) {
			ALL = [];
		}
	}
	var byId = {};
	ALL.forEach( function ( s ) {
		byId[ s.id ] = s;
	} );
	var SNAP = 'tapaufest-jadual-times';
	function sig( s ) {
		return s.day + '|' + s.start + '|' + s.end + '|' + s.stage;
	}
	function movedList() {
		var snap = store.get( SNAP, {} ) || {};
		return mine().filter( function ( id ) {
			return byId[ id ] && snap[ id ] && snap[ id ] !== sig( byId[ id ] );
		} );
	}
	function snapshot( ids ) {
		var snap = store.get( SNAP, {} ) || {};
		( ids || mine() ).forEach( function ( id ) {
			if ( byId[ id ] ) {
				snap[ id ] = sig( byId[ id ] );
			}
		} );
		store.set( SNAP, snap );
	}
	function clashMap( ids ) {
		var picked = ids.map( function ( id ) {
			return byId[ id ];
		} ).filter( Boolean );
		var out = {};
		picked.forEach( function ( a ) {
			picked.forEach( function ( b ) {
				if ( a.id !== b.id && a.day === b.day && a.start < b.end && b.start < a.end && ! a.off && ! b.off ) {
					out[ a.id ] = out[ a.id ] || [];
					out[ a.id ].push( b );
				}
			} );
		} );
		return out;
	}
	function oldTime( id ) {
		var snap = store.get( SNAP, {} ) || {};
		var parts = ( snap[ id ] || '' ).split( '|' );
		var m = parseInt( parts[ 1 ], 10 );
		if ( isNaN( m ) ) {
			return '';
		}
		var h = Math.floor( m / 60 ) % 24;
		var mm = m % 60;
		return ( h % 12 || 12 ) + ( mm ? ':' + ( mm < 10 ? '0' : '' ) + mm : '' ) + ( h >= 12 ? 'pm' : 'am' );
	}

	// Change notice anywhere the session data is printed (timetable, Jadual Aku).
	if ( ALL.length && ! $( '[data-tf2-plan]' ) ) {
		var moved = movedList();
		if ( moved.length ) {
			var s0 = byId[ moved[ 0 ] ];
			var n = toast( 'gold', 'clock', '<b>' + esc( t( 'moved', 'Time change' ) ) + '.</b> ' + esc( s0.title ) + ' now ' + esc( s0.time ) + ( moved.length > 1 ? ' (+' + ( moved.length - 1 ) + ' more)' : '' ) + '. <a href="' + esc( cfg.jadual ) + '">Check Jadual Aku</a>', 0 );
			n.querySelector( 'button' ).addEventListener( 'click', function () {
				snapshot( moved );
			} );
		}
	}
	// Keep the snapshot in step when picks change on pages that know the times.
	document.addEventListener( 'tf:jadual', function ( e ) {
		if ( ! ALL.length ) {
			return;
		}
		var snap = store.get( SNAP, {} ) || {};
		( e.detail.mine || [] ).forEach( function ( id ) {
			if ( byId[ id ] && ! snap[ id ] ) {
				snap[ id ] = sig( byId[ id ] );
			}
		} );
		store.set( SNAP, snap );
	} );

	/* ---------- Jadual Aku page ---------- */
	var plan = $( '[data-tf2-plan]' );
	if ( plan ) {
		var list = $( '[data-tf2-plan-list]', plan );
		var emptyBox = $( '[data-tf2-plan-empty]', plan );
		var tabs = $( '[data-tf2-plan-tabs]', plan );
		var aside = $( '[data-tf2-plan-aside]', plan );
		var noticeBox = $( '[data-tf2-plan-notice]', plan );
		var day = '';
		var params = new URLSearchParams( window.location.search );
		var shared = ( params.get( 'jadual' ) || '' ).split( '.' ).filter( function ( x ) {
			return /^\d+$/.test( x ) && byId[ x ];
		} );

		var drawPlan = function () {
			var ids = mine().filter( function ( id ) {
				return byId[ id ];
			} );
			var clash = clashMap( ids );
			var moved = movedList();
			var days = {};
			ids.forEach( function ( id ) {
				var s = byId[ id ];
				days[ s.day ] = days[ s.day ] || { label: s.dayLabel, items: [] };
				days[ s.day ].items.push( s );
			} );
			var keys = Object.keys( days ).sort();
			if ( ! day || ! days[ day ] ) {
				day = keys[ 0 ] || '';
			}
			emptyBox.hidden = ids.length > 0;
			aside.hidden = ids.length === 0;
			list.hidden = ids.length === 0;
			tabs.hidden = keys.length < 2;
			tabs.innerHTML = keys.map( function ( k ) {
				return '<button type="button" class="tf2-chip" data-day="' + esc( k ) + '" aria-pressed="' + ( k === day ? 'true' : 'false' ) + '">' + esc( days[ k ].label ) + ' · ' + days[ k ].items.length + '</button>';
			} ).join( '' );
			var items = day ? days[ day ].items.sort( function ( a, b ) {
				return a.start - b.start;
			} ) : [];
			list.innerHTML = items.map( function ( s, i ) {
				var cls = 'tf2-plan__item' + ( clash[ s.id ] ? ' is-clash' : '' ) + ( moved.indexOf( s.id ) !== -1 ? ' is-moved' : '' ) + ( s.off ? ' is-off' : '' );
				var tag = s.off ? '<span class="tf2-tag tf2-tag--cili">Off tonight</span>' : moved.indexOf( s.id ) !== -1 ? '<span class="tf2-tag tf2-tag--gold">Moved</span>' : clash[ s.id ] ? '<span class="tf2-tag tf2-tag--cili">Clash</span>' : '';
				var sub = s.stage + ( moved.indexOf( s.id ) !== -1 && oldTime( s.id ) ? ' · was ' + oldTime( s.id ) : '' ) + ( clash[ s.id ] ? ' · with ' + clash[ s.id ].map( function ( c ) {
					return c.title;
				} ).join( ', ' ) : '' );
				return '<li class="' + cls + '" style="animation-delay:' + i * 40 + 'ms"><span class="tf2-plan__time">' + esc( s.time ) + '</span><div class="tf2-card tf2-plan__card"><span><b>' + ( s.url ? '<a href="' + esc( s.url ) + '">' + esc( s.title ) + '</a>' : esc( s.title ) ) + '</b><span>' + esc( sub ) + '</span></span><span class="tf2-row" style="gap:10px;flex-wrap:nowrap">' + tag + '<button type="button" class="tf-star" data-tf-star="' + esc( s.id ) + '" aria-pressed="true" aria-label="Remove ' + esc( s.title ) + ' from Jadual Aku"><span aria-hidden="true">' + icon( 'star-filled' ) + '</span></button></span></div></li>';
			} ).join( '' );
			// Aside: clash count and share link.
			var nClash = Object.keys( clash ).length;
			var clashCard = $( '[data-tf2-clashcard]', plan );
			if ( clashCard ) {
				clashCard.hidden = nClash === 0;
				$( '[data-tf2-clashcount]', clashCard ).textContent = nClash === 0 ? '' : ( Math.ceil( nClash / 2 ) === 1 ? '1 clash' : Math.ceil( nClash / 2 ) + ' clashes' );
				var first = Object.keys( clash )[ 0 ];
				$( '[data-tf2-clashtext]', clashCard ).textContent = first ? byId[ first ].title + ' overlaps ' + clash[ first ][ 0 ].title + '.' : '';
			}
			var ics = $( '[data-tf2-ics]', plan );
			if ( ics && ics.getAttribute( 'data-base' ) ) {
				ics.href = ics.getAttribute( 'data-base' ) + ( ics.getAttribute( 'data-base' ).indexOf( '?' ) === -1 ? '?' : '&' ) + 'ids=' + ids.join( ',' );
			}
			// Time-change notice at the top.
			if ( noticeBox ) {
				if ( moved.length ) {
					var m0 = byId[ moved[ 0 ] ];
					noticeBox.hidden = false;
					$( '[data-tf2-notice-text]', noticeBox ).innerHTML = '<b>' + esc( t( 'moved', 'Time change' ) ) + '.</b> ' + esc( m0.title ) + ' moved to ' + esc( m0.time ) + ( moved.length > 1 ? ', plus ' + ( moved.length - 1 ) + ' more' : '' ) + '. Your plan is updated.';
				} else {
					noticeBox.hidden = true;
				}
			}
		};
		plan.addEventListener( 'click', function ( e ) {
			var b = e.target.closest( '[data-tf2-plan-tabs] [data-day]' );
			if ( b ) {
				day = b.getAttribute( 'data-day' );
				drawPlan();
				return;
			}
			if ( e.target.closest( '[data-tf2-notice-ok]' ) ) {
				snapshot();
				drawPlan();
				return;
			}
			if ( e.target.closest( '[data-tf2-share]' ) ) {
				var url = cfg.jadual + '?jadual=' + mine().join( '.' );
				var done = function () {
					var lab = $( '[data-tf2-share] span', plan );
					if ( lab ) {
						lab.textContent = 'Link copied';
					}
					toast( 'blue', 'check', esc( t( 'copied', 'Link copied. Send it to your geng.' ) ), 3000 );
				};
				if ( navigator.share && window.matchMedia( '(pointer: coarse)' ).matches ) {
					navigator.share( { title: 'Jadual Aku · TAPAUfest', url: url } ).catch( function () {} );
				} else if ( navigator.clipboard ) {
					navigator.clipboard.writeText( url ).then( done, function () {
						window.prompt( 'Copy this link', url );
					} );
				} else {
					window.prompt( 'Copy this link', url );
				}
			}
		} );
		document.addEventListener( 'tf:jadual', drawPlan );

		// Paste a friend's link (empty state).
		var paste = $( '[data-tf2-paste]', plan );
		if ( paste ) {
			paste.addEventListener( 'submit', function ( e ) {
				e.preventDefault();
				var v = $( 'input', paste ).value;
				var m = v.match( /jadual=([\d.]+)/ );
				var err = $( '[data-tf2-paste-err]', paste );
				if ( ! m ) {
					err.hidden = false;
					return;
				}
				window.location.href = cfg.jadual + '?jadual=' + m[ 1 ];
			} );
		}

		// Shared plan: pick which sets to add.
		var imp = $( '[data-tf2-import]', plan );
		if ( shared.length && imp ) {
			var have = mine();
			var own = clashMap( have );
			var rows = $( '[data-tf2-import-rows]', imp );
			rows.innerHTML = shared.map( function ( id, i ) {
				var s = byId[ id ];
				var already = have.indexOf( id ) !== -1;
				var hit = clashMap( have.concat( [ id ] ) )[ id ];
				var tag = already ? '<span class="tf2-tag">Have it</span>' : hit ? '<span class="tf2-tag tf2-tag--cili">Clash</span>' : '<span class="tf2-tag tf2-tag--gold">New</span>';
				return '<label class="tf2-card tf2-import__row tf2-up" style="animation-delay:' + i * 40 + 'ms"><input type="checkbox" value="' + esc( id ) + '"' + ( already ? ' disabled checked' : ' checked' ) + '><span><b style="display:block;font:800 1.05rem var(--tf2-text)">' + esc( s.title ) + '</b><span style="font-size:.9rem">' + esc( s.dayLabel + ' · ' + s.time + ' · ' + s.stage ) + '</span></span>' + tag + '</label>';
			} ).join( '' );
			var nHave = shared.filter( function ( id ) {
				return have.indexOf( id ) !== -1;
			} ).length;
			var nClashNew = shared.filter( function ( id ) {
				return have.indexOf( id ) === -1 && clashMap( have.concat( [ id ] ) )[ id ];
			} ).length;
			$( '[data-tf2-import-note]', imp ).textContent = ( nHave ? nHave + ' already in your plan. ' : '' ) + ( nClashNew ? nClashNew + ' clash with yours.' : '' );
			var cta = $( '[data-tf2-import-yes]', imp );
			var count = function () {
				var n = $$( 'input:checked:not(:disabled)', rows ).length;
				cta.querySelector( 'span' ).textContent = n ? 'Add ' + n + ( n === 1 ? ' set' : ' sets' ) : 'Nothing picked';
				cta.disabled = n === 0;
			};
			rows.addEventListener( 'change', count );
			count();
			imp.hidden = false;
			$( '[data-tf2-plan-main]', plan ).hidden = true;
			var finish = function ( msg ) {
				params.delete( 'jadual' );
				var q = params.toString();
				window.history.replaceState( null, '', window.location.pathname + ( q ? '?' + q : '' ) );
				imp.hidden = true;
				$( '[data-tf2-plan-main]', plan ).hidden = false;
				drawPlan();
				toast( 'blue', 'check', esc( msg ), 3500 );
			};
			cta.addEventListener( 'click', function () {
				var add = $$( 'input:checked:not(:disabled)', rows ).map( function ( i ) {
					return i.value;
				} );
				var next = mine();
				add.forEach( function ( id ) {
					if ( next.indexOf( id ) === -1 ) {
						next.push( id );
					}
				} );
				setMine( next );
				snapshot( add );
				finish( add.length + ( add.length === 1 ? ' set added to Jadual Aku.' : ' sets added to Jadual Aku.' ) );
			} );
			$( '[data-tf2-import-no]', imp ).addEventListener( 'click', function () {
				finish( 'Kept your plan as is.' );
			} );
			void own;
		}
		drawPlan();
	}

	/* ---------- tickets: pick a pass, how many, total ---------- */
	$$( '[data-tf2-order]' ).forEach( function ( root ) {
		var rows = $$( '.tf2-tier--row:not(.is-out)', root );
		var qtyOut = $( '[data-tf2-qty]', root );
		var nameOut = $( '[data-tf2-sel]', root );
		var totalOut = $( '[data-tf2-total]', root );
		var go = $( '[data-tf2-go]', root );
		var qty = 1;
		var picked = rows[ 0 ] || null;
		var preset = new URLSearchParams( window.location.search ).get( 'tier' );
		rows.forEach( function ( r ) {
			r.setAttribute( 'tabindex', '0' );
			r.setAttribute( 'role', 'radio' );
			if ( preset && r.getAttribute( 'data-tier' ) === preset ) {
				picked = r;
			}
		} );
		var wrap = rows.length ? rows[ 0 ].parentNode : null;
		if ( wrap ) {
			wrap.setAttribute( 'role', 'radiogroup' );
			wrap.setAttribute( 'aria-label', 'Passes' );
		}
		function draw() {
			rows.forEach( function ( r ) {
				r.classList.toggle( 'is-picked', r === picked );
				r.setAttribute( 'aria-checked', r === picked ? 'true' : 'false' );
			} );
			if ( ! picked ) {
				return;
			}
			var price = parseInt( picked.getAttribute( 'data-price' ), 10 ) || 0;
			if ( nameOut ) {
				nameOut.textContent = picked.getAttribute( 'data-name' );
			}
			if ( qtyOut ) {
				qtyOut.textContent = qty;
			}
			if ( totalOut ) {
				totalOut.textContent = price ? 'RM' + ( price * qty ).toLocaleString() : 'RM TBC';
			}
			if ( go ) {
				go.href = go.getAttribute( 'data-base' ) + '?tier=' + picked.getAttribute( 'data-tier' ) + '&qty=' + qty;
			}
		}
		rows.forEach( function ( r ) {
			r.addEventListener( 'click', function () {
				picked = r;
				draw();
			} );
			r.addEventListener( 'keydown', function ( e ) {
				if ( e.key === 'Enter' || e.key === ' ' ) {
					e.preventDefault();
					picked = r;
					draw();
				}
			} );
		} );
		root.addEventListener( 'click', function ( e ) {
			var b = e.target.closest( '[data-tf2-step]' );
			if ( b ) {
				qty = Math.max( 1, Math.min( 6, qty + parseInt( b.getAttribute( 'data-tf2-step' ), 10 ) ) );
				draw();
			}
		} );
		draw();
	} );

	/* ---------- "Ping me" for a sold-out pass ---------- */
	$$( '[data-tf2-ping]' ).forEach( function ( form ) {
		form.addEventListener( 'submit', function ( e ) {
			if ( ! window.fetch ) {
				return;
			}
			e.preventDefault();
			var btn = $( 'button', form );
			btn.disabled = true;
			var fd = new FormData( form );
			fd.append( 'tf_ajax', '1' );
			fetch( form.getAttribute( 'action' ), { method: 'POST', body: fd, credentials: 'same-origin' } ).then( function ( r ) {
				return r.json();
			} ).then( function ( res ) {
				if ( res && res.success ) {
					form.innerHTML = '<p class="tf2-alert tf2-alert--blue">' + icon( 'check' ) + '<span>' + esc( t( 'pinged', 'You’re on the list. We email you first.' ) ) + '</span></p>';
				} else {
					btn.disabled = false;
					var err = $( '[data-tf2-ping-err]', form );
					if ( err ) {
						err.hidden = false;
					}
				}
			} ).catch( function () {
				btn.disabled = false;
				toast( 'cili', 'offline', esc( t( 'sendFail', 'Didn’t send. Try again.' ) ) );
			} );
		} );
	} );

	/* ---------- checkout hand-off ---------- */
	var hand = $( '[data-tf2-handoff]' );
	if ( hand ) {
		var dest = hand.getAttribute( 'data-tf2-handoff' );
		if ( dest ) {
			setTimeout( function () {
				window.location.href = dest;
			}, reduced ? 400 : 1600 );
		}
	}
	// Held-for timer on the "didn't finish" screen.
	$$( '[data-tf2-hold]' ).forEach( function ( el ) {
		var end = Date.now() + parseInt( el.getAttribute( 'data-tf2-hold' ), 10 ) * 1000;
		function tick() {
			var s = Math.max( 0, Math.round( ( end - Date.now() ) / 1000 ) );
			el.textContent = Math.floor( s / 60 ) + ':' + ( '0' + ( s % 60 ) ).slice( -2 );
			if ( s > 0 ) {
				setTimeout( tick, 1000 );
			}
		}
		tick();
	} );

	/* ---------- tabs (route modes, vendor zones) ---------- */
	$$( '[data-tf2-tabs]' ).forEach( function ( root ) {
		var btns = $$( '[data-tf2-tab]', root );
		function show( key ) {
			btns.forEach( function ( b ) {
				var on = b.getAttribute( 'data-tf2-tab' ) === key;
				b.setAttribute( 'aria-selected', on ? 'true' : 'false' );
				b.setAttribute( 'tabindex', on ? '0' : '-1' );
			} );
			$$( '[data-tf2-panel]', root ).forEach( function ( p ) {
				p.hidden = p.getAttribute( 'data-tf2-panel' ) !== key;
			} );
		}
		btns.forEach( function ( b, i ) {
			b.addEventListener( 'click', function () {
				show( b.getAttribute( 'data-tf2-tab' ) );
			} );
			b.addEventListener( 'keydown', function ( e ) {
				var d = e.key === 'ArrowRight' ? 1 : e.key === 'ArrowLeft' ? -1 : 0;
				if ( d ) {
					var nb = btns[ ( i + d + btns.length ) % btns.length ];
					nb.focus();
					show( nb.getAttribute( 'data-tf2-tab' ) );
				}
			} );
		} );
		var hash = window.location.hash.replace( '#', '' );
		var first = btns.filter( function ( b ) {
			return b.getAttribute( 'data-tf2-tab' ) === hash;
		} )[ 0 ] || btns.filter( function ( b ) {
			return b.getAttribute( 'aria-selected' ) === 'true';
		} )[ 0 ] || btns[ 0 ];
		if ( first ) {
			show( first.getAttribute( 'data-tf2-tab' ) );
		}
	} );

	/* ---------- pack list, kept on this phone ---------- */
	$$( '[data-tf2-pack]' ).forEach( function ( root ) {
		var key = 'tapaufest-pack';
		var got = store.get( key, {} ) || {};
		var boxes = $$( 'input[type="checkbox"]', root );
		var out = $( '[data-tf2-packed]', root );
		function count() {
			var n = boxes.filter( function ( b ) {
				return b.checked;
			} ).length;
			if ( out ) {
				out.textContent = n + ' of ' + boxes.length + ' packed' + ( n === boxes.length ? '. Siap!' : '' );
			}
		}
		boxes.forEach( function ( b ) {
			b.checked = !! got[ b.value ];
			b.addEventListener( 'change', function () {
				got[ b.value ] = b.checked;
				store.set( key, got );
				count();
			} );
		} );
		count();
	} );

	/* ---------- FAQ: topics + search ---------- */
	$$( '[data-tf2-faq]' ).forEach( function ( root ) {
		var nav = $$( '[data-tf2-group]', root );
		var groups = $$( '.tf2-faqgroup', root );
		var input = $( 'input[type="search"]', root );
		var none = $( '[data-tf2-faq-none]', root );
		function showGroup( slug ) {
			nav.forEach( function ( b ) {
				b.setAttribute( 'aria-pressed', b.getAttribute( 'data-tf2-group' ) === slug ? 'true' : 'false' );
			} );
			groups.forEach( function ( g ) {
				g.hidden = slug !== '*' && g.getAttribute( 'data-group' ) !== slug;
				$$( '.tf2-q', g ).forEach( function ( q ) {
					q.hidden = false;
				} );
			} );
			if ( none ) {
				none.hidden = true;
			}
		}
		nav.forEach( function ( b ) {
			b.addEventListener( 'click', function () {
				if ( input ) {
					input.value = '';
				}
				showGroup( b.getAttribute( 'data-tf2-group' ) );
			} );
		} );
		if ( input ) {
			input.addEventListener( 'input', function () {
				var q = input.value.trim().toLowerCase();
				if ( ! q ) {
					showGroup( nav[ 0 ] ? nav[ 0 ].getAttribute( 'data-tf2-group' ) : '*' );
					return;
				}
				nav.forEach( function ( b ) {
					b.setAttribute( 'aria-pressed', 'false' );
				} );
				var hits = 0;
				groups.forEach( function ( g ) {
					var any = false;
					$$( '.tf2-q', g ).forEach( function ( item ) {
						var ok = item.textContent.toLowerCase().indexOf( q ) !== -1;
						item.hidden = ! ok;
						if ( ok ) {
							any = true;
							hits++;
						}
					} );
					g.hidden = ! any;
				} );
				if ( none ) {
					none.hidden = hits > 0;
					var term = $( '[data-tf2-term]', none );
					if ( term ) {
						term.textContent = input.value.trim();
					}
				}
			} );
		}
		if ( nav[ 0 ] ) {
			showGroup( nav[ 0 ].getAttribute( 'data-tf2-group' ) );
		}
	} );

	/* ---------- stall form: inline errors, summary, keep data, retry ---------- */
	$$( '[data-tf2-apply]' ).forEach( function ( form ) {
		var summary = $( '[data-tf2-summary]', form );
		var status = $( '[data-tf2-status]', form );
		var btn = $( 'button[type="submit"]', form );
		var label = btn ? btn.innerHTML : '';
		function fieldOf( el ) {
			return el.closest( '.tf2-field' ) || el.closest( 'fieldset' );
		}
		function setErr( el, msg ) {
			var f = fieldOf( el );
			if ( ! f ) {
				return;
			}
			f.classList.toggle( 'is-bad', !! msg );
			var e = $( '.tf2-field__err', f );
			if ( e ) {
				e.textContent = msg || '';
				e.hidden = ! msg;
			}
			$$( 'input, select, textarea', f ).forEach( function ( i ) {
				if ( msg ) {
					i.setAttribute( 'aria-invalid', 'true' );
				} else {
					i.removeAttribute( 'aria-invalid' );
				}
			} );
		}
		function check( el ) {
			var v = ( el.value || '' ).trim();
			if ( el.type === 'radio' ) {
				var on = $( 'input[name="' + el.name + '"]:checked', form );
				return el.required && ! on ? 'Pick one.' : '';
			}
			if ( el.type === 'checkbox' ) {
				return el.required && ! el.checked ? 'Tick this so we can store your details.' : '';
			}
			if ( el.required && ! v ) {
				return 'We need this one.';
			}
			if ( v && el.type === 'email' && ! /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test( v ) ) {
				return 'That email doesn’t look right.';
			}
			if ( v && el.type === 'tel' && v.replace( /\D/g, '' ).length < 9 ) {
				return 'Include the country code, like +60 12 345 6789.';
			}
			return '';
		}
		function validate() {
			var bad = [];
			var seen = {};
			$$( 'input, select, textarea', form ).forEach( function ( el ) {
				if ( el.type === 'hidden' || el.closest( '.tf2-hp' ) || seen[ el.name ] ) {
					return;
				}
				seen[ el.name ] = true;
				var msg = check( el );
				setErr( el, msg );
				if ( msg ) {
					var f = fieldOf( el );
					var lab = f ? ( $( 'legend', f ) || $( 'label', f ) ) : null;
					bad.push( { el: el, name: el.getAttribute( 'data-tf2-name' ) || ( lab ? lab.textContent.replace( '*', '' ).trim() : el.name ) } );
				}
			} );
			return bad;
		}
		function showSummary( bad ) {
			if ( ! summary ) {
				return;
			}
			if ( ! bad.length ) {
				summary.hidden = true;
				return;
			}
			summary.hidden = false;
			summary.innerHTML = icon( 'alert' ) + '<div><b>' + ( bad.length === 1 ? esc( t( 'fixOne', '1 thing to fix before we can send' ) ) : bad.length + ' ' + esc( t( 'fix', 'things to fix before we can send' ) ) ) + ':</b> ' + bad.map( function ( b ) {
				return '<a href="#' + esc( b.el.id ) + '">' + esc( b.name ) + '</a>';
			} ).join( ', ' ) + '</div>';
			form.classList.remove( 'tf2-shake' );
			void form.offsetWidth;
			form.classList.add( 'tf2-shake' );
			summary.focus();
		}
		form.addEventListener( 'blur', function ( e ) {
			if ( e.target.matches( 'input, select, textarea' ) && e.target.closest( '.is-bad' ) ) {
				setErr( e.target, check( e.target ) );
			}
		}, true );
		form.addEventListener( 'change', function ( e ) {
			if ( e.target.closest( '.is-bad' ) ) {
				setErr( e.target, check( e.target ) );
			}
		} );
		form.setAttribute( 'novalidate', '' );
		var cool = 0;
		form.addEventListener( 'submit', function ( e ) {
			var bad = validate();
			if ( bad.length ) {
				e.preventDefault();
				showSummary( bad );
				return;
			}
			showSummary( [] );
			if ( ! window.fetch || Date.now() < cool ) {
				if ( Date.now() < cool ) {
					e.preventDefault();
				}
				return;
			}
			e.preventDefault();
			var fd = new FormData( form );
			fd.append( 'tf_ajax', '1' );
			btn.disabled = true;
			btn.innerHTML = '<span class="tf2-spin tf2-spin--sm" aria-hidden="true"></span><span>' + esc( t( 'sending', 'Sending…' ) ) + '</span>';
			status.hidden = true;
			fetch( form.getAttribute( 'action' ), { method: 'POST', body: fd, credentials: 'same-origin' } ).then( function ( r ) {
				return r.json().then( function ( j ) {
					return { code: r.status, body: j };
				} );
			} ).then( function ( res ) {
				if ( res.body && res.body.success ) {
					window.location.href = res.body.data.redirect + '#apply';
					return;
				}
				var d = ( res.body && res.body.data ) || {};
				if ( d.apply_error === 'missing' && d.apply_fields ) {
					var list = d.apply_fields.split( ',' ).map( function ( k ) {
						var el = form.elements[ k ];
						el = el && el.length && ! el.tagName ? el[ 0 ] : el;
						if ( el ) {
							setErr( el, 'Check this one.' );
						}
						return el ? { el: el, name: k } : null;
					} ).filter( Boolean );
					showSummary( list );
					reset();
					return;
				}
				if ( d.apply_error === 'duplicate' || d.apply_error === 'closed' || d.apply_error === 'expired' ) {
					window.location.href = window.location.pathname + '?apply_error=' + d.apply_error + ( d.since ? '&since=' + encodeURIComponent( d.since ) : '' ) + '#apply';
					return;
				}
				if ( d.apply_error === 'slow' ) {
					limited( 60 );
					return;
				}
				failed();
			} ).catch( failed );
		} );
		function reset() {
			btn.disabled = false;
			btn.innerHTML = label;
		}
		function failed() {
			reset();
			btn.innerHTML = icon( 'retry' ) + '<span>' + esc( t( 'again', 'Send again' ) ) + '</span>';
			status.className = 'tf2-alert tf2-alert--cili';
			status.innerHTML = icon( 'offline' ) + '<div>' + esc( t( 'sendFail', 'Didn’t send. Connection dropped. Your answers are still here.' ) ) + '</div>';
			status.hidden = false;
		}
		function limited( s ) {
			cool = Date.now() + s * 1000;
			status.className = 'tf2-alert tf2-alert--gold';
			status.innerHTML = icon( 'clock' ) + '<div>' + esc( t( 'slow', 'Too many tries. Wait a minute, then send again.' ) ) + '</div>';
			status.hidden = false;
			btn.disabled = true;
			( function tick() {
				var left = Math.max( 0, Math.round( ( cool - Date.now() ) / 1000 ) );
				btn.innerHTML = '<span>' + esc( t( 'againIn', 'Send again in' ) ) + ' 0:' + ( '0' + left ).slice( -2 ) + '</span>';
				if ( left > 0 ) {
					setTimeout( tick, 1000 );
				} else {
					reset();
					btn.innerHTML = icon( 'retry' ) + '<span>' + esc( t( 'again', 'Send again' ) ) + '</span>';
				}
			} )();
		}
	} );

	/* ---------- on now: next starred set ---------- */
	var nextBox = $( '[data-tf2-nextpick]' );
	if ( nextBox && ALL.length ) {
		var drawNext = function () {
			var dayNow = nextBox.getAttribute( 'data-day' );
			var minNow = parseInt( nextBox.getAttribute( 'data-min' ), 10 );
			var picks = mine().map( function ( id ) {
				return byId[ id ];
			} ).filter( function ( s ) {
				return s && s.day === dayNow && s.end > minNow && ! s.off;
			} ).sort( function ( a, b ) {
				return a.start - b.start;
			} );
			var title = $( '[data-tf2-nextpick-title]', nextBox );
			var note = $( '[data-tf2-nextpick-note]', nextBox );
			if ( picks.length ) {
				title.hidden = false;
				title.textContent = picks[ 0 ].title + ' · ' + picks[ 0 ].time;
				note.textContent = picks[ 0 ].stage + ( picks[ 0 ].start <= minNow ? '. On now, go go go.' : '.' );
			}
		};
		drawNext();
		document.addEventListener( 'tf:jadual', drawNext );
	}

	/* ---------- map: the picked place in the side panel ---------- */
	var panel = $( '[data-tf2-mappanel]' );
	if ( panel ) {
		document.addEventListener( 'tf:map-pick', function ( e ) {
			var card = document.querySelector( '.tf-map__card[data-card="' + e.detail.key + '"]' );
			if ( ! card ) {
				return;
			}
			panel.innerHTML = card.innerHTML;
			panel.classList.remove( 'tf2-up' );
			void panel.offsetWidth;
			panel.classList.add( 'tf2-up' );
		} );
	}

	/* ---------- share this page ---------- */
	document.addEventListener( 'click', function ( e ) {
		var b = e.target.closest( '[data-tf2-share-page]' );
		if ( ! b ) {
			return;
		}
		var url = window.location.href.split( '#' )[ 0 ];
		if ( navigator.share ) {
			navigator.share( { title: b.getAttribute( 'data-title' ) || document.title, url: url } ).catch( function () {} );
		} else if ( navigator.clipboard ) {
			navigator.clipboard.writeText( url ).then( function () {
				toast( 'blue', 'check', 'Link copied.', 2500 );
			} );
		}
	} );

	/* ---------- vendor tag filter ---------- */
	$$( '[data-tf2-vfilter]' ).forEach( function ( root ) {
		var chips = $$( '[data-tf2-vtag]', root );
		chips.forEach( function ( c ) {
			c.addEventListener( 'click', function () {
				var on = c.getAttribute( 'aria-pressed' ) !== 'true';
				chips.forEach( function ( x ) {
					x.setAttribute( 'aria-pressed', 'false' );
				} );
				c.setAttribute( 'aria-pressed', on ? 'true' : 'false' );
				var tag = on ? c.getAttribute( 'data-tf2-vtag' ) : '';
				$$( '.tf2-vendor', root ).forEach( function ( v ) {
					v.hidden = !! tag && ( v.getAttribute( 'data-tags' ) || '' ).toLowerCase().indexOf( tag.toLowerCase() ) === -1;
				} );
				$$( '[data-tf2-panel]', root ).forEach( function ( p ) {
					var vis = $$( '.tf2-vendor', p ).filter( function ( v ) {
						return ! v.hidden;
					} ).length;
					var e = $( '[data-tf2-vnone]', p );
					if ( e ) {
						e.hidden = vis > 0;
					}
				} );
			} );
		} );
	} );
	/* ---------- set times on phones: one stage at a time ---------- */
	$$( '[data-tf2-stagelist]' ).forEach( function ( list ) {
		var chips = $$( '[data-tf2-stage]', list );
		var cards = $$( '.tf2-ttstage', list );
		chips.forEach( function ( c ) {
			c.addEventListener( 'click', function () {
				var key = c.getAttribute( 'data-tf2-stage' );
				chips.forEach( function ( x ) {
					x.setAttribute( 'aria-pressed', x === c ? 'true' : 'false' );
				} );
				cards.forEach( function ( card ) {
					card.hidden = !! key && card.getAttribute( 'data-stage' ) !== key;
					if ( ! card.hidden ) {
						motion( card, 'tf2-swap' );
					}
				} );
			} );
		} );
	} );

	/* ---------- motion: reveal on scroll, panel swaps, little pops ---------- */
	function motion( el, cls ) {
		if ( reduced || ! el ) {
			return;
		}
		el.classList.remove( cls );
		void el.offsetWidth;
		el.classList.add( cls );
		el.addEventListener( 'animationend', function done() {
			el.classList.remove( cls );
			el.removeEventListener( 'animationend', done );
		} );
	}
	if ( ! reduced && 'IntersectionObserver' in window ) {
		var groups = [
			'.tf2-auto > *',
			'.tf2-tiers > *',
			'.tf2-steps > *',
			'.tf2-pack > *',
			'.tf2-ttstage',
			'.tf2-faqgroup',
			'.tf2-gallery figure',
			'.tf2-main > .tf2-two > *',
			'.tf2-sec .tf2-two > *',
			'.tf2-main > .tf2-stack > *',
			'.tf2-route-step',
			'.tf2-nolist li'
		].join( ',' );
		var targets = $$( groups ).filter( function ( el ) {
			return ! el.closest( '.tf2-hero' ) && ! el.classList.contains( 'tf2-up' ) && ! el.closest( '.tf2-rv' ) && ! el.closest( '.tf2-order' );
		} );
		var io = new IntersectionObserver( function ( entries ) {
			var batch = entries.filter( function ( e ) {
				return e.isIntersecting;
			} );
			batch.forEach( function ( e, i ) {
				var el = e.target;
				el.style.setProperty( '--tf2-d', Math.min( i, 6 ) * 60 + 'ms' );
				el.classList.add( 'is-in' );
				io.unobserve( el );
				setTimeout( function () {
					el.classList.remove( 'tf2-rv', 'is-in' );
					el.style.removeProperty( '--tf2-d' );
				}, 700 + Math.min( i, 6 ) * 60 );
			} );
		}, { rootMargin: '0px 0px -8% 0px', threshold: 0.08 } );
		targets.forEach( function ( el ) {
			el.classList.add( 'tf2-rv' );
			io.observe( el );
		} );
	}
	// Tabs (days, route modes, vendor zones): the shown panel slides in.
	document.addEventListener( 'click', function ( e ) {
		var tab = e.target.closest( '[data-tf2-tab]' );
		if ( tab ) {
			var root = tab.closest( '[data-tf2-tabs]' );
			var p = root && $( '[data-tf2-panel="' + tab.getAttribute( 'data-tf2-tab' ) + '"]', root );
			motion( p, 'tf2-swap' );
			return;
		}
		if ( e.target.closest( '.tf2-tier--row:not(.is-out), [data-tf2-step]' ) ) {
			setTimeout( function () {
				motion( $( '[data-tf2-total]' ), 'tf2-pop' );
			}, 0 );
		}
	} );
	// Pack list: a small cheer when everything is in the bag.
	$$( '[data-tf2-pack]' ).forEach( function ( root ) {
		root.addEventListener( 'change', function () {
			var boxes = $$( 'input[type="checkbox"]', root );
			if ( boxes.every( function ( b ) {
				return b.checked;
			} ) ) {
				motion( $( '[data-tf2-packed]', root ), 'tf2-cheer' );
			}
		} );
	} );

	/* ---------- lineup reel: arrows, jumps, and stories on phones ---------- */
	$$( '[data-tf2-reel-root]' ).forEach( function ( root ) {
		var reel = $( '[data-tf2-reel]', root );
		if ( ! reel ) {
			return;
		}
		var phone = window.matchMedia( '(max-width: 700px)' );
		var arrows = $$( '[data-tf2-reel-step]', root );
		function padLeft() {
			return parseFloat( getComputedStyle( reel ).paddingLeft ) || 0;
		}
		function goTo( el ) {
			if ( el ) {
				reel.scrollTo( { left: el.offsetLeft - padLeft(), behavior: reduced ? 'auto' : 'smooth' } );
			}
		}
		function syncArrows() {
			var max = reel.scrollWidth - reel.clientWidth - 2;
			arrows.forEach( function ( b ) {
				b.disabled = b.getAttribute( 'data-tf2-reel-step' ) === '-1' ? reel.scrollLeft < 2 : reel.scrollLeft > max;
			} );
		}
		arrows.forEach( function ( b ) {
			b.addEventListener( 'click', function () {
				var dir = parseInt( b.getAttribute( 'data-tf2-reel-step' ), 10 );
				reel.scrollBy( { left: dir * Math.max( 300, reel.clientWidth * 0.6 ), behavior: reduced ? 'auto' : 'smooth' } );
			} );
		} );
		reel.addEventListener( 'scroll', syncArrows, { passive: true } );
		syncArrows();
		$$( '[data-tf2-reel-jump]', root ).forEach( function ( a ) {
			a.addEventListener( 'click', function ( e ) {
				var el = document.getElementById( a.getAttribute( 'data-tf2-reel-jump' ) );
				if ( el ) {
					e.preventDefault();
					goTo( el );
					var top = reel.getBoundingClientRect().top;
					if ( top < 0 || top > window.innerHeight * 0.5 ) {
						window.scrollTo( { top: window.scrollY + top - 120, behavior: reduced ? 'auto' : 'smooth' } );
					}
				}
			} );
		} );
		// Keyboard: arrow keys move one card while the reel has focus.
		reel.addEventListener( 'keydown', function ( e ) {
			if ( e.key !== 'ArrowRight' && e.key !== 'ArrowLeft' ) {
				return;
			}
			e.preventDefault();
			var cards = $$( '.tf2-poster, .tf2-reeldiv', reel );
			var x = reel.scrollLeft + padLeft() + 4;
			var cur = 0;
			cards.forEach( function ( c, i ) {
				if ( c.offsetLeft <= x ) {
					cur = i;
				}
			} );
			goTo( cards[ Math.max( 0, Math.min( cards.length - 1, cur + ( e.key === 'ArrowRight' ? 1 : -1 ) ) ) ] );
		} );

		/* Stories: full screen, one act at a time, in running order. */
		var box = $( '[data-tf2-stories]', root );
		var dataEl = document.getElementById( 'tf2-story-data' );
		if ( ! box || ! dataEl ) {
			return;
		}
		var acts = [];
		try {
			acts = JSON.parse( dataEl.textContent ) || [];
		} catch ( e ) {}
		if ( ! acts.length ) {
			return;
		}
		var media = $( '[data-tf2-st-media]', box );
		var body = $( '[data-tf2-st-body]', box );
		var segs = $( '[data-tf2-st-segs]', box );
		var pos = $( '[data-tf2-st-pos]', box );
		var MS = 7000;
		var idx = 0;
		var timer = null;
		var opener = null;
		segs.innerHTML = acts.map( function () {
			return '<i></i>';
		} ).join( '' );
		function starred( id ) {
			return mine().indexOf( String( id ) ) !== -1;
		}
		function starHtml( a ) {
			var on = starred( a.id );
			return '<button type="button" class="tf2-btn ' + ( on ? 'tf2-btn--paper' : 'tf2-btn--cili' ) + '" data-tf-star="' + esc( a.id ) + '" data-tf-star-label aria-pressed="' + ( on ? 'true' : 'false' ) + '">' + icon( on ? 'star-filled' : 'star' ) + ' <span>' + ( on ? 'In your Jadual Aku' : 'Add to Jadual Aku' ) + '</span></button>';
		}
		function draw() {
			var a = acts[ idx ];
			var nx = acts[ idx + 1 ];
			media.innerHTML = a.img ? '<img src="' + esc( a.img ) + '" alt="">' : '<span class="tf2-poster__ph" aria-hidden="true">?</span>';
			body.innerHTML = ( a.label ? '<span class="tf2-tag">' + esc( a.label ) + '</span>' : '' ) +
				'<span class="tf2-poster__pill" style="align-self:flex-start">' + esc( [ a.dayShort, a.time, a.stage ].filter( Boolean ).join( ' · ' ) ) + '</span>' +
				'<a class="tf2-stories__name' + ( a.name.length > 16 ? ' is-long' : '' ) + ( a.main ? ' is-main' : '' ) + '" href="' + esc( a.url ) + '">' + esc( a.name ) + '</a>' +
				( a.blurb ? '<p class="tf2-stories__blurb">' + esc( a.blurb ) + '</p>' : '' ) +
				'<div class="tf2-stories__acts">' + ( a.id ? starHtml( a ) : '' ) +
				( a.listen ? '<a class="tf2-btn tf2-btn--paper" href="' + esc( a.listen ) + '" target="_blank" rel="noopener" aria-label="Listen to ' + esc( a.name ) + ' (opens in a new tab)" style="padding:0 16px">' + icon( 'play' ) + '</a>' : '' ) + '</div>' +
				( nx ? '<button type="button" class="tf2-stories__next" data-tf2-st-step="1">' + ( nx.img ? '<img src="' + esc( nx.img ) + '" alt="">' : '' ) + '<span><small>UP NEXT · ' + esc( nx.dayShort + ' ' + nx.time ) + '</small><b>' + esc( nx.name ) + '</b></span></button>' : '' );
			body.style.animation = 'none';
			void body.offsetWidth;
			body.style.animation = '';
			$$( 'i', segs ).forEach( function ( s, i ) {
				s.className = i < idx ? 'is-done' : i === idx ? 'is-now' : '';
			} );
			pos.textContent = a.dayShort + ' · ' + ( idx + 1 ) + ' of ' + acts.length + ' · ' + a.name;
			if ( nx && nx.img ) {
				new Image().src = nx.img;
			}
			arm();
		}
		function arm() {
			clearTimeout( timer );
			if ( reduced || box.classList.contains( 'is-paused' ) ) {
				return;
			}
			box.style.setProperty( '--tf2-st-ms', MS + 'ms' );
			timer = setTimeout( function () {
				step( 1 );
			}, MS );
		}
		function step( d ) {
			var n = idx + d;
			if ( n >= acts.length ) {
				close();
				return;
			}
			idx = Math.max( 0, n );
			draw();
		}
		function open( i, from ) {
			idx = Math.max( 0, Math.min( acts.length - 1, i ) );
			opener = from || document.activeElement;
			box.hidden = false;
			document.documentElement.classList.add( 'tf2-locked' );
			box.classList.remove( 'is-paused' );
			draw();
			$( '[data-tf2-st-close]', box ).focus();
		}
		function close() {
			clearTimeout( timer );
			box.hidden = true;
			document.documentElement.classList.remove( 'tf2-locked' );
			var card = reel.querySelector( '[data-tf2-story="' + idx + '"]' );
			if ( card && phone.matches ) {
				goTo( card );
			}
			if ( opener && opener.focus ) {
				opener.focus();
			}
		}
		// Phones: tapping a card opens its story (stars and links still work).
		reel.addEventListener( 'click', function ( e ) {
			if ( ! phone.matches || e.target.closest( '[data-tf-star], .tf2-poster__ico' ) ) {
				return;
			}
			var card = e.target.closest( '[data-tf2-story]' );
			if ( card ) {
				e.preventDefault();
				open( parseInt( card.getAttribute( 'data-tf2-story' ), 10 ), card );
			}
		} );
		$$( '[data-tf2-story-open]', root ).forEach( function ( b ) {
			b.addEventListener( 'click', function () {
				open( parseInt( b.getAttribute( 'data-tf2-story-open' ), 10 ) || 0, b );
			} );
		} );
		box.addEventListener( 'click', function ( e ) {
			var s = e.target.closest( '[data-tf2-st-step]' );
			if ( s ) {
				step( parseInt( s.getAttribute( 'data-tf2-st-step' ), 10 ) );
				return;
			}
			if ( e.target.closest( '[data-tf2-st-close]' ) ) {
				close();
				return;
			}
			if ( e.target.closest( '[data-tf-star]' ) ) {
				arm();
			}
		} );
		// Hold to pause, like stories everywhere.
		var holdT = null;
		box.addEventListener( 'pointerdown', function ( e ) {
			if ( e.target.closest( 'a, [data-tf-star], [data-tf2-st-close]' ) ) {
				return;
			}
			holdT = setTimeout( function () {
				box.classList.add( 'is-paused' );
				clearTimeout( timer );
			}, 250 );
		} );
		[ 'pointerup', 'pointercancel', 'pointerleave' ].forEach( function ( ev ) {
			box.addEventListener( ev, function () {
				clearTimeout( holdT );
				if ( box.classList.contains( 'is-paused' ) ) {
					box.classList.remove( 'is-paused' );
					arm();
				}
			} );
		} );
		// Swipe down to close.
		var y0 = null;
		box.addEventListener( 'touchstart', function ( e ) {
			y0 = e.touches[ 0 ].clientY;
		}, { passive: true } );
		box.addEventListener( 'touchend', function ( e ) {
			if ( y0 !== null && e.changedTouches[ 0 ].clientY - y0 > 90 ) {
				close();
			}
			y0 = null;
		}, { passive: true } );
		document.addEventListener( 'keydown', function ( e ) {
			if ( box.hidden ) {
				return;
			}
			if ( e.key === 'Escape' ) {
				close();
			} else if ( e.key === 'ArrowRight' ) {
				step( 1 );
			} else if ( e.key === 'ArrowLeft' ) {
				step( -1 );
			} else if ( e.key === 'Tab' ) {
				var f = $$( 'button, a[href]', box ).filter( function ( n ) {
					return n.offsetParent !== null;
				} );
				if ( f.length && e.shiftKey && document.activeElement === f[ 0 ] ) {
					e.preventDefault();
					f[ f.length - 1 ].focus();
				} else if ( f.length && ! e.shiftKey && document.activeElement === f[ f.length - 1 ] ) {
					e.preventDefault();
					f[ 0 ].focus();
				}
			}
		} );
		document.addEventListener( 'visibilitychange', function () {
			if ( document.hidden ) {
				clearTimeout( timer );
			} else if ( ! box.hidden ) {
				arm();
			}
		} );
	} );

} )();
