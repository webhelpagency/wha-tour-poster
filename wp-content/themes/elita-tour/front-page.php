<?php
/**
 * The front page template
 *
 * Renders the homepage of the reference layout. It is used both when the front
 * page shows the latest posts and when it shows a static page; in the latter
 * case the content of that page is rendered between the "about" and the
 * enquiry sections. The blog listing keeps using index.php / home.php.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Elita_Tour
 */

/*
 * With Settings > Reading left on "Your latest posts" WordPress picks this
 * template for the blog listing as well. The landing sections belong to a
 * static front page, so the blog is handed over to index.php untouched.
 */
if ( 'posts' === get_option( 'show_on_front' ) ) {
	get_template_part( 'index' );
	return;
}

get_header();
?>

	<main id="primary" class="site-main" tabindex="-1">

		<?php
		get_template_part( 'template-parts/home/hero' );
		get_template_part( 'template-parts/home/search' );
		get_template_part( 'template-parts/home/seasons' );
		get_template_part( 'template-parts/home/top' );
		get_template_part( 'template-parts/home/facts' );
		get_template_part( 'template-parts/home/about' );

		// A static front page can still carry its own content: show it as a
		// regular section, so nothing the user wrote is lost.
		if ( is_page() && have_posts() ) :
			while ( have_posts() ) :
				the_post();

				if ( '' === trim( get_the_content() ) ) {
					continue;
				}
				?>
				<section class="section">
					<div class="container">
						<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry' ); ?>>
							<div class="entry-content">
								<?php
								the_content();

								wp_link_pages(
									array(
										'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'elita-tour' ),
										'after'  => '</div>',
									)
								);
								?>
							</div><!-- .entry-content -->
						</article><!-- #post-## -->
					</div><!-- .container -->
				</section>
				<?php
			endwhile;
		endif;

		get_template_part( 'template-parts/home/lead' );
		?>

	</main><!-- #primary -->

<?php
get_footer();
