<?php
/**
 * Title: Home: last year
 * Slug: tapaufest-2026/home-last-year
 * Categories: tapaufest
 * Description: The 2025 aftermovie with headline numbers.
 *
 * @package Tapaufest2026
 */

echo tapaufest_band_open( 'kopi' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo tapaufest_heading( __( 'Last year', 'tapaufest-2026' ), __( 'What it feels like', 'tapaufest-2026' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo tapaufest_film( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	__( 'Watch: TAPAUfest 2025 ASEAN, The Aftermovie ↗', 'tapaufest-2026' ),
	tapaufest_theme_setting( 'aftermovie_url', 'https://tapau.asia/vod/tapaufest-2025-asean-the-aftermovie/' ),
	array(
		'2,000+'        => __( 'festival-goers in 2025', 'tapaufest-2026' ),
		'12 acts'       => __( 'from 4 ASEAN countries', 'tapaufest-2026' ),
		'Best Festival' => __( 'voted on Juice Best Of ’25', 'tapaufest-2026' ),
	)
);
echo tapaufest_more_link( __( 'Past editions →', 'tapaufest-2026' ), '/archive/' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo tapaufest_band_close(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
