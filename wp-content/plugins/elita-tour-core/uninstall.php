<?php
/**
 * Uninstall routine.
 *
 * Only the options and transients created by this plugin are removed. Tours,
 * taxonomy terms, tour meta and stored leads are content and are always kept.
 *
 * @package Elita_Tour_Core
 */

defined( 'WP_UNINSTALL_PLUGIN' ) || exit;

/**
 * Delete the plugin options and transients of the current site.
 *
 * @return void
 */
function elita_tour_core_uninstall_site() {
	global $wpdb;

	// Options and transients created by this plugin, all prefixed with `elita_tour_core_`.
	$patterns = array(
		$wpdb->esc_like( 'elita_tour_core_' ) . '%',
		$wpdb->esc_like( '_transient_elita_tour_core_' ) . '%',
		$wpdb->esc_like( '_transient_timeout_elita_tour_core_' ) . '%',
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
	$elita_tour_core_sites = get_sites(
		array(
			'fields' => 'ids',
			'number' => 0,
		)
	);

	foreach ( $elita_tour_core_sites as $elita_tour_core_site_id ) {
		switch_to_blog( (int) $elita_tour_core_site_id );
		elita_tour_core_uninstall_site();
		restore_current_blog();
	}
} else {
	elita_tour_core_uninstall_site();
}
