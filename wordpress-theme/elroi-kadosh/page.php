<?php if ( elroi_maybe_render_elementor() ) { return; } ?>
<?php get_header(); ?>
<?php while ( have_posts() ) : the_post(); ?>
	<?php elroi_page_hero( get_the_title() ); ?>
	<section class="section">
		<div class="container prose">
			<?php the_content(); ?>
		</div>
	</section>
<?php endwhile; ?>
<?php get_footer(); ?>
