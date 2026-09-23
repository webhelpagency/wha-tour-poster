<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Elita_Tour
 */

get_header();

$elita_tour_has_sidebar = is_active_sidebar( 'sidebar-1' );
?>

	<main id="primary" class="site-main" tabindex="-1">
		<section class="section">
			<div class="container">
				<div class="layout<?php echo $elita_tour_has_sidebar ? ' layout--sidebar' : ''; ?>">
					<div class="layout__main">

						<?php
						while ( have_posts() ) :
							the_post();

							get_template_part( 'template-parts/content', 'single' );

							the_post_navigation(
								array(
									'prev_text' => '<span class="nav-subtitle">' . esc_html__( 'Previous:', 'elita-tour' ) . '</span> <span class="nav-title">%title</span>',
									'next_text' => '<span class="nav-subtitle">' . esc_html__( 'Next:', 'elita-tour' ) . '</span> <span class="nav-title">%title</span>',
								)
							);

							// If comments are open or we have at least one comment, load up the comment template.
							if ( comments_open() || get_comments_number() ) :
								comments_template();
							endif;

						endwhile; // End of the loop.
						?>

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
