<?php
/**
 * Screen: getting there. Pick how you're coming, then pack.
 *
 * @package Tapaufest2026
 */

defined( 'ABSPATH' ) || exit;

$tf_maps  = tapaufest_setting( 'maps_url' );
$tf_modes = array(
	'mrt'  => array(
		__( 'MRT + shuttle', 'tapaufest-2026' ),
		array(
			array( __( 'MRT to Sri Raya', 'tapaufest-2026' ), __( 'Take the MRT Kajang Line to MRT Sri Raya.', 'tapaufest-2026' ) ),
			array( __( 'Hop on our shuttle', 'tapaufest-2026' ), __( 'Official TAPAUfest shuttles run from the station straight to the gate.', 'tapaufest-2026' ) ),
			array( __( 'Off at the gate', 'tapaufest-2026' ), __( 'Wristband counter is right there. Gate opens 9am.', 'tapaufest-2026' ) ),
		),
		__( 'Shuttle times land closer to the day.', 'tapaufest-2026' ),
	),
	'drive' => array(
		__( 'Drive', 'tapaufest-2026' ),
		array(
			array( __( 'Head to Hulu Langat', 'tapaufest-2026' ), __( 'About an hour from Kuala Lumpur. Set your map to Chongkak Park & Resort.', 'tapaufest-2026' ) ),
			array( __( 'Follow the TAPAUfest signs', 'tapaufest-2026' ), __( 'Crew in gold shirts wave you in.', 'tapaufest-2026' ) ),
			array( __( 'Park and walk', 'tapaufest-2026' ), __( 'Short walk to the gate.', 'tapaufest-2026' ) ),
		),
		__( 'Parking details land closer to the day. Carpool if you can.', 'tapaufest-2026' ),
	),
	'grab' => array(
		__( 'Grab', 'tapaufest-2026' ),
		array(
			array( __( 'Drop-off at Chongkak Park', 'tapaufest-2026' ), __( 'Use the official drop-off point. Pin coming soon.', 'tapaufest-2026' ) ),
			array( __( 'Walk to the gate', 'tapaufest-2026' ), __( 'A couple of minutes.', 'tapaufest-2026' ) ),
			array( __( 'Going home', 'tapaufest-2026' ), __( 'Signal drops near the river, so book your ride home early.', 'tapaufest-2026' ) ),
		),
		__( 'Coming from Ipoh, Penang or JB? Find a carpool in the comments on our latest Instagram post.', 'tapaufest-2026' ),
	),
);
$tf_pack = array( __( 'Your e-ticket, saved offline', 'tapaufest-2026' ), __( 'Raincoat or poncho', 'tapaufest-2026' ), __( 'Refillable water bottle', 'tapaufest-2026' ), __( 'Power bank', 'tapaufest-2026' ), __( 'Torchlight for the campsite', 'tapaufest-2026' ), __( 'Insect repellent', 'tapaufest-2026' ), __( 'Some cash for smaller stalls', 'tapaufest-2026' ), __( 'Slippers that can get wet', 'tapaufest-2026' ) );
$tf_no   = array( __( 'Glass and bottles', 'tapaufest-2026' ), __( 'Cigarettes and vapes', 'tapaufest-2026' ), __( 'Professional recording gear', 'tapaufest-2026' ), __( 'Weapons, lasers, fireworks and flares', 'tapaufest-2026' ), __( 'Animals', 'tapaufest-2026' ) );
$tf_stay = array(
	array( 'santuy-campsite', __( 'Santuy Campsite', 'tapaufest-2026' ), __( 'Camp two nights inside the festival grounds. The bonfire runs late. Booked separately.', 'tapaufest-2026' ), __( 'Booking link soon', 'tapaufest-2026' ) ),
	array( 'campsite', __( 'Nearby campsites', 'tapaufest-2026' ), __( 'Locally run campsites close to the venue. Book with them directly.', 'tapaufest-2026' ), __( 'List coming soon', 'tapaufest-2026' ) ),
	array( 'venue-forest', __( 'Chalets', 'tapaufest-2026' ), __( 'Chalet stays at Chongkak Park & Resort, plus homestays around Hulu Langat.', 'tapaufest-2026' ), __( 'Partner deals soon', 'tapaufest-2026' ) ),
);
?>
<div class="tf2-wrap tf2-main">
	<?php echo tf2_head( tapaufest_setting( 'venue' ), __( 'Getting there', 'tapaufest-2026' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	<div class="tf2-two" data-tf2-tabs>
		<div class="tf2-stack">
			<div class="tf2-row" role="tablist" aria-label="<?php esc_attr_e( 'How you’re coming', 'tapaufest-2026' ); ?>" style="gap:10px">
				<?php $tf_first = true; ?>
				<?php foreach ( $tf_modes as $tf_k => $tf_m ) : ?>
					<button type="button" class="tf2-chip" role="tab" id="tf2-tab-<?php echo esc_attr( $tf_k ); ?>" data-tf2-tab="<?php echo esc_attr( $tf_k ); ?>" aria-controls="tf2-mode-<?php echo esc_attr( $tf_k ); ?>" aria-selected="<?php echo $tf_first ? 'true' : 'false'; ?>"><?php echo esc_html( $tf_m[0] ); ?></button>
					<?php $tf_first = false; ?>
				<?php endforeach; ?>
			</div>
			<?php $tf_first = true; ?>
			<?php foreach ( $tf_modes as $tf_k => $tf_m ) : ?>
				<div class="tf2-stack tf2-stack--sm tf2-route-panel" role="tabpanel" id="tf2-mode-<?php echo esc_attr( $tf_k ); ?>" aria-labelledby="tf2-tab-<?php echo esc_attr( $tf_k ); ?>" data-tf2-panel="<?php echo esc_attr( $tf_k ); ?>"<?php echo $tf_first ? '' : ' hidden'; ?>>
					<?php foreach ( $tf_m[1] as $tf_i => $tf_step ) : ?>
						<div class="tf2-card tf2-route-step tf2-up" style="animation-delay:<?php echo (int) $tf_i * 60; ?>ms"><b><?php echo (int) $tf_i + 1; ?></b><span><strong><?php echo esc_html( $tf_step[0] ); ?></strong><?php echo esc_html( $tf_step[1] ); ?></span></div>
					<?php endforeach; ?>
					<?php echo tf2_alert( 'gold', 'info', esc_html( $tf_m[2] ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
				<?php $tf_first = false; ?>
			<?php endforeach; ?>
		</div>
		<div class="tf2-stack">
			<?php echo tf2_photo( 'venue-forest', __( 'Under the trees at Chongkak Park', 'tapaufest-2026' ), 'tf2-photo--shadow' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<div class="tf2-row">
				<?php echo tf2_btn( __( 'Open in Google Maps', 'tapaufest-2026' ), $tf_maps, 'paper', array( 'icon' => 'pin', 'external' => true ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<?php echo tf2_btn( __( 'Festival map', 'tapaufest-2026' ), home_url( '/map/' ), 'ghost' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
		</div>
	</div>

	<section style="margin-top:72px" id="pack" data-tf2-pack>
		<?php echo tf2_head( __( 'Pack list', 'tapaufest-2026' ), __( 'Pack light, pack smart', 'tapaufest-2026' ), __( 'Ticks stay on this phone.', 'tapaufest-2026' ), 'h2' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<div class="tf2-pack">
			<?php foreach ( $tf_pack as $tf_i => $tf_item ) : ?>
				<label class="tf2-card"><input type="checkbox" value="<?php echo esc_attr( sanitize_title( $tf_item ) ); ?>"><span><?php echo esc_html( $tf_item ); ?></span></label>
			<?php endforeach; ?>
		</div>
		<p style="margin-top:16px;font:700 .9rem var(--tf2-text);color:var(--tf2-gold)" data-tf2-packed aria-live="polite"></p>
		<div style="margin-top:40px;max-width:560px">
			<p class="tf2-eb"><?php esc_html_e( 'Leave at home', 'tapaufest-2026' ); ?></p>
			<ul class="tf2-nolist" style="list-style:none;padding:0;margin-top:10px">
				<?php foreach ( $tf_no as $tf_item ) : ?>
					<li><?php echo tf2_icon( 'close' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php echo esc_html( $tf_item ); ?></li>
				<?php endforeach; ?>
			</ul>
		</div>
	</section>
</div>
<section class="tf2-band">
	<div class="tf2-wrap tf2-sec">
		<?php echo tf2_head( __( 'Where to sleep', 'tapaufest-2026' ), __( 'Camp or check in', 'tapaufest-2026' ), '', 'h2' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<div class="tf2-auto tf2-auto--lg">
			<?php foreach ( $tf_stay as $tf_s ) : ?>
				<div class="tf2-card" style="overflow:hidden">
					<img src="<?php echo esc_url( tapaufest_photo_url( $tf_s[0] ) ); ?>" alt="" loading="lazy" style="width:100%;height:200px;object-fit:cover;display:block;border-bottom:3px solid var(--tf2-ink)">
					<div class="tf2-pad tf2-stack tf2-stack--sm"><p class="tf2-h3"><?php echo esc_html( $tf_s[1] ); ?></p><p><?php echo esc_html( $tf_s[2] ); ?></p><span class="tf2-tag tf2-tag--gold"><?php echo esc_html( $tf_s[3] ); ?></span></div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
