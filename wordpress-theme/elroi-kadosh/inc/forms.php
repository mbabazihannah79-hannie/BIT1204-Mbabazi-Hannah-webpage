<?php
/**
 * Booking requests and contact messages.
 * Both are saved in the WordPress dashboard (Bookings / Messages menus).
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function elroi_register_types() {
	register_post_type( 'elroi_booking', array(
		'labels' => array(
			'name'          => 'Bookings',
			'singular_name' => 'Booking',
			'all_items'     => 'All Bookings',
			'edit_item'     => 'View Booking',
		),
		'public'        => false,
		'show_ui'       => true,
		'menu_icon'     => 'dashicons-calendar-alt',
		'menu_position' => 25,
		'supports'      => array( 'title' ),
		'capability_type' => 'post',
		'capabilities'  => array( 'create_posts' => 'do_not_allow' ),
		'map_meta_cap'  => true,
	) );
	register_post_type( 'elroi_message', array(
		'labels' => array(
			'name'          => 'Messages',
			'singular_name' => 'Message',
			'edit_item'     => 'View Message',
		),
		'public'        => false,
		'show_ui'       => true,
		'menu_icon'     => 'dashicons-email-alt',
		'menu_position' => 26,
		'supports'      => array( 'title', 'editor' ),
		'capabilities'  => array( 'create_posts' => 'do_not_allow' ),
		'map_meta_cap'  => true,
	) );
}
add_action( 'init', 'elroi_register_types' );

/* ------------------------------------------------------------------
 * Booking form handler
 * ---------------------------------------------------------------- */
function elroi_handle_booking() {
	$back = wp_get_referer() ? wp_get_referer() : elroi_page_url( 'booking' );
	$back = remove_query_arg( array( 'booked', 'ref', 'err' ), $back );

	if ( ! isset( $_POST['elroi_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['elroi_nonce'] ) ), 'elroi_booking' ) ) {
		wp_safe_redirect( add_query_arg( 'err', 'security', $back ) ); exit;
	}
	if ( ! empty( $_POST['website'] ) ) { // honeypot for spam bots
		wp_safe_redirect( $back ); exit;
	}

	$rooms    = elroi_rooms();
	$name     = sanitize_text_field( wp_unslash( $_POST['full_name'] ?? '' ) );
	$phone    = sanitize_text_field( wp_unslash( $_POST['phone'] ?? '' ) );
	$email    = sanitize_email( wp_unslash( $_POST['email'] ?? '' ) );
	$room     = sanitize_key( wp_unslash( $_POST['room'] ?? '' ) );
	$checkin  = sanitize_text_field( wp_unslash( $_POST['checkin'] ?? '' ) );
	$checkout = sanitize_text_field( wp_unslash( $_POST['checkout'] ?? '' ) );
	$guests   = max( 1, intval( $_POST['guests'] ?? 1 ) );
	$roomsqty = max( 1, min( 8, intval( $_POST['rooms_qty'] ?? 1 ) ) );
	$notes    = sanitize_textarea_field( wp_unslash( $_POST['notes'] ?? '' ) );

	$in  = DateTime::createFromFormat( 'Y-m-d', $checkin );
	$out = DateTime::createFromFormat( 'Y-m-d', $checkout );
	$today = new DateTime( 'today' );

	if ( ! $name || ! $phone || ! isset( $rooms[ $room ] ) || ! $in || ! $out ) {
		wp_safe_redirect( add_query_arg( 'err', 'missing', $back ) ); exit;
	}
	if ( $in < $today || $out <= $in ) {
		wp_safe_redirect( add_query_arg( 'err', 'dates', $back ) ); exit;
	}

	$nights = (int) $in->diff( $out )->days;
	$total  = $nights * $rooms[ $room ]['price'] * $roomsqty;
	$ref    = 'ERK-' . strtoupper( wp_generate_password( 6, false, false ) );

	$post_id = wp_insert_post( array(
		'post_type'   => 'elroi_booking',
		'post_status' => 'publish',
		'post_title'  => $ref . ' | ' . $name . ' | ' . $rooms[ $room ]['name'],
	) );

	if ( ! $post_id || is_wp_error( $post_id ) ) {
		wp_safe_redirect( add_query_arg( 'err', 'save', $back ) ); exit;
	}

	$meta = array(
		'ref' => $ref, 'name' => $name, 'phone' => $phone, 'email' => $email,
		'room' => $rooms[ $room ]['name'], 'rooms_qty' => $roomsqty, 'checkin' => $checkin, 'checkout' => $checkout,
		'nights' => $nights, 'guests' => $guests, 'total' => $total, 'notes' => $notes, 'status' => 'Pending',
	);
	foreach ( $meta as $k => $v ) { update_post_meta( $post_id, '_elroi_' . $k, $v ); }

	// Email the motel (works once email is set up on the live server).
	$body = "New booking request ($ref)\n\n";
	foreach ( $meta as $k => $v ) { $body .= ucfirst( str_replace( '_', ' ', $k ) ) . ': ' . ( 'total' === $k ? elroi_money( $v ) : $v ) . "\n"; }
	@wp_mail( elroi_opt( 'email' ), 'New booking request ' . $ref, $body );

	wp_safe_redirect( add_query_arg( array( 'booked' => 1, 'ref' => $ref ), $back ) . '#booking-form' ); exit;
}
add_action( 'admin_post_nopriv_elroi_booking', 'elroi_handle_booking' );
add_action( 'admin_post_elroi_booking', 'elroi_handle_booking' );

/* ------------------------------------------------------------------
 * Contact form handler
 * ---------------------------------------------------------------- */
function elroi_handle_contact() {
	$back = wp_get_referer() ? wp_get_referer() : elroi_page_url( 'contact' );
	$back = remove_query_arg( array( 'sent', 'err' ), $back );

	if ( ! isset( $_POST['elroi_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['elroi_nonce'] ) ), 'elroi_contact' ) ) {
		wp_safe_redirect( add_query_arg( 'err', 'security', $back ) ); exit;
	}
	if ( ! empty( $_POST['website'] ) ) { wp_safe_redirect( $back ); exit; }

	$name    = sanitize_text_field( wp_unslash( $_POST['full_name'] ?? '' ) );
	$contact = sanitize_text_field( wp_unslash( $_POST['contact'] ?? '' ) );
	$subject = sanitize_text_field( wp_unslash( $_POST['subject'] ?? '' ) );
	$message = sanitize_textarea_field( wp_unslash( $_POST['message'] ?? '' ) );

	if ( ! $name || ! $contact || ! $message ) {
		wp_safe_redirect( add_query_arg( 'err', 'missing', $back ) . '#contact-form' ); exit;
	}

	$post_id = wp_insert_post( array(
		'post_type'    => 'elroi_message',
		'post_status'  => 'publish',
		'post_title'   => $name . ' | ' . ( $subject ? $subject : 'General enquiry' ),
		'post_content' => "From: $name\nPhone/Email: $contact\n\n$message",
	) );
	@wp_mail( elroi_opt( 'email' ), 'Website message: ' . $subject, "From: $name ($contact)\n\n$message" );

	wp_safe_redirect( add_query_arg( 'sent', $post_id ? 1 : 0, $back ) . '#contact-form' ); exit;
}
add_action( 'admin_post_nopriv_elroi_contact', 'elroi_handle_contact' );
add_action( 'admin_post_elroi_contact', 'elroi_handle_contact' );

/* ------------------------------------------------------------------
 * Admin: booking columns and details box
 * ---------------------------------------------------------------- */
function elroi_booking_columns( $cols ) {
	return array(
		'cb'       => $cols['cb'],
		'title'    => 'Booking',
		'phone'    => 'Phone',
		'dates'    => 'Stay',
		'total'    => 'Estimated total',
		'status'   => 'Status',
		'date'     => 'Received',
	);
}
add_filter( 'manage_elroi_booking_posts_columns', 'elroi_booking_columns' );

function elroi_booking_column_content( $col, $post_id ) {
	$m = function ( $k ) use ( $post_id ) { return get_post_meta( $post_id, '_elroi_' . $k, true ); };
	switch ( $col ) {
		case 'phone':  echo esc_html( $m( 'phone' ) ); break;
		case 'dates':  echo esc_html( $m( 'checkin' ) . ' → ' . $m( 'checkout' ) . ' (' . $m( 'nights' ) . ' nights)' ); break;
		case 'total':  echo esc_html( elroi_money( $m( 'total' ) ) ); break;
		case 'status': echo '<strong>' . esc_html( $m( 'status' ) ) . '</strong>'; break;
	}
}
add_action( 'manage_elroi_booking_posts_custom_column', 'elroi_booking_column_content', 10, 2 );

function elroi_booking_metabox() {
	add_meta_box( 'elroi_booking_details', 'Booking details', 'elroi_booking_metabox_html', 'elroi_booking', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'elroi_booking_metabox' );

function elroi_booking_metabox_html( $post ) {
	$fields = array( 'ref' => 'Reference', 'name' => 'Guest name', 'phone' => 'Phone', 'email' => 'Email', 'room' => 'Room type', 'rooms_qty' => 'Number of rooms',
		'checkin' => 'Check-in', 'checkout' => 'Check-out', 'nights' => 'Nights', 'guests' => 'Guests', 'total' => 'Estimated total', 'notes' => 'Special requests' );
	echo '<table class="widefat striped"><tbody>';
	foreach ( $fields as $k => $label ) {
		$v = get_post_meta( $post->ID, '_elroi_' . $k, true );
		if ( 'total' === $k ) { $v = elroi_money( $v ); }
		echo '<tr><th style="width:180px">' . esc_html( $label ) . '</th><td>' . nl2br( esc_html( $v ) ) . '</td></tr>';
	}
	echo '</tbody></table>';
	$status = get_post_meta( $post->ID, '_elroi_status', true );
	wp_nonce_field( 'elroi_status', 'elroi_status_nonce' );
	echo '<p><label><strong>Status:</strong> <select name="elroi_status">';
	foreach ( array( 'Pending', 'Confirmed', 'Checked in', 'Completed', 'Cancelled' ) as $s ) {
		echo '<option ' . selected( $status, $s, false ) . '>' . esc_html( $s ) . '</option>';
	}
	echo '</select></label> Click <em>Update</em> to save.</p>';
}

function elroi_save_booking_status( $post_id ) {
	if ( ! isset( $_POST['elroi_status_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['elroi_status_nonce'] ) ), 'elroi_status' ) ) { return; }
	if ( ! current_user_can( 'edit_post', $post_id ) ) { return; }
	if ( isset( $_POST['elroi_status'] ) ) {
		update_post_meta( $post_id, '_elroi_status', sanitize_text_field( wp_unslash( $_POST['elroi_status'] ) ) );
	}
}
add_action( 'save_post_elroi_booking', 'elroi_save_booking_status' );
