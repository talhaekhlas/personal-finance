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
class Expense_Budget {

	use Singleton;

	/**
	 * Constructor of Bootstrap class.
	 */
	private function __construct() {
        session_start();
		$this->budget_form_handler();
        
        $this->delete_income_expense_sector();

		$this->expense_budget_page();
		
	}

	/**
     * Expense budget page handler.
     *
     * @return void
     */
    public function expense_budget_page() {
        $action = isset( $_GET['action'] ) ? $_GET['action'] : 'list';
        $url    = isset( $_GET['page'] ) ? $_GET['page'] : 'income_sector';
        

        switch ( $action ) {
            case 'new':
                $template = WPCPF_PLUGIN_DIR . '/templates/expense-budget/create.php';
                break;

            case 'edit':
                $id       = isset( $_GET['id'] ) ? $_GET['id'] : null;
                $single_income_expense = wpcpf_get_single_income_expense_sector( $id );
                $template = WPCPF_PLUGIN_DIR . '/templates/income-expense-sector/edit.php';
                break;

            case 'view':
                $template = __DIR__ . '/views/income-expense-sector/view.php';
                break;

            default:
                $data     = wpcpf_get_expense_budget();
                $template = WPCPF_PLUGIN_DIR . '/templates/expense-budget/list.php';
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
    public function budget_form_handler() {
        if ( ! isset( $_POST['submit_income_expense_sector'] ) ) {
            return;
        }
		

        if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'new-income-expense-sector' ) ) {
            wp_die( 'Are you cheating?' );
        }

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( 'Are you cheating?' );
        }

        $name     = isset( $_POST['name'] ) ? sanitize_text_field( $_POST['name'] ) : '';
        $type     = isset( $_POST['type'] ) ? sanitize_textarea_field( $_POST['type'] ) : '';
        $id       = isset( $_POST['id'] ) ? sanitize_textarea_field( $_POST['id'] ) : null;
        $page_url = $type == 1 ? 'income_sector' : 'expense_sector';

        if ( empty( $name ) ) {
            $this->errors['name'] = __( 'Please provide a name', 'wpcodal-pf' );
        }

        if ( ! empty( $this->errors ) ) {
            return;
        }

        if ( ! $id ) {
            $insert_id = wpcpf_insert_income_expense_sector( [
                'name'    => $name,
                'type'    => (int) $type,
            ] );
    
            if ( is_wp_error( $insert_id ) ) {
                wp_die( $insert_id->get_error_message() );
            }

            $redirected_to = admin_url( "admin.php?page={$page_url}&inserted=true" );
        } else {
            $update_data = wpcpf_update_income_expense_sector( [
                'name'    => $name,
                'type'    => (int) $type,
            ], $id );
    
            if ( is_wp_error( $update_data ) ) {
                wp_die( $update_data->get_error_message() );
            }
            $redirected_to = admin_url( "admin.php?page={$page_url}&updateee=true" );
        }

        $_SESSION["alert_message"] = true;

        wp_redirect( $redirected_to );
        exit;
    }

    public function delete_income_expense_sector() {
        
        if ( ! isset( $_REQUEST['delete_sector_action'] ) ) {
            return;
        }
        if ( ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'wpcpf-delete-sector' ) ) {
            wp_die( 'Are you cheating?' );
        }

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( 'Are you cheating?' );
        }

        $id   = isset( $_REQUEST['id'] ) ? intval( $_REQUEST['id'] ) : 0;
        $page = isset( $_REQUEST['page'] ) ? $_REQUEST['page'] : 'income_sector';

        if ( delete_sector( $id ) ) {
            $redirected_to = admin_url( "admin.php?page={$page}&sector-deleted=true" );
        } else {
            $redirected_to = admin_url( "admin.php?page={$page}&sector-deleted-failed=true" );
        }
        $_SESSION["alert_message"] = true;
        wp_redirect( $redirected_to );
        exit;
    }
}
