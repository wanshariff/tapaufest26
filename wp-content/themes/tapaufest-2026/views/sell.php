<?php
/**
 * Screen: sell at TAPAUfest. One page, one form.
 *
 * Covers: sent, applications closed, already applied, missing or wrong
 * answers (inline and summarised, answers kept), too many tries, timed-out
 * form and server errors. With script on, sending failures keep the form
 * filled in and offer a retry.
 *
 * @package Tapaufest2026
 */

defined( 'ABSPATH' ) || exit;

// phpcs:disable WordPress.Security.NonceVerification.Recommended -- display-only flags set by our own redirect.
$tf_done   = isset( $_GET['applied'] );
$tf_error  = isset( $_GET['apply_error'] ) ? sanitize_key( wp_unslash( $_GET['apply_error'] ) ) : '';
$tf_bad    = isset( $_GET['apply_fields'] ) ? array_map( 'sanitize_key', explode( ',', sanitize_text_field( wp_unslash( $_GET['apply_fields'] ) ) ) ) : array();
$tf_since  = isset( $_GET['since'] ) ? sanitize_text_field( wp_unslash( $_GET['since'] ) ) : '';
$tf_draft  = isset( $_GET['draft'] ) ? get_transient( 'tf_app_draft_' . sanitize_key( wp_unslash( $_GET['draft'] ) ) ) : array();
// phpcs:enable
$tf_draft    = is_array( $tf_draft ) ? $tf_draft : array();
$tf_open     = (bool) tapaufest_setting( 'apply_open' ) && 'closed' !== $tf_error;
$tf_deadline = tapaufest_setting( 'apply_deadline' );
$tf_kinds    = tapaufest_application_types();
$tf_kind_sub = array(
	'food'     => __( 'Food and drinks', 'tapaufest-2026' ),
	'market'   => __( 'Merch, records, art, crafts', 'tapaufest-2026' ),
	'brand'    => __( 'A booth for your brand', 'tapaufest-2026' ),
	'activity' => __( 'Run something people join', 'tapaufest-2026' ),
);
$tf_msg = array(
	'expired' => __( 'The form timed out. Send it again, your answers are below.', 'tapaufest-2026' ),
	'slow'    => __( 'Too many tries. Wait a minute, then send again.', 'tapaufest-2026' ),
	'server'  => __( 'Something broke on our side. Try again, or message @tapaufest on Instagram.', 'tapaufest-2026' ),
);
?>
<div class="tf2-wrap tf2-main" id="apply">
	<div class="tf2-two">
		<div class="tf2-stack">
			<p class="tf2-eb"><?php esc_html_e( 'Sell at TAPAUfest', 'tapaufest-2026' ); ?></p>
			<h1 class="tf2-h1 tf2-h1--xl"><?php esc_html_e( 'Open a stall by the river.', 'tapaufest-2026' ); ?></h1>
			<p class="tf2-lead"><?php esc_html_e( 'WARUNG for food and drinks, PASAR for merch, records and crafts. No account needed.', 'tapaufest-2026' ); ?></p>
			<div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
				<?php echo tf2_photo( 'warung-forest', __( 'WARUNG stalls under the trees', 'tapaufest-2026' ), 'tf2-sq' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<?php echo tf2_photo( 'pasar-dj', __( 'PASAR with a DJ', 'tapaufest-2026' ), 'tf2-sq' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
			<ul class="tf2-checks">
				<?php echo tf2_check( esc_html__( 'We review every stall and reply by email or WhatsApp.', 'tapaufest-2026' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<?php if ( $tf_deadline ) : ?>
					<?php /* translators: %s: deadline. */ ?>
					<?php echo tf2_check( esc_html( sprintf( __( 'Applications close %s.', 'tapaufest-2026' ), $tf_deadline ) ), 'clock' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<?php endif; ?>
				<?php echo tf2_check( esc_html__( 'Your details stay with the TAPAUasia team. Never shared.', 'tapaufest-2026' ), 'lock' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</ul>
		</div>

		<div>
		<?php if ( $tf_done ) : ?>
			<div class="tf2-card tf2-pad--lg tf2-stack" role="status">
				<svg class="tf2-done-mark" viewBox="0 0 96 96" width="96" height="96" fill="none" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="48" cy="48" r="41" fill="#F2A93B" opacity=".35"></circle><circle class="circ" cx="48" cy="48" r="41" stroke="#A82E05" stroke-width="5"></circle><path class="draw" d="M30 49l12 12 25-26" stroke="#1E1208" stroke-width="7"></path></svg>
				<p class="tf2-h2"><?php esc_html_e( 'Got it, terima kasih!', 'tapaufest-2026' ); ?></p>
				<p><?php esc_html_e( 'A copy is in your email. Here’s what happens next.', 'tapaufest-2026' ); ?></p>
				<div class="tf2-steps">
					<div class="tf2-step"><b>1</b><span><strong><?php esc_html_e( 'We check your feed and menu', 'tapaufest-2026' ); ?></strong></span></div>
					<div class="tf2-step"><b>2</b><span><strong><?php esc_html_e( 'You hear back with a spot and stall fee', 'tapaufest-2026' ); ?></strong></span></div>
					<div class="tf2-step"><b>3</b><span><strong><?php esc_html_e( 'Your stall goes live on Makan & Pasar', 'tapaufest-2026' ); ?></strong></span></div>
				</div>
				<?php echo tf2_btn( __( 'Back to home', 'tapaufest-2026' ), home_url( '/' ), 'paper' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>

		<?php elseif ( 'duplicate' === $tf_error ) : ?>
			<div class="tf2-stack">
				<?php /* translators: %s: date. */ ?>
				<?php echo tf2_alert( 'blue', 'info', '<b>' . esc_html__( 'You already applied', 'tapaufest-2026' ) . '</b>' . ( $tf_since ? ' ' . esc_html( sprintf( __( 'on %s', 'tapaufest-2026' ), $tf_since ) ) : '' ) . '. ' . esc_html__( 'Same email or Instagram as an earlier application.', 'tapaufest-2026' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<div class="tf2-card tf2-pad--lg tf2-stack">
					<p class="tf2-h2"><?php esc_html_e( 'We’re on it.', 'tapaufest-2026' ); ?></p>
					<div class="tf2-tracker">
						<div class="is-done"><i><?php echo tf2_icon( 'check' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></i><?php esc_html_e( 'Received', 'tapaufest-2026' ); ?></div>
						<div class="is-now"><i></i><?php esc_html_e( 'In review', 'tapaufest-2026' ); ?></div>
						<div class="is-next"><i></i><?php esc_html_e( 'Spot and fee', 'tapaufest-2026' ); ?></div>
					</div>
					<p class="tf2-note"><?php esc_html_e( 'Need to change something, or applying for a different stall? Message @tapaufest and we’ll sort it.', 'tapaufest-2026' ); ?></p>
					<?php echo tf2_btn( __( 'Message @tapaufest', 'tapaufest-2026' ), 'https://www.instagram.com/tapaufest/', 'paper', array( 'external' => true, 'icon' => 'instagram' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
			</div>

		<?php elseif ( ! $tf_open ) : ?>
			<?php echo tf2_empty( 'lock', __( 'Stalls are full.', 'tapaufest-2026' ), __( 'Applications are closed for this edition. Thanks for the love.', 'tapaufest-2026' ), array_filter( array( tapaufest_setting( 'waitlist_url' ) ? tf2_btn( __( 'Tell me next year', 'tapaufest-2026' ), tapaufest_setting( 'waitlist_url' ), 'cili', array( 'icon' => 'bell', 'external' => true ) ) : tf2_btn( __( 'Follow @tapaufest', 'tapaufest-2026' ), 'https://www.instagram.com/tapaufest/', 'cili', array( 'external' => true ) ), tf2_btn( __( 'Come as a guest', 'tapaufest-2026' ), home_url( '/tickets/' ), 'paper' ) ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

		<?php else : ?>
			<form class="tf2-card tf2-pad--lg tf2-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" data-tf2-apply novalidate>
				<input type="hidden" name="action" value="tapaufest_apply">
				<?php wp_nonce_field( 'tapaufest_apply', 'tf_app_nonce' ); ?>
				<p class="tf2-hp" aria-hidden="true"><label>Website <input type="text" name="tf_app_website" tabindex="-1" autocomplete="off"></label></p>
				<div class="tf2-alert tf2-alert--cili" data-tf2-summary tabindex="-1" role="alert"<?php echo 'missing' === $tf_error ? '' : ' hidden'; ?>>
					<?php echo tf2_icon( 'alert' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<?php /* translators: %d: number of fields. */ ?>
					<div><b><?php echo esc_html( 1 === count( $tf_bad ) ? __( '1 thing to fix before we can send.', 'tapaufest-2026' ) : sprintf( __( '%d things to fix before we can send.', 'tapaufest-2026' ), max( 1, count( $tf_bad ) ) ) ); ?></b> <?php esc_html_e( 'Your answers are kept.', 'tapaufest-2026' ); ?></div>
				</div>
				<?php if ( isset( $tf_msg[ $tf_error ] ) ) : ?>
					<?php echo tf2_alert( 'slow' === $tf_error ? 'gold' : 'cili', 'slow' === $tf_error ? 'clock' : 'alert', esc_html( $tf_msg[ $tf_error ] ), 'alert' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<?php endif; ?>
				<div data-tf2-status hidden></div>
				<?php foreach ( tapaufest_application_fields() as $tf_key => $tf_f ) : ?>
					<?php
					list( $tf_label, $tf_type, $tf_req, $tf_extra ) = $tf_f;
					$tf_id   = 'tf-' . $tf_key;
					$tf_val  = isset( $tf_draft[ $tf_key ] ) ? $tf_draft[ $tf_key ] : '';
					$tf_isb  = in_array( $tf_key, $tf_bad, true );
					$tf_help = is_string( $tf_extra ) ? $tf_extra : '';
					$tf_req_attr = $tf_req ? ' required' : '';
					$tf_inv  = $tf_isb ? ' aria-invalid="true"' : '';
					$tf_desc = ' aria-describedby="' . esc_attr( $tf_id ) . '-err' . ( $tf_help ? ' ' . esc_attr( $tf_id ) . '-help' : '' ) . '"';
					?>
					<?php if ( 'radio' === $tf_type ) : ?>
						<fieldset class="tf2-field<?php echo $tf_isb ? ' is-bad' : ''; ?>">
							<legend><?php echo esc_html( $tf_label ); ?><?php echo $tf_req ? ' *' : ''; ?></legend>
							<div class="tf2-kinds">
								<?php $tf_n = 0; ?>
								<?php foreach ( $tf_extra as $tf_v => $tf_l ) : ?>
									<label class="tf2-kind"><input type="radio" id="<?php echo esc_attr( 0 === $tf_n ? $tf_id : $tf_id . '-' . $tf_v ); ?>" name="<?php echo esc_attr( $tf_key ); ?>" value="<?php echo esc_attr( $tf_v ); ?>"<?php echo $tf_req_attr; ?><?php checked( $tf_val, $tf_v ); ?>><b><?php echo esc_html( preg_replace( '/^.*\((\w+)\)$/', '$1', $tf_l ) === $tf_l ? $tf_l : preg_replace( '/^.*\((\w+)\)$/', '$1', $tf_l ) ); ?></b><span><?php echo esc_html( isset( $tf_kind_sub[ $tf_v ] ) ? $tf_kind_sub[ $tf_v ] : '' ); ?></span></label>
									<?php ++$tf_n; ?>
								<?php endforeach; ?>
							</div>
							<span class="tf2-field__err" id="<?php echo esc_attr( $tf_id ); ?>-err"<?php echo $tf_isb ? '' : ' hidden'; ?>><?php echo $tf_isb ? esc_html__( 'Pick one.', 'tapaufest-2026' ) : ''; ?></span>
						</fieldset>
					<?php else : ?>
						<div class="tf2-field<?php echo in_array( $tf_type, array( 'textarea' ), true ) ? ' is-wide' : ''; ?><?php echo $tf_isb ? ' is-bad' : ''; ?>">
							<label for="<?php echo esc_attr( $tf_id ); ?>"><?php echo esc_html( $tf_label ); ?><?php echo $tf_req ? ' *' : ''; ?></label>
							<?php if ( 'textarea' === $tf_type ) : ?>
								<textarea id="<?php echo esc_attr( $tf_id ); ?>" name="<?php echo esc_attr( $tf_key ); ?>" rows="3"<?php echo $tf_req_attr . $tf_inv . $tf_desc; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php echo esc_textarea( $tf_val ); ?></textarea>
							<?php elseif ( 'select' === $tf_type ) : ?>
								<select id="<?php echo esc_attr( $tf_id ); ?>" name="<?php echo esc_attr( $tf_key ); ?>"<?php echo $tf_req_attr . $tf_inv . $tf_desc; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
									<?php foreach ( $tf_extra as $tf_v => $tf_l ) : ?>
										<option value="<?php echo esc_attr( $tf_v ); ?>"<?php selected( $tf_val, $tf_v ); ?>><?php echo esc_html( $tf_l ); ?></option>
									<?php endforeach; ?>
								</select>
							<?php else : ?>
								<input id="<?php echo esc_attr( $tf_id ); ?>" type="<?php echo esc_attr( $tf_type ); ?>" name="<?php echo esc_attr( $tf_key ); ?>" value="<?php echo esc_attr( $tf_val ); ?>"<?php echo $tf_req_attr . $tf_inv . $tf_desc; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php echo 'email' === $tf_type ? ' autocomplete="email"' : ( 'tel' === $tf_type ? ' autocomplete="tel" inputmode="tel"' : '' ); ?>>
							<?php endif; ?>
							<?php if ( $tf_help ) : ?>
								<span class="tf2-field__help" id="<?php echo esc_attr( $tf_id ); ?>-help"><?php echo esc_html( $tf_help ); ?></span>
							<?php endif; ?>
							<span class="tf2-field__err" id="<?php echo esc_attr( $tf_id ); ?>-err"<?php echo $tf_isb ? '' : ' hidden'; ?>><?php echo $tf_isb ? esc_html__( 'Check this one.', 'tapaufest-2026' ) : ''; ?></span>
						</div>
					<?php endif; ?>
				<?php endforeach; ?>
				<div class="tf2-field is-wide<?php echo in_array( 'tf_app_consent', $tf_bad, true ) ? ' is-bad' : ''; ?>">
					<label class="tf2-consent"><input type="checkbox" id="tf-tf_app_consent" name="tf_app_consent" value="1" required data-tf2-name="<?php esc_attr_e( 'Consent', 'tapaufest-2026' ); ?>"> <span><?php esc_html_e( 'TAPAUasia may store these details to review my application and contact me about TAPAUfest. Not shared, not used for marketing.', 'tapaufest-2026' ); ?></span></label>
					<span class="tf2-field__err"<?php echo in_array( 'tf_app_consent', $tf_bad, true ) ? '' : ' hidden'; ?>><?php esc_html_e( 'Tick this so we can store your details.', 'tapaufest-2026' ); ?></span>
				</div>
				<div class="tf2-form__foot tf2-stack tf2-stack--sm">
					<button type="submit" class="tf2-btn tf2-btn--cili tf2-btn--block"><span><?php esc_html_e( 'Send application', 'tapaufest-2026' ); ?></span></button>
					<p class="tf2-note" style="text-align:center"><?php esc_html_e( 'No account needed. We reply by email or WhatsApp.', 'tapaufest-2026' ); ?></p>
				</div>
			</form>
		<?php endif; ?>
		</div>
	</div>
</div>
