<?php
/**
 * Screen: lineup. Filters on the left, acts on the right, stars on every card.
 *
 * @package Tapaufest2026
 */

defined( 'ABSPATH' ) || exit;

$tf_artists = array_map( 'tapaufest_artist_data', tapaufest_get_artists() );
$tf_hosts   = array_map( 'tapaufest_artist_data', tapaufest_get_artists( 0, true ) );
$tf_days    = tapaufest_days();
$tf_cc      = array();
foreach ( $tf_artists as $tf_a ) {
	if ( $tf_a['country'] ) {
		$tf_cc[ $tf_a['country'] ] = $tf_a['country_name'];
	}
}
asort( $tf_cc );
?>
<div class="tf2-wrap tf2-main" data-tf2-lineup>
	<?php echo tf2_head( __( 'Tentative lineup · subject to change', 'tapaufest-2026' ), __( 'Lineup', 'tapaufest-2026' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	<?php if ( ! $tf_artists ) : ?>
		<?php echo tf2_empty( 'music', __( 'Lineup drops soon.', 'tapaufest-2026' ), __( 'Join Geng TAPAU and hear it first.', 'tapaufest-2026' ), array( tf2_btn( __( 'Join free', 'tapaufest-2026' ), tapaufest_setting( 'join_url' ), 'cili', array( 'external' => true ) ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	<?php else : ?>
	<div class="tf2-side">
		<aside class="tf2-card tf2-pad tf2-filters tf2-sticky" aria-label="<?php esc_attr_e( 'Filter the lineup', 'tapaufest-2026' ); ?>">
			<?php if ( count( $tf_days ) > 1 ) : ?>
				<div class="tf2-filters__group" role="group" aria-label="<?php esc_attr_e( 'Day', 'tapaufest-2026' ); ?>">
					<p class="tf2-filters__label"><?php esc_html_e( 'Day', 'tapaufest-2026' ); ?></p>
					<div class="tf2-filters__chips">
						<button type="button" class="tf2-chip" data-tf2-day="" aria-pressed="true"><?php esc_html_e( 'Both days', 'tapaufest-2026' ); ?></button>
						<?php foreach ( $tf_days as $tf_d => $tf_l ) : ?>
							<button type="button" class="tf2-chip" data-tf2-day="<?php echo esc_attr( $tf_d ); ?>" aria-pressed="false"><?php echo esc_html( $tf_l ); ?></button>
						<?php endforeach; ?>
					</div>
				</div>
			<?php endif; ?>
			<?php if ( count( $tf_cc ) > 1 ) : ?>
				<div class="tf2-filters__group" role="group" aria-label="<?php esc_attr_e( 'Country', 'tapaufest-2026' ); ?>">
					<p class="tf2-filters__label"><?php esc_html_e( 'From', 'tapaufest-2026' ); ?></p>
					<div class="tf2-filters__chips">
						<button type="button" class="tf2-chip" data-tf2-cc="" aria-pressed="true"><?php esc_html_e( 'Everywhere', 'tapaufest-2026' ); ?></button>
						<?php foreach ( $tf_cc as $tf_c => $tf_n ) : ?>
							<button type="button" class="tf2-chip" data-tf2-cc="<?php echo esc_attr( $tf_c ); ?>" aria-pressed="false"><?php echo esc_html( $tf_n ); ?></button>
						<?php endforeach; ?>
					</div>
				</div>
			<?php endif; ?>
			<label class="tf2-switch"><input type="checkbox" data-tf2-only> <?php esc_html_e( 'Only my stars', 'tapaufest-2026' ); ?></label>
			<?php /* translators: %d: number of acts. */ ?>
			<p class="tf2-count-line" data-tf2-count-line aria-live="polite"><?php echo esc_html( sprintf( __( 'Showing %1$d of %1$d acts', 'tapaufest-2026' ), count( $tf_artists ) ) ); ?></p>
			<?php echo tf2_btn( __( 'Open set times', 'tapaufest-2026' ), home_url( '/timetable/' ), 'cili' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</aside>
		<div class="tf2-stack">
			<?php echo tf2_empty( 'star', __( 'No stars yet.', 'tapaufest-2026' ), __( 'Tap the star on any act. It lands in Jadual Aku.', 'tapaufest-2026' ), array(), 'data-tf2-nostars hidden' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<?php echo tf2_empty( 'search', __( 'No acts match.', 'tapaufest-2026' ), __( 'Loosen one filter and try again.', 'tapaufest-2026' ), array( '<button type="button" class="tf2-btn tf2-btn--cili" data-tf2-clear><span>' . esc_html__( 'Clear filters', 'tapaufest-2026' ) . '</span></button>' ), 'data-tf2-none hidden' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<div class="tf2-auto">
				<?php
				foreach ( $tf_artists as $tf_a ) {
					echo tf2_artist_card( $tf_a ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				}
				?>
			</div>
		</div>
	</div>
	<?php endif; ?>

	<?php if ( $tf_hosts ) : ?>
		<section style="margin-top:72px">
			<?php echo tf2_head( __( 'On the mic', 'tapaufest-2026' ), __( 'Your hosts', 'tapaufest-2026' ), __( 'Stand-ins from 2025 until this year’s hosts are announced.', 'tapaufest-2026' ), 'h2' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<div class="tf2-auto">
				<?php
				foreach ( $tf_hosts as $tf_a ) {
					echo tf2_artist_card( $tf_a, false ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				}
				?>
			</div>
		</section>
	<?php endif; ?>
</div>
