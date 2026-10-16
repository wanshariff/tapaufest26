<?php
/**
 * Title: Page: Lineup
 * Slug: tapaufest-2026/page-lineup
 * Categories: tapaufest-pages
 * Post Types: page
 * Description: Page head and the full lineup with filters and poster view.
 *
 * @package Tapaufest2026
 */

echo tapaufest_page_head( __( 'Lineup', 'tapaufest-2026' ), __( 'Who’s playing', 'tapaufest-2026' ), __( 'Tap an act for set times and to add them to your Jadual Aku. ▶ plays a track without leaving the page.', 'tapaufest-2026' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo tapaufest_band_open( 'paper' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
?>
<!-- wp:tapaufest/lineup {"align":"wide"} /-->
<?php
echo tapaufest_band_close(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
