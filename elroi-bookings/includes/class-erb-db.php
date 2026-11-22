<?php
/**
 * Database layer: three tables, every query through $wpdb->prepare().
 *
 *   erb_rooms     (id, slug, name, price, max_guests, beds, description, features, photo_ids, photo_keys, sort_order, is_active)
 *   erb_bookings  (id, reference, room_id → erb_rooms.id, room_name, guest_name, phone, email, check_in, check_out,
 *                  nights, rooms_qty, guests, total, notes, status, staff_note, created_at)
 *   erb_messages  (id, name, contact, subject, message, is_read, created_at)
 *
 * A booking keeps a copy of the room name, so deleting a room never loses past bookings.
 *
 * @package elroi-bookings
 */

defined( 'ABSPATH' ) || exit;

class ERB_DB {

	public static function rooms_table() {
		global $wpdb;
		return $wpdb->prefix . 'erb_rooms';
	}

	public static function bookings_table() {
		global $wpdb;
		return $wpdb->prefix . 'erb_bookings';
	}

	public static function messages_table() {
		global $wpdb;
		return $wpdb->prefix . 'erb_messages';
	}

	/** Create or upgrade the tables, then add the starting rooms if there are none. */
	public static function install() {
		global $wpdb;
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		$charset = $wpdb->get_charset_collate();
		$rooms   = self::rooms_table();
		$book    = self::bookings_table();
		$msg     = self::messages_table();

		dbDelta(
			"CREATE TABLE {$rooms} (
				id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
				slug varchar(60) NOT NULL,
				name varchar(120) NOT NULL,
				price int(10) unsigned NOT NULL DEFAULT 0,
				max_guests tinyint(3) unsigned NOT NULL DEFAULT 2,
				beds varchar(120) NOT NULL DEFAULT '',
				description text NOT NULL,
				features text NOT NULL,
				photo_ids varchar(255) NOT NULL DEFAULT '',
				photo_keys varchar(255) NOT NULL DEFAULT '',
				sort_order int(11) NOT NULL DEFAULT 0,
				is_active tinyint(1) NOT NULL DEFAULT 1,
				PRIMARY KEY  (id),
				UNIQUE KEY slug (slug)
			) {$charset};"
		);
		dbDelta(
			"CREATE TABLE {$book} (
				id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
				reference varchar(20) NOT NULL,
				room_id bigint(20) unsigned DEFAULT NULL,
				room_name varchar(120) NOT NULL DEFAULT '',
				guest_name varchar(120) NOT NULL,
				phone varchar(40) NOT NULL,
				email varchar(120) NOT NULL DEFAULT '',
				check_in date NOT NULL,
				check_out date NOT NULL,
				nights smallint(5) unsigned NOT NULL DEFAULT 1,
				rooms_qty tinyint(3) unsigned NOT NULL DEFAULT 1,
				guests tinyint(3) unsigned NOT NULL DEFAULT 1,
				total bigint(20) unsigned NOT NULL DEFAULT 0,
				notes text NOT NULL,
				status varchar(20) NOT NULL DEFAULT 'pending',
				staff_note text NOT NULL,
				created_at datetime NOT NULL,
				PRIMARY KEY  (id),
				KEY room_id (room_id),
				KEY status (status),
				KEY check_in (check_in)
			) {$charset};"
		);
		dbDelta(
			"CREATE TABLE {$msg} (
				id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
				name varchar(120) NOT NULL,
				contact varchar(160) NOT NULL,
				subject varchar(200) NOT NULL DEFAULT '',
				message text NOT NULL,
				is_read tinyint(1) NOT NULL DEFAULT 0,
				created_at datetime NOT NULL,
				PRIMARY KEY  (id),
				KEY is_read (is_read)
			) {$charset};"
		);

		update_option( 'erb_db_version', ERB_VERSION );

		if ( ! (int) $wpdb->get_var( "SELECT COUNT(*) FROM {$rooms}" ) ) { // phpcs:ignore WordPress.DB.PreparedSQL
			self::import_old_content();
		}
	}

	/** The motel's 8 rooms in 4 types. Prices are sample figures until confirmed with the motel. */
	public static function default_rooms() {
		return array(
			array(
				'slug'        => 'standard',
				'name'        => 'Standard Double Room',
				'price'       => 60000,
				'max_guests'  => 2,
				'beds'        => '1 double bed',
				'photo_keys'  => 'room-standard,bathroom,workdesk',
				'description' => 'A clean, quiet room for solo travellers and couples, with a private shower room, free toiletries and a work desk.',
				'features'    => "Private bathroom with shower\nFree toiletries\nFlat-screen TV\nFree Wi-Fi\nElectric kettle\nWardrobe\nWork desk",
			),
			array(
				'slug'        => 'deluxe',
				'name'        => 'Deluxe Room with Balcony',
				'price'       => 80000,
				'max_guests'  => 2,
				'beds'        => '1 large double bed',
				'photo_keys'  => 'room-deluxe,room-extra1,bathroom',
				'description' => 'Our most popular room. Step out onto your own balcony with a view over Kyenjojo town, then rest in a roomy double bed.',
				'features'    => "Private balcony with town view\nPrivate bathroom with shower\nFlat-screen TV\nFree Wi-Fi\nElectric kettle\nSafety deposit box\nWork desk",
			),
			array(
				'slug'        => 'family',
				'name'        => 'Family Room',
				'price'       => 120000,
				'max_guests'  => 4,
				'beds'        => '1 double bed + 2 single beds',
				'photo_keys'  => 'room-family,room-extra2,bathroom',
				'description' => 'Space for the whole family, with a dining area, balcony and enough beds for four. Limited number available, so book early.',
				'features'    => "Sleeps 4\nBalcony\nDining area\nPrivate bathroom with shower\nFlat-screen TV\nFree Wi-Fi\nElectric kettle",
			),
			array(
				'slug'        => 'apartment',
				'name'        => 'One-Bedroom Apartment with Balcony',
				'price'       => 150000,
				'max_guests'  => 2,
				'beds'        => '1 large double bed',
				'photo_keys'  => 'room-apartment,room-extra1,workdesk',
				'description' => 'Ideal for longer stays and business travellers: a separate bedroom, dining area, flat-screen TV and a balcony with city views.',
				'features'    => "Separate bedroom\nBalcony with city view\nDining area\nFlat-screen TV\nPrivate bathroom with shower\nFree Wi-Fi\nWork desk",
			),
		);
	}

	/**
	 * First install. If the site ran the old theme, its rooms (with any prices and photos
	 * the motel already changed), bookings and messages are copied in; otherwise the
	 * starting rooms are added.
	 */
	private static function import_old_content() {
		global $wpdb;
		$old_rooms = get_posts(
			array(
				'post_type'      => 'elroi_room',
				'post_status'    => 'publish',
				'posts_per_page' => -1,
				'orderby'        => array( 'menu_order' => 'ASC', 'ID' => 'ASC' ),
			)
		);

		$map = array(); // old room name => new id.
		if ( $old_rooms ) {
			foreach ( $old_rooms as $i => $p ) {
				$m      = function ( $k ) use ( $p ) {
					return get_post_meta( $p->ID, '_elroi_' . $k, true );
				};
				$photos = array();
				if ( has_post_thumbnail( $p ) ) {
					$photos[] = (int) get_post_thumbnail_id( $p );
				}
				foreach ( array_filter( array_map( 'absint', explode( ',', (string) $m( 'gallery' ) ) ) ) as $pid ) {
					$photos[] = $pid;
				}
				$keys = (array) get_post_meta( $p->ID, '_elroi_gallery_keys', true );
				$id   = self::save_room(
					array(
						'slug'        => $p->post_name,
						'name'        => $p->post_title,
						'price'       => (int) $m( 'price' ),
						'max_guests'  => max( 1, (int) $m( 'guests' ) ),
						'beds'        => (string) $m( 'bed' ),
						'description' => (string) $m( 'desc' ),
						'features'    => (string) $m( 'features' ),
						'photo_ids'   => implode( ',', array_unique( $photos ) ),
						'photo_keys'  => implode( ',', array_filter( array_map( 'sanitize_key', $keys ) ) ),
						'sort_order'  => $i + 1,
						'is_active'   => 1,
					)
				);
				$map[ $p->post_title ] = $id;
			}
		} else {
			foreach ( self::default_rooms() as $i => $room ) {
				$room['sort_order'] = $i + 1;
				$room['is_active']  = 1;
				self::save_room( $room );
			}
		}

		// Old booking requests and contact messages.
		foreach ( get_posts( array( 'post_type' => 'elroi_booking', 'post_status' => 'any', 'posts_per_page' => -1, 'order' => 'ASC' ) ) as $p ) {
			$m      = function ( $k ) use ( $p ) {
				return (string) get_post_meta( $p->ID, '_elroi_' . $k, true );
			};
			$status = array_search( $m( 'status' ), erb_statuses(), true );
			$wpdb->insert(
				self::bookings_table(),
				array(
					'reference'  => $m( 'ref' ) ? $m( 'ref' ) : 'ERK-' . $p->ID,
					'room_id'    => isset( $map[ $m( 'room' ) ] ) ? $map[ $m( 'room' ) ] : null,
					'room_name'  => $m( 'room' ),
					'guest_name' => $m( 'name' ),
					'phone'      => $m( 'phone' ),
					'email'      => $m( 'email' ),
					'check_in'   => $m( 'checkin' ),
					'check_out'  => $m( 'checkout' ),
					'nights'     => max( 1, (int) $m( 'nights' ) ),
					'rooms_qty'  => max( 1, (int) $m( 'rooms_qty' ) ),
					'guests'     => max( 1, (int) $m( 'guests' ) ),
					'total'      => (int) $m( 'total' ),
					'notes'      => $m( 'notes' ),
					'status'     => $status ? $status : 'pending',
					'staff_note' => '',
					'created_at' => $p->post_date,
				)
			);
		}
		foreach ( get_posts( array( 'post_type' => 'elroi_message', 'post_status' => 'any', 'posts_per_page' => -1, 'order' => 'ASC' ) ) as $p ) {
			$parts = array_map( 'trim', explode( '|', $p->post_title, 2 ) );
			$wpdb->insert(
				self::messages_table(),
				array(
					'name'       => $parts[0],
					'contact'    => preg_match( '/Phone\/Email: (.*)/', $p->post_content, $c ) ? trim( $c[1] ) : '',
					'subject'    => isset( $parts[1] ) ? $parts[1] : '',
					'message'    => trim( preg_replace( '/^From:.*\nPhone\/Email:.*\n\n/', '', $p->post_content ) ),
					'is_read'    => 1,
					'created_at' => $p->post_date,
				)
			);
		}
	}

	/* ---------------------------------------------------------------------
	 * Rooms
	 * ------------------------------------------------------------------ */

	/** All rooms (or only those shown on the website), in display order. */
	public static function rooms( $active_only = true ) {
		global $wpdb;
		$t     = self::rooms_table();
		$where = $active_only ? 'WHERE is_active = 1' : '';
		return $wpdb->get_results( "SELECT * FROM {$t} {$where} ORDER BY sort_order ASC, id ASC" ); // phpcs:ignore WordPress.DB.PreparedSQL
	}

	public static function room( $id ) {
		global $wpdb;
		$t = self::rooms_table();
		return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$t} WHERE id = %d", $id ) ); // phpcs:ignore WordPress.DB.PreparedSQL
	}

	public static function room_by_slug( $slug ) {
		global $wpdb;
		$t = self::rooms_table();
		return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$t} WHERE slug = %s AND is_active = 1", $slug ) ); // phpcs:ignore WordPress.DB.PreparedSQL
	}

	/** Insert ($data without id) or update a room. Returns the room id. */
	public static function save_room( $data, $id = 0 ) {
		global $wpdb;
		$row = array(
			'slug'        => sanitize_title( isset( $data['slug'] ) && $data['slug'] ? $data['slug'] : $data['name'] ),
			'name'        => sanitize_text_field( $data['name'] ),
			'price'       => absint( isset( $data['price'] ) ? $data['price'] : 0 ),
			'max_guests'  => max( 1, min( 20, absint( isset( $data['max_guests'] ) ? $data['max_guests'] : 2 ) ) ),
			'beds'        => sanitize_text_field( isset( $data['beds'] ) ? $data['beds'] : '' ),
			'description' => sanitize_textarea_field( isset( $data['description'] ) ? $data['description'] : '' ),
			'features'    => sanitize_textarea_field( isset( $data['features'] ) ? $data['features'] : '' ),
			'photo_ids'   => implode( ',', array_filter( array_map( 'absint', explode( ',', (string) ( isset( $data['photo_ids'] ) ? $data['photo_ids'] : '' ) ) ) ) ),
			'photo_keys'  => implode( ',', array_filter( array_map( 'sanitize_key', explode( ',', (string) ( isset( $data['photo_keys'] ) ? $data['photo_keys'] : '' ) ) ) ) ),
			'sort_order'  => (int) ( isset( $data['sort_order'] ) ? $data['sort_order'] : 0 ),
			'is_active'   => empty( $data['is_active'] ) ? 0 : 1,
		);
		$row['slug'] = self::unique_slug( $row['slug'] ? $row['slug'] : 'room', $id );
		if ( $id ) {
			$wpdb->update( self::rooms_table(), $row, array( 'id' => $id ) );
			return (int) $id;
		}
		$wpdb->insert( self::rooms_table(), $row );
		return (int) $wpdb->insert_id;
	}

	private static function unique_slug( $slug, $id ) {
		global $wpdb;
		$t    = self::rooms_table();
		$base = $slug;
		$n    = 2;
		while ( $wpdb->get_var( $wpdb->prepare( "SELECT id FROM {$t} WHERE slug = %s AND id <> %d", $slug, $id ) ) ) { // phpcs:ignore WordPress.DB.PreparedSQL
			$slug = $base . '-' . $n++;
		}
		return $slug;
	}

	public static function delete_room( $id ) {
		global $wpdb;
		// Past bookings keep their copy of the room name.
		$wpdb->update( self::bookings_table(), array( 'room_id' => null ), array( 'room_id' => $id ) );
		return $wpdb->delete( self::rooms_table(), array( 'id' => $id ), array( '%d' ) );
	}

	/* ---------------------------------------------------------------------
	 * Bookings
	 * ------------------------------------------------------------------ */

	public static function add_booking( $data ) {
		global $wpdb;
		$data['created_at'] = current_time( 'mysql' );
		$data['status']     = 'pending';
		$data['staff_note'] = '';
		$ok                 = $wpdb->insert( self::bookings_table(), $data );
		return $ok ? (int) $wpdb->insert_id : 0;
	}

	/** Bookings, newest first, with the room's current name when it still exists (JOIN). */
	public static function bookings( $status = '', $limit = 200 ) {
		global $wpdb;
		$b = self::bookings_table();
		$r = self::rooms_table();
		$sql = "SELECT b.*, COALESCE(r.name, b.room_name) AS room_label FROM {$b} b LEFT JOIN {$r} r ON r.id = b.room_id";
		if ( $status && isset( erb_statuses()[ $status ] ) ) {
			return $wpdb->get_results( $wpdb->prepare( $sql . ' WHERE b.status = %s ORDER BY b.created_at DESC LIMIT %d', $status, $limit ) ); // phpcs:ignore WordPress.DB.PreparedSQL
		}
		return $wpdb->get_results( $wpdb->prepare( $sql . ' ORDER BY b.created_at DESC LIMIT %d', $limit ) ); // phpcs:ignore WordPress.DB.PreparedSQL
	}

	public static function booking( $id ) {
		global $wpdb;
		$b = self::bookings_table();
		$r = self::rooms_table();
		return $wpdb->get_row( $wpdb->prepare( "SELECT b.*, COALESCE(r.name, b.room_name) AS room_label FROM {$b} b LEFT JOIN {$r} r ON r.id = b.room_id WHERE b.id = %d", $id ) ); // phpcs:ignore WordPress.DB.PreparedSQL
	}

	/** Number of bookings per status, for the filter links. */
	public static function booking_counts() {
		global $wpdb;
		$b    = self::bookings_table();
		$rows = $wpdb->get_results( "SELECT status, COUNT(*) AS n FROM {$b} GROUP BY status" ); // phpcs:ignore WordPress.DB.PreparedSQL
		$out  = array();
		foreach ( $rows as $row ) {
			$out[ $row->status ] = (int) $row->n;
		}
		return $out;
	}

	public static function update_booking( $id, $status, $staff_note ) {
		global $wpdb;
		return $wpdb->update(
			self::bookings_table(),
			array(
				'status'     => isset( erb_statuses()[ $status ] ) ? $status : 'pending',
				'staff_note' => sanitize_textarea_field( $staff_note ),
			),
			array( 'id' => $id )
		);
	}

	public static function delete_booking( $id ) {
		global $wpdb;
		return $wpdb->delete( self::bookings_table(), array( 'id' => $id ), array( '%d' ) );
	}

	/* ---------------------------------------------------------------------
	 * Messages
	 * ------------------------------------------------------------------ */

	public static function add_message( $data ) {
		global $wpdb;
		$data['created_at'] = current_time( 'mysql' );
		$data['is_read']    = 0;
		$ok                 = $wpdb->insert( self::messages_table(), $data );
		return $ok ? (int) $wpdb->insert_id : 0;
	}

	public static function messages( $limit = 200 ) {
		global $wpdb;
		$t = self::messages_table();
		return $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$t} ORDER BY created_at DESC LIMIT %d", $limit ) ); // phpcs:ignore WordPress.DB.PreparedSQL
	}

	public static function message( $id ) {
		global $wpdb;
		$t = self::messages_table();
		return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$t} WHERE id = %d", $id ) ); // phpcs:ignore WordPress.DB.PreparedSQL
	}

	public static function unread_messages() {
		global $wpdb;
		$t = self::messages_table();
		return (int) $wpdb->get_var( "SELECT COUNT(*) FROM {$t} WHERE is_read = 0" ); // phpcs:ignore WordPress.DB.PreparedSQL
	}

	public static function mark_read( $id ) {
		global $wpdb;
		return $wpdb->update( self::messages_table(), array( 'is_read' => 1 ), array( 'id' => $id ) );
	}

	public static function delete_message( $id ) {
		global $wpdb;
		return $wpdb->delete( self::messages_table(), array( 'id' => $id ), array( '%d' ) );
	}
}
