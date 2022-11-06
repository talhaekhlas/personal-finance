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
 * Add menu class.
 */
class Menu {

	use Singleton;

	/**
	 * Constructor of Bootstrap class.
	 */
	private function __construct() {
		require_once __DIR__ . '/class-income-expense-sector.php';
		require_once __DIR__ . '/class-expense-budget.php';
		require_once __DIR__ . '/class-income-expense.php';
		require_once __DIR__ . '/class-loan-investment.php';
		add_action( 'admin_menu', [$this, 'admin_menu'] );
	}

		/**
		 * Add admin menu.
		 */
		public function admin_menu() {
				$parent_slug = 'income_sector';
				$capability  = 'manage_options';

				$main_hook = add_menu_page( __( 'Personal Finance', 'wpcodal-pf' ), __( 'Personal Finance', 'wpcodal-pf' ), $capability, $parent_slug, [ $this, 'income_sector' ], 'dashicons-money-alt' );
				add_submenu_page( $parent_slug, __( 'Income Sector', 'wpcodal-pf' ), __( 'Income Sector', 'wpcodal-pf' ), $capability, $parent_slug, [ $this, 'income_sector' ] );
				$expense_sector_hook = add_submenu_page( $parent_slug, __( 'Expense Sector', 'wpcodal-pf' ), __( 'Expense Sector', 'wpcodal-pf' ), $capability, 'expense_sector', [ $this, 'expense_sector' ] );
				$expense_budget_hook = add_submenu_page( $parent_slug, __( 'Expense Budget', 'wpcodal-pf' ), __( 'Expense Budget', 'wpcodal-pf' ), $capability, 'expense_budget', [ $this, 'expense_budget' ] );
				$income_hook         = add_submenu_page( $parent_slug, __( 'Income', 'wpcodal-pf' ), __( 'Income', 'wpcodal-pf' ), $capability, 'income', [ $this, 'income' ] );
				$expense_hook        = add_submenu_page( $parent_slug, __( 'Expense', 'wpcodal-pf' ), __( 'Expense', 'wpcodal-pf' ), $capability, 'expense', [ $this, 'expense' ] );
				$loan_hook           = add_submenu_page( $parent_slug, __( 'Loan', 'wpcodal-pf' ), __( 'Loan', 'wpcodal-pf' ), $capability, 'loan', [ $this, 'loan' ] );
				$investment_hook     = add_submenu_page( $parent_slug, __( 'Investment', 'wpcodal-pf' ), __( 'Investment', 'wpcodal-pf' ), $capability, 'investment', [ $this, 'investment' ] );
				add_action( 'admin_head-' . $main_hook, [ $this, 'enqueue_assets' ] );
				add_action( 'admin_head-' . $expense_sector_hook, [ $this, 'enqueue_assets' ] );
				add_action( 'admin_head-' . $expense_budget_hook, [ $this, 'enqueue_assets' ] );
				add_action( 'admin_head-' . $income_hook, [ $this, 'enqueue_assets' ] );
				add_action( 'admin_head-' . $expense_hook, [ $this, 'enqueue_assets' ] );
				add_action( 'admin_head-' . $loan_hook, [ $this, 'enqueue_assets' ] );
				add_action( 'admin_head-' . $investment_hook, [ $this, 'enqueue_assets' ] );
		}

		/**
		* Income sector.
		*/
		public function income_sector() {
		Income_Expense_Sector::instance('income');
		}

		/**
		 * Income.
		 * 
		 * @return void
		 */
		public function income() {
			Income_Expense::instance();
		}

		/**
		 * Expense.
		 * 
		 * @return void
		 */
		public function expense() {
			Income_Expense::instance();
		}

		/**
		 * Expense sector.
		 */
		public function expense_sector() {
			Income_Expense_Sector::instance('expense');
		}

		/**
		 * Expense budget.
		 */
		public function expense_budget() {
			Expense_Budget::instance();
		}

		/**
		 * Loan page.
		 */
		public function loan() {
			Loan_Investment::instance();
		}

		/**
		 * Investment page.
		 */
		public function investment() {
			Loan_Investment::instance();
		}

		/**
		 * Enqueue scripts and styles
		 *
		 * @return void
		 */
		public function enqueue_assets() {
				wp_enqueue_style( 'admin-style' );
				wp_enqueue_script( 'flowbite-js' );
				wp_enqueue_script( 'tailwind-script' );
				wp_enqueue_script( 'wpcpf-sweetalert-js' );
				wp_enqueue_script( 'wpcpf-admin-js' );

				$action    = 'ajd_protected';
				$ajd_nonce = wp_create_nonce( $action );

				wp_localize_script(
					'wpcpf-admin-js',
					'some_localize_info',
					array(
						'ajax_url'     => admin_url( 'admin-ajax.php' ),
						'ajd_nonce'    => $ajd_nonce,
					)
				);
		}
}