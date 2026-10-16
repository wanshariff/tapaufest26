<?php
/**
 * Title: Home: partners strip
 * Slug: tapaufest-2026/home-partners
 * Categories: tapaufest
 * Description: Partner names or logos in one strip, with a link to the Partners page.
 *
 * @package Tapaufest2026
 */

echo tapaufest_band_open( 'paper', 'tf-home-partners' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo tapaufest_heading( __( 'With thanks to', 'tapaufest-2026' ), __( 'The people behind the scene', 'tapaufest-2026' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
?>
<!-- wp:tapaufest/partners {"compact":true} /-->
<?php
echo tapaufest_more_link( __( 'All partners, and how to work with us →', 'tapaufest-2026' ), '/partners/' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo tapaufest_band_close(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
