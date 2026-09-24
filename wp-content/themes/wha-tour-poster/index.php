<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WHA_Tour_Poster
 */

get_header();

$wha_tour_poster_has_sidebar = is_active_sidebar( 'sidebar-1' );
$wha_tour_poster_title       = ( is_home() && ! is_front_page() ) ? single_post_title( '', false ) : '';

if ( '' === trim( (string) $wha_tour_poster_title ) ) {
	$wha_tour_poster_title = __( 'Latest posts', 'wha-tour-poster' );
}
?>

	<main id="primary" class="site-main" tabindex="-1">
		<section class="section">
			<div class="container">

				<div class="section__head">
					<div>
						<span class="eyebrow"><?php esc_html_e( 'Blog', 'wha-tour-poster' ); ?></span>
						<h1 class="page-title"><?php echo esc_html( $wha_tour_poster_title ); ?></h1>
					</div>
				</div><!-- .section__head -->

				<div class="layout<?php echo $wha_tour_poster_has_sidebar ? ' layout--sidebar' : ''; ?>">
					<div class="layout__main">
						<?php if ( have_posts() ) : ?>

							<div class="grid grid--3">
								<?php
								/* Start the Loop */
								while ( have_posts() ) :
									the_post();

									/*
									 * Include the Post-Type-specific template for the content.
									 * If you want to override this in a child theme, then include a file
									 * called content-___.php (where ___ is the Post Type name) and that will be used instead.
									 */
									get_template_part( 'template-parts/content', get_post_type() );

								endwhile;
								?>
							</div><!-- .grid -->

							<?php
							the_posts_pagination(
								array(
									'mid_size'           => 2,
									'prev_text'          => esc_html__( 'Previous', 'wha-tour-poster' ),
									'next_text'          => esc_html__( 'Next', 'wha-tour-poster' ),
									'screen_reader_text' => esc_html__( 'Posts navigation', 'wha-tour-poster' ),
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
