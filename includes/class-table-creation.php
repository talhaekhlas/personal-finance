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
class Table_Creation {

	use Singleton;

	/**
	 * Constructor of Bootstrap class.
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
        ) $charset_collate";

        if ( ! function_exists( 'dbDelta' ) ) {
            require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        }

        dbDelta( $schema );
    }
}

