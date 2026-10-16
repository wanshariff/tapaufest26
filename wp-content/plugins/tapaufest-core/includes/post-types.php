<?php
/**
 * Content types: artists, sessions (set times), ticket tiers, FAQs, vendors.
 *
 * @package TapaufestCore
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register post types and the FAQ group taxonomy.
 */
function tapaufest_register_post_types() {
	register_post_type(
		'tf_artist',
		array(
			'labels'        => array(
				'name'          => __( 'Artists', 'tapaufest' ),
				'singular_name' => __( 'Artist', 'tapaufest' ),
				'add_new_item'  => __( 'Add artist', 'tapaufest' ),
				'edit_item'     => __( 'Edit artist', 'tapaufest' ),
				'all_items'     => __( 'Lineup', 'tapaufest' ),
				'menu_name'     => __( 'TAPAUfest', 'tapaufest' ),
			),
			'public'        => true,
			'has_archive'   => 'lineup',
			'rewrite'       => array(
				'slug'       => 'artist',
				'with_front' => false,
			),
			'menu_icon'     => 'dashicons-tickets-alt',
			'menu_position' => 21,
			'show_in_rest'  => true,
			'supports'      => array( 'title', 'editor', 'excerpt', 'thumbnail', 'page-attributes', 'custom-fields' ),
		)
	);

	$child = array(
		'public'             => false,
		'publicly_queryable' => false,
		'show_ui'            => true,
		'show_in_menu'       => 'edit.php?post_type=tf_artist',
		'show_in_rest'       => true,
		'supports'           => array( 'title', 'page-attributes', 'custom-fields' ),
	);

	register_post_type(
		'tf_session',
		array_merge(
			$child,
			array(
				'labels'             => array(
					'name'          => __( 'Shows and set times', 'tapaufest' ),
					'singular_name' => __( 'Show', 'tapaufest' ),
					'add_new_item'  => __( 'Add show or set time', 'tapaufest' ),
					'edit_item'     => __( 'Edit show', 'tapaufest' ),
					'menu_name'     => __( 'Shows and set times', 'tapaufest' ),
				),
				// Public so every show, film and workshop gets its own page.
				'public'             => true,
				'publicly_queryable' => true,
				'has_archive'        => false,
				'rewrite'            => array(
					'slug'       => 'show',
					'with_front' => false,
				),
				'supports'           => array( 'title', 'editor', 'excerpt', 'thumbnail', 'page-attributes', 'custom-fields' ),
			)
		)
	);

	register_post_type(
		'tf_tier',
		array_merge(
			$child,
			array(
				'labels' => array(
					'name'          => __( 'Ticket tiers', 'tapaufest' ),
					'singular_name' => __( 'Ticket tier', 'tapaufest' ),
					'add_new_item'  => __( 'Add ticket tier', 'tapaufest' ),
					'edit_item'     => __( 'Edit ticket tier', 'tapaufest' ),
				),
			)
		)
	);

	register_post_type(
		'tf_faq',
		array_merge(
			$child,
			array(
				'labels'   => array(
					'name'          => __( 'FAQs', 'tapaufest' ),
					'singular_name' => __( 'FAQ', 'tapaufest' ),
					'add_new_item'  => __( 'Add question', 'tapaufest' ),
					'edit_item'     => __( 'Edit question', 'tapaufest' ),
				),
				'supports' => array( 'title', 'editor', 'page-attributes' ),
			)
		)
	);

	register_post_type(
		'tf_vendor',
		array_merge(
			$child,
			array(
				'labels'   => array(
					'name'          => __( 'Vendors', 'tapaufest' ),
					'singular_name' => __( 'Vendor', 'tapaufest' ),
					'add_new_item'  => __( 'Add vendor', 'tapaufest' ),
					'edit_item'     => __( 'Edit vendor', 'tapaufest' ),
				),
				// Public so each stall gets its own page.
				'public'             => true,
				'publicly_queryable' => true,
				'has_archive'        => false,
				'rewrite'            => array(
					'slug'       => 'vendor',
					'with_front' => false,
				),
				'supports'           => array( 'title', 'editor', 'excerpt', 'thumbnail', 'page-attributes', 'custom-fields' ),
			)
		)
	);

	register_post_type(
		'tf_partner',
		array_merge(
			$child,
			array(
				'labels'   => array(
					'name'          => __( 'Partners', 'tapaufest' ),
					'singular_name' => __( 'Partner', 'tapaufest' ),
					'add_new_item'  => __( 'Add partner', 'tapaufest' ),
					'edit_item'     => __( 'Edit partner', 'tapaufest' ),
				),
				'supports' => array( 'title', 'thumbnail', 'page-attributes', 'custom-fields' ),
			)
		)
	);

	register_taxonomy(
		'tf_faq_group',
		'tf_faq',
		array(
			'labels'            => array(
				'name'          => __( 'FAQ groups', 'tapaufest' ),
				'singular_name' => __( 'FAQ group', 'tapaufest' ),
			),
			'public'            => false,
			'show_ui'           => true,
			'show_in_rest'      => true,
			'show_admin_column' => true,
			'hierarchical'      => true,
		)
	);
}
add_action( 'init', 'tapaufest_register_post_types' );

/**
 * Show set times in day, then start order on the lineup and timetable admin lists.
 *
 * @param WP_Query $query Query.
 */
function tapaufest_admin_order( $query ) {
	if ( ! is_admin() || ! $query->is_main_query() ) {
		return;
	}
	$type = $query->get( 'post_type' );
	if ( in_array( $type, array( 'tf_artist', 'tf_tier', 'tf_faq', 'tf_vendor', 'tf_partner' ), true ) && ! $query->get( 'orderby' ) ) {
		$query->set( 'orderby', array( 'menu_order' => 'ASC', 'title' => 'ASC' ) );
	}
}
add_action( 'pre_get_posts', 'tapaufest_admin_order' );

/**
 * Lineup archive shows every artist, headliners first.
 *
 * @param WP_Query $query Query.
 */
function tapaufest_lineup_query( $query ) {
	if ( is_admin() || ! $query->is_main_query() || ! $query->is_post_type_archive( 'tf_artist' ) ) {
		return;
	}
	$query->set( 'posts_per_page', -1 );
	$query->set( 'orderby', array( 'menu_order' => 'ASC', 'title' => 'ASC' ) );
}
add_action( 'pre_get_posts', 'tapaufest_lineup_query' );

/**
 * A show without its own photo borrows the linked artist's photo.
 *
 * @param int|false        $thumbnail_id Thumbnail ID.
 * @param int|WP_Post|null $post         Post.
 * @return int|false
 */
function tapaufest_session_thumbnail( $thumbnail_id, $post ) {
	$post = get_post( $post );
	if ( $thumbnail_id || ! $post || 'tf_session' !== $post->post_type ) {
		return $thumbnail_id;
	}
	$artist = (int) get_post_meta( $post->ID, 'tf_artist', true );
	return $artist ? (int) get_post_thumbnail_id( $artist ) : $thumbnail_id;
}
add_filter( 'post_thumbnail_id', 'tapaufest_session_thumbnail', 10, 2 );
