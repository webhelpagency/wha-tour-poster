<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * Ported from the reference layout: .header / .header__row and the .mnav drawer.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Elita_Tour
 */

$elita_tour_description = get_bloginfo( 'description', 'display' );
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'elita-tour' ); ?></a>

	<header id="masthead" class="header">
		<div class="container header__row">
			<div class="header__logo">
				<?php
				if ( has_custom_logo() ) {
					the_custom_logo();
				}
				?>
				<a class="header__brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
					<span class="site-title"><?php bloginfo( 'name' ); ?></span>
					<?php if ( $elita_tour_description || is_customize_preview() ) : ?>
						<small class="site-description"><?php echo esc_html( $elita_tour_description ); ?></small>
					<?php endif; ?>
				</a>
			</div>

			<?php if ( has_nav_menu( 'primary' ) ) : ?>
				<nav id="site-navigation" class="nav" aria-label="<?php esc_attr_e( 'Primary menu', 'elita-tour' ); ?>">
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'primary',
							'container'      => false,
							'menu_class'     => 'nav__list',
							'menu_id'        => 'primary-menu',
							'depth'          => 1,
							'fallback_cb'    => false,
						)
					);
					?>
				</nav><!-- #site-navigation -->
			<?php endif; ?>

			<?php
			elita_tour_the_header_phone();
			elita_tour_the_header_cta();
			?>

			<button class="burger" type="button" aria-label="<?php esc_attr_e( 'Open menu', 'elita-tour' ); ?>" aria-controls="mnav" aria-expanded="false" data-menu-open>
				<?php elita_tour_icon( 'menu', 'icon--lg' ); ?>
			</button>
		</div>
	</header><!-- #masthead -->

	<div class="mnav" id="mnav" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'Site menu', 'elita-tour' ); ?>" aria-hidden="true">
		<div class="mnav__top">
			<a class="mnav__brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
				<?php
				if ( has_custom_logo() ) {
					the_custom_logo();
				} else {
					bloginfo( 'name' );
				}
				?>
			</a>
			<button class="burger" type="button" aria-label="<?php esc_attr_e( 'Close menu', 'elita-tour' ); ?>" data-menu-close>
				<?php elita_tour_icon( 'close', 'icon--lg' ); ?>
			</button>
		</div>

		<?php if ( has_nav_menu( 'primary' ) ) : ?>
			<nav aria-label="<?php esc_attr_e( 'Primary menu', 'elita-tour' ); ?>">
				<?php
				// The same menu is rendered twice, so drop the item IDs here to keep them unique.
				add_filter( 'nav_menu_item_id', '__return_empty_string' );

				wp_nav_menu(
					array(
						'theme_location' => 'primary',
						'container'      => false,
						'menu_class'     => 'mnav__list',
						'menu_id'        => 'mnav-menu',
						'depth'          => 1,
						'fallback_cb'    => false,
					)
				);

				remove_filter( 'nav_menu_item_id', '__return_empty_string' );
				?>
			</nav>
		<?php endif; ?>

		<div class="mnav__bottom">
			<?php
			$elita_tour_phone      = elita_tour_get_phone();
			$elita_tour_phone_href = elita_tour_get_phone_href();

			if ( '' !== $elita_tour_phone && '' !== $elita_tour_phone_href ) :
				?>
				<a class="btn btn--on-dark" href="<?php echo esc_url( $elita_tour_phone_href, array( 'tel' ) ); ?>"><?php elita_tour_icon( 'phone' ); ?><?php echo esc_html( $elita_tour_phone ); ?></a>
			<?php endif; ?>
			<a class="btn btn--primary" href="<?php echo esc_url( elita_tour_get_cta_url() ); ?>"><?php echo esc_html( elita_tour_get_cta_text() ); ?></a>
		</div>
	</div><!-- #mnav -->
