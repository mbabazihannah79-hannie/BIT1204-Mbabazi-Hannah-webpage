<?php if ( elroi_maybe_render_elementor() ) { return; } ?>
<?php get_header(); ?>
<?php elroi_page_hero( elroi_t( 'contact_hero_title' ), elroi_t( 'contact_hero_sub' ), elroi_banner( 'contact' ) ); ?>

<section class="section">
	<div class="container contact-cards">
		<a class="contact-card" href="<?php echo esc_url( elroi_tel_url() ); ?>">
			<span class="amenity__icon"><?php echo elroi_icon( 'phone' ); ?></span>
			<h3>Call us</h3><p><?php echo esc_html( elroi_opt( 'phone' ) ); ?></p>
		</a>
		<a class="contact-card" href="<?php echo esc_url( elroi_whatsapp_url( 'Hello El Roi Khadoshi Motel' ) ); ?>" target="_blank" rel="noopener">
			<span class="amenity__icon"><?php echo elroi_icon( 'whatsapp' ); ?></span>
			<h3>WhatsApp</h3><p>Chat with reception</p>
		</a>
		<a class="contact-card" href="mailto:<?php echo esc_attr( elroi_opt( 'email' ) ); ?>">
			<span class="amenity__icon"><?php echo elroi_icon( 'mail' ); ?></span>
			<h3>Email</h3><p><?php echo esc_html( elroi_opt( 'email' ) ); ?></p>
		</a>
		<a class="contact-card" href="https://www.google.com/maps/search/?api=1&amp;query=<?php echo rawurlencode( elroi_opt( 'map_query' ) ); ?>" target="_blank" rel="noopener">
			<span class="amenity__icon"><?php echo elroi_icon( 'pin' ); ?></span>
			<h3>Visit us</h3><p><?php echo esc_html( elroi_opt( 'address' ) ); ?></p>
		</a>
	</div>
</section>

<section class="section section--tint">
	<div class="container split split--top">
		<div class="split__text" id="contact-form">
			<h2>Send us a message</h2>
			<?php if ( isset( $_GET['sent'] ) && '1' === $_GET['sent'] ) : ?>
				<div class="alert alert--ok">Thank you! Your message was sent. We will get back to you soon.</div>
			<?php elseif ( isset( $_GET['err'] ) ) : ?>
				<div class="alert alert--error">Please fill in your name, phone or email, and your message.</div>
			<?php endif; ?>
			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" class="form">
				<input type="hidden" name="action" value="elroi_contact">
				<?php wp_nonce_field( 'elroi_contact', 'elroi_nonce' ); ?>
				<input type="text" name="website" class="hp" tabindex="-1" autocomplete="off" aria-hidden="true">
				<div class="form-row">
					<label>Your name *<input type="text" name="full_name" required></label>
					<label>Phone or email *<input type="text" name="contact" required></label>
				</div>
				<label>Subject<input type="text" name="subject" placeholder="Booking question, group stay, events..."></label>
				<label>Message *<textarea name="message" rows="6" required></textarea></label>
				<button class="btn btn--gold" type="submit">Send Message</button>
			</form>
		</div>
		<div>
			<h2><?php elroi_e( 'contact_find_title' ); ?></h2>
			<p><?php elroi_e( 'contact_find_text' ); ?></p>
			<ul class="ticks">
				<?php foreach ( elroi_lines( 'contact_tips' ) as $pt ) : ?>
					<li><?php echo elroi_icon( 'check' ); ?> <?php echo wp_kses( $pt, elroi_allowed_html() ); ?></li>
				<?php endforeach; ?>
			</ul>
			<div class="map map--tall"><?php get_template_part( 'template-parts/map' ); ?></div>
		</div>
	</div>
</section>

<?php get_footer(); ?>
