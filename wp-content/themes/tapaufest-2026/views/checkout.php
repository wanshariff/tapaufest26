<?php
/**
 * Screen: checkout hand-off. Says where you're going, then sends you to
 * TAPAUtix. With ?status=failed (TAPAUtix return link) it explains what
 * happened and how to try again.
 *
 * @package Tapaufest2026
 */

defined( 'ABSPATH' ) || exit;

// phpcs:disable WordPress.Security.NonceVerification.Recommended -- read-only display of a public link.
$tf_tier_id = isset( $_GET['tier'] ) ? absint( $_GET['tier'] ) : 0;
$tf_qty     = isset( $_GET['qty'] ) ? max( 1, min( 6, absint( $_GET['qty'] ) ) ) : 1;
$tf_failed  = isset( $_GET['status'] ) && 'failed' === sanitize_key( wp_unslash( $_GET['status'] ) );
// phpcs:enable
$tf_tier = null;
foreach ( tapaufest_get_tiers() as $tf_t ) {
	if ( (int) $tf_t['id'] === $tf_tier_id ) {
		$tf_tier = $tf_t;
	}
}
$tf_state  = tapaufest_state();
$tf_closed = ! in_array( $tf_state, array( 'early', 'public' ), true );
$tf_sum    = '';
if ( $tf_tier ) {
	$tf_price = tapaufest_tier_price( $tf_tier );
	$tf_sum   = '<div class="tf2-card tf2-pad tf2-stack tf2-stack--sm"><p class="tf2-eb">' . esc_html__( 'Your order', 'tapaufest-2026' ) . '</p>'
		. '<p style="font:800 1.3rem var(--tf2-text)">' . esc_html( $tf_tier['name'] . ' × ' . $tf_qty ) . '</p>'
		. '<div class="tf2-total"><span>' . esc_html__( 'Total', 'tapaufest-2026' ) . '</span><b>' . esc_html( tapaufest_rm( $tf_price * $tf_qty ) ) . '</b></div></div>';
}
?>
<div class="tf2-wrap tf2-main" style="padding-block:72px">
	<?php if ( ! $tf_tier || $tf_closed ) : ?>
		<?php echo tf2_empty( 'ticket', $tf_closed ? __( 'Online sales are closed.', 'tapaufest-2026' ) : __( 'Pick a pass first.', 'tapaufest-2026' ), $tf_closed ? __( 'Check the tickets page for door tickets and what’s next.', 'tapaufest-2026' ) : __( 'That link is missing a pass. Start from the tickets page.', 'tapaufest-2026' ), array( tf2_btn( __( 'Go to tickets', 'tapaufest-2026' ), home_url( '/tickets/' ), 'cili' ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

	<?php elseif ( $tf_failed ) : ?>
		<div class="tf2-two tf2-two--center">
			<div class="tf2-stack">
				<span style="color:var(--tf2-gold)"><?php echo tf2_icon( 'close' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
				<h1 class="tf2-h1"><?php esc_html_e( 'Checkout didn’t finish.', 'tapaufest-2026' ); ?></h1>
				<p class="tf2-lead"><?php esc_html_e( 'Usually that means no payment went through. Your pick is right here, try again when you’re ready.', 'tapaufest-2026' ); ?></p>
				<div class="tf2-row">
					<?php echo tf2_btn( __( 'Try again', 'tapaufest-2026' ), tapaufest_checkout_url( $tf_tier, $tf_qty ), 'cili', array( 'icon' => 'retry' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<?php echo tf2_btn( __( 'Change pass', 'tapaufest-2026' ), add_query_arg( 'tier', $tf_tier['id'], home_url( '/tickets/' ) ), 'ghost' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
				<p class="tf2-note"><?php esc_html_e( 'Charged anyway? Email TAPAUtix with your bank reference and they’ll sort it.', 'tapaufest-2026' ); ?></p>
			</div>
			<?php echo $tf_sum; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</div>

	<?php elseif ( $tf_tier['soldout'] ) : ?>
		<?php /* translators: %s: pass name. */ ?>
		<?php echo tf2_empty( 'ticket', sprintf( __( '%s just sold out.', 'tapaufest-2026' ), $tf_tier['name'] ), __( 'Sorry, geng. Pick another pass or ask us to ping you if some come back.', 'tapaufest-2026' ), array( tf2_btn( __( 'See other passes', 'tapaufest-2026' ), home_url( '/tickets/' ), 'cili' ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

	<?php else : ?>
		<?php $tf_go = tapaufest_tier_buy_url( $tf_tier ); ?>
		<div class="tf2-two tf2-two--center" data-tf2-handoff="<?php echo esc_url( $tf_go ); ?>">
			<div class="tf2-stack">
				<span class="tf2-spin" aria-hidden="true"></span>
				<h1 class="tf2-h1" role="status" aria-live="polite"><?php esc_html_e( 'Taking you to TAPAUtix…', 'tapaufest-2026' ); ?></h1>
				<p class="tf2-lead">
					<?php
					/* translators: %d: number of passes. */
					echo esc_html( sprintf( _n( 'Pick %d pass there, pay, and your e-ticket comes by email.', 'Pick %d passes there, pay, and your e-tickets come by email.', $tf_qty, 'tapaufest-2026' ), $tf_qty ) );
					?>
				</p>
				<?php if ( 'early' === $tf_state ) : ?>
					<?php echo tf2_alert( 'gold', 'ticket', esc_html__( 'Have your waitlist code ready. You enter it at TAPAUtix checkout.', 'tapaufest-2026' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<?php endif; ?>
				<p><?php esc_html_e( 'Didn’t open?', 'tapaufest-2026' ); ?> <a href="<?php echo esc_url( $tf_go ); ?>"><?php esc_html_e( 'Continue to TAPAUtix', 'tapaufest-2026' ); ?></a></p>
			</div>
			<div class="tf2-stack">
				<?php echo $tf_sum; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<?php echo tf2_alert( 'blue', 'lock', esc_html__( 'Secure checkout by TAPAUtix. We never see your card.', 'tapaufest-2026' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
		</div>
	<?php endif; ?>
</div>
