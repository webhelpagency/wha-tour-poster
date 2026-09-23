<?php
/**
 * Homepage "season hits" rail.
 *
 * Ported from `section.section#top` of the reference layout. Without the
 * companion plugin the rail lists the latest posts, sticky ones first.
 *
 * @package Elita_Tour
 */

$elita_tour_hits = elita_tour_get_hit_tours();

if ( empty( $elita_tour_hits->posts ) ) {
	return;
}
?>
<section class="section" id="top">
	<div class="container">
		<div class="section__head">
			<div>
				<span class="eyebrow"><?php esc_html_e( 'Chosen most often', 'elita-tour' ); ?></span>
				<h2><?php esc_html_e( 'Season hits', 'elita-tour' ); ?></h2>
			</div>

			<a class="btn btn--ghost" href="<?php echo esc_url( elita_tour_archive_link() ); ?>"><?php esc_html_e( 'All programmes', 'elita-tour' ); ?> <?php elita_tour_icon( 'arrow' ); ?></a>
		</div>

		<div class="rail rail--4">
			<?php
			foreach ( $elita_tour_hits->posts as $elita_tour_post ) {
				get_template_part(
					'template-parts/tour/poster',
					null,
					array( 'post' => $elita_tour_post )
				);
			}
			?>
		</div>
	</div>
</section><!-- #top -->
