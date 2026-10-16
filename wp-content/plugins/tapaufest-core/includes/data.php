<?php
/**
 * Read helpers shared by blocks and the REST endpoint.
 *
 * @package TapaufestCore
 */

defined( 'ABSPATH' ) || exit;

/**
 * Minutes since midnight for an HH:MM string (24:30 = 1470).
 *
 * @param string $time Time.
 * @return int
 */
function tapaufest_minutes( $time ) {
	if ( ! preg_match( '/^(\d{1,2}):(\d{2})$/', (string) $time, $m ) ) {
		return 0;
	}
	return ( (int) $m[1] * 60 ) + (int) $m[2];
}

/**
 * Format HH:MM as 9pm / 9:45pm. Hours past 24 wrap to the next morning.
 *
 * @param string $time Time.
 * @return string
 */
function tapaufest_format_time( $time ) {
	if ( '' === (string) $time ) {
		return '';
	}
	$mins   = tapaufest_minutes( $time );
	$hour   = (int) floor( $mins / 60 ) % 24;
	$minute = $mins % 60;
	$suffix = $hour >= 12 ? 'pm' : 'am';
	$h12    = $hour % 12 ? $hour % 12 : 12;
	return $h12 . ( $minute ? ':' . str_pad( (string) $minute, 2, '0', STR_PAD_LEFT ) : '' ) . $suffix;
}

/**
 * Published artists, headliners first.
 *
 * @param int $limit Max artists, 0 for all.
 * @return WP_Post[]
 */
function tapaufest_get_artists( $limit = 0 ) {
	return get_posts(
		array(
			'post_type'      => 'tf_artist',
			'posts_per_page' => $limit > 0 ? $limit : -1,
			'orderby'        => array(
				'menu_order' => 'ASC',
				'title'      => 'ASC',
			),
		)
	);
}

/**
 * Artist data for templates.
 *
 * @param WP_Post|int $artist Artist.
 * @return array
 */
function tapaufest_artist_data( $artist ) {
	$artist    = get_post( $artist );
	$countries = tapaufest_countries();
	$country   = (string) get_post_meta( $artist->ID, 'tf_country', true );
	$name      = get_the_title( $artist );
	$words     = preg_split( '/\s+/', trim( $name ) );
	$initials  = '';
	foreach ( array_slice( $words, 0, 3 ) as $word ) {
		$initials .= function_exists( 'mb_substr' ) ? mb_substr( $word, 0, 1 ) : substr( $word, 0, 1 );
	}
	return array(
		'id'           => $artist->ID,
		'name'         => $name,
		'url'          => get_permalink( $artist ),
		'country'      => $country,
		'country_name' => isset( $countries[ $country ] ) ? $countries[ $country ] : $country,
		'genre'        => (string) get_post_meta( $artist->ID, 'tf_genre', true ),
		'headliner'    => (bool) get_post_meta( $artist->ID, 'tf_headliner', true ),
		'listen'       => (string) get_post_meta( $artist->ID, 'tf_listen', true ),
		'profile'      => (string) get_post_meta( $artist->ID, 'tf_profile', true ),
		'initials'     => $initials,
		'session'      => tapaufest_artist_session( $artist->ID ),
	);
}

/**
 * All sessions, sorted by day then start.
 *
 * @param string $day Optional Y-m-d filter.
 * @return array[]
 */
function tapaufest_get_sessions( $day = '' ) {
	static $cache = null;
	if ( null === $cache ) {
		$cache = array();
		$posts = get_posts(
			array(
				'post_type'      => 'tf_session',
				'posts_per_page' => -1,
				'no_found_rows'  => true,
			)
		);
		foreach ( $posts as $post ) {
			$cache[] = tapaufest_session_data( $post );
		}
		usort(
			$cache,
			function ( $a, $b ) {
				if ( $a['day'] === $b['day'] ) {
					return $a['start_min'] - $b['start_min'];
				}
				return strcmp( $a['day'], $b['day'] );
			}
		);
	}
	if ( '' === $day ) {
		return $cache;
	}
	return array_values(
		array_filter(
			$cache,
			function ( $s ) use ( $day ) {
				return $s['day'] === $day;
			}
		)
	);
}

/**
 * Session data.
 *
 * @param WP_Post $post Session post.
 * @return array
 */
function tapaufest_session_data( $post ) {
	$kind   = (string) get_post_meta( $post->ID, 'tf_kind', true );
	$kind   = $kind ? $kind : 'other';
	$artist = (int) get_post_meta( $post->ID, 'tf_artist', true );
	$start  = (string) get_post_meta( $post->ID, 'tf_start', true );
	$length = (int) get_post_meta( $post->ID, 'tf_length', true );
	$title  = $post->post_title;
	$url    = '';
	if ( 'act' === $kind && $artist && 'publish' === get_post_status( $artist ) ) {
		$title = get_the_title( $artist );
		$url   = get_permalink( $artist );
	}
	$start_min = tapaufest_minutes( $start );
	return array(
		'id'        => $post->ID,
		'title'     => $title,
		'url'       => $url,
		'kind'      => $kind,
		'artist'    => $artist,
		'day'       => (string) get_post_meta( $post->ID, 'tf_day', true ),
		'stage'     => (string) get_post_meta( $post->ID, 'tf_stage', true ),
		'start'     => $start,
		'start_min' => $start_min,
		'end_min'   => $start_min + max( 0, $length ),
		'length'    => $length,
		'label'     => tapaufest_format_time( $start ),
	);
}

/**
 * First set for an artist.
 *
 * @param int $artist_id Artist ID.
 * @return array|null
 */
function tapaufest_artist_session( $artist_id ) {
	foreach ( tapaufest_get_sessions() as $session ) {
		if ( 'act' === $session['kind'] && (int) $session['artist'] === (int) $artist_id ) {
			return $session;
		}
	}
	return null;
}

/**
 * Ticket tiers.
 *
 * @return array[]
 */
function tapaufest_get_tiers() {
	$out   = array();
	$posts = get_posts(
		array(
			'post_type'      => 'tf_tier',
			'posts_per_page' => -1,
			'orderby'        => array(
				'menu_order' => 'ASC',
				'title'      => 'ASC',
			),
		)
	);
	foreach ( $posts as $post ) {
		$includes = array_filter( array_map( 'trim', preg_split( '/\r\n|\r|\n/', (string) get_post_meta( $post->ID, 'tf_includes', true ) ) ) );
		$out[]    = array(
			'id'       => $post->ID,
			'name'     => $post->post_title,
			'who'      => (string) get_post_meta( $post->ID, 'tf_who', true ),
			'people'   => max( 1, (int) get_post_meta( $post->ID, 'tf_people', true ) ),
			'days'     => max( 1, (int) get_post_meta( $post->ID, 'tf_days', true ) ),
			'early'    => (int) get_post_meta( $post->ID, 'tf_early', true ),
			'regular'  => (int) get_post_meta( $post->ID, 'tf_regular', true ),
			'includes' => $includes,
			'campsite' => (bool) get_post_meta( $post->ID, 'tf_campsite', true ),
			'url'      => (string) get_post_meta( $post->ID, 'tf_url', true ),
			'soldout'  => (bool) get_post_meta( $post->ID, 'tf_soldout', true ),
		);
	}
	return $out;
}

/**
 * Format a ringgit amount.
 *
 * @param int $amount Amount.
 * @return string
 */
function tapaufest_rm( $amount ) {
	return $amount ? 'RM' . number_format_i18n( $amount ) : __( 'RM TBC', 'tapaufest' );
}

/**
 * The time used for "now" on festival days.
 *
 * Outside the festival dates live mode previews Day 1 at 8:50pm, so the
 * Now / Next cards can be reviewed before the event.
 *
 * @return array Day (Y-m-d), minutes since midnight, preview flag.
 */
function tapaufest_now() {
	$tz   = tapaufest_timezone();
	$now  = new DateTimeImmutable( 'now', $tz );
	$days = array_keys( tapaufest_days() );
	$day  = $now->format( 'Y-m-d' );
	$mins = ( (int) $now->format( 'G' ) * 60 ) + (int) $now->format( 'i' );

	// Before 6am counts as the previous festival night (sets past midnight).
	if ( $mins < 360 ) {
		$prev = $now->modify( '-1 day' )->format( 'Y-m-d' );
		if ( in_array( $prev, $days, true ) ) {
			return array(
				'day'     => $prev,
				'minutes' => $mins + 1440,
				'preview' => false,
			);
		}
	}
	if ( in_array( $day, $days, true ) ) {
		return array(
			'day'     => $day,
			'minutes' => $mins,
			'preview' => false,
		);
	}
	return array(
		'day'     => $days ? $days[0] : $day,
		'minutes' => ( 20 * 60 ) + 50,
		'preview' => true,
	);
}

/**
 * Now and next per stage.
 *
 * @return array Stage => array( now, next ).
 */
function tapaufest_now_next() {
	$now = tapaufest_now();
	$out = array();
	foreach ( array_keys( tapaufest_stages() ) as $stage ) {
		$current = null;
		$next    = null;
		foreach ( tapaufest_get_sessions( $now['day'] ) as $s ) {
			if ( $s['stage'] !== $stage || 'prayer' === $s['kind'] ) {
				continue;
			}
			if ( $s['start_min'] <= $now['minutes'] && $s['end_min'] > $now['minutes'] ) {
				$current = $s;
			} elseif ( $s['start_min'] > $now['minutes'] && null === $next ) {
				$next = $s;
			}
		}
		if ( $current || $next ) {
			$out[ $stage ] = array(
				'now'  => $current,
				'next' => $next,
			);
		}
	}
	return $out;
}

/**
 * Days left until gates open on Day 1.
 *
 * @return int
 */
function tapaufest_days_to_go() {
	$tz    = tapaufest_timezone();
	$start = DateTimeImmutable::createFromFormat( 'Y-m-d H:i', tapaufest_setting( 'day1' ) . ' ' . tapaufest_setting( 'gates_open' ), $tz );
	if ( ! $start ) {
		return 0;
	}
	$diff = $start->getTimestamp() - time();
	return max( 0, (int) ceil( $diff / DAY_IN_SECONDS ) );
}
