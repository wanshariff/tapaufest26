<?php
/**
 * Set details for one artist: when, where, listen, add to Jadual Aku.
 *
 * @package TapaufestCore
 */

defined( 'ABSPATH' ) || exit;

$tf_id = isset( $block->context['postId'] ) ? (int) $block->context['postId'] : get_the_ID();
if ( ! $tf_id || 'tf_artist' !== get_post_type( $tf_id ) ) {
	if ( is_admin() || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) ) {
		echo '<p>' . esc_html__( 'Artist set details show here on an artist page.', 'tapaufest' ) . '</p>';
	}
	return;
}

$tf_a     = tapaufest_artist_data( $tf_id );
$tf_s     = $tf_a['session'];
$tf_days  = tapaufest_days();
$tf_embed = tapaufest_embed( $tf_a['listen'] );
$tf_more  = array();
foreach ( tapaufest_get_artists() as $tf_other ) {
	if ( $tf_other->ID === $tf_id ) {
		continue;
	}
	$tf_od = tapaufest_artist_data( $tf_other );
	if ( ( $tf_a['genre'] && $tf_od['genre'] === $tf_a['genre'] ) || ( $tf_a['country'] && $tf_od['country'] === $tf_a['country'] ) ) {
		$tf_more[] = $tf_od;
	}
	if ( count( $tf_more ) >= 4 ) {
		break;
	}
}
?>
<div <?php echo get_block_wrapper_attributes( array( 'class' => 'tf-artist-details' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<?php
	$tf_bill_types = tapaufest_billing_types();
	$tf_bill       = ( $tf_a['billing'] && 'act' !== $tf_a['billing'] && isset( $tf_bill_types[ $tf_a['billing'] ] ) ) ? $tf_bill_types[ $tf_a['billing'] ] : '';
	?>
	<p class="tf-eyebrow"><?php echo esc_html( implode( ' · ', array_filter( array( $tf_bill, $tf_a['country_name'], $tf_a['genre'] ) ) ) ); ?></p>

	<?php if ( $tf_s ) : ?>
		<dl class="tf-facts"<?php echo tapaufest_session_attrs( $tf_s ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
			<div><dt><?php esc_html_e( 'Plays', 'tapaufest' ); ?></dt><dd><?php echo esc_html( ( isset( $tf_days[ $tf_s['day'] ] ) ? $tf_days[ $tf_s['day'] ] : $tf_s['day'] ) . ', ' . $tf_s['label'] ); ?></dd></div>
			<div><dt><?php esc_html_e( 'Stage', 'tapaufest' ); ?></dt><dd><?php echo esc_html( $tf_s['stage'] ); ?></dd></div>
			<?php if ( $tf_s['length'] ) : ?>
				<?php /* translators: %d: minutes. */ ?>
				<div><dt><?php esc_html_e( 'Set length', 'tapaufest' ); ?></dt><dd><?php echo esc_html( sprintf( __( '%d min', 'tapaufest' ), $tf_s['length'] ) ); ?></dd></div>
			<?php endif; ?>
		</dl>
	<?php else : ?>
		<p class="tf-muted"><?php esc_html_e( 'Set time coming soon.', 'tapaufest' ); ?></p>
	<?php endif; ?>

	<div class="tf-ctas tf-ctas--left">
		<?php if ( $tf_s ) : ?>
			<button type="button" class="tf-btn tf-btn--cili" data-tf-star="<?php echo (int) $tf_s['id']; ?>" data-tf-star-label aria-pressed="false"><?php echo tapaufest_icon( 'star' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> <?php esc_html_e( 'Add to Jadual Aku', 'tapaufest' ); ?></button>
		<?php endif; ?>
		<?php if ( $tf_a['profile'] ) : ?>
			<a class="tf-btn" href="<?php echo esc_url( $tf_a['profile'] ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Profile on tapau.asia', 'tapaufest' ); ?> <?php echo tapaufest_icon( 'external' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
		<?php endif; ?>
	</div>

	<h2 class="tf-h2"><?php esc_html_e( 'Listen before you go', 'tapaufest' ); ?></h2>
	<?php if ( $tf_embed ) : ?>
		<div class="tf-player"><?php echo $tf_embed; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- oEmbed HTML from a trusted provider. ?></div>
	<?php else : ?>
		<div class="tf-player tf-player--empty"><?php esc_html_e( 'A Bandcamp or YouTube player shows here once a listen link is added.', 'tapaufest' ); ?></div>
	<?php endif; ?>

	<?php if ( $tf_more ) : ?>
		<?php /* translators: %s: artist name. */ ?>
		<h2 class="tf-h2"><?php echo esc_html( sprintf( __( 'If you like %s', 'tapaufest' ), $tf_a['name'] ) ); ?></h2>
		<div class="tf-grid tf-grid--4">
			<?php
			foreach ( $tf_more as $tf_od ) {
				echo tapaufest_artist_card( $tf_od, false ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped inside.
				echo tapaufest_listen_template( $tf_od ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped inside.
			}
			?>
		</div>
	<?php endif; ?>
</div>
