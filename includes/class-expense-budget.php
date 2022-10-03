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
                $expense_sectors = wpcpf_get_income_expense_sector( 2 );
                $template        = WPCPF_PLUGIN_DIR . '/templates/expense-budget/create.php';
                break;

            case 'edit':
                $id       = isset( $_GET['id'] ) ? $_GET['id'] : null;
                $single_income_expense = wpcpf_get_single_income_expense_sector( $id );
                $template = WPCPF_PLUGIN_DIR . '/templates/expense-budget/edit.php';
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
        
        if ( ! isset( $_POST['submit_expense_budget'] ) ) {
            return;
        }
		

        if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'expense-budget' ) ) {
            wp_die( 'Are you cheating?' );
        }

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( 'Are you cheating?' );
        }

        $expense_sector_id = isset( $_POST['expense_sector_id'] ) ? sanitize_text_field( $_POST['expense_sector_id'] ) : '';
        $amount            = isset( $_POST['amount'] ) ? sanitize_textarea_field( $_POST['amount'] ) : '';
        $start_date        = isset( $_POST['start_date'] ) ? sanitize_textarea_field( $_POST['start_date'] ) : '';
        $end_date          = isset( $_POST['end_date'] ) ? sanitize_textarea_field( $_POST['end_date'] ) : '';
        $remarks           = isset( $_POST['remarks'] ) ? sanitize_textarea_field( $_POST['remarks'] ) : '';
        $id                = isset( $_POST['id'] ) ? sanitize_textarea_field( $_POST['id'] ) : null;

        if ( empty( $expense_sector_id ) ) {
            $this->errors['expense_sector_id'] = __( 'Please Provide Expense Sector', 'wpcodal-pf' );
        }

        if ( empty( $amount ) ) {
            $this->errors['amount'] = __( 'Please Provide Budget Amount', 'wpcodal-pf' );
        }

        if ( empty( $start_date ) ) {
            $this->errors['start_date'] = __( 'Please Provide Start Date', 'wpcodal-pf' );
        }

        if ( empty( $end_date ) ) {
            $this->errors['end_date'] = __( 'Please Provide End Date', 'wpcodal-pf' );
        }

        if ( empty( $remarks ) ) {
            $this->errors['remarks'] = __( 'Please Provide Remarks', 'wpcodal-pf' );
        }

        if ( ! empty( $this->errors ) ) {
            return;
        }

        if ( ! $id ) {
            $insert_id = wpcpf_insert_expense_budget( [
                'expense_sector_id' => $expense_sector_id,
                'amount'            => $amount,
                'start_date'        => $start_date,
                'end_date'          => $end_date,
                'remarks'           => $remarks
            ] );
    
            if ( is_wp_error( $insert_id ) ) {
                wp_die( $insert_id->get_error_message() );
            }

            $redirected_to = admin_url( "admin.php?page=expense_budget&inserted_expense_budget=true" );
        } else {
            $update_data = wpcpf_update_expense_budget( [
                'expense_sector_id' => $expense_sector_id,
                'amount'            => $amount,
                'start_date'        => $start_date,
                'end_date'          => $end_date,
                'remarks'           => $remarks
            ], $id );
    
            if ( is_wp_error( $update_data ) ) {
                wp_die( $update_data->get_error_message() );
            }
            $redirected_to = admin_url( "admin.php?page=expense_budget&updateee_expense_budget=true" );
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
