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
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );

		add_action( 'admin_enqueue_scripts', [ $this, 'register_admin_assets' ] );

	}

	/**
	 * Add JS scripts.
	 */
	public function enqueue_scripts() {
		// wp_enqueue_script();
	}

	/**
	 * Add CSS files.
	 */
	public function enqueue_styles() {
		// wp_enqueue_style();
	}

	/**
     * All available admin scripts.
     *
     * @return array
     */
    public function get_admin_scripts() {
        return [
            'wpcpf-admin-js' => [
                'src'     => WPCPF_PLUGIN_URL . '/assets/js/admin.js',
                'version' => time(),
                'deps'    => [ 'jquery' ]
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
     * @return array
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
        wp_enqueue_script( 'tailwind-script' );

        foreach ( $styles as $handle => $style ) {
            $deps = isset( $style['deps'] ) ? $style['deps'] : false;

            wp_register_style( $handle, $style['src'], $deps, $style['version'] );
        }
    }
}
