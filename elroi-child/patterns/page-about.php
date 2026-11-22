<?php
/**
 * Title: El Roi — About Us page
 * Slug: elroi-child/page-about
 * Categories: elroi
 * Post Types: page
 * Block Types: core/post-content
 * Description: Our story, mission, vision and values, numbers and why guests choose us.
 *
 * @package elroi-child
 */

$erk_stats = '';
foreach ( array( array( '8', 'Comfortable rooms' ), array( '24/7', 'Front desk' ), array( '~25 km', 'To Kibale National Park' ), array( '100%', 'Non-smoking' ) ) as $erk_s ) {
	$erk_stats .= erk_b_group( erk_b_p( '<strong>' . esc_html( $erk_s[0] ) . '</strong>', 'erk-stat-number' ) . erk_b_p( esc_html( $erk_s[1] ), 'erk-stat-label' ), 'erk-stat', '', 'default' );
}

echo erk_b_banner( 'About Us', 'A small motel with a big welcome', 'exterior' ); // phpcs:ignore WordPress.Security.EscapeOutput

echo erk_b_section( // phpcs:ignore WordPress.Security.EscapeOutput
	erk_b_split(
		erk_b_eyebrow( 'Our story' )
		. erk_b_h( 'Welcome to El Roi Khadoshi Motel' )
		. erk_b_p( 'El Roi Khadoshi Motel sits on Market Street in Kyenjojo, near the Centenary Bank building, right in the centre of town. Kyenjojo lies on the main highway between Kampala and Fort Portal, which makes us a natural stop for travellers, business visitors, families and tourists on their way to Kibale National Park.' )
		. erk_b_p( 'We are a small motel on purpose. With just 8 rooms, we know our guests by name, keep every room spotless and make sure your meal is hot and your stay is quiet.' )
		. erk_b_p( 'Our name comes from Hebrew. <strong>El Roi</strong> means "the God who sees" and <strong>Khadoshi</strong> comes from the Hebrew word for "holy". It reminds us to treat every guest with honesty, care and respect.' ),
		erk_b_image( 'reception', 'Reception' ),
		true
	)
);

echo erk_b_section( // phpcs:ignore WordPress.Security.EscapeOutput
	erk_b_grid(
		erk_b_card( 'star', 'Our mission', 'To give every traveller in Kyenjojo a clean, safe and affordable place to rest, with service that feels like home.' )
		. erk_b_card( 'eye', 'Our vision', 'To be the most trusted place to stay in Kyenjojo and the first choice for visitors to Kibale and the Rwenzori region.' )
		. erk_b_card( 'heart', 'Our values', 'Honesty, cleanliness, warm hospitality, safety for every guest, and respect for our community.' ),
		3
	),
	true
);

echo erk_b_section( erk_b_grid( $erk_stats, 4, 'erk-stats' ) ); // phpcs:ignore WordPress.Security.EscapeOutput

echo erk_b_section( // phpcs:ignore WordPress.Security.EscapeOutput
	erk_b_split(
		erk_b_image( 'terrace', 'Terrace' ),
		erk_b_eyebrow( 'Why guests choose us' )
		. erk_b_h( 'Simple comforts, done well' )
		. erk_b_list(
			array(
				'Private bathroom with shower and free toiletries in every room',
				'Balconies, some with views over the town',
				'Restaurant, bar and room service',
				'Free Wi-Fi and free private parking',
				'Safety deposit box and a 24-hour front desk',
				'Central location near banks, shops and transport',
			)
		)
		. erk_b_buttons( array( erk_b_button( 'View rooms', erk_url( 'rooms' ), 'green' ) ) )
	),
	true
);
