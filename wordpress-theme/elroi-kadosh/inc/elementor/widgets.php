<?php
/**
 * ERK widgets for Elementor. They appear in the Elementor panel under "El Roi Khadoshi Motel".
 * Loaded only when Elementor is active.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Widget_Base;

abstract class ERK_Widget extends Widget_Base {
	protected $erk_title = '';
	protected $erk_icon  = 'eicon-site-identity';
	protected $erk_note  = ''; // Where the data is edited.

	public function get_title() { return 'ERK ' . $this->erk_title; }
	public function get_icon() { return $this->erk_icon; }
	public function get_categories() { return array( 'erk-motel' ); }
	public function get_keywords() { return array( 'erk', 'motel', 'khadoshi', strtolower( $this->erk_title ) ); }

	protected function erk_start( $label = 'Settings' ) {
		$this->start_controls_section( 'erk_section', array( 'label' => $label ) );
		if ( $this->erk_note ) {
			$this->add_control( 'erk_note', array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => wp_kses_post( $this->erk_note ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			) );
		}
	}

	protected function erk_link( $label, $url ) {
		return ' <a href="' . esc_url( admin_url( $url ) ) . '" target="_blank">' . esc_html( $label ) . ' &rarr;</a>';
	}

	protected function erk_columns_control( $default = '4' ) {
		$this->add_control( 'columns', array(
			'label'   => 'Columns',
			'type'    => Controls_Manager::SELECT,
			'default' => $default,
			'options' => array( '2' => '2', '3' => '3', '4' => '4' ),
		) );
	}
}

class ERK_Rooms_Widget extends ERK_Widget {
	protected $erk_title = 'Room Cards';
	protected $erk_icon  = 'eicon-posts-grid';
	public function get_name() { return 'erk-rooms'; }
	protected function register_controls() {
		$this->erk_note = 'Room names, prices and photos come from <strong>Motel Content &rarr; Rooms</strong>.' . $this->erk_link( 'Edit rooms', 'edit.php?post_type=elroi_room' );
		$this->erk_start( 'Room Cards' );
		$this->add_control( 'limit', array( 'label' => 'How many rooms (0 = all)', 'type' => Controls_Manager::NUMBER, 'default' => 0, 'min' => 0 ) );
		$this->erk_columns_control( '4' );
		$this->end_controls_section();
	}
	protected function render() {
		$s = $this->get_settings_for_display();
		elroi_render_rooms_grid( (int) $s['limit'], (int) $s['columns'] );
	}
}

class ERK_Room_Details_Widget extends ERK_Widget {
	protected $erk_title = 'Room Details List';
	protected $erk_icon  = 'eicon-gallery-justified';
	public function get_name() { return 'erk-room-details'; }
	protected function register_controls() {
		$this->erk_note = 'Shows every room with photos, features and a Book button. Edit in <strong>Motel Content &rarr; Rooms</strong>.' . $this->erk_link( 'Edit rooms', 'edit.php?post_type=elroi_room' );
		$this->erk_start( 'Room Details' );
		$this->add_control( 'compare', array( 'label' => 'Show comparison table', 'type' => Controls_Manager::SWITCHER, 'default' => 'yes' ) );
		$this->end_controls_section();
	}
	protected function render() {
		$s = $this->get_settings_for_display();
		elroi_render_room_details( 'yes' === $s['compare'] );
	}
}

class ERK_Quick_Book_Widget extends ERK_Widget {
	protected $erk_title = 'Check Availability Bar';
	protected $erk_icon  = 'eicon-date';
	public function get_name() { return 'erk-quick-book'; }
	protected function register_controls() {
		$this->erk_note = 'A small form that sends guests to the Book a Room page with their dates filled in.';
		$this->erk_start( 'Check Availability' );
		$this->add_control( 'button', array( 'label' => 'Button text', 'type' => Controls_Manager::TEXT, 'default' => 'Check Availability' ) );
		$this->end_controls_section();
	}
	protected function render() {
		$s = $this->get_settings_for_display();
		elroi_render_quick_book( $s['button'] );
	}
}

class ERK_Booking_Widget extends ERK_Widget {
	protected $erk_title = 'Booking Form';
	protected $erk_icon  = 'eicon-form-horizontal';
	public function get_name() { return 'erk-booking'; }
	protected function register_controls() {
		$this->erk_note = 'Bookings are saved in <strong>Bookings</strong> in the dashboard.' . $this->erk_link( 'View bookings', 'edit.php?post_type=elroi_booking' );
		$this->erk_start( 'Booking Form' );
		$this->add_control( 'sidebar', array( 'label' => 'Show rates & policies panel', 'type' => Controls_Manager::SWITCHER, 'default' => 'yes' ) );
		$this->add_control( 'button', array( 'label' => 'Button text', 'type' => Controls_Manager::TEXT, 'default' => 'Send Booking Request' ) );
		$this->end_controls_section();
	}
	protected function render() {
		$s = $this->get_settings_for_display();
		elroi_render_booking( 'yes' === $s['sidebar'], $s['button'] );
	}
}

class ERK_Amenities_Widget extends ERK_Widget {
	protected $erk_title = 'Amenities';
	protected $erk_icon  = 'eicon-icon-box';
	public function get_name() { return 'erk-amenities'; }
	protected function register_controls() {
		$this->erk_note = 'Edit the list in <strong>Motel Content &rarr; Amenities</strong>.' . $this->erk_link( 'Edit amenities', 'edit.php?post_type=elroi_amenity' );
		$this->erk_start( 'Amenities' );
		$this->add_control( 'source', array( 'label' => 'Show', 'type' => Controls_Manager::SELECT, 'default' => 'all',
			'options' => array( 'all' => 'All amenities', 'home' => 'Only those ticked "Show on Home page"' ) ) );
		$this->erk_columns_control( '4' );
		$this->end_controls_section();
	}
	protected function render() {
		$s = $this->get_settings_for_display();
		elroi_render_amenities( 'home' === $s['source'], (int) $s['columns'] );
	}
}

class ERK_Places_Widget extends ERK_Widget {
	protected $erk_title = 'Places to Visit';
	protected $erk_icon  = 'eicon-google-maps';
	public function get_name() { return 'erk-places'; }
	protected function register_controls() {
		$this->erk_note = 'Edit places in <strong>Motel Content &rarr; Places to Visit</strong>.' . $this->erk_link( 'Edit places', 'edit.php?post_type=elroi_place' );
		$this->erk_start( 'Places' );
		$this->add_control( 'layout', array( 'label' => 'Layout', 'type' => Controls_Manager::SELECT, 'default' => 'cards',
			'options' => array( 'cards' => 'Photo cards', 'rows' => 'Photo + text rows' ) ) );
		$this->add_control( 'limit', array( 'label' => 'How many (0 = all)', 'type' => Controls_Manager::NUMBER, 'default' => 0, 'min' => 0 ) );
		$this->end_controls_section();
	}
	protected function render() {
		$s = $this->get_settings_for_display();
		elroi_render_places( $s['layout'], (int) $s['limit'] );
	}
}

class ERK_Menu_Widget extends ERK_Widget {
	protected $erk_title = 'Restaurant Menu';
	protected $erk_icon  = 'eicon-price-list';
	public function get_name() { return 'erk-menu'; }
	protected function register_controls() {
		$this->erk_note = 'Dishes and prices come from <strong>Motel Content &rarr; Restaurant Menu</strong>.' . $this->erk_link( 'Edit menu', 'edit.php?post_type=elroi_dish' );
		$this->erk_start( 'Restaurant Menu' );
		$this->end_controls_section();
	}
	protected function render() { elroi_render_menu(); }
}

class ERK_Gallery_Widget extends ERK_Widget {
	protected $erk_title = 'Photo Gallery';
	protected $erk_icon  = 'eicon-gallery-grid';
	public function get_name() { return 'erk-gallery'; }
	protected function register_controls() {
		$this->erk_note = 'Uses the room photos, place photos and Site Photos. Click a photo on the live site to open it full size.';
		$this->erk_start( 'Gallery' );
		$this->add_control( 'style', array( 'label' => 'Style', 'type' => Controls_Manager::SELECT, 'default' => 'grid',
			'options' => array( 'grid' => 'Grid with category buttons', 'mosaic' => '6-photo collage' ) ) );
		$this->add_control( 'filters', array( 'label' => 'Show category buttons', 'type' => Controls_Manager::SWITCHER, 'default' => 'yes', 'condition' => array( 'style' => 'grid' ) ) );
		$this->add_control( 'limit', array( 'label' => 'How many photos (0 = all)', 'type' => Controls_Manager::NUMBER, 'default' => 0, 'min' => 0, 'condition' => array( 'style' => 'grid' ) ) );
		$this->end_controls_section();
	}
	protected function render() {
		$s = $this->get_settings_for_display();
		elroi_render_gallery( $s['style'], 'yes' === $s['filters'], (int) $s['limit'] );
	}
}

class ERK_FAQ_Widget extends ERK_Widget {
	protected $erk_title = 'FAQs';
	protected $erk_icon  = 'eicon-accordion';
	public function get_name() { return 'erk-faq'; }
	protected function register_controls() {
		$this->erk_note = 'Questions and answers come from <strong>Motel Content &rarr; FAQs</strong>.' . $this->erk_link( 'Edit FAQs', 'edit.php?post_type=elroi_faq' );
		$this->erk_start( 'FAQs' );
		$this->end_controls_section();
	}
	protected function render() { elroi_render_faq(); }
}

class ERK_Map_Widget extends ERK_Widget {
	protected $erk_title = 'Motel Map';
	protected $erk_icon  = 'eicon-map-pin';
	public function get_name() { return 'erk-map'; }
	protected function register_controls() {
		$this->erk_note = 'The map location is set in <strong>Motel Content &rarr; Contacts &amp; Details</strong>.';
		$this->erk_start( 'Map' );
		$this->add_control( 'height', array( 'label' => 'Height (px)', 'type' => Controls_Manager::NUMBER, 'default' => 380, 'min' => 200, 'max' => 800 ) );
		$this->end_controls_section();
	}
	protected function render() {
		$s = $this->get_settings_for_display();
		elroi_render_map( (int) $s['height'] );
	}
}

class ERK_Contact_Info_Widget extends ERK_Widget {
	protected $erk_title = 'Contact Details';
	protected $erk_icon  = 'eicon-call-to-action';
	public function get_name() { return 'erk-contact-info'; }
	protected function register_controls() {
		$this->erk_note = 'Phone, WhatsApp, email and address are set in <strong>Motel Content &rarr; Contacts &amp; Details</strong>, so they stay the same on every page.';
		$this->erk_start( 'Contact Details' );
		$this->add_control( 'style', array( 'label' => 'Style', 'type' => Controls_Manager::SELECT, 'default' => 'cards',
			'options' => array( 'cards' => '4 cards', 'list' => 'Simple list' ) ) );
		$this->end_controls_section();
	}
	protected function render() {
		$s = $this->get_settings_for_display();
		'list' === $s['style'] ? elroi_render_contact_list() : elroi_render_contact_cards();
	}
}

class ERK_Contact_Form_Widget extends ERK_Widget {
	protected $erk_title = 'Contact Form';
	protected $erk_icon  = 'eicon-mail';
	public function get_name() { return 'erk-contact-form'; }
	protected function register_controls() {
		$this->erk_note = 'Messages are saved in <strong>Messages</strong> in the dashboard.' . $this->erk_link( 'View messages', 'edit.php?post_type=elroi_message' );
		$this->erk_start( 'Contact Form' );
		$this->add_control( 'button', array( 'label' => 'Button text', 'type' => Controls_Manager::TEXT, 'default' => 'Send Message' ) );
		$this->end_controls_section();
	}
	protected function render() {
		$s = $this->get_settings_for_display();
		elroi_render_contact_form( $s['button'] );
	}
}

class ERK_Stats_Widget extends ERK_Widget {
	protected $erk_title = 'Number Boxes';
	protected $erk_icon  = 'eicon-number-field';
	public function get_name() { return 'erk-stats'; }
	protected function register_controls() {
		$this->erk_start( 'Number Boxes' );
		$r = new Repeater();
		$r->add_control( 'number', array( 'label' => 'Number', 'type' => Controls_Manager::TEXT, 'default' => '8' ) );
		$r->add_control( 'label', array( 'label' => 'Label', 'type' => Controls_Manager::TEXT, 'default' => 'Rooms' ) );
		$this->add_control( 'items', array(
			'label' => 'Boxes', 'type' => Controls_Manager::REPEATER, 'fields' => $r->get_controls(), 'title_field' => '{{{ number }}} – {{{ label }}}',
			'default' => array(
				array( 'number' => '8', 'label' => 'Comfortable rooms' ),
				array( 'number' => '24/7', 'label' => 'Front desk' ),
				array( 'number' => '~25 km', 'label' => 'To Kibale National Park' ),
				array( 'number' => '100%', 'label' => 'Non-smoking' ),
			),
		) );
		$this->end_controls_section();
	}
	protected function render() {
		$s = $this->get_settings_for_display();
		echo '<div class="stats">';
		foreach ( (array) $s['items'] as $it ) {
			echo '<div><strong>' . esc_html( $it['number'] ) . '</strong><span>' . esc_html( $it['label'] ) . '</span></div>';
		}
		echo '</div>';
	}
}

class ERK_Hours_Widget extends ERK_Widget {
	protected $erk_title = 'Opening Hours';
	protected $erk_icon  = 'eicon-clock-o';
	public function get_name() { return 'erk-hours'; }
	protected function register_controls() {
		$this->erk_start( 'Opening Hours' );
		$r = new Repeater();
		$r->add_control( 'label', array( 'label' => 'Name', 'type' => Controls_Manager::TEXT, 'default' => 'Breakfast' ) );
		$r->add_control( 'time', array( 'label' => 'Time', 'type' => Controls_Manager::TEXT, 'default' => '6:30 AM – 10:30 AM' ) );
		$this->add_control( 'items', array(
			'label' => 'Rows', 'type' => Controls_Manager::REPEATER, 'fields' => $r->get_controls(), 'title_field' => '{{{ label }}}',
			'default' => array(
				array( 'label' => 'Breakfast', 'time' => '6:30 AM – 10:30 AM' ),
				array( 'label' => 'Lunch', 'time' => '12:30 PM – 3:30 PM' ),
				array( 'label' => 'Dinner', 'time' => '6:30 PM – 10:30 PM' ),
				array( 'label' => 'Bar', 'time' => 'Open daily until late' ),
				array( 'label' => 'Room service', 'time' => 'Available' ),
			),
		) );
		$this->end_controls_section();
	}
	protected function render() {
		$s = $this->get_settings_for_display();
		echo '<ul class="hours">';
		foreach ( (array) $s['items'] as $it ) {
			echo '<li><span>' . esc_html( $it['label'] ) . '</span><span>' . esc_html( $it['time'] ) . '</span></li>';
		}
		echo '</ul>';
	}
}

function elroi_widget_classes() {
	return array(
		'ERK_Rooms_Widget', 'ERK_Room_Details_Widget', 'ERK_Quick_Book_Widget', 'ERK_Booking_Widget', 'ERK_Amenities_Widget',
		'ERK_Places_Widget', 'ERK_Menu_Widget', 'ERK_Gallery_Widget', 'ERK_FAQ_Widget', 'ERK_Map_Widget',
		'ERK_Contact_Info_Widget', 'ERK_Contact_Form_Widget', 'ERK_Stats_Widget', 'ERK_Hours_Widget',
	);
}
