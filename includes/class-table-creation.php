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
        $this->create_tables();
    }

    /**
     * Create necessary database tables
     *
     * @return void
     */
    public function create_tables() {
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
}

