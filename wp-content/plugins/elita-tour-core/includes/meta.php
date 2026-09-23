<?php
/**
 * Post meta registration, CMB2 fields and the computed next departure date.
 *
 * @package Elita_Tour_Core
 */

defined( 'ABSPATH' ) || exit;

/**
 * Allowed transport keys.
 *
 * @return string[]
 */
function elita_tour_core_get_transports() {
	return array( 'bus', 'plane', 'train', 'mixed' );
}

/**
 * Allowed departure date status keys.
 *
 * @return string[]
 */
function elita_tour_core_get_date_statuses() {
	return array( 'ok', 'few', 'closed' );
}

/**
 * Sanitize a transport key.
 *
 * @param mixed $value Raw value.
 * @return string Allowed transport key or an empty string.
 */
function elita_tour_core_sanitize_transport( $value ) {
	$value = is_scalar( $value ) ? sanitize_key( (string) $value ) : '';

	return in_array( $value, elita_tour_core_get_transports(), true ) ? $value : '';
}

/**
 * Sanitize a departure status key.
 *
 * @param mixed $value Raw value.
 * @return string Allowed status key, defaults to `ok`.
 */
function elita_tour_core_sanitize_date_status( $value ) {
	$value = is_scalar( $value ) ? sanitize_key( (string) $value ) : '';

	return in_array( $value, elita_tour_core_get_date_statuses(), true ) ? $value : 'ok';
}

/**
 * Sanitize a `Y-m-d` date string.
 *
 * @param mixed $value Raw value.
 * @return string Date in `Y-m-d` format or an empty string.
 */
function elita_tour_core_sanitize_date( $value ) {
	if ( ! is_scalar( $value ) ) {
		return '';
	}

	$value = trim( sanitize_text_field( (string) $value ) );

	if ( '' === $value ) {
		return '';
	}

	$parts = array_map( 'absint', explode( '-', $value ) );

	if ( 3 !== count( $parts ) || ! wp_checkdate( $parts[1], $parts[2], $parts[0], $value ) ) {
		return '';
	}

	return sprintf( '%04d-%02d-%02d', $parts[0], $parts[1], $parts[2] );
}

/**
 * Sanitize the checkbox style flag.
 *
 * @param mixed $value Raw value.
 * @return string `1` when enabled, otherwise an empty string.
 */
function elita_tour_core_sanitize_flag( $value ) {
	return empty( $value ) ? '' : '1';
}

/**
 * Sanitize the repeatable route stops.
 *
 * @param mixed $value Raw value.
 * @return string[] Clean list of stops.
 */
function elita_tour_core_sanitize_route( $value ) {
	if ( ! is_array( $value ) ) {
		return array();
	}

	$route = array();

	foreach ( $value as $stop ) {
		if ( ! is_scalar( $stop ) ) {
			continue;
		}

		$stop = sanitize_text_field( (string) $stop );

		if ( '' !== $stop ) {
			$route[] = $stop;
		}
	}

	return $route;
}

/**
 * Sanitize the repeatable departure dates group.
 *
 * @param mixed $value Raw value.
 * @return array[] Clean list of `start`, `end` and `status` rows.
 */
function elita_tour_core_sanitize_dates( $value ) {
	if ( ! is_array( $value ) ) {
		return array();
	}

	$dates = array();

	foreach ( $value as $row ) {
		if ( ! is_array( $row ) ) {
			continue;
		}

		$start = isset( $row['start'] ) ? elita_tour_core_sanitize_date( $row['start'] ) : '';
		$end   = isset( $row['end'] ) ? elita_tour_core_sanitize_date( $row['end'] ) : '';

		if ( '' === $start ) {
			continue;
		}

		if ( '' !== $end && $end < $start ) {
			$end = $start;
		}

		$dates[] = array(
			'start'  => $start,
			'end'    => $end,
			'status' => isset( $row['status'] ) ? elita_tour_core_sanitize_date_status( $row['status'] ) : 'ok',
		);
	}

	usort(
		$dates,
		static function ( $a, $b ) {
			return strcmp( $a['start'], $b['start'] );
		}
	);

	return $dates;
}

/**
 * Authorisation callback for the tour post meta.
 *
 * @param bool   $allowed   Whether the user can add the meta.
 * @param string $meta_key  Meta key.
 * @param int    $object_id Post ID.
 * @return bool
 */
function elita_tour_core_post_meta_auth( $allowed, $meta_key, $object_id ) {
	unset( $allowed, $meta_key );

	return current_user_can( 'edit_post', (int) $object_id );
}

/**
 * Register the tour post meta.
 *
 * @return void
 */
function elita_tour_core_register_post_meta() {

	register_post_meta(
		'tour',
		'_elita_tour_days',
		array(
			'type'              => 'integer',
			'description'       => __( 'Duration of the tour in days.', 'elita-tour-core' ),
			'single'            => true,
			'default'           => 0,
			'show_in_rest'      => true,
			'sanitize_callback' => 'absint',
			'auth_callback'     => 'elita_tour_core_post_meta_auth',
		)
	);

	register_post_meta(
		'tour',
		'_elita_tour_transport',
		array(
			'type'              => 'string',
			'description'       => __( 'Main transport used on the tour.', 'elita-tour-core' ),
			'single'            => true,
			'default'           => '',
			'show_in_rest'      => array(
				'schema' => array(
					'type' => 'string',
					'enum' => array( '', 'bus', 'plane', 'train', 'mixed' ),
				),
			),
			'sanitize_callback' => 'elita_tour_core_sanitize_transport',
			'auth_callback'     => 'elita_tour_core_post_meta_auth',
		)
	);

	register_post_meta(
		'tour',
		'_elita_tour_price',
		array(
			'type'              => 'string',
			'description'       => __( 'Price label. Leave empty for "on request".', 'elita-tour-core' ),
			'single'            => true,
			'default'           => '',
			'show_in_rest'      => true,
			'sanitize_callback' => 'sanitize_text_field',
			'auth_callback'     => 'elita_tour_core_post_meta_auth',
		)
	);

	register_post_meta(
		'tour',
		'_elita_tour_route',
		array(
			'type'              => 'array',
			'description'       => __( 'Ordered list of route stops.', 'elita-tour-core' ),
			'single'            => true,
			'default'           => array(),
			'show_in_rest'      => array(
				'schema' => array(
					'type'  => 'array',
					'items' => array(
						'type' => 'string',
					),
				),
			),
			'sanitize_callback' => 'elita_tour_core_sanitize_route',
			'auth_callback'     => 'elita_tour_core_post_meta_auth',
		)
	);

	register_post_meta(
		'tour',
		'_elita_tour_dates',
		array(
			'type'              => 'array',
			'description'       => __( 'Departure dates with availability status.', 'elita-tour-core' ),
			'single'            => true,
			'default'           => array(),
			'show_in_rest'      => array(
				'schema' => array(
					'type'  => 'array',
					'items' => array(
						'type'       => 'object',
						'properties' => array(
							'start'  => array(
								'type'        => 'string',
								'format'      => 'date',
								'description' => __( 'First day, in Y-m-d format.', 'elita-tour-core' ),
							),
							'end'    => array(
								'type'        => 'string',
								'format'      => 'date',
								'description' => __( 'Last day, in Y-m-d format.', 'elita-tour-core' ),
							),
							'status' => array(
								'type' => 'string',
								'enum' => array( 'ok', 'few', 'closed' ),
							),
						),
					),
				),
			),
			'sanitize_callback' => 'elita_tour_core_sanitize_dates',
			'auth_callback'     => 'elita_tour_core_post_meta_auth',
		)
	);

	register_post_meta(
		'tour',
		'_elita_tour_hit',
		array(
			'type'              => 'string',
			'description'       => __( 'Whether the tour is highlighted as a season hit.', 'elita-tour-core' ),
			'single'            => true,
			'default'           => '',
			'show_in_rest'      => array(
				'schema' => array(
					'type' => 'string',
					'enum' => array( '', '1' ),
				),
			),
			'sanitize_callback' => 'elita_tour_core_sanitize_flag',
			'auth_callback'     => 'elita_tour_core_post_meta_auth',
		)
	);

	register_post_meta(
		'tour',
		'_elita_tour_next_departure',
		array(
			'type'              => 'string',
			'description'       => __( 'Computed date of the next departure, in Y-m-d format.', 'elita-tour-core' ),
			'single'            => true,
			'default'           => '',
			'show_in_rest'      => true,
			'sanitize_callback' => 'elita_tour_core_sanitize_date',
			'auth_callback'     => 'elita_tour_core_post_meta_auth',
		)
	);
}
add_action( 'init', 'elita_tour_core_register_post_meta' );

/**
 * Work out the next departure date for a set of departure rows.
 *
 * The earliest upcoming start date of a row that is not closed wins; when every
 * row is in the past or closed, the earliest start date is used instead.
 *
 * @param array $dates Departure rows.
 * @return string Date in `Y-m-d` format, or an empty string.
 */
function elita_tour_core_calculate_next_departure( $dates ) {
	if ( ! is_array( $dates ) || empty( $dates ) ) {
		return '';
	}

	$today    = current_time( 'Y-m-d' );
	$upcoming = array();
	$all      = array();

	foreach ( $dates as $row ) {
		if ( ! is_array( $row ) || empty( $row['start'] ) ) {
			continue;
		}

		$start = elita_tour_core_sanitize_date( $row['start'] );

		if ( '' === $start ) {
			continue;
		}

		$all[] = $start;

		$status = isset( $row['status'] ) ? elita_tour_core_sanitize_date_status( $row['status'] ) : 'ok';

		if ( 'closed' !== $status && $start >= $today ) {
			$upcoming[] = $start;
		}
	}

	if ( ! empty( $upcoming ) ) {
		return min( $upcoming );
	}

	if ( ! empty( $all ) ) {
		return min( $all );
	}

	return '';
}

/**
 * Recalculate and store `_elita_tour_next_departure` for a tour.
 *
 * @param int $post_id Tour ID.
 * @return void
 */
function elita_tour_core_update_next_departure( $post_id ) {
	static $running = false;

	$post_id = (int) $post_id;

	if ( $running || $post_id <= 0 || 'tour' !== get_post_type( $post_id ) ) {
		return;
	}

	$running = true;

	$dates   = get_post_meta( $post_id, '_elita_tour_dates', true );
	$next    = elita_tour_core_calculate_next_departure( is_array( $dates ) ? $dates : array() );
	$current = (string) get_post_meta( $post_id, '_elita_tour_next_departure', true );

	if ( '' === $next ) {
		if ( '' !== $current ) {
			delete_post_meta( $post_id, '_elita_tour_next_departure' );
		}
	} elseif ( $next !== $current ) {
		update_post_meta( $post_id, '_elita_tour_next_departure', $next );
	}

	$running = false;
}

/**
 * Recalculate the next departure after a tour is saved.
 *
 * Runs late so that the CMB2 fields are already stored.
 *
 * @param int $post_id Tour ID.
 * @return void
 */
function elita_tour_core_save_tour( $post_id ) {
	if ( wp_is_post_revision( $post_id ) || wp_is_post_autosave( $post_id ) ) {
		return;
	}

	elita_tour_core_update_next_departure( $post_id );
}
add_action( 'save_post_tour', 'elita_tour_core_save_tour', 100 );

/**
 * Recalculate the next departure after CMB2 stored the tour fields.
 *
 * @param int $object_id Tour ID.
 * @return void
 */
function elita_tour_core_after_cmb2_save( $object_id ) {
	elita_tour_core_update_next_departure( $object_id );
}
add_action( 'cmb2_save_post_fields_elita_tour_core_details', 'elita_tour_core_after_cmb2_save', 10, 1 );

/**
 * Recalculate the next departure when the dates meta is written directly, for
 * example through the REST API or `update_post_meta()`.
 *
 * @param int    $meta_id   Meta row ID.
 * @param int    $object_id Post ID.
 * @param string $meta_key  Meta key.
 * @return void
 */
function elita_tour_core_dates_meta_changed( $meta_id, $object_id, $meta_key ) {
	unset( $meta_id );

	if ( '_elita_tour_dates' !== $meta_key ) {
		return;
	}

	elita_tour_core_update_next_departure( $object_id );
}
add_action( 'added_post_meta', 'elita_tour_core_dates_meta_changed', 10, 3 );
add_action( 'updated_post_meta', 'elita_tour_core_dates_meta_changed', 10, 3 );

/**
 * Recalculate the next departure when the dates meta is deleted.
 *
 * @param string[] $meta_ids  Deleted meta row IDs.
 * @param int      $object_id Post ID.
 * @param string   $meta_key  Meta key.
 * @return void
 */
function elita_tour_core_dates_meta_deleted( $meta_ids, $object_id, $meta_key ) {
	unset( $meta_ids );

	if ( '_elita_tour_dates' !== $meta_key ) {
		return;
	}

	elita_tour_core_update_next_departure( $object_id );
}
add_action( 'deleted_post_meta', 'elita_tour_core_dates_meta_deleted', 10, 3 );

/**
 * Register the CMB2 metabox for tours.
 *
 * @return void
 */
function elita_tour_core_register_tour_metabox() {
	if ( ! function_exists( 'new_cmb2_box' ) ) {
		return;
	}

	$box = new_cmb2_box(
		array(
			'id'           => 'elita_tour_core_details',
			'title'        => esc_html__( 'Tour details', 'elita-tour-core' ),
			'object_types' => array( 'tour' ),
			'context'      => 'normal',
			'priority'     => 'high',
		)
	);

	$box->add_field(
		array(
			'name'            => esc_html__( 'Days', 'elita-tour-core' ),
			'desc'            => esc_html__( 'Length of the programme in days.', 'elita-tour-core' ),
			'id'              => '_elita_tour_days',
			'type'            => 'text_small',
			'sanitization_cb' => 'absint',
			'attributes'      => array(
				'type' => 'number',
				'min'  => 1,
				'step' => 1,
			),
		)
	);

	$box->add_field(
		array(
			'name'            => esc_html__( 'Transport', 'elita-tour-core' ),
			'id'              => '_elita_tour_transport',
			'type'            => 'select',
			'default'         => 'bus',
			'sanitization_cb' => 'elita_tour_core_sanitize_transport',
			'options'         => array(
				'bus'   => esc_html__( 'Bus', 'elita-tour-core' ),
				'plane' => esc_html__( 'Plane', 'elita-tour-core' ),
				'train' => esc_html__( 'Train', 'elita-tour-core' ),
				'mixed' => esc_html__( 'Combined', 'elita-tour-core' ),
			),
		)
	);

	$box->add_field(
		array(
			'name'            => esc_html__( 'Price', 'elita-tour-core' ),
			'desc'            => esc_html__( 'Free text, for example "from 455 €". Leave empty to show "on request".', 'elita-tour-core' ),
			'id'              => '_elita_tour_price',
			'type'            => 'text',
			'sanitization_cb' => 'sanitize_text_field',
		)
	);

	$box->add_field(
		array(
			'name'            => esc_html__( 'Route', 'elita-tour-core' ),
			'desc'            => esc_html__( 'One stop per row, in travel order.', 'elita-tour-core' ),
			'id'              => '_elita_tour_route',
			'type'            => 'text',
			'repeatable'      => true,
			'sanitization_cb' => 'elita_tour_core_sanitize_route',
			'text'            => array(
				'add_row_text' => esc_html__( 'Add stop', 'elita-tour-core' ),
			),
		)
	);

	$dates = $box->add_field(
		array(
			'name'       => esc_html__( 'Departure dates', 'elita-tour-core' ),
			'id'         => '_elita_tour_dates',
			'type'       => 'group',
			'repeatable' => true,
			'options'    => array(
				'group_title'    => esc_html__( 'Departure {#}', 'elita-tour-core' ),
				'add_button'     => esc_html__( 'Add departure', 'elita-tour-core' ),
				'remove_button'  => esc_html__( 'Remove departure', 'elita-tour-core' ),
				'sortable'       => true,
				'closed'         => false,
				'remove_confirm' => esc_html__( 'Remove this departure?', 'elita-tour-core' ),
			),
		)
	);

	$box->add_group_field(
		$dates,
		array(
			'name'            => esc_html__( 'Start', 'elita-tour-core' ),
			'id'              => 'start',
			'type'            => 'text_date',
			'date_format'     => 'Y-m-d',
			'sanitization_cb' => 'elita_tour_core_sanitize_date',
		)
	);

	$box->add_group_field(
		$dates,
		array(
			'name'            => esc_html__( 'End', 'elita-tour-core' ),
			'id'              => 'end',
			'type'            => 'text_date',
			'date_format'     => 'Y-m-d',
			'sanitization_cb' => 'elita_tour_core_sanitize_date',
		)
	);

	$box->add_group_field(
		$dates,
		array(
			'name'            => esc_html__( 'Status', 'elita-tour-core' ),
			'id'              => 'status',
			'type'            => 'select',
			'default'         => 'ok',
			'sanitization_cb' => 'elita_tour_core_sanitize_date_status',
			'options'         => array(
				'ok'     => esc_html__( 'Places available', 'elita-tour-core' ),
				'few'    => esc_html__( 'Few places left', 'elita-tour-core' ),
				'closed' => esc_html__( 'Sold out', 'elita-tour-core' ),
			),
		)
	);

	$box->add_field(
		array(
			'name'            => esc_html__( 'Season hit', 'elita-tour-core' ),
			'desc'            => esc_html__( 'Show this tour in the "hits of the season" section.', 'elita-tour-core' ),
			'id'              => '_elita_tour_hit',
			'type'            => 'checkbox',
			'sanitization_cb' => 'elita_tour_core_sanitize_flag',
		)
	);
}
add_action( 'cmb2_admin_init', 'elita_tour_core_register_tour_metabox' );

/**
 * Register the CMB2 term fields for the tour taxonomies.
 *
 * @return void
 */
function elita_tour_core_register_term_fields() {
	if ( ! function_exists( 'new_cmb2_box' ) ) {
		return;
	}

	$category = new_cmb2_box(
		array(
			'id'           => 'elita_tour_core_category_fields',
			'title'        => esc_html__( 'Category settings', 'elita-tour-core' ),
			'object_types' => array( 'term' ),
			'taxonomies'   => array( 'tour_category' ),
		)
	);

	$category->add_field(
		array(
			'name'         => esc_html__( 'Image', 'elita-tour-core' ),
			'desc'         => esc_html__( 'Image used on the category panel.', 'elita-tour-core' ),
			'id'           => '_elita_tour_image',
			'type'         => 'file',
			'options'      => array(
				'url' => false,
			),
			'text'         => array(
				'add_upload_file_text' => esc_html__( 'Add image', 'elita-tour-core' ),
			),
			'query_args'   => array(
				'type' => array( 'image/jpeg', 'image/png', 'image/webp' ),
			),
			'preview_size' => 'medium',
		)
	);

	$category->add_field(
		array(
			'name'            => esc_html__( 'Short description', 'elita-tour-core' ),
			'desc'            => esc_html__( 'One line shown under the category title.', 'elita-tour-core' ),
			'id'              => '_elita_tour_short_desc',
			'type'            => 'textarea_small',
			'sanitization_cb' => 'sanitize_text_field',
		)
	);

	$category->add_field(
		array(
			'name'            => esc_html__( 'Colour', 'elita-tour-core' ),
			'desc'            => esc_html__( 'Badge colour. Leave on "Automatic" to derive it from the slug.', 'elita-tour-core' ),
			'id'              => '_elita_tour_color',
			'type'            => 'select',
			'default'         => '',
			'sanitization_cb' => 'elita_tour_core_sanitize_color',
			'options'         => array(
				''          => esc_html__( 'Automatic', 'elita-tour-core' ),
				'camps'     => esc_html__( 'Camps', 'elita-tour-core' ),
				'europe'    => esc_html__( 'Europe', 'elita-tour-core' ),
				'ukraine'   => esc_html__( 'Ukraine', 'elita-tour-core' ),
				'adventure' => esc_html__( 'Adventure', 'elita-tour-core' ),
			),
		)
	);

	$season = new_cmb2_box(
		array(
			'id'           => 'elita_tour_core_season_fields',
			'title'        => esc_html__( 'Season settings', 'elita-tour-core' ),
			'object_types' => array( 'term' ),
			'taxonomies'   => array( 'tour_season' ),
		)
	);

	$season->add_field(
		array(
			'name'            => esc_html__( 'Order', 'elita-tour-core' ),
			'desc'            => esc_html__( 'Lower numbers come first in the season tabs.', 'elita-tour-core' ),
			'id'              => '_elita_tour_order',
			'type'            => 'text_small',
			'sanitization_cb' => 'absint',
			'attributes'      => array(
				'type' => 'number',
				'min'  => 0,
				'step' => 1,
			),
		)
	);
}
add_action( 'cmb2_admin_init', 'elita_tour_core_register_term_fields' );
