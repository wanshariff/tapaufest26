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

$tf_maps = tapaufest_theme_setting( 'maps_url', 'https://www.google.com/maps/search/Chongkak+Park+%26+Resort+Hulu+Langat' );
echo tapaufest_page_head( __( 'Plan your trip', 'tapaufest-2026' ), __( 'Getting to Chongkak Park', 'tapaufest-2026' ), __( 'Our new home in the rainforest of Hulu Langat, Selangor. Here’s how to get there, where to sleep and what to pack. Parking and drop-off details land by 6 Nov.', 'tapaufest-2026' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo tapaufest_band_open( 'kopi' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo tapaufest_photo( 'venue-forest', __( 'Festival-goers under the trees at Chongkak Park & Resort', 'tapaufest-2026' ), 'tf-wide-photo' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo tapaufest_heading( __( 'Getting there', 'tapaufest-2026' ), __( 'Drive, or MRT and shuttle', 'tapaufest-2026' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo tapaufest_grid( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	array(
		tapaufest_card( __( 'Address', 'tapaufest-2026' ), array( esc_html( tapaufest_theme_setting( 'venue', 'Chongkak Park & Resort, Hulu Langat, Selangor' ) ), '<a href="' . esc_url( $tf_maps ) . '">' . esc_html__( 'Open in Maps ↗', 'tapaufest-2026' ) . '</a>' ) ),
		tapaufest_card( __( 'MRT and shuttle', 'tapaufest-2026' ), array( esc_html__( 'Take the MRT Kajang Line to MRT Sri Raya. Official TAPAUfest shuttles take you straight to the festival gates.', 'tapaufest-2026' ), tapaufest_tbc( __( 'Shuttle times TBC', 'tapaufest-2026' ) ) ) ),
		tapaufest_card( __( 'Driving', 'tapaufest-2026' ), array( esc_html__( 'About an hour from Kuala Lumpur.', 'tapaufest-2026' ) . ' ' . esc_html__( 'Parking:', 'tapaufest-2026' ) . ' ' . tapaufest_tbc( 'TBC' ) . '.' ) ),
		tapaufest_card( __( 'Grab', 'tapaufest-2026' ), array( esc_html__( 'Use the official drop-off point.', 'tapaufest-2026' ) . ' ' . tapaufest_tbc( 'TBC' ), esc_html__( 'Signal can be patchy, so book your ride home early.', 'tapaufest-2026' ) ) ),
		tapaufest_card( __( 'Carpool', 'tapaufest-2026' ), array( esc_html__( 'Coming from Ipoh, Penang or JB? Ask in the comments on our latest Instagram post to find a ride.', 'tapaufest-2026' ) ) ),
	)
);
echo tapaufest_band_close(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo tapaufest_band_open( 'paper' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo tapaufest_heading( __( 'Where to sleep', 'tapaufest-2026' ), __( 'Camp or check in', 'tapaufest-2026' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo tapaufest_grid( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	array(
		tapaufest_card( __( 'Santuy Campsite', 'tapaufest-2026' ), array( esc_html__( 'Camp two nights right inside the festival grounds. The Santrav team handles the decor and the bonfire sessions run late. Book your spot separately.', 'tapaufest-2026' ), tapaufest_tbc( __( 'Booking link TBC', 'tapaufest-2026' ) ) ), 'santuy-campsite' ),
		tapaufest_card( __( 'Nearby campsites', 'tapaufest-2026' ), array( esc_html__( 'Locally run campsites close to the venue. Book with them directly.', 'tapaufest-2026' ), tapaufest_tbc( __( 'List coming soon', 'tapaufest-2026' ) ) ) ),
		tapaufest_card( __( 'Chalets', 'tapaufest-2026' ), array( esc_html__( 'Chalet staycations at Chongkak Park & Resort, plus homestays around Hulu Langat.', 'tapaufest-2026' ), tapaufest_tbc( __( 'Partner deals TBC', 'tapaufest-2026' ) ) ) ),
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
