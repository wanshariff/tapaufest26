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
		<p class="tf-empty"><?php esc_html_e( 'Vendor list coming soon.', 'tapaufest' ); ?></p>
	<?php else : ?>
		<ul class="tf-vendors__list">
			<?php foreach ( $tf_vendors as $tf_v ) : ?>
				<li class="tf-vendor"><b><?php echo esc_html( get_the_title( $tf_v ) ); ?></b><span><?php echo esc_html( get_post_meta( $tf_v->ID, 'tf_note', true ) ); ?></span></li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>
</div>
