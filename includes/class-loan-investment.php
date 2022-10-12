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
class Loan_Investment {

	use Singleton;

	/**
	 * Constructor of Bootstrap class.
	 */
	private function __construct() {
        session_start();
		$this->loan_investment_form_handler();
        
        $this->delete_loan_investment();

		$this->income_expense_page();
		
	}

	/**
     * Expense budget page handler.
     *
     * @return void
     */
    public function income_expense_page() {
        $action                  = isset( $_GET['action'] ) ? $_GET['action'] : 'list';
        $page                    = isset( $_GET['page'] ) ? $_GET['page'] : 'list';
        $income_sectors          = wpcpf_get_income_expense_sector( 1 ); // 1 means income sector.
        $budget_list_for_expense = wpcpf_get_budget_list_for_expense();

        $income_sector_by_id = [];

        foreach ($income_sectors as $value) {
            $income_sector_by_id[$value->id] = $value->name;
            $type = $page == 'loan' ? 1 : 2; //1 for loan, 2 for investment.
        }

        switch ( $action ) {
            case 'new':
                $parent_data = wpcpf_get_parent_loan_investment_data( $type, null );
                $template    = WPCPF_PLUGIN_DIR . '/templates/loan-investment/create.php';
                break;

            case 'edit':
                $id                     = isset( $_GET['id'] ) ? $_GET['id'] : null;
                $parent_data            = wpcpf_get_parent_loan_investment_data( $type, $id );
                $single_loan_investment = wpcpf_get_single_loan_investment( $id );
                $is_parent              = wpcpf_get_is_parent_check( $id );
                $template               = WPCPF_PLUGIN_DIR . '/templates/loan-investment/edit.php';
                break;

            case 'view':
                $template = __DIR__ . '/views/income-expense-sector/view.php';
                break;

            default:
                $type     = $page == 'loan' ? 1 : 2; //1 for loan, 2 for investment.
                $data     = wpcpf_get_loan_investment_data( $type );
                $template = WPCPF_PLUGIN_DIR . '/templates/loan-investment/list.php';
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
    public function loan_investment_form_handler() {
        
        if ( ! isset( $_POST['submit_loan_investment'] ) ) {
            return;
        }
		

        if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'loan_investment' ) ) {
            wp_die( 'Are you cheating?' );
        }

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( 'Are you cheating?' );
        }

        $trn_type           = isset( $_POST['trn_type'] ) ? sanitize_text_field( $_POST['trn_type'] ) : '';
        $loan_or_investment = isset( $_POST['loan_or_investment'] ) ? sanitize_text_field( $_POST['loan_or_investment'] ) : '';
        $parent_source_id   = isset( $_POST['parent_source_id'] ) ? sanitize_text_field( $_POST['parent_source_id'] ) : '';
        $source_name        = isset( $_POST['source_name'] ) ? sanitize_text_field( $_POST['source_name'] ) : '';
        $amount             = isset( $_POST['amount'] ) ? sanitize_textarea_field( $_POST['amount'] ) : '';
        $entry_date         = isset( $_POST['entry_date'] ) ? sanitize_textarea_field( $_POST['entry_date'] ) : '';
        $remarks            = isset( $_POST['remarks'] ) ? sanitize_textarea_field( $_POST['remarks'] ) : '';
        $id                 = isset( $_POST['id'] ) ? sanitize_textarea_field( $_POST['id'] ) : null;
        $page               = $_GET['page'];
        
        if ( $parent_source_id == 'no_parent' && empty( $source_name ) ) {
            $this->errors['source_name'] = __( 'Please Provide Source Name', 'wpcodal-pf' );
        } else {
            $this->prev_data['source_name'] = $source_name;
        }

        if ( $parent_source_id != 'no_parent' ) {
            $this->prev_data['parent_source_id'] = $parent_source_id;
        }

        if ( empty( $source_name ) ) {
            $this->prev_data['trn_type'] = $trn_type;
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

        if ( $parent_source_id != 'no_parent' ) {
            $parent_data = wpcpf_get_single_loan_investment( $parent_source_id );
        }

        if ( strtotime( $parent_data->entry_date ) > strtotime( $entry_date ) ) {
            $this->errors['greater_entry_date'] = __( 'Entry date should be greater than entry date of parent source.', 'wpcodal-pf' );
            
            return;
        }

        if ( $id == $parent_source_id ) {
            $this->errors['invalid_parent_source'] = __( 'Parent source and child source can not be same.', 'wpcodal-pf' );
            
            return;
        }

        $data['trn_type']           = $trn_type;
        $data['parent_source_id']   = $parent_source_id == 'no_parent' ? null : $parent_source_id;
        $data['source_name']        = $source_name;
        $data['loan_or_investment'] = $loan_or_investment;
        $data['amount']             = $amount;
        $data['entry_date']         = $entry_date;
        $data['remarks']            = $remarks;

        // echo '<pre>';
        // print_r($data);
        // die();

        if ( ! $id ) {
            $insert_id = wpcpf_insert_loan_investment( $data, $page );
            if ( is_wp_error( $insert_id ) ) {
                wp_die( $insert_id->get_error_message() );
            }
            $redirected_to = admin_url( "admin.php?page={$page}&inserted_{$page}=true" );
        } else {
            $update_data = wpcpf_update_loan_investment( $data, $page, $id );
    
            if ( is_wp_error( $update_data ) ) {
                wp_die( $update_data->get_error_message() );
            }
            $redirected_to = admin_url( "admin.php?page={$page}&updateee_{$page}=true" );
        }

        $_SESSION["alert_message"] = true;

        wp_redirect( $redirected_to );
        exit;
    }

    public function delete_loan_investment() {
        
        if ( ! isset( $_REQUEST['delete_loan_invest_action'] ) ) {
            return;
        }
        if ( ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'wpcpf-delete-loan-investment' ) ) {
            wp_die( 'Are you cheating?' );
        }

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( 'Are you cheating?' );
        }

        $id   = isset( $_REQUEST['id'] ) ? intval( $_REQUEST['id'] ) : 0;
        $page = $_REQUEST['page'];

        if ( delete_loan_investment( $id ) ) {
            $redirected_to = admin_url( "admin.php?page={$page}&{$page}_deleted=true" );
        } else {
            $redirected_to = admin_url( "admin.php?page={$page}&{$page}_deleted_failed=true" );
        }
        $_SESSION["alert_message"] = true;
        wp_redirect( $redirected_to );
        exit;
    }
}


