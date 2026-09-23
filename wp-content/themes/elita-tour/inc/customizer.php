<?php
/**
 * Elita Tour Theme Customizer
 *
 * @package Elita_Tour
 */

/**
 * Register Customizer settings and controls.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function elita_tour_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport        = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial(
			'blogname',
			array(
				'selector'        => '.site-title',
				'render_callback' => 'elita_tour_customize_partial_blogname',
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'blogdescription',
			array(
				'selector'        => '.site-description',
				'render_callback' => 'elita_tour_customize_partial_blogdescription',
			)
		);
	}

	$wp_customize->add_panel(
		'elita_tour',
		array(
			'title'       => esc_html__( 'Elita Tour', 'elita-tour' ),
			'description' => esc_html__( 'Contacts, call to action and social links used by the header and the footer.', 'elita-tour' ),
			'priority'    => 130,
		)
	);

	// Header: phone number and call to action.
	$wp_customize->add_section(
		'elita_tour_header',
		array(
			'title' => esc_html__( 'Header', 'elita-tour' ),
			'panel' => 'elita_tour',
		)
	);

	$wp_customize->add_setting(
		'elita_tour_phone',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		)
	);
	$wp_customize->add_control(
		'elita_tour_phone',
		array(
			'label'       => esc_html__( 'Phone number', 'elita-tour' ),
			'description' => esc_html__( 'Shown in the header and in the mobile menu. Leave empty to hide the phone link.', 'elita-tour' ),
			'section'     => 'elita_tour_header',
			'type'        => 'text',
		)
	);

	$wp_customize->add_setting(
		'elita_tour_cta_text',
		array(
			'default'           => __( 'Pick a programme', 'elita-tour' ),
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		)
	);
	$wp_customize->add_control(
		'elita_tour_cta_text',
		array(
			'label'   => esc_html__( 'Call to action label', 'elita-tour' ),
			'section' => 'elita_tour_header',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'elita_tour_cta_url',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
			'transport'         => 'postMessage',
		)
	);
	$wp_customize->add_control(
		'elita_tour_cta_url',
		array(
			'label'       => esc_html__( 'Call to action link', 'elita-tour' ),
			'description' => esc_html__( 'Leave empty to link to the request form on the front page.', 'elita-tour' ),
			'section'     => 'elita_tour_header',
			// A plain text field: the default is a fragment on the front page,
			// which a `url` input would reject as an invalid URL.
			'type'        => 'text',
		)
	);

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial(
			'elita_tour_phone',
			array(
				'selector'            => '.header__phone',
				'container_inclusive' => true,
				'render_callback'     => 'elita_tour_the_header_phone',
				'fallback_refresh'    => true,
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'elita_tour_cta_text',
			array(
				'selector'            => '.header__cta',
				'container_inclusive' => true,
				'render_callback'     => 'elita_tour_the_header_cta',
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'elita_tour_cta_url',
			array(
				'selector'            => '.header__cta',
				'container_inclusive' => true,
				'render_callback'     => 'elita_tour_the_header_cta',
			)
		);
	}

	// Social links.
	$wp_customize->add_section(
		'elita_tour_social',
		array(
			'title'       => esc_html__( 'Social links', 'elita-tour' ),
			'description' => esc_html__( 'Links shown in the footer. Empty fields are not rendered.', 'elita-tour' ),
			'panel'       => 'elita_tour',
		)
	);

	$networks = array(
		'elita_tour_facebook'  => esc_html__( 'Facebook URL', 'elita-tour' ),
		'elita_tour_instagram' => esc_html__( 'Instagram URL', 'elita-tour' ),
		'elita_tour_telegram'  => esc_html__( 'Telegram URL', 'elita-tour' ),
		'elita_tour_viber'     => esc_html__( 'Viber URL', 'elita-tour' ),
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
				'section' => 'elita_tour_social',
				'type'    => 'url',
			)
		);

		if ( isset( $wp_customize->selective_refresh ) ) {
			$wp_customize->selective_refresh->add_partial(
				$setting_id,
				array(
					'selector'         => '.footer .social',
					'render_callback'  => 'elita_tour_the_social_links',
					'fallback_refresh' => true,
				)
			);
		}
	}

	// Hero: headline and lead of the homepage.
	$wp_customize->add_section(
		'elita_tour_hero',
		array(
			'title'       => esc_html__( 'Homepage hero', 'elita-tour' ),
			'description' => esc_html__( 'Headline above the category panels of the homepage.', 'elita-tour' ),
			'panel'       => 'elita_tour',
		)
	);

	$elita_tour_hero_fields = array(
		'elita_tour_hero_eyebrow' => array(
			'label'    => esc_html__( 'Eyebrow', 'elita-tour' ),
			'default'  => __( 'Tour operator for children', 'elita-tour' ),
			'type'     => 'text',
			'sanitize' => 'sanitize_text_field',
		),
		'elita_tour_hero_title'   => array(
			'label'    => esc_html__( 'Headline', 'elita-tour' ),
			'default'  => __( 'Where are we going', 'elita-tour' ),
			'type'     => 'text',
			'sanitize' => 'sanitize_text_field',
		),
		'elita_tour_hero_accent'  => array(
			'label'       => esc_html__( 'Headline accent', 'elita-tour' ),
			'description' => esc_html__( 'Second half of the headline, shown in the accent colour.', 'elita-tour' ),
			'default'     => __( 'for the holidays?', 'elita-tour' ),
			'type'        => 'text',
			'sanitize'    => 'sanitize_text_field',
		),
		'elita_tour_hero_lead'    => array(
			'label'    => esc_html__( 'Lead paragraph', 'elita-tour' ),
			'default'  => __( 'Seaside camps, coach tours around Europe, excursions at home and adventure routes for school groups and families. Pick a direction, we arrange the rest.', 'elita-tour' ),
			'type'     => 'textarea',
			'sanitize' => 'wp_kses_post',
		),
	);

	elita_tour_customize_add_fields( $wp_customize, 'elita_tour_hero', $elita_tour_hero_fields );

	// Facts: four slots of the "what we take care of" strip.
	$wp_customize->add_section(
		'elita_tour_facts',
		array(
			'title'       => esc_html__( 'Homepage facts', 'elita-tour' ),
			'description' => esc_html__( 'Four numbers shown below the programmes. A slot without a title is not rendered.', 'elita-tour' ),
			'panel'       => 'elita_tour',
		)
	);

	$elita_tour_fact_fields = array();

	for ( $elita_tour_i = 1; $elita_tour_i <= 4; $elita_tour_i++ ) {
		$elita_tour_fact_fields[ 'elita_tour_fact_' . $elita_tour_i . '_number' ] = array(
			/* translators: %d: fact number. */
			'label'    => sprintf( esc_html__( 'Fact %d: number', 'elita-tour' ), $elita_tour_i ),
			'default'  => '',
			'type'     => 'text',
			'sanitize' => 'sanitize_text_field',
		);
		$elita_tour_fact_fields[ 'elita_tour_fact_' . $elita_tour_i . '_accent' ] = array(
			/* translators: %d: fact number. */
			'label'       => sprintf( esc_html__( 'Fact %d: accent', 'elita-tour' ), $elita_tour_i ),
			'description' => esc_html__( 'Short suffix of the number, shown in the accent colour.', 'elita-tour' ),
			'default'     => '',
			'type'        => 'text',
			'sanitize'    => 'sanitize_text_field',
		);
		$elita_tour_fact_fields[ 'elita_tour_fact_' . $elita_tour_i . '_title' ]  = array(
			/* translators: %d: fact number. */
			'label'    => sprintf( esc_html__( 'Fact %d: title', 'elita-tour' ), $elita_tour_i ),
			'default'  => '',
			'type'     => 'text',
			'sanitize' => 'sanitize_text_field',
		);
		$elita_tour_fact_fields[ 'elita_tour_fact_' . $elita_tour_i . '_text' ]   = array(
			/* translators: %d: fact number. */
			'label'    => sprintf( esc_html__( 'Fact %d: text', 'elita-tour' ), $elita_tour_i ),
			'default'  => '',
			'type'     => 'textarea',
			'sanitize' => 'wp_kses_post',
		);
	}

	elita_tour_customize_add_fields( $wp_customize, 'elita_tour_facts', $elita_tour_fact_fields );

	// About: the page shown in the "about us" block and its three stats.
	$wp_customize->add_section(
		'elita_tour_about',
		array(
			'title'       => esc_html__( 'Homepage about block', 'elita-tour' ),
			'description' => esc_html__( 'Title, excerpt and featured image are taken from the selected page. Without a page the block is not rendered.', 'elita-tour' ),
			'panel'       => 'elita_tour',
		)
	);

	$wp_customize->add_setting(
		'elita_tour_about_eyebrow',
		array(
			'default'           => __( 'About us', 'elita-tour' ),
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'elita_tour_about_eyebrow',
		array(
			'label'   => esc_html__( 'Eyebrow', 'elita-tour' ),
			'section' => 'elita_tour_about',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'elita_tour_about_page',
		array(
			'default'           => 0,
			'sanitize_callback' => 'absint',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'elita_tour_about_page',
		array(
			'label'   => esc_html__( 'Page', 'elita-tour' ),
			'section' => 'elita_tour_about',
			'type'    => 'dropdown-pages',
		)
	);

	$elita_tour_stat_fields = array();

	for ( $elita_tour_i = 1; $elita_tour_i <= 3; $elita_tour_i++ ) {
		$elita_tour_stat_fields[ 'elita_tour_stat_' . $elita_tour_i . '_number' ] = array(
			/* translators: %d: stat number. */
			'label'    => sprintf( esc_html__( 'Stat %d: number', 'elita-tour' ), $elita_tour_i ),
			'default'  => '',
			'type'     => 'text',
			'sanitize' => 'sanitize_text_field',
		);
		$elita_tour_stat_fields[ 'elita_tour_stat_' . $elita_tour_i . '_label' ]  = array(
			/* translators: %d: stat number. */
			'label'    => sprintf( esc_html__( 'Stat %d: label', 'elita-tour' ), $elita_tour_i ),
			'default'  => '',
			'type'     => 'text',
			'sanitize' => 'sanitize_text_field',
		);
	}

	elita_tour_customize_add_fields( $wp_customize, 'elita_tour_about', $elita_tour_stat_fields );

	// Enquiry block.
	$wp_customize->add_section(
		'elita_tour_lead',
		array(
			'title'       => esc_html__( 'Homepage enquiry block', 'elita-tour' ),
			'description' => esc_html__( 'Text next to the enquiry form. The form itself comes from the "Lead form" widget area or from the Elita Tour Core plugin.', 'elita-tour' ),
			'panel'       => 'elita_tour',
		)
	);

	elita_tour_customize_add_fields(
		$wp_customize,
		'elita_tour_lead',
		array(
			'elita_tour_lead_heading' => array(
				'label'    => esc_html__( 'Heading', 'elita-tour' ),
				'default'  => __( 'Tell us about your group and we will pick a programme within a day', 'elita-tour' ),
				'type'     => 'text',
				'sanitize' => 'sanitize_text_field',
			),
			'elita_tour_lead_text'    => array(
				'label'    => esc_html__( 'Text', 'elita-tour' ),
				'default'  => __( 'How many children, what age, when the holidays are and what budget you have. We take care of the rest.', 'elita-tour' ),
				'type'     => 'textarea',
				'sanitize' => 'wp_kses_post',
			),
		)
	);
}
add_action( 'customize_register', 'elita_tour_customize_register' );

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
function elita_tour_customize_add_fields( $wp_customize, $section, $fields ) {
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
function elita_tour_customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function elita_tour_customize_partial_blogdescription() {
	bloginfo( 'description' );
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function elita_tour_customize_preview_js() {
	wp_enqueue_script(
		'elita-tour-customizer',
		get_theme_file_uri( 'assets/js/customizer.js' ),
		array( 'customize-preview' ),
		ELITA_TOUR_VERSION,
		true
	);
}
add_action( 'customize_preview_init', 'elita_tour_customize_preview_js' );
