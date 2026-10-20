<?php
/**
 * Festival screen: one whole page, rendered from a view file.
 *
 * Views live in the theme (views/{screen}.php) so the look stays with the
 * theme; the plugin keeps the data. If the theme has no view for a screen,
 * nothing is printed.
 *
 * @package TapaufestCore
 */

defined( 'ABSPATH' ) || exit;

$tf_screen = isset( $attributes['screen'] ) ? sanitize_key( $attributes['screen'] ) : 'home';
$tf_file   = locate_template( 'views/' . $tf_screen . '.php' );
if ( ! $tf_file ) {
	return;
}
?>
<div <?php echo get_block_wrapper_attributes( array( 'class' => 'tf2 tf2-screen tf2-screen--' . $tf_screen . ' alignfull' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<?php include $tf_file; ?>
</div>
