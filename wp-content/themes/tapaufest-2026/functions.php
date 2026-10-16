<?php
/**
 * TAPAUfest 2026 theme setup.
 *
 * @package Tapaufest2026
 */

defined( 'ABSPATH' ) || exit;

/**
 * Theme supports that theme.json can't express.
 */
function tapaufest_theme_setup() {
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'editor-styles' );
	add_editor_style( 'style.css' );
	load_theme_textdomain( 'tapaufest-2026', get_template_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'tapaufest_theme_setup' );

/**
 * Front-end stylesheet.
 */
function tapaufest_theme_assets() {
	wp_enqueue_style( 'tapaufest-2026', get_stylesheet_uri(), array(), wp_get_theme()->get( 'Version' ) );
}
add_action( 'wp_enqueue_scripts', 'tapaufest_theme_assets' );

/**
 * Block styles used across the patterns.
 */
function tapaufest_theme_block_styles() {
	register_block_style(
		'core/group',
		array(
			'name'  => 'tf-band',
			'label' => __( 'Kopi band', 'tapaufest-2026' ),
		)
	);
	register_block_style(
		'core/group',
		array(
			'name'  => 'tf-paper-band',
			'label' => __( 'Paper band', 'tapaufest-2026' ),
		)
	);
	register_block_style(
		'core/group',
		array(
			'name'  => 'tf-ink-card',
			'label' => __( 'Ink card', 'tapaufest-2026' ),
		)
	);
	register_block_style(
		'core/column',
		array(
			'name'  => 'tf-ink-card',
			'label' => __( 'Ink card', 'tapaufest-2026' ),
		)
	);
	register_block_style(
		'core/button',
		array(
			'name'  => 'tf-cili',
			'label' => __( 'Cili', 'tapaufest-2026' ),
		)
	);
	register_block_pattern_category( 'tapaufest', array( 'label' => __( 'TAPAUfest', 'tapaufest-2026' ) ) );
	register_block_pattern_category( 'tapaufest-pages', array( 'label' => __( 'TAPAUfest pages', 'tapaufest-2026' ) ) );
}
add_action( 'init', 'tapaufest_theme_block_styles' );

/**
 * Cili button style.
 */
function tapaufest_theme_inline_styles() {
	wp_add_inline_style(
		'tapaufest-2026',
		'.wp-block-button.is-style-tf-cili .wp-block-button__link{background-color:var(--wp--preset--color--cili);color:var(--wp--preset--color--paper-hi)}'
	);
}
add_action( 'wp_enqueue_scripts', 'tapaufest_theme_inline_styles', 20 );

/**
 * Remind admins the theme needs the TAPAUfest Core plugin.
 */
function tapaufest_theme_plugin_notice() {
	if ( function_exists( 'tapaufest_state' ) || ! current_user_can( 'activate_plugins' ) ) {
		return;
	}
	echo '<div class="notice notice-warning"><p>' . esc_html__( 'The TAPAUfest 2026 theme needs the TAPAUfest Core plugin for the lineup, timetable, tickets and FAQ. Activate it under Plugins.', 'tapaufest-2026' ) . '</p></div>';
}
add_action( 'admin_notices', 'tapaufest_theme_plugin_notice' );

/**
 * Point the browser icon at the festival mark when no Site Icon is set.
 */
function tapaufest_theme_favicon() {
	if ( has_site_icon() ) {
		return;
	}
	echo '<link rel="icon" href="' . esc_url( get_theme_file_uri( 'assets/images/site-icon.png' ) ) . '" sizes="192x192">' . "\n";
}
add_action( 'wp_head', 'tapaufest_theme_favicon' );

/**
 * Read a TAPAUfest Core setting, with a fallback when the plugin is off.
 *
 * @param string $key      Setting key.
 * @param string $fallback Fallback value.
 * @return string
 */
function tapaufest_theme_setting( $key, $fallback = '' ) {
	if ( function_exists( 'tapaufest_setting' ) ) {
		$value = tapaufest_setting( $key );
		return '' !== (string) $value ? (string) $value : $fallback;
	}
	return $fallback;
}

/**
 * Opening markup for a full-width section band (used by patterns).
 *
 * @param string $style 'paper' or 'kopi'.
 * @param string $extra Extra class names.
 * @return string
 */
function tapaufest_band_open( $style = 'kopi', $extra = '' ) {
	$class = 'paper' === $style ? 'is-style-tf-paper-band' : 'is-style-tf-band';
	$class = trim( $class . ' ' . $extra );
	return '<!-- wp:group {"align":"full","className":"' . esc_attr( $class ) . '","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60"}}},"layout":{"type":"constrained","contentSize":"1120px"}} -->'
		. '<div class="wp-block-group alignfull ' . esc_attr( $class ) . '" style="padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60)">';
}

/**
 * Closing markup for a band.
 *
 * @return string
 */
function tapaufest_band_close() {
	return '</div><!-- /wp:group -->';
}

/**
 * Section or page heading group: eyebrow, heading, optional lead.
 *
 * @param string $eyebrow Eyebrow.
 * @param string $title   Heading.
 * @param string $lead    Lead paragraph (may contain safe inline HTML).
 * @param int    $level   Heading level.
 * @return string
 */
function tapaufest_heading( $eyebrow, $title, $lead = '', $level = 2 ) {
	$out = '<!-- wp:group {"className":"tf-section-head","layout":{"type":"default"}} --><div class="wp-block-group tf-section-head">';
	if ( $eyebrow ) {
		$out .= '<!-- wp:paragraph {"className":"tf-eyebrow"} --><p class="tf-eyebrow">' . esc_html( $eyebrow ) . '</p><!-- /wp:paragraph -->';
	}
	$out .= '<!-- wp:heading {"level":' . (int) $level . '} --><h' . (int) $level . ' class="wp-block-heading">' . esc_html( $title ) . '</h' . (int) $level . '><!-- /wp:heading -->';
	if ( $lead ) {
		$out .= '<!-- wp:paragraph {"className":"tf-lead"} --><p class="tf-lead">' . wp_kses_post( $lead ) . '</p><!-- /wp:paragraph -->';
	}
	return $out . '</div><!-- /wp:group -->';
}

/**
 * Page head band used at the top of festival pages.
 *
 * @param string $eyebrow Eyebrow.
 * @param string $title   Page title.
 * @param string $lead    Lead.
 * @return string
 */
function tapaufest_page_head( $eyebrow, $title, $lead = '' ) {
	return '<!-- wp:group {"align":"full","className":"tf-page-head","layout":{"type":"constrained","contentSize":"1120px"}} --><div class="wp-block-group alignfull tf-page-head">'
		. tapaufest_heading( $eyebrow, $title, $lead, 1 )
		. '</div><!-- /wp:group -->';
}

/**
 * Ink card group with a heading and paragraphs.
 *
 * @param string   $title Card title.
 * @param string[] $paras Paragraphs (may contain safe inline HTML).
 * @return string
 */
function tapaufest_card( $title, $paras ) {
	$out = '<!-- wp:group {"className":"is-style-tf-ink-card","layout":{"type":"default"}} --><div class="wp-block-group is-style-tf-ink-card">';
	$out .= '<!-- wp:heading {"level":3} --><h3 class="wp-block-heading">' . esc_html( $title ) . '</h3><!-- /wp:heading -->';
	foreach ( (array) $paras as $p ) {
		$out .= '<!-- wp:paragraph --><p>' . wp_kses_post( $p ) . '</p><!-- /wp:paragraph -->';
	}
	return $out . '</div><!-- /wp:group -->';
}

/**
 * Responsive grid of cards.
 *
 * @param string[] $cards Card markup.
 * @param string   $min   Minimum column width.
 * @return string
 */
function tapaufest_grid( $cards, $min = '15rem' ) {
	return '<!-- wp:group {"layout":{"type":"grid","minimumColumnWidth":"' . esc_attr( $min ) . '"}} --><div class="wp-block-group">'
		. implode( '', $cards )
		. '</div><!-- /wp:group -->';
}

/**
 * "To be confirmed" marker.
 *
 * @param string $text Text.
 * @return string
 */
function tapaufest_tbc( $text ) {
	return '<mark class="tf-tbc">' . esc_html( $text ) . '</mark>';
}

/**
 * Festival zones, as shown on the home page and the Experience page.
 *
 * @return array[] Name, description, note.
 */
function tapaufest_zones() {
	return array(
		array( 'PENTAS', __( 'Main stage. Indie acts from across South East Asia, both nights.', 'tapaufest-2026' ), '' ),
		array( 'BENGKEL', __( 'Workshops and sharing sessions: zines, phone recording, screenprinting.', 'tapaufest-2026' ), __( 'Sign up on site', 'tapaufest-2026' ) ),
		array( 'LAYAR', __( 'Indie short films and documentaries.', 'tapaufest-2026' ), '' ),
		array( 'PASAR', __( 'Indie market: merch, vinyl, zines and crafts.', 'tapaufest-2026' ), '' ),
		array( 'WARUNG', __( 'All-day makan from local kitchens.', 'tapaufest-2026' ), '' ),
		array( 'KEMBARA', __( 'River walks, sunrise yoga and a waterfall trail.', 'tapaufest-2026' ), __( 'Weather dependent', 'tapaufest-2026' ) ),
		array( 'KHEMAH', __( 'Camp two nights at the official festival campsite.', 'tapaufest-2026' ), __( 'With Duo Campers passes', 'tapaufest-2026' ) ),
		array( 'UNGGUN', __( 'Late bonfire sets at the campsite.', 'tapaufest-2026' ), __( 'After midnight', 'tapaufest-2026' ) ),
	);
}

/**
 * Zone cards markup.
 *
 * @param int $limit Max cards, 0 for all.
 * @return string
 */
function tapaufest_zone_cards( $limit = 0 ) {
	$zones = tapaufest_zones();
	if ( $limit ) {
		$zones = array_slice( $zones, 0, $limit );
	}
	$cards = array();
	foreach ( $zones as $z ) {
		$paras = array( esc_html( $z[1] ) );
		if ( $z[2] ) {
			$paras[] = '<strong class="tf-note">' . esc_html( $z[2] ) . '</strong>';
		}
		$cards[] = tapaufest_card( $z[0], $paras );
	}
	return tapaufest_grid( $cards, '14rem' );
}

/**
 * Aftermovie link card plus stats.
 *
 * @param string $caption Link caption.
 * @param string $url     Link URL.
 * @param array  $stats   Number => label pairs.
 * @return string
 */
function tapaufest_film( $caption, $url, $stats ) {
	$out  = '<!-- wp:columns --><div class="wp-block-columns">';
	$out .= '<!-- wp:column {"width":"66.66%"} --><div class="wp-block-column" style="flex-basis:66.66%">';
	$out .= '<!-- wp:html --><a class="tf-reel" href="' . esc_url( $url ) . '"><span class="tf-reel__play" aria-hidden="true"><svg viewBox="0 0 10 12"><path d="M0 0L10 6L0 12z"/></svg></span><span class="tf-reel__cap">' . esc_html( $caption ) . '</span></a><!-- /wp:html -->';
	$out .= '</div><!-- /wp:column -->';
	$out .= '<!-- wp:column {"width":"33.33%"} --><div class="wp-block-column" style="flex-basis:33.33%">';
	foreach ( $stats as $num => $label ) {
		$out .= '<!-- wp:group {"className":"is-style-tf-ink-card","layout":{"type":"default"}} --><div class="wp-block-group is-style-tf-ink-card">';
		$out .= '<!-- wp:paragraph {"className":"tf-stat"} --><p class="tf-stat"><b>' . esc_html( $num ) . '</b>' . esc_html( $label ) . '</p><!-- /wp:paragraph -->';
		$out .= '</div><!-- /wp:group -->';
	}
	$out .= '</div><!-- /wp:column --></div><!-- /wp:columns -->';
	return $out;
}

/**
 * "More" link paragraph.
 *
 * @param string $label Label.
 * @param string $path  Path.
 * @return string
 */
function tapaufest_more_link( $label, $path ) {
	return '<!-- wp:paragraph {"className":"tf-more"} --><p class="tf-more"><a href="' . esc_url( home_url( $path ) ) . '">' . esc_html( $label ) . '</a></p><!-- /wp:paragraph -->';
}
