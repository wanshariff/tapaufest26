<?php
/**
 * Demo content: mock artists, set times, tiers, FAQs, vendors and pages.
 *
 * Everything created here is tagged with the `_tapaufest_demo` meta key so
 * it can be removed in one go. Load it from Settings > TAPAUfest or with
 * `wp tapaufest seed`; remove it with `wp tapaufest unseed`.
 *
 * Artists, photos, running order and prices follow the tentative TAPAUfest 2026
 * plan. Workshop and outdoor times and the sample vendors are placeholders.
 *
 * @package TapaufestCore
 */

defined( 'ABSPATH' ) || exit;

/**
 * Demo artists: the tentative TAPAUfest 2026 lineup (subject to change), plus
 * the 2025 hosts as stand-ins. Name, country, genre, headliner, bio, photo file.
 *
 * @return array
 */
function tapaufest_demo_artists() {
	return array(
		array( 'Plague of Happiness x Leaism', 'MY', 'Ska-punk x soul', false, 'A one-off festival mashup: Johor Bahru’s ska-punk brass crew Plague of Happiness with Leaism’s soulful, expressive vocals. Built to kick off the weekend with big brass, dance grooves and a crowd that joins in.', 'plague-of-happiness-x-leaism' ),
		array( 'Banda Neira', 'ID', 'Folk-pop', false, 'The beloved Indonesian folk-pop duo, known for heartfelt acoustic songwriting and quiet warmth. Their sunset set is made for a slow, full-field sing-along.', 'banda-neira' ),
		array( 'Battle of the Bands winner', 'MY', 'To be decided', false, 'This slot goes to the winner of our first nationwide Battle of the Bands: a stage for the next big name in Malaysia’s underground indie scene.', '' ),
		array( 'DOTDOTDOT', 'TH', 'Experimental rock', false, 'A fast-rising Thai outfit mixing modern alternative rock with traditional Isaan sounds. Rhythmic, bold and genre-crossing.', 'dotdotdot' ),
		array( 'Aizat & Friends', 'MY', 'Singer-songwriter', false, 'Multi-award-winning singer-songwriter Aizat Amdan brings an exclusive “Aizat & Friends” showcase, with guest collaborators joining him on stage.', 'aizat-and-friends' ),
		array( 'The Panturas', 'ID', 'Surf rock', true, 'Indonesia’s surf-rock heavyweights: wild guitar riffs, vintage beats and full-throttle energy. They close Day 1.', 'the-panturas' ),
		array( 'Forests', 'SG', 'Math rock', false, 'Singapore’s math-rock trio. Hyper-energetic sets, catchy hooks and emo-pop melodies played with real precision.', 'forests' ),
		array( 'Straw 林水草', 'MY', 'Indie rock', false, 'Local indie-rock staples with melodic guitars and anthemic hooks built for open fields. 90s-tinged and loud.', 'straw' ),
		array( 'Thee Marloes', 'ID', 'Soul', false, 'From Surabaya and signed to Brooklyn’s Big Crown Records: vintage 60s soul, smooth R&B and easy grooves for the afternoon.', 'thee-marloes' ),
		array( 'Empty Page', 'MY', 'Pop-punk', false, 'Punchy, guitar-heavy pop-punk with honest lyrics about growing up. Expect full-crowd sing-alongs.', 'empty-page' ),
		array( 'Alkateri', 'ID', 'Indie pop', false, 'One of Bandung’s fastest-rising acts, known for their debut album Kontemplasi. Their first show outside Indonesia: bright, 90s-inspired indie pop.', 'alkateri' ),
		array( 'Noh Salleh', 'MY', 'Singer-songwriter', true, 'A generational voice of Malaysian indie for two decades, from Angin Kencang to his newest collaborations. He closes the festival.', 'noh-salleh' ),
		array( 'Iedil Dzuhrie', 'MY', 'Host', false, 'Hosted TAPAUfest 2025 and the 2021 online edition. Your voice between sets.', '', 'mc' ),
		array( 'Nadia Nash', 'MY', 'Host', false, 'Co-hosted TAPAUfest 2025 by the lake in Lenggong.', '', 'mc' ),
	);
}

/**
 * Show page descriptions for the demo sessions, keyed by title.
 *
 * @return array
 */
function tapaufest_demo_session_notes() {
	return array(
		'Sesi Tikar Mengkuang and river dip'  => 'Guided morning meditation and stretching by the stream with Sesi Tikar Mengkuang, then a dip in the natural river pools.',
		'Skytrex canopy adventure'            => 'With Skytrex Sg Congkak: zip-lines and aerial obstacle courses above the forest canopy and the river rapids. Book at the KEMBARA desk.',
		'Forest trek and nature walk'         => 'A guided walk on the Sungai Congkak trails. Good for birdwatching, plant spotting and slowing down.',
		'Bike and trail run'                  => 'Morning ride and jungle trail run through the Hulu Langat valley, with local run and cycle clubs.',
		'Art workshop: rubber-cut printmaking' => 'Carve a stamp, ink it, print it on paper or a tote. All materials provided.',
		'Panel: the indie ecosystem'          => 'Leaders from the indie scene on building audiences, touring the region and getting heard.',
		'Sharing session: making it in the scene' => 'Artists and organisers share what they learned the hard way. Bring questions.',
		'Art workshop: mini-zine making'      => 'Fold, cut, collage and staple your own zine to take home. No experience needed.',
		'Indie shorts'                        => 'Indie short films and documentaries from around the region in the BENGKEL and LAYAR tent.',
		'Starlight cinema'                    => 'LAYAR goes open-air under the forest canopy once PENTAS wraps up. Indie films on a big screen. Weather permitting.',
		'Bonfire sessions at Santuy Campsite' => 'Bonfire sets and jams at the Santuy Campsite, run by the Santrav team, late into the night.',
	);
}

/**
 * Demo sessions: day index (1|2), stage, start, minutes, artist name or kind:title.
 * PENTAS follows the tentative 2026 festival rundown; other slots are placeholders.
 *
 * @return array
 */
function tapaufest_demo_sessions() {
	return array(
		array( 1, 'KEMBARA', '07:30', 90, 'outdoor:Sesi Tikar Mengkuang and river dip' ),
		array( 1, 'KEMBARA', '10:00', 180, 'outdoor:Skytrex canopy adventure' ),
		array( 1, 'BENGKEL', '11:00', 90, 'workshop:Art workshop: rubber-cut printmaking' ),
		array( 1, 'BENGKEL', '14:00', 60, 'workshop:Panel: the indie ecosystem' ),
		array( 1, 'LAYAR', '13:00', 90, 'film:Indie shorts' ),
		array( 1, 'LAYAR', '23:45', 90, 'film:Starlight cinema' ),
		array( 1, 'PENTAS', '17:00', 45, 'Plague of Happiness x Leaism' ),
		array( 1, 'PENTAS', '18:00', 45, 'Banda Neira' ),
		array( 1, 'PENTAS', '18:45', 60, 'prayer:Maghrib break' ),
		array( 1, 'PENTAS', '19:45', 45, 'Battle of the Bands winner' ),
		array( 1, 'PENTAS', '20:30', 15, 'prayer:Isyak break' ),
		array( 1, 'PENTAS', '20:45', 45, 'DOTDOTDOT' ),
		array( 1, 'PENTAS', '21:45', 45, 'Aizat & Friends' ),
		array( 1, 'PENTAS', '22:45', 45, 'The Panturas' ),
		array( 1, 'UNGGUN', '24:00', 90, 'other:Bonfire sessions at Santuy Campsite' ),
		array( 2, 'KEMBARA', '07:00', 90, 'outdoor:Bike and trail run' ),
		array( 2, 'KEMBARA', '08:00', 90, 'outdoor:Sesi Tikar Mengkuang and river dip' ),
		array( 2, 'KEMBARA', '10:00', 120, 'outdoor:Forest trek and nature walk' ),
		array( 2, 'BENGKEL', '11:00', 60, 'workshop:Sharing session: making it in the scene' ),
		array( 2, 'BENGKEL', '14:00', 90, 'workshop:Art workshop: mini-zine making' ),
		array( 2, 'LAYAR', '13:00', 90, 'film:Indie shorts' ),
		array( 2, 'LAYAR', '23:45', 90, 'film:Starlight cinema' ),
		array( 2, 'PENTAS', '17:00', 45, 'Forests' ),
		array( 2, 'PENTAS', '18:00', 45, 'Straw 林水草' ),
		array( 2, 'PENTAS', '18:45', 60, 'prayer:Maghrib break' ),
		array( 2, 'PENTAS', '19:45', 45, 'Thee Marloes' ),
		array( 2, 'PENTAS', '20:30', 15, 'prayer:Isyak break' ),
		array( 2, 'PENTAS', '20:45', 45, 'Empty Page' ),
		array( 2, 'PENTAS', '21:45', 45, 'Alkateri' ),
		array( 2, 'PENTAS', '22:45', 45, 'Noh Salleh' ),
		array( 2, 'UNGGUN', '24:00', 90, 'other:Bonfire sessions at Santuy Campsite' ),
	);
}

/**
 * Import a bundled demo photo into the media library.
 *
 * @param string $file    File name in assets/demo, without extension.
 * @param int    $post_id Post to attach it to.
 * @param string $alt     Alt text.
 * @return int Attachment ID, or 0.
 */
function tapaufest_demo_photo( $file, $post_id, $alt ) {
	$path = TAPAUFEST_DIR . 'assets/demo/' . sanitize_file_name( $file ) . '.webp';
	if ( ! $file || ! file_exists( $path ) ) {
		return 0;
	}
	$upload = wp_upload_bits( basename( $path ), null, file_get_contents( $path ) ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- local bundled file.
	if ( ! empty( $upload['error'] ) ) {
		return 0;
	}
	$id = wp_insert_attachment(
		array(
			'post_mime_type' => 'image/webp',
			'post_title'     => $alt,
			'post_status'    => 'inherit',
		),
		$upload['file'],
		$post_id
	);
	if ( ! $id || is_wp_error( $id ) ) {
		return 0;
	}
	require_once ABSPATH . 'wp-admin/includes/image.php';
	wp_update_attachment_metadata( $id, wp_generate_attachment_metadata( $id, $upload['file'] ) );
	update_post_meta( $id, '_wp_attachment_image_alt', $alt );
	update_post_meta( $id, '_tapaufest_demo', 1 );
	set_post_thumbnail( $post_id, $id );
	return $id;
}

/**
 * Create one demo post with meta.
 *
 * @param string $type    Post type.
 * @param string $title   Title.
 * @param array  $meta    Meta.
 * @param array  $args    Extra wp_insert_post args.
 * @return int
 */
function tapaufest_demo_insert( $type, $title, $meta = array(), $args = array() ) {
	$id = wp_insert_post(
		array_merge(
			array(
				'post_type'   => $type,
				'post_title'  => $title,
				'post_status' => 'publish',
			),
			$args
		)
	);
	if ( ! $id || is_wp_error( $id ) ) {
		return 0;
	}
	update_post_meta( $id, '_tapaufest_demo', 1 );
	foreach ( $meta as $key => $value ) {
		update_post_meta( $id, $key, $value );
	}
	return $id;
}

/**
 * Load demo content. Skips if it is already loaded.
 *
 * @return bool True when content was created.
 */
function tapaufest_seed() {
	$existing = get_posts(
		array(
			'post_type'      => array( 'tf_artist', 'tf_session', 'tf_tier', 'tf_faq', 'tf_vendor', 'tf_partner', 'page' ),
			'posts_per_page' => 1,
			'meta_key'       => '_tapaufest_demo', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_query_meta_key
			'fields'         => 'ids',
		)
	);
	if ( $existing ) {
		return false;
	}

	$artist_ids = array();
	foreach ( tapaufest_demo_artists() as $i => $a ) {
		$artist_ids[ $a[0] ] = tapaufest_demo_insert(
			'tf_artist',
			$a[0],
			array(
				'tf_country'   => $a[1],
				'tf_genre'     => $a[2],
				'tf_headliner' => $a[3],
				'tf_billing'   => isset( $a[6] ) ? $a[6] : 'act',
				'tf_profile'   => 'https://tapau.asia/',
			),
			array(
				'post_content' => '<!-- wp:paragraph --><p>' . esc_html( $a[4] ) . '</p><!-- /wp:paragraph -->',
				'post_excerpt' => $a[4],
				'post_name'    => sanitize_title( preg_replace( '/[^\x20-\x7E]/', '', $a[0] ) ),
				'menu_order'   => $i,
			)
		);
		tapaufest_demo_photo( $a[5], $artist_ids[ $a[0] ], $a[0] );
	}

	$days = array(
		1 => tapaufest_setting( 'day1' ),
		2 => tapaufest_setting( 'day2' ),
	);
	$tf_notes = tapaufest_demo_session_notes();
	foreach ( tapaufest_demo_sessions() as $i => $s ) {
		$is_act = isset( $artist_ids[ $s[4] ] );
		$parts  = $is_act ? array( 'act', $s[4] ) : explode( ':', $s[4], 2 );
		tapaufest_demo_insert(
			'tf_session',
			$parts[1],
			array(
				'tf_day'    => $days[ $s[0] ],
				'tf_stage'  => $s[1],
				'tf_start'  => $s[2],
				'tf_length' => $s[3],
				'tf_kind'   => $parts[0],
				'tf_artist' => $is_act ? $artist_ids[ $s[4] ] : 0,
			),
			array(
				'menu_order'   => $i,
				'post_content' => isset( $tf_notes[ $parts[1] ] ) ? '<!-- wp:paragraph --><p>' . esc_html( $tf_notes[ $parts[1] ] ) . '</p><!-- /wp:paragraph -->' : '',
				'post_excerpt' => isset( $tf_notes[ $parts[1] ] ) ? $tf_notes[ $parts[1] ] : '',
			)
		);
	}

	// Name, who, people, days, early-bird price, price, includes, campsite, art.
	$tiers = array(
		array( 'Day Ticket', '1 person · 1 day', 1, 1, 0, 158, "Saturday or Sunday\nAll zones that day\nRM198 at the door", false, 'day' ),
		array( 'Weekend Ticket', '1 person · 2 days', 1, 2, 188, 288, "Both days\nAll zones\nEarly bird for the first 2 weeks", false, 'weekend' ),
		array( 'Student Weekend', '1 student · 2 days', 1, 2, 98, 98, "Both days\nAll zones\nLimited to 500\nValid student ID at the gate", false, 'geng' ),
	);
	foreach ( $tiers as $i => $t ) {
		tapaufest_demo_insert(
			'tf_tier',
			$t[0],
			array(
				'tf_who'      => $t[1],
				'tf_people'   => $t[2],
				'tf_days'     => $t[3],
				'tf_early'    => $t[4],
				'tf_regular'  => $t[5],
				'tf_includes' => $t[6],
				'tf_campsite' => $t[7],
				'tf_art'      => $t[8],
				'tf_url'      => tapaufest_setting( 'tickets_url' ),
			),
			array( 'menu_order' => $i )
		);
	}

	$faq = array(
		'The basics' => array(
			array( 'When and where is it?', 'Saturday 12 and Sunday 13 Dec 2026 at Chongkak Park & Resort, Hulu Langat, Selangor. Registration and gates open at 9am both days.' ),
			array( 'How do I get there without a car?', 'Take the MRT Kajang Line to MRT Sri Raya. Official TAPAUfest shuttles run from there straight to the festival gates. By car it’s about an hour from KL.', true ),
			array( 'Can I go solo?', 'Plenty do. Grab a Day or Weekend Ticket and say hi to Geng TAPAU at the WARUNG.' ),
			array( 'Can I bring my kids?', 'Yes. Children aged 12 and below must stay with a parent or guardian at all times.', true ),
		),
		'Tickets'    => array(
			array( 'How much are tickets?', 'Weekend Tickets are RM188 early bird (first 2 weeks), then RM288. Day Tickets are RM158, or RM198 at the door. Student Weekend Tickets are RM98, limited to 500, with a valid student ID.' ),
			array( 'How do my tickets work?', 'Your e-ticket arrives by email from TAPAUtix. Swap it for a wristband at the gate and keep it on all weekend. Lost wristbands can’t be replaced.' ),
			array( 'Can I get a refund?', 'All sales are final, unless the festival is cancelled or postponed.', true ),
			array( 'What is early access?', 'People on the waitlist get a code by email. Enter it at TAPAUtix checkout for the early-bird price before public sale opens.' ),
		),
		'On site'    => array(
			array( 'Can I camp?', 'Yes. Book a spot at the Santuy Campsite inside the festival grounds for both nights, run by the Santrav team with bonfire sessions late into the night. Locally run campsites and chalets nearby work too.' ),
			array( 'What can’t I bring?', 'Glass and bottles, cigarettes and vapes, professional recording gear, weapons, lasers, fireworks and flares, and animals.' ),
			array( 'Is there free water?', 'Yes. Bring your own bottle and refill for free at the Water Station.' ),
			array( 'Is there a surau?', 'Yes, next to the WARUNG. PENTAS pauses for Maghrib and Isyak on both days.' ),
			array( 'Can I pay by card?', 'Most stalls take DuitNow QR and cards. Bring some cash for smaller stalls.' ),
		),
		'Weather'    => array(
			array( 'What happens if it rains?', 'December is rainy season, so pack a poncho and slippers that can get wet. LAYAR’s open-air cinema runs weather permitting. Updates and any set changes post to the alert bar at the top of every page first. All sales are final unless the festival is cancelled or postponed.', true ),
			array( 'Are river activities safe?', 'River dips and KEMBARA activities only run when water levels are safe. Changes post to the alert bar and the WhatsApp channel first.' ),
		),
	);
	$order = 0;
	foreach ( $faq as $group => $items ) {
		$term = wp_insert_term( $group, 'tf_faq_group' );
		if ( is_wp_error( $term ) ) {
			$existing_term = get_term_by( 'name', $group, 'tf_faq_group' );
			$term_id       = $existing_term ? (int) $existing_term->term_id : 0;
		} else {
			$term_id = (int) $term['term_id'];
			update_term_meta( $term_id, '_tapaufest_demo', 1 );
		}
		foreach ( $items as $item ) {
			$id = tapaufest_demo_insert(
				'tf_faq',
				$item[0],
				! empty( $item[2] ) ? array( 'tf_featured' => true ) : array(),
				array(
					'post_content' => '<!-- wp:paragraph --><p>' . esc_html( $item[1] ) . '</p><!-- /wp:paragraph -->',
					'menu_order'   => $order++,
				)
			);
			if ( $id && $term_id ) {
				wp_set_object_terms( $id, array( $term_id ), 'tf_faq_group' );
			}
		}
	}

	// Two confirmed 2025 names, then sample stalls (tagged "Sample") based on what
	// past editions served: nasi lemak, cendol, fried food, desserts, a community cookout.
	$vendors = array(
		array( 'pasar', 'TAPAUfest x Humblezing', 'Official festival merch', 'The 2025 collab line sold out fast. Watch for the 2026 drop.', 'Merch, Limited run', '2025', '', '$$', '' ),
		array( 'pasar', 'Pekan Pop Up', 'Indie market curators', 'Curated the 2025 PASAR: indie brands, art, crafts and vinyl.', 'Curated, Local brands', '2025', '', '', '' ),
		array( 'warung', 'Nasi Lemak Corner', 'Nasi lemak, all day', 'Breakfast, lunch and the 2am refuel.', 'Halal, Cashless', 'Sample', 'Nasi lemak sambal sotong', '$', '' ),
		array( 'warung', 'Cendol and ABC', 'Shaved ice desserts', 'The cold one you need after the sun sets on PENTAS.', 'Halal, Vegetarian options', 'Sample', 'Cendol pulut', '$', '' ),
		array( 'warung', 'Goreng-goreng', 'Fried snacks', 'Pisang goreng, keropok lekor and cucur, fresh from the wok.', 'Halal, Vegetarian options', 'Sample', 'Keropok lekor', '$', '' ),
		array( 'warung', 'Satay and Grill', 'Satay, ketupat and grilled corn', 'Smoke you can follow from the campsite.', 'Halal, Late night', 'Sample', '10 sticks of satay ayam', '$$', '' ),
		array( 'warung', 'Kopi and Teh Tarik', 'Kopi, teh tarik, Milo ais', 'Hot or iced. Bring your own cup for a discount.', 'Halal, Vegetarian options, Bring your cup', 'Sample', 'Kopi ais kaw', '$', '' ),
		array( 'warung', 'Roti John Late Night', 'Roti john and burgers', 'Opens when the bonfire sets start.', 'Halal, Late night', 'Sample', 'Roti john daging', '$$', '' ),
		array( 'warung', 'Green Bowls', 'Vegetarian rice bowls', 'Plant-based bowls and salads.', 'Vegetarian, Vegan options', 'Sample', 'Tempeh sambal bowl', '$$', '' ),
		array( 'warung', 'Community Cookout', 'Shared pot by the campsite', 'Like 2023: bring a dish, share a dish, meet your neighbours.', 'Free, Campsite', 'Sample', '', '', '' ),
		array( 'pasar', 'Band Merch Table', 'Tees and tapes from the lineup', 'One table for every act on PENTAS.', 'Merch, Cashless', 'Sample', '', '$$', '' ),
		array( 'pasar', 'Vinyl and Tapes', 'Indie records', 'South East Asian vinyl and cassettes.', 'Records', 'Sample', '', '$$$', '' ),
		array( 'pasar', 'Zine Corner', 'Zines and prints', 'Local zine makers and illustrators.', 'Zines, Art', 'Sample', '', '$', '' ),
		array( 'pasar', 'Live Screenprint', 'Print your own tee', 'Bring a shirt or buy one. Prints while you wait.', 'Hands-on', 'Sample', '', '$$', '' ),
	);
	foreach ( $vendors as $i => $v ) {
		tapaufest_demo_insert(
			'tf_vendor',
			$v[1],
			array(
				'tf_vendor_type' => $v[0],
				'tf_note'        => $v[2],
				'tf_tags'        => $v[4],
				'tf_year'        => $v[5],
				'tf_signature'   => $v[6],
				'tf_price'       => $v[7],
				'tf_instagram'   => $v[8],
			),
			array(
				'menu_order'   => $i,
				'post_excerpt' => $v[3],
				'post_content' => '<!-- wp:paragraph --><p>' . esc_html( $v[3] ) . '</p><!-- /wp:paragraph -->',
			)
		);
	}

	// TAPAUfest 2025 partners, as credited on the 2025 poster and in press coverage.
	$partners = array(
		array( 'supporter', 'ASEAN Malaysia 2025', 'Main supporter of the ASEAN edition', '' ),
		array( 'supporter', 'Visit Malaysia 2026', 'Main supporter, with Tourism Malaysia', '' ),
		array( 'strategic', 'KBS', 'Kementerian Belia dan Sukan (Ministry of Youth and Sports)', '' ),
		array( 'strategic', 'IMPACT', 'Impact Integrated, strategic partner', '' ),
		array( 'strategic', 'Majlis Daerah Lenggong', 'Local council and host of the 2025 site at Resort Tasik Raban', '' ),
		array( 'programme', 'Pekan Pop Up', 'Curated the PASAR market', '' ),
		array( 'programme', 'SEA Short Film Festival', 'Short films for LAYAR', '' ),
		array( 'programme', 'Sesi Tikar Mengkuang', 'Yoga at KEMBARA', '' ),
		array( 'programme', 'Santuy Travellers', 'Campsite and late-night bonfire sets', '' ),
		array( 'programme', 'Slow Cub Camp', 'Campsite', '' ),
		array( 'brand', 'Spritzer', 'Free drinking water for everyone on site', '' ),
		array( 'brand', 'Humblezing', 'TAPAUfest x Humblezing merch', '' ),
		array( 'media', 'ERA', 'Media partner', '' ),
		array( 'media', 'hitz', 'Media partner', '' ),
		array( 'media', 'VOCKET', 'Media partner', '' ),
		array( 'media', 'Noisy Headspace', 'Media partner', 'https://noisyheadspace.com/' ),
		array( 'media', 'Rakita 107.9', 'Media partner', '' ),
		array( 'community', 'NRDN Photography', 'Festival photography', '' ),
		array( 'community', 'TAPAUtix', 'Ticketing', 'https://tapautix.asia/' ),
	);
	// Past sponsors and partners since 2014.
	foreach ( array( 'JBL', 'Waka', 'Vans', 'ERA', 'TV9', 'Astro', 'TM', 'Cradle', 'Maxis', 'Tune Talk', 'Kementerian Komunikasi dan Digital', 'MyCreative Ventures', 'SYOK', 'Grab', 'Maybank', 'Marshall Headphones', 'Yoodo', 'Gempak', 'iConcerts', 'MUBI', 'Cendana', 'Rakita', 'ArtsFAS by Yayasan Hasanah', 'Tourism Malaysia', 'Montigo' ) as $tf_past ) {
		$partners[] = array( 'past', $tf_past, '', '' );
	}
	foreach ( $partners as $i => $p ) {
		tapaufest_demo_insert(
			'tf_partner',
			$p[1],
			array(
				'tf_tier' => $p[0],
				'tf_role' => $p[2],
				'tf_url'  => $p[3],
				'tf_year' => 'past' === $p[0] ? '' : '2025',
			),
			array( 'menu_order' => $i )
		);
	}

	$pages = array(
		'tickets'    => 'Tickets',
		'timetable'  => 'Timetable',
		'experience' => 'Experience',
		'plan'       => 'Plan your trip',
		'map'        => 'Map and help',
		'faq'        => 'FAQ',
		'partners'   => 'Partners',
		'sell'       => 'Sell at TAPAUfest',
		'recap'      => 'Recap',
		'archive'    => 'Past editions',
	);
	foreach ( $pages as $slug => $title ) {
		if ( get_page_by_path( $slug ) ) {
			continue;
		}
		tapaufest_demo_insert(
			'page',
			$title,
			array( '_wp_page_template' => 'page-landing' ),
			array(
				'post_name'    => $slug,
				'post_content' => '<!-- wp:pattern {"slug":"tapaufest-2026/page-' . $slug . '"} /-->',
			)
		);
	}

	// Festival pages are built from screens (see includes/screens.php).
	tapaufest_install_screen_pages();

	flush_rewrite_rules();
	return true;
}

/**
 * Remove everything the seeder created.
 */
function tapaufest_unseed() {
	$ids = get_posts(
		array(
			'post_type'      => array( 'tf_artist', 'tf_session', 'tf_tier', 'tf_faq', 'tf_vendor', 'tf_partner', 'page' ),
			'post_status'    => 'any',
			'posts_per_page' => -1,
			'meta_key'       => '_tapaufest_demo', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_query_meta_key
			'fields'         => 'ids',
		)
	);
	foreach ( $ids as $id ) {
		wp_delete_post( $id, true );
	}
	$media = get_posts(
		array(
			'post_type'      => 'attachment',
			'post_status'    => 'any',
			'posts_per_page' => -1,
			'meta_key'       => '_tapaufest_demo', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_query_meta_key
			'fields'         => 'ids',
		)
	);
	foreach ( $media as $id ) {
		wp_delete_attachment( $id, true );
	}
	$terms = get_terms(
		array(
			'taxonomy'   => 'tf_faq_group',
			'hide_empty' => false,
			'meta_key'   => '_tapaufest_demo', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_query_meta_key
			'fields'     => 'ids',
		)
	);
	if ( ! is_wp_error( $terms ) ) {
		foreach ( $terms as $term_id ) {
			wp_delete_term( $term_id, 'tf_faq_group' );
		}
	}
}

/**
 * Admin button: load demo content.
 */
function tapaufest_handle_seed() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You don’t have permission to do that.', 'tapaufest' ) );
	}
	check_admin_referer( 'tapaufest_seed' );
	tapaufest_seed();
	wp_safe_redirect( admin_url( 'options-general.php?page=tapaufest&tapaufest_seeded=1' ) );
	exit;
}
add_action( 'admin_post_tapaufest_seed', 'tapaufest_handle_seed' );

/**
 * Admin button: remove demo content.
 */
function tapaufest_handle_unseed() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You don’t have permission to do that.', 'tapaufest' ) );
	}
	check_admin_referer( 'tapaufest_unseed' );
	tapaufest_unseed();
	wp_safe_redirect( admin_url( 'options-general.php?page=tapaufest&tapaufest_unseeded=1' ) );
	exit;
}
add_action( 'admin_post_tapaufest_unseed', 'tapaufest_handle_unseed' );

if ( defined( 'WP_CLI' ) && WP_CLI ) {
	WP_CLI::add_command(
		'tapaufest seed',
		function () {
			if ( tapaufest_seed() ) {
				WP_CLI::success( 'Demo content loaded.' );
			} else {
				WP_CLI::warning( 'Demo content is already loaded. Run `wp tapaufest unseed` first to reload it.' );
			}
		}
	);
	WP_CLI::add_command(
		'tapaufest unseed',
		function () {
			tapaufest_unseed();
			WP_CLI::success( 'Demo content removed.' );
		}
	);
	WP_CLI::add_command(
		'tapaufest state',
		function ( $args ) {
			$state = isset( $args[0] ) ? $args[0] : '';
			if ( ! array_key_exists( $state, tapaufest_states() ) ) {
				WP_CLI::error( 'Use one of: ' . implode( ', ', array_keys( tapaufest_states() ) ) );
			}
			$opt          = get_option( 'tapaufest_settings', array() );
			$opt          = is_array( $opt ) ? $opt : array();
			$opt['state'] = $state;
			update_option( 'tapaufest_settings', $opt );
			WP_CLI::success( 'Site state is now ' . $state . '.' );
		}
	);
}
