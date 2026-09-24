<?php
/**
 * Taxonomies and term meta.
 *
 * @package WHA_Tours_Core
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register the tour taxonomies.
 *
 * @return void
 */
function wha_tours_core_register_taxonomies() {

	register_taxonomy(
		'tour_category',
		array( 'tour' ),
		array(
			'labels'             => array(
				'name'              => _x( 'Tour Categories', 'Taxonomy general name', 'wha-tours-core' ),
				'singular_name'     => _x( 'Tour Category', 'Taxonomy singular name', 'wha-tours-core' ),
				'menu_name'         => __( 'Categories', 'wha-tours-core' ),
				'all_items'         => __( 'All Tour Categories', 'wha-tours-core' ),
				'edit_item'         => __( 'Edit Tour Category', 'wha-tours-core' ),
				'view_item'         => __( 'View Tour Category', 'wha-tours-core' ),
				'update_item'       => __( 'Update Tour Category', 'wha-tours-core' ),
				'add_new_item'      => __( 'Add New Tour Category', 'wha-tours-core' ),
				'new_item_name'     => __( 'New Tour Category Name', 'wha-tours-core' ),
				'parent_item'       => __( 'Parent Tour Category', 'wha-tours-core' ),
				'parent_item_colon' => __( 'Parent Tour Category:', 'wha-tours-core' ),
				'search_items'      => __( 'Search Tour Categories', 'wha-tours-core' ),
				'not_found'         => __( 'No tour categories found.', 'wha-tours-core' ),
				'back_to_items'     => __( 'Back to Tour Categories', 'wha-tours-core' ),
			),
			'description'        => __( 'Groups of tours, for example camps or school trips.', 'wha-tours-core' ),
			'public'             => true,
			'publicly_queryable' => true,
			'hierarchical'       => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'show_in_nav_menus'  => true,
			'show_in_rest'       => true,
			'show_admin_column'  => true,
			'show_tagcloud'      => false,
			'query_var'          => true,
			'rewrite'            => array(
				'slug'         => 'tour-category',
				'with_front'   => false,
				'hierarchical' => true,
			),
		)
	);

	register_taxonomy(
		'tour_season',
		array( 'tour' ),
		array(
			'labels'             => array(
				'name'          => _x( 'Seasons', 'Taxonomy general name', 'wha-tours-core' ),
				'singular_name' => _x( 'Season', 'Taxonomy singular name', 'wha-tours-core' ),
				'menu_name'     => __( 'Seasons', 'wha-tours-core' ),
				'all_items'     => __( 'All Seasons', 'wha-tours-core' ),
				'edit_item'     => __( 'Edit Season', 'wha-tours-core' ),
				'view_item'     => __( 'View Season', 'wha-tours-core' ),
				'update_item'   => __( 'Update Season', 'wha-tours-core' ),
				'add_new_item'  => __( 'Add New Season', 'wha-tours-core' ),
				'new_item_name' => __( 'New Season Name', 'wha-tours-core' ),
				'search_items'  => __( 'Search Seasons', 'wha-tours-core' ),
				'not_found'     => __( 'No seasons found.', 'wha-tours-core' ),
				'back_to_items' => __( 'Back to Seasons', 'wha-tours-core' ),
			),
			'description'        => __( 'Season a tour belongs to.', 'wha-tours-core' ),
			'public'             => true,
			'publicly_queryable' => true,
			'hierarchical'       => false,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'show_in_nav_menus'  => true,
			'show_in_rest'       => true,
			'show_admin_column'  => true,
			'show_tagcloud'      => false,
			'query_var'          => true,
			'rewrite'            => array(
				'slug'       => 'season',
				'with_front' => false,
			),
		)
	);

	register_taxonomy(
		'tour_country',
		array( 'tour' ),
		array(
			'labels'             => array(
				'name'          => _x( 'Countries', 'Taxonomy general name', 'wha-tours-core' ),
				'singular_name' => _x( 'Country', 'Taxonomy singular name', 'wha-tours-core' ),
				'menu_name'     => __( 'Countries', 'wha-tours-core' ),
				'all_items'     => __( 'All Countries', 'wha-tours-core' ),
				'edit_item'     => __( 'Edit Country', 'wha-tours-core' ),
				'view_item'     => __( 'View Country', 'wha-tours-core' ),
				'update_item'   => __( 'Update Country', 'wha-tours-core' ),
				'add_new_item'  => __( 'Add New Country', 'wha-tours-core' ),
				'new_item_name' => __( 'New Country Name', 'wha-tours-core' ),
				'search_items'  => __( 'Search Countries', 'wha-tours-core' ),
				'not_found'     => __( 'No countries found.', 'wha-tours-core' ),
				'back_to_items' => __( 'Back to Countries', 'wha-tours-core' ),
			),
			'description'        => __( 'Country a tour takes place in.', 'wha-tours-core' ),
			'public'             => true,
			'publicly_queryable' => true,
			'hierarchical'       => false,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'show_in_nav_menus'  => true,
			'show_in_rest'       => true,
			'show_admin_column'  => true,
			'show_tagcloud'      => false,
			'query_var'          => true,
			'rewrite'            => array(
				'slug'       => 'country',
				'with_front' => false,
			),
		)
	);
}
add_action( 'init', 'wha_tours_core_register_taxonomies' );

/**
 * Register the term meta used by the tour taxonomies.
 *
 * @return void
 */
function wha_tours_core_register_term_meta() {

	register_term_meta(
		'tour_category',
		'_wha_tour_image_id',
		array(
			'type'              => 'integer',
			'description'       => __( 'Attachment ID of the category image.', 'wha-tours-core' ),
			'single'            => true,
			'default'           => 0,
			'show_in_rest'      => true,
			'sanitize_callback' => 'absint',
			'auth_callback'     => 'wha_tours_core_term_meta_auth',
		)
	);

	register_term_meta(
		'tour_category',
		'_wha_tour_short_desc',
		array(
			'type'              => 'string',
			'description'       => __( 'Short description shown on the category panel.', 'wha-tours-core' ),
			'single'            => true,
			'default'           => '',
			'show_in_rest'      => true,
			'sanitize_callback' => 'sanitize_text_field',
			'auth_callback'     => 'wha_tours_core_term_meta_auth',
		)
	);

	register_term_meta(
		'tour_category',
		'_wha_tour_color',
		array(
			'type'              => 'string',
			'description'       => __( 'Colour key used for the category badge.', 'wha-tours-core' ),
			'single'            => true,
			'default'           => '',
			'show_in_rest'      => array(
				'schema' => array(
					'type' => 'string',
					'enum' => array( '', 'camps', 'europe', 'ukraine', 'adventure' ),
				),
			),
			'sanitize_callback' => 'wha_tours_core_sanitize_color',
			'auth_callback'     => 'wha_tours_core_term_meta_auth',
		)
	);

	register_term_meta(
		'tour_season',
		'_wha_tour_order',
		array(
			'type'              => 'integer',
			'description'       => __( 'Sort order of the season.', 'wha-tours-core' ),
			'single'            => true,
			'default'           => 0,
			'show_in_rest'      => true,
			'sanitize_callback' => 'absint',
			'auth_callback'     => 'wha_tours_core_term_meta_auth',
		)
	);
}
add_action( 'init', 'wha_tours_core_register_term_meta' );

/**
 * Authorisation callback for the tour term meta.
 *
 * @return bool
 */
function wha_tours_core_term_meta_auth() {
	return current_user_can( 'manage_categories' );
}

/**
 * Sanitize a category colour key.
 *
 * @param mixed $value Raw value.
 * @return string One of the allowed colour keys, or an empty string.
 */
function wha_tours_core_sanitize_color( $value ) {
	$value = is_scalar( $value ) ? sanitize_key( (string) $value ) : '';

	return in_array( $value, wha_tours_core_get_colors(), true ) ? $value : '';
}

/**
 * List of allowed category colour keys.
 *
 * @return string[]
 */
function wha_tours_core_get_colors() {
	return array( 'camps', 'europe', 'ukraine', 'adventure' );
}
