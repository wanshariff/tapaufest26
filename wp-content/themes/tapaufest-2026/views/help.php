<?php
/**
 * Screen: help on site.
 *
 * @package Tapaufest2026
 */

defined( 'ABSPATH' ) || exit;

$tf_wa   = tapaufest_setting( 'whatsapp_url' );
$tf_help = array(
	array( 'first-aid', '#A82E05', __( 'First aid', 'tapaufest-2026' ), __( 'Next to the gate', 'tapaufest-2026' ), __( 'Open till the last set ends. Walk in, or ask any gold shirt to radio them.', 'tapaufest-2026' ) ),
	array( 'lost-found', '#2E6FA8', __( 'Lost and found', 'tapaufest-2026' ), __( 'Info booth', 'tapaufest-2026' ), __( 'Lost something? Info booth by the gate. After the fest, message @tapaufest.', 'tapaufest-2026' ) ),
	array( 'water', '#3D8BD0', __( 'Water', 'tapaufest-2026' ), __( 'Free refills', 'tapaufest-2026' ), __( 'Bring a bottle. Refill free at the Water Station next to WARUNG.', 'tapaufest-2026' ) ),
	array( 'surau', '#4E7A2E', __( 'Surau', 'tapaufest-2026' ), __( 'Next to WARUNG', 'tapaufest-2026' ), __( 'PENTAS pauses for Maghrib and Isyak both days.', 'tapaufest-2026' ) ),
	array( 'info', '#6F310B', __( 'Info booth', 'tapaufest-2026' ), __( 'By the gate', 'tapaufest-2026' ), __( 'Wristbands, shuttle questions, the best signal on site.', 'tapaufest-2026' ) ),
	array( 'person', '#1E1208', __( 'Feel unsafe?', 'tapaufest-2026' ), __( 'Crew in gold shirts', 'tapaufest-2026' ), __( 'Tell any gold shirt or the info booth. We act fast and keep it quiet.', 'tapaufest-2026' ) ),
);
?>
<div class="tf2-wrap tf2-main">
	<?php echo tf2_head( __( 'Crew wear gold shirts', 'tapaufest-2026' ), __( 'Need a hand?', 'tapaufest-2026' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	<div class="tf2-auto tf2-auto--lg">
		<?php foreach ( $tf_help as $tf_i => $tf_h ) : ?>
			<div class="tf2-card tf2-help tf2-up" style="animation-delay:<?php echo (int) $tf_i * 50; ?>ms">
				<span class="tf2-help__ic" style="background:<?php echo esc_attr( $tf_h[1] ); ?>"><?php echo tf2_icon( $tf_h[0] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
				<strong><?php echo esc_html( $tf_h[2] ); ?></strong>
				<span class="tf2-eb tf2-eb--cili"><?php echo esc_html( $tf_h[3] ); ?></span>
				<span><?php echo esc_html( $tf_h[4] ); ?></span>
			</div>
		<?php endforeach; ?>
	</div>
	<div class="tf2-two" style="margin-top:36px">
		<a class="tf2-sos" href="tel:999"><?php echo tf2_icon( 'alert' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php esc_html_e( 'Emergency · call 999', 'tapaufest-2026' ); ?></a>
		<div class="tf2-stack tf2-stack--sm">
			<p><?php esc_html_e( 'Set changes and weather alerts show in the bar at the top of every page first.', 'tapaufest-2026' ); ?></p>
			<div class="tf2-row">
				<?php echo tf2_btn( __( 'Map', 'tapaufest-2026' ), home_url( '/map/' ), 'paper', array( 'icon' => 'pin' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<?php if ( $tf_wa ) : ?>
					<?php echo tf2_btn( __( 'WhatsApp updates', 'tapaufest-2026' ), $tf_wa, 'ghost', array( 'icon' => 'whatsapp', 'external' => true ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>
