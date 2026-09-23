<?php
/**
 * Theme side wrappers around the Elita Tour Core plugin.
 *
 * Every function in this file works with and without the companion plugin: the
 * plugin provides the `tour` post type together with its taxonomies, the theme
 * falls back to the standard posts and categories so the homepage keeps its
 * layout when the plugin is deactivated.
 *
 * @package Elita_Tour
 */

if ( ! function_exists( 'elita_tour_has_core' ) ) :
	/**
	 * Whether the Elita Tour Core plugin is active and ready.
	 *
	 * @return bool True when the plugin helpers and the `tour` post type exist.
	 */
	function elita_tour_has_core() {
		return function_exists( 'elita_tour_core_get_tours' ) && post_type_exists( 'tour' );
	}
endif;

if ( ! function_exists( 'elita_tour_archive_link' ) ) :
	/**
	 * URL of the programme catalogue.
	 *
	 * @return string Tour archive URL, the blog page URL as a fallback.
	 */
	function elita_tour_archive_link() {
		if ( elita_tour_has_core() ) {
			$link = get_post_type_archive_link( 'tour' );

			if ( $link ) {
				return $link;
			}
		}

		$posts_page = (int) get_option( 'page_for_posts' );

		if ( $posts_page > 0 && 'publish' === get_post_status( $posts_page ) ) {
			return (string) get_permalink( $posts_page );
		}

		return home_url( '/' );
	}
endif;

if ( ! function_exists( 'elita_tour_term_color' ) ) :
	/**
	 * Colour key used by the `badge--cat-*` modifiers.
	 *
	 * @param WP_Term|null $term Term object.
	 * @return string One of `camps`, `europe`, `ukraine`, `adventure`.
	 */
	function elita_tour_term_color( $term ) {
		$colors = array( 'camps', 'europe', 'ukraine', 'adventure' );

		if ( ! $term instanceof WP_Term ) {
			return $colors[0];
		}

		if ( 'tour_category' === $term->taxonomy && function_exists( 'elita_tour_core_category_color' ) ) {
			return elita_tour_core_category_color( $term );
		}

		// Without the plugin the badge colour is derived from the term slug, so
		// that a given category always gets the same colour.
		return $colors[ absint( crc32( $term->slug ) ) % count( $colors ) ];
	}
endif;

if ( ! function_exists( 'elita_tour_get_post_term' ) ) :
	/**
	 * Primary term shown on a poster card.
	 *
	 * @param int $post_id Post ID.
	 * @return WP_Term|null Term object, or null when the post has no term.
	 */
	function elita_tour_get_post_term( $post_id ) {
		$taxonomy = 'category';

		if ( 'tour' === get_post_type( $post_id ) && taxonomy_exists( 'tour_category' ) ) {
			$taxonomy = 'tour_category';
		}

		$terms = get_the_terms( $post_id, $taxonomy );

		if ( is_wp_error( $terms ) || empty( $terms ) ) {
			return null;
		}

		return reset( $terms );
	}
endif;

if ( ! function_exists( 'elita_tour_get_home_categories' ) ) :
	/**
	 * The four categories shown as hero panels.
	 *
	 * @return array[] List of rows with `name`, `link`, `count`, `image_id`,
	 *                 `description` and `color` keys. Empty when there is no term.
	 */
	function elita_tour_get_home_categories() {
		$taxonomy = elita_tour_has_core() && taxonomy_exists( 'tour_category' ) ? 'tour_category' : 'category';

		$terms = get_terms(
			array(
				'taxonomy'   => $taxonomy,
				'hide_empty' => true,
				'orderby'    => 'count',
				'order'      => 'DESC',
				'number'     => 4,
			)
		);

		if ( is_wp_error( $terms ) || empty( $terms ) ) {
			return array();
		}

		$categories = array();

		foreach ( $terms as $term ) {
			$image_id    = 0;
			$description = trim( (string) $term->description );

			if ( 'tour_category' === $taxonomy ) {
				if ( function_exists( 'elita_tour_core_get_term_image_id' ) ) {
					$image_id = elita_tour_core_get_term_image_id( $term->term_id );
				}

				$short = trim( (string) get_term_meta( $term->term_id, '_elita_tour_short_desc', true ) );

				if ( '' !== $short ) {
					$description = $short;
				}
			}

			$categories[] = array(
				'name'        => $term->name,
				'link'        => get_term_link( $term ),
				'count'       => (int) $term->count,
				'image_id'    => (int) $image_id,
				'description' => $description,
				'color'       => elita_tour_term_color( $term ),
			);
		}

		return $categories;
	}
endif;

if ( ! function_exists( 'elita_tour_get_seasons' ) ) :
	/**
	 * Season terms in the order set by the plugin.
	 *
	 * @return WP_Term[] Terms, empty without the companion plugin.
	 */
	function elita_tour_get_seasons() {
		if ( ! elita_tour_has_core() || ! taxonomy_exists( 'tour_season' ) ) {
			return array();
		}

		$terms = get_terms(
			array(
				'taxonomy'   => 'tour_season',
				'hide_empty' => true,
				'orderby'    => 'meta_value_num',
				'meta_key'   => '_elita_tour_order', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key -- Four season terms, ordered by the plugin's sort index.
				'order'      => 'ASC',
			)
		);

		if ( is_wp_error( $terms ) || empty( $terms ) ) {
			return array();
		}

		return $terms;
	}
endif;

if ( ! function_exists( 'elita_tour_get_season_tours' ) ) :
	/**
	 * Tours of one season.
	 *
	 * @param string $slug Season slug.
	 * @return WP_Query Query object, empty without the companion plugin.
	 */
	function elita_tour_get_season_tours( $slug ) {
		if ( ! elita_tour_has_core() ) {
			return new WP_Query( array( 'post__in' => array( 0 ) ) );
		}

		return elita_tour_core_get_tours(
			array(
				'season' => sanitize_title( $slug ),
				'limit'  => 8,
			)
		);
	}
endif;

if ( ! function_exists( 'elita_tour_get_hit_tours' ) ) :
	/**
	 * The programmes shown in the "season hits" rail.
	 *
	 * Without the companion plugin the rail falls back to the latest posts, with
	 * the sticky ones first.
	 *
	 * @return WP_Query Query object.
	 */
	function elita_tour_get_hit_tours() {
		if ( elita_tour_has_core() ) {
			return elita_tour_core_get_tours(
				array(
					'hit'   => true,
					'limit' => 8,
				)
			);
		}

		$query = new WP_Query(
			array(
				'post_type'           => 'post',
				'post_status'         => 'publish',
				'posts_per_page'      => 8,
				'ignore_sticky_posts' => true,
				'no_found_rows'       => true,
			)
		);

		$sticky = array_map( 'absint', (array) get_option( 'sticky_posts' ) );

		if ( ! empty( $sticky ) && ! empty( $query->posts ) ) {
			$first = array();
			$rest  = array();

			foreach ( $query->posts as $hit_post ) {
				if ( in_array( (int) $hit_post->ID, $sticky, true ) ) {
					$first[] = $hit_post;
				} else {
					$rest[] = $hit_post;
				}
			}

			$query->posts = array_merge( $first, $rest );
		}

		return $query;
	}
endif;

if ( ! function_exists( 'elita_tour_current_season_slug' ) ) :
	/**
	 * Season matching the current month.
	 *
	 * Uses the same mapping as assets/js/main.js: 12–2 winter, 3–5 spring,
	 * 6–8 summer, 9–11 autumn.
	 *
	 * @return string Season slug.
	 */
	function elita_tour_current_season_slug() {
		$month = (int) current_time( 'n' );

		if ( 12 === $month || $month <= 2 ) {
			return 'winter';
		}

		if ( $month <= 5 ) {
			return 'spring';
		}

		if ( $month <= 8 ) {
			return 'summer';
		}

		return 'autumn';
	}
endif;

if ( ! function_exists( 'elita_tour_pre_get_posts' ) ) :
	/**
	 * Tunes the main query for the catalogue and for the search results.
	 *
	 * The catalogue and the tour taxonomy archives are sorted by the next
	 * departure the companion plugin stores for every tour, with the very
	 * ordering `elita_tour_core_get_tours()` uses, so that the archives and the
	 * homepage rails agree. Search results list programmes next to the posts
	 * and pages. Without the plugin nothing is changed.
	 *
	 * @param WP_Query $query Query that is about to run.
	 */
	function elita_tour_pre_get_posts( $query ) {
		if ( is_admin() || ! $query instanceof WP_Query || ! $query->is_main_query() ) {
			return;
		}

		if ( ! elita_tour_has_core() ) {
			return;
		}

		if ( $query->is_search() ) {
			// Only when the search was not already limited to a post type:
			// the searchable types, with the programmes added to them.
			if ( ! $query->get( 'post_type' ) ) {
				$elita_tour_types = get_post_types( array( 'exclude_from_search' => false ) );

				// Media is searchable by default, but an attachment among the
				// programmes and posts is never a useful result here.
				unset( $elita_tour_types['attachment'] );

				$elita_tour_types['tour'] = 'tour';

				$query->set( 'post_type', array_values( $elita_tour_types ) );
			}

			return;
		}

		if ( ! $query->is_post_type_archive( 'tour' ) && ! $query->is_tax( array( 'tour_category', 'tour_season', 'tour_country' ) ) ) {
			return;
		}

		// The plugin turns this query var into a LEFT JOIN on the computed
		// `_elita_tour_next_departure` meta through `posts_clauses`: soonest
		// departure first, tours without a departure date last.
		$query->set( 'elita_tour_core_nulls_last', 'ASC' );
		$query->set( 'orderby', 'date' );
		$query->set( 'order', 'DESC' );
	}
endif;
add_action( 'pre_get_posts', 'elita_tour_pre_get_posts' );
