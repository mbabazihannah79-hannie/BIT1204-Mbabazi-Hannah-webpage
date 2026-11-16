<?php if ( elroi_maybe_render_elementor() ) { return; } ?>
<?php get_header(); ?>
<?php elroi_page_hero( elroi_t( 'dining_hero_title' ), elroi_t( 'dining_hero_sub' ), elroi_banner( 'dining' ) ); ?>

<section class="section">
	<div class="container split">
		<div class="split__text">
			<p class="eyebrow"><?php elroi_e( 'dining_eyebrow' ); ?></p>
			<h2><?php elroi_e( 'dining_title' ); ?></h2>
			<p><?php elroi_e( 'dining_intro' ); ?></p>
			<ul class="hours">
				<?php foreach ( elroi_lines( 'dining_hours' ) as $line ) : $h = array_map( 'trim', explode( '|', $line, 2 ) ); ?>
					<li><span><?php echo esc_html( $h[0] ); ?></span><span><?php echo esc_html( isset( $h[1] ) ? $h[1] : '' ); ?></span></li>
				<?php endforeach; ?>
			</ul>
		</div>
		<div class="split__media stack">
			<?php elroi_image_tag( 'dining-room', 'Dining room', 900, 'stack__a' ); ?>
			<?php elroi_image_tag( 'breakfast', 'Breakfast', 700, 'stack__b' ); ?>
		</div>
	</div>
</section>

<section class="section section--tint">
	<div class="container">
		<div class="section-head">
			<p class="eyebrow">Menu</p>
			<h2><?php elroi_e( 'dining_menu_title' ); ?></h2>
			<p class="muted"><?php elroi_e( 'dining_menu_note' ); ?></p>
		</div>
		<?php
		$menu = elroi_menu();
		?>
		<div class="grid grid--3 menu-cols">
			<?php foreach ( $menu as $section => $items ) : ?>
				<div class="menu-col">
					<h3><?php echo esc_html( $section ); ?></h3>
					<ul class="menu-list">
						<?php foreach ( $items as $it ) : ?>
							<li>
								<div><strong><?php echo esc_html( $it[0] ); ?></strong><small><?php echo esc_html( $it[1] ); ?></small></div>
								<span><?php echo esc_html( elroi_money( $it[2] ) ); ?></span>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<section class="section">
	<div class="container split split--flip">
		<div class="split__media"><?php elroi_image_tag( 'bar', 'Bar at El Roi Khadoshi Motel', 900, 'rounded' ); ?></div>
		<div class="split__text">
			<p class="eyebrow">The bar</p>
			<h2><?php elroi_e( 'bar_title' ); ?></h2>
			<p><?php elroi_e( 'bar_text' ); ?></p>
			<a class="btn" href="<?php echo esc_url( elroi_page_url( 'booking' ) ); ?>">Stay with us</a>
		</div>
	</div>
</section>

<section class="section section--tint">
	<div class="container">
		<div class="mosaic mosaic--3">
			<?php foreach ( array( 'breakfast2' => 'Breakfast table', 'coffee' => 'Fresh coffee', 'restaurant' => 'Restaurant' ) as $k => $l ) : ?>
				<a href="<?php echo esc_url( elroi_img( $k, 1600 ) ); ?>" class="js-lightbox" data-caption="<?php echo esc_attr( $l ); ?>"><?php elroi_image_tag( $k, $l, 700 ); ?></a>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<?php get_footer(); ?>
