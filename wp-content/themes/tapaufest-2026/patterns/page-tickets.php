<?php
/**
 * Title: Page: Tickets
 * Slug: tapaufest-2026/page-tickets
 * Categories: tapaufest-pages
 * Post Types: page
 * Description: Pass cards, comparison table and how tickets work.
 *
 * @package Tapaufest2026
 */

echo tapaufest_page_head( __( 'Tickets', 'tapaufest-2026' ), __( 'Pick your pass', 'tapaufest-2026' ), __( 'Four passes for one weekend by the river. Pay on TAPAUtix and your e-ticket arrives by email.', 'tapaufest-2026' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo tapaufest_band_open( 'kopi' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo tapaufest_sr_heading( __( 'Passes', 'tapaufest-2026' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
?>
<!-- wp:tapaufest/tickets {"compare":true} /-->
<?php
echo tapaufest_band_close(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo tapaufest_band_open( 'paper' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo tapaufest_heading( __( 'How it works', 'tapaufest-2026' ), __( 'From checkout to the gate', 'tapaufest-2026' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo tapaufest_grid( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	array(
		tapaufest_card( __( '1. Buy on TAPAUtix', 'tapaufest-2026' ), array( __( 'Pay on TAPAUtix. Your e-ticket arrives by email.', 'tapaufest-2026' ) ) ),
		tapaufest_card( __( '2. Get your wristband', 'tapaufest-2026' ), array( __( 'Show the e-ticket at the redemption counter at the gate.', 'tapaufest-2026' ) ) ),
		tapaufest_card( __( '3. Keep it on', 'tapaufest-2026' ), array( __( 'Wear the wristband all weekend. Lost wristbands can’t be replaced.', 'tapaufest-2026' ) ) ),
	)
);
?>
<!-- wp:paragraph -->
<p><?php esc_html_e( 'All sales are final unless the festival is cancelled or postponed.', 'tapaufest-2026' ); ?> <a href="<?php echo esc_url( tapaufest_theme_setting( 'tickets_url', 'https://tapautix.asia/' ) ); ?>"><?php esc_html_e( 'Full terms on TAPAUtix ↗', 'tapaufest-2026' ); ?></a></p>
<!-- /wp:paragraph -->
<?php
echo tapaufest_band_close(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
