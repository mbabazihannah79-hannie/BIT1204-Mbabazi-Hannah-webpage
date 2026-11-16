<?php if ( elroi_maybe_render_elementor() ) { return; } ?>
<?php get_header(); ?>
<?php elroi_page_hero( elroi_t( 'explore_hero_title' ), elroi_t( 'explore_hero_sub' ), elroi_banner( 'explore' ) ); ?>

<section class="section">
	<div class="container">
		<div class="section-head">
			<p><?php elroi_e( 'explore_intro' ); ?></p>
		</div>
		<?php foreach ( elroi_attractions() as $i => $a ) : ?>
			<div class="split <?php echo 1 === $i % 2 ? 'split--flip' : ''; ?> explore-row">
				<div class="split__media"><?php elroi_image_tag( $a['image'], $a['title'], 900, 'rounded' ); ?></div>
				<div class="split__text">
					<span class="chip"><?php echo esc_html( $a['distance'] ); ?></span>
					<h2><?php echo esc_html( $a['title'] ); ?></h2>
					<p><?php echo esc_html( $a['text'] ); ?></p>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
</section>

<section class="section section--tint">
	<div class="container">
		<div class="section-head"><h2>Travel tips</h2></div>
		<div class="grid grid--3">
			<?php for ( $n = 1; $n <= 3; $n++ ) : ?>
				<div class="tip"><h3><?php elroi_e( 'tip' . $n . '_title' ); ?></h3><p><?php elroi_e( 'tip' . $n . '_text' ); ?></p></div>
			<?php endfor; ?>
		</div>
	</div>
</section>

<?php get_footer(); ?>
