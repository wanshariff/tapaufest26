<?php
/**
 * Title: Page: Timetable
 * Slug: tapaufest-2026/page-timetable
 * Categories: tapaufest-pages
 * Post Types: page
 * Description: Set times per stage with Jadual Aku.
 *
 * @package Tapaufest2026
 */

echo tapaufest_page_head( __( 'Timetable', 'tapaufest-2026' ), __( 'Set times', 'tapaufest-2026' ), __( 'One list per stage. Star the sets you want and Jadual Aku warns you about clashes, adds them to your calendar and shares them with your geng.', 'tapaufest-2026' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo tapaufest_band_open( 'kopi' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo tapaufest_sr_heading( __( 'Set times by day and stage', 'tapaufest-2026' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
?>
<!-- wp:tapaufest/timetable {"align":"wide"} /-->
<?php
echo tapaufest_band_close(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
