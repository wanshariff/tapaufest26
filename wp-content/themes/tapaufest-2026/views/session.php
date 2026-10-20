<?php
/**
 * Screen: one session (workshop, film, outdoor, bonfire).
 *
 * @package Tapaufest2026
 */

defined( 'ABSPATH' ) || exit;

$tf_id = get_queried_object_id();
if ( ! $tf_id || 'tf_session' !== get_post_type( $tf_id ) ) {
	return;
}
$tf_s     = tapaufest_session_data( get_post( $tf_id ) );
$tf_days  = tapaufest_days();
$tf_day   = isset( $tf_days[ $tf_s['day'] ] ) ? wp_date( 'D j M', strtotime( $tf_s['day'] . ' 12:00' ) ) : $tf_s['day'];
$tf_end   = tapaufest_format_time( sprintf( '%02d:%02d', floor( $tf_s['end_min'] / 60 ), $tf_s['end_min'] % 60 ) );
$tf_body  = apply_filters( 'the_content', get_post_field( 'post_content', $tf_id ) ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- core filter.
$tf_note  = function_exists( 'tapaufest_notices' ) ? tapaufest_notices() : array();
$tf_off   = isset( $tf_note[ $tf_s['stage'] ] ) ? $tf_note[ $tf_s['stage'] ] : null;
$tf_clash = tf2_overlaps( $tf_s );
$tf_photo = array(
	'LAYAR'   => 'layar',
	'BENGKEL' => 'bengkel-sharing',
	'KEMBARA' => 'kembara-trail',
	'UNGGUN'  => 'santuy-bonfire',
	'PENTAS'  => 'pentas-night',
);
$tf_thumb = get_the_post_thumbnail( $tf_id, 'large', array( 'class' => 'tf2-photo tf2-photo--shadow', 'alt' => '' ) );
$tf_kinds = array(
	'workshop' => __( 'Drop in. Spots go first come, first served.', 'tapaufest-2026' ),
	'film'     => __( 'Bring a mat. Screenings run weather permitting.', 'tapaufest-2026' ),
	'outdoor'  => __( 'Wear shoes you can get wet. Crew lead every group.', 'tapaufest-2026' ),
	'other'    => __( 'Open to every pass holder.', 'tapaufest-2026' ),
);
?>
<div class="tf2-wrap tf2-main" style="padding-top:32px">
	<?php echo tf2_back( __( 'Set times', 'tapaufest-2026' ), home_url( '/timetable/' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	<div class="tf2-two tf2-two--center">
		<div class="tf2-stack">
			<p class="tf2-eb"><?php echo esc_html( $tf_day . ' · ' . $tf_s['stage'] . ' · ' . $tf_s['label'] ); ?></p>
			<h1 class="tf2-h1"><?php echo esc_html( html_entity_decode( $tf_s['title'] ) ); ?></h1>
			<?php if ( $tf_off ) : ?>
				<?php echo tf2_alert( 'cili', 'off' === $tf_off['status'] ? 'rain' : 'wave', '<b>' . esc_html( 'off' === $tf_off['status'] ? __( 'Off today.', 'tapaufest-2026' ) : __( 'Paused.', 'tapaufest-2026' ) ) . '</b> ' . esc_html( $tf_off['message'] ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<?php endif; ?>
			<?php if ( trim( wp_strip_all_tags( $tf_body ) ) ) : ?>
				<div class="tf2-bio"><?php echo wp_kses_post( $tf_body ); ?></div>
			<?php endif; ?>
			<div class="tf2-row">
				<?php echo tf2_star_button( $tf_s ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<?php echo tf2_btn( __( 'Map', 'tapaufest-2026' ), home_url( '/map/' ), 'ghost', array( 'icon' => 'pin' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
		</div>
		<div class="tf2-stack">
			<?php echo $tf_thumb ? $tf_thumb : tf2_photo( isset( $tf_photo[ $tf_s['stage'] ] ) ? $tf_photo[ $tf_s['stage'] ] : 'venue-forest', '', 'tf2-photo--shadow' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<div class="tf2-card tf2-pad">
				<ul class="tf2-checks">
					<?php /* translators: 1: start, 2: end, 3: minutes. */ ?>
					<?php echo tf2_check( esc_html( sprintf( __( '%1$s till %2$s · %3$d min', 'tapaufest-2026' ), $tf_s['label'], $tf_end, $tf_s['length'] ) ), 'clock' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<?php echo tf2_check( esc_html( isset( $tf_kinds[ $tf_s['kind'] ] ) ? $tf_kinds[ $tf_s['kind'] ] : $tf_kinds['other'] ), 'info' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<?php if ( $tf_clash ) : ?>
						<?php /* translators: 1: other set, 2: stage. */ ?>
						<?php echo tf2_check( esc_html( sprintf( __( 'Same time as %1$s at %2$s', 'tapaufest-2026' ), html_entity_decode( $tf_clash[0]['title'] ), $tf_clash[0]['stage'] ) ), 'alert' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<?php endif; ?>
				</ul>
			</div>
		</div>
	</div>
</div>
