<?php
/**
 * Festival hero. Content follows the site state.
 *
 * @package TapaufestCore
 */

defined( 'ABSPATH' ) || exit;

$tf_state = tapaufest_state();
$tf_logo  = tapaufest_logo_url();
$tf_days  = array_keys( tapaufest_days() );
$tf_tz    = tapaufest_timezone();
$tf_when  = '';
if ( $tf_days ) {
	$tf_first = strtotime( $tf_days[0] . ' 12:00:00' );
	$tf_last  = strtotime( end( $tf_days ) . ' 12:00:00' );
	$tf_when  = ( count( $tf_days ) > 1 )
		? wp_date( 'j', $tf_first, $tf_tz ) . ' & ' . wp_date( 'j M Y', $tf_last, $tf_tz )
		: wp_date( 'j M Y', $tf_first, $tf_tz );
}
$tf_join = tapaufest_setting( 'join_url' );
?>
<section <?php echo get_block_wrapper_attributes( array( 'class' => 'tf-hero tf-hero--' . $tf_state ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<h1 class="tf-hero__title">
		<?php if ( $tf_logo ) : ?>
			<img class="tf-hero__logo" src="<?php echo esc_url( $tf_logo ); ?>" alt="<?php echo esc_attr( tapaufest_setting( 'edition' ) ); ?>" width="900" height="540" fetchpriority="high">
		<?php else : ?>
			<?php echo esc_html( tapaufest_setting( 'edition' ) ); ?>
		<?php endif; ?>
	</h1>
	<p class="tf-hero__when"><?php echo esc_html( $tf_when ); ?></p>
	<p class="tf-hero__where"><?php echo esc_html( tapaufest_setting( 'venue' ) ); ?></p>

	<?php if ( 'live' === $tf_state ) : ?>
		<?php echo tapaufest_now_next_html(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped inside. ?>
		<div class="tf-ctas">
			<a class="tf-btn tf-btn--cili" href="<?php echo esc_url( home_url( '/timetable/' ) ); ?>"><?php esc_html_e( 'Full timetable', 'tapaufest' ); ?></a>
			<a class="tf-btn" href="<?php echo esc_url( home_url( '/map/' ) ); ?>"><?php esc_html_e( 'Map and help', 'tapaufest' ); ?></a>
		</div>

	<?php elseif ( 'recap' === $tf_state ) : ?>
		<p class="tf-hero__note"><?php esc_html_e( 'Terima kasih, geng. Photos, highlights and set recordings are up.', 'tapaufest' ); ?></p>
		<div class="tf-ctas">
			<a class="tf-btn tf-btn--cili" href="<?php echo esc_url( home_url( '/recap/' ) ); ?>"><?php esc_html_e( 'See the recap', 'tapaufest' ); ?></a>
			<?php if ( tapaufest_setting( 'survey_url' ) ) : ?>
				<a class="tf-btn" href="<?php echo esc_url( tapaufest_setting( 'survey_url' ) ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Take the 2-minute survey', 'tapaufest' ); ?></a>
			<?php endif; ?>
		</div>

	<?php else : ?>
		<div class="tf-ctas">
			<a class="tf-btn tf-btn--cili" href="<?php echo esc_url( home_url( '/tickets/' ) ); ?>"><?php esc_html_e( 'Get tickets', 'tapaufest' ); ?></a>
			<?php if ( $tf_join ) : ?>
				<a class="tf-btn" href="<?php echo esc_url( $tf_join ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Join Geng TAPAU. It’s free.', 'tapaufest' ); ?></a>
			<?php endif; ?>
		</div>
		<p class="tf-hero__note">
			<?php
			echo esc_html(
				'early' === $tf_state
					? __( 'Waitlist first: use the code in your email for the early-bird price. Public sale opens after.', 'tapaufest' )
					: __( 'Tickets are on sale now on TAPAUtix.', 'tapaufest' )
			);
			?>
		</p>
		<?php $tf_left = tapaufest_days_to_go(); ?>
		<?php if ( $tf_left > 0 ) : ?>
			<p class="tf-countdown"><b><?php echo esc_html( number_format_i18n( $tf_left ) ); ?></b> <span><?php echo esc_html( _n( 'day to go', 'days to go', $tf_left, 'tapaufest' ) ); ?></span></p>
		<?php endif; ?>
	<?php endif; ?>

	<p class="tf-hero__tags">#SerumpunBerpesta <span>#MakeAScene #BikinScene</span></p>
</section>
