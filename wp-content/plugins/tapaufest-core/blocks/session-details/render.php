<?php
/**
 * Show page details: when, where, who, and save buttons.
 *
 * @package TapaufestCore
 */

defined( 'ABSPATH' ) || exit;

$tf_id = isset( $block->context['postId'] ) ? (int) $block->context['postId'] : get_the_ID();
if ( ! $tf_id || 'tf_session' !== get_post_type( $tf_id ) ) {
	if ( is_admin() || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) ) {
		echo '<p>' . esc_html__( 'Show details appear here on a show page.', 'tapaufest' ) . '</p>';
	}
	return;
}

$tf_s      = tapaufest_session_data( get_post( $tf_id ) );
$tf_days   = tapaufest_days();
$tf_stages = tapaufest_stages();
$tf_kinds  = tapaufest_session_kinds();
$tf_artist = ( $tf_s['artist'] && 'publish' === get_post_status( $tf_s['artist'] ) ) ? tapaufest_artist_data( $tf_s['artist'] ) : null;
$tf_ics    = rest_url( 'tapaufest/v1/ics' ) . '?ids=' . (int) $tf_id;

$tf_same = array();
foreach ( tapaufest_get_sessions( $tf_s['day'] ) as $tf_o ) {
	if ( $tf_o['stage'] === $tf_s['stage'] && $tf_o['id'] !== $tf_s['id'] && 'prayer' !== $tf_o['kind'] ) {
		$tf_same[] = $tf_o;
	}
}
?>
<div <?php echo get_block_wrapper_attributes( array( 'class' => 'tf-artist-details tf-session-details' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<p class="tf-eyebrow"><?php echo esc_html( implode( ' · ', array_filter( array( isset( $tf_kinds[ $tf_s['kind'] ] ) ? preg_replace( '/\s*\(.*\)/', '', $tf_kinds[ $tf_s['kind'] ] ) : '', $tf_s['stage'] ) ) ) ); ?></p>
	<dl class="tf-facts"<?php echo tapaufest_session_attrs( $tf_s ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<div><dt><?php esc_html_e( 'When', 'tapaufest' ); ?></dt><dd><?php echo esc_html( ( isset( $tf_days[ $tf_s['day'] ] ) ? $tf_days[ $tf_s['day'] ] : __( 'Day TBC', 'tapaufest' ) ) . ( $tf_s['label'] ? ', ' . $tf_s['label'] : '' ) ); ?></dd></div>
		<div><dt><?php esc_html_e( 'Where', 'tapaufest' ); ?></dt><dd><?php echo esc_html( $tf_s['stage'] . ( isset( $tf_stages[ $tf_s['stage'] ] ) ? ' · ' . $tf_stages[ $tf_s['stage'] ] : '' ) ); ?></dd></div>
		<?php if ( $tf_s['length'] ) : ?>
			<?php /* translators: %d: minutes. */ ?>
			<div><dt><?php esc_html_e( 'Length', 'tapaufest' ); ?></dt><dd><?php echo esc_html( sprintf( __( '%d min', 'tapaufest' ), $tf_s['length'] ) ); ?></dd></div>
		<?php endif; ?>
		<?php if ( $tf_artist ) : ?>
			<div><dt><?php esc_html_e( 'With', 'tapaufest' ); ?></dt><dd><a href="<?php echo esc_url( $tf_artist['url'] ); ?>"><?php echo esc_html( $tf_artist['name'] ); ?></a></dd></div>
		<?php endif; ?>
	</dl>

	<div class="tf-ctas tf-ctas--left">
		<button type="button" class="tf-btn tf-btn--cili" data-tf-star="<?php echo (int) $tf_id; ?>" data-tf-star-label aria-pressed="false"><?php esc_html_e( '☆ Add to Jadual Aku', 'tapaufest' ); ?></button>
		<a class="tf-btn" href="<?php echo esc_url( $tf_ics ); ?>"><?php esc_html_e( 'Add to calendar', 'tapaufest' ); ?></a>
	</div>

	<?php if ( $tf_same ) : ?>
		<?php /* translators: %s: stage name. */ ?>
		<h2 class="tf-h2"><?php echo esc_html( sprintf( __( 'Also on %s that day', 'tapaufest' ), $tf_s['stage'] ) ); ?></h2>
		<ul class="tf-same">
			<?php foreach ( $tf_same as $tf_o ) : ?>
				<li><b><?php echo esc_html( $tf_o['label'] ); ?></b> <?php if ( $tf_o['url'] ) : ?><a href="<?php echo esc_url( $tf_o['url'] ); ?>"><?php echo esc_html( $tf_o['title'] ); ?></a><?php else : ?><?php echo esc_html( $tf_o['title'] ); ?><?php endif; ?></li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>
</div>
