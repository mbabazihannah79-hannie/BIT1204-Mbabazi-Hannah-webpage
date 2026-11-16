<?php get_header(); ?>
<?php elroi_page_hero( 'Page not found', 'Sorry, we could not find that page.' ); ?>
<section class="section"><div class="container center">
	<p>Try the menu above, or go back to the home page.</p>
	<a class="btn" href="<?php echo esc_url( home_url( '/' ) ); ?>">Go to Home</a>
</div></section>
<?php get_footer(); ?>
