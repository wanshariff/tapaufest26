<?php
/**
 * Block registration, shared assets and shared render helpers.
 *
 * Every block is dynamic (server-rendered from block.json "render"), so the
 * editor preview and the live page always match and no build step is needed.
 *
 * @package TapaufestCore
 */

defined( 'ABSPATH' ) || exit;

/**
 * Block folder names.
 *
 * @return string[]
 */
function tapaufest_block_names() {
	return array( 'alert-bar', 'header-cta', 'hero', 'lineup', 'artist-details', 'timetable', 'now-next', 'tickets', 'vendors', 'faq', 'sticky-cta', 'ticker', 'site-map', 'partners', 'session-details', 'vendor-details', 'vendor-apply' );
}

/**
 * Register assets and blocks.
 */
function tapaufest_register_blocks() {
	wp_register_style( 'tapaufest-blocks', TAPAUFEST_URL . 'assets/blocks.css', array(), TAPAUFEST_VERSION );
	wp_register_script(
		'tapaufest-blocks-editor',
		TAPAUFEST_URL . 'assets/editor.js',
		array( 'wp-blocks', 'wp-element', 'wp-components', 'wp-block-editor', 'wp-server-side-render', 'wp-i18n' ),
		TAPAUFEST_VERSION,
		true
	);
	wp_register_script( 'tapaufest-view', TAPAUFEST_URL . 'assets/view.js', array(), TAPAUFEST_VERSION, array( 'in_footer' => true, 'strategy' => 'defer' ) );
	// Interactive map: Leaflet (bundled, BSD-2) plus our map script.
	wp_register_style( 'tapaufest-leaflet', TAPAUFEST_URL . 'assets/vendor/leaflet/leaflet.css', array(), '1.9.4' );
	wp_register_script( 'tapaufest-leaflet', TAPAUFEST_URL . 'assets/vendor/leaflet/leaflet.js', array(), '1.9.4', array( 'in_footer' => true ) );
	wp_register_script( 'tapaufest-map', TAPAUFEST_URL . 'assets/map.js', array( 'tapaufest-leaflet' ), TAPAUFEST_VERSION, array( 'in_footer' => true ) );
	wp_localize_script(
		'tapaufest-view',
		'tapaufestView',
		array(
			'icsUrl'  => esc_url_raw( rest_url( 'tapaufest/v1/ics' ) ),
			'pageUrl' => esc_url_raw( home_url( '/timetable/' ) ),
			'i18n'    => array(
				'added'    => __( 'Added to Jadual Aku', 'tapaufest' ),
				'clash'    => __( 'Added. Heads up, it clashes with another pick.', 'tapaufest' ),
				'removed'  => __( 'Removed from Jadual Aku', 'tapaufest' ),
				'copied'   => __( 'Link copied. Send it to your geng.', 'tapaufest' ),
				'imported' => __( 'Your geng’s picks are in your Jadual Aku.', 'tapaufest' ),
			),
		)
	);

	foreach ( tapaufest_block_names() as $name ) {
		register_block_type( TAPAUFEST_DIR . 'blocks/' . $name );
	}
}
add_action( 'init', 'tapaufest_register_blocks' );

/**
 * Load the shared stylesheet and script on every front-end page.
 * Both are small and the header and footer blocks appear site-wide.
 */
function tapaufest_enqueue_front() {
	wp_enqueue_style( 'tapaufest-blocks' );
	wp_enqueue_script( 'tapaufest-view' );
}
add_action( 'wp_enqueue_scripts', 'tapaufest_enqueue_front' );

/**
 * Block category.
 *
 * @param array $categories Categories.
 * @return array
 */
function tapaufest_block_category( $categories ) {
	array_unshift(
		$categories,
		array(
			'slug'  => 'tapaufest',
			'title' => __( 'TAPAUfest', 'tapaufest' ),
		)
	);
	return $categories;
}
add_filter( 'block_categories_all', 'tapaufest_block_category' );

/**
 * One shared sheet for listen previews and other pop-ups.
 */
function tapaufest_print_sheet() {
	?>
	<dialog class="tf-sheet" id="tf-sheet" aria-labelledby="tf-sheet-title">
		<div class="tf-sheet__inner">
			<span class="tf-sheet__grab" aria-hidden="true"></span>
			<h2 class="tf-sheet__title" id="tf-sheet-title"></h2>
			<div class="tf-sheet__body"></div>
			<button type="button" class="tf-sheet__close" data-tf-close><?php esc_html_e( 'Close', 'tapaufest' ); ?></button>
		</div>
	</dialog>
	<div class="tf-toast" role="status" aria-live="polite" hidden></div>
	<?php
}
add_action( 'wp_footer', 'tapaufest_print_sheet' );

/**
 * Play icon.
 *
 * @return string
 */
function tapaufest_play_icon() {
	return '<svg viewBox="0 0 10 12" aria-hidden="true" focusable="false"><path d="M0 0L10 6L0 12z"/></svg>';
}

/**
 * Cached oEmbed HTML for a listen link.
 *
 * @param string $url Bandcamp or YouTube URL.
 * @return string
 */
function tapaufest_embed( $url ) {
	if ( ! $url ) {
		return '';
	}
	$key  = 'tf_embed_' . md5( $url );
	$html = get_transient( $key );
	if ( false === $html ) {
		$html = wp_oembed_get( $url, array( 'width' => 560 ) );
		$html = $html ? $html : '';
		set_transient( $key, $html, $html ? WEEK_IN_SECONDS : HOUR_IN_SECONDS );
	}
	return $html;
}

/**
 * Hidden template holding an artist's listen sheet content.
 *
 * @param array $a Artist data.
 * @return string
 */
function tapaufest_listen_template( $a ) {
	$embed = tapaufest_embed( $a['listen'] );
	$body  = $embed
		? '<div class="tf-player">' . $embed . '</div>'
		: '<div class="tf-player tf-player--empty">' . esc_html__( 'A track from this artist plays here once their Bandcamp or YouTube link is added.', 'tapaufest' ) . '</div>';
	$body .= '<p class="tf-sheet__acts"><a class="tf-btn" href="' . esc_url( $a['url'] ) . '">' . esc_html__( 'Set time and details', 'tapaufest' ) . '</a></p>';
	return '<template id="tf-listen-' . (int) $a['id'] . '" data-title="' . esc_attr( $a['name'] ) . '">' . $body . '</template>';
}

/**
 * Colour for an artist's placeholder tile, stable per artist.
 *
 * @param int $id Artist ID.
 * @return string Palette slug.
 */
function tapaufest_tile_color( $id ) {
	$slugs = array( 'cili', 'specs', 'gold', 'kopi', 'cili-deep', 'specs-deep' );
	return $slugs[ absint( $id ) % count( $slugs ) ];
}

/**
 * Artist card markup.
 *
 * @param array $a    Artist data.
 * @param bool  $big  Use the headliner size.
 * @return string
 */
function tapaufest_artist_card( $a, $big = false ) {
	$s     = $a['session'];
	$days  = tapaufest_days();
	$day   = $s && isset( $days[ $s['day'] ] ) ? $days[ $s['day'] ] : '';
	$thumb = get_the_post_thumbnail( $a['id'], 'medium_large', array( 'class' => 'tf-card__img', 'alt' => '' ) );
	$photo = $thumb ? $thumb : '<span class="tf-card__ph has-' . esc_attr( tapaufest_tile_color( $a['id'] ) ) . '-background-color" aria-hidden="true">' . esc_html( $a['initials'] ) . '</span>';
	$sub   = array_filter( array( $a['genre'], $day ) );

	$html  = '<article class="tf-artist' . ( $big && $a['headliner'] ? ' tf-artist--big' : '' ) . '" data-country="' . esc_attr( $a['country'] ) . '" data-day="' . esc_attr( $s ? $s['day'] : '' ) . '">';
	$html .= '<a class="tf-artist__link" href="' . esc_url( $a['url'] ) . '">' . $photo . '<span class="tf-artist__name">' . esc_html( $a['name'] ) . '</span>';
	$html .= '<span class="tf-artist__sub">' . esc_html( implode( ' · ', $sub ) ) . '</span></a>';
	$html .= '<div class="tf-artist__meta">';
	if ( $a['country'] ) {
		$html .= '<abbr class="tf-cc" title="' . esc_attr( $a['country_name'] ) . '">' . esc_html( $a['country'] ) . '</abbr>';
	}
	/* translators: %s: artist name. */
	$html .= '<button type="button" class="tf-play" data-tf-listen="' . (int) $a['id'] . '" aria-label="' . esc_attr( sprintf( __( 'Listen to %s', 'tapaufest' ), $a['name'] ) ) . '">' . tapaufest_play_icon() . '</button>';
	$html .= '</div></article>';
	return $html;
}

/**
 * Star toggle for Jadual Aku.
 *
 * @param array $s Session data.
 * @return string
 */
function tapaufest_star_button( $s ) {
	return sprintf(
		'<button type="button" class="tf-star" data-tf-star="%1$d" aria-pressed="false" aria-label="%2$s"><span aria-hidden="true">☆</span></button>',
		(int) $s['id'],
		/* translators: %s: set title. */
		esc_attr( sprintf( __( 'Add %s to Jadual Aku', 'tapaufest' ), $s['title'] ) )
	);
}

/**
 * Data attributes describing a session for the front-end script.
 *
 * @param array $s Session data.
 * @return string
 */
function tapaufest_session_attrs( $s ) {
	$days = tapaufest_days();
	return sprintf(
		' data-tf-session="%1$d" data-day="%2$s" data-day-label="%3$s" data-start="%4$d" data-end="%5$d" data-title="%6$s" data-stage="%7$s" data-time="%8$s"',
		(int) $s['id'],
		esc_attr( $s['day'] ),
		esc_attr( isset( $days[ $s['day'] ] ) ? $days[ $s['day'] ] : $s['day'] ),
		(int) $s['start_min'],
		(int) $s['end_min'],
		esc_attr( $s['title'] ),
		esc_attr( $s['stage'] ),
		esc_attr( $s['label'] )
	);
}

/**
 * Ticket button link and label for the current state.
 *
 * @return array Label, URL, and whether sales are open.
 */
function tapaufest_cta() {
	switch ( tapaufest_state() ) {
		case 'live':
			return array(
				'label' => __( 'Map and help', 'tapaufest' ),
				'url'   => home_url( '/map/' ),
			);
		case 'recap':
			return array(
				'label' => __( 'See the recap', 'tapaufest' ),
				'url'   => home_url( '/recap/' ),
			);
		default:
			return array(
				'label' => __( 'Get tickets', 'tapaufest' ),
				'url'   => home_url( '/tickets/' ),
			);
	}
}

/**
 * Now / Next cards for each stage.
 *
 * @return string
 */
function tapaufest_now_next_html() {
	$now    = tapaufest_now();
	$stages = tapaufest_now_next();
	$html   = '';
	if ( $now['preview'] ) {
		$html .= '<p class="tf-preview-note">' . esc_html__( 'Preview: showing Day 1 at 8:50pm. On festival days this follows the real time.', 'tapaufest' ) . '</p>';
	}
	if ( ! $stages ) {
		return $html . '<p class="tf-empty">' . esc_html__( 'Nothing on right now. Check the timetable for what’s next.', 'tapaufest' ) . '</p>';
	}
	$html .= '<div class="tf-nownext">';
	foreach ( $stages as $stage => $pair ) {
		$html .= '<div class="tf-stagecard"><h3 class="tf-stagecard__name">' . esc_html( $stage ) . '</h3>';
		if ( $pair['now'] ) {
			$title = $pair['now']['url'] ? '<a href="' . esc_url( $pair['now']['url'] ) . '">' . esc_html( $pair['now']['title'] ) . '</a>' : esc_html( $pair['now']['title'] );
			$html .= '<div class="tf-stagecard__now"><span class="tf-dot" aria-hidden="true"></span><div><p class="tf-label">' . esc_html__( 'On now', 'tapaufest' ) . '</p><p class="tf-stagecard__act">' . $title . '</p></div></div>';
		} else {
			$html .= '<p class="tf-label">' . esc_html__( 'Between sets', 'tapaufest' ) . '</p>';
		}
		if ( $pair['next'] ) {
			/* translators: %s: start time. */
			$html .= '<p class="tf-stagecard__next"><b>' . esc_html( sprintf( __( 'Next %s:', 'tapaufest' ), $pair['next']['label'] ) ) . '</b> ' . esc_html( $pair['next']['title'] ) . '</p>';
		} else {
			$html .= '<p class="tf-stagecard__next">' . esc_html__( 'Done for tonight', 'tapaufest' ) . '</p>';
		}
		$html .= '</div>';
	}
	return $html . '</div>';
}

/**
 * URL of the edition logo: the site logo if one is set, else the theme's.
 *
 * @return string
 */
function tapaufest_logo_url() {
	$url = '';
	$id  = (int) get_theme_mod( 'custom_logo' );
	if ( ! $id ) {
		$id = (int) get_option( 'site_logo' );
	}
	if ( $id ) {
		$url = wp_get_attachment_image_url( $id, 'full' );
	}
	if ( ! $url && file_exists( get_theme_file_path( 'assets/images/tapaufest-2026-logo.webp' ) ) ) {
		$url = get_theme_file_uri( 'assets/images/tapaufest-2026-logo.webp' );
	}
	/**
	 * Filters the edition logo used in the hero.
	 *
	 * @param string $url Logo URL.
	 */
	return apply_filters( 'tapaufest_logo_url', $url );
}
