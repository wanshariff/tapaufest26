<?php
/**
 * Plugin Name:       TAPAUfest Core
 * Plugin URI:        https://tapaufest.asia/
 * Description:       Festival content and blocks for tapaufest.asia: lineup, timetable with Jadual Aku, ticket tiers, FAQ, vendors and the site state switch (early access, public sale, festival day, recap).
 * Version:           1.0.0
 * Requires at least: 6.6
 * Requires PHP:      7.4
 * Author:            TAPAUasia
 * Author URI:        https://tapau.asia/
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       tapaufest
 *
 * @package TapaufestCore
 */

defined( 'ABSPATH' ) || exit;

define( 'TAPAUFEST_VERSION', '1.0.0' );
define( 'TAPAUFEST_FILE', __FILE__ );
define( 'TAPAUFEST_DIR', plugin_dir_path( __FILE__ ) );
define( 'TAPAUFEST_URL', plugin_dir_url( __FILE__ ) );

require_once TAPAUFEST_DIR . 'includes/settings.php';
require_once TAPAUFEST_DIR . 'includes/post-types.php';
require_once TAPAUFEST_DIR . 'includes/fields.php';
require_once TAPAUFEST_DIR . 'includes/data.php';
require_once TAPAUFEST_DIR . 'includes/blocks.php';
require_once TAPAUFEST_DIR . 'includes/rest.php';
require_once TAPAUFEST_DIR . 'includes/seed.php';

/**
 * Flush rewrite rules once after activation so /lineup/ and /artist/ resolve.
 */
function tapaufest_activate() {
	tapaufest_register_post_types();
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'tapaufest_activate' );

/**
 * Clean up rewrite rules on deactivation.
 */
function tapaufest_deactivate() {
	flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'tapaufest_deactivate' );
