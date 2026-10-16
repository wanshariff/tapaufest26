<?php
/**
 * Title: Home: lineup
 * Slug: tapaufest-2026/home-lineup
 * Categories: tapaufest
 * Description: The full lineup, headliners first. A swipe strip on phones so tickets stay near the top.
 *
 * @package Tapaufest2026
 */

echo tapaufest_band_open( 'paper', 'tf-home-lineup' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo tapaufest_heading( __( 'Lineup', 'tapaufest-2026' ), __( 'Who’s playing', 'tapaufest-2026' ), __( 'Tentative lineup, subject to change.', 'tapaufest-2026' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
?>
<!-- wp:tapaufest/lineup {"filters":false,"moreLink":true,"headlinersFirst":true,"strip":true} /-->
<?php
echo tapaufest_band_close(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
