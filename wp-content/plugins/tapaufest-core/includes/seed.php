<?php
/**
 * Demo content: mock artists, set times, tiers, FAQs, vendors and pages.
 *
 * Everything created here is tagged with the `_tapaufest_demo` meta key so
 * it can be removed in one go. Load it from Settings > TAPAUfest or with
 * `wp tapaufest seed`; remove it with `wp tapaufest unseed`.
 *
 * Artists, photos and the running order come from TAPAUfest 2025 and stand in
 * until the 2026 lineup is announced. Times, prices and vendors are placeholders.
 *
 * @package TapaufestCore
 */

defined( 'ABSPATH' ) || exit;

/**
 * Demo artists: the TAPAUfest 2025 lineup, standing in until the 2026 lineup
 * is announced. Name, country, genre, headliner, bio, photo file.
 *
 * @return array
 */
function tapaufest_demo_artists() {
	return array(
		array( 'Raksar', 'MY', 'Indie', false, 'Opened Day 1 of TAPAUfest 2025 by the lake in Lenggong.', 'raksar' ),
		array( 'Skandal', 'ID', 'Indie', false, 'From Yogyakarta. TAPAUfest 2025 was their first show outside Indonesia.', 'skandal' ),
		array( 'Seru x Wani Ardy', 'MY', 'Music and poetry', false, 'Seru with poet Wani Ardy: songs and spoken word on one stage.', 'seru-x-wani-ardy' ),
		array( 'KIKI', 'TH', 'Indie', false, 'Brought the Thai indie scene to PENTAS on Day 1.', 'kiki' ),
		array( 'FUGŌ', 'MY', 'Indie', false, 'Played Day 1 with a special appearance by Heidi Moru.', 'fugo' ),
		array( 'Sal Priadi', 'ID', 'Singer-songwriter', true, 'Closed Day 1 with a theatrical, all-in set.', 'sal-priadi' ),
		array( 'Cactus Cactus', 'SG', 'Indie', false, 'Singapore four-piece who opened Day 2.', 'cactus-cactus' ),
		array( 'Okirama', 'MY', 'Indie', false, 'Their healing-chairs setup caught the sunset on Day 2.', 'okirama' ),
		array( 'White Chorus', 'ID', 'Indie', false, 'Indonesian band on the Day 2 bill.', 'white-chorus' ),
		array( 'Insomniacks', 'MY', 'Pop rock', false, 'A Day 2 singalong with one of Malaysia’s best-loved bands.', 'insomniacks' ),
		array( 'Perunggu', 'ID', 'Rock', false, 'Jakarta rock band, loud and big-hearted.', 'perunggu' ),
		array( 'Couple', 'MY', 'Indie rock', true, 'Reunited their Top of the Pops lineup for the first time since 2006: a 14-song set for their 30th anniversary.', 'couple' ),
	);
}

/**
 * Demo sessions: day index (1|2), stage, start, minutes, artist name or kind:title.
 * Running order follows TAPAUfest 2025; the times are placeholders.
 *
 * @return array
 */
function tapaufest_demo_sessions() {
	return array(
		array( 1, 'KEMBARA', '08:00', 60, 'outdoor:Yoga with Sesi Tikar Mengkuang' ),
		array( 1, 'KEMBARA', '10:00', 90, 'outdoor:Kayaking (weather dependent)' ),
		array( 1, 'BENGKEL', '11:00', 90, 'workshop:Rubber-cut printmaking' ),
		array( 1, 'BENGKEL', '14:00', 60, 'workshop:Talk: building a visual identity' ),
		array( 1, 'BENGKEL', '15:30', 90, 'workshop:Mini-zine making' ),
		array( 1, 'LAYAR', '14:00', 60, 'film:SEA Short Film Festival: Vietnam' ),
		array( 1, 'LAYAR', '16:00', 70, 'film:SEA Short Film Festival: Myanmar' ),
		array( 1, 'LAYAR', '21:00', 60, 'film:Night shorts' ),
		array( 1, 'PENTAS', '16:00', 45, 'Raksar' ),
		array( 1, 'PENTAS', '17:00', 45, 'Skandal' ),
		array( 1, 'PENTAS', '18:00', 50, 'Seru x Wani Ardy' ),
		array( 1, 'PENTAS', '19:10', 30, 'prayer:Maghrib break. Surau next to WARUNG.' ),
		array( 1, 'PENTAS', '19:45', 50, 'KIKI' ),
		array( 1, 'PENTAS', '20:50', 60, 'FUGŌ' ),
		array( 1, 'PENTAS', '22:10', 75, 'Sal Priadi' ),
		array( 1, 'UNGGUN', '24:00', 60, 'other:Bonfire sets at the campsite' ),
		array( 2, 'KEMBARA', '07:30', 60, 'outdoor:Sunrise yoga' ),
		array( 2, 'KEMBARA', '09:30', 90, 'outdoor:Boat ride (weather dependent)' ),
		array( 2, 'BENGKEL', '11:00', 60, 'workshop:Talk: the ASEAN indie circuit' ),
		array( 2, 'BENGKEL', '14:30', 90, 'workshop:Zine swap and print jam' ),
		array( 2, 'LAYAR', '14:00', 60, 'film:SEA Short Film Festival: Malaysia' ),
		array( 2, 'LAYAR', '20:00', 60, 'film:Night shorts' ),
		array( 2, 'PENTAS', '16:00', 45, 'Cactus Cactus' ),
		array( 2, 'PENTAS', '17:00', 45, 'Okirama' ),
		array( 2, 'PENTAS', '18:00', 50, 'White Chorus' ),
		array( 2, 'PENTAS', '19:10', 30, 'prayer:Maghrib break. Surau next to WARUNG.' ),
		array( 2, 'PENTAS', '19:45', 50, 'Insomniacks' ),
		array( 2, 'PENTAS', '20:50', 60, 'Perunggu' ),
		array( 2, 'PENTAS', '22:10', 80, 'Couple' ),
		array( 2, 'UNGGUN', '24:00', 60, 'other:Bonfire jam, open to all' ),
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
			'post_type'      => array( 'tf_artist', 'tf_session', 'tf_tier', 'tf_faq', 'tf_vendor', 'page' ),
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
				'tf_profile'   => 'https://tapau.asia/',
			),
			array(
				'post_content' => '<!-- wp:paragraph --><p>' . esc_html( $a[4] ) . '</p><!-- /wp:paragraph -->',
				'post_excerpt' => $a[4],
				'menu_order'   => $i,
			)
		);
		tapaufest_demo_photo( $a[5], $artist_ids[ $a[0] ], $a[0] . ' (TAPAUfest 2025)' );
	}

	$days = array(
		1 => tapaufest_setting( 'day1' ),
		2 => tapaufest_setting( 'day2' ),
	);
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
			array( 'menu_order' => $i )
		);
	}

	$tiers = array(
		array( 'Solo Day', '1 person · 1 day', 1, 1, 129, 159, "Day 1 or Day 2\nAll zones that day", false ),
		array( 'Solo Weekend', '1 person · 2 days', 1, 2, 229, 279, "Both days\nAll zones", false ),
		array( 'Geng Weekend', '4 people · 2 days', 4, 2, 849, 999, "4 weekend passes\nLowest price per person", false ),
		array( 'Duo Campers', '2 people · 2 days + camping', 2, 2, 529, 629, "2 weekend passes\nTent spot at the festival campsite\nBonfire sets after midnight", true ),
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
				'tf_url'      => tapaufest_setting( 'tickets_url' ),
			),
			array( 'menu_order' => $i )
		);
	}

	$faq = array(
		'The basics' => array(
			array( 'When and where is it?', '12 and 13 Dec 2026 at Sungai Congkak, Hulu Langat, Selangor. Gates open at 11am (to be confirmed).' ),
			array( 'Can I go solo?', 'Plenty do. Grab a Solo pass and say hi to Geng TAPAU at the WARUNG.' ),
			array( 'Can I bring my kids?', 'Yes. Children aged 12 and below must stay with a parent or guardian at all times.' ),
		),
		'Tickets'    => array(
			array( 'How do my tickets work?', 'Your e-ticket arrives by email from TAPAUtix. Swap it for a wristband at the gate and keep it on all weekend. Lost wristbands can’t be replaced.' ),
			array( 'Can I get a refund?', 'All sales are final, unless the festival is cancelled or postponed.' ),
			array( 'What is early access?', 'People on the waitlist get a code by email. Enter it at TAPAUtix checkout for the early-bird price before public sale opens.' ),
		),
		'On site'    => array(
			array( 'What can’t I bring?', 'Glass and bottles, cigarettes and vapes, professional recording gear, weapons, lasers, fireworks and flares, and animals.' ),
			array( 'Is there a surau?', 'Yes, next to the WARUNG. PENTAS pauses for Maghrib on both days.' ),
			array( 'Can I pay by card?', 'Most stalls take DuitNow QR and cards. Bring some cash for smaller stalls.' ),
		),
		'Weather'    => array(
			array( 'What happens if it rains?', 'December is rainy season. Our rain and cancellation policy goes here before tickets go on sale.' ),
			array( 'Are river activities safe?', 'KEMBARA activities only run when water levels are safe. Changes post to the alert bar and the WhatsApp channel first.' ),
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
				array(),
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

	$vendors = array(
		array( 'warung', 'Warung Pak Din', 'Nasi lemak, all day' ),
		array( 'warung', 'Roti John Bros', 'Roti john, late night' ),
		array( 'warung', 'Kopi Lepak', 'Kopi, teh tarik, Milo ais' ),
		array( 'warung', 'Satay Corner', 'Satay and ketupat' ),
		array( 'warung', 'Kedai Hijau', 'Vegetarian rice bowls' ),
		array( 'warung', 'Ais Kacang Kak Mah', 'Desserts' ),
		array( 'pasar', 'TAPAUfest x Humblezing', 'Festival merch. Sold out fast in 2025.' ),
		array( 'pasar', 'Pekan Pop Up', 'Curators of the 2025 PASAR' ),
		array( 'pasar', 'Rak Vinyl', 'Indie vinyl and tapes' ),
		array( 'pasar', 'Zine Corner', 'Zines and prints' ),
		array( 'pasar', 'Hulu Langat Handmade', 'Local crafts' ),
		array( 'pasar', 'Cetak Studio', 'Live screenprinting' ),
	);
	foreach ( $vendors as $i => $v ) {
		tapaufest_demo_insert(
			'tf_vendor',
			$v[1],
			array(
				'tf_vendor_type' => $v[0],
				'tf_note'        => $v[2],
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

	flush_rewrite_rules();
	return true;
}

/**
 * Remove everything the seeder created.
 */
function tapaufest_unseed() {
	$ids = get_posts(
		array(
			'post_type'      => array( 'tf_artist', 'tf_session', 'tf_tier', 'tf_faq', 'tf_vendor', 'page' ),
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
