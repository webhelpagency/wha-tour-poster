<?php
/**
 * Taxonomies and term meta.
 *
 * @package Elita_Tour_Core
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register the tour taxonomies.
 *
 * @return void
 */
function elita_tour_core_register_taxonomies() {

	register_taxonomy(
		'tour_category',
		array( 'tour' ),
		array(
			'labels'             => array(
				'name'              => _x( 'Tour Categories', 'Taxonomy general name', 'elita-tour-core' ),
				'singular_name'     => _x( 'Tour Category', 'Taxonomy singular name', 'elita-tour-core' ),
				'menu_name'         => __( 'Categories', 'elita-tour-core' ),
				'all_items'         => __( 'All Tour Categories', 'elita-tour-core' ),
				'edit_item'         => __( 'Edit Tour Category', 'elita-tour-core' ),
				'view_item'         => __( 'View Tour Category', 'elita-tour-core' ),
				'update_item'       => __( 'Update Tour Category', 'elita-tour-core' ),
				'add_new_item'      => __( 'Add New Tour Category', 'elita-tour-core' ),
				'new_item_name'     => __( 'New Tour Category Name', 'elita-tour-core' ),
				'parent_item'       => __( 'Parent Tour Category', 'elita-tour-core' ),
				'parent_item_colon' => __( 'Parent Tour Category:', 'elita-tour-core' ),
				'search_items'      => __( 'Search Tour Categories', 'elita-tour-core' ),
				'not_found'         => __( 'No tour categories found.', 'elita-tour-core' ),
				'back_to_items'     => __( 'Back to Tour Categories', 'elita-tour-core' ),
			),
			'description'        => __( 'Groups of tours, for example camps or school trips.', 'elita-tour-core' ),
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
				'name'          => _x( 'Seasons', 'Taxonomy general name', 'elita-tour-core' ),
				'singular_name' => _x( 'Season', 'Taxonomy singular name', 'elita-tour-core' ),
				'menu_name'     => __( 'Seasons', 'elita-tour-core' ),
				'all_items'     => __( 'All Seasons', 'elita-tour-core' ),
				'edit_item'     => __( 'Edit Season', 'elita-tour-core' ),
				'view_item'     => __( 'View Season', 'elita-tour-core' ),
				'update_item'   => __( 'Update Season', 'elita-tour-core' ),
				'add_new_item'  => __( 'Add New Season', 'elita-tour-core' ),
				'new_item_name' => __( 'New Season Name', 'elita-tour-core' ),
				'search_items'  => __( 'Search Seasons', 'elita-tour-core' ),
				'not_found'     => __( 'No seasons found.', 'elita-tour-core' ),
				'back_to_items' => __( 'Back to Seasons', 'elita-tour-core' ),
			),
			'description'        => __( 'Season a tour belongs to.', 'elita-tour-core' ),
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
				'name'          => _x( 'Countries', 'Taxonomy general name', 'elita-tour-core' ),
				'singular_name' => _x( 'Country', 'Taxonomy singular name', 'elita-tour-core' ),
				'menu_name'     => __( 'Countries', 'elita-tour-core' ),
				'all_items'     => __( 'All Countries', 'elita-tour-core' ),
				'edit_item'     => __( 'Edit Country', 'elita-tour-core' ),
				'view_item'     => __( 'View Country', 'elita-tour-core' ),
				'update_item'   => __( 'Update Country', 'elita-tour-core' ),
				'add_new_item'  => __( 'Add New Country', 'elita-tour-core' ),
				'new_item_name' => __( 'New Country Name', 'elita-tour-core' ),
				'search_items'  => __( 'Search Countries', 'elita-tour-core' ),
				'not_found'     => __( 'No countries found.', 'elita-tour-core' ),
				'back_to_items' => __( 'Back to Countries', 'elita-tour-core' ),
			),
			'description'        => __( 'Country a tour takes place in.', 'elita-tour-core' ),
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
add_action( 'init', 'elita_tour_core_register_taxonomies' );

/**
 * Register the term meta used by the tour taxonomies.
 *
 * @return void
 */
function elita_tour_core_register_term_meta() {

	register_term_meta(
		'tour_category',
		'_elita_tour_image_id',
		array(
			'type'              => 'integer',
			'description'       => __( 'Attachment ID of the category image.', 'elita-tour-core' ),
			'single'            => true,
			'default'           => 0,
			'show_in_rest'      => true,
			'sanitize_callback' => 'absint',
			'auth_callback'     => 'elita_tour_core_term_meta_auth',
		)
	);

	register_term_meta(
		'tour_category',
		'_elita_tour_short_desc',
		array(
			'type'              => 'string',
			'description'       => __( 'Short description shown on the category panel.', 'elita-tour-core' ),
			'single'            => true,
			'default'           => '',
			'show_in_rest'      => true,
			'sanitize_callback' => 'sanitize_text_field',
			'auth_callback'     => 'elita_tour_core_term_meta_auth',
		)
	);

	register_term_meta(
		'tour_category',
		'_elita_tour_color',
		array(
			'type'              => 'string',
			'description'       => __( 'Colour key used for the category badge.', 'elita-tour-core' ),
			'single'            => true,
			'default'           => '',
			'show_in_rest'      => array(
				'schema' => array(
					'type' => 'string',
					'enum' => array( '', 'camps', 'europe', 'ukraine', 'adventure' ),
				),
			),
			'sanitize_callback' => 'elita_tour_core_sanitize_color',
			'auth_callback'     => 'elita_tour_core_term_meta_auth',
		)
	);

	register_term_meta(
		'tour_season',
		'_elita_tour_order',
		array(
			'type'              => 'integer',
			'description'       => __( 'Sort order of the season.', 'elita-tour-core' ),
			'single'            => true,
			'default'           => 0,
			'show_in_rest'      => true,
			'sanitize_callback' => 'absint',
			'auth_callback'     => 'elita_tour_core_term_meta_auth',
		)
	);
}
add_action( 'init', 'elita_tour_core_register_term_meta' );

/**
 * Authorisation callback for the tour term meta.
 *
 * @return bool
 */
function elita_tour_core_term_meta_auth() {
	return current_user_can( 'manage_categories' );
}

/**
 * Sanitize a category colour key.
 *
 * @param mixed $value Raw value.
 * @return string One of the allowed colour keys, or an empty string.
 */
function elita_tour_core_sanitize_color( $value ) {
	$value = is_scalar( $value ) ? sanitize_key( (string) $value ) : '';

	return in_array( $value, elita_tour_core_get_colors(), true ) ? $value : '';
}

/**
 * List of allowed category colour keys.
 *
 * @return string[]
 */
function elita_tour_core_get_colors() {
	return array( 'camps', 'europe', 'ukraine', 'adventure' );
}
