<?php
/**
 * Title: Footer
 * Slug: tapaufest-2026/footer
 * Categories: footer
 * Block Types: core/template-part/footer
 * Inserter: no
 * Description: Logo, hashtags, page links, socials and the mobile ticket bar.
 *
 * @package Tapaufest2026
 */

$tf_cols = array(
	__( 'Festival', 'tapaufest-2026' )  => array(
		home_url( '/lineup/' )     => __( 'Lineup', 'tapaufest-2026' ),
		home_url( '/timetable/' )  => __( 'Timetable', 'tapaufest-2026' ),
		home_url( '/tickets/' )    => __( 'Tickets', 'tapaufest-2026' ),
		home_url( '/experience/' ) => __( 'Experience', 'tapaufest-2026' ),
	),
	__( 'Plan', 'tapaufest-2026' )      => array(
		home_url( '/plan/' ) => __( 'Plan your trip', 'tapaufest-2026' ),
		home_url( '/map/' )  => __( 'Map and help', 'tapaufest-2026' ),
		home_url( '/faq/' )  => __( 'FAQ', 'tapaufest-2026' ),
	),
	__( 'Look back', 'tapaufest-2026' ) => array(
		home_url( '/recap/' )   => tapaufest_is_recap() ? __( '2026 recap', 'tapaufest-2026' ) : __( '2025 recap', 'tapaufest-2026' ),
		home_url( '/archive/' ) => __( 'Past editions', 'tapaufest-2026' ),
		'https://tapau.asia/'   => __( 'tapau.asia', 'tapaufest-2026' ),
	),
	__( 'Follow', 'tapaufest-2026' )    => array(
		'https://www.instagram.com/tapaufest/' => 'Instagram',
		'https://www.tiktok.com/@tapaufest'    => 'TikTok',
		'https://x.com/TAPAUfest'              => 'X (Twitter)',
		'https://www.facebook.com/TAPAUfest/'  => 'Facebook',
	),
);
?>
<!-- wp:group {"align":"full","className":"tf-footer-bar","layout":{"type":"constrained","contentSize":"1120px"}} -->
<div class="wp-block-group alignfull tf-footer-bar">
<!-- wp:group {"layout":{"type":"grid","minimumColumnWidth":"9rem"}} -->
<div class="wp-block-group">
<!-- wp:group {"layout":{"type":"default"}} -->
<div class="wp-block-group">
<!-- wp:image {"width":"120px","sizeSlug":"full"} -->
<figure class="wp-block-image size-full is-resized"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/tapaufest-2026-logo.webp' ) ); ?>" alt="TAPAUfest 2026" style="width:120px"/></figure>
<!-- /wp:image -->

<!-- wp:paragraph {"className":"tf-hashtags"} -->
<p class="tf-hashtags">#SerumpunBerpesta<br>#MakeAScene #BikinScene</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<?php foreach ( $tf_cols as $tf_head => $tf_links ) : ?>

<!-- wp:group {"layout":{"type":"default"}} -->
<div class="wp-block-group">
<!-- wp:paragraph {"className":"tf-footer-head"} -->
<p class="tf-footer-head"><?php echo esc_html( $tf_head ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:list {"className":"tf-footer-links"} -->
<ul class="wp-block-list tf-footer-links">
	<?php foreach ( $tf_links as $tf_url => $tf_label ) : ?>
<!-- wp:list-item -->
<li><a href="<?php echo esc_url( $tf_url ); ?>"><?php echo esc_html( $tf_label ); ?></a></li>
<!-- /wp:list-item -->
	<?php endforeach; ?>
</ul>
<!-- /wp:list -->
</div>
<!-- /wp:group -->
<?php endforeach; ?>
</div>
<!-- /wp:group -->

<!-- wp:paragraph {"className":"tf-copyright"} -->
<p class="tf-copyright">© 2026 TAPAUasia Sdn. Bhd. <?php esc_html_e( 'Tickets sold on TAPAUtix.', 'tapaufest-2026' ); ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->

<!-- wp:tapaufest/sticky-cta /-->
