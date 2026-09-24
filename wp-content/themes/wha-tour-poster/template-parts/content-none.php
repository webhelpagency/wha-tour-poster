<?php
/**
 * Template part for displaying a message that posts cannot be found
 *
 * The heading of the page is printed by the template that includes this part,
 * so the empty state only carries the explanation and a way out.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WHA_Tour_Poster
 */

?>

<section class="no-results not-found">
	<div class="section__head">
		<div>
			<span class="eyebrow"><?php esc_html_e( 'Empty', 'wha-tour-poster' ); ?></span>
			<h2><?php esc_html_e( 'Nothing found', 'wha-tour-poster' ); ?></h2>
		</div>
	</div><!-- .section__head -->

	<div class="page-content">
		<?php
		if ( is_home() && current_user_can( 'publish_posts' ) ) :

			printf(
				'<p class="lead">' . wp_kses(
					/* translators: 1: link to WP admin new post page. */
					__( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'wha-tour-poster' ),
					array(
						'a' => array(
							'href' => array(),
						),
					)
				) . '</p>',
				esc_url( admin_url( 'post-new.php' ) )
			);

		elseif ( is_search() ) :
			?>

			<p class="lead"><?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'wha-tour-poster' ); ?></p>
			<?php
			get_search_form();

		else :
			?>

			<p class="lead"><?php esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'wha-tour-poster' ); ?></p>
			<?php
			get_search_form();

		endif;
		?>

		<?php if ( wha_tour_poster_has_core() ) : ?>
			<p>
				<a class="btn btn--primary" href="<?php echo esc_url( wha_tour_poster_archive_link() ); ?>"><?php esc_html_e( 'All programmes', 'wha-tour-poster' ); ?></a>
			</p>
		<?php endif; ?>
	</div><!-- .page-content -->
</section><!-- .no-results -->
