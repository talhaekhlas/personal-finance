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
        $this->budget_search_form_handler();
        
        $this->delete_expense_budget();

		$this->expense_budget_page();
		
	}

	/**
     * Expense budget page handler.
     *
     * @return void
     */
    public function expense_budget_page() {
        $action            = isset( $_GET['action'] ) ? $_GET['action'] : 'list';
        $expense_sectors   = wpcpf_get_income_expense_sector( 2 ); // 2 means expense sector.
        $start_date        = isset( $_GET['start_date'] ) ? $_GET['start_date'] : null;
        $end_date          = isset( $_GET['end_date'] ) ? $_GET['end_date'] : null;
        $expense_sector_id = isset( $_GET['expense_sector_id'] ) ? $_GET['expense_sector_id'] : null;

        $expense_sector_by_id = [];

        foreach ($expense_sectors as $value) {
            $expense_sector_by_id[$value->id] = $value->name;
        }

        switch ( $action ) {
            case 'new':
                $template        = WPCPF_PLUGIN_DIR . '/templates/expense-budget/create.php';
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
                $data     = wpcpf_get_expense_budget( $start_date, $end_date, $expense_sector_id );
                $data_for_dropdown = wpcpf_get_expense_budget(null, null, null);
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
        } else {
            $this->prev_data['expense_sector_id'] = $expense_sector_id;
        }

        if ( empty( $amount ) ) {
            $this->errors['amount'] = __( 'Please Provide Budget Amount', 'wpcodal-pf' );
        } else {
            $this->prev_data['amount'] = $amount;
        }

        if ( empty( $start_date ) ) {
            $this->errors['start_date'] = __( 'Please Provide Start Date', 'wpcodal-pf' );
        } else {
            $this->prev_data['start_date'] = $start_date;
        }

        if ( empty( $end_date ) ) {
            $this->errors['end_date'] = __( 'Please Provide End Date', 'wpcodal-pf' );
        } else {
            $this->prev_data['end_date'] = $end_date;
        }

        if ( empty( $remarks ) ) {
            $this->errors['remarks'] = __( 'Please Provide Remarks', 'wpcodal-pf' );
        } else {
            $this->prev_data['remarks'] = $remarks;
        }

        if ( ! empty( $this->errors ) ) {
            return;
        }

        if ( strtotime( $start_date ) > strtotime( $end_date ) ) {
            $this->errors['greater_start_date'] = __( 'Start date will no be greater than end date', 'wpcodal-pf' );
            return;
        }

        $check_data_in_this_range = wpcpf_check_data_in_this_range( $expense_sector_id, $start_date, $id );

        if ( $check_data_in_this_range ) {
            $this->errors['already_exist_budget'] = __( 'Expense budget in this sector and range exist already', 'wpcodal-pf' );
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

    /**
     * Handle the form
     *
     * @return void
     */
    public function budget_search_form_handler() {
        
        if ( ! isset( $_POST['submit_search_budget'] ) ) {
            return;
        }
		

        if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'search_budget' ) ) {
            wp_die( 'Are you cheating?' );
        }

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( 'Are you cheating?' );
        }

        $start_date  = isset( $_POST['start_date'] ) ? sanitize_textarea_field( $_POST['start_date'] ) : '';
        $end_date    = isset( $_POST['end_date'] ) ? sanitize_textarea_field( $_POST['end_date'] ) : '';
        $expense_sector_id   = isset( $_POST['expense_sector_id'] ) ? sanitize_text_field( $_POST['expense_sector_id'] ) : '';
        $this->prev_data['expense_sector_id'] = $expense_sector_id;
        if ( empty( $start_date ) ) {
            $this->errors['start_date'] = __( 'Please Provide Start Date', 'wpcodal-pf' );
        } else {
            $this->prev_data['start_date'] = $start_date;
        }

        if ( empty( $end_date ) ) {
            $this->errors['end_date'] = __( 'Please Provide End Date', 'wpcodal-pf' );
        } else {
            $this->prev_data['end_date'] = $end_date;
        }

        if ( strtotime( $start_date ) > strtotime( $end_date ) ) {
            $this->errors['greater_start_date'] = __( 'Start date should not be less than end date.', 'wpcodal-pf' );
        }

        if ( ! empty( $this->errors ) ) {
            return;
        }

        if ( strtotime( $start_date ) > strtotime( $end_date ) ) {
            $this->errors['greater_start_date'] = __( 'Start date will no be greater than end date', 'wpcodal-pf' );
            return;
        }
        $extra_parameter = "start_date={$start_date}&end_date={$end_date}&expense_sector_id={$expense_sector_id}";
        $redirected_to = admin_url( "admin.php?page=expense_budget&$extra_parameter" );

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


