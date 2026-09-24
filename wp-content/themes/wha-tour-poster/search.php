<?php
/**
 * The template for displaying search results pages
 *
 * With the companion plugin active the results also contain programmes, which
 * are rendered as poster cards next to the posts and pages.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package WHA_Tour_Poster
 */

get_header();

$wha_tour_poster_has_sidebar = is_active_sidebar( 'sidebar-1' );
?>

	<main id="primary" class="site-main" tabindex="-1">
		<section class="section">
			<div class="container">

				<div class="section__head">
					<div>
						<span class="eyebrow"><?php esc_html_e( 'Search', 'wha-tour-poster' ); ?></span>
						<h1 class="page-title">
							<?php
							/* translators: %s: search query. */
							printf( esc_html__( 'Search Results for: %s', 'wha-tour-poster' ), '<span>' . esc_html( get_search_query() ) . '</span>' );
							?>
						</h1>
					</div>

					<?php get_search_form(); ?>
				</div><!-- .section__head -->

				<div class="layout<?php echo $wha_tour_poster_has_sidebar ? ' layout--sidebar' : ''; ?>">
					<div class="layout__main">
						<?php if ( have_posts() ) : ?>

							<div class="grid grid--3">
								<?php
								/* Start the Loop */
								while ( have_posts() ) :
									the_post();

									/**
									 * Run the loop for the search to output the results.
									 * If you want to overload this in a child theme then include a file
									 * called content-search.php and that will be used instead.
									 */
									if ( 'tour' === get_post_type() ) {
										get_template_part(
											'template-parts/tour/poster',
											null,
											array( 'heading_level' => 'h2' )
										);
									} else {
										get_template_part( 'template-parts/content', 'search' );
									}

								endwhile;
								?>
							</div><!-- .grid -->

							<?php
							the_posts_pagination(
								array(
									'mid_size'           => 2,
									'prev_text'          => esc_html__( 'Previous', 'wha-tour-poster' ),
									'next_text'          => esc_html__( 'Next', 'wha-tour-poster' ),
									'screen_reader_text' => esc_html__( 'Search results navigation', 'wha-tour-poster' ),
								)
							);
							?>

						<?php else : ?>

							<?php get_template_part( 'template-parts/content', 'none' ); ?>

						<?php endif; ?>
					</div><!-- .layout__main -->

					<?php
					if ( $wha_tour_poster_has_sidebar ) {
						get_sidebar();
					}
					?>
				</div><!-- .layout -->

			</div><!-- .container -->
		</section>
	</main><!-- #primary -->

<?php
get_footer();
