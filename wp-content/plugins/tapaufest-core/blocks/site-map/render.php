<?php
/**
 * Interactive site map: a designed basemap with pins. Hover a pin (or tap it)
 * for a place card with photo, details, what's on next and who's selling.
 * Without JavaScript the basemap shows as a picture with the cards listed below.
 *
 * @package TapaufestCore
 */

defined( 'ABSPATH' ) || exit;

wp_enqueue_style( 'tapaufest-leaflet' );
wp_enqueue_script( 'tapaufest-map' );

$tf_spots = tapaufest_map_spots();
$tf_cats  = tapaufest_map_categories();
$tf_uid   = wp_unique_id( 'tf-map-' );
$tf_pins  = array();
foreach ( $tf_spots as $tf_key => $tf_s ) {
	$tf_sh     = $tf_s['shape'];
	$tf_x      = 'rect' === $tf_sh[0] ? $tf_sh[1] + $tf_sh[3] / 2 : $tf_sh[1];
	$tf_y      = 'rect' === $tf_sh[0] ? $tf_sh[2] + $tf_sh[4] * ( $tf_sh[4] > $tf_sh[3] * 1.6 ? 0.3 : 0.5 ) : $tf_sh[2];
	$tf_pins[] = array(
		'key'   => $tf_key,
		'cat'   => $tf_s['cat'],
		'label' => $tf_s['label'],
		'x'     => round( $tf_x ),
		'y'     => round( $tf_y ),
		'color' => isset( $tf_cats[ $tf_s['cat'] ] ) ? $tf_cats[ $tf_s['cat'] ][1] : '#5A3A20',
		'icon'  => tapaufest_map_pin_icon( $tf_s['cat'], isset( $tf_s['icon'] ) ? $tf_s['icon'] : '' ),
		'small' => ! empty( $tf_s['icon'] ),
	);
}
$tf_used = array_unique( wp_list_pluck( $tf_pins, 'cat' ) );

$tf_vendor_names = function ( $type ) {
	return wp_list_pluck(
		get_posts(
			array(
				'post_type'      => 'tf_vendor',
				'posts_per_page' => 6,
				'meta_key'       => 'tf_vendor_type', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_query_meta_key
				'meta_value'     => $type, // phpcs:ignore WordPress.DB.SlowDBQuery.slow_query_meta_value
				'orderby'        => 'menu_order',
				'order'          => 'ASC',
			)
		),
		'post_title'
	);
};
?>
<div <?php echo get_block_wrapper_attributes( array( 'class' => 'tf-map' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> data-tf-map data-pins="<?php echo esc_attr( wp_json_encode( $tf_pins ) ); ?>">
	<div class="tf-map__filters" role="group" aria-label="<?php esc_attr_e( 'Show on the map', 'tapaufest' ); ?>">
		<button type="button" class="tf-map__filter" data-cat="" aria-pressed="true"><?php esc_html_e( 'Everything', 'tapaufest' ); ?></button>
		<?php foreach ( $tf_cats as $tf_slug => $tf_c ) : ?>
			<?php if ( ! in_array( $tf_slug, $tf_used, true ) ) { continue; } ?>
			<button type="button" class="tf-map__filter" data-cat="<?php echo esc_attr( $tf_slug ); ?>" aria-pressed="false"><i style="background:<?php echo esc_attr( $tf_c[1] ); ?>" aria-hidden="true"></i><?php echo esc_html( $tf_c[0] ); ?></button>
		<?php endforeach; ?>
	</div>

	<div class="tf-map__canvas" id="<?php echo esc_attr( $tf_uid ); ?>">
		<?php echo tapaufest_map_basemap_svg( $tf_spots ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped inside. ?>
	</div>
	<p class="tf-map__note"><?php esc_html_e( 'Draft layout, not to scale. Drag to move, use + and − to zoom.', 'tapaufest' ); ?></p>

	<div class="tf-map__cards">
		<?php foreach ( $tf_spots as $tf_key => $tf_s ) : ?>
			<?php
			$tf_photo = ( ! empty( $tf_s['photo'] ) && function_exists( 'tapaufest_photo_url' ) ) ? tapaufest_photo_url( $tf_s['photo'] ) : '';
			$tf_next  = ! empty( $tf_s['stage'] ) ? tapaufest_map_stage_sessions( $tf_s['stage'] ) : array();
			$tf_vend  = ! empty( $tf_s['vendors'] ) ? $tf_vendor_names( $tf_s['vendors'] ) : array();
			$tf_col   = isset( $tf_cats[ $tf_s['cat'] ] ) ? $tf_cats[ $tf_s['cat'] ][1] : '#5A3A20';
			?>
			<article class="tf-map__card" data-card="<?php echo esc_attr( $tf_key ); ?>" style="--tf-pin:<?php echo esc_attr( $tf_col ); ?>">
				<?php if ( $tf_photo ) : ?>
					<img class="tf-map__photo" src="<?php echo esc_url( $tf_photo ); ?>" alt="" loading="lazy" width="400" height="200">
				<?php endif; ?>
				<div class="tf-map__body">
					<p class="tf-map__kind"><?php echo esc_html( $tf_s['kind'] ); ?></p>
					<h3 class="tf-map__name"><?php echo esc_html( $tf_s['label'] ); ?></h3>
					<p class="tf-map__desc"><?php echo esc_html( $tf_s['desc'] ); ?></p>
					<?php if ( ! empty( $tf_s['facts'] ) ) : ?>
						<ul class="tf-map__facts">
							<?php foreach ( $tf_s['facts'] as $tf_f ) : ?>
								<li><?php echo esc_html( $tf_f ); ?></li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
					<?php if ( $tf_next ) : ?>
						<p class="tf-map__sub"><?php esc_html_e( 'Up next', 'tapaufest' ); ?></p>
						<ul class="tf-map__next">
							<?php foreach ( $tf_next as $tf_n ) : ?>
								<li><b><?php echo esc_html( $tf_n['when'] ); ?></b> <?php echo esc_html( $tf_n['title'] ); ?></li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
					<?php if ( ! empty( $tf_s['vendors'] ) ) : ?>
						<p class="tf-map__sub"><?php esc_html_e( 'Stalls here', 'tapaufest' ); ?></p>
						<p class="tf-map__vendors"><?php echo $tf_vend ? esc_html( implode( ' · ', $tf_vend ) ) : esc_html__( 'Announced in November.', 'tapaufest' ); ?></p>
					<?php endif; ?>
					<?php if ( ! empty( $tf_s['link'] ) ) : ?>
						<a class="tf-map__link" href="<?php echo esc_url( home_url( $tf_s['link'] ) ); ?>"><?php echo esc_html( $tf_s['cta'] ); ?> →</a>
					<?php endif; ?>
				</div>
			</article>
		<?php endforeach; ?>
	</div>
</div>
