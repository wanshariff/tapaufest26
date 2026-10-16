<?php
/**
 * Interactive site map: zones and facilities, with a detail card for each.
 *
 * Positions are a draft until production sends the real layout. Change them
 * with the `tapaufest_map_spots` filter rather than editing this file.
 *
 * @package TapaufestCore
 */

defined( 'ABSPATH' ) || exit;

/**
 * Map spots. Shapes use a 1000×640 viewBox.
 *
 * Keys: label, kind, shape (rect: x,y,w,h | circle: cx,cy,r), fill, text
 * (label colour), icon (facilities), desc, facts (lines), photo (theme photo
 * name), stage (timetable stage), vendors (warung|pasar), link (path), cta.
 *
 * @return array
 */
function tapaufest_map_spots() {
	$spots = array(
		'pentas'  => array(
			'label' => 'PENTAS',
			'kind'  => __( 'Main stage', 'tapaufest' ),
			'shape' => array( 'rect', 380, 200, 260, 140 ),
			'fill'  => '#A82E05',
			'text'  => '#FFF2DA',
			'desc'  => __( 'Where the bands play, from late afternoon until close, both nights. The lawn in front is yours: bring a mat.', 'tapaufest' ),
			'facts' => array( __( 'Sets from 4pm', 'tapaufest' ), __( 'Pauses for Maghrib', 'tapaufest' ) ),
			'photo' => 'pentas-day',
			'stage' => 'PENTAS',
			'link'  => '/timetable/',
			'cta'   => __( 'Full timetable', 'tapaufest' ),
		),
		'warung'  => array(
			'label'   => 'WARUNG',
			'kind'    => __( 'Food and drink', 'tapaufest' ),
			'shape'   => array( 'rect', 110, 300, 200, 100 ),
			'fill'    => '#F2A93B',
			'desc'    => __( 'Local kitchens all day and late into the night. Free water refills next door.', 'tapaufest' ),
			'facts'   => array( __( 'Cards and DuitNow QR at most stalls', 'tapaufest' ), __( 'Bring some cash', 'tapaufest' ) ),
			'photo'   => 'warung',
			'vendors' => 'warung',
			'link'    => '/experience/',
			'cta'     => __( 'Who’s cooking', 'tapaufest' ),
		),
		'pasar'   => array(
			'label'   => 'PASAR',
			'kind'    => __( 'Indie market', 'tapaufest' ),
			'shape'   => array( 'rect', 110, 420, 200, 84 ),
			'fill'    => '#FFF2DA',
			'desc'    => __( 'Merch, vinyl, zines and crafts from the scene, plus the official festival merch.', 'tapaufest' ),
			'photo'   => 'pasar',
			'vendors' => 'pasar',
			'link'    => '/experience/',
			'cta'     => __( 'See the stalls', 'tapaufest' ),
		),
		'layar'   => array(
			'label' => 'LAYAR',
			'kind'  => __( 'Screenings', 'tapaufest' ),
			'shape' => array( 'rect', 690, 225, 150, 82 ),
			'fill'  => '#FFF2DA',
			'desc'  => __( 'A shaded tent for indie short films and documentaries from around the region.', 'tapaufest' ),
			'photo' => 'layar',
			'stage' => 'LAYAR',
			'link'  => '/timetable/',
			'cta'   => __( 'Screening times', 'tapaufest' ),
		),
		'bengkel' => array(
			'label' => 'BENGKEL',
			'kind'  => __( 'Workshops', 'tapaufest' ),
			'shape' => array( 'rect', 690, 325, 150, 82 ),
			'fill'  => '#FFF2DA',
			'desc'  => __( 'Talks and hands-on workshops. Slots open at the tent from 10am, first come first served.', 'tapaufest' ),
			'photo' => 'crowd',
			'stage' => 'BENGKEL',
			'link'  => '/timetable/',
			'cta'   => __( 'Workshop times', 'tapaufest' ),
		),
		'kembara' => array(
			'label' => 'KEMBARA',
			'kind'  => __( 'Outdoor', 'tapaufest' ),
			'shape' => array( 'rect', 70, 135, 190, 70 ),
			'fill'  => '#BFD7EA',
			'desc'  => __( 'Morning yoga and river time by the water. Runs only when water levels are safe.', 'tapaufest' ),
			'facts' => array( __( 'Weather dependent', 'tapaufest' ) ),
			'photo' => 'kembara-yoga',
			'stage' => 'KEMBARA',
			'link'  => '/timetable/',
			'cta'   => __( 'Outdoor slots', 'tapaufest' ),
		),
		'khemah'  => array(
			'label' => 'KHEMAH',
			'kind'  => __( 'Festival campsite', 'tapaufest' ),
			'shape' => array( 'rect', 865, 170, 115, 290 ),
			'fill'  => '#9DBF73',
			'desc'  => __( 'The official campsite, a short walk from PENTAS. Included with Duo Campers passes.', 'tapaufest' ),
			'facts' => array( __( 'Quiet hours after the bonfire', 'tapaufest' ), __( 'Bring a torchlight', 'tapaufest' ) ),
			'photo' => 'campsite',
			'link'  => '/plan/',
			'cta'   => __( 'Camping and stays', 'tapaufest' ),
		),
		'unggun'  => array(
			'label' => 'UNGGUN',
			'kind'  => __( 'Bonfire', 'tapaufest' ),
			'shape' => array( 'circle', 922, 400, 34 ),
			'fill'  => '#E33D07',
			'text'  => '#FFF2DA',
			'desc'  => __( 'Late bonfire sets at the campsite after the main stage closes.', 'tapaufest' ),
			'photo' => 'pentas-night',
			'stage' => 'UNGGUN',
			'link'  => '/timetable/',
			'cta'   => __( 'Bonfire sets', 'tapaufest' ),
		),
		'gate'    => array(
			'label' => 'GATE',
			'kind'  => __( 'Entry and wristbands', 'tapaufest' ),
			'shape' => array( 'rect', 440, 560, 140, 56 ),
			'fill'  => '#1E1208',
			'text'  => '#F8DAA5',
			'desc'  => __( 'Swap your e-ticket for a wristband at the redemption counter. Keep it on all weekend.', 'tapaufest' ),
			'facts' => array( __( 'Gates open 11am (TBC)', 'tapaufest' ), __( 'Lost wristbands can’t be replaced', 'tapaufest' ) ),
			'link'  => '/tickets/',
			'cta'   => __( 'How tickets work', 'tapaufest' ),
		),
		'surau'   => array(
			'label' => __( 'Surau', 'tapaufest' ),
			'kind'  => __( 'Facility', 'tapaufest' ),
			'icon'  => 'S',
			'shape' => array( 'circle', 345, 455, 20 ),
			'fill'  => '#FFF2DA',
			'desc'  => __( 'Prayer space next to the WARUNG. PENTAS pauses for Maghrib on both days.', 'tapaufest' ),
		),
		'toilets' => array(
			'label' => __( 'Toilets', 'tapaufest' ),
			'kind'  => __( 'Facility', 'tapaufest' ),
			'icon'  => 'T',
			'shape' => array( 'circle', 655, 470, 20 ),
			'fill'  => '#FFF2DA',
			'desc'  => __( 'Toilets by the main path, plus a block at the campsite.', 'tapaufest' ),
		),
		'aid'     => array(
			'label' => __( 'First aid', 'tapaufest' ),
			'kind'  => __( 'Facility', 'tapaufest' ),
			'icon'  => '+',
			'shape' => array( 'circle', 380, 560, 20 ),
			'fill'  => '#A82E05',
			'text'  => '#FFF2DA',
			'desc'  => __( 'Next to the gate, open until the last set ends. In an emergency call 999.', 'tapaufest' ),
		),
		'water'   => array(
			'label' => __( 'Water refill', 'tapaufest' ),
			'kind'  => __( 'Facility', 'tapaufest' ),
			'icon'  => 'W',
			'shape' => array( 'circle', 335, 300, 20 ),
			'fill'  => '#3D8BD0',
			'text'  => '#FFFFFF',
			'desc'  => __( 'Free refills all day. Bring your own bottle.', 'tapaufest' ),
		),
		'info'    => array(
			'label' => __( 'Info and lost and found', 'tapaufest' ),
			'kind'  => __( 'Facility', 'tapaufest' ),
			'icon'  => 'i',
			'shape' => array( 'circle', 640, 588, 20 ),
			'fill'  => '#F2A93B',
			'desc'  => __( 'Questions, lost things and help. Crew in gold shirts can help too.', 'tapaufest' ),
		),
	);
	return apply_filters( 'tapaufest_map_spots', $spots );
}

/**
 * Next few sessions on a stage, for a map card.
 *
 * @param string $stage Stage.
 * @param int    $limit How many.
 * @return array
 */
function tapaufest_map_stage_sessions( $stage, $limit = 3 ) {
	$now  = tapaufest_now();
	$days = tapaufest_days();
	$out  = array();
	foreach ( tapaufest_get_sessions() as $s ) {
		if ( $s['stage'] !== $stage || 'prayer' === $s['kind'] ) {
			continue;
		}
		if ( $s['day'] < $now['day'] || ( $s['day'] === $now['day'] && $s['end_min'] <= $now['minutes'] ) ) {
			continue;
		}
		$day   = isset( $days[ $s['day'] ] ) ? $days[ $s['day'] ] : '';
		$out[] = array(
			'when'  => trim( preg_replace( '/\s+\d+\s+\w+$/', '', $day ) . ' ' . $s['label'] ),
			'title' => $s['title'],
		);
		if ( count( $out ) >= $limit ) {
			break;
		}
	}
	return $out;
}
