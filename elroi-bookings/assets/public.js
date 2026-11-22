/**
 * El Roi Bookings — check-out date follows check-in, and the live price estimate.
 */
( function () {
	'use strict';

	document.querySelectorAll( 'form' ).forEach( function ( form ) {
		var cin = form.querySelector( '[name="checkin"]' );
		var cout = form.querySelector( '[name="checkout"]' );
		if ( ! cin || ! cout ) {
			return;
		}
		cin.addEventListener( 'change', function () {
			if ( ! cin.value ) {
				return;
			}
			var d = new Date( cin.value + 'T00:00:00' );
			d.setDate( d.getDate() + 1 );
			var next = d.getFullYear() + '-' + String( d.getMonth() + 1 ).padStart( 2, '0' ) + '-' + String( d.getDate() ).padStart( 2, '0' );
			cout.min = next;
			if ( ! cout.value || cout.value < next ) {
				cout.value = next;
			}
			cout.dispatchEvent( new Event( 'change', { bubbles: true } ) );
		} );
	} );

	var form = document.querySelector( '.js-booking' );
	if ( ! form ) {
		return;
	}
	var prices = {};
	try {
		prices = JSON.parse( form.getAttribute( 'data-prices' ) || '{}' );
	} catch ( e ) {}
	var out = form.querySelector( '.js-total' );
	function calc() {
		var cin = form.elements.checkin.value;
		var cout = form.elements.checkout.value;
		var price = prices[ form.elements.room.value ] || 0;
		var qty = parseInt( form.elements.rooms_qty.value, 10 ) || 1;
		if ( ! cin || ! cout ) {
			out.textContent = 'Choose your dates';
			return;
		}
		var nights = Math.round( ( new Date( cout + 'T00:00:00' ) - new Date( cin + 'T00:00:00' ) ) / 86400000 );
		if ( nights < 1 ) {
			out.textContent = 'Check your dates';
			return;
		}
		out.textContent = 'UGX ' + ( nights * price * qty ).toLocaleString( 'en-US' ) + ' (' + nights + ' night' + ( nights > 1 ? 's' : '' ) + ')';
	}
	[ 'change', 'input' ].forEach( function ( ev ) {
		form.addEventListener( ev, calc );
	} );
	calc();
}() );
