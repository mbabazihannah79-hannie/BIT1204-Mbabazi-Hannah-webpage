<?php
/**
 * El Roi Khadoshi Motel child theme functions.
 *
 * @package elroi-child
 */

defined( 'ABSPATH' ) || exit;

/*
 * Motel details used across the site. When the El Roi Bookings plugin is active,
 * the values saved in Bookings → Settings are used instead, so staff can change
 * them without touching code. Confirm the real numbers with the motel.
 */
define( 'ERK_NAME', 'El Roi Khadoshi Motel' );
define( 'ERK_TAGLINE', 'Rest well in the heart of Kyenjojo' );
define( 'ERK_PHONE', '+256 700 000 000' );
define( 'ERK_WHATSAPP', '256700000000' );
define( 'ERK_EMAIL', 'info@elroikhadoshimotel.com' );
define( 'ERK_ADDRESS', '20 Market Street, near Centenary Bank Building, Kyenjojo, Uganda' );
define( 'ERK_MAP_QUERY', 'Centenary Bank Kyenjojo, Uganda' );
define( 'ERK_CHECKIN', '11:00 AM' );
define( 'ERK_CHECKOUT', '12:00 noon' );

// Block markup helpers and the motel's text for the page designs.
require_once get_stylesheet_directory() . '/inc/blocks.php';
require_once get_stylesheet_directory() . '/inc/content.php';

// One-click site setup (Appearance → Motel setup).
require_once get_stylesheet_directory() . '/inc/setup.php';

/**
 * A motel detail: the Bookings → Settings value if set, otherwise the theme default.
 *
 * @param string $key phone | whatsapp | email | address | map_query | checkin | checkout
 */
function erk_contact( $key ) {
	$defaults = array(
		'phone'     => ERK_PHONE,
		'whatsapp'  => ERK_WHATSAPP,
		'email'     => ERK_EMAIL,
		'address'   => ERK_ADDRESS,
		'map_query' => ERK_MAP_QUERY,
		'checkin'   => ERK_CHECKIN,
		'checkout'  => ERK_CHECKOUT,
	);
	if ( function_exists( 'erb_setting' ) ) {
		$value = erb_setting( $key );
		if ( is_string( $value ) && '' !== trim( $value ) ) {
			return $value;
		}
	}
	return isset( $defaults[ $key ] ) ? $defaults[ $key ] : '';
}

/** Page address by slug, e.g. erk_url( 'rooms' ). */
function erk_url( $slug = '' ) {
	return $slug ? home_url( '/' . trim( $slug, '/' ) . '/' ) : home_url( '/' );
}

/** tel: link for the motel phone. */
function erk_tel() {
	return 'tel:' . preg_replace( '/[^\d+]/', '', erk_contact( 'phone' ) );
}

/** WhatsApp chat link, with an optional first message. */
function erk_wa( $text = '' ) {
	$url = 'https://wa.me/' . preg_replace( '/\D/', '', erk_contact( 'whatsapp' ) );
	return $text ? $url . '?text=' . rawurlencode( $text ) : $url;
}

/**
 * Sample photos (free Unsplash images) used until the motel's own photos are uploaded.
 * Key => Unsplash photo id.
 */
function erk_sample_photos() {
	return array(
		'hero'           => 'photo-1551882547-ff40c63fe5fa',
		'exterior'       => 'photo-1542314831-068cd1dbfeeb',
		'reception'      => 'photo-1564501049412-61c2a3083791',
		'room-standard'  => 'photo-1611892440504-42a792e24d32',
		'room-deluxe'    => 'photo-1590490360182-c33d57733427',
		'room-family'    => 'photo-1631049307264-da0ec9d70304',
		'room-apartment' => 'photo-1582719478250-c89cae4dc85b',
		'room-extra1'    => 'photo-1618773928121-c32242e63f39',
		'room-extra2'    => 'photo-1596394516093-501ba68a0ba6',
		'bathroom'       => 'photo-1552321554-5fefe8c9ef14',
		'restaurant'     => 'photo-1414235077428-338989a2e8c0',
		'dining-room'    => 'photo-1517248135467-4c7edcad34c4',
		'breakfast'      => 'photo-1533777857889-4be7c70b33f7',
		'breakfast2'     => 'photo-1525610553991-2bede1a236e2',
		'coffee'         => 'photo-1509042239860-f550ce710b93',
		'bar'            => 'photo-1514933651103-005eec06c04b',
		'gym'            => 'photo-1534438327276-14e5300c3a48',
		'terrace'        => 'photo-1600585154340-be6161a56a0c',
		'workdesk'       => 'photo-1497366216548-37526070297c',
		'wifi'           => 'photo-1498050108023-c5249f4df085',
		'parking'        => 'photo-1506521781263-d8422e82f27a',
		'landscape'      => 'photo-1516426122078-c23e76319801',
		'chimp'          => 'photo-1540573133985-87b6da6d54a9',
		'tea'            => 'photo-1563822249366-3efb23b8e0c9',
	);
}

/**
 * Media Library attachment found by file name: "hero" finds hero.jpg / .jpeg / .png / .webp,
 * WordPress's hero-scaled.jpg and hero-1.jpg (a newer upload with the same name). Newest wins.
 *
 * @param string $key File name without extension (".jpg" is ignored).
 * @return int Attachment ID or 0.
 */
function erk_find_attachment( $key ) {
	global $wpdb;
	$name = preg_replace( '/[^A-Za-z0-9_-]/', '', pathinfo( $key, PATHINFO_FILENAME ) );
	if ( '' === $name ) {
		return 0;
	}
	return (int) $wpdb->get_var(
		$wpdb->prepare(
			"SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = '_wp_attached_file' AND meta_value REGEXP %s ORDER BY post_id DESC LIMIT 1",
			'(^|/)' . $name . '(-[0-9]+)?(-scaled)?[.](jpe?g|png|webp|avif|gif)$'
		)
	);
}

/**
 * URL of a site photo by key (e.g. "terrace"). Upload a photo named terrace.jpg to the
 * Media Library and it is used; otherwise the sample photo, otherwise a branded placeholder.
 *
 * @param string $key   Photo key.
 * @param int    $width Width of the sample photo to request.
 */
function erk_img( $key, $width = 1600 ) {
	static $cache = array();
	$cache_key = $key . '|' . $width;
	if ( isset( $cache[ $cache_key ] ) ) {
		return $cache[ $cache_key ];
	}
	$url = '';
	$id  = erk_find_attachment( $key );
	if ( $id ) {
		$url = wp_get_attachment_image_url( $id, $width > 1200 ? 'full' : 'large' );
	}
	if ( ! $url ) {
		$samples = erk_sample_photos();
		$url     = isset( $samples[ $key ] )
			? 'https://images.unsplash.com/' . $samples[ $key ] . '?auto=format&fit=crop&w=' . (int) $width . '&q=80'
			: get_stylesheet_directory_uri() . '/assets/images/placeholder.svg';
	}
	$cache[ $cache_key ] = esc_url( $url );
	return $cache[ $cache_key ];
}

/**
 * Assets.
 */
function erk_enqueue_assets() {
	$version = wp_get_theme()->get( 'Version' );
	$dir     = get_stylesheet_directory_uri();
	wp_enqueue_style( 'erk-child', get_stylesheet_uri(), array(), $version );
	wp_enqueue_script( 'erk-site', $dir . '/assets/js/site.js', array(), $version, array( 'strategy' => 'defer', 'in_footer' => true ) );
	wp_enqueue_script( 'erk-slider', $dir . '/assets/js/slider.js', array(), $version, array( 'strategy' => 'defer', 'in_footer' => true ) );
	wp_enqueue_script( 'erk-gallery', $dir . '/assets/js/gallery.js', array(), $version, array( 'strategy' => 'defer', 'in_footer' => true ) );
	wp_localize_script( 'erk-site', 'ERK', array( 'placeholder' => $dir . '/assets/images/placeholder.svg' ) );
}
add_action( 'wp_enqueue_scripts', 'erk_enqueue_assets' );

/**
 * Editor: the same look as the website, plus labels that explain the sliders and
 * the live booking blocks to the person editing.
 */
add_action(
	'after_setup_theme',
	function () {
		add_editor_style( array( 'style.css', 'assets/css/editor.css' ) );
	}
);

/**
 * Block styles, picked from the Styles panel when a block is selected.
 */
add_action(
	'init',
	function () {
		register_block_pattern_category( 'elroi', array( 'label' => __( 'El Roi Khadoshi Motel pages', 'elroi-child' ) ) );

		register_block_style( 'core/button', array( 'name' => 'green', 'label' => __( 'Black (dark button)', 'elroi-child' ) ) );
		register_block_style( 'core/button', array( 'name' => 'ghost', 'label' => __( 'White outline (on photos)', 'elroi-child' ) ) );
		register_block_style( 'core/paragraph', array( 'name' => 'eyebrow', 'label' => __( 'Small blue label', 'elroi-child' ) ) );
		register_block_style( 'core/paragraph', array( 'name' => 'chip', 'label' => __( 'Blue tag', 'elroi-child' ) ) );
		register_block_style( 'core/list', array( 'name' => 'ticks', 'label' => __( 'Blue ticks', 'elroi-child' ) ) );
		register_block_style( 'core/image', array( 'name' => 'erk-rounded', 'label' => __( 'Rounded photo', 'elroi-child' ) ) );
	}
);

/** Floating "Chat on WhatsApp" button on every page. */
function erk_whatsapp_button() {
	printf(
		'<a class="erk-wa-float" href="%1$s" target="_blank" rel="noopener" aria-label="%2$s"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2a10 10 0 0 0-8.6 15.1L2 22l5-1.3A10 10 0 1 0 12 2Zm0 18.2a8.2 8.2 0 0 1-4.2-1.2l-.3-.2-3 .8.8-2.9-.2-.3A8.2 8.2 0 1 1 12 20.2Zm4.5-6.1c-.2-.1-1.5-.7-1.7-.8s-.4-.1-.6.1-.7.8-.8 1-.3.2-.5.1a6.7 6.7 0 0 1-3.3-2.9c-.3-.4.3-.4.7-1.4a.5.5 0 0 0 0-.4l-.8-1.8c-.2-.5-.4-.4-.6-.4h-.5a1 1 0 0 0-.7.3 3 3 0 0 0-.9 2.2 5.1 5.1 0 0 0 1.1 2.7 11.7 11.7 0 0 0 4.5 4c1.7.7 2.3.8 3.2.6a2.7 2.7 0 0 0 1.8-1.2 2.2 2.2 0 0 0 .1-1.2c0-.1-.2-.2-.4-.3Z"/></svg></a>',
		esc_url( erk_wa( 'Hello ' . ERK_NAME ) ),
		esc_attr__( 'Chat on WhatsApp', 'elroi-child' )
	);
}
add_action( 'wp_footer', 'erk_whatsapp_button' );

/** Booking plugin: room photos fall back to the theme's sample photos. */
add_filter(
	'erb_image_fallback',
	function ( $url, $key ) {
		return $key ? erk_img( $key, 1200 ) : get_stylesheet_directory_uri() . '/assets/images/placeholder.svg';
	},
	10,
	2
);

/** Shortcodes inside header and footer template parts. */
add_filter( 'render_block_core/shortcode', 'do_shortcode' );

/** Search engines: a description for every page that has none from an SEO plugin. */
add_action(
	'wp_head',
	function () {
		if ( defined( 'WPSEO_VERSION' ) || defined( 'RANK_MATH_VERSION' ) ) {
			return;
		}
		printf( '<meta name="description" content="%s">' . "\n", esc_attr( ERK_NAME . ' – comfortable rooms, restaurant, bar and free parking on Market Street, Kyenjojo, Uganda. Close to Kibale National Park.' ) );
	},
	1
);

/** Browser tab icon: the motel crest, until a Site Icon is set in Settings → General. */
function erk_favicon() {
	if ( has_site_icon() ) {
		return;
	}
	$dir = get_stylesheet_directory_uri() . '/assets/images/brand/';
	echo '<link rel="icon" type="image/svg+xml" href="' . esc_url( $dir . 'favicon.svg' ) . '">' . "\n";
	echo '<link rel="icon" type="image/png" sizes="512x512" href="' . esc_url( $dir . 'favicon.png' ) . '">' . "\n";
	echo '<link rel="apple-touch-icon" href="' . esc_url( $dir . 'favicon.png' ) . '">' . "\n";
}
add_action( 'wp_head', 'erk_favicon' );
add_action( 'admin_head', 'erk_favicon' );

/** Don't advertise the WordPress version. */
remove_action( 'wp_head', 'wp_generator' );
add_filter( 'the_generator', '__return_empty_string' );
