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
class Menu {

	use Singleton;

	/**
	 * Constructor of Bootstrap class.
	 */
	private function __construct() {
		require_once __DIR__ . '/class-income-expense-sector.php';
		add_action( 'admin_menu', [$this, 'admin_menu'] );
	}

    /**
     * Add admin menu.
     */
    public function admin_menu() {
        $parent_slug = 'income_sector';
        $capability = 'manage_options';

        $main_hook = add_menu_page( __( 'Personal Finance', 'wpcodal-pf' ), __( 'Personal Finance', 'wpcodal-pf' ), $capability, $parent_slug, [ $this, 'income_sector' ], 'dashicons-money-alt' );
        add_submenu_page( $parent_slug, __( 'Income Sector', 'wpcodal-pf' ), __( 'Income Sector', 'wpcodal-pf' ), $capability, $parent_slug, [ $this, 'income_sector' ] );
        $expense_hook = add_submenu_page( $parent_slug, __( 'Expense Sector', 'wpcodal-pf' ), __( 'Expense Sector', 'wpcodal-pf' ), $capability, 'expense_sector', [ $this, 'expense_sector' ] );
        add_action( 'admin_head-' . $main_hook, [ $this, 'enqueue_assets' ] );
        add_action( 'admin_head-' . $expense_hook, [ $this, 'enqueue_assets' ] );
    }

	/**
     * Income sector.
     */
    public function income_sector() {
		Income_Expense_Sector::instance('income');
		// echo 'income';
        // (new Income_Expense_Sector())->income_expense_page('Income');
    }

    /**
     * Expense sector.
     */
    public function expense_sector() {
		Income_Expense_Sector::instance('expense');
        // (new Income_Expense_Sector())->income_expense_page('Expense');
    }

	/**
     * Enqueue scripts and styles
     *
     * @return void
     */
    public function enqueue_assets() {
        wp_enqueue_style( 'admin-style' );
        wp_enqueue_script( 'tailwind-script' );
        wp_enqueue_script( 'wpcpf-sweetalert-js' );
        wp_enqueue_script( 'wpcpf-admin-js' );
    }

}
