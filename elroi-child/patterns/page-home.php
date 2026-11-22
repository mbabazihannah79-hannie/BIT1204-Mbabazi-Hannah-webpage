<?php
/**
 * Title: El Roi — Home page
 * Slug: elroi-child/page-home
 * Categories: elroi
 * Post Types: page
 * Block Types: core/post-content
 * Description: Photo hero with the availability search, about, rooms, amenities, restaurant, places, photo slider and map.
 *
 * @package elroi-child
 */

$erk_hero = erk_img( 'hero', 2000 );
$erk_dine = erk_img( 'restaurant', 2000 );

$erk_amenities = '';
foreach ( array_slice( erk_amenities(), 0, 8 ) as $erk_a ) {
	$erk_amenities .= erk_b_card( $erk_a[0], $erk_a[1], esc_html( $erk_a[2] ) );
}

$erk_places = '';
foreach ( erk_places() as $erk_p ) {
	$erk_places .= erk_b_group(
		erk_b_image( $erk_p[0], $erk_p[1], '' )
			. erk_b_group( erk_b_p( esc_html( $erk_p[2] ), 'is-style-chip' ) . erk_b_h( esc_html( $erk_p[1] ), 3 ), 'erk-place-card-body', '', 'default' ),
		'erk-place-card',
		'',
		'default'
	);
}
?>
<!-- wp:cover {"url":"<?php echo esc_url_raw( html_entity_decode( $erk_hero ) ); ?>","dimRatio":70,"overlayColor":"accent-3","isUserOverlayColor":true,"minHeight":88,"minHeightUnit":"vh","align":"full","className":"erk-hero","layout":{"type":"constrained"}} -->
<div class="wp-block-cover alignfull erk-hero" style="min-height:88vh"><span aria-hidden="true" class="wp-block-cover__background has-accent-3-background-color has-background-dim-70 has-background-dim"></span><img class="wp-block-cover__image-background" alt="" src="<?php echo $erk_hero; // phpcs:ignore WordPress.Security.EscapeOutput ?>" data-object-fit="cover"/><div class="wp-block-cover__inner-container">
<?php
echo erk_b_group( // phpcs:ignore WordPress.Security.EscapeOutput
	erk_b_eyebrow( 'Welcome to Kyenjojo' )
	. erk_b_h( 'El Roi Khadoshi Motel', 1 )
	. erk_b_p( 'Rest well in the heart of Kyenjojo. Comfortable rooms with balconies, a restaurant and bar, free Wi-Fi and safe parking, on Market Street near Centenary Bank.', 'erk-hero-lead' )
	. erk_b_buttons(
		array(
			erk_b_button( 'See Our Rooms', erk_url( 'rooms' ) ),
			erk_b_button( 'Call ' . esc_html( erk_contact( 'phone' ) ), erk_tel(), 'ghost' ),
		)
	),
	'erk-hero-copy',
	'wide',
	'default'
);
echo erk_b_group( erk_b_shortcode( '[erk_quick_book]' ), 'erk-hero-search', 'wide', 'default' ); // phpcs:ignore WordPress.Security.EscapeOutput
?>
</div></div>
<!-- /wp:cover -->

<?php
// About.
echo erk_b_section( // phpcs:ignore WordPress.Security.EscapeOutput
	erk_b_split(
		erk_b_image( 'exterior', 'The motel building' ),
		erk_b_eyebrow( 'About the motel' )
		. erk_b_h( 'A peaceful stop on the Kampala to Fort Portal road' )
		. erk_b_p( 'El Roi Khadoshi Motel is a small, friendly motel with 8 rooms in the centre of Kyenjojo town. Whether you are passing through, visiting family, working in the district or heading to see the chimpanzees of Kibale, we give you a clean room, a good meal and a safe place to park.' )
		. erk_b_p( 'The name says what we stand for: <em>El Roi</em>, "the God who sees", and <em>Khadoshi</em>, from the Hebrew word for "holy". We look after every guest with care.' )
		. erk_b_list(
			array(
				'Every room has a private bathroom and a balcony',
				'24-hour front desk and room service',
				'Walking distance to banks, shops and the market',
			)
		)
		. erk_b_buttons( array( erk_b_button( 'Read our story', erk_url( 'about' ), 'green' ) ) )
	)
);

// Rooms (live from Bookings → Rooms).
echo erk_b_section( // phpcs:ignore WordPress.Security.EscapeOutput
	erk_b_head( 'Rooms & rates', 'Choose your room', 'All rooms include free Wi-Fi, a flat-screen TV, fresh linen and towels, and free toiletries.' )
	. erk_b_wide( erk_b_shortcode( '[erk_rooms]' ) ),
	true
);

// Amenities.
echo erk_b_section( // phpcs:ignore WordPress.Security.EscapeOutput
	erk_b_head( 'Amenities', 'Everything you need for a good stay' )
	. erk_b_grid( $erk_amenities, 4, 'erk-amenities' )
	. erk_b_buttons( array( erk_b_button( 'All amenities', erk_url( 'amenities' ), 'outline' ) ), 'center' )
);
?>

<!-- wp:cover {"url":"<?php echo esc_url_raw( html_entity_decode( $erk_dine ) ); ?>","dimRatio":80,"overlayColor":"accent-3","isUserOverlayColor":true,"align":"full","className":"erk-band","layout":{"type":"constrained"}} -->
<div class="wp-block-cover alignfull erk-band"><span aria-hidden="true" class="wp-block-cover__background has-accent-3-background-color has-background-dim-80 has-background-dim"></span><img class="wp-block-cover__image-background" alt="" src="<?php echo $erk_dine; // phpcs:ignore WordPress.Security.EscapeOutput ?>" data-object-fit="cover"/><div class="wp-block-cover__inner-container">
<?php
echo erk_b_group( // phpcs:ignore WordPress.Security.EscapeOutput
	erk_b_eyebrow( 'Restaurant & Bar' )
	. erk_b_h( 'Breakfast, lunch, dinner and a cold drink on the terrace' )
	. erk_b_p( 'Start the day with a continental or full English breakfast, enjoy local Ugandan dishes for lunch and dinner, and unwind at the bar in the evening.' )
	. erk_b_buttons( array( erk_b_button( 'See the menu', erk_url( 'dining' ) ) ) ),
	'erk-band-copy',
	'wide',
	'default'
);
?>
</div></div>
<!-- /wp:cover -->

<?php
// Places near Kyenjojo.
echo erk_b_section( // phpcs:ignore WordPress.Security.EscapeOutput
	erk_b_head( 'Explore', 'Your base for western Uganda' )
	. erk_b_grid( $erk_places, 4, 'erk-places' )
	. erk_b_buttons( array( erk_b_button( 'Things to do near us', erk_url( 'explore' ), 'outline' ) ), 'center' )
);

// Photo slider.
echo erk_b_section( // phpcs:ignore WordPress.Security.EscapeOutput
	erk_b_head( 'Gallery', 'Take a look around' )
	. erk_b_slider( array_slice( erk_gallery_photos(), 0, 8 ), 'slide', 520 )
	. erk_b_buttons( array( erk_b_button( 'Open full gallery', erk_url( 'gallery' ), 'outline' ) ), 'center' ),
	true
);

// Find us.
echo erk_b_section( // phpcs:ignore WordPress.Security.EscapeOutput
	erk_b_split(
		erk_b_eyebrow( 'Find us' )
		. erk_b_h( 'In the heart of Kyenjojo town' )
		. erk_b_list(
			array(
				esc_html( erk_contact( 'address' ) ),
				'<a href="' . esc_url( erk_tel() ) . '">' . esc_html( erk_contact( 'phone' ) ) . '</a>',
				'<a href="mailto:' . esc_attr( erk_contact( 'email' ) ) . '">' . esc_html( erk_contact( 'email' ) ) . '</a>',
				'Reception open 24 hours',
			),
			'erk-icon-list',
			array( 'erk-ico erk-ico-pin', 'erk-ico erk-ico-phone', 'erk-ico erk-ico-mail', 'erk-ico erk-ico-clock' )
		)
		. erk_b_buttons( array( erk_b_button( 'Get directions', erk_url( 'contact' ), 'green' ) ) ),
		erk_b_map( 400 ),
		false,
		'erk-split-map'
	)
);
