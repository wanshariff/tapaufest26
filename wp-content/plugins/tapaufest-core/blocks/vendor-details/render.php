<?php
/**
 * Vendor page details.
 *
 * @package TapaufestCore
 */

defined( 'ABSPATH' ) || exit;

$tf_id = isset( $block->context['postId'] ) ? (int) $block->context['postId'] : get_the_ID();
if ( ! $tf_id || 'tf_vendor' !== get_post_type( $tf_id ) ) {
	if ( is_admin() || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) ) {
		echo '<p>' . esc_html__( 'Vendor details appear here on a vendor page.', 'tapaufest' ) . '</p>';
	}
	return;
}
$tf_types  = tapaufest_vendor_types();
$tf_prices = tapaufest_price_ranges();
$tf_type   = (string) get_post_meta( $tf_id, 'tf_vendor_type', true );
$tf_price  = (string) get_post_meta( $tf_id, 'tf_price', true );
$tf_sig    = (string) get_post_meta( $tf_id, 'tf_signature', true );
$tf_ig     = ltrim( (string) get_post_meta( $tf_id, 'tf_instagram', true ), '@' );
$tf_year   = (string) get_post_meta( $tf_id, 'tf_year', true );
$tf_tags   = array_filter( array_map( 'trim', explode( ',', (string) get_post_meta( $tf_id, 'tf_tags', true ) ) ) );
?>
<div <?php echo get_block_wrapper_attributes( array( 'class' => 'tf-artist-details tf-vendor-details' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<p class="tf-eyebrow"><?php echo esc_html( implode( ' · ', array_filter( array( isset( $tf_types[ $tf_type ] ) ? $tf_types[ $tf_type ] : '', get_post_meta( $tf_id, 'tf_note', true ) ) ) ) ); ?></p>
	<dl class="tf-facts">
		<?php if ( $tf_sig ) : ?>
			<div><dt><?php esc_html_e( 'Must-try', 'tapaufest' ); ?></dt><dd><?php echo esc_html( $tf_sig ); ?></dd></div>
		<?php endif; ?>
		<?php if ( $tf_price && isset( $tf_prices[ $tf_price ] ) ) : ?>
			<div><dt><?php esc_html_e( 'Price', 'tapaufest' ); ?></dt><dd><?php echo esc_html( $tf_prices[ $tf_price ] ); ?></dd></div>
		<?php endif; ?>
		<?php if ( $tf_year ) : ?>
			<div><dt><?php esc_html_e( 'At TAPAUfest', 'tapaufest' ); ?></dt><dd><?php echo esc_html( $tf_year ); ?></dd></div>
		<?php endif; ?>
		<div><dt><?php esc_html_e( 'Find them', 'tapaufest' ); ?></dt><dd><a href="<?php echo esc_url( home_url( '/map/' ) ); ?>"><?php echo esc_html( ( isset( $tf_types[ $tf_type ] ) ? $tf_types[ $tf_type ] : '' ) . ' ' . __( 'on the map', 'tapaufest' ) ); ?></a></dd></div>
	</dl>
	<?php if ( $tf_tags ) : ?>
		<p class="tf-vendor__tags">
			<?php foreach ( $tf_tags as $tf_t ) : ?>
				<span class="tf-tag"><?php echo esc_html( $tf_t ); ?></span>
			<?php endforeach; ?>
		</p>
	<?php endif; ?>
	<div class="tf-ctas tf-ctas--left">
		<?php if ( $tf_ig ) : ?>
			<a class="tf-btn" href="<?php echo esc_url( 'https://www.instagram.com/' . rawurlencode( $tf_ig ) . '/' ); ?>" target="_blank" rel="noopener">@<?php echo esc_html( $tf_ig ); ?> ↗</a>
		<?php endif; ?>
		<a class="tf-btn" href="<?php echo esc_url( home_url( '/experience/' ) ); ?>"><?php esc_html_e( 'All stalls', 'tapaufest' ); ?></a>
	</div>
</div>
