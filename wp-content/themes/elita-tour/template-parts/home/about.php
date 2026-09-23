<?php
/**
 * Homepage "about us" block.
 *
 * Ported from `section.section#about` of the reference layout. The content is
 * taken from the page selected in the Customizer, so the section disappears
 * when no page is picked.
 *
 * @package Elita_Tour
 */

$elita_tour_about_id = absint( get_theme_mod( 'elita_tour_about_page', 0 ) );

if ( $elita_tour_about_id <= 0 || 'publish' !== get_post_status( $elita_tour_about_id ) ) {
	return;
}

$elita_tour_about_eyebrow = trim( (string) get_theme_mod( 'elita_tour_about_eyebrow', __( 'About us', 'elita-tour' ) ) );
$elita_tour_about_media   = has_post_thumbnail( $elita_tour_about_id );

$elita_tour_stats = array();

for ( $elita_tour_i = 1; $elita_tour_i <= 3; $elita_tour_i++ ) {
	$elita_tour_number = trim( (string) get_theme_mod( 'elita_tour_stat_' . $elita_tour_i . '_number', '' ) );
	$elita_tour_label  = trim( (string) get_theme_mod( 'elita_tour_stat_' . $elita_tour_i . '_label', '' ) );

	if ( '' === $elita_tour_number && '' === $elita_tour_label ) {
		continue;
	}

	$elita_tour_stats[] = array(
		'number' => $elita_tour_number,
		'label'  => $elita_tour_label,
	);
}
?>
<section class="section" id="about">
	<div class="container about<?php echo $elita_tour_about_media ? '' : ' about--single'; ?>">
		<?php if ( $elita_tour_about_media ) : ?>
			<div class="about__media">
				<?php
				echo wp_kses(
					get_the_post_thumbnail(
						$elita_tour_about_id,
						'elita-tour-wide',
						array( 'loading' => 'lazy' )
					),
					elita_tour_image_allowed_html()
				);
				?>
			</div>
		<?php endif; ?>

		<div class="about__body">
			<?php if ( '' !== $elita_tour_about_eyebrow ) : ?>
				<span class="eyebrow"><?php echo esc_html( $elita_tour_about_eyebrow ); ?></span>
			<?php endif; ?>

			<h2><?php echo esc_html( get_the_title( $elita_tour_about_id ) ); ?></h2>

			<?php
			$elita_tour_about_excerpt = get_the_excerpt( $elita_tour_about_id );

			if ( '' !== trim( (string) $elita_tour_about_excerpt ) ) :
				?>
				<p><?php echo esc_html( $elita_tour_about_excerpt ); ?></p>
			<?php endif; ?>

			<?php if ( ! empty( $elita_tour_stats ) ) : ?>
				<div class="stats">
					<?php foreach ( $elita_tour_stats as $elita_tour_stat ) : ?>
						<div class="stat">
							<strong><?php echo esc_html( $elita_tour_stat['number'] ); ?></strong>
							<span><?php echo esc_html( $elita_tour_stat['label'] ); ?></span>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<div>
				<a class="btn btn--outline" href="<?php echo esc_url( (string) get_permalink( $elita_tour_about_id ) ); ?>"><?php esc_html_e( 'More about the company', 'elita-tour' ); ?> <?php elita_tour_icon( 'arrow' ); ?></a>
			</div>
		</div>
	</div>
</section><!-- #about -->
