<?php
/**
 * Title: Page: Plan your trip
 * Slug: tapaufest-2026/page-plan
 * Categories: tapaufest-pages
 * Post Types: page
 * Description: Getting there, where to sleep and what to pack.
 *
 * @package Tapaufest2026
 */

$tf_maps = tapaufest_theme_setting( 'maps_url', 'https://www.google.com/maps/search/Sungai+Congkak+Hulu+Langat' );
echo tapaufest_page_head( __( 'Plan your trip', 'tapaufest-2026' ), __( 'Getting to Sungai Congkak', 'tapaufest-2026' ), __( 'New venue this year. Here’s how to get there, where to sleep and what to pack.', 'tapaufest-2026' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo tapaufest_band_open( 'kopi' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo tapaufest_grid( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	array(
		tapaufest_card( __( 'Address', 'tapaufest-2026' ), array( esc_html( tapaufest_theme_setting( 'venue', 'Sungai Congkak, Hulu Langat, Selangor' ) ) . ' ' . tapaufest_tbc( __( 'exact site TBC', 'tapaufest-2026' ) ), '<a href="' . esc_url( $tf_maps ) . '">' . esc_html__( 'Open in Maps ↗', 'tapaufest-2026' ) . '</a>' ) ),
		tapaufest_card( __( 'Driving', 'tapaufest-2026' ), array( esc_html__( 'Drive time from KL:', 'tapaufest-2026' ) . ' ' . tapaufest_tbc( 'TBC' ) . '. ' . esc_html__( 'Parking:', 'tapaufest-2026' ) . ' ' . tapaufest_tbc( 'TBC' ) . '.' ) ),
		tapaufest_card( __( 'Grab', 'tapaufest-2026' ), array( esc_html__( 'Use the official drop-off point.', 'tapaufest-2026' ) . ' ' . tapaufest_tbc( 'TBC' ), esc_html__( 'Signal can be patchy, so book your ride home early.', 'tapaufest-2026' ) ) ),
		tapaufest_card( __( 'Carpool', 'tapaufest-2026' ), array( esc_html__( 'Coming from Ipoh, Penang or JB? Ask in the comments on our latest Instagram post to find a ride.', 'tapaufest-2026' ) ) ),
	)
);
echo tapaufest_band_close(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo tapaufest_band_open( 'paper' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo tapaufest_heading( __( 'Where to sleep', 'tapaufest-2026' ), __( 'Camp or check in', 'tapaufest-2026' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo tapaufest_grid( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	array(
		tapaufest_card( __( 'Festival campsite', 'tapaufest-2026' ), array( esc_html__( 'The official campsite, a short walk from PENTAS. Included with Duo Campers passes, with bonfire sets after midnight.', 'tapaufest-2026' ) ) ),
		tapaufest_card( __( 'Nearby campsites', 'tapaufest-2026' ), array( esc_html__( 'Other campsites along the river. Book with them directly.', 'tapaufest-2026' ), tapaufest_tbc( __( 'List coming soon', 'tapaufest-2026' ) ) ) ),
		tapaufest_card( __( 'Homestays and resorts', 'tapaufest-2026' ), array( esc_html__( 'Around Hulu Langat and Semenyih.', 'tapaufest-2026' ), tapaufest_tbc( __( 'Partner deals TBC', 'tapaufest-2026' ) ) ) ),
	)
);
echo tapaufest_band_close(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo tapaufest_band_open( 'kopi' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
$tf_pack = array( __( 'Your e-ticket, saved offline', 'tapaufest-2026' ), __( 'Raincoat or poncho', 'tapaufest-2026' ), __( 'Refillable water bottle', 'tapaufest-2026' ), __( 'Power bank', 'tapaufest-2026' ), __( 'Torchlight for the campsite', 'tapaufest-2026' ), __( 'Insect repellent', 'tapaufest-2026' ), __( 'Some cash for smaller stalls', 'tapaufest-2026' ), __( 'Slippers that can get wet', 'tapaufest-2026' ) );
$tf_no   = array( __( 'Glass and bottles', 'tapaufest-2026' ), __( 'Cigarettes and vapes', 'tapaufest-2026' ), __( 'Professional recording gear', 'tapaufest-2026' ), __( 'Weapons, lasers, fireworks and flares', 'tapaufest-2026' ), __( 'Animals', 'tapaufest-2026' ) );
?>
<!-- wp:columns -->
<div class="wp-block-columns">
<?php foreach ( array( array( __( 'Pack list', 'tapaufest-2026' ), __( 'What to bring', 'tapaufest-2026' ), $tf_pack, 'tf-checklist' ), array( __( 'Leave at home', 'tapaufest-2026' ), __( 'Not allowed', 'tapaufest-2026' ), $tf_no, 'tf-checklist tf-nolist' ) ) as $tf_col ) : ?>
<!-- wp:column -->
<div class="wp-block-column">
<?php echo tapaufest_heading( $tf_col[0], $tf_col[1] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
<!-- wp:list {"className":"<?php echo esc_attr( $tf_col[3] ); ?>"} -->
<ul class="wp-block-list <?php echo esc_attr( $tf_col[3] ); ?>">
<?php foreach ( $tf_col[2] as $tf_item ) : ?>
<!-- wp:list-item -->
<li><?php echo esc_html( $tf_item ); ?></li>
<!-- /wp:list-item -->
<?php endforeach; ?>
</ul>
<!-- /wp:list -->
</div>
<!-- /wp:column -->
<?php endforeach; ?>
</div>
<!-- /wp:columns -->
<?php
echo tapaufest_band_close(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
