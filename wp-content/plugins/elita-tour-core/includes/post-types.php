<?php
/**
 * Custom post types.
 *
 * @package Elita_Tour_Core
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register the `tour` and `elita_lead` post types.
 *
 * @return void
 */
function elita_tour_core_register_post_types() {

	$tour_labels = array(
		'name'                  => _x( 'Tours', 'Post type general name', 'elita-tour-core' ),
		'singular_name'         => _x( 'Tour', 'Post type singular name', 'elita-tour-core' ),
		'menu_name'             => _x( 'Tours', 'Admin Menu text', 'elita-tour-core' ),
		'name_admin_bar'        => _x( 'Tour', 'Add New on Toolbar', 'elita-tour-core' ),
		'add_new'               => __( 'Add New', 'elita-tour-core' ),
		'add_new_item'          => __( 'Add New Tour', 'elita-tour-core' ),
		'new_item'              => __( 'New Tour', 'elita-tour-core' ),
		'edit_item'             => __( 'Edit Tour', 'elita-tour-core' ),
		'view_item'             => __( 'View Tour', 'elita-tour-core' ),
		'view_items'            => __( 'View Tours', 'elita-tour-core' ),
		'all_items'             => __( 'All Tours', 'elita-tour-core' ),
		'search_items'          => __( 'Search Tours', 'elita-tour-core' ),
		'parent_item_colon'     => __( 'Parent Tours:', 'elita-tour-core' ),
		'not_found'             => __( 'No tours found.', 'elita-tour-core' ),
		'not_found_in_trash'    => __( 'No tours found in Trash.', 'elita-tour-core' ),
		'featured_image'        => __( 'Tour poster', 'elita-tour-core' ),
		'set_featured_image'    => __( 'Set tour poster', 'elita-tour-core' ),
		'remove_featured_image' => __( 'Remove tour poster', 'elita-tour-core' ),
		'use_featured_image'    => __( 'Use as tour poster', 'elita-tour-core' ),
		'archives'              => __( 'Tour archives', 'elita-tour-core' ),
		'insert_into_item'      => __( 'Insert into tour', 'elita-tour-core' ),
		'uploaded_to_this_item' => __( 'Uploaded to this tour', 'elita-tour-core' ),
		'filter_items_list'     => __( 'Filter tours list', 'elita-tour-core' ),
		'items_list_navigation' => __( 'Tours list navigation', 'elita-tour-core' ),
		'items_list'            => __( 'Tours list', 'elita-tour-core' ),
		'item_published'        => __( 'Tour published.', 'elita-tour-core' ),
		'item_updated'          => __( 'Tour updated.', 'elita-tour-core' ),
	);

	register_post_type(
		'tour',
		array(
			'labels'             => $tour_labels,
			'description'        => __( 'Travel programmes offered by the agency.', 'elita-tour-core' ),
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
		'name'               => _x( 'Leads', 'Post type general name', 'elita-tour-core' ),
		'singular_name'      => _x( 'Lead', 'Post type singular name', 'elita-tour-core' ),
		'menu_name'          => _x( 'Leads', 'Admin Menu text', 'elita-tour-core' ),
		'all_items'          => __( 'Leads', 'elita-tour-core' ),
		'edit_item'          => __( 'Lead', 'elita-tour-core' ),
		'view_item'          => __( 'View Lead', 'elita-tour-core' ),
		'search_items'       => __( 'Search Leads', 'elita-tour-core' ),
		'not_found'          => __( 'No leads found.', 'elita-tour-core' ),
		'not_found_in_trash' => __( 'No leads found in Trash.', 'elita-tour-core' ),
	);

	register_post_type(
		'elita_lead',
		array(
			'labels'              => $lead_labels,
			'description'         => __( 'Requests submitted through the lead form.', 'elita-tour-core' ),
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
add_action( 'init', 'elita_tour_core_register_post_types' );
