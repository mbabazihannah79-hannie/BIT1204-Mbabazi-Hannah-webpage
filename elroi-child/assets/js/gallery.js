/**
 * El Roi Khadoshi Motel — gallery.
 *
 * 1. Filter buttons above any Group with the class "erk-gallery", made from the
 *    photos' categories (classes erk-cat-motel, erk-cat-rooms, erk-cat-dining, erk-cat-area).
 * 2. A full-screen photo viewer with previous/next, captions and keyboard keys, for
 *    photos inside "erk-lightbox" groups and on the room pages (links with class js-lightbox).
 */
( function () {
	'use strict';

	var LABELS = { all: 'All', motel: 'The Motel', rooms: 'Rooms', dining: 'Dining & Bar', area: 'Around Kyenjojo' };

	function category( fig ) {
		var m = fig.className.match( /erk-cat-([a-z0-9-]+)/ );
		return m ? m[ 1 ] : '';
	}

	/* Filters */
	document.querySelectorAll( '.erk-gallery' ).forEach( function ( grid ) {
		var figures = Array.prototype.slice.call( grid.querySelectorAll( ':scope > .wp-block-image' ) );
		var cats = [];
		figures.forEach( function ( f ) {
			var c = category( f );
			if ( c && cats.indexOf( c ) === -1 ) {
				cats.push( c );
			}
		} );
		if ( cats.length < 2 ) {
			return;
		}
		var bar = document.createElement( 'div' );
		bar.className = 'erk-filters';
		[ 'all' ].concat( cats ).forEach( function ( c ) {
			var b = document.createElement( 'button' );
			b.type = 'button';
			b.className = 'erk-filter' + ( c === 'all' ? ' is-active' : '' );
			b.textContent = LABELS[ c ] || c.charAt( 0 ).toUpperCase() + c.slice( 1 );
			b.addEventListener( 'click', function () {
				bar.querySelectorAll( '.erk-filter' ).forEach( function ( x ) { x.classList.remove( 'is-active' ); } );
				b.classList.add( 'is-active' );
				figures.forEach( function ( f ) {
					f.classList.toggle( 'is-hidden', c !== 'all' && category( f ) !== c );
				} );
			} );
			bar.appendChild( b );
		} );
		grid.parentNode.insertBefore( bar, grid );
	} );

	/* Viewer */
	var box = document.createElement( 'div' );
	box.className = 'erk-viewer';
	box.hidden = true;
	box.setAttribute( 'role', 'dialog' );
	box.setAttribute( 'aria-modal', 'true' );
	box.setAttribute( 'aria-label', 'Photo viewer' );
	box.innerHTML = '<button type="button" class="erk-viewer-close" aria-label="Close">&times;</button>' +
		'<button type="button" class="erk-viewer-prev" aria-label="Previous">&#8249;</button>' +
		'<img src="" alt="">' +
		'<button type="button" class="erk-viewer-next" aria-label="Next">&#8250;</button>' +
		'<p class="erk-viewer-caption"></p>';
	document.body.appendChild( box );
	var img = box.querySelector( 'img' );
	var cap = box.querySelector( '.erk-viewer-caption' );
	var list = [];
	var idx = 0;
	var lastFocus = null;

	function items() {
		var out = [];
		document.querySelectorAll( '.erk-lightbox .wp-block-image:not(.is-hidden), a.js-lightbox' ).forEach( function ( el ) {
			var pic = el.tagName === 'A' ? el.querySelector( 'img' ) : el.querySelector( 'img' );
			if ( ! pic ) {
				return;
			}
			var capEl = el.querySelector( 'figcaption' );
			out.push( {
				el: el,
				src: el.tagName === 'A' ? el.href : ( pic.currentSrc || pic.src ),
				caption: el.getAttribute( 'data-caption' ) || ( capEl ? capEl.textContent : pic.alt )
			} );
		} );
		return out;
	}
	function show( k ) {
		idx = ( k + list.length ) % list.length;
		img.src = list[ idx ].src;
		img.alt = list[ idx ].caption || '';
		cap.textContent = list[ idx ].caption || '';
	}
	function open( el ) {
		list = items();
		var at = 0;
		list.forEach( function ( it, x ) { if ( it.el === el ) { at = x; } } );
		lastFocus = document.activeElement;
		box.hidden = false;
		document.body.style.overflow = 'hidden';
		show( at );
		box.querySelector( '.erk-viewer-close' ).focus();
	}
	function close() {
		box.hidden = true;
		document.body.style.overflow = '';
		if ( lastFocus ) {
			lastFocus.focus();
		}
	}

	document.addEventListener( 'click', function ( e ) {
		var a = e.target.closest( 'a.js-lightbox' );
		var fig = e.target.closest( '.erk-lightbox .wp-block-image' );
		if ( a ) {
			e.preventDefault();
			open( a );
		} else if ( fig && ! e.target.closest( 'a' ) ) {
			open( fig );
		}
	} );
	box.querySelector( '.erk-viewer-close' ).addEventListener( 'click', close );
	box.querySelector( '.erk-viewer-prev' ).addEventListener( 'click', function () { show( idx - 1 ); } );
	box.querySelector( '.erk-viewer-next' ).addEventListener( 'click', function () { show( idx + 1 ); } );
	box.addEventListener( 'click', function ( e ) { if ( e.target === box ) { close(); } } );
	document.addEventListener( 'keydown', function ( e ) {
		if ( box.hidden ) {
			return;
		}
		if ( e.key === 'Escape' ) { close(); }
		if ( e.key === 'ArrowLeft' ) { show( idx - 1 ); }
		if ( e.key === 'ArrowRight' ) { show( idx + 1 ); }
	} );
}() );
