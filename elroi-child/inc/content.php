<?php
/**
 * The motel's lists (amenities, places, menu, FAQs, gallery photos) used to build
 * the page designs. Once the site is built, everything is edited on the pages
 * themselves in the block editor; this file is only the starting content.
 *
 * @package elroi-child
 */

defined( 'ABSPATH' ) || exit;

/** Amenities: [ icon, title, text ]. The first 8 also show on the Home page. */
function erk_amenities() {
	return array(
		array( 'wifi', 'Free Wi-Fi', 'Free wireless internet in every room and across the property.' ),
		array( 'car', 'Free Private Parking', 'Secure parking on site at no extra cost.' ),
		array( 'food', 'Restaurant', 'Local and continental dishes served daily.' ),
		array( 'cup', 'Breakfast', 'Continental or full English breakfast every morning.' ),
		array( 'glass', 'Bar', 'Relax with a cold drink after a long day on the road.' ),
		array( 'clock', '24-Hour Front Desk', 'Someone is always at reception, day and night.' ),
		array( 'bell', 'Room Service', 'Meals and drinks delivered to your room.' ),
		array( 'sun', 'Terrace', 'An open-air terrace with views over Kyenjojo town.' ),
		array( 'dumbbell', 'Fitness Room', 'Keep up your routine in our small on-site gym.' ),
		array( 'lock', 'Safety Deposit Box', 'Keep your valuables safe in your room.' ),
		array( 'family', 'Family Rooms', 'Rooms that sleep up to four people.' ),
		array( 'nosmoke', 'Non-Smoking', 'The whole property is smoke-free for your comfort.' ),
	);
}

/** Places near Kyenjojo: [ photo, title, distance, text ]. */
function erk_places() {
	return array(
		array( 'chimp', 'Kibale National Park', 'About 25 km', 'Home to one of the largest chimpanzee populations in East Africa. Book a chimp-tracking or forest walk and come back to a hot shower and dinner.' ),
		array( 'tea', 'Tea Estates of Kyenjojo', 'Around town', 'Rolling green tea plantations surround Kyenjojo. The drive along the Kampala to Fort Portal road is beautiful early in the morning.' ),
		array( 'landscape', 'Fort Portal & Tooro Botanical Gardens', 'About 47 km', 'The tourism city of western Uganda, with crater lakes, the Tooro Botanical Gardens and the Tooro Kingdom palace.' ),
		array( 'exterior', 'Kyenjojo Town & Market', 'On our doorstep', 'We are on Market Street, near the Centenary Bank building, a short walk from shops, banks and the main road.' ),
	);
}

/** Restaurant menu: section => [ [ name, description, price ] ]. */
function erk_menu() {
	return array(
		'Breakfast'       => array(
			array( 'Continental breakfast', 'Bread, butter and jam, fresh fruit, juice, tea or coffee', 15000 ),
			array( 'Full English breakfast', 'Eggs, sausages, baked beans, toast, tomato, tea or coffee', 25000 ),
			array( 'Katogo', 'Matooke cooked with beans, offal or groundnut sauce', 12000 ),
			array( 'Rolex', 'Chapati rolled with a vegetable omelette', 6000 ),
		),
		'Main meals'      => array(
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

/** Frequently asked questions: [ question, answer ]. */
function erk_faqs() {
	return array(
		array( 'What time is check-in and check-out?', 'Check-in is from 11:00 AM and check-out is by 12:00 noon. Early check-in or late check-out can be arranged when rooms are free. Just ask at reception.' ),
		array( 'Is breakfast included?', 'We serve a continental or full English breakfast every morning. Ask at booking whether breakfast is included in your rate.' ),
		array( 'Do you have parking?', 'Yes. Free private parking is available on site.' ),
		array( 'Is there Wi-Fi?', 'Yes. Free Wi-Fi is available in all rooms and public areas.' ),
		array( 'How do I pay?', 'You can pay at reception with cash or mobile money. When you book online, we will call or WhatsApp you to confirm your booking and payment.' ),
		array( 'Can I cancel my booking?', 'Please let us know at least 24 hours before your arrival date if your plans change.' ),
		array( 'Do you have rooms for families?', 'Yes. Our Family Rooms sleep up to four people. There are only a few, so book early.' ),
		array( 'Can I smoke at the motel?', 'The motel is non-smoking in all areas.' ),
		array( 'How far are you from Kibale National Park?', 'Kibale National Park is about 25 km away, which makes the motel a good base for chimpanzee tracking.' ),
		array( 'Do you offer room service?', 'Yes. Meals and drinks from our restaurant and bar can be delivered to your room.' ),
	);
}

/** Every gallery photo: [ photo, caption, category ]. Categories: motel, rooms, dining, area. */
function erk_gallery_photos() {
	return array(
		array( 'hero', 'Motel building', 'motel' ),
		array( 'exterior', 'Outside view', 'motel' ),
		array( 'reception', 'Reception', 'motel' ),
		array( 'terrace', 'Terrace', 'motel' ),
		array( 'parking', 'Parking', 'motel' ),
		array( 'gym', 'Fitness room', 'motel' ),
		array( 'room-standard', 'Standard Double Room', 'rooms' ),
		array( 'bathroom', 'Standard Double Room', 'rooms' ),
		array( 'workdesk', 'Standard Double Room', 'rooms' ),
		array( 'room-deluxe', 'Deluxe Room with Balcony', 'rooms' ),
		array( 'room-extra1', 'Deluxe Room with Balcony', 'rooms' ),
		array( 'bathroom', 'Deluxe Room with Balcony', 'rooms' ),
		array( 'room-family', 'Family Room', 'rooms' ),
		array( 'room-extra2', 'Family Room', 'rooms' ),
		array( 'bathroom', 'Family Room', 'rooms' ),
		array( 'room-apartment', 'One-Bedroom Apartment with Balcony', 'rooms' ),
		array( 'room-extra1', 'One-Bedroom Apartment with Balcony', 'rooms' ),
		array( 'workdesk', 'One-Bedroom Apartment with Balcony', 'rooms' ),
		array( 'bathroom', 'Private bathroom', 'rooms' ),
		array( 'restaurant', 'Restaurant', 'dining' ),
		array( 'dining-room', 'Dining room', 'dining' ),
		array( 'breakfast', 'Breakfast', 'dining' ),
		array( 'breakfast2', 'Breakfast table', 'dining' ),
		array( 'coffee', 'Coffee', 'dining' ),
		array( 'bar', 'Bar', 'dining' ),
		array( 'chimp', 'Kibale National Park', 'area' ),
		array( 'tea', 'Tea Estates of Kyenjojo', 'area' ),
		array( 'landscape', 'Fort Portal & Tooro Botanical Gardens', 'area' ),
		array( 'exterior', 'Kyenjojo Town & Market', 'area' ),
	);
}

/** "UGX 15,000". */
function erk_money( $amount ) {
	return 'UGX ' . number_format( (float) $amount );
}
