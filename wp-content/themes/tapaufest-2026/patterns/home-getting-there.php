<?php
/**
 * Title: Home: getting there and camping
 * Slug: tapaufest-2026/home-getting-there
 * Categories: tapaufest
 * Description: Venue intro, the festival campsite and nearby stays, with a link to Plan your trip.
 *
 * @package Tapaufest2026
 */

$tf_maps = tapaufest_theme_setting( 'maps_url', 'https://www.google.com/maps/search/Sungai+Congkak+Hulu+Langat' );
echo tapaufest_band_open( 'paper', 'tf-home-getting-there' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
?>
<!-- wp:columns {"verticalAlignment":"center","className":"tf-split"} -->
<div class="wp-block-columns are-vertically-aligned-center tf-split">
<!-- wp:column {"verticalAlignment":"center","width":"46%"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:46%">
<?php echo tapaufest_photo( 'campsite', __( 'Tents and string lights at the 2025 festival campsite', 'tapaufest-2026' ), 'tf-split-photo' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
</div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center","width":"54%"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:54%">
<?php echo tapaufest_heading( __( 'New venue', 'tapaufest-2026' ), __( 'Two nights by the river', 'tapaufest-2026' ), __( 'This year we move to Sungai Congkak in Hulu Langat, Selangor. Pitch a tent at the festival campsite or stay nearby.', 'tapaufest-2026' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
<!-- wp:list {"className":"tf-facts"} -->
<ul class="wp-block-list tf-facts">
<!-- wp:list-item -->
<li><strong><?php esc_html_e( 'Festival campsite', 'tapaufest-2026' ); ?></strong> <?php esc_html_e( 'A short walk from PENTAS. Included with Duo Campers.', 'tapaufest-2026' ); ?></li>
<!-- /wp:list-item -->
<!-- wp:list-item -->
<li><strong><?php esc_html_e( 'Nearby stays', 'tapaufest-2026' ); ?></strong> <?php esc_html_e( 'Riverside campsites, homestays and resorts around Hulu Langat and Semenyih.', 'tapaufest-2026' ); ?></li>
<!-- /wp:list-item -->
<!-- wp:list-item -->
<li><strong><?php esc_html_e( 'Getting there', 'tapaufest-2026' ); ?></strong> <?php esc_html_e( 'Drive, Grab or carpool. Drop-off and parking details by 6 Nov.', 'tapaufest-2026' ); ?></li>
<!-- /wp:list-item -->
</ul>
<!-- /wp:list -->

<!-- wp:buttons -->
<div class="wp-block-buttons">
<!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/plan/' ) ); ?>"><?php esc_html_e( 'Plan your trip', 'tapaufest-2026' ); ?></a></div>
<!-- /wp:button -->

<!-- wp:button {"className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( $tf_maps ); ?>"><?php esc_html_e( 'Open in Maps ↗', 'tapaufest-2026' ); ?></a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->
<?php
echo tapaufest_band_close(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
