<?php
/**
 * The template for displaying archive pages
 *
 * Used for the archives of the standard posts; the programme catalogue has its
 * own archive-tour.php.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Elita_Tour
 */

get_header();

$elita_tour_has_sidebar = is_active_sidebar( 'sidebar-1' );
$elita_tour_title       = get_the_archive_title();
$elita_tour_description = get_the_archive_description();
?>

	<main id="primary" class="site-main" tabindex="-1">
		<section class="section">
			<div class="container">

				<div class="section__head">
					<div>
						<span class="eyebrow"><?php esc_html_e( 'Archive', 'elita-tour' ); ?></span>
						<h1 class="page-title"><?php echo wp_kses_post( $elita_tour_title ); ?></h1>

						<?php if ( '' !== trim( (string) $elita_tour_description ) ) : ?>
							<div class="lead archive-description"><?php echo wp_kses_post( $elita_tour_description ); ?></div>
						<?php endif; ?>
					</div>
				</div><!-- .section__head -->

				<div class="layout<?php echo $elita_tour_has_sidebar ? ' layout--sidebar' : ''; ?>">
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
									'prev_text'          => esc_html__( 'Previous', 'elita-tour' ),
									'next_text'          => esc_html__( 'Next', 'elita-tour' ),
									'screen_reader_text' => esc_html__( 'Posts navigation', 'elita-tour' ),
								)
							);
							?>

						<?php else : ?>

							<?php get_template_part( 'template-parts/content', 'none' ); ?>

						<?php endif; ?>
					</div><!-- .layout__main -->

					<?php
					if ( $elita_tour_has_sidebar ) {
						get_sidebar();
					}
					?>
				</div><!-- .layout -->

			</div><!-- .container -->
		</section>
	</main><!-- #primary -->

<?php
get_footer();
