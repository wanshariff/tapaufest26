<?php
/**
 * Screen: Jadual Aku, the personal plan. Picks live in this browser.
 *
 * Covers: empty plan, clashes, time changes since you starred a set, sets
 * called off by the crew, and plans shared by a friend (?jadual=1.2.3).
 *
 * @package Tapaufest2026
 */

defined( 'ABSPATH' ) || exit;

$tf_ics = function_exists( 'rest_url' ) ? rest_url( 'tapaufest/v1/ics' ) : '';
?>
<div class="tf2-wrap tf2-main" data-tf2-plan>
	<?php echo tf2_head( __( 'Saved on this phone · no account', 'tapaufest-2026' ), __( 'Jadual Aku', 'tapaufest-2026' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

	<section data-tf2-import hidden>
		<div class="tf2-two tf2-two--wide">
			<div class="tf2-stack tf2-stack--sm">
				<p class="tf2-lead" style="margin-bottom:8px"><?php esc_html_e( 'A friend shared their sets. Pick the ones you want.', 'tapaufest-2026' ); ?></p>
				<div class="tf2-stack tf2-stack--sm" data-tf2-import-rows></div>
			</div>
			<div class="tf2-card tf2-pad tf2-stack tf2-stack--sm tf2-sticky">
				<p style="font:800 1.1rem var(--tf2-text)"><?php esc_html_e( 'Add to your plan?', 'tapaufest-2026' ); ?></p>
				<p class="tf2-note" data-tf2-import-note></p>
				<button type="button" class="tf2-btn tf2-btn--cili" data-tf2-import-yes><span><?php esc_html_e( 'Add sets', 'tapaufest-2026' ); ?></span></button>
				<button type="button" class="tf2-btn tf2-btn--paper" data-tf2-import-no><span><?php esc_html_e( 'No thanks', 'tapaufest-2026' ); ?></span></button>
				<p class="tf2-note"><?php esc_html_e( 'Nothing in your plan gets removed.', 'tapaufest-2026' ); ?></p>
			</div>
		</div>
	</section>

	<div data-tf2-plan-main>
		<div class="tf2-alert tf2-alert--gold" data-tf2-plan-notice hidden style="margin-bottom:20px">
			<?php echo tf2_icon( 'clock' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<div style="flex:1"><span data-tf2-notice-text></span></div>
			<button type="button" class="tf2-btn tf2-btn--paper" data-tf2-notice-ok style="min-height:40px;padding:0 14px"><span><?php esc_html_e( 'Got it', 'tapaufest-2026' ); ?></span></button>
		</div>

		<div class="tf2-two tf2-two--wide" data-tf2-plan-empty>
			<?php echo tf2_empty( 'star', __( 'Nothing starred yet.', 'tapaufest-2026' ), __( 'Tap the star on any act or session. Clashes show up here.', 'tapaufest-2026' ), array( tf2_btn( __( 'Browse the lineup', 'tapaufest-2026' ), home_url( '/lineup/' ), 'cili' ), tf2_btn( __( 'Open set times', 'tapaufest-2026' ), home_url( '/timetable/' ), 'paper' ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<form class="tf2-card tf2-pad tf2-stack tf2-stack--sm" data-tf2-paste novalidate>
				<p style="font:800 1.05rem var(--tf2-text)"><?php esc_html_e( 'Got a friend’s plan?', 'tapaufest-2026' ); ?></p>
				<label class="tf2-field" for="tf2-paste"><?php esc_html_e( 'Paste their link', 'tapaufest-2026' ); ?>
					<input id="tf2-paste" type="url" placeholder="<?php echo esc_attr( home_url( '/jadual-aku/?jadual=…' ) ); ?>">
				</label>
				<p class="tf2-field__err" data-tf2-paste-err hidden><?php esc_html_e( 'That isn’t a Jadual Aku link. It ends in ?jadual= and some numbers.', 'tapaufest-2026' ); ?></p>
				<button type="submit" class="tf2-btn tf2-btn--cili"><span><?php esc_html_e( 'Open plan', 'tapaufest-2026' ); ?></span></button>
			</form>
		</div>

		<div class="tf2-two tf2-two--wide">
			<div class="tf2-stack tf2-stack--sm">
				<div class="tf2-row" data-tf2-plan-tabs role="group" aria-label="<?php esc_attr_e( 'Day', 'tapaufest-2026' ); ?>" hidden></div>
				<ol class="tf2-plan__day" data-tf2-plan-list style="list-style:none;padding:0" hidden></ol>
			</div>
			<aside class="tf2-stack tf2-sticky" data-tf2-plan-aside hidden>
				<div class="tf2-card tf2-card--gold tf2-pad tf2-stack tf2-stack--sm" data-tf2-clashcard hidden>
					<p class="tf2-h3" data-tf2-clashcount></p>
					<p data-tf2-clashtext></p>
					<?php echo tf2_btn( __( 'Sort it on set times', 'tapaufest-2026' ), home_url( '/timetable/' ), 'paper' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
				<div class="tf2-card tf2-pad tf2-stack tf2-stack--sm">
					<p style="font:800 1.05rem var(--tf2-text)"><?php esc_html_e( 'Bring the geng', 'tapaufest-2026' ); ?></p>
					<button type="button" class="tf2-btn tf2-btn--cili" data-tf2-share><?php echo tf2_icon( 'share' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><span><?php esc_html_e( 'Copy share link', 'tapaufest-2026' ); ?></span></button>
					<?php if ( $tf_ics ) : ?>
						<a class="tf2-btn tf2-btn--paper" data-tf2-ics data-base="<?php echo esc_url( $tf_ics ); ?>" href="<?php echo esc_url( $tf_ics ); ?>"><?php echo tf2_icon( 'calendar' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><span><?php esc_html_e( 'Add to calendar', 'tapaufest-2026' ); ?></span></a>
					<?php endif; ?>
				</div>
				<p class="tf2-note"><?php esc_html_e( 'Times change? We update your plan and flag new clashes. Works offline once opened.', 'tapaufest-2026' ); ?></p>
			</aside>
		</div>
	</div>
	<script type="application/json" id="tf2-sessions"><?php echo wp_json_encode( tapaufest_sessions_json() ); ?></script>
</div>
