<?php
/**
 * Title: Home: experience preview
 * Slug: tapaufest-2026/home-experience
 * Categories: tapaufest
 * Description: Four zone cards with a link to the Experience page.
 *
 * @package Tapaufest2026
 */

echo tapaufest_band_open( 'paper' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo tapaufest_heading( __( 'More than a gig', 'tapaufest-2026' ), __( 'Lepak all weekend', 'tapaufest-2026' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo tapaufest_zone_cards( 4 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo tapaufest_more_link( __( 'Everything on site →', 'tapaufest-2026' ), '/experience/' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo tapaufest_band_close(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
