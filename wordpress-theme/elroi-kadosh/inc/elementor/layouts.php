<?php
/**
 * Page designs for Elementor. Each page is built from normal Elementor sections,
 * columns and widgets (Heading, Text, Image, Button, Icon List, Icon Box) plus ERK widgets,
 * so every element can be clicked and edited in the Elementor editor.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

/* ---------------- Builders ---------------- */
function erk_id() { return substr( md5( uniqid( '', true ) . wp_rand() ), 0, 7 ); }

function erk_w( $type, $settings = array() ) {
	return array( 'id' => erk_id(), 'elType' => 'widget', 'widgetType' => $type, 'settings' => (object) $settings, 'elements' => array() );
}

function erk_col( $size, $widgets, $settings = array() ) {
	return array(
		'id' => erk_id(), 'elType' => 'column', 'isInner' => false,
		'settings' => (object) array_merge( array( '_column_size' => $size, '_inline_size' => null ), $settings ),
		'elements' => $widgets,
	);
}

function erk_sec( $cols, $settings = array() ) {
	return array(
		'id' => erk_id(), 'elType' => 'section', 'isInner' => false,
		'settings' => (object) array_merge( array(
			'layout'        => 'boxed',
			'content_width' => array( 'unit' => 'px', 'size' => 1180, 'sizes' => array() ),
			'gap'           => 'extended',
			'padding'       => erk_pad( 88, 88 ),
			'content_position' => 'middle',
		), $settings ),
		'elements' => $cols,
	);
}

function erk_pad( $top, $bottom, $side = 0 ) {
	return array( 'unit' => 'px', 'top' => (string) $top, 'right' => (string) $side, 'bottom' => (string) $bottom, 'left' => (string) $side, 'isLinked' => false );
}

function erk_tint() {
	return array( 'background_background' => 'classic', 'background_color' => '#F7F3EA' );
}

function erk_bg( $image, $opacity = 0.8, $color = '#143528' ) {
	return array(
		'background_background'         => 'classic',
		'background_color'              => '#1F4D3A',
		'background_image'              => array( 'url' => elroi_img( $image, 2000 ), 'id' => '', 'source' => 'library' ),
		'background_position'           => 'center center',
		'background_size'               => 'cover',
		'background_overlay_background' => 'classic',
		'background_overlay_color'      => $color,
		'background_overlay_opacity'    => array( 'unit' => 'px', 'size' => $opacity, 'sizes' => array() ),
	);
}

function erk_heading( $text, $tag = 'h2', $align = '', $light = false ) {
	$s = array( 'title' => $text, 'header_size' => $tag );
	if ( $align ) { $s['align'] = $align; }
	if ( $light ) { $s['title_color'] = '#FFFFFF'; }
	return erk_w( 'heading', $s );
}

function erk_eyebrow( $text, $align = '', $light = false ) {
	$s = array( 'title' => $text, 'header_size' => 'p', '_css_classes' => 'erk-eyebrow', 'title_color' => $light ? '#C9A24A' : '#A8842F' );
	if ( $align ) { $s['align'] = $align; }
	return erk_w( 'heading', $s );
}

function erk_text( $html, $align = '', $light = false ) {
	$s = array( 'editor' => $html );
	if ( $align ) { $s['align'] = $align; }
	if ( $light ) { $s['text_color'] = '#EEF3EF'; }
	return erk_w( 'text-editor', $s );
}

function erk_p( $key ) {
	return '<p>' . wp_kses( elroi_t( $key ), elroi_allowed_html() ) . '</p>';
}

function erk_button( $text, $url, $style = 'gold', $align = '', $inline = false ) {
	$styles = array(
		'gold'    => array( '#C9A24A', '#143528', '#C9A24A', '#A8842F', '#FFFFFF' ),
		'green'   => array( '#1F4D3A', '#FFFFFF', '#1F4D3A', '#143528', '#FFFFFF' ),
		'ghost'   => array( 'rgba(0,0,0,0)', '#FFFFFF', '#FFFFFF', '#FFFFFF', '#143528' ),
		'outline' => array( 'rgba(0,0,0,0)', '#1F4D3A', '#1F4D3A', '#1F4D3A', '#FFFFFF' ),
	);
	$c = $styles[ $style ];
	$s = array(
		'text' => $text,
		'link' => array( 'url' => $url, 'is_external' => '', 'nofollow' => '' ),
		'background_color' => $c[0], 'button_text_color' => $c[1],
		'border_border' => 'solid', 'border_width' => array( 'unit' => 'px', 'top' => '2', 'right' => '2', 'bottom' => '2', 'left' => '2', 'isLinked' => true ),
		'border_color' => $c[2],
		'border_radius' => array( 'unit' => 'px', 'top' => '999', 'right' => '999', 'bottom' => '999', 'left' => '999', 'isLinked' => true ),
		'button_background_hover_color' => $c[3], 'hover_color' => $c[4], 'button_hover_border_color' => $c[3],
		'text_padding' => array( 'unit' => 'px', 'top' => '14', 'right' => '28', 'bottom' => '14', 'left' => '28', 'isLinked' => false ),
		'_css_classes' => 'erk-btn',
	);
	if ( $align ) { $s['align'] = $align; }
	if ( $inline ) {
		$s['_element_width'] = 'auto';
		$s['_margin'] = array( 'unit' => 'px', 'top' => '0', 'right' => '12', 'bottom' => '12', 'left' => '0', 'isLinked' => false );
	}
	return erk_w( 'button', $s );
}

function erk_image( $key, $alt = '', $class = 'erk-rounded' ) {
	return erk_w( 'image', array(
		'image'        => array( 'url' => elroi_img( $key, 1200 ), 'id' => '', 'alt' => $alt, 'source' => 'library' ),
		'image_size'   => 'full',
		'_css_classes' => $class,
	) );
}

function erk_icon_list( $items, $icon = 'fas fa-check' ) {
	$list = array();
	foreach ( $items as $t ) {
		$list[] = array( '_id' => erk_id(), 'text' => wp_strip_all_tags( $t ), 'selected_icon' => array( 'value' => $icon, 'library' => 'fa-solid' ) );
	}
	return erk_w( 'icon-list', array( 'icon_list' => $list, 'icon_color' => '#A8842F', 'space_between' => array( 'unit' => 'px', 'size' => 10, 'sizes' => array() ) ) );
}

function erk_icon_box( $icon, $title, $text ) {
	return erk_w( 'icon-box', array(
		'selected_icon'    => array( 'value' => $icon, 'library' => 'fa-solid' ),
		'title_text'       => $title,
		'description_text' => $text,
		'position'         => 'top',
		'title_size'       => 'h3',
		'primary_color'    => '#1F4D3A',
		'_css_classes'     => 'erk-card',
	) );
}

function erk_spacer( $px = 24 ) {
	return erk_w( 'spacer', array( 'space' => array( 'unit' => 'px', 'size' => $px, 'sizes' => array() ) ) );
}

function erk_erk( $name, $settings = array() ) { return erk_w( $name, $settings ); }

/** Centered section heading block. */
function erk_head( $eyebrow, $title, $text = '', $light = false ) {
	$w = array();
	if ( $eyebrow ) { $w[] = erk_eyebrow( $eyebrow, 'center', $light ); }
	$w[] = erk_heading( $title, 'h2', 'center', $light );
	if ( $text ) { $w[] = erk_text( '<p>' . $text . '</p>', 'center', $light ); }
	$w[] = erk_spacer( 20 );
	return $w;
}

/** Inner page banner. */
function erk_banner( $page, $title_key, $sub_key ) {
	return erk_sec(
		array( erk_col( 100, array(
			erk_heading( elroi_t( $title_key ), 'h1', 'center', true ),
			erk_text( '<p>' . esc_html( elroi_t( $sub_key ) ) . '</p>', 'center', true ),
		) ) ),
		array_merge( erk_bg( elroi_banner( $page ), 0.75 ), array( 'padding' => erk_pad( 110, 90 ) ) )
	);
}

/* ---------------- Pages ---------------- */
function elroi_elementor_layout( $slug ) {
	$url = function ( $s ) { return elroi_page_url( $s ); };
	switch ( $slug ) {

		case 'home':
			$rooms = array_values( elroi_rooms() );
			return array(
				erk_sec( array( erk_col( 100, array(
					erk_eyebrow( elroi_t( 'home_eyebrow' ), '', true ),
					erk_heading( elroi_opt( 'motel_name' ), 'h1', '', true ),
					erk_text( erk_p( 'home_lead' ), '', true ),
					erk_spacer( 10 ),
					erk_button( elroi_t( 'home_btn' ), $url( 'rooms' ), 'gold', '', true ),
					erk_button( 'Call ' . elroi_opt( 'phone' ), elroi_tel_url(), 'ghost', '', true ),
					erk_spacer( 30 ),
					erk_erk( 'erk-quick-book' ),
				) ) ), array_merge( erk_bg( 'hero', 0.72 ), array( 'height' => 'min-height', 'custom_height' => array( 'unit' => 'vh', 'size' => 88, 'sizes' => array() ), 'padding' => erk_pad( 100, 60 ) ) ) ),

				erk_sec( array(
					erk_col( 50, array( erk_image( 'exterior', 'The motel building' ) ) ),
					erk_col( 50, array(
						erk_eyebrow( elroi_t( 'home_about_eyebrow' ) ),
						erk_heading( elroi_t( 'home_about_title' ) ),
						erk_text( erk_p( 'home_about_p1' ) . erk_p( 'home_about_p2' ) ),
						erk_icon_list( elroi_lines( 'home_about_points' ) ),
						erk_spacer( 10 ),
						erk_button( 'Read our story', $url( 'about' ), 'green' ),
					) ),
				) ),

				erk_sec( array( erk_col( 100, array_merge(
					erk_head( elroi_t( 'home_rooms_eyebrow' ), elroi_t( 'home_rooms_title' ), esc_html( elroi_t( 'home_rooms_text' ) ) ),
					array( erk_erk( 'erk-rooms', array( 'limit' => 0, 'columns' => '4' ) ) )
				) ) ), erk_tint() ),

				erk_sec( array( erk_col( 100, array_merge(
					erk_head( elroi_t( 'home_amen_eyebrow' ), elroi_t( 'home_amen_title' ) ),
					array( erk_erk( 'erk-amenities', array( 'source' => 'home', 'columns' => '4' ) ), erk_spacer( 20 ), erk_button( 'All amenities', $url( 'amenities' ), 'outline', 'center' ) )
				) ) ) ),

				erk_sec( array(
					erk_col( 60, array(
						erk_eyebrow( elroi_t( 'home_dining_eyebrow' ), '', true ),
						erk_heading( elroi_t( 'home_dining_title' ), 'h2', '', true ),
						erk_text( erk_p( 'home_dining_text' ), '', true ),
						erk_button( 'See the menu', $url( 'dining' ), 'gold' ),
					) ),
					erk_col( 40, array() ),
				), array_merge( erk_bg( 'restaurant', 0.78 ), array( 'padding' => erk_pad( 110, 110 ) ) ) ),

				erk_sec( array( erk_col( 100, array_merge(
					erk_head( elroi_t( 'home_explore_eyebrow' ), elroi_t( 'home_explore_title' ) ),
					array( erk_erk( 'erk-places', array( 'layout' => 'cards', 'limit' => 4 ) ), erk_spacer( 20 ), erk_button( 'Things to do near us', $url( 'explore' ), 'outline', 'center' ) )
				) ) ) ),

				erk_sec( array( erk_col( 100, array_merge(
					erk_head( elroi_t( 'home_gallery_eyebrow' ), elroi_t( 'home_gallery_title' ) ),
					array( erk_erk( 'erk-gallery', array( 'style' => 'mosaic' ) ), erk_spacer( 20 ), erk_button( 'Open full gallery', $url( 'gallery' ), 'outline', 'center' ) )
				) ) ), erk_tint() ),

				erk_sec( array(
					erk_col( 40, array(
						erk_eyebrow( elroi_t( 'home_find_eyebrow' ) ),
						erk_heading( elroi_t( 'home_find_title' ) ),
						erk_erk( 'erk-contact-info', array( 'style' => 'list' ) ),
						erk_button( 'Get directions', $url( 'contact' ), 'green' ),
					) ),
					erk_col( 60, array( erk_erk( 'erk-map', array( 'height' => 400 ) ) ) ),
				) ),
			);

		case 'about':
			$stats = array();
			foreach ( elroi_lines( 'about_stats' ) as $l ) {
				$p = array_map( 'trim', explode( '|', $l, 2 ) );
				$stats[] = array( '_id' => erk_id(), 'number' => $p[0], 'label' => isset( $p[1] ) ? $p[1] : '' );
			}
			return array(
				erk_banner( 'about', 'about_hero_title', 'about_hero_sub' ),
				erk_sec( array(
					erk_col( 50, array(
						erk_eyebrow( elroi_t( 'about_eyebrow' ) ),
						erk_heading( elroi_t( 'about_title' ) ),
						erk_text( erk_p( 'about_p1' ) . erk_p( 'about_p2' ) . erk_p( 'about_p3' ) ),
					) ),
					erk_col( 50, array( erk_image( 'reception', 'Reception' ) ) ),
				) ),
				erk_sec( array(
					erk_col( 33.333, array( erk_icon_box( 'fas fa-star', 'Our mission', elroi_t( 'about_mission' ) ) ) ),
					erk_col( 33.333, array( erk_icon_box( 'fas fa-eye', 'Our vision', elroi_t( 'about_vision' ) ) ) ),
					erk_col( 33.333, array( erk_icon_box( 'fas fa-heart', 'Our values', elroi_t( 'about_values' ) ) ) ),
				), array_merge( erk_tint(), array( 'content_position' => 'top' ) ) ),
				erk_sec( array( erk_col( 100, array( erk_erk( 'erk-stats', array( 'items' => $stats ) ) ) ) ) ),
				erk_sec( array(
					erk_col( 50, array( erk_image( 'terrace', 'Terrace' ) ) ),
					erk_col( 50, array(
						erk_eyebrow( elroi_t( 'about_why_eyebrow' ) ),
						erk_heading( elroi_t( 'about_why_title' ) ),
						erk_icon_list( elroi_lines( 'about_why_points' ) ),
						erk_spacer( 10 ),
						erk_button( 'View rooms', $url( 'rooms' ), 'green' ),
					) ),
				), erk_tint() ),
			);

		case 'rooms':
			return array(
				erk_banner( 'rooms', 'rooms_hero_title', 'rooms_hero_sub' ),
				erk_sec( array( erk_col( 100, array(
					erk_text( erk_p( 'rooms_intro' ), 'center' ),
					erk_spacer( 10 ),
					erk_erk( 'erk-room-details', array( 'compare' => 'yes' ) ),
				) ) ) ),
			);

		case 'amenities':
			$secs = array(
				erk_banner( 'amenities', 'amen_hero_title', 'amen_hero_sub' ),
				erk_sec( array( erk_col( 100, array( erk_erk( 'erk-amenities', array( 'source' => 'all', 'columns' => '4' ) ) ) ) ) ),
			);
			$imgs = array( 1 => 'wifi', 2 => 'parking', 3 => 'gym', 4 => 'terrace' );
			foreach ( $imgs as $n => $img ) {
				$media = erk_col( 50, array( erk_image( $img, elroi_t( 'amen_f' . $n . '_title' ) ) ) );
				$text  = erk_col( 50, array( erk_heading( elroi_t( 'amen_f' . $n . '_title' ) ), erk_text( erk_p( 'amen_f' . $n . '_text' ) ) ) );
				$secs[] = erk_sec( 0 === $n % 2 ? array( $text, $media ) : array( $media, $text ), 1 === $n % 2 ? erk_tint() : array() );
			}
			$list  = elroi_lines( 'amen_room_list' );
			$half  = (int) ceil( count( $list ) / 2 );
			$secs[] = erk_sec( array(
				erk_col( 100, array( erk_heading( 'In every room', 'h2', 'center' ) ) ),
			), array( 'padding' => erk_pad( 80, 10 ) ) );
			$secs[] = erk_sec( array(
				erk_col( 50, array( erk_icon_list( array_slice( $list, 0, $half ) ) ) ),
				erk_col( 50, array( erk_icon_list( array_slice( $list, $half ) ) ) ),
			), array( 'padding' => erk_pad( 10, 80 ), 'content_width' => array( 'unit' => 'px', 'size' => 820, 'sizes' => array() ), 'content_position' => 'top' ) );
			return $secs;

		case 'dining':
			$hours = array();
			foreach ( elroi_lines( 'dining_hours' ) as $l ) {
				$p = array_map( 'trim', explode( '|', $l, 2 ) );
				$hours[] = array( '_id' => erk_id(), 'label' => $p[0], 'time' => isset( $p[1] ) ? $p[1] : '' );
			}
			return array(
				erk_banner( 'dining', 'dining_hero_title', 'dining_hero_sub' ),
				erk_sec( array(
					erk_col( 50, array(
						erk_eyebrow( elroi_t( 'dining_eyebrow' ) ),
						erk_heading( elroi_t( 'dining_title' ) ),
						erk_text( erk_p( 'dining_intro' ) ),
						erk_erk( 'erk-hours', array( 'items' => $hours ) ),
					) ),
					erk_col( 50, array( erk_image( 'dining-room', 'Dining room' ) ) ),
				) ),
				erk_sec( array( erk_col( 100, array_merge(
					erk_head( 'Menu', elroi_t( 'dining_menu_title' ), esc_html( elroi_t( 'dining_menu_note' ) ) ),
					array( erk_erk( 'erk-menu' ) )
				) ) ), erk_tint() ),
				erk_sec( array(
					erk_col( 50, array( erk_image( 'bar', 'Bar' ) ) ),
					erk_col( 50, array(
						erk_eyebrow( 'The bar' ),
						erk_heading( elroi_t( 'bar_title' ) ),
						erk_text( erk_p( 'bar_text' ) ),
						erk_button( 'Stay with us', $url( 'booking' ), 'green' ),
					) ),
				) ),
				erk_sec( array(
					erk_col( 33.333, array( erk_image( 'breakfast2', 'Breakfast table', 'erk-rounded erk-square' ) ) ),
					erk_col( 33.333, array( erk_image( 'coffee', 'Fresh coffee', 'erk-rounded erk-square' ) ) ),
					erk_col( 33.333, array( erk_image( 'restaurant', 'Restaurant', 'erk-rounded erk-square' ) ) ),
				), erk_tint() ),
			);

		case 'gallery':
			return array(
				erk_banner( 'gallery', 'gallery_hero_title', 'gallery_hero_sub' ),
				erk_sec( array( erk_col( 100, array( erk_erk( 'erk-gallery', array( 'style' => 'grid', 'filters' => 'yes', 'limit' => 0 ) ) ) ) ) ),
			);

		case 'explore':
			$icons = array( 1 => 'fas fa-bus', 2 => 'fas fa-binoculars', 3 => 'fas fa-cloud-sun' );
			$tips  = array();
			foreach ( $icons as $n => $ic ) {
				$tips[] = erk_col( 33.333, array( erk_icon_box( $ic, elroi_t( 'tip' . $n . '_title' ), elroi_t( 'tip' . $n . '_text' ) ) ) );
			}
			return array(
				erk_banner( 'explore', 'explore_hero_title', 'explore_hero_sub' ),
				erk_sec( array( erk_col( 100, array(
					erk_text( erk_p( 'explore_intro' ), 'center' ),
					erk_spacer( 10 ),
					erk_erk( 'erk-places', array( 'layout' => 'rows', 'limit' => 0 ) ),
				) ) ) ),
				erk_sec( array( erk_col( 100, erk_head( '', 'Travel tips' ) ) ), array_merge( erk_tint(), array( 'padding' => erk_pad( 80, 0 ) ) ) ),
				erk_sec( $tips, array_merge( erk_tint(), array( 'padding' => erk_pad( 0, 80 ), 'content_position' => 'top' ) ) ),
			);

		case 'booking':
			return array(
				erk_banner( 'booking', 'booking_hero_title', 'booking_hero_sub' ),
				erk_sec( array( erk_col( 100, array( erk_erk( 'erk-booking', array( 'sidebar' => 'yes', 'button' => 'Send Booking Request' ) ) ) ) ) ),
			);

		case 'contact':
			return array(
				erk_banner( 'contact', 'contact_hero_title', 'contact_hero_sub' ),
				erk_sec( array( erk_col( 100, array( erk_erk( 'erk-contact-info', array( 'style' => 'cards' ) ) ) ) ) ),
				erk_sec( array(
					erk_col( 50, array( erk_heading( 'Send us a message' ), erk_erk( 'erk-contact-form', array( 'button' => 'Send Message' ) ) ) ),
					erk_col( 50, array(
						erk_heading( elroi_t( 'contact_find_title' ) ),
						erk_text( erk_p( 'contact_find_text' ) ),
						erk_icon_list( elroi_lines( 'contact_tips' ) ),
						erk_erk( 'erk-map', array( 'height' => 340 ) ),
					) ),
				), array_merge( erk_tint(), array( 'content_position' => 'top' ) ) ),
			);

		case 'faq':
			return array(
				erk_banner( 'faq', 'faq_hero_title', 'faq_hero_sub' ),
				erk_sec( array( erk_col( 100, array(
					erk_erk( 'erk-faq' ),
					erk_spacer( 20 ),
					erk_text( '<p>Still have a question? <a href="' . esc_url( $url( 'contact' ) ) . '">Contact us</a> or <a href="' . esc_url( elroi_whatsapp_url() ) . '" target="_blank">chat on WhatsApp</a>.</p>', 'center' ),
				) ) ), array( 'content_width' => array( 'unit' => 'px', 'size' => 820, 'sizes' => array() ) ) ),
			);
	}
	return array();
}
