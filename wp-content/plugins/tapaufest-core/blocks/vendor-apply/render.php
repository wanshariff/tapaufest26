<?php
/**
 * Stall application form.
 *
 * @package TapaufestCore
 */

defined( 'ABSPATH' ) || exit;

// phpcs:disable WordPress.Security.NonceVerification.Recommended -- display-only flags set by our own redirect.
$tf_done  = isset( $_GET['applied'] );
$tf_error = isset( $_GET['apply_error'] ) ? sanitize_key( wp_unslash( $_GET['apply_error'] ) ) : '';
// phpcs:enable
$tf_errors = array(
	'missing' => __( 'A few required answers are missing, or the email doesn’t look right. Check the form and try again.', 'tapaufest' ),
	'expired' => __( 'The form timed out. Please send it again.', 'tapaufest' ),
	'slow'    => __( 'You just sent one. Give it a minute before sending another.', 'tapaufest' ),
	'server'  => __( 'Something went wrong on our side. Please try again, or message us on Instagram @tapaufest.', 'tapaufest' ),
);
$tf_deadline = tapaufest_setting( 'apply_deadline' );
?>
<div <?php echo get_block_wrapper_attributes( array( 'class' => 'tf-apply' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> id="apply">
	<?php if ( $tf_done ) : ?>
		<div class="tf-apply__done" role="status">
			<p class="tf-apply__big"><?php esc_html_e( 'Terima kasih! Application received.', 'tapaufest' ); ?></p>
			<p><?php esc_html_e( 'We’ve emailed you a copy. The team reviews every stall and replies by email or WhatsApp once selection is done.', 'tapaufest' ); ?></p>
		</div>
	<?php elseif ( ! tapaufest_setting( 'apply_open' ) ) : ?>
		<p class="tf-empty"><?php esc_html_e( 'Stall applications are closed for this edition. Follow @tapaufest for the next call.', 'tapaufest' ); ?></p>
	<?php else : ?>
		<?php if ( $tf_error && isset( $tf_errors[ $tf_error ] ) ) : ?>
			<p class="tf-apply__error" role="alert"><?php echo esc_html( $tf_errors[ $tf_error ] ); ?></p>
		<?php endif; ?>
		<?php if ( $tf_deadline ) : ?>
			<?php /* translators: %s: deadline. */ ?>
			<p class="tf-apply__deadline"><?php echo esc_html( sprintf( __( 'Applications close %s.', 'tapaufest' ), $tf_deadline ) ); ?></p>
		<?php endif; ?>
		<form class="tf-apply__form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<input type="hidden" name="action" value="tapaufest_apply">
			<?php wp_nonce_field( 'tapaufest_apply', 'tf_app_nonce' ); ?>
			<p class="tf-apply__hp" aria-hidden="true"><label>Website <input type="text" name="tf_app_website" tabindex="-1" autocomplete="off"></label></p>
			<?php foreach ( tapaufest_application_fields() as $tf_key => $tf_f ) : ?>
				<?php
				list( $tf_label, $tf_type, $tf_req, $tf_extra ) = $tf_f;
				$tf_id   = 'tf-' . $tf_key;
				$tf_help = is_string( $tf_extra ) ? $tf_extra : '';
				$tf_wide = in_array( $tf_type, array( 'textarea', 'radio' ), true ) ? ' is-wide' : '';
				?>
				<?php if ( 'radio' === $tf_type ) : ?>
					<fieldset class="tf-field<?php echo esc_attr( $tf_wide ); ?>">
						<legend><?php echo esc_html( $tf_label ); ?><?php echo $tf_req ? ' <span class="tf-req" aria-hidden="true">*</span>' : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></legend>
						<div class="tf-choices">
							<?php foreach ( $tf_extra as $tf_v => $tf_l ) : ?>
								<label class="tf-choice"><input type="radio" name="<?php echo esc_attr( $tf_key ); ?>" value="<?php echo esc_attr( $tf_v ); ?>"<?php echo $tf_req ? ' required' : ''; ?>> <span><?php echo esc_html( $tf_l ); ?></span></label>
							<?php endforeach; ?>
						</div>
					</fieldset>
				<?php else : ?>
					<p class="tf-field<?php echo esc_attr( $tf_wide ); ?>">
						<label for="<?php echo esc_attr( $tf_id ); ?>"><?php echo esc_html( $tf_label ); ?><?php echo $tf_req ? ' <span class="tf-req" aria-hidden="true">*</span>' : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></label>
						<?php if ( 'textarea' === $tf_type ) : ?>
							<textarea id="<?php echo esc_attr( $tf_id ); ?>" name="<?php echo esc_attr( $tf_key ); ?>" rows="3"<?php echo $tf_req ? ' required' : ''; ?><?php echo $tf_help ? ' aria-describedby="' . esc_attr( $tf_id ) . '-help"' : ''; ?>></textarea>
						<?php elseif ( 'select' === $tf_type ) : ?>
							<select id="<?php echo esc_attr( $tf_id ); ?>" name="<?php echo esc_attr( $tf_key ); ?>"<?php echo $tf_req ? ' required' : ''; ?>>
								<?php foreach ( $tf_extra as $tf_v => $tf_l ) : ?>
									<option value="<?php echo esc_attr( $tf_v ); ?>"><?php echo esc_html( $tf_l ); ?></option>
								<?php endforeach; ?>
							</select>
						<?php else : ?>
							<input id="<?php echo esc_attr( $tf_id ); ?>" type="<?php echo esc_attr( $tf_type ); ?>" name="<?php echo esc_attr( $tf_key ); ?>"<?php echo $tf_req ? ' required' : ''; ?><?php echo 'email' === $tf_type ? ' autocomplete="email"' : ( 'tel' === $tf_type ? ' autocomplete="tel"' : '' ); ?><?php echo $tf_help ? ' aria-describedby="' . esc_attr( $tf_id ) . '-help"' : ''; ?>>
						<?php endif; ?>
						<?php if ( $tf_help ) : ?>
							<span class="tf-help" id="<?php echo esc_attr( $tf_id ); ?>-help"><?php echo esc_html( $tf_help ); ?></span>
						<?php endif; ?>
					</p>
				<?php endif; ?>
			<?php endforeach; ?>
			<p class="tf-field is-wide tf-consent">
				<label><input type="checkbox" name="tf_app_consent" value="1" required> <span><?php esc_html_e( 'TAPAUasia may store these details to review my application and contact me about TAPAUfest. They aren’t shared or used for marketing.', 'tapaufest' ); ?></span></label>
			</p>
			<p class="tf-field is-wide">
				<button type="submit" class="tf-btn tf-btn--cili"><?php esc_html_e( 'Send application', 'tapaufest' ); ?></button>
				<span class="tf-help"><?php esc_html_e( 'No account needed. We reply by email or WhatsApp.', 'tapaufest' ); ?></span>
			</p>
		</form>
	<?php endif; ?>
</div>
