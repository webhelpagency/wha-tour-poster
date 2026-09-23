<?php
/**
 * Plugin Name:       Elita Tour Core
 * Description:       Companion plugin for the Elita Tour theme: tour post type, tour taxonomies, tour meta fields and the lead request form (shortcode and block).
 * Version:           1.0.0
 * Requires at least: 6.4
 * Requires PHP:      7.4
 * Author:            Web Help Agency
 * Author URI:        https://webhelpagency.com/
 * License:           GPLv2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       elita-tour-core
 * Domain Path:       /languages
 *
 * @package Elita_Tour_Core
 */

defined( 'ABSPATH' ) || exit;

define( 'ELITA_TOUR_CORE_VERSION', '1.0.0' );
define( 'ELITA_TOUR_CORE_FILE', __FILE__ );
define( 'ELITA_TOUR_CORE_PATH', plugin_dir_path( __FILE__ ) );
define( 'ELITA_TOUR_CORE_URL', plugin_dir_url( __FILE__ ) );

/**
 * Load CMB2 (bundled through Composer) before anything else needs it.
 */
if ( ! defined( 'CMB2_LOADED' ) && file_exists( ELITA_TOUR_CORE_PATH . 'vendor/cmb2/cmb2/init.php' ) ) {
	require_once ELITA_TOUR_CORE_PATH . 'vendor/cmb2/cmb2/init.php';
}

require_once ELITA_TOUR_CORE_PATH . 'includes/post-types.php';
require_once ELITA_TOUR_CORE_PATH . 'includes/taxonomies.php';
require_once ELITA_TOUR_CORE_PATH . 'includes/meta.php';
require_once ELITA_TOUR_CORE_PATH . 'includes/helpers.php';
require_once ELITA_TOUR_CORE_PATH . 'includes/lead-form.php';
require_once ELITA_TOUR_CORE_PATH . 'includes/admin.php';
require_once ELITA_TOUR_CORE_PATH . 'includes/demo-import.php';

/**
 * Load the plugin translations.
 *
 * @return void
 */
function elita_tour_core_load_textdomain() {
	load_plugin_textdomain( 'elita-tour-core', false, dirname( plugin_basename( ELITA_TOUR_CORE_FILE ) ) . '/languages' );
}
add_action( 'init', 'elita_tour_core_load_textdomain' );

/**
 * Register the plugin assets so that block.json and the render callbacks can reference them.
 *
 * @return void
 */
function elita_tour_core_register_assets() {
	$style_path = ELITA_TOUR_CORE_PATH . 'assets/css/elita-tour-core.css';
	$style_ver  = file_exists( $style_path ) ? (string) filemtime( $style_path ) : ELITA_TOUR_CORE_VERSION;

	wp_register_style(
		'elita-tour-core',
		ELITA_TOUR_CORE_URL . 'assets/css/elita-tour-core.css',
		array(),
		$style_ver
	);

	$script_path = ELITA_TOUR_CORE_PATH . 'blocks/lead-form/index.js';
	$script_ver  = file_exists( $script_path ) ? (string) filemtime( $script_path ) : ELITA_TOUR_CORE_VERSION;

	wp_register_script(
		'elita-tour-core-lead-form-editor',
		ELITA_TOUR_CORE_URL . 'blocks/lead-form/index.js',
		array( 'wp-blocks', 'wp-element', 'wp-server-side-render', 'wp-i18n', 'wp-block-editor' ),
		$script_ver,
		true
	);

	if ( function_exists( 'wp_set_script_translations' ) ) {
		wp_set_script_translations( 'elita-tour-core-lead-form-editor', 'elita-tour-core', ELITA_TOUR_CORE_PATH . 'languages' );
	}
}
add_action( 'init', 'elita_tour_core_register_assets', 5 );

/**
 * Plugin activation: register the content types, then flush the rewrite rules once.
 *
 * @return void
 */
function elita_tour_core_activate() {
	elita_tour_core_register_post_types();
	elita_tour_core_register_taxonomies();
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'elita_tour_core_activate' );

/**
 * Plugin deactivation: drop the rewrite rules the plugin added.
 *
 * @return void
 */
function elita_tour_core_deactivate() {
	unregister_post_type( 'tour' );
	flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'elita_tour_core_deactivate' );
