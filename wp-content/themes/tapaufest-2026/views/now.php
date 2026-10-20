<?php
/**
 * Screen: on now. Every stage at a glance, plus crew notices.
 *
 * @package Tapaufest2026
 */

defined( 'ABSPATH' ) || exit;

$tf_now     = tapaufest_now();
$tf_nn      = tapaufest_now_next();
$tf_notices = tapaufest_notices();
$tf_days    = tapaufest_days();
$tf_clock   = tapaufest_format_time( sprintf( '%02d:%02d', floor( $tf_now['minutes'] / 60 ) % 24, $tf_now['minutes'] % 60 ) );
$tf_live    = 'live' === tapaufest_state();
?>
<div class="tf2-wrap tf2-main">
	<div class="tf2-between">
		<?php echo tf2_head( ( isset( $tf_days[ $tf_now['day'] ] ) ? $tf_days[ $tf_now['day'] ] : '' ) . ' · ' . $tf_clock, __( 'On now', 'tapaufest-2026' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<?php if ( $tf_live && ! $tf_now['preview'] ) : ?>
			<span class="tf2-chip is-on" style="margin-bottom:32px"><span class="tf2-bar__dot" aria-hidden="true" style="background:var(--tf2-cili)"></span><?php esc_html_e( 'Live', 'tapaufest-2026' ); ?></span>
		<?php endif; ?>
	</div>
	<?php if ( $tf_now['preview'] ) : ?>
		<div style="margin-bottom:20px"><?php echo tf2_alert( 'blue', 'info', esc_html__( 'Preview: this shows Day 1 at 8:50pm. On festival days it follows the real time.', 'tapaufest-2026' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
	<?php endif; ?>
	<?php foreach ( $tf_notices as $tf_zone => $tf_n ) : ?>
		<div style="margin-bottom:16px"><?php echo tf2_alert( 'off' === $tf_n['status'] ? 'cili' : 'gold', 'off' === $tf_n['status'] ? 'rain' : 'wave', '<b>' . esc_html( $tf_zone ) . ' ' . esc_html( 'off' === $tf_n['status'] ? __( 'is off', 'tapaufest-2026' ) : __( 'is paused', 'tapaufest-2026' ) ) . '.</b> ' . esc_html( $tf_n['message'] ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
	<?php endforeach; ?>
	<div class="tf2-two tf2-two--wide">
		<div class="tf2-stack tf2-stack--sm">
			<?php if ( ! $tf_nn ) : ?>
				<?php echo tf2_empty( 'moon', __( 'Nothing on right now.', 'tapaufest-2026' ), __( 'Rest up. Here’s what’s next.', 'tapaufest-2026' ), array( tf2_btn( __( 'Set times', 'tapaufest-2026' ), home_url( '/timetable/' ), 'cili' ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<?php endif; ?>
			<?php $tf_i = 0; ?>
			<?php foreach ( $tf_nn as $tf_stage => $tf_pair ) : ?>
				<?php
				$tf_s   = $tf_pair['now'] ? $tf_pair['now'] : $tf_pair['next'];
				$tf_off = isset( $tf_notices[ $tf_stage ] ) && 'off' === $tf_notices[ $tf_stage ]['status'];
				$tf_img = $tf_s['artist'] ? get_the_post_thumbnail( $tf_s['artist'], 'thumbnail', array( 'alt' => '' ) ) : '';
				$tf_img = $tf_img ? $tf_img : '<span class="tf2-card__ph has-' . esc_attr( tapaufest_tile_color( $tf_s['id'] ) ) . '-background-color" aria-hidden="true">' . esc_html( mb_substr( $tf_stage, 0, 1 ) ) . '</span>';
				$tf_pct = $tf_pair['now'] && $tf_s['length'] ? max( 4, min( 100, round( 100 * ( $tf_now['minutes'] - $tf_s['start_min'] ) / $tf_s['length'] ) ) ) : 0;
				$tf_end = tapaufest_format_time( sprintf( '%02d:%02d', floor( $tf_s['end_min'] / 60 ), $tf_s['end_min'] % 60 ) );
				?>
				<div class="tf2-card tf2-stage-now<?php echo $tf_off ? ' is-off' : ''; ?>" style="animation-delay:<?php echo (int) $tf_i++ * 50; ?>ms">
					<?php echo $tf_img; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<span>
						<span class="tf2-eb tf2-eb--cili"><?php echo esc_html( $tf_stage . ( $tf_pair['now'] ? '' : ' · ' . __( 'next', 'tapaufest-2026' ) ) ); ?></span>
						<strong><?php echo $tf_s['url'] ? '<a href="' . esc_url( $tf_s['url'] ) . '" style="color:var(--tf2-ink)">' . esc_html( html_entity_decode( $tf_s['title'] ) ) . '</a>' : esc_html( html_entity_decode( $tf_s['title'] ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong>
						<?php if ( $tf_pair['now'] ) : ?>
							<span class="tf2-progress tf2-progress--live"><span style="width:<?php echo (int) $tf_pct; ?>%"></span></span>
						<?php endif; ?>
					</span>
					<span class="tf2-stage-now__time"><?php echo esc_html( $tf_off ? __( 'Off tonight', 'tapaufest-2026' ) : $tf_s['label'] . ' till ' . $tf_end ); ?></span>
				</div>
			<?php endforeach; ?>
		</div>
		<aside class="tf2-stack tf2-sticky">
			<?php
			$tf_next = null;
			$tf_mine_note = __( 'Star sets on the lineup and your next one shows here.', 'tapaufest-2026' );
			?>
			<div class="tf2-card tf2-card--gold tf2-pad tf2-stack tf2-stack--sm" data-tf2-nextpick data-day="<?php echo esc_attr( $tf_now['day'] ); ?>" data-min="<?php echo (int) $tf_now['minutes']; ?>">
				<p style="font:800 1.05rem var(--tf2-text)"><?php esc_html_e( 'Next in your plan', 'tapaufest-2026' ); ?></p>
				<p class="tf2-h3" data-tf2-nextpick-title hidden></p>
				<p data-tf2-nextpick-note><?php echo esc_html( $tf_mine_note ); ?></p>
				<?php echo tf2_btn( __( 'Open Jadual Aku', 'tapaufest-2026' ), home_url( '/jadual-aku/' ), 'paper', array( 'icon' => 'star' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
			<?php echo tf2_btn( __( 'Map', 'tapaufest-2026' ), home_url( '/map/' ), 'paper', array( 'icon' => 'pin' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<?php echo tf2_btn( __( 'Need a hand?', 'tapaufest-2026' ), home_url( '/help/' ), 'ghost', array( 'icon' => 'info' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</aside>
	</div>
</div>
<script type="application/json" id="tf2-sessions"><?php echo wp_json_encode( tapaufest_sessions_json() ); ?></script>
