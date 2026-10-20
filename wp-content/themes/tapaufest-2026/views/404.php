<?php
/**
 * Screen: page not found.
 *
 * @package Tapaufest2026
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="tf2-wrap tf2-main" style="padding-block:96px">
	<div class="tf2-two tf2-two--center">
		<div class="tf2-stack">
			<p class="tf2-eb"><?php esc_html_e( 'Error 404', 'tapaufest-2026' ); ?></p>
			<h1 class="tf2-h1 tf2-h1--xl"><?php esc_html_e( 'Sesat, eh?', 'tapaufest-2026' ); ?></h1>
			<p class="tf2-lead"><?php esc_html_e( 'This page wandered off into the forest.', 'tapaufest-2026' ); ?></p>
			<div class="tf2-row">
				<?php echo tf2_btn( __( 'Back to home', 'tapaufest-2026' ), home_url( '/' ), 'cili' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<?php echo tf2_btn( __( 'Lineup', 'tapaufest-2026' ), home_url( '/lineup/' ), 'paper' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<?php echo tf2_btn( __( 'Open the map', 'tapaufest-2026' ), home_url( '/map/' ), 'ghost' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
		</div>
		<?php echo tf2_photo( 'venue-forest', '', 'tf2-photo--shadow' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</div>
</div>
