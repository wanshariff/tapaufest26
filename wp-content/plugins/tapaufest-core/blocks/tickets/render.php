<?php
/**
 * Ticket tiers. Prices and buttons follow the site state.
 *
 * Early access: the early-bird price shows, and buyers enter the code from
 * their waitlist email at TAPAUtix checkout. The code is never checked or
 * shown here, so it can't leak from the site.
 *
 * @package TapaufestCore
 */

defined( 'ABSPATH' ) || exit;

$tf_state   = tapaufest_state();
$tf_tiers   = tapaufest_get_tiers();
$tf_limit   = isset( $attributes['limit'] ) ? (int) $attributes['limit'] : 0;
$tf_closed  = in_array( $tf_state, array( 'live', 'recap' ), true );
$tf_early   = 'early' === $tf_state;
$tf_default = tapaufest_setting( 'tickets_url' );
if ( $tf_limit > 0 ) {
	$tf_tiers = array_slice( $tf_tiers, 0, $tf_limit );
}
?>
<div <?php echo get_block_wrapper_attributes( array( 'class' => 'tf-tickets' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> id="tickets" data-tf-tickets>
	<?php if ( 'recap' === $tf_state ) : ?>
		<div class="tf-callout">
			<p class="tf-callout__title"><?php esc_html_e( 'Sales have closed. See you next year.', 'tapaufest' ); ?></p>
			<p><?php esc_html_e( 'Join the waitlist for early access and the early-bird price next edition.', 'tapaufest' ); ?></p>
			<?php if ( tapaufest_setting( 'waitlist_url' ) ) : ?>
				<p><a class="tf-btn tf-btn--cili" href="<?php echo esc_url( tapaufest_setting( 'waitlist_url' ) ); ?>"><?php esc_html_e( 'Join the waitlist', 'tapaufest' ); ?></a></p>
			<?php endif; ?>
		</div>
		<?php
		return;
	endif;
	?>

	<?php if ( $tf_early ) : ?>
		<div class="tf-callout">
			<p class="tf-callout__title"><?php esc_html_e( 'Early access is open for the waitlist', 'tapaufest' ); ?></p>
			<p><?php esc_html_e( 'Use the code in your waitlist email at TAPAUtix checkout to get the early-bird price. Not on the waitlist? Public sale opens soon.', 'tapaufest' ); ?></p>
		</div>
	<?php elseif ( 'live' === $tf_state ) : ?>
		<p class="tf-muted"><?php esc_html_e( 'Sales have closed. See you at the gate.', 'tapaufest' ); ?></p>
	<?php endif; ?>

	<?php if ( ! $tf_tiers ) : ?>
		<p class="tf-empty"><?php esc_html_e( 'Ticket tiers are on the way.', 'tapaufest' ); ?></p>
	<?php else : ?>
		<div class="tf-tiers">
			<?php foreach ( $tf_tiers as $tf_t ) : ?>
				<article class="tf-tier">
					<?php $tf_art = tapaufest_tier_art( $tf_t ); ?>
					<?php if ( $tf_art ) : ?>
						<img class="tf-tier__art" src="<?php echo esc_url( $tf_art ); ?>" alt="" width="360" height="260" loading="lazy">
					<?php endif; ?>
					<p class="tf-tier__who"><?php echo esc_html( $tf_t['who'] ); ?></p>
					<h3 class="tf-tier__name"><?php echo esc_html( $tf_t['name'] ); ?></h3>
					<?php if ( $tf_t['soldout'] ) : ?>
						<span class="tf-badge tf-badge--out"><?php esc_html_e( 'Sold out', 'tapaufest' ); ?></span>
					<?php elseif ( $tf_early ) : ?>
						<span class="tf-badge"><?php esc_html_e( 'Early bird · waitlist', 'tapaufest' ); ?></span>
					<?php else : ?>
						<span class="tf-badge"><?php esc_html_e( 'Regular price', 'tapaufest' ); ?></span>
					<?php endif; ?>
					<p class="tf-price">
						<?php if ( $tf_early && $tf_t['early'] ) : ?>
							<b><?php echo esc_html( tapaufest_rm( $tf_t['early'] ) ); ?></b>
							<?php if ( $tf_t['regular'] ) : ?>
								<?php /* translators: %s: regular price. */ ?>
								<span class="tf-price__was"><?php echo esc_html( sprintf( __( 'Regular %s', 'tapaufest' ), tapaufest_rm( $tf_t['regular'] ) ) ); ?></span>
							<?php endif; ?>
						<?php else : ?>
							<b><?php echo esc_html( tapaufest_rm( $tf_t['regular'] ) ); ?></b>
						<?php endif; ?>
					</p>
					<?php if ( $tf_t['includes'] ) : ?>
						<ul class="tf-tier__list">
							<?php foreach ( $tf_t['includes'] as $tf_line ) : ?>
								<li><?php echo tapaufest_icon( 'check' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php echo esc_html( $tf_line ); ?></li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
					<?php if ( $tf_closed || $tf_t['soldout'] ) : ?>
						<span class="tf-btn tf-btn--off" aria-disabled="true"><?php echo esc_html( $tf_t['soldout'] ? __( 'Sold out', 'tapaufest' ) : __( 'Sales closed', 'tapaufest' ) ); ?></span>
					<?php else : ?>
						<?php /* translators: %s: tier name. */ ?>
						<a class="tf-btn tf-btn--cili" href="<?php echo esc_url( $tf_t['url'] ? $tf_t['url'] : $tf_default ); ?>" target="_blank" rel="noopener" aria-label="<?php echo esc_attr( sprintf( __( 'Buy %s on TAPAUtix (opens in a new tab)', 'tapaufest' ), $tf_t['name'] ) ); ?>">
							<?php
							echo esc_html( $tf_early ? __( 'Buy with your code', 'tapaufest' ) : __( 'Get this pass', 'tapaufest' ) ) . ' ' . tapaufest_icon( 'external' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							?>
						</a>
					<?php endif; ?>
				</article>
			<?php endforeach; ?>
		</div>
		<p class="tf-muted tf-tickets__via"><?php esc_html_e( 'Checkout and your e-ticket happen on TAPAUtix.', 'tapaufest' ); ?></p>

		<?php if ( ! empty( $attributes['compare'] ) ) : ?>
			<div class="tf-tablewrap">
				<table class="tf-table">
					<caption class="screen-reader-text"><?php esc_html_e( 'What each pass includes', 'tapaufest' ); ?></caption>
					<thead>
						<tr>
							<th scope="col"><?php esc_html_e( 'Included', 'tapaufest' ); ?></th>
							<?php foreach ( $tf_tiers as $tf_t ) : ?>
								<th scope="col"><?php echo esc_html( $tf_t['name'] ); ?></th>
							<?php endforeach; ?>
						</tr>
					</thead>
					<tbody>
						<tr>
							<th scope="row"><?php esc_html_e( 'People', 'tapaufest' ); ?></th>
							<?php foreach ( $tf_tiers as $tf_t ) : ?>
								<td><?php echo esc_html( number_format_i18n( $tf_t['people'] ) ); ?></td>
							<?php endforeach; ?>
						</tr>
						<tr>
							<th scope="row"><?php esc_html_e( 'Days', 'tapaufest' ); ?></th>
							<?php foreach ( $tf_tiers as $tf_t ) : ?>
								<td><?php echo esc_html( number_format_i18n( $tf_t['days'] ) ); ?></td>
							<?php endforeach; ?>
						</tr>
						<tr>
							<th scope="row"><?php esc_html_e( 'Festival campsite', 'tapaufest' ); ?></th>
							<?php foreach ( $tf_tiers as $tf_t ) : ?>
								<td class="<?php echo $tf_t['campsite'] ? 'tf-yes' : ''; ?>"><?php echo esc_html( $tf_t['campsite'] ? __( 'Yes', 'tapaufest' ) : __( 'No', 'tapaufest' ) ); ?></td>
							<?php endforeach; ?>
						</tr>
						<tr>
							<th scope="row"><?php esc_html_e( 'Price per person', 'tapaufest' ); ?></th>
							<?php foreach ( $tf_tiers as $tf_t ) : ?>
								<?php $tf_price = ( $tf_early && $tf_t['early'] ) ? $tf_t['early'] : $tf_t['regular']; ?>
								<td><?php echo esc_html( tapaufest_rm( (int) round( $tf_price / $tf_t['people'] ) ) ); ?></td>
							<?php endforeach; ?>
						</tr>
					</tbody>
				</table>
			</div>
		<?php endif; ?>
	<?php endif; ?>
</div>
