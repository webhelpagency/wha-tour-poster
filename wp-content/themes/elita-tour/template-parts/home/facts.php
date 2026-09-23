<?php
/**
 * Homepage facts strip.
 *
 * Ported from `section.section--alt#why` of the reference layout. The four
 * slots are edited in the Customizer; a slot without a title is skipped.
 *
 * @package Elita_Tour
 */

$elita_tour_facts = array();

for ( $elita_tour_i = 1; $elita_tour_i <= 4; $elita_tour_i++ ) {
	$elita_tour_title = trim( (string) get_theme_mod( 'elita_tour_fact_' . $elita_tour_i . '_title', '' ) );

	if ( '' === $elita_tour_title ) {
		continue;
	}

	$elita_tour_facts[] = array(
		'number' => trim( (string) get_theme_mod( 'elita_tour_fact_' . $elita_tour_i . '_number', '' ) ),
		'accent' => trim( (string) get_theme_mod( 'elita_tour_fact_' . $elita_tour_i . '_accent', '' ) ),
		'title'  => $elita_tour_title,
		'text'   => trim( (string) get_theme_mod( 'elita_tour_fact_' . $elita_tour_i . '_text', '' ) ),
	);
}

if ( empty( $elita_tour_facts ) ) {
	return;
}
?>
<section class="section section--alt" id="why">
	<div class="container">
		<div class="section__head">
			<div>
				<span class="eyebrow"><?php esc_html_e( 'Why parents trust us with their children', 'elita-tour' ); ?></span>
				<h2><?php esc_html_e( 'What we take care of', 'elita-tour' ); ?></h2>
			</div>
		</div>

		<div class="facts">
			<?php foreach ( $elita_tour_facts as $elita_tour_fact ) : ?>
				<div class="fact">
					<?php if ( '' !== $elita_tour_fact['number'] || '' !== $elita_tour_fact['accent'] ) : ?>
						<?php
						$elita_tour_number = esc_html( $elita_tour_fact['number'] );

						if ( '' !== $elita_tour_fact['accent'] ) {
							$elita_tour_number .= '<em>' . esc_html( $elita_tour_fact['accent'] ) . '</em>';
						}
						?>
						<strong><?php echo wp_kses( $elita_tour_number, array( 'em' => array() ) ); ?></strong>
					<?php endif; ?>

					<h3><?php echo esc_html( $elita_tour_fact['title'] ); ?></h3>

					<?php if ( '' !== $elita_tour_fact['text'] ) : ?>
						<p><?php echo esc_html( $elita_tour_fact['text'] ); ?></p>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section><!-- #why -->
