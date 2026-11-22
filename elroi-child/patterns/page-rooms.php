<?php
/**
 * Title: El Roi — Rooms page
 * Slug: elroi-child/page-rooms
 * Categories: elroi
 * Post Types: page
 * Block Types: core/post-content
 * Description: Every room with photos, features, price and a comparison table (live from Bookings → Rooms).
 *
 * @package elroi-child
 */

echo erk_b_banner( 'Rooms & Rates', 'Clean, comfortable rooms for every kind of trip', 'room-deluxe' ); // phpcs:ignore WordPress.Security.EscapeOutput

echo erk_b_section( // phpcs:ignore WordPress.Security.EscapeOutput
	erk_b_group( erk_b_p( 'Every room has a private bathroom with a shower, free toiletries, fresh linen and towels, a flat-screen TV, free Wi-Fi, a wardrobe and an electric kettle. Rates are per room, per night.', '', 'center' ), 'erk-section-head', '', 'constrained' )
	. erk_b_wide( erk_b_shortcode( '[erk_room_details compare="1"]' ) )
);
