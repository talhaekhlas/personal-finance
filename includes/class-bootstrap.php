<?php
/**
 * Bootstrap class.
 *
 * @package WPCPF
 */

namespace WPCPF;

use WPCPF\Ajax\Sample_Ajax;
use WPCPF\Traits\Singleton;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Load core functionality inside this class.
 */
class Bootstrap {

	use Singleton;

	/**
	 * Constructor of Bootstrap class.
	 */
	private function __construct() {
		// Include custom function files.
		$this->custom_functions();

		// Include asset method.
		$this->load_scripts();

		// Include ajax classes.
		$this->load_ajax_classes();

		// Include common classes.
		$this->load_common_classes();
	}

	/**
	 * Load custom functions.
	 */
	private function custom_functions() {
		require_once __DIR__ . '/custom-functions.php';
	}

	/**
	 * Load scripts and styles.
	 */
	private function load_scripts() {
		require_once __DIR__ . '/class-enqueue.php';

		Enqueue::instance();
	}

	/**
	 * Load ajax classes
	 */
	private function load_ajax_classes() {
		require_once __DIR__ . '/ajax/class-sample-ajax.php';
		
		Sample_Ajax::instance();
	}

	/**
	 * Load ajax classes
	 */
	private function load_common_classes() {
		require_once __DIR__ . '/class-table-creation.php';
		require_once __DIR__ . '/class-menu.php';
		Table_Creation::instance();
		Menu::instance();
	}

}
