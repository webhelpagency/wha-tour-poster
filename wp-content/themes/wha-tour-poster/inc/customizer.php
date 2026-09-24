<?php
/**
 * WHA Tour Poster Theme Customizer
 *
 * @package WHA_Tour_Poster
 */

/**
 * Register Customizer settings and controls.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function wha_tour_poster_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport        = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial(
			'blogname',
			array(
				'selector'        => '.site-title',
				'render_callback' => 'wha_tour_poster_customize_partial_blogname',
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'blogdescription',
			array(
				'selector'        => '.site-description',
				'render_callback' => 'wha_tour_poster_customize_partial_blogdescription',
			)
		);
	}

	$wp_customize->add_panel(
		'wha_tour_poster',
		array(
			'title'       => esc_html__( 'WHA Tour Poster', 'wha-tour-poster' ),
			'description' => esc_html__( 'Contacts, call to action and social links used by the header and the footer.', 'wha-tour-poster' ),
			'priority'    => 130,
		)
	);

	// Header: phone number and call to action.
	$wp_customize->add_section(
		'wha_tour_poster_header',
		array(
			'title' => esc_html__( 'Header', 'wha-tour-poster' ),
			'panel' => 'wha_tour_poster',
		)
	);

	$wp_customize->add_setting(
		'wha_tour_poster_phone',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		)
	);
	$wp_customize->add_control(
		'wha_tour_poster_phone',
		array(
			'label'       => esc_html__( 'Phone number', 'wha-tour-poster' ),
			'description' => esc_html__( 'Shown in the header and in the mobile menu. Leave empty to hide the phone link.', 'wha-tour-poster' ),
			'section'     => 'wha_tour_poster_header',
			'type'        => 'text',
		)
	);

	$wp_customize->add_setting(
		'wha_tour_poster_cta_text',
		array(
			'default'           => __( 'Pick a programme', 'wha-tour-poster' ),
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		)
	);
	$wp_customize->add_control(
		'wha_tour_poster_cta_text',
		array(
			'label'   => esc_html__( 'Call to action label', 'wha-tour-poster' ),
			'section' => 'wha_tour_poster_header',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'wha_tour_poster_cta_url',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
			'transport'         => 'postMessage',
		)
	);
	$wp_customize->add_control(
		'wha_tour_poster_cta_url',
		array(
			'label'       => esc_html__( 'Call to action link', 'wha-tour-poster' ),
			'description' => esc_html__( 'Leave empty to link to the request form on the front page.', 'wha-tour-poster' ),
			'section'     => 'wha_tour_poster_header',
			// A plain text field: the default is a fragment on the front page,
			// which a `url` input would reject as an invalid URL.
			'type'        => 'text',
		)
	);

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial(
			'wha_tour_poster_phone',
			array(
				'selector'            => '.header__phone',
				'container_inclusive' => true,
				'render_callback'     => 'wha_tour_poster_the_header_phone',
				'fallback_refresh'    => true,
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'wha_tour_poster_cta_text',
			array(
				'selector'            => '.header__cta',
				'container_inclusive' => true,
				'render_callback'     => 'wha_tour_poster_the_header_cta',
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'wha_tour_poster_cta_url',
			array(
				'selector'            => '.header__cta',
				'container_inclusive' => true,
				'render_callback'     => 'wha_tour_poster_the_header_cta',
			)
		);
	}

	// Social links.
	$wp_customize->add_section(
		'wha_tour_poster_social',
		array(
			'title'       => esc_html__( 'Social links', 'wha-tour-poster' ),
			'description' => esc_html__( 'Links shown in the footer. Empty fields are not rendered.', 'wha-tour-poster' ),
			'panel'       => 'wha_tour_poster',
		)
	);

	$networks = array(
		'wha_tour_poster_facebook'  => esc_html__( 'Facebook URL', 'wha-tour-poster' ),
		'wha_tour_poster_instagram' => esc_html__( 'Instagram URL', 'wha-tour-poster' ),
		'wha_tour_poster_telegram'  => esc_html__( 'Telegram URL', 'wha-tour-poster' ),
		'wha_tour_poster_viber'     => esc_html__( 'Viber URL', 'wha-tour-poster' ),
	);

	foreach ( $networks as $setting_id => $label ) {
		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => '',
				'sanitize_callback' => 'esc_url_raw',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			$setting_id,
			array(
				'label'   => $label,
				'section' => 'wha_tour_poster_social',
				'type'    => 'url',
			)
		);

		if ( isset( $wp_customize->selective_refresh ) ) {
			$wp_customize->selective_refresh->add_partial(
				$setting_id,
				array(
					'selector'         => '.footer .social',
					'render_callback'  => 'wha_tour_poster_the_social_links',
					'fallback_refresh' => true,
				)
			);
		}
	}

	// Hero: headline and lead of the homepage.
	$wp_customize->add_section(
		'wha_tour_poster_hero',
		array(
			'title'       => esc_html__( 'Homepage hero', 'wha-tour-poster' ),
			'description' => esc_html__( 'Headline above the category panels of the homepage.', 'wha-tour-poster' ),
			'panel'       => 'wha_tour_poster',
		)
	);

	$wha_tour_poster_hero_fields = array(
		'wha_tour_poster_hero_eyebrow' => array(
			'label'    => esc_html__( 'Eyebrow', 'wha-tour-poster' ),
			'default'  => __( 'Tour operator for children', 'wha-tour-poster' ),
			'type'     => 'text',
			'sanitize' => 'sanitize_text_field',
		),
		'wha_tour_poster_hero_title'   => array(
			'label'    => esc_html__( 'Headline', 'wha-tour-poster' ),
			'default'  => __( 'Where are we going', 'wha-tour-poster' ),
			'type'     => 'text',
			'sanitize' => 'sanitize_text_field',
		),
		'wha_tour_poster_hero_accent'  => array(
			'label'       => esc_html__( 'Headline accent', 'wha-tour-poster' ),
			'description' => esc_html__( 'Second half of the headline, shown in the accent colour.', 'wha-tour-poster' ),
			'default'     => __( 'for the holidays?', 'wha-tour-poster' ),
			'type'        => 'text',
			'sanitize'    => 'sanitize_text_field',
		),
		'wha_tour_poster_hero_lead'    => array(
			'label'    => esc_html__( 'Lead paragraph', 'wha-tour-poster' ),
			'default'  => __( 'Seaside camps, coach tours around Europe, excursions at home and adventure routes for school groups and families. Pick a direction, we arrange the rest.', 'wha-tour-poster' ),
			'type'     => 'textarea',
			'sanitize' => 'wp_kses_post',
		),
	);

	wha_tour_poster_customize_add_fields( $wp_customize, 'wha_tour_poster_hero', $wha_tour_poster_hero_fields );

	// Facts: four slots of the "what we take care of" strip.
	$wp_customize->add_section(
		'wha_tour_poster_facts',
		array(
			'title'       => esc_html__( 'Homepage facts', 'wha-tour-poster' ),
			'description' => esc_html__( 'Four numbers shown below the programmes. A slot without a title is not rendered.', 'wha-tour-poster' ),
			'panel'       => 'wha_tour_poster',
		)
	);

	$wha_tour_poster_fact_fields = array();

	for ( $wha_tour_poster_i = 1; $wha_tour_poster_i <= 4; $wha_tour_poster_i++ ) {
		$wha_tour_poster_fact_fields[ 'wha_tour_poster_fact_' . $wha_tour_poster_i . '_number' ] = array(
			/* translators: %d: fact number. */
			'label'    => sprintf( esc_html__( 'Fact %d: number', 'wha-tour-poster' ), $wha_tour_poster_i ),
			'default'  => '',
			'type'     => 'text',
			'sanitize' => 'sanitize_text_field',
		);
		$wha_tour_poster_fact_fields[ 'wha_tour_poster_fact_' . $wha_tour_poster_i . '_accent' ] = array(
			/* translators: %d: fact number. */
			'label'       => sprintf( esc_html__( 'Fact %d: accent', 'wha-tour-poster' ), $wha_tour_poster_i ),
			'description' => esc_html__( 'Short suffix of the number, shown in the accent colour.', 'wha-tour-poster' ),
			'default'     => '',
			'type'        => 'text',
			'sanitize'    => 'sanitize_text_field',
		);
		$wha_tour_poster_fact_fields[ 'wha_tour_poster_fact_' . $wha_tour_poster_i . '_title' ]  = array(
			/* translators: %d: fact number. */
			'label'    => sprintf( esc_html__( 'Fact %d: title', 'wha-tour-poster' ), $wha_tour_poster_i ),
			'default'  => '',
			'type'     => 'text',
			'sanitize' => 'sanitize_text_field',
		);
		$wha_tour_poster_fact_fields[ 'wha_tour_poster_fact_' . $wha_tour_poster_i . '_text' ]   = array(
			/* translators: %d: fact number. */
			'label'    => sprintf( esc_html__( 'Fact %d: text', 'wha-tour-poster' ), $wha_tour_poster_i ),
			'default'  => '',
			'type'     => 'textarea',
			'sanitize' => 'wp_kses_post',
		);
	}

	wha_tour_poster_customize_add_fields( $wp_customize, 'wha_tour_poster_facts', $wha_tour_poster_fact_fields );

	// About: the page shown in the "about us" block and its three stats.
	$wp_customize->add_section(
		'wha_tour_poster_about',
		array(
			'title'       => esc_html__( 'Homepage about block', 'wha-tour-poster' ),
			'description' => esc_html__( 'Title, excerpt and featured image are taken from the selected page. Without a page the block is not rendered.', 'wha-tour-poster' ),
			'panel'       => 'wha_tour_poster',
		)
	);

	$wp_customize->add_setting(
		'wha_tour_poster_about_eyebrow',
		array(
			'default'           => __( 'About us', 'wha-tour-poster' ),
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'wha_tour_poster_about_eyebrow',
		array(
			'label'   => esc_html__( 'Eyebrow', 'wha-tour-poster' ),
			'section' => 'wha_tour_poster_about',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'wha_tour_poster_about_page',
		array(
			'default'           => 0,
			'sanitize_callback' => 'absint',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'wha_tour_poster_about_page',
		array(
			'label'   => esc_html__( 'Page', 'wha-tour-poster' ),
			'section' => 'wha_tour_poster_about',
			'type'    => 'dropdown-pages',
		)
	);

	$wha_tour_poster_stat_fields = array();

	for ( $wha_tour_poster_i = 1; $wha_tour_poster_i <= 3; $wha_tour_poster_i++ ) {
		$wha_tour_poster_stat_fields[ 'wha_tour_poster_stat_' . $wha_tour_poster_i . '_number' ] = array(
			/* translators: %d: stat number. */
			'label'    => sprintf( esc_html__( 'Stat %d: number', 'wha-tour-poster' ), $wha_tour_poster_i ),
			'default'  => '',
			'type'     => 'text',
			'sanitize' => 'sanitize_text_field',
		);
		$wha_tour_poster_stat_fields[ 'wha_tour_poster_stat_' . $wha_tour_poster_i . '_label' ]  = array(
			/* translators: %d: stat number. */
			'label'    => sprintf( esc_html__( 'Stat %d: label', 'wha-tour-poster' ), $wha_tour_poster_i ),
			'default'  => '',
			'type'     => 'text',
			'sanitize' => 'sanitize_text_field',
		);
	}

	wha_tour_poster_customize_add_fields( $wp_customize, 'wha_tour_poster_about', $wha_tour_poster_stat_fields );

	// Enquiry block.
	$wp_customize->add_section(
		'wha_tour_poster_lead',
		array(
			'title'       => esc_html__( 'Homepage enquiry block', 'wha-tour-poster' ),
			'description' => esc_html__( 'Text next to the enquiry form. The form itself comes from the "Lead form" widget area or from the WHA Tours Core plugin.', 'wha-tour-poster' ),
			'panel'       => 'wha_tour_poster',
		)
	);

	wha_tour_poster_customize_add_fields(
		$wp_customize,
		'wha_tour_poster_lead',
		array(
			'wha_tour_poster_lead_heading' => array(
				'label'    => esc_html__( 'Heading', 'wha-tour-poster' ),
				'default'  => __( 'Tell us about your group and we will pick a programme within a day', 'wha-tour-poster' ),
				'type'     => 'text',
				'sanitize' => 'sanitize_text_field',
			),
			'wha_tour_poster_lead_text'    => array(
				'label'    => esc_html__( 'Text', 'wha-tour-poster' ),
				'default'  => __( 'How many children, what age, when the holidays are and what budget you have. We take care of the rest.', 'wha-tour-poster' ),
				'type'     => 'textarea',
				'sanitize' => 'wp_kses_post',
			),
		)
	);
}
add_action( 'customize_register', 'wha_tour_poster_customize_register' );

/**
 * Register a group of simple text settings and their controls.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 * @param string               $section      Section ID the controls belong to.
 * @param array[]              $fields       Field definitions keyed by setting ID. Each
 *                                           field takes `label`, `default`, `type`,
 *                                           `sanitize` and an optional `description`.
 * @return void
 */
function wha_tour_poster_customize_add_fields( $wp_customize, $section, $fields ) {
	foreach ( $fields as $setting_id => $field ) {
		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => isset( $field['default'] ) ? $field['default'] : '',
				'sanitize_callback' => isset( $field['sanitize'] ) ? $field['sanitize'] : 'sanitize_text_field',
				'transport'         => 'refresh',
			)
		);

		$control_args = array(
			'label'   => $field['label'],
			'section' => $section,
			'type'    => isset( $field['type'] ) ? $field['type'] : 'text',
		);

		if ( isset( $field['description'] ) ) {
			$control_args['description'] = $field['description'];
		}

		$wp_customize->add_control( $setting_id, $control_args );
	}
}

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function wha_tour_poster_customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function wha_tour_poster_customize_partial_blogdescription() {
	bloginfo( 'description' );
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function wha_tour_poster_customize_preview_js() {
	wp_enqueue_script(
		'wha-tour-poster-customizer',
		get_theme_file_uri( 'assets/js/customizer.js' ),
		array( 'customize-preview' ),
		WHA_TOUR_POSTER_VERSION,
		true
	);
}
add_action( 'customize_preview_init', 'wha_tour_poster_customize_preview_js' );
