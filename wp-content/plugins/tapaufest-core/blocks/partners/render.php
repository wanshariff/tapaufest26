<?php
/**
 * Partners, grouped by credit. Compact mode is a single logo strip for the
 * home page; the full mode adds what each partner did.
 *
 * @package TapaufestCore
 */

defined( 'ABSPATH' ) || exit;

$tf_compact  = ! empty( $attributes['compact'] );
$tf_partners = get_posts(
	array(
		'post_type'      => 'tf_partner',
		'posts_per_page' => -1,
		'orderby'        => array(
			'menu_order' => 'ASC',
			'title'      => 'ASC',
		),
	)
);
$tf_groups   = array();
foreach ( tapaufest_partner_tiers() as $tf_slug => $tf_label ) {
	if ( $tf_compact && 'past' === $tf_slug ) {
		continue; // The full list lives on the Partners page.
	}
	$tf_groups[ $tf_slug ] = array(
		'label' => $tf_label,
		'items' => array(),
	);
}
foreach ( $tf_partners as $tf_p ) {
	$tf_tier = (string) get_post_meta( $tf_p->ID, 'tf_tier', true );
	if ( $tf_compact && 'past' === $tf_tier ) {
		continue;
	}
	$tf_tier = isset( $tf_groups[ $tf_tier ] ) ? $tf_tier : 'community';
	$tf_groups[ $tf_tier ]['items'][] = $tf_p;
}
$tf_years = array_unique( array_filter( array_map( function ( $p ) { return (string) get_post_meta( $p->ID, 'tf_year', true ); }, $tf_partners ) ) );

/**
 * One partner tile.
 *
 * @param WP_Post $p       Partner.
 * @param bool    $compact Compact.
 * @return string
 */
$tf_tile = function ( $p, $compact ) {
	$url  = (string) get_post_meta( $p->ID, 'tf_url', true );
	$role = (string) get_post_meta( $p->ID, 'tf_role', true );
	$logo = get_the_post_thumbnail( $p->ID, 'medium', array( 'class' => 'tf-partner__logo', 'alt' => get_the_title( $p ) ) );
	$mark = $logo ? $logo : '<span class="tf-partner__mark">' . esc_html( get_the_title( $p ) ) . '</span>';
	$tag  = $url ? 'a' : 'div';
	$attr = $url ? ' href="' . esc_url( $url ) . '" target="_blank" rel="noopener"' : '';
	$out  = '<li class="tf-partner"><' . $tag . ' class="tf-partner__in"' . $attr . '>' . $mark;
	if ( ! $compact ) {
		$out .= '<span class="tf-partner__name">' . esc_html( get_the_title( $p ) ) . '</span>';
		if ( $role ) {
			$out .= '<span class="tf-partner__role">' . esc_html( $role ) . '</span>';
		}
	}
	return $out . '</' . $tag . '></li>';
};
?>
<div <?php echo get_block_wrapper_attributes( array( 'class' => 'tf-partners' . ( $tf_compact ? ' tf-partners--compact' : '' ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<?php if ( ! $tf_partners ) : ?>
		<p class="tf-empty"><?php esc_html_e( 'Partners announced soon.', 'tapaufest' ); ?></p>
	<?php else : ?>
		<?php foreach ( $tf_groups as $tf_slug => $tf_g ) : ?>
			<?php if ( ! $tf_g['items'] ) { continue; } ?>
			<div class="tf-partners__group tf-partners__group--<?php echo esc_attr( $tf_slug ); ?>">
				<h3 class="tf-partners__title"><?php echo esc_html( $tf_g['label'] ); ?></h3>
				<ul class="tf-partners__list">
					<?php
					foreach ( $tf_g['items'] as $tf_p ) {
						echo $tf_tile( $tf_p, $tf_compact ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped inside.
					}
					?>
				</ul>
			</div>
		<?php endforeach; ?>
		<?php if ( $tf_years && ! in_array( substr( tapaufest_setting( 'day1' ), 0, 4 ), $tf_years, true ) ) : ?>
			<p class="tf-partners__note"><?php echo esc_html( sprintf( /* translators: %s: year. */ __( 'Partners of TAPAUfest %s. This year’s partners are announced soon.', 'tapaufest' ), implode( ', ', $tf_years ) ) ); ?></p>
		<?php endif; ?>
	<?php endif; ?>
</div>
