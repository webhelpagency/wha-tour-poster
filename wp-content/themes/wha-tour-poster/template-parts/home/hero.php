<?php
/**
 * Homepage hero: headline and the four category panels.
 *
 * Ported from `section.hero-c#cats` of the reference layout.
 *
 * @package WHA_Tour_Poster
 */

$wha_tour_poster_hero_eyebrow = trim( (string) get_theme_mod( 'wha_tour_poster_hero_eyebrow', __( 'Tour operator for children', 'wha-tour-poster' ) ) );
$wha_tour_poster_hero_title   = trim( (string) get_theme_mod( 'wha_tour_poster_hero_title', __( 'Where are we going', 'wha-tour-poster' ) ) );
$wha_tour_poster_hero_accent  = trim( (string) get_theme_mod( 'wha_tour_poster_hero_accent', __( 'for the holidays?', 'wha-tour-poster' ) ) );
$wha_tour_poster_hero_lead    = trim( (string) get_theme_mod( 'wha_tour_poster_hero_lead', __( 'Seaside camps, coach tours around Europe, excursions at home and adventure routes for school groups and families. Pick a direction, we arrange the rest.', 'wha-tour-poster' ) ) );

$wha_tour_poster_panels = wha_tour_poster_get_home_categories();
?>
<section class="hero-c" id="cats">
	<div class="container hero-c__head">
		<div>
			<?php if ( '' !== $wha_tour_poster_hero_eyebrow ) : ?>
				<span class="eyebrow reveal" data-reveal><?php echo esc_html( $wha_tour_poster_hero_eyebrow ); ?></span>
			<?php endif; ?>

			<?php
			$wha_tour_poster_headline = esc_html( $wha_tour_poster_hero_title );

			if ( '' !== $wha_tour_poster_hero_accent ) {
				$wha_tour_poster_headline = trim( $wha_tour_poster_headline . ' <em>' . esc_html( $wha_tour_poster_hero_accent ) . '</em>' );
			}
			?>
			<h1 class="reveal" data-reveal data-delay="80"><?php echo wp_kses( $wha_tour_poster_headline, array( 'em' => array() ) ); ?></h1>
		</div>

		<?php if ( '' !== $wha_tour_poster_hero_lead ) : ?>
			<p class="lead reveal" data-reveal data-delay="160"><?php echo esc_html( $wha_tour_poster_hero_lead ); ?></p>
		<?php endif; ?>
	</div>

	<?php if ( ! empty( $wha_tour_poster_panels ) ) : ?>
		<div class="panels">
			<?php
			foreach ( $wha_tour_poster_panels as $wha_tour_poster_panel ) :
				// A term whose link could not be built has nowhere to point to.
				if ( is_wp_error( $wha_tour_poster_panel['link'] ) || '' === (string) $wha_tour_poster_panel['link'] ) {
					continue;
				}

				// Theme Check reads a quoted array key inside _n() as a missing
				// singular string, so the count is hoisted into a variable first.
				$wha_tour_poster_count = absint( $wha_tour_poster_panel['count'] );
				?>
				<a class="panel<?php echo $wha_tour_poster_panel['image_id'] ? '' : ' panel--noimg'; ?>" href="<?php echo esc_url( (string) $wha_tour_poster_panel['link'] ); ?>">
					<?php
					if ( $wha_tour_poster_panel['image_id'] ) {
						echo wp_kses(
							wp_get_attachment_image(
								$wha_tour_poster_panel['image_id'],
								'wha-tour-poster-panel',
								false,
								array( 'alt' => '' )
							),
							wha_tour_poster_image_allowed_html()
						);
					}
					?>
					<span class="panel__count">
						<?php
						printf(
							/* translators: %d: number of programmes in the category. */
							esc_html( _n( '%d programme', '%d programmes', $wha_tour_poster_count, 'wha-tour-poster' ) ),
							absint( $wha_tour_poster_count )
						);
						?>
					</span>
					<span class="panel__name"><?php echo esc_html( $wha_tour_poster_panel['name'] ); ?></span>
					<?php if ( '' !== $wha_tour_poster_panel['description'] ) : ?>
						<span class="panel__desc"><?php echo esc_html( wp_strip_all_tags( $wha_tour_poster_panel['description'] ) ); ?></span>
					<?php endif; ?>
					<span class="panel__go"><?php esc_html_e( 'View programmes', 'wha-tour-poster' ); ?> <?php wha_tour_poster_icon( 'arrow' ); ?></span>
				</a>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
</section><!-- #cats -->
