<?php
/**
 * Site content: images, rooms, amenities, attractions and FAQs.
 * Edit this file to change room names, prices and text.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Image library. Each key points to a free Unsplash photo (loaded from the internet).
 * To use your own photo instead, save it as assets/images/<key>.jpg
 * (for example assets/images/hero.jpg) and the theme will use it automatically.
 */
function elroi_images() {
	return array(
		'hero'        => 'photo-1551882547-ff40c63fe5fa',
		'exterior'    => 'photo-1542314831-068cd1dbfeeb',
		'reception'   => 'photo-1564501049412-61c2a3083791',
		'room-standard' => 'photo-1611892440504-42a792e24d32',
		'room-deluxe' => 'photo-1590490360182-c33d57733427',
		'room-family' => 'photo-1631049307264-da0ec9d70304',
		'room-apartment' => 'photo-1582719478250-c89cae4dc85b',
		'room-extra1' => 'photo-1618773928121-c32242e63f39',
		'room-extra2' => 'photo-1596394516093-501ba68a0ba6',
		'bathroom'    => 'photo-1552321554-5fefe8c9ef14',
		'restaurant'  => 'photo-1414235077428-338989a2e8c0',
		'dining-room' => 'photo-1517248135467-4c7edcad34c4',
		'breakfast'   => 'photo-1533777857889-4be7c70b33f7',
		'breakfast2'  => 'photo-1525610553991-2bede1a236e2',
		'coffee'      => 'photo-1509042239860-f550ce710b93',
		'bar'         => 'photo-1514933651103-005eec06c04b',
		'gym'         => 'photo-1534438327276-14e5300c3a48',
		'terrace'     => 'photo-1600585154340-be6161a56a0c',
		'workdesk'    => 'photo-1497366216548-37526070297c',
		'wifi'        => 'photo-1498050108023-c5249f4df085',
		'parking'     => 'photo-1506521781263-d8422e82f27a',
		'landscape'   => 'photo-1516426122078-c23e76319801',
		'chimp'       => 'photo-1540573133985-87b6da6d54a9',
		'tea'         => 'photo-1563822249366-3efb23b8e0c9',
	);
}

/** Return the URL for an image key (local file first, then Unsplash). */
function elroi_img( $key, $width = 1200 ) {
	// A full URL (e.g. a photo chosen from the Media Library) is used as-is.
	if ( is_string( $key ) && preg_match( '#^(https?:)?//#', $key ) ) {
		return $key;
	}
	// Photo chosen in Appearance > Customize > Site Photos.
	$custom = get_theme_mod( 'elroi_photo_' . $key );
	if ( $custom ) {
		return $custom;
	}
	foreach ( array( 'jpg', 'jpeg', 'png', 'webp' ) as $ext ) {
		$rel = 'assets/images/' . $key . '.' . $ext;
		if ( file_exists( get_template_directory() . '/' . $rel ) ) {
			return get_template_directory_uri() . '/' . $rel;
		}
	}
	$images = elroi_images();
	if ( isset( $images[ $key ] ) ) {
		return 'https://images.unsplash.com/' . $images[ $key ] . '?auto=format&fit=crop&w=' . intval( $width ) . '&q=80';
	}
	return elroi_placeholder();
}

function elroi_placeholder() {
	return get_template_directory_uri() . '/assets/images/placeholder.svg';
}

/** Print an <img> tag with a local fallback if the online photo fails to load. */
function elroi_image_tag( $key, $alt, $width = 1200, $class = '' ) {
	printf(
		'<img src="%1$s" alt="%2$s" class="%3$s" loading="lazy" onerror="this.onerror=null;this.src=\'%4$s\';">',
		esc_url( elroi_img( $key, $width ) ),
		esc_attr( $alt ),
		esc_attr( $class ),
		esc_url( elroi_placeholder() )
	);
}

/**
 * Rooms. The motel has 8 rooms in total.
 * PRICES ARE SAMPLE FIGURES: confirm the real rates with the motel and edit them here.
 */
function elroi_default_rooms() {
	return array(
		'standard' => array(
			'name'   => 'Standard Double Room',
			'price'  => 60000,
			'guests' => 2,
			'bed'    => '1 double bed',
			'image'  => 'room-standard',
			'gallery'=> array( 'room-standard', 'bathroom', 'workdesk' ),
			'desc'   => 'A clean, quiet room for solo travellers and couples, with a private shower room, free toiletries and a work desk.',
			'features' => array( 'Private bathroom with shower', 'Free toiletries', 'Flat-screen TV', 'Free Wi-Fi', 'Electric kettle', 'Wardrobe', 'Work desk' ),
		),
		'deluxe' => array(
			'name'   => 'Deluxe Room with Balcony',
			'price'  => 80000,
			'guests' => 2,
			'bed'    => '1 large double bed',
			'image'  => 'room-deluxe',
			'gallery'=> array( 'room-deluxe', 'room-extra1', 'bathroom' ),
			'desc'   => 'Our most popular room. Step out onto your own balcony with a view over Kyenjojo town, then rest in a roomy double bed.',
			'features' => array( 'Private balcony with town view', 'Private bathroom with shower', 'Flat-screen TV', 'Free Wi-Fi', 'Electric kettle', 'Safety deposit box', 'Work desk' ),
		),
		'family' => array(
			'name'   => 'Family Room',
			'price'  => 120000,
			'guests' => 4,
			'bed'    => '1 double bed + 2 single beds',
			'image'  => 'room-family',
			'gallery'=> array( 'room-family', 'room-extra2', 'bathroom' ),
			'desc'   => 'Space for the whole family, with a dining area, balcony and enough beds for four. Limited number available, so book early.',
			'features' => array( 'Sleeps 4', 'Balcony', 'Dining area', 'Private bathroom with shower', 'Flat-screen TV', 'Free Wi-Fi', 'Electric kettle' ),
		),
		'apartment' => array(
			'name'   => 'One-Bedroom Apartment with Balcony',
			'price'  => 150000,
			'guests' => 2,
			'bed'    => '1 large double bed',
			'image'  => 'room-apartment',
			'gallery'=> array( 'room-apartment', 'room-extra1', 'workdesk' ),
			'desc'   => 'Ideal for longer stays and business travellers: a separate bedroom, dining area, flat-screen TV and a balcony with city views.',
			'features' => array( 'Separate bedroom', 'Balcony with city view', 'Dining area', 'Flat-screen TV', 'Private bathroom with shower', 'Free Wi-Fi', 'Work desk' ),
		),
	);
}

function elroi_money( $amount ) {
	return 'UGX ' . number_format( (float) $amount );
}

/** Amenities, taken from the motel's public listings. */
function elroi_default_amenities() {
	return array(
		array( 'icon' => 'wifi',    'title' => 'Free Wi-Fi',         'text' => 'Free wireless internet in every room and across the property.' ),
		array( 'icon' => 'car',     'title' => 'Free Private Parking','text' => 'Secure parking on site at no extra cost.' ),
		array( 'icon' => 'food',    'title' => 'Restaurant',         'text' => 'Local and continental dishes served daily.' ),
		array( 'icon' => 'cup',     'title' => 'Breakfast',          'text' => 'Continental or full English breakfast every morning.' ),
		array( 'icon' => 'glass',   'title' => 'Bar',                'text' => 'Relax with a cold drink after a long day on the road.' ),
		array( 'icon' => 'clock',   'title' => '24-Hour Front Desk', 'text' => 'Someone is always at reception, day and night.' ),
		array( 'icon' => 'bell',    'title' => 'Room Service',       'text' => 'Meals and drinks delivered to your room.' ),
		array( 'icon' => 'sun',     'title' => 'Terrace',            'text' => 'An open-air terrace with views over Kyenjojo town.' ),
		array( 'icon' => 'dumbbell','title' => 'Fitness Room',       'text' => 'Keep up your routine in our small on-site gym.' ),
		array( 'icon' => 'lock',    'title' => 'Safety Deposit Box', 'text' => 'Keep your valuables safe in your room.' ),
		array( 'icon' => 'family',  'title' => 'Family Rooms',       'text' => 'Rooms that sleep up to four people.' ),
		array( 'icon' => 'nosmoke', 'title' => 'Non-Smoking',        'text' => 'The whole property is smoke-free for your comfort.' ),
	);
}

/** Places near Kyenjojo (approximate distances by road). */
function elroi_default_attractions() {
	return array(
		array( 'title' => 'Kibale National Park', 'distance' => 'About 25 km', 'image' => 'chimp',
			'text' => 'Home to one of the largest chimpanzee populations in East Africa. Book a chimp-tracking or forest walk and come back to a hot shower and dinner.' ),
		array( 'title' => 'Tea Estates of Kyenjojo', 'distance' => 'Around town', 'image' => 'tea',
			'text' => 'Rolling green tea plantations surround Kyenjojo. The drive along the Kampala to Fort Portal road is beautiful early in the morning.' ),
		array( 'title' => 'Fort Portal & Tooro Botanical Gardens', 'distance' => 'About 47 km', 'image' => 'landscape',
			'text' => 'The tourism city of western Uganda, with crater lakes, the Tooro Botanical Gardens and the Tooro Kingdom palace.' ),
		array( 'title' => 'Kyenjojo Town & Market', 'distance' => 'On our doorstep', 'image' => 'exterior',
			'text' => 'We are on Market Street, near the Centenary Bank building, a short walk from shops, banks and the main road.' ),
	);
}

function elroi_default_faqs() {
	return array(
		array( 'q' => 'What time is check-in and check-out?', 'a' => 'Check-in is from 11:00 AM and check-out is by 12:00 noon. Early check-in or late check-out can be arranged when rooms are free. Just ask at reception.' ),
		array( 'q' => 'Is breakfast included?', 'a' => 'We serve a continental or full English breakfast every morning. Ask at booking whether breakfast is included in your rate.' ),
		array( 'q' => 'Do you have parking?', 'a' => 'Yes. Free private parking is available on site.' ),
		array( 'q' => 'Is there Wi-Fi?', 'a' => 'Yes. Free Wi-Fi is available in all rooms and public areas.' ),
		array( 'q' => 'How do I pay?', 'a' => 'You can pay at reception with cash or mobile money. When you book online, we will call or WhatsApp you to confirm your booking and payment.' ),
		array( 'q' => 'Can I cancel my booking?', 'a' => 'Please let us know at least 24 hours before your arrival date if your plans change.' ),
		array( 'q' => 'Do you have rooms for families?', 'a' => 'Yes. Our Family Rooms sleep up to four people. There are only a few, so book early.' ),
		array( 'q' => 'Can I smoke at the motel?', 'a' => 'The motel is non-smoking in all areas.' ),
		array( 'q' => 'How far are you from Kibale National Park?', 'a' => 'Kibale National Park is about 25 km away, which makes the motel a good base for chimpanzee tracking.' ),
		array( 'q' => 'Do you offer room service?', 'a' => 'Yes. Meals and drinks from our restaurant and bar can be delivered to your room.' ),
	);
}

/** Simple line icons (SVG). */
function elroi_icon( $name ) {
	$p = array(
		'wifi'    => '<path d="M2 9a15 15 0 0 1 20 0M5 12.5a10 10 0 0 1 14 0M8.5 16a5 5 0 0 1 7 0"/><circle cx="12" cy="19.5" r="1"/>',
		'car'     => '<path d="M5 17h14M3 17v-4l2-5h14l2 5v4"/><circle cx="7" cy="17" r="2"/><circle cx="17" cy="17" r="2"/>',
		'food'    => '<path d="M4 3v8a3 3 0 0 0 6 0V3M7 3v18M17 3c-2 2-3 5-3 8h3v10"/>',
		'cup'     => '<path d="M4 8h13v5a6 6 0 0 1-6 6h-1a6 6 0 0 1-6-6zM17 10h2a2 2 0 0 1 0 4h-2M8 2v3M12 2v3"/>',
		'glass'   => '<path d="M6 3h12l-6 8zM12 11v9M8 21h8"/>',
		'clock'   => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
		'bell'    => '<path d="M4 18h16M6 18a6 6 0 0 1 12 0M12 9V7M10 7h4"/>',
		'sun'     => '<circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M2 12h2M20 12h2M5 5l1.5 1.5M17.5 17.5 19 19M5 19l1.5-1.5M17.5 6.5 19 5"/>',
		'dumbbell'=> '<path d="M6 7v10M18 7v10M3 10v4M21 10v4M6 12h12"/>',
		'lock'    => '<rect x="5" y="11" width="14" height="10" rx="2"/><path d="M8 11V7a4 4 0 0 1 8 0v4"/>',
		'family'  => '<circle cx="8" cy="6" r="2.5"/><circle cx="16" cy="6" r="2.5"/><path d="M4 21v-7a4 4 0 0 1 8 0v7M12 21v-7a4 4 0 0 1 8 0v7"/>',
		'nosmoke' => '<circle cx="12" cy="12" r="9"/><path d="M5.6 5.6l12.8 12.8M7 13h7M16 13h1"/>',
		'phone'   => '<path d="M5 3h4l2 5-3 2a12 12 0 0 0 6 6l2-3 5 2v4a2 2 0 0 1-2 2A18 18 0 0 1 3 5a2 2 0 0 1 2-2"/>',
		'mail'    => '<rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/>',
		'pin'     => '<path d="M12 21s7-6 7-12a7 7 0 0 0-14 0c0 6 7 12 7 12z"/><circle cx="12" cy="9" r="2.5"/>',
		'whatsapp'=> '<path d="M4 20l1.5-4A8 8 0 1 1 8 18.5z"/><path d="M9 9c0 3 3 6 6 6l1-1.5-2-1-1 1c-1-.5-2-1.5-2.5-2.5l1-1-1-2z"/>',
		'bed'     => '<path d="M3 18V7M3 14h18v4M21 14v-3a3 3 0 0 0-3-3h-7v6"/><circle cx="7" cy="11" r="2"/>',
		'users'   => '<circle cx="9" cy="8" r="3"/><path d="M3 20v-1a6 6 0 0 1 12 0v1M16 5a3 3 0 0 1 0 6M21 20v-1a5 5 0 0 0-3-4.5"/>',
		'check'   => '<path d="m5 12 4 4 10-10"/>',
		'star'    => '<path d="m12 3 2.8 5.8 6.2.9-4.5 4.4 1 6.2L12 17.4 6.5 20.3l1-6.2L3 9.7l6.2-.9z"/>',
	);
	$path = isset( $p[ $name ] ) ? $p[ $name ] : $p['check'];
	return '<svg class="icon" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . $path . '</svg>';
}

/** Built-in restaurant menu (copied into Motel Content > Restaurant Menu on first setup). */
function elroi_default_menu() {
	return array(
		'Breakfast' => array(
			array( 'Continental breakfast', 'Bread, butter and jam, fresh fruit, juice, tea or coffee', 15000 ),
			array( 'Full English breakfast', 'Eggs, sausages, baked beans, toast, tomato, tea or coffee', 25000 ),
			array( 'Katogo', 'Matooke cooked with beans, offal or groundnut sauce', 12000 ),
			array( 'Rolex', 'Chapati rolled with a vegetable omelette', 6000 ),
		),
		'Main meals' => array(
			array( 'Local chicken stew', 'Served with matooke, rice or posho', 30000 ),
			array( 'Beef or goat stew', 'With greens and your choice of food', 25000 ),
			array( 'Fresh tilapia', 'Whole fried or stewed fish with chips or matooke', 35000 ),
			array( 'Luwombo', 'Chicken or beef steamed in banana leaves', 35000 ),
			array( 'Vegetarian plate', 'Beans, groundnut sauce, greens and matooke', 15000 ),
		),
		'Snacks & drinks' => array(
			array( 'Chips & chicken', 'Crispy chips with grilled chicken', 22000 ),
			array( 'Samosas (3)', 'Beef or vegetable', 6000 ),
			array( 'African tea or coffee', 'Made with fresh local milk', 5000 ),
			array( 'Fresh juice', 'Passion, pineapple or mango', 7000 ),
			array( 'Sodas & water', 'Cold soft drinks and mineral water', 3000 ),
		),
	);
}
