</main>

<section class="cta-band">
	<div class="container cta-band__inner">
		<div>
			<h2><?php elroi_e( 'cta_title' ); ?></h2>
			<p><?php elroi_e( 'cta_text' ); ?></p>
		</div>
		<div class="cta-band__actions">
			<a class="btn btn--gold" href="<?php echo esc_url( elroi_page_url( 'booking' ) ); ?>">Book a Room</a>
			<a class="btn btn--ghost" href="<?php echo esc_url( elroi_whatsapp_url( 'Hello El Roi Khadoshi Motel, I would like to book a room.' ) ); ?>" target="_blank" rel="noopener"><?php echo elroi_icon( 'whatsapp' ); ?> WhatsApp Us</a>
		</div>
	</div>
</section>

<footer class="site-footer">
	<div class="container footer__grid">
		<div>
			<div class="brand brand--footer">
				<img class="brand__img brand__img--footer" src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/logo-white.svg' ); ?>" alt="<?php echo esc_attr( elroi_opt( 'motel_name' ) ); ?>" width="260" height="67" loading="lazy">
			</div>
			<p><?php elroi_e( 'footer_about' ); ?></p>
		</div>
		<div>
			<h4>Explore</h4>
			<ul class="footer-links">
				<li><a href="<?php echo esc_url( elroi_page_url( 'rooms' ) ); ?>">Rooms &amp; Rates</a></li>
				<li><a href="<?php echo esc_url( elroi_page_url( 'amenities' ) ); ?>">Amenities</a></li>
				<li><a href="<?php echo esc_url( elroi_page_url( 'dining' ) ); ?>">Dining &amp; Bar</a></li>
				<li><a href="<?php echo esc_url( elroi_page_url( 'gallery' ) ); ?>">Gallery</a></li>
				<li><a href="<?php echo esc_url( elroi_page_url( 'explore' ) ); ?>">Explore Kyenjojo</a></li>
				<li><a href="<?php echo esc_url( elroi_page_url( 'faq' ) ); ?>">FAQ</a></li>
			</ul>
		</div>
		<div>
			<h4>Contact</h4>
			<ul class="footer-contact">
				<li><?php echo elroi_icon( 'pin' ); ?> <?php echo esc_html( elroi_opt( 'address' ) ); ?></li>
				<li><?php echo elroi_icon( 'phone' ); ?> <a href="<?php echo esc_url( elroi_tel_url() ); ?>"><?php echo esc_html( elroi_opt( 'phone' ) ); ?></a></li>
				<li><?php echo elroi_icon( 'whatsapp' ); ?> <a href="<?php echo esc_url( elroi_whatsapp_url() ); ?>" target="_blank" rel="noopener">WhatsApp</a></li>
				<li><?php echo elroi_icon( 'mail' ); ?> <a href="mailto:<?php echo esc_attr( elroi_opt( 'email' ) ); ?>"><?php echo esc_html( elroi_opt( 'email' ) ); ?></a></li>
			</ul>
		</div>
		<div>
			<h4>Good to know</h4>
			<ul class="footer-links">
				<li>Check-in: from <?php echo esc_html( elroi_opt( 'checkin' ) ); ?></li>
				<li>Check-out: by <?php echo esc_html( elroi_opt( 'checkout' ) ); ?></li>
				<li>24-hour front desk</li>
				<li>Free Wi-Fi &amp; parking</li>
			</ul>
			<div class="social">
				<?php if ( elroi_opt( 'facebook' ) ) : ?><a href="<?php echo esc_url( elroi_opt( 'facebook' ) ); ?>" target="_blank" rel="noopener">Facebook</a><?php endif; ?>
				<?php if ( elroi_opt( 'instagram' ) ) : ?><a href="<?php echo esc_url( elroi_opt( 'instagram' ) ); ?>" target="_blank" rel="noopener">Instagram</a><?php endif; ?>
			</div>
		</div>
	</div>
	<div class="footer__bottom">
		<div class="container">
			&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php echo esc_html( elroi_opt( 'motel_name' ) ); ?>, Kyenjojo, Uganda. All rights reserved.
		</div>
	</div>
</footer>

<a class="wa-float" href="<?php echo esc_url( elroi_whatsapp_url( 'Hello El Roi Khadoshi Motel' ) ); ?>" target="_blank" rel="noopener" aria-label="Chat on WhatsApp"><?php echo elroi_icon( 'whatsapp' ); ?></a>

<div class="lightbox" id="lightbox" hidden>
	<button class="lightbox__close" aria-label="Close">&times;</button>
	<button class="lightbox__prev" aria-label="Previous">&#8249;</button>
	<img src="" alt="">
	<button class="lightbox__next" aria-label="Next">&#8250;</button>
	<p class="lightbox__caption"></p>
</div>

<?php wp_footer(); ?>
</body>
</html>
