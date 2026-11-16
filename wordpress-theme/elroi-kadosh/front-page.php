<?php if ( elroi_maybe_render_elementor() ) { return; } ?>
<?php get_header(); ?>

<section class="hero" style="--hero:url('<?php echo esc_url( elroi_img( 'hero', 2000 ) ); ?>')">
	<div class="container hero__inner">
		<p class="eyebrow"><?php elroi_e( 'home_eyebrow' ); ?></p>
		<h1><?php echo esc_html( elroi_opt( 'motel_name' ) ); ?></h1>
		<p class="hero__lead"><?php elroi_e( 'home_lead' ); ?></p>
		<div class="hero__actions">
			<a class="btn btn--gold" href="<?php echo esc_url( elroi_page_url( 'rooms' ) ); ?>"><?php elroi_e( 'home_btn' ); ?></a>
			<a class="btn btn--ghost" href="<?php echo esc_url( elroi_tel_url() ); ?>"><?php echo elroi_icon( 'phone' ); ?> Call <?php echo esc_html( elroi_opt( 'phone' ) ); ?></a>
		</div>
	</div>

	<form class="quick-book container" action="<?php echo esc_url( elroi_page_url( 'booking' ) ); ?>" method="get">
		<label>Check-in<input type="date" name="checkin" required min="<?php echo esc_attr( gmdate( 'Y-m-d' ) ); ?>"></label>
		<label>Check-out<input type="date" name="checkout" required min="<?php echo esc_attr( gmdate( 'Y-m-d' ) ); ?>"></label>
		<label>Room
			<select name="room">
				<?php foreach ( elroi_rooms() as $slug => $r ) : ?>
					<option value="<?php echo esc_attr( $slug ); ?>"><?php echo esc_html( $r['name'] ); ?></option>
				<?php endforeach; ?>
			</select>
		</label>
		<label>Guests<input type="number" name="guests" min="1" max="8" value="2"></label>
		<button class="btn btn--gold" type="submit">Check Availability</button>
	</form>
</section>

<section class="section">
	<div class="container split">
		<div class="split__media stack">
			<?php elroi_image_tag( 'exterior', 'El Roi Khadoshi Motel building', 900, 'stack__a' ); ?>
			<?php elroi_image_tag( 'reception', 'Reception', 700, 'stack__b' ); ?>
		</div>
		<div class="split__text">
			<p class="eyebrow"><?php elroi_e( 'home_about_eyebrow' ); ?></p>
			<h2><?php elroi_e( 'home_about_title' ); ?></h2>
			<p><?php elroi_e( 'home_about_p1' ); ?></p>
			<p><?php elroi_e( 'home_about_p2' ); ?></p>
			<ul class="ticks">
				<?php foreach ( elroi_lines( 'home_about_points' ) as $pt ) : ?>
					<li><?php echo elroi_icon( 'check' ); ?> <?php echo wp_kses( $pt, elroi_allowed_html() ); ?></li>
				<?php endforeach; ?>
			</ul>
			<a class="btn" href="<?php echo esc_url( elroi_page_url( 'about' ) ); ?>">Read our story</a>
		</div>
	</div>
</section>

<section class="section section--tint">
	<div class="container">
		<div class="section-head">
			<p class="eyebrow"><?php elroi_e( 'home_rooms_eyebrow' ); ?></p>
			<h2><?php elroi_e( 'home_rooms_title' ); ?></h2>
			<p><?php elroi_e( 'home_rooms_text' ); ?></p>
		</div>
		<div class="grid grid--4">
			<?php foreach ( elroi_rooms() as $slug => $room ) { elroi_room_card( $slug, $room ); } ?>
		</div>
	</div>
</section>

<section class="section">
	<div class="container">
		<div class="section-head">
			<p class="eyebrow"><?php elroi_e( 'home_amen_eyebrow' ); ?></p>
			<h2><?php elroi_e( 'home_amen_title' ); ?></h2>
		</div>
		<div class="grid grid--4 amenities">
			<?php foreach ( elroi_amenities( true ) as $a ) : ?>
				<div class="amenity">
					<span class="amenity__icon"><?php echo elroi_icon( $a['icon'] ); ?></span>
					<h3><?php echo esc_html( $a['title'] ); ?></h3>
					<p><?php echo esc_html( $a['text'] ); ?></p>
				</div>
			<?php endforeach; ?>
		</div>
		<p class="center"><a class="btn btn--outline" href="<?php echo esc_url( elroi_page_url( 'amenities' ) ); ?>">All amenities</a></p>
	</div>
</section>

<section class="feature-band" style="--hero:url('<?php echo esc_url( elroi_img( 'restaurant', 1800 ) ); ?>')">
	<div class="container feature-band__inner">
		<p class="eyebrow"><?php elroi_e( 'home_dining_eyebrow' ); ?></p>
		<h2><?php elroi_e( 'home_dining_title' ); ?></h2>
		<p><?php elroi_e( 'home_dining_text' ); ?></p>
		<a class="btn btn--gold" href="<?php echo esc_url( elroi_page_url( 'dining' ) ); ?>">See the menu</a>
	</div>
</section>

<section class="section">
	<div class="container">
		<div class="section-head">
			<p class="eyebrow"><?php elroi_e( 'home_explore_eyebrow' ); ?></p>
			<h2><?php elroi_e( 'home_explore_title' ); ?></h2>
		</div>
		<div class="grid grid--4">
			<?php foreach ( elroi_attractions() as $a ) : ?>
				<article class="place-card">
					<?php elroi_image_tag( $a['image'], $a['title'], 700 ); ?>
					<div class="place-card__body">
						<span class="chip"><?php echo esc_html( $a['distance'] ); ?></span>
						<h3><?php echo esc_html( $a['title'] ); ?></h3>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
		<p class="center"><a class="btn btn--outline" href="<?php echo esc_url( elroi_page_url( 'explore' ) ); ?>">Things to do near us</a></p>
	</div>
</section>

<section class="section section--tint">
	<div class="container">
		<div class="section-head">
			<p class="eyebrow"><?php elroi_e( 'home_gallery_eyebrow' ); ?></p>
			<h2><?php elroi_e( 'home_gallery_title' ); ?></h2>
		</div>
		<div class="mosaic">
			<?php
			$room_list = array_values( elroi_rooms() );
			$shots = array(
				array( isset( $room_list[1] ) ? $room_list[1]['image'] : 'room-deluxe', isset( $room_list[1] ) ? $room_list[1]['name'] : 'Room' ),
				array( 'terrace', 'Terrace' ),
				array( 'breakfast', 'Breakfast' ),
				array( isset( $room_list[2] ) ? $room_list[2]['image'] : 'room-family', isset( $room_list[2] ) ? $room_list[2]['name'] : 'Room' ),
				array( 'bar', 'Bar' ),
				array( 'bathroom', 'Bathroom' ),
			);
			foreach ( $shots as $shot ) : list( $key, $label ) = $shot; ?>
				<a href="<?php echo esc_url( elroi_img( $key, 1600 ) ); ?>" class="js-lightbox" data-caption="<?php echo esc_attr( $label ); ?>">
					<?php elroi_image_tag( $key, $label, 700 ); ?>
				</a>
			<?php endforeach; ?>
		</div>
		<p class="center"><a class="btn btn--outline" href="<?php echo esc_url( elroi_page_url( 'gallery' ) ); ?>">Open full gallery</a></p>
	</div>
</section>

<section class="section">
	<div class="container split split--map">
		<div class="split__text">
			<p class="eyebrow"><?php elroi_e( 'home_find_eyebrow' ); ?></p>
			<h2><?php elroi_e( 'home_find_title' ); ?></h2>
			<ul class="contact-list">
				<li><?php echo elroi_icon( 'pin' ); ?> <?php echo esc_html( elroi_opt( 'address' ) ); ?></li>
				<li><?php echo elroi_icon( 'phone' ); ?> <a href="<?php echo esc_url( elroi_tel_url() ); ?>"><?php echo esc_html( elroi_opt( 'phone' ) ); ?></a></li>
				<li><?php echo elroi_icon( 'mail' ); ?> <a href="mailto:<?php echo esc_attr( elroi_opt( 'email' ) ); ?>"><?php echo esc_html( elroi_opt( 'email' ) ); ?></a></li>
				<li><?php echo elroi_icon( 'clock' ); ?> Reception open 24 hours</li>
			</ul>
			<a class="btn" href="<?php echo esc_url( elroi_page_url( 'contact' ) ); ?>">Get directions</a>
		</div>
		<div class="map">
			<?php get_template_part( 'template-parts/map' ); ?>
		</div>
	</div>
</section>

<?php get_footer(); ?>
