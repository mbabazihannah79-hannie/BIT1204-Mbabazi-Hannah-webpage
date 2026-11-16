<?php
/**
 * Reusable dynamic sections. Used by the ERK Elementor widgets.
 * Each function prints HTML that reads live data from Motel Content.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function elroi_grid_class( $cols ) {
	$cols = (int) $cols;
	return 'grid grid--' . ( in_array( $cols, array( 2, 3, 4 ), true ) ? $cols : 4 );
}

/** Room cards. $limit 0 = all. */
function elroi_render_rooms_grid( $limit = 0, $cols = 4 ) {
	$rooms = elroi_rooms();
	if ( $limit > 0 ) { $rooms = array_slice( $rooms, 0, $limit, true ); }
	echo '<div class="' . esc_attr( elroi_grid_class( $cols ) ) . '">';
	foreach ( $rooms as $slug => $room ) { elroi_room_card( $slug, $room ); }
	echo '</div>';
}

/** Full room listing with photos, features and booking buttons. */
function elroi_render_room_details( $show_compare = true ) {
	$i = 0;
	foreach ( elroi_rooms() as $slug => $room ) :
		$i++; ?>
		<article class="room-detail <?php echo 0 === $i % 2 ? 'room-detail--flip' : ''; ?>" id="room-<?php echo esc_attr( $slug ); ?>">
			<div class="room-detail__gallery">
				<a href="<?php echo esc_url( elroi_img( $room['gallery'][0], 1600 ) ); ?>" class="js-lightbox room-detail__main" data-caption="<?php echo esc_attr( $room['name'] ); ?>">
					<?php elroi_image_tag( $room['gallery'][0], $room['name'], 1000 ); ?>
				</a>
				<div class="room-detail__thumbs">
					<?php foreach ( array_slice( $room['gallery'], 1 ) as $g ) : ?>
						<a href="<?php echo esc_url( elroi_img( $g, 1600 ) ); ?>" class="js-lightbox" data-caption="<?php echo esc_attr( $room['name'] ); ?>"><?php elroi_image_tag( $g, $room['name'], 500 ); ?></a>
					<?php endforeach; ?>
				</div>
			</div>
			<div class="room-detail__info">
				<h2><?php echo esc_html( $room['name'] ); ?></h2>
				<p class="room-price"><?php echo esc_html( elroi_money( $room['price'] ) ); ?> <small>per night</small></p>
				<p class="room-meta">
					<span><?php echo elroi_icon( 'users' ); ?> Up to <?php echo intval( $room['guests'] ); ?> guests</span>
					<span><?php echo elroi_icon( 'bed' ); ?> <?php echo esc_html( $room['bed'] ); ?></span>
				</p>
				<p><?php echo esc_html( $room['desc'] ); ?></p>
				<ul class="feature-list">
					<?php foreach ( $room['features'] as $f ) : ?><li><?php echo elroi_icon( 'check' ); ?> <?php echo esc_html( $f ); ?></li><?php endforeach; ?>
				</ul>
				<div class="btn-row">
					<a class="btn btn--gold" href="<?php echo esc_url( add_query_arg( 'room', $slug, elroi_page_url( 'booking' ) ) ); ?>">Book this room</a>
					<a class="btn btn--outline" href="<?php echo esc_url( elroi_whatsapp_url( 'Hello, I would like to ask about the ' . $room['name'] . '.' ) ); ?>" target="_blank" rel="noopener">Ask on WhatsApp</a>
				</div>
			</div>
		</article>
	<?php endforeach;

	if ( $show_compare ) : ?>
		<div class="table-wrap" style="margin-top:3rem">
			<table class="compare">
				<thead><tr><th>Room</th><th>Guests</th><th>Beds</th><th>Balcony</th><th>Dining area</th><th>Price / night</th></tr></thead>
				<tbody>
				<?php foreach ( elroi_rooms() as $slug => $room ) :
					$feat = strtolower( implode( ' ', $room['features'] ) ); ?>
					<tr>
						<td><a href="#room-<?php echo esc_attr( $slug ); ?>"><?php echo esc_html( $room['name'] ); ?></a></td>
						<td><?php echo intval( $room['guests'] ); ?></td>
						<td><?php echo esc_html( $room['bed'] ); ?></td>
						<td><?php echo false !== strpos( $feat, 'balcony' ) ? 'Yes' : 'No'; ?></td>
						<td><?php echo false !== strpos( $feat, 'dining' ) ? 'Yes' : 'No'; ?></td>
						<td><strong><?php echo esc_html( elroi_money( $room['price'] ) ); ?></strong></td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	<?php endif;
}

/** Check-availability bar. */
function elroi_render_quick_book( $button = 'Check Availability' ) { ?>
	<form class="quick-book quick-book--inline" action="<?php echo esc_url( elroi_page_url( 'booking' ) ); ?>" method="get">
		<label>Check-in<input type="date" name="checkin" required min="<?php echo esc_attr( gmdate( 'Y-m-d' ) ); ?>"></label>
		<label>Check-out<input type="date" name="checkout" required min="<?php echo esc_attr( gmdate( 'Y-m-d' ) ); ?>"></label>
		<label>Room
			<select name="room">
				<?php foreach ( elroi_rooms() as $slug => $r ) : ?><option value="<?php echo esc_attr( $slug ); ?>"><?php echo esc_html( $r['name'] ); ?></option><?php endforeach; ?>
			</select>
		</label>
		<label>Guests<input type="number" name="guests" min="1" max="8" value="2"></label>
		<button class="btn btn--gold" type="submit"><?php echo esc_html( $button ); ?></button>
	</form>
<?php }

function elroi_render_amenities( $home_only = false, $cols = 4 ) {
	echo '<div class="' . esc_attr( elroi_grid_class( $cols ) ) . ' amenities">';
	foreach ( elroi_amenities( $home_only ) as $a ) : ?>
		<div class="amenity">
			<span class="amenity__icon"><?php echo elroi_icon( $a['icon'] ); ?></span>
			<h3><?php echo esc_html( $a['title'] ); ?></h3>
			<p><?php echo esc_html( $a['text'] ); ?></p>
		</div>
	<?php endforeach;
	echo '</div>';
}

/** Places to visit: 'cards' (photo tiles) or 'rows' (photo + text). */
function elroi_render_places( $layout = 'cards', $limit = 0 ) {
	$places = elroi_attractions();
	if ( $limit > 0 ) { $places = array_slice( $places, 0, $limit ); }
	if ( 'rows' === $layout ) {
		foreach ( $places as $i => $a ) : ?>
			<div class="split <?php echo 1 === $i % 2 ? 'split--flip' : ''; ?> explore-row">
				<div class="split__media"><?php elroi_image_tag( $a['image'], $a['title'], 900, 'rounded' ); ?></div>
				<div class="split__text">
					<span class="chip"><?php echo esc_html( $a['distance'] ); ?></span>
					<h2><?php echo esc_html( $a['title'] ); ?></h2>
					<p><?php echo esc_html( $a['text'] ); ?></p>
				</div>
			</div>
		<?php endforeach;
		return;
	}
	echo '<div class="grid grid--4">';
	foreach ( $places as $a ) : ?>
		<article class="place-card">
			<?php elroi_image_tag( $a['image'], $a['title'], 700 ); ?>
			<div class="place-card__body">
				<span class="chip"><?php echo esc_html( $a['distance'] ); ?></span>
				<h3><?php echo esc_html( $a['title'] ); ?></h3>
			</div>
		</article>
	<?php endforeach;
	echo '</div>';
}

function elroi_render_menu() {
	echo '<div class="grid grid--3 menu-cols">';
	foreach ( elroi_menu() as $section => $items ) : ?>
		<div class="menu-col">
			<h3><?php echo esc_html( $section ); ?></h3>
			<ul class="menu-list">
				<?php foreach ( $items as $it ) : ?>
					<li>
						<div><strong><?php echo esc_html( $it[0] ); ?></strong><small><?php echo esc_html( $it[1] ); ?></small></div>
						<span><?php echo esc_html( elroi_money( $it[2] ) ); ?></span>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	<?php endforeach;
	echo '</div>';
}

/** All gallery photos: [image, caption, category]. */
function elroi_gallery_items() {
	$gallery = array(
		array( 'hero', 'Motel building', 'motel' ),
		array( 'exterior', 'Outside view', 'motel' ),
		array( 'reception', 'Reception', 'motel' ),
		array( 'terrace', 'Terrace', 'motel' ),
		array( 'parking', 'Parking', 'motel' ),
		array( 'gym', 'Fitness room', 'motel' ),
	);
	foreach ( elroi_rooms() as $room ) {
		foreach ( array_unique( $room['gallery'] ) as $g ) { $gallery[] = array( $g, $room['name'], 'rooms' ); }
	}
	$gallery[] = array( 'bathroom', 'Private bathroom', 'rooms' );
	foreach ( array( 'restaurant' => 'Restaurant', 'dining-room' => 'Dining room', 'breakfast' => 'Breakfast', 'breakfast2' => 'Breakfast table', 'coffee' => 'Coffee', 'bar' => 'Bar' ) as $k => $l ) {
		$gallery[] = array( $k, $l, 'dining' );
	}
	foreach ( elroi_attractions() as $a ) { $gallery[] = array( $a['image'], $a['title'], 'area' ); }
	return $gallery;
}

/** Gallery: 'grid' (with filter buttons) or 'mosaic' (6-photo collage). */
function elroi_render_gallery( $style = 'grid', $filters = true, $limit = 0 ) {
	if ( 'mosaic' === $style ) {
		$rooms = array_values( elroi_rooms() );
		$shots = array(
			array( isset( $rooms[1] ) ? $rooms[1]['image'] : 'room-deluxe', isset( $rooms[1] ) ? $rooms[1]['name'] : 'Room' ),
			array( 'terrace', 'Terrace' ),
			array( 'breakfast', 'Breakfast' ),
			array( isset( $rooms[2] ) ? $rooms[2]['image'] : 'room-family', isset( $rooms[2] ) ? $rooms[2]['name'] : 'Room' ),
			array( 'bar', 'Bar' ),
			array( 'bathroom', 'Bathroom' ),
		);
		echo '<div class="mosaic">';
		foreach ( $shots as $s ) {
			echo '<a href="' . esc_url( elroi_img( $s[0], 1600 ) ) . '" class="js-lightbox" data-caption="' . esc_attr( $s[1] ) . '">';
			elroi_image_tag( $s[0], $s[1], 700 );
			echo '</a>';
		}
		echo '</div>';
		return;
	}
	$items = elroi_gallery_items();
	if ( $limit > 0 ) { $items = array_slice( $items, 0, $limit ); }
	if ( $filters ) {
		$f = array( 'all' => 'All', 'motel' => 'The Motel', 'rooms' => 'Rooms', 'dining' => 'Dining & Bar', 'area' => 'Around Kyenjojo' );
		echo '<div class="filters">';
		foreach ( $f as $k => $l ) {
			echo '<button type="button" class="filter ' . ( 'all' === $k ? 'is-active' : '' ) . '" data-filter="' . esc_attr( $k ) . '">' . esc_html( $l ) . '</button>';
		}
		echo '</div>';
	}
	echo '<div class="gallery-grid">';
	foreach ( $items as $g ) {
		echo '<a href="' . esc_url( elroi_img( $g[0], 1600 ) ) . '" class="gallery-item js-lightbox" data-cat="' . esc_attr( $g[2] ) . '" data-caption="' . esc_attr( $g[1] ) . '">';
		elroi_image_tag( $g[0], $g[1], 700 );
		echo '<span>' . esc_html( $g[1] ) . '</span></a>';
	}
	echo '</div>';
}

function elroi_render_faq() {
	echo '<div class="faq">';
	foreach ( elroi_faqs() as $i => $f ) {
		echo '<details ' . ( 0 === $i ? 'open' : '' ) . '><summary>' . esc_html( $f['q'] ) . '</summary><p>' . esc_html( $f['a'] ) . '</p></details>';
	}
	echo '</div>';
}

function elroi_render_map( $height = 380 ) {
	echo '<div class="map" style="--map-h:' . intval( $height ) . 'px">';
	get_template_part( 'template-parts/map' );
	echo '</div>';
}

function elroi_render_contact_list() { ?>
	<ul class="contact-list">
		<li><?php echo elroi_icon( 'pin' ); ?> <?php echo esc_html( elroi_opt( 'address' ) ); ?></li>
		<li><?php echo elroi_icon( 'phone' ); ?> <a href="<?php echo esc_url( elroi_tel_url() ); ?>"><?php echo esc_html( elroi_opt( 'phone' ) ); ?></a></li>
		<li><?php echo elroi_icon( 'whatsapp' ); ?> <a href="<?php echo esc_url( elroi_whatsapp_url() ); ?>" target="_blank" rel="noopener">WhatsApp</a></li>
		<li><?php echo elroi_icon( 'mail' ); ?> <a href="mailto:<?php echo esc_attr( elroi_opt( 'email' ) ); ?>"><?php echo esc_html( elroi_opt( 'email' ) ); ?></a></li>
		<li><?php echo elroi_icon( 'clock' ); ?> Reception open 24 hours</li>
	</ul>
<?php }

function elroi_render_contact_cards() { ?>
	<div class="contact-cards">
		<a class="contact-card" href="<?php echo esc_url( elroi_tel_url() ); ?>">
			<span class="amenity__icon"><?php echo elroi_icon( 'phone' ); ?></span><h3>Call us</h3><p><?php echo esc_html( elroi_opt( 'phone' ) ); ?></p>
		</a>
		<a class="contact-card" href="<?php echo esc_url( elroi_whatsapp_url( 'Hello ' . elroi_opt( 'motel_name' ) ) ); ?>" target="_blank" rel="noopener">
			<span class="amenity__icon"><?php echo elroi_icon( 'whatsapp' ); ?></span><h3>WhatsApp</h3><p>Chat with reception</p>
		</a>
		<a class="contact-card" href="mailto:<?php echo esc_attr( elroi_opt( 'email' ) ); ?>">
			<span class="amenity__icon"><?php echo elroi_icon( 'mail' ); ?></span><h3>Email</h3><p><?php echo esc_html( elroi_opt( 'email' ) ); ?></p>
		</a>
		<a class="contact-card" href="https://www.google.com/maps/search/?api=1&amp;query=<?php echo rawurlencode( elroi_opt( 'map_query' ) ); ?>" target="_blank" rel="noopener">
			<span class="amenity__icon"><?php echo elroi_icon( 'pin' ); ?></span><h3>Visit us</h3><p><?php echo esc_html( elroi_opt( 'address' ) ); ?></p>
		</a>
	</div>
<?php }

function elroi_render_contact_form( $button = 'Send Message' ) {
	if ( isset( $_GET['sent'] ) && '1' === $_GET['sent'] ) {
		echo '<div class="alert alert--ok">Thank you! Your message was sent. We will get back to you soon.</div>';
	} elseif ( isset( $_GET['err'] ) ) {
		echo '<div class="alert alert--error">Please fill in your name, phone or email, and your message.</div>';
	} ?>
	<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" class="form" id="contact-form">
		<input type="hidden" name="action" value="elroi_contact">
		<?php wp_nonce_field( 'elroi_contact', 'elroi_nonce' ); ?>
		<input type="text" name="website" class="hp" tabindex="-1" autocomplete="off" aria-hidden="true">
		<div class="form-row">
			<label>Your name *<input type="text" name="full_name" required></label>
			<label>Phone or email *<input type="text" name="contact" required></label>
		</div>
		<label>Subject<input type="text" name="subject" placeholder="Booking question, group stay, events..."></label>
		<label>Message *<textarea name="message" rows="6" required></textarea></label>
		<button class="btn btn--gold" type="submit"><?php echo esc_html( $button ); ?></button>
	</form>
<?php }

/** Booking form (with optional side panel of rates and policies). */
function elroi_render_booking( $sidebar = true, $button = 'Send Booking Request' ) {
	$rooms  = elroi_rooms();
	$sel    = isset( $_GET['room'] ) ? sanitize_key( wp_unslash( $_GET['room'] ) ) : key( $rooms );
	$cin    = isset( $_GET['checkin'] ) ? sanitize_text_field( wp_unslash( $_GET['checkin'] ) ) : '';
	$cout   = isset( $_GET['checkout'] ) ? sanitize_text_field( wp_unslash( $_GET['checkout'] ) ) : '';
	$guests = isset( $_GET['guests'] ) ? max( 1, intval( $_GET['guests'] ) ) : 2;
	$errors = array(
		'missing'  => 'Please fill in your name, phone number, room and both dates.',
		'dates'    => 'Please check your dates. Check-in cannot be in the past, and check-out must be after check-in.',
		'security' => 'Your session expired. Please try again.',
		'save'     => 'Something went wrong while saving. Please try again or call us.',
	);
	$err   = isset( $_GET['err'] ) ? sanitize_key( wp_unslash( $_GET['err'] ) ) : '';
	$today = gmdate( 'Y-m-d' ); ?>
	<div class="booking <?php echo $sidebar ? '' : 'booking--single'; ?>">
		<div class="booking__form" id="booking-form">
			<?php if ( isset( $_GET['booked'] ) ) : ?>
				<div class="alert alert--ok">
					<h3>Thank you! Your booking request was received.</h3>
					<p>Your reference is <strong><?php echo esc_html( sanitize_text_field( wp_unslash( $_GET['ref'] ?? '' ) ) ); ?></strong>. We will call or WhatsApp you shortly to confirm your room and payment. Need it faster? Call <a href="<?php echo esc_url( elroi_tel_url() ); ?>"><?php echo esc_html( elroi_opt( 'phone' ) ); ?></a>.</p>
				</div>
			<?php elseif ( $err && isset( $errors[ $err ] ) ) : ?>
				<div class="alert alert--error"><?php echo esc_html( $errors[ $err ] ); ?></div>
			<?php endif; ?>
			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" class="form js-booking">
				<input type="hidden" name="action" value="elroi_booking">
				<?php wp_nonce_field( 'elroi_booking', 'elroi_nonce' ); ?>
				<input type="text" name="website" class="hp" tabindex="-1" autocomplete="off" aria-hidden="true">
				<h2>Your stay</h2>
				<div class="form-row">
					<label>Check-in date *<input type="date" name="checkin" required min="<?php echo esc_attr( $today ); ?>" value="<?php echo esc_attr( $cin ); ?>"></label>
					<label>Check-out date *<input type="date" name="checkout" required min="<?php echo esc_attr( $today ); ?>" value="<?php echo esc_attr( $cout ); ?>"></label>
				</div>
				<div class="form-row">
					<label>Room type *
						<select name="room" required>
							<?php foreach ( $rooms as $slug => $r ) : ?>
								<option value="<?php echo esc_attr( $slug ); ?>" <?php selected( $sel, $slug ); ?>><?php echo esc_html( $r['name'] . ' – ' . elroi_money( $r['price'] ) ); ?></option>
							<?php endforeach; ?>
						</select>
					</label>
					<label>Number of rooms<input type="number" name="rooms_qty" min="1" max="8" value="1"></label>
					<label>Guests<input type="number" name="guests" min="1" max="20" value="<?php echo esc_attr( $guests ); ?>"></label>
				</div>
				<h2>Your details</h2>
				<div class="form-row">
					<label>Full name *<input type="text" name="full_name" required autocomplete="name"></label>
					<label>Phone / WhatsApp *<input type="tel" name="phone" required placeholder="07XX XXX XXX" autocomplete="tel"></label>
				</div>
				<label>Email (optional)<input type="email" name="email" autocomplete="email"></label>
				<label>Special requests<textarea name="notes" rows="4" placeholder="Arrival time, breakfast, extra bed, airport or bus pick-up..."></textarea></label>
				<div class="summary" aria-live="polite"><span>Estimated total:</span><strong class="js-total">Choose your dates</strong></div>
				<p class="muted small"><?php elroi_e( 'booking_note' ); ?></p>
				<button class="btn btn--gold btn--block" type="submit"><?php echo esc_html( $button ); ?></button>
			</form>
		</div>
		<?php if ( $sidebar ) : ?>
			<aside class="booking__side">
				<div class="side-card">
					<h3>Prefer to talk?</h3>
					<p><a class="btn btn--block" href="<?php echo esc_url( elroi_tel_url() ); ?>"><?php echo elroi_icon( 'phone' ); ?> <?php echo esc_html( elroi_opt( 'phone' ) ); ?></a></p>
					<p><a class="btn btn--outline btn--block" href="<?php echo esc_url( elroi_whatsapp_url( 'Hello, I would like to book a room at ' . elroi_opt( 'motel_name' ) . '.' ) ); ?>" target="_blank" rel="noopener"><?php echo elroi_icon( 'whatsapp' ); ?> Book on WhatsApp</a></p>
				</div>
				<div class="side-card">
					<h3>Our rates</h3>
					<ul class="rate-list">
						<?php foreach ( $rooms as $r ) : ?><li><span><?php echo esc_html( $r['name'] ); ?></span><strong><?php echo esc_html( elroi_money( $r['price'] ) ); ?></strong></li><?php endforeach; ?>
					</ul>
					<p class="muted small">Per room, per night.</p>
				</div>
				<div class="side-card">
					<h3>Policies</h3>
					<ul class="rate-list">
						<li><span>Check-in</span><strong>from <?php echo esc_html( elroi_opt( 'checkin' ) ); ?></strong></li>
						<li><span>Check-out</span><strong>by <?php echo esc_html( elroi_opt( 'checkout' ) ); ?></strong></li>
						<li><span>Smoking</span><strong>Not allowed</strong></li>
						<li><span>Cancellation</span><strong><?php elroi_e( 'booking_cancel' ); ?></strong></li>
					</ul>
				</div>
			</aside>
		<?php endif; ?>
	</div>
<?php }
