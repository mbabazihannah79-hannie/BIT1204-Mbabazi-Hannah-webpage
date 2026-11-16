<?php if ( elroi_maybe_render_elementor() ) { return; } ?>
<?php get_header(); ?>
<?php elroi_page_hero( elroi_t( 'gallery_hero_title' ), elroi_t( 'gallery_hero_sub' ), elroi_banner( 'gallery' ) ); ?>

<?php
$gallery = array(
	array( 'hero', 'Motel building', 'motel' ),
	array( 'exterior', 'Outside view', 'motel' ),
	array( 'reception', 'Reception', 'motel' ),
	array( 'terrace', 'Terrace', 'motel' ),
	array( 'parking', 'Parking', 'motel' ),
	array( 'gym', 'Fitness room', 'motel' ),
);
// Every room photo from Motel Content > Rooms.
foreach ( elroi_rooms() as $room ) {
	foreach ( array_unique( $room['gallery'] ) as $g ) { $gallery[] = array( $g, $room['name'], 'rooms' ); }
}
$gallery[] = array( 'bathroom', 'Private bathroom', 'rooms' );
foreach ( array( 'restaurant' => 'Restaurant', 'dining-room' => 'Dining room', 'breakfast' => 'Breakfast', 'breakfast2' => 'Breakfast table', 'coffee' => 'Coffee', 'bar' => 'Bar' ) as $k => $l ) {
	$gallery[] = array( $k, $l, 'dining' );
}
// Photos of places from Motel Content > Places to Visit.
foreach ( elroi_attractions() as $a ) { $gallery[] = array( $a['image'], $a['title'], 'area' ); }
$filters = array( 'all' => 'All', 'motel' => 'The Motel', 'rooms' => 'Rooms', 'dining' => 'Dining & Bar', 'area' => 'Around Kyenjojo' );
?>

<section class="section">
	<div class="container">
		<?php elroi_render_slider( elroi_slider_slides( 'gallery', 10 ), array( 'height' => 600, 'effect' => 'fade' ) ); ?>
		<div style="height:40px"></div>
		<div class="filters" role="tablist">
			<?php foreach ( $filters as $k => $l ) : ?>
				<button class="filter <?php echo 'all' === $k ? 'is-active' : ''; ?>" data-filter="<?php echo esc_attr( $k ); ?>"><?php echo esc_html( $l ); ?></button>
			<?php endforeach; ?>
		</div>
		<div class="gallery-grid">
			<?php foreach ( $gallery as $g ) : ?>
				<a href="<?php echo esc_url( elroi_img( $g[0], 1600 ) ); ?>" class="gallery-item js-lightbox" data-cat="<?php echo esc_attr( $g[2] ); ?>" data-caption="<?php echo esc_attr( $g[1] ); ?>">
					<?php elroi_image_tag( $g[0], $g[1], 700 ); ?>
					<span><?php echo esc_html( $g[1] ); ?></span>
				</a>
			<?php endforeach; ?>
		</div>

		<?php
		// Photos uploaded through the WordPress editor on this page are shown here too.
		while ( have_posts() ) : the_post();
			if ( get_the_content() ) : ?>
				<div class="prose" style="margin-top:3rem"><?php the_content(); ?></div>
			<?php endif;
		endwhile; ?>
	</div>
</section>

<?php get_footer(); ?>
