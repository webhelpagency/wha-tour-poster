<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package Elita_Tour
 */

get_header();
?>

	<main id="primary" class="site-main" tabindex="-1">
		<section class="section">
			<div class="container">

				<div class="error-404 not-found">
					<span class="eyebrow"><?php esc_html_e( '404', 'elita-tour' ); ?></span>
					<h1 class="page-title"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'elita-tour' ); ?></h1>

					<p class="lead"><?php esc_html_e( 'It looks like nothing was found at this location. Try a search, or pick a programme from the catalogue.', 'elita-tour' ); ?></p>

					<?php get_search_form(); ?>

					<p>
						<a class="btn btn--primary" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Back to the homepage', 'elita-tour' ); ?></a>
					</p>
				</div><!-- .error-404 -->

			</div><!-- .container -->
		</section>
	</main><!-- #primary -->

<?php
get_footer();
