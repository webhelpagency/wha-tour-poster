<?php
/**
 * Uninstall routine.
 *
 * Only the options and transients created by this plugin are removed. Tours,
 * taxonomy terms, tour meta and stored leads are content and are always kept.
 *
 * @package WHA_Tours_Core
 */

defined( 'WP_UNINSTALL_PLUGIN' ) || exit;

/**
 * Delete the plugin options and transients of the current site.
 *
 * @return void
 */
function wha_tours_core_uninstall_site() {
	global $wpdb;

	// Options and transients created by this plugin, all prefixed with `wha_tours_core_`.
	$patterns = array(
		$wpdb->esc_like( 'wha_tours_core_' ) . '%',
		$wpdb->esc_like( '_transient_wha_tours_core_' ) . '%',
		$wpdb->esc_like( '_transient_timeout_wha_tours_core_' ) . '%',
	);

	foreach ( $patterns as $pattern ) {
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
		$option_names = $wpdb->get_col( $wpdb->prepare( "SELECT option_name FROM {$wpdb->options} WHERE option_name LIKE %s", $pattern ) );

		foreach ( (array) $option_names as $option_name ) {
			delete_option( $option_name );
		}
	}

	wp_cache_flush();
}

if ( is_multisite() ) {
	$wha_tours_core_sites = get_sites(
		array(
			'fields' => 'ids',
			'number' => 0,
		)
	);

	foreach ( $wha_tours_core_sites as $wha_tours_core_site_id ) {
		switch_to_blog( (int) $wha_tours_core_site_id );
		wha_tours_core_uninstall_site();
		restore_current_blog();
	}
} else {
	wha_tours_core_uninstall_site();
}
