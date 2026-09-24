<?php
/**
 * One poster card.
 *
 * Ported from `article.poster` of the reference layout. The card works for a
 * `tour` of the companion plugin (route, duration, transport, price, departure
 * dates) and for a plain post (thumbnail, first category, title, date).
 *
 * @package WHA_Tour_Poster
 *
 * @var array $args {
 *     Optional. Arguments passed through get_template_part().
 *
 *     @type int|WP_Post $post          Post to render. Defaults to the current post.
 *     @type string      $heading_level Heading element for the card title, so the
 *                                      card fits the outline of the page that
 *                                      includes it. One of `h2`, `h3`, `h4`, `h5`,
 *                                      `h6`. Default `h3`.
 * }
 */

$wha_tour_poster_item = isset( $args['post'] ) ? get_post( $args['post'] ) : get_post();

if ( ! $wha_tour_poster_item instanceof WP_Post ) {
	return;
}

$wha_tour_poster_heading = isset( $args['heading_level'] ) ? strtolower( (string) $args['heading_level'] ) : 'h3';

if ( ! in_array( $wha_tour_poster_heading, array( 'h2', 'h3', 'h4', 'h5', 'h6' ), true ) ) {
	$wha_tour_poster_heading = 'h3';
}

$wha_tour_poster_id    = (int) $wha_tour_poster_item->ID;
$wha_tour_poster_title = get_the_title( $wha_tour_poster_id );
$wha_tour_poster_term  = wha_tour_poster_get_post_term( $wha_tour_poster_id );

$wha_tour_poster_meta = array(
	'days'      => 0,
	'transport' => '',
	'price'     => '',
	'route'     => array(),
	'dates'     => array(),
	'hit'       => false,
);

if ( 'tour' === get_post_type( $wha_tour_poster_id ) && function_exists( 'wha_tours_core_get_meta' ) ) {
	$wha_tour_poster_meta = array_merge( $wha_tour_poster_meta, wha_tours_core_get_meta( $wha_tour_poster_id ) );
}

$wha_tour_poster_dates_label = '';
$wha_tour_poster_dates_next  = null;

if ( ! empty( $wha_tour_poster_meta['dates'] ) && function_exists( 'wha_tours_core_format_dates' ) ) {
	$wha_tour_poster_dates_label = wha_tours_core_format_dates( $wha_tour_poster_meta['dates'] );
	$wha_tour_poster_dates_next  = wha_tours_core_next_date( $wha_tour_poster_meta['dates'] );
}
?>
<article <?php post_class( 'poster', $wha_tour_poster_id ); ?>>
	<a class="poster__link" href="<?php echo esc_url( (string) get_permalink( $wha_tour_poster_id ) ); ?>" aria-label="<?php echo esc_attr( $wha_tour_poster_title ); ?>">
		<?php
		if ( has_post_thumbnail( $wha_tour_poster_id ) ) {
			echo wp_kses(
				get_the_post_thumbnail(
					$wha_tour_poster_id,
					'wha-tour-poster-card',
					array(
						'alt'     => '',
						'loading' => 'lazy',
					)
				),
				wha_tour_poster_image_allowed_html()
			);
		} else {
			?>
			<span class="poster__img--empty" aria-hidden="true"></span>
			<?php
		}
		?>

		<div class="poster__top">
			<?php if ( $wha_tour_poster_term instanceof WP_Term ) : ?>
				<span class="badge badge--cat badge--cat-<?php echo esc_attr( wha_tour_poster_term_color( $wha_tour_poster_term ) ); ?>"><?php echo esc_html( $wha_tour_poster_term->name ); ?></span>
			<?php endif; ?>

			<?php if ( ! empty( $wha_tour_poster_meta['hit'] ) ) : ?>
				<span class="badge badge--hit"><?php wha_tour_poster_icon( 'star' ); ?><?php esc_html_e( 'Hit', 'wha-tour-poster' ); ?></span>
			<?php endif; ?>
		</div>

		<div class="poster__body">
			<?php if ( ! empty( $wha_tour_poster_meta['route'] ) ) : ?>
				<div class="route">
					<?php foreach ( $wha_tour_poster_meta['route'] as $wha_tour_poster_stop ) : ?>
						<span class="route__stop"><?php echo esc_html( $wha_tour_poster_stop ); ?></span>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<<?php echo esc_html( $wha_tour_poster_heading ); ?> class="poster__title"><?php echo esc_html( $wha_tour_poster_title ); ?></<?php echo esc_html( $wha_tour_poster_heading ); ?>>

			<?php
			// Theme Check reads a quoted array key inside _n() as a missing
			// singular string, so the count is hoisted into a variable first.
			$wha_tour_poster_days = absint( $wha_tour_poster_meta['days'] );

			if ( $wha_tour_poster_days || ! empty( $wha_tour_poster_meta['transport'] ) ) :
				?>
				<div class="poster__meta">
					<?php if ( $wha_tour_poster_days ) : ?>
						<span>
							<?php wha_tour_poster_icon( 'days' ); ?>
							<?php
							printf(
								/* translators: %d: number of days. */
								esc_html( _n( '%d day', '%d days', $wha_tour_poster_days, 'wha-tour-poster' ) ),
								absint( $wha_tour_poster_days )
							);
							?>
						</span>
					<?php endif; ?>

					<?php
					if ( ! empty( $wha_tour_poster_meta['transport'] ) && function_exists( 'wha_tours_core_transport_label' ) ) :
						$wha_tour_poster_transport = wha_tours_core_transport_label( $wha_tour_poster_meta['transport'] );

						// The icon helper is a plugin function too, so the card keeps a
						// sensible default when only the label helper exists.
						$wha_tour_poster_transport_icon = function_exists( 'wha_tours_core_transport_icon' )
							? wha_tours_core_transport_icon( $wha_tour_poster_meta['transport'] )
							: 'bus';

						if ( '' !== $wha_tour_poster_transport ) :
							?>
							<span>
								<?php wha_tour_poster_icon( $wha_tour_poster_transport_icon ); ?>
								<?php echo esc_html( $wha_tour_poster_transport ); ?>
							</span>
							<?php
						endif;
					endif;
					?>
				</div>
			<?php endif; ?>

			<div class="poster__foot">
				<?php if ( function_exists( 'wha_tours_core_price_label' ) && 'tour' === get_post_type( $wha_tour_poster_id ) ) : ?>
					<span class="poster__price">
						<small><?php esc_html_e( 'price', 'wha-tour-poster' ); ?></small>
						<?php echo esc_html( wha_tours_core_price_label( $wha_tour_poster_meta['price'] ) ); ?>
					</span>
				<?php endif; ?>

				<?php if ( '' !== $wha_tour_poster_dates_label ) : ?>
					<span class="poster__date poster__date--<?php echo esc_attr( isset( $wha_tour_poster_dates_next['status'] ) ? $wha_tour_poster_dates_next['status'] : 'ok' ); ?>"><?php echo esc_html( $wha_tour_poster_dates_label ); ?></span>
				<?php elseif ( 'tour' !== get_post_type( $wha_tour_poster_id ) ) : ?>
					<span class="poster__date"><?php echo esc_html( get_the_date( '', $wha_tour_poster_id ) ); ?></span>
				<?php endif; ?>
			</div>
		</div>
	</a>
</article>
