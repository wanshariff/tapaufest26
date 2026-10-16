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

$tf_survey   = tapaufest_theme_setting( 'survey_url' );
$tf_takedown = tapaufest_theme_setting( 'takedown_url' );
$tf_waitlist = tapaufest_theme_setting( 'waitlist_url' );
echo tapaufest_page_head( __( 'Recap', 'tapaufest-2026' ), __( 'TAPAUfest 2026, packed up', 'tapaufest-2026' ), __( 'Terima kasih, geng. Relive it, find yourself, tell us how it went.', 'tapaufest-2026' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo tapaufest_band_open( 'kopi' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo tapaufest_film( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	__( 'TAPAUfest 2026 Aftermovie: lands 2 to 4 weeks after the fest', 'tapaufest-2026' ),
	home_url( '/recap/' ),
	array(
		'[__]'  => __( 'festival-goers', 'tapaufest-2026' ),
		'16'    => __( 'acts on PENTAS', 'tapaufest-2026' ),
		'2'     => __( 'nights by the river', 'tapaufest-2026' ),
	)
);
echo tapaufest_band_close(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo tapaufest_band_open( 'paper' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo tapaufest_heading( __( 'Photos', 'tapaufest-2026' ), __( 'Find yourself', 'tapaufest-2026' ), __( 'Day 1 and Day 2 galleries go up within 48 hours. Add them here as Gallery blocks.', 'tapaufest-2026' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo tapaufest_grid( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	array(
		tapaufest_card( __( 'Day 1 · Sat 12 Dec', 'tapaufest-2026' ), array( tapaufest_tbc( __( 'Gallery goes here', 'tapaufest-2026' ) ) ) ),
		tapaufest_card( __( 'Day 2 · Sun 13 Dec', 'tapaufest-2026' ), array( tapaufest_tbc( __( 'Gallery goes here', 'tapaufest-2026' ) ) ) ),
	)
);
echo tapaufest_band_close(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo tapaufest_band_open( 'kopi' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo tapaufest_grid( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	array(
		tapaufest_card( __( 'How was it?', 'tapaufest-2026' ), array( esc_html__( 'Two minutes, five questions. It shapes next year.', 'tapaufest-2026' ), $tf_survey ? '<a href="' . esc_url( $tf_survey ) . '">' . esc_html__( 'Take the survey ↗', 'tapaufest-2026' ) . '</a>' : tapaufest_tbc( __( 'Survey link goes in Settings > TAPAUfest', 'tapaufest-2026' ) ) ) ),
		tapaufest_card( __( 'Your photo, your call', 'tapaufest-2026' ), array( esc_html__( 'Spotted yourself and want it down? We remove it within 48 hours.', 'tapaufest-2026' ), $tf_takedown ? '<a href="' . esc_url( $tf_takedown ) . '">' . esc_html__( 'Request a takedown ↗', 'tapaufest-2026' ) . '</a>' : tapaufest_tbc( __( 'Takedown form link goes in Settings > TAPAUfest', 'tapaufest-2026' ) ) ) ),
		tapaufest_card( __( 'See you next year', 'tapaufest-2026' ), array( esc_html__( 'Waitlist members get early access and the early-bird price.', 'tapaufest-2026' ), $tf_waitlist ? '<a href="' . esc_url( $tf_waitlist ) . '">' . esc_html__( 'Join the waitlist ↗', 'tapaufest-2026' ) . '</a>' : tapaufest_tbc( __( 'Waitlist link goes in Settings > TAPAUfest', 'tapaufest-2026' ) ) ) ),
	)
);
echo tapaufest_band_close(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
