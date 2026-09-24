<?php
/**
 * Homepage "season hits" rail.
 *
 * Ported from `section.section#top` of the reference layout. Without the
 * companion plugin the rail lists the latest posts, sticky ones first.
 *
 * @package WHA_Tour_Poster
 */

$wha_tour_poster_hits = wha_tour_poster_get_hit_tours();

if ( empty( $wha_tour_poster_hits->posts ) ) {
	return;
}
?>
<section class="section" id="top">
	<div class="container">
		<div class="section__head">
			<div>
				<span class="eyebrow"><?php esc_html_e( 'Chosen most often', 'wha-tour-poster' ); ?></span>
				<h2><?php esc_html_e( 'Season hits', 'wha-tour-poster' ); ?></h2>
			</div>

			<a class="btn btn--ghost" href="<?php echo esc_url( wha_tour_poster_archive_link() ); ?>"><?php esc_html_e( 'All programmes', 'wha-tour-poster' ); ?> <?php wha_tour_poster_icon( 'arrow' ); ?></a>
		</div>

		<div class="rail rail--4">
			<?php
			foreach ( $wha_tour_poster_hits->posts as $wha_tour_poster_post ) {
				get_template_part(
					'template-parts/tour/poster',
					null,
					array( 'post' => $wha_tour_poster_post )
				);
			}
			?>
		</div>
	</div>
</section><!-- #top -->
