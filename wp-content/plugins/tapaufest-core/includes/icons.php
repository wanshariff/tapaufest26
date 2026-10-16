<?php
/**
 * TAPAUfest icon set.
 *
 * One SVG sprite (assets/icons/sprite.svg, built by tools/build-icons.py) is
 * printed once at the end of each page. Icons reference it with <use>, inherit
 * the text colour, and scale with the font size, so they stay tiny (about
 * 6 KB gzipped for the whole set) and need no extra requests.
 *
 * @package TapaufestCore
 */

defined( 'ABSPATH' ) || exit;

/**
 * Icon names available in the sprite.
 *
 * @return string[]
 */
function tapaufest_icon_names() {
	static $names = null;
	if ( null === $names ) {
		$file  = TAPAUFEST_DIR . 'assets/icons/icons.json';
		$names = file_exists( $file ) ? (array) json_decode( (string) file_get_contents( $file ), true ) : array(); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- local file.
	}
	return $names;
}

/**
 * Icon markup.
 *
 * @param string $name  Icon name, e.g. 'tent'.
 * @param array  $args  label (accessible name; omit for decorative), class.
 * @return string
 */
function tapaufest_icon( $name, $args = array() ) {
	$name  = sanitize_key( $name );
	$label = isset( $args['label'] ) ? (string) $args['label'] : '';
	$class = 'tf-icon tf-icon--' . $name . ( ! empty( $args['class'] ) ? ' ' . $args['class'] : '' );
	$a11y  = $label ? 'role="img" aria-label="' . esc_attr( $label ) . '"' : 'aria-hidden="true" focusable="false"';
	// Front end: the inline sprite. Editor previews (REST, iframe canvas)
	// cannot see that, so they point at the sprite file instead.
	$ref = tapaufest_icon_external() ? TAPAUFEST_URL . 'assets/icons/sprite.svg?ver=' . TAPAUFEST_VERSION : '';
	return '<svg class="' . esc_attr( $class ) . '" ' . $a11y . '><use href="' . esc_url( $ref ) . '#tf-i-' . esc_attr( $name ) . '"></use></svg>';
}

/**
 * Whether icons should reference the sprite file rather than the inline copy.
 *
 * @return bool
 */
function tapaufest_icon_external() {
	return is_admin() || ( defined( 'REST_REQUEST' ) && REST_REQUEST );
}

/**
 * Print the sprite once, at the end of the page.
 */
function tapaufest_print_icon_sprite() {
	$file = TAPAUFEST_DIR . 'assets/icons/sprite.svg';
	if ( file_exists( $file ) ) {
		echo file_get_contents( $file ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped, WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- our own static SVG.
	}
}
add_action( 'wp_footer', 'tapaufest_print_icon_sprite', 1 );

/**
 * Swap typed arrows and stars for icons, so editors can keep writing
 * "Full lineup →" and still get the icon set:
 * "→" at the end of a link, "↗" (opens elsewhere), "↓" (download),
 * "←" at the start of a link, and "☆" at the start of a button.
 *
 * @param string $html Block HTML.
 * @return string
 */
function tapaufest_glyphs_to_icons( $html ) {
	if ( ! preg_match( '/[↗→↓←☆]/u', $html ) ) {
		return $html;
	}
	$html = preg_replace( '/\s?↗(?=\s*(<\/span>\s*)?<\/(a|button)>)/u', ' ' . tapaufest_icon( 'external' ), $html );
	$html = preg_replace( '/\s?→(?=\s*(<\/span>\s*)?<\/(a|button)>)/u', ' ' . tapaufest_icon( 'arrow-right' ), $html );
	$html = preg_replace( '/\s?↓(?=\s*(<\/span>\s*)?<\/(a|button)>)/u', ' ' . tapaufest_icon( 'download' ), $html );
	$html = preg_replace( '/(<a\b[^>]*>)\s*←\s*/u', '$1' . tapaufest_icon( 'arrow-left' ) . ' ', $html );
	$html = preg_replace( '/(<button\b[^>]*>)\s*☆\s*/u', '$1' . tapaufest_icon( 'star' ) . ' ', $html );
	return $html;
}

/**
 * Zone headings ("PENTAS", "LAYAR"...) get their zone icon, so editors keep
 * typing plain text.
 *
 * @param string $html Heading HTML.
 * @return string
 */
function tapaufest_zone_heading_icon( $html ) {
	return preg_replace_callback(
		'/(<h[2-4]\b[^>]*>)\s*(PENTAS|LAYAR|BENGKEL|KEMBARA|UNGGUN|KHEMAH|WARUNG|PASAR)\s*(<\/h[2-4]>)/',
		function ( $m ) {
			return $m[1] . tapaufest_icon( tapaufest_stage_icon( $m[2] ) ) . $m[2] . $m[3];
		},
		$html
	);
}

/**
 * Apply the swaps at render time. Skips code blocks; harmless to run on
 * nested blocks twice because the glyphs are gone after the first pass.
 *
 * @param string $content Block HTML.
 * @param array  $block   Block.
 * @return string
 */
function tapaufest_render_block_icons( $content, $block ) {
	$name = isset( $block['blockName'] ) ? (string) $block['blockName'] : '';
	if ( '' === $content || in_array( $name, array( 'core/code', 'core/preformatted', 'core/html' ), true ) ) {
		return $content;
	}
	$content = tapaufest_glyphs_to_icons( $content );
	if ( 'core/heading' === $name ) {
		$content = tapaufest_zone_heading_icon( $content );
	}
	if ( 'core/navigation' === $name ) {
		$content = tapaufest_nav_icons( $content );
	}
	return $content;
}
add_filter( 'render_block', 'tapaufest_render_block_icons', 20, 2 );

/**
 * Icon for a vendor tag, matched on keywords. Returns '' when none fits.
 *
 * @param string $tag Tag text.
 * @return string Icon name.
 */
function tapaufest_tag_icon( $tag ) {
	$t   = strtolower( $tag );
	$map = array(
		'veg'      => 'leaf',
		'cashless' => 'card',
		'card'     => 'card',
		'cash'     => 'cash',
		'late'     => 'moon',
		'cup'      => 'cup',
		'merch'    => 'market',
		'record'   => 'music',
		'zine'     => 'workshop',
		'hands-on' => 'workshop',
		'campsite' => 'tent',
		'free'     => 'check',
	);
	foreach ( $map as $needle => $icon ) {
		if ( false !== strpos( $t, $needle ) ) {
			return $icon;
		}
	}
	return '';
}

/**
 * Icon for a stage or zone name.
 *
 * @param string $stage Stage or zone label.
 * @return string Icon name.
 */
function tapaufest_stage_icon( $stage ) {
	$map = array(
		'PENTAS'  => 'stage',
		'LAYAR'   => 'film',
		'BENGKEL' => 'workshop',
		'KEMBARA' => 'outdoor',
		'UNGGUN'  => 'fire',
		'KHEMAH'  => 'tent',
		'WARUNG'  => 'food',
		'PASAR'   => 'market',
		'GATE'    => 'gate',
	);
	$key = strtoupper( trim( $stage ) );
	return isset( $map[ $key ] ) ? $map[ $key ] : 'pin';
}

/**
 * Main menu: hand-drawn Menu and Close buttons with labels, an arrow on each
 * link (shown in the phone overlay only) and a ticket button with the dates
 * at the foot of the overlay.
 *
 * @param string $html Navigation block HTML.
 * @return string
 */
function tapaufest_nav_icons( $html ) {
	$html = preg_replace(
		'/(<button[^>]*wp-block-navigation__responsive-container-open[^>]*>)\s*<svg.*?<\/svg>/s',
		'$1' . tapaufest_icon( 'menu' ) . '<span class="tf-nav__label">' . esc_html__( 'Menu', 'tapaufest' ) . '</span>',
		$html,
		1
	);
	$html = preg_replace(
		'/(<button[^>]*wp-block-navigation__responsive-container-close[^>]*>)\s*<svg.*?<\/svg>/s',
		'$1<span class="tf-nav__label">' . esc_html__( 'Close', 'tapaufest' ) . '</span>' . tapaufest_icon( 'close' ),
		$html,
		1
	);
	$html = str_replace( '</span></a></li>', '</span>' . tapaufest_icon( 'arrow-right', array( 'class' => 'tf-nav__arrow' ) ) . '</a></li>', $html );
	if ( function_exists( 'tapaufest_cta' ) ) {
		$cta   = tapaufest_cta();
		$when  = '';
		$extra = '<div class="tf-nav__extra">'
			. '<p class="tf-nav__when">' . tapaufest_icon( 'calendar' ) . esc_html( $when ? $when : __( '12 & 13 Dec 2026, Sungai Congkak', 'tapaufest' ) ) . '</p>'
			. '<a class="tf-btn tf-btn--cili" href="' . esc_url( $cta['url'] ) . '">' . esc_html( $cta['label'] ) . ' ' . tapaufest_icon( 'arrow-right' ) . '</a>'
			. '</div>';
		$pos = strrpos( $html, '</ul>' );
		if ( false !== $pos ) {
			$html = substr_replace( $html, '</ul>' . $extra, $pos, 5 );
		}
	}
	return $html;
}
