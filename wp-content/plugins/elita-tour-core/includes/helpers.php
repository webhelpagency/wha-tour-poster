<?php
/**
 * Public helpers used by the Elita Tour theme.
 *
 * Every function is wrapped in `function_exists()` so that a theme can ship its
 * own fallbacks and so that the plugin can be deactivated safely.
 *
 * @package Elita_Tour_Core
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'elita_tour_core_get_meta' ) ) {
	/**
	 * Read every tour meta field at once.
	 *
	 * @param int $post_id Optional. Tour ID. Defaults to the current post.
	 * @return array {
	 *     @type int      $days           Duration in days.
	 *     @type string   $transport      One of `bus`, `plane`, `train`, `mixed` or an empty string.
	 *     @type string   $price          Price label, empty when the price is on request.
	 *     @type string[] $route          Ordered route stops.
	 *     @type array[]  $dates          Departure rows with `start`, `end` and `status` keys.
	 *     @type bool     $hit            Whether the tour is a season hit.
	 *     @type string   $next_departure Computed next departure date, `Y-m-d` or an empty string.
	 * }
	 */
	function elita_tour_core_get_meta( $post_id = 0 ) {
		$post_id = $post_id ? (int) $post_id : (int) get_the_ID();

		$defaults = array(
			'days'           => 0,
			'transport'      => '',
			'price'          => '',
			'route'          => array(),
			'dates'          => array(),
			'hit'            => false,
			'next_departure' => '',
		);

		if ( $post_id <= 0 ) {
			return $defaults;
		}

		$route = get_post_meta( $post_id, '_elita_tour_route', true );
		$dates = get_post_meta( $post_id, '_elita_tour_dates', true );

		return array(
			'days'           => absint( get_post_meta( $post_id, '_elita_tour_days', true ) ),
			'transport'      => elita_tour_core_sanitize_transport( get_post_meta( $post_id, '_elita_tour_transport', true ) ),
			'price'          => (string) get_post_meta( $post_id, '_elita_tour_price', true ),
			'route'          => elita_tour_core_sanitize_route( $route ),
			'dates'          => elita_tour_core_sanitize_dates( $dates ),
			'hit'            => '' !== (string) get_post_meta( $post_id, '_elita_tour_hit', true ),
			'next_departure' => (string) get_post_meta( $post_id, '_elita_tour_next_departure', true ),
		);
	}
}

if ( ! function_exists( 'elita_tour_core_get_tours' ) ) {
	/**
	 * Query tours.
	 *
	 * Tours are ordered by the computed `_elita_tour_next_departure` meta, soonest
	 * first; tours without any departure date are listed last.
	 *
	 * @param array $args {
	 *     Optional. Query arguments.
	 *
	 *     @type string   $season   `tour_season` slug.
	 *     @type string   $category `tour_category` slug.
	 *     @type string   $country  `tour_country` slug.
	 *     @type bool     $hit      Limit to tours flagged as season hits.
	 *     @type int      $limit    Number of tours. Default 8. `-1` for all.
	 *     @type int[]    $exclude  Tour IDs to skip.
	 *     @type bool     $upcoming Limit to tours departing today or later.
	 *     @type int      $paged    Page number.
	 *     @type string   $orderby  `next_departure` (default), or any WP_Query value.
	 *     @type string   $order    `ASC` (default) or `DESC`.
	 *     @type string   $search   Search term.
	 * }
	 * @return WP_Query
	 */
	function elita_tour_core_get_tours( array $args = array() ) {
		$args = wp_parse_args(
			$args,
			array(
				'season'   => '',
				'category' => '',
				'country'  => '',
				'hit'      => false,
				'limit'    => 8,
				'exclude'  => array(),
				'upcoming' => false,
				'paged'    => 0,
				'orderby'  => 'next_departure',
				'order'    => 'ASC',
				'search'   => '',
			)
		);

		$query_args = array(
			'post_type'           => 'tour',
			'post_status'         => 'publish',
			'posts_per_page'      => (int) $args['limit'],
			'ignore_sticky_posts' => true,
			'no_found_rows'       => empty( $args['paged'] ),
		);

		if ( ! empty( $args['paged'] ) ) {
			$query_args['paged']         = absint( $args['paged'] );
			$query_args['no_found_rows'] = false;
		}

		if ( ! empty( $args['exclude'] ) ) {
			$query_args['post__not_in'] = array_map( 'absint', (array) $args['exclude'] );
		}

		if ( '' !== $args['search'] ) {
			$query_args['s'] = sanitize_text_field( $args['search'] );
		}

		$tax_query = array();

		foreach ( array(
			'season'   => 'tour_season',
			'category' => 'tour_category',
			'country'  => 'tour_country',
		) as $arg => $taxonomy ) {
			if ( empty( $args[ $arg ] ) ) {
				continue;
			}

			$tax_query[] = array(
				'taxonomy' => $taxonomy,
				'field'    => 'slug',
				'terms'    => array_map( 'sanitize_title', (array) $args[ $arg ] ),
			);
		}

		if ( count( $tax_query ) > 1 ) {
			$tax_query['relation'] = 'AND';
		}

		if ( $tax_query ) {
			$query_args['tax_query'] = $tax_query; // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
		}

		$meta_query = array();

		if ( ! empty( $args['hit'] ) ) {
			$meta_query[] = array(
				'key'     => '_elita_tour_hit',
				'value'   => '1',
				'compare' => '=',
			);
		}

		$order = 'DESC' === strtoupper( (string) $args['order'] ) ? 'DESC' : 'ASC';

		if ( ! empty( $args['upcoming'] ) ) {
			$meta_query['elita_next_departure'] = array(
				'key'     => '_elita_tour_next_departure',
				'value'   => current_time( 'Y-m-d' ),
				'compare' => '>=',
				'type'    => 'DATE',
			);

			$query_args['orderby'] = array( 'elita_next_departure' => $order );
		} elseif ( 'next_departure' === $args['orderby'] ) {
			// Ordered through `posts_clauses` so that tours without a departure date are kept, but listed last.
			$query_args['elita_tour_core_nulls_last'] = $order;
			$query_args['orderby']                    = 'date';
			$query_args['order']                      = 'DESC';
		} else {
			$query_args['orderby'] = sanitize_key( (string) $args['orderby'] );
			$query_args['order']   = $order;
		}

		if ( $meta_query ) {
			$query_args['meta_query'] = $meta_query; // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
		}

		/**
		 * Filter the arguments used by `elita_tour_core_get_tours()`.
		 *
		 * @param array $query_args WP_Query arguments.
		 * @param array $args       Helper arguments.
		 */
		$query_args = apply_filters( 'elita_tour_core_get_tours_args', $query_args, $args );

		return new WP_Query( $query_args );
	}
}

if ( ! function_exists( 'elita_tour_core_get_term_image_id' ) ) {
	/**
	 * Attachment ID stored for a tour category term.
	 *
	 * @param int $term_id Term ID.
	 * @return int Attachment ID, or 0 when the term has no image.
	 */
	function elita_tour_core_get_term_image_id( $term_id ) {
		$term_id = (int) $term_id;

		if ( $term_id <= 0 ) {
			return 0;
		}

		return absint( get_term_meta( $term_id, '_elita_tour_image_id', true ) );
	}
}

if ( ! function_exists( 'elita_tour_core_next_date' ) ) {
	/**
	 * The nearest departure that is still open for booking.
	 *
	 * @param array $dates Departure rows.
	 * @return array|null {
	 *     Null when there is no open departure.
	 *
	 *     @type string $start  Start date, `Y-m-d`.
	 *     @type string $end    End date, `Y-m-d` or an empty string.
	 *     @type string $status `ok` or `few`.
	 *     @type string $label  Human readable range, for example `24.10 – 29.10`.
	 * }
	 */
	function elita_tour_core_next_date( $dates ) {
		$dates = elita_tour_core_sanitize_dates( $dates );

		if ( empty( $dates ) ) {
			return null;
		}

		$today = current_time( 'Y-m-d' );
		$found = null;

		foreach ( $dates as $row ) {
			if ( 'closed' === $row['status'] || $row['start'] < $today ) {
				continue;
			}

			if ( null === $found || $row['start'] < $found['start'] ) {
				$found = $row;
			}
		}

		if ( null === $found ) {
			return null;
		}

		$found['label'] = elita_tour_core_date_range_label( $found['start'], $found['end'] );

		return $found;
	}
}

if ( ! function_exists( 'elita_tour_core_date_range_label' ) ) {
	/**
	 * Format a departure range as `24.10 – 29.10`.
	 *
	 * @param string $start Start date, `Y-m-d`.
	 * @param string $end   Optional. End date, `Y-m-d`.
	 * @return string
	 */
	function elita_tour_core_date_range_label( $start, $end = '' ) {
		$start = elita_tour_core_sanitize_date( $start );
		$end   = elita_tour_core_sanitize_date( $end );

		if ( '' === $start ) {
			return '';
		}

		$start_label = gmdate( 'd.m', (int) strtotime( $start . ' 00:00:00 +0000' ) );

		if ( '' === $end || $end === $start ) {
			return $start_label;
		}

		$end_label = gmdate( 'd.m', (int) strtotime( $end . ' 00:00:00 +0000' ) );

		/* translators: 1: start date, 2: end date. */
		return sprintf( _x( '%1$s – %2$s', 'departure date range', 'elita-tour-core' ), $start_label, $end_label );
	}
}

if ( ! function_exists( 'elita_tour_core_format_dates' ) ) {
	/**
	 * Format the departure dates as `24.10 – 29.10 +1`.
	 *
	 * The nearest open departure is shown as a range, followed by the number of
	 * further upcoming departures.
	 *
	 * @param array $dates Departure rows.
	 * @return string Empty string when there is nothing to show.
	 */
	function elita_tour_core_format_dates( array $dates ) {
		$dates = elita_tour_core_sanitize_dates( $dates );

		if ( empty( $dates ) ) {
			return '';
		}

		$next = elita_tour_core_next_date( $dates );

		if ( null === $next ) {
			return '';
		}

		$label = $next['label'];
		$rest  = 0;

		foreach ( $dates as $row ) {
			if ( 'closed' === $row['status'] || $row['start'] <= $next['start'] ) {
				continue;
			}

			++$rest;
		}

		if ( $rest > 0 ) {
			/* translators: 1: nearest departure range, 2: number of further departures. */
			$label = sprintf( _x( '%1$s +%2$d', 'departure dates summary', 'elita-tour-core' ), $label, $rest );
		}

		return $label;
	}
}

if ( ! function_exists( 'elita_tour_core_transport_label' ) ) {
	/**
	 * Translated label for a transport key.
	 *
	 * @param string $key Transport key.
	 * @return string Empty string for an unknown key.
	 */
	function elita_tour_core_transport_label( $key ) {
		$labels = array(
			'bus'   => __( 'Bus', 'elita-tour-core' ),
			'plane' => __( 'Plane', 'elita-tour-core' ),
			'train' => __( 'Train', 'elita-tour-core' ),
			'mixed' => __( 'Combined', 'elita-tour-core' ),
		);

		$key = elita_tour_core_sanitize_transport( $key );

		return isset( $labels[ $key ] ) ? $labels[ $key ] : '';
	}
}

if ( ! function_exists( 'elita_tour_core_transport_icon' ) ) {
	/**
	 * Icon name the theme sprite should use for a transport key.
	 *
	 * @param string $key Transport key.
	 * @return string Icon name, `bus` by default.
	 */
	function elita_tour_core_transport_icon( $key ) {
		$icons = array(
			'bus'   => 'bus',
			'plane' => 'plane',
			'train' => 'bus',
			'mixed' => 'bus',
		);

		$key = elita_tour_core_sanitize_transport( $key );

		return isset( $icons[ $key ] ) ? $icons[ $key ] : 'bus';
	}
}

if ( ! function_exists( 'elita_tour_core_category_color' ) ) {
	/**
	 * Colour key of a tour category.
	 *
	 * Falls back to a key derived from the term slug when no colour is stored.
	 *
	 * @param int|WP_Term $term Term object or term ID.
	 * @return string One of `camps`, `europe`, `ukraine`, `adventure`.
	 */
	function elita_tour_core_category_color( $term ) {
		if ( is_numeric( $term ) ) {
			$term = get_term( (int) $term, 'tour_category' );
		}

		if ( ! $term instanceof WP_Term ) {
			return 'camps';
		}

		$color = elita_tour_core_sanitize_color( get_term_meta( $term->term_id, '_elita_tour_color', true ) );

		if ( '' !== $color ) {
			return $color;
		}

		$slug     = $term->slug;
		$patterns = array(
			'camps'     => array( 'camp', 'tabir', 'tabor' ),
			'europe'    => array( 'europe', 'evrop', 'yevrop' ),
			'ukraine'   => array( 'ukrain', 'ukrayin' ),
			'adventure' => array( 'adventure', 'pryhod', 'prygod', 'active' ),
		);

		foreach ( $patterns as $key => $needles ) {
			foreach ( $needles as $needle ) {
				if ( false !== strpos( $slug, $needle ) ) {
					return $key;
				}
			}
		}

		$colors = elita_tour_core_get_colors();

		return $colors[ absint( crc32( $slug ) ) % count( $colors ) ];
	}
}

if ( ! function_exists( 'elita_tour_core_price_label' ) ) {
	/**
	 * Price label, or the "on request" fallback.
	 *
	 * @param string $price Stored price.
	 * @return string
	 */
	function elita_tour_core_price_label( $price ) {
		$price = is_scalar( $price ) ? trim( (string) $price ) : '';

		return '' !== $price ? $price : __( 'on request', 'elita-tour-core' );
	}
}

/**
 * Order tours by their computed next departure date, keeping tours without one last.
 *
 * Implemented with a dedicated LEFT JOIN instead of `meta_key`/`meta_value`,
 * because a meta query would drop the tours that have no departure date at all.
 *
 * @param string[] $clauses SQL clauses of the query.
 * @param WP_Query $query   Query being filtered.
 * @return string[]
 */
function elita_tour_core_next_departure_clauses( $clauses, $query ) {
	global $wpdb;

	if ( ! $query instanceof WP_Query ) {
		return $clauses;
	}

	$order = $query->get( 'elita_tour_core_nulls_last' );

	if ( ! $order ) {
		return $clauses;
	}

	$order = 'DESC' === strtoupper( (string) $order ) ? 'DESC' : 'ASC';

	$clauses['join'] .= $wpdb->prepare(
		" LEFT JOIN {$wpdb->postmeta} AS elita_nd ON ( {$wpdb->posts}.ID = elita_nd.post_id AND elita_nd.meta_key = %s )",
		'_elita_tour_next_departure'
	);

	$clauses['orderby'] = "CASE WHEN elita_nd.meta_value IS NULL OR elita_nd.meta_value = '' THEN 1 ELSE 0 END ASC, elita_nd.meta_value {$order}, {$wpdb->posts}.post_date DESC";

	return $clauses;
}
add_filter( 'posts_clauses', 'elita_tour_core_next_departure_clauses', 10, 2 );
