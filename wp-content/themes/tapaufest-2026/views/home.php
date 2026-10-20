<?php
/**
 * Screen: home. The hero follows the site state; the bands below stay put.
 *
 * @package Tapaufest2026
 */

defined( 'ABSPATH' ) || exit;

$tf_state   = tapaufest_state();
$tf_tiers   = tapaufest_get_tiers();
$tf_from    = $tf_tiers ? min( array_filter( array_map( 'tapaufest_tier_price', $tf_tiers ) ) ?: array( 0 ) ) : 0;
$tf_artists = array_map( 'tapaufest_artist_data', tapaufest_get_artists() );
usort(
	$tf_artists,
	function ( $a, $b ) {
		return (int) $b['headliner'] - (int) $a['headliner'];
	}
);
$tf_start = tapaufest_setting( 'day1' ) . 'T' . tapaufest_setting( 'gates_open' ) . ':00+08:00';
$tf_ms    = max( 0, strtotime( $tf_start ) - time() );
$tf_logo  = tapaufest_logo_url();
?>
<?php if ( 'early' === $tf_state || 'public' === $tf_state ) : ?>
	<div class="tf2-loader" hidden aria-hidden="true">
		<div class="tf2-loader__in">
			<?php if ( $tf_logo ) : ?>
				<img src="<?php echo esc_url( $tf_logo ); ?>" alt="">
			<?php endif; ?>
			<div class="tf2-loader__bar"><i></i></div>
			<div class="tf2-loader__dots"><i></i><i></i><i></i></div>
			<span><?php esc_html_e( 'Tapau-ing the good stuff', 'tapaufest-2026' ); ?></span>
		</div>
	</div>
<?php endif; ?>

<section class="tf2-wrap tf2-hero" data-tf-tickets>
	<?php if ( 'live' === $tf_state ) : ?>
		<?php
		$tf_now  = tapaufest_now();
		$tf_nn   = tapaufest_now_next();
		$tf_main = isset( $tf_nn['PENTAS'] ) ? $tf_nn['PENTAS'] : ( $tf_nn ? reset( $tf_nn ) : null );
		$tf_days = tapaufest_days();
		$tf_idx  = array_search( $tf_now['day'], array_keys( $tf_days ), true );
		?>
		<div class="tf2-two">
			<div class="tf2-stack">
				<p class="tf2-eb">
					<?php
					/* translators: 1: day number, 2: date. */
					echo esc_html( sprintf( __( 'Day %1$d · %2$s', 'tapaufest-2026' ), false === $tf_idx ? 1 : $tf_idx + 1, isset( $tf_days[ $tf_now['day'] ] ) ? $tf_days[ $tf_now['day'] ] : '' ) );
					?>
				</p>
				<h1 class="tf2-h1 tf2-h1--xl tf2-up"><?php esc_html_e( 'It’s happening.', 'tapaufest-2026' ); ?></h1>
				<?php if ( $tf_main && $tf_main['now'] ) : ?>
					<?php echo tf2_now_card( $tf_main['now'], $tf_now ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<?php else : ?>
					<p class="tf2-lead"><?php esc_html_e( 'Between sets right now. Here’s what’s next.', 'tapaufest-2026' ); ?></p>
				<?php endif; ?>
				<div class="tf2-row">
					<?php echo tf2_btn( __( 'Everything on now', 'tapaufest-2026' ), home_url( '/now/' ), 'cili' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<?php echo tf2_btn( __( 'Map', 'tapaufest-2026' ), home_url( '/map/' ), 'paper', array( 'icon' => 'pin' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<?php echo tf2_btn( __( 'Help', 'tapaufest-2026' ), home_url( '/help/' ), 'ghost', array( 'icon' => 'info' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
				<?php if ( $tf_now['preview'] ) : ?>
					<p class="tf2-note"><?php esc_html_e( 'Preview: showing Day 1 at 8:50pm. On festival days this follows the real time.', 'tapaufest-2026' ); ?></p>
				<?php endif; ?>
			</div>
			<div class="tf2-stack tf2-stack--sm">
				<p class="tf2-eb"><?php esc_html_e( 'Up next', 'tapaufest-2026' ); ?></p>
				<?php foreach ( array_slice( $tf_nn, 0, 4, true ) as $tf_stage => $tf_pair ) : ?>
					<?php if ( $tf_pair['next'] ) : ?>
						<div class="tf2-card tf2-pad tf2-between" style="padding:16px 18px;align-items:center">
							<span><b style="display:block;font:800 1.05rem var(--tf2-text)"><?php echo esc_html( html_entity_decode( $tf_pair['next']['title'] ) ); ?></b><span style="font-size:.875rem"><?php echo esc_html( $tf_stage ); ?></span></span>
							<span class="tf2-d" style="font-size:1.4rem;color:var(--tf2-cili)"><?php echo esc_html( $tf_pair['next']['label'] ); ?></span>
						</div>
					<?php endif; ?>
				<?php endforeach; ?>
				<?php foreach ( tapaufest_notices() as $tf_zone => $tf_n ) : ?>
					<?php echo tf2_alert( 'off' === $tf_n['status'] ? 'cili' : 'gold', 'off' === $tf_n['status'] ? 'rain' : 'alert', '<b>' . esc_html( $tf_zone ) . ':</b> ' . esc_html( $tf_n['message'] ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<?php endforeach; ?>
			</div>
		</div>

	<?php elseif ( 'postponed' === $tf_state ) : ?>
		<div class="tf2-two">
			<div class="tf2-stack">
				<p class="tf2-eb"><?php esc_html_e( 'Update from the team', 'tapaufest-2026' ); ?></p>
				<h1 class="tf2-h1 tf2-up"><?php esc_html_e( 'We need a little more time.', 'tapaufest-2026' ); ?></h1>
				<p class="tf2-lead"><?php echo esc_html( tapaufest_alert()['text'] ); ?></p>
				<div class="tf2-row">
					<?php echo tf2_btn( __( 'What happens to my pass', 'tapaufest-2026' ), home_url( '/tickets/' ), 'cili' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<?php echo tf2_btn( __( 'Refund FAQ', 'tapaufest-2026' ), home_url( '/faq/' ), 'ghost' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
			</div>
			<?php echo tf2_pass_safe_card(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</div>

	<?php elseif ( 'recap' === $tf_state ) : ?>
		<?php echo tf2_film( true ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<?php echo tf2_recap_cards(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

	<?php else : ?>
		<div class="tf2-two tf2-two--center">
			<div class="tf2-stack">
				<p class="tf2-eb tf2-up"><?php echo esc_html( tf2_when() . ' · ' . tf2_where() ); ?></p>
				<?php if ( 'early' === $tf_state ) : ?>
					<h1 class="tf2-h1 tf2-h1--xl tf2-up"><?php esc_html_e( 'You’re early. Nice.', 'tapaufest-2026' ); ?></h1>
					<p class="tf2-lead tf2-up">
						<?php
						$tf_wk = array_filter(
							$tf_tiers,
							function ( $t ) {
								return $t['early'] && $t['early'] < $t['regular'];
							}
						);
						$tf_wk = $tf_wk ? reset( $tf_wk ) : null;
						echo esc_html(
							$tf_wk
								/* translators: 1: pass name, 2: price. */
								? sprintf( __( 'Got the waitlist email? Your code unlocks %1$s at %2$s.', 'tapaufest-2026' ), $tf_wk['name'], tapaufest_rm( $tf_wk['early'] ) )
								: __( 'Got the waitlist email? Your code unlocks the early-bird price.', 'tapaufest-2026' )
						);
						?>
					</p>
				<?php elseif ( 'door' === $tf_state ) : ?>
					<h1 class="tf2-h1 tf2-h1--xl tf2-up"><?php esc_html_e( 'See you at the door.', 'tapaufest-2026' ); ?></h1>
					<p class="tf2-lead tf2-up">
						<?php
						/* translators: %s: door price. */
						echo esc_html( sprintf( __( 'Online sales have closed. Tickets at the gate for RM%s while they last.', 'tapaufest-2026' ), tapaufest_setting( 'door_price' ) ) );
						?>
					</p>
				<?php else : ?>
					<h1 class="tf2-h1 tf2-h1--xl tf2-up"><?php esc_html_e( 'Jom tapau the weekend.', 'tapaufest-2026' ); ?></h1>
					<p class="tf2-lead tf2-up"><?php esc_html_e( 'Two days of music, films and forest by the river. Gate opens 9am.', 'tapaufest-2026' ); ?></p>
				<?php endif; ?>

				<?php if ( $tf_ms > 0 ) : ?>
					<div class="tf2-count tf2-up" data-tf2-count="<?php echo esc_attr( $tf_start ); ?>" aria-label="<?php esc_attr_e( 'Time until the gate opens', 'tapaufest-2026' ); ?>">
						<?php foreach ( array( 'd' => array( floor( $tf_ms / DAY_IN_SECONDS ), __( 'Days', 'tapaufest-2026' ) ), 'h' => array( floor( $tf_ms / HOUR_IN_SECONDS ) % 24, __( 'Hrs', 'tapaufest-2026' ) ), 'm' => array( floor( $tf_ms / MINUTE_IN_SECONDS ) % 60, __( 'Min', 'tapaufest-2026' ) ) ) as $tf_u => $tf_c ) : ?>
							<div class="tf2-card tf2-count__box"><b data-unit="<?php echo esc_attr( $tf_u ); ?>"><?php echo esc_html( $tf_c[0] ); ?></b><span><?php echo esc_html( strtoupper( $tf_c[1] ) ); ?></span></div>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>

				<div class="tf2-row tf2-up">
					<?php if ( 'early' === $tf_state ) : ?>
						<?php echo tf2_btn( __( 'Use my code', 'tapaufest-2026' ), home_url( '/tickets/' ), 'cili', array( 'class' => 'tf2-glow' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<?php if ( tapaufest_setting( 'join_url' ) ) : ?>
							<?php echo tf2_btn( __( 'Not on the list? Join free', 'tapaufest-2026' ), tapaufest_setting( 'join_url' ), 'ghost', array( 'external' => true ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<?php endif; ?>
					<?php elseif ( 'door' === $tf_state ) : ?>
						<?php echo tf2_btn( __( 'How to get there', 'tapaufest-2026' ), home_url( '/plan/' ), 'cili' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<?php echo tf2_btn( __( 'Door tickets', 'tapaufest-2026' ), home_url( '/tickets/' ), 'ghost' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<?php else : ?>
						<?php /* translators: %s: lowest price. */ ?>
						<?php echo tf2_btn( $tf_from ? sprintf( __( 'Get tickets · from %s', 'tapaufest-2026' ), tapaufest_rm( $tf_from ) ) : __( 'Get tickets', 'tapaufest-2026' ), home_url( '/tickets/' ), 'cili' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<?php echo tf2_btn( __( 'See the lineup', 'tapaufest-2026' ), home_url( '/lineup/' ), 'ghost' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<?php endif; ?>
				</div>
				<?php if ( 'early' === $tf_state ) : ?>
					<p class="tf2-note"><?php esc_html_e( 'Public sale opens soon. Follow @tapaufest for the date.', 'tapaufest-2026' ); ?></p>
				<?php endif; ?>
			</div>
			<div class="tf2-hero__art">
				<?php echo tf2_photo( 'venue-river', __( 'The river at Chongkak Park', 'tapaufest-2026' ), 'tf2-photo--shadow tf2-zoom' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<div class="tf2-card tf2-card--gold tf2-hero__sticker"><b><?php esc_html_e( 'Our new playground', 'tapaufest-2026' ); ?></b><span><?php echo esc_html( tf2_where() ); ?></span></div>
				<?php
				$tf_dkeys = array_keys( tapaufest_days() );
				if ( $tf_dkeys ) :
					$tf_tz = tapaufest_timezone();
					$tf_d1 = strtotime( $tf_dkeys[0] . ' 12:00:00' );
					$tf_d2 = strtotime( end( $tf_dkeys ) . ' 12:00:00' );
					?>
					<div class="tf2-card tf2-card--gold tf2-hero__date tf2-drop" aria-hidden="true"><b><?php echo esc_html( wp_date( 'j', $tf_d1, $tf_tz ) . ( $tf_d1 !== $tf_d2 ? ' & ' . wp_date( 'j', $tf_d2, $tf_tz ) : '' ) ); ?></b><span><?php echo esc_html( wp_date( 'M Y', $tf_d2, $tf_tz ) ); ?></span></div>
				<?php endif; ?>
			</div>
		</div>
	<?php endif; ?>
</section>

<?php if ( 'recap' !== $tf_state && $tf_artists ) : ?>
<section class="tf2-band">
	<div class="tf2-wrap tf2-sec">
		<div class="tf2-between" style="margin-bottom:32px">
			<div class="tf2-stack tf2-stack--sm"><p class="tf2-eb"><?php esc_html_e( 'Tentative lineup', 'tapaufest-2026' ); ?></p><h2 class="tf2-h2"><?php esc_html_e( 'Who’s playing', 'tapaufest-2026' ); ?></h2></div>
			<?php /* translators: %d: number of acts. */ ?>
			<a href="<?php echo esc_url( home_url( '/lineup/' ) ); ?>" style="font:800 1rem var(--tf2-text)"><?php echo esc_html( sprintf( __( 'All %d acts', 'tapaufest-2026' ), count( $tf_artists ) ) ); ?></a>
		</div>
		<div class="tf2-auto tf2-rail" data-tf2-reveal>
			<?php
			foreach ( array_slice( $tf_artists, 0, 6 ) as $tf_a ) {
				echo tf2_artist_card( $tf_a ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
			?>
		</div>
		<p class="tf2-note" style="margin-top:20px"><?php esc_html_e( 'Subject to change.', 'tapaufest-2026' ); ?></p>
	</div>
</section>
<?php endif; ?>

<?php if ( 'recap' !== $tf_state ) : ?>
<section class="tf2-wrap tf2-sec">
	<?php echo tf2_head( __( 'What’s on', 'tapaufest-2026' ), __( 'Pick your corner', 'tapaufest-2026' ), '', 'h2' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	<?php echo tf2_zone_grid( 6 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	<p style="margin-top:24px"><a href="<?php echo esc_url( home_url( '/experience/' ) ); ?>" style="font:800 1rem var(--tf2-text)"><?php esc_html_e( 'Everything that’s on', 'tapaufest-2026' ); ?></a></p>
</section>
<?php endif; ?>

<?php if ( in_array( $tf_state, array( 'early', 'public' ), true ) && $tf_tiers ) : ?>
<section class="tf2-band">
	<div class="tf2-wrap tf2-sec">
		<?php echo tf2_head( __( 'Passes', 'tapaufest-2026' ), __( 'One pass, every stage', 'tapaufest-2026' ), '', 'h2' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<div class="tf2-auto tf2-passes" style="grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:28px" data-tf2-reveal>
			<?php foreach ( $tf_tiers as $tf_t ) : ?>
				<div class="tf2-stack tf2-stack--sm">
					<?php echo tf2_tier( $tf_t, 'card' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<?php if ( $tf_t['soldout'] ) : ?>
						<span class="tf2-btn tf2-btn--off tf2-btn--block" aria-disabled="true"><?php esc_html_e( 'Sold out', 'tapaufest-2026' ); ?></span>
					<?php else : ?>
						<?php echo tf2_btn( 'early' === $tf_state && $tf_t['early'] && $tf_t['early'] < $tf_t['regular'] ? __( 'Use my code', 'tapaufest-2026' ) : __( 'Choose', 'tapaufest-2026' ), add_query_arg( 'tier', $tf_t['id'], home_url( '/tickets/' ) ), 'cili', array( 'class' => 'tf2-btn--block' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
<?php endif; ?>

<?php if ( 'recap' !== $tf_state && 'postponed' !== $tf_state ) : ?>
<section class="tf2-wrap tf2-sec tf2-getthere">
	<div class="tf2-two tf2-two--center">
		<?php echo tf2_photo( 'venue-forest', __( 'Festival-goers under the trees at Chongkak Park', 'tapaufest-2026' ), '' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<div class="tf2-stack">
			<p class="tf2-eb"><?php esc_html_e( 'Getting there', 'tapaufest-2026' ); ?></p>
			<h2 class="tf2-h2"><?php esc_html_e( 'MRT, then our shuttle.', 'tapaufest-2026' ); ?></h2>
			<p class="tf2-lead"><?php esc_html_e( 'Or drive in. Parking details land closer to the day.', 'tapaufest-2026' ); ?></p>
			<div><?php echo tf2_btn( __( 'Plan my route', 'tapaufest-2026' ), home_url( '/plan/' ), 'paper' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
		</div>
	</div>
</section>
<?php endif; ?>
