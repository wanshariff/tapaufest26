<?php
/**
 * Custom fields for each content type, with simple meta boxes.
 *
 * Fields are declared once in tapaufest_fields() and drive registration,
 * the edit screen and saving.
 *
 * @package TapaufestCore
 */

defined( 'ABSPATH' ) || exit;

/**
 * Country codes used on artist cards.
 *
 * @return array
 */
function tapaufest_countries() {
	return array(
		'MY' => __( 'Malaysia', 'tapaufest' ),
		'ID' => __( 'Indonesia', 'tapaufest' ),
		'SG' => __( 'Singapore', 'tapaufest' ),
		'TH' => __( 'Thailand', 'tapaufest' ),
		'PH' => __( 'Philippines', 'tapaufest' ),
		'VN' => __( 'Vietnam', 'tapaufest' ),
		'BN' => __( 'Brunei', 'tapaufest' ),
		'KH' => __( 'Cambodia', 'tapaufest' ),
		'LA' => __( 'Laos', 'tapaufest' ),
		'MM' => __( 'Myanmar', 'tapaufest' ),
		'TL' => __( 'Timor-Leste', 'tapaufest' ),
	);
}

/**
 * Session kinds.
 *
 * @return array
 */
function tapaufest_session_kinds() {
	return array(
		'act'      => __( 'Live set (linked to an artist)', 'tapaufest' ),
		'film'     => __( 'Screening', 'tapaufest' ),
		'workshop' => __( 'Workshop', 'tapaufest' ),
		'outdoor'  => __( 'Outdoor activity', 'tapaufest' ),
		'prayer'   => __( 'Prayer break', 'tapaufest' ),
		'other'    => __( 'Other', 'tapaufest' ),
	);
}

/**
 * Field definitions per post type.
 *
 * @return array
 */
function tapaufest_fields() {
	return array(
		'tf_artist'  => array(
			'tf_country'   => array(
				'label'   => __( 'Country', 'tapaufest' ),
				'type'    => 'select',
				'options' => 'tapaufest_countries',
			),
			'tf_genre'     => array(
				'label' => __( 'Genre', 'tapaufest' ),
				'type'  => 'text',
			),
			'tf_headliner' => array(
				'label' => __( 'Headliner (bigger card)', 'tapaufest' ),
				'type'  => 'checkbox',
			),
			'tf_listen'    => array(
				'label' => __( 'Listen link (Bandcamp or YouTube)', 'tapaufest' ),
				'type'  => 'url',
				'help'  => __( 'Plays in a sheet on the lineup without leaving the page.', 'tapaufest' ),
			),
			'tf_profile'   => array(
				'label' => __( 'Profile on tapau.asia', 'tapaufest' ),
				'type'  => 'url',
			),
		),
		'tf_session' => array(
			'tf_day'    => array(
				'label'   => __( 'Day', 'tapaufest' ),
				'type'    => 'select',
				'options' => 'tapaufest_days',
			),
			'tf_start'  => array(
				'label' => __( 'Start (HH:MM, 24-hour)', 'tapaufest' ),
				'type'  => 'text',
				'help'  => __( 'After midnight, keep counting: 00:30 the next morning is 24:30.', 'tapaufest' ),
			),
			'tf_length' => array(
				'label' => __( 'Length (minutes)', 'tapaufest' ),
				'type'  => 'number',
			),
			'tf_stage'  => array(
				'label'   => __( 'Stage', 'tapaufest' ),
				'type'    => 'select',
				'options' => 'tapaufest_stage_options',
			),
			'tf_kind'   => array(
				'label'   => __( 'Type', 'tapaufest' ),
				'type'    => 'select',
				'options' => 'tapaufest_session_kinds',
			),
			'tf_artist' => array(
				'label'   => __( 'Artist (for live sets)', 'tapaufest' ),
				'type'    => 'select',
				'options' => 'tapaufest_artist_options',
				'help'    => __( 'The title is ignored for live sets; the artist name shows instead.', 'tapaufest' ),
			),
		),
		'tf_tier'    => array(
			'tf_who'      => array(
				'label' => __( 'Who it’s for', 'tapaufest' ),
				'type'  => 'text',
				'help'  => __( 'For example: 1 person · 2 days', 'tapaufest' ),
			),
			'tf_people'   => array(
				'label' => __( 'People per pass', 'tapaufest' ),
				'type'  => 'number',
			),
			'tf_days'     => array(
				'label' => __( 'Days per pass', 'tapaufest' ),
				'type'  => 'number',
			),
			'tf_early'    => array(
				'label' => __( 'Early-bird price (RM)', 'tapaufest' ),
				'type'  => 'number',
			),
			'tf_regular'  => array(
				'label' => __( 'Regular price (RM)', 'tapaufest' ),
				'type'  => 'number',
			),
			'tf_includes' => array(
				'label' => __( 'What’s included (one per line)', 'tapaufest' ),
				'type'  => 'textarea',
			),
			'tf_campsite' => array(
				'label' => __( 'Includes the festival campsite', 'tapaufest' ),
				'type'  => 'checkbox',
			),
			'tf_url'      => array(
				'label' => __( 'TAPAUtix product link', 'tapaufest' ),
				'type'  => 'url',
			),
			'tf_soldout'  => array(
				'label' => __( 'Sold out', 'tapaufest' ),
				'type'  => 'checkbox',
			),
		),
		'tf_faq'     => array(
			'tf_featured' => array(
				'label' => __( 'Show on the home page (mini FAQ)', 'tapaufest' ),
				'type'  => 'checkbox',
			),
		),
		'tf_vendor'  => array(
			'tf_vendor_type' => array(
				'label'   => __( 'Zone', 'tapaufest' ),
				'type'    => 'select',
				'options' => 'tapaufest_vendor_types',
			),
			'tf_note'        => array(
				'label' => __( 'What they sell', 'tapaufest' ),
				'type'  => 'text',
			),
		),
	);
}

/**
 * Vendor zones.
 *
 * @return array
 */
function tapaufest_vendor_types() {
	return array(
		'warung' => 'WARUNG',
		'pasar'  => 'PASAR',
	);
}

/**
 * Stage select options.
 *
 * @return array
 */
function tapaufest_stage_options() {
	$out = array();
	foreach ( tapaufest_stages() as $slug => $desc ) {
		$out[ $slug ] = $desc ? $slug . ' · ' . $desc : $slug;
	}
	return $out;
}

/**
 * Artist select options.
 *
 * @return array
 */
function tapaufest_artist_options() {
	$out   = array( '' => __( 'None', 'tapaufest' ) );
	$posts = get_posts(
		array(
			'post_type'      => 'tf_artist',
			'posts_per_page' => -1,
			'post_status'    => array( 'publish', 'draft', 'pending', 'future' ),
			'orderby'        => 'title',
			'order'          => 'ASC',
		)
	);
	foreach ( $posts as $post ) {
		$out[ $post->ID ] = $post->post_title;
	}
	return $out;
}

/**
 * Register meta so it shows in REST and stays typed.
 */
function tapaufest_register_meta() {
	foreach ( tapaufest_fields() as $post_type => $fields ) {
		foreach ( $fields as $key => $field ) {
			$type = 'string';
			if ( 'checkbox' === $field['type'] ) {
				$type = 'boolean';
			} elseif ( 'number' === $field['type'] || 'tf_artist' === $key ) {
				$type = 'integer';
			}
			register_post_meta(
				$post_type,
				$key,
				array(
					'type'          => $type,
					'single'        => true,
					'show_in_rest'  => true,
					'auth_callback' => function () {
						return current_user_can( 'edit_posts' );
					},
				)
			);
		}
	}
}
add_action( 'init', 'tapaufest_register_meta' );

/**
 * Add one details box per content type.
 */
function tapaufest_add_meta_boxes() {
	foreach ( array_keys( tapaufest_fields() ) as $post_type ) {
		add_meta_box( 'tapaufest-details', __( 'Festival details', 'tapaufest' ), 'tapaufest_render_meta_box', $post_type, 'normal', 'high' );
	}
}
add_action( 'add_meta_boxes', 'tapaufest_add_meta_boxes' );

/**
 * Render the details box.
 *
 * @param WP_Post $post Post.
 */
function tapaufest_render_meta_box( $post ) {
	$all    = tapaufest_fields();
	$fields = isset( $all[ $post->post_type ] ) ? $all[ $post->post_type ] : array();
	wp_nonce_field( 'tapaufest_save_meta', 'tapaufest_meta_nonce' );
	echo '<table class="form-table" role="presentation"><tbody>';
	foreach ( $fields as $key => $field ) {
		$value = get_post_meta( $post->ID, $key, true );
		$id    = 'tf-field-' . $key;
		echo '<tr><th scope="row"><label for="' . esc_attr( $id ) . '">' . esc_html( $field['label'] ) . '</label></th><td>';
		switch ( $field['type'] ) {
			case 'select':
				$options = call_user_func( $field['options'] );
				echo '<select id="' . esc_attr( $id ) . '" name="' . esc_attr( $key ) . '">';
				if ( ! isset( $options[''] ) ) {
					echo '<option value="">' . esc_html__( 'Choose', 'tapaufest' ) . '</option>';
				}
				foreach ( $options as $opt_value => $opt_label ) {
					echo '<option value="' . esc_attr( $opt_value ) . '"' . selected( (string) $value, (string) $opt_value, false ) . '>' . esc_html( $opt_label ) . '</option>';
				}
				echo '</select>';
				break;
			case 'checkbox':
				echo '<input type="checkbox" id="' . esc_attr( $id ) . '" name="' . esc_attr( $key ) . '" value="1"' . checked( (bool) $value, true, false ) . '>';
				break;
			case 'textarea':
				echo '<textarea id="' . esc_attr( $id ) . '" name="' . esc_attr( $key ) . '" rows="4" class="large-text">' . esc_textarea( $value ) . '</textarea>';
				break;
			case 'number':
				echo '<input type="number" min="0" step="1" id="' . esc_attr( $id ) . '" name="' . esc_attr( $key ) . '" value="' . esc_attr( $value ) . '" class="small-text">';
				break;
			case 'url':
				echo '<input type="url" id="' . esc_attr( $id ) . '" name="' . esc_attr( $key ) . '" value="' . esc_attr( $value ) . '" class="large-text" placeholder="https://">';
				break;
			default:
				echo '<input type="text" id="' . esc_attr( $id ) . '" name="' . esc_attr( $key ) . '" value="' . esc_attr( $value ) . '" class="regular-text">';
		}
		if ( ! empty( $field['help'] ) ) {
			echo '<p class="description">' . esc_html( $field['help'] ) . '</p>';
		}
		echo '</td></tr>';
	}
	echo '</tbody></table>';
}

/**
 * Save the details box.
 *
 * @param int $post_id Post ID.
 */
function tapaufest_save_meta( $post_id ) {
	if ( ! isset( $_POST['tapaufest_meta_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['tapaufest_meta_nonce'] ) ), 'tapaufest_save_meta' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	$all       = tapaufest_fields();
	$post_type = get_post_type( $post_id );
	if ( ! isset( $all[ $post_type ] ) ) {
		return;
	}
	foreach ( $all[ $post_type ] as $key => $field ) {
		$raw = isset( $_POST[ $key ] ) ? wp_unslash( $_POST[ $key ] ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		update_post_meta( $post_id, $key, tapaufest_sanitize_field( $key, $field, $raw ) );
	}
}
add_action( 'save_post', 'tapaufest_save_meta' );

/**
 * Sanitize one field value.
 *
 * @param string $key   Meta key.
 * @param array  $field Field definition.
 * @param mixed  $raw   Raw value.
 * @return mixed
 */
function tapaufest_sanitize_field( $key, $field, $raw ) {
	switch ( $field['type'] ) {
		case 'checkbox':
			return ! empty( $raw );
		case 'number':
			return absint( $raw );
		case 'url':
			return esc_url_raw( $raw );
		case 'textarea':
			return sanitize_textarea_field( $raw );
		case 'select':
			$raw = sanitize_text_field( $raw );
			if ( 'tf_artist' === $key ) {
				return absint( $raw );
			}
			$options = call_user_func( $field['options'] );
			return array_key_exists( $raw, $options ) ? $raw : '';
		default:
			$raw = sanitize_text_field( $raw );
			if ( 'tf_start' === $key ) {
				return preg_match( '/^([01]?\d|2\d):[0-5]\d$/', $raw ) ? $raw : '';
			}
			return $raw;
	}
}

/**
 * Set time list columns.
 *
 * @param array $columns Columns.
 * @return array
 */
function tapaufest_session_columns( $columns ) {
	return array(
		'cb'       => $columns['cb'],
		'title'    => __( 'Set', 'tapaufest' ),
		'tf_day'   => __( 'Day', 'tapaufest' ),
		'tf_start' => __( 'Start', 'tapaufest' ),
		'tf_stage' => __( 'Stage', 'tapaufest' ),
	);
}
add_filter( 'manage_tf_session_posts_columns', 'tapaufest_session_columns' );

/**
 * Fill set time list columns.
 *
 * @param string $column  Column key.
 * @param int    $post_id Post ID.
 */
function tapaufest_session_column_values( $column, $post_id ) {
	if ( 'tf_day' === $column ) {
		$days = tapaufest_days();
		$day  = get_post_meta( $post_id, 'tf_day', true );
		echo esc_html( isset( $days[ $day ] ) ? $days[ $day ] : $day );
	} elseif ( 'tf_start' === $column ) {
		echo esc_html( tapaufest_format_time( get_post_meta( $post_id, 'tf_start', true ) ) );
	} elseif ( 'tf_stage' === $column ) {
		echo esc_html( get_post_meta( $post_id, 'tf_stage', true ) );
	}
}
add_action( 'manage_tf_session_posts_custom_column', 'tapaufest_session_column_values', 10, 2 );

/**
 * Show the artist name as the set title in the admin list.
 *
 * @param string $title   Title.
 * @param int    $post_id Post ID.
 * @return string
 */
function tapaufest_session_admin_title( $title, $post_id = 0 ) {
	if ( is_admin() && $post_id && 'tf_session' === get_post_type( $post_id ) ) {
		$artist = (int) get_post_meta( $post_id, 'tf_artist', true );
		if ( $artist && 'act' === get_post_meta( $post_id, 'tf_kind', true ) ) {
			return get_the_title( $artist );
		}
	}
	return $title;
}
add_filter( 'the_title', 'tapaufest_session_admin_title', 10, 2 );
