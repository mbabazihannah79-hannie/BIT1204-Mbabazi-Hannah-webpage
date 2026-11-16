<?php
/**
 * Dashboard-editable content: Rooms, Restaurant Menu, FAQs, Places to Visit, Amenities.
 * Everything appears under the "Motel Content" menu in the WordPress dashboard.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

/* ------------------------------------------------------------------
 * Post types
 * ---------------------------------------------------------------- */
function elroi_content_types() {
	return array(
		'elroi_room'    => array( 'Rooms', 'Room', array( 'title', 'thumbnail', 'page-attributes' ) ),
		'elroi_dish'    => array( 'Restaurant Menu', 'Dish', array( 'title', 'page-attributes' ) ),
		'elroi_amenity' => array( 'Amenities', 'Amenity', array( 'title', 'page-attributes' ) ),
		'elroi_place'   => array( 'Places to Visit', 'Place', array( 'title', 'thumbnail', 'page-attributes' ) ),
		'elroi_faq'     => array( 'FAQs', 'FAQ', array( 'title', 'editor', 'page-attributes' ) ),
	);
}

function elroi_register_content_types() {
	foreach ( elroi_content_types() as $type => $c ) {
		register_post_type( $type, array(
			'labels' => array(
				'name'               => $c[0],
				'singular_name'      => $c[1],
				'add_new'            => 'Add ' . $c[1],
				'add_new_item'       => 'Add New ' . $c[1],
				'edit_item'          => 'Edit ' . $c[1],
				'all_items'          => $c[0],
				'featured_image'     => 'Main photo',
				'set_featured_image' => 'Set main photo',
				'remove_featured_image' => 'Remove main photo',
				'use_featured_image' => 'Use as main photo',
			),
			'public'       => false,
			'show_ui'      => true,
			'show_in_menu' => 'elroi-content',
			'show_in_rest' => false, // classic, simple edit screen
			'supports'     => $c[2],
			'hierarchical' => false,
		) );
	}
}
add_action( 'init', 'elroi_register_content_types' );

function elroi_content_menu() {
	add_menu_page( 'Motel Content', 'Motel Content', 'edit_posts', 'elroi-content', 'elroi_content_home', 'dashicons-building', 24 );
	add_submenu_page( 'elroi-content', 'Page Text & Photos', 'Page Text & Photos', 'edit_theme_options', 'customize.php?autofocus[panel]=elroi_text_panel' );
	add_submenu_page( 'elroi-content', 'Contacts', 'Contacts & Details', 'edit_theme_options', 'customize.php?autofocus[section]=elroi_details' );
}
add_action( 'admin_menu', 'elroi_content_menu' );

function elroi_content_home() {
	$links = array(
		'edit.php?post_type=elroi_room'    => array( 'Rooms', 'Room names, prices, beds, features and photos.' ),
		'edit.php?post_type=elroi_dish'    => array( 'Restaurant Menu', 'Dishes and drinks with prices, shown on the Dining & Bar page.' ),
		'edit.php?post_type=elroi_amenity' => array( 'Amenities', 'Wi-Fi, parking, restaurant and other facilities.' ),
		'edit.php?post_type=elroi_place'   => array( 'Places to Visit', 'Attractions near Kyenjojo, shown on Explore and Home.' ),
		'edit.php?post_type=elroi_faq'     => array( 'FAQs', 'Questions and answers on the FAQ page.' ),
		'customize.php?autofocus[panel]=elroi_text_panel' => array( 'Page Text & Photos', 'Headings, paragraphs and photos on every page, with live preview.' ),
		'customize.php?autofocus[section]=elroi_details'  => array( 'Contacts & Details', 'Phone, WhatsApp, email, address, map, check-in times.' ),
		'edit.php?post_type=elroi_booking' => array( 'Bookings', 'Booking requests from the website.' ),
		'edit.php?post_type=elroi_message' => array( 'Messages', 'Messages from the contact form.' ),
	);
	echo '<div class="wrap"><h1>Motel Content</h1><p>Everything on the El Roi Khadoshi Motel website can be changed from here. Click a box to start.</p>';
	echo '<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:16px;margin-top:20px">';
	foreach ( $links as $url => $l ) {
		echo '<a href="' . esc_url( admin_url( $url ) ) . '" style="display:block;background:#fff;border:1px solid #dcdcde;border-left:4px solid #c9a24a;padding:18px;text-decoration:none;border-radius:4px">';
		echo '<strong style="font-size:15px;color:#1f4d3a">' . esc_html( $l[0] ) . '</strong><br><span style="color:#50575e">' . esc_html( $l[1] ) . '</span></a>';
	}
	echo '</div></div>';
}

/* ------------------------------------------------------------------
 * Meta boxes (extra fields)
 * ---------------------------------------------------------------- */
function elroi_meta_fields() {
	$sections = array( 'Breakfast', 'Main meals', 'Snacks & drinks', 'Bar' );
	return array(
		'elroi_room' => array(
			'price'    => array( 'Price per night (UGX, numbers only)', 'number' ),
			'guests'   => array( 'Maximum guests', 'number' ),
			'bed'      => array( 'Beds (e.g. 1 double bed)', 'text' ),
			'desc'     => array( 'Short description', 'textarea' ),
			'features' => array( 'Features (one per line)', 'textarea' ),
			'gallery'  => array( 'Extra photos', 'gallery' ),
		),
		'elroi_dish' => array(
			'section' => array( 'Menu section', 'select', $sections ),
			'price'   => array( 'Price (UGX, numbers only)', 'number' ),
			'desc'    => array( 'Description', 'text' ),
		),
		'elroi_amenity' => array(
			'icon' => array( 'Icon', 'select', array( 'wifi', 'car', 'food', 'cup', 'glass', 'clock', 'bell', 'sun', 'dumbbell', 'lock', 'family', 'nosmoke', 'phone', 'mail', 'pin', 'bed', 'users', 'star', 'check' ) ),
			'text' => array( 'Short description', 'textarea' ),
			'home' => array( 'Show on Home page', 'checkbox' ),
		),
		'elroi_place' => array(
			'distance' => array( 'Distance (e.g. About 25 km)', 'text' ),
			'desc'     => array( 'Description', 'textarea' ),
		),
	);
}

function elroi_add_meta_boxes() {
	foreach ( elroi_meta_fields() as $type => $fields ) {
		add_meta_box( 'elroi_fields', 'Details', 'elroi_render_meta_box', $type, 'normal', 'high' );
	}
}
add_action( 'add_meta_boxes', 'elroi_add_meta_boxes' );

function elroi_render_meta_box( $post ) {
	$fields = elroi_meta_fields()[ $post->post_type ];
	wp_nonce_field( 'elroi_meta', 'elroi_meta_nonce' );
	echo '<table class="form-table"><tbody>';
	foreach ( $fields as $key => $f ) {
		$val  = get_post_meta( $post->ID, '_elroi_' . $key, true );
		$name = 'elroi_meta[' . $key . ']';
		echo '<tr><th><label>' . esc_html( $f[0] ) . '</label></th><td>';
		switch ( $f[1] ) {
			case 'textarea':
				echo '<textarea class="large-text" rows="4" name="' . esc_attr( $name ) . '">' . esc_textarea( $val ) . '</textarea>';
				break;
			case 'select':
				echo '<select name="' . esc_attr( $name ) . '">';
				foreach ( $f[2] as $opt ) {
					echo '<option value="' . esc_attr( $opt ) . '" ' . selected( $val, $opt, false ) . '>' . esc_html( $opt ) . '</option>';
				}
				echo '</select>';
				if ( 'icon' === $key ) {
					echo ' <span class="elroi-icon-preview" style="vertical-align:middle;color:#1f4d3a">' . elroi_icon( $val ? $val : 'wifi' ) . '</span>';
				}
				break;
			case 'checkbox':
				echo '<label><input type="checkbox" name="' . esc_attr( $name ) . '" value="1" ' . checked( $val, '1', false ) . '> Yes</label>';
				break;
			case 'gallery':
				$ids = array_filter( array_map( 'intval', explode( ',', (string) $val ) ) );
				echo '<div class="elroi-gallery">';
				echo '<input type="hidden" name="' . esc_attr( $name ) . '" value="' . esc_attr( implode( ',', $ids ) ) . '">';
				echo '<div class="elroi-gallery__thumbs" style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:8px">';
				foreach ( $ids as $id ) { echo wp_get_attachment_image( $id, array( 90, 90 ) ); }
				echo '</div><button type="button" class="button elroi-gallery__pick">Choose photos</button> <button type="button" class="button-link elroi-gallery__clear">Remove all</button>';
				echo '<p class="description">Shown next to the main photo on the Rooms page. Set the main photo in the "Main photo" box on the right.</p></div>';
				break;
			default:
				echo '<input type="' . esc_attr( $f[1] ) . '" class="regular-text" name="' . esc_attr( $name ) . '" value="' . esc_attr( $val ) . '">';
		}
		echo '</td></tr>';
	}
	echo '</tbody></table>';
}

function elroi_save_meta( $post_id, $post ) {
	if ( ! isset( $_POST['elroi_meta_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['elroi_meta_nonce'] ) ), 'elroi_meta' ) ) { return; }
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) { return; }
	if ( ! current_user_can( 'edit_post', $post_id ) ) { return; }
	$all = elroi_meta_fields();
	if ( ! isset( $all[ $post->post_type ] ) ) { return; }
	$in = isset( $_POST['elroi_meta'] ) ? wp_unslash( $_POST['elroi_meta'] ) : array(); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
	foreach ( $all[ $post->post_type ] as $key => $f ) {
		$v = isset( $in[ $key ] ) ? $in[ $key ] : '';
		if ( 'textarea' === $f[1] ) {
			$v = sanitize_textarea_field( $v );
		} elseif ( 'number' === $f[1] ) {
			$v = '' === $v ? '' : (string) absint( $v );
		} elseif ( 'gallery' === $f[1] ) {
			$v = implode( ',', array_filter( array_map( 'absint', explode( ',', $v ) ) ) );
		} elseif ( 'checkbox' === $f[1] ) {
			$v = $v ? '1' : '';
		} else {
			$v = sanitize_text_field( $v );
		}
		update_post_meta( $post_id, '_elroi_' . $key, $v );
	}
}
add_action( 'save_post', 'elroi_save_meta', 10, 2 );

function elroi_admin_assets( $hook ) {
	if ( ! in_array( $hook, array( 'post.php', 'post-new.php' ), true ) ) { return; }
	wp_enqueue_media();
	wp_enqueue_script( 'elroi-admin', get_template_directory_uri() . '/assets/js/admin.js', array( 'jquery' ), wp_get_theme()->get( 'Version' ), true );
}
add_action( 'admin_enqueue_scripts', 'elroi_admin_assets' );

/* Admin list columns */
function elroi_cols_price( $cols ) {
	$new = array();
	foreach ( $cols as $k => $v ) {
		$new[ $k ] = $v;
		if ( 'title' === $k ) {
			$new['elroi_thumb'] = 'Photo';
			$new['elroi_price'] = 'Price';
		}
	}
	unset( $new['date'] );
	return $new;
}
add_filter( 'manage_elroi_room_posts_columns', 'elroi_cols_price' );
add_filter( 'manage_elroi_dish_posts_columns', function ( $c ) { $c = elroi_cols_price( $c ); unset( $c['elroi_thumb'] ); $c['elroi_section'] = 'Section'; return $c; } );

function elroi_cols_content( $col, $id ) {
	if ( 'elroi_price' === $col ) { echo esc_html( elroi_money( get_post_meta( $id, '_elroi_price', true ) ) ); }
	if ( 'elroi_section' === $col ) { echo esc_html( get_post_meta( $id, '_elroi_section', true ) ); }
	if ( 'elroi_thumb' === $col ) {
		$img = has_post_thumbnail( $id ) ? get_the_post_thumbnail_url( $id, 'thumbnail' ) : elroi_img( get_post_meta( $id, '_elroi_img_key', true ), 120 );
		echo '<img src="' . esc_url( $img ) . '" style="width:70px;height:50px;object-fit:cover;border-radius:4px" alt="">';
	}
}
add_action( 'manage_elroi_room_posts_custom_column', 'elroi_cols_content', 10, 2 );
add_action( 'manage_elroi_dish_posts_custom_column', 'elroi_cols_content', 10, 2 );

/* Sort lists by the "Order" number */
function elroi_admin_order( $q ) {
	if ( is_admin() && $q->is_main_query() && in_array( $q->get( 'post_type' ), array_keys( elroi_content_types() ), true ) && ! $q->get( 'orderby' ) ) {
		$q->set( 'orderby', 'menu_order title' );
		$q->set( 'order', 'ASC' );
	}
}
add_action( 'pre_get_posts', 'elroi_admin_order' );

/* ------------------------------------------------------------------
 * Reading content for the website (falls back to built-in defaults)
 * ---------------------------------------------------------------- */
function elroi_get_items( $type ) {
	static $cache = array();
	if ( ! isset( $cache[ $type ] ) ) {
		$cache[ $type ] = get_posts( array(
			'post_type'      => $type,
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'orderby'        => array( 'menu_order' => 'ASC', 'ID' => 'ASC' ),
			'suppress_filters' => false,
		) );
	}
	return $cache[ $type ];
}

function elroi_post_image( $p, $size = 'large' ) {
	if ( has_post_thumbnail( $p ) ) { return get_the_post_thumbnail_url( $p, $size ); }
	$key = get_post_meta( $p->ID, '_elroi_img_key', true );
	return $key ? $key : 'placeholder';
}

function elroi_rooms() {
	$posts = elroi_get_items( 'elroi_room' );
	if ( ! $posts ) { return elroi_default_rooms(); }
	$rooms = array();
	foreach ( $posts as $p ) {
		$m     = function ( $k ) use ( $p ) { return get_post_meta( $p->ID, '_elroi_' . $k, true ); };
		$image = elroi_post_image( $p );
		$extra = array();
		foreach ( array_filter( array_map( 'intval', explode( ',', (string) $m( 'gallery' ) ) ) ) as $id ) {
			$u = wp_get_attachment_image_url( $id, 'large' );
			if ( $u ) { $extra[] = $u; }
		}
		if ( ! $extra ) {
			$keys  = (array) get_post_meta( $p->ID, '_elroi_gallery_keys', true );
			$extra = array_slice( array_filter( $keys ), 1 );
		}
		$rooms[ $p->post_name ] = array(
			'name'     => get_the_title( $p ),
			'price'    => (int) $m( 'price' ),
			'guests'   => max( 1, (int) $m( 'guests' ) ),
			'bed'      => $m( 'bed' ),
			'image'    => $image,
			'gallery'  => array_slice( array_merge( array( $image ), $extra ), 0, 3 ),
			'desc'     => $m( 'desc' ),
			'features' => array_filter( array_map( 'trim', explode( "\n", (string) $m( 'features' ) ) ) ),
		);
	}
	return $rooms;
}

function elroi_amenities( $home_only = false ) {
	$posts = elroi_get_items( 'elroi_amenity' );
	if ( ! $posts ) {
		$d = elroi_default_amenities();
		return $home_only ? array_slice( $d, 0, 8 ) : $d;
	}
	$out = array();
	foreach ( $posts as $p ) {
		if ( $home_only && '1' !== get_post_meta( $p->ID, '_elroi_home', true ) ) { continue; }
		$out[] = array(
			'icon'  => get_post_meta( $p->ID, '_elroi_icon', true ),
			'title' => get_the_title( $p ),
			'text'  => get_post_meta( $p->ID, '_elroi_text', true ),
		);
	}
	return $out;
}

function elroi_attractions() {
	$posts = elroi_get_items( 'elroi_place' );
	if ( ! $posts ) { return elroi_default_attractions(); }
	$out = array();
	foreach ( $posts as $p ) {
		$out[] = array(
			'title'    => get_the_title( $p ),
			'distance' => get_post_meta( $p->ID, '_elroi_distance', true ),
			'image'    => elroi_post_image( $p ),
			'text'     => get_post_meta( $p->ID, '_elroi_desc', true ),
		);
	}
	return $out;
}

function elroi_faqs() {
	$posts = elroi_get_items( 'elroi_faq' );
	if ( ! $posts ) { return elroi_default_faqs(); }
	$out = array();
	foreach ( $posts as $p ) {
		$out[] = array( 'q' => get_the_title( $p ), 'a' => wp_strip_all_tags( $p->post_content ) );
	}
	return $out;
}

/** Restaurant menu grouped by section: section => [ [name, desc, price], ... ] */
function elroi_menu() {
	$posts = elroi_get_items( 'elroi_dish' );
	if ( ! $posts ) { return elroi_default_menu(); }
	$out = array();
	foreach ( $posts as $p ) {
		$sec = get_post_meta( $p->ID, '_elroi_section', true );
		$out[ $sec ? $sec : 'Menu' ][] = array( get_the_title( $p ), get_post_meta( $p->ID, '_elroi_desc', true ), (int) get_post_meta( $p->ID, '_elroi_price', true ) );
	}
	return $out;
}

/* ------------------------------------------------------------------
 * First-time setup: copy the built-in content into the dashboard
 * ---------------------------------------------------------------- */
function elroi_seed_content() {
	if ( get_option( 'elroi_content_seeded' ) ) { return; }
	update_option( 'elroi_content_seeded', 1 );

	$add = function ( $type, $title, $order, $meta, $content = '', $slug = '' ) {
		$id = wp_insert_post( array(
			'post_type' => $type, 'post_status' => 'publish', 'post_title' => $title,
			'post_name' => $slug, 'menu_order' => $order, 'post_content' => $content,
		) );
		if ( $id && ! is_wp_error( $id ) ) {
			foreach ( $meta as $k => $v ) { update_post_meta( $id, '_elroi_' . $k, $v ); }
		}
	};

	$i = 0;
	foreach ( elroi_default_rooms() as $slug => $r ) {
		$add( 'elroi_room', $r['name'], ++$i, array(
			'price' => $r['price'], 'guests' => $r['guests'], 'bed' => $r['bed'], 'desc' => $r['desc'],
			'features' => implode( "\n", $r['features'] ), 'img_key' => $r['image'], 'gallery_keys' => $r['gallery'],
		), '', $slug );
	}
	$i = 0;
	foreach ( elroi_default_menu() as $section => $items ) {
		foreach ( $items as $it ) {
			$add( 'elroi_dish', $it[0], ++$i, array( 'section' => $section, 'desc' => $it[1], 'price' => $it[2] ) );
		}
	}
	$i = 0;
	foreach ( elroi_default_amenities() as $a ) {
		$add( 'elroi_amenity', $a['title'], ++$i, array( 'icon' => $a['icon'], 'text' => $a['text'], 'home' => $i <= 8 ? '1' : '' ) );
	}
	$i = 0;
	foreach ( elroi_default_attractions() as $a ) {
		$add( 'elroi_place', $a['title'], ++$i, array( 'distance' => $a['distance'], 'desc' => $a['text'], 'img_key' => $a['image'] ) );
	}
	$i = 0;
	foreach ( elroi_default_faqs() as $f ) {
		$add( 'elroi_faq', $f['q'], ++$i, array(), $f['a'] );
	}
}
add_action( 'after_switch_theme', 'elroi_seed_content', 20 );
add_action( 'admin_init', 'elroi_seed_content' ); // also runs for sites that activated an earlier version
