<?php
/**
 * Title: Page: Past editions
 * Slug: tapaufest-2026/page-archive
 * Categories: tapaufest-pages
 * Post Types: page
 * Description: Every edition since 2014.
 *
 * @package Tapaufest2026
 */

$tf_editions = array(
	array( '2026', __( '12 & 13 Dec · Chongkak Park & Resort, Hulu Langat, Selangor', 'tapaufest-2026' ), __( 'A new era at a new home: an art x nature festival under the forest canopy.', 'tapaufest-2026' ) ),
	array( '2025', __( '23 & 24 Aug · Resort Tasik Raban, Lenggong, Perak', 'tapaufest-2026' ), __( 'The first ASEAN edition, bringing together indie creatives and community from 9 South East Asian nations. 2,000+ festival-goers. Voted Best Festival in Juice’s Best of 2025.', 'tapaufest-2026' ) ),
	array( '2024', __( 'Postponed', 'tapaufest-2026' ), __( 'Postponed for lack of funding.', 'tapaufest-2026' ) ),
	array( '2023', __( '7 & 8 Oct · Resort Tasik Raban, Lenggong, Perak', 'tapaufest-2026' ), __( '#SerumpunBerpesta with Zee Avi, Nadin Amizah, Rub of Rub, Jemson, Force Vomit, Door Plant and Bayangan, plus Amir Jahari at the campsite. BENGKEL and LAYAR co-curated with KakiSeni. Hammocks, biking and a community cookout.', 'tapaufest-2026' ) ),
	array( '2022', __( 'Resort Tasik Raban, Lenggong, Perak', 'tapaufest-2026' ), __( 'The first Lenggong edition.', 'tapaufest-2026' ) ),
	array( '2021', __( '30 Oct · Online', 'tapaufest-2026' ), __( 'During lockdown, a regional online festival: 14 hours streamed on tapau.asia with Zee Avi, Sore, The Panturas, Force Vomit, Subsonic Eye and more from Malaysia, Indonesia and Singapore. Hosted by Iedil Dzuhrie and Sharifah Amani.', 'tapaufest-2026' ) ),
	array( '2019', __( 'The Gasket Alley, Petaling Jaya', 'tapaufest-2026' ), __( 'Disqovr presents TAPAUfest: Hacktick, Midnight Fusic, Milo Dinosaur and more, with a local food row and indie market. With Vans, Marshall, Yoodo, Snek and Rakita.', 'tapaufest-2026' ) ),
	array( '2016', __( '31 Dec · Zebra Square, Kuala Lumpur', 'tapaufest-2026' ), __( 'A New Year’s Eve edition with 10 local acts.', 'tapaufest-2026' ) ),
	array( '2014', __( 'Botanical Garden, Penang', 'tapaufest-2026' ), __( 'South East Asia’s first crowdfunded festival, and the biggest crowdfunded project at the time.', 'tapaufest-2026' ) ),
);
echo tapaufest_page_head( __( 'Since 2014', 'tapaufest-2026' ), __( 'Past editions', 'tapaufest-2026' ), __( 'From a crowdfunded day in Penang to a weekend by the river.', 'tapaufest-2026' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo tapaufest_band_open( 'paper' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
?>
<!-- wp:group {"className":"tf-timeline","layout":{"type":"flex","orientation":"vertical","justifyContent":"stretch"}} -->
<div class="wp-block-group tf-timeline">
<?php foreach ( $tf_editions as $tf_e ) : ?>
<!-- wp:group {"className":"is-style-tf-ink-card","layout":{"type":"default"}} -->
<div class="wp-block-group is-style-tf-ink-card">
<!-- wp:paragraph {"className":"tf-year"} -->
<p class="tf-year"><?php echo esc_html( $tf_e[0] ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p><strong><?php echo esc_html( $tf_e[1] ); ?></strong></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p><?php echo esc_html( $tf_e[2] ); ?></p>
<!-- /wp:paragraph -->
<?php if ( '2025' === $tf_e[0] ) : ?>
<!-- wp:group {"className":"tf-archive-media","layout":{"type":"default"}} -->
<div class="wp-block-group tf-archive-media">
<?php
echo tapaufest_photo( 'poster-2025', __( 'TAPAUfest 2025 ASEAN poster', 'tapaufest-2026' ), 'tf-archive-poster' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo tapaufest_photo( 'pentas-day', __( 'PENTAS by the lake at Resort Tasik Raban, 2025', 'tapaufest-2026' ), 'tf-archive-photo' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
?>
</div>
<!-- /wp:group -->
<?php echo tapaufest_more_link( __( '2025 photos and aftermovie →', 'tapaufest-2026' ), '/recap/' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
<?php endif; ?>
</div>
<!-- /wp:group -->
<?php endforeach; ?>
</div>
<!-- /wp:group -->
<?php
echo tapaufest_band_close(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
