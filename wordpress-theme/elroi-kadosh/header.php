<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="<?php echo esc_attr( elroi_opt( 'motel_name' ) . ' – comfortable rooms, restaurant, bar and free parking on Market Street, Kyenjojo, Uganda. Close to Kibale National Park.' ); ?>">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link" href="#main">Skip to content</a>

<div class="topbar">
	<div class="container topbar__inner">
		<span><?php echo elroi_icon( 'pin' ); ?> <?php echo esc_html( elroi_opt( 'address' ) ); ?></span>
		<span class="topbar__right">
			<a href="<?php echo esc_url( elroi_tel_url() ); ?>"><?php echo elroi_icon( 'phone' ); ?> <?php echo esc_html( elroi_opt( 'phone' ) ); ?></a>
			<a href="mailto:<?php echo esc_attr( elroi_opt( 'email' ) ); ?>"><?php echo elroi_icon( 'mail' ); ?> <?php echo esc_html( elroi_opt( 'email' ) ); ?></a>
		</span>
	</div>
</div>

<header class="site-header" id="site-header">
	<div class="container header__inner">
		<a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>">
			<?php if ( has_custom_logo() ) : ?>
				<?php echo wp_get_attachment_image( get_theme_mod( 'custom_logo' ), 'full', false, array( 'class' => 'brand__logo' ) ); ?>
			<?php else : ?>
				<img class="brand__img" src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/logo.svg' ); ?>" alt="<?php echo esc_attr( elroi_opt( 'motel_name' ) ); ?>" width="290" height="75">
			<?php endif; ?>
		</a>

		<button class="nav-toggle" aria-controls="primary-nav" aria-expanded="false">
			<span></span><span></span><span></span><span class="screen-reader-text">Menu</span>
		</button>

		<nav class="primary-nav" id="primary-nav" aria-label="Main menu">
			<?php
			wp_nav_menu( array(
				'theme_location' => 'primary',
				'container'      => false,
				'menu_class'     => 'menu',
				'fallback_cb'    => 'elroi_fallback_menu',
				'depth'          => 2,
			) );
			?>
			<a class="btn btn--gold nav-cta" href="<?php echo esc_url( elroi_page_url( 'booking' ) ); ?>">Book Now</a>
		</nav>
	</div>
</header>

<main id="main" class="site-main">
