<?php
/**
 * Page text and photos, editable in Appearance > Customize > Page Text & Photos.
 * Each field has a default; the website uses the edited value once it is saved.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Sections => [ title, banner photo default key (or ''), fields ]
 * Field => [ label, default, type ]   type: text | textarea | lines
 *   textarea: a paragraph (you may use <strong>bold</strong> and <em>italic</em>)
 *   lines:    one item per line
 */
function elroi_text_fields() {
	return array(
		'home' => array( 'Home Page', '', array(
			'home_eyebrow'        => array( 'Small text above the motel name', 'Welcome to Kyenjojo', 'text' ),
			'home_lead'           => array( 'Big intro paragraph', 'Rest well in the heart of Kyenjojo. Comfortable rooms with balconies, a restaurant and bar, free Wi-Fi and safe parking, on Market Street near Centenary Bank.', 'textarea' ),
			'home_btn'            => array( 'Main button text', 'See Our Rooms', 'text' ),
			'home_about_eyebrow'  => array( 'About section – small label', 'About the motel', 'text' ),
			'home_about_title'    => array( 'About section – heading', 'A peaceful stop on the Kampala to Fort Portal road', 'text' ),
			'home_about_p1'       => array( 'About section – paragraph 1', 'El Roi Khadoshi Motel is a small, friendly motel with 8 rooms in the centre of Kyenjojo town. Whether you are passing through, visiting family, working in the district or heading to see the chimpanzees of Kibale, we give you a clean room, a good meal and a safe place to park.', 'textarea' ),
			'home_about_p2'       => array( 'About section – paragraph 2', 'The name says what we stand for: <em>El Roi</em>, "the God who sees", and <em>Khadoshi</em>, from the Hebrew word for "holy". We look after every guest with care.', 'textarea' ),
			'home_about_points'   => array( 'About section – tick list (one per line)', "Every room has a private bathroom and a balcony\n24-hour front desk and room service\nWalking distance to banks, shops and the market", 'lines' ),
			'home_rooms_eyebrow'  => array( 'Rooms section – small label', 'Rooms & rates', 'text' ),
			'home_rooms_title'    => array( 'Rooms section – heading', 'Choose your room', 'text' ),
			'home_rooms_text'     => array( 'Rooms section – text', 'All rooms include free Wi-Fi, a flat-screen TV, fresh linen and towels, and free toiletries.', 'textarea' ),
			'home_amen_eyebrow'   => array( 'Amenities section – small label', 'Amenities', 'text' ),
			'home_amen_title'     => array( 'Amenities section – heading', 'Everything you need for a good stay', 'text' ),
			'home_dining_eyebrow' => array( 'Restaurant band – small label', 'Restaurant & Bar', 'text' ),
			'home_dining_title'   => array( 'Restaurant band – heading', 'Breakfast, lunch, dinner and a cold drink on the terrace', 'text' ),
			'home_dining_text'    => array( 'Restaurant band – text', 'Start the day with a continental or full English breakfast, enjoy local Ugandan dishes for lunch and dinner, and unwind at the bar in the evening.', 'textarea' ),
			'home_explore_eyebrow'=> array( 'Explore section – small label', 'Explore', 'text' ),
			'home_explore_title'  => array( 'Explore section – heading', 'Your base for western Uganda', 'text' ),
			'home_gallery_eyebrow'=> array( 'Gallery section – small label', 'Gallery', 'text' ),
			'home_gallery_title'  => array( 'Gallery section – heading', 'Take a look around', 'text' ),
			'home_find_eyebrow'   => array( 'Map section – small label', 'Find us', 'text' ),
			'home_find_title'     => array( 'Map section – heading', 'In the heart of Kyenjojo town', 'text' ),
		) ),
		'about' => array( 'About Us Page', 'exterior', array(
			'about_hero_title' => array( 'Banner heading', 'About Us', 'text' ),
			'about_hero_sub'   => array( 'Banner text', 'A small motel with a big welcome', 'text' ),
			'about_eyebrow'    => array( 'Story – small label', 'Our story', 'text' ),
			'about_title'      => array( 'Story – heading', 'Welcome to El Roi Khadoshi Motel', 'text' ),
			'about_p1'         => array( 'Story – paragraph 1', 'El Roi Khadoshi Motel sits on Market Street in Kyenjojo, near the Centenary Bank building, right in the centre of town. Kyenjojo lies on the main highway between Kampala and Fort Portal, which makes us a natural stop for travellers, business visitors, families and tourists on their way to Kibale National Park.', 'textarea' ),
			'about_p2'         => array( 'Story – paragraph 2', 'We are a small motel on purpose. With just 8 rooms, we know our guests by name, keep every room spotless and make sure your meal is hot and your stay is quiet.', 'textarea' ),
			'about_p3'         => array( 'Story – paragraph 3', 'Our name comes from Hebrew. <strong>El Roi</strong> means "the God who sees" and <strong>Khadoshi</strong> comes from the Hebrew word for "holy". It reminds us to treat every guest with honesty, care and respect.', 'textarea' ),
			'about_mission'    => array( 'Our mission', 'To give every traveller in Kyenjojo a clean, safe and affordable place to rest, with service that feels like home.', 'textarea' ),
			'about_vision'     => array( 'Our vision', 'To be the most trusted place to stay in Kyenjojo and the first choice for visitors to Kibale and the Rwenzori region.', 'textarea' ),
			'about_values'     => array( 'Our values', 'Honesty, cleanliness, warm hospitality, safety for every guest, and respect for our community.', 'textarea' ),
			'about_stats'      => array( 'Number boxes (one per line: number | label)', "8 | Comfortable rooms\n24/7 | Front desk\n~25 km | To Kibale National Park\n100% | Non-smoking", 'lines' ),
			'about_why_eyebrow'=> array( 'Why us – small label', 'Why guests choose us', 'text' ),
			'about_why_title'  => array( 'Why us – heading', 'Simple comforts, done well', 'text' ),
			'about_why_points' => array( 'Why us – tick list (one per line)', "Private bathroom with shower and free toiletries in every room\nBalconies, some with views over the town\nRestaurant, bar and room service\nFree Wi-Fi and free private parking\nSafety deposit box and a 24-hour front desk\nCentral location near banks, shops and transport", 'lines' ),
		) ),
		'rooms' => array( 'Rooms Page', 'room-deluxe', array(
			'rooms_hero_title' => array( 'Banner heading', 'Rooms & Rates', 'text' ),
			'rooms_hero_sub'   => array( 'Banner text', 'Clean, comfortable rooms for every kind of trip', 'text' ),
			'rooms_intro'      => array( 'Intro text', 'Every room has a private bathroom with a shower, free toiletries, fresh linen and towels, a flat-screen TV, free Wi-Fi, a wardrobe and an electric kettle. Rates are per room, per night.', 'textarea' ),
		) ),
		'amenities' => array( 'Amenities Page', 'terrace', array(
			'amen_hero_title' => array( 'Banner heading', 'Amenities', 'text' ),
			'amen_hero_sub'   => array( 'Banner text', 'Comfort and convenience, all included', 'text' ),
			'amen_f1_title'   => array( 'Feature 1 – heading', 'Stay connected', 'text' ),
			'amen_f1_text'    => array( 'Feature 1 – text', 'Free Wi-Fi reaches every room, the restaurant and the terrace, so you can work, study or call home. Each room has a desk for your laptop.', 'textarea' ),
			'amen_f2_title'   => array( 'Feature 2 – heading', 'Free, secure parking', 'text' ),
			'amen_f2_text'    => array( 'Feature 2 – text', 'Driving between Kampala and Fort Portal? Park your car or tour vehicle on site at no extra cost, with our front desk watching day and night.', 'textarea' ),
			'amen_f3_title'   => array( 'Feature 3 – heading', 'Keep active', 'text' ),
			'amen_f3_text'    => array( 'Feature 3 – text', 'Our small fitness room lets you keep up your routine while you travel.', 'textarea' ),
			'amen_f4_title'   => array( 'Feature 4 – heading', 'Relax on the terrace', 'text' ),
			'amen_f4_text'    => array( 'Feature 4 – text', 'Enjoy the cool Kyenjojo evenings with a drink or a meal on our open-air terrace.', 'textarea' ),
			'amen_room_list'  => array( '"In every room" list (one per line)', "Private bathroom with shower\nFree toiletries\nTowels and bed linen\nFlat-screen TV\nFree Wi-Fi\nElectric kettle\nWardrobe / closet\nWork desk\nSafety deposit box\nBalcony (most rooms)", 'lines' ),
		) ),
		'dining' => array( 'Dining & Bar Page', 'restaurant', array(
			'dining_hero_title' => array( 'Banner heading', 'Dining & Bar', 'text' ),
			'dining_hero_sub'   => array( 'Banner text', 'Good food, served with a smile', 'text' ),
			'dining_eyebrow'    => array( 'Restaurant – small label', 'Our restaurant', 'text' ),
			'dining_title'      => array( 'Restaurant – heading', 'From early breakfast to late dinner', 'text' ),
			'dining_intro'      => array( 'Restaurant – text', 'Our restaurant serves freshly cooked local and continental meals every day. Eat in the dining room, outside on the terrace, or ask for room service and we will bring your meal to you.', 'textarea' ),
			'dining_hours'      => array( 'Opening hours (one per line: name | time)', "Breakfast | 6:30 AM – 10:30 AM\nLunch | 12:30 PM – 3:30 PM\nDinner | 6:30 PM – 10:30 PM\nBar | Open daily until late\nRoom service | Available", 'lines' ),
			'dining_menu_title' => array( 'Menu – heading', 'A taste of what we serve', 'text' ),
			'dining_menu_note'  => array( 'Menu – note', "Sample menu. Ask our staff for today's specials.", 'text' ),
			'bar_title'         => array( 'Bar – heading', 'Unwind after a long day', 'text' ),
			'bar_text'          => array( 'Bar – text', 'Cold beers, sodas, juices, wines and spirits, with sports on TV. Sit inside or take your drink out to the terrace and enjoy the evening breeze.', 'textarea' ),
		) ),
		'gallery' => array( 'Gallery Page', 'room-extra1', array(
			'gallery_hero_title' => array( 'Banner heading', 'Gallery', 'text' ),
			'gallery_hero_sub'   => array( 'Banner text', 'A look inside El Roi Khadoshi Motel', 'text' ),
		) ),
		'explore' => array( 'Explore Kyenjojo Page', 'landscape', array(
			'explore_hero_title' => array( 'Banner heading', 'Explore Kyenjojo', 'text' ),
			'explore_hero_sub'   => array( 'Banner text', 'Chimpanzees, tea hills and the road to the Rwenzoris', 'text' ),
			'explore_intro'      => array( 'Intro text', 'Kyenjojo is a green, hilly town in western Uganda on the main highway between Kampala and Fort Portal. Stay with us and spend your days exploring some of the most beautiful places in the country.', 'textarea' ),
			'tip1_title' => array( 'Tip 1 – heading', 'Getting here', 'text' ),
			'tip1_text'  => array( 'Tip 1 – text', 'Buses and taxis between Kampala and Fort Portal pass through Kyenjojo every day. Drop off in town and we are a short walk away on Market Street.', 'textarea' ),
			'tip2_title' => array( 'Tip 2 – heading', 'Chimp tracking', 'text' ),
			'tip2_text'  => array( 'Tip 2 – text', 'Permits for Kibale National Park are issued by the Uganda Wildlife Authority. Book early in the busy season, and ask us for an early breakfast.', 'textarea' ),
			'tip3_title' => array( 'Tip 3 – heading', 'Weather', 'text' ),
			'tip3_text'  => array( 'Tip 3 – text', 'Kyenjojo is cooler than Kampala. Pack a light jacket for the evenings and a raincoat in the rainy months.', 'textarea' ),
		) ),
		'booking' => array( 'Book a Room Page', 'room-standard', array(
			'booking_hero_title' => array( 'Banner heading', 'Book a Room', 'text' ),
			'booking_hero_sub'   => array( 'Banner text', 'Send your request and we will confirm by phone or WhatsApp', 'text' ),
			'booking_note'       => array( 'Payment note under the form', 'No payment is taken online. You pay at reception (cash or mobile money) after we confirm your booking.', 'textarea' ),
			'booking_cancel'     => array( 'Cancellation policy (short)', '24 hrs notice', 'text' ),
		) ),
		'contact' => array( 'Contact Us Page', 'reception', array(
			'contact_hero_title' => array( 'Banner heading', 'Contact Us', 'text' ),
			'contact_hero_sub'   => array( 'Banner text', 'We are here to help, 24 hours a day', 'text' ),
			'contact_find_title' => array( 'Directions – heading', 'How to find us', 'text' ),
			'contact_find_text'  => array( 'Directions – text', 'We are on <strong>Market Street in Kyenjojo town</strong>, near the <strong>Centenary Bank building</strong>. Kyenjojo is on the Kampala to Fort Portal highway, about 47 km before Fort Portal.', 'textarea' ),
			'contact_tips'       => array( 'Directions – list (one per line)', "<strong>By bus or taxi:</strong> ask to be dropped in Kyenjojo town, then walk to Market Street.\n<strong>By car:</strong> turn into town from the main highway and look for Centenary Bank. Free parking is available.\n<strong>Lost?</strong> Call or WhatsApp us and we will guide you.", 'lines' ),
		) ),
		'faq' => array( 'FAQ Page', 'room-extra2', array(
			'faq_hero_title' => array( 'Banner heading', 'Frequently Asked Questions', 'text' ),
			'faq_hero_sub'   => array( 'Banner text', 'Quick answers before you book', 'text' ),
		) ),
		'footer' => array( 'Footer & Booking Band', '', array(
			'cta_title'    => array( 'Gold band – heading', 'Planning a trip to Kyenjojo or Kibale?', 'text' ),
			'cta_text'     => array( 'Gold band – text', 'Reserve your room in a minute. We confirm every booking by phone or WhatsApp.', 'text' ),
			'footer_about' => array( 'Footer – about the motel', 'A clean, quiet and affordable place to stay on Market Street, Kyenjojo, on the road between Kampala and Fort Portal.', 'textarea' ),
		) ),
	);
}

function elroi_text_defaults() {
	static $d = null;
	if ( null === $d ) {
		$d = array();
		foreach ( elroi_text_fields() as $sec ) {
			foreach ( $sec[2] as $k => $f ) { $d[ $k ] = array( $f[1], $f[2] ); }
		}
	}
	return $d;
}

/** Get a text value (raw). */
function elroi_t( $key ) {
	$d = elroi_text_defaults();
	return get_theme_mod( 'elroi_txt_' . $key, isset( $d[ $key ] ) ? $d[ $key ][0] : '' );
}

/** Echo safe text: plain for single lines, basic HTML (bold/italic/links) for paragraphs. */
function elroi_e( $key ) {
	$d = elroi_text_defaults();
	$type = isset( $d[ $key ] ) ? $d[ $key ][1] : 'text';
	echo 'text' === $type ? esc_html( elroi_t( $key ) ) : wp_kses( elroi_t( $key ), elroi_allowed_html() );
}

function elroi_allowed_html() {
	return array( 'strong' => array(), 'b' => array(), 'em' => array(), 'i' => array(), 'br' => array(), 'a' => array( 'href' => array(), 'target' => array() ) );
}

/** Split a "lines" field into an array of lines. */
function elroi_lines( $key ) {
	return array_values( array_filter( array_map( 'trim', preg_split( '/\r\n|\r|\n/', (string) elroi_t( $key ) ) ) ) );
}

/** Banner photo for a page. */
function elroi_banner( $page ) {
	$f = elroi_text_fields();
	$custom = get_theme_mod( 'elroi_banner_' . $page );
	return $custom ? $custom : ( isset( $f[ $page ] ) ? $f[ $page ][1] : 'exterior' );
}

/** Friendly names for the shared site photos. */
function elroi_photo_labels() {
	return array(
		'hero' => 'Home page big photo (motel building)', 'exterior' => 'Outside of the motel', 'reception' => 'Reception',
		'terrace' => 'Terrace', 'parking' => 'Parking', 'gym' => 'Fitness room', 'wifi' => 'Wi-Fi / laptop',
		'workdesk' => 'Desk / TV area', 'bathroom' => 'Bathroom', 'room-extra1' => 'Extra room photo 1', 'room-extra2' => 'Extra room photo 2',
		'restaurant' => 'Restaurant', 'dining-room' => 'Dining room', 'breakfast' => 'Breakfast', 'breakfast2' => 'Breakfast table',
		'coffee' => 'Tea / coffee', 'bar' => 'Bar',
	);
}

/* ------------------------------------------------------------------
 * Customizer
 * ---------------------------------------------------------------- */
function elroi_customize_text( $wp_customize ) {
	$wp_customize->add_panel( 'elroi_text_panel', array(
		'title'       => 'Page Text & Photos',
		'priority'    => 25,
		'description' => 'Change the words and photos on every page. Open a section, edit, watch the preview, then click Publish.',
	) );

	$prio = 1;
	foreach ( elroi_text_fields() as $page => $sec ) {
		$section = 'elroi_txt_' . $page;
		$wp_customize->add_section( $section, array( 'title' => $sec[0], 'panel' => 'elroi_text_panel', 'priority' => $prio++ ) );

		if ( $sec[1] ) {
			$wp_customize->add_setting( 'elroi_banner_' . $page, array( 'default' => '', 'sanitize_callback' => 'esc_url_raw' ) );
			$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'elroi_banner_' . $page, array(
				'label' => 'Banner photo', 'section' => $section, 'description' => 'Leave empty to use the default photo.',
			) ) );
		}
		foreach ( $sec[2] as $key => $f ) {
			$wp_customize->add_setting( 'elroi_txt_' . $key, array(
				'default'           => $f[1],
				'sanitize_callback' => 'text' === $f[2] ? 'sanitize_text_field' : 'elroi_sanitize_rich',
			) );
			$wp_customize->add_control( 'elroi_txt_' . $key, array(
				'label'   => $f[0],
				'section' => $section,
				'type'    => 'text' === $f[2] ? 'text' : 'textarea',
			) );
		}
	}

	// Shared photos.
	$wp_customize->add_section( 'elroi_photos', array(
		'title' => 'Site Photos', 'panel' => 'elroi_text_panel', 'priority' => 99,
		'description' => 'Replace the sample photos with real photos of the motel. Room photos are changed in Motel Content > Rooms, and place photos in Motel Content > Places to Visit.',
	) );
	foreach ( elroi_photo_labels() as $key => $label ) {
		$wp_customize->add_setting( 'elroi_photo_' . $key, array( 'default' => '', 'sanitize_callback' => 'esc_url_raw' ) );
		$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'elroi_photo_' . $key, array( 'label' => $label, 'section' => 'elroi_photos' ) ) );
	}
}
add_action( 'customize_register', 'elroi_customize_text' );

function elroi_sanitize_rich( $v ) {
	return wp_kses( $v, elroi_allowed_html() );
}
