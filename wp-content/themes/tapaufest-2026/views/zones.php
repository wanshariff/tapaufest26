<?php
/**
 * Screen: what's on. Every zone, with crew notices on top.
 *
 * @package Tapaufest2026
 */

defined( 'ABSPATH' ) || exit;

$tf_notices = tapaufest_notices();
$tf_times   = array();
foreach ( tapaufest_get_sessions() as $tf_s ) {
	if ( 'prayer' !== $tf_s['kind'] ) {
		$tf_times[ $tf_s['stage'] ][] = $tf_s;
	}
}
?>
<div class="tf2-wrap tf2-main">
	<?php echo tf2_head( __( 'Eight corners, one weekend', 'tapaufest-2026' ), __( 'What’s on', 'tapaufest-2026' ), tapaufest_setting( 'explainer' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	<?php foreach ( $tf_notices as $tf_zone => $tf_n ) : ?>
		<div style="margin-bottom:16px"><?php echo tf2_alert( 'off' === $tf_n['status'] ? 'cili' : 'gold', 'off' === $tf_n['status'] ? 'rain' : 'wave', '<b>' . esc_html( $tf_zone ) . ':</b> ' . esc_html( $tf_n['message'] ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
	<?php endforeach; ?>
	<div class="tf2-stack" style="gap:28px">
		<?php foreach ( tapaufest_zones() as $tf_i => $tf_z ) : ?>
			<?php
			$tf_n    = isset( $tf_notices[ $tf_z[0] ] ) ? $tf_notices[ $tf_z[0] ] : null;
			$tf_list = isset( $tf_times[ $tf_z[0] ] ) ? array_slice( $tf_times[ $tf_z[0] ], 0, 3 ) : array();
			$tf_link = in_array( $tf_z[0], array( 'WARUNG', 'PASAR' ), true ) ? array( __( 'See the stalls', 'tapaufest-2026' ), '/makan-pasar/' ) : ( $tf_list ? array( __( 'Set times', 'tapaufest-2026' ), '/timetable/' ) : array( __( 'Getting there', 'tapaufest-2026' ), '/plan/' ) );
			?>
			<article class="tf2-card" id="zone-<?php echo esc_attr( sanitize_title( $tf_z[0] ) ); ?>" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));overflow:hidden">
				<div style="position:relative;min-height:260px">
					<img src="<?php echo esc_url( tapaufest_photo_url( $tf_z[3] ) ); ?>" alt="" loading="lazy" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover<?php echo $tf_n ? ';filter:grayscale(.6)' : ''; ?>">
					<?php if ( $tf_n ) : ?>
						<span class="tf2-tag tf2-tag--cili" style="position:absolute;top:14px;left:14px"><?php echo esc_html( 'off' === $tf_n['status'] ? __( 'Off today', 'tapaufest-2026' ) : __( 'Paused', 'tapaufest-2026' ) ); ?></span>
					<?php endif; ?>
				</div>
				<div class="tf2-pad--lg tf2-stack tf2-stack--sm">
					<p class="tf2-eb"><?php echo esc_html( $tf_z[2] ); ?></p>
					<h2 class="tf2-h2" style="color:var(--tf2-cili)"><?php echo esc_html( $tf_z[0] ); ?></h2>
					<p style="font-size:1.05rem;line-height:1.55"><?php echo esc_html( $tf_n && $tf_n['message'] ? $tf_n['message'] : $tf_z[1] ); ?></p>
					<?php if ( $tf_list ) : ?>
						<ul class="tf2-checks">
							<?php foreach ( $tf_list as $tf_s ) : ?>
								<?php echo tf2_check( '<b>' . esc_html( wp_date( 'D', strtotime( $tf_s['day'] . ' 12:00' ) ) . ' ' . $tf_s['label'] ) . '</b> ' . esc_html( html_entity_decode( $tf_s['title'] ) ), 'clock' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
					<div><?php echo tf2_btn( $tf_link[0], home_url( $tf_link[1] ), 'paper' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
				</div>
			</article>
		<?php endforeach; ?>
	</div>
</div>
