<?php
/**
 * The programme catalogue.
 *
 * Shared by archive-tour.php and by the taxonomy-tour_*.php wrappers, so that
 * the catalogue, a category, a season and a country archive are one layout:
 * the section head, two rows of filter chips, the search bar of the homepage
 * and a rail of poster cards.
 *
 * The filters are plain links: the season row sets the `tour_season` query var
 * on the current archive, the category row points at the category archives and
 * carries the rest of the current filters along.
 *
 * @package WHA_Tour_Poster
 */

$wha_tour_poster_term = get_queried_object();

if ( ! $wha_tour_poster_term instanceof WP_Term ) {
	$wha_tour_poster_term = null;
}

/*
 * A term archive that also carries a filter in the query string — say
 * /country/ukrayina/?tour_season=summer — is queried as an intersection of
 * both taxonomies, and get_queried_object() then hands back the term of the
 * query string rather than the archive we are on. So the archive term is
 * resolved from the path instead: every taxonomy that appears in the query
 * string is a filter, the first one left that resolves to a term is the
 * archive.
 */
if ( $wha_tour_poster_term && isset( $_GET[ $wha_tour_poster_term->taxonomy ] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only display logic.
	$wha_tour_poster_term = null;

	foreach ( array( 'tour_category', 'tour_country', 'tour_season' ) as $wha_tour_poster_taxonomy ) {
		if ( isset( $_GET[ $wha_tour_poster_taxonomy ] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only display logic.
			continue;
		}

		$wha_tour_poster_slug = sanitize_title( (string) get_query_var( $wha_tour_poster_taxonomy ) );

		if ( '' === $wha_tour_poster_slug ) {
			continue;
		}

		$wha_tour_poster_archive_term = get_term_by( 'slug', $wha_tour_poster_slug, $wha_tour_poster_taxonomy );

		if ( $wha_tour_poster_archive_term instanceof WP_Term ) {
			$wha_tour_poster_term = $wha_tour_poster_archive_term;
			break;
		}
	}
}

$wha_tour_poster_title = $wha_tour_poster_term ? $wha_tour_poster_term->name : post_type_archive_title( '', false );

if ( '' === trim( (string) $wha_tour_poster_title ) ) {
	$wha_tour_poster_title = __( 'Programmes', 'wha-tour-poster' );
}

$wha_tour_poster_description = $wha_tour_poster_term ? term_description( $wha_tour_poster_term ) : '';

// The catalogue URL, or the category / country archive we are currently on: the
// season chips are hung off it so that a pretty permalink keeps its context.
$wha_tour_poster_base = '';

if ( $wha_tour_poster_term && in_array( $wha_tour_poster_term->taxonomy, array( 'tour_category', 'tour_country' ), true ) ) {
	$wha_tour_poster_term_link = get_term_link( $wha_tour_poster_term );

	if ( ! is_wp_error( $wha_tour_poster_term_link ) ) {
		$wha_tour_poster_base = $wha_tour_poster_term_link;
	}
}

if ( '' === $wha_tour_poster_base ) {
	$wha_tour_poster_base = wha_tour_poster_archive_link();
}

// Current filter state. The query vars are filled both by a pretty term
// archive and by the query string of the homepage search bar.
$wha_tour_poster_state = array();

foreach ( array( 'tour_category', 'tour_season', 'tour_country' ) as $wha_tour_poster_key ) {
	$wha_tour_poster_value = sanitize_title( (string) get_query_var( $wha_tour_poster_key ) );

	if ( '' !== $wha_tour_poster_value ) {
		$wha_tour_poster_state[ $wha_tour_poster_key ] = $wha_tour_poster_value;
	}
}

/**
 * Builds one filter URL: the current state, minus the filters the link replaces.
 *
 * @param string   $base  Base URL.
 * @param array    $state Current filter state.
 * @param string[] $drop  Query vars the link sets itself.
 * @return string URL.
 */
$wha_tour_poster_filter_url = static function ( $base, array $state, array $drop ) {
	foreach ( $drop as $wha_tour_poster_dropped ) {
		unset( $state[ $wha_tour_poster_dropped ] );
	}

	return empty( $state ) ? $base : add_query_arg( $state, $base );
};

// A season chip replaces the season, and the term of the archive it links to.
$wha_tour_poster_season_drop = array( 'tour_season' );

if ( $wha_tour_poster_term && in_array( $wha_tour_poster_term->taxonomy, array( 'tour_category', 'tour_country' ), true ) ) {
	$wha_tour_poster_season_drop[] = $wha_tour_poster_term->taxonomy;
}

$wha_tour_poster_seasons_url = $wha_tour_poster_filter_url( $wha_tour_poster_base, $wha_tour_poster_state, $wha_tour_poster_season_drop );
$wha_tour_poster_seasons     = wha_tour_poster_get_seasons();

$wha_tour_poster_categories = get_terms(
	array(
		'taxonomy'   => 'tour_category',
		'hide_empty' => true,
		'orderby'    => 'name',
	)
);

if ( is_wp_error( $wha_tour_poster_categories ) ) {
	$wha_tour_poster_categories = array();
}

$wha_tour_poster_current_season   = isset( $wha_tour_poster_state['tour_season'] ) ? $wha_tour_poster_state['tour_season'] : '';
$wha_tour_poster_current_category = isset( $wha_tour_poster_state['tour_category'] ) ? $wha_tour_poster_state['tour_category'] : '';
?>

<main id="primary" class="site-main" tabindex="-1">

	<?php get_template_part( 'template-parts/home/search' ); ?>

	<section class="section">
		<div class="container">

			<div class="section__head">
				<div>
					<span class="eyebrow"><?php esc_html_e( 'Catalogue', 'wha-tour-poster' ); ?></span>
					<h1 class="page-title"><?php echo esc_html( $wha_tour_poster_title ); ?></h1>

					<?php if ( '' !== trim( (string) $wha_tour_poster_description ) ) : ?>
						<div class="lead archive-description"><?php echo wp_kses_post( $wha_tour_poster_description ); ?></div>
					<?php endif; ?>
				</div>
			</div><!-- .section__head -->

			<?php if ( ! empty( $wha_tour_poster_seasons ) || ! empty( $wha_tour_poster_categories ) ) : ?>
				<div class="filters">
					<?php if ( ! empty( $wha_tour_poster_seasons ) ) : ?>
						<nav class="chips chips--scroll" aria-label="<?php esc_attr_e( 'Filter by season', 'wha-tour-poster' ); ?>">
							<a class="chip<?php echo '' === $wha_tour_poster_current_season ? ' is-active' : ''; ?>" href="<?php echo esc_url( $wha_tour_poster_seasons_url ); ?>"<?php echo '' === $wha_tour_poster_current_season ? ' aria-current="page"' : ''; ?>><?php esc_html_e( 'All seasons', 'wha-tour-poster' ); ?></a>

							<?php
							foreach ( $wha_tour_poster_seasons as $wha_tour_poster_season ) :
								$wha_tour_poster_is_active = ( $wha_tour_poster_season->slug === $wha_tour_poster_current_season );
								?>
								<a
									class="chip<?php echo $wha_tour_poster_is_active ? ' is-active' : ''; ?>"
									href="<?php echo esc_url( add_query_arg( 'tour_season', $wha_tour_poster_season->slug, $wha_tour_poster_seasons_url ) ); ?>"
									<?php echo $wha_tour_poster_is_active ? ' aria-current="page"' : ''; ?>
								><?php echo esc_html( $wha_tour_poster_season->name ); ?></a>
							<?php endforeach; ?>
						</nav>
					<?php endif; ?>

					<?php if ( ! empty( $wha_tour_poster_categories ) ) : ?>
						<nav class="chips chips--scroll" aria-label="<?php esc_attr_e( 'Filter by category', 'wha-tour-poster' ); ?>">
							<a class="chip<?php echo '' === $wha_tour_poster_current_category ? ' is-active' : ''; ?>" href="<?php echo esc_url( $wha_tour_poster_filter_url( wha_tour_poster_archive_link(), $wha_tour_poster_state, array( 'tour_category' ) ) ); ?>"<?php echo '' === $wha_tour_poster_current_category ? ' aria-current="true"' : ''; ?>><?php esc_html_e( 'All categories', 'wha-tour-poster' ); ?></a>

							<?php
							foreach ( $wha_tour_poster_categories as $wha_tour_poster_category ) :
								$wha_tour_poster_category_link = get_term_link( $wha_tour_poster_category );

								if ( is_wp_error( $wha_tour_poster_category_link ) ) {
									continue;
								}

								$wha_tour_poster_is_active = ( $wha_tour_poster_category->slug === $wha_tour_poster_current_category );
								?>
								<a
									class="chip<?php echo $wha_tour_poster_is_active ? ' is-active' : ''; ?>"
									href="<?php echo esc_url( $wha_tour_poster_filter_url( $wha_tour_poster_category_link, $wha_tour_poster_state, array( 'tour_category' ) ) ); ?>"
									<?php echo $wha_tour_poster_is_active ? ' aria-current="page"' : ''; ?>
								><?php echo esc_html( $wha_tour_poster_category->name ); ?></a>
							<?php endforeach; ?>
						</nav>
					<?php endif; ?>
				</div><!-- .filters -->
			<?php endif; ?>

			<?php if ( have_posts() ) : ?>

				<div class="rail rail--4 rail--grid">
					<?php
					while ( have_posts() ) :
						the_post();

						get_template_part(
							'template-parts/tour/poster',
							null,
							array( 'heading_level' => 'h2' )
						);
					endwhile;
					?>
				</div><!-- .rail -->

				<?php
				the_posts_pagination(
					array(
						'mid_size'           => 2,
						'prev_text'          => esc_html__( 'Previous', 'wha-tour-poster' ),
						'next_text'          => esc_html__( 'Next', 'wha-tour-poster' ),
						'screen_reader_text' => esc_html__( 'Programmes navigation', 'wha-tour-poster' ),
					)
				);
				?>

			<?php else : ?>

				<?php get_template_part( 'template-parts/content', 'none' ); ?>

			<?php endif; ?>

		</div><!-- .container -->
	</section>

</main><!-- #primary -->
