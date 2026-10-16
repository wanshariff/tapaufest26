<?php
/**
 * Alert bar.
 *
 * @package TapaufestCore
 */

defined( 'ABSPATH' ) || exit;

if ( ! tapaufest_setting( 'alert_on' ) ) {
	return;
}
$tf_alert = tapaufest_alert();
?>
<div <?php echo get_block_wrapper_attributes( array( 'class' => 'tf-alert tf-alert--' . tapaufest_state() ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<div class="tf-alert__inner">
		<span class="tf-alert__tag"><?php echo esc_html( $tf_alert['tag'] ); ?></span>
		<p class="tf-alert__text"><?php echo esc_html( $tf_alert['text'] ); ?></p>
	</div>
</div>
