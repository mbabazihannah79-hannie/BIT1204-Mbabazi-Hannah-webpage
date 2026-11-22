<?php
/**
 * Title: El Roi header
 * Slug: elroi-child/header
 * Categories: header
 * Block Types: core/template-part/header
 * Inserter: no
 * Description: Top bar with address, phone and email; logo, menu and Book Now button.
 *
 * @package elroi-child
 */

$erk_brand = get_stylesheet_directory_uri() . '/assets/images/brand/';
$erk_links = array(
	''          => 'Home',
	'about'     => 'About Us',
	'rooms'     => 'Rooms',
	'amenities' => 'Amenities',
	'dining'    => 'Dining &amp; Bar',
	'gallery'   => 'Gallery',
	'explore'   => 'Explore Kyenjojo',
	'faq'       => 'FAQ',
	'contact'   => 'Contact Us',
);
?>
<!-- wp:group {"align":"full","className":"erk-topbar","backgroundColor":"accent-3","textColor":"accent-5","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull erk-topbar has-accent-5-color has-accent-3-background-color has-text-color has-background">
	<!-- wp:group {"align":"wide","className":"erk-topbar-inner","layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between"}} -->
	<div class="wp-block-group alignwide erk-topbar-inner">
		<!-- wp:paragraph {"className":"erk-ico erk-ico-pin erk-topbar-address"} -->
		<p class="erk-ico erk-ico-pin erk-topbar-address"><?php echo esc_html( erk_contact( 'address' ) ); ?></p>
		<!-- /wp:paragraph -->
		<!-- wp:group {"className":"erk-topbar-links","layout":{"type":"flex","flexWrap":"wrap"}} -->
		<div class="wp-block-group erk-topbar-links">
			<!-- wp:paragraph {"className":"erk-ico erk-ico-phone"} -->
			<p class="erk-ico erk-ico-phone"><a href="<?php echo esc_url( erk_tel() ); ?>"><?php echo esc_html( erk_contact( 'phone' ) ); ?></a></p>
			<!-- /wp:paragraph -->
			<!-- wp:paragraph {"className":"erk-ico erk-ico-mail"} -->
			<p class="erk-ico erk-ico-mail"><a href="mailto:<?php echo esc_attr( erk_contact( 'email' ) ); ?>"><?php echo esc_html( erk_contact( 'email' ) ); ?></a></p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->

<!-- wp:group {"align":"full","className":"erk-header","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull erk-header">
	<!-- wp:group {"align":"wide","className":"erk-header-inner","layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between"}} -->
	<div class="wp-block-group alignwide erk-header-inner">
		<!-- wp:image {"width":"240px","sizeSlug":"full","linkDestination":"custom","className":"erk-logo"} -->
		<figure class="wp-block-image size-full is-resized erk-logo"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><img src="<?php echo esc_url( $erk_brand . 'logo.svg' ); ?>" alt="<?php echo esc_attr( ERK_NAME ); ?>" style="width:240px"/></a></figure>
		<!-- /wp:image -->

		<!-- wp:group {"className":"erk-header-actions","layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"right"}} -->
		<div class="wp-block-group erk-header-actions">
			<!-- wp:navigation {"overlayBackgroundColor":"base","overlayTextColor":"contrast","className":"erk-nav","layout":{"type":"flex","justifyContent":"right","flexWrap":"nowrap"}} -->
			<?php foreach ( $erk_links as $erk_slug => $erk_label ) : ?>
				<!-- wp:navigation-link {"label":"<?php echo $erk_label; // phpcs:ignore WordPress.Security.EscapeOutput ?>","url":"<?php echo esc_url( erk_url( $erk_slug ) ); ?>","kind":"custom"} /-->
			<?php endforeach; ?>
			<!-- /wp:navigation -->

			<!-- wp:buttons {"className":"erk-header-cta"} -->
			<div class="wp-block-buttons erk-header-cta">
				<!-- wp:button -->
				<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( erk_url( 'booking' ) ); ?>">Book Now</a></div>
				<!-- /wp:button -->
			</div>
			<!-- /wp:buttons -->
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
