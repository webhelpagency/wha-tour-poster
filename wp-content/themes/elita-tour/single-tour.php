<?php
/**
 * The template for displaying a single programme
 *
 * Only ever loaded when the Elita Tour Core plugin registers the `tour` post
 * type, but every plugin helper is still guarded, so that the template also
 * survives a half-loaded plugin.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Elita_Tour
 */

get_header();
?>

	<main id="primary" class="site-main" tabindex="-1">

		<?php
		while ( have_posts() ) :
			the_post();

			$elita_tour_id   = (int) get_the_ID();
			$elita_tour_term = elita_tour_get_post_term( $elita_tour_id );

			$elita_tour_meta = array(
				'days'      => 0,
				'transport' => '',
				'price'     => '',
				'route'     => array(),
				'dates'     => array(),
				'hit'       => false,
			);

			if ( function_exists( 'elita_tour_core_get_meta' ) ) {
				$elita_tour_meta = array_merge( $elita_tour_meta, elita_tour_core_get_meta( $elita_tour_id ) );
			}

			// get_the_term_list() hands back false when the programme carries no
			// country, so the list is normalised to a string and the "Countries"
			// group of the card is left out rather than rendered empty.
			$elita_tour_countries = get_the_term_list( $elita_tour_id, 'tour_country', '', ', ' );

			if ( is_wp_error( $elita_tour_countries ) || ! is_string( $elita_tour_countries ) ) {
				$elita_tour_countries = '';
			}

			$elita_tour_statuses = array(
				'ok'     => __( 'Seats available', 'elita-tour' ),
				'few'    => __( 'Few seats', 'elita-tour' ),
				'closed' => __( 'Closed', 'elita-tour' ),
			);

			$elita_tour_phone      = elita_tour_get_phone();
			$elita_tour_phone_href = elita_tour_get_phone_href();
			?>

			<article id="post-<?php the_ID(); ?>" <?php post_class( 'tour-single' ); ?>>

				<div class="tour-hero">
					<?php if ( has_post_thumbnail() ) : ?>
						<div class="tour-hero__media">
							<?php
							echo wp_kses(
								get_the_post_thumbnail(
									$elita_tour_id,
									'elita-tour-wide',
									array(
										'alt'           => '',
										'fetchpriority' => 'high',
									)
								),
								elita_tour_image_allowed_html()
							);
							?>
						</div>
					<?php endif; ?>

					<div class="container tour-hero__inner">
						<?php if ( $elita_tour_term instanceof WP_Term || ! empty( $elita_tour_meta['hit'] ) ) : ?>
							<div class="tour-hero__badges">
								<?php if ( $elita_tour_term instanceof WP_Term ) : ?>
									<a class="badge badge--cat badge--cat-<?php echo esc_attr( elita_tour_term_color( $elita_tour_term ) ); ?>" href="<?php echo esc_url( (string) get_term_link( $elita_tour_term ) ); ?>"><?php echo esc_html( $elita_tour_term->name ); ?></a>
								<?php endif; ?>

								<?php if ( ! empty( $elita_tour_meta['hit'] ) ) : ?>
									<span class="badge badge--hit"><?php elita_tour_icon( 'star' ); ?><?php esc_html_e( 'Hit', 'elita-tour' ); ?></span>
								<?php endif; ?>
							</div>
						<?php endif; ?>

						<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

						<?php if ( ! empty( $elita_tour_meta['route'] ) ) : ?>
							<div class="route">
								<?php foreach ( $elita_tour_meta['route'] as $elita_tour_stop ) : ?>
									<span class="route__stop"><?php echo esc_html( $elita_tour_stop ); ?></span>
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
										'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'elita-tour' ),
										'after'  => '</div>',
									)
								);
								?>
							</div><!-- .entry-content -->

							<aside class="tour-card" aria-label="<?php esc_attr_e( 'Programme details', 'elita-tour' ); ?>">
								<?php if ( function_exists( 'elita_tour_core_price_label' ) ) : ?>
									<p class="price tour-card__price">
										<small><?php esc_html_e( 'Price', 'elita-tour' ); ?></small>
										<strong><?php echo esc_html( elita_tour_core_price_label( $elita_tour_meta['price'] ) ); ?></strong>
									</p>
								<?php endif; ?>

								<?php
								// Theme Check reads a quoted array key inside _n() as a missing
								// singular string, so the count is hoisted into a variable first.
								$elita_tour_days = absint( $elita_tour_meta['days'] );

								if ( $elita_tour_days || ! empty( $elita_tour_meta['transport'] ) ) :
									?>
									<div class="card__meta">
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

											if ( '' !== $elita_tour_transport ) :
												?>
												<span>
													<?php elita_tour_icon( elita_tour_core_transport_icon( $elita_tour_meta['transport'] ) ); ?>
													<?php echo esc_html( $elita_tour_transport ); ?>
												</span>
												<?php
											endif;
										endif;
										?>
									</div>
								<?php endif; ?>

								<?php if ( ! empty( $elita_tour_meta['dates'] ) && function_exists( 'elita_tour_core_date_range_label' ) ) : ?>
									<div class="tour-card__group">
										<h2 class="tour-card__label"><?php esc_html_e( 'Departure dates', 'elita-tour' ); ?></h2>

										<ul class="tour-card__dates">
											<?php
											foreach ( $elita_tour_meta['dates'] as $elita_tour_date ) :
												$elita_tour_label = elita_tour_core_date_range_label( $elita_tour_date['start'], $elita_tour_date['end'] );

												if ( '' === $elita_tour_label ) {
													continue;
												}

												$elita_tour_status = isset( $elita_tour_statuses[ $elita_tour_date['status'] ] ) ? $elita_tour_date['status'] : 'ok';
												?>
												<li class="tour-card__date">
													<span class="date date--<?php echo esc_attr( $elita_tour_status ); ?>"><?php echo esc_html( $elita_tour_label ); ?></span>
													<span class="tour-card__status"><?php echo esc_html( $elita_tour_statuses[ $elita_tour_status ] ); ?></span>
												</li>
											<?php endforeach; ?>
										</ul>
									</div>
								<?php endif; ?>

								<?php if ( ! empty( $elita_tour_meta['route'] ) ) : ?>
									<div class="tour-card__group">
										<h2 class="tour-card__label"><?php esc_html_e( 'Route', 'elita-tour' ); ?></h2>

										<div class="route">
											<?php foreach ( $elita_tour_meta['route'] as $elita_tour_stop ) : ?>
												<span class="route__stop"><?php echo esc_html( $elita_tour_stop ); ?></span>
											<?php endforeach; ?>
										</div>
									</div>
								<?php endif; ?>

								<?php if ( '' !== $elita_tour_countries ) : ?>
									<div class="tour-card__group">
										<h2 class="tour-card__label"><?php esc_html_e( 'Countries', 'elita-tour' ); ?></h2>
										<p class="tour-card__countries"><?php echo wp_kses_post( $elita_tour_countries ); ?></p>
									</div>
								<?php endif; ?>

								<a class="btn btn--primary btn--block" href="#contact"><?php esc_html_e( 'Request this programme', 'elita-tour' ); ?></a>

								<?php if ( '' !== $elita_tour_phone && '' !== $elita_tour_phone_href ) : ?>
									<a class="tour-card__phone" href="<?php echo esc_url( $elita_tour_phone_href, array( 'tel' ) ); ?>"><?php elita_tour_icon( 'phone' ); ?><?php echo esc_html( $elita_tour_phone ); ?></a>
								<?php endif; ?>
							</aside><!-- .tour-card -->

						</div><!-- .tour-layout -->
					</div><!-- .container -->
				</div>

			</article><!-- #post-<?php the_ID(); ?> -->

			<?php
			// "Similar programmes": the same category, this programme excluded.
			$elita_tour_similar = null;

			if ( elita_tour_has_core() && $elita_tour_term instanceof WP_Term && 'tour_category' === $elita_tour_term->taxonomy ) {
				$elita_tour_similar = elita_tour_core_get_tours(
					array(
						'category' => $elita_tour_term->slug,
						'exclude'  => array( $elita_tour_id ),
						'limit'    => 4,
					)
				);
			}

			if ( $elita_tour_similar instanceof WP_Query && ! empty( $elita_tour_similar->posts ) ) :
				?>
				<section class="section section--alt">
					<div class="container">
						<div class="section__head">
							<div>
								<span class="eyebrow"><?php esc_html_e( 'Nearby', 'elita-tour' ); ?></span>
								<h2><?php esc_html_e( 'Similar programmes', 'elita-tour' ); ?></h2>
							</div>

							<a class="btn btn--ghost" href="<?php echo esc_url( elita_tour_archive_link() ); ?>"><?php esc_html_e( 'All programmes', 'elita-tour' ); ?> <?php elita_tour_icon( 'arrow' ); ?></a>
						</div>

						<div class="rail rail--4">
							<?php
							foreach ( $elita_tour_similar->posts as $elita_tour_post ) {
								get_template_part(
									'template-parts/tour/poster',
									null,
									array( 'post' => $elita_tour_post )
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
