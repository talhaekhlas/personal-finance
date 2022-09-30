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
        $sector_type_id = $sector_type == 'income' ? 1 : 2;

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
                $data  = wpcpf_get_income_expense_sector( $sector_type_id );
                $title = $sector_type == 'income' ? 'List of Income Sectors' : 'List of Expense Sectors';
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

        $name    = isset( $_POST['name'] ) ? sanitize_text_field( $_POST['name'] ) : '';
        $type    = isset( $_POST['type'] ) ? sanitize_textarea_field( $_POST['type'] ) : '';

        if ( empty( $name ) ) {
            $this->errors['name'] = __( 'Please provide a name', 'wpcodal-pf' );
        }

        if ( ! empty( $this->errors ) ) {
            return;
        }

        $insert_id = wpcpf_insert_income_expense_sector( [
            'name'    => $name,
            'type'    => (int) $type,
        ] );

        if ( is_wp_error( $insert_id ) ) {
            wp_die( $insert_id->get_error_message() );
        }

        $page_url = $type == 1 ? 'income_sector' : 'expense_sector';
        
        $redirected_to = admin_url( "admin.php?page={$page_url}&inserted=true" );
        wp_redirect( $redirected_to );
        exit;
    }
}
