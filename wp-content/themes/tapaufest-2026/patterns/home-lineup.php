<?php
/**
 * Title: Home: lineup preview
 * Slug: tapaufest-2026/home-lineup
 * Categories: tapaufest
 * Description: Six artist cards with a link to the full lineup.
 *
 * @package Tapaufest2026
 */

echo tapaufest_band_open( 'paper' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo tapaufest_heading( __( 'Lineup', 'tapaufest-2026' ), __( 'Who’s playing', 'tapaufest-2026' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
?>
<!-- wp:tapaufest/lineup {"limit":6,"filters":false,"moreLink":true} /-->
<?php
echo tapaufest_band_close(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
