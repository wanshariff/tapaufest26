<?php
/**
 * Title: Page: FAQ
 * Slug: tapaufest-2026/page-faq
 * Categories: tapaufest-pages
 * Post Types: page
 * Description: All questions, grouped.
 *
 * @package Tapaufest2026
 */

echo tapaufest_page_head( __( 'FAQ', 'tapaufest-2026' ), __( 'Good to know', 'tapaufest-2026' ), __( 'Can’t find your answer? Message us on Instagram @tapaufest.', 'tapaufest-2026' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo tapaufest_band_open( 'paper' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
?>
<!-- wp:tapaufest/faq /-->
<?php
echo tapaufest_band_close(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
