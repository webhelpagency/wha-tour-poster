<?php
/**
 * Homepage enquiry block.
 *
 * Ported from `section.section--alt#contact` of the reference layout. The form
 * column is filled by the "Lead form" widget area, or by the enquiry form of
 * the companion plugin; with neither available the block keeps only the text
 * column and goes full width.
 *
 * @package WHA_Tour_Poster
 */

$wha_tour_poster_lead_heading = trim( (string) get_theme_mod( 'wha_tour_poster_lead_heading', __( 'Tell us about your group and we will pick a programme within a day', 'wha-tour-poster' ) ) );
$wha_tour_poster_lead_text    = trim( (string) get_theme_mod( 'wha_tour_poster_lead_text', __( 'How many children, what age, when the holidays are and what budget you have. We take care of the rest.', 'wha-tour-poster' ) ) );

$wha_tour_poster_lead_phone      = wha_tour_poster_get_phone();
$wha_tour_poster_lead_phone_href = wha_tour_poster_get_phone_href();
$wha_tour_poster_lead_telegram   = trim( (string) get_theme_mod( 'wha_tour_poster_telegram', '' ) );
$wha_tour_poster_lead_viber      = trim( (string) get_theme_mod( 'wha_tour_poster_viber', '' ) );

$wha_tour_poster_lead_widgets   = is_active_sidebar( 'lead-form' );
$wha_tour_poster_lead_shortcode = ! $wha_tour_poster_lead_widgets && shortcode_exists( 'wha_tours_lead_form' );
$wha_tour_poster_lead_has_form  = $wha_tour_poster_lead_widgets || $wha_tour_poster_lead_shortcode;
?>
<section class="section section--alt" id="contact">
	<div class="container">
		<div class="lead-block<?php echo $wha_tour_poster_lead_has_form ? '' : ' lead-block--single'; ?>">
			<div class="lead-block__text">
				<span class="eyebrow"><?php esc_html_e( 'Request', 'wha-tour-poster' ); ?></span>

				<?php if ( '' !== $wha_tour_poster_lead_heading ) : ?>
					<h2><?php echo esc_html( $wha_tour_poster_lead_heading ); ?></h2>
				<?php endif; ?>

				<?php if ( '' !== $wha_tour_poster_lead_text ) : ?>
					<p class="lead"><?php echo esc_html( $wha_tour_poster_lead_text ); ?></p>
				<?php endif; ?>

				<?php if ( ( '' !== $wha_tour_poster_lead_phone && '' !== $wha_tour_poster_lead_phone_href ) || '' !== $wha_tour_poster_lead_telegram || '' !== $wha_tour_poster_lead_viber ) : ?>
					<div class="lead-block__contacts">
						<?php if ( '' !== $wha_tour_poster_lead_phone && '' !== $wha_tour_poster_lead_phone_href ) : ?>
							<a href="<?php echo esc_url( $wha_tour_poster_lead_phone_href, array( 'tel' ) ); ?>"><?php wha_tour_poster_icon( 'phone' ); ?><?php echo esc_html( $wha_tour_poster_lead_phone ); ?></a>
						<?php endif; ?>

						<?php if ( '' !== $wha_tour_poster_lead_telegram ) : ?>
							<a href="<?php echo esc_url( $wha_tour_poster_lead_telegram ); ?>" rel="noopener"><?php wha_tour_poster_icon( 'telegram' ); ?><?php esc_html_e( 'Message us on Telegram', 'wha-tour-poster' ); ?></a>
						<?php endif; ?>

						<?php if ( '' !== $wha_tour_poster_lead_viber ) : ?>
							<a href="<?php echo esc_url( $wha_tour_poster_lead_viber ); ?>" rel="noopener"><?php wha_tour_poster_icon( 'viber' ); ?><?php esc_html_e( 'Message us on Viber', 'wha-tour-poster' ); ?></a>
						<?php endif; ?>
					</div>
				<?php endif; ?>
			</div>

			<?php
			if ( $wha_tour_poster_lead_widgets ) {
				dynamic_sidebar( 'lead-form' );
			} elseif ( $wha_tour_poster_lead_shortcode ) {
				// The enquiry form belongs to the companion plugin, which escapes
				// its own markup; wp_kses() would strip the form controls.
				echo do_shortcode( '[wha_tours_lead_form]' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
			?>
		</div>
	</div>
</section><!-- #contact -->
