<?php
/**
 * Title: El Roi footer
 * Slug: elroi-child/footer
 * Categories: footer
 * Block Types: core/template-part/footer
 * Inserter: no
 * Description: Gold booking band, then the footer with links, contacts and check-in times.
 *
 * @package elroi-child
 */

$erk_brand = get_stylesheet_directory_uri() . '/assets/images/brand/';
?>
<!-- wp:group {"align":"full","className":"erk-cta-band","backgroundColor":"gold","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull erk-cta-band has-gold-background-color has-background">
	<!-- wp:group {"align":"wide","className":"erk-cta-inner","layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between"}} -->
	<div class="wp-block-group alignwide erk-cta-inner">
		<!-- wp:group {"className":"erk-cta-text","layout":{"type":"default"}} -->
		<div class="wp-block-group erk-cta-text">
			<!-- wp:heading -->
			<h2 class="wp-block-heading">Planning a trip to Kyenjojo or Kibale?</h2>
			<!-- /wp:heading -->
			<!-- wp:paragraph -->
			<p>Reserve your room in a minute. We confirm every booking by phone or WhatsApp.</p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->
		<!-- wp:buttons -->
		<div class="wp-block-buttons">
			<!-- wp:button {"className":"is-style-green"} -->
			<div class="wp-block-button is-style-green"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( erk_url( 'booking' ) ); ?>">Book a Room</a></div>
			<!-- /wp:button -->
			<!-- wp:button {"className":"is-style-outline"} -->
			<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( erk_wa( 'Hello El Roi Khadoshi Motel, I would like to book a room.' ) ); ?>" target="_blank" rel="noreferrer noopener">WhatsApp Us</a></div>
			<!-- /wp:button -->
		</div>
		<!-- /wp:buttons -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->

<!-- wp:group {"align":"full","className":"erk-footer","backgroundColor":"accent-3","textColor":"accent-5","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull erk-footer has-accent-5-color has-accent-3-background-color has-text-color has-background">
	<!-- wp:columns {"align":"wide","className":"erk-footer-grid"} -->
	<div class="wp-block-columns alignwide erk-footer-grid">
		<!-- wp:column {"width":"30%"} -->
		<div class="wp-block-column" style="flex-basis:30%">
			<!-- wp:image {"width":"240px","sizeSlug":"full","linkDestination":"none","className":"erk-footer-logo"} -->
			<figure class="wp-block-image size-full is-resized erk-footer-logo"><img src="<?php echo esc_url( $erk_brand . 'logo-white.svg' ); ?>" alt="<?php echo esc_attr( ERK_NAME ); ?>" style="width:240px"/></figure>
			<!-- /wp:image -->
			<!-- wp:paragraph -->
			<p>A clean, quiet and affordable place to stay on Market Street, Kyenjojo, on the road between Kampala and Fort Portal.</p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:heading {"level":4,"className":"erk-footer-title"} -->
			<h4 class="wp-block-heading erk-footer-title">Explore</h4>
			<!-- /wp:heading -->
			<!-- wp:list {"className":"erk-footer-links"} -->
			<ul class="wp-block-list erk-footer-links">
				<!-- wp:list-item -->
				<li><a href="<?php echo esc_url( erk_url( 'rooms' ) ); ?>">Rooms &amp; Rates</a></li>
				<!-- /wp:list-item -->
				<!-- wp:list-item -->
				<li><a href="<?php echo esc_url( erk_url( 'amenities' ) ); ?>">Amenities</a></li>
				<!-- /wp:list-item -->
				<!-- wp:list-item -->
				<li><a href="<?php echo esc_url( erk_url( 'dining' ) ); ?>">Dining &amp; Bar</a></li>
				<!-- /wp:list-item -->
				<!-- wp:list-item -->
				<li><a href="<?php echo esc_url( erk_url( 'gallery' ) ); ?>">Gallery</a></li>
				<!-- /wp:list-item -->
				<!-- wp:list-item -->
				<li><a href="<?php echo esc_url( erk_url( 'explore' ) ); ?>">Explore Kyenjojo</a></li>
				<!-- /wp:list-item -->
				<!-- wp:list-item -->
				<li><a href="<?php echo esc_url( erk_url( 'faq' ) ); ?>">FAQ</a></li>
				<!-- /wp:list-item -->
			</ul>
			<!-- /wp:list -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:heading {"level":4,"className":"erk-footer-title"} -->
			<h4 class="wp-block-heading erk-footer-title">Contact</h4>
			<!-- /wp:heading -->
			<!-- wp:list {"className":"erk-footer-links erk-icon-list"} -->
			<ul class="wp-block-list erk-footer-links erk-icon-list">
				<!-- wp:list-item {"className":"erk-ico erk-ico-pin"} -->
				<li class="erk-ico erk-ico-pin"><?php echo esc_html( erk_contact( 'address' ) ); ?></li>
				<!-- /wp:list-item -->
				<!-- wp:list-item {"className":"erk-ico erk-ico-phone"} -->
				<li class="erk-ico erk-ico-phone"><a href="<?php echo esc_url( erk_tel() ); ?>"><?php echo esc_html( erk_contact( 'phone' ) ); ?></a></li>
				<!-- /wp:list-item -->
				<!-- wp:list-item {"className":"erk-ico erk-ico-whatsapp"} -->
				<li class="erk-ico erk-ico-whatsapp"><a href="<?php echo esc_url( erk_wa() ); ?>" target="_blank" rel="noreferrer noopener">WhatsApp</a></li>
				<!-- /wp:list-item -->
				<!-- wp:list-item {"className":"erk-ico erk-ico-mail"} -->
				<li class="erk-ico erk-ico-mail"><a href="mailto:<?php echo esc_attr( erk_contact( 'email' ) ); ?>"><?php echo esc_html( erk_contact( 'email' ) ); ?></a></li>
				<!-- /wp:list-item -->
			</ul>
			<!-- /wp:list -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:heading {"level":4,"className":"erk-footer-title"} -->
			<h4 class="wp-block-heading erk-footer-title">Good to know</h4>
			<!-- /wp:heading -->
			<!-- wp:list {"className":"erk-footer-links"} -->
			<ul class="wp-block-list erk-footer-links">
				<!-- wp:list-item -->
				<li>Check-in: from <?php echo esc_html( erk_contact( 'checkin' ) ); ?></li>
				<!-- /wp:list-item -->
				<!-- wp:list-item -->
				<li>Check-out: by <?php echo esc_html( erk_contact( 'checkout' ) ); ?></li>
				<!-- /wp:list-item -->
				<!-- wp:list-item -->
				<li>24-hour front desk</li>
				<!-- /wp:list-item -->
				<!-- wp:list-item -->
				<li>Free Wi-Fi &amp; parking</li>
				<!-- /wp:list-item -->
			</ul>
			<!-- /wp:list -->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->

	<!-- wp:group {"align":"wide","className":"erk-footer-bottom","layout":{"type":"constrained"}} -->
	<div class="wp-block-group alignwide erk-footer-bottom">
		<!-- wp:paragraph {"align":"center","fontSize":"small"} -->
		<p class="has-text-align-center has-small-font-size">© <?php echo esc_html( gmdate( 'Y' ) ); ?> El Roi Khadoshi Motel, Kyenjojo, Uganda. All rights reserved.</p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
