<?php
/**
 * Homepage enquiry block.
 *
 * Ported from `section.section--alt#contact` of the reference layout. The form
 * column is filled by the "Lead form" widget area, or by the enquiry form of
 * the companion plugin; with neither available the block keeps only the text
 * column and goes full width.
 *
 * @package Elita_Tour
 */

$elita_tour_lead_heading = trim( (string) get_theme_mod( 'elita_tour_lead_heading', __( 'Tell us about your group and we will pick a programme within a day', 'elita-tour' ) ) );
$elita_tour_lead_text    = trim( (string) get_theme_mod( 'elita_tour_lead_text', __( 'How many children, what age, when the holidays are and what budget you have. We take care of the rest.', 'elita-tour' ) ) );

$elita_tour_lead_phone      = elita_tour_get_phone();
$elita_tour_lead_phone_href = elita_tour_get_phone_href();
$elita_tour_lead_telegram   = trim( (string) get_theme_mod( 'elita_tour_telegram', '' ) );
$elita_tour_lead_viber      = trim( (string) get_theme_mod( 'elita_tour_viber', '' ) );

$elita_tour_lead_widgets   = is_active_sidebar( 'lead-form' );
$elita_tour_lead_shortcode = ! $elita_tour_lead_widgets && shortcode_exists( 'elita_tour_lead_form' );
$elita_tour_lead_has_form  = $elita_tour_lead_widgets || $elita_tour_lead_shortcode;
?>
<section class="section section--alt" id="contact">
	<div class="container">
		<div class="lead-block<?php echo $elita_tour_lead_has_form ? '' : ' lead-block--single'; ?>">
			<div class="lead-block__text">
				<span class="eyebrow"><?php esc_html_e( 'Request', 'elita-tour' ); ?></span>

				<?php if ( '' !== $elita_tour_lead_heading ) : ?>
					<h2><?php echo esc_html( $elita_tour_lead_heading ); ?></h2>
				<?php endif; ?>

				<?php if ( '' !== $elita_tour_lead_text ) : ?>
					<p class="lead"><?php echo esc_html( $elita_tour_lead_text ); ?></p>
				<?php endif; ?>

				<?php if ( ( '' !== $elita_tour_lead_phone && '' !== $elita_tour_lead_phone_href ) || '' !== $elita_tour_lead_telegram || '' !== $elita_tour_lead_viber ) : ?>
					<div class="lead-block__contacts">
						<?php if ( '' !== $elita_tour_lead_phone && '' !== $elita_tour_lead_phone_href ) : ?>
							<a href="<?php echo esc_url( $elita_tour_lead_phone_href, array( 'tel' ) ); ?>"><?php elita_tour_icon( 'phone' ); ?><?php echo esc_html( $elita_tour_lead_phone ); ?></a>
						<?php endif; ?>

						<?php if ( '' !== $elita_tour_lead_telegram ) : ?>
							<a href="<?php echo esc_url( $elita_tour_lead_telegram ); ?>" rel="noopener"><?php elita_tour_icon( 'telegram' ); ?><?php esc_html_e( 'Message us on Telegram', 'elita-tour' ); ?></a>
						<?php endif; ?>

						<?php if ( '' !== $elita_tour_lead_viber ) : ?>
							<a href="<?php echo esc_url( $elita_tour_lead_viber ); ?>" rel="noopener"><?php elita_tour_icon( 'viber' ); ?><?php esc_html_e( 'Message us on Viber', 'elita-tour' ); ?></a>
						<?php endif; ?>
					</div>
				<?php endif; ?>
			</div>

			<?php
			if ( $elita_tour_lead_widgets ) {
				dynamic_sidebar( 'lead-form' );
			} elseif ( $elita_tour_lead_shortcode ) {
				// The enquiry form belongs to the companion plugin, which escapes
				// its own markup; wp_kses() would strip the form controls.
				echo do_shortcode( '[elita_tour_lead_form]' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
			?>
		</div>
	</div>
</section><!-- #contact -->
