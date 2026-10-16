<?php
/**
 * WARUNG or PASAR vendor list.
 *
 * @package TapaufestCore
 */

defined( 'ABSPATH' ) || exit;

$tf_type    = ( isset( $attributes['type'] ) && 'pasar' === $attributes['type'] ) ? 'pasar' : 'warung';
$tf_vendors = get_posts(
	array(
		'post_type'      => 'tf_vendor',
		'posts_per_page' => -1,
		'meta_key'       => 'tf_vendor_type', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_query_meta_key
		'meta_value'     => $tf_type, // phpcs:ignore WordPress.DB.SlowDBQuery.slow_query_meta_value
		'orderby'        => array(
			'menu_order' => 'ASC',
			'title'      => 'ASC',
		),
	)
);
?>
<div <?php echo get_block_wrapper_attributes( array( 'class' => 'tf-vendors' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<?php if ( ! $tf_vendors ) : ?>
		<p class="tf-empty"><?php esc_html_e( 'This year’s stalls are announced in November.', 'tapaufest' ); ?></p>
	<?php else : ?>
		<ul class="tf-vendors__list">
			<?php foreach ( $tf_vendors as $tf_v ) : ?>
				<?php
				$tf_sig   = (string) get_post_meta( $tf_v->ID, 'tf_signature', true );
				$tf_price = (string) get_post_meta( $tf_v->ID, 'tf_price', true );
				$tf_ig    = ltrim( (string) get_post_meta( $tf_v->ID, 'tf_instagram', true ), '@' );
				$tf_year  = (string) get_post_meta( $tf_v->ID, 'tf_year', true );
				$tf_tags  = array_filter( array_map( 'trim', explode( ',', (string) get_post_meta( $tf_v->ID, 'tf_tags', true ) ) ) );
				$tf_desc  = has_excerpt( $tf_v ) ? get_the_excerpt( $tf_v ) : '';
				$tf_img   = get_the_post_thumbnail( $tf_v->ID, 'medium', array( 'class' => 'tf-vendor__img', 'alt' => '' ) );
				$tf_prices = tapaufest_price_ranges();
				?>
				<li class="tf-vendor<?php echo $tf_img ? ' has-img' : ''; ?>">
					<?php if ( $tf_img ) : ?>
						<?php echo $tf_img; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<?php endif; ?>
					<div class="tf-vendor__body">
						<p class="tf-vendor__top">
							<a class="tf-vendor__name" href="<?php echo esc_url( get_permalink( $tf_v ) ); ?>"><?php echo esc_html( get_the_title( $tf_v ) ); ?></a>
							<?php if ( $tf_year && $tf_year !== substr( tapaufest_setting( 'day1' ), 0, 4 ) ) : ?>
								<span class="tf-vendor__year"><?php echo esc_html( $tf_year ); ?></span>
							<?php endif; ?>
						</p>
						<span class="tf-vendor__note"><?php echo esc_html( get_post_meta( $tf_v->ID, 'tf_note', true ) ); ?></span>
						<?php if ( $tf_desc ) : ?>
							<span class="tf-vendor__desc"><?php echo esc_html( $tf_desc ); ?></span>
						<?php endif; ?>
						<?php if ( $tf_sig ) : ?>
							<span class="tf-vendor__sig"><?php esc_html_e( 'Must-try:', 'tapaufest' ); ?> <?php echo esc_html( $tf_sig ); ?></span>
						<?php endif; ?>
						<?php if ( $tf_tags || $tf_price ) : ?>
							<span class="tf-vendor__tags">
								<?php if ( $tf_price && isset( $tf_prices[ $tf_price ] ) ) : ?>
									<span class="tf-tag"><?php echo esc_html( $tf_prices[ $tf_price ] ); ?></span>
								<?php endif; ?>
								<?php foreach ( $tf_tags as $tf_t ) : ?>
									<span class="tf-tag"><?php echo esc_html( $tf_t ); ?></span>
								<?php endforeach; ?>
							</span>
						<?php endif; ?>
						<?php if ( $tf_ig ) : ?>
							<a class="tf-vendor__ig" href="<?php echo esc_url( 'https://www.instagram.com/' . rawurlencode( $tf_ig ) . '/' ); ?>" target="_blank" rel="noopener">@<?php echo esc_html( $tf_ig ); ?> ↗</a>
						<?php endif; ?>
					</div>
				</li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>
</div>
