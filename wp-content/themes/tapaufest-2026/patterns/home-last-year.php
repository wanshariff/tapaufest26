<?php
/**
 * Title: Home: last year
 * Slug: tapaufest-2026/home-last-year
 * Categories: tapaufest
 * Description: The 2025 aftermovie, headline numbers and a photo strip.
 *
 * @package Tapaufest2026
 */

echo tapaufest_band_open( 'kopi' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo tapaufest_heading( __( 'Last year', 'tapaufest-2026' ), __( 'What it feels like', 'tapaufest-2026' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo tapaufest_film( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	__( 'Watch: TAPAUfest 2025 ASEAN, The Aftermovie ↗', 'tapaufest-2026' ),
	tapaufest_theme_setting( 'aftermovie_url', 'https://player.castr.com/vod/0OnEi4OQE72IphJS' ),
	array(
		'2,000+'        => __( 'festival-goers in 2025', 'tapaufest-2026' ),
		'12 acts'       => __( 'from 4 ASEAN countries', 'tapaufest-2026' ),
		'Best Festival' => __( 'voted on Juice Best Of ’25', 'tapaufest-2026' ),
	)
);
echo tapaufest_photo_strip( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	array(
		'crowd'        => __( 'Day 2 on the lawn', 'tapaufest-2026' ),
		'pentas-night' => __( 'PENTAS after dark', 'tapaufest-2026' ),
		'pasar'        => __( 'PASAR', 'tapaufest-2026' ),
		'campsite'     => __( 'Santuy Campsite', 'tapaufest-2026' ),
		'kembara-yoga' => __( 'Morning yoga, KEMBARA', 'tapaufest-2026' ),
	)
);
echo tapaufest_more_link( __( 'Past editions →', 'tapaufest-2026' ), '/archive/' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo tapaufest_band_close(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
