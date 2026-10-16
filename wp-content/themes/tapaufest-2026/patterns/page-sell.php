<?php
/**
 * Title: Page: Sell at TAPAUfest
 * Slug: tapaufest-2026/page-sell
 * Categories: tapaufest-pages
 * Post Types: page
 * Description: Who can apply for a stall, what to expect, and the application form.
 *
 * @package Tapaufest2026
 */

echo tapaufest_page_head( __( 'Stall applications', 'tapaufest-2026' ), __( 'Sell at TAPAUfest', 'tapaufest-2026' ), __( 'Local kitchens, indie makers, brands and activity hosts: open a stall for two days by the river with 2,000+ festival-goers.', 'tapaufest-2026' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo tapaufest_band_open( 'paper' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo tapaufest_heading( __( 'Who we’re looking for', 'tapaufest-2026' ), __( 'Four kinds of stall', 'tapaufest-2026' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo tapaufest_grid( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	array(
		tapaufest_card( __( 'WARUNG: food and drink', 'tapaufest-2026' ), array( esc_html__( 'Local favourites, late-night snacks, coffee and desserts. Vegetarian options are a big plus.', 'tapaufest-2026' ) ), 'warung' ),
		tapaufest_card( __( 'PASAR: makers and merch', 'tapaufest-2026' ), array( esc_html__( 'Indie brands, art, crafts, zines, records and band merch.', 'tapaufest-2026' ) ), 'pasar' ),
		tapaufest_card( __( 'Brand activations', 'tapaufest-2026' ), array( esc_html__( 'Something festival-goers can do, try or take home, not just a banner.', 'tapaufest-2026' ) ), 'dj' ),
		tapaufest_card( __( 'Workshops and activities', 'tapaufest-2026' ), array( esc_html__( 'Hands-on sessions for BENGKEL or outdoor slots for KEMBARA.', 'tapaufest-2026' ) ), 'kembara-yoga' ),
	),
	'14rem'
);
echo tapaufest_band_close(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo tapaufest_band_open( 'kopi' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
?>
<!-- wp:columns -->
<div class="wp-block-columns">
<!-- wp:column {"width":"36%"} -->
<div class="wp-block-column" style="flex-basis:36%">
<?php echo tapaufest_heading( __( 'How it works', 'tapaufest-2026' ), __( 'Apply in five minutes', 'tapaufest-2026' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
<!-- wp:list {"className":"tf-facts"} -->
<ul class="wp-block-list tf-facts">
<!-- wp:list-item -->
<li><strong><?php esc_html_e( '1. Apply', 'tapaufest-2026' ); ?></strong> <?php esc_html_e( 'Tell us what you sell. No account needed.', 'tapaufest-2026' ); ?></li>
<!-- /wp:list-item -->
<!-- wp:list-item -->
<li><strong><?php esc_html_e( '2. Hear back', 'tapaufest-2026' ); ?></strong> <?php esc_html_e( 'The team curates the mix and replies by email or WhatsApp.', 'tapaufest-2026' ); ?></li>
<!-- /wp:list-item -->
<!-- wp:list-item -->
<li><strong><?php esc_html_e( '3. Confirm', 'tapaufest-2026' ); ?></strong> <?php esc_html_e( 'Stall fee, size, power and setup times come with your offer.', 'tapaufest-2026' ); ?> <?php echo tapaufest_tbc( __( 'Fees TBC', 'tapaufest-2026' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></li>
<!-- /wp:list-item -->
<!-- wp:list-item -->
<li><strong><?php esc_html_e( '4. Trade', 'tapaufest-2026' ); ?></strong> <?php esc_html_e( '12 and 13 Dec at Chongkak Park & Resort. Your stall gets its own page on this site.', 'tapaufest-2026' ); ?></li>
<!-- /wp:list-item -->
</ul>
<!-- /wp:list -->
</div>
<!-- /wp:column -->

<!-- wp:column {"width":"64%"} -->
<div class="wp-block-column" style="flex-basis:64%">
<!-- wp:tapaufest/vendor-apply /-->
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->
<?php
echo tapaufest_band_close(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
