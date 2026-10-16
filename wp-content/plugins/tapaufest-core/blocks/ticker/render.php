<?php
/**
 * Lineup ticker: artist names on a loop. The second copy of the list is
 * decorative (aria-hidden) and only there to make the loop seamless.
 *
 * @package TapaufestCore
 */

defined( 'ABSPATH' ) || exit;

$tf_artists = array_map( 'tapaufest_artist_data', tapaufest_get_artists() );
usort(
	$tf_artists,
	function ( $a, $b ) {
		return (int) $b['headliner'] - (int) $a['headliner'];
	}
);
$tf_items = wp_list_pluck( $tf_artists, 'name' );
if ( ! $tf_items ) {
	$tf_items = array( 'PENTAS', 'LAYAR', 'BENGKEL', 'PASAR', 'WARUNG', 'KEMBARA', 'KHEMAH', 'UNGGUN' );
}
$tf_label = isset( $attributes['label'] ) ? $attributes['label'] : __( 'Lineup', 'tapaufest' );
?>
<div <?php echo get_block_wrapper_attributes( array( 'class' => 'tf-ticker alignfull' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<a class="tf-ticker__track" href="<?php echo esc_url( get_post_type_archive_link( 'tf_artist' ) ); ?>" aria-label="<?php echo esc_attr( $tf_label . ': ' . implode( ', ', $tf_items ) ); ?>">
		<?php for ( $tf_i = 0; $tf_i < 2; $tf_i++ ) : ?>
			<span class="tf-ticker__set" aria-hidden="true">
				<?php foreach ( $tf_items as $tf_name ) : ?>
					<span class="tf-ticker__item"><?php echo esc_html( $tf_name ); ?></span>
				<?php endforeach; ?>
			</span>
		<?php endfor; ?>
	</a>
</div>
