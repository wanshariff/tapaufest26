<?php
/**
 * Screen: set times. One grid per day, a column per stage, prayer breaks
 * across every stage. Stars add a set to Jadual Aku.
 *
 * @package Tapaufest2026
 */

defined( 'ABSPATH' ) || exit;

$tf_days    = tapaufest_days();
$tf_stages  = array_keys( tapaufest_stages() );
$tf_notices = tapaufest_notices();
$tf_now     = tapaufest_now();
$tf_live    = 'live' === tapaufest_state();
$tf_row     = 22; // px per 15 minutes.
$tf_pick    = isset( $tf_days[ $tf_now['day'] ] ) && ( $tf_live ) ? $tf_now['day'] : key( $tf_days );
?>
<div class="tf2-wrap tf2-main" data-tf2-tabs>
	<div class="tf2-between">
		<?php echo tf2_head( implode( ' & ', array_values( $tf_days ) ), __( 'Set times', 'tapaufest-2026' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<?php if ( count( $tf_days ) > 1 ) : ?>
			<div class="tf2-row" role="tablist" aria-label="<?php esc_attr_e( 'Day', 'tapaufest-2026' ); ?>" style="margin-bottom:32px">
				<?php foreach ( $tf_days as $tf_d => $tf_l ) : ?>
					<button type="button" class="tf2-chip" role="tab" data-tf2-tab="<?php echo esc_attr( $tf_d ); ?>" aria-controls="tf2-day-<?php echo esc_attr( $tf_d ); ?>" aria-selected="<?php echo $tf_d === $tf_pick ? 'true' : 'false'; ?>"><?php echo esc_html( $tf_l ); ?></button>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
	<div class="tf2-legend">
		<span><i></i><?php esc_html_e( 'In Jadual Aku', 'tapaufest-2026' ); ?></span>
		<span><i class="is-clash"></i><?php esc_html_e( 'Clashes with another pick', 'tapaufest-2026' ); ?></span>
		<span><i class="is-pray"></i><?php esc_html_e( 'Prayer break, all stages pause', 'tapaufest-2026' ); ?></span>
		<span style="opacity:.8"><?php esc_html_e( 'Times can shift on the day. Starred sets get a heads-up.', 'tapaufest-2026' ); ?></span>
	</div>
	<?php foreach ( $tf_notices as $tf_zone => $tf_n ) : ?>
		<div style="margin-bottom:16px"><?php echo tf2_alert( 'off' === $tf_n['status'] ? 'cili' : 'gold', 'off' === $tf_n['status'] ? 'rain' : 'wave', '<b>' . esc_html( $tf_zone ) . ':</b> ' . esc_html( $tf_n['message'] ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
	<?php endforeach; ?>

	<?php foreach ( $tf_days as $tf_d => $tf_l ) : ?>
		<?php
		$tf_list = tapaufest_get_sessions( $tf_d );
		if ( ! $tf_list ) {
			echo '<div data-tf2-panel="' . esc_attr( $tf_d ) . '" id="tf2-day-' . esc_attr( $tf_d ) . '" role="tabpanel">' . tf2_empty( 'clock', __( 'Set times land soon.', 'tapaufest-2026' ), __( 'Star acts on the lineup now and we’ll slot them in.', 'tapaufest-2026' ), array( tf2_btn( __( 'Browse the lineup', 'tapaufest-2026' ), home_url( '/lineup/' ), 'cili' ) ) ) . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			continue;
		}
		$tf_min = (int) floor( min( wp_list_pluck( $tf_list, 'start_min' ) ) / 60 ) * 60;
		$tf_max = (int) ceil( max( wp_list_pluck( $tf_list, 'end_min' ) ) / 60 ) * 60;
		$tf_n   = (int) ( ( $tf_max - $tf_min ) / 15 );
		?>
		<div class="tf2-ttwrap" data-tf2-panel="<?php echo esc_attr( $tf_d ); ?>" id="tf2-day-<?php echo esc_attr( $tf_d ); ?>" role="tabpanel" tabindex="0" aria-label="<?php echo esc_attr( $tf_l ); ?>"<?php echo $tf_d === $tf_pick ? '' : ' hidden'; ?>>
			<div class="tf2-tt" style="grid-template-columns:64px repeat(<?php echo count( $tf_stages ); ?>,minmax(170px,1fr));grid-template-rows:52px repeat(<?php echo (int) $tf_n; ?>,<?php echo (int) $tf_row; ?>px)">
				<?php for ( $tf_i = 0; $tf_i < $tf_n; $tf_i++ ) : ?>
					<?php $tf_m = $tf_min + $tf_i * 15; ?>
					<div class="tf2-tt__time<?php echo 0 === $tf_m % 60 ? '' : ' is-half'; ?>" style="grid-row:<?php echo (int) $tf_i + 2; ?>"><?php echo 0 === $tf_m % 60 ? esc_html( tapaufest_format_time( sprintf( '%02d:00', $tf_m / 60 ) ) ) : ''; ?></div>
				<?php endfor; ?>
				<?php foreach ( $tf_stages as $tf_si => $tf_stage ) : ?>
					<div class="tf2-tt__stage" style="grid-column:<?php echo (int) $tf_si + 2; ?>">
						<?php echo esc_html( $tf_stage ); ?>
						<?php if ( isset( $tf_notices[ $tf_stage ] ) ) : ?>
							<span class="tf2-tag tf2-tag--cili"><?php echo esc_html( 'off' === $tf_notices[ $tf_stage ]['status'] ? __( 'Off', 'tapaufest-2026' ) : __( 'Paused', 'tapaufest-2026' ) ); ?></span>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
				<?php foreach ( $tf_list as $tf_s ) : ?>
					<?php
					$tf_a = (int) round( ( $tf_s['start_min'] - $tf_min ) / 15 ) + 2;
					$tf_b = max( $tf_a + 1, (int) round( ( $tf_s['end_min'] - $tf_min ) / 15 ) + 2 );
					if ( 'prayer' === $tf_s['kind'] ) :
						?>
						<div class="tf2-tt__pray" style="grid-column:2 / -1;grid-row:<?php echo (int) $tf_a; ?> / <?php echo (int) $tf_b; ?>"><?php echo tf2_icon( 'moon' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php echo esc_html( html_entity_decode( $tf_s['title'] ) ); ?></div>
						<?php
						continue;
					endif;
					$tf_col = array_search( $tf_s['stage'], $tf_stages, true );
					if ( false === $tf_col ) {
						continue;
					}
					$tf_end = tapaufest_format_time( sprintf( '%02d:%02d', floor( $tf_s['end_min'] / 60 ), $tf_s['end_min'] % 60 ) );
					$tf_cls = 'tf2-card tf2-slot' . ( $tf_s['length'] <= 30 ? ' is-short' : '' ) . ( isset( $tf_notices[ $tf_s['stage'] ] ) ? ' is-off' : '' );
					?>
					<div class="<?php echo esc_attr( $tf_cls ); ?>" style="grid-column:<?php echo (int) $tf_col + 2; ?>;grid-row:<?php echo (int) $tf_a; ?> / <?php echo (int) $tf_b; ?>"<?php echo tapaufest_session_attrs( $tf_s ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
						<?php if ( $tf_s['url'] ) : ?>
							<a href="<?php echo esc_url( $tf_s['url'] ); ?>"><?php echo esc_html( html_entity_decode( $tf_s['title'] ) ); ?></a>
						<?php else : ?>
							<a><?php echo esc_html( html_entity_decode( $tf_s['title'] ) ); ?></a>
						<?php endif; ?>
						<span class="tf2-slot__time"><?php echo esc_html( $tf_s['label'] . ' till ' . $tf_end ); ?></span>
						<?php echo tapaufest_star_button( $tf_s ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
				<?php endforeach; ?>
				<?php if ( $tf_live && $tf_d === $tf_now['day'] && $tf_now['minutes'] >= $tf_min && $tf_now['minutes'] < $tf_max ) : ?>
					<?php $tf_r = (int) floor( ( $tf_now['minutes'] - $tf_min ) / 15 ) + 2; ?>
					<div class="tf2-tt__now" style="grid-row:<?php echo (int) $tf_r; ?>;margin-top:<?php echo (int) round( ( ( $tf_now['minutes'] - $tf_min ) % 15 ) / 15 * $tf_row ); ?>px"><span><?php esc_html_e( 'NOW', 'tapaufest-2026' ); ?></span></div>
				<?php endif; ?>
			</div>
		</div>
	<?php endforeach; ?>
	<script type="application/json" id="tf2-sessions"><?php echo wp_json_encode( tapaufest_sessions_json() ); ?></script>
</div>
