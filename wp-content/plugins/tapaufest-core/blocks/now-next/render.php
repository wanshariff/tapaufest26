<?php
/**
 * Now and next per stage.
 *
 * @package TapaufestCore
 */

defined( 'ABSPATH' ) || exit;
?>
<div <?php echo get_block_wrapper_attributes( array( 'class' => 'tf-nownext-block' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<?php echo tapaufest_now_next_html(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped inside. ?>
</div>
