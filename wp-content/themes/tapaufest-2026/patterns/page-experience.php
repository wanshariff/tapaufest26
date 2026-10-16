<?php
/**
 * Title: Page: Experience
 * Slug: tapaufest-2026/page-experience
 * Categories: tapaufest-pages
 * Post Types: page
 * Description: Zones, food and market vendors, workshops.
 *
 * @package Tapaufest2026
 */

echo tapaufest_page_head( __( 'Experience', 'tapaufest-2026' ), __( 'Lepak all weekend', 'tapaufest-2026' ), __( 'Music is the start. Here’s everything else running across the two days.', 'tapaufest-2026' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo tapaufest_band_open( 'paper' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo tapaufest_heading( __( 'Zones', 'tapaufest-2026' ), __( 'Eight zones, one weekend', 'tapaufest-2026' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo tapaufest_zone_cards(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo tapaufest_heading( __( 'Find your way', 'tapaufest-2026' ), __( 'Explore the site', 'tapaufest-2026' ), __( 'Hover or tap a zone to see what’s on and who’s selling.', 'tapaufest-2026' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
?>
<!-- wp:tapaufest/site-map {"align":"wide"} /-->
<?php
echo tapaufest_band_close(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo tapaufest_band_open( 'kopi' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
?>
<!-- wp:columns -->
<div class="wp-block-columns">
<!-- wp:column -->
<div class="wp-block-column">
<?php echo tapaufest_heading( 'WARUNG', __( 'Makan', 'tapaufest-2026' ), __( 'Local kitchens all day and late into the night, with free water refills. Sample stalls below are based on past editions; this year’s are announced in November.', 'tapaufest-2026' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
<!-- wp:tapaufest/vendors {"type":"warung"} /-->
</div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column">
<?php echo tapaufest_heading( 'PASAR', __( 'Shop', 'tapaufest-2026' ), __( 'Merch, vinyl, zines and crafts from the scene. 2025 partners plus sample stalls.', 'tapaufest-2026' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
<!-- wp:tapaufest/vendors {"type":"pasar"} /-->
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->

<!-- wp:buttons -->
<div class="wp-block-buttons">
<!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/sell/' ) ); ?>"><?php esc_html_e( 'Want a stall? Apply here', 'tapaufest-2026' ); ?></a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
<?php
echo tapaufest_band_close(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo tapaufest_band_open( 'paper' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo tapaufest_heading( __( 'BENGKEL and KEMBARA', 'tapaufest-2026' ), __( 'Workshops and outdoor slots', 'tapaufest-2026' ), __( 'Slots open at the BENGKEL tent from 10am each day, first come first served. Times are on the timetable.', 'tapaufest-2026' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo tapaufest_more_link( __( 'See workshop times →', 'tapaufest-2026' ), '/timetable/' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo tapaufest_band_close(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
