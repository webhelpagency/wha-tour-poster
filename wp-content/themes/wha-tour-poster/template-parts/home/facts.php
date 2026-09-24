<?php
/**
 * Homepage facts strip.
 *
 * Ported from `section.section--alt#why` of the reference layout. The four
 * slots are edited in the Customizer; a slot without a title is skipped.
 *
 * @package WHA_Tour_Poster
 */

$wha_tour_poster_facts = array();

for ( $wha_tour_poster_i = 1; $wha_tour_poster_i <= 4; $wha_tour_poster_i++ ) {
	$wha_tour_poster_title = trim( (string) get_theme_mod( 'wha_tour_poster_fact_' . $wha_tour_poster_i . '_title', '' ) );

	if ( '' === $wha_tour_poster_title ) {
		continue;
	}

	$wha_tour_poster_facts[] = array(
		'number' => trim( (string) get_theme_mod( 'wha_tour_poster_fact_' . $wha_tour_poster_i . '_number', '' ) ),
		'accent' => trim( (string) get_theme_mod( 'wha_tour_poster_fact_' . $wha_tour_poster_i . '_accent', '' ) ),
		'title'  => $wha_tour_poster_title,
		'text'   => trim( (string) get_theme_mod( 'wha_tour_poster_fact_' . $wha_tour_poster_i . '_text', '' ) ),
	);
}

if ( empty( $wha_tour_poster_facts ) ) {
	return;
}
?>
<section class="section section--alt" id="why">
	<div class="container">
		<div class="section__head">
			<div>
				<span class="eyebrow"><?php esc_html_e( 'Why parents trust us with their children', 'wha-tour-poster' ); ?></span>
				<h2><?php esc_html_e( 'What we take care of', 'wha-tour-poster' ); ?></h2>
			</div>
		</div>

		<div class="facts">
			<?php foreach ( $wha_tour_poster_facts as $wha_tour_poster_fact ) : ?>
				<div class="fact">
					<?php if ( '' !== $wha_tour_poster_fact['number'] || '' !== $wha_tour_poster_fact['accent'] ) : ?>
						<?php
						$wha_tour_poster_number = esc_html( $wha_tour_poster_fact['number'] );

						if ( '' !== $wha_tour_poster_fact['accent'] ) {
							$wha_tour_poster_number .= '<em>' . esc_html( $wha_tour_poster_fact['accent'] ) . '</em>';
						}
						?>
						<strong><?php echo wp_kses( $wha_tour_poster_number, array( 'em' => array() ) ); ?></strong>
					<?php endif; ?>

					<h3><?php echo esc_html( $wha_tour_poster_fact['title'] ); ?></h3>

					<?php if ( '' !== $wha_tour_poster_fact['text'] ) : ?>
						<p><?php echo esc_html( $wha_tour_poster_fact['text'] ); ?></p>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section><!-- #why -->
