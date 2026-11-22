<?php
/**
 * Title: El Roi — Contact Us page
 * Slug: elroi-child/page-contact
 * Categories: elroi
 * Post Types: page
 * Block Types: core/post-content
 * Description: Call, WhatsApp, email and visit cards; message form; directions and map.
 *
 * @package elroi-child
 */

$erk_cards = erk_b_card( 'phone', 'Call us', '<a href="' . esc_url( erk_tel() ) . '">' . esc_html( erk_contact( 'phone' ) ) . '</a>', 'erk-card erk-contact-card' )
	. erk_b_card( 'whatsapp', 'WhatsApp', '<a href="' . esc_url( erk_wa( 'Hello El Roi Khadoshi Motel' ) ) . '" target="_blank" rel="noreferrer noopener">Chat with reception</a>', 'erk-card erk-contact-card' )
	. erk_b_card( 'mail', 'Email', '<a href="mailto:' . esc_attr( erk_contact( 'email' ) ) . '">' . esc_html( erk_contact( 'email' ) ) . '</a>', 'erk-card erk-contact-card' )
	. erk_b_card( 'pin', 'Visit us', '<a href="https://www.google.com/maps/search/?api=1&amp;query=' . rawurlencode( erk_contact( 'map_query' ) ) . '" target="_blank" rel="noreferrer noopener">' . esc_html( erk_contact( 'address' ) ) . '</a>', 'erk-card erk-contact-card' );

echo erk_b_banner( 'Contact Us', 'We are here to help, 24 hours a day', 'reception' ); // phpcs:ignore WordPress.Security.EscapeOutput

echo erk_b_section( erk_b_grid( $erk_cards, 4, 'erk-contact-cards' ) ); // phpcs:ignore WordPress.Security.EscapeOutput

echo erk_b_section( // phpcs:ignore WordPress.Security.EscapeOutput
	erk_b_split(
		erk_b_h( 'Send us a message' ) . erk_b_shortcode( '[erk_contact_form]' ),
		erk_b_h( 'How to find us' )
		. erk_b_p( 'We are on <strong>Market Street in Kyenjojo town</strong>, near the <strong>Centenary Bank building</strong>. Kyenjojo is on the Kampala to Fort Portal highway, about 47 km before Fort Portal.' )
		. erk_b_list(
			array(
				'<strong>By bus or taxi:</strong> ask to be dropped in Kyenjojo town, then walk to Market Street.',
				'<strong>By car:</strong> turn into town from the main highway and look for Centenary Bank. Free parking is available.',
				'<strong>Lost?</strong> Call or WhatsApp us and we will guide you.',
			)
		)
		. erk_b_map( 340 ),
		false,
		'erk-split-top'
	),
	true
);
