<?php
/**
 * Small markup helpers shared by the festival screens (views/*.php).
 *
 * Every helper returns escaped HTML. Arguments named $html may hold safe
 * inline markup built by other helpers.
 *
 * @package Tapaufest2026
 */

defined( 'ABSPATH' ) || exit;

/**
 * Icon from the plugin sprite, or nothing if the plugin is off.
 *
 * @param string $name Icon name.
 * @return string
 */
function tf2_icon( $name ) {
	return function_exists( 'tapaufest_icon' ) ? tapaufest_icon( $name ) : '';
}

/**
 * Button link.
 *
 * @param string $label Label (plain text).
 * @param string $url   URL.
 * @param string $style cili, paper, ghost or gold.
 * @param array  $opts  icon, attrs (raw, pre-escaped), class, external.
 * @return string
 */
function tf2_btn( $label, $url, $style = 'paper', $opts = array() ) {
	$icon  = ! empty( $opts['icon'] ) ? tf2_icon( $opts['icon'] ) : '';
	$attrs = isset( $opts['attrs'] ) ? ' ' . $opts['attrs'] : '';
	$class = 'tf2-btn tf2-btn--' . $style . ( ! empty( $opts['class'] ) ? ' ' . $opts['class'] : '' );
	$ext   = ! empty( $opts['external'] ) ? ' target="_blank" rel="noopener"' : '';
	return '<a class="' . esc_attr( $class ) . '" href="' . esc_url( $url ) . '"' . $ext . $attrs . '>' . $icon . '<span>' . esc_html( $label ) . '</span></a>';
}

/**
 * Page or section head: eyebrow, title, lead.
 *
 * @param string $eyebrow Eyebrow.
 * @param string $title   Title.
 * @param string $lead    Lead (plain text).
 * @param string $tag     h1 or h2.
 * @return string
 */
function tf2_head( $eyebrow, $title, $lead = '', $tag = 'h1' ) {
	$out = '<header class="tf2-head">';
	if ( $eyebrow ) {
		$out .= '<p class="tf2-eb">' . esc_html( $eyebrow ) . '</p>';
	}
	$out .= '<' . $tag . ' class="tf2-' . $tag . '">' . esc_html( $title ) . '</' . $tag . '>';
	if ( $lead ) {
		$out .= '<p class="tf2-lead">' . esc_html( $lead ) . '</p>';
	}
	return $out . '</header>';
}

/**
 * Alert box.
 *
 * @param string $tone gold, cili, blue or ink.
 * @param string $icon Icon name.
 * @param string $html Message (safe inline HTML).
 * @param string $role status or alert.
 * @return string
 */
function tf2_alert( $tone, $icon, $html, $role = 'status' ) {
	return '<div class="tf2-alert tf2-alert--' . esc_attr( $tone ) . '" role="' . esc_attr( $role ) . '">' . tf2_icon( $icon ) . '<div>' . wp_kses_post( $html ) . '</div></div>';
}

/**
 * Empty or error state that always offers a way on.
 *
 * @param string $icon    Icon name.
 * @param string $title   Title.
 * @param string $line    One line.
 * @param array  $actions Button markup.
 * @param string $attrs   Extra attributes (pre-escaped).
 * @return string
 */
function tf2_empty( $icon, $title, $line, $actions = array(), $attrs = '' ) {
	return '<div class="tf2-empty"' . ( $attrs ? ' ' . $attrs : '' ) . '><span class="tf2-empty__icon" aria-hidden="true">' . tf2_icon( $icon ) . '</span>'
		. '<p class="tf2-empty__title">' . esc_html( $title ) . '</p><p class="tf2-empty__line">' . esc_html( $line ) . '</p>'
		. ( $actions ? '<div class="tf2-row tf2-row--center">' . implode( '', $actions ) . '</div>' : '' ) . '</div>';
}

/**
 * Bundled festival photo.
 *
 * @param string $name  Photo name (assets/images/2025).
 * @param string $alt   Alt text, '' for decorative.
 * @param string $class Extra class.
 * @return string
 */
function tf2_photo( $name, $alt = '', $class = '' ) {
	return '<img class="tf2-photo ' . esc_attr( $class ) . '" src="' . esc_url( tapaufest_photo_url( $name ) ) . '" alt="' . esc_attr( $alt ) . '" loading="lazy" decoding="async">';
}

/**
 * Line with a leading icon.
 *
 * @param string $html Text (safe inline HTML).
 * @param string $icon Icon name.
 * @return string
 */
function tf2_check( $html, $icon = 'check' ) {
	return '<li class="tf2-check">' . tf2_icon( $icon ) . '<span>' . wp_kses_post( $html ) . '</span></li>';
}

/**
 * Back link.
 *
 * @param string $label Label.
 * @param string $url   URL.
 * @return string
 */
function tf2_back( $label, $url ) {
	return '<a class="tf2-back" href="' . esc_url( $url ) . '">' . tf2_icon( 'arrow-left' ) . '<span>' . esc_html( $label ) . '</span></a>';
}

/**
 * Festival dates as "12 & 13 Dec 2026".
 *
 * @return string
 */
function tf2_when() {
	if ( ! function_exists( 'tapaufest_days' ) ) {
		return '';
	}
	$days = array_keys( tapaufest_days() );
	if ( ! $days ) {
		return '';
	}
	$tz    = tapaufest_timezone();
	$first = strtotime( $days[0] . ' 12:00:00' );
	$last  = strtotime( end( $days ) . ' 12:00:00' );
	return count( $days ) > 1 ? wp_date( 'j', $first, $tz ) . ' & ' . wp_date( 'j M Y', $last, $tz ) : wp_date( 'j M Y', $first, $tz );
}

/**
 * Short venue line: "Chongkak Park, Hulu Langat".
 *
 * @return string
 */
function tf2_where() {
	return __( 'Chongkak Park, Hulu Langat', 'tapaufest-2026' );
}

/**
 * Artist card for the screens: photo, name, when, star for their set.
 *
 * @param array $a     Artist data (tapaufest_artist_data()).
 * @param bool  $star  Show the star button.
 * @return string
 */
function tf2_artist_card( $a, $star = true ) {
	$s     = $a['session'];
	$days  = tapaufest_days();
	$when  = $s ? implode( ' · ', array_filter( array( isset( $days[ $s['day'] ] ) ? wp_date( 'D', strtotime( $s['day'] . ' 12:00' ) ) : '', $s['stage'], $s['label'] ) ) ) : __( 'Set time TBA', 'tapaufest-2026' );
	$thumb = get_the_post_thumbnail( $a['id'], 'medium_large', array( 'class' => 'tf2-card__img', 'alt' => '' ) );
	$img   = $thumb ? $thumb : '<span class="tf2-card__img tf2-card__ph has-' . esc_attr( tapaufest_tile_color( $a['id'] ) ) . '-background-color" aria-hidden="true">' . esc_html( $a['initials'] ) . '</span>';
	$out   = '<article class="tf2-card tf2-lift tf2-artist" data-country="' . esc_attr( $a['country'] ) . '" data-day="' . esc_attr( $s ? $s['day'] : '' ) . '" data-session="' . esc_attr( $s ? $s['id'] : '' ) . '">';
	$out  .= '<a class="tf2-artist__link" href="' . esc_url( $a['url'] ) . '">' . $img . '<span class="tf2-artist__body"><span class="tf2-artist__name">' . esc_html( $a['name'] ) . '</span><span class="tf2-artist__when">' . esc_html( $when ) . '</span>';
	if ( $a['country'] ) {
		$out .= '<abbr class="tf2-cc" title="' . esc_attr( $a['country_name'] ) . '">' . esc_html( $a['country'] ) . '</abbr>';
	}
	$out .= '</span></a>';
	if ( $star && $s ) {
		$out .= tapaufest_star_button( $s );
	}
	return $out . '</article>';
}

/**
 * Ticket tier as a wide row (tickets page) or a tall card (home).
 *
 * @param array  $t     Tier.
 * @param string $shape row or card.
 * @return string
 */
function tf2_tier( $t, $shape = 'row' ) {
	$state = tapaufest_state();
	$early = 'early' === $state && $t['early'] && $t['early'] < $t['regular'];
	$price = tapaufest_tier_price( $t );
	$art   = tapaufest_tier_art( $t );
	$out   = $t['soldout'];
	$tag   = '';
	if ( $out ) {
		/* translators: %d: number of passes. */
		$tag = $t['cap'] ? sprintf( __( 'All %d gone', 'tapaufest-2026' ), $t['cap'] ) : __( 'Sold out', 'tapaufest-2026' );
	} elseif ( $early ) {
		$tag = __( 'Early bird', 'tapaufest-2026' );
	} elseif ( $t['cap'] ) {
		/* translators: %d: number of passes. */
		$tag = sprintf( __( 'Only %d', 'tapaufest-2026' ), $t['cap'] );
	}
	$was = $early ? tapaufest_rm( $t['regular'] ) : '';
	if ( ! $early && 'early' !== $state && $t['regular'] && 1 === (int) $t['days'] && tapaufest_setting( 'door_price' ) ) {
		/* translators: %s: door price. */
		$was = sprintf( __( 'Door RM%s', 'tapaufest-2026' ), tapaufest_setting( 'door_price' ) );
	}
	$cls = 'tf2-tier tf2-tier--' . $shape . ( $out ? ' is-out' : '' );
	$html  = '<article class="' . esc_attr( $cls ) . '" data-tier="' . (int) $t['id'] . '" data-price="' . (int) $price . '" data-name="' . esc_attr( $t['name'] ) . '"' . ( $out ? ' aria-disabled="true"' : '' ) . '>';
	if ( $art ) {
		$html .= '<img class="tf2-tier__art" src="' . esc_url( $art ) . '" alt="" width="360" height="260" loading="lazy">';
	}
	$price_html = '<div class="tf2-tier__price"><b class="tf2-price">' . esc_html( tapaufest_rm( $price ) ) . '</b>';
	if ( $was ) {
		$price_html .= '<s class="tf2-was">' . esc_html( $was ) . '</s>';
	}
	if ( $tag ) {
		$price_html .= '<span class="tf2-tag' . ( $out ? ' tf2-tag--cili' : '' ) . '">' . esc_html( $tag ) . '</span>';
	}
	$price_html .= '</div>';
	$html .= '<div class="tf2-tier__body"><h3 class="tf2-tier__name">' . esc_html( $t['name'] ) . '</h3><p class="tf2-tier__who">' . esc_html( $t['who'] ) . '</p>';
	if ( 'card' === $shape ) {
		$html .= $price_html;
	}
	if ( $t['includes'] ) {
		$html .= '<ul class="tf2-checks">';
		foreach ( 'card' === $shape ? $t['includes'] : array_slice( $t['includes'], 0, 3 ) as $line ) {
			$html .= tf2_check( esc_html( $line ) );
		}
		$html .= '</ul>';
	}
	$html .= '</div>';
	if ( 'card' !== $shape ) {
		$html .= $price_html;
	}
	$html .= '</article>';
	return $html;
}

/**
 * Zone photo cards. Notices from the crew (weather, river) show as a tag.
 *
 * @param int  $limit Max zones, 0 for all.
 * @param bool $desc  Show the description line.
 * @return string
 */
function tf2_zone_grid( $limit = 0, $desc = false ) {
	$zones   = tapaufest_zones();
	$notices = tapaufest_notices();
	$subs    = array(
		'PENTAS'     => __( 'Main stage', 'tapaufest-2026' ),
		'LAYAR'      => __( 'Films', 'tapaufest-2026' ),
		'BENGKEL'    => __( 'Workshops', 'tapaufest-2026' ),
		'KEMBARA'    => __( 'Outdoor', 'tapaufest-2026' ),
		'CAMP VIBES' => __( 'Santuy Campsite', 'tapaufest-2026' ),
		'WARUNG'     => __( 'Makan', 'tapaufest-2026' ),
		'PASAR'      => __( 'Market', 'tapaufest-2026' ),
		'UNGGUN'     => __( 'Bonfire', 'tapaufest-2026' ),
	);
	if ( $limit ) {
		// Home: the six that sell the weekend.
		$pick  = array( 'PENTAS', 'LAYAR', 'BENGKEL', 'KEMBARA', 'CAMP VIBES', 'WARUNG' );
		$zones = array_values(
			array_filter(
				$zones,
				function ( $z ) use ( $pick ) {
					return in_array( $z[0], $pick, true );
				}
			)
		);
		$zones = array_slice( $zones, 0, $limit );
	}
	$out = '<div class="tf2-auto tf2-auto--lg tf2-zones">';
	foreach ( $zones as $z ) {
		$n     = isset( $notices[ $z[0] ] ) ? $notices[ $z[0] ] : null;
		$href  = in_array( $z[0], array( 'WARUNG', 'PASAR' ), true ) ? home_url( '/makan-pasar/' ) : home_url( '/experience/#zone-' . sanitize_title( $z[0] ) );
		$out  .= '<a class="tf2-card tf2-lift tf2-zone' . ( $n ? ' is-off' : '' ) . '" href="' . esc_url( $href ) . '">' . tf2_photo( $z[3], '' );
		if ( $n ) {
			$out .= '<span class="tf2-tag tf2-tag--cili">' . esc_html( 'off' === $n['status'] ? __( 'Off today', 'tapaufest-2026' ) : __( 'Paused', 'tapaufest-2026' ) ) . '</span>';
		}
		$out .= '<span class="tf2-zone__body"><span class="tf2-zone__name">' . esc_html( $z[0] ) . '</span><span class="tf2-zone__sub">' . esc_html( $n && $n['message'] ? $n['message'] : ( $desc ? $z[1] : ( isset( $subs[ $z[0] ] ) ? $subs[ $z[0] ] : $z[2] ) ) ) . '</span></span></a>';
	}
	return $out . '</div>';
}

/**
 * "On now" card for a session, with how far through the set we are.
 *
 * @param array $s   Session.
 * @param array $now tapaufest_now().
 * @return string
 */
function tf2_now_card( $s, $now ) {
	$img = '';
	if ( $s['artist'] ) {
		$img = get_the_post_thumbnail( $s['artist'], 'medium', array( 'alt' => '' ) );
	}
	if ( ! $img ) {
		$img = '<span class="tf2-card__ph has-' . esc_attr( tapaufest_tile_color( $s['id'] ) ) . '-background-color" aria-hidden="true">' . esc_html( mb_substr( $s['title'], 0, 1 ) ) . '</span>';
	}
	$pct = $s['length'] ? max( 4, min( 100, round( 100 * ( $now['minutes'] - $s['start_min'] ) / $s['length'] ) ) ) : 50;
	$end = tapaufest_format_time( sprintf( '%02d:%02d', floor( $s['end_min'] / 60 ), $s['end_min'] % 60 ) );
	$tag = $s['url'] ? 'a href="' . esc_url( $s['url'] ) . '"' : 'div';
	/* translators: %s: stage. */
	return '<' . $tag . ' class="tf2-card tf2-lift tf2-now">' . $img . '<span class="tf2-now__body"><span class="tf2-eb tf2-eb--cili tf2-now__eb"><span class="tf2-eq" aria-hidden="true"><i></i><i></i><i></i><i></i></span>' . esc_html( sprintf( __( 'On now · %s', 'tapaufest-2026' ), $s['stage'] ) ) . '</span>'
		. '<span class="tf2-d" style="font-size:2.2rem;color:var(--tf2-cili)">' . esc_html( html_entity_decode( $s['title'] ) ) . '</span>'
		/* translators: 1: start, 2: end. */
		. '<span style="font:600 .95rem var(--tf2-text);color:var(--tf2-ink)">' . esc_html( sprintf( __( '%1$s till %2$s', 'tapaufest-2026' ), $s['label'], $end ) ) . '</span>'
		. '<span class="tf2-progress tf2-progress--live" role="progressbar" aria-valuenow="' . (int) $pct . '" aria-valuemin="0" aria-valuemax="100" aria-label="' . esc_attr__( 'Set progress', 'tapaufest-2026' ) . '"><span style="width:' . (int) $pct . '%"></span></span></span></' . ( 'div' === $tag ? 'div' : 'a' ) . '>';
}

/**
 * Postponed: what happens to my pass.
 *
 * @return string
 */
function tf2_pass_safe_card() {
	return '<div class="tf2-card tf2-pad--lg tf2-stack tf2-stack--sm"><p style="font:800 1.25rem var(--tf2-text)">' . esc_html__( 'What happens to my pass?', 'tapaufest-2026' ) . '</p><ul class="tf2-checks">'
		. tf2_check( esc_html__( 'It works on the new dates. Nothing to do.', 'tapaufest-2026' ) )
		. tf2_check( esc_html__( 'Can’t make the new dates? Ask TAPAUtix for a refund within the window in your email.', 'tapaufest-2026' ) )
		. tf2_check( esc_html__( 'Camping bookings move with the fest. We email you.', 'tapaufest-2026' ), 'info' )
		. '</ul><p class="tf2-note">' . esc_html__( 'New dates go out by email and on @tapaufest first.', 'tapaufest-2026' ) . '</p></div>';
}

/**
 * Aftermovie hero.
 *
 * @param bool $h1 Use the page heading.
 * @return string
 */
function tf2_film( $h1 = false ) {
	$url = tapaufest_setting( 'aftermovie_url' );
	$tag = $h1 ? 'h1' : 'h2';
	$out = '<div class="tf2-film">' . tf2_photo( 'pentas-night', '' );
	if ( $url ) {
		$out .= '<a class="tf2-film__play" href="' . esc_url( $url ) . '" target="_blank" rel="noopener" aria-label="' . esc_attr__( 'Play the aftermovie', 'tapaufest-2026' ) . '">' . tf2_icon( 'play' ) . '</a>';
	}
	return $out . '<div class="tf2-film__cap"><p class="tf2-eb">' . esc_html__( 'That’s a wrap', 'tapaufest-2026' ) . '</p><' . $tag . ' class="tf2-h1" style="margin-top:6px">' . esc_html__( 'Terima kasih, geng.', 'tapaufest-2026' ) . '</' . $tag . '></div></div>';
}

/**
 * Recap actions: photos, survey, photo takedown.
 *
 * @return string
 */
function tf2_recap_cards() {
	$cards = array(
		array( 'camera', __( 'Photos are up', 'tapaufest-2026' ), __( 'Find yourself. Tag us.', 'tapaufest-2026' ), __( 'Open the gallery', 'tapaufest-2026' ), home_url( '/recap/' ), 'cili' ),
	);
	if ( tapaufest_setting( 'survey_url' ) ) {
		$cards[] = array( 'star', __( '2 min survey', 'tapaufest-2026' ), __( 'Tell us what to keep for next year.', 'tapaufest-2026' ), __( 'Take the survey', 'tapaufest-2026' ), tapaufest_setting( 'survey_url' ), 'paper' );
	}
	$cards[] = array( 'close', __( 'Photo takedown', 'tapaufest-2026' ), __( 'In a photo you want gone? We remove it, no questions.', 'tapaufest-2026' ), __( 'Ask us', 'tapaufest-2026' ), tapaufest_setting( 'takedown_url' ) ? tapaufest_setting( 'takedown_url' ) : 'https://www.instagram.com/tapaufest/', 'paper' );
	$out = '<div class="tf2-auto" style="grid-template-columns:repeat(auto-fit,minmax(280px,1fr));margin-top:48px">';
	foreach ( $cards as $c ) {
		$out .= '<div class="tf2-card tf2-pad tf2-stack tf2-stack--sm"><span style="color:var(--tf2-cili)">' . tf2_icon( $c[0] ) . '</span><p class="tf2-h3">' . esc_html( $c[1] ) . '</p><p>' . esc_html( $c[2] ) . '</p>' . tf2_btn( $c[3], $c[4], $c[5] ) . '</div>';
	}
	return $out . '</div>';
}

/**
 * Sessions on other stages that overlap this one.
 *
 * @param array $s Session.
 * @return array[]
 */
function tf2_overlaps( $s ) {
	$out = array();
	foreach ( tapaufest_get_sessions( $s['day'] ) as $o ) {
		if ( $o['id'] === $s['id'] || 'prayer' === $o['kind'] || $o['stage'] === $s['stage'] ) {
			continue;
		}
		if ( $o['start_min'] < $s['end_min'] && $s['start_min'] < $o['end_min'] ) {
			$out[] = $o;
		}
	}
	return $out;
}

/**
 * "Add to Jadual Aku" button for a session (the plugin script fills in the state).
 *
 * @param array $s Session.
 * @return string
 */
function tf2_star_button( $s ) {
	return '<button type="button" class="tf2-btn tf2-btn--cili" data-tf-star="' . (int) $s['id'] . '" data-tf-star-label aria-pressed="false">' . tf2_icon( 'star' ) . ' <span>' . esc_html__( 'Add to Jadual Aku', 'tapaufest-2026' ) . '</span></button>';
}

/**
 * Small linked card for "also on" rows.
 *
 * @param string $url   URL.
 * @param string $img   Image markup.
 * @param string $title Title.
 * @param string $sub   Sub line.
 * @return string
 */
function tf2_mini( $url, $img, $title, $sub ) {
	return '<a class="tf2-card tf2-lift tf2-mini" href="' . esc_url( $url ) . '">' . $img . '<span><b>' . esc_html( html_entity_decode( $title ) ) . '</b><span>' . esc_html( $sub ) . '</span></span></a>';
}

/**
 * Share button (native share on phones, copy link elsewhere).
 *
 * @param string $title Title to share.
 * @return string
 */
function tf2_share_button( $title ) {
	return '<button type="button" class="tf2-btn tf2-btn--ghost" data-tf2-share-page data-title="' . esc_attr( $title ) . '">' . tf2_icon( 'share' ) . '<span>' . esc_html__( 'Share', 'tapaufest-2026' ) . '</span></button>';
}

/**
 * "Ping me" form for a sold-out pass.
 *
 * @param int  $tier  Tier ID.
 * @param bool $error Show the email error.
 * @return string
 */
function tf2_ping_form( $tier, $error = false ) {
	$id = 'tf2-ping-' . (int) $tier;
	return '<form class="tf2-stack tf2-stack--sm" method="post" action="' . esc_url( admin_url( 'admin-post.php' ) ) . '" data-tf2-ping style="width:100%;max-width:420px">'
		. '<input type="hidden" name="action" value="tapaufest_ping"><input type="hidden" name="tf_ping_tier" value="' . (int) $tier . '">' . wp_nonce_field( 'tapaufest_ping', 'tf_ping_nonce', true, false )
		. '<label class="tf2-field" for="' . esc_attr( $id ) . '" style="text-align:left">' . esc_html__( 'Your email', 'tapaufest-2026' ) . '<input id="' . esc_attr( $id ) . '" type="email" name="tf_ping_email" required autocomplete="email"></label>'
		. '<p class="tf2-field__err" data-tf2-ping-err' . ( $error ? '' : ' hidden' ) . '>' . esc_html__( 'That email doesn’t look right.', 'tapaufest-2026' ) . '</p>'
		. '<button type="submit" class="tf2-btn tf2-btn--cili">' . tf2_icon( 'bell' ) . '<span>' . esc_html__( 'Ping me', 'tapaufest-2026' ) . '</span></button></form>';
}
