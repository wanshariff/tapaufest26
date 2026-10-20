<?php
/**
 * Screen: festival map. Map on the left, the picked place on the right.
 *
 * @package Tapaufest2026
 */

defined( 'ABSPATH' ) || exit;

$tf_notices = tapaufest_notices();
?>
<div class="tf2-wrap tf2-main" data-tf2-map>
	<?php echo tf2_head( __( 'Screenshot it before you go. Signal on site can be weak.', 'tapaufest-2026' ), __( 'Map', 'tapaufest-2026' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	<?php foreach ( $tf_notices as $tf_zone => $tf_n ) : ?>
		<div style="margin-bottom:16px"><?php echo tf2_alert( 'off' === $tf_n['status'] ? 'cili' : 'gold', 'off' === $tf_n['status'] ? 'rain' : 'wave', '<b>' . esc_html( $tf_zone ) . ':</b> ' . esc_html( $tf_n['message'] ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
	<?php endforeach; ?>
	<div class="tf2-mapgrid">
		<div class="tf2-mapbox">
			<?php echo render_block( array( 'blockName' => 'tapaufest/site-map', 'attrs' => array(), 'innerBlocks' => array(), 'innerHTML' => '', 'innerContent' => array() ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</div>
		<aside class="tf2-card tf2-mappanel tf2-sticky" data-tf2-mappanel aria-live="polite">
			<?php echo tf2_photo( 'pentas-day', '' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<div class="tf2-pad tf2-stack tf2-stack--sm">
				<p class="tf2-eb"><?php esc_html_e( 'Tap a pin', 'tapaufest-2026' ); ?></p>
				<p class="tf2-h2" style="font-size:2rem"><?php esc_html_e( 'What’s where', 'tapaufest-2026' ); ?></p>
				<p><?php esc_html_e( 'Pick any pin to see what’s there, what’s on next and who’s selling.', 'tapaufest-2026' ); ?></p>
				<?php echo tf2_btn( __( 'Need a hand?', 'tapaufest-2026' ), home_url( '/help/' ), 'paper', array( 'icon' => 'info' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
		</aside>
	</div>
</div>
