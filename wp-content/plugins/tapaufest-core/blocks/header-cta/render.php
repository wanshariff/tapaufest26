<?php
/**
 * Header button that follows the site state.
 *
 * @package TapaufestCore
 */

defined( 'ABSPATH' ) || exit;

$tf_cta = tapaufest_cta();
?>
<div <?php echo get_block_wrapper_attributes( array( 'class' => 'tf-header-cta' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<a class="tf-btn tf-btn--cili" href="<?php echo esc_url( $tf_cta['url'] ); ?>"><?php echo esc_html( $tf_cta['label'] ); ?></a>
</div>
