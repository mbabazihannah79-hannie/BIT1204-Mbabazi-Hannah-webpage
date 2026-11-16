<?php
/**
 * Elementor support.
 * - Registers the "El Roi Khadoshi Motel" widget category and the ERK widgets.
 * - Builds every site page as an editable Elementor design (automatically, once).
 * - Motel Content > Elementor Pages: rebuild / reset any page to the original design.
 * - Pages built with Elementor show the theme header & footer with the Elementor content in between.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function elroi_elementor_active() {
	return did_action( 'elementor/loaded' ) && class_exists( '\Elementor\Plugin' );
}

/* ---------------- Widgets ---------------- */
add_action( 'elementor/elements/categories_registered', function ( $manager ) {
	$manager->add_category( 'erk-motel', array( 'title' => 'El Roi Khadoshi Motel', 'icon' => 'eicon-site-identity' ) );
} );

add_action( 'elementor/widgets/register', function ( $widgets_manager ) {
	require_once __DIR__ . '/widgets.php';
	foreach ( elroi_widget_classes() as $class ) {
		$widgets_manager->register( new $class() );
	}
} );

// Theme styles and scripts inside the Elementor editor preview too.
add_action( 'elementor/preview/enqueue_styles', 'elroi_assets' );

/* ---------------- Rendering ---------------- */
function elroi_is_elementor_page( $post_id = 0 ) {
	if ( ! elroi_elementor_active() ) { return false; }
	$post_id = $post_id ? $post_id : get_queried_object_id();
	if ( ! $post_id ) { return false; }
	if ( isset( $_GET['elementor-preview'] ) && (int) $_GET['elementor-preview'] === (int) $post_id ) { return true; } // phpcs:ignore
	$doc = \Elementor\Plugin::$instance->documents->get( $post_id );
	return $doc && $doc->is_built_with_elementor();
}

/**
 * Called at the top of page templates. If the page is built with Elementor,
 * prints header + Elementor content + footer and returns true.
 */
function elroi_maybe_render_elementor() {
	if ( ! is_singular() || ! elroi_is_elementor_page() ) { return false; }
	get_header();
	while ( have_posts() ) {
		the_post();
		echo '<div class="erk-elementor-page">';
		the_content();
		echo '</div>';
	}
	get_footer();
	return true;
}

/* ---------------- Building pages ---------------- */
function elroi_elementor_build_page( $slug, $overwrite = false ) {
	if ( ! elroi_elementor_active() ) { return false; }
	require_once __DIR__ . '/layouts.php';
	$page = get_page_by_path( $slug );
	if ( ! $page ) { return false; }
	if ( ! $overwrite && get_post_meta( $page->ID, '_elementor_edit_mode', true ) === 'builder' ) { return false; }
	$data = elroi_elementor_layout( $slug );
	if ( ! $data ) { return false; }

	// Keep a copy of the previous design so a reset can be undone from Elementor's History/Revisions.
	update_post_meta( $page->ID, '_elementor_data', wp_slash( wp_json_encode( $data ) ) );
	update_post_meta( $page->ID, '_elementor_edit_mode', 'builder' );
	update_post_meta( $page->ID, '_elementor_template_type', 'wp-page' );
	update_post_meta( $page->ID, '_elementor_version', defined( 'ELEMENTOR_VERSION' ) ? ELEMENTOR_VERSION : '3.0.0' );
	update_post_meta( $page->ID, '_wp_page_template', 'default' );
	delete_post_meta( $page->ID, '_elementor_css' );
	delete_post_meta( $page->ID, '_elementor_element_cache' );
	return true;
}

function elroi_elementor_build_all( $overwrite = false ) {
	$built = 0;
	foreach ( array_keys( elroi_site_pages() ) as $slug ) {
		if ( elroi_elementor_build_page( $slug, $overwrite ) ) { $built++; }
	}
	elroi_elementor_brand_kit();
	if ( elroi_elementor_active() && isset( \Elementor\Plugin::$instance->files_manager ) ) {
		\Elementor\Plugin::$instance->files_manager->clear_cache();
	}
	return $built;
}

/** Brand colours and fonts in Elementor's Global Colors / Global Fonts, and let the theme styles show. */
function elroi_elementor_brand_kit() {
	update_option( 'elementor_disable_color_schemes', 'yes' );
	update_option( 'elementor_disable_typography_schemes', 'yes' );
	$kit_id = (int) get_option( 'elementor_active_kit' );
	if ( ! $kit_id ) { return; }
	$settings = get_post_meta( $kit_id, '_elementor_page_settings', true );
	$settings = is_array( $settings ) ? $settings : array();
	$settings['system_colors'] = array(
		array( '_id' => 'primary', 'title' => 'Primary (green)', 'color' => '#1F4D3A' ),
		array( '_id' => 'secondary', 'title' => 'Secondary (gold)', 'color' => '#C9A24A' ),
		array( '_id' => 'text', 'title' => 'Text', 'color' => '#1D2320' ),
		array( '_id' => 'accent', 'title' => 'Accent (gold)', 'color' => '#C9A24A' ),
	);
	$settings['custom_colors'] = array(
		array( '_id' => 'erkdark', 'title' => 'Dark green', 'color' => '#143528' ),
		array( '_id' => 'erkcream', 'title' => 'Cream', 'color' => '#F7F3EA' ),
		array( '_id' => 'erkgold2', 'title' => 'Dark gold', 'color' => '#A8842F' ),
	);
	$settings['system_typography'] = array(
		array( '_id' => 'primary', 'title' => 'Headings', 'typography_typography' => 'custom', 'typography_font_family' => 'Playfair Display', 'typography_font_weight' => '700' ),
		array( '_id' => 'secondary', 'title' => 'Sub headings', 'typography_typography' => 'custom', 'typography_font_family' => 'Inter', 'typography_font_weight' => '600' ),
		array( '_id' => 'text', 'title' => 'Body text', 'typography_typography' => 'custom', 'typography_font_family' => 'Inter', 'typography_font_weight' => '400' ),
		array( '_id' => 'accent', 'title' => 'Buttons', 'typography_typography' => 'custom', 'typography_font_family' => 'Inter', 'typography_font_weight' => '600' ),
	);
	$settings['container_width'] = array( 'unit' => 'px', 'size' => 1180, 'sizes' => array() );
	update_post_meta( $kit_id, '_elementor_page_settings', $settings );
}

/* First time Elementor is active: build all pages automatically. */
function elroi_elementor_auto_build() {
	if ( ! elroi_elementor_active() || get_option( 'elroi_elementor_built' ) ) { return; }
	if ( ! current_user_can( 'edit_pages' ) ) { return; }
	update_option( 'elroi_elementor_built', 1 );
	$n = elroi_elementor_build_all( false );
	set_transient( 'elroi_el_notice', $n, 120 );
}
add_action( 'admin_init', 'elroi_elementor_auto_build', 20 );
add_action( 'after_switch_theme', 'elroi_elementor_auto_build', 30 );

/* ---------------- Admin: Motel Content > Elementor Pages ---------------- */
add_action( 'admin_menu', function () {
	add_submenu_page( 'elroi-content', 'Elementor Pages', 'Elementor Pages', 'edit_pages', 'elroi-elementor', 'elroi_elementor_admin_page' );
}, 20 );

function elroi_elementor_admin_page() {
	echo '<div class="wrap"><h1>Elementor Pages</h1>';
	if ( ! elroi_elementor_active() ) {
		echo '<div class="notice notice-warning"><p><strong>Elementor is not active.</strong> Go to <a href="' . esc_url( admin_url( 'plugin-install.php?s=elementor&tab=search&type=term' ) ) . '">Plugins &rarr; Add New</a>, install and activate <strong>Elementor Website Builder</strong>, then come back here. The pages will be built for you automatically.</p></div></div>';
		return;
	}
	if ( isset( $_GET['done'] ) ) { // phpcs:ignore
		echo '<div class="notice notice-success is-dismissible"><p>Done. ' . intval( $_GET['done'] ) . ' page(s) rebuilt with the original design.</p></div>'; // phpcs:ignore
	}
	echo '<p>Every page of the website is an Elementor design. Click <strong>Edit with Elementor</strong>, then click any heading, paragraph, photo or button on the page to change it. Drag new widgets from the left panel; the motel widgets are in the <strong>El Roi Khadoshi Motel</strong> group.</p>';
	echo '<table class="widefat striped" style="max-width:900px"><thead><tr><th>Page</th><th>Status</th><th></th></tr></thead><tbody>';
	foreach ( elroi_site_pages() as $slug => $title ) {
		$page = get_page_by_path( $slug );
		if ( ! $page ) { continue; }
		$built = 'builder' === get_post_meta( $page->ID, '_elementor_edit_mode', true );
		$edit  = admin_url( 'post.php?post=' . $page->ID . '&action=elementor' );
		$reset = wp_nonce_url( admin_url( 'admin-post.php?action=elroi_el_reset&page_slug=' . $slug ), 'elroi_el_reset' );
		echo '<tr><td><strong>' . esc_html( get_the_title( $page ) ) . '</strong></td><td>' . ( $built ? '✅ Elementor' : '— Theme layout' ) . '</td><td>';
		echo '<a class="button button-primary" href="' . esc_url( $edit ) . '">Edit with Elementor</a> ';
		echo '<a class="button" href="' . esc_url( get_permalink( $page ) ) . '" target="_blank">View</a> ';
		echo '<a class="button" href="' . esc_url( $reset ) . '" onclick="return confirm(\'Replace this page with the original design? Changes made in Elementor will be lost (you can still restore them from Elementor > Revisions).\')">Reset to original design</a>';
		echo '</td></tr>';
	}
	echo '</tbody></table>';
	$all = wp_nonce_url( admin_url( 'admin-post.php?action=elroi_el_reset&page_slug=all' ), 'elroi_el_reset' );
	echo '<p style="margin-top:20px"><a class="button" href="' . esc_url( $all ) . '" onclick="return confirm(\'Reset ALL pages to the original design?\')">Reset all pages</a></p>';
	echo '<p class="description">After moving the site to a new address (hosting), use <strong>Elementor &rarr; Tools &rarr; Replace URL</strong> to update links inside the pages.</p></div>';
}

add_action( 'admin_post_elroi_el_reset', function () {
	check_admin_referer( 'elroi_el_reset' );
	if ( ! current_user_can( 'edit_pages' ) ) { wp_die( 'Not allowed' ); }
	$slug = isset( $_GET['page_slug'] ) ? sanitize_key( wp_unslash( $_GET['page_slug'] ) ) : '';
	if ( 'all' === $slug ) {
		$n = elroi_elementor_build_all( true );
	} else {
		$n = elroi_elementor_build_page( $slug, true ) ? 1 : 0;
		if ( elroi_elementor_active() ) { \Elementor\Plugin::$instance->files_manager->clear_cache(); }
	}
	wp_safe_redirect( admin_url( 'admin.php?page=elroi-elementor&done=' . $n ) );
	exit;
} );

add_action( 'admin_notices', function () {
	$n = get_transient( 'elroi_el_notice' );
	if ( false === $n ) { return; }
	delete_transient( 'elroi_el_notice' );
	echo '<div class="notice notice-success is-dismissible"><p><strong>Elementor is ready.</strong> ' . intval( $n ) . ' pages were built as Elementor designs. Open <a href="' . esc_url( admin_url( 'admin.php?page=elroi-elementor' ) ) . '">Motel Content &rarr; Elementor Pages</a> or click <em>Edit with Elementor</em> on any page.</p></div>';
} );

/* Without Elementor: a gentle reminder on the Motel Content screen. */
add_action( 'admin_notices', function () {
	$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
	if ( elroi_elementor_active() || ! $screen || false === strpos( $screen->id, 'elroi-content' ) ) { return; }
	echo '<div class="notice notice-info"><p>Want to edit pages visually by clicking on them? Install the free <strong>Elementor</strong> plugin (Plugins &rarr; Add New &rarr; search "Elementor"). This theme will build all pages for it automatically.</p></div>';
} );
