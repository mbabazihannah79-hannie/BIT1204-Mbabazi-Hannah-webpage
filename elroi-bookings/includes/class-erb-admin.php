<?php
/**
 * Dashboard screens under "Bookings": booking requests, rooms, messages and settings.
 * Every change checks the user's permission and a nonce.
 *
 * @package elroi-bookings
 */

defined( 'ABSPATH' ) || exit;

class ERB_Admin {

	const CAP = 'edit_pages';

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'assets' ) );
		add_action( 'admin_post_erb_save_booking', array( $this, 'save_booking' ) );
		add_action( 'admin_post_erb_delete_booking', array( $this, 'delete_booking' ) );
		add_action( 'admin_post_erb_save_room', array( $this, 'save_room' ) );
		add_action( 'admin_post_erb_delete_room', array( $this, 'delete_room' ) );
		add_action( 'admin_post_erb_delete_message', array( $this, 'delete_message' ) );
		add_action( 'admin_post_erb_save_settings', array( $this, 'save_settings' ) );
	}

	public function menu() {
		$pending = ERB_DB::booking_counts();
		$pending = isset( $pending['pending'] ) ? $pending['pending'] : 0;
		$badge   = $pending ? ' <span class="awaiting-mod">' . (int) $pending . '</span>' : '';
		$unread  = ERB_DB::unread_messages();

		add_menu_page( 'Bookings', 'Bookings' . $badge, self::CAP, 'erb-bookings', array( $this, 'bookings_page' ), 'dashicons-calendar-alt', 25 );
		add_submenu_page( 'erb-bookings', 'Bookings', 'All bookings', self::CAP, 'erb-bookings', array( $this, 'bookings_page' ) );
		add_submenu_page( 'erb-bookings', 'Rooms', 'Rooms & prices', self::CAP, 'erb-rooms', array( $this, 'rooms_page' ) );
		add_submenu_page( 'erb-bookings', 'Messages', 'Messages' . ( $unread ? ' <span class="awaiting-mod">' . (int) $unread . '</span>' : '' ), self::CAP, 'erb-messages', array( $this, 'messages_page' ) );
		add_submenu_page( 'erb-bookings', 'Settings', 'Settings', 'manage_options', 'erb-settings', array( $this, 'settings_page' ) );
	}

	public function assets( $hook ) {
		if ( false === strpos( $hook, 'erb-' ) ) {
			return;
		}
		wp_enqueue_style( 'erb-admin', ERB_URL . 'assets/admin.css', array(), ERB_VERSION );
		if ( false !== strpos( $hook, 'erb-rooms' ) ) {
			wp_enqueue_media();
			wp_enqueue_script( 'erb-admin', ERB_URL . 'assets/admin.js', array( 'jquery' ), ERB_VERSION, true );
		}
	}

	private function check( $action ) {
		if ( ! current_user_can( 'erb-settings' === $action ? 'manage_options' : self::CAP ) ) {
			wp_die( esc_html__( 'You are not allowed to do this.', 'elroi-bookings' ), 403 );
		}
		check_admin_referer( $action );
	}

	private function notice() {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$msg = isset( $_GET['msg'] ) ? sanitize_key( $_GET['msg'] ) : '';
		$all = array(
			'saved'   => 'Saved.',
			'deleted' => 'Deleted.',
		);
		if ( isset( $all[ $msg ] ) ) {
			echo '<div class="notice notice-success is-dismissible"><p>' . esc_html( $all[ $msg ] ) . '</p></div>';
		}
	}

	/* ---------------------------------------------------------------------
	 * Bookings
	 * ------------------------------------------------------------------ */

	public function bookings_page() {
		// phpcs:disable WordPress.Security.NonceVerification.Recommended
		$view   = isset( $_GET['view'] ) ? absint( $_GET['view'] ) : 0;
		$status = isset( $_GET['status'] ) ? sanitize_key( $_GET['status'] ) : '';
		// phpcs:enable
		echo '<div class="wrap erb-wrap">';
		$this->notice();

		if ( $view ) {
			$b = ERB_DB::booking( $view );
			if ( ! $b ) {
				echo '<h1>Booking not found</h1></div>';
				return;
			}
			$wa = 'https://wa.me/' . erb_wa_digits( $b->phone );
			?>
			<h1>Booking <?php echo esc_html( $b->reference ); ?> <span class="erb-status erb-status--<?php echo esc_attr( $b->status ); ?>"><?php echo esc_html( erb_statuses()[ $b->status ] ?? $b->status ); ?></span></h1>
			<p><a href="<?php echo esc_url( admin_url( 'admin.php?page=erb-bookings' ) ); ?>">&larr; All bookings</a></p>
			<div class="erb-columns">
				<table class="widefat striped erb-details"><tbody>
					<?php
					$rows = array(
						'Guest name'       => $b->guest_name,
						'Phone'            => $b->phone,
						'Email'            => $b->email,
						'Room type'        => $b->room_label,
						'Number of rooms'  => $b->rooms_qty,
						'Check-in'         => wp_date( 'D j M Y', strtotime( $b->check_in ) ),
						'Check-out'        => wp_date( 'D j M Y', strtotime( $b->check_out ) ),
						'Nights'           => $b->nights,
						'Guests'           => $b->guests,
						'Estimated total'  => erb_money( $b->total ),
						'Special requests' => $b->notes,
						'Received'         => wp_date( 'j M Y, H:i', strtotime( $b->created_at ) ),
					);
					foreach ( $rows as $label => $value ) {
						echo '<tr><th>' . esc_html( $label ) . '</th><td>' . nl2br( esc_html( (string) $value ) ) . '</td></tr>';
					}
					?>
				</tbody></table>
				<div class="erb-card">
					<h2>Update booking</h2>
					<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
						<input type="hidden" name="action" value="erb_save_booking">
						<input type="hidden" name="id" value="<?php echo (int) $b->id; ?>">
						<?php wp_nonce_field( 'erb_save_booking' ); ?>
						<p><label for="erb-status"><strong>Status</strong></label><br>
							<select name="status" id="erb-status">
								<?php foreach ( erb_statuses() as $k => $label ) : ?>
									<option value="<?php echo esc_attr( $k ); ?>" <?php selected( $b->status, $k ); ?>><?php echo esc_html( $label ); ?></option>
								<?php endforeach; ?>
							</select></p>
						<p><label for="erb-note"><strong>Staff note</strong> (only staff see this)</label><br>
							<textarea name="staff_note" id="erb-note" rows="4" class="large-text"><?php echo esc_textarea( $b->staff_note ); ?></textarea></p>
						<?php submit_button( 'Save', 'primary', 'submit', false ); ?>
					</form>
					<p class="erb-actions">
						<a class="button" href="tel:<?php echo esc_attr( preg_replace( '/[^\d+]/', '', $b->phone ) ); ?>">Call guest</a>
						<?php if ( erb_wa_digits( $b->phone ) ) : ?>
							<a class="button" target="_blank" rel="noopener" href="<?php echo esc_url( $wa . '?text=' . rawurlencode( 'Hello ' . $b->guest_name . ', this is El Roi Khadoshi Motel about your booking ' . $b->reference . '.' ) ); ?>">WhatsApp guest</a>
						<?php endif; ?>
						<?php if ( $b->email ) : ?>
							<a class="button" href="mailto:<?php echo esc_attr( $b->email ); ?>?subject=<?php echo rawurlencode( 'Your booking ' . $b->reference ); ?>">Email guest</a>
						<?php endif; ?>
					</p>
					<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" onsubmit="return confirm('Delete this booking for good?');">
						<input type="hidden" name="action" value="erb_delete_booking">
						<input type="hidden" name="id" value="<?php echo (int) $b->id; ?>">
						<?php wp_nonce_field( 'erb_delete_booking' ); ?>
						<button class="button-link erb-delete" type="submit">Delete booking</button>
					</form>
				</div>
			</div>
			<?php
			echo '</div>';
			return;
		}

		$counts = ERB_DB::booking_counts();
		$list   = ERB_DB::bookings( $status );
		?>
		<h1 class="wp-heading-inline">Bookings</h1>
		<p>Booking requests from the website. Open one to confirm it, add a note or change its status.</p>
		<ul class="subsubsub">
			<li><a href="<?php echo esc_url( admin_url( 'admin.php?page=erb-bookings' ) ); ?>" class="<?php echo $status ? '' : 'current'; ?>">All <span class="count">(<?php echo (int) array_sum( $counts ); ?>)</span></a></li>
			<?php foreach ( erb_statuses() as $k => $label ) : ?>
				<li>| <a href="<?php echo esc_url( admin_url( 'admin.php?page=erb-bookings&status=' . $k ) ); ?>" class="<?php echo $status === $k ? 'current' : ''; ?>"><?php echo esc_html( $label ); ?> <span class="count">(<?php echo (int) ( $counts[ $k ] ?? 0 ); ?>)</span></a></li>
			<?php endforeach; ?>
		</ul>
		<table class="widefat striped erb-table">
			<thead><tr><th>Booking</th><th>Guest</th><th>Phone</th><th>Room</th><th>Stay</th><th>Estimated total</th><th>Status</th><th>Received</th></tr></thead>
			<tbody>
			<?php if ( ! $list ) : ?>
				<tr><td colspan="8">No bookings yet.</td></tr>
			<?php endif; ?>
			<?php foreach ( $list as $b ) : ?>
				<tr>
					<td><a href="<?php echo esc_url( admin_url( 'admin.php?page=erb-bookings&view=' . (int) $b->id ) ); ?>"><strong><?php echo esc_html( $b->reference ); ?></strong></a></td>
					<td><?php echo esc_html( $b->guest_name ); ?></td>
					<td><?php echo esc_html( $b->phone ); ?></td>
					<td><?php echo esc_html( $b->room_label ); ?><?php echo $b->rooms_qty > 1 ? ' × ' . (int) $b->rooms_qty : ''; ?></td>
					<td><?php echo esc_html( wp_date( 'j M', strtotime( $b->check_in ) ) . ' → ' . wp_date( 'j M Y', strtotime( $b->check_out ) ) . ' (' . (int) $b->nights . ' night' . ( 1 === (int) $b->nights ? '' : 's' ) . ')' ); ?></td>
					<td><?php echo esc_html( erb_money( $b->total ) ); ?></td>
					<td><span class="erb-status erb-status--<?php echo esc_attr( $b->status ); ?>"><?php echo esc_html( erb_statuses()[ $b->status ] ?? $b->status ); ?></span></td>
					<td><?php echo esc_html( wp_date( 'j M Y', strtotime( $b->created_at ) ) ); ?></td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
		</div>
		<?php
	}

	public function save_booking() {
		$this->check( 'erb_save_booking' );
		$id = absint( $_POST['id'] ?? 0 );
		ERB_DB::update_booking( $id, sanitize_key( wp_unslash( $_POST['status'] ?? '' ) ), wp_unslash( $_POST['staff_note'] ?? '' ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput -- sanitised in update_booking().
		wp_safe_redirect( admin_url( 'admin.php?page=erb-bookings&view=' . $id . '&msg=saved' ) );
		exit;
	}

	public function delete_booking() {
		$this->check( 'erb_delete_booking' );
		ERB_DB::delete_booking( absint( $_POST['id'] ?? 0 ) );
		wp_safe_redirect( admin_url( 'admin.php?page=erb-bookings&msg=deleted' ) );
		exit;
	}

	/* ---------------------------------------------------------------------
	 * Rooms
	 * ------------------------------------------------------------------ */

	public function rooms_page() {
		// phpcs:disable WordPress.Security.NonceVerification.Recommended
		$edit = isset( $_GET['edit'] ) ? sanitize_key( $_GET['edit'] ) : '';
		// phpcs:enable
		echo '<div class="wrap erb-wrap">';
		$this->notice();

		if ( '' !== $edit ) {
			$room = 'new' === $edit ? null : ERB_DB::room( absint( $edit ) );
			$r    = $room ? $room : (object) array(
				'id'          => 0,
				'slug'        => '',
				'name'        => '',
				'price'       => '',
				'max_guests'  => 2,
				'beds'        => '',
				'description' => '',
				'features'    => '',
				'photo_ids'   => '',
				'photo_keys'  => '',
				'sort_order'  => count( ERB_DB::rooms( false ) ) + 1,
				'is_active'   => 1,
			);
			$ids  = array_filter( array_map( 'absint', explode( ',', (string) $r->photo_ids ) ) );
			?>
			<h1><?php echo $room ? 'Edit room' : 'Add a room'; ?></h1>
			<p><a href="<?php echo esc_url( admin_url( 'admin.php?page=erb-rooms' ) ); ?>">&larr; All rooms</a></p>
			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" class="erb-room-form">
				<input type="hidden" name="action" value="erb_save_room">
				<input type="hidden" name="id" value="<?php echo (int) $r->id; ?>">
				<input type="hidden" name="photo_keys" value="<?php echo esc_attr( $r->photo_keys ); ?>">
				<?php wp_nonce_field( 'erb_save_room' ); ?>
				<table class="form-table"><tbody>
					<tr><th><label for="erb-name">Room name</label></th><td><input type="text" id="erb-name" name="name" class="regular-text" required value="<?php echo esc_attr( $r->name ); ?>"></td></tr>
					<tr><th><label for="erb-price">Price per night (UGX)</label></th><td><input type="number" id="erb-price" name="price" min="0" step="1000" required value="<?php echo esc_attr( $r->price ); ?>"> <span class="description">Numbers only, e.g. 60000</span></td></tr>
					<tr><th><label for="erb-guests">Maximum guests</label></th><td><input type="number" id="erb-guests" name="max_guests" min="1" max="20" value="<?php echo esc_attr( $r->max_guests ); ?>"></td></tr>
					<tr><th><label for="erb-beds">Beds</label></th><td><input type="text" id="erb-beds" name="beds" class="regular-text" placeholder="1 double bed" value="<?php echo esc_attr( $r->beds ); ?>"></td></tr>
					<tr><th><label for="erb-desc">Short description</label></th><td><textarea id="erb-desc" name="description" rows="3" class="large-text"><?php echo esc_textarea( $r->description ); ?></textarea></td></tr>
					<tr><th><label for="erb-feat">Features</label></th><td><textarea id="erb-feat" name="features" rows="7" class="large-text"><?php echo esc_textarea( $r->features ); ?></textarea><p class="description">One per line. "Balcony" and "Dining area" also tick the boxes in the comparison table.</p></td></tr>
					<tr><th>Photos</th><td>
						<div class="erb-photos">
							<input type="hidden" name="photo_ids" value="<?php echo esc_attr( implode( ',', $ids ) ); ?>">
							<div class="erb-photos__thumbs">
								<?php
								foreach ( $ids as $pid ) {
									echo wp_get_attachment_image( $pid, array( 110, 80 ) );
								}
								if ( ! $ids ) {
									foreach ( ERB_Public::photos( $r, 'thumbnail' ) as $url ) {
										echo '<img src="' . esc_url( $url ) . '" alt="" width="110" height="80">';
									}
								}
								?>
							</div>
							<button type="button" class="button erb-photos__pick">Choose photos</button>
							<button type="button" class="button-link erb-photos__clear">Use sample photos</button>
							<p class="description">The first photo is the main one on the room card. Up to three show on the Rooms page; tap a photo there to see it full size.</p>
						</div>
					</td></tr>
					<tr><th><label for="erb-slug">Web address name</label></th><td><input type="text" id="erb-slug" name="slug" class="regular-text" value="<?php echo esc_attr( $r->slug ); ?>"><p class="description">Used in links like /rooms/#room-<em>deluxe</em>. Leave empty to make one from the name.</p></td></tr>
					<tr><th><label for="erb-order">Order</label></th><td><input type="number" id="erb-order" name="sort_order" value="<?php echo esc_attr( $r->sort_order ); ?>" class="small-text"> <span class="description">Lower numbers show first.</span></td></tr>
					<tr><th>Show on website</th><td><label><input type="checkbox" name="is_active" value="1" <?php checked( (int) $r->is_active, 1 ); ?>> Yes, guests can see and book this room</label></td></tr>
				</tbody></table>
				<?php submit_button( $room ? 'Save room' : 'Add room' ); ?>
			</form>
			<?php if ( $room ) : ?>
				<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" onsubmit="return confirm('Delete this room? Past bookings keep the room name.');">
					<input type="hidden" name="action" value="erb_delete_room">
					<input type="hidden" name="id" value="<?php echo (int) $room->id; ?>">
					<?php wp_nonce_field( 'erb_delete_room' ); ?>
					<button class="button-link erb-delete" type="submit">Delete room</button>
				</form>
			<?php endif; ?>
			<?php
			echo '</div>';
			return;
		}
		?>
		<h1 class="wp-heading-inline">Rooms &amp; prices</h1>
		<a href="<?php echo esc_url( admin_url( 'admin.php?page=erb-rooms&edit=new' ) ); ?>" class="page-title-action">Add room</a>
		<p>These rooms appear on the Home and Rooms pages, in the booking form and in the rates list. Prices are per room, per night.</p>
		<table class="widefat striped erb-table">
			<thead><tr><th>Photo</th><th>Room</th><th>Price / night</th><th>Guests</th><th>Beds</th><th>On website</th></tr></thead>
			<tbody>
			<?php foreach ( ERB_DB::rooms( false ) as $room ) : ?>
				<?php $thumb = ERB_Public::photos( $room, 'thumbnail' ); ?>
				<tr>
					<td><img src="<?php echo esc_url( $thumb[0] ); ?>" alt="" class="erb-thumb"></td>
					<td><a href="<?php echo esc_url( admin_url( 'admin.php?page=erb-rooms&edit=' . (int) $room->id ) ); ?>"><strong><?php echo esc_html( $room->name ); ?></strong></a></td>
					<td><?php echo esc_html( erb_money( $room->price ) ); ?></td>
					<td><?php echo (int) $room->max_guests; ?></td>
					<td><?php echo esc_html( $room->beds ); ?></td>
					<td><?php echo $room->is_active ? 'Yes' : 'Hidden'; ?></td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
		</div>
		<?php
	}

	public function save_room() {
		$this->check( 'erb_save_room' );
		$id   = absint( $_POST['id'] ?? 0 );
		$data = array(
			'slug'        => wp_unslash( $_POST['slug'] ?? '' ),
			'name'        => wp_unslash( $_POST['name'] ?? '' ),
			'price'       => wp_unslash( $_POST['price'] ?? 0 ),
			'max_guests'  => wp_unslash( $_POST['max_guests'] ?? 2 ),
			'beds'        => wp_unslash( $_POST['beds'] ?? '' ),
			'description' => wp_unslash( $_POST['description'] ?? '' ),
			'features'    => wp_unslash( $_POST['features'] ?? '' ),
			'photo_ids'   => wp_unslash( $_POST['photo_ids'] ?? '' ),
			'photo_keys'  => wp_unslash( $_POST['photo_keys'] ?? '' ),
			'sort_order'  => wp_unslash( $_POST['sort_order'] ?? 0 ),
			'is_active'   => ! empty( $_POST['is_active'] ),
		); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput -- every field is sanitised in ERB_DB::save_room().
		if ( '' === trim( (string) $data['name'] ) ) {
			wp_safe_redirect( admin_url( 'admin.php?page=erb-rooms&edit=' . ( $id ? $id : 'new' ) ) );
			exit;
		}
		$id = ERB_DB::save_room( $data, $id );
		wp_safe_redirect( admin_url( 'admin.php?page=erb-rooms&edit=' . $id . '&msg=saved' ) );
		exit;
	}

	public function delete_room() {
		$this->check( 'erb_delete_room' );
		ERB_DB::delete_room( absint( $_POST['id'] ?? 0 ) );
		wp_safe_redirect( admin_url( 'admin.php?page=erb-rooms&msg=deleted' ) );
		exit;
	}

	/* ---------------------------------------------------------------------
	 * Messages
	 * ------------------------------------------------------------------ */

	public function messages_page() {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$view = isset( $_GET['view'] ) ? absint( $_GET['view'] ) : 0;
		echo '<div class="wrap erb-wrap">';
		$this->notice();

		if ( $view ) {
			$m = ERB_DB::message( $view );
			if ( ! $m ) {
				echo '<h1>Message not found</h1></div>';
				return;
			}
			ERB_DB::mark_read( $m->id );
			$reply = is_email( $m->contact ) ? 'mailto:' . $m->contact . '?subject=' . rawurlencode( 'Re: ' . $m->subject ) : 'tel:' . preg_replace( '/[^\d+]/', '', $m->contact );
			?>
			<h1><?php echo esc_html( $m->subject ); ?></h1>
			<p><a href="<?php echo esc_url( admin_url( 'admin.php?page=erb-messages' ) ); ?>">&larr; All messages</a></p>
			<div class="erb-card erb-message">
				<p><strong><?php echo esc_html( $m->name ); ?></strong> · <?php echo esc_html( $m->contact ); ?> · <?php echo esc_html( wp_date( 'j M Y, H:i', strtotime( $m->created_at ) ) ); ?></p>
				<p><?php echo nl2br( esc_html( $m->message ) ); ?></p>
				<p><a class="button button-primary" href="<?php echo esc_url( $reply ); ?>"><?php echo is_email( $m->contact ) ? 'Reply by email' : 'Call back'; ?></a></p>
			</div>
			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" onsubmit="return confirm('Delete this message?');">
				<input type="hidden" name="action" value="erb_delete_message">
				<input type="hidden" name="id" value="<?php echo (int) $m->id; ?>">
				<?php wp_nonce_field( 'erb_delete_message' ); ?>
				<button class="button-link erb-delete" type="submit">Delete message</button>
			</form>
			<?php
			echo '</div>';
			return;
		}
		?>
		<h1>Messages</h1>
		<p>Messages sent from the contact form on the Contact Us page. New messages are in bold.</p>
		<table class="widefat striped erb-table">
			<thead><tr><th>From</th><th>Phone / email</th><th>Subject</th><th>Received</th></tr></thead>
			<tbody>
			<?php $list = ERB_DB::messages(); ?>
			<?php if ( ! $list ) : ?>
				<tr><td colspan="4">No messages yet.</td></tr>
			<?php endif; ?>
			<?php foreach ( $list as $m ) : ?>
				<tr class="<?php echo $m->is_read ? '' : 'erb-unread'; ?>">
					<td><a href="<?php echo esc_url( admin_url( 'admin.php?page=erb-messages&view=' . (int) $m->id ) ); ?>"><?php echo esc_html( $m->name ); ?></a></td>
					<td><?php echo esc_html( $m->contact ); ?></td>
					<td><?php echo esc_html( $m->subject ); ?></td>
					<td><?php echo esc_html( wp_date( 'j M Y, H:i', strtotime( $m->created_at ) ) ); ?></td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
		</div>
		<?php
	}

	public function delete_message() {
		$this->check( 'erb_delete_message' );
		ERB_DB::delete_message( absint( $_POST['id'] ?? 0 ) );
		wp_safe_redirect( admin_url( 'admin.php?page=erb-messages&msg=deleted' ) );
		exit;
	}

	/* ---------------------------------------------------------------------
	 * Settings
	 * ------------------------------------------------------------------ */

	private function setting_fields() {
		return array(
			'phone'        => array( 'Phone number (as shown on the website)', 'text' ),
			'whatsapp'     => array( 'WhatsApp number (e.g. 256772123456 or 0772 123 456)', 'text' ),
			'email'        => array( 'Motel email (shown on the website)', 'email' ),
			'notify_email' => array( 'Send booking alerts to (leave empty to use the motel email)', 'email' ),
			'address'      => array( 'Physical address', 'text' ),
			'map_query'    => array( 'Google Maps search text for the map', 'text' ),
			'checkin'      => array( 'Check-in time', 'text' ),
			'checkout'     => array( 'Check-out time', 'text' ),
			'cancellation' => array( 'Cancellation policy (short)', 'text' ),
			'payment_note' => array( 'Payment note under the booking form', 'textarea' ),
		);
	}

	public function settings_page() {
		$values = wp_parse_args( (array) get_option( 'erb_settings', array() ), erb_default_settings() );
		?>
		<div class="wrap erb-wrap">
			<?php $this->notice(); ?>
			<h1>Bookings settings</h1>
			<p>These details are used across the website: the top bar, footer, contact cards, booking form and WhatsApp buttons. Pages already built keep the text they were built with until you edit them or reset them in Appearance → Motel setup.</p>
			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
				<input type="hidden" name="action" value="erb_save_settings">
				<?php wp_nonce_field( 'erb-settings' ); ?>
				<table class="form-table"><tbody>
				<?php foreach ( $this->setting_fields() as $key => $f ) : ?>
					<tr><th><label for="erb-<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $f[0] ); ?></label></th><td>
						<?php if ( 'textarea' === $f[1] ) : ?>
							<textarea id="erb-<?php echo esc_attr( $key ); ?>" name="erb[<?php echo esc_attr( $key ); ?>]" rows="3" class="large-text"><?php echo esc_textarea( $values[ $key ] ); ?></textarea>
						<?php else : ?>
							<input type="<?php echo esc_attr( $f[1] ); ?>" id="erb-<?php echo esc_attr( $key ); ?>" name="erb[<?php echo esc_attr( $key ); ?>]" class="regular-text" value="<?php echo esc_attr( $values[ $key ] ); ?>">
						<?php endif; ?>
					</td></tr>
				<?php endforeach; ?>
				</tbody></table>
				<?php submit_button( 'Save settings' ); ?>
			</form>
		</div>
		<?php
	}

	public function save_settings() {
		$this->check( 'erb-settings' );
		$in  = isset( $_POST['erb'] ) && is_array( $_POST['erb'] ) ? wp_unslash( $_POST['erb'] ) : array(); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput -- sanitised below.
		$out = array();
		foreach ( $this->setting_fields() as $key => $f ) {
			$v           = isset( $in[ $key ] ) ? $in[ $key ] : '';
			$out[ $key ] = 'textarea' === $f[1] ? sanitize_textarea_field( $v ) : ( 'email' === $f[1] ? sanitize_email( $v ) : sanitize_text_field( $v ) );
		}
		update_option( 'erb_settings', $out );
		wp_safe_redirect( admin_url( 'admin.php?page=erb-settings&msg=saved' ) );
		exit;
	}
}
