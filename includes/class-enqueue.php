<?php
/**
 * Enqueue class.
 *
 * @package WPBP
 */

namespace WPCPF;

use WPCPF\Traits\Singleton;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add styles of scripts files inside this class.
 */
class Enqueue {

	use Singleton;

	/**
	 * Constructor of Bootstrap class.
	 */
	private function __construct() {
		add_action( 'admin_enqueue_scripts', [ $this, 'register_admin_assets' ] );
	}

	/**
	 * All available admin scripts.
	 *
	 * @return array
	 */
	public function get_admin_scripts() {
		return [
			'wpcpf-sweetalert-js' => [
				'src'     => WPCPF_PLUGIN_URL . '/assets/js/sweetalert.js',
				'version' => time(),
				'deps'    => []
			],
			'wpcpf-admin-js' => [
				'src'     => WPCPF_PLUGIN_URL . '/assets/js/admin.js',
				'version' => time(),
				'deps'    => [ 'wpcpf-sweetalert-js','jquery' ]
			],
			'flowbite-js' => [
				'src'     => WPCPF_PLUGIN_URL . '/assets/js/flowbite153.js',
				'version' => time(),
				'deps'    => []
			],
			'tailwind-script' => [
				'src'     => WPCPF_PLUGIN_URL . '/assets/js/tailwind.js',
				'version' => time(),
				'deps'    => []
			]
		];
	}

	/**
	 * All available admin styles.
	 *
	 */
	public function get_admin_styles() {
		return [
			'admin-style' => [
				'src'     => WPCPF_PLUGIN_URL . '/assets/css/admin.css',
				'version' => time()
			]
		];
	}

	/**
	 * Register scripts and styles
	 *
	 * @return void
	 */
	public function register_admin_assets() {
		$scripts = $this->get_admin_scripts();
		$styles  = $this->get_admin_styles();

		foreach ( $scripts as $handle => $script ) {
			$deps = isset( $script['deps'] ) ? $script['deps'] : false;

			wp_register_script( $handle, $script['src'], $deps, $script['version'], true );
		}

		foreach ( $styles as $handle => $style ) {
			$deps = isset( $style['deps'] ) ? $style['deps'] : false;

			wp_register_style( $handle, $style['src'], $deps, $style['version'] );
		}
	}
}