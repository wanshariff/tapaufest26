<?php
/**
 * Screen: FAQ. Topics on the left, search across everything.
 *
 * @package Tapaufest2026
 */

defined( 'ABSPATH' ) || exit;

$tf_terms = get_terms(
	array(
		'taxonomy'   => 'tf_faq_group',
		'hide_empty' => true,
		'orderby'    => 'term_id',
	)
);
$tf_terms = is_wp_error( $tf_terms ) ? array() : $tf_terms;
$tf_sets  = array();
foreach ( $tf_terms as $tf_term ) {
	$tf_sets[ $tf_term->slug ] = array(
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
if ( ! $tf_sets ) {
	$tf_posts = get_posts(
		array(
			'post_type'      => 'tf_faq',
			'posts_per_page' => -1,
			'orderby'        => 'menu_order',
			'order'          => 'ASC',
		)
	);
	if ( $tf_posts ) {
		$tf_sets['all'] = array(
			'name'  => __( 'Everything', 'tapaufest-2026' ),
			'posts' => $tf_posts,
		);
	}
}
?>
<div class="tf2-wrap tf2-main" data-tf2-faq>
	<?php echo tf2_head( __( 'Quick answers', 'tapaufest-2026' ), __( 'FAQ', 'tapaufest-2026' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	<?php if ( ! $tf_sets ) : ?>
		<?php echo tf2_empty( 'info', __( 'Answers on the way.', 'tapaufest-2026' ), __( 'Ask us on Instagram in the meantime.', 'tapaufest-2026' ), array( tf2_btn( '@tapaufest', 'https://www.instagram.com/tapaufest/', 'cili', array( 'external' => true ) ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	<?php else : ?>
	<div class="tf2-side">
		<aside class="tf2-stack tf2-stack--sm tf2-sticky">
			<label class="tf2-search"><?php echo tf2_icon( 'search' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><span class="screen-reader-text"><?php esc_html_e( 'Search the FAQ', 'tapaufest-2026' ); ?></span><input type="search" placeholder="<?php esc_attr_e( 'Search: parking, refund, kids', 'tapaufest-2026' ); ?>"></label>
			<nav class="tf2-faqnav" aria-label="<?php esc_attr_e( 'FAQ topics', 'tapaufest-2026' ); ?>">
				<?php $tf_first = true; ?>
				<?php foreach ( $tf_sets as $tf_slug => $tf_set ) : ?>
					<button type="button" data-tf2-group="<?php echo esc_attr( $tf_slug ); ?>" aria-pressed="<?php echo $tf_first ? 'true' : 'false'; ?>"><?php echo esc_html( $tf_set['name'] ); ?></button>
					<?php $tf_first = false; ?>
				<?php endforeach; ?>
			</nav>
			<p class="tf2-note"><?php esc_html_e( 'Still stuck?', 'tapaufest-2026' ); ?> <a href="https://www.instagram.com/tapaufest/" target="_blank" rel="noopener"><?php esc_html_e( 'Message us', 'tapaufest-2026' ); ?></a></p>
		</aside>
		<div class="tf2-stack tf2-stack--sm">
			<?php $tf_first = true; ?>
			<?php foreach ( $tf_sets as $tf_slug => $tf_set ) : ?>
				<section class="tf2-faqgroup tf2-stack tf2-stack--sm" data-group="<?php echo esc_attr( $tf_slug ); ?>"<?php echo $tf_first ? '' : ' hidden'; ?>>
					<h2 class="screen-reader-text"><?php echo esc_html( $tf_set['name'] ); ?></h2>
					<?php foreach ( $tf_set['posts'] as $tf_i => $tf_q ) : ?>
						<details class="tf2-card tf2-q"<?php echo $tf_first && 0 === $tf_i ? ' open' : ''; ?>>
							<summary><?php echo esc_html( get_the_title( $tf_q ) ); ?><?php echo tf2_icon( 'plus' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></summary>
							<div class="tf2-q__a"><?php echo wp_kses_post( wpautop( $tf_q->post_content ) ); ?></div>
						</details>
					<?php endforeach; ?>
				</section>
				<?php $tf_first = false; ?>
			<?php endforeach; ?>
			<div class="tf2-empty" data-tf2-faq-none hidden>
				<span class="tf2-empty__icon" aria-hidden="true"><?php echo tf2_icon( 'search' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
				<p class="tf2-empty__title"><?php esc_html_e( 'Nothing on that yet.', 'tapaufest-2026' ); ?></p>
				<p class="tf2-empty__line"><?php esc_html_e( 'No answer for', 'tapaufest-2026' ); ?> “<span data-tf2-term></span>”. <?php esc_html_e( 'Ask us and we’ll add it.', 'tapaufest-2026' ); ?></p>
				<?php echo tf2_btn( __( 'Message @tapaufest', 'tapaufest-2026' ), 'https://www.instagram.com/tapaufest/', 'cili', array( 'external' => true ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
		</div>
	</div>
	<?php endif; ?>
</div>
