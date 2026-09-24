<?php
/**
 * Front-end and editor assets.
 *
 * @package WHA_Tour_Poster
 */

/**
 * Enqueue styles and scripts.
 *
 * The stylesheets form a dependency chain so the cascade order is guaranteed:
 * the design tokens, then the stylesheet that consumes them, then style.css.
 * The @font-face rules are printed by WordPress itself from the `fontFace`
 * entries of theme.json, so the theme ships no separate font stylesheet.
 */
function wha_tour_poster_scripts() {
	wp_enqueue_style(
		'wha-tour-poster-tokens',
		get_theme_file_uri( 'assets/css/tokens.css' ),
		array(),
		WHA_TOUR_POSTER_VERSION
	);

	wp_enqueue_style(
		'wha-tour-poster-main',
		get_theme_file_uri( 'assets/css/main.css' ),
		array( 'wha-tour-poster-tokens' ),
		WHA_TOUR_POSTER_VERSION
	);

	// The theme stylesheet comes last so a child theme can override everything.
	// `get_stylesheet_uri()` resolves to the child theme's style.css when one is active.
	wp_enqueue_style(
		'wha-tour-poster-style',
		get_stylesheet_uri(),
		array( 'wha-tour-poster-main' ),
		WHA_TOUR_POSTER_VERSION
	);

	wp_enqueue_script(
		'wha-tour-poster-main',
		get_theme_file_uri( 'assets/js/main.js' ),
		array(),
		WHA_TOUR_POSTER_VERSION,
		array(
			'in_footer' => true,
			'strategy'  => 'defer',
		)
	);

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'wha_tour_poster_scripts' );

/**
 * Register the block editor stylesheets so the editor matches the front end.
 *
 * The design tokens are added as a separate editor style, because
 * assets/css/editor.css only carries the content rules. The font faces come
 * from the `fontFace` entries of theme.json, which the editor loads on its own.
 */
function wha_tour_poster_editor_styles() {
	add_editor_style(
		array(
			'assets/css/tokens.css',
			'assets/css/editor.css',
		)
	);
}
add_action( 'after_setup_theme', 'wha_tour_poster_editor_styles' );
