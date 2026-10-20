<?php
/**
 * REST: calendar export for Jadual Aku.
 *
 * GET /wp-json/tapaufest/v1/ics?ids=12,15,18 returns an .ics file with
 * those sets, so visitors can add their picks to any calendar app.
 *
 * @package TapaufestCore
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register routes.
 */
function tapaufest_register_routes() {
	register_rest_route(
		'tapaufest/v1',
		'/ics',
		array(
			'methods'             => 'GET',
			'callback'            => 'tapaufest_rest_ics',
			'permission_callback' => '__return_true',
			'args'                => array(
				'ids' => array(
					'type'              => 'string',
					'required'          => false,
					'sanitize_callback' => 'sanitize_text_field',
				),
				'fest' => array(
					'type'     => 'boolean',
					'required' => false,
				),
			),
		)
	);
}
add_action( 'rest_api_init', 'tapaufest_register_routes' );

/**
 * Escape text for an iCalendar property.
 *
 * @param string $text Text.
 * @return string
 */
function tapaufest_ics_escape( $text ) {
	return str_replace( array( '\\', ';', ',', "\n" ), array( '\\\\', '\;', '\,', '\n' ), wp_strip_all_tags( html_entity_decode( $text, ENT_QUOTES, 'UTF-8' ) ) );
}

/**
 * Build and send the calendar file.
 *
 * @param WP_REST_Request $request Request.
 * @return WP_REST_Response|WP_Error
 */
function tapaufest_rest_ics( $request ) {
	$ids = array_slice( array_filter( array_map( 'absint', explode( ',', (string) $request->get_param( 'ids' ) ) ) ), 0, 60 );
	$fest = (bool) $request->get_param( 'fest' );
	if ( ! $ids && ! $fest ) {
		return new WP_Error( 'tapaufest_no_sets', __( 'Pick at least one set first.', 'tapaufest' ), array( 'status' => 400 ) );
	}

	$tz      = tapaufest_timezone();
	$utc     = new DateTimeZone( 'UTC' );
	$host    = wp_parse_url( home_url(), PHP_URL_HOST );
	$edition = tapaufest_setting( 'edition' );
	$venue   = tapaufest_setting( 'venue' );
	$lines   = array( 'BEGIN:VCALENDAR', 'VERSION:2.0', 'PRODID:-//TAPAUasia//TAPAUfest//EN', 'CALSCALE:GREGORIAN', 'X-WR-CALNAME:' . tapaufest_ics_escape( $edition . ' · Jadual Aku' ) );
	$stamp   = gmdate( 'Ymd\THis\Z' );

	if ( $fest ) {
		// The festival itself: gate open till late, each day.
		foreach ( array_keys( tapaufest_days() ) as $n => $day ) {
			$open = DateTimeImmutable::createFromFormat( 'Y-m-d H:i', $day . ' ' . tapaufest_setting( 'gates_open' ), $tz );
			if ( ! $open ) {
				continue;
			}
			$lines[] = 'BEGIN:VEVENT';
			$lines[] = 'UID:tapaufest-day-' . $day . '@' . $host;
			$lines[] = 'DTSTAMP:' . $stamp;
			$lines[] = 'DTSTART:' . $open->setTimezone( $utc )->format( 'Ymd\THis\Z' );
			$lines[] = 'DTEND:' . $open->setTime( 23, 59 )->setTimezone( $utc )->format( 'Ymd\THis\Z' );
			$lines[] = 'SUMMARY:' . tapaufest_ics_escape( $edition . ' · Day ' . ( $n + 1 ) );
			$lines[] = 'LOCATION:' . tapaufest_ics_escape( $venue );
			$lines[] = 'URL:' . esc_url_raw( home_url( '/' ) );
			$lines[] = 'END:VEVENT';
		}
	}

	foreach ( tapaufest_get_sessions() as $s ) {
		if ( ! in_array( (int) $s['id'], $ids, true ) || ! $s['day'] || '' === $s['start'] ) {
			continue;
		}
		$base = DateTimeImmutable::createFromFormat( 'Y-m-d H:i', $s['day'] . ' 00:00', $tz );
		if ( ! $base ) {
			continue;
		}
		$start   = $base->modify( '+' . (int) $s['start_min'] . ' minutes' )->setTimezone( $utc );
		$end     = $base->modify( '+' . (int) max( $s['end_min'], $s['start_min'] + 30 ) . ' minutes' )->setTimezone( $utc );
		$lines[] = 'BEGIN:VEVENT';
		$lines[] = 'UID:tapaufest-' . (int) $s['id'] . '@' . $host;
		$lines[] = 'DTSTAMP:' . $stamp;
		$lines[] = 'DTSTART:' . $start->format( 'Ymd\THis\Z' );
		$lines[] = 'DTEND:' . $end->format( 'Ymd\THis\Z' );
		$lines[] = 'SUMMARY:' . tapaufest_ics_escape( $s['title'] . ' · ' . $s['stage'] );
		$lines[] = 'LOCATION:' . tapaufest_ics_escape( $s['stage'] . ', ' . $edition . ', ' . $venue );
		if ( $s['url'] ) {
			$lines[] = 'URL:' . esc_url_raw( $s['url'] );
		}
		$lines[] = 'END:VEVENT';
	}
	$lines[] = 'END:VCALENDAR';

	$response = new WP_REST_Response();
	$response->set_headers(
		array(
			'Content-Type'        => 'text/calendar; charset=utf-8',
			'Content-Disposition' => 'attachment; filename="tapaufest-jadual-aku.ics"',
		)
	);
	add_filter(
		'rest_pre_serve_request',
		function ( $served ) use ( $lines ) {
			echo implode( "\r\n", $lines ) . "\r\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- iCalendar text, escaped per property above.
			return true;
		}
	);
	return $response;
}
