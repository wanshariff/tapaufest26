<?php
/**
 * Interactive site map.
 *
 * The SVG is drawn from tapaufest_map_spots(). Every spot is a focusable
 * button; its detail card is rendered server-side so the map still makes
 * sense with no JavaScript (the cards then show as a list under the map).
 *
 * @package TapaufestCore
 */

defined( 'ABSPATH' ) || exit;

$tf_spots = tapaufest_map_spots();
$tf_uid   = wp_unique_id( 'tf-map-' );

/**
 * Vendor names for a zone.
 *
 * @param string $type warung|pasar.
 * @return string[]
 */
$tf_vendor_names = function ( $type ) {
	return wp_list_pluck(
		get_posts(
			array(
				'post_type'      => 'tf_vendor',
				'posts_per_page' => 8,
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
<div <?php echo get_block_wrapper_attributes( array( 'class' => 'tf-map' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> data-tf-map>
	<div class="tf-map__stage">
		<svg class="tf-map__svg" viewBox="0 0 1000 640" role="group" aria-label="<?php esc_attr_e( 'Festival site map. Choose a zone or facility for details.', 'tapaufest' ); ?>">
			<defs>
				<pattern id="<?php echo esc_attr( $tf_uid ); ?>-grass" width="18" height="18" patternUnits="userSpaceOnUse">
					<path d="M3 14 l2 -5 M10 16 l1 -5 M15 12 l2 -4" stroke="#C9A867" stroke-width="2" stroke-linecap="round"/>
				</pattern>
			</defs>
			<rect width="1000" height="640" fill="#EBCB91"/>
			<rect width="1000" height="640" fill="url(#<?php echo esc_attr( $tf_uid ); ?>-grass)" opacity=".7"/>
			<path class="tf-map__river" d="M-20 92 C 160 40, 330 140, 520 88 S 840 30, 1020 78" fill="none" stroke="#3D8BD0" stroke-width="44" stroke-linecap="round"/>
			<path d="M-20 92 C 160 40, 330 140, 520 88 S 840 30, 1020 78" fill="none" stroke="#BFD7EA" stroke-width="4" stroke-dasharray="14 18" stroke-linecap="round" class="tf-map__ripple"/>
			<text x="520" y="70" text-anchor="middle" class="tf-map__river-label">Sungai Congkak</text>
			<g fill="#6E9A4A" stroke="#1E1208" stroke-width="3">
				<circle cx="40" cy="250" r="22"/><circle cx="62" cy="275" r="16"/><circle cx="560" cy="150" r="18"/>
				<circle cx="960" cy="560" r="26"/><circle cx="60" cy="590" r="24"/><circle cx="820" cy="140" r="16"/>
			</g>
			<path d="M510 560 L510 340 M510 470 L310 420 M510 470 L690 380 M640 470 L865 420" stroke="#1E1208" stroke-width="5" stroke-dasharray="2 12" stroke-linecap="round" fill="none"/>
			<?php foreach ( $tf_spots as $tf_key => $tf_s ) : ?>
				<?php
				$tf_shape = $tf_s['shape'];
				$tf_text  = isset( $tf_s['text'] ) ? $tf_s['text'] : '#1E1208';
				$tf_is_fac = ! empty( $tf_s['icon'] );
				?>
				<g class="tf-map__spot<?php echo $tf_is_fac ? ' is-facility' : ''; ?>" tabindex="0" role="button" aria-label="<?php echo esc_attr( $tf_s['label'] . ', ' . $tf_s['kind'] ); ?>" aria-controls="<?php echo esc_attr( $tf_uid . '-' . $tf_key ); ?>" aria-expanded="false" data-spot="<?php echo esc_attr( $tf_key ); ?>">
					<?php if ( 'rect' === $tf_shape[0] ) : ?>
						<rect class="tf-map__shadow" x="<?php echo (int) $tf_shape[1] + 6; ?>" y="<?php echo (int) $tf_shape[2] + 6; ?>" width="<?php echo (int) $tf_shape[3]; ?>" height="<?php echo (int) $tf_shape[4]; ?>" rx="10" fill="#1E1208"/>
						<rect class="tf-map__shape" x="<?php echo (int) $tf_shape[1]; ?>" y="<?php echo (int) $tf_shape[2]; ?>" width="<?php echo (int) $tf_shape[3]; ?>" height="<?php echo (int) $tf_shape[4]; ?>" rx="10" fill="<?php echo esc_attr( $tf_s['fill'] ); ?>" stroke="#1E1208" stroke-width="4"/>
						<text class="tf-map__label" x="<?php echo (int) ( $tf_shape[1] + $tf_shape[3] / 2 ); ?>" y="<?php echo (int) ( $tf_shape[2] + $tf_shape[4] / 2 ); ?>" fill="<?php echo esc_attr( $tf_text ); ?>" text-anchor="middle" dominant-baseline="central"<?php echo ( $tf_shape[4] > $tf_shape[3] * 1.6 ) ? ' transform="rotate(-90 ' . (int) ( $tf_shape[1] + $tf_shape[3] / 2 ) . ' ' . (int) ( $tf_shape[2] + $tf_shape[4] / 2 ) . ')"' : ''; ?>><?php echo esc_html( $tf_s['label'] ); ?></text>
					<?php else : ?>
						<circle class="tf-map__shadow" cx="<?php echo (int) $tf_shape[1] + 4; ?>" cy="<?php echo (int) $tf_shape[2] + 4; ?>" r="<?php echo (int) $tf_shape[3]; ?>" fill="#1E1208"/>
						<circle class="tf-map__shape" cx="<?php echo (int) $tf_shape[1]; ?>" cy="<?php echo (int) $tf_shape[2]; ?>" r="<?php echo (int) $tf_shape[3]; ?>" fill="<?php echo esc_attr( $tf_s['fill'] ); ?>" stroke="#1E1208" stroke-width="3"/>
						<text class="tf-map__label<?php echo $tf_is_fac ? ' tf-map__icon' : ' tf-map__label--sm'; ?>" x="<?php echo (int) $tf_shape[1]; ?>" y="<?php echo (int) $tf_shape[2]; ?>" fill="<?php echo esc_attr( $tf_text ); ?>" text-anchor="middle" dominant-baseline="central"><?php echo esc_html( $tf_is_fac ? $tf_s['icon'] : $tf_s['label'] ); ?></text>
					<?php endif; ?>
				</g>
			<?php endforeach; ?>
		</svg>
		<p class="tf-map__hint" aria-hidden="true"><?php esc_html_e( 'Hover or tap a zone', 'tapaufest' ); ?></p>
	</div>

	<div class="tf-map__legend" role="group" aria-label="<?php esc_attr_e( 'Jump to a spot', 'tapaufest' ); ?>">
		<?php foreach ( $tf_spots as $tf_key => $tf_s ) : ?>
			<button type="button" class="tf-map__chip<?php echo ! empty( $tf_s['icon'] ) ? ' is-facility' : ''; ?>" data-chip="<?php echo esc_attr( $tf_key ); ?>" aria-controls="<?php echo esc_attr( $tf_uid . '-' . $tf_key ); ?>">
				<?php if ( ! empty( $tf_s['icon'] ) ) : ?>
					<span class="tf-map__chip-icon" style="background:<?php echo esc_attr( $tf_s['fill'] ); ?>;color:<?php echo esc_attr( isset( $tf_s['text'] ) ? $tf_s['text'] : '#1E1208' ); ?>" aria-hidden="true"><?php echo esc_html( $tf_s['icon'] ); ?></span>
				<?php endif; ?>
				<?php echo esc_html( $tf_s['label'] ); ?>
			</button>
		<?php endforeach; ?>
	</div>

	<div class="tf-map__cards">
		<?php foreach ( $tf_spots as $tf_key => $tf_s ) : ?>
			<?php
			$tf_photo = ( ! empty( $tf_s['photo'] ) && function_exists( 'tapaufest_photo_url' ) ) ? tapaufest_photo_url( $tf_s['photo'] ) : '';
			$tf_next  = ! empty( $tf_s['stage'] ) ? tapaufest_map_stage_sessions( $tf_s['stage'] ) : array();
			$tf_vend  = ! empty( $tf_s['vendors'] ) ? $tf_vendor_names( $tf_s['vendors'] ) : array();
			?>
			<article class="tf-map__card" id="<?php echo esc_attr( $tf_uid . '-' . $tf_key ); ?>" data-card="<?php echo esc_attr( $tf_key ); ?>">
				<?php if ( $tf_photo ) : ?>
					<img class="tf-map__photo" src="<?php echo esc_url( $tf_photo ); ?>" alt="" loading="lazy" width="400" height="225">
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
						<p class="tf-map__sub"><?php esc_html_e( 'Here this year', 'tapaufest' ); ?></p>
						<p class="tf-map__vendors"><?php echo $tf_vend ? esc_html( implode( ' · ', $tf_vend ) ) : esc_html__( 'Stalls announced in November.', 'tapaufest' ); ?></p>
					<?php endif; ?>
					<?php if ( ! empty( $tf_s['link'] ) ) : ?>
						<a class="tf-map__link" href="<?php echo esc_url( home_url( $tf_s['link'] ) ); ?>"><?php echo esc_html( $tf_s['cta'] ); ?> →</a>
					<?php endif; ?>
				</div>
			</article>
		<?php endforeach; ?>
	</div>
</div>
