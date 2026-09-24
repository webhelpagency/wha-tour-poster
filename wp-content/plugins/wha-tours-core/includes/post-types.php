<?php
/**
 * Custom post types.
 *
 * @package WHA_Tours_Core
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register the `tour` and `wha_tours_lead` post types.
 *
 * @return void
 */
function wha_tours_core_register_post_types() {

	$tour_labels = array(
		'name'                  => _x( 'Tours', 'Post type general name', 'wha-tours-core' ),
		'singular_name'         => _x( 'Tour', 'Post type singular name', 'wha-tours-core' ),
		'menu_name'             => _x( 'Tours', 'Admin Menu text', 'wha-tours-core' ),
		'name_admin_bar'        => _x( 'Tour', 'Add New on Toolbar', 'wha-tours-core' ),
		'add_new'               => __( 'Add New', 'wha-tours-core' ),
		'add_new_item'          => __( 'Add New Tour', 'wha-tours-core' ),
		'new_item'              => __( 'New Tour', 'wha-tours-core' ),
		'edit_item'             => __( 'Edit Tour', 'wha-tours-core' ),
		'view_item'             => __( 'View Tour', 'wha-tours-core' ),
		'view_items'            => __( 'View Tours', 'wha-tours-core' ),
		'all_items'             => __( 'All Tours', 'wha-tours-core' ),
		'search_items'          => __( 'Search Tours', 'wha-tours-core' ),
		'parent_item_colon'     => __( 'Parent Tours:', 'wha-tours-core' ),
		'not_found'             => __( 'No tours found.', 'wha-tours-core' ),
		'not_found_in_trash'    => __( 'No tours found in Trash.', 'wha-tours-core' ),
		'featured_image'        => __( 'Tour poster', 'wha-tours-core' ),
		'set_featured_image'    => __( 'Set tour poster', 'wha-tours-core' ),
		'remove_featured_image' => __( 'Remove tour poster', 'wha-tours-core' ),
		'use_featured_image'    => __( 'Use as tour poster', 'wha-tours-core' ),
		'archives'              => __( 'Tour archives', 'wha-tours-core' ),
		'insert_into_item'      => __( 'Insert into tour', 'wha-tours-core' ),
		'uploaded_to_this_item' => __( 'Uploaded to this tour', 'wha-tours-core' ),
		'filter_items_list'     => __( 'Filter tours list', 'wha-tours-core' ),
		'items_list_navigation' => __( 'Tours list navigation', 'wha-tours-core' ),
		'items_list'            => __( 'Tours list', 'wha-tours-core' ),
		'item_published'        => __( 'Tour published.', 'wha-tours-core' ),
		'item_updated'          => __( 'Tour updated.', 'wha-tours-core' ),
	);

	register_post_type(
		'tour',
		array(
			'labels'             => $tour_labels,
			'description'        => __( 'Travel programmes offered by the agency.', 'wha-tours-core' ),
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'show_in_nav_menus'  => true,
			'show_in_admin_bar'  => true,
			'show_in_rest'       => true,
			'rest_base'          => 'tours',
			'menu_position'      => 5,
			'menu_icon'          => 'dashicons-palmtree',
			'capability_type'    => 'post',
			'map_meta_cap'       => true,
			'hierarchical'       => false,
			'has_archive'        => 'tours',
			'rewrite'            => array(
				'slug'       => 'tour',
				'with_front' => false,
			),
			'query_var'          => true,
			'supports'           => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions' ),
			'taxonomies'         => array( 'tour_category', 'tour_season', 'tour_country' ),
		)
	);

	$lead_labels = array(
		'name'               => _x( 'Leads', 'Post type general name', 'wha-tours-core' ),
		'singular_name'      => _x( 'Lead', 'Post type singular name', 'wha-tours-core' ),
		'menu_name'          => _x( 'Leads', 'Admin Menu text', 'wha-tours-core' ),
		'all_items'          => __( 'Leads', 'wha-tours-core' ),
		'edit_item'          => __( 'Lead', 'wha-tours-core' ),
		'view_item'          => __( 'View Lead', 'wha-tours-core' ),
		'search_items'       => __( 'Search Leads', 'wha-tours-core' ),
		'not_found'          => __( 'No leads found.', 'wha-tours-core' ),
		'not_found_in_trash' => __( 'No leads found in Trash.', 'wha-tours-core' ),
	);

	register_post_type(
		'wha_tours_lead',
		array(
			'labels'              => $lead_labels,
			'description'         => __( 'Requests submitted through the lead form.', 'wha-tours-core' ),
			'public'              => false,
			'publicly_queryable'  => false,
			'exclude_from_search' => true,
			'show_ui'             => true,
			'show_in_menu'        => 'edit.php?post_type=tour',
			'show_in_nav_menus'   => false,
			'show_in_rest'        => false,
			'menu_icon'           => 'dashicons-email-alt',
			'capability_type'     => 'post',
			'map_meta_cap'        => true,
			'capabilities'        => array(
				'create_posts'           => 'do_not_allow',
				'publish_posts'          => 'manage_options',
				'edit_posts'             => 'manage_options',
				'edit_others_posts'      => 'manage_options',
				'edit_published_posts'   => 'manage_options',
				'edit_private_posts'     => 'manage_options',
				'read_private_posts'     => 'manage_options',
				'delete_posts'           => 'manage_options',
				'delete_others_posts'    => 'manage_options',
				'delete_published_posts' => 'manage_options',
				'delete_private_posts'   => 'manage_options',
			),
			'hierarchical'        => false,
			'has_archive'         => false,
			'rewrite'             => false,
			'query_var'           => false,
			'supports'            => array( 'title' ),
		)
	);
}
add_action( 'init', 'wha_tours_core_register_post_types' );
