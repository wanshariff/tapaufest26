<?php
/**
 * Screen: one artist.
 *
 * @package Tapaufest2026
 */

defined( 'ABSPATH' ) || exit;

$tf_id = get_queried_object_id();
if ( ! $tf_id || 'tf_artist' !== get_post_type( $tf_id ) ) {
	return;
}
$tf_a     = tapaufest_artist_data( $tf_id );
$tf_s     = $tf_a['session'];
$tf_days  = tapaufest_days();
$tf_thumb = get_the_post_thumbnail( $tf_id, 'large', array( 'class' => 'tf2-photo tf2-photo--shadow tf2-detail__img', 'alt' => $tf_a['name'] ) );
$tf_embed = tapaufest_embed( $tf_a['listen'] );
$tf_bio   = apply_filters( 'the_content', get_post_field( 'post_content', $tf_id ) ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- core filter.
$tf_tba   = ! $tf_s;
$tf_same  = array();
if ( $tf_s ) {
	foreach ( tapaufest_get_sessions( $tf_s['day'] ) as $tf_o ) {
		if ( 'act' === $tf_o['kind'] && $tf_o['id'] !== $tf_s['id'] && $tf_o['artist'] ) {
			$tf_same[] = $tf_o;
		}
	}
}
$tf_clash = $tf_s ? tf2_overlaps( $tf_s ) : array();
$tf_day   = $tf_s && isset( $tf_days[ $tf_s['day'] ] ) ? wp_date( 'D j M', strtotime( $tf_s['day'] . ' 12:00' ) ) : '';
$tf_end   = $tf_s ? tapaufest_format_time( sprintf( '%02d:%02d', floor( $tf_s['end_min'] / 60 ), $tf_s['end_min'] % 60 ) ) : '';
?>
<div class="tf2-wrap tf2-main" style="padding-top:32px">
	<?php echo tf2_back( __( 'Lineup', 'tapaufest-2026' ), get_post_type_archive_link( 'tf_artist' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	<div class="tf2-two">
		<div>
			<?php if ( $tf_thumb ) : ?>
				<?php echo $tf_thumb; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<?php else : ?>
				<div class="tf2-tba" aria-hidden="true"><span class="tf2-wobble">?</span></div>
			<?php endif; ?>
		</div>
		<div class="tf2-stack">
			<p class="tf2-eb"><?php echo esc_html( $tf_s ? $tf_day . ' · ' . $tf_s['stage'] : __( 'To be announced', 'tapaufest-2026' ) ); ?></p>
			<h1 class="tf2-h1 tf2-h1--xl"><?php echo esc_html( $tf_a['name'] ); ?></h1>
			<div class="tf2-row" style="gap:10px">
				<?php if ( $tf_s ) : ?>
					<?php /* translators: 1: start, 2: end. */ ?>
					<span class="tf2-chip is-on"><?php echo tf2_icon( 'clock' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php echo esc_html( sprintf( __( '%1$s till %2$s', 'tapaufest-2026' ), $tf_s['label'], $tf_end ) ); ?></span>
				<?php endif; ?>
				<?php if ( $tf_a['genre'] ) : ?>
					<span class="tf2-chip"><?php echo esc_html( $tf_a['genre'] ); ?></span>
				<?php endif; ?>
				<?php if ( $tf_a['country_name'] ) : ?>
					<span class="tf2-chip"><?php echo esc_html( $tf_a['country_name'] ); ?></span>
				<?php endif; ?>
			</div>
			<?php if ( $tf_clash ) : ?>
				<?php
				$tf_c = $tf_clash[0];
				/* translators: 1: other session, 2: stage, 3: time. */
				echo tf2_alert( 'gold', 'alert', esc_html( sprintf( __( 'Same time as %1$s at %2$s (%3$s). Pick one or catch both halves.', 'tapaufest-2026' ), html_entity_decode( $tf_c['title'] ), $tf_c['stage'], $tf_c['label'] ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				?>
			<?php endif; ?>
			<?php if ( $tf_tba ) : ?>
				<?php echo tf2_alert( 'blue', 'info', esc_html__( 'Set time lands with the full timetable. Star them later and we’ll fit them into your plan.', 'tapaufest-2026' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<?php endif; ?>
			<?php if ( trim( wp_strip_all_tags( $tf_bio ) ) ) : ?>
				<div class="tf2-bio"><?php echo wp_kses_post( $tf_bio ); ?></div>
			<?php endif; ?>
			<div class="tf2-row">
				<?php if ( $tf_s ) : ?>
					<?php echo tf2_star_button( $tf_s ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<?php endif; ?>
				<?php echo tf2_share_button( $tf_a['name'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<?php if ( $tf_a['profile'] ) : ?>
					<?php echo tf2_btn( __( 'Profile on tapau.asia', 'tapaufest-2026' ), $tf_a['profile'], 'ghost', array( 'external' => true ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<?php endif; ?>
			</div>
			<?php if ( $tf_embed ) : ?>
				<p class="tf2-eb" style="margin-top:12px"><?php esc_html_e( 'Listen before you go', 'tapaufest-2026' ); ?></p>
				<div class="tf2-player"><?php echo $tf_embed; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- oEmbed HTML from a trusted provider. ?></div>
			<?php else : ?>
				<p class="tf2-note"><?php esc_html_e( 'No audio preview yet. We only link what the artist shares.', 'tapaufest-2026' ); ?></p>
			<?php endif; ?>
		</div>
	</div>

	<?php if ( $tf_same ) : ?>
		<section style="margin-top:72px">
			<?php /* translators: %s: day. */ ?>
			<p class="tf2-eb"><?php echo esc_html( sprintf( __( 'Also on %s', 'tapaufest-2026' ), $tf_day ) ); ?></p>
			<div class="tf2-auto" style="margin-top:20px">
				<?php foreach ( array_slice( $tf_same, 0, 4 ) as $tf_o ) : ?>
					<?php
					$tf_img = get_the_post_thumbnail( $tf_o['artist'], 'thumbnail', array( 'alt' => '' ) );
					$tf_img = $tf_img ? $tf_img : '<span class="tf2-card__ph has-' . esc_attr( tapaufest_tile_color( $tf_o['artist'] ) ) . '-background-color" aria-hidden="true">' . esc_html( mb_substr( $tf_o['title'], 0, 1 ) ) . '</span>';
					echo tf2_mini( $tf_o['url'], $tf_img, $tf_o['title'], $tf_o['stage'] . ' · ' . $tf_o['label'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					?>
				<?php endforeach; ?>
			</div>
		</section>
	<?php endif; ?>
</div>
