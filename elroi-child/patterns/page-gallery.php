<?php
/**
 * Title: El Roi — Gallery page
 * Slug: elroi-child/page-gallery
 * Categories: elroi
 * Post Types: page
 * Block Types: core/post-content
 * Description: Photo slider, then every photo with category filters and a full-screen viewer.
 *
 * @package elroi-child
 */

echo erk_b_banner( 'Gallery', 'A look inside El Roi Khadoshi Motel', 'room-extra1' ); // phpcs:ignore WordPress.Security.EscapeOutput

echo erk_b_section( // phpcs:ignore WordPress.Security.EscapeOutput
	erk_b_slider( array_slice( erk_gallery_photos(), 0, 10 ), 'fade', 600 )
	. erk_b_spacer( 40 )
	. erk_b_gallery( erk_gallery_photos() )
);
