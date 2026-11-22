<?php
/**
 * Title: El Roi — Explore Kyenjojo page
 * Slug: elroi-child/page-explore
 * Categories: elroi
 * Post Types: page
 * Block Types: core/post-content
 * Description: Places to visit near Kyenjojo and travel tips.
 *
 * @package elroi-child
 */

$erk_rows = '';
foreach ( erk_places() as $erk_i => $erk_p ) {
	$erk_rows .= erk_b_split(
		erk_b_image( $erk_p[0], $erk_p[1] ),
		erk_b_p( esc_html( $erk_p[2] ), 'is-style-chip' ) . erk_b_h( esc_html( $erk_p[1] ) ) . erk_b_p( esc_html( $erk_p[3] ) ),
		1 === $erk_i % 2,
		'erk-explore-row'
	);
}

echo erk_b_banner( 'Explore Kyenjojo', 'Chimpanzees, tea hills and the road to the Rwenzoris', 'landscape' ); // phpcs:ignore WordPress.Security.EscapeOutput

echo erk_b_section( // phpcs:ignore WordPress.Security.EscapeOutput
	erk_b_group( erk_b_p( 'Kyenjojo is a green, hilly town in western Uganda on the main highway between Kampala and Fort Portal. Stay with us and spend your days exploring some of the most beautiful places in the country.', '', 'center' ), 'erk-section-head', '', 'constrained' )
	. $erk_rows
);

echo erk_b_section( // phpcs:ignore WordPress.Security.EscapeOutput
	erk_b_head( '', 'Travel tips' )
	. erk_b_grid(
		erk_b_card( 'bus', 'Getting here', 'Buses and taxis between Kampala and Fort Portal pass through Kyenjojo every day. Drop off in town and we are a short walk away on Market Street.' )
		. erk_b_card( 'binoculars', 'Chimp tracking', 'Permits for Kibale National Park are issued by the Uganda Wildlife Authority. Book early in the busy season, and ask us for an early breakfast.' )
		. erk_b_card( 'cloud', 'Weather', 'Kyenjojo is cooler than Kampala. Pack a light jacket for the evenings and a raincoat in the rainy months.' ),
		3
	),
	true
);
