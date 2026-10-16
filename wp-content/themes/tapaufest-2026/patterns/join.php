<?php
/**
 * Title: Join Geng TAPAU band
 * Slug: tapaufest-2026/join
 * Categories: tapaufest, call-to-action
 * Description: Red band pointing fest visitors to a free Geng TAPAU account on tapau.asia.
 *
 * @package Tapaufest2026
 */

$tf_join = tapaufest_theme_setting( 'join_url', 'https://tapau.asia/' );
?>
<!-- wp:group {"align":"full","className":"tf-join","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60"}}},"layout":{"type":"constrained","contentSize":"1120px"}} -->
<div class="wp-block-group alignfull tf-join" style="padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60)">
<!-- wp:columns {"verticalAlignment":"center"} -->
<div class="wp-block-columns are-vertically-aligned-center">
<!-- wp:column {"verticalAlignment":"center","width":"62%"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:62%">
<!-- wp:heading -->
<h2 class="wp-block-heading"><?php esc_html_e( 'Join Geng TAPAU', 'tapaufest-2026' ); ?></h2>
<!-- /wp:heading -->

<!-- wp:list -->
<ul class="wp-block-list">
<!-- wp:list-item -->
<li><?php esc_html_e( 'Set times land in your account first', 'tapaufest-2026' ); ?></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><?php esc_html_e( 'Watch TAPAUtv and back the artists you love', 'tapaufest-2026' ); ?></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><?php esc_html_e( 'Review the sets you caught, after the fest', 'tapaufest-2026' ); ?></li>
<!-- /wp:list-item -->
</ul>
<!-- /wp:list -->
</div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center"} -->
<div class="wp-block-column is-vertically-aligned-center">
<!-- wp:buttons -->
<div class="wp-block-buttons">
<!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( $tf_join ); ?>"><?php esc_html_e( 'Join free on tapau.asia ↗', 'tapaufest-2026' ); ?></a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->

<!-- wp:paragraph {"fontSize":"small"} -->
<p class="has-small-font-size"><?php esc_html_e( 'Your Geng TAPAU account lives on tapau.asia.', 'tapaufest-2026' ); ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->
</div>
<!-- /wp:group -->
