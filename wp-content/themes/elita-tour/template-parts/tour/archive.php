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
 * @package Elita_Tour
 */

$elita_tour_term = get_queried_object();

if ( ! $elita_tour_term instanceof WP_Term ) {
	$elita_tour_term = null;
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
if ( $elita_tour_term && isset( $_GET[ $elita_tour_term->taxonomy ] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only display logic.
	$elita_tour_term = null;

	foreach ( array( 'tour_category', 'tour_country', 'tour_season' ) as $elita_tour_taxonomy ) {
		if ( isset( $_GET[ $elita_tour_taxonomy ] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only display logic.
			continue;
		}

		$elita_tour_slug = sanitize_title( (string) get_query_var( $elita_tour_taxonomy ) );

		if ( '' === $elita_tour_slug ) {
			continue;
		}

		$elita_tour_archive_term = get_term_by( 'slug', $elita_tour_slug, $elita_tour_taxonomy );

		if ( $elita_tour_archive_term instanceof WP_Term ) {
			$elita_tour_term = $elita_tour_archive_term;
			break;
		}
	}
}

$elita_tour_title = $elita_tour_term ? $elita_tour_term->name : post_type_archive_title( '', false );

if ( '' === trim( (string) $elita_tour_title ) ) {
	$elita_tour_title = __( 'Programmes', 'elita-tour' );
}

$elita_tour_description = $elita_tour_term ? term_description( $elita_tour_term ) : '';

// The catalogue URL, or the category / country archive we are currently on: the
// season chips are hung off it so that a pretty permalink keeps its context.
$elita_tour_base = '';

if ( $elita_tour_term && in_array( $elita_tour_term->taxonomy, array( 'tour_category', 'tour_country' ), true ) ) {
	$elita_tour_term_link = get_term_link( $elita_tour_term );

	if ( ! is_wp_error( $elita_tour_term_link ) ) {
		$elita_tour_base = $elita_tour_term_link;
	}
}

if ( '' === $elita_tour_base ) {
	$elita_tour_base = elita_tour_archive_link();
}

// Current filter state. The query vars are filled both by a pretty term
// archive and by the query string of the homepage search bar.
$elita_tour_state = array();

foreach ( array( 'tour_category', 'tour_season', 'tour_country' ) as $elita_tour_key ) {
	$elita_tour_value = sanitize_title( (string) get_query_var( $elita_tour_key ) );

	if ( '' !== $elita_tour_value ) {
		$elita_tour_state[ $elita_tour_key ] = $elita_tour_value;
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
$elita_tour_filter_url = static function ( $base, array $state, array $drop ) {
	foreach ( $drop as $elita_tour_dropped ) {
		unset( $state[ $elita_tour_dropped ] );
	}

	return empty( $state ) ? $base : add_query_arg( $state, $base );
};

// A season chip replaces the season, and the term of the archive it links to.
$elita_tour_season_drop = array( 'tour_season' );

if ( $elita_tour_term && in_array( $elita_tour_term->taxonomy, array( 'tour_category', 'tour_country' ), true ) ) {
	$elita_tour_season_drop[] = $elita_tour_term->taxonomy;
}

$elita_tour_seasons_url = $elita_tour_filter_url( $elita_tour_base, $elita_tour_state, $elita_tour_season_drop );
$elita_tour_seasons     = elita_tour_get_seasons();

$elita_tour_categories = get_terms(
	array(
		'taxonomy'   => 'tour_category',
		'hide_empty' => true,
		'orderby'    => 'name',
	)
);

if ( is_wp_error( $elita_tour_categories ) ) {
	$elita_tour_categories = array();
}

$elita_tour_current_season   = isset( $elita_tour_state['tour_season'] ) ? $elita_tour_state['tour_season'] : '';
$elita_tour_current_category = isset( $elita_tour_state['tour_category'] ) ? $elita_tour_state['tour_category'] : '';
?>

<main id="primary" class="site-main" tabindex="-1">

	<?php get_template_part( 'template-parts/home/search' ); ?>

	<section class="section">
		<div class="container">

			<div class="section__head">
				<div>
					<span class="eyebrow"><?php esc_html_e( 'Catalogue', 'elita-tour' ); ?></span>
					<h1 class="page-title"><?php echo esc_html( $elita_tour_title ); ?></h1>

					<?php if ( '' !== trim( (string) $elita_tour_description ) ) : ?>
						<div class="lead archive-description"><?php echo wp_kses_post( $elita_tour_description ); ?></div>
					<?php endif; ?>
				</div>
			</div><!-- .section__head -->

			<?php if ( ! empty( $elita_tour_seasons ) || ! empty( $elita_tour_categories ) ) : ?>
				<div class="filters">
					<?php if ( ! empty( $elita_tour_seasons ) ) : ?>
						<nav class="chips chips--scroll" aria-label="<?php esc_attr_e( 'Filter by season', 'elita-tour' ); ?>">
							<a class="chip<?php echo '' === $elita_tour_current_season ? ' is-active' : ''; ?>" href="<?php echo esc_url( $elita_tour_seasons_url ); ?>"<?php echo '' === $elita_tour_current_season ? ' aria-current="page"' : ''; ?>><?php esc_html_e( 'All seasons', 'elita-tour' ); ?></a>

							<?php
							foreach ( $elita_tour_seasons as $elita_tour_season ) :
								$elita_tour_is_active = ( $elita_tour_season->slug === $elita_tour_current_season );
								?>
								<a
									class="chip<?php echo $elita_tour_is_active ? ' is-active' : ''; ?>"
									href="<?php echo esc_url( add_query_arg( 'tour_season', $elita_tour_season->slug, $elita_tour_seasons_url ) ); ?>"
									<?php echo $elita_tour_is_active ? ' aria-current="page"' : ''; ?>
								><?php echo esc_html( $elita_tour_season->name ); ?></a>
							<?php endforeach; ?>
						</nav>
					<?php endif; ?>

					<?php if ( ! empty( $elita_tour_categories ) ) : ?>
						<nav class="chips chips--scroll" aria-label="<?php esc_attr_e( 'Filter by category', 'elita-tour' ); ?>">
							<a class="chip<?php echo '' === $elita_tour_current_category ? ' is-active' : ''; ?>" href="<?php echo esc_url( $elita_tour_filter_url( elita_tour_archive_link(), $elita_tour_state, array( 'tour_category' ) ) ); ?>"<?php echo '' === $elita_tour_current_category ? ' aria-current="true"' : ''; ?>><?php esc_html_e( 'All categories', 'elita-tour' ); ?></a>

							<?php
							foreach ( $elita_tour_categories as $elita_tour_category ) :
								$elita_tour_category_link = get_term_link( $elita_tour_category );

								if ( is_wp_error( $elita_tour_category_link ) ) {
									continue;
								}

								$elita_tour_is_active = ( $elita_tour_category->slug === $elita_tour_current_category );
								?>
								<a
									class="chip<?php echo $elita_tour_is_active ? ' is-active' : ''; ?>"
									href="<?php echo esc_url( $elita_tour_filter_url( $elita_tour_category_link, $elita_tour_state, array( 'tour_category' ) ) ); ?>"
									<?php echo $elita_tour_is_active ? ' aria-current="page"' : ''; ?>
								><?php echo esc_html( $elita_tour_category->name ); ?></a>
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
						'prev_text'          => esc_html__( 'Previous', 'elita-tour' ),
						'next_text'          => esc_html__( 'Next', 'elita-tour' ),
						'screen_reader_text' => esc_html__( 'Programmes navigation', 'elita-tour' ),
					)
				);
				?>

			<?php else : ?>

				<?php get_template_part( 'template-parts/content', 'none' ); ?>

			<?php endif; ?>

		</div><!-- .container -->
	</section>

</main><!-- #primary -->
