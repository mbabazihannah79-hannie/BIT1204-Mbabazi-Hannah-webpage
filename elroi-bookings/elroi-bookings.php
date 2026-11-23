<?php
/**
 * Plugin Name:       El Roi Bookings
 * Plugin URI:        https://github.com/mbabazihannah79-hannie/BIT1204-Mbabazi-Hannah-webpage
 * Description:       Rooms and booking module for El Roi Khadoshi Motel, Kyenjojo: room types with prices and photos, booking requests with a live price estimate and email alerts, a contact form, and dashboard screens to manage bookings, rooms and messages. Uses its own database tables with prepared statements.
 * Version:           1.0.1
 * Author:            Mbabazi Hannah
 * Requires at least: 6.0
 * Requires PHP:      7.4
 * License:           GPL-2.0-or-later
 * Text Domain:       elroi-bookings
 *
 * @package elroi-bookings
 */

defined( 'ABSPATH' ) || exit;

define( 'ERB_VERSION', '1.0.1' );
define( 'ERB_PATH', plugin_dir_path( __FILE__ ) );
define( 'ERB_URL', plugin_dir_url( __FILE__ ) );

require_once ERB_PATH . 'includes/class-erb-db.php';
require_once ERB_PATH . 'includes/class-erb-public.php';
require_once ERB_PATH . 'includes/class-erb-admin.php';

/**
 * Motel settings with defaults (Bookings → Settings).
 *
 * @param string $key phone | whatsapp | email | notify_email | address | map_query | checkin | checkout | cancellation | payment_note
 */
function erb_setting( $key ) {
	$saved = array_filter(
		(array) get_option( 'erb_settings', array() ),
		function ( $v ) {
			return is_string( $v ) && '' !== trim( $v );
		}
	);
	$all   = wp_parse_args( $saved, erb_default_settings() );
	return isset( $all[ $key ] ) ? $all[ $key ] : null;
}

/** Default settings: the motel's details as first published on the website. */
function erb_default_settings() {
	return array(
		'phone'        => '+256 700 000 000',
		'whatsapp'     => '256700000000',
		'email'        => 'info@elroikhadoshimotel.com',
		'notify_email' => '',
		'address'      => '20 Market Street, near Centenary Bank Building, Kyenjojo, Uganda',
		'map_query'    => 'Centenary Bank Kyenjojo, Uganda',
		'checkin'      => '11:00 AM',
		'checkout'     => '12:00 noon',
		'cancellation' => '24 hrs notice',
		'payment_note' => 'No payment is taken online. You pay at reception (cash or mobile money) after we confirm your booking.',
	);
}

/** Booking statuses: key => label. */
function erb_statuses() {
	return array(
		'pending'    => 'Pending',
		'confirmed'  => 'Confirmed',
		'checked_in' => 'Checked in',
		'completed'  => 'Completed',
		'cancelled'  => 'Cancelled',
	);
}

/** "UGX 60,000". */
function erb_money( $amount ) {
	return 'UGX ' . number_format( (float) $amount );
}

/** Where booking alerts go: the notification email, or the motel email. */
function erb_notify_email() {
	$to = erb_setting( 'notify_email' );
	return is_email( $to ) ? $to : erb_setting( 'email' );
}

/** tel: link. */
function erb_tel_url() {
	return 'tel:' . preg_replace( '/[^\d+]/', '', (string) erb_setting( 'phone' ) );
}

/** A Ugandan phone number in WhatsApp format: 0772 123 456 -> 256772123456. */
function erb_wa_digits( $phone ) {
	$digits = preg_replace( '/\D/', '', (string) $phone );
	if ( 10 === strlen( $digits ) && '0' === $digits[0] ) {
		$digits = '256' . substr( $digits, 1 );
	}
	return $digits;
}

/** WhatsApp chat link to the motel, with an optional first message. */
function erb_whatsapp_url( $text = '' ) {
	return 'https://wa.me/' . erb_wa_digits( erb_setting( 'whatsapp' ) ) . ( $text ? '?text=' . rawurlencode( $text ) : '' );
}

/** Address of a site page by slug (e.g. "booking"). */
function erb_page_url( $slug ) {
	$page = get_page_by_path( $slug );
	return $page ? get_permalink( $page ) : home_url( '/' . $slug . '/' );
}

register_activation_hook( __FILE__, array( 'ERB_DB', 'install' ) );

add_action(
	'plugins_loaded',
	function () {
		if ( get_option( 'erb_db_version' ) !== ERB_VERSION ) {
			ERB_DB::install();
		}
		if ( is_admin() ) {
			new ERB_Admin();
		}
	}
);

ERB_Public::init();
