<?php
/**
 * Mobile bottom bar with the main button.
 *
 * Left off pages that already end in their own main action (tickets,
 * checkout, the sell form, Jadual Aku) so two bars never stack.
 *
 * @package TapaufestCore
 */

defined( 'ABSPATH' ) || exit;

$tf_state = tapaufest_state();
if ( 'recap' === $tf_state || is_404() || is_page( array( 'tickets', 'checkout', 'youre-in', 'sell', 'jadual-aku', 'now', 'help' ) ) ) {
	return;
}
$tf_cta  = tapaufest_cta();
$tf_note = array(
	'early'     => __( 'Early bird for the waitlist', 'tapaufest' ),
	'public'    => tapaufest_setting( 'venue' ),
	'door'      => __( 'Tickets at the gate', 'tapaufest' ),
	'live'      => __( 'Live now', 'tapaufest' ),
	'postponed' => __( 'New dates coming', 'tapaufest' ),
);
$tf_text = isset( $tf_note[ $tf_state ] ) ? $tf_note[ $tf_state ] : '';
?>
<div <?php echo get_block_wrapper_attributes( array( 'class' => 'tf-dock' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> data-tf-dock>
	<?php if ( $tf_text ) : ?>
		<p class="tf-dock__note"><?php echo esc_html( $tf_text ); ?></p>
	<?php endif; ?>
	<a class="tf-btn tf-btn--cili" href="<?php echo esc_url( $tf_cta['url'] ); ?>"><?php echo esc_html( $tf_cta['label'] ); ?></a>
</div>
