<?php if ( elroi_maybe_render_elementor() ) { return; } ?>
<?php get_header(); ?>
<?php elroi_page_hero( elroi_t( 'faq_hero_title' ), elroi_t( 'faq_hero_sub' ), elroi_banner( 'faq' ) ); ?>

<section class="section">
	<div class="container narrow">
		<div class="faq">
			<?php foreach ( elroi_faqs() as $i => $f ) : ?>
				<details <?php echo 0 === $i ? 'open' : ''; ?>>
					<summary><?php echo esc_html( $f['q'] ); ?></summary>
					<p><?php echo esc_html( $f['a'] ); ?></p>
				</details>
			<?php endforeach; ?>
		</div>
		<p class="center muted">Still have a question? <a href="<?php echo esc_url( elroi_page_url( 'contact' ) ); ?>">Contact us</a> or <a href="<?php echo esc_url( elroi_whatsapp_url() ); ?>" target="_blank" rel="noopener">chat on WhatsApp</a>.</p>
	</div>
</section>

<?php get_footer(); ?>
