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
class Income {

	use Singleton;

	/**
	 * Constructor of Bootstrap class.
	 */
	private function __construct() {
        session_start();
		$this->income_form_handler();
        
        $this->delete_expense_budget();

		$this->income_page();
		
	}

	/**
     * Expense budget page handler.
     *
     * @return void
     */
    public function income_page() {
        $action = isset( $_GET['action'] ) ? $_GET['action'] : 'list';
        $income_sectors = wpcpf_get_income_expense_sector( 1 ); // 1 means income sector.

        switch ( $action ) {
            case 'new':
                $template        = WPCPF_PLUGIN_DIR . '/templates/income/create.php';
                break;

            case 'edit':
                $id                    = isset( $_GET['id'] ) ? $_GET['id'] : null;
                $single_expense_budget = wpcpf_get_single_expense_budget( $id );
                $template = WPCPF_PLUGIN_DIR . '/templates/expense-budget/edit.php';
                break;

            case 'view':
                $template = __DIR__ . '/views/income-expense-sector/view.php';
                break;

            default:
                $data     = wpcpf_get_income();
                $template = WPCPF_PLUGIN_DIR . '/templates/income/list.php';
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
    public function income_form_handler() {
        
        if ( ! isset( $_POST['submit_income'] ) ) {
            return;
        }
		

        if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'income' ) ) {
            wp_die( 'Are you cheating?' );
        }

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( 'Are you cheating?' );
        }

        $income_sector_id = isset( $_POST['income_sector_id'] ) ? sanitize_text_field( $_POST['income_sector_id'] ) : '';
        $amount            = isset( $_POST['amount'] ) ? sanitize_textarea_field( $_POST['amount'] ) : '';
        $entry_date        = isset( $_POST['entry_date'] ) ? sanitize_textarea_field( $_POST['entry_date'] ) : '';
        $remarks           = isset( $_POST['remarks'] ) ? sanitize_textarea_field( $_POST['remarks'] ) : '';
        $id                = isset( $_POST['id'] ) ? sanitize_textarea_field( $_POST['id'] ) : null;

        if ( empty( $income_sector_id ) ) {
            $this->errors['income_sector_id'] = __( 'Please Provide Income Sector', 'wpcodal-pf' );
        } else {
            $this->prev_data['income_sector_id'] = $income_sector_id;
        }

        if ( empty( $amount ) ) {
            $this->errors['amount'] = __( 'Please Provide Budget Amount', 'wpcodal-pf' );
        } else {
            $this->prev_data['amount'] = $amount;
        }

        if ( empty( $entry_date ) ) {
            $this->errors['entry_date'] = __( 'Please Provide Income Date', 'wpcodal-pf' );
        } else {
            $this->prev_data['entry_date'] = $entry_date;
        }

       

        if ( empty( $remarks ) ) {
            $this->errors['remarks'] = __( 'Please Provide Remarks', 'wpcodal-pf' );
        } else {
            $this->prev_data['remarks'] = $remarks;
        }

        if ( ! empty( $this->errors ) ) {
            return;
        }

        // if ( strtotime( $start_date ) > strtotime( $end_date ) ) {
        //     $this->errors['greater_start_date'] = __( 'Start date will no be greater than end date', 'wpcodal-pf' );
        //     return;
        // }

        // $check_data_in_this_range = wpcpf_check_data_in_this_range( $expense_sector_id, $entry_date, $id );

        // if ( $check_data_in_this_range ) {
        //     $this->errors['already_exist_budget'] = __( 'Expense budget in this sector and range exist already', 'wpcodal-pf' );
        //     return;
        // }

        if ( ! $id ) {
            $insert_id = wpcpf_insert_income( [
                'income_sector_id' => $income_sector_id,
                'amount'           => $amount,
                'entry_date'       => $entry_date,
                'remarks'          => $remarks
            ] );
    
            if ( is_wp_error( $insert_id ) ) {
                wp_die( $insert_id->get_error_message() );
            }

            $redirected_to = admin_url( "admin.php?page=expense_budget&inserted_expense_budget=true" );
        } else {
            $update_data = wpcpf_update_expense_budget( [
                'expense_sector_id' => $income_sector_id,
                'amount'            => $amount,
                'start_date'        => $entry_date,
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

    public function delete_expense_budget() {
        
        if ( ! isset( $_REQUEST['delete_expense_budget_action'] ) ) {
            return;
        }
        if ( ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'wpcpf-delete-expense-budget' ) ) {
            wp_die( 'Are you cheating?' );
        }

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( 'Are you cheating?' );
        }

        $id   = isset( $_REQUEST['id'] ) ? intval( $_REQUEST['id'] ) : 0;
        $page = isset( $_REQUEST['page'] ) ? $_REQUEST['page'] : 'income_sector';

        if ( delete_expense_budget( $id ) ) {
            $redirected_to = admin_url( "admin.php?page=expense_budget&expense_budget_deleted=true" );
        } else {
            $redirected_to = admin_url( "admin.php?page=expense_budget&expense_budget_deleted_failed=true" );
        }
        $_SESSION["alert_message"] = true;
        wp_redirect( $redirected_to );
        exit;
    }
}


