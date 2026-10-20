<?php
/**
 * Screen: recap. Before the fest it shows 2025; after, this year.
 *
 * @package Tapaufest2026
 */

defined( 'ABSPATH' ) || exit;

$tf_recap    = 'recap' === tapaufest_state();
$tf_takedown = tapaufest_setting( 'takedown_url' ) ? tapaufest_setting( 'takedown_url' ) : 'https://www.instagram.com/tapaufest/';
$tf_photos   = array(
	'pentas-day'      => __( 'PENTAS by the lake', 'tapaufest-2026' ),
	'crowd'           => __( 'The crowd', 'tapaufest-2026' ),
	'layar'           => __( 'LAYAR', 'tapaufest-2026' ),
	'dj'              => __( 'Between sets', 'tapaufest-2026' ),
	'warung'          => __( 'WARUNG', 'tapaufest-2026' ),
	'aerial'          => __( 'The site from above', 'tapaufest-2026' ),
	'campsite'        => __( 'The campsite', 'tapaufest-2026' ),
	'pasar'           => __( 'PASAR', 'tapaufest-2026' ),
	'kembara-yoga'    => __( 'Morning yoga', 'tapaufest-2026' ),
	'santuy-bonfire'  => __( 'Bonfire', 'tapaufest-2026' ),
	'pentas-night'    => __( 'PENTAS at night', 'tapaufest-2026' ),
	'kembara-skytrex' => __( 'Skytrex', 'tapaufest-2026' ),
);
?>
<div class="tf2-wrap tf2-main">
	<?php if ( $tf_recap ) : ?>
		<?php echo tf2_film( true ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<?php echo tf2_recap_cards(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<div style="margin-top:56px">
			<?php echo tf2_head( __( 'Photos', 'tapaufest-2026' ), __( 'Find yourself', 'tapaufest-2026' ), __( 'Day 1 and Day 2 galleries go up within 48 hours.', 'tapaufest-2026' ), 'h2' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</div>
	<?php else : ?>
		<div class="tf2-between">
			<?php echo tf2_head( __( 'TAPAUfest 2025 ASEAN · Lenggong', 'tapaufest-2026' ), __( '2025 photos', 'tapaufest-2026' ), __( 'This year’s recap lands after 13 Dec. Photos within 48 hours, the aftermovie 2 to 4 weeks after.', 'tapaufest-2026' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<?php if ( tapaufest_setting( 'aftermovie_url' ) ) : ?>
				<div style="margin-bottom:32px"><?php echo tf2_btn( __( 'Watch the 2025 aftermovie', 'tapaufest-2026' ), tapaufest_setting( 'aftermovie_url' ), 'cili', array( 'icon' => 'play', 'external' => true ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
			<?php endif; ?>
		</div>
	<?php endif; ?>
	<div class="tf2-alert tf2-alert--gold" style="margin-bottom:24px">
		<?php echo tf2_icon( 'camera' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<div><?php esc_html_e( 'In a photo you want gone?', 'tapaufest-2026' ); ?> <a href="<?php echo esc_url( $tf_takedown ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Ask us', 'tapaufest-2026' ); ?></a>. <?php esc_html_e( 'We take it down, no questions.', 'tapaufest-2026' ); ?></div>
	</div>
	<div class="tf2-gallery">
		<?php foreach ( $tf_photos as $tf_name => $tf_alt ) : ?>
			<figure><img src="<?php echo esc_url( tapaufest_photo_url( $tf_name ) ); ?>" alt="<?php echo esc_attr( $tf_alt ); ?>" loading="lazy" decoding="async"></figure>
		<?php endforeach; ?>
	</div>
</div>
