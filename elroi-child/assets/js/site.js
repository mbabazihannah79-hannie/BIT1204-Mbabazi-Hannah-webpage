/**
 * El Roi Khadoshi Motel — small site scripts:
 * header shadow on scroll, reveal-on-scroll, and a backup picture if an online photo fails.
 */
( function () {
	'use strict';

	var placeholder = ( window.ERK && window.ERK.placeholder ) || '';
	if ( placeholder ) {
		document.querySelectorAll( 'img' ).forEach( function ( img ) {
			var swap = function () {
				if ( img.src !== placeholder ) {
					img.src = placeholder;
				}
			};
			if ( img.complete && img.naturalWidth === 0 && img.getAttribute( 'src' ) ) {
				swap();
			}
			img.addEventListener( 'error', swap, { once: true } );
		} );
	}

	var header = document.querySelector( 'header.wp-block-template-part' );
	if ( header ) {
		var onScroll = function () {
			header.classList.toggle( 'is-scrolled', window.scrollY > 10 );
		};
		window.addEventListener( 'scroll', onScroll, { passive: true } );
		onScroll();
	}

	var selectors = '.erk-section-head, .erk-card, .erk-place-card, .erk-split > .wp-block-column, .erk-stat, .erk-menu-col, .room-card, .room-detail';
	if ( 'IntersectionObserver' in window && ! ( window.matchMedia && window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches ) ) {
		var io = new IntersectionObserver( function ( entries ) {
			entries.forEach( function ( entry ) {
				if ( entry.isIntersecting ) {
					entry.target.classList.add( 'is-visible' );
					io.unobserve( entry.target );
				}
			} );
		}, { threshold: 0.12 } );
		document.querySelectorAll( selectors ).forEach( function ( el ) {
			el.classList.add( 'erk-reveal' );
			io.observe( el );
		} );
	}
}() );
