<?php
/**
 * Demo content importer.
 *
 * Creates the sample catalogue shipped in demo/content.php: tour categories,
 * seasons, countries, twelve tours with photographs, the pages of the site, two
 * menus, the Customizer settings of the Elita Tour theme and the Reading
 * settings. Everything it creates is tagged with the `_elita_tour_core_demo`
 * meta key, so a second run skips what is already there.
 *
 * Available under Tours → Import demo content and as `wp elita-tour demo import`.
 *
 * @package Elita_Tour_Core
 */

defined( 'ABSPATH' ) || exit;

/**
 * Meta key that marks an imported post, attachment or term with its source key.
 */
define( 'ELITA_TOUR_CORE_DEMO_META', '_elita_tour_core_demo' );

/**
 * Read the demo content definition.
 *
 * @return array Demo content, or an empty array when the file is missing.
 */
function elita_tour_core_demo_content() {
	static $content = null;

	if ( null !== $content ) {
		return $content;
	}

	$file = ELITA_TOUR_CORE_PATH . 'demo/content.php';

	$content = file_exists( $file ) ? (array) require $file : array();

	return $content;
}

/**
 * Empty summary structure.
 *
 * @return array Counters keyed by group, plus an `errors` list.
 */
function elita_tour_core_demo_summary() {
	$counts = array(
		'created' => 0,
		'skipped' => 0,
	);

	return array(
		'images'     => $counts,
		'categories' => $counts,
		'seasons'    => $counts,
		'countries'  => $counts,
		'tours'      => $counts,
		'pages'      => $counts,
		'menus'      => $counts,
		'theme_mods' => $counts,
		'options'    => $counts,
		'errors'     => array(),
	);
}

/**
 * Find a post, page or attachment created by an earlier import.
 *
 * @param string $key       Source key stored in the demo meta field.
 * @param string $post_type Post type to look in.
 * @return int Post ID, 0 when the item was not imported yet.
 */
function elita_tour_core_demo_find_post( $key, $post_type ) {
	$found = get_posts(
		array(
			'post_type'              => $post_type,
			'post_status'            => 'attachment' === $post_type ? 'inherit' : 'any',
			'posts_per_page'         => 1,
			'fields'                 => 'ids',
			'no_found_rows'          => true,
			'update_post_term_cache' => false,
			'meta_key'               => ELITA_TOUR_CORE_DEMO_META, // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
			'meta_value'             => $key,                      // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
		)
	);

	return empty( $found ) ? 0 : (int) $found[0];
}

/**
 * Find a term created by an earlier import.
 *
 * @param string $key      Source key stored in the demo meta field.
 * @param string $taxonomy Taxonomy to look in.
 * @return int Term ID, 0 when the term was not imported yet.
 */
function elita_tour_core_demo_find_term( $key, $taxonomy ) {
	$found = get_terms(
		array(
			'taxonomy'   => $taxonomy,
			'hide_empty' => false,
			'number'     => 1,
			'fields'     => 'ids',
			'meta_key'   => ELITA_TOUR_CORE_DEMO_META, // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
			'meta_value' => $key,                      // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
		)
	);

	if ( is_wp_error( $found ) || empty( $found ) ) {
		return 0;
	}

	return (int) $found[0];
}

/**
 * Import one of the bundled photographs into the media library.
 *
 * The file is copied to a temporary location first, so that the copy inside the
 * plugin folder stays where it is.
 *
 * @param string $file    File name inside demo/images/.
 * @param string $title   Attachment title and alternative text.
 * @param array  $summary Summary, passed by reference.
 * @return int Attachment ID, 0 on failure.
 */
function elita_tour_core_demo_image( $file, $title, &$summary ) {
	$file = basename( (string) $file );

	if ( '' === $file ) {
		return 0;
	}

	$existing = elita_tour_core_demo_find_post( 'image:' . $file, 'attachment' );

	if ( $existing ) {
		++$summary['images']['skipped'];

		return $existing;
	}

	$source = ELITA_TOUR_CORE_PATH . 'demo/images/' . $file;

	if ( ! file_exists( $source ) ) {
		/* translators: %s: image file name. */
		$summary['errors'][] = sprintf( __( 'Demo image %s is missing.', 'elita-tour-core' ), $file );

		return 0;
	}

	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';

	$tmp = wp_tempnam( $file );

	// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_copy -- The bundled file is copied to a temporary file for media_handle_sideload(); WP_Filesystem has no equivalent that keeps the source in place.
	if ( ! $tmp || ! copy( $source, $tmp ) ) {
		if ( $tmp ) {
			wp_delete_file( $tmp );
		}

		/* translators: %s: image file name. */
		$summary['errors'][] = sprintf( __( 'Could not prepare the demo image %s.', 'elita-tour-core' ), $file );

		return 0;
	}

	$attachment_id = media_handle_sideload(
		array(
			'name'     => $file,
			'tmp_name' => $tmp,
		),
		0,
		$title
	);

	if ( is_wp_error( $attachment_id ) ) {
		wp_delete_file( $tmp );

		$summary['errors'][] = sprintf(
			/* translators: 1: image file name, 2: error message. */
			__( 'Could not import the demo image %1$s: %2$s', 'elita-tour-core' ),
			$file,
			$attachment_id->get_error_message()
		);

		return 0;
	}

	update_post_meta( $attachment_id, ELITA_TOUR_CORE_DEMO_META, 'image:' . $file );
	update_post_meta( $attachment_id, '_wp_attachment_image_alt', $title );

	++$summary['images']['created'];

	return (int) $attachment_id;
}

/**
 * Create a demo term once.
 *
 * A term that already uses the slug is reused and left untouched.
 *
 * @param string $taxonomy Taxonomy.
 * @param array  $item     Term definition with `key` and `name`.
 * @param string $group    Summary group to count in.
 * @param array  $summary  Summary, passed by reference.
 * @return int Term ID, 0 on failure.
 */
function elita_tour_core_demo_term( $taxonomy, $item, $group, &$summary ) {
	$key = $taxonomy . ':' . $item['key'];

	$term_id = elita_tour_core_demo_find_term( $key, $taxonomy );

	if ( $term_id ) {
		++$summary[ $group ]['skipped'];

		return $term_id;
	}

	$existing = get_term_by( 'slug', $item['key'], $taxonomy );

	if ( $existing instanceof WP_Term ) {
		++$summary[ $group ]['skipped'];

		return (int) $existing->term_id;
	}

	$created = wp_insert_term( $item['name'], $taxonomy, array( 'slug' => $item['key'] ) );

	if ( is_wp_error( $created ) ) {
		$summary['errors'][] = sprintf(
			/* translators: 1: term name, 2: error message. */
			__( 'Could not create the term %1$s: %2$s', 'elita-tour-core' ),
			$item['name'],
			$created->get_error_message()
		);

		return 0;
	}

	$term_id = (int) $created['term_id'];

	update_term_meta( $term_id, ELITA_TOUR_CORE_DEMO_META, $key );

	++$summary[ $group ]['created'];

	return $term_id;
}

/**
 * Work out the next departure date of a tour.
 *
 * @param array  $dates Departure rows with a `start` key.
 * @param string $today Today's date, `Y-m-d`.
 * @return string Next departure date, or an empty string.
 */
function elita_tour_core_demo_next_departure( $dates, $today ) {
	$next = '';

	foreach ( (array) $dates as $date ) {
		if ( empty( $date['start'] ) || $date['start'] < $today ) {
			continue;
		}

		if ( '' === $next || $date['start'] < $next ) {
			$next = $date['start'];
		}
	}

	return $next;
}

/**
 * Import the demo content.
 *
 * Safe to run more than once: items created by an earlier run are skipped.
 *
 * @return array Summary with the created and skipped counts per group.
 */
function elita_tour_core_demo_import() {
	$summary = elita_tour_core_demo_summary();
	$content = elita_tour_core_demo_content();

	if ( empty( $content ) ) {
		$summary['errors'][] = __( 'The demo content file is missing.', 'elita-tour-core' );

		return $summary;
	}

	if ( ! post_type_exists( 'tour' ) ) {
		$summary['errors'][] = __( 'The Tour post type is not registered.', 'elita-tour-core' );

		return $summary;
	}

	// Sideloading a dozen photographs takes longer than a normal request.
	if ( function_exists( 'set_time_limit' ) ) {
		set_time_limit( 120 );
	}

	$terms = array(
		'tour_category' => array(),
		'tour_season'   => array(),
		'tour_country'  => array(),
	);

	// 1. Tour categories, with their colour, description and panel photograph.
	foreach ( $content['categories'] as $category ) {
		$term_id = elita_tour_core_demo_term( 'tour_category', $category, 'categories', $summary );

		if ( ! $term_id ) {
			continue;
		}

		$terms['tour_category'][ $category['key'] ] = $term_id;

		if ( '' === (string) get_term_meta( $term_id, '_elita_tour_color', true ) ) {
			update_term_meta( $term_id, '_elita_tour_color', $category['color'] );
		}

		if ( '' === (string) get_term_meta( $term_id, '_elita_tour_short_desc', true ) ) {
			update_term_meta( $term_id, '_elita_tour_short_desc', $category['short_desc'] );
		}

		if ( ! get_term_meta( $term_id, '_elita_tour_image_id', true ) ) {
			$image_id = elita_tour_core_demo_image( $category['image'], $category['name'], $summary );

			if ( $image_id ) {
				update_term_meta( $term_id, '_elita_tour_image_id', $image_id );
			}
		}
	}

	// 2. Seasons, in the order the front page shows them.
	foreach ( $content['seasons'] as $season ) {
		$term_id = elita_tour_core_demo_term( 'tour_season', $season, 'seasons', $summary );

		if ( ! $term_id ) {
			continue;
		}

		$terms['tour_season'][ $season['key'] ] = $term_id;

		/*
		 * A term form saved without a value leaves a 0 behind, which is not an
		 * order the demo content ever uses: treat it as "not set yet".
		 */
		if ( ! (int) get_term_meta( $term_id, '_elita_tour_order', true ) ) {
			update_term_meta( $term_id, '_elita_tour_order', (int) $season['order'] );
		}
	}

	// 3. Countries.
	foreach ( $content['countries'] as $country ) {
		$term_id = elita_tour_core_demo_term( 'tour_country', $country, 'countries', $summary );

		if ( $term_id ) {
			$terms['tour_country'][ $country['key'] ] = $term_id;
		}
	}

	// 4. Tours.
	$today = current_time( 'Y-m-d' );

	foreach ( $content['tours'] as $tour ) {
		$key = 'tour:' . $tour['key'];

		if ( elita_tour_core_demo_find_post( $key, 'tour' ) ) {
			++$summary['tours']['skipped'];
			continue;
		}

		$existing = get_page_by_path( $tour['key'], OBJECT, 'tour' );

		if ( $existing instanceof WP_Post ) {
			++$summary['tours']['skipped'];
			continue;
		}

		$post_id = wp_insert_post(
			array(
				'post_type'    => 'tour',
				'post_status'  => 'publish',
				'post_title'   => $tour['title'],
				'post_name'    => $tour['key'],
				'post_excerpt' => $tour['excerpt'],
				'post_content' => $tour['content'],
			),
			true
		);

		if ( is_wp_error( $post_id ) ) {
			$summary['errors'][] = sprintf(
				/* translators: 1: tour title, 2: error message. */
				__( 'Could not create the tour %1$s: %2$s', 'elita-tour-core' ),
				$tour['title'],
				$post_id->get_error_message()
			);
			continue;
		}

		update_post_meta( $post_id, ELITA_TOUR_CORE_DEMO_META, $key );

		if ( isset( $terms['tour_category'][ $tour['category'] ] ) ) {
			wp_set_object_terms( $post_id, array( (int) $terms['tour_category'][ $tour['category'] ] ), 'tour_category' );
		}

		$tour_taxonomies = array(
			'tour_season'  => 'seasons',
			'tour_country' => 'countries',
		);

		foreach ( $tour_taxonomies as $taxonomy => $field ) {
			$term_ids = array();

			foreach ( $tour[ $field ] as $term_key ) {
				if ( isset( $terms[ $taxonomy ][ $term_key ] ) ) {
					$term_ids[] = (int) $terms[ $taxonomy ][ $term_key ];
				}
			}

			wp_set_object_terms( $post_id, $term_ids, $taxonomy );
		}

		update_post_meta( $post_id, '_elita_tour_days', (int) $tour['meta']['days'] );
		update_post_meta( $post_id, '_elita_tour_transport', $tour['meta']['transport'] );
		update_post_meta( $post_id, '_elita_tour_price', $tour['meta']['price'] );
		update_post_meta( $post_id, '_elita_tour_route', $tour['meta']['route'] );
		update_post_meta( $post_id, '_elita_tour_dates', $tour['meta']['dates'] );
		update_post_meta( $post_id, '_elita_tour_hit', $tour['hit'] ? '1' : '' );
		update_post_meta( $post_id, '_elita_tour_next_departure', elita_tour_core_demo_next_departure( $tour['meta']['dates'], $today ) );

		$image_id = elita_tour_core_demo_image( $tour['image'], $tour['title'], $summary );

		if ( $image_id ) {
			set_post_thumbnail( $post_id, $image_id );
		}

		++$summary['tours']['created'];
	}

	// 5. Pages.
	$pages = array();

	foreach ( $content['pages'] as $page ) {
		$key      = 'page:' . $page['key'];
		$page_id  = elita_tour_core_demo_find_post( $key, 'page' );
		$existing = $page_id ? null : get_page_by_path( $page['key'] );

		if ( $page_id || $existing instanceof WP_Post ) {
			$pages[ $page['key'] ] = $page_id ? $page_id : (int) $existing->ID;
			++$summary['pages']['skipped'];
			continue;
		}

		$page_id = wp_insert_post(
			array(
				'post_type'    => 'page',
				'post_status'  => 'publish',
				'post_title'   => $page['title'],
				'post_name'    => $page['key'],
				'post_content' => $page['content'],
			),
			true
		);

		if ( is_wp_error( $page_id ) ) {
			$summary['errors'][] = sprintf(
				/* translators: 1: page title, 2: error message. */
				__( 'Could not create the page %1$s: %2$s', 'elita-tour-core' ),
				$page['title'],
				$page_id->get_error_message()
			);
			continue;
		}

		update_post_meta( $page_id, ELITA_TOUR_CORE_DEMO_META, $key );

		if ( '' !== $page['image'] ) {
			$image_id = elita_tour_core_demo_image( $page['image'], $page['title'], $summary );

			if ( $image_id ) {
				set_post_thumbnail( $page_id, $image_id );
			}
		}

		$pages[ $page['key'] ] = (int) $page_id;

		++$summary['pages']['created'];
	}

	// 6. Menus: only for theme locations that have no menu yet.
	elita_tour_core_demo_import_menus( $content['menus'], $terms['tour_category'], $pages, $summary );

	// 7. Customizer settings of the Elita Tour theme.
	elita_tour_core_demo_apply_theme_mods( $content['theme_mods'], $pages, $summary );

	// 8. Settings → Reading.
	elita_tour_core_demo_apply_reading( $content['reading'], $pages, $summary );

	return $summary;
}

/**
 * Create the demo menus and assign them to their theme locations.
 *
 * A location that already has a menu is left alone.
 *
 * @param array $menus      Menu definitions.
 * @param array $categories Term IDs of the demo categories, keyed by demo key.
 * @param array $pages      Page IDs of the demo pages, keyed by demo key.
 * @param array $summary    Summary, passed by reference.
 * @return void
 */
function elita_tour_core_demo_import_menus( $menus, $categories, $pages, &$summary ) {
	require_once ABSPATH . 'wp-admin/includes/nav-menu.php';

	$registered = get_registered_nav_menus();
	$locations  = get_nav_menu_locations();

	foreach ( $menus as $menu ) {
		if ( ! isset( $registered[ $menu['location'] ] ) || has_nav_menu( $menu['location'] ) ) {
			++$summary['menus']['skipped'];
			continue;
		}

		$object  = wp_get_nav_menu_object( $menu['name'] );
		$menu_id = $object ? (int) $object->term_id : 0;

		if ( ! $menu_id ) {
			$menu_id = wp_create_nav_menu( $menu['name'] );

			if ( is_wp_error( $menu_id ) ) {
				$summary['errors'][] = sprintf(
					/* translators: 1: menu name, 2: error message. */
					__( 'Could not create the menu %1$s: %2$s', 'elita-tour-core' ),
					$menu['name'],
					$menu_id->get_error_message()
				);
				continue;
			}

			$menu_id = (int) $menu_id;
		}

		$existing_titles = array();
		$items           = wp_get_nav_menu_items( $menu_id, array( 'post_status' => 'publish,draft' ) );

		foreach ( (array) $items as $item ) {
			$existing_titles[] = $item->title;
		}

		foreach ( $menu['items'] as $item ) {
			$args = array();

			if ( 'tour_category' === $item['type'] ) {
				if ( ! isset( $categories[ $item['object'] ] ) ) {
					continue;
				}

				$term = get_term( (int) $categories[ $item['object'] ], 'tour_category' );

				if ( ! $term instanceof WP_Term ) {
					continue;
				}

				$args = array(
					'menu-item-title'     => $term->name,
					'menu-item-object'    => 'tour_category',
					'menu-item-object-id' => (int) $term->term_id,
					'menu-item-type'      => 'taxonomy',
				);
			} elseif ( 'page' === $item['type'] ) {
				if ( empty( $pages[ $item['object'] ] ) ) {
					continue;
				}

				$args = array(
					'menu-item-title'     => get_the_title( (int) $pages[ $item['object'] ] ),
					'menu-item-object'    => 'page',
					'menu-item-object-id' => (int) $pages[ $item['object'] ],
					'menu-item-type'      => 'post_type',
				);
			} else {
				$args = array(
					'menu-item-title' => $item['title'],
					'menu-item-url'   => home_url( $item['path'] ),
					'menu-item-type'  => 'custom',
				);
			}

			if ( in_array( $args['menu-item-title'], $existing_titles, true ) ) {
				continue;
			}

			$args['menu-item-status'] = 'publish';

			wp_update_nav_menu_item( $menu_id, 0, $args );
		}

		$locations[ $menu['location'] ] = $menu_id;

		++$summary['menus']['created'];
	}

	// Only touch the theme mod when a location was actually filled.
	if ( $summary['menus']['created'] > 0 ) {
		set_theme_mod( 'nav_menu_locations', $locations );
	}
}

/**
 * Apply the Customizer settings of the Elita Tour theme.
 *
 * Only settings that have no value yet are written, and only while the Elita
 * Tour theme is the active theme.
 *
 * @param array $mods    Theme mods to apply.
 * @param array $pages   Page IDs of the demo pages, keyed by demo key.
 * @param array $summary Summary, passed by reference.
 * @return void
 */
function elita_tour_core_demo_apply_theme_mods( $mods, $pages, &$summary ) {
	$theme = wp_get_theme();

	if ( 'elita-tour' !== $theme->get( 'TextDomain' ) ) {
		$summary['theme_mods']['skipped'] += count( $mods );

		return;
	}

	if ( ! empty( $pages['pro-nas'] ) ) {
		$mods['elita_tour_about_page'] = (int) $pages['pro-nas'];
	}

	foreach ( $mods as $name => $value ) {
		$current = get_theme_mod( $name, '' );

		// Nothing to write for a demo value that is empty to begin with.
		if ( '' === (string) $value ) {
			++$summary['theme_mods']['skipped'];
			continue;
		}

		if ( '' !== $current && null !== $current && 0 !== $current ) {
			++$summary['theme_mods']['skipped'];
			continue;
		}

		set_theme_mod( $name, $value );

		++$summary['theme_mods']['created'];
	}
}

/**
 * Point Settings → Reading at the demo front page and blog page.
 *
 * Nothing happens unless the site still shows the latest posts on the front page.
 *
 * @param array $reading Reading settings definition.
 * @param array $pages   Page IDs of the demo pages, keyed by demo key.
 * @param array $summary Summary, passed by reference.
 * @return void
 */
function elita_tour_core_demo_apply_reading( $reading, $pages, &$summary ) {
	$front = empty( $pages[ $reading['front_page'] ] ) ? 0 : (int) $pages[ $reading['front_page'] ];
	$blog  = empty( $pages[ $reading['blog_page'] ] ) ? 0 : (int) $pages[ $reading['blog_page'] ];

	if ( 'posts' !== get_option( 'show_on_front' ) || ! $front ) {
		++$summary['options']['skipped'];

		return;
	}

	update_option( 'show_on_front', 'page' );
	update_option( 'page_on_front', $front );

	if ( $blog ) {
		update_option( 'page_for_posts', $blog );
	}

	++$summary['options']['created'];
}

/**
 * Turn a summary into printable lines.
 *
 * @param array $summary Import summary.
 * @return string[] Translated lines.
 */
function elita_tour_core_demo_summary_lines( $summary ) {
	$labels = array(
		'categories' => __( 'Tour categories', 'elita-tour-core' ),
		'seasons'    => __( 'Seasons', 'elita-tour-core' ),
		'countries'  => __( 'Countries', 'elita-tour-core' ),
		'tours'      => __( 'Tours', 'elita-tour-core' ),
		'images'     => __( 'Images', 'elita-tour-core' ),
		'pages'      => __( 'Pages', 'elita-tour-core' ),
		'menus'      => __( 'Menus', 'elita-tour-core' ),
		'theme_mods' => __( 'Customizer settings', 'elita-tour-core' ),
		'options'    => __( 'Reading settings', 'elita-tour-core' ),
	);

	$lines = array();

	foreach ( $labels as $group => $label ) {
		$lines[] = sprintf(
			/* translators: 1: group of demo items, 2: number of created items, 3: number of skipped items. */
			__( '%1$s: %2$d created, %3$d skipped', 'elita-tour-core' ),
			$label,
			(int) $summary[ $group ]['created'],
			(int) $summary[ $group ]['skipped']
		);
	}

	return $lines;
}

/**
 * Register the import screen under the Tours menu.
 *
 * @return void
 */
function elita_tour_core_demo_admin_menu() {
	$hook = add_submenu_page(
		'edit.php?post_type=tour',
		__( 'Import demo content', 'elita-tour-core' ),
		__( 'Import demo content', 'elita-tour-core' ),
		'manage_options',
		'elita-tour-core-demo',
		'elita_tour_core_demo_admin_page'
	);

	if ( $hook ) {
		add_action( 'load-' . $hook, 'elita_tour_core_demo_admin_load' );
	}
}
add_action( 'admin_menu', 'elita_tour_core_demo_admin_menu' );

/**
 * Run the import when the form was submitted, before anything is printed.
 *
 * @return void
 */
function elita_tour_core_demo_admin_load() {
	$method = isset( $_SERVER['REQUEST_METHOD'] ) ? strtoupper( sanitize_text_field( wp_unslash( $_SERVER['REQUEST_METHOD'] ) ) ) : 'GET';

	if ( 'POST' !== $method ) {
		return;
	}

	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You are not allowed to import demo content.', 'elita-tour-core' ), 403 );
	}

	check_admin_referer( 'elita_tour_core_demo_import' );

	$summary = elita_tour_core_demo_import();

	set_transient( 'elita_tour_core_demo_summary_' . get_current_user_id(), $summary, 5 * MINUTE_IN_SECONDS );

	wp_safe_redirect(
		add_query_arg(
			array(
				'post_type'           => 'tour',
				'page'                => 'elita-tour-core-demo',
				'elita-tour-imported' => '1',
			),
			admin_url( 'edit.php' )
		)
	);
	exit;
}

/**
 * Show the result of the last import.
 *
 * @return void
 */
function elita_tour_core_demo_admin_notice() {
	$screen = get_current_screen();

	if ( ! $screen || 'tour_page_elita-tour-core-demo' !== $screen->id ) {
		return;
	}

	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only check of a redirect marker; the summary itself comes from a transient.
	if ( empty( $_GET['elita-tour-imported'] ) ) {
		return;
	}

	$transient = 'elita_tour_core_demo_summary_' . get_current_user_id();
	$summary   = get_transient( $transient );

	if ( ! is_array( $summary ) ) {
		return;
	}

	delete_transient( $transient );

	if ( ! empty( $summary['errors'] ) ) {
		echo '<div class="notice notice-error is-dismissible"><p>' . esc_html__( 'The demo import reported problems:', 'elita-tour-core' ) . '</p><ul class="ul-disc">';

		foreach ( $summary['errors'] as $error ) {
			echo '<li>' . esc_html( $error ) . '</li>';
		}

		echo '</ul></div>';
	}

	echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'Demo content imported.', 'elita-tour-core' ) . '</p><ul class="ul-disc">';

	foreach ( elita_tour_core_demo_summary_lines( $summary ) as $line ) {
		echo '<li>' . esc_html( $line ) . '</li>';
	}

	echo '</ul></div>';
}
add_action( 'admin_notices', 'elita_tour_core_demo_admin_notice' );

/**
 * Render the import screen.
 *
 * @return void
 */
function elita_tour_core_demo_admin_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You are not allowed to import demo content.', 'elita-tour-core' ), 403 );
	}

	$items = array(
		__( 'Four tour categories, four seasons and eleven countries.', 'elita-tour-core' ),
		__( 'Twelve sample tours with route, dates, price and a featured image.', 'elita-tour-core' ),
		__( 'Four pages: a front page, a blog page, an about page and a contact page.', 'elita-tour-core' ),
		__( 'A primary menu and a footer menu, for theme locations that have none yet.', 'elita-tour-core' ),
		__( 'Customizer settings of the Elita Tour theme that are still empty, and the Reading settings of a site that still shows the latest posts.', 'elita-tour-core' ),
	);

	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Import demo content', 'elita-tour-core' ); ?></h1>

		<p><?php esc_html_e( 'Fills the site with a sample tour catalogue, so that a fresh installation looks like the theme demo. Nothing that already exists is changed or overwritten, and running the import twice creates no duplicates.', 'elita-tour-core' ); ?></p>

		<p><?php esc_html_e( 'The import creates:', 'elita-tour-core' ); ?></p>

		<ul class="ul-disc">
			<?php foreach ( $items as $item ) : ?>
				<li><?php echo esc_html( $item ); ?></li>
			<?php endforeach; ?>
		</ul>

		<p>
			<?php esc_html_e( 'All demo photographs are bundled with the plugin and released under CC0 1.0 Universal (public domain dedication).', 'elita-tour-core' ); ?>
			<a href="<?php echo esc_url( plugins_url( 'demo/images/CREDITS.txt', ELITA_TOUR_CORE_FILE ) ); ?>"><?php esc_html_e( 'See the photo credits', 'elita-tour-core' ); ?></a>
		</p>

		<form method="post">
			<?php wp_nonce_field( 'elita_tour_core_demo_import' ); ?>
			<?php submit_button( __( 'Import demo content', 'elita-tour-core' ) ); ?>
		</form>
	</div>
	<?php
}

if ( defined( 'WP_CLI' ) && WP_CLI ) {
	/**
	 * Import the demo content of the Elita Tour Core plugin.
	 *
	 * ## EXAMPLES
	 *
	 *     wp elita-tour demo import
	 *
	 * @return void
	 */
	function elita_tour_core_demo_cli_import() {
		$summary = elita_tour_core_demo_import();

		foreach ( elita_tour_core_demo_summary_lines( $summary ) as $line ) {
			WP_CLI::log( $line );
		}

		foreach ( $summary['errors'] as $error ) {
			WP_CLI::warning( $error );
		}

		if ( ! empty( $summary['errors'] ) ) {
			WP_CLI::error( __( 'The demo import finished with problems.', 'elita-tour-core' ) );
		}

		WP_CLI::success( __( 'Demo content imported.', 'elita-tour-core' ) );
	}

	WP_CLI::add_command( 'elita-tour demo import', 'elita_tour_core_demo_cli_import' );
}
