<?php
/**
 * Small helpers that print standard WordPress block markup for the page designs
 * in /patterns. They only save typing: the pages they build are ordinary blocks
 * (Cover, Group, Heading, Paragraph, Image, Buttons, List…) that can be clicked
 * and edited in the block editor like any other page.
 *
 * @package elroi-child
 */

defined( 'ABSPATH' ) || exit;

/** Block attributes as JSON for a block comment. */
function erk_b_attrs( $attrs ) {
	$attrs = array_filter(
		$attrs,
		function ( $v ) {
			return null !== $v && '' !== $v;
		}
	);
	return $attrs ? ' ' . wp_json_encode( $attrs, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) : '';
}

/** Paragraph. $html may contain <strong>, <em>, <a> and <br>. */
function erk_b_p( $html, $class = '', $align = '' ) {
	$classes = trim( ( $align ? 'has-text-align-' . $align . ' ' : '' ) . $class );
	return '<!-- wp:paragraph' . erk_b_attrs( array( 'align' => $align, 'className' => $class ) ) . ' -->' . "\n"
		. '<p' . ( $classes ? ' class="' . esc_attr( $classes ) . '"' : '' ) . '>' . $html . '</p>' . "\n"
		. '<!-- /wp:paragraph -->' . "\n";
}

/** Small gold label above a heading. */
function erk_b_eyebrow( $text, $align = '' ) {
	return erk_b_p( esc_html( $text ), 'is-style-eyebrow', $align );
}

/** Heading. */
function erk_b_h( $text, $level = 2, $align = '', $class = '' ) {
	$attrs = array( 'textAlign' => $align );
	if ( 2 !== $level ) {
		$attrs['level'] = $level;
	}
	$attrs['className'] = $class;
	$classes            = trim( 'wp-block-heading ' . ( $align ? 'has-text-align-' . $align . ' ' : '' ) . $class );
	return '<!-- wp:heading' . erk_b_attrs( $attrs ) . ' -->' . "\n"
		. '<h' . $level . ' class="' . esc_attr( $classes ) . '">' . $text . '</h' . $level . '>' . "\n"
		. '<!-- /wp:heading -->' . "\n";
}

/**
 * Image block.
 *
 * @param string $key     Photo key for erk_img(), or a full URL.
 * @param string $alt     Alt text.
 * @param string $class   Extra classes.
 * @param string $caption Caption ('' for none).
 */
function erk_b_image( $key, $alt, $class = 'is-style-erk-rounded', $caption = '' ) {
	$src = preg_match( '#^https?://#', $key ) ? esc_url( $key ) : erk_img( $key );
	return '<!-- wp:image' . erk_b_attrs(
		array(
			'sizeSlug'        => 'large',
			'linkDestination' => 'none',
			'className'       => $class,
		)
	) . ' -->' . "\n"
		. '<figure class="' . esc_attr( trim( 'wp-block-image size-large ' . $class ) ) . '"><img src="' . $src . '" alt="' . esc_attr( $alt ) . '"/>'
		. ( '' !== $caption ? '<figcaption class="wp-element-caption">' . esc_html( $caption ) . '</figcaption>' : '' )
		. '</figure>' . "\n"
		. '<!-- /wp:image -->' . "\n";
}

/**
 * One button. $style: '' (gold), 'green', 'outline' or 'ghost'.
 */
function erk_b_button( $text, $url, $style = '', $new_tab = false ) {
	$class = $style ? 'is-style-' . $style : '';
	return '<!-- wp:button' . erk_b_attrs( array( 'className' => $class ) ) . ' -->' . "\n"
		. '<div class="' . esc_attr( trim( 'wp-block-button ' . $class ) ) . '"><a class="wp-block-button__link wp-element-button" href="' . esc_url( $url ) . '"'
		. ( $new_tab ? ' target="_blank" rel="noreferrer noopener"' : '' ) . '>' . $text . '</a></div>' . "\n"
		. '<!-- /wp:button -->' . "\n";
}

/** A row of buttons. $buttons: list of erk_b_button() strings. */
function erk_b_buttons( $buttons, $justify = '' ) {
	$attrs = $justify ? array( 'layout' => array( 'type' => 'flex', 'justifyContent' => $justify ) ) : array();
	return '<!-- wp:buttons' . erk_b_attrs( $attrs ) . ' -->' . "\n"
		. '<div class="wp-block-buttons">' . "\n" . implode( '', $buttons ) . '</div>' . "\n"
		. '<!-- /wp:buttons -->' . "\n";
}

/**
 * List. $items are HTML strings. $item_classes (optional) gives each item its own class,
 * used for the small icons in contact lists.
 */
function erk_b_list( $items, $class = 'is-style-ticks', $item_classes = array() ) {
	$out = '<!-- wp:list' . erk_b_attrs( array( 'className' => $class ) ) . ' -->' . "\n"
		. '<ul class="' . esc_attr( trim( 'wp-block-list ' . $class ) ) . '">' . "\n";
	foreach ( array_values( $items ) as $i => $item ) {
		$ic   = isset( $item_classes[ $i ] ) ? $item_classes[ $i ] : '';
		$out .= '<!-- wp:list-item' . erk_b_attrs( array( 'className' => $ic ) ) . ' -->' . "\n"
			. '<li' . ( $ic ? ' class="' . esc_attr( $ic ) . '"' : '' ) . '>' . $item . '</li>' . "\n"
			. '<!-- /wp:list-item -->' . "\n";
	}
	return $out . '</ul>' . "\n" . '<!-- /wp:list -->' . "\n";
}

/**
 * Group. $inner is block markup. $bg is a palette slug ('accent-2' for the cream band).
 */
function erk_b_group( $inner, $class = '', $align = '', $layout = 'constrained', $bg = '', $extra = array() ) {
	$attrs = array(
		'align'           => $align,
		'className'       => $class,
		'backgroundColor' => $bg,
	);
	$attrs = array_merge( $attrs, $extra );
	if ( $layout ) {
		$attrs['layout'] = is_array( $layout ) ? $layout : array( 'type' => $layout );
	}
	$classes = 'wp-block-group' . ( $align ? ' align' . $align : '' ) . ( $class ? ' ' . $class : '' ) . ( $bg ? ' has-' . $bg . '-background-color has-background' : '' );
	return '<!-- wp:group' . erk_b_attrs( $attrs ) . ' -->' . "\n"
		. '<div class="' . esc_attr( $classes ) . '">' . "\n" . $inner . '</div>' . "\n"
		. '<!-- /wp:group -->' . "\n";
}

/** Full-width page section. $tint = cream background. */
function erk_b_section( $inner, $tint = false, $class = '' ) {
	return erk_b_group( $inner, trim( 'erk-section ' . $class ), 'full', 'constrained', $tint ? 'accent-2' : '' );
}

/** Wide inner wrapper inside a section. */
function erk_b_wide( $inner, $class = '' ) {
	return erk_b_group( $inner, $class, 'wide', 'default' );
}

/** Centered section heading: label, heading and an optional line of text. */
function erk_b_head( $eyebrow, $title, $text = '' ) {
	$inner = ( $eyebrow ? erk_b_eyebrow( $eyebrow, 'center' ) : '' )
		. erk_b_h( esc_html( $title ), 2, 'center' )
		. ( $text ? erk_b_p( $text, '', 'center' ) : '' );
	return erk_b_group( $inner, 'erk-section-head', '', 'constrained' );
}

/** Grid of cards (Group with grid layout). */
function erk_b_grid( $inner, $columns = 4, $class = '' ) {
	return erk_b_group(
		$inner,
		trim( 'erk-grid erk-grid-' . $columns . ' ' . $class ),
		'wide',
		array(
			'type'        => 'grid',
			'columnCount' => $columns,
		)
	);
}

/** Two columns (photo and text). $flip puts the photo on the right. */
function erk_b_split( $media, $text, $flip = false, $class = '' ) {
	$col_a = '<!-- wp:column -->' . "\n" . '<div class="wp-block-column">' . "\n" . ( $flip ? $text : $media ) . '</div>' . "\n" . '<!-- /wp:column -->' . "\n";
	$col_b = '<!-- wp:column -->' . "\n" . '<div class="wp-block-column">' . "\n" . ( $flip ? $media : $text ) . '</div>' . "\n" . '<!-- /wp:column -->' . "\n";
	$cls   = trim( 'erk-split ' . $class );
	return '<!-- wp:columns' . erk_b_attrs(
		array(
			'align'     => 'wide',
			'className' => $cls,
		)
	) . ' -->' . "\n"
		. '<div class="wp-block-columns alignwide ' . esc_attr( $cls ) . '">' . "\n" . $col_a . $col_b . '</div>' . "\n"
		. '<!-- /wp:columns -->' . "\n";
}

/** Card with an icon (icon = class erk-ico-<name>), heading and text. */
function erk_b_card( $icon, $title, $text, $class = 'erk-card' ) {
	return erk_b_group(
		erk_b_h( esc_html( $title ), 3 ) . erk_b_p( $text ),
		trim( $class . ( $icon ? ' erk-has-icon erk-ico-' . $icon : '' ) ),
		'',
		'default'
	);
}

/**
 * Photo banner at the top of inner pages (Cover block). Click the photo in the
 * editor to replace it; the heading and text are normal blocks.
 */
function erk_b_banner( $title, $sub, $photo ) {
	$url = erk_img( $photo, 2000 );
	return '<!-- wp:cover' . erk_b_attrs(
		array(
			'url'                => html_entity_decode( $url ),
			'dimRatio'           => 70,
			'overlayColor'       => 'accent-3',
			'isUserOverlayColor' => true,
			'align'              => 'full',
			'className'          => 'erk-banner',
			'layout'             => array( 'type' => 'constrained' ),
		)
	) . ' -->' . "\n"
		. '<div class="wp-block-cover alignfull erk-banner"><span aria-hidden="true" class="wp-block-cover__background has-accent-3-background-color has-background-dim-70 has-background-dim"></span>'
		. '<img class="wp-block-cover__image-background" alt="" src="' . $url . '" data-object-fit="cover"/>'
		. '<div class="wp-block-cover__inner-container">' . "\n"
		. erk_b_h( esc_html( $title ), 1, 'center' )
		. ( $sub ? erk_b_p( esc_html( $sub ), 'erk-banner-sub', 'center' ) : '' )
		. '</div></div>' . "\n"
		. '<!-- /wp:cover -->' . "\n";
}

/** Live content from the El Roi Bookings plugin. */
function erk_b_shortcode( $code ) {
	return '<!-- wp:shortcode -->' . "\n" . $code . "\n" . '<!-- /wp:shortcode -->' . "\n";
}

/** Google map of the motel (no API key needed). */
function erk_b_map( $height = 400 ) {
	$q = rawurlencode( erk_contact( 'map_query' ) );
	return '<!-- wp:html -->' . "\n"
		. '<div class="erk-map" style="--map-h:' . (int) $height . 'px"><iframe title="Map showing El Roi Khadoshi Motel, Kyenjojo" src="https://maps.google.com/maps?q=' . esc_attr( $q ) . '&amp;z=16&amp;output=embed" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe></div>' . "\n"
		. '<!-- /wp:html -->' . "\n";
}

/** Spacer. */
function erk_b_spacer( $px = 24 ) {
	return '<!-- wp:spacer {"height":"' . (int) $px . 'px"} -->' . "\n"
		. '<div style="height:' . (int) $px . 'px" aria-hidden="true" class="wp-block-spacer"></div>' . "\n"
		. '<!-- /wp:spacer -->' . "\n";
}

/**
 * Sliding photo gallery: a Group of Image blocks. slider.js turns it into a slider
 * with arrows, dots, autoplay and swipe. In the editor the photos show as a row, so
 * each one can be replaced or captioned, and Image blocks can be added or removed.
 *
 * @param array  $photos List of [ key, caption ].
 * @param string $effect slide | fade.
 * @param int    $height Height in px.
 */
function erk_b_slider( $photos, $effect = 'slide', $height = 520 ) {
	$inner = '';
	foreach ( $photos as $p ) {
		$inner .= erk_b_image( $p[0], $p[1] . ', El Roi Khadoshi Motel', 'erk-slide', $p[1] );
	}
	return erk_b_group( $inner, 'erk-slider erk-slider-' . $effect . ' erk-h-' . (int) $height, 'wide', 'default' );
}

/**
 * Photo grid with category filters and a full-screen viewer (gallery.js).
 * Each photo's category is the class erk-cat-motel | erk-cat-rooms | erk-cat-dining | erk-cat-area.
 *
 * @param array $photos List of [ key, caption, category ].
 */
function erk_b_gallery( $photos ) {
	$inner = '';
	foreach ( $photos as $p ) {
		$inner .= erk_b_image( $p[0], $p[1], 'erk-gallery-item erk-cat-' . $p[2], $p[1] );
	}
	return erk_b_group(
		$inner,
		'erk-gallery erk-lightbox',
		'wide',
		array(
			'type'        => 'grid',
			'columnCount' => 4,
		)
	);
}

/** Table block. $rows: list of [ cell HTML, cell HTML, … ]. */
function erk_b_table( $rows, $class = '' ) {
	$body = '';
	foreach ( $rows as $row ) {
		$body .= '<tr>';
		foreach ( $row as $cell ) {
			$body .= '<td>' . $cell . '</td>';
		}
		$body .= '</tr>';
	}
	return '<!-- wp:table' . erk_b_attrs( array( 'className' => $class ) ) . ' -->' . "\n"
		. '<figure class="' . esc_attr( trim( 'wp-block-table ' . $class ) ) . '"><table class="has-fixed-layout"><tbody>' . $body . '</tbody></table></figure>' . "\n"
		. '<!-- /wp:table -->' . "\n";
}
