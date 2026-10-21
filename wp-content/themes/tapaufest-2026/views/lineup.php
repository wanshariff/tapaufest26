<?php
/**
 * Screen: lineup. One reel of full-height poster cards in running order.
 * Desktop scrolls sideways; phones swipe and tap a card for full-screen
 * stories. Headliners get double-wide gold cards.
 *
 * @package Tapaufest2026
 */

defined( 'ABSPATH' ) || exit;

$tf_days  = tapaufest_days();
$tf_hosts = array_map( 'tapaufest_artist_data', tapaufest_get_artists( 0, true ) );
$tf_all   = array_map( 'tapaufest_artist_data', tapaufest_get_artists() );

// Running order: by day, then start time. Acts without a slot go last.
usort(
	$tf_all,
	function ( $a, $b ) {
		$ka = $a['session'] ? $a['session']['day'] . sprintf( '%05d', $a['session']['start_min'] ) : 'z';
		$kb = $b['session'] ? $b['session']['day'] . sprintf( '%05d', $b['session']['start_min'] ) : 'z';
		return strcmp( $ka, $kb );
	}
);
$tf_groups = array();
foreach ( $tf_all as $tf_a ) {
	$tf_k                 = $tf_a['session'] && isset( $tf_days[ $tf_a['session']['day'] ] ) ? $tf_a['session']['day'] : 'tba';
	$tf_groups[ $tf_k ][] = $tf_a;
}
$tf_daykeys = array_keys( $tf_days );
$tf_lastday = end( $tf_daykeys );
$tf_total   = count( $tf_all );

/**
 * Card and story data for one act.
 *
 * @param array  $a    Artist data.
 * @param int    $n    Position in the running order (1-based).
 * @param string $last Last festival day.
 * @return array
 */
$tf_card = function ( $a, $n, $last ) use ( $tf_days, $tf_daykeys ) {
	$s     = $a['session'];
	$day   = $s ? $s['day'] : '';
	$main  = $a['headliner'] && $day === $last;
	$label = '';
	if ( $a['headliner'] ) {
		if ( $main ) {
			$label = __( 'Main act · last set', 'tapaufest-2026' );
		} else {
			$idx   = array_search( $day, $tf_daykeys, true );
			/* translators: %s: night number word. */
			$label = false === $idx ? __( 'Headliner', 'tapaufest-2026' ) : sprintf( __( 'Headliner · closes night %s', 'tapaufest-2026' ), 0 === $idx ? __( 'one', 'tapaufest-2026' ) : __( 'two', 'tapaufest-2026' ) );
		}
	}
	$excerpt = get_post_field( 'post_excerpt', $a['id'] );
	$blurb   = $excerpt ? $excerpt : wp_trim_words( wp_strip_all_tags( get_post_field( 'post_content', $a['id'] ) ), 24 );
	return array(
		'id'       => $s ? (string) $s['id'] : '',
		'artist'   => $a['id'],
		'n'        => $n,
		'name'     => html_entity_decode( $a['name'], ENT_QUOTES, 'UTF-8' ),
		'url'      => $a['url'],
		'img'      => (string) get_the_post_thumbnail_url( $a['id'], 'large' ),
		'day'      => $day,
		'dayShort' => $day ? wp_date( 'D', strtotime( $day . ' 12:00' ), tapaufest_timezone() ) : __( 'TBA', 'tapaufest-2026' ),
		'time'     => $s ? $s['label'] : __( 'Time TBA', 'tapaufest-2026' ),
		'stage'    => $s ? $s['stage'] : '',
		'genre'    => $a['genre'],
		'cc'       => $a['country'],
		'country'  => $a['country_name'],
		'blurb'    => html_entity_decode( $blurb, ENT_QUOTES, 'UTF-8' ),
		'head'     => (bool) $a['headliner'],
		'main'     => $main,
		'label'    => $label,
		'listen'   => $a['listen'],
		'initials' => $a['initials'],
	);
};

$tf_cards = array();
$tf_n     = 0;
foreach ( $tf_groups as $tf_k => $tf_list ) {
	foreach ( $tf_list as $tf_a ) {
		$tf_cards[ $tf_k ][] = $tf_card( $tf_a, ++$tf_n, $tf_lastday );
	}
}
$tf_flat = array_merge( ...array_values( $tf_cards ?: array( array() ) ) );
$tf_word = array( __( 'one', 'tapaufest-2026' ), __( 'two', 'tapaufest-2026' ), __( 'three', 'tapaufest-2026' ) );
?>
<div class="tf2-main tf2-lineup" data-tf2-reel-root>
	<div class="tf2-wrap tf2-between tf2-reelhead">
		<div class="tf2-head" style="margin:0">
			<p class="tf2-eb"><span class="tf2-hide-sm"><?php esc_html_e( 'Tentative lineup · scroll sideways', 'tapaufest-2026' ); ?></span><span class="tf2-show-sm"><?php esc_html_e( 'Tentative lineup · swipe, tap for stories', 'tapaufest-2026' ); ?></span></p>
			<h1 class="tf2-h1 tf2-h1--xl"><?php esc_html_e( 'The weekend, in order', 'tapaufest-2026' ); ?></h1>
		</div>
		<?php if ( $tf_cards ) : ?>
			<div class="tf2-row tf2-reelnav">
				<?php $tf_i = 0; ?>
				<?php foreach ( array_keys( $tf_cards ) as $tf_k ) : ?>
					<?php if ( 'tba' === $tf_k ) : continue; endif; ?>
					<?php /* translators: %s: night number word. */ ?>
					<a class="tf2-chip" href="#reel-<?php echo esc_attr( $tf_k ); ?>" data-tf2-reel-jump="reel-<?php echo esc_attr( $tf_k ); ?>"><?php echo esc_html( sprintf( __( 'Night %s', 'tapaufest-2026' ), $tf_word[ min( $tf_i, 2 ) ] ) ); ?><span class="tf2-chip__day">&nbsp;·&nbsp;<?php echo esc_html( wp_date( 'D', strtotime( $tf_k . ' 12:00' ), tapaufest_timezone() ) ); ?></span></a>
					<?php ++$tf_i; ?>
				<?php endforeach; ?>
				<button type="button" class="tf2-btn tf2-btn--gold tf2-show-sm tf2-playall" data-tf2-story-open="0"><?php echo tf2_icon( 'play' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><span><?php esc_html_e( 'Play all', 'tapaufest-2026' ); ?></span></button>
				<span class="tf2-reelarrows tf2-hide-sm">
					<button type="button" class="tf2-reelarrow" data-tf2-reel-step="-1" aria-label="<?php esc_attr_e( 'Scroll back', 'tapaufest-2026' ); ?>"><?php echo tf2_icon( 'arrow-left' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></button>
					<button type="button" class="tf2-reelarrow" data-tf2-reel-step="1" aria-label="<?php esc_attr_e( 'Scroll on', 'tapaufest-2026' ); ?>"><?php echo tf2_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></button>
				</span>
			</div>
		<?php endif; ?>
	</div>

	<?php if ( ! $tf_cards ) : ?>
		<div class="tf2-wrap">
			<?php echo tf2_empty( 'music', __( 'Lineup drops soon.', 'tapaufest-2026' ), __( 'Join Geng TAPAU and hear it first.', 'tapaufest-2026' ), array( tf2_btn( __( 'Join free', 'tapaufest-2026' ), tapaufest_setting( 'join_url' ), 'cili', array( 'external' => true ) ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</div>
	<?php else : ?>
		<div class="tf2-reel" tabindex="0" data-tf2-reel aria-label="<?php esc_attr_e( 'Lineup, in running order. Scroll sideways.', 'tapaufest-2026' ); ?>">
			<?php $tf_i = 0; ?>
			<?php foreach ( $tf_cards as $tf_k => $tf_list ) : ?>
				<div class="tf2-reeldiv" id="reel-<?php echo esc_attr( $tf_k ); ?>">
					<?php if ( 'tba' === $tf_k ) : ?>
						<span class="tf2-d tf2-reeldiv__label"><?php esc_html_e( 'Still to come', 'tapaufest-2026' ); ?></span>
						<span class="tf2-reeldiv__sub"><?php esc_html_e( 'Slots announced soon', 'tapaufest-2026' ); ?></span>
					<?php else : ?>
						<span class="tf2-reeldiv__num"><?php echo esc_html( sprintf( '%02d', $tf_i + 1 ) ); ?></span>
						<?php /* translators: %s: night number word. */ ?>
						<span class="tf2-d tf2-reeldiv__label"><?php echo esc_html( sprintf( __( 'Night %s', 'tapaufest-2026' ), $tf_word[ min( $tf_i, 2 ) ] ) ); ?></span>
						<span class="tf2-reeldiv__sub"><?php echo esc_html( wp_date( 'l j M', strtotime( $tf_k . ' 12:00' ), tapaufest_timezone() ) ); ?></span>
						<?php $tf_close = end( $tf_list ); ?>
						<span class="tf2-reeldiv__run"><?php echo esc_html( $tf_list[0]['time'] . ' → ' . $tf_close['name'] ); ?> →</span>
						<?php ++$tf_i; ?>
					<?php endif; ?>
				</div>
				<?php foreach ( $tf_list as $tf_c ) : ?>
					<article class="tf2-poster<?php echo $tf_c['head'] ? ' is-head' : ''; ?><?php echo $tf_c['main'] ? ' is-main' : ''; ?>" id="act-<?php echo esc_attr( $tf_c['artist'] ); ?>" data-tf2-story="<?php echo (int) $tf_c['n'] - 1; ?>">
						<?php if ( $tf_c['img'] ) : ?>
							<img class="tf2-poster__img" src="<?php echo esc_url( $tf_c['img'] ); ?>" alt="" loading="<?php echo $tf_c['n'] <= 4 ? 'eager' : 'lazy'; ?>" decoding="async">
						<?php else : ?>
							<span class="tf2-poster__img tf2-poster__ph" aria-hidden="true">?</span>
						<?php endif; ?>
						<span class="tf2-poster__shade" aria-hidden="true"></span>
						<?php if ( $tf_c['label'] ) : ?>
							<span class="tf2-tag tf2-poster__tag"><?php echo esc_html( $tf_c['label'] ); ?></span>
						<?php endif; ?>
						<?php /* translators: 1: position, 2: total. */ ?>
						<span class="tf2-poster__n"><?php echo esc_html( sprintf( __( '%1$d / %2$d', 'tapaufest-2026' ), $tf_c['n'], $tf_total ) ); ?></span>
						<div class="tf2-poster__body">
							<span class="tf2-poster__pill"><?php echo esc_html( implode( ' · ', array_filter( array( $tf_c['dayShort'], $tf_c['time'], $tf_c['stage'] ) ) ) ); ?></span>
							<a class="tf2-poster__name" href="<?php echo esc_url( $tf_c['url'] ); ?>"><?php echo esc_html( $tf_c['name'] ); ?></a>
							<?php if ( $tf_c['blurb'] ) : ?>
								<p class="tf2-poster__blurb"><?php echo esc_html( $tf_c['blurb'] ); ?></p>
							<?php endif; ?>
							<div class="tf2-poster__acts">
								<?php if ( $tf_c['id'] ) : ?>
									<?php echo tapaufest_star_button( array( 'id' => $tf_c['id'], 'title' => $tf_c['name'] ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								<?php endif; ?>
								<?php if ( $tf_c['listen'] ) : ?>
									<?php /* translators: %s: act name. */ ?>
									<a class="tf2-poster__ico" href="<?php echo esc_url( $tf_c['listen'] ); ?>" target="_blank" rel="noopener" aria-label="<?php echo esc_attr( sprintf( __( 'Listen to %s (opens in a new tab)', 'tapaufest-2026' ), $tf_c['name'] ) ); ?>"><?php echo tf2_icon( 'play' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
								<?php endif; ?>
								<span class="tf2-poster__meta"><?php echo esc_html( implode( ' · ', array_filter( array( $tf_c['genre'], $tf_c['cc'] ) ) ) ); ?></span>
							</div>
						</div>
					</article>
				<?php endforeach; ?>
			<?php endforeach; ?>
			<span class="tf2-reelend" aria-hidden="true"></span>
		</div>

		<div class="tf2-wrap tf2-reeldots tf2-hide-sm">
			<p class="tf2-eb"><?php esc_html_e( 'Jump to a set', 'tapaufest-2026' ); ?></p>
			<div class="tf2-reeldots__grid">
				<?php $tf_i = 0; ?>
				<?php foreach ( $tf_cards as $tf_k => $tf_list ) : ?>
					<?php if ( 'tba' === $tf_k ) : continue; endif; ?>
					<div>
						<?php /* translators: %s: night number word. */ ?>
						<p class="tf2-reeldots__day"><?php echo esc_html( sprintf( __( 'Night %s', 'tapaufest-2026' ), $tf_word[ min( $tf_i, 2 ) ] ) . ' · ' . wp_date( 'D j M', strtotime( $tf_k . ' 12:00' ), tapaufest_timezone() ) ); ?></p>
						<div class="tf2-reeldots__row">
							<?php foreach ( $tf_list as $tf_c ) : ?>
								<a href="#act-<?php echo esc_attr( $tf_c['artist'] ); ?>" data-tf2-reel-jump="act-<?php echo esc_attr( $tf_c['artist'] ); ?>" class="<?php echo $tf_c['head'] ? 'is-head' : ''; ?>"><i aria-hidden="true"></i><span><?php echo esc_html( $tf_c['time'] ); ?></span><span class="screen-reader-text"><?php echo esc_html( ' ' . $tf_c['name'] ); ?></span></a>
							<?php endforeach; ?>
						</div>
					</div>
					<?php ++$tf_i; ?>
				<?php endforeach; ?>
			</div>
		</div>

		<div class="tf2-stories" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'Lineup stories', 'tapaufest-2026' ); ?>" data-tf2-stories hidden>
			<div class="tf2-stories__media" data-tf2-st-media></div>
			<div class="tf2-stories__top">
				<div class="tf2-stories__segs" data-tf2-st-segs aria-hidden="true"></div>
				<div class="tf2-stories__bar">
					<span class="tf2-stories__pos" data-tf2-st-pos aria-live="polite"></span>
					<button type="button" class="tf2-stories__close" data-tf2-st-close aria-label="<?php esc_attr_e( 'Close stories', 'tapaufest-2026' ); ?>"><?php echo tf2_icon( 'close' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></button>
				</div>
			</div>
			<button type="button" class="tf2-stories__tap is-prev" data-tf2-st-step="-1" aria-label="<?php esc_attr_e( 'Previous act', 'tapaufest-2026' ); ?>"></button>
			<button type="button" class="tf2-stories__tap is-next" data-tf2-st-step="1" aria-label="<?php esc_attr_e( 'Next act', 'tapaufest-2026' ); ?>"></button>
			<div class="tf2-stories__body" data-tf2-st-body></div>
		</div>
		<script type="application/json" id="tf2-story-data"><?php echo wp_json_encode( $tf_flat ); ?></script>
	<?php endif; ?>

	<?php if ( $tf_hosts ) : ?>
		<section class="tf2-wrap tf2-hosts">
			<?php echo tf2_head( __( 'On the mic', 'tapaufest-2026' ), __( 'Your hosts', 'tapaufest-2026' ), __( 'Stand-ins from 2025 until this year’s hosts are announced.', 'tapaufest-2026' ), 'h2' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<div class="tf2-auto">
				<?php
				foreach ( $tf_hosts as $tf_a ) {
					echo tf2_artist_card( $tf_a, false ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				}
				?>
			</div>
		</section>
	<?php endif; ?>
</div>
