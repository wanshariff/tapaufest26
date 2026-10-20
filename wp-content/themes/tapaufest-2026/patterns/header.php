<?php
/**
 * Title: Header
 * Slug: tapaufest-2026/header
 * Categories: header
 * Block Types: core/template-part/header
 * Inserter: no
 * Description: Alert bar, logo, main menu and the state-aware header button.
 *
 * @package Tapaufest2026
 */

$tf_links = array(
	'/lineup/'      => __( 'Lineup', 'tapaufest-2026' ),
	'/timetable/'   => __( 'Set times', 'tapaufest-2026' ),
	'/jadual-aku/'  => __( 'Jadual Aku', 'tapaufest-2026' ),
	'/plan/'        => __( 'Getting there', 'tapaufest-2026' ),
	'/map/'         => __( 'Map', 'tapaufest-2026' ),
	'/makan-pasar/' => __( 'Makan & Pasar', 'tapaufest-2026' ),
	'/faq/'         => __( 'FAQ', 'tapaufest-2026' ),
);
// Phone menu only: the rest of the site.
$tf_more = array(
	'/experience/' => __( 'What’s on', 'tapaufest-2026' ),
	'/tickets/'    => __( 'Tickets', 'tapaufest-2026' ),
	'/help/'       => __( 'Help on site', 'tapaufest-2026' ),
	'/sell/'       => __( 'Sell with us', 'tapaufest-2026' ),
	'/partners/'   => __( 'Partners', 'tapaufest-2026' ),
	'/archive/'    => __( 'Past editions', 'tapaufest-2026' ),
);
?>
<!-- wp:group {"align":"full","className":"tf-header-bar","layout":{"type":"constrained","contentSize":"1120px"}} -->
<div class="wp-block-group alignfull tf-header-bar">
<!-- wp:group {"className":"tf-header-row","layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between"}} -->
<div class="wp-block-group tf-header-row">
<!-- wp:image {"width":"68px","sizeSlug":"full","linkDestination":"custom","className":"tf-logo"} -->
<figure class="wp-block-image size-full is-resized tf-logo"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/tapaufest-2026-logo.webp' ) ); ?>" alt="<?php esc_attr_e( 'TAPAUfest 2026 home', 'tapaufest-2026' ); ?>" style="width:68px"/></a></figure>
<!-- /wp:image -->

<!-- wp:navigation {"className":"tf-nav","overlayMenu":"mobile","layout":{"type":"flex","justifyContent":"center"}} -->
<?php foreach ( $tf_links as $tf_path => $tf_label ) : ?>
<!-- wp:navigation-link {"label":"<?php echo esc_attr( $tf_label ); ?>","url":"<?php echo esc_url( home_url( $tf_path ) ); ?>","kind":"custom","isTopLevelLink":true} /-->
<?php endforeach; ?>
<?php foreach ( $tf_more as $tf_path => $tf_label ) : ?>
<!-- wp:navigation-link {"label":"<?php echo esc_attr( $tf_label ); ?>","url":"<?php echo esc_url( home_url( $tf_path ) ); ?>","kind":"custom","isTopLevelLink":true,"className":"tf-nav__more"} /-->
<?php endforeach; ?>
<!-- /wp:navigation -->

<!-- wp:tapaufest/header-cta /-->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->

<!-- wp:tapaufest/alert-bar /-->
