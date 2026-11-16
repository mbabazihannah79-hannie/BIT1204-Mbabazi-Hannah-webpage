<?php
/**
 * Creates all site pages and the main menu when the theme is activated.
 * Safe to run again: existing pages are not duplicated.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function elroi_site_pages() {
	return array(
		'home'      => 'Home',
		'about'     => 'About Us',
		'rooms'     => 'Rooms',
		'amenities' => 'Amenities',
		'dining'    => 'Dining & Bar',
		'gallery'   => 'Gallery',
		'explore'   => 'Explore Kyenjojo',
		'booking'   => 'Book a Room',
		'contact'   => 'Contact Us',
		'faq'       => 'FAQ',
	);
}

function elroi_create_pages() {
	$ids = array();
	foreach ( elroi_site_pages() as $slug => $title ) {
		$existing = get_page_by_path( $slug );
		if ( $existing ) {
			$ids[ $slug ] = $existing->ID;
			if ( 'publish' !== $existing->post_status ) {
				wp_update_post( array( 'ID' => $existing->ID, 'post_status' => 'publish' ) );
			}
			continue;
		}
		$ids[ $slug ] = wp_insert_post( array(
			'post_type'    => 'page',
			'post_status'  => 'publish',
			'post_title'   => $title,
			'post_name'    => $slug,
			'post_content' => '', // Page layout comes from the theme template (page-<slug>.php).
		) );
	}

	// Static front page.
	if ( ! empty( $ids['home'] ) ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $ids['home'] );
	}

	// Pretty links like /rooms/ instead of ?page_id=5
	if ( ! get_option( 'permalink_structure' ) ) {
		update_option( 'permalink_structure', '/%postname%/' );
	}

	update_option( 'blogname', elroi_opt( 'motel_name' ) );
	update_option( 'blogdescription', elroi_opt( 'tagline' ) );

	// Main menu.
	$menu_name = 'El Roi Main Menu';
	$menu      = wp_get_nav_menu_object( $menu_name );
	if ( ! $menu ) {
		$menu_id = wp_create_nav_menu( $menu_name );
		$order   = array( 'home', 'about', 'rooms', 'amenities', 'dining', 'gallery', 'explore', 'faq', 'contact' );
		foreach ( $order as $i => $slug ) {
			if ( empty( $ids[ $slug ] ) ) { continue; }
			wp_update_nav_menu_item( $menu_id, 0, array(
				'menu-item-title'     => 'home' === $slug ? 'Home' : get_the_title( $ids[ $slug ] ),
				'menu-item-object'    => 'page',
				'menu-item-object-id' => $ids[ $slug ],
				'menu-item-type'      => 'post_type',
				'menu-item-status'    => 'publish',
				'menu-item-position'  => $i + 1,
			) );
		}
	} else {
		$menu_id = $menu->term_id;
	}
	$locations            = get_theme_mod( 'nav_menu_locations', array() );
	$locations['primary'] = $menu_id;
	set_theme_mod( 'nav_menu_locations', $locations );

	elroi_register_types();
	flush_rewrite_rules();
	set_transient( 'elroi_activated', 1, 60 );
}
add_action( 'after_switch_theme', 'elroi_create_pages' );

function elroi_activation_notice() {
	if ( ! get_transient( 'elroi_activated' ) ) { return; }
	delete_transient( 'elroi_activated' );
	echo '<div class="notice notice-success is-dismissible"><p><strong>El Roi Khadoshi Motel theme is ready.</strong> All pages and the main menu were created. Next: go to <a href="' . esc_url( admin_url( 'customize.php?autofocus[section]=elroi_details' ) ) . '">Appearance &rarr; Customize &rarr; Motel Details</a> and enter the real phone, WhatsApp and email.</p></div>';
}
add_action( 'admin_notices', 'elroi_activation_notice' );
