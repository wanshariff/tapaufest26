<?php
/**
 * Timetable with Jadual Aku (personal schedule).
 *
 * Picks are kept in the visitor's browser. The script builds the Jadual Aku
 * tab from the data attributes on each slot.
 *
 * @package TapaufestCore
 */

defined( 'ABSPATH' ) || exit;

$tf_days   = tapaufest_days();
$tf_stages = tapaufest_stages();
$tf_now    = tapaufest_now();
$tf_first  = ( 'live' === tapaufest_state() && ! $tf_now['preview'] && isset( $tf_days[ $tf_now['day'] ] ) ) ? $tf_now['day'] : key( $tf_days );
$tf_kinds  = array(
	'film'     => __( 'Screening', 'tapaufest' ),
	'workshop' => __( 'Workshop', 'tapaufest' ),
	'outdoor'  => __( 'Outdoor', 'tapaufest' ),
	'other'    => '',
);

if ( ! tapaufest_get_sessions() ) : ?>
	<div <?php echo get_block_wrapper_attributes( array( 'class' => 'tf-timetable' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<p class="tf-empty"><?php esc_html_e( 'Set times drop closer to the festival. Join Geng TAPAU to hear first.', 'tapaufest' ); ?></p>
	</div>
	<?php
	return;
endif;
?>
<div <?php echo get_block_wrapper_attributes( array( 'class' => 'tf-timetable' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> data-tf-timetable>
	<div class="tf-import" data-tf-import hidden>
		<p><?php esc_html_e( 'Your geng shared their Jadual Aku. Add their picks to yours?', 'tapaufest' ); ?></p>
		<button type="button" class="tf-btn tf-btn--sm tf-btn--cili" data-tf-import-yes><?php esc_html_e( 'Add their picks', 'tapaufest' ); ?></button>
		<button type="button" class="tf-btn tf-btn--sm" data-tf-import-no><?php esc_html_e( 'No thanks', 'tapaufest' ); ?></button>
	</div>

	<div class="tf-tools">
		<div class="tf-toggle" role="tablist" aria-label="<?php esc_attr_e( 'Day', 'tapaufest' ); ?>">
			<?php foreach ( $tf_days as $tf_date => $tf_label ) : ?>
				<button type="button" role="tab" id="tf-tab-<?php echo esc_attr( $tf_date ); ?>" aria-controls="tf-panel-<?php echo esc_attr( $tf_date ); ?>" aria-selected="<?php echo $tf_date === $tf_first ? 'true' : 'false'; ?>" data-tf-tab="<?php echo esc_attr( $tf_date ); ?>"><?php echo esc_html( $tf_label ); ?></button>
			<?php endforeach; ?>
			<button type="button" role="tab" id="tf-tab-mine" aria-controls="tf-panel-mine" aria-selected="false" data-tf-tab="mine"><?php esc_html_e( 'Jadual Aku', 'tapaufest' ); ?> (<span data-tf-count>0</span>)</button>
		</div>
		<?php /* translators: %s: gates opening time. */ ?>
		<p class="tf-muted"><?php echo esc_html( sprintf( __( 'Gates open %s. Maghrib breaks are marked.', 'tapaufest' ), tapaufest_format_time( tapaufest_setting( 'gates_open' ) ) ) ); ?></p>
	</div>

	<?php foreach ( $tf_days as $tf_date => $tf_label ) : ?>
		<div class="tf-stages" role="tabpanel" id="tf-panel-<?php echo esc_attr( $tf_date ); ?>" aria-labelledby="tf-tab-<?php echo esc_attr( $tf_date ); ?>"<?php echo $tf_date === $tf_first ? '' : ' hidden'; ?>>
			<?php
			$tf_day_sessions = tapaufest_get_sessions( $tf_date );
			foreach ( $tf_stages as $tf_stage => $tf_desc ) :
				$tf_list = array_filter(
					$tf_day_sessions,
					function ( $s ) use ( $tf_stage ) {
						return $s['stage'] === $tf_stage;
					}
				);
				if ( ! $tf_list ) {
					continue;
				}
				?>
				<section class="tf-stagecol">
					<h3 class="tf-stagecol__head"><?php echo tapaufest_icon( tapaufest_stage_icon( $tf_stage ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php echo esc_html( $tf_stage ); ?> <small><?php echo esc_html( $tf_desc ); ?></small></h3>
					<?php foreach ( $tf_list as $tf_s ) : ?>
						<?php if ( 'prayer' === $tf_s['kind'] ) : ?>
							<div class="tf-slot tf-slot--prayer"><time><?php echo esc_html( $tf_s['label'] ); ?></time><p class="tf-slot__title"><?php echo esc_html( $tf_s['title'] ); ?></p></div>
						<?php else : ?>
							<div class="tf-slot"<?php echo tapaufest_session_attrs( $tf_s ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
								<time><?php echo esc_html( $tf_s['label'] ); ?></time>
								<p class="tf-slot__title">
									<?php if ( $tf_s['url'] ) : ?>
										<a href="<?php echo esc_url( $tf_s['url'] ); ?>"><?php echo esc_html( $tf_s['title'] ); ?></a>
									<?php else : ?>
										<?php echo esc_html( $tf_s['title'] ); ?>
									<?php endif; ?>
									<span class="tf-slot__sub">
										<?php
										$tf_bits = array();
										if ( 'act' === $tf_s['kind'] && $tf_s['artist'] ) {
											$tf_bits[] = get_post_meta( $tf_s['artist'], 'tf_genre', true );
										} elseif ( isset( $tf_kinds[ $tf_s['kind'] ] ) ) {
											$tf_bits[] = $tf_kinds[ $tf_s['kind'] ];
										}
										if ( $tf_s['length'] ) {
											/* translators: %d: minutes. */
											$tf_bits[] = sprintf( __( '%d min', 'tapaufest' ), $tf_s['length'] );
										}
										echo esc_html( implode( ' · ', array_filter( $tf_bits ) ) );
										?>
									</span>
									<span class="tf-slot__clash" hidden><?php esc_html_e( 'Clashes with another pick', 'tapaufest' ); ?></span>
								</p>
								<?php echo tapaufest_star_button( $tf_s ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped inside. ?>
							</div>
						<?php endif; ?>
					<?php endforeach; ?>
				</section>
			<?php endforeach; ?>
		</div>
	<?php endforeach; ?>

	<div class="tf-mine" role="tabpanel" id="tf-panel-mine" aria-labelledby="tf-tab-mine" hidden>
		<div class="tf-mine__empty" data-tf-mine-empty>
			<p class="tf-mine__big"><?php esc_html_e( 'Your Jadual Aku is empty.', 'tapaufest' ); ?></p>
			<p><?php printf( /* translators: %s: star icon */ esc_html__( 'Tap %s on any set to add it here. Picks stay on this phone or computer.', 'tapaufest' ), tapaufest_icon( 'star', array( 'label' => __( 'the star', 'tapaufest' ) ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
		</div>
		<ol class="tf-mine__list" data-tf-mine-list></ol>
		<div class="tf-ctas tf-ctas--left" data-tf-mine-actions hidden>
			<a class="tf-btn" data-tf-ics href="#"><?php esc_html_e( 'Add to calendar', 'tapaufest' ); ?></a>
			<button type="button" class="tf-btn" data-tf-share><?php esc_html_e( 'Share with your geng', 'tapaufest' ); ?></button>
		</div>
		<p class="tf-share-url" data-tf-share-url hidden></p>
	</div>
</div>
