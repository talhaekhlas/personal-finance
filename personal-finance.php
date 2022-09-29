<?php
/**
 * Plugin Name: Personal Finance
 * Description: A Personal Finance Plugin
 * Plugin URI: http://wpcodal.com
 * Author: Talha Ekhlas
 * Author URI: http://wpcodal.com
 * Version: 1.0
 * License: GPL2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: wpcodal-pf
 */

/*


// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Define plugin __FILE__
 */
if ( ! defined( 'WPCPF_PLUGIN_FILE' ) ) {
	define( 'WPCPF_PLUGIN_FILE', __FILE__ );
}

/**
 * Define plugin __FILE__
 */
if ( ! defined( 'WPCPF_PLUGIN_URL' ) ) {
	define( 'WPCPF_PLUGIN_URL', plugins_url( '', WPCPF_PLUGIN_FILE ) );
}


/**
 * Define plugin __FILE__
 */
if ( ! defined( 'WPCPF_PLUGIN_DIR' ) ) {
	define( 'WPCPF_PLUGIN_DIR', __DIR__ );
}

/**
 * Include necessary files to initial load of the plugin.
 */
if ( ! class_exists( 'WPCPF\Bootstrap' ) ) {
	require_once __DIR__ . '/includes/traits/trait-singleton.php';
	require_once __DIR__ . '/includes/class-bootstrap.php';
}

/**
 * Initialize the plugin functionality.
 *
 * @since  1.0.0
 * @return WPBP\Bootstrap
 */
function wp_boilerplate_plugin() {
	return WPCPF\Bootstrap::instance();
}

// Call initialization function.
wp_boilerplate_plugin();
