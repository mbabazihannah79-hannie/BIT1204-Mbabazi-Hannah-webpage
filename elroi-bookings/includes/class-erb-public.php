<?php
/**
 * Website side: shortcodes, the booking and contact form handlers, and email alerts.
 *
 *   [erk_rooms]                 room cards (Home page)
 *   [erk_room_details compare="1"] every room with photos, features and a comparison table (Rooms page)
 *   [erk_quick_book]            check-in / check-out search bar that opens the booking form
 *   [erk_booking_form sidebar="1"] booking request form with a live price estimate, rates and policies
 *   [erk_contact_form]          contact form
 *
 * @package elroi-bookings
 */

defined( 'ABSPATH' ) || exit;

class ERB_Public {

	public static function init() {
		add_shortcode( 'erk_rooms', array( __CLASS__, 'sc_rooms' ) );
		add_shortcode( 'erk_room_details', array( __CLASS__, 'sc_room_details' ) );
		add_shortcode( 'erk_quick_book', array( __CLASS__, 'sc_quick_book' ) );
		add_shortcode( 'erk_booking_form', array( __CLASS__, 'sc_booking_form' ) );
		add_shortcode( 'erk_contact_form', array( __CLASS__, 'sc_contact_form' ) );

		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'assets' ) );
		add_action( 'admin_post_nopriv_erb_booking', array( __CLASS__, 'handle_booking' ) );
		add_action( 'admin_post_erb_booking', array( __CLASS__, 'handle_booking' ) );
		add_action( 'admin_post_nopriv_erb_contact', array( __CLASS__, 'handle_contact' ) );
		add_action( 'admin_post_erb_contact', array( __CLASS__, 'handle_contact' ) );
	}

	public static function assets() {
		wp_enqueue_style( 'erb-public', ERB_URL . 'assets/public.css', array(), ERB_VERSION );
		wp_enqueue_script( 'erb-public', ERB_URL . 'assets/public.js', array(), ERB_VERSION, array( 'strategy' => 'defer', 'in_footer' => true ) );
	}

	/* ---------------------------------------------------------------------
	 * Helpers
	 * ------------------------------------------------------------------ */

	/** Line icons from the original design. */
	public static function icon( $name ) {
		$p    = array(
			'users'    => '<circle cx="9" cy="8" r="3"/><path d="M3 20v-1a6 6 0 0 1 12 0v1M16 5a3 3 0 0 1 0 6M21 20v-1a5 5 0 0 0-3-4.5"/>',
			'bed'      => '<path d="M3 18V7M3 14h18v4M21 14v-3a3 3 0 0 0-3-3h-7v6"/><circle cx="7" cy="11" r="2"/>',
			'check'    => '<path d="m5 12 4 4 10-10"/>',
			'phone'    => '<path d="M5 3h4l2 5-3 2a12 12 0 0 0 6 6l2-3 5 2v4a2 2 0 0 1-2 2A18 18 0 0 1 3 5a2 2 0 0 1 2-2"/>',
			'whatsapp' => '<path d="M4 20l1.5-4A8 8 0 1 1 8 18.5z"/><path d="M9 9c0 3 3 6 6 6l1-1.5-2-1-1 1c-1-.5-2-1.5-2.5-2.5l1-1-1-2z"/>',
		);
		$path = isset( $p[ $name ] ) ? $p[ $name ] : $p['check'];
		return '<svg class="erb-icon" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . $path . '</svg>';
	}

	/**
	 * A room's photos as URLs: Media Library photos chosen in Bookings → Rooms first,
	 * then the theme's sample photos.
	 */
	public static function photos( $room, $size = 'large' ) {
		$urls = array();
		foreach ( array_filter( array_map( 'absint', explode( ',', (string) $room->photo_ids ) ) ) as $id ) {
			$u = wp_get_attachment_image_url( $id, $size );
			if ( $u ) {
				$urls[] = $u;
			}
		}
		if ( ! $urls ) {
			foreach ( array_filter( explode( ',', (string) $room->photo_keys ) ) as $key ) {
				$urls[] = apply_filters( 'erb_image_fallback', ERB_URL . 'assets/placeholder.svg', $key );
			}
		}
		if ( ! $urls ) {
			$urls[] = apply_filters( 'erb_image_fallback', ERB_URL . 'assets/placeholder.svg', '' );
		}
		return $urls;
	}

	public static function features( $room ) {
		return array_values( array_filter( array_map( 'trim', preg_split( '/\r\n|\r|\n/', (string) $room->features ) ) ) );
	}

	private static function img( $url, $alt ) {
		return '<img src="' . esc_url( $url ) . '" alt="' . esc_attr( $alt ) . '" loading="lazy">';
	}

	private static function booking_url( $room = null ) {
		$url = erb_page_url( 'booking' );
		return $room ? add_query_arg( 'room', $room->slug, $url ) : $url;
	}

	/* ---------------------------------------------------------------------
	 * Shortcodes
	 * ------------------------------------------------------------------ */

	/** Room cards. */
	public static function sc_rooms( $atts ) {
		$atts  = shortcode_atts( array( 'limit' => 0 ), $atts, 'erk_rooms' );
		$rooms = ERB_DB::rooms();
		if ( (int) $atts['limit'] > 0 ) {
			$rooms = array_slice( $rooms, 0, (int) $atts['limit'] );
		}
		if ( ! $rooms ) {
			return '<p class="erb-empty">Rooms are being updated. Please call us for rates.</p>';
		}
		ob_start();
		echo '<div class="erb-grid">';
		foreach ( $rooms as $room ) {
			$photos = self::photos( $room );
			?>
			<article class="room-card">
				<a class="room-card__img" href="<?php echo esc_url( erb_page_url( 'rooms' ) . '#room-' . $room->slug ); ?>">
					<?php echo self::img( $photos[0], $room->name ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					<span class="price-tag"><?php echo esc_html( erb_money( $room->price ) ); ?><small>/night</small></span>
				</a>
				<div class="room-card__body">
					<h3><?php echo esc_html( $room->name ); ?></h3>
					<p class="room-meta">
						<span><?php echo self::icon( 'users' ); // phpcs:ignore WordPress.Security.EscapeOutput ?> Up to <?php echo (int) $room->max_guests; ?> guests</span>
						<span><?php echo self::icon( 'bed' ); // phpcs:ignore WordPress.Security.EscapeOutput ?> <?php echo esc_html( $room->beds ); ?></span>
					</p>
					<p><?php echo esc_html( $room->description ); ?></p>
					<a class="erb-btn erb-btn--small" href="<?php echo esc_url( self::booking_url( $room ) ); ?>">Book this room</a>
				</div>
			</article>
			<?php
		}
		echo '</div>';
		return ob_get_clean();
	}

	/** Full room listing with photos, features, booking buttons and the comparison table. */
	public static function sc_room_details( $atts ) {
		$atts  = shortcode_atts( array( 'compare' => '1' ), $atts, 'erk_room_details' );
		$rooms = ERB_DB::rooms();
		if ( ! $rooms ) {
			return '<p class="erb-empty">Rooms are being updated. Please call us for rates.</p>';
		}
		ob_start();
		$i = 0;
		foreach ( $rooms as $room ) {
			++$i;
			$photos = array_slice( self::photos( $room ), 0, 3 );
			$full   = self::photos( $room, 'full' );
			?>
			<article class="room-detail<?php echo 0 === $i % 2 ? ' room-detail--flip' : ''; ?>" id="room-<?php echo esc_attr( $room->slug ); ?>">
				<div class="room-detail__gallery">
					<a href="<?php echo esc_url( $full[0] ); ?>" class="js-lightbox room-detail__main" data-caption="<?php echo esc_attr( $room->name ); ?>"><?php echo self::img( $photos[0], $room->name ); // phpcs:ignore WordPress.Security.EscapeOutput ?></a>
					<?php if ( count( $photos ) > 1 ) : ?>
						<div class="room-detail__thumbs">
							<?php foreach ( array_slice( $photos, 1 ) as $n => $url ) : ?>
								<a href="<?php echo esc_url( isset( $full[ $n + 1 ] ) ? $full[ $n + 1 ] : $url ); ?>" class="js-lightbox" data-caption="<?php echo esc_attr( $room->name ); ?>"><?php echo self::img( $url, $room->name ); // phpcs:ignore WordPress.Security.EscapeOutput ?></a>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
				</div>
				<div class="room-detail__info">
					<h2><?php echo esc_html( $room->name ); ?></h2>
					<p class="room-price"><?php echo esc_html( erb_money( $room->price ) ); ?> <small>per night</small></p>
					<p class="room-meta">
						<span><?php echo self::icon( 'users' ); // phpcs:ignore WordPress.Security.EscapeOutput ?> Up to <?php echo (int) $room->max_guests; ?> guests</span>
						<span><?php echo self::icon( 'bed' ); // phpcs:ignore WordPress.Security.EscapeOutput ?> <?php echo esc_html( $room->beds ); ?></span>
					</p>
					<p><?php echo esc_html( $room->description ); ?></p>
					<ul class="feature-list">
						<?php foreach ( self::features( $room ) as $f ) : ?>
							<li><?php echo self::icon( 'check' ); // phpcs:ignore WordPress.Security.EscapeOutput ?> <?php echo esc_html( $f ); ?></li>
						<?php endforeach; ?>
					</ul>
					<div class="btn-row">
						<a class="erb-btn erb-btn--gold" href="<?php echo esc_url( self::booking_url( $room ) ); ?>">Book this room</a>
						<a class="erb-btn erb-btn--outline" href="<?php echo esc_url( erb_whatsapp_url( 'Hello, I would like to ask about the ' . $room->name . '.' ) ); ?>" target="_blank" rel="noopener">Ask on WhatsApp</a>
					</div>
				</div>
			</article>
			<?php
		}

		if ( '1' === (string) $atts['compare'] ) :
			?>
			<div class="table-wrap">
				<table class="compare">
					<thead><tr><th>Room</th><th>Guests</th><th>Beds</th><th>Balcony</th><th>Dining area</th><th>Price / night</th></tr></thead>
					<tbody>
					<?php
					foreach ( $rooms as $room ) :
						$feat = strtolower( implode( ' ', self::features( $room ) ) );
						?>
						<tr>
							<td><a href="#room-<?php echo esc_attr( $room->slug ); ?>"><?php echo esc_html( $room->name ); ?></a></td>
							<td><?php echo (int) $room->max_guests; ?></td>
							<td><?php echo esc_html( $room->beds ); ?></td>
							<td><?php echo false !== strpos( $feat, 'balcony' ) ? 'Yes' : 'No'; ?></td>
							<td><?php echo false !== strpos( $feat, 'dining' ) ? 'Yes' : 'No'; ?></td>
							<td><strong><?php echo esc_html( erb_money( $room->price ) ); ?></strong></td>
						</tr>
					<?php endforeach; ?>
					</tbody>
				</table>
			</div>
			<?php
		endif;
		return ob_get_clean();
	}

	/** Check-availability bar: sends the dates to the booking form. */
	public static function sc_quick_book( $atts ) {
		$atts  = shortcode_atts( array( 'button' => 'Check Availability' ), $atts, 'erk_quick_book' );
		$today = wp_date( 'Y-m-d' );
		ob_start();
		?>
		<form class="quick-book" action="<?php echo esc_url( erb_page_url( 'booking' ) ); ?>" method="get">
			<label>Check-in<input type="date" name="checkin" required min="<?php echo esc_attr( $today ); ?>"></label>
			<label>Check-out<input type="date" name="checkout" required min="<?php echo esc_attr( $today ); ?>"></label>
			<label>Room
				<select name="room">
					<?php foreach ( ERB_DB::rooms() as $room ) : ?>
						<option value="<?php echo esc_attr( $room->slug ); ?>"><?php echo esc_html( $room->name ); ?></option>
					<?php endforeach; ?>
				</select>
			</label>
			<label>Guests<input type="number" name="guests" min="1" max="8" value="2"></label>
			<button class="erb-btn erb-btn--gold" type="submit"><?php echo esc_html( $atts['button'] ); ?></button>
		</form>
		<?php
		return ob_get_clean();
	}

	/** Booking request form, with the rates and policies panel. */
	public static function sc_booking_form( $atts ) {
		$atts  = shortcode_atts( array( 'sidebar' => '1', 'button' => 'Send Booking Request' ), $atts, 'erk_booking_form' );
		$rooms = ERB_DB::rooms();
		if ( ! $rooms ) {
			return '<p class="erb-empty">Online booking is not open yet. Please call or WhatsApp us.</p>';
		}
		// phpcs:disable WordPress.Security.NonceVerification.Recommended -- read-only values from the search bar and the redirect after saving.
		$sel    = isset( $_GET['room'] ) ? sanitize_title( wp_unslash( $_GET['room'] ) ) : $rooms[0]->slug;
		$cin    = isset( $_GET['checkin'] ) ? sanitize_text_field( wp_unslash( $_GET['checkin'] ) ) : '';
		$cout   = isset( $_GET['checkout'] ) ? sanitize_text_field( wp_unslash( $_GET['checkout'] ) ) : '';
		$guests = isset( $_GET['guests'] ) ? max( 1, absint( $_GET['guests'] ) ) : 2;
		$err    = isset( $_GET['err'] ) ? sanitize_key( wp_unslash( $_GET['err'] ) ) : '';
		$booked = isset( $_GET['booked'] );
		$ref    = isset( $_GET['ref'] ) ? sanitize_text_field( wp_unslash( $_GET['ref'] ) ) : '';
		// phpcs:enable
		$errors = array(
			'missing'  => 'Please fill in your name, phone number, room and both dates.',
			'dates'    => 'Please check your dates. Check-in cannot be in the past, and check-out must be after check-in.',
			'security' => 'Your session expired. Please try again.',
			'save'     => 'Something went wrong while saving. Please try again or call us.',
			'busy'     => 'You have sent several requests in a short time. Please wait a few minutes, or call us.',
		);
		$prices = array();
		foreach ( $rooms as $room ) {
			$prices[ $room->slug ] = (int) $room->price;
		}
		$today    = wp_date( 'Y-m-d' );
		$sidebar  = '1' === (string) $atts['sidebar'];
		ob_start();
		?>
		<div class="booking<?php echo $sidebar ? '' : ' booking--single'; ?>">
			<div class="booking__form" id="booking-form">
				<?php if ( $booked ) : ?>
					<div class="alert alert--ok">
						<h3>Thank you! Your booking request was received.</h3>
						<p>Your reference is <strong><?php echo esc_html( $ref ); ?></strong>. We will call or WhatsApp you shortly to confirm your room and payment. Need it faster? Call <a href="<?php echo esc_url( erb_tel_url() ); ?>"><?php echo esc_html( erb_setting( 'phone' ) ); ?></a>.</p>
					</div>
				<?php elseif ( $err && isset( $errors[ $err ] ) ) : ?>
					<div class="alert alert--error"><?php echo esc_html( $errors[ $err ] ); ?></div>
				<?php endif; ?>
				<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" class="erb-form js-booking" data-prices="<?php echo esc_attr( wp_json_encode( $prices ) ); ?>">
					<input type="hidden" name="action" value="erb_booking">
					<?php wp_nonce_field( 'erb_booking', 'erb_nonce' ); ?>
					<input type="text" name="website" class="erb-hp" tabindex="-1" autocomplete="off" aria-hidden="true">
					<h2>Your stay</h2>
					<div class="form-row">
						<label>Check-in date *<input type="date" name="checkin" required min="<?php echo esc_attr( $today ); ?>" value="<?php echo esc_attr( $cin ); ?>"></label>
						<label>Check-out date *<input type="date" name="checkout" required min="<?php echo esc_attr( $today ); ?>" value="<?php echo esc_attr( $cout ); ?>"></label>
					</div>
					<div class="form-row">
						<label>Room type *
							<select name="room" required>
								<?php foreach ( $rooms as $room ) : ?>
									<option value="<?php echo esc_attr( $room->slug ); ?>" <?php selected( $sel, $room->slug ); ?>><?php echo esc_html( $room->name . ' – ' . erb_money( $room->price ) ); ?></option>
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
					<p class="erb-muted erb-small"><?php echo esc_html( erb_setting( 'payment_note' ) ); ?></p>
					<button class="erb-btn erb-btn--gold erb-btn--block" type="submit"><?php echo esc_html( $atts['button'] ); ?></button>
				</form>
			</div>
			<?php if ( $sidebar ) : ?>
				<aside class="booking__side">
					<div class="side-card">
						<h3>Prefer to talk?</h3>
						<p><a class="erb-btn erb-btn--green erb-btn--block" href="<?php echo esc_url( erb_tel_url() ); ?>"><?php echo self::icon( 'phone' ); // phpcs:ignore WordPress.Security.EscapeOutput ?> <?php echo esc_html( erb_setting( 'phone' ) ); ?></a></p>
						<p><a class="erb-btn erb-btn--outline erb-btn--block" href="<?php echo esc_url( erb_whatsapp_url( 'Hello, I would like to book a room at El Roi Khadoshi Motel.' ) ); ?>" target="_blank" rel="noopener"><?php echo self::icon( 'whatsapp' ); // phpcs:ignore WordPress.Security.EscapeOutput ?> Book on WhatsApp</a></p>
					</div>
					<div class="side-card">
						<h3>Our rates</h3>
						<ul class="rate-list">
							<?php foreach ( $rooms as $room ) : ?>
								<li><span><?php echo esc_html( $room->name ); ?></span><strong><?php echo esc_html( erb_money( $room->price ) ); ?></strong></li>
							<?php endforeach; ?>
						</ul>
						<p class="erb-muted erb-small">Per room, per night.</p>
					</div>
					<div class="side-card">
						<h3>Policies</h3>
						<ul class="rate-list">
							<li><span>Check-in</span><strong>from <?php echo esc_html( erb_setting( 'checkin' ) ); ?></strong></li>
							<li><span>Check-out</span><strong>by <?php echo esc_html( erb_setting( 'checkout' ) ); ?></strong></li>
							<li><span>Smoking</span><strong>Not allowed</strong></li>
							<li><span>Cancellation</span><strong><?php echo esc_html( erb_setting( 'cancellation' ) ); ?></strong></li>
						</ul>
					</div>
				</aside>
			<?php endif; ?>
		</div>
		<?php
		return ob_get_clean();
	}

	/** Contact form. */
	public static function sc_contact_form( $atts ) {
		$atts = shortcode_atts( array( 'button' => 'Send Message' ), $atts, 'erk_contact_form' );
		ob_start();
		// phpcs:disable WordPress.Security.NonceVerification.Recommended
		if ( isset( $_GET['sent'] ) && '1' === $_GET['sent'] ) {
			echo '<div class="alert alert--ok">Thank you! Your message was sent. We will get back to you soon.</div>';
		} elseif ( isset( $_GET['cerr'] ) ) {
			echo '<div class="alert alert--error">' . ( 'busy' === $_GET['cerr'] ? 'You have sent several messages in a short time. Please wait a few minutes, or call us.' : 'Please fill in your name, phone or email, and your message.' ) . '</div>';
		}
		// phpcs:enable
		?>
		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" class="erb-form" id="contact-form">
			<input type="hidden" name="action" value="erb_contact">
			<?php wp_nonce_field( 'erb_contact', 'erb_nonce' ); ?>
			<input type="text" name="website" class="erb-hp" tabindex="-1" autocomplete="off" aria-hidden="true">
			<div class="form-row">
				<label>Your name *<input type="text" name="full_name" required></label>
				<label>Phone or email *<input type="text" name="contact" required></label>
			</div>
			<label>Subject<input type="text" name="subject" placeholder="Booking question, group stay, events..."></label>
			<label>Message *<textarea name="message" rows="6" required></textarea></label>
			<button class="erb-btn erb-btn--gold" type="submit"><?php echo esc_html( $atts['button'] ); ?></button>
		</form>
		<?php
		return ob_get_clean();
	}

	/* ---------------------------------------------------------------------
	 * Form handlers
	 * ------------------------------------------------------------------ */

	/** Allow a few submissions per visitor in 10 minutes, to stop floods. */
	private static function too_many( $kind ) {
		$ip  = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';
		$key = 'erb_rl_' . $kind . '_' . md5( $ip );
		$n   = (int) get_transient( $key );
		if ( $n >= 5 ) {
			return true;
		}
		set_transient( $key, $n + 1, 10 * MINUTE_IN_SECONDS );
		return false;
	}

	public static function handle_booking() {
		$back = wp_get_referer() ? wp_get_referer() : erb_page_url( 'booking' );
		$back = remove_query_arg( array( 'booked', 'ref', 'err' ), $back );

		if ( ! isset( $_POST['erb_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['erb_nonce'] ) ), 'erb_booking' ) ) {
			wp_safe_redirect( add_query_arg( 'err', 'security', $back ) . '#booking-form' );
			exit;
		}
		if ( ! empty( $_POST['website'] ) ) { // Spam trap: real people never fill this hidden field.
			wp_safe_redirect( $back );
			exit;
		}
		if ( self::too_many( 'booking' ) ) {
			wp_safe_redirect( add_query_arg( 'err', 'busy', $back ) . '#booking-form' );
			exit;
		}

		$name     = sanitize_text_field( wp_unslash( $_POST['full_name'] ?? '' ) );
		$phone    = sanitize_text_field( wp_unslash( $_POST['phone'] ?? '' ) );
		$email    = sanitize_email( wp_unslash( $_POST['email'] ?? '' ) );
		$room     = ERB_DB::room_by_slug( sanitize_title( wp_unslash( $_POST['room'] ?? '' ) ) );
		$checkin  = sanitize_text_field( wp_unslash( $_POST['checkin'] ?? '' ) );
		$checkout = sanitize_text_field( wp_unslash( $_POST['checkout'] ?? '' ) );
		$guests   = max( 1, min( 20, absint( $_POST['guests'] ?? 1 ) ) );
		$qty      = max( 1, min( 8, absint( $_POST['rooms_qty'] ?? 1 ) ) );
		$notes    = sanitize_textarea_field( wp_unslash( $_POST['notes'] ?? '' ) );

		$tz    = wp_timezone();
		$in    = DateTime::createFromFormat( '!Y-m-d', $checkin, $tz );
		$out   = DateTime::createFromFormat( '!Y-m-d', $checkout, $tz );
		$today = new DateTime( 'today', $tz );

		if ( ! $name || ! $phone || ! $room || ! $in || ! $out ) {
			wp_safe_redirect( add_query_arg( 'err', 'missing', $back ) . '#booking-form' );
			exit;
		}
		if ( $in < $today || $out <= $in ) {
			wp_safe_redirect( add_query_arg( 'err', 'dates', $back ) . '#booking-form' );
			exit;
		}

		$nights = (int) $in->diff( $out )->days;
		$total  = $nights * (int) $room->price * $qty;
		$ref    = 'ERK-' . strtoupper( wp_generate_password( 6, false, false ) );

		$id = ERB_DB::add_booking(
			array(
				'reference'  => $ref,
				'room_id'    => (int) $room->id,
				'room_name'  => $room->name,
				'guest_name' => $name,
				'phone'      => $phone,
				'email'      => $email,
				'check_in'   => $in->format( 'Y-m-d' ),
				'check_out'  => $out->format( 'Y-m-d' ),
				'nights'     => $nights,
				'rooms_qty'  => $qty,
				'guests'     => $guests,
				'total'      => $total,
				'notes'      => $notes,
			)
		);
		if ( ! $id ) {
			wp_safe_redirect( add_query_arg( 'err', 'save', $back ) . '#booking-form' );
			exit;
		}

		// Email the motel (works once email is set up on the live server).
		$lines = array(
			'Reference'       => $ref,
			'Guest'           => $name,
			'Phone'           => $phone,
			'Email'           => $email,
			'Room'            => $room->name,
			'Number of rooms' => $qty,
			'Check-in'        => $in->format( 'D j M Y' ),
			'Check-out'       => $out->format( 'D j M Y' ),
			'Nights'          => $nights,
			'Guests'          => $guests,
			'Estimated total' => erb_money( $total ),
			'Special requests' => $notes,
		);
		$body = "New booking request ($ref)\n\n";
		foreach ( $lines as $k => $v ) {
			$body .= $k . ': ' . $v . "\n";
		}
		$body .= "\nManage it in the dashboard: " . admin_url( 'admin.php?page=erb-bookings&view=' . $id );
		wp_mail( erb_notify_email(), 'New booking request ' . $ref, $body );

		if ( is_email( $email ) ) {
			wp_mail(
				$email,
				'We received your booking request ' . $ref,
				"Hello $name,\n\nThank you for choosing El Roi Khadoshi Motel. We received your request for the {$room->name}, " . $in->format( 'j M' ) . ' to ' . $out->format( 'j M Y' ) . " ($nights night" . ( 1 === $nights ? '' : 's' ) . ").\n\n"
				. 'Estimated total: ' . erb_money( $total ) . "\nReference: $ref\n\nWe will call or WhatsApp you shortly to confirm. " . erb_setting( 'payment_note' ) . "\n\nEl Roi Khadoshi Motel\n" . erb_setting( 'phone' ) . "\n" . erb_setting( 'address' )
			);
		}

		wp_safe_redirect(
			add_query_arg(
				array(
					'booked' => 1,
					'ref'    => $ref,
				),
				$back
			) . '#booking-form'
		);
		exit;
	}

	public static function handle_contact() {
		$back = wp_get_referer() ? wp_get_referer() : erb_page_url( 'contact' );
		$back = remove_query_arg( array( 'sent', 'cerr' ), $back );

		if ( ! isset( $_POST['erb_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['erb_nonce'] ) ), 'erb_contact' ) ) {
			wp_safe_redirect( add_query_arg( 'cerr', 'security', $back ) . '#contact-form' );
			exit;
		}
		if ( ! empty( $_POST['website'] ) ) {
			wp_safe_redirect( $back );
			exit;
		}
		if ( self::too_many( 'contact' ) ) {
			wp_safe_redirect( add_query_arg( 'cerr', 'busy', $back ) . '#contact-form' );
			exit;
		}

		$name    = sanitize_text_field( wp_unslash( $_POST['full_name'] ?? '' ) );
		$contact = sanitize_text_field( wp_unslash( $_POST['contact'] ?? '' ) );
		$subject = sanitize_text_field( wp_unslash( $_POST['subject'] ?? '' ) );
		$message = sanitize_textarea_field( wp_unslash( $_POST['message'] ?? '' ) );

		if ( ! $name || ! $contact || ! $message ) {
			wp_safe_redirect( add_query_arg( 'cerr', 'missing', $back ) . '#contact-form' );
			exit;
		}

		$id = ERB_DB::add_message(
			array(
				'name'    => $name,
				'contact' => $contact,
				'subject' => $subject ? $subject : 'General enquiry',
				'message' => $message,
			)
		);
		wp_mail( erb_notify_email(), 'Website message: ' . ( $subject ? $subject : 'General enquiry' ), "From: $name ($contact)\n\n$message" );

		wp_safe_redirect( add_query_arg( 'sent', $id ? 1 : 0, $back ) . '#contact-form' );
		exit;
	}
}
