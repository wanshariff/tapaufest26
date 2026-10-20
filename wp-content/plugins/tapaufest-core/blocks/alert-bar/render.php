<?php
/**
 * State bar: one line under the header saying where the festival is at,
 * with the one thing to do next.
 *
 * @package TapaufestCore
 */

defined( 'ABSPATH' ) || exit;

if ( ! tapaufest_setting( 'alert_on' ) ) {
	return;
}
$tf_state = tapaufest_state();
$tf_alert = tapaufest_alert();
$tf_here  = isset( $_SERVER['REQUEST_URI'] ) ? wp_parse_url( esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ), PHP_URL_PATH ) : '';
$tf_go    = wp_parse_url( $tf_alert['url'], PHP_URL_PATH );
?>
<div <?php echo get_block_wrapper_attributes( array( 'class' => 'tf-alert tf2-bar tf2-bar--' . $tf_state . ' tf-alert--' . $tf_state ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> role="status">
	<div class="tf-alert__inner">
		<?php if ( 'live' === $tf_state ) : ?>
			<span class="tf2-bar__dot" aria-hidden="true"></span>
		<?php endif; ?>
		<span class="tf-alert__tag"><?php echo esc_html( $tf_alert['tag'] ); ?></span>
		<p class="tf-alert__text"><?php echo esc_html( $tf_alert['text'] ); ?></p>
		<?php if ( $tf_alert['cta'] && $tf_go !== $tf_here ) : ?>
			<a class="tf2-bar__cta" href="<?php echo esc_url( $tf_alert['url'] ); ?>"><?php echo esc_html( $tf_alert['cta'] ); ?></a>
		<?php endif; ?>
	</div>
</div>
