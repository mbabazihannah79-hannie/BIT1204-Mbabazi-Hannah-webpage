<?php get_header(); ?>
<?php elroi_page_hero( is_home() ? 'News & Updates' : get_the_archive_title() ); ?>
<section class="section">
	<div class="container prose">
		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
			<article class="post-item">
				<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
				<p class="muted"><?php echo esc_html( get_the_date() ); ?></p>
				<?php the_excerpt(); ?>
			</article>
		<?php endwhile; the_posts_pagination(); else : ?>
			<p>Nothing here yet.</p>
		<?php endif; ?>
	</div>
</section>
<?php get_footer(); ?>
