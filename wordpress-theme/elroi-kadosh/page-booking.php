<?php if ( elroi_maybe_render_elementor() ) { return; } ?>
<?php get_header(); ?>
<?php elroi_page_hero( elroi_t( 'booking_hero_title' ), elroi_t( 'booking_hero_sub' ), elroi_banner( 'booking' ) ); ?>

<?php
$rooms   = elroi_rooms();
$sel     = isset( $_GET['room'] ) ? sanitize_key( wp_unslash( $_GET['room'] ) ) : key( $rooms );
$cin     = isset( $_GET['checkin'] ) ? sanitize_text_field( wp_unslash( $_GET['checkin'] ) ) : '';
$cout    = isset( $_GET['checkout'] ) ? sanitize_text_field( wp_unslash( $_GET['checkout'] ) ) : '';
$guests  = isset( $_GET['guests'] ) ? max( 1, intval( $_GET['guests'] ) ) : 2;
$errors  = array(
	'missing'  => 'Please fill in your name, phone number, room and both dates.',
	'dates'    => 'Please check your dates. Check-in cannot be in the past, and check-out must be after check-in.',
	'security' => 'Your session expired. Please try again.',
	'save'     => 'Something went wrong while saving. Please try again or call us.',
);
$err = isset( $_GET['err'] ) ? sanitize_key( wp_unslash( $_GET['err'] ) ) : '';
$today = gmdate( 'Y-m-d' );
?>

<section class="section">
	<div class="container booking">
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

				<div class="summary" aria-live="polite">
					<span>Estimated total:</span>
					<strong class="js-total">Choose your dates</strong>
				</div>
				<p class="muted small"><?php elroi_e( 'booking_note' ); ?></p>
				<button class="btn btn--gold btn--block" type="submit">Send Booking Request</button>
			</form>
		</div>

		<aside class="booking__side">
			<div class="side-card">
				<h3>Prefer to talk?</h3>
				<p><a class="btn btn--block" href="<?php echo esc_url( elroi_tel_url() ); ?>"><?php echo elroi_icon( 'phone' ); ?> <?php echo esc_html( elroi_opt( 'phone' ) ); ?></a></p>
				<p><a class="btn btn--outline btn--block" href="<?php echo esc_url( elroi_whatsapp_url( 'Hello, I would like to book a room at El Roi Khadoshi Motel.' ) ); ?>" target="_blank" rel="noopener"><?php echo elroi_icon( 'whatsapp' ); ?> Book on WhatsApp</a></p>
			</div>
			<div class="side-card">
				<h3>Our rates</h3>
				<ul class="rate-list">
					<?php foreach ( $rooms as $r ) : ?>
						<li><span><?php echo esc_html( $r['name'] ); ?></span><strong><?php echo esc_html( elroi_money( $r['price'] ) ); ?></strong></li>
					<?php endforeach; ?>
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
	</div>
</section>

<?php get_footer(); ?>
