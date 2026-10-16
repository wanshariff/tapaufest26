<?php
/**
 * Mobile bottom bar with the main button.
 *
 * @package TapaufestCore
 */

defined( 'ABSPATH' ) || exit;

$tf_state = tapaufest_state();
if ( 'recap' === $tf_state ) {
	return;
}
$tf_cta  = tapaufest_cta();
$tf_note = array(
	'early'  => __( 'Early bird for the waitlist', 'tapaufest' ),
	'public' => tapaufest_setting( 'venue' ),
	'live'   => __( 'Live now', 'tapaufest' ),
);
?>
<div <?php echo get_block_wrapper_attributes( array( 'class' => 'tf-dock' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> data-tf-dock>
	<p class="tf-dock__note"><?php echo esc_html( $tf_note[ $tf_state ] ); ?></p>
	<a class="tf-btn tf-btn--cili" href="<?php echo esc_url( $tf_cta['url'] ); ?>"><?php echo esc_html( $tf_cta['label'] ); ?></a>
</div>
