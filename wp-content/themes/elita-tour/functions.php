<?php
/**
 * Elita Tour functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Elita_Tour
 */

if ( ! defined( 'ELITA_TOUR_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( 'ELITA_TOUR_VERSION', '1.0.0' );
}

if ( ! function_exists( 'elita_tour_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function elita_tour_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 */
		load_theme_textdomain( 'elita-tour', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		// Poster card (3 / 4), category panel (3 / 4 tall) and wide media (4 / 3).
		add_image_size( 'elita-tour-poster', 600, 800, true );
		add_image_size( 'elita-tour-panel', 900, 1200, true );
		add_image_size( 'elita-tour-wide', 1280, 960, true );

		// This theme uses wp_nav_menu() in two locations.
		register_nav_menus(
			array(
				'primary'      => esc_html__( 'Primary Menu', 'elita-tour' ),
				'footer-legal' => esc_html__( 'Footer Legal Menu', 'elita-tour' ),
			)
		);

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
				'navigation-widgets',
			)
		);

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support(
			'custom-logo',
			array(
				'height'      => 74,
				'width'       => 104,
				'flex-width'  => true,
				'flex-height' => true,
			)
		);

		// Make media and embeds behave in the fluid poster layout.
		add_theme_support( 'responsive-embeds' );

		// Editor: wide alignments, theme styles and the default block styles.
		add_theme_support( 'align-wide' );
		add_theme_support( 'editor-styles' );
		add_theme_support( 'wp-block-styles' );

		/*
		 * Starter content: the pages, menus and Customizer settings a fresh
		 * site needs to show the landing layout. WordPress only offers it in
		 * the Customizer of a site without content, and nothing here depends
		 * on a plugin.
		 */
		add_theme_support(
			'starter-content',
			array(
				'posts'      => array(
					'home'    => array(
						'post_type'  => 'page',
						'post_title' => _x( 'Home', 'Theme starter content', 'elita-tour' ),
					),
					'blog'    => array(
						'post_type'  => 'page',
						'post_title' => _x( 'Blog', 'Theme starter content', 'elita-tour' ),
					),
					'about'   => array(
						'post_title' => _x( 'About us', 'Theme starter content', 'elita-tour' ),
					),
					'contact' => array(
						'post_title' => _x( 'Contacts', 'Theme starter content', 'elita-tour' ),
					),
				),
				'options'    => array(
					'show_on_front'  => 'page',
					'page_on_front'  => '{{home}}',
					'page_for_posts' => '{{blog}}',
				),
				'nav_menus'  => array(
					'primary'      => array(
						'name'  => __( 'Primary Menu', 'elita-tour' ),
						'items' => array(
							'page_about',
							'page_contact',
						),
					),
					'footer-legal' => array(
						'name'  => __( 'Footer Legal Menu', 'elita-tour' ),
						'items' => array(
							'link_terms' => array(
								'type'  => 'custom',
								'title' => _x( 'Terms of service', 'Theme starter content', 'elita-tour' ),
								'url'   => home_url( '/terms/' ),
							),
						),
					),
				),
				'theme_mods' => array(
					'elita_tour_phone'         => _x( '+1 555 0100', 'Theme starter content', 'elita-tour' ),
					'elita_tour_hero_eyebrow'  => _x( 'Tour operator for children', 'Theme starter content', 'elita-tour' ),
					'elita_tour_hero_title'    => _x( 'Where are we going', 'Theme starter content', 'elita-tour' ),
					'elita_tour_hero_accent'   => _x( 'for the holidays?', 'Theme starter content', 'elita-tour' ),
					'elita_tour_hero_lead'     => _x( 'Seaside camps, coach tours around Europe, excursions at home and adventure routes for school groups and families. Pick a direction, we arrange the rest.', 'Theme starter content', 'elita-tour' ),
					'elita_tour_fact_1_number' => '28',
					'elita_tour_fact_1_title'  => _x( 'years of travel for children', 'Theme starter content', 'elita-tour' ),
					'elita_tour_fact_1_text'   => _x( 'We know how to take a group through a border, a hotel and a museum without any stress.', 'Theme starter content', 'elita-tour' ),
					'elita_tour_fact_2_number' => '1',
					'elita_tour_fact_2_accent' => ':1',
					'elita_tour_fact_2_title'  => _x( 'group leader of our own', 'Theme starter content', 'elita-tour' ),
					'elita_tour_fact_2_text'   => _x( 'With the group from departure to return, and in touch with the parents every day.', 'Theme starter content', 'elita-tour' ),
					'elita_tour_stat_1_number' => '1998',
					'elita_tour_stat_1_label'  => _x( 'year founded', 'Theme starter content', 'elita-tour' ),
					'elita_tour_stat_2_number' => '39',
					'elita_tour_stat_2_label'  => _x( 'programmes in the catalogue', 'Theme starter content', 'elita-tour' ),
					'elita_tour_stat_3_number' => '12',
					'elita_tour_stat_3_label'  => _x( 'countries in Europe', 'Theme starter content', 'elita-tour' ),
					'elita_tour_lead_heading'  => _x( 'Tell us about your group and we will pick a programme within a day', 'Theme starter content', 'elita-tour' ),
					'elita_tour_lead_text'     => _x( 'How many children, what age, when the holidays are and what budget you have. We take care of the rest.', 'Theme starter content', 'elita-tour' ),
					'elita_tour_about_page'    => '{{about}}',
				),
			)
		);
	}
endif;
add_action( 'after_setup_theme', 'elita_tour_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function elita_tour_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'elita_tour_content_width', 1280 );
}
add_action( 'after_setup_theme', 'elita_tour_content_width', 0 );

/**
 * Register widget areas.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function elita_tour_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'elita-tour' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'elita-tour' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);

	$footer_areas = array(
		'footer-1' => esc_html__( 'Footer 1', 'elita-tour' ),
		'footer-2' => esc_html__( 'Footer 2', 'elita-tour' ),
		'footer-3' => esc_html__( 'Footer 3', 'elita-tour' ),
	);

	foreach ( $footer_areas as $footer_id => $footer_name ) {
		register_sidebar(
			array(
				'name'          => $footer_name,
				'id'            => $footer_id,
				'description'   => esc_html__( 'Widgets shown in the footer column.', 'elita-tour' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h4 class="widget-title">',
				'after_title'   => '</h4>',
			)
		);
	}

	register_sidebar(
		array(
			'name'          => esc_html__( 'Lead form', 'elita-tour' ),
			'id'            => 'lead-form',
			'description'   => esc_html__( 'Drop the enquiry form widget of the Elita Tour Core plugin here.', 'elita-tour' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h4 class="widget-title">',
			'after_title'   => '</h4>',
		)
	);
}
add_action( 'widgets_init', 'elita_tour_widgets_init' );

/**
 * Flag menu items that point at a tour category (or a plain category) so the
 * navigation can underline them like the reference layout does.
 *
 * @param string[] $classes Array of the CSS classes applied to the menu item's <li>.
 * @param WP_Post  $item    The current menu item.
 * @return string[] Filtered classes.
 */
function elita_tour_nav_menu_css_class( $classes, $item ) {
	if ( isset( $item->object ) && in_array( $item->object, array( 'tour_category', 'category' ), true ) ) {
		$classes[] = 'is-cat';
	}

	return $classes;
}
add_filter( 'nav_menu_css_class', 'elita_tour_nav_menu_css_class', 10, 2 );

/**
 * Mirror the `is-cat` flag onto the menu link, because the reference styles
 * target `.nav a.is-cat`.
 *
 * @param array   $atts HTML attributes applied to the menu item's <a>.
 * @param WP_Post $item The current menu item.
 * @return array Filtered attributes.
 */
function elita_tour_nav_menu_link_attributes( $atts, $item ) {
	if ( isset( $item->object ) && in_array( $item->object, array( 'tour_category', 'category' ), true ) ) {
		$atts['class'] = isset( $atts['class'] ) ? trim( $atts['class'] . ' is-cat' ) : 'is-cat';
	}

	return $atts;
}
add_filter( 'nav_menu_link_attributes', 'elita_tour_nav_menu_link_attributes', 10, 2 );

/**
 * Scripts, styles and editor styles.
 */
require get_template_directory() . '/inc/enqueue.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Tour data helpers, with fallbacks for a site without the companion plugin.
 */
require get_template_directory() . '/inc/tours.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}
