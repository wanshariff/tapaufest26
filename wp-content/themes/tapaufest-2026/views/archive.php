<?php
/**
 * Screen: past editions, since 2014.
 *
 * @package Tapaufest2026
 */

defined( 'ABSPATH' ) || exit;

$tf_editions = array(
	array( '2026', __( '12 & 13 Dec · Chongkak Park & Resort, Hulu Langat, Selangor', 'tapaufest-2026' ), __( 'A new era at a new home: an art x nature festival under the forest canopy.', 'tapaufest-2026' ) ),
	array( '2025', __( '23 & 24 Aug · Resort Tasik Raban, Lenggong, Perak', 'tapaufest-2026' ), __( 'The first ASEAN edition, bringing together indie creatives and community from 9 South East Asian nations. 2,000+ festival-goers. Voted Best Festival in Juice’s Best of 2025.', 'tapaufest-2026' ) ),
	array( '2024', __( 'Postponed', 'tapaufest-2026' ), __( 'Postponed for lack of funding.', 'tapaufest-2026' ) ),
	array( '2023', __( '7 & 8 Oct · Resort Tasik Raban, Lenggong, Perak', 'tapaufest-2026' ), __( '#SerumpunBerpesta with Zee Avi, Nadin Amizah, Rub of Rub, Jemson, Force Vomit, Door Plant and Bayangan, plus Amir Jahari at the campsite. BENGKEL and LAYAR co-curated with KakiSeni. Hammocks, biking and a community cookout.', 'tapaufest-2026' ) ),
	array( '2022', __( 'Resort Tasik Raban, Lenggong, Perak', 'tapaufest-2026' ), __( 'The first Lenggong edition.', 'tapaufest-2026' ) ),
	array( '2021', __( '30 Oct · Online', 'tapaufest-2026' ), __( 'During lockdown, a regional online festival: 14 hours streamed on tapau.asia with Zee Avi, Sore, The Panturas, Force Vomit, Subsonic Eye and more from Malaysia, Indonesia and Singapore. Hosted by Iedil Dzuhrie and Sharifah Amani.', 'tapaufest-2026' ) ),
	array( '2019', __( 'The Gasket Alley, Petaling Jaya', 'tapaufest-2026' ), __( 'Disqovr presents TAPAUfest: Hacktick, Midnight Fusic, Milo Dinosaur and more, with a local food row and indie market. With Vans, Marshall, Yoodo, Snek and Rakita.', 'tapaufest-2026' ) ),
	array( '2016', __( '31 Dec · Zebra Square, Kuala Lumpur', 'tapaufest-2026' ), __( 'A New Year’s Eve edition with 10 local acts.', 'tapaufest-2026' ) ),
	array( '2014', __( 'Botanical Garden, Penang', 'tapaufest-2026' ), __( 'South East Asia’s first crowdfunded festival, and the biggest crowdfunded project at the time.', 'tapaufest-2026' ) ),
);
$tf_photos = array(
	'2026' => 'venue-river',
	'2025' => 'pentas-day',
	'2023' => 'santuy-campsite',
);
?>
<div class="tf2-wrap tf2-main">
	<?php echo tf2_head( __( 'Since 2014', 'tapaufest-2026' ), __( 'Past editions', 'tapaufest-2026' ), __( 'From a crowdfunded day in Penang to a weekend by the river.', 'tapaufest-2026' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	<ol class="tf2-stack" style="list-style:none;padding:0;gap:18px">
		<?php foreach ( $tf_editions as $tf_i => $tf_e ) : ?>
			<li class="tf2-card tf2-up" style="display:grid;grid-template-columns:<?php echo isset( $tf_photos[ $tf_e[0] ] ) ? 'minmax(0,1fr) minmax(0,1.6fr)' : '1fr'; ?>;overflow:hidden;animation-delay:<?php echo (int) min( $tf_i, 6 ) * 40; ?>ms">
				<?php if ( isset( $tf_photos[ $tf_e[0] ] ) ) : ?>
					<img src="<?php echo esc_url( tapaufest_photo_url( $tf_photos[ $tf_e[0] ] ) ); ?>" alt="" loading="lazy" style="width:100%;height:100%;min-height:200px;object-fit:cover;border-right:3px solid var(--tf2-ink)">
				<?php endif; ?>
				<div class="tf2-pad--lg tf2-stack tf2-stack--sm">
					<span class="tf2-d" style="font-size:3rem;color:var(--tf2-cili)"><?php echo esc_html( $tf_e[0] ); ?></span>
					<b style="font:800 1.05rem var(--tf2-text)"><?php echo esc_html( $tf_e[1] ); ?></b>
					<p style="line-height:1.55;max-width:60ch"><?php echo esc_html( $tf_e[2] ); ?></p>
					<?php if ( '2025' === $tf_e[0] ) : ?>
						<div><?php echo tf2_btn( __( '2025 photos and aftermovie', 'tapaufest-2026' ), home_url( '/recap/' ), 'paper', array( 'icon' => 'camera' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
					<?php endif; ?>
				</div>
			</li>
		<?php endforeach; ?>
	</ol>
</div>
<style>@media (max-width:700px){.tf2-screen--archive li.tf2-card{grid-template-columns:1fr!important}.tf2-screen--archive li.tf2-card img{border-right:0!important;border-bottom:3px solid var(--tf2-ink);max-height:220px}}</style>
