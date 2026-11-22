<?php
/**
 * Title: El Roi — FAQ page
 * Slug: elroi-child/page-faq
 * Categories: elroi
 * Post Types: page
 * Block Types: core/post-content
 * Description: Questions and answers that open and close (Details blocks).
 *
 * @package elroi-child
 */

$erk_faq = '';
foreach ( erk_faqs() as $erk_i => $erk_f ) {
	$erk_open = 0 === $erk_i;
	$erk_faq .= '<!-- wp:details {' . ( $erk_open ? '"showContent":true,' : '' ) . '"className":"erk-faq-item"} -->' . "\n"
		. '<details class="wp-block-details erk-faq-item"' . ( $erk_open ? ' open' : '' ) . '><summary>' . esc_html( $erk_f[0] ) . '</summary>' . "\n"
		. erk_b_p( esc_html( $erk_f[1] ) )
		. '</details>' . "\n"
		. '<!-- /wp:details -->' . "\n";
}

echo erk_b_banner( 'Frequently Asked Questions', 'Quick answers before you book', 'room-extra2' ); // phpcs:ignore WordPress.Security.EscapeOutput

echo erk_b_section( // phpcs:ignore WordPress.Security.EscapeOutput
	erk_b_group( $erk_faq, 'erk-faq', '', 'default' )
	. erk_b_spacer( 20 )
	. erk_b_p( 'Still have a question? <a href="' . esc_url( erk_url( 'contact' ) ) . '">Contact us</a> or <a href="' . esc_url( erk_wa() ) . '" target="_blank" rel="noreferrer noopener">chat on WhatsApp</a>.', '', 'center' )
);
