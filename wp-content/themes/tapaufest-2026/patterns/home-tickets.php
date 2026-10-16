<?php
/**
 * Title: Home: ticket tiers
 * Slug: tapaufest-2026/home-tickets
 * Categories: tapaufest
 * Description: Pass cards that follow the site state, with a link to compare passes.
 *
 * @package Tapaufest2026
 */

echo tapaufest_band_open( 'kopi' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo tapaufest_heading( __( 'Tickets', 'tapaufest-2026' ), __( 'Pick your pass', 'tapaufest-2026' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
?>
<!-- wp:tapaufest/tickets /-->
<?php
echo tapaufest_more_link( __( 'Compare all passes →', 'tapaufest-2026' ), '/tickets/' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo tapaufest_band_close(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
