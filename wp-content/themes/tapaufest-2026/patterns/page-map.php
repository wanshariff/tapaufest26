<?php
/**
 * Title: Page: Map and help
 * Slug: tapaufest-2026/page-map
 * Categories: tapaufest-pages
 * Post Types: page
 * Description: Site map, on-now cards and where to get help.
 *
 * @package Tapaufest2026
 */

$tf_wa = tapaufest_theme_setting( 'whatsapp_url' );
echo tapaufest_page_head( __( 'Map and help', 'tapaufest-2026' ), __( 'Site map and help', 'tapaufest-2026' ), __( 'Screenshot the map before you arrive. Signal on site can be weak.', 'tapaufest-2026' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo tapaufest_band_open( 'paper' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo tapaufest_sr_heading( __( 'Site map', 'tapaufest-2026' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
?>
<!-- wp:columns -->
<div class="wp-block-columns">
<!-- wp:column {"width":"58%"} -->
<div class="wp-block-column" style="flex-basis:58%">
<!-- wp:image {"sizeSlug":"full","className":"tf-sitemap"} -->
<figure class="wp-block-image size-full tf-sitemap"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/site-map-draft.svg' ) ); ?>" alt="<?php esc_attr_e( 'Draft site map: river along the top, PENTAS in the centre, WARUNG and PASAR to the left, LAYAR and BENGKEL to the right, campsite at the far right, gate at the bottom. S is the surau, T toilets, + first aid, W water refill, i info and lost and found.', 'tapaufest-2026' ); ?>"/><figcaption class="wp-element-caption"><?php esc_html_e( 'S surau · T toilets · + first aid · W water refill · i info, lost and found. Draft layout: the final map comes from production.', 'tapaufest-2026' ); ?></figcaption></figure>
<!-- /wp:image -->

<!-- wp:paragraph {"className":"tf-more"} -->
<p class="tf-more"><a href="<?php echo esc_url( get_theme_file_uri( 'assets/images/site-map-draft.svg' ) ); ?>" download="tapaufest-2026-site-map.svg"><?php esc_html_e( 'Save the map to your phone ↓', 'tapaufest-2026' ); ?></a></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:column -->

<!-- wp:column {"width":"42%"} -->
<div class="wp-block-column" style="flex-basis:42%">
<?php
echo tapaufest_sr_heading( __( 'Help on site', 'tapaufest-2026' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo tapaufest_card( __( 'Need help on site?', 'tapaufest-2026' ), array( esc_html__( 'Find the info booth next to the gate, or any crew member in a gold shirt.', 'tapaufest-2026' ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo tapaufest_card( __( 'First aid', 'tapaufest-2026' ), array( esc_html__( 'Next to the gate, open until the last set ends.', 'tapaufest-2026' ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo tapaufest_card( __( 'Lost and found', 'tapaufest-2026' ), array( esc_html__( 'At the info booth. After the fest, message us on Instagram @tapaufest.', 'tapaufest-2026' ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo tapaufest_card( __( 'Emergency', 'tapaufest-2026' ), array( esc_html__( 'Call 999.', 'tapaufest-2026' ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo tapaufest_card( __( 'Live updates', 'tapaufest-2026' ), array( esc_html__( 'Set changes and weather alerts show in the bar at the top of every page first.', 'tapaufest-2026' ) . ( $tf_wa ? ' <a href="' . esc_url( $tf_wa ) . '">' . esc_html__( 'Follow the WhatsApp channel ↗', 'tapaufest-2026' ) . '</a>' : '' ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
?>
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->
<?php
echo tapaufest_band_close(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo tapaufest_band_open( 'kopi' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo tapaufest_heading( __( 'Right now', 'tapaufest-2026' ), __( 'On now and next', 'tapaufest-2026' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
?>
<!-- wp:tapaufest/now-next /-->
<?php
echo tapaufest_band_close(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
