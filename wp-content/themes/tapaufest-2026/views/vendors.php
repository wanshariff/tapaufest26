<?php
/**
 * Screen: Makan & Pasar. WARUNG and PASAR stalls, with a quick filter.
 *
 * @package Tapaufest2026
 */

defined( 'ABSPATH' ) || exit;

$tf_zones = array(
	'warung' => array( 'WARUNG', __( 'Food and drinks. Halal unless marked. Free water refills next door.', 'tapaufest-2026' ), array( 'warung-forest', 'warung', 'water-station', 'crowd', 'campsite' ) ),
	'pasar'  => array( 'PASAR', __( 'Records, merch, art and crafts, with DJs spinning while you shop.', 'tapaufest-2026' ), array( 'pasar-dj', 'pasar', 'dj', 'bengkel-sharing' ) ),
);
$tf_all   = array();
$tf_tags  = array();
foreach ( array_keys( $tf_zones ) as $tf_z ) {
	$tf_all[ $tf_z ] = get_posts(
		array(
			'post_type'      => 'tf_vendor',
			'posts_per_page' => -1,
			'meta_key'       => 'tf_vendor_type', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_query_meta_key
			'meta_value'     => $tf_z, // phpcs:ignore WordPress.DB.SlowDBQuery.slow_query_meta_value
			'orderby'        => array(
				'menu_order' => 'ASC',
				'title'      => 'ASC',
			),
		)
	);
	foreach ( $tf_all[ $tf_z ] as $tf_v ) {
		foreach ( array_filter( array_map( 'trim', explode( ',', (string) get_post_meta( $tf_v->ID, 'tf_tags', true ) ) ) ) as $tf_t ) {
			$tf_tags[ strtolower( $tf_t ) ] = $tf_t;
		}
	}
}
$tf_prices = tapaufest_price_ranges();
$tf_any_sample = false;
?>
<div class="tf2-wrap tf2-main" data-tf2-tabs data-tf2-vfilter>
	<div class="tf2-between">
		<?php echo tf2_head( __( 'This year’s stalls are announced in November', 'tapaufest-2026' ), __( 'Makan & Pasar', 'tapaufest-2026' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<div class="tf2-row" role="tablist" aria-label="<?php esc_attr_e( 'Zone', 'tapaufest-2026' ); ?>" style="margin-bottom:32px">
			<?php foreach ( $tf_zones as $tf_z => $tf_info ) : ?>
				<button type="button" class="tf2-chip" role="tab" id="tf2-tab-<?php echo esc_attr( $tf_z ); ?>" data-tf2-tab="<?php echo esc_attr( $tf_z ); ?>" aria-controls="tf2-zone-<?php echo esc_attr( $tf_z ); ?>" aria-selected="<?php echo 'warung' === $tf_z ? 'true' : 'false'; ?>"><?php echo esc_html( $tf_info[0] ); ?> · <?php echo count( $tf_all[ $tf_z ] ); ?></button>
			<?php endforeach; ?>
		</div>
	</div>
	<?php if ( $tf_tags ) : ?>
		<div class="tf2-row" style="gap:8px;margin-bottom:24px" role="group" aria-label="<?php esc_attr_e( 'Only show', 'tapaufest-2026' ); ?>">
			<?php foreach ( array_slice( $tf_tags, 0, 6 ) as $tf_t ) : ?>
				<button type="button" class="tf2-chip" data-tf2-vtag="<?php echo esc_attr( $tf_t ); ?>" aria-pressed="false"><?php echo esc_html( $tf_t ); ?></button>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
	<?php foreach ( $tf_zones as $tf_z => $tf_info ) : ?>
		<section role="tabpanel" id="tf2-zone-<?php echo esc_attr( $tf_z ); ?>" aria-labelledby="tf2-tab-<?php echo esc_attr( $tf_z ); ?>" data-tf2-panel="<?php echo esc_attr( $tf_z ); ?>"<?php echo 'warung' === $tf_z ? '' : ' hidden'; ?>>
			<p class="tf2-lead" style="margin-bottom:24px"><?php echo esc_html( $tf_info[1] ); ?></p>
			<?php if ( ! $tf_all[ $tf_z ] ) : ?>
				<?php echo tf2_empty( 'food', __( 'Stalls land in November.', 'tapaufest-2026' ), __( 'Want one of them to be yours?', 'tapaufest-2026' ), array( tf2_btn( __( 'Sell with us', 'tapaufest-2026' ), home_url( '/sell/' ), 'cili' ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<?php else : ?>
				<div class="tf2-auto">
					<?php foreach ( $tf_all[ $tf_z ] as $tf_vi => $tf_v ) : ?>
						<?php
						$tf_year   = (string) get_post_meta( $tf_v->ID, 'tf_year', true );
						$tf_sample = 'sample' === strtolower( $tf_year );
						$tf_any_sample = $tf_any_sample || $tf_sample;
						$tf_note   = (string) get_post_meta( $tf_v->ID, 'tf_note', true );
						$tf_price  = (string) get_post_meta( $tf_v->ID, 'tf_price', true );
						$tf_tg     = (string) get_post_meta( $tf_v->ID, 'tf_tags', true );
						$tf_img    = get_the_post_thumbnail( $tf_v->ID, 'medium_large', array( 'alt' => '' ) );
						$tf_img    = $tf_img ? $tf_img : '<img src="' . esc_url( tapaufest_photo_url( $tf_info[2][ $tf_vi % count( $tf_info[2] ) ] ) ) . '" alt="" loading="lazy">';
						?>
						<a class="tf2-card tf2-lift tf2-vendor" href="<?php echo esc_url( get_permalink( $tf_v ) ); ?>" data-tags="<?php echo esc_attr( $tf_tg ); ?>">
							<span class="tf2-vendor__img"><?php echo $tf_img; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php echo $tf_sample ? '<span class="tf2-tag">' . esc_html__( 'Sample', 'tapaufest-2026' ) . '</span>' : ''; ?></span>
							<span class="tf2-vendor__body"><b><?php echo esc_html( get_the_title( $tf_v ) ); ?></b><span><?php echo esc_html( implode( ' · ', array_filter( array( $tf_note, isset( $tf_prices[ $tf_price ] ) ? $tf_prices[ $tf_price ] : '' ) ) ) ); ?></span></span>
						</a>
					<?php endforeach; ?>
				</div>
				<?php echo tf2_empty( 'search', __( 'No stalls with that tag here.', 'tapaufest-2026' ), __( 'Try the other zone or clear the filter.', 'tapaufest-2026' ), array(), 'data-tf2-vnone hidden' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<?php endif; ?>
		</section>
	<?php endforeach; ?>
	<?php if ( $tf_any_sample ) : ?>
		<p class="tf2-note" style="margin-top:20px"><?php esc_html_e( 'Stalls marked Sample are based on past editions, to show what’s coming. They aren’t confirmed for this year.', 'tapaufest-2026' ); ?></p>
	<?php endif; ?>
	<div class="tf2-card tf2-card--gold tf2-pad--lg tf2-between" style="margin-top:48px;align-items:center">
		<span class="tf2-h3" style="font-size:1.9rem"><?php esc_html_e( 'Got a stall? Sell with us.', 'tapaufest-2026' ); ?></span>
		<?php echo tf2_btn( __( 'Apply, no account needed', 'tapaufest-2026' ), home_url( '/sell/' ), 'cili' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</div>
</div>
