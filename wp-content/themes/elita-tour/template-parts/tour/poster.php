<?php
/**
 * One poster card.
 *
 * Ported from `article.poster` of the reference layout. The card works for a
 * `tour` of the companion plugin (route, duration, transport, price, departure
 * dates) and for a plain post (thumbnail, first category, title, date).
 *
 * @package Elita_Tour
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

$elita_tour_poster = isset( $args['post'] ) ? get_post( $args['post'] ) : get_post();

if ( ! $elita_tour_poster instanceof WP_Post ) {
	return;
}

$elita_tour_heading = isset( $args['heading_level'] ) ? strtolower( (string) $args['heading_level'] ) : 'h3';

if ( ! in_array( $elita_tour_heading, array( 'h2', 'h3', 'h4', 'h5', 'h6' ), true ) ) {
	$elita_tour_heading = 'h3';
}

$elita_tour_id    = (int) $elita_tour_poster->ID;
$elita_tour_title = get_the_title( $elita_tour_id );
$elita_tour_term  = elita_tour_get_post_term( $elita_tour_id );

$elita_tour_meta = array(
	'days'      => 0,
	'transport' => '',
	'price'     => '',
	'route'     => array(),
	'dates'     => array(),
	'hit'       => false,
);

if ( 'tour' === get_post_type( $elita_tour_id ) && function_exists( 'elita_tour_core_get_meta' ) ) {
	$elita_tour_meta = array_merge( $elita_tour_meta, elita_tour_core_get_meta( $elita_tour_id ) );
}

$elita_tour_dates_label = '';
$elita_tour_dates_next  = null;

if ( ! empty( $elita_tour_meta['dates'] ) && function_exists( 'elita_tour_core_format_dates' ) ) {
	$elita_tour_dates_label = elita_tour_core_format_dates( $elita_tour_meta['dates'] );
	$elita_tour_dates_next  = elita_tour_core_next_date( $elita_tour_meta['dates'] );
}
?>
<article <?php post_class( 'poster', $elita_tour_id ); ?>>
	<a class="poster__link" href="<?php echo esc_url( (string) get_permalink( $elita_tour_id ) ); ?>" aria-label="<?php echo esc_attr( $elita_tour_title ); ?>">
		<?php
		if ( has_post_thumbnail( $elita_tour_id ) ) {
			echo wp_kses(
				get_the_post_thumbnail(
					$elita_tour_id,
					'elita-tour-poster',
					array(
						'alt'     => '',
						'loading' => 'lazy',
					)
				),
				elita_tour_image_allowed_html()
			);
		} else {
			?>
			<span class="poster__img--empty" aria-hidden="true"></span>
			<?php
		}
		?>

		<div class="poster__top">
			<?php if ( $elita_tour_term instanceof WP_Term ) : ?>
				<span class="badge badge--cat badge--cat-<?php echo esc_attr( elita_tour_term_color( $elita_tour_term ) ); ?>"><?php echo esc_html( $elita_tour_term->name ); ?></span>
			<?php endif; ?>

			<?php if ( ! empty( $elita_tour_meta['hit'] ) ) : ?>
				<span class="badge badge--hit"><?php elita_tour_icon( 'star' ); ?><?php esc_html_e( 'Hit', 'elita-tour' ); ?></span>
			<?php endif; ?>
		</div>

		<div class="poster__body">
			<?php if ( ! empty( $elita_tour_meta['route'] ) ) : ?>
				<div class="route">
					<?php foreach ( $elita_tour_meta['route'] as $elita_tour_stop ) : ?>
						<span class="route__stop"><?php echo esc_html( $elita_tour_stop ); ?></span>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<<?php echo esc_html( $elita_tour_heading ); ?> class="poster__title"><?php echo esc_html( $elita_tour_title ); ?></<?php echo esc_html( $elita_tour_heading ); ?>>

			<?php
			// Theme Check reads a quoted array key inside _n() as a missing
			// singular string, so the count is hoisted into a variable first.
			$elita_tour_days = absint( $elita_tour_meta['days'] );

			if ( $elita_tour_days || ! empty( $elita_tour_meta['transport'] ) ) :
				?>
				<div class="poster__meta">
					<?php if ( $elita_tour_days ) : ?>
						<span>
							<?php elita_tour_icon( 'days' ); ?>
							<?php
							printf(
								/* translators: %d: number of days. */
								esc_html( _n( '%d day', '%d days', $elita_tour_days, 'elita-tour' ) ),
								absint( $elita_tour_days )
							);
							?>
						</span>
					<?php endif; ?>

					<?php
					if ( ! empty( $elita_tour_meta['transport'] ) && function_exists( 'elita_tour_core_transport_label' ) ) :
						$elita_tour_transport = elita_tour_core_transport_label( $elita_tour_meta['transport'] );

						// The icon helper is a plugin function too, so the card keeps a
						// sensible default when only the label helper exists.
						$elita_tour_transport_icon = function_exists( 'elita_tour_core_transport_icon' )
							? elita_tour_core_transport_icon( $elita_tour_meta['transport'] )
							: 'bus';

						if ( '' !== $elita_tour_transport ) :
							?>
							<span>
								<?php elita_tour_icon( $elita_tour_transport_icon ); ?>
								<?php echo esc_html( $elita_tour_transport ); ?>
							</span>
							<?php
						endif;
					endif;
					?>
				</div>
			<?php endif; ?>

			<div class="poster__foot">
				<?php if ( function_exists( 'elita_tour_core_price_label' ) && 'tour' === get_post_type( $elita_tour_id ) ) : ?>
					<span class="poster__price">
						<small><?php esc_html_e( 'price', 'elita-tour' ); ?></small>
						<?php echo esc_html( elita_tour_core_price_label( $elita_tour_meta['price'] ) ); ?>
					</span>
				<?php endif; ?>

				<?php if ( '' !== $elita_tour_dates_label ) : ?>
					<span class="poster__date poster__date--<?php echo esc_attr( isset( $elita_tour_dates_next['status'] ) ? $elita_tour_dates_next['status'] : 'ok' ); ?>"><?php echo esc_html( $elita_tour_dates_label ); ?></span>
				<?php elseif ( 'tour' !== get_post_type( $elita_tour_id ) ) : ?>
					<span class="poster__date"><?php echo esc_html( get_the_date( '', $elita_tour_id ) ); ?></span>
				<?php endif; ?>
			</div>
		</div>
	</a>
</article>
