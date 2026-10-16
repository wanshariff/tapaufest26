<?php
/**
 * FAQ grouped by FAQ group. Uses native details/summary, so it works without script.
 *
 * @package TapaufestCore
 */

defined( 'ABSPATH' ) || exit;

$tf_group = isset( $attributes['group'] ) ? sanitize_title( $attributes['group'] ) : '';
$tf_mini  = ! empty( $attributes['mini'] );
if ( $tf_mini ) {
	// Mini FAQ for the home page: questions flagged "Show on home page".
	$tf_featured = get_posts(
		array(
			'post_type'      => 'tf_faq',
			'posts_per_page' => 6,
			'orderby'        => array( 'menu_order' => 'ASC' ),
			'meta_key'       => 'tf_featured', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_query_meta_key
			'meta_value'     => '1', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_query_meta_value
		)
	);
}
$tf_terms = get_terms(
	array(
		'taxonomy'   => 'tf_faq_group',
		'hide_empty' => true,
		'orderby'    => 'term_id',
	) + ( $tf_group ? array( 'slug' => array( $tf_group ) ) : array() )
);
if ( is_wp_error( $tf_terms ) ) {
	$tf_terms = array();
}

$tf_sets = array();
if ( $tf_mini ) {
	$tf_terms = array();
	if ( $tf_featured ) {
		$tf_sets[] = array(
			'name'  => '',
			'posts' => $tf_featured,
		);
	}
}
foreach ( $tf_terms as $tf_term ) {
	$tf_sets[] = array(
		'name'  => $tf_term->name,
		'posts' => get_posts(
			array(
				'post_type'      => 'tf_faq',
				'posts_per_page' => -1,
				'orderby'        => array(
					'menu_order' => 'ASC',
					'title'      => 'ASC',
				),
				'tax_query'      => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_query_tax_query
					array(
						'taxonomy' => 'tf_faq_group',
						'terms'    => $tf_term->term_id,
					),
				),
			)
		),
	);
}
if ( ! $tf_group && ! $tf_mini ) {
	$tf_loose = get_posts(
		array(
			'post_type'      => 'tf_faq',
			'posts_per_page' => -1,
			'orderby'        => array( 'menu_order' => 'ASC' ),
			'tax_query'      => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_query_tax_query
				array(
					'taxonomy' => 'tf_faq_group',
					'operator' => 'NOT EXISTS',
				),
			),
		)
	);
	if ( $tf_loose ) {
		$tf_sets[] = array(
			'name'  => '',
			'posts' => $tf_loose,
		);
	}
}
?>
<div <?php echo get_block_wrapper_attributes( array( 'class' => 'tf-faq' . ( $tf_mini ? ' tf-faq--mini' : '' ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<?php if ( ! $tf_sets ) : ?>
		<p class="tf-empty"><?php esc_html_e( 'Questions and answers coming soon.', 'tapaufest' ); ?></p>
	<?php endif; ?>
	<?php foreach ( $tf_sets as $tf_i => $tf_set ) : ?>
		<div class="tf-faq__group">
			<?php if ( $tf_set['name'] && ! $tf_group ) : ?>
				<h2 class="tf-faq__title"><?php echo esc_html( $tf_set['name'] ); ?></h2>
			<?php endif; ?>
			<?php foreach ( $tf_set['posts'] as $tf_j => $tf_q ) : ?>
				<details class="tf-faq__item"<?php echo ( 0 === $tf_i && 0 === $tf_j ) ? ' open' : ''; ?>>
					<summary><?php echo esc_html( get_the_title( $tf_q ) ); ?><?php echo tapaufest_icon( 'plus', array( 'class' => 'tf-faq__toggle' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></summary>
					<div class="tf-faq__answer"><?php echo wp_kses_post( wpautop( do_blocks( $tf_q->post_content ) ) ); ?></div>
				</details>
			<?php endforeach; ?>
		</div>
	<?php endforeach; ?>
</div>
