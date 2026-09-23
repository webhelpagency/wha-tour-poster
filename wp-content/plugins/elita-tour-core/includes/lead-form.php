<?php
/**
 * Lead request form: shortcode, block and submission handler.
 *
 * @package Elita_Tour_Core
 */

defined( 'ABSPATH' ) || exit;

/**
 * Status flag of the last submission, read from the redirect URL.
 *
 * The flag is deliberately NOT registered as a public query variable: a
 * registered variable in the query string of the site root makes WordPress
 * resolve the request as the blog index instead of the static front page, so
 * the notice would never be shown next to the form it belongs to.
 *
 * @return string `ok`, `error` or an empty string.
 */
function elita_tour_core_lead_status() {
	// Read-only display flag, no form is processed here.
	$status = isset( $_GET['elita_lead'] ) ? sanitize_key( wp_unslash( $_GET['elita_lead'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

	return in_array( $status, array( 'ok', 'error' ), true ) ? $status : '';
}

/**
 * Register the lead form shortcode.
 *
 * @return void
 */
function elita_tour_core_register_shortcode() {
	add_shortcode( 'elita_tour_lead_form', 'elita_tour_core_lead_form_shortcode' );
}
add_action( 'init', 'elita_tour_core_register_shortcode' );

/**
 * Render the `[elita_tour_lead_form]` shortcode.
 *
 * @param array|string $atts Shortcode attributes.
 * @return string
 */
function elita_tour_core_lead_form_shortcode( $atts ) {
	$atts = shortcode_atts(
		array(
			'button' => '',
			'note'   => '',
		),
		$atts,
		'elita_tour_lead_form'
	);

	return elita_tour_core_lead_form_html( $atts );
}

/**
 * Register the dynamic lead form block.
 *
 * @return void
 */
function elita_tour_core_register_block() {
	if ( ! function_exists( 'register_block_type' ) ) {
		return;
	}

	$block = ELITA_TOUR_CORE_PATH . 'blocks/lead-form';

	if ( ! file_exists( $block . '/block.json' ) ) {
		return;
	}

	register_block_type(
		$block,
		array(
			'render_callback' => 'elita_tour_core_lead_form_block',
		)
	);
}
add_action( 'init', 'elita_tour_core_register_block', 20 );

/**
 * Render callback of the `elita-tour-core/lead-form` block.
 *
 * @param array $attributes Block attributes.
 * @return string
 */
function elita_tour_core_lead_form_block( $attributes = array() ) {
	$atts = array(
		'button' => isset( $attributes['button'] ) ? $attributes['button'] : '',
		'note'   => isset( $attributes['note'] ) ? $attributes['note'] : '',
	);

	$wrapper = function_exists( 'get_block_wrapper_attributes' ) ? get_block_wrapper_attributes() : '';
	$form    = elita_tour_core_lead_form_html( $atts );

	return '' !== $wrapper ? '<div ' . $wrapper . '>' . $form . '</div>' : $form;
}

/**
 * ID of the status notice of one form instance.
 *
 * The submission handler redirects to the notice of the first form on the
 * page, so the browser scrolls it into view and the notice can take focus.
 *
 * @param int $instance Form instance number on the page, starting at 1.
 * @return string Element ID.
 */
function elita_tour_core_lead_notice_id( $instance = 1 ) {
	return 'elita-lead-' . absint( $instance ) . '-notice';
}

/**
 * URL the form returns to after a submission.
 *
 * @return string
 */
function elita_tour_core_current_url() {
	if ( is_singular() ) {
		$permalink = get_permalink();

		if ( $permalink ) {
			return $permalink;
		}
	}

	global $wp;

	if ( isset( $wp->request ) && '' !== $wp->request ) {
		return home_url( user_trailingslashit( $wp->request ) );
	}

	return home_url( '/' );
}

/**
 * Build the lead form markup.
 *
 * @param array $atts {
 *     Optional. Display options.
 *
 *     @type string $button Submit button label.
 *     @type string $note   Note shown under the button.
 * }
 * @return string
 */
function elita_tour_core_lead_form_html( $atts = array() ) {
	static $instance = 0;

	++$instance;

	wp_enqueue_style( 'elita-tour-core' );

	$button = ! empty( $atts['button'] ) ? sanitize_text_field( $atts['button'] ) : __( 'Send request', 'elita-tour-core' );
	$note   = ! empty( $atts['note'] ) ? sanitize_text_field( $atts['note'] ) : __( 'We will call you back during the working day. No spam, no newsletters.', 'elita-tour-core' );

	$prefix = 'elita-lead-' . $instance . '-';
	$status = elita_tour_core_lead_status();

	ob_start();
	?>
	<form class="form" id="<?php echo esc_attr( $prefix . 'form' ); ?>" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
		<?php
		wp_nonce_field( 'elita_tour_core_lead', 'elita_tour_core_lead_nonce' );

		if ( 'ok' === $status ) {
			?>
			<p class="form__note elita-lead-notice elita-lead-notice--ok" id="<?php echo esc_attr( elita_tour_core_lead_notice_id( $instance ) ); ?>" tabindex="-1" role="status">
				<?php esc_html_e( 'Thank you! We have received your request and will call you back shortly.', 'elita-tour-core' ); ?>
			</p>
			<?php
		} elseif ( 'error' === $status ) {
			?>
			<p class="form__note elita-lead-notice elita-lead-notice--error" id="<?php echo esc_attr( elita_tour_core_lead_notice_id( $instance ) ); ?>" tabindex="-1" role="alert">
				<?php esc_html_e( 'Sorry, the request could not be sent. Please check the name and the phone number and try again.', 'elita-tour-core' ); ?>
			</p>
			<?php
		}
		?>
		<input type="hidden" name="action" value="elita_tour_core_lead" />
		<input type="hidden" name="redirect_to" value="<?php echo esc_url( elita_tour_core_current_url() ); ?>" />
		<div class="field elita-lead-hp" aria-hidden="true">
			<label for="<?php echo esc_attr( $prefix . 'website' ); ?>"><?php esc_html_e( 'Leave this field empty', 'elita-tour-core' ); ?></label>
			<input type="text" id="<?php echo esc_attr( $prefix . 'website' ); ?>" name="elita_tour_core_website" value="" tabindex="-1" autocomplete="off" />
		</div>
		<div class="field">
			<label for="<?php echo esc_attr( $prefix . 'name' ); ?>"><?php esc_html_e( 'Name', 'elita-tour-core' ); ?></label>
			<input type="text" id="<?php echo esc_attr( $prefix . 'name' ); ?>" name="elita_lead_name" value="" autocomplete="name" placeholder="<?php esc_attr_e( 'How should we address you', 'elita-tour-core' ); ?>" required />
		</div>
		<div class="field">
			<label for="<?php echo esc_attr( $prefix . 'phone' ); ?>"><?php esc_html_e( 'Phone', 'elita-tour-core' ); ?></label>
			<input type="tel" id="<?php echo esc_attr( $prefix . 'phone' ); ?>" name="elita_lead_phone" value="" autocomplete="tel" placeholder="<?php esc_attr_e( '+38 0__ ___ __ __', 'elita-tour-core' ); ?>" required />
		</div>
		<div class="field">
			<label for="<?php echo esc_attr( $prefix . 'message' ); ?>"><?php esc_html_e( 'Comment', 'elita-tour-core' ); ?></label>
			<textarea id="<?php echo esc_attr( $prefix . 'message' ); ?>" name="elita_lead_message" rows="3" autocomplete="off" placeholder="<?php esc_attr_e( 'Group size, ages, preferred dates', 'elita-tour-core' ); ?>"></textarea>
		</div>
		<button class="btn btn--primary btn--lg btn--block" type="submit"><?php echo esc_html( $button ); ?></button>
		<p class="form__note"><?php echo esc_html( $note ); ?></p>
	</form>
	<?php

	return (string) ob_get_clean();
}

/**
 * Handle a lead form submission.
 *
 * @return void
 */
function elita_tour_core_handle_lead() {
	$redirect = home_url( '/' );

	// The nonce is verified right below; the URL is only used for the redirect
	// back, and `wp_validate_redirect()` keeps it on this host.
	if ( isset( $_POST['redirect_to'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
		$redirect = wp_validate_redirect( sanitize_url( wp_unslash( $_POST['redirect_to'] ) ), home_url( '/' ) ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
	}

	$nonce = isset( $_POST['elita_tour_core_lead_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['elita_tour_core_lead_nonce'] ) ) : '';

	if ( ! wp_verify_nonce( $nonce, 'elita_tour_core_lead' ) ) {
		elita_tour_core_lead_redirect( $redirect, 'error' );
	}

	$honeypot = isset( $_POST['elita_tour_core_website'] ) ? trim( sanitize_text_field( wp_unslash( $_POST['elita_tour_core_website'] ) ) ) : '';

	if ( '' !== $honeypot ) {
		elita_tour_core_lead_redirect( $redirect, 'error' );
	}

	$ip  = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';
	$key = 'elita_tour_core_lead_' . md5( $ip );

	if ( get_transient( $key ) ) {
		elita_tour_core_lead_redirect( $redirect, 'error' );
	}

	$name    = isset( $_POST['elita_lead_name'] ) ? sanitize_text_field( wp_unslash( $_POST['elita_lead_name'] ) ) : '';
	$phone   = isset( $_POST['elita_lead_phone'] ) ? sanitize_text_field( wp_unslash( $_POST['elita_lead_phone'] ) ) : '';
	$message = isset( $_POST['elita_lead_message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['elita_lead_message'] ) ) : '';

	if ( '' === $name || '' === $phone ) {
		elita_tour_core_lead_redirect( $redirect, 'error' );
	}

	// One submission per IP address per minute.
	set_transient( $key, 1, MINUTE_IN_SECONDS );

	$post_id = wp_insert_post(
		array(
			'post_type'    => 'elita_lead',
			'post_status'  => 'private',
			/* translators: 1: visitor name, 2: submission date. */
			'post_title'   => sprintf( __( 'Request from %1$s (%2$s)', 'elita-tour-core' ), $name, current_time( 'Y-m-d H:i' ) ),
			'post_content' => '',
		),
		true
	);

	if ( ! is_wp_error( $post_id ) && $post_id ) {
		update_post_meta( $post_id, '_elita_tour_core_lead_name', $name );
		update_post_meta( $post_id, '_elita_tour_core_lead_phone', $phone );
		update_post_meta( $post_id, '_elita_tour_core_lead_message', $message );
		update_post_meta( $post_id, '_elita_tour_core_lead_source', $redirect );
	}

	/**
	 * Filter the address the lead notification is sent to.
	 *
	 * @param string $recipient Email address.
	 */
	$recipient = apply_filters( 'elita_tour_core_lead_recipient', get_option( 'admin_email' ) );

	if ( is_email( $recipient ) ) {
		/* translators: %s: site name. */
		$subject = sprintf( __( 'New tour request on %s', 'elita-tour-core' ), wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES ) );

		$body = implode(
			"\n",
			array(
				/* translators: %s: visitor name. */
				sprintf( __( 'Name: %s', 'elita-tour-core' ), $name ),
				/* translators: %s: visitor phone number. */
				sprintf( __( 'Phone: %s', 'elita-tour-core' ), $phone ),
				/* translators: %s: visitor comment. */
				sprintf( __( 'Comment: %s', 'elita-tour-core' ), '' !== $message ? $message : '—' ),
				/* translators: %s: page URL the form was submitted from. */
				sprintf( __( 'Page: %s', 'elita-tour-core' ), $redirect ),
			)
		);

		wp_mail( $recipient, $subject, $body );
	}

	/**
	 * Fires after a lead has been stored and sent.
	 *
	 * @param int|WP_Error $post_id Lead post ID.
	 * @param array        $lead    Submitted values.
	 */
	do_action(
		'elita_tour_core_lead_submitted',
		$post_id,
		array(
			'name'    => $name,
			'phone'   => $phone,
			'message' => $message,
			'source'  => $redirect,
		)
	);

	elita_tour_core_lead_redirect( $redirect, 'ok' );
}
add_action( 'admin_post_nopriv_elita_tour_core_lead', 'elita_tour_core_handle_lead' );
add_action( 'admin_post_elita_tour_core_lead', 'elita_tour_core_handle_lead' );

/**
 * Redirect back to the form with a status flag.
 *
 * @param string $redirect Target URL.
 * @param string $status   Either `ok` or `error`.
 * @return void
 */
function elita_tour_core_lead_redirect( $redirect, $status ) {
	$status   = 'ok' === $status ? 'ok' : 'error';
	$redirect = $redirect ? $redirect : home_url( '/' );
	$redirect = add_query_arg( 'elita_lead', $status, $redirect );

	// Land on the notice itself, so it is scrolled into view and can take focus.
	$redirect = $redirect . '#' . elita_tour_core_lead_notice_id( 1 );

	wp_safe_redirect( $redirect, 302 );
	exit;
}
