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
echo tapaufest_page_head( __( 'Map and help', 'tapaufest-2026' ), __( 'Site map and help', 'tapaufest-2026' ), __( 'Hover or tap any pin to see what’s there, what’s on next and who’s selling. Screenshot it before you arrive: signal on site can be weak.', 'tapaufest-2026' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo tapaufest_band_open( 'paper' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo tapaufest_sr_heading( __( 'Site map', 'tapaufest-2026' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
?>
<!-- wp:tapaufest/site-map {"align":"wide"} /-->


<?php
echo tapaufest_heading( __( 'Help on site', 'tapaufest-2026' ), __( 'Need a hand?', 'tapaufest-2026' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo tapaufest_grid( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	array(
		tapaufest_card( __( 'Need help on site?', 'tapaufest-2026' ), array( esc_html__( 'Find the info booth next to the gate, or any crew member in a gold shirt.', 'tapaufest-2026' ) ) ),
		tapaufest_card( __( 'First aid', 'tapaufest-2026' ), array( esc_html__( 'Next to the gate, open until the last set ends.', 'tapaufest-2026' ) ) ),
		tapaufest_card( __( 'Lost and found', 'tapaufest-2026' ), array( esc_html__( 'At the info booth. After the fest, message us on Instagram @tapaufest.', 'tapaufest-2026' ) ) ),
		tapaufest_card( __( 'Emergency', 'tapaufest-2026' ), array( esc_html__( 'Call 999.', 'tapaufest-2026' ) ) ),
		tapaufest_card( __( 'Live updates', 'tapaufest-2026' ), array( esc_html__( 'Set changes and weather alerts show in the bar at the top of every page first.', 'tapaufest-2026' ) . ( $tf_wa ? ' <a href="' . esc_url( $tf_wa ) . '">' . esc_html__( 'Follow the WhatsApp channel ↗', 'tapaufest-2026' ) . '</a>' : '' ) ) ),
	),
	'13rem'
);
?>
<?php
echo tapaufest_band_close(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo tapaufest_band_open( 'kopi' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo tapaufest_heading( __( 'Right now', 'tapaufest-2026' ), __( 'On now and next', 'tapaufest-2026' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
?>
<!-- wp:tapaufest/now-next /-->
<?php
echo tapaufest_band_close(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
