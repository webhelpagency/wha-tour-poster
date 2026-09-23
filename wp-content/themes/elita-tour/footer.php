<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 * Ported from the reference layout: .footer / .footer__grid / .footer__bottom.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Elita_Tour
 */

$elita_tour_tagline = get_bloginfo( 'description', 'display' );
?>

	<footer id="colophon" class="footer">
		<div class="container">
			<div class="footer__grid">
				<div>
					<?php if ( has_custom_logo() ) : ?>
						<div class="footer__logo"><?php the_custom_logo(); ?></div>
					<?php else : ?>
						<a class="footer__brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
					<?php endif; ?>

					<?php if ( $elita_tour_tagline ) : ?>
						<p class="small footer__tagline"><?php echo esc_html( $elita_tour_tagline ); ?></p>
					<?php endif; ?>

					<?php if ( elita_tour_has_social_links() ) : ?>
						<div class="social">
							<?php elita_tour_the_social_links(); ?>
						</div>
					<?php endif; ?>
				</div>

				<?php foreach ( array( 'footer-1', 'footer-2', 'footer-3' ) as $elita_tour_footer_area ) : ?>
					<?php if ( is_active_sidebar( $elita_tour_footer_area ) ) : ?>
						<div class="footer__col">
							<?php dynamic_sidebar( $elita_tour_footer_area ); ?>
						</div>
					<?php endif; ?>
				<?php endforeach; ?>
			</div>

			<div class="footer__bottom">
				<span>
					<?php
					echo esc_html(
						sprintf(
							/* translators: 1: current year, 2: site name. */
							__( '© %1$s %2$s', 'elita-tour' ),
							wp_date( 'Y' ),
							get_bloginfo( 'name' )
						)
					);
					?>
				</span>

				<?php if ( has_nav_menu( 'footer-legal' ) ) : ?>
					<nav class="footer__legal-nav" aria-label="<?php esc_attr_e( 'Footer menu', 'elita-tour' ); ?>">
						<?php
						wp_nav_menu(
							array(
								'theme_location' => 'footer-legal',
								'container'      => false,
								'menu_class'     => 'footer__legal',
								'menu_id'        => 'footer-legal-menu',
								'depth'          => 1,
								'fallback_cb'    => false,
							)
						);
						?>
					</nav>
				<?php endif; ?>

				<span class="footer__credit">
					<?php
					printf(
						/* translators: 1: theme name, 2: theme author link. */
						esc_html__( 'Theme: %1$s by %2$s', 'elita-tour' ),
						'Elita Tour',
						'<a href="https://webhelpagency.com">Web Help Agency</a>'
					);
					?>
				</span>
			</div>
		</div>
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
