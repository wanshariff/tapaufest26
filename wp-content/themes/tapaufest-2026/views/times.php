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
		<div data-tf2-panel="<?php echo esc_attr( $tf_d ); ?>" id="tf2-day-<?php echo esc_attr( $tf_d ); ?>" role="tabpanel" aria-label="<?php echo esc_attr( $tf_l ); ?>"<?php echo $tf_d === $tf_pick ? '' : ' hidden'; ?>>
		<div class="tf2-ttwrap" tabindex="0" aria-label="<?php echo esc_attr( sprintf( /* translators: %s: day. */ __( '%s, grid by stage', 'tapaufest-2026' ), $tf_l ) ); ?>">
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
		<?php
		// Phone view: one list per stage, with prayer breaks in line.
		$tf_pray   = array_values( wp_list_filter( $tf_list, array( 'kind' => 'prayer' ) ) );
		$tf_bystg  = array();
		foreach ( $tf_list as $tf_s ) {
			if ( 'prayer' !== $tf_s['kind'] && in_array( $tf_s['stage'], $tf_stages, true ) ) {
				$tf_bystg[ $tf_s['stage'] ][] = $tf_s;
			}
		}
		$tf_desc = tapaufest_stages();
		?>
		<div class="tf2-ttlist" data-tf2-stagelist>
			<div class="tf2-ttlist__chips" role="group" aria-label="<?php esc_attr_e( 'Stage', 'tapaufest-2026' ); ?>">
				<button type="button" class="tf2-chip" data-tf2-stage="" aria-pressed="true"><?php esc_html_e( 'All stages', 'tapaufest-2026' ); ?></button>
				<?php foreach ( array_intersect( $tf_stages, array_keys( $tf_bystg ) ) as $tf_stage ) : ?>
					<button type="button" class="tf2-chip" data-tf2-stage="<?php echo esc_attr( $tf_stage ); ?>" aria-pressed="false"><?php echo esc_html( $tf_stage ); ?></button>
				<?php endforeach; ?>
			</div>
			<?php foreach ( $tf_stages as $tf_stage ) : ?>
				<?php
				if ( empty( $tf_bystg[ $tf_stage ] ) ) {
					continue;
				}
				$tf_rows = array_merge( $tf_bystg[ $tf_stage ], $tf_pray );
				usort(
					$tf_rows,
					function ( $a, $b ) {
						return $a['start_min'] - $b['start_min'];
					}
				);
				$tf_first = $tf_bystg[ $tf_stage ][0]['start_min'];
				$tf_last  = end( $tf_bystg[ $tf_stage ] )['end_min'];
				?>
				<section class="tf2-card tf2-ttstage" data-stage="<?php echo esc_attr( $tf_stage ); ?>">
					<h2 class="tf2-ttstage__head">
						<span><?php echo esc_html( $tf_stage ); ?></span>
						<?php if ( isset( $tf_notices[ $tf_stage ] ) ) : ?>
							<span class="tf2-tag tf2-tag--cili"><?php echo esc_html( 'off' === $tf_notices[ $tf_stage ]['status'] ? __( 'Off', 'tapaufest-2026' ) : __( 'Paused', 'tapaufest-2026' ) ); ?></span>
						<?php elseif ( ! empty( $tf_desc[ $tf_stage ] ) ) : ?>
							<small><?php echo esc_html( $tf_desc[ $tf_stage ] ); ?></small>
						<?php endif; ?>
					</h2>
					<?php foreach ( $tf_rows as $tf_s ) : ?>
						<?php if ( 'prayer' === $tf_s['kind'] ) : ?>
							<?php
							if ( $tf_s['end_min'] <= $tf_first || $tf_s['start_min'] >= $tf_last ) {
								continue;
							}
							?>
							<div class="tf2-lrow is-pray"><?php echo tf2_icon( 'moon' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><span><?php echo esc_html( html_entity_decode( $tf_s['title'] ) . ' · ' . $tf_s['label'] ); ?></span></div>
						<?php else : ?>
							<?php $tf_end = tapaufest_format_time( sprintf( '%02d:%02d', floor( $tf_s['end_min'] / 60 ) % 24, $tf_s['end_min'] % 60 ) ); ?>
							<div class="tf2-lrow<?php echo isset( $tf_notices[ $tf_s['stage'] ] ) ? ' is-off' : ''; ?>"<?php echo tapaufest_session_attrs( $tf_s ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
								<span class="tf2-lrow__time"><?php echo esc_html( $tf_s['label'] ); ?></span>
								<span class="tf2-lrow__body">
									<?php if ( $tf_s['url'] ) : ?>
										<a href="<?php echo esc_url( $tf_s['url'] ); ?>"><?php echo esc_html( html_entity_decode( $tf_s['title'] ) ); ?></a>
									<?php else : ?>
										<b><?php echo esc_html( html_entity_decode( $tf_s['title'] ) ); ?></b>
									<?php endif; ?>
									<span><?php echo esc_html( sprintf( /* translators: %s: end time. */ __( 'till %s', 'tapaufest-2026' ), $tf_end ) ); ?></span>
									<span class="tf2-lrow__clash"><?php esc_html_e( 'Clashes with another pick', 'tapaufest-2026' ); ?></span>
								</span>
								<?php echo tapaufest_star_button( $tf_s ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							</div>
						<?php endif; ?>
					<?php endforeach; ?>
				</section>
			<?php endforeach; ?>
		</div>
		</div>
	<?php endforeach; ?>
	<script type="application/json" id="tf2-sessions"><?php echo wp_json_encode( tapaufest_sessions_json() ); ?></script>
</div>
