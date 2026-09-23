<?php
/**
 * Custom template tags for this theme
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Elita_Tour
 */

if ( ! function_exists( 'elita_tour_posted_on' ) ) :
	/**
	 * Prints HTML with meta information for the current post-date/time.
	 */
	function elita_tour_posted_on() {
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
		}

		$time_string = sprintf(
			$time_string,
			esc_attr( get_the_date( DATE_W3C ) ),
			esc_html( get_the_date() ),
			esc_attr( get_the_modified_date( DATE_W3C ) ),
			esc_html( get_the_modified_date() )
		);

		$posted_on = sprintf(
			/* translators: %s: post date. */
			esc_html_x( 'Posted on %s', 'post date', 'elita-tour' ),
			'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
		);

		echo '<span class="posted-on">' . $posted_on . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
endif;

if ( ! function_exists( 'elita_tour_posted_by' ) ) :
	/**
	 * Prints HTML with meta information for the current author.
	 */
	function elita_tour_posted_by() {
		$byline = sprintf(
			/* translators: %s: post author. */
			esc_html_x( 'by %s', 'post author', 'elita-tour' ),
			'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
		);

		echo '<span class="byline"> ' . $byline . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
endif;

if ( ! function_exists( 'elita_tour_entry_footer' ) ) :
	/**
	 * Prints HTML with meta information for the categories, tags and comments.
	 */
	function elita_tour_entry_footer() {
		// Hide category and tag text for pages.
		if ( 'post' === get_post_type() ) {
			/* translators: used between list items, there is a space after the comma */
			$categories_list = get_the_category_list( esc_html__( ', ', 'elita-tour' ) );
			if ( $categories_list ) {
				/* translators: 1: list of categories. */
				printf( '<span class="cat-links">' . esc_html__( 'Posted in %1$s', 'elita-tour' ) . '</span>', $categories_list ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}

			/* translators: used between list items, there is a space after the comma */
			$tags_list = get_the_tag_list( '', esc_html_x( ', ', 'list item separator', 'elita-tour' ) );
			if ( $tags_list ) {
				/* translators: 1: list of tags. */
				printf( '<span class="tags-links">' . esc_html__( 'Tagged %1$s', 'elita-tour' ) . '</span>', $tags_list ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		}

		if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
			echo '<span class="comments-link">';
			comments_popup_link(
				sprintf(
					wp_kses(
						/* translators: %s: post title */
						__( 'Leave a Comment<span class="screen-reader-text"> on %s</span>', 'elita-tour' ),
						array(
							'span' => array(
								'class' => array(),
							),
						)
					),
					wp_kses_post( get_the_title() )
				)
			);
			echo '</span>';
		}

		edit_post_link(
			sprintf(
				wp_kses(
					/* translators: %s: Name of current post. Only visible to screen readers */
					__( 'Edit <span class="screen-reader-text">%s</span>', 'elita-tour' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				wp_kses_post( get_the_title() )
			),
			'<span class="edit-link">',
			'</span>'
		);
	}
endif;

if ( ! function_exists( 'elita_tour_post_thumbnail' ) ) :
	/**
	 * Displays an optional post thumbnail.
	 *
	 * Wraps the post thumbnail in an anchor element on index views, or a div
	 * element when on single views.
	 *
	 * @param string $size          Optional. Image size. Default `post-thumbnail`.
	 * @param string $wrapper_class Optional. Class of the wrapper element, so
	 *                              that the card layouts can reuse the tag.
	 *                              Default `post-thumbnail`.
	 */
	function elita_tour_post_thumbnail( $size = 'post-thumbnail', $wrapper_class = 'post-thumbnail' ) {
		if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
			return;
		}

		if ( is_singular() ) :
			?>

			<div class="<?php echo esc_attr( $wrapper_class ); ?>">
				<?php the_post_thumbnail( $size ); ?>
			</div><!-- .post-thumbnail -->

		<?php else : ?>

			<a class="<?php echo esc_attr( $wrapper_class ); ?>" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
				<?php
					the_post_thumbnail(
						$size,
						array(
							'alt' => the_title_attribute(
								array(
									'echo' => false,
								)
							),
						)
					);
				?>
			</a>

			<?php
		endif; // End is_singular().
	}
endif;


/**
 * Attributes allowed inside the bundled SVG icon sprite.
 *
 * @return array[] Tag/attribute map for wp_kses().
 */
function elita_tour_svg_allowed_html() {
	$attributes = array(
		'aria-hidden'     => true,
		'aria-labelledby' => true,
		'class'           => true,
		'cx'              => true,
		'cy'              => true,
		'd'               => true,
		'fill'            => true,
		'focusable'       => true,
		'height'          => true,
		'href'            => true,
		'id'              => true,
		'points'          => true,
		'r'               => true,
		'role'            => true,
		'rx'              => true,
		'ry'              => true,
		'stroke'          => true,
		'stroke-linecap'  => true,
		'stroke-linejoin' => true,
		'stroke-width'    => true,
		'transform'       => true,
		'viewbox'         => true,
		'width'           => true,
		'x'               => true,
		'x1'              => true,
		'x2'              => true,
		'xmlns'           => true,
		'y'               => true,
		'y1'              => true,
		'y2'              => true,
	);

	return array(
		'svg'      => $attributes,
		'symbol'   => $attributes,
		'defs'     => $attributes,
		'g'        => $attributes,
		'title'    => $attributes,
		'desc'     => $attributes,
		'use'      => $attributes,
		'path'     => $attributes,
		'circle'   => $attributes,
		'ellipse'  => $attributes,
		'rect'     => $attributes,
		'line'     => $attributes,
		'polyline' => $attributes,
		'polygon'  => $attributes,
	);
}

/**
 * Attributes allowed on the images rendered by the theme templates.
 *
 * `wp_kses_post()` drops `srcset`, `sizes` and `decoding`, which would break
 * the responsive images core generates, so the poster and panel markup is
 * filtered through this narrower list instead.
 *
 * @return array[] Tag/attribute map for wp_kses().
 */
function elita_tour_image_allowed_html() {
	return array(
		'img' => array(
			'alt'           => true,
			'class'         => true,
			'decoding'      => true,
			'fetchpriority' => true,
			'height'        => true,
			'id'            => true,
			'loading'       => true,
			'sizes'         => true,
			'src'           => true,
			'srcset'        => true,
			'style'         => true,
			'title'         => true,
			'width'         => true,
		),
	);
}

if ( ! function_exists( 'elita_tour_icon' ) ) :
	/**
	 * Prints one icon of the bundled SVG sprite.
	 *
	 * @param string $name        Icon name without the `i-` prefix, for example `phone`.
	 * @param string $extra_class Optional. Extra class names for the <svg> element.
	 */
	function elita_tour_icon( $name, $extra_class = '' ) {
		$name = preg_replace( '/[^a-z0-9\-]/', '', strtolower( (string) $name ) );

		if ( '' === $name ) {
			return;
		}

		printf(
			'<svg class="%1$s" aria-hidden="true" focusable="false"><use href="#i-%2$s"></use></svg>',
			esc_attr( trim( 'icon ' . $extra_class ) ),
			esc_attr( $name )
		);
	}
endif;

if ( ! function_exists( 'elita_tour_the_icon_sprite' ) ) :
	/**
	 * Inlines the theme icon sprite once, right after the opening <body> tag.
	 *
	 * The sprite is a static file that ships with the theme, so it is read from
	 * disk instead of being enqueued: `<use href="#i-…">` can only reference
	 * symbols that live in the same document.
	 */
	function elita_tour_the_icon_sprite() {
		static $printed = false;

		if ( $printed ) {
			return;
		}

		$printed = true;

		$file = get_theme_file_path( 'assets/images/icons.svg' );

		if ( ! is_readable( $file ) ) {
			return;
		}

		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- Reading a static file shipped with the theme, not a remote resource.
		$sprite = file_get_contents( $file );

		if ( ! $sprite ) {
			return;
		}

		// Drop an XML prolog or doctype: the sprite is inlined into an HTML document.
		$sprite = preg_replace( '/^\s*<\?xml[^>]*\?>\s*/i', '', $sprite );
		$sprite = preg_replace( '/^\s*<!DOCTYPE[^>]*>\s*/i', '', $sprite );

		echo "\n<!-- Elita Tour icon sprite: the static file assets/images/icons.svg that ships with the theme, inlined once so the icons can reference its symbols. -->\n";
		echo wp_kses( $sprite, elita_tour_svg_allowed_html() );
	}
endif;
add_action( 'wp_body_open', 'elita_tour_the_icon_sprite', 5 );

if ( ! function_exists( 'elita_tour_get_phone' ) ) :
	/**
	 * Returns the contact phone number set in the Customizer.
	 *
	 * @return string Phone number, or an empty string when it is not set.
	 */
	function elita_tour_get_phone() {
		return trim( (string) get_theme_mod( 'elita_tour_phone', '' ) );
	}
endif;

if ( ! function_exists( 'elita_tour_get_phone_href' ) ) :
	/**
	 * Returns the `tel:` URL for the Customizer phone number.
	 *
	 * @return string URL, or an empty string when no phone number is set.
	 */
	function elita_tour_get_phone_href() {
		$phone = preg_replace( '/[^0-9+]/', '', elita_tour_get_phone() );

		return $phone ? 'tel:' . $phone : '';
	}
endif;

if ( ! function_exists( 'elita_tour_get_cta_text' ) ) :
	/**
	 * Returns the call to action label.
	 *
	 * @return string Label.
	 */
	function elita_tour_get_cta_text() {
		$text = trim( (string) get_theme_mod( 'elita_tour_cta_text', '' ) );

		return '' !== $text ? $text : __( 'Pick a programme', 'elita-tour' );
	}
endif;

if ( ! function_exists( 'elita_tour_get_cta_url' ) ) :
	/**
	 * Returns the call to action URL.
	 *
	 * A bare `#contact` fragment is dead on every page but the front page, so
	 * the default points at the enquiry section of the front page instead.
	 *
	 * @return string URL.
	 */
	function elita_tour_get_cta_url() {
		$url = trim( (string) get_theme_mod( 'elita_tour_cta_url', '' ) );

		return '' !== $url ? $url : home_url( '/#contact' );
	}
endif;

if ( ! function_exists( 'elita_tour_the_header_phone' ) ) :
	/**
	 * Prints the header phone link, when a phone number is configured.
	 */
	function elita_tour_the_header_phone() {
		$phone = elita_tour_get_phone();
		$href  = elita_tour_get_phone_href();

		if ( '' === $phone || '' === $href ) {
			return;
		}
		?>
		<a class="header__phone" href="<?php echo esc_url( $href, array( 'tel' ) ); ?>"><?php elita_tour_icon( 'phone' ); ?><?php echo esc_html( $phone ); ?></a>
		<?php
	}
endif;

if ( ! function_exists( 'elita_tour_the_header_cta' ) ) :
	/**
	 * Prints the header call to action button.
	 */
	function elita_tour_the_header_cta() {
		?>
		<a class="btn btn--sm header__cta" href="<?php echo esc_url( elita_tour_get_cta_url() ); ?>"><?php echo esc_html( elita_tour_get_cta_text() ); ?></a>
		<?php
	}
endif;

if ( ! function_exists( 'elita_tour_the_social_links' ) ) :
	/**
	 * Prints the social links configured in the Customizer.
	 *
	 * Only networks with a URL are rendered.
	 */
	function elita_tour_the_social_links() {
		$networks = array(
			'facebook'  => __( 'Facebook', 'elita-tour' ),
			'instagram' => __( 'Instagram', 'elita-tour' ),
			'telegram'  => __( 'Telegram', 'elita-tour' ),
			'viber'     => __( 'Viber', 'elita-tour' ),
		);

		foreach ( $networks as $network => $label ) {
			$url = trim( (string) get_theme_mod( 'elita_tour_' . $network, '' ) );

			if ( '' === $url ) {
				continue;
			}
			?>
			<a href="<?php echo esc_url( $url ); ?>" aria-label="<?php echo esc_attr( $label ); ?>" rel="noopener"><?php elita_tour_icon( $network ); ?></a>
			<?php
		}
	}
endif;

if ( ! function_exists( 'elita_tour_has_social_links' ) ) :
	/**
	 * Whether at least one social network URL is configured.
	 *
	 * @return bool True when a social link should be rendered.
	 */
	function elita_tour_has_social_links() {
		foreach ( array( 'facebook', 'instagram', 'telegram', 'viber' ) as $network ) {
			if ( '' !== trim( (string) get_theme_mod( 'elita_tour_' . $network, '' ) ) ) {
				return true;
			}
		}

		return false;
	}
endif;
