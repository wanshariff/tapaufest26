<?php
/**
 * Lineup grid with filters, poster view and listen previews.
 *
 * @package TapaufestCore
 */

defined( 'ABSPATH' ) || exit;

$tf_limit   = isset( $attributes['limit'] ) ? (int) $attributes['limit'] : 0;
$tf_filters = ! empty( $attributes['filters'] ) && 0 === $tf_limit;
$tf_artists = array_map( 'tapaufest_artist_data', tapaufest_get_artists( $tf_limit ) );
$tf_days    = tapaufest_days();

if ( ! $tf_artists ) : ?>
	<div <?php echo get_block_wrapper_attributes( array( 'class' => 'tf-lineup' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<p class="tf-empty"><?php esc_html_e( 'The lineup drops soon. Join the waitlist to hear first.', 'tapaufest' ); ?></p>
	</div>
	<?php
	return;
endif;

$tf_countries = array();
foreach ( $tf_artists as $tf_a ) {
	if ( $tf_a['country'] ) {
		$tf_countries[ $tf_a['country'] ] = $tf_a['country_name'];
	}
}
?>
<div <?php echo get_block_wrapper_attributes( array( 'class' => 'tf-lineup' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> data-tf-lineup>
	<?php if ( $tf_filters ) : ?>
		<div class="tf-tools">
			<div class="tf-chips" role="group" aria-label="<?php esc_attr_e( 'Country', 'tapaufest' ); ?>">
				<button type="button" class="tf-chip" data-tf-country="" aria-pressed="true"><?php esc_html_e( 'All countries', 'tapaufest' ); ?></button>
				<?php foreach ( $tf_countries as $tf_code => $tf_name ) : ?>
					<button type="button" class="tf-chip" data-tf-country="<?php echo esc_attr( $tf_code ); ?>" aria-pressed="false"><?php echo esc_html( $tf_name ); ?></button>
				<?php endforeach; ?>
			</div>
			<div class="tf-toggle" role="group" aria-label="<?php esc_attr_e( 'Lineup view', 'tapaufest' ); ?>">
				<button type="button" data-tf-view="grid" aria-pressed="true"><?php esc_html_e( 'Grid', 'tapaufest' ); ?></button>
				<button type="button" data-tf-view="poster" aria-pressed="false"><?php esc_html_e( 'Poster', 'tapaufest' ); ?></button>
			</div>
		</div>
		<?php if ( count( $tf_days ) > 1 ) : ?>
			<div class="tf-chips" role="group" aria-label="<?php esc_attr_e( 'Day', 'tapaufest' ); ?>">
				<button type="button" class="tf-chip" data-tf-day="" aria-pressed="true"><?php esc_html_e( 'Both days', 'tapaufest' ); ?></button>
				<?php foreach ( $tf_days as $tf_date => $tf_label ) : ?>
					<button type="button" class="tf-chip" data-tf-day="<?php echo esc_attr( $tf_date ); ?>" aria-pressed="false"><?php echo esc_html( $tf_label ); ?></button>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	<?php endif; ?>

	<div class="tf-grid" data-tf-grid>
		<?php
		foreach ( $tf_artists as $tf_a ) {
			echo tapaufest_artist_card( $tf_a, true ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped inside.
		}
		?>
	</div>
	<p class="tf-empty" data-tf-noresults hidden>
		<?php esc_html_e( 'No acts match these filters.', 'tapaufest' ); ?>
		<button type="button" class="tf-btn tf-btn--sm" data-tf-clear><?php esc_html_e( 'Clear filters', 'tapaufest' ); ?></button>
	</p>

	<?php if ( $tf_filters ) : ?>
		<div class="tf-poster" data-tf-poster hidden>
			<p class="tf-poster__eyebrow"><?php echo esc_html( implode( ' · ', array_values( $tf_days ) ) . ' · ' . tapaufest_setting( 'venue' ) ); ?></p>
			<p class="tf-poster__top">
				<?php echo esc_html( implode( ' · ', wp_list_pluck( wp_list_filter( $tf_artists, array( 'headliner' => true ) ), 'name' ) ) ); ?>
			</p>
			<p class="tf-poster__rest">
				<?php echo esc_html( implode( ' · ', wp_list_pluck( wp_list_filter( $tf_artists, array( 'headliner' => false ) ), 'name' ) ) ); ?>
			</p>
		</div>
	<?php endif; ?>

	<?php if ( ! empty( $attributes['moreLink'] ) ) : ?>
		<p class="tf-more"><a href="<?php echo esc_url( get_post_type_archive_link( 'tf_artist' ) ); ?>"><?php esc_html_e( 'Full lineup →', 'tapaufest' ); ?></a></p>
	<?php endif; ?>

	<?php
	foreach ( $tf_artists as $tf_a ) {
		echo tapaufest_listen_template( $tf_a ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped inside.
	}
	?>
</div>
