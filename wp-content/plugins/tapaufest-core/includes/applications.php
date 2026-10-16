<?php
/**
 * Stall applications: a public form (block tapaufest/vendor-apply) that saves
 * each application as a private post the team reviews in wp-admin.
 *
 * No visitor account is needed. Applications are never public, and the form
 * asks for consent before storing anything (PDPA).
 *
 * @package TapaufestCore
 */

defined( 'ABSPATH' ) || exit;

/**
 * Application form fields, in display order.
 *
 * @return array Key => [ label, type, required, options|help ].
 */
function tapaufest_application_fields() {
	return array(
		'tf_app_type'     => array( __( 'What kind of stall?', 'tapaufest' ), 'radio', true, tapaufest_application_types() ),
		'tf_app_business' => array( __( 'Business or brand name', 'tapaufest' ), 'text', true, '' ),
		'tf_app_contact'  => array( __( 'Your name', 'tapaufest' ), 'text', true, '' ),
		'tf_app_email'    => array( __( 'Email', 'tapaufest' ), 'email', true, '' ),
		'tf_app_phone'    => array( __( 'Phone or WhatsApp', 'tapaufest' ), 'tel', true, __( 'Include the country code, e.g. +60 12 345 6789.', 'tapaufest' ) ),
		'tf_app_instagram' => array( __( 'Instagram or website', 'tapaufest' ), 'text', false, __( 'Where we can see your work or menu.', 'tapaufest' ) ),
		'tf_app_offer'    => array( __( 'What will you sell or run?', 'tapaufest' ), 'textarea', true, __( 'Your menu, products or activity, in a few lines.', 'tapaufest' ) ),
		'tf_app_price'    => array( __( 'Typical price range', 'tapaufest' ), 'select', false, tapaufest_price_ranges() ),
		'tf_app_halal'    => array( __( 'Food and drink only: halal status', 'tapaufest' ), 'select', false, array( '' => __( 'Not food or drink', 'tapaufest' ), 'certified' => __( 'Halal certified', 'tapaufest' ), 'muslim' => __( 'Muslim-owned, not certified', 'tapaufest' ), 'no' => __( 'Not halal', 'tapaufest' ) ) ),
		'tf_app_days'     => array( __( 'Which days?', 'tapaufest' ), 'select', true, array( 'both' => __( 'Both days', 'tapaufest' ), 'day1' => __( 'Day 1 only', 'tapaufest' ), 'day2' => __( 'Day 2 only', 'tapaufest' ) ) ),
		'tf_app_power'    => array( __( 'Do you need power?', 'tapaufest' ), 'select', true, array( 'no' => __( 'No', 'tapaufest' ), 'light' => __( 'Lights and phone charging', 'tapaufest' ), 'heavy' => __( 'Cooking equipment', 'tapaufest' ) ) ),
		'tf_app_past'     => array( __( 'Festivals or markets you’ve done before', 'tapaufest' ), 'textarea', false, '' ),
	);
}

/**
 * Stall types.
 *
 * @return array
 */
function tapaufest_application_types() {
	return array(
		'food'     => __( 'Food or drink (WARUNG)', 'tapaufest' ),
		'market'   => __( 'Market, merch or makers (PASAR)', 'tapaufest' ),
		'brand'    => __( 'Brand activation', 'tapaufest' ),
		'activity' => __( 'Workshop or activity', 'tapaufest' ),
	);
}

/**
 * Review statuses.
 *
 * @return array
 */
function tapaufest_application_statuses() {
	return array(
		'new'         => __( 'New', 'tapaufest' ),
		'shortlisted' => __( 'Shortlisted', 'tapaufest' ),
		'accepted'    => __( 'Accepted', 'tapaufest' ),
		'declined'    => __( 'Declined', 'tapaufest' ),
	);
}

/**
 * Register the private Applications type.
 */
function tapaufest_register_applications() {
	register_post_type(
		'tf_application',
		array(
			'labels'          => array(
				'name'          => __( 'Stall applications', 'tapaufest' ),
				'singular_name' => __( 'Stall application', 'tapaufest' ),
				'edit_item'     => __( 'Review application', 'tapaufest' ),
				'menu_name'     => __( 'Applications', 'tapaufest' ),
			),
			'public'          => false,
			'show_ui'         => true,
			'show_in_menu'    => 'edit.php?post_type=tf_artist',
			'show_in_rest'    => false,
			'capability_type' => 'post',
			'capabilities'    => array( 'create_posts' => 'do_not_allow' ),
			'map_meta_cap'    => true,
			'supports'        => array( 'title' ),
		)
	);
}
add_action( 'init', 'tapaufest_register_applications' );

/**
 * Handle a submission (logged in or not).
 */
function tapaufest_handle_application() {
	$back = wp_get_referer() ? wp_get_referer() : home_url( '/sell/' );
	$back = remove_query_arg( array( 'applied', 'apply_error' ), $back );

	// phpcs:disable WordPress.Security.NonceVerification.Missing -- verified just below.
	if ( ! isset( $_POST['tf_app_nonce'] ) || ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['tf_app_nonce'] ) ), 'tapaufest_apply' ) ) {
		wp_safe_redirect( add_query_arg( 'apply_error', 'expired', $back ) . '#apply' );
		exit;
	}
	// Honeypot: real people leave this empty.
	if ( ! empty( $_POST['tf_app_website'] ) ) {
		wp_safe_redirect( add_query_arg( 'applied', '1', $back ) . '#apply' );
		exit;
	}
	// One application per minute per visitor IP.
	$ip_key = 'tf_app_' . md5( isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '' );
	if ( get_transient( $ip_key ) ) {
		wp_safe_redirect( add_query_arg( 'apply_error', 'slow', $back ) . '#apply' );
		exit;
	}

	$data   = array();
	$errors = array();
	foreach ( tapaufest_application_fields() as $key => $f ) {
		$raw = isset( $_POST[ $key ] ) ? wp_unslash( $_POST[ $key ] ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- sanitised per type below.
		switch ( $f[1] ) {
			case 'email':
				$val = sanitize_email( $raw );
				break;
			case 'textarea':
				$val = sanitize_textarea_field( $raw );
				break;
			case 'radio':
			case 'select':
				$val = sanitize_key( $raw );
				$val = array_key_exists( $val, $f[3] ) ? $val : '';
				break;
			default:
				$val = sanitize_text_field( $raw );
		}
		if ( $f[2] && '' === $val ) {
			$errors[] = $key;
		}
		$data[ $key ] = $val;
	}
	if ( $data['tf_app_email'] && ! is_email( $data['tf_app_email'] ) ) {
		$errors[] = 'tf_app_email';
	}
	if ( empty( $_POST['tf_app_consent'] ) ) {
		$errors[] = 'tf_app_consent';
	}
	// phpcs:enable

	if ( $errors ) {
		wp_safe_redirect( add_query_arg( 'apply_error', 'missing', $back ) . '#apply' );
		exit;
	}

	$types = tapaufest_application_types();
	$id    = wp_insert_post(
		array(
			'post_type'   => 'tf_application',
			'post_status' => 'private',
			'post_title'  => $data['tf_app_business'] . ' · ' . ( isset( $types[ $data['tf_app_type'] ] ) ? $types[ $data['tf_app_type'] ] : '' ),
		)
	);
	if ( ! $id || is_wp_error( $id ) ) {
		wp_safe_redirect( add_query_arg( 'apply_error', 'server', $back ) . '#apply' );
		exit;
	}
	foreach ( $data as $key => $val ) {
		update_post_meta( $id, $key, $val );
	}
	update_post_meta( $id, 'tf_app_status', 'new' );
	update_post_meta( $id, 'tf_app_consent', current_time( 'mysql' ) );
	set_transient( $ip_key, 1, MINUTE_IN_SECONDS );

	$edition = tapaufest_setting( 'edition' );
	$to      = tapaufest_setting( 'apply_email' ) ? tapaufest_setting( 'apply_email' ) : get_option( 'admin_email' );
	wp_mail(
		$to,
		/* translators: 1: business name, 2: edition. */
		sprintf( __( 'New stall application: %1$s (%2$s)', 'tapaufest' ), $data['tf_app_business'], $edition ),
		/* translators: %s: link to the application. */
		sprintf( __( "Review it here:\n%s", 'tapaufest' ), admin_url( 'post.php?post=' . (int) $id . '&action=edit' ) )
	);
	wp_mail(
		$data['tf_app_email'],
		/* translators: %s: edition. */
		sprintf( __( 'We got your stall application for %s', 'tapaufest' ), $edition ),
		/* translators: 1: contact name, 2: business name. */
		sprintf( __( "Hi %1\$s,\n\nThanks for applying to open %2\$s at TAPAUfest. The team reviews every application and replies by email or WhatsApp once stall selection is done.\n\nTerima kasih,\nTAPAUfest", 'tapaufest' ), $data['tf_app_contact'], $data['tf_app_business'] )
	);

	wp_safe_redirect( add_query_arg( 'applied', '1', $back ) . '#apply' );
	exit;
}
add_action( 'admin_post_tapaufest_apply', 'tapaufest_handle_application' );
add_action( 'admin_post_nopriv_tapaufest_apply', 'tapaufest_handle_application' );

/**
 * Review box: the answers, read-only, plus a status the team can change.
 */
function tapaufest_application_meta_box() {
	add_meta_box(
		'tapaufest-application',
		__( 'Application', 'tapaufest' ),
		function ( $post ) {
			wp_nonce_field( 'tapaufest_app_status', 'tf_app_status_nonce' );
			echo '<table class="form-table" role="presentation">';
			foreach ( tapaufest_application_fields() as $key => $f ) {
				$val = (string) get_post_meta( $post->ID, $key, true );
				if ( is_array( $f[3] ) && isset( $f[3][ $val ] ) ) {
					$val = $f[3][ $val ];
				}
				echo '<tr><th scope="row">' . esc_html( $f[0] ) . '</th><td>' . nl2br( esc_html( $val ) ) . '</td></tr>';
			}
			echo '<tr><th scope="row">' . esc_html__( 'Consent recorded', 'tapaufest' ) . '</th><td>' . esc_html( (string) get_post_meta( $post->ID, 'tf_app_consent', true ) ) . '</td></tr>';
			$status = (string) get_post_meta( $post->ID, 'tf_app_status', true );
			echo '<tr><th scope="row"><label for="tf_app_status">' . esc_html__( 'Status', 'tapaufest' ) . '</label></th><td><select id="tf_app_status" name="tf_app_status">';
			foreach ( tapaufest_application_statuses() as $slug => $label ) {
				echo '<option value="' . esc_attr( $slug ) . '"' . selected( $status, $slug, false ) . '>' . esc_html( $label ) . '</option>';
			}
			echo '</select></td></tr></table>';
		},
		'tf_application',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'tapaufest_application_meta_box' );

/**
 * Save the review status.
 *
 * @param int $post_id Post ID.
 */
function tapaufest_save_application_status( $post_id ) {
	if ( ! isset( $_POST['tf_app_status_nonce'] ) || ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['tf_app_status_nonce'] ) ), 'tapaufest_app_status' ) || ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	$status = isset( $_POST['tf_app_status'] ) ? sanitize_key( wp_unslash( $_POST['tf_app_status'] ) ) : 'new';
	if ( array_key_exists( $status, tapaufest_application_statuses() ) ) {
		update_post_meta( $post_id, 'tf_app_status', $status );
	}
}
add_action( 'save_post_tf_application', 'tapaufest_save_application_status' );

/**
 * List columns: type, contact, status.
 *
 * @param array $cols Columns.
 * @return array
 */
function tapaufest_application_columns( $cols ) {
	return array(
		'cb'        => $cols['cb'],
		'title'     => __( 'Stall', 'tapaufest' ),
		'tf_type'   => __( 'Type', 'tapaufest' ),
		'tf_who'    => __( 'Contact', 'tapaufest' ),
		'tf_status' => __( 'Status', 'tapaufest' ),
		'date'      => $cols['date'],
	);
}
add_filter( 'manage_tf_application_posts_columns', 'tapaufest_application_columns' );

/**
 * Column values.
 *
 * @param string $col     Column.
 * @param int    $post_id Post ID.
 */
function tapaufest_application_column( $col, $post_id ) {
	if ( 'tf_type' === $col ) {
		$types = tapaufest_application_types();
		$t     = (string) get_post_meta( $post_id, 'tf_app_type', true );
		echo esc_html( isset( $types[ $t ] ) ? $types[ $t ] : '' );
	} elseif ( 'tf_who' === $col ) {
		echo esc_html( get_post_meta( $post_id, 'tf_app_contact', true ) ) . '<br>' . esc_html( get_post_meta( $post_id, 'tf_app_email', true ) ) . '<br>' . esc_html( get_post_meta( $post_id, 'tf_app_phone', true ) );
	} elseif ( 'tf_status' === $col ) {
		$s = tapaufest_application_statuses();
		$v = (string) get_post_meta( $post_id, 'tf_app_status', true );
		echo esc_html( isset( $s[ $v ] ) ? $s[ $v ] : '' );
	}
}
add_action( 'manage_tf_application_posts_custom_column', 'tapaufest_application_column', 10, 2 );
