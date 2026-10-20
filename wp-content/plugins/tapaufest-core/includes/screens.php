<?php
/**
 * Data and endpoints behind the festival screens: "Ping me" for sold-out
 * passes, the checkout hand-off, the offline service worker, and the session
 * list the Jadual Aku page reads.
 *
 * @package TapaufestCore
 */

defined( 'ABSPATH' ) || exit;

/**
 * Price a tier sells at right now.
 *
 * @param array $t Tier.
 * @return int
 */
function tapaufest_tier_price( $t ) {
	return ( 'early' === tapaufest_state() && $t['early'] ) ? $t['early'] : $t['regular'];
}

/**
 * Hand-off URL for a tier: our own page that says where you are going, then
 * sends you to TAPAUtix.
 *
 * @param array $t   Tier.
 * @param int   $qty How many.
 * @return string
 */
function tapaufest_checkout_url( $t, $qty = 1 ) {
	return add_query_arg(
		array(
			'tier' => (int) $t['id'],
			'qty'  => max( 1, min( 6, (int) $qty ) ),
		),
		home_url( '/checkout/' )
	);
}

/**
 * Where a tier's buy button finally goes.
 *
 * @param array $t Tier.
 * @return string
 */
function tapaufest_tier_buy_url( $t ) {
	return $t['url'] ? $t['url'] : tapaufest_setting( 'tickets_url' );
}

/**
 * Sessions as plain data for the Jadual Aku page script.
 *
 * @return array[]
 */
function tapaufest_sessions_json() {
	$days    = tapaufest_days();
	$notices = tapaufest_notices();
	$out     = array();
	foreach ( tapaufest_get_sessions() as $s ) {
		if ( 'prayer' === $s['kind'] ) {
			continue;
		}
		$out[] = array(
			'id'       => (string) $s['id'],
			'title'    => html_entity_decode( $s['title'], ENT_QUOTES, 'UTF-8' ),
			'url'      => $s['url'],
			'day'      => $s['day'],
			'dayLabel' => isset( $days[ $s['day'] ] ) ? $days[ $s['day'] ] : $s['day'],
			'stage'    => $s['stage'],
			'start'    => $s['start_min'],
			'end'      => $s['end_min'],
			'time'     => $s['label'],
			'kind'     => $s['kind'],
			'off'      => isset( $notices[ $s['stage'] ] ) && 'off' === $notices[ $s['stage'] ]['status'],
		);
	}
	return $out;
}

/**
 * "Ping me" sign-ups for a sold-out pass. Saved to an option and emailed to
 * the festival inbox, so nobody needs a mailing tool to start.
 */
function tapaufest_handle_ping() {
	$back  = wp_get_referer() ? remove_query_arg( array( 'pinged', 'ping_error' ), wp_get_referer() ) : home_url( '/tickets/' );
	$ajax  = ! empty( $_POST['tf_ajax'] ); // phpcs:ignore WordPress.Security.NonceVerification.Missing -- checked below.
	$fail  = function ( $code ) use ( $back, $ajax ) {
		if ( $ajax ) {
			wp_send_json_error( array( 'code' => $code ), 400 );
		}
		wp_safe_redirect( add_query_arg( 'ping_error', $code, $back ) . '#tickets' );
		exit;
	};
	if ( ! isset( $_POST['tf_ping_nonce'] ) || ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['tf_ping_nonce'] ) ), 'tapaufest_ping' ) ) {
		$fail( 'expired' );
	}
	$email = isset( $_POST['tf_ping_email'] ) ? sanitize_email( wp_unslash( $_POST['tf_ping_email'] ) ) : '';
	$tier  = isset( $_POST['tf_ping_tier'] ) ? absint( $_POST['tf_ping_tier'] ) : 0;
	if ( ! is_email( $email ) ) {
		$fail( 'email' );
	}
	$list = get_option( 'tapaufest_pings', array() );
	$list = is_array( $list ) ? $list : array();
	$key  = md5( strtolower( $email ) . '|' . $tier );
	if ( ! isset( $list[ $key ] ) ) {
		$list[ $key ] = array(
			'email' => $email,
			'tier'  => $tier,
			'time'  => current_time( 'mysql' ),
		);
		update_option( 'tapaufest_pings', $list, false );
		$to = tapaufest_setting( 'apply_email' ) ? tapaufest_setting( 'apply_email' ) : get_option( 'admin_email' );
		/* translators: 1: email, 2: pass name. */
		wp_mail( $to, __( 'Ping me: sold-out pass', 'tapaufest' ), sprintf( __( "%1\$s wants to hear if %2\$s comes back.", 'tapaufest' ), $email, $tier ? get_the_title( $tier ) : __( 'a pass', 'tapaufest' ) ) );
	}
	if ( $ajax ) {
		wp_send_json_success();
	}
	wp_safe_redirect( add_query_arg( 'pinged', $tier, $back ) . '#tickets' );
	exit;
}
add_action( 'admin_post_tapaufest_ping', 'tapaufest_handle_ping' );
add_action( 'admin_post_nopriv_tapaufest_ping', 'tapaufest_handle_ping' );

/**
 * Serve the offline service worker from the site root, so it can look after
 * every page. Network first; the saved copy only shows when there's no signal.
 */
function tapaufest_service_worker() {
	$path = wp_parse_url( home_url( '/tf-sw.js' ), PHP_URL_PATH );
	$req  = isset( $_SERVER['REQUEST_URI'] ) ? wp_parse_url( esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ), PHP_URL_PATH ) : '';
	if ( $req !== $path ) {
		return;
	}
	$file = TAPAUFEST_DIR . 'assets/sw.js';
	if ( ! file_exists( $file ) ) {
		return;
	}
	nocache_headers();
	header( 'Content-Type: application/javascript; charset=utf-8' );
	header( 'Service-Worker-Allowed: /' );
	$js = file_get_contents( $file ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
	echo str_replace( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- our own script file.
		array( '__TF_VERSION__', '__TF_HOME__', '__TF_OFFLINE_TITLE__', '__TF_OFFLINE_TEXT__', '__TF_RETRY__' ),
		array(
			esc_js( TAPAUFEST_VERSION ),
			esc_url_raw( home_url( '/' ) ),
			esc_js( __( 'Couldn’t load that.', 'tapaufest' ) ),
			esc_js( __( 'No signal right now. Pages you opened before still work.', 'tapaufest' ) ),
			esc_js( __( 'Try again', 'tapaufest' ) ),
		),
		$js
	);
	exit;
}
add_action( 'init', 'tapaufest_service_worker', 1 );

/**
 * Front-end config the screen script reads.
 */
function tapaufest_screen_config() {
	wp_localize_script(
		'tapaufest-view',
		'tapaufestScreen',
		array(
			'sw'      => esc_url_raw( home_url( '/tf-sw.js' ) ),
			'home'    => esc_url_raw( home_url( '/' ) ),
			'jadual'  => esc_url_raw( home_url( '/jadual-aku/' ) ),
			'state'   => tapaufest_state(),
			'version' => TAPAUFEST_VERSION,
			'i18n'    => array(
				'offline'  => __( 'Offline. Showing what you saved', 'tapaufest' ),
				'online'   => __( 'Back online.', 'tapaufest' ),
				'moved'    => __( 'Time change', 'tapaufest' ),
				'fix'      => __( 'things to fix before we can send', 'tapaufest' ),
				'fixOne'   => __( '1 thing to fix before we can send', 'tapaufest' ),
				'sending'  => __( 'Sending…', 'tapaufest' ),
				'sendFail' => __( 'Didn’t send. Connection dropped. Your answers are still here.', 'tapaufest' ),
				'slow'     => __( 'Too many tries. Wait a minute, then send again.', 'tapaufest' ),
				'again'    => __( 'Send again', 'tapaufest' ),
				'againIn'  => __( 'Send again in', 'tapaufest' ),
				'pinged'   => __( 'You’re on the list. We email you first.', 'tapaufest' ),
				'copied'   => __( 'Link copied. Send it to your geng.', 'tapaufest' ),
			),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'tapaufest_screen_config', 20 );

/**
 * Pages built from festival screens: slug => array( title, screen ).
 *
 * @return array
 */
function tapaufest_screen_pages() {
	return array(
		'tickets'     => array( __( 'Tickets', 'tapaufest' ), 'tickets' ),
		'timetable'   => array( __( 'Set times', 'tapaufest' ), 'times' ),
		'jadual-aku'  => array( __( 'Jadual Aku', 'tapaufest' ), 'jadual' ),
		'experience'  => array( __( 'What’s on', 'tapaufest' ), 'zones' ),
		'plan'        => array( __( 'Getting there', 'tapaufest' ), 'route' ),
		'map'         => array( __( 'Map', 'tapaufest' ), 'map' ),
		'now'         => array( __( 'On now', 'tapaufest' ), 'now' ),
		'help'        => array( __( 'Help on site', 'tapaufest' ), 'help' ),
		'makan-pasar' => array( __( 'Makan & Pasar', 'tapaufest' ), 'vendors' ),
		'faq'         => array( __( 'FAQ', 'tapaufest' ), 'faq' ),
		'sell'        => array( __( 'Sell at TAPAUfest', 'tapaufest' ), 'sell' ),
		'partners'    => array( __( 'Partners', 'tapaufest' ), 'partners' ),
		'archive'     => array( __( 'Past editions', 'tapaufest' ), 'archive' ),
		'recap'       => array( __( 'Recap', 'tapaufest' ), 'recap' ),
		'checkout'    => array( __( 'Checkout', 'tapaufest' ), 'checkout' ),
		'youre-in'    => array( __( 'You’re in', 'tapaufest' ), 'youre-in' ),
	);
}

/**
 * Create or update the screen pages. Safe to run again.
 *
 * @return array Slug => page ID.
 */
function tapaufest_install_screen_pages() {
	$out = array();
	foreach ( tapaufest_screen_pages() as $slug => $info ) {
		$content = '<!-- wp:tapaufest/screen {"screen":"' . $info[1] . '","align":"full"} /-->';
		$page    = get_page_by_path( $slug );
		$args    = array(
			'post_type'    => 'page',
			'post_status'  => 'publish',
			'post_title'   => $info[0],
			'post_name'    => $slug,
			'post_content' => $content,
		);
		if ( $page ) {
			$args['ID'] = $page->ID;
			$id         = wp_update_post( $args );
		} else {
			$id = wp_insert_post( $args );
		}
		if ( $id && ! is_wp_error( $id ) ) {
			update_post_meta( $id, '_wp_page_template', 'page-screen' );
			$out[ $slug ] = $id;
		}
	}
	// Utility pages stay out of search results.
	foreach ( array( 'checkout', 'youre-in' ) as $slug ) {
		if ( isset( $out[ $slug ] ) ) {
			update_post_meta( $out[ $slug ], '_tapaufest_noindex', 1 );
		}
	}
	return $out;
}

/**
 * Keep hand-off pages out of search engines.
 *
 * @param array $robots Robots directives.
 * @return array
 */
function tapaufest_screen_robots( $robots ) {
	if ( is_page() && get_post_meta( get_queried_object_id(), '_tapaufest_noindex', true ) ) {
		$robots['noindex'] = true;
	}
	return $robots;
}
add_filter( 'wp_robots', 'tapaufest_screen_robots' );
