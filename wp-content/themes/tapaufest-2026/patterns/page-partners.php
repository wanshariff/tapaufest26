<?php
/**
 * Title: Page: Partners
 * Slug: tapaufest-2026/page-partners
 * Categories: tapaufest-pages
 * Post Types: page
 * Description: Partners by credit, and how brands, vendors and media can work with TAPAUfest.
 *
 * @package Tapaufest2026
 */

echo tapaufest_page_head( __( 'Partners', 'tapaufest-2026' ), __( 'Made possible by', 'tapaufest-2026' ), __( 'The supporters, brands, programme partners, media and crew behind TAPAUfest.', 'tapaufest-2026' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo tapaufest_band_open( 'paper' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo tapaufest_sr_heading( __( 'Partners by credit', 'tapaufest-2026' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
?>
<!-- wp:tapaufest/partners {"align":"wide"} /-->
<?php
echo tapaufest_band_close(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo tapaufest_band_open( 'kopi' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo tapaufest_heading( __( 'Work with us', 'tapaufest-2026' ), __( 'Make the scene with us', 'tapaufest-2026' ), __( 'Message us on Instagram @tapaufest and tell us what you have in mind.', 'tapaufest-2026' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
$tf_ig = '<a href="https://www.instagram.com/tapaufest/" target="_blank" rel="noopener">' . esc_html__( 'Message @tapaufest ↗', 'tapaufest-2026' ) . '</a>';
echo tapaufest_grid( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	array(
		tapaufest_card( __( 'Brands and sponsors', 'tapaufest-2026' ), array( esc_html__( 'Put your brand in front of 2,000+ festival-goers over two days, on site and on tapau.asia.', 'tapaufest-2026' ), $tf_ig ) ),
		tapaufest_card( __( 'Sell at PASAR or WARUNG', 'tapaufest-2026' ), array( esc_html__( 'Indie brands, makers and local kitchens. Apply online, no account needed.', 'tapaufest-2026' ), '<a href="' . esc_url( home_url( '/sell/' ) ) . '">' . esc_html__( 'Apply for a stall →', 'tapaufest-2026' ) . '</a>' ) ),
		tapaufest_card( __( 'Media and creators', 'tapaufest-2026' ), array( esc_html__( 'Press passes, interviews and content partnerships with the TAPAUasia media team.', 'tapaufest-2026' ), $tf_ig ) ),
	),
	'15rem'
);
echo tapaufest_band_close(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
