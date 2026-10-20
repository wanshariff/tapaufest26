<?php
/**
 * Screen: partners.
 *
 * @package Tapaufest2026
 */

defined( 'ABSPATH' ) || exit;

$tf_all    = get_posts(
	array(
		'post_type'      => 'tf_partner',
		'posts_per_page' => -1,
		'orderby'        => array(
			'menu_order' => 'ASC',
			'title'      => 'ASC',
		),
	)
);
$tf_groups = array();
foreach ( tapaufest_partner_tiers() as $tf_slug => $tf_label ) {
	$tf_groups[ $tf_slug ] = array( $tf_label, array() );
}
foreach ( $tf_all as $tf_p ) {
	$tf_t = (string) get_post_meta( $tf_p->ID, 'tf_tier', true );
	$tf_t = isset( $tf_groups[ $tf_t ] ) ? $tf_t : 'community';
	$tf_groups[ $tf_t ][1][] = $tf_p;
}
?>
<div class="tf2-wrap tf2-main">
	<?php echo tf2_head( __( 'Made possible by', 'tapaufest-2026' ), __( 'Partners', 'tapaufest-2026' ), __( 'The supporters, brands, programme partners, media and crew behind TAPAUfest.', 'tapaufest-2026' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	<?php if ( ! $tf_all ) : ?>
		<?php echo tf2_empty( 'geng', __( 'Partners announced soon.', 'tapaufest-2026' ), __( 'Want to be one of them?', 'tapaufest-2026' ), array( tf2_btn( __( 'Message @tapaufest', 'tapaufest-2026' ), 'https://www.instagram.com/tapaufest/', 'cili', array( 'external' => true ) ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	<?php endif; ?>
	<div class="tf2-stack" style="gap:40px">
		<?php foreach ( $tf_groups as $tf_slug => $tf_g ) : ?>
			<?php if ( ! $tf_g[1] ) { continue; } ?>
			<section class="tf2-stack tf2-stack--sm">
				<h2 class="tf2-eb"><?php echo esc_html( $tf_g[0] ); ?></h2>
				<div class="tf2-auto tf2-logos" style="grid-template-columns:repeat(auto-fill,minmax(<?php echo 'past' === $tf_slug ? '160' : '200'; ?>px,1fr));gap:16px">
					<?php foreach ( $tf_g[1] as $tf_p ) : ?>
						<?php
						$tf_url  = (string) get_post_meta( $tf_p->ID, 'tf_url', true );
						$tf_role = (string) get_post_meta( $tf_p->ID, 'tf_role', true );
						$tf_logo = get_the_post_thumbnail( $tf_p->ID, 'medium', array( 'alt' => get_the_title( $tf_p ) ) );
						$tf_in   = $tf_logo ? $tf_logo : '<span>' . esc_html( get_the_title( $tf_p ) ) . ( $tf_role && 'past' !== $tf_slug ? '<br><small style="font-weight:500;color:var(--tf2-muted)">' . esc_html( $tf_role ) . '</small>' : '' ) . '</span>';
						$tf_tag  = $tf_url ? 'a href="' . esc_url( $tf_url ) . '" target="_blank" rel="noopener"' : 'div';
						?>
						<<?php echo $tf_tag; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> class="tf2-card tf2-logo-tile<?php echo $tf_url ? ' tf2-lift' : ''; ?>"<?php echo 'past' === $tf_slug ? ' style="min-height:72px;font-size:.85rem"' : ''; ?>><?php echo $tf_in; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></<?php echo $tf_url ? 'a' : 'div'; ?>>
					<?php endforeach; ?>
				</div>
			</section>
		<?php endforeach; ?>
	</div>
	<div class="tf2-card tf2-card--gold tf2-pad--lg tf2-between" style="margin-top:56px;align-items:center">
		<span class="tf2-stack tf2-stack--sm"><span class="tf2-h3" style="font-size:1.9rem"><?php esc_html_e( 'Want your brand here?', 'tapaufest-2026' ); ?></span><span><?php esc_html_e( 'Tell us what you have in mind. We keep it small.', 'tapaufest-2026' ); ?></span></span>
		<div class="tf2-row">
			<?php echo tf2_btn( __( 'Message @tapaufest', 'tapaufest-2026' ), 'https://www.instagram.com/tapaufest/', 'paper', array( 'external' => true, 'icon' => 'instagram' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<?php echo tf2_btn( __( 'Sell at a stall', 'tapaufest-2026' ), home_url( '/sell/' ), 'cili' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</div>
	</div>
</div>
