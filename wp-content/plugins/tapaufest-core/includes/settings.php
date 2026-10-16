<?php
/**
 * Festival settings: the site state switch and festival facts.
 *
 * Settings live in one option, `tapaufest_settings`, edited under
 * Settings > TAPAUfest.
 *
 * @package TapaufestCore
 */

defined( 'ABSPATH' ) || exit;

/**
 * Default settings for the 2026 edition.
 *
 * @return array
 */
function tapaufest_default_settings() {
	return array(
		'state'          => 'early',
		'alert_on'       => 1,
		'alert_text'     => '',
		'edition'        => 'TAPAUfest 2026',
		'day1'           => '2026-12-12',
		'day2'           => '2026-12-13',
		'gates_open'     => '11:00',
		'venue'          => 'Sungai Congkak, Hulu Langat, Selangor',
		'maps_url'       => 'https://www.google.com/maps/search/Sungai+Congkak+Hulu+Langat',
		'tickets_url'    => 'https://tapautix.asia/',
		'join_url'       => 'https://tapau.asia/',
		'waitlist_url'   => '',
		'aftermovie_url' => 'https://tapau.asia/vod/tapaufest-2025-asean-the-aftermovie/',
		'survey_url'     => '',
		'takedown_url'   => '',
		'whatsapp_url'   => '',
		'stages'         => "PENTAS|Main stage\nLAYAR|Screenings\nBENGKEL|Workshops\nKEMBARA|Outdoor\nUNGGUN|Bonfire at the campsite",
		'timezone'       => 'Asia/Kuala_Lumpur',
	);
}

/**
 * Site states, in the order the festival moves through them.
 *
 * @return array Slug => label.
 */
function tapaufest_states() {
	return array(
		'early'  => __( 'Early access (waitlist window)', 'tapaufest' ),
		'public' => __( 'Public sale', 'tapaufest' ),
		'live'   => __( 'Festival day (live mode)', 'tapaufest' ),
		'recap'  => __( 'Recap', 'tapaufest' ),
	);
}

/**
 * Read one setting, falling back to its default.
 *
 * @param string $key Setting key.
 * @return mixed
 */
function tapaufest_setting( $key ) {
	$saved    = get_option( 'tapaufest_settings', array() );
	$defaults = tapaufest_default_settings();
	if ( is_array( $saved ) && isset( $saved[ $key ] ) && '' !== $saved[ $key ] ) {
		return $saved[ $key ];
	}
	return isset( $defaults[ $key ] ) ? $defaults[ $key ] : '';
}

/**
 * Current site state slug.
 *
 * @return string
 */
function tapaufest_state() {
	$state = tapaufest_setting( 'state' );
	return array_key_exists( $state, tapaufest_states() ) ? $state : 'early';
}

/**
 * Festival timezone object.
 *
 * @return DateTimeZone
 */
function tapaufest_timezone() {
	try {
		return new DateTimeZone( tapaufest_setting( 'timezone' ) );
	} catch ( Exception $e ) {
		return wp_timezone();
	}
}

/**
 * Stages as slug => description, in display order.
 *
 * @return array
 */
function tapaufest_stages() {
	$stages = array();
	$lines  = preg_split( '/\r\n|\r|\n/', (string) tapaufest_setting( 'stages' ) );
	foreach ( $lines as $line ) {
		$parts = array_map( 'trim', explode( '|', $line, 2 ) );
		if ( '' === $parts[0] ) {
			continue;
		}
		$stages[ strtoupper( $parts[0] ) ] = isset( $parts[1] ) ? $parts[1] : '';
	}
	return $stages;
}

/**
 * Festival days as Y-m-d => short label.
 *
 * @return array
 */
function tapaufest_days() {
	$days = array();
	foreach ( array( 'day1', 'day2' ) as $key ) {
		$date = tapaufest_setting( $key );
		if ( ! $date ) {
			continue;
		}
		$ts = strtotime( $date . ' 12:00:00' );
		if ( $ts ) {
			$days[ $date ] = wp_date( 'D j M', $ts, tapaufest_timezone() );
		}
	}
	return $days;
}

/**
 * Default alert text per state, used when the editor leaves it blank.
 *
 * @param string $state State slug.
 * @return array Tag and text.
 */
function tapaufest_alert( $state = '' ) {
	$state    = $state ? $state : tapaufest_state();
	$defaults = array(
		'early'  => array( __( 'Early access', 'tapaufest' ), __( 'The waitlist window is open. Use the code in your email at checkout for the early-bird price.', 'tapaufest' ) ),
		'public' => array( __( 'On sale', 'tapaufest' ), __( 'Tickets are on sale now on TAPAUtix.', 'tapaufest' ) ),
		'live'   => array( __( 'Live', 'tapaufest' ), __( 'Set changes and weather updates post here first.', 'tapaufest' ) ),
		'recap'  => array( __( 'Recap', 'tapaufest' ), __( 'Photos are up. Find yourself in the Day 1 and Day 2 galleries.', 'tapaufest' ) ),
	);
	$custom = trim( (string) tapaufest_setting( 'alert_text' ) );
	return array(
		'tag'  => $defaults[ $state ][0],
		'text' => '' !== $custom ? $custom : $defaults[ $state ][1],
	);
}

/**
 * Register the settings page.
 */
function tapaufest_settings_menu() {
	add_options_page(
		__( 'TAPAUfest', 'tapaufest' ),
		__( 'TAPAUfest', 'tapaufest' ),
		'manage_options',
		'tapaufest',
		'tapaufest_render_settings_page'
	);
}
add_action( 'admin_menu', 'tapaufest_settings_menu' );

/**
 * Register the option with its sanitizer.
 */
function tapaufest_register_settings() {
	register_setting(
		'tapaufest',
		'tapaufest_settings',
		array(
			'type'              => 'array',
			'sanitize_callback' => 'tapaufest_sanitize_settings',
			'default'           => tapaufest_default_settings(),
		)
	);
}
add_action( 'admin_init', 'tapaufest_register_settings' );

/**
 * Sanitize submitted settings.
 *
 * @param array $input Raw input.
 * @return array
 */
function tapaufest_sanitize_settings( $input ) {
	$input    = is_array( $input ) ? $input : array();
	$defaults = tapaufest_default_settings();
	$out      = array();

	$out['state']    = ( isset( $input['state'] ) && array_key_exists( $input['state'], tapaufest_states() ) ) ? $input['state'] : 'early';
	$out['alert_on'] = empty( $input['alert_on'] ) ? 0 : 1;

	foreach ( array( 'alert_text', 'edition', 'venue', 'gates_open', 'timezone' ) as $key ) {
		$out[ $key ] = isset( $input[ $key ] ) ? sanitize_text_field( $input[ $key ] ) : $defaults[ $key ];
	}
	foreach ( array( 'day1', 'day2' ) as $key ) {
		$value       = isset( $input[ $key ] ) ? sanitize_text_field( $input[ $key ] ) : '';
		$out[ $key ] = preg_match( '/^\d{4}-\d{2}-\d{2}$/', $value ) ? $value : $defaults[ $key ];
	}
	foreach ( array( 'maps_url', 'tickets_url', 'join_url', 'waitlist_url', 'aftermovie_url', 'survey_url', 'takedown_url', 'whatsapp_url' ) as $key ) {
		$out[ $key ] = isset( $input[ $key ] ) ? esc_url_raw( $input[ $key ] ) : '';
	}
	$out['stages'] = isset( $input['stages'] ) ? sanitize_textarea_field( $input['stages'] ) : $defaults['stages'];

	return $out;
}

/**
 * Render Settings > TAPAUfest.
 */
function tapaufest_render_settings_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$opt = wp_parse_args( get_option( 'tapaufest_settings', array() ), tapaufest_default_settings() );

	$text_fields = array(
		'edition'        => __( 'Edition name', 'tapaufest' ),
		'day1'           => __( 'Day 1 (YYYY-MM-DD)', 'tapaufest' ),
		'day2'           => __( 'Day 2 (YYYY-MM-DD)', 'tapaufest' ),
		'gates_open'     => __( 'Gates open (HH:MM)', 'tapaufest' ),
		'venue'          => __( 'Venue', 'tapaufest' ),
		'timezone'       => __( 'Festival timezone', 'tapaufest' ),
		'maps_url'       => __( 'Maps link', 'tapaufest' ),
		'tickets_url'    => __( 'TAPAUtix event page', 'tapaufest' ),
		'join_url'       => __( 'Geng TAPAU sign-up link', 'tapaufest' ),
		'waitlist_url'   => __( 'Next-edition waitlist link', 'tapaufest' ),
		'aftermovie_url' => __( 'Aftermovie link', 'tapaufest' ),
		'survey_url'     => __( 'Feedback survey link', 'tapaufest' ),
		'takedown_url'   => __( 'Photo takedown form link', 'tapaufest' ),
		'whatsapp_url'   => __( 'WhatsApp channel link', 'tapaufest' ),
	);
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'TAPAUfest', 'tapaufest' ); ?></h1>
		<?php if ( isset( $_GET['tapaufest_seeded'] ) ) : // phpcs:ignore WordPress.Security.NonceVerification.Recommended ?>
			<div class="notice notice-success"><p><?php esc_html_e( 'Demo content is in. Every demo item is tagged so you can remove it in one click.', 'tapaufest' ); ?></p></div>
		<?php endif; ?>
		<?php if ( isset( $_GET['tapaufest_unseeded'] ) ) : // phpcs:ignore WordPress.Security.NonceVerification.Recommended ?>
			<div class="notice notice-success"><p><?php esc_html_e( 'Demo content removed.', 'tapaufest' ); ?></p></div>
		<?php endif; ?>

		<form method="post" action="options.php">
			<?php settings_fields( 'tapaufest' ); ?>
			<h2><?php esc_html_e( 'Site state', 'tapaufest' ); ?></h2>
			<p><?php esc_html_e( 'Switch this as the festival moves along. It changes the homepage hero, the alert bar, the header button and the ticket buttons.', 'tapaufest' ); ?></p>
			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><?php esc_html_e( 'State', 'tapaufest' ); ?></th>
					<td>
						<?php foreach ( tapaufest_states() as $slug => $label ) : ?>
							<label style="display:block;margin-bottom:6px">
								<input type="radio" name="tapaufest_settings[state]" value="<?php echo esc_attr( $slug ); ?>" <?php checked( $opt['state'], $slug ); ?>>
								<?php echo esc_html( $label ); ?>
							</label>
						<?php endforeach; ?>
					</td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'Alert bar', 'tapaufest' ); ?></th>
					<td>
						<label><input type="checkbox" name="tapaufest_settings[alert_on]" value="1" <?php checked( ! empty( $opt['alert_on'] ) ); ?>> <?php esc_html_e( 'Show the alert bar', 'tapaufest' ); ?></label>
						<p><input type="text" class="large-text" name="tapaufest_settings[alert_text]" value="<?php echo esc_attr( $opt['alert_text'] ); ?>" placeholder="<?php echo esc_attr( tapaufest_alert()['text'] ); ?>"></p>
						<p class="description"><?php esc_html_e( 'Leave blank to use the default message for the current state. On festival days, post set changes and weather updates here.', 'tapaufest' ); ?></p>
					</td>
				</tr>
			</table>

			<h2><?php esc_html_e( 'Festival details', 'tapaufest' ); ?></h2>
			<table class="form-table" role="presentation">
				<?php foreach ( $text_fields as $key => $label ) : ?>
					<tr>
						<th scope="row"><label for="tf-<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $label ); ?></label></th>
						<td><input type="text" class="regular-text" id="tf-<?php echo esc_attr( $key ); ?>" name="tapaufest_settings[<?php echo esc_attr( $key ); ?>]" value="<?php echo esc_attr( $opt[ $key ] ); ?>"></td>
					</tr>
				<?php endforeach; ?>
				<tr>
					<th scope="row"><label for="tf-stages"><?php esc_html_e( 'Stages', 'tapaufest' ); ?></label></th>
					<td>
						<textarea id="tf-stages" class="large-text" rows="6" name="tapaufest_settings[stages]"><?php echo esc_textarea( $opt['stages'] ); ?></textarea>
						<p class="description"><?php esc_html_e( 'One per line, as NAME|Short description. The order here is the order on the timetable.', 'tapaufest' ); ?></p>
					</td>
				</tr>
			</table>
			<?php submit_button(); ?>
		</form>

		<hr>
		<h2><?php esc_html_e( 'Demo content', 'tapaufest' ); ?></h2>
		<p><?php esc_html_e( 'Loads mock artists, set times, ticket tiers, FAQs, vendors, pages and the main menu so the site can be reviewed before real content is ready.', 'tapaufest' ); ?></p>
		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="display:inline-block;margin-right:8px">
			<input type="hidden" name="action" value="tapaufest_seed">
			<?php wp_nonce_field( 'tapaufest_seed' ); ?>
			<?php submit_button( __( 'Load demo content', 'tapaufest' ), 'secondary', 'submit', false ); ?>
		</form>
		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="display:inline-block">
			<input type="hidden" name="action" value="tapaufest_unseed">
			<?php wp_nonce_field( 'tapaufest_unseed' ); ?>
			<?php submit_button( __( 'Remove demo content', 'tapaufest' ), 'delete', 'submit', false ); ?>
		</form>
	</div>
	<?php
}

/**
 * Show the current state in the admin bar so editors always know which version visitors see.
 *
 * @param WP_Admin_Bar $bar Admin bar.
 */
function tapaufest_admin_bar( $bar ) {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$states = tapaufest_states();
	$bar->add_node(
		array(
			'id'    => 'tapaufest-state',
			/* translators: %s: site state label. */
			'title' => sprintf( __( 'Fest: %s', 'tapaufest' ), $states[ tapaufest_state() ] ),
			'href'  => admin_url( 'options-general.php?page=tapaufest' ),
		)
	);
}
add_action( 'admin_bar_menu', 'tapaufest_admin_bar', 90 );
