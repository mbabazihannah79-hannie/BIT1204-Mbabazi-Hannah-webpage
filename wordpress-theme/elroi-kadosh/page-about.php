<?php if ( elroi_maybe_render_elementor() ) { return; } ?>
<?php get_header(); ?>
<?php elroi_page_hero( elroi_t( 'about_hero_title' ), elroi_t( 'about_hero_sub' ), elroi_banner( 'about' ) ); ?>

<section class="section">
	<div class="container split">
		<div class="split__text">
			<p class="eyebrow"><?php elroi_e( 'about_eyebrow' ); ?></p>
			<h2><?php elroi_e( 'about_title' ); ?></h2>
			<p><?php elroi_e( 'about_p1' ); ?></p>
			<p><?php elroi_e( 'about_p2' ); ?></p>
			<p><?php elroi_e( 'about_p3' ); ?></p>
		</div>
		<div class="split__media">
			<?php elroi_image_tag( 'reception', 'Reception at ' . elroi_opt( 'motel_name' ), 900, 'rounded' ); ?>
		</div>
	</div>
</section>

<section class="section section--tint">
	<div class="container">
		<div class="grid grid--3 values">
			<div class="value">
				<span class="amenity__icon"><?php echo elroi_icon( 'star' ); ?></span>
				<h3>Our mission</h3>
				<p><?php elroi_e( 'about_mission' ); ?></p>
			</div>
			<div class="value">
				<span class="amenity__icon"><?php echo elroi_icon( 'sun' ); ?></span>
				<h3>Our vision</h3>
				<p><?php elroi_e( 'about_vision' ); ?></p>
			</div>
			<div class="value">
				<span class="amenity__icon"><?php echo elroi_icon( 'users' ); ?></span>
				<h3>Our values</h3>
				<p><?php elroi_e( 'about_values' ); ?></p>
			</div>
		</div>
	</div>
</section>

<section class="section">
	<div class="container">
		<div class="stats">
			<?php foreach ( elroi_lines( 'about_stats' ) as $line ) :
				$parts = array_map( 'trim', explode( '|', $line, 2 ) ); ?>
				<div><strong><?php echo esc_html( $parts[0] ); ?></strong><span><?php echo esc_html( isset( $parts[1] ) ? $parts[1] : '' ); ?></span></div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<section class="section section--tint">
	<div class="container split">
		<div class="split__media">
			<?php elroi_image_tag( 'terrace', 'Motel terrace', 900, 'rounded' ); ?>
		</div>
		<div class="split__text">
			<p class="eyebrow"><?php elroi_e( 'about_why_eyebrow' ); ?></p>
			<h2><?php elroi_e( 'about_why_title' ); ?></h2>
			<ul class="ticks">
				<?php foreach ( elroi_lines( 'about_why_points' ) as $pt ) : ?>
					<li><?php echo elroi_icon( 'check' ); ?> <?php echo wp_kses( $pt, elroi_allowed_html() ); ?></li>
				<?php endforeach; ?>
			</ul>
			<a class="btn" href="<?php echo esc_url( elroi_page_url( 'rooms' ) ); ?>">View rooms</a>
		</div>
	</div>
</section>

<?php while ( have_posts() ) : the_post(); if ( get_the_content() ) : ?>
	<section class="section"><div class="container prose"><?php the_content(); ?></div></section>
<?php endif; endwhile; ?>

<?php get_footer(); ?>
