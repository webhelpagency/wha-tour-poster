<?php
/**
 * Plugin Name:       WHA Tours Core
 * Description:       Companion plugin for the WHA Tour Poster theme: tour post type, tour taxonomies, tour meta fields and the lead request form (shortcode and block).
 * Version:           1.0.0
 * Requires at least: 6.4
 * Requires PHP:      7.4
 * Author:            Web Help Agency
 * Author URI:        https://webhelpagency.com/
 * License:           GPLv2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       wha-tours-core
 *
 * @package WHA_Tours_Core
 */

defined( 'ABSPATH' ) || exit;

define( 'WHA_TOURS_CORE_VERSION', '1.0.0' );
define( 'WHA_TOURS_CORE_FILE', __FILE__ );
define( 'WHA_TOURS_CORE_PATH', plugin_dir_path( __FILE__ ) );
define( 'WHA_TOURS_CORE_URL', plugin_dir_url( __FILE__ ) );

/**
 * Load CMB2 (bundled through Composer) before anything else needs it.
 */
if ( ! defined( 'CMB2_LOADED' ) && file_exists( WHA_TOURS_CORE_PATH . 'vendor/cmb2/cmb2/init.php' ) ) {
	require_once WHA_TOURS_CORE_PATH . 'vendor/cmb2/cmb2/init.php';
}

/**
 * Drop the CMB2 script handles whose files this plugin does not distribute.
 *
 * `wp-color-picker-alpha`: the plugin uses no `colorpicker` fields, so that
 * script is never needed. CMB2 only registers it when the handle is present in
 * its dependency list (`CMB2_JS::enqueue()`), so dropping the handle here means
 * the missing file is never registered nor enqueued, even if another plugin
 * adds a colorpicker field with the `alpha` option to one of our metaboxes.
 *
 * `jquery-ui-datetimepicker`: CMB2 registers it from its bundled copy of
 * jQuery UI Timepicker Addon (v1.5.0), which only the `text_time` and
 * `text_datetime_timestamp` field types ask for. This plugin uses `text_date`
 * only, so the file is left out of the package and the handle is dropped for
 * the same reason as above.
 *
 * @param array $dependencies CMB2 script dependencies, keyed by handle.
 * @return array
 */
function wha_tours_core_cmb2_script_dependencies( $dependencies ) {
	unset( $dependencies['wp-color-picker-alpha'], $dependencies['jquery-ui-datetimepicker'] );

	return $dependencies;
}
add_filter( 'cmb2_script_dependencies', 'wha_tours_core_cmb2_script_dependencies' );

/**
 * Last-resort safety net for the `wp-color-picker-alpha` handle.
 *
 * `CMB2_Type_Colorpicker::dequeue_rgba_colorpicker_script()` enqueues the same
 * script directly when a third-party plugin has enqueued `jw-cmb2-rgba-picker-js`.
 * That path does not run through `cmb2_script_dependencies`, so the handle is
 * dropped again late in the enqueue cycle — but only when it points at the
 * bundled CMB2 copy, so a colour picker registered by another plugin under the
 * same handle keeps working.
 *
 * @return void
 */
function wha_tours_core_remove_colorpicker_alpha() {
	if ( ! wp_script_is( 'wp-color-picker-alpha', 'registered' ) ) {
		return;
	}

	$registered = wp_scripts()->registered['wp-color-picker-alpha'];
	$src        = isset( $registered->src ) ? (string) $registered->src : '';

	// Only drop the handle when it points at the file this plugin does not ship.
	if ( '' === $src || false === strpos( $src, WHA_TOURS_CORE_URL . 'vendor/cmb2/cmb2/js/' ) ) {
		return;
	}

	wp_dequeue_script( 'wp-color-picker-alpha' );
	wp_deregister_script( 'wp-color-picker-alpha' );
}
add_action( 'admin_enqueue_scripts', 'wha_tours_core_remove_colorpicker_alpha', 999 );
add_action( 'wp_enqueue_scripts', 'wha_tours_core_remove_colorpicker_alpha', 999 );

require_once WHA_TOURS_CORE_PATH . 'includes/post-types.php';
require_once WHA_TOURS_CORE_PATH . 'includes/taxonomies.php';
require_once WHA_TOURS_CORE_PATH . 'includes/meta.php';
require_once WHA_TOURS_CORE_PATH . 'includes/helpers.php';
require_once WHA_TOURS_CORE_PATH . 'includes/lead-form.php';
require_once WHA_TOURS_CORE_PATH . 'includes/admin.php';
require_once WHA_TOURS_CORE_PATH . 'includes/demo-import.php';

/**
 * Register the plugin assets so that block.json and the render callbacks can reference them.
 *
 * @return void
 */
function wha_tours_core_register_assets() {
	$style_path = WHA_TOURS_CORE_PATH . 'assets/css/wha-tours-core.css';
	$style_ver  = file_exists( $style_path ) ? (string) filemtime( $style_path ) : WHA_TOURS_CORE_VERSION;

	wp_register_style(
		'wha-tours-core',
		WHA_TOURS_CORE_URL . 'assets/css/wha-tours-core.css',
		array(),
		$style_ver
	);

	$script_path = WHA_TOURS_CORE_PATH . 'blocks/lead-form/index.js';
	$script_ver  = file_exists( $script_path ) ? (string) filemtime( $script_path ) : WHA_TOURS_CORE_VERSION;

	wp_register_script(
		'wha-tours-core-lead-form-editor',
		WHA_TOURS_CORE_URL . 'blocks/lead-form/index.js',
		array( 'wp-blocks', 'wp-element', 'wp-server-side-render', 'wp-i18n', 'wp-block-editor' ),
		$script_ver,
		true
	);

	if ( function_exists( 'wp_set_script_translations' ) ) {
		wp_set_script_translations( 'wha-tours-core-lead-form-editor', 'wha-tours-core' );
	}
}
add_action( 'init', 'wha_tours_core_register_assets', 5 );

/**
 * Plugin activation: register the content types, then flush the rewrite rules once.
 *
 * @return void
 */
function wha_tours_core_activate() {
	wha_tours_core_register_post_types();
	wha_tours_core_register_taxonomies();
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'wha_tours_core_activate' );

/**
 * Plugin deactivation: drop the rewrite rules the plugin added.
 *
 * @return void
 */
function wha_tours_core_deactivate() {
	unregister_post_type( 'tour' );
	flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'wha_tours_core_deactivate' );
