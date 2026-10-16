<?php
/**
 * Title: Home: mini FAQ
 * Slug: tapaufest-2026/home-faq
 * Categories: tapaufest
 * Description: The questions flagged "Show on the home page", with a link to the full FAQ.
 *
 * @package Tapaufest2026
 */

echo tapaufest_band_open( 'kopi', 'tf-home-faq' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
?>
<!-- wp:columns -->
<div class="wp-block-columns">
<!-- wp:column {"width":"36%"} -->
<div class="wp-block-column" style="flex-basis:36%">
<?php
echo tapaufest_heading( __( 'FAQ', 'tapaufest-2026' ), __( 'Before you ask', 'tapaufest-2026' ), __( 'The questions we get most. Everything else is in the full FAQ.', 'tapaufest-2026' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo tapaufest_more_link( __( 'All questions →', 'tapaufest-2026' ), '/faq/' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
?>
</div>
<!-- /wp:column -->

<!-- wp:column {"width":"64%"} -->
<div class="wp-block-column" style="flex-basis:64%">
<!-- wp:tapaufest/faq {"mini":true} /-->
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->
<?php
echo tapaufest_band_close(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
