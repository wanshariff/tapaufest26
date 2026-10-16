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
	array( '2026', __( '12 & 13 Dec · Sungai Congkak, Selangor', 'tapaufest-2026' ), __( 'The first edition at Sungai Congkak.', 'tapaufest-2026' ) ),
	array( '2025', __( '23 & 24 Aug · Resort Tasik Raban, Lenggong, Perak', 'tapaufest-2026' ), __( 'ASEAN edition. 2,000+ festival-goers and 12 acts from 4 countries. Voted Best Festival on Juice Best Of ’25.', 'tapaufest-2026' ) ),
	array( '2024', __( 'Postponed', 'tapaufest-2026' ), __( 'Postponed for lack of funding.', 'tapaufest-2026' ) ),
	array( '2023', __( '7 & 8 Oct · Resort Tasik Raban, Lenggong, Perak', 'tapaufest-2026' ), __( '#SerumpunBerpesta with acts from Malaysia, Singapore, Indonesia and Thailand.', 'tapaufest-2026' ) ),
	array( '2022', __( 'Resort Tasik Raban, Lenggong, Perak', 'tapaufest-2026' ), __( 'The first Lenggong edition.', 'tapaufest-2026' ) ),
	array( '2021', __( '30 Oct · Online', 'tapaufest-2026' ), __( 'A 14-hour virtual festival streamed on tapau.asia, with acts from three countries.', 'tapaufest-2026' ) ),
	array( '2016', __( '31 Dec · Zebra Square, Kuala Lumpur', 'tapaufest-2026' ), __( 'A New Year’s Eve edition with 10 local acts.', 'tapaufest-2026' ) ),
	array( '2014', __( 'Botanical Garden, Penang', 'tapaufest-2026' ), __( 'South East Asia’s first crowdfunded music festival.', 'tapaufest-2026' ) ),
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
</div>
<!-- /wp:group -->
<?php endforeach; ?>
</div>
<!-- /wp:group -->
<?php
echo tapaufest_band_close(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
