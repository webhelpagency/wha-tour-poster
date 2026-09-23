<?php
/**
 * Homepage hero: headline and the four category panels.
 *
 * Ported from `section.hero-c#cats` of the reference layout.
 *
 * @package Elita_Tour
 */

$elita_tour_hero_eyebrow = trim( (string) get_theme_mod( 'elita_tour_hero_eyebrow', __( 'Tour operator for children', 'elita-tour' ) ) );
$elita_tour_hero_title   = trim( (string) get_theme_mod( 'elita_tour_hero_title', __( 'Where are we going', 'elita-tour' ) ) );
$elita_tour_hero_accent  = trim( (string) get_theme_mod( 'elita_tour_hero_accent', __( 'for the holidays?', 'elita-tour' ) ) );
$elita_tour_hero_lead    = trim( (string) get_theme_mod( 'elita_tour_hero_lead', __( 'Seaside camps, coach tours around Europe, excursions at home and adventure routes for school groups and families. Pick a direction, we arrange the rest.', 'elita-tour' ) ) );

$elita_tour_panels = elita_tour_get_home_categories();
?>
<section class="hero-c" id="cats">
	<div class="container hero-c__head">
		<div>
			<?php if ( '' !== $elita_tour_hero_eyebrow ) : ?>
				<span class="eyebrow reveal" data-reveal><?php echo esc_html( $elita_tour_hero_eyebrow ); ?></span>
			<?php endif; ?>

			<?php
			$elita_tour_headline = esc_html( $elita_tour_hero_title );

			if ( '' !== $elita_tour_hero_accent ) {
				$elita_tour_headline = trim( $elita_tour_headline . ' <em>' . esc_html( $elita_tour_hero_accent ) . '</em>' );
			}
			?>
			<h1 class="reveal" data-reveal data-delay="80"><?php echo wp_kses( $elita_tour_headline, array( 'em' => array() ) ); ?></h1>
		</div>

		<?php if ( '' !== $elita_tour_hero_lead ) : ?>
			<p class="lead reveal" data-reveal data-delay="160"><?php echo esc_html( $elita_tour_hero_lead ); ?></p>
		<?php endif; ?>
	</div>

	<?php if ( ! empty( $elita_tour_panels ) ) : ?>
		<div class="panels">
			<?php
			foreach ( $elita_tour_panels as $elita_tour_panel ) :
				// A term whose link could not be built has nowhere to point to.
				if ( is_wp_error( $elita_tour_panel['link'] ) || '' === (string) $elita_tour_panel['link'] ) {
					continue;
				}

				// Theme Check reads a quoted array key inside _n() as a missing
				// singular string, so the count is hoisted into a variable first.
				$elita_tour_count = absint( $elita_tour_panel['count'] );
				?>
				<a class="panel<?php echo $elita_tour_panel['image_id'] ? '' : ' panel--noimg'; ?>" href="<?php echo esc_url( (string) $elita_tour_panel['link'] ); ?>">
					<?php
					if ( $elita_tour_panel['image_id'] ) {
						echo wp_kses(
							wp_get_attachment_image(
								$elita_tour_panel['image_id'],
								'elita-tour-panel',
								false,
								array( 'alt' => '' )
							),
							elita_tour_image_allowed_html()
						);
					}
					?>
					<span class="panel__count">
						<?php
						printf(
							/* translators: %d: number of programmes in the category. */
							esc_html( _n( '%d programme', '%d programmes', $elita_tour_count, 'elita-tour' ) ),
							absint( $elita_tour_count )
						);
						?>
					</span>
					<span class="panel__name"><?php echo esc_html( $elita_tour_panel['name'] ); ?></span>
					<?php if ( '' !== $elita_tour_panel['description'] ) : ?>
						<span class="panel__desc"><?php echo esc_html( wp_strip_all_tags( $elita_tour_panel['description'] ) ); ?></span>
					<?php endif; ?>
					<span class="panel__go"><?php esc_html_e( 'View programmes', 'elita-tour' ); ?> <?php elita_tour_icon( 'arrow' ); ?></span>
				</a>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
</section><!-- #cats -->
