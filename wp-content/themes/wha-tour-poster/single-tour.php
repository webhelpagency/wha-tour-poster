<?php
/**
 * The template for displaying a single programme
 *
 * Only ever loaded when the WHA Tours Core plugin registers the `tour` post
 * type, but every plugin helper is still guarded, so that the template also
 * survives a half-loaded plugin.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package WHA_Tour_Poster
 */

get_header();
?>

	<main id="primary" class="site-main" tabindex="-1">

		<?php
		while ( have_posts() ) :
			the_post();

			$wha_tour_poster_id   = (int) get_the_ID();
			$wha_tour_poster_term = wha_tour_poster_get_post_term( $wha_tour_poster_id );

			$wha_tour_poster_meta = array(
				'days'      => 0,
				'transport' => '',
				'price'     => '',
				'route'     => array(),
				'dates'     => array(),
				'hit'       => false,
			);

			if ( function_exists( 'wha_tours_core_get_meta' ) ) {
				$wha_tour_poster_meta = array_merge( $wha_tour_poster_meta, wha_tours_core_get_meta( $wha_tour_poster_id ) );
			}

			// get_the_term_list() hands back false when the programme carries no
			// country, so the list is normalised to a string and the "Countries"
			// group of the card is left out rather than rendered empty.
			$wha_tour_poster_countries = get_the_term_list( $wha_tour_poster_id, 'tour_country', '', ', ' );

			if ( is_wp_error( $wha_tour_poster_countries ) || ! is_string( $wha_tour_poster_countries ) ) {
				$wha_tour_poster_countries = '';
			}

			$wha_tour_poster_statuses = array(
				'ok'     => __( 'Seats available', 'wha-tour-poster' ),
				'few'    => __( 'Few seats', 'wha-tour-poster' ),
				'closed' => __( 'Closed', 'wha-tour-poster' ),
			);

			$wha_tour_poster_phone      = wha_tour_poster_get_phone();
			$wha_tour_poster_phone_href = wha_tour_poster_get_phone_href();
			?>

			<article id="post-<?php the_ID(); ?>" <?php post_class( 'tour-single' ); ?>>

				<div class="tour-hero">
					<?php if ( has_post_thumbnail() ) : ?>
						<div class="tour-hero__media">
							<?php
							echo wp_kses(
								get_the_post_thumbnail(
									$wha_tour_poster_id,
									'wha-tour-poster-wide',
									array(
										'alt'           => '',
										'fetchpriority' => 'high',
									)
								),
								wha_tour_poster_image_allowed_html()
							);
							?>
						</div>
					<?php endif; ?>

					<div class="container tour-hero__inner">
						<?php if ( $wha_tour_poster_term instanceof WP_Term || ! empty( $wha_tour_poster_meta['hit'] ) ) : ?>
							<div class="tour-hero__badges">
								<?php if ( $wha_tour_poster_term instanceof WP_Term ) : ?>
									<a class="badge badge--cat badge--cat-<?php echo esc_attr( wha_tour_poster_term_color( $wha_tour_poster_term ) ); ?>" href="<?php echo esc_url( (string) get_term_link( $wha_tour_poster_term ) ); ?>"><?php echo esc_html( $wha_tour_poster_term->name ); ?></a>
								<?php endif; ?>

								<?php if ( ! empty( $wha_tour_poster_meta['hit'] ) ) : ?>
									<span class="badge badge--hit"><?php wha_tour_poster_icon( 'star' ); ?><?php esc_html_e( 'Hit', 'wha-tour-poster' ); ?></span>
								<?php endif; ?>
							</div>
						<?php endif; ?>

						<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

						<?php if ( ! empty( $wha_tour_poster_meta['route'] ) ) : ?>
							<div class="route">
								<?php foreach ( $wha_tour_poster_meta['route'] as $wha_tour_poster_stop ) : ?>
									<span class="route__stop"><?php echo esc_html( $wha_tour_poster_stop ); ?></span>
								<?php endforeach; ?>
							</div>
						<?php endif; ?>
					</div>
				</div><!-- .tour-hero -->

				<div class="section">
					<div class="container">
						<div class="tour-layout">

							<div class="entry-content">
								<?php
								the_content();

								wp_link_pages(
									array(
										'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'wha-tour-poster' ),
										'after'  => '</div>',
									)
								);
								?>
							</div><!-- .entry-content -->

							<aside class="tour-card" aria-label="<?php esc_attr_e( 'Programme details', 'wha-tour-poster' ); ?>">
								<?php if ( function_exists( 'wha_tours_core_price_label' ) ) : ?>
									<p class="price tour-card__price">
										<small><?php esc_html_e( 'Price', 'wha-tour-poster' ); ?></small>
										<strong><?php echo esc_html( wha_tours_core_price_label( $wha_tour_poster_meta['price'] ) ); ?></strong>
									</p>
								<?php endif; ?>

								<?php
								// Theme Check reads a quoted array key inside _n() as a missing
								// singular string, so the count is hoisted into a variable first.
								$wha_tour_poster_days = absint( $wha_tour_poster_meta['days'] );

								if ( $wha_tour_poster_days || ! empty( $wha_tour_poster_meta['transport'] ) ) :
									?>
									<div class="card__meta">
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

											if ( '' !== $wha_tour_poster_transport ) :
												?>
												<span>
													<?php wha_tour_poster_icon( wha_tours_core_transport_icon( $wha_tour_poster_meta['transport'] ) ); ?>
													<?php echo esc_html( $wha_tour_poster_transport ); ?>
												</span>
												<?php
											endif;
										endif;
										?>
									</div>
								<?php endif; ?>

								<?php if ( ! empty( $wha_tour_poster_meta['dates'] ) && function_exists( 'wha_tours_core_date_range_label' ) ) : ?>
									<div class="tour-card__group">
										<h2 class="tour-card__label"><?php esc_html_e( 'Departure dates', 'wha-tour-poster' ); ?></h2>

										<ul class="tour-card__dates">
											<?php
											foreach ( $wha_tour_poster_meta['dates'] as $wha_tour_poster_date ) :
												$wha_tour_poster_label = wha_tours_core_date_range_label( $wha_tour_poster_date['start'], $wha_tour_poster_date['end'] );

												if ( '' === $wha_tour_poster_label ) {
													continue;
												}

												$wha_tour_poster_status = isset( $wha_tour_poster_statuses[ $wha_tour_poster_date['status'] ] ) ? $wha_tour_poster_date['status'] : 'ok';
												?>
												<li class="tour-card__date">
													<span class="date date--<?php echo esc_attr( $wha_tour_poster_status ); ?>"><?php echo esc_html( $wha_tour_poster_label ); ?></span>
													<span class="tour-card__status"><?php echo esc_html( $wha_tour_poster_statuses[ $wha_tour_poster_status ] ); ?></span>
												</li>
											<?php endforeach; ?>
										</ul>
									</div>
								<?php endif; ?>

								<?php if ( ! empty( $wha_tour_poster_meta['route'] ) ) : ?>
									<div class="tour-card__group">
										<h2 class="tour-card__label"><?php esc_html_e( 'Route', 'wha-tour-poster' ); ?></h2>

										<div class="route">
											<?php foreach ( $wha_tour_poster_meta['route'] as $wha_tour_poster_stop ) : ?>
												<span class="route__stop"><?php echo esc_html( $wha_tour_poster_stop ); ?></span>
											<?php endforeach; ?>
										</div>
									</div>
								<?php endif; ?>

								<?php if ( '' !== $wha_tour_poster_countries ) : ?>
									<div class="tour-card__group">
										<h2 class="tour-card__label"><?php esc_html_e( 'Countries', 'wha-tour-poster' ); ?></h2>
										<p class="tour-card__countries"><?php echo wp_kses_post( $wha_tour_poster_countries ); ?></p>
									</div>
								<?php endif; ?>

								<a class="btn btn--primary btn--block" href="#contact"><?php esc_html_e( 'Request this programme', 'wha-tour-poster' ); ?></a>

								<?php if ( '' !== $wha_tour_poster_phone && '' !== $wha_tour_poster_phone_href ) : ?>
									<a class="tour-card__phone" href="<?php echo esc_url( $wha_tour_poster_phone_href, array( 'tel' ) ); ?>"><?php wha_tour_poster_icon( 'phone' ); ?><?php echo esc_html( $wha_tour_poster_phone ); ?></a>
								<?php endif; ?>
							</aside><!-- .tour-card -->

						</div><!-- .tour-layout -->
					</div><!-- .container -->
				</div>

			</article><!-- #post-<?php the_ID(); ?> -->

			<?php
			// "Similar programmes": the same category, this programme excluded.
			$wha_tour_poster_similar = null;

			if ( wha_tour_poster_has_core() && $wha_tour_poster_term instanceof WP_Term && 'tour_category' === $wha_tour_poster_term->taxonomy ) {
				$wha_tour_poster_similar = wha_tours_core_get_tours(
					array(
						'category' => $wha_tour_poster_term->slug,
						'exclude'  => array( $wha_tour_poster_id ),
						'limit'    => 4,
					)
				);
			}

			if ( $wha_tour_poster_similar instanceof WP_Query && ! empty( $wha_tour_poster_similar->posts ) ) :
				?>
				<section class="section section--alt">
					<div class="container">
						<div class="section__head">
							<div>
								<span class="eyebrow"><?php esc_html_e( 'Nearby', 'wha-tour-poster' ); ?></span>
								<h2><?php esc_html_e( 'Similar programmes', 'wha-tour-poster' ); ?></h2>
							</div>

							<a class="btn btn--ghost" href="<?php echo esc_url( wha_tour_poster_archive_link() ); ?>"><?php esc_html_e( 'All programmes', 'wha-tour-poster' ); ?> <?php wha_tour_poster_icon( 'arrow' ); ?></a>
						</div>

						<div class="rail rail--4">
							<?php
							foreach ( $wha_tour_poster_similar->posts as $wha_tour_poster_post ) {
								get_template_part(
									'template-parts/tour/poster',
									null,
									array( 'post' => $wha_tour_poster_post )
								);
							}
							?>
						</div>
					</div>
				</section>
				<?php
			endif;

			get_template_part( 'template-parts/home/lead' );

			if ( comments_open() || get_comments_number() ) :
				?>
				<div class="section">
					<div class="container">
						<div class="entry">
							<?php comments_template(); ?>
						</div>
					</div>
				</div>
				<?php
			endif;

		endwhile; // End of the loop.
		?>

	</main><!-- #primary -->

<?php
get_footer();
