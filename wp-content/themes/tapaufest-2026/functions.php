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
	// In <head>, not deferred: the pagereveal listener has to exist before first render.
	wp_enqueue_script( 'tapaufest-2026-view-transitions', get_theme_file_uri( 'assets/view-transitions.js' ), array(), wp_get_theme()->get( 'Version' ), array( 'in_footer' => false ) );
	wp_enqueue_style( 'tapaufest-2026-screens', get_theme_file_uri( 'assets/v2.css' ), array( 'tapaufest-2026' ), wp_get_theme()->get( 'Version' ) );
	wp_enqueue_script( 'tapaufest-2026-screens', get_theme_file_uri( 'assets/v2.js' ), array(), wp_get_theme()->get( 'Version' ), array( 'in_footer' => true, 'strategy' => 'defer' ) );
	wp_enqueue_script( 'tapaufest-2026-motion', get_theme_file_uri( 'assets/motion.js' ), array(), wp_get_theme()->get( 'Version' ), array( 'in_footer' => true, 'strategy' => 'defer' ) );
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
 * @param string   $image Optional 2025 photo name (see tapaufest_photo_url()).
 * @return string
 */
function tapaufest_card( $title, $paras, $image = '' ) {
	$class = $image ? 'is-style-tf-ink-card tf-photo-card' : 'is-style-tf-ink-card';
	$out   = '<!-- wp:group {"className":"' . $class . '","layout":{"type":"default"}} --><div class="wp-block-group ' . $class . '">';
	if ( $image ) {
		$out .= tapaufest_photo( $image, '', 'tf-card-photo' );
	}
	$out .= '<!-- wp:heading {"level":3} --><h3 class="wp-block-heading">' . esc_html( $title ) . '</h3><!-- /wp:heading -->';
	foreach ( (array) $paras as $p ) {
		$out .= '<!-- wp:paragraph --><p>' . wp_kses_post( $p ) . '</p><!-- /wp:paragraph -->';
	}
	return $out . '</div><!-- /wp:group -->';
}

/**
 * URL of a photo bundled with the theme (TAPAUfest 2025 shots, stand-ins until 2026 media lands).
 *
 * @param string $name File name without extension, in assets/images/2025.
 * @return string
 */
function tapaufest_photo_url( $name ) {
	return get_theme_file_uri( 'assets/images/2025/' . sanitize_file_name( $name ) . '.webp' );
}

/**
 * Image block for a bundled photo.
 *
 * @param string $name    Photo name.
 * @param string $alt     Alt text (empty for decorative).
 * @param string $class   Extra class.
 * @param string $caption Optional caption.
 * @return string
 */
function tapaufest_photo( $name, $alt = '', $class = '', $caption = '' ) {
	$cls = trim( 'wp-block-image size-large ' . $class );
	$out = '<!-- wp:image {"sizeSlug":"large","className":"' . esc_attr( $class ) . '"} --><figure class="' . esc_attr( $cls ) . '"><img src="' . esc_url( tapaufest_photo_url( $name ) ) . '" alt="' . esc_attr( $alt ) . '" loading="lazy"/>';
	if ( $caption ) {
		$out .= '<figcaption class="wp-element-caption">' . esc_html( $caption ) . '</figcaption>';
	}
	return $out . '</figure><!-- /wp:image -->';
}

/**
 * Photo strip: one big photo and up to four small ones.
 *
 * @param array $photos Name => caption pairs. The first is the big one.
 * @return string
 */
function tapaufest_photo_strip( $photos ) {
	$out = '<!-- wp:group {"className":"tf-photo-strip","layout":{"type":"default"}} --><div class="wp-block-group tf-photo-strip">';
	foreach ( $photos as $name => $caption ) {
		$out .= tapaufest_photo( $name, $caption, 'tf-strip-photo', $caption );
	}
	return $out . '</div><!-- /wp:group -->';
}

/**
 * Responsive grid of cards.
 *
 * @param string[] $cards Card markup.
 * @param string   $min   Minimum column width.
 * @param string   $class Extra class name.
 * @return string
 */
function tapaufest_grid( $cards, $min = '15rem', $class = '' ) {
	$attrs = $class ? ',"className":"' . esc_attr( $class ) . '"' : '';
	return '<!-- wp:group {"layout":{"type":"grid","minimumColumnWidth":"' . esc_attr( $min ) . '"}' . $attrs . '} --><div class="wp-block-group' . ( $class ? ' ' . esc_attr( $class ) : '' ) . '">'
		. implode( '', $cards )
		. '</div><!-- /wp:group -->';
}

/**
 * Heading for screen readers only, to keep the outline in order where a
 * visible heading would repeat the page title.
 *
 * @param string $text Heading text.
 * @return string
 */
function tapaufest_sr_heading( $text ) {
	return '<!-- wp:heading {"className":"screen-reader-text"} --><h2 class="wp-block-heading screen-reader-text">' . esc_html( $text ) . '</h2><!-- /wp:heading -->';
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
 * @return array[] Name, description, note, photo.
 */
function tapaufest_zones() {
	return array(
		array( 'PENTAS', __( 'Main stage. 12 live sets from top and rising South East Asian indie acts, both nights.', 'tapaufest-2026' ), __( 'Tentative lineup: 12 acts from 4 countries', 'tapaufest-2026' ), 'pentas-day' ),
		array( 'BENGKEL', __( 'Interactive art workshops, sharing sessions and panels with indie ecosystem leaders, under a shaded tent.', 'tapaufest-2026' ), __( '10am to 4pm', 'tapaufest-2026' ), 'bengkel-sharing' ),
		array( 'LAYAR', __( 'Indie shorts and documentaries. After PENTAS, an open-air starlight cinema under the forest canopy.', 'tapaufest-2026' ), __( 'Night screenings weather permitting', 'tapaufest-2026' ), 'layar' ),
		array( 'PASAR', __( 'Indie market: artworks, records and band merch, with DJs spinning while you shop.', 'tapaufest-2026' ), __( 'Indie artisans, brands and musicians', 'tapaufest-2026' ), 'pasar-dj' ),
		array( 'WARUNG', __( 'A curated pasar malam of Malaysian street food from indie businesses and Hulu Langat vendors, at fair prices.', 'tapaufest-2026' ), __( 'Free refills at the Water Station', 'tapaufest-2026' ), 'warung-forest' ),
		array( 'KEMBARA', __( 'River dips, Skytrex canopy zip-lines, forest treks, and morning bike and trail runs.', 'tapaufest-2026' ), __( 'Weather dependent', 'tapaufest-2026' ), 'kembara-skytrex' ),
		array( 'CAMP VIBES', __( 'Camp two nights inside the festival grounds at Santuy Campsite, or book a campsite or chalet nearby.', 'tapaufest-2026' ), __( 'Set up by the Santrav team', 'tapaufest-2026' ), 'santuy-campsite' ),
		array( 'UNGGUN', __( 'Bonfire sessions at Santuy Campsite, late into the night.', 'tapaufest-2026' ), __( 'After PENTAS closes', 'tapaufest-2026' ), 'santuy-bonfire' ),
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
		$cards[] = tapaufest_card( $z[0], $paras, isset( $z[3] ) ? $z[3] : '' );
	}
	return tapaufest_grid( $cards, '14rem', 'tf-zone-grid' );
}

/**
 * Aftermovie link card plus stats.
 *
 * @param string $caption Link caption.
 * @param string $url     Link URL.
 * @param array  $stats   Number => label pairs.
 * @param string $photo   Background photo name, or '' for none.
 * @param bool   $juice   Add the Juice Best Of '25 badge card.
 * @return string
 */
function tapaufest_film( $caption, $url, $stats, $photo = 'pentas-day', $juice = false ) {
	$out  = '<!-- wp:columns --><div class="wp-block-columns">';
	$out .= '<!-- wp:column {"width":"66.66%"} --><div class="wp-block-column" style="flex-basis:66.66%">';
	$out .= '<!-- wp:html --><a class="tf-reel" href="' . esc_url( $url ) . '"' . ( $photo ? ' style="background-image:linear-gradient(rgba(30,18,8,.25),rgba(30,18,8,.55)),url(' . esc_url( tapaufest_photo_url( $photo ) ) . ')"' : '' ) . '><span class="tf-reel__play" aria-hidden="true"><svg viewBox="0 0 10 12"><path d="M0 0L10 6L0 12z"/></svg></span><span class="tf-reel__cap">' . esc_html( $caption ) . '</span></a><!-- /wp:html -->';
	$out .= '</div><!-- /wp:column -->';
	$out .= '<!-- wp:column {"width":"33.33%"} --><div class="wp-block-column" style="flex-basis:33.33%">';
	foreach ( $stats as $num => $label ) {
		$out .= '<!-- wp:group {"className":"is-style-tf-ink-card","layout":{"type":"default"}} --><div class="wp-block-group is-style-tf-ink-card">';
		$out .= '<!-- wp:paragraph {"className":"tf-stat"} --><p class="tf-stat"><b>' . esc_html( $num ) . '</b>' . esc_html( $label ) . '</p><!-- /wp:paragraph -->';
		$out .= '</div><!-- /wp:group -->';
	}
	if ( $juice ) {
		$out .= '<!-- wp:group {"className":"is-style-tf-ink-card tf-juice","layout":{"type":"default"}} --><div class="wp-block-group is-style-tf-ink-card tf-juice">';
		$out .= '<!-- wp:image {"sizeSlug":"full"} --><figure class="wp-block-image size-full"><img src="' . esc_url( get_theme_file_uri( 'assets/images/juice-best-of-25.webp' ) ) . '" alt="' . esc_attr__( 'Juice Best Of ’25', 'tapaufest-2026' ) . '" width="600" height="271" loading="lazy"/></figure><!-- /wp:image -->';
		$out .= '<!-- wp:paragraph {"className":"tf-juice__cap"} --><p class="tf-juice__cap">' . esc_html__( 'Voted Best Festival on Juice Best Of ’25', 'tapaufest-2026' ) . '</p><!-- /wp:paragraph -->';
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

/**
 * Page descriptions used for the meta description and social cards.
 *
 * @return array Path => description.
 */
function tapaufest_page_descriptions() {
	return array(
		'/'           => __( 'TAPAUfest 2026: an art x nature weekend of South East Asian indie music, films, workshops and camping at Chongkak Park & Resort, Hulu Langat. 12 and 13 Dec. Tickets on TAPAUtix.', 'tapaufest-2026' ),
		'lineup'      => __( 'Who’s playing TAPAUfest 2026. Filter by day and country, and play a track before you go.', 'tapaufest-2026' ),
		'timetable'   => __( 'Set times for every stage at TAPAUfest 2026. Star your sets, catch clashes and add them to your calendar.', 'tapaufest-2026' ),
		'tickets'     => __( 'Day, Weekend and Student Weekend Tickets for TAPAUfest 2026, from RM98. Sold on TAPAUtix.', 'tapaufest-2026' ),
		'experience'  => __( 'PENTAS, BENGKEL, LAYAR, PASAR, WARUNG, KEMBARA and CAMP VIBES: everything running at TAPAUfest 2026.', 'tapaufest-2026' ),
		'plan'        => __( 'Getting to Chongkak Park & Resort by car or MRT and shuttle, where to camp or stay, and what to pack for TAPAUfest 2026.', 'tapaufest-2026' ),
		'map'         => __( 'Site map, first aid, lost and found and live updates for TAPAUfest 2026.', 'tapaufest-2026' ),
		'faq'         => __( 'Tickets, kids, rain, refunds and what you can bring to TAPAUfest 2026.', 'tapaufest-2026' ),
		'recap'       => __( 'Photos, the aftermovie and set recordings from TAPAUfest.', 'tapaufest-2026' ),
		'archive'     => __( 'Every TAPAUfest since the first crowdfunded edition in Penang in 2014.', 'tapaufest-2026' ),
		'partners'    => __( 'The supporters, brands, programme partners and media behind TAPAUfest.', 'tapaufest-2026' ),
		'sell'        => __( 'Apply for a food, market, brand or activity stall at TAPAUfest 2026, Chongkak Park & Resort, 12 and 13 Dec.', 'tapaufest-2026' ),
	);
}

/**
 * Title: "Lineup · TAPAUfest 2026", and the date and venue on the home page.
 *
 * @param array $parts Title parts.
 * @return array
 */
function tapaufest_title_parts( $parts ) {
	$edition = tapaufest_theme_setting( 'edition', 'TAPAUfest 2026' );
	if ( is_front_page() ) {
		$parts['title']   = $edition;
		$parts['tagline'] = __( '12 & 13 Dec, Chongkak Park, Hulu Langat', 'tapaufest-2026' );
	} else {
		if ( is_post_type_archive( 'tf_artist' ) ) {
			$parts['title'] = __( 'Lineup', 'tapaufest-2026' );
		}
		$parts['site'] = $edition;
	}
	return $parts;
}
add_filter( 'document_title_parts', 'tapaufest_title_parts' );
add_filter(
	'document_title_separator',
	function () {
		return '·';
	}
);

/**
 * Meta description, canonical, Open Graph and X card tags.
 */
function tapaufest_social_meta() {
	if ( is_admin() || is_404() ) {
		return;
	}
	$desc  = tapaufest_page_descriptions();
	$text  = $desc['/'];
	$image = get_theme_file_uri( 'assets/images/og-tapaufest-2026.jpg' );
	$url   = home_url( '/' );
	if ( is_singular( 'tf_artist' ) ) {
		$post  = get_queried_object();
		$text  = has_excerpt( $post ) ? get_the_excerpt( $post ) : $desc['lineup'];
		$url   = get_permalink( $post );
		$thumb = get_the_post_thumbnail_url( $post, 'large' );
		$image = $thumb ? $thumb : $image;
	} elseif ( is_post_type_archive( 'tf_artist' ) ) {
		$text = $desc['lineup'];
		$url  = get_post_type_archive_link( 'tf_artist' );
	} elseif ( is_page() ) {
		$slug = get_post_field( 'post_name', get_queried_object_id() );
		$text = isset( $desc[ $slug ] ) ? $desc[ $slug ] : $text;
		$url  = get_permalink();
	}
	$title = wp_get_document_title();
	$tags  = array(
		array( 'name', 'description', $text ),
		array( 'property', 'og:type', 'website' ),
		array( 'property', 'og:site_name', 'TAPAUfest' ),
		array( 'property', 'og:title', $title ),
		array( 'property', 'og:description', $text ),
		array( 'property', 'og:url', $url ),
		array( 'property', 'og:image', $image ),
		array( 'property', 'og:locale', 'en_MY' ),
		array( 'name', 'twitter:card', 'summary_large_image' ),
		array( 'name', 'twitter:site', '@TAPAUfest' ),
	);
	foreach ( $tags as $tag ) {
		printf( '<meta %s="%s" content="%s">' . "\n", esc_attr( $tag[0] ), esc_attr( $tag[1] ), esc_attr( $tag[2] ) );
	}
	if ( ! is_singular() || is_front_page() ) {
		printf( '<link rel="canonical" href="%s">' . "\n", esc_url( $url ) );
	}
}
add_action( 'wp_head', 'tapaufest_social_meta', 5 );

/**
 * Whether the site is in the recap state.
 *
 * @return bool
 */
function tapaufest_is_recap() {
	return function_exists( 'tapaufest_state' ) && 'recap' === tapaufest_state();
}

/**
 * A setup note for editors only. Logged-out visitors see nothing.
 *
 * @param string $text Note.
 * @return string
 */
function tapaufest_admin_note( $text ) {
	return current_user_can( 'edit_pages' ) ? tapaufest_tbc( $text ) : '';
}

/**
 * Flag motion support before first paint, so the hero entrance doesn't flash.
 * Visitors who prefer reduced motion never get the class.
 */
function tapaufest_motion_flag() {
	echo "<script>try{if(!window.matchMedia('(prefers-reduced-motion: reduce)').matches){document.documentElement.classList.add('tf-motion')}}catch(e){}</script>\n";
}
add_action( 'wp_head', 'tapaufest_motion_flag', 1 );

/**
 * Social icon for a footer link (needs the TAPAUfest Core icon set).
 *
 * @param string $url Link URL.
 * @return string
 */
function tapaufest_footer_icon( $url ) {
	if ( ! function_exists( 'tapaufest_icon' ) ) {
		return '';
	}
	$host = (string) wp_parse_url( $url, PHP_URL_HOST );
	$map  = array(
		'instagram.com' => 'instagram',
		'tiktok.com'    => 'tiktok',
		'x.com'         => 'x',
		'twitter.com'   => 'x',
		'facebook.com'  => 'facebook',
		'youtube.com'   => 'youtube',
		'whatsapp.com'  => 'whatsapp',
	);
	foreach ( $map as $domain => $icon ) {
		if ( $host === $domain || substr( $host, -strlen( '.' . $domain ) ) === '.' . $domain ) {
			return tapaufest_icon( $icon );
		}
	}
	return '';
}

/**
 * Keep the festival name as "TAPAUfest" even where a style sets capitals
 * (eyebrows, notes, footer heads). Wraps the name in text, never inside
 * tags or attributes.
 *
 * @param string $content Block HTML.
 * @return string
 */
function tapaufest_keep_name_case( $content ) {
	if ( false === strpos( $content, 'TAPAUfest' ) ) {
		return $content;
	}
	$parts = preg_split( '/(<[^>]+>)/', $content, -1, PREG_SPLIT_DELIM_CAPTURE );
	$skip  = 0;
	foreach ( $parts as $i => $part ) {
		if ( '' === $part ) {
			continue;
		}
		if ( '<' === $part[0] ) {
			if ( preg_match( '#^<(script|style|textarea|title|option)\b#i', $part ) ) {
				++$skip;
			} elseif ( preg_match( '#^</(script|style|textarea|title|option)>#i', $part ) ) {
				$skip = max( 0, $skip - 1 );
			} elseif ( preg_match( '#^<span class="tf-name">#', $part ) ) {
				return $content; // Already done.
			}
			continue;
		}
		if ( ! $skip && false !== strpos( $part, 'TAPAUfest' ) ) {
			$parts[ $i ] = str_replace( 'TAPAUfest', '<span class="tf-name">TAPAUfest</span>', $part );
		}
	}
	return implode( '', $parts );
}
add_filter(
	'render_block',
	function ( $content, $block ) {
		$name = isset( $block['blockName'] ) ? (string) $block['blockName'] : '';
		// Leaf text blocks only, so nested groups are not wrapped twice.
		if ( in_array( $name, array( 'core/paragraph', 'core/heading', 'core/list-item', 'core/button' ), true ) || 0 === strpos( $name, 'tapaufest/' ) ) {
			return tapaufest_keep_name_case( $content );
		}
		return $content;
	},
	30,
	2
);

require_once get_theme_file_path( 'views/_kit.php' );

/**
 * Body class for pages built from festival screens.
 *
 * @param string[] $classes Classes.
 * @return string[]
 */
function tapaufest_screen_body_class( $classes ) {
	$classes[] = 'tf2-page';
	return $classes;
}
add_filter( 'body_class', 'tapaufest_screen_body_class' );
