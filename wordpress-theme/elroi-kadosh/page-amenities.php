<?php if ( elroi_maybe_render_elementor() ) { return; } ?>
<?php get_header(); ?>
<?php elroi_page_hero( elroi_t( 'amen_hero_title' ), elroi_t( 'amen_hero_sub' ), elroi_banner( 'amenities' ) ); ?>

<section class="section">
	<div class="container">
		<div class="grid grid--4 amenities">
			<?php foreach ( elroi_amenities() as $a ) : ?>
				<div class="amenity">
					<span class="amenity__icon"><?php echo elroi_icon( $a['icon'] ); ?></span>
					<h3><?php echo esc_html( $a['title'] ); ?></h3>
					<p><?php echo esc_html( $a['text'] ); ?></p>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<?php
$features = array(
	array( 'img' => 'wifi',    'title' => elroi_t( 'amen_f1_title' ), 'text' => elroi_t( 'amen_f1_text' ) ),
	array( 'img' => 'parking', 'title' => elroi_t( 'amen_f2_title' ), 'text' => elroi_t( 'amen_f2_text' ) ),
	array( 'img' => 'gym',     'title' => elroi_t( 'amen_f3_title' ), 'text' => elroi_t( 'amen_f3_text' ) ),
	array( 'img' => 'terrace', 'title' => elroi_t( 'amen_f4_title' ), 'text' => elroi_t( 'amen_f4_text' ) ),
);
foreach ( $features as $i => $f ) : ?>
	<section class="section <?php echo 0 === $i % 2 ? 'section--tint' : ''; ?>">
		<div class="container split <?php echo 1 === $i % 2 ? 'split--flip' : ''; ?>">
			<div class="split__media"><?php elroi_image_tag( $f['img'], $f['title'], 900, 'rounded' ); ?></div>
			<div class="split__text">
				<h2><?php echo esc_html( $f['title'] ); ?></h2>
				<p><?php echo wp_kses( $f['text'], elroi_allowed_html() ); ?></p>
			</div>
		</div>
	</section>
<?php endforeach; ?>

<section class="section">
	<div class="container">
		<div class="section-head"><h2>In every room</h2></div>
		<ul class="feature-list feature-list--cols">
			<?php foreach ( elroi_lines( 'amen_room_list' ) as $f ) : ?>
				<li><?php echo elroi_icon( 'check' ); ?> <?php echo esc_html( $f ); ?></li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>

<?php get_footer(); ?>
