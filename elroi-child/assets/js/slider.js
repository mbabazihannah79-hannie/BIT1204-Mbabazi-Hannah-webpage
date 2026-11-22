/**
 * El Roi Khadoshi Motel — photo slider.
 *
 * Turns the Image blocks inside a Group with the class "erk-slider" into a slider
 * with arrows, dots, autoplay and swipe. "erk-slider-fade" crossfades instead of
 * sliding. Autoplay pauses on hover and is off for visitors who ask their device
 * to reduce motion.
 */
( function () {
	'use strict';

	var SPEED = 4500;
	var reduceMotion = window.matchMedia && window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;

	function init( el ) {
		if ( el.dataset.ready ) {
			return;
		}
		var slides = Array.prototype.slice.call( el.children ).filter( function ( c ) {
			return c.classList.contains( 'wp-block-image' );
		} );
		if ( ! slides.length ) {
			return;
		}
		el.dataset.ready = '1';

		var track = document.createElement( 'div' );
		track.className = 'erk-slider-track';
		slides.forEach( function ( s ) {
			s.classList.add( 'erk-slide' );
			track.appendChild( s );
		} );
		el.insertBefore( track, el.firstChild );
		el.classList.add( 'is-ready' );
		el.setAttribute( 'tabindex', '0' );
		el.setAttribute( 'role', 'region' );
		el.setAttribute( 'aria-roledescription', 'carousel' );
		el.setAttribute( 'aria-label', 'Photos of El Roi Khadoshi Motel' );

		var fade = el.classList.contains( 'erk-slider-fade' );
		var n = slides.length;
		var i = 0;
		var timer = null;
		var dots = [];

		if ( n > 1 ) {
			var prev = document.createElement( 'button' );
			prev.type = 'button';
			prev.className = 'erk-slider-arrow erk-slider-prev';
			prev.setAttribute( 'aria-label', 'Previous photo' );
			prev.innerHTML = '&#8249;';
			var next = document.createElement( 'button' );
			next.type = 'button';
			next.className = 'erk-slider-arrow erk-slider-next';
			next.setAttribute( 'aria-label', 'Next photo' );
			next.innerHTML = '&#8250;';
			prev.addEventListener( 'click', function () { go( i - 1 ); play(); } );
			next.addEventListener( 'click', function () { go( i + 1 ); play(); } );
			el.appendChild( prev );
			el.appendChild( next );

			var dotWrap = document.createElement( 'div' );
			dotWrap.className = 'erk-slider-dots';
			slides.forEach( function ( s, x ) {
				var d = document.createElement( 'button' );
				d.type = 'button';
				d.className = 'erk-slider-dot';
				d.setAttribute( 'aria-label', 'Go to photo ' + ( x + 1 ) );
				d.addEventListener( 'click', function () { go( x ); play(); } );
				dotWrap.appendChild( d );
				dots.push( d );
			} );
			el.appendChild( dotWrap );
		}

		function go( k ) {
			i = ( k + n ) % n;
			if ( ! fade ) {
				track.style.transform = 'translateX(' + ( -100 * i ) + '%)';
			}
			slides.forEach( function ( s, x ) {
				s.classList.toggle( 'is-active', x === i );
				s.setAttribute( 'aria-hidden', x === i ? 'false' : 'true' );
			} );
			dots.forEach( function ( d, x ) {
				d.classList.toggle( 'is-active', x === i );
			} );
		}
		function stop() {
			if ( timer ) {
				clearInterval( timer );
				timer = null;
			}
		}
		function play() {
			stop();
			if ( ! reduceMotion && n > 1 ) {
				timer = setInterval( function () { go( i + 1 ); }, SPEED );
			}
		}

		el.addEventListener( 'mouseenter', stop );
		el.addEventListener( 'mouseleave', play );
		el.addEventListener( 'focusin', stop );
		el.addEventListener( 'keydown', function ( e ) {
			if ( e.key === 'ArrowLeft' ) { go( i - 1 ); }
			if ( e.key === 'ArrowRight' ) { go( i + 1 ); }
		} );
		var startX = null;
		el.addEventListener( 'touchstart', function ( e ) { startX = e.touches[ 0 ].clientX; stop(); }, { passive: true } );
		el.addEventListener( 'touchend', function ( e ) {
			if ( startX === null ) {
				return;
			}
			var dx = e.changedTouches[ 0 ].clientX - startX;
			if ( Math.abs( dx ) > 40 ) {
				go( dx < 0 ? i + 1 : i - 1 );
			}
			startX = null;
			play();
		} );
		document.addEventListener( 'visibilitychange', function () {
			if ( document.hidden ) { stop(); } else { play(); }
		} );

		go( 0 );
		play();
	}

	function boot() {
		document.querySelectorAll( '.erk-slider' ).forEach( init );
	}
	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', boot );
	} else {
		boot();
	}
}() );
