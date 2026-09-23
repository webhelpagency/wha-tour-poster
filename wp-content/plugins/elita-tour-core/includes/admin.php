<?php
/**
 * Admin list table columns.
 *
 * @package Elita_Tour_Core
 */

defined( 'ABSPATH' ) || exit;

/**
 * Add the tour columns.
 *
 * @param string[] $columns Existing columns.
 * @return string[]
 */
function elita_tour_core_tour_columns( $columns ) {
	$new = array();

	foreach ( $columns as $key => $label ) {
		$new[ $key ] = $label;

		if ( 'title' === $key ) {
			$new['elita_days']  = esc_html__( 'Days', 'elita-tour-core' );
			$new['elita_price'] = esc_html__( 'Price', 'elita-tour-core' );
			$new['elita_next']  = esc_html__( 'Next departure', 'elita-tour-core' );
			$new['elita_hit']   = esc_html__( 'Hit', 'elita-tour-core' );
		}
	}

	return $new;
}
add_filter( 'manage_tour_posts_columns', 'elita_tour_core_tour_columns' );

/**
 * Render the tour columns.
 *
 * @param string $column  Column key.
 * @param int    $post_id Tour ID.
 * @return void
 */
function elita_tour_core_tour_column( $column, $post_id ) {
	if ( 0 !== strpos( $column, 'elita_' ) ) {
		return;
	}

	$meta = elita_tour_core_get_meta( $post_id );

	switch ( $column ) {
		case 'elita_days':
			echo $meta['days'] > 0 ? esc_html( number_format_i18n( $meta['days'] ) ) : '—';
			break;

		case 'elita_price':
			echo esc_html( elita_tour_core_price_label( $meta['price'] ) );
			break;

		case 'elita_next':
			if ( '' === $meta['next_departure'] ) {
				echo '—';
				break;
			}

			echo esc_html( mysql2date( get_option( 'date_format' ), $meta['next_departure'] . ' 00:00:00' ) );
			break;

		case 'elita_hit':
			echo $meta['hit'] ? esc_html__( 'Yes', 'elita-tour-core' ) : '—';
			break;
	}
}
add_action( 'manage_tour_posts_custom_column', 'elita_tour_core_tour_column', 10, 2 );

/**
 * Make the next departure column sortable.
 *
 * @param array $columns Sortable columns.
 * @return array
 */
function elita_tour_core_tour_sortable_columns( $columns ) {
	$columns['elita_next'] = 'elita_next';

	return $columns;
}
add_filter( 'manage_edit-tour_sortable_columns', 'elita_tour_core_tour_sortable_columns' );

/**
 * Sort the tour list by the next departure date.
 *
 * @param WP_Query $query Current admin query.
 * @return void
 */
function elita_tour_core_admin_sort( $query ) {
	if ( ! is_admin() || ! $query->is_main_query() || 'tour' !== $query->get( 'post_type' ) ) {
		return;
	}

	if ( 'elita_next' !== $query->get( 'orderby' ) ) {
		return;
	}

	$query->set( 'meta_key', '_elita_tour_next_departure' ); // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
	$query->set( 'orderby', 'meta_value' );
}
add_action( 'pre_get_posts', 'elita_tour_core_admin_sort' );

/**
 * Add the lead columns.
 *
 * @param string[] $columns Existing columns.
 * @return string[]
 */
function elita_tour_core_lead_columns( $columns ) {
	$new = array();

	foreach ( $columns as $key => $label ) {
		$new[ $key ] = $label;

		if ( 'title' === $key ) {
			$new['elita_lead_phone'] = esc_html__( 'Phone', 'elita-tour-core' );
		}
	}

	return $new;
}
add_filter( 'manage_elita_lead_posts_columns', 'elita_tour_core_lead_columns' );

/**
 * Render the lead columns.
 *
 * @param string $column  Column key.
 * @param int    $post_id Lead ID.
 * @return void
 */
function elita_tour_core_lead_column( $column, $post_id ) {
	if ( 'elita_lead_phone' !== $column ) {
		return;
	}

	$phone = (string) get_post_meta( $post_id, '_elita_tour_core_lead_phone', true );

	echo '' !== $phone ? esc_html( $phone ) : '—';
}
add_action( 'manage_elita_lead_posts_custom_column', 'elita_tour_core_lead_column', 10, 2 );

/**
 * Show the stored lead details on the lead edit screen.
 *
 * @return void
 */
function elita_tour_core_lead_metabox() {
	add_meta_box(
		'elita_tour_core_lead_details',
		esc_html__( 'Request details', 'elita-tour-core' ),
		'elita_tour_core_lead_metabox_render',
		'elita_lead',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes_elita_lead', 'elita_tour_core_lead_metabox' );

/**
 * Render the lead details metabox.
 *
 * @param WP_Post $post Lead post.
 * @return void
 */
function elita_tour_core_lead_metabox_render( $post ) {
	$fields = array(
		'_elita_tour_core_lead_name'    => esc_html__( 'Name', 'elita-tour-core' ),
		'_elita_tour_core_lead_phone'   => esc_html__( 'Phone', 'elita-tour-core' ),
		'_elita_tour_core_lead_message' => esc_html__( 'Comment', 'elita-tour-core' ),
		'_elita_tour_core_lead_source'  => esc_html__( 'Page', 'elita-tour-core' ),
	);

	echo '<table class="widefat striped"><tbody>';

	foreach ( $fields as $key => $label ) {
		$value = (string) get_post_meta( $post->ID, $key, true );

		echo '<tr><th scope="row">' . esc_html( $label ) . '</th><td>';

		if ( '_elita_tour_core_lead_source' === $key && '' !== $value ) {
			echo '<a href="' . esc_url( $value ) . '">' . esc_html( $value ) . '</a>';
		} else {
			echo esc_html( '' !== $value ? $value : '—' );
		}

		echo '</td></tr>';
	}

	echo '</tbody></table>';
}
