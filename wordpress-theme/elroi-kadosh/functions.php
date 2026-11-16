<?php
/**
 * El Roi Khadoshi Motel theme functions.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

require_once get_template_directory() . '/inc/data.php';
require_once get_template_directory() . '/inc/forms.php';
require_once get_template_directory() . '/inc/setup-pages.php';
require_once get_template_directory() . '/inc/content-types.php';
require_once get_template_directory() . '/inc/customizer-text.php';
require_once get_template_directory() . '/inc/sections.php';
require_once get_template_directory() . '/inc/elementor/elementor.php';

/* ------------------------------------------------------------------
 * Theme setup
 * ---------------------------------------------------------------- */
function elroi_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'custom-logo', array( 'height' => 80, 'width' => 240, 'flex-width' => true, 'flex-height' => true ) );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script' ) );
	register_nav_menus( array(
		'primary' => __( 'Main Menu', 'elroi' ),
		'footer'  => __( 'Footer Menu', 'elroi' ),
	) );
}
add_action( 'after_setup_theme', 'elroi_setup' );

function elroi_assets() {
	$ver = wp_get_theme()->get( 'Version' );
	wp_enqueue_style( 'elroi-fonts', 'https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700&family=Inter:wght@400;500;600&display=swap', array(), null );
	wp_enqueue_style( 'elroi-main', get_template_directory_uri() . '/assets/css/main.css', array(), $ver );
	wp_enqueue_script( 'elroi-main', get_template_directory_uri() . '/assets/js/main.js', array(), $ver, true );
	wp_localize_script( 'elroi-main', 'ELROI', array(
		'rooms' => array_map( function ( $r ) { return $r['price']; }, elroi_rooms() ),
		'placeholder' => elroi_placeholder(),
	) );
}
add_action( 'wp_enqueue_scripts', 'elroi_assets' );

/** Browser-tab icon (used unless a Site Icon is set in Customize > Site Identity). */
function elroi_favicon() {
	if ( has_site_icon() ) { return; }
	$dir = get_template_directory_uri() . '/assets/images/';
	echo '<link rel="icon" type="image/svg+xml" href="' . esc_url( $dir . 'favicon.svg' ) . '">' . "\n";
	echo '<link rel="icon" type="image/png" sizes="512x512" href="' . esc_url( $dir . 'favicon.png' ) . '">' . "\n";
	echo '<link rel="apple-touch-icon" href="' . esc_url( $dir . 'favicon.png' ) . '">' . "\n";
}
add_action( 'wp_head', 'elroi_favicon' );
add_action( 'admin_head', 'elroi_favicon' );

/* ------------------------------------------------------------------
 * Motel details (Appearance > Customize > Motel Details)
 * ---------------------------------------------------------------- */
function elroi_defaults() {
	return array(
		'motel_name' => 'El Roi Khadoshi Motel',
		'tagline'    => 'Rest well in the heart of Kyenjojo',
		'phone'      => '+256 700 000 000',
		'whatsapp'   => '256700000000',
		'email'      => 'info@elroikhadoshimotel.com',
		'address'    => '20 Market Street, near Centenary Bank Building, Kyenjojo, Uganda',
		'map_query'  => 'Centenary Bank Kyenjojo, Uganda',
		'facebook'   => '',
		'instagram'  => '',
		'checkin'    => '11:00 AM',
		'checkout'   => '12:00 noon',
	);
}

function elroi_opt( $key ) {
	$d = elroi_defaults();
	return get_theme_mod( 'elroi_' . $key, isset( $d[ $key ] ) ? $d[ $key ] : '' );
}

function elroi_customize( $wp_customize ) {
	$wp_customize->add_section( 'elroi_details', array(
		'title'    => __( 'Motel Details', 'elroi' ),
		'priority' => 30,
		'description' => __( 'Phone, email, address and other details shown across the website.', 'elroi' ),
	) );
	$labels = array(
		'motel_name' => 'Motel name',
		'tagline'    => 'Home page tagline',
		'phone'      => 'Phone number (as shown)',
		'whatsapp'   => 'WhatsApp number (digits only, with 256, e.g. 256772123456)',
		'email'      => 'Email address',
		'address'    => 'Physical address',
		'map_query'  => 'Google Maps search text for the map',
		'facebook'   => 'Facebook page link',
		'instagram'  => 'Instagram link',
		'checkin'    => 'Check-in time',
		'checkout'   => 'Check-out time',
	);
	foreach ( elroi_defaults() as $key => $default ) {
		$wp_customize->add_setting( 'elroi_' . $key, array(
			'default'           => $default,
			'sanitize_callback' => in_array( $key, array( 'facebook', 'instagram' ), true ) ? 'esc_url_raw' : 'sanitize_text_field',
		) );
		$wp_customize->add_control( 'elroi_' . $key, array(
			'label'   => $labels[ $key ],
			'section' => 'elroi_details',
			'type'    => in_array( $key, array( 'facebook', 'instagram' ), true ) ? 'url' : 'text',
		) );
	}
}
add_action( 'customize_register', 'elroi_customize' );

/* ------------------------------------------------------------------
 * Helpers
 * ---------------------------------------------------------------- */
function elroi_page_url( $slug ) {
	$page = get_page_by_path( $slug );
	return $page ? get_permalink( $page ) : home_url( '/' . $slug . '/' );
}

function elroi_whatsapp_url( $text = '' ) {
	$num = preg_replace( '/\D/', '', elroi_opt( 'whatsapp' ) );
	$url = 'https://wa.me/' . $num;
	if ( $text ) { $url .= '?text=' . rawurlencode( $text ); }
	return $url;
}

function elroi_tel_url() {
	return 'tel:' . preg_replace( '/[^\d+]/', '', elroi_opt( 'phone' ) );
}

/** Page banner used at the top of inner pages. */
function elroi_page_hero( $title, $subtitle = '', $image = 'exterior' ) {
	?>
	<section class="page-hero" style="--hero:url('<?php echo esc_url( elroi_img( $image, 1800 ) ); ?>')">
		<div class="container">
			<h1><?php echo esc_html( $title ); ?></h1>
			<?php if ( $subtitle ) : ?><p><?php echo esc_html( $subtitle ); ?></p><?php endif; ?>
		</div>
	</section>
	<?php
}

/** Fallback menu if no menu is assigned yet. */
function elroi_fallback_menu() {
	$items = array(
		''          => 'Home',
		'about'     => 'About',
		'rooms'     => 'Rooms',
		'amenities' => 'Amenities',
		'dining'    => 'Dining',
		'gallery'   => 'Gallery',
		'explore'   => 'Explore',
		'contact'   => 'Contact',
	);
	echo '<ul class="menu">';
	foreach ( $items as $slug => $label ) {
		$url = $slug ? elroi_page_url( $slug ) : home_url( '/' );
		echo '<li><a href="' . esc_url( $url ) . '">' . esc_html( $label ) . '</a></li>';
	}
	echo '</ul>';
}

/** Show room price per night. */
function elroi_room_card( $slug, $room ) {
	?>
	<article class="room-card">
		<a class="room-card__img" href="<?php echo esc_url( elroi_page_url( 'rooms' ) . '#room-' . $slug ); ?>">
			<?php elroi_image_tag( $room['image'], $room['name'], 900 ); ?>
			<span class="price-tag"><?php echo esc_html( elroi_money( $room['price'] ) ); ?><small>/night</small></span>
		</a>
		<div class="room-card__body">
			<h3><?php echo esc_html( $room['name'] ); ?></h3>
			<p class="room-meta">
				<span><?php echo elroi_icon( 'users' ); ?> Up to <?php echo intval( $room['guests'] ); ?> guests</span>
				<span><?php echo elroi_icon( 'bed' ); ?> <?php echo esc_html( $room['bed'] ); ?></span>
			</p>
			<p><?php echo esc_html( $room['desc'] ); ?></p>
			<a class="btn btn--small" href="<?php echo esc_url( add_query_arg( 'room', $slug, elroi_page_url( 'booking' ) ) ); ?>">Book this room</a>
		</div>
	</article>
	<?php
}
