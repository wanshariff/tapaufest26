/* TAPAUfest offline helper. Network first: a saved copy only shows when there's no signal. */
var VERSION = '__TF_VERSION__';
var HOME = '__TF_HOME__';
var PAGES = 'tf-pages-v1';
var ASSETS = 'tf-assets-' + VERSION;
var PRECACHE = [ HOME, HOME + 'timetable/', HOME + 'jadual-aku/', HOME + 'map/', HOME + 'now/', HOME + 'help/' ];

self.addEventListener( 'install', function ( e ) {
	e.waitUntil(
		caches.open( PAGES ).then( function ( c ) {
			return Promise.all( PRECACHE.map( function ( u ) {
				return fetch( u, { credentials: 'same-origin' } ).then( function ( r ) {
					return r.ok ? stamp( r ).then( function ( s ) { return c.put( u, s ); } ) : null;
				} ).catch( function () {} );
			} ) );
		} ).then( function () { return self.skipWaiting(); } )
	);
} );

self.addEventListener( 'activate', function ( e ) {
	e.waitUntil(
		caches.keys().then( function ( keys ) {
			return Promise.all( keys.filter( function ( k ) {
				return k.indexOf( 'tf-assets-' ) === 0 && k !== ASSETS;
			} ).map( function ( k ) { return caches.delete( k ); } ) );
		} ).then( function () { return self.clients.claim(); } )
	);
} );

// Remember when a page was saved, so the offline banner can say so.
function stamp( res ) {
	return res.clone().blob().then( function ( body ) {
		var h = new Headers( res.headers );
		h.set( 'X-TF-Saved', new Date().toISOString() );
		return new Response( body, { status: res.status, statusText: res.statusText, headers: h } );
	} );
}

function offlinePage() {
	var html = '<!doctype html><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Offline</title>' +
		'<style>body{margin:0;min-height:100vh;display:grid;place-items:center;background:#6F310B;color:#F8DAA5;font:16px/1.5 system-ui,sans-serif;text-align:center;padding:24px}' +
		'h1{font-size:34px;margin:0 0 8px}a,button{display:inline-block;margin:8px;min-height:48px;padding:0 18px;border:3px solid #1E1208;border-radius:6px;background:#A82E05;color:#FFF2DA;font:700 16px system-ui;text-decoration:none;line-height:42px;cursor:pointer}a.p{background:#F8DAA5;color:#1E1208}</style>' +
		'<div><h1>__TF_OFFLINE_TITLE__</h1><p>__TF_OFFLINE_TEXT__</p><button onclick="location.reload()">__TF_RETRY__</button><a class="p" href="' + HOME + 'timetable/">Set times</a><a class="p" href="' + HOME + 'jadual-aku/">Jadual Aku</a></div>';
	return new Response( html, { status: 503, headers: { 'Content-Type': 'text/html; charset=utf-8' } } );
}

self.addEventListener( 'fetch', function ( e ) {
	var req = e.request;
	if ( req.method !== 'GET' ) {
		return;
	}
	var url = new URL( req.url );
	if ( url.origin !== self.location.origin || url.pathname.indexOf( '/wp-admin' ) === 0 || url.pathname.indexOf( '/wp-json' ) === 0 || url.search.indexOf( 'preview' ) !== -1 ) {
		return;
	}
	if ( req.mode === 'navigate' ) {
		e.respondWith(
			fetch( req ).then( function ( res ) {
				if ( res.ok && res.type === 'basic' ) {
					var copy = res.clone();
					stamp( copy ).then( function ( s ) {
						caches.open( PAGES ).then( function ( c ) { c.put( url.origin + url.pathname, s ); } );
					} );
				}
				return res;
			} ).catch( function () {
				return caches.open( PAGES ).then( function ( c ) {
					return c.match( url.origin + url.pathname ).then( function ( hit ) {
						return hit || offlinePage();
					} );
				} );
			} )
		);
		return;
	}
	if ( /\.(css|js|woff2?|svg|webp|png|jpe?g)$/.test( url.pathname ) && url.pathname.indexOf( '/wp-content/' ) !== -1 ) {
		e.respondWith(
			caches.open( ASSETS ).then( function ( c ) {
				return c.match( req ).then( function ( hit ) {
					var net = fetch( req ).then( function ( res ) {
						if ( res.ok ) {
							c.put( req, res.clone() );
						}
						return res;
					} );
					return hit || net;
				} );
			} )
		);
	}
} );
