<?php
/**
 * Screen: you're in. Where TAPAUtix sends buyers back after paying.
 *
 * @package Tapaufest2026
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="tf2-wrap tf2-main" style="padding-block:72px">
	<div class="tf2-two tf2-two--center">
		<div class="tf2-stack">
			<svg class="tf2-done-mark" viewBox="0 0 96 96" width="110" height="110" fill="none" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="48" cy="48" r="41" fill="#F2A93B" opacity=".25"></circle><circle class="circ" cx="48" cy="48" r="41" stroke="#F2A93B" stroke-width="5"></circle><path class="draw" d="M30 49l12 12 25-26" stroke="#F8DAA5" stroke-width="7"></path></svg>
			<h1 class="tf2-h1 tf2-h1--xl tf2-up"><?php esc_html_e( 'You’re in, geng.', 'tapaufest-2026' ); ?></h1>
			<p class="tf2-lead tf2-up"><?php esc_html_e( 'Your e-ticket is in your email from TAPAUtix. Show the QR at the gate to get your wristband.', 'tapaufest-2026' ); ?></p>
			<div class="tf2-row">
				<?php echo tf2_btn( __( 'Add to calendar', 'tapaufest-2026' ), add_query_arg( 'fest', '1', rest_url( 'tapaufest/v1/ics' ) ), 'cili', array( 'icon' => 'calendar' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<?php echo tf2_share_button( __( 'I’m going to TAPAUfest', 'tapaufest-2026' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
			<p class="tf2-note"><?php esc_html_e( 'No email in 10 minutes? Check spam, then your order on TAPAUtix.', 'tapaufest-2026' ); ?></p>
		</div>
		<div class="tf2-card tf2-pad--lg tf2-stack tf2-stack--sm tf2-up">
			<p class="tf2-eb"><?php esc_html_e( 'Next up', 'tapaufest-2026' ); ?></p>
			<div class="tf2-steps">
				<?php
				$tf_next = array(
					array( __( 'Star your acts', 'tapaufest-2026' ), __( 'Build Jadual Aku', 'tapaufest-2026' ), '/lineup/' ),
					array( __( 'Plan the route', 'tapaufest-2026' ), __( 'MRT and shuttle, or drive', 'tapaufest-2026' ), '/plan/' ),
					array( __( 'Pack light', 'tapaufest-2026' ), __( 'Bottle, poncho, some cash', 'tapaufest-2026' ), '/plan/#pack' ),
				);
				foreach ( $tf_next as $tf_i => $tf_n ) :
					?>
					<a class="tf2-step tf2-lift" href="<?php echo esc_url( home_url( $tf_n[2] ) ); ?>"><b><?php echo (int) $tf_i + 1; ?></b><span><strong><?php echo esc_html( $tf_n[0] ); ?></strong><span><?php echo esc_html( $tf_n[1] ); ?></span></span></a>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</div>
