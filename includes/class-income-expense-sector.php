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
class Income_Expense_Sector {

	use Singleton;

	/**
	 * Constructor of Bootstrap class.
	 */
	private function __construct( $sector_type ) {
		$this->form_handler();
		$this->income_expense_page( $sector_type );
		
	}

	/**
     * Income expense page handler.
     *
     * @return void
     */
    public function income_expense_page( $sector_type ) {
        $action = isset( $_GET['action'] ) ? $_GET['action'] : 'list';
        $url    = isset( $_GET['page'] ) ? $_GET['page'] : 'income_sector';

        switch ( $action ) {
            case 'new':
                $template = WPCPF_PLUGIN_DIR . '/templates/income-expense-sector/create.php';
                break;

            case 'edit':
                $template = __DIR__ . '/views/income-expense-sector/edit.php';
                break;

            case 'view':
                $template = __DIR__ . '/views/income-expense-sector/view.php';
                break;

            default:
                $template = WPCPF_PLUGIN_DIR . '/templates/income-expense-sector/list.php';
                break;
        }

        if ( file_exists( $template ) ) {
            include $template;
        }
    }

	/**
     * Handle the form
     *
     * @return void
     */
    public function form_handler() {
        if ( ! isset( $_POST['submit_income_expense_sector'] ) ) {
            return;
        }
		

        if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'new-income-expense-sector' ) ) {
            wp_die( 'Are you cheating?' );
        }

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( 'Are you cheating?' );
        }

        echo '<pre>';
        print_r( $_POST );
        exit;
    }
}
