<?php
/**
 * El Roi Khadoshi Motel one-click site setup.
 *
 * Appearance → Motel setup. Builds or refreshes every page from the theme's page
 * designs, sets the homepage, switches to "Post name" permalinks and moves the
 * WordPress sample content to the trash.
 *
 * Safe to run again: once the site is built, pages are only replaced when the
 * reset box is ticked, so edits made in the block editor are kept.
 *
 * @package elroi-child
 */

defined( 'ABSPATH' ) || exit;

/**
 * Pages: slug => [ title, pattern slug ]. The slugs are the same as before, so old links keep working.
 */
function erk_setup_pages() {
	return array(
		'home'      => array( 'Home', 'elroi-child/page-home' ),
		'about'     => array( 'About Us', 'elroi-child/page-about' ),
		'rooms'     => array( 'Rooms', 'elroi-child/page-rooms' ),
		'amenities' => array( 'Amenities', 'elroi-child/page-amenities' ),
		'dining'    => array( 'Dining & Bar', 'elroi-child/page-dining' ),
		'gallery'   => array( 'Gallery', 'elroi-child/page-gallery' ),
		'explore'   => array( 'Explore Kyenjojo', 'elroi-child/page-explore' ),
		'booking'   => array( 'Book a Room', 'elroi-child/page-booking' ),
		'contact'   => array( 'Contact Us', 'elroi-child/page-contact' ),
		'faq'       => array( 'FAQ', 'elroi-child/page-faq' ),
	);
}

/**
 * Run the setup. Returns a list of what was done, for the report.
 *
 * @param bool $reset Replace pages that already exist with the original designs.
 */
function erk_run_site_setup( $reset = true ) {
	$log      = array();
	$registry = WP_Block_Patterns_Registry::get_instance();
	$ids      = array();

	if ( ! function_exists( 'erb_setting' ) ) {
		$log[] = 'Note: the El Roi Bookings plugin is not active, so the room cards and the booking and contact forms will be empty until it is activated (Plugins → El Roi Bookings → Activate).';
	}

	// 1. Pages.
	foreach ( erk_setup_pages() as $slug => $page ) {
		list( $title, $pattern ) = $page;

		$registered = $registry->get_registered( $pattern );
		if ( ! $registered ) {
			$log[] = "Skipped {$title}: design {$pattern} not found.";
			continue;
		}

		$existing = get_page_by_path( $slug );
		if ( $existing && ! $reset ) {
			$ids[ $slug ] = $existing->ID;
			$log[]        = "Kept page as edited: {$title}";
			continue;
		}

		$data = array(
			'post_title'   => $title,
			'post_name'    => $slug,
			'post_type'    => 'page',
			'post_status'  => 'publish',
			'post_content' => $registered['content'],
		);

		if ( $existing ) {
			$data['ID'] = $existing->ID;
			$id         = wp_update_post( wp_slash( $data ), true );
			$verb       = 'Refreshed';
		} else {
			$id   = wp_insert_post( wp_slash( $data ), true );
			$verb = 'Created';
		}

		if ( is_wp_error( $id ) ) {
			$log[] = "Could not save {$title}: " . $id->get_error_message();
			continue;
		}

		update_post_meta( $id, '_wp_page_template', 'page-no-title' );
		// Pages from the old Elementor version: hand them back to the block editor.
		delete_post_meta( $id, '_elementor_edit_mode' );
		delete_post_meta( $id, '_elementor_data' );
		delete_post_meta( $id, '_elementor_page_settings' );
		$ids[ $slug ] = $id;
		$log[]        = "{$verb} page: {$title}";
	}

	// 2. Homepage and site details.
	if ( isset( $ids['home'] ) ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $ids['home'] );
		$log[] = 'Set Home as the homepage';
	}
	update_option( 'blogname', ERK_NAME );
	update_option( 'blogdescription', ERK_TAGLINE );
	update_option( 'timezone_string', 'Africa/Kampala' );
	$log[] = 'Set site title, tagline and time zone (Africa/Kampala)';

	// 3. Clean addresses such as /rooms/.
	update_option( 'permalink_structure', '/%postname%/' );
	flush_rewrite_rules( true );
	$log[] = 'Switched permalinks to "Post name"';

	// 4. Move WordPress sample content to the trash (restorable from Trash).
	foreach ( array( array( 'sample-page', 'page' ), array( 'hello-world', 'post' ) ) as $sample ) {
		$post = get_page_by_path( $sample[0], OBJECT, $sample[1] );
		if ( $post && 'trash' !== $post->post_status ) {
			wp_trash_post( $post->ID );
			$log[] = 'Moved to trash: ' . $post->post_title;
		}
	}

	return $log;
}

/* -------------------------------------------------------------------------
 * Admin screen
 * ---------------------------------------------------------------------- */

add_action(
	'admin_menu',
	function () {
		add_theme_page( 'Motel setup', 'Motel setup', 'manage_options', 'erk-setup', 'erk_setup_screen' );
	}
);

add_action(
	'admin_post_erk_run_setup',
	function () {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You are not allowed to do this.', 'elroi-child' ), 403 );
		}
		check_admin_referer( 'erk_run_setup' );

		// Once the site is built, pages are only replaced when the reset box is ticked.
		$reset = ! get_option( 'erk_site_built' ) || ! empty( $_POST['erk_reset_pages'] );
		set_transient( 'erk_setup_log_' . get_current_user_id(), erk_run_site_setup( $reset ), 10 * MINUTE_IN_SECONDS );
		update_option( 'erk_site_built', time(), false );
		wp_safe_redirect( admin_url( 'themes.php?page=erk-setup&done=1' ) );
		exit;
	}
);

/** Point to the setup screen right after the theme is activated. */
add_action(
	'after_switch_theme',
	function () {
		set_transient( 'erk_activated', 1, 120 );
	}
);
add_action(
	'admin_notices',
	function () {
		if ( ! get_transient( 'erk_activated' ) || get_option( 'erk_site_built' ) ) {
			return;
		}
		delete_transient( 'erk_activated' );
		printf(
			'<div class="notice notice-success is-dismissible"><p><strong>El Roi Khadoshi Motel theme is active.</strong> Next: <a href="%s">Appearance → Motel setup → Build the site</a>.</p></div>',
			esc_url( admin_url( 'themes.php?page=erk-setup' ) )
		);
	}
);

function erk_setup_screen() {
	$log = get_transient( 'erk_setup_log_' . get_current_user_id() );
	?>
	<div class="wrap">
		<h1>Motel setup</h1>

		<?php if ( isset( $_GET['done'] ) && is_array( $log ) ) : // phpcs:ignore WordPress.Security.NonceVerification ?>
			<div class="notice notice-success"><p><strong>Setup finished.</strong> <a href="<?php echo esc_url( home_url( '/' ) ); ?>">View the site</a></p></div>
			<ul style="list-style:disc;padding-left:1.5em">
				<?php foreach ( $log as $line ) : ?>
					<li><?php echo esc_html( $line ); ?></li>
				<?php endforeach; ?>
			</ul>
			<?php delete_transient( 'erk_setup_log_' . get_current_user_id() ); ?>
		<?php endif; ?>

		<p>This builds the whole site from the theme's designs in one step:</p>
		<ul style="list-style:disc;padding-left:1.5em">
			<li>Creates the pages Home, About Us, Rooms, Amenities, Dining &amp; Bar, Gallery, Explore Kyenjojo, Book a Room, Contact Us and FAQ.</li>
			<li>Sets Home as the homepage, the site title, tagline, time zone and "Post name" permalinks.</li>
			<li>Moves the WordPress "Hello world!" post and "Sample Page" to the trash.</li>
		</ul>
		<p>After that, every page is edited in <strong>Pages</strong> with the block editor: click any heading, paragraph, photo or button to change it.
			The header and footer are in <strong>Appearance → Editor → Patterns → Header / Footer</strong>.
			Rooms, prices, bookings and messages are under <strong>Bookings</strong>.</p>
		<?php $built = get_option( 'erk_site_built' ); ?>
		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<input type="hidden" name="action" value="erk_run_setup">
			<?php wp_nonce_field( 'erk_run_setup' ); ?>
			<?php if ( $built ) : ?>
				<p>The site was built on <?php echo esc_html( wp_date( get_option( 'date_format' ), (int) $built ) ); ?>. Running it again is safe: it only adds pages that are missing and <strong>keeps every edit you have made</strong>.</p>
				<p style="padding:.75em 1em;background:#fcf0f1;border-left:4px solid #d63638">
					<label><input type="checkbox" name="erk_reset_pages" value="1"> <strong>Reset the pages to the original designs.</strong> This replaces the text and photos you changed on every page.</label>
				</p>
			<?php endif; ?>
			<?php submit_button( $built ? 'Run setup' : 'Build the site', 'primary', 'submit', false ); ?>
		</form>
	</div>
	<?php
}
