<?php
/**
 * Homepage "about us" block.
 *
 * Ported from `section.section#about` of the reference layout. The content is
 * taken from the page selected in the Customizer, so the section disappears
 * when no page is picked.
 *
 * @package WHA_Tour_Poster
 */

$wha_tour_poster_about_id = absint( get_theme_mod( 'wha_tour_poster_about_page', 0 ) );

if ( $wha_tour_poster_about_id <= 0 || 'publish' !== get_post_status( $wha_tour_poster_about_id ) ) {
	return;
}

$wha_tour_poster_about_eyebrow = trim( (string) get_theme_mod( 'wha_tour_poster_about_eyebrow', __( 'About us', 'wha-tour-poster' ) ) );
$wha_tour_poster_about_media   = has_post_thumbnail( $wha_tour_poster_about_id );

$wha_tour_poster_stats = array();

for ( $wha_tour_poster_i = 1; $wha_tour_poster_i <= 3; $wha_tour_poster_i++ ) {
	$wha_tour_poster_number = trim( (string) get_theme_mod( 'wha_tour_poster_stat_' . $wha_tour_poster_i . '_number', '' ) );
	$wha_tour_poster_label  = trim( (string) get_theme_mod( 'wha_tour_poster_stat_' . $wha_tour_poster_i . '_label', '' ) );

	if ( '' === $wha_tour_poster_number && '' === $wha_tour_poster_label ) {
		continue;
	}

	$wha_tour_poster_stats[] = array(
		'number' => $wha_tour_poster_number,
		'label'  => $wha_tour_poster_label,
	);
}
?>
<section class="section" id="about">
	<div class="container about<?php echo $wha_tour_poster_about_media ? '' : ' about--single'; ?>">
		<?php if ( $wha_tour_poster_about_media ) : ?>
			<div class="about__media">
				<?php
				echo wp_kses(
					get_the_post_thumbnail(
						$wha_tour_poster_about_id,
						'wha-tour-poster-wide',
						array( 'loading' => 'lazy' )
					),
					wha_tour_poster_image_allowed_html()
				);
				?>
			</div>
		<?php endif; ?>

		<div class="about__body">
			<?php if ( '' !== $wha_tour_poster_about_eyebrow ) : ?>
				<span class="eyebrow"><?php echo esc_html( $wha_tour_poster_about_eyebrow ); ?></span>
			<?php endif; ?>

			<h2><?php echo esc_html( get_the_title( $wha_tour_poster_about_id ) ); ?></h2>

			<?php
			$wha_tour_poster_about_excerpt = get_the_excerpt( $wha_tour_poster_about_id );

			if ( '' !== trim( (string) $wha_tour_poster_about_excerpt ) ) :
				?>
				<p><?php echo esc_html( $wha_tour_poster_about_excerpt ); ?></p>
			<?php endif; ?>

			<?php if ( ! empty( $wha_tour_poster_stats ) ) : ?>
				<div class="stats">
					<?php foreach ( $wha_tour_poster_stats as $wha_tour_poster_stat ) : ?>
						<div class="stat">
							<strong><?php echo esc_html( $wha_tour_poster_stat['number'] ); ?></strong>
							<span><?php echo esc_html( $wha_tour_poster_stat['label'] ); ?></span>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<div>
				<a class="btn btn--outline" href="<?php echo esc_url( (string) get_permalink( $wha_tour_poster_about_id ) ); ?>"><?php esc_html_e( 'More about the company', 'wha-tour-poster' ); ?> <?php wha_tour_poster_icon( 'arrow' ); ?></a>
			</div>
		</div>
	</div>
</section><!-- #about -->
