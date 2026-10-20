<?php
/**
 * Screen: one stall.
 *
 * @package Tapaufest2026
 */

defined( 'ABSPATH' ) || exit;

$tf_id = get_queried_object_id();
if ( ! $tf_id || 'tf_vendor' !== get_post_type( $tf_id ) ) {
	return;
}
$tf_type   = (string) get_post_meta( $tf_id, 'tf_vendor_type', true );
$tf_year   = (string) get_post_meta( $tf_id, 'tf_year', true );
$tf_sample = 'sample' === strtolower( $tf_year );
$tf_tags   = array_filter( array_map( 'trim', explode( ',', (string) get_post_meta( $tf_id, 'tf_tags', true ) ) ) );
$tf_price  = (string) get_post_meta( $tf_id, 'tf_price', true );
$tf_prices = tapaufest_price_ranges();
$tf_ig     = ltrim( (string) get_post_meta( $tf_id, 'tf_instagram', true ), '@' );
$tf_note   = (string) get_post_meta( $tf_id, 'tf_note', true );
$tf_sig    = (string) get_post_meta( $tf_id, 'tf_signature', true );
$tf_body   = has_excerpt( $tf_id ) ? get_the_excerpt( $tf_id ) : wp_strip_all_tags( get_post_field( 'post_content', $tf_id ) );
$tf_thumb  = get_the_post_thumbnail( $tf_id, 'large', array( 'class' => 'tf2-photo tf2-photo--shadow', 'alt' => '' ) );
$tf_zone   = 'pasar' === $tf_type ? 'PASAR' : 'WARUNG';
?>
<div class="tf2-wrap tf2-main" style="padding-top:32px">
	<?php echo tf2_back( __( 'Makan & Pasar', 'tapaufest-2026' ), home_url( '/makan-pasar/#' . strtolower( $tf_zone ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	<div class="tf2-two">
		<div style="position:relative">
			<?php echo $tf_thumb ? $tf_thumb : tf2_photo( 'pasar' === $tf_type ? 'pasar-dj' : 'warung-forest', '', 'tf2-photo--shadow' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<?php if ( $tf_sample ) : ?>
				<span class="tf2-tag" style="position:absolute;top:14px;left:14px"><?php esc_html_e( 'Sample stall', 'tapaufest-2026' ); ?></span>
			<?php endif; ?>
		</div>
		<div class="tf2-stack">
			<p class="tf2-eb"><?php echo esc_html( $tf_zone . ( $tf_year && ! $tf_sample ? ' · ' . $tf_year : '' ) ); ?></p>
			<h1 class="tf2-h1"><?php echo esc_html( get_the_title( $tf_id ) ); ?></h1>
			<?php if ( $tf_tags || $tf_price ) : ?>
				<div class="tf2-row" style="gap:8px">
					<?php foreach ( $tf_tags as $tf_i => $tf_t ) : ?>
						<span class="tf2-chip<?php echo 0 === $tf_i ? ' is-on' : ''; ?>"><?php echo esc_html( $tf_t ); ?></span>
					<?php endforeach; ?>
					<?php if ( $tf_price && isset( $tf_prices[ $tf_price ] ) ) : ?>
						<span class="tf2-chip"><?php echo esc_html( $tf_prices[ $tf_price ] ); ?></span>
					<?php endif; ?>
				</div>
			<?php endif; ?>
			<?php if ( $tf_body ) : ?>
				<p class="tf2-lead"><?php echo esc_html( $tf_body ); ?></p>
			<?php endif; ?>
			<?php if ( $tf_note || $tf_sig ) : ?>
				<div class="tf2-card tf2-pad tf2-stack tf2-stack--sm">
					<?php if ( $tf_note ) : ?>
						<div class="tf2-menu-row"><span><?php esc_html_e( 'What they sell', 'tapaufest-2026' ); ?></span><span><?php echo esc_html( $tf_note ); ?></span></div>
					<?php endif; ?>
					<?php if ( $tf_sig ) : ?>
						<div class="tf2-menu-row"><span><?php esc_html_e( 'Must-try', 'tapaufest-2026' ); ?></span><span><?php echo esc_html( $tf_sig ); ?></span></div>
					<?php endif; ?>
					<?php if ( $tf_sample ) : ?>
						<p class="tf2-note"><?php esc_html_e( 'Sample stall based on past editions. This year’s stalls are announced in November.', 'tapaufest-2026' ); ?></p>
					<?php endif; ?>
				</div>
			<?php endif; ?>
			<div class="tf2-row">
				<?php echo tf2_btn( __( 'Find on map', 'tapaufest-2026' ), home_url( '/map/' ), 'cili', array( 'icon' => 'pin' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<?php if ( $tf_ig ) : ?>
					<?php echo tf2_btn( '@' . $tf_ig, 'https://www.instagram.com/' . rawurlencode( $tf_ig ) . '/', 'ghost', array( 'external' => true, 'icon' => 'instagram' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>
