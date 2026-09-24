<?php
/**
 * Lead request form: shortcode, block and submission handler.
 *
 * @package WHA_Tours_Core
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
function wha_tours_core_lead_status() {
	// Read-only display flag, no form is processed here.
	$status = isset( $_GET['wha_lead'] ) ? sanitize_key( wp_unslash( $_GET['wha_lead'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

	return in_array( $status, array( 'ok', 'error' ), true ) ? $status : '';
}

/**
 * Register the lead form shortcode.
 *
 * @return void
 */
function wha_tours_core_register_shortcode() {
	add_shortcode( 'wha_tours_lead_form', 'wha_tours_core_lead_form_shortcode' );
}
add_action( 'init', 'wha_tours_core_register_shortcode' );

/**
 * Render the `[wha_tours_lead_form]` shortcode.
 *
 * @param array|string $atts Shortcode attributes.
 * @return string
 */
function wha_tours_core_lead_form_shortcode( $atts ) {
	$atts = shortcode_atts(
		array(
			'button' => '',
			'note'   => '',
		),
		$atts,
		'wha_tours_lead_form'
	);

	return wha_tours_core_lead_form_html( $atts );
}

/**
 * Register the dynamic lead form block.
 *
 * @return void
 */
function wha_tours_core_register_block() {
	if ( ! function_exists( 'register_block_type' ) ) {
		return;
	}

	$block = WHA_TOURS_CORE_PATH . 'blocks/lead-form';

	if ( ! file_exists( $block . '/block.json' ) ) {
		return;
	}

	register_block_type(
		$block,
		array(
			'render_callback' => 'wha_tours_core_lead_form_block',
		)
	);
}
add_action( 'init', 'wha_tours_core_register_block', 20 );

/**
 * Render callback of the `wha-tours-core/lead-form` block.
 *
 * @param array $attributes Block attributes.
 * @return string
 */
function wha_tours_core_lead_form_block( $attributes = array() ) {
	$atts = array(
		'button' => isset( $attributes['button'] ) ? $attributes['button'] : '',
		'note'   => isset( $attributes['note'] ) ? $attributes['note'] : '',
	);

	$wrapper = function_exists( 'get_block_wrapper_attributes' ) ? get_block_wrapper_attributes() : '';
	$form    = wha_tours_core_lead_form_html( $atts );

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
function wha_tours_core_lead_notice_id( $instance = 1 ) {
	return 'wha-lead-' . absint( $instance ) . '-notice';
}

/**
 * URL the form returns to after a submission.
 *
 * @return string
 */
function wha_tours_core_current_url() {
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
function wha_tours_core_lead_form_html( $atts = array() ) {
	static $instance = 0;

	++$instance;

	wp_enqueue_style( 'wha-tours-core' );

	$button = ! empty( $atts['button'] ) ? sanitize_text_field( $atts['button'] ) : __( 'Send request', 'wha-tours-core' );
	$note   = ! empty( $atts['note'] ) ? sanitize_text_field( $atts['note'] ) : __( 'We will call you back during the working day. No spam, no newsletters.', 'wha-tours-core' );

	$prefix = 'wha-lead-' . $instance . '-';
	$status = wha_tours_core_lead_status();

	ob_start();
	?>
	<form class="form" id="<?php echo esc_attr( $prefix . 'form' ); ?>" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
		<?php
		wp_nonce_field( 'wha_tours_core_lead', 'wha_tours_core_lead_nonce' );

		if ( 'ok' === $status ) {
			?>
			<p class="form__note wha-lead-notice wha-lead-notice--ok" id="<?php echo esc_attr( wha_tours_core_lead_notice_id( $instance ) ); ?>" tabindex="-1" role="status">
				<?php esc_html_e( 'Thank you! We have received your request and will call you back shortly.', 'wha-tours-core' ); ?>
			</p>
			<?php
		} elseif ( 'error' === $status ) {
			?>
			<p class="form__note wha-lead-notice wha-lead-notice--error" id="<?php echo esc_attr( wha_tours_core_lead_notice_id( $instance ) ); ?>" tabindex="-1" role="alert">
				<?php esc_html_e( 'Sorry, the request could not be sent. Please check the name and the phone number and try again.', 'wha-tours-core' ); ?>
			</p>
			<?php
		}
		?>
		<input type="hidden" name="action" value="wha_tours_core_lead" />
		<input type="hidden" name="redirect_to" value="<?php echo esc_url( wha_tours_core_current_url() ); ?>" />
		<div class="field wha-lead-hp" aria-hidden="true">
			<label for="<?php echo esc_attr( $prefix . 'website' ); ?>"><?php esc_html_e( 'Leave this field empty', 'wha-tours-core' ); ?></label>
			<input type="text" id="<?php echo esc_attr( $prefix . 'website' ); ?>" name="wha_tours_core_website" value="" tabindex="-1" autocomplete="off" />
		</div>
		<div class="field">
			<label for="<?php echo esc_attr( $prefix . 'name' ); ?>"><?php esc_html_e( 'Name', 'wha-tours-core' ); ?></label>
			<input type="text" id="<?php echo esc_attr( $prefix . 'name' ); ?>" name="wha_lead_name" value="" autocomplete="name" placeholder="<?php esc_attr_e( 'How should we address you', 'wha-tours-core' ); ?>" required />
		</div>
		<div class="field">
			<label for="<?php echo esc_attr( $prefix . 'phone' ); ?>"><?php esc_html_e( 'Phone', 'wha-tours-core' ); ?></label>
			<input type="tel" id="<?php echo esc_attr( $prefix . 'phone' ); ?>" name="wha_lead_phone" value="" autocomplete="tel" placeholder="<?php esc_attr_e( '+38 0__ ___ __ __', 'wha-tours-core' ); ?>" required />
		</div>
		<div class="field">
			<label for="<?php echo esc_attr( $prefix . 'message' ); ?>"><?php esc_html_e( 'Comment', 'wha-tours-core' ); ?></label>
			<textarea id="<?php echo esc_attr( $prefix . 'message' ); ?>" name="wha_lead_message" rows="3" autocomplete="off" placeholder="<?php esc_attr_e( 'Group size, ages, preferred dates', 'wha-tours-core' ); ?>"></textarea>
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
function wha_tours_core_handle_lead() {
	// Verify the nonce before reading anything else from the request. Without a
	// valid nonce nothing is read and the visitor is sent back to the home page.
	$nonce = isset( $_POST['wha_tours_core_lead_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['wha_tours_core_lead_nonce'] ) ) : '';

	if ( ! wp_verify_nonce( $nonce, 'wha_tours_core_lead' ) ) {
		wp_safe_redirect( home_url( '/' ) );
		exit;
	}

	// The nonce is valid from here on, so the rest of the submission can be read.
	$redirect = home_url( '/' );

	if ( isset( $_POST['redirect_to'] ) ) {
		$redirect = wp_validate_redirect( sanitize_url( wp_unslash( $_POST['redirect_to'] ) ), home_url( '/' ) );
	}

	$honeypot = isset( $_POST['wha_tours_core_website'] ) ? trim( sanitize_text_field( wp_unslash( $_POST['wha_tours_core_website'] ) ) ) : '';

	if ( '' !== $honeypot ) {
		wha_tours_core_lead_redirect( $redirect, 'error' );
	}

	$ip  = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';
	$key = 'wha_tours_core_lead_' . md5( $ip );

	if ( get_transient( $key ) ) {
		wha_tours_core_lead_redirect( $redirect, 'error' );
	}

	$name    = isset( $_POST['wha_lead_name'] ) ? sanitize_text_field( wp_unslash( $_POST['wha_lead_name'] ) ) : '';
	$phone   = isset( $_POST['wha_lead_phone'] ) ? sanitize_text_field( wp_unslash( $_POST['wha_lead_phone'] ) ) : '';
	$message = isset( $_POST['wha_lead_message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['wha_lead_message'] ) ) : '';

	if ( '' === $name || '' === $phone ) {
		wha_tours_core_lead_redirect( $redirect, 'error' );
	}

	// One submission per IP address per minute.
	set_transient( $key, 1, MINUTE_IN_SECONDS );

	$post_id = wp_insert_post(
		array(
			'post_type'    => 'wha_tours_lead',
			'post_status'  => 'private',
			/* translators: 1: visitor name, 2: submission date. */
			'post_title'   => sprintf( __( 'Request from %1$s (%2$s)', 'wha-tours-core' ), $name, current_time( 'Y-m-d H:i' ) ),
			'post_content' => '',
		),
		true
	);

	if ( ! is_wp_error( $post_id ) && $post_id ) {
		update_post_meta( $post_id, '_wha_tours_core_lead_name', $name );
		update_post_meta( $post_id, '_wha_tours_core_lead_phone', $phone );
		update_post_meta( $post_id, '_wha_tours_core_lead_message', $message );
		update_post_meta( $post_id, '_wha_tours_core_lead_source', $redirect );
	}

	/**
	 * Filter the address the lead notification is sent to.
	 *
	 * @param string $recipient Email address.
	 */
	$recipient = apply_filters( 'wha_tours_core_lead_recipient', get_option( 'admin_email' ) );

	if ( is_email( $recipient ) ) {
		/* translators: %s: site name. */
		$subject = sprintf( __( 'New tour request on %s', 'wha-tours-core' ), wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES ) );

		$body = implode(
			"\n",
			array(
				/* translators: %s: visitor name. */
				sprintf( __( 'Name: %s', 'wha-tours-core' ), $name ),
				/* translators: %s: visitor phone number. */
				sprintf( __( 'Phone: %s', 'wha-tours-core' ), $phone ),
				/* translators: %s: visitor comment. */
				sprintf( __( 'Comment: %s', 'wha-tours-core' ), '' !== $message ? $message : '—' ),
				/* translators: %s: page URL the form was submitted from. */
				sprintf( __( 'Page: %s', 'wha-tours-core' ), $redirect ),
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
		'wha_tours_core_lead_submitted',
		$post_id,
		array(
			'name'    => $name,
			'phone'   => $phone,
			'message' => $message,
			'source'  => $redirect,
		)
	);

	wha_tours_core_lead_redirect( $redirect, 'ok' );
}
add_action( 'admin_post_nopriv_wha_tours_core_lead', 'wha_tours_core_handle_lead' );
add_action( 'admin_post_wha_tours_core_lead', 'wha_tours_core_handle_lead' );

/**
 * Redirect back to the form with a status flag.
 *
 * @param string $redirect Target URL.
 * @param string $status   Either `ok` or `error`.
 * @return void
 */
function wha_tours_core_lead_redirect( $redirect, $status ) {
	$status   = 'ok' === $status ? 'ok' : 'error';
	$redirect = $redirect ? $redirect : home_url( '/' );
	$redirect = add_query_arg( 'wha_lead', $status, $redirect );

	// Land on the notice itself, so it is scrolled into view and can take focus.
	$redirect = $redirect . '#' . wha_tours_core_lead_notice_id( 1 );

	wp_safe_redirect( $redirect, 302 );
	exit;
}
