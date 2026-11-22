<?php
/**
 * Title: El Roi — Amenities page
 * Slug: elroi-child/page-amenities
 * Categories: elroi
 * Post Types: page
 * Block Types: core/post-content
 * Description: All amenities, four photo features and the "In every room" list.
 *
 * @package elroi-child
 */

$erk_cards = '';
foreach ( erk_amenities() as $erk_a ) {
	$erk_cards .= erk_b_card( $erk_a[0], $erk_a[1], esc_html( $erk_a[2] ) );
}

echo erk_b_banner( 'Amenities', 'Comfort and convenience, all included', 'terrace' ); // phpcs:ignore WordPress.Security.EscapeOutput

echo erk_b_section( erk_b_grid( $erk_cards, 4, 'erk-amenities' ) ); // phpcs:ignore WordPress.Security.EscapeOutput

$erk_features = array(
	array( 'wifi', 'Stay connected', 'Free Wi-Fi reaches every room, the restaurant and the terrace, so you can work, study or call home. Each room has a desk for your laptop.' ),
	array( 'parking', 'Free, secure parking', 'Driving between Kampala and Fort Portal? Park your car or tour vehicle on site at no extra cost, with our front desk watching day and night.' ),
	array( 'gym', 'Keep active', 'Our small fitness room lets you keep up your routine while you travel.' ),
	array( 'terrace', 'Relax on the terrace', 'Enjoy the cool Kyenjojo evenings with a drink or a meal on our open-air terrace.' ),
);
foreach ( $erk_features as $erk_i => $erk_f ) {
	echo erk_b_section( // phpcs:ignore WordPress.Security.EscapeOutput
		erk_b_split( erk_b_image( $erk_f[0], $erk_f[1] ), erk_b_h( $erk_f[1] ) . erk_b_p( $erk_f[2] ), 1 === $erk_i % 2 ),
		0 === $erk_i % 2
	);
}

echo erk_b_section( // phpcs:ignore WordPress.Security.EscapeOutput
	erk_b_h( 'In every room', 2, 'center' )
	. erk_b_list(
		array( 'Private bathroom with shower', 'Free toiletries', 'Towels and bed linen', 'Flat-screen TV', 'Free Wi-Fi', 'Electric kettle', 'Wardrobe / closet', 'Work desk', 'Safety deposit box', 'Balcony (most rooms)' ),
		'is-style-ticks erk-two-cols'
	)
);
