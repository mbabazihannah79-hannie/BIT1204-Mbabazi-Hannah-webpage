<?php if ( elroi_maybe_render_elementor() ) { return; } ?>
<?php get_header(); ?>
<?php elroi_page_hero( elroi_t( 'rooms_hero_title' ), elroi_t( 'rooms_hero_sub' ), elroi_banner( 'rooms' ) ); ?>

<section class="section">
	<div class="container">
		<div class="section-head">
			<p><?php elroi_e( 'rooms_intro' ); ?></p>
		</div>

		<?php $i = 0; foreach ( elroi_rooms() as $slug => $room ) : $i++; ?>
			<article class="room-detail <?php echo 0 === $i % 2 ? 'room-detail--flip' : ''; ?>" id="room-<?php echo esc_attr( $slug ); ?>">
				<div class="room-detail__gallery">
					<a href="<?php echo esc_url( elroi_img( $room['gallery'][0], 1600 ) ); ?>" class="js-lightbox room-detail__main" data-caption="<?php echo esc_attr( $room['name'] ); ?>">
						<?php elroi_image_tag( $room['gallery'][0], $room['name'], 1000 ); ?>
					</a>
					<div class="room-detail__thumbs">
						<?php foreach ( array_slice( $room['gallery'], 1 ) as $g ) : ?>
							<a href="<?php echo esc_url( elroi_img( $g, 1600 ) ); ?>" class="js-lightbox" data-caption="<?php echo esc_attr( $room['name'] ); ?>">
								<?php elroi_image_tag( $g, $room['name'], 500 ); ?>
							</a>
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
						<?php foreach ( $room['features'] as $f ) : ?>
							<li><?php echo elroi_icon( 'check' ); ?> <?php echo esc_html( $f ); ?></li>
						<?php endforeach; ?>
					</ul>
					<div class="btn-row">
						<a class="btn btn--gold" href="<?php echo esc_url( add_query_arg( 'room', $slug, elroi_page_url( 'booking' ) ) ); ?>">Book this room</a>
						<a class="btn btn--outline" href="<?php echo esc_url( elroi_whatsapp_url( 'Hello, I would like to ask about the ' . $room['name'] . '.' ) ); ?>" target="_blank" rel="noopener">Ask on WhatsApp</a>
					</div>
				</div>
			</article>
		<?php endforeach; ?>
	</div>
</section>

<section class="section section--tint">
	<div class="container">
		<div class="section-head"><h2>Compare rooms</h2></div>
		<div class="table-wrap">
			<table class="compare">
				<thead><tr><th>Room</th><th>Guests</th><th>Beds</th><th>Balcony</th><th>Dining area</th><th>Price / night</th></tr></thead>
				<tbody>
					<?php foreach ( elroi_rooms() as $slug => $room ) :
						$feat    = strtolower( implode( ' ', $room['features'] ) );
						$balcony = false !== strpos( $feat, 'balcony' ) ? 'Yes' : 'No';
						$dining  = false !== strpos( $feat, 'dining' ) ? 'Yes' : 'No'; ?>
						<tr>
							<td><a href="#room-<?php echo esc_attr( $slug ); ?>"><?php echo esc_html( $room['name'] ); ?></a></td>
							<td><?php echo intval( $room['guests'] ); ?></td>
							<td><?php echo esc_html( $room['bed'] ); ?></td>
							<td><?php echo esc_html( $balcony ); ?></td>
							<td><?php echo esc_html( $dining ); ?></td>
							<td><strong><?php echo esc_html( elroi_money( $room['price'] ) ); ?></strong></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
		<p class="muted center">Check-in from <?php echo esc_html( elroi_opt( 'checkin' ) ); ?> &middot; Check-out by <?php echo esc_html( elroi_opt( 'checkout' ) ); ?> &middot; Breakfast available every morning</p>
	</div>
</section>

<?php get_footer(); ?>
