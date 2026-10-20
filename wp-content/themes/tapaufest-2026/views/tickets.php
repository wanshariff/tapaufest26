<?php
/**
 * Screen: tickets. Pick a pass, how many, then hand off to TAPAUtix.
 *
 * Covers: early access (code goes in at TAPAUtix, never here), sold-out
 * tiers with "Ping me", a capped tier that ran out, door-only, postponed,
 * live and recap.
 *
 * @package Tapaufest2026
 */

defined( 'ABSPATH' ) || exit;

$tf_state  = tapaufest_state();
$tf_tiers  = tapaufest_get_tiers();
$tf_open   = array_values( wp_list_filter( $tf_tiers, array( 'soldout' => false ) ) );
$tf_out    = array_values( wp_list_filter( $tf_tiers, array( 'soldout' => true ) ) );
// phpcs:disable WordPress.Security.NonceVerification.Recommended -- display-only flags from our own redirect.
$tf_pinged = isset( $_GET['pinged'] );
$tf_perr   = isset( $_GET['ping_error'] );
// phpcs:enable
$tf_door   = tapaufest_setting( 'door_price' );
$tf_pay    = tapaufest_setting( 'door_pay' );
?>
<div class="tf2-wrap tf2-main" id="tickets" data-tf-tickets>
	<?php if ( 'recap' === $tf_state ) : ?>
		<?php echo tf2_head( __( 'Tickets', 'tapaufest-2026' ), __( 'That’s a wrap.', 'tapaufest-2026' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<?php echo tf2_empty( 'ticket', __( 'Sales have closed.', 'tapaufest-2026' ), __( 'Join the waitlist for early access and the early-bird price next edition.', 'tapaufest-2026' ), array_filter( array( tapaufest_setting( 'waitlist_url' ) ? tf2_btn( __( 'Join the waitlist', 'tapaufest-2026' ), tapaufest_setting( 'waitlist_url' ), 'cili', array( 'external' => true ) ) : '', tf2_btn( __( 'See the photos', 'tapaufest-2026' ), home_url( '/recap/' ), 'paper' ) ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

	<?php elseif ( 'postponed' === $tf_state ) : ?>
		<?php echo tf2_head( __( 'Tickets', 'tapaufest-2026' ), __( 'Your pass is safe.', 'tapaufest-2026' ), tapaufest_alert()['text'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<div class="tf2-two">
			<?php echo tf2_pass_safe_card(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<div class="tf2-stack">
				<?php echo tf2_btn( __( 'Refund questions', 'tapaufest-2026' ), home_url( '/faq/' ), 'paper' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<?php echo tf2_btn( __( 'Your order on TAPAUtix', 'tapaufest-2026' ), tapaufest_setting( 'tickets_url' ), 'ghost', array( 'external' => true ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
		</div>

	<?php elseif ( 'door' === $tf_state || 'live' === $tf_state ) : ?>
		<div class="tf2-two tf2-two--center">
			<div class="tf2-stack">
				<p class="tf2-eb"><?php esc_html_e( 'Sales closed online', 'tapaufest-2026' ); ?></p>
				<h1 class="tf2-h1 tf2-h1--xl"><?php esc_html_e( 'See you at the door.', 'tapaufest-2026' ); ?></h1>
				<p class="tf2-lead"><?php esc_html_e( 'Tickets at the gate while they last. Sold out at the door? We post it on Instagram first.', 'tapaufest-2026' ); ?></p>
				<div class="tf2-row">
					<?php echo tf2_btn( __( 'How to get there', 'tapaufest-2026' ), home_url( '/plan/' ), 'cili' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<?php echo tf2_btn( __( 'Already bought? Find my ticket', 'tapaufest-2026' ), tapaufest_setting( 'tickets_url' ), 'ghost', array( 'external' => true ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
			</div>
			<div class="tf2-card tf2-pad--lg tf2-stack tf2-stack--sm">
				<?php if ( $tf_door ) : ?>
					<div class="tf2-between" style="align-items:baseline"><span style="font:800 1.25rem var(--tf2-text)"><?php esc_html_e( 'Door price', 'tapaufest-2026' ); ?></span><b class="tf2-price" style="font-size:3.5rem">RM<?php echo esc_html( $tf_door ); ?></b></div>
				<?php endif; ?>
				<ul class="tf2-checks">
					<?php if ( $tf_pay ) : ?>
						<?php echo tf2_check( esc_html( $tf_pay ), 'card' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<?php endif; ?>
					<?php /* translators: %s: gate time. */ ?>
					<?php echo tf2_check( esc_html( sprintf( __( 'Gate opens %s, both days', 'tapaufest-2026' ), tapaufest_format_time( tapaufest_setting( 'gates_open' ) ) ) ), 'clock' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<?php echo tf2_check( esc_html__( 'Bring the e-ticket or ID you bought with', 'tapaufest-2026' ), 'wristband' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</ul>
			</div>
		</div>

	<?php else : ?>
		<?php echo tf2_head( __( 'Tickets by TAPAUtix', 'tapaufest-2026' ), __( 'Pick a pass', 'tapaufest-2026' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<?php if ( ! $tf_tiers ) : ?>
			<?php echo tf2_empty( 'ticket', __( 'Passes land soon.', 'tapaufest-2026' ), __( 'Join Geng TAPAU and hear first.', 'tapaufest-2026' ), array( tf2_btn( __( 'Join free', 'tapaufest-2026' ), tapaufest_setting( 'join_url' ), 'cili', array( 'external' => true ) ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<?php elseif ( ! $tf_open ) : ?>
			<?php echo tf2_empty( 'ticket', __( 'All sold out. Wow.', 'tapaufest-2026' ), __( 'Returns happen. Leave your email and we’ll ping you first.', 'tapaufest-2026' ), array( tf2_ping_form( $tf_out[0]['id'] ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<?php else : ?>
			<div class="tf2-two tf2-two--wide" data-tf2-order>
				<div class="tf2-stack">
					<?php if ( $tf_pinged ) : ?>
						<?php echo tf2_alert( 'blue', 'check', esc_html__( 'You’re on the list. We email you first if passes come back.', 'tapaufest-2026' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<?php endif; ?>
					<?php if ( 'early' === $tf_state ) : ?>
						<?php echo tf2_alert( 'gold', 'ticket', '<b>' . esc_html__( 'Early access, waitlist only.', 'tapaufest-2026' ) . '</b> ' . esc_html__( 'Enter the code from your email at TAPAUtix checkout to get the early price.', 'tapaufest-2026' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<?php endif; ?>
					<?php foreach ( $tf_out as $tf_o ) : ?>
						<?php
						$tf_alt = $tf_open[0];
						if ( $tf_o['cap'] ) {
							/* translators: 1: pass, 2: number, 3: other pass. */
							$tf_msg = sprintf( __( '<b>%1$s: all %2$d gone.</b> That was quick. Next best: %3$s.', 'tapaufest-2026' ), esc_html( $tf_o['name'] ), (int) $tf_o['cap'], esc_html( $tf_alt['name'] ) );
						} else {
							/* translators: 1: pass, 2: other pass. */
							$tf_msg = sprintf( __( '<b>%1$s is gone.</b> %2$s is still open.', 'tapaufest-2026' ), esc_html( $tf_o['name'] ), esc_html( $tf_alt['name'] ) );
						}
						echo tf2_alert( 'gold', 'info', $tf_msg ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						?>
					<?php endforeach; ?>
					<div class="tf2-tiers">
						<?php
						foreach ( array_merge( $tf_open, $tf_out ) as $tf_t ) {
							echo tf2_tier( $tf_t, 'row' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						}
						?>
					</div>
					<p class="tf2-note"><?php esc_html_e( 'Camping at Santuy Campsite is booked separately. Checkout and your e-ticket happen on TAPAUtix.', 'tapaufest-2026' ); ?></p>
				</div>
				<aside class="tf2-stack tf2-sticky">
					<div class="tf2-card tf2-pad tf2-order">
						<p class="tf2-eb"><?php esc_html_e( 'Your order', 'tapaufest-2026' ); ?></p>
						<p style="font:800 1.4rem var(--tf2-text)" data-tf2-sel><?php echo esc_html( $tf_open[0]['name'] ); ?></p>
						<div class="tf2-between" style="align-items:center">
							<span style="font:700 1rem var(--tf2-text)"><?php esc_html_e( 'How many', 'tapaufest-2026' ); ?></span>
							<span class="tf2-qty">
								<button type="button" data-tf2-step="-1" aria-label="<?php esc_attr_e( 'One fewer', 'tapaufest-2026' ); ?>">−</button>
								<output data-tf2-qty aria-live="polite">1</output>
								<button type="button" data-tf2-step="1" aria-label="<?php esc_attr_e( 'One more', 'tapaufest-2026' ); ?>">+</button>
							</span>
						</div>
						<div class="tf2-total"><span><?php esc_html_e( 'Total', 'tapaufest-2026' ); ?></span><b data-tf2-total><?php echo esc_html( tapaufest_rm( tapaufest_tier_price( $tf_open[0] ) ) ); ?></b></div>
						<a class="tf2-btn tf2-btn--cili" data-tf2-go data-base="<?php echo esc_url( home_url( '/checkout/' ) ); ?>" href="<?php echo esc_url( tapaufest_checkout_url( $tf_open[0] ) ); ?>"><span><?php esc_html_e( 'Continue to TAPAUtix', 'tapaufest-2026' ); ?></span></a>
						<p class="tf2-note"><?php echo esc_html( 'early' === $tf_state ? __( 'You enter your waitlist code and pay on TAPAUtix.', 'tapaufest-2026' ) : __( 'You pay on TAPAUtix. Your e-ticket arrives by email.', 'tapaufest-2026' ) ); ?></p>
					</div>
					<?php if ( $tf_out ) : ?>
						<div class="tf2-card tf2-pad tf2-stack tf2-stack--sm">
							<?php /* translators: %s: pass name. */ ?>
							<p style="font:800 1.05rem var(--tf2-text)"><?php echo esc_html( sprintf( __( 'Want %s if some come back?', 'tapaufest-2026' ), $tf_out[0]['name'] ) ); ?></p>
							<?php echo tf2_ping_form( $tf_out[0]['id'], $tf_perr ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</div>
					<?php endif; ?>
				</aside>
			</div>
		<?php endif; ?>

		<section style="margin-top:72px">
			<?php echo tf2_head( __( 'How it works', 'tapaufest-2026' ), __( 'From checkout to the gate', 'tapaufest-2026' ), '', 'h2' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<div class="tf2-auto" style="grid-template-columns:repeat(auto-fit,minmax(260px,1fr))">
				<?php foreach ( array( array( __( 'Buy on TAPAUtix', 'tapaufest-2026' ), __( 'Your e-ticket arrives by email.', 'tapaufest-2026' ) ), array( __( 'Get your wristband', 'tapaufest-2026' ), __( 'Show the e-ticket at the redemption counter at the gate.', 'tapaufest-2026' ) ), array( __( 'Keep it on', 'tapaufest-2026' ), __( 'Wear it all weekend. Lost wristbands can’t be replaced.', 'tapaufest-2026' ) ) ) as $tf_i => $tf_step ) : ?>
					<div class="tf2-step"><b><?php echo (int) $tf_i + 1; ?></b><span><strong><?php echo esc_html( $tf_step[0] ); ?></strong><span><?php echo esc_html( $tf_step[1] ); ?></span></span></div>
				<?php endforeach; ?>
			</div>
			<p class="tf2-note" style="margin-top:16px"><?php esc_html_e( 'All sales are final unless the festival is cancelled or postponed.', 'tapaufest-2026' ); ?></p>
		</section>
	<?php endif; ?>
</div>
