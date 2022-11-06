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
 * Table creation class.
 */
class Table_Creation {

	use Singleton;

	/**
	 * Constructor of class.
	 */
	private function __construct() {
		register_activation_hook( WPCPF_PLUGIN_FILE, [ $this, 'activate' ] );
	}

	/**
	 * Do stuff upon plugin activation
	 *
	 * @return void
	 */
	public function activate() {
		$this->create_income_expense_sectors_table();
		$this->create_budget_for_expenses_table();
		$this->create_income_expense_table();
		$this->create_loan_investments_table();
	}

	/**
	 * Create income expense sector table.
	 *
	 * @return void
	 */
	public function create_income_expense_sectors_table() {
		global $wpdb;

		$charset_collate = $wpdb->get_charset_collate();

		$schema = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}income_expense_sectors` (
			`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			`type` varchar(100) NOT NULL COMMENT '1=income_sector, 2=expense_sector',
			`name` varchar(100) DEFAULT NULL,
			`created_by` bigint(20) unsigned NOT NULL,
			PRIMARY KEY (`id`)
		) $charset_collate";

		if ( ! function_exists( 'dbDelta' ) ) {
			require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		}

		dbDelta( $schema );
	}

	/**
	 * Create expense budget table.
	 *
	 * @return void
	 */
	public function create_budget_for_expenses_table() {
		global $wpdb;

		$charset_collate = $wpdb->get_charset_collate();

		$schema = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}budget_for_expenses` (
			`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			`expense_sector_id` int(11) unsigned NOT NULL,
			`amount` int(11) unsigned NOT NULL,
			`start_date` date NOT NULL,
			`end_date` date NOT NULL,
			`remarks` varchar(300) DEFAULT NULL,
			`created_by` bigint(20) unsigned NOT NULL,
			PRIMARY KEY (`id`),
			FOREIGN KEY (`expense_sector_id`) REFERENCES `{$wpdb->prefix}income_expense_sectors`(`id`)
			ON DELETE CASCADE
		) $charset_collate";

		if ( ! function_exists( 'dbDelta' ) ) {
			require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		}

		dbDelta( $schema );
	}

	/**
	* Create income expense budget table.
	*
	* @return void
	*/
	public function create_income_expense_table() {
		global $wpdb;

		$charset_collate = $wpdb->get_charset_collate();

		$schema = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}income_expenses` (
			`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			`income_sector_id` int(11) unsigned DEFAULT NULL,
			`budget_for_expense_id` int(11) unsigned DEFAULT NULL,
			`amount` int(11) unsigned NOT NULL,
			`entry_date` date NOT NULL,
			`remarks` varchar(300) DEFAULT NULL,
			`created_by` bigint(20) unsigned NOT NULL,
			PRIMARY KEY (`id`),
			FOREIGN KEY (`income_sector_id`) REFERENCES `{$wpdb->prefix}income_expense_sectors`(`id`)
			ON DELETE CASCADE,
			FOREIGN KEY (`budget_for_expense_id`) REFERENCES `{$wpdb->prefix}budget_for_expenses`(`id`)
			ON DELETE CASCADE
		) $charset_collate";

		if ( ! function_exists( 'dbDelta' ) ) {
			require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		}

		dbDelta( $schema );
	}

	/**
	* Create loan_investments table table.
	*
	* @return void
	*/
	public function create_loan_investments_table() {
		global $wpdb;

		$charset_collate = $wpdb->get_charset_collate();

		$schema = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}loan_investments` (
			`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			`loan_or_investment` int(11) unsigned DEFAULT NULL COMMENT '1=loan, 2=investment',
			`trn_type` int(11) unsigned DEFAULT NULL COMMENT '1=loan pay, 2=loan recieve, 3=investment, 4=earning',
			`parent_source_id` int(11) unsigned DEFAULT NULL,
			`source_name` varchar(200) DEFAULT NULL,
			`amount` int(11) unsigned NOT NULL,
			`entry_date` date DEFAULT NULL,
			`remarks` varchar(300) DEFAULT NULL,
			`created_by` bigint(20) unsigned NOT NULL,
			PRIMARY KEY (`id`),
			FOREIGN KEY (`parent_source_id`) REFERENCES `{$wpdb->prefix}loan_investments`(`id`)
			ON DELETE CASCADE
		) $charset_collate";

		if ( ! function_exists( 'dbDelta' ) ) {
			require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		}

		dbDelta( $schema );
	}
}