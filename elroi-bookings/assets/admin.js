/* El Roi Bookings — choose room photos from the Media Library. */
jQuery( function ( $ ) {
	$( '.erb-photos' ).each( function () {
		var box = $( this );
		var input = box.find( 'input[type=hidden]' );
		var thumbs = box.find( '.erb-photos__thumbs' );
		var frame;
		box.find( '.erb-photos__pick' ).on( 'click', function ( e ) {
			e.preventDefault();
			if ( ! frame ) {
				frame = wp.media( { title: 'Room photos (the first is the main photo)', multiple: 'add', library: { type: 'image' }, button: { text: 'Use these photos' } } );
				frame.on( 'open', function () {
					var sel = frame.state().get( 'selection' );
					( input.val() || '' ).split( ',' ).filter( Boolean ).forEach( function ( id ) {
						var a = wp.media.attachment( id );
						a.fetch();
						sel.add( a );
					} );
				} );
				frame.on( 'select', function () {
					var ids = [];
					thumbs.empty();
					frame.state().get( 'selection' ).each( function ( a ) {
						ids.push( a.id );
						var s = a.get( 'sizes' );
						var url = s && s.thumbnail ? s.thumbnail.url : a.get( 'url' );
						thumbs.append( $( '<img alt="">' ).attr( 'src', url ) );
					} );
					input.val( ids.join( ',' ) );
				} );
			}
			frame.open();
		} );
		box.find( '.erb-photos__clear' ).on( 'click', function ( e ) {
			e.preventDefault();
			input.val( '' );
			thumbs.empty().append( $( '<em>Sample photos will be used. Save to apply.</em>' ) );
		} );
	} );
} );
