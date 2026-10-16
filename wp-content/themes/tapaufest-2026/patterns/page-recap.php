<?php
/**
 * Title: Page: Recap
 * Slug: tapaufest-2026/page-recap
 * Categories: tapaufest-pages
 * Post Types: page
 * Description: Aftermovie, photo galleries, feedback and the next-edition waitlist.
 *
 * @package Tapaufest2026
 */

if ( ! tapaufest_is_recap() ) :
	// Before the fest: a look back at 2025 and when the 2026 recap lands.
	echo tapaufest_page_head( __( 'Recap', 'tapaufest-2026' ), __( 'The 2026 recap lands 14 Dec', 'tapaufest-2026' ), __( 'Photos go up within 48 hours of the last set, then highlights, set recordings and the aftermovie. Until then, here’s 2025.', 'tapaufest-2026' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	echo tapaufest_band_open( 'kopi' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	echo tapaufest_heading( __( 'TAPAUfest 2025 ASEAN', 'tapaufest-2026' ), __( 'Lenggong, August 2025', 'tapaufest-2026' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	echo tapaufest_film( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		__( 'Watch: TAPAUfest 2025 ASEAN, The Aftermovie ↗', 'tapaufest-2026' ),
		tapaufest_theme_setting( 'aftermovie_url', 'https://player.castr.com/vod/0OnEi4OQE72IphJS' ),
		array(
			'2,000+'  => __( 'festival-goers', 'tapaufest-2026' ),
			'12 acts' => __( 'from 4 ASEAN countries', 'tapaufest-2026' ),
		),
		'pentas-night',
		true
	);
	echo tapaufest_photo_strip( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		array(
			'pentas-day'   => __( 'PENTAS by the lake', 'tapaufest-2026' ),
			'layar'        => __( 'LAYAR', 'tapaufest-2026' ),
			'dj'           => __( 'Between sets', 'tapaufest-2026' ),
			'warung'       => __( 'WARUNG', 'tapaufest-2026' ),
			'aerial'       => __( 'The site from above', 'tapaufest-2026' ),
		)
	);
	echo tapaufest_band_close(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	echo tapaufest_band_open( 'paper' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	echo tapaufest_heading( __( 'After 13 Dec', 'tapaufest-2026' ), __( 'What lands here, and when', 'tapaufest-2026' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	echo tapaufest_grid( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		array(
			tapaufest_card( __( 'Photos', 'tapaufest-2026' ), array( esc_html__( 'Day 1 and Day 2 galleries, within 48 hours.', 'tapaufest-2026' ) ) ),
			tapaufest_card( __( 'Highlights', 'tapaufest-2026' ), array( esc_html__( 'A short video for each day, 1 to 2 days after.', 'tapaufest-2026' ) ) ),
			tapaufest_card( __( 'Set recordings', 'tapaufest-2026' ), array( esc_html__( 'Full sets, 1 to 3 weeks after, once each artist signs off.', 'tapaufest-2026' ) ) ),
			tapaufest_card( __( 'Aftermovie', 'tapaufest-2026' ), array( esc_html__( '2 to 4 weeks after the fest.', 'tapaufest-2026' ) ) ),
		),
		'13rem'
	);
	echo tapaufest_band_close(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	return;
endif;

$tf_survey   = tapaufest_theme_setting( 'survey_url' );
$tf_takedown = tapaufest_theme_setting( 'takedown_url' );
$tf_waitlist = tapaufest_theme_setting( 'waitlist_url' );
echo tapaufest_page_head( __( 'Recap', 'tapaufest-2026' ), __( 'TAPAUfest 2026, packed up', 'tapaufest-2026' ), __( 'Terima kasih, geng. Relive it, find yourself, tell us how it went.', 'tapaufest-2026' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo tapaufest_band_open( 'kopi' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo tapaufest_film( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	__( 'TAPAUfest 2026 Aftermovie: lands 2 to 4 weeks after the fest', 'tapaufest-2026' ),
	home_url( '/recap/' ),
	array(
			'2'     => __( 'nights by the river', 'tapaufest-2026' ),
	)
);
echo tapaufest_band_close(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo tapaufest_band_open( 'paper' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo tapaufest_heading( __( 'Photos', 'tapaufest-2026' ), __( 'Find yourself', 'tapaufest-2026' ), __( 'Day 1 and Day 2 galleries go up within 48 hours.', 'tapaufest-2026' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo tapaufest_grid( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	array(
		tapaufest_card( __( 'Day 1 · Sat 12 Dec', 'tapaufest-2026' ), array( tapaufest_admin_note( __( 'Add the gallery here as a Gallery block', 'tapaufest-2026' ) ) ) ),
		tapaufest_card( __( 'Day 2 · Sun 13 Dec', 'tapaufest-2026' ), array( tapaufest_admin_note( __( 'Add the gallery here as a Gallery block', 'tapaufest-2026' ) ) ) ),
	)
);
echo tapaufest_band_close(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo tapaufest_band_open( 'kopi' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo tapaufest_grid( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	array(
		tapaufest_card( __( 'How was it?', 'tapaufest-2026' ), array( esc_html__( 'Two minutes, five questions. It shapes next year.', 'tapaufest-2026' ), $tf_survey ? '<a href="' . esc_url( $tf_survey ) . '">' . esc_html__( 'Take the survey ↗', 'tapaufest-2026' ) . '</a>' : tapaufest_admin_note( __( 'Survey link goes in Settings > TAPAUfest', 'tapaufest-2026' ) ) ) ),
		tapaufest_card( __( 'Your photo, your call', 'tapaufest-2026' ), array( esc_html__( 'Spotted yourself and want it down? We remove it within 48 hours.', 'tapaufest-2026' ), $tf_takedown ? '<a href="' . esc_url( $tf_takedown ) . '">' . esc_html__( 'Request a takedown ↗', 'tapaufest-2026' ) . '</a>' : tapaufest_admin_note( __( 'Takedown form link goes in Settings > TAPAUfest', 'tapaufest-2026' ) ) ) ),
		tapaufest_card( __( 'See you next year', 'tapaufest-2026' ), array( esc_html__( 'Waitlist members get early access and the early-bird price.', 'tapaufest-2026' ), $tf_waitlist ? '<a href="' . esc_url( $tf_waitlist ) . '">' . esc_html__( 'Join the waitlist ↗', 'tapaufest-2026' ) . '</a>' : tapaufest_admin_note( __( 'Waitlist link goes in Settings > TAPAUfest', 'tapaufest-2026' ) ) ) ),
	)
);
echo tapaufest_band_close(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
