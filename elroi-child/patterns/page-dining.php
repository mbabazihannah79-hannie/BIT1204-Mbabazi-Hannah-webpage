<?php
/**
 * Title: El Roi — Dining & Bar page
 * Slug: elroi-child/page-dining
 * Categories: elroi
 * Post Types: page
 * Block Types: core/post-content
 * Description: Restaurant, opening hours, the menu with prices, and the bar.
 *
 * @package elroi-child
 */

$erk_hours = erk_b_table(
	array(
		array( '<strong>Breakfast</strong>', '6:30 AM – 10:30 AM' ),
		array( '<strong>Lunch</strong>', '12:30 PM – 3:30 PM' ),
		array( '<strong>Dinner</strong>', '6:30 PM – 10:30 PM' ),
		array( '<strong>Bar</strong>', 'Open daily until late' ),
		array( '<strong>Room service</strong>', 'Available' ),
	),
	'erk-hours'
);

$erk_menu = '';
foreach ( erk_menu() as $erk_section => $erk_items ) {
	$erk_rows = array();
	foreach ( $erk_items as $erk_it ) {
		$erk_rows[] = array( '<strong>' . esc_html( $erk_it[0] ) . '</strong><br>' . esc_html( $erk_it[1] ), esc_html( erk_money( $erk_it[2] ) ) );
	}
	$erk_menu .= erk_b_group( erk_b_h( esc_html( $erk_section ), 3 ) . erk_b_table( $erk_rows, 'erk-menu-table' ), 'erk-menu-col', '', 'default' );
}

echo erk_b_banner( 'Dining & Bar', 'Good food, served with a smile', 'restaurant' ); // phpcs:ignore WordPress.Security.EscapeOutput

echo erk_b_section( // phpcs:ignore WordPress.Security.EscapeOutput
	erk_b_split(
		erk_b_eyebrow( 'Our restaurant' )
		. erk_b_h( 'From early breakfast to late dinner' )
		. erk_b_p( 'Our restaurant serves freshly cooked local and continental meals every day. Eat in the dining room, outside on the terrace, or ask for room service and we will bring your meal to you.' )
		. $erk_hours,
		erk_b_image( 'dining-room', 'Dining room' ),
		true
	)
);

echo erk_b_section( // phpcs:ignore WordPress.Security.EscapeOutput
	erk_b_head( 'Menu', 'A taste of what we serve', 'Sample menu. Ask our staff for today\'s specials.' )
	. erk_b_grid( $erk_menu, 3, 'erk-menu' ),
	true
);

echo erk_b_section( // phpcs:ignore WordPress.Security.EscapeOutput
	erk_b_split(
		erk_b_image( 'bar', 'Bar' ),
		erk_b_eyebrow( 'The bar' )
		. erk_b_h( 'Unwind after a long day' )
		. erk_b_p( 'Cold beers, sodas, juices, wines and spirits, with sports on TV. Sit inside or take your drink out to the terrace and enjoy the evening breeze.' )
		. erk_b_buttons( array( erk_b_button( 'Stay with us', erk_url( 'booking' ), 'green' ) ) )
	)
);

echo erk_b_section( // phpcs:ignore WordPress.Security.EscapeOutput
	erk_b_grid(
		erk_b_image( 'breakfast2', 'Breakfast table', 'is-style-erk-rounded erk-square' )
		. erk_b_image( 'coffee', 'Fresh coffee', 'is-style-erk-rounded erk-square' )
		. erk_b_image( 'restaurant', 'Restaurant', 'is-style-erk-rounded erk-square' ),
		3
	),
	true
);
