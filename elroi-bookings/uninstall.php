<?php
/**
 * Removes the module's tables and settings when the plugin is deleted
 * (Plugins → El Roi Bookings → Delete). Deactivating keeps everything.
 *
 * @package elroi-bookings
 */

defined( 'WP_UNINSTALL_PLUGIN' ) || exit;

global $wpdb;
foreach ( array( 'erb_messages', 'erb_bookings', 'erb_rooms' ) as $table ) {
	$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}{$table}" ); // phpcs:ignore WordPress.DB
}
delete_option( 'erb_settings' );
delete_option( 'erb_db_version' );
