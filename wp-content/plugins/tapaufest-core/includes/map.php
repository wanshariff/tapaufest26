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
 * Keys: cat (pin colour and filter group), label, kind, shape (rect: x,y,w,h | circle: cx,cy,r), fill, text
 * (label colour), icon (facilities), desc, facts (lines), photo (theme photo
 * name), stage (timetable stage), vendors (warung|pasar), link (path), cta.
 *
 * @return array
 */
function tapaufest_map_spots() {
	$spots = array(
		'pentas'  => array(
			'cat'   => 'stage',
			'label' => 'PENTAS',
			'kind'  => __( 'Main stage', 'tapaufest' ),
			'shape' => array( 'rect', 380, 200, 260, 140 ),
			'fill'  => '#A82E05',
			'text'  => '#FFF2DA',
			'desc'  => __( '12 live sets from top and rising South East Asian indie acts, from 5pm until late, both nights. The lawn in front is yours: bring a mat.', 'tapaufest' ),
			'facts' => array( __( 'Sets from 5pm', 'tapaufest' ), __( 'Pauses for Maghrib and Isyak', 'tapaufest' ) ),
			'photo' => 'pentas-day',
			'stage' => 'PENTAS',
			'link'  => '/timetable/',
			'cta'   => __( 'Full timetable', 'tapaufest' ),
		),
		'warung'  => array(
			'cat'   => 'food',
			'label'   => 'WARUNG',
			'kind'    => __( 'Food and drink', 'tapaufest' ),
			'shape'   => array( 'rect', 110, 300, 200, 100 ),
			'fill'    => '#F2A93B',
			'desc'    => __( 'A curated pasar malam: Malaysian street food from indie businesses and Hulu Langat vendors, at fair prices. Free refills at the Water Station.', 'tapaufest' ),
			'facts'   => array( __( 'Cards and DuitNow QR at most stalls', 'tapaufest' ), __( 'Bring some cash', 'tapaufest' ) ),
			'photo'   => 'warung',
			'vendors' => 'warung',
			'link'    => '/experience/',
			'cta'     => __( 'Who’s cooking', 'tapaufest' ),
		),
		'pasar'   => array(
			'cat'   => 'market',
			'label'   => 'PASAR',
			'kind'    => __( 'Indie market', 'tapaufest' ),
			'shape'   => array( 'rect', 110, 420, 200, 84 ),
			'fill'    => '#FFF2DA',
			'desc'    => __( 'Artworks, records and band merch from indie artisans, brands and musicians, with DJs spinning while you shop.', 'tapaufest' ),
			'photo'   => 'pasar',
			'vendors' => 'pasar',
			'link'    => '/experience/',
			'cta'     => __( 'See the stalls', 'tapaufest' ),
		),
		'layar'   => array(
			'cat'   => 'film',
			'label' => 'LAYAR',
			'kind'  => __( 'Films', 'tapaufest' ),
			'shape' => array( 'rect', 690, 225, 150, 82 ),
			'fill'  => '#FFF2DA',
			'desc'  => __( 'Indie shorts and documentaries by day. After PENTAS wraps up, an open-air starlight cinema under the forest canopy, weather permitting.', 'tapaufest' ),
			'photo' => 'layar',
			'stage' => 'LAYAR',
			'link'  => '/timetable/',
			'cta'   => __( 'Screening times', 'tapaufest' ),
		),
		'bengkel' => array(
			'cat'   => 'workshop',
			'label' => 'BENGKEL',
			'kind'  => __( 'Workshops', 'tapaufest' ),
			'shape' => array( 'rect', 690, 325, 150, 82 ),
			'fill'  => '#FFF2DA',
			'desc'  => __( 'Art workshops, sharing sessions and panels with indie ecosystem leaders, under a shaded tent. 10am to 4pm, first come first served.', 'tapaufest' ),
			'photo' => 'crowd',
			'stage' => 'BENGKEL',
			'link'  => '/timetable/',
			'cta'   => __( 'Workshop times', 'tapaufest' ),
		),
		'kembara' => array(
			'cat'   => 'outdoor',
			'label' => 'KEMBARA',
			'kind'  => __( 'Outdoor', 'tapaufest' ),
			'shape' => array( 'rect', 70, 135, 190, 70 ),
			'fill'  => '#BFD7EA',
			'desc'  => __( 'River dips with Sesi Tikar Mengkuang, Skytrex canopy zip-lines, forest treks, and morning bike and trail runs.', 'tapaufest' ),
			'facts' => array( __( 'Weather dependent', 'tapaufest' ) ),
			'photo' => 'kembara-yoga',
			'stage' => 'KEMBARA',
			'link'  => '/timetable/',
			'cta'   => __( 'Outdoor slots', 'tapaufest' ),
		),
		'khemah'  => array(
			'cat'   => 'camp',
			'label' => 'CAMP VIBES',
			'kind'  => __( 'Santuy Campsite', 'tapaufest' ),
			'shape' => array( 'rect', 865, 170, 115, 290 ),
			'fill'  => '#9DBF73',
			'desc'  => __( 'Camp two nights inside the festival grounds at Santuy Campsite, set up by the Santrav team. Book your spot separately.', 'tapaufest' ),
			'facts' => array( __( 'Quiet hours after the bonfire', 'tapaufest' ), __( 'Bring a torchlight', 'tapaufest' ) ),
			'photo' => 'campsite',
			'link'  => '/plan/',
			'cta'   => __( 'Camping and stays', 'tapaufest' ),
		),
		'unggun'  => array(
			'cat'   => 'stage',
			'label' => 'UNGGUN',
			'kind'  => __( 'Bonfire', 'tapaufest' ),
			'shape' => array( 'circle', 922, 400, 34 ),
			'fill'  => '#E33D07',
			'text'  => '#FFF2DA',
			'desc'  => __( 'Bonfire sessions at Santuy Campsite after the main stage closes, late into the night.', 'tapaufest' ),
			'photo' => 'pentas-night',
			'stage' => 'UNGGUN',
			'link'  => '/timetable/',
			'cta'   => __( 'Bonfire sets', 'tapaufest' ),
		),
		'gate'    => array(
			'cat'   => 'entry',
			'label' => 'GATE',
			'kind'  => __( 'Entry and wristbands', 'tapaufest' ),
			'shape' => array( 'rect', 440, 560, 140, 56 ),
			'fill'  => '#1E1208',
			'text'  => '#F8DAA5',
			'desc'  => __( 'Registration from 9am. Swap your e-ticket for a wristband and keep it on all weekend. Shuttles from MRT Sri Raya stop here.', 'tapaufest' ),
			'facts' => array( __( 'Gates open 9am', 'tapaufest' ), __( 'Lost wristbands can’t be replaced', 'tapaufest' ) ),
			'link'  => '/tickets/',
			'cta'   => __( 'How tickets work', 'tapaufest' ),
		),
		'surau'   => array(
			'cat'   => 'facility',
			'label' => __( 'Surau', 'tapaufest' ),
			'kind'  => __( 'Facility', 'tapaufest' ),
			'icon'  => 'S',
			'shape' => array( 'circle', 345, 455, 20 ),
			'fill'  => '#FFF2DA',
			'desc'  => __( 'Prayer space next to the WARUNG. PENTAS pauses for Maghrib on both days.', 'tapaufest' ),
		),
		'toilets' => array(
			'cat'   => 'facility',
			'label' => __( 'Toilets', 'tapaufest' ),
			'kind'  => __( 'Facility', 'tapaufest' ),
			'icon'  => 'T',
			'shape' => array( 'circle', 655, 470, 20 ),
			'fill'  => '#FFF2DA',
			'desc'  => __( 'Toilets by the main path, plus a block at the campsite.', 'tapaufest' ),
		),
		'aid'     => array(
			'cat'   => 'facility',
			'label' => __( 'First aid', 'tapaufest' ),
			'kind'  => __( 'Facility', 'tapaufest' ),
			'icon'  => '+',
			'shape' => array( 'circle', 380, 560, 20 ),
			'fill'  => '#A82E05',
			'text'  => '#FFF2DA',
			'desc'  => __( 'Next to the gate, open until the last set ends. In an emergency call 999.', 'tapaufest' ),
		),
		'water'   => array(
			'cat'   => 'facility',
			'label' => __( 'Water refill', 'tapaufest' ),
			'kind'  => __( 'Facility', 'tapaufest' ),
			'icon'  => 'W',
			'shape' => array( 'circle', 335, 300, 20 ),
			'fill'  => '#3D8BD0',
			'text'  => '#FFFFFF',
			'desc'  => __( 'Free refills all day. Bring your own bottle.', 'tapaufest' ),
		),
		'info'    => array(
			'cat'   => 'facility',
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

/**
 * Map categories: filter chips and pin colours.
 *
 * @return array Slug => [ label, colour ].
 */
function tapaufest_map_categories() {
	return array(
		'stage'    => array( __( 'Stages', 'tapaufest' ), '#A82E05' ),
		'film'     => array( __( 'Films', 'tapaufest' ), '#1E1208' ),
		'workshop' => array( __( 'Workshops', 'tapaufest' ), '#6F310B' ),
		'food'     => array( __( 'Food', 'tapaufest' ), '#D98A1E' ),
		'market'   => array( __( 'Market', 'tapaufest' ), '#2E6FA8' ),
		'outdoor'  => array( __( 'Outdoor', 'tapaufest' ), '#3F7D4E' ),
		'camp'     => array( __( 'Camping', 'tapaufest' ), '#4E7A2E' ),
		'entry'    => array( __( 'Entry', 'tapaufest' ), '#1E1208' ),
		'facility' => array( __( 'Facilities', 'tapaufest' ), '#5A3A20' ),
	);
}

/**
 * The designed basemap: terrain, river, roads, paths and zone footprints in a
 * light "street map" style, drawn from the spots so positions always match.
 * 1000×640 units, y down.
 *
 * @param array $spots Spots from tapaufest_map_spots().
 * @return string SVG markup.
 */
function tapaufest_map_basemap_svg( $spots ) {
	$cats = tapaufest_map_categories();
	$rnd  = 7;
	$next = function () use ( &$rnd ) {
		$rnd = ( $rnd * 9301 + 49297 ) % 233280;
		return $rnd / 233280;
	};
	// Tree canopy dots in the forest patches.
	$trees  = '';
	$forest = array( array( 0, 120, 70, 520 ), array( 560, 110, 300, 60 ), array( 870, 470, 130, 170 ), array( 0, 520, 110, 120 ), array( 270, 110, 100, 60 ),
		// Surroundings, so panning never shows an empty edge.
		array( -200, -300, 1400, 250 ), array( -200, 140, 180, 700 ), array( 1010, 120, 190, 700 ), array( -200, 660, 1400, 280 ) );
	foreach ( $forest as $f ) {
		$n = (int) ( ( $f[2] * $f[3] ) / 700 );
		for ( $i = 0; $i < $n; $i++ ) {
			$cx     = $f[0] + $next() * $f[2];
			$cy     = $f[1] + $next() * $f[3];
			$r      = 6 + $next() * 9;
			$trees .= sprintf( '<circle cx="%.1f" cy="%.1f" r="%.1f"/>', $cx, $cy, $r );
		}
	}
	$foot = '';
	$labels = '';
	foreach ( $spots as $key => $s ) {
		if ( ! empty( $s['icon'] ) ) {
			continue;
		}
		$col = isset( $cats[ $s['cat'] ] ) ? $cats[ $s['cat'] ][1] : '#5A3A20';
		$sh  = $s['shape'];
		if ( 'rect' === $sh[0] ) {
			list( , $x, $y, $w, $h ) = $sh;
			$foot .= sprintf( '<rect x="%d" y="%d" width="%d" height="%d" rx="8" fill="%s" fill-opacity=".14" stroke="%s" stroke-opacity=".55" stroke-width="2"/>', $x, $y, $w, $h, $col, $col );
			// Structures inside each zone.
			if ( 'pentas' === $key ) {
				$foot .= sprintf( '<rect x="%d" y="%d" width="%d" height="%d" rx="3" fill="#CDBBA2" stroke="#9C8467" stroke-width="1.5"/>', $x + $w * 0.2, $y + 10, $w * 0.6, 34 );
				$foot .= sprintf( '<rect x="%d" y="%d" width="%d" height="%d" rx="2" fill="#B9A487"/>', $x + 18, $y + 16, 20, 20 ) . sprintf( '<rect x="%d" y="%d" width="%d" height="%d" rx="2" fill="#B9A487"/>', $x + $w - 38, $y + 16, 20, 20 );
				$foot .= sprintf( '<rect x="%d" y="%d" width="%d" height="%d" rx="2" fill="#D8CBB6"/>', $x + $w / 2 - 18, $y + $h - 34, 36, 22 );
			} elseif ( in_array( $key, array( 'warung', 'pasar' ), true ) ) {
				$cols = (int) floor( ( $w - 20 ) / 28 );
				$rows = 'warung' === $key ? 2 : 1;
				for ( $r = 0; $r < $rows; $r++ ) {
					for ( $c = 0; $c < $cols; $c++ ) {
						$foot .= sprintf( '<rect x="%d" y="%d" width="20" height="16" rx="2" fill="#FFFFFF" stroke="%s" stroke-opacity=".6" stroke-width="1.5"/>', $x + 12 + $c * 28, $y + 14 + $r * 26, $col );
					}
				}
			} elseif ( in_array( $key, array( 'layar', 'bengkel' ), true ) ) {
				$foot .= sprintf( '<rect x="%d" y="%d" width="%d" height="%d" rx="4" fill="#FFFFFF" stroke="%s" stroke-opacity=".6" stroke-width="1.5"/>', $x + 18, $y + 14, $w - 36, $h - 40, $col );
			} elseif ( 'khemah' === $key ) {
				for ( $r = 0; $r < 7; $r++ ) {
					for ( $c = 0; $c < 3; $c++ ) {
						if ( 5 === $r && 1 === $c ) {
							continue;
						}
						$tx    = $x + 20 + $c * 34;
						$ty    = $y + 22 + $r * 36;
						$foot .= sprintf( '<path d="M%d %d l10 -14 l10 14 z" fill="#FFFFFF" stroke="%s" stroke-opacity=".7" stroke-width="1.5"/>', $tx, $ty + 10, $col );
					}
				}
			} elseif ( 'gate' === $key ) {
				$foot .= sprintf( '<rect x="%d" y="%d" width="%d" height="10" rx="2" fill="#8F7B60"/>', $x + 10, $y + 8, $w - 20 );
			}
			$lx = $x + $w / 2;
			$ly = $y + $h - 10;
			if ( 'khemah' === $key ) {
				$ly = $y + $h + 18;
			}
			$labels .= sprintf( '<text x="%d" y="%d" fill="%s">%s</text>', $lx, $ly, $col, esc_html( $s['label'] ) );
		} else {
			$foot .= sprintf( '<circle cx="%d" cy="%d" r="%d" fill="%s" fill-opacity=".18" stroke="%s" stroke-opacity=".6" stroke-width="2"/>', $sh[1], $sh[2], $sh[3], $col, $col );
		}
	}
	ob_start();
	?>
	<svg xmlns="http://www.w3.org/2000/svg" class="tf-map__base" viewBox="-200 -300 1400 1240" data-extent="-200 -300 1400 1240" role="img" aria-label="<?php esc_attr_e( 'Festival site map, draft layout', 'tapaufest' ); ?>">
		<rect x="-200" y="-300" width="1400" height="1240" fill="#F2EEE4"/>
		<path d="M90 130 C 200 112, 420 120, 600 118 C 740 116, 840 140, 860 170 L 860 470 C 840 540, 700 556, 600 552 L 140 548 C 100 540, 86 500, 88 460 Z" fill="#E1ECD3"/>
		<g fill="#CFE2BF"><?php foreach ( $forest as $f ) { printf( '<rect x="%d" y="%d" width="%d" height="%d" rx="30"/>', $f[0], $f[1], $f[2], $f[3] ); } ?></g>
		<g fill="#BBD6A8" opacity=".9"><?php echo $trees; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- generated numbers only. ?></g>
		<g fill="none" stroke="#E2D9C7" stroke-width="1.5">
			<path d="M0 470 C 60 450, 90 380, 80 300"/><path d="M880 640 C 900 560, 960 520, 1000 520"/><path d="M890 620 C 920 580, 960 560, 1000 556"/>
		</g>
		<path d="M-220 150 C -120 120, -60 110, -20 96 C 160 44, 330 144, 520 92 S 840 34, 1020 82 C 1100 70, 1160 40, 1220 20" fill="none" stroke="#86C1E8" stroke-width="58" stroke-linecap="round"/>
		<path id="tf-river-line" d="M-220 150 C -120 120, -60 110, -20 96 C 160 44, 330 144, 520 92 S 840 34, 1020 82 C 1100 70, 1160 40, 1220 20" fill="none" stroke="#AAD6F3" stroke-width="48" stroke-linecap="round"/>
		<text class="tf-map__water"><textPath href="#tf-river-line" startOffset="46%">Sungai Congkak</textPath></text>
		<g fill="none" stroke-linecap="round" stroke-linejoin="round">
			<path d="M-220 700 C -100 660, -40 632, -10 628 C 160 610, 330 640, 510 616 C 600 604, 640 596, 720 598 L 1220 604" stroke="#D3CABA" stroke-width="26"/>
			<path d="M-220 700 C -100 660, -40 632, -10 628 C 160 610, 330 640, 510 616 C 600 604, 640 596, 720 598 L 1220 604" stroke="#FFFFFF" stroke-width="20"/>
			<path d="M510 560 L 510 345 M 510 470 L 310 430 M 510 470 L 690 380 M 640 470 L 865 430 M 380 300 L 260 205 M 840 280 L 865 280" stroke="#E1D7C5" stroke-width="12"/>
			<path d="M510 560 L 510 345 M 510 470 L 310 430 M 510 470 L 690 380 M 640 470 L 865 430 M 380 300 L 260 205 M 840 280 L 865 280" stroke="#FFFFFF" stroke-width="8"/>
		</g>
		<g>
			<rect x="640" y="560" width="210" height="0" fill="none"/>
			<rect x="610" y="566" width="230" height="24" rx="4" fill="#E5E1D8" stroke="#D3CABA" stroke-width="1.5"/>
			<g stroke="#FFFFFF" stroke-width="1.5"><?php for ( $i = 0; $i < 14; $i++ ) { printf( '<path d="M%d 568 v20"/>', 624 + $i * 15 ); } ?></g>
			<circle cx="850" cy="578" r="11" fill="#2E6FA8"/><text x="850" y="582" class="tf-map__p">P</text>
		</g>
		<?php echo $foot; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built from escaped and numeric values above. ?>
		<text x="40" y="604" class="tf-map__road">To Hulu Langat</text>
		<g transform="translate(958 140)"><circle r="18" fill="#FFFFFF" stroke="#D3CABA"/><path d="M0 -12 L6 6 L0 2 L-6 6 Z" fill="#1E1208"/><text y="-21" class="tf-map__n">N</text></g>
	</svg>
	<?php
	return (string) ob_get_clean();
}

/**
 * Small icon per category for map pins.
 *
 * @param string $cat  Category.
 * @param string $icon Facility letter, if any.
 * @return string SVG or text.
 */
function tapaufest_map_pin_icon( $cat, $icon = '' ) {
	$letters = array(
		'S' => 'surau',
		'T' => 'toilet',
		'+' => 'first-aid',
		'W' => 'water',
		'i' => 'info',
		'P' => 'parking',
	);
	$cats    = array(
		'camp'  => 'tent',
		'entry' => 'gate',
	);
	if ( $icon ) {
		$name = isset( $letters[ $icon ] ) ? $letters[ $icon ] : $icon;
	} else {
		$name = isset( $cats[ $cat ] ) ? $cats[ $cat ] : $cat;
	}
	return tapaufest_icon( $name );
}

/**
 * Icon name for a map category (filters and legends).
 *
 * @param string $cat Category slug.
 * @return string
 */
function tapaufest_map_cat_icon( $cat ) {
	$map = array(
		'camp'     => 'tent',
		'entry'    => 'gate',
		'facility' => 'info',
	);
	return isset( $map[ $cat ] ) ? $map[ $cat ] : $cat;
}
