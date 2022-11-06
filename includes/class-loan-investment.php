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
 * Loan investment class.
 */
class Loan_Investment {

	use Singleton;

	/**
	 * Constructor of Bootstrap class.
	 */
	private function __construct() {
		session_start();
		$this->loan_investment_form_handler();
		$this->search_loan_investment_form_handler();
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
		$start_date              = isset( $_GET['start_date'] ) ? $_GET['start_date'] : null;
		$end_date                = isset( $_GET['end_date'] ) ? $_GET['end_date'] : null;
		$loan_investment_id      = isset( $_GET['loan_investment_id'] ) ? $_GET['loan_investment_id'] : null;
		$trn_type                = isset( $_GET['trn_type'] ) ? $_GET['trn_type'] : null;
		$budget_list_for_expense = wpcpf_get_budget_list_for_expense();

		$income_sector_by_id = [];

		foreach ($income_sectors as $value) {
			$income_sector_by_id[$value->id] = $value->name;
			//1 for loan, 2 for investment.
			$type = $page == 'loan' ? 1 : 2; 
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
				$type                   = $page == 'loan' ? 1 : 2; //1 for loan, 2 for investment.
				$data                   = wpcpf_get_loan_investment_data( $type, $trn_type, $loan_investment_id, $start_date, $end_date );
				$parent_loan_investment = wpcpf_get_parent_loan_investment_data( $type );
				$template    = WPCPF_PLUGIN_DIR . '/templates/loan-investment/list.php';
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

		if ( $trn_type == 4 ) {
			$source_name = null;
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

		if ( $parent_source_id != 'no_parent' ) {
			$parent_data = wpcpf_get_single_loan_investment( $parent_source_id );
		}

		if ( isset($parent_data) && (strtotime( $parent_data->entry_date ) > strtotime( $entry_date )) ) {
			$this->errors['greater_entry_date'] = __( 'Entry date should be greater than entry date of parent source.', 'wpcodal-pf' );
		}

		if ( $id == $parent_source_id ) {
			$this->errors['invalid_parent_source'] = __( 'Parent source and child source can not be same.', 'wpcodal-pf' );
		}

		if ( $parent_source_id == 'no_parent' && $trn_type == 4 ) { //trn type 4 means earning from investment.
			$this->errors['missing_parent_investment_earning'] = __( 'Earning should have parent source.', 'wpcodal-pf' ); 
		}

		$amount_validation = $this->investment_or_loan_pay_capability_check( $entry_date, $amount );

		if ( ($trn_type == 2 || $trn_type == 3) &&  ! $amount_validation) {
			return;
		}

		if ( ! empty( $this->errors ) ) {
			return;
		}

		$data['trn_type']           = $trn_type;
		$data['parent_source_id']   = $parent_source_id == 'no_parent' ? null : $parent_source_id;
		$data['source_name']        = $parent_source_id == 'no_parent' ? $source_name : null;
		$data['loan_or_investment'] = $loan_or_investment;
		$data['amount']             = $amount;
		$data['entry_date']         = $entry_date;
		$data['remarks']            = $remarks;

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

	/**
	 * Handle loan investment search form
	 *
	 * @return void
	 */
	public function search_loan_investment_form_handler() {
		
		if ( ! isset( $_POST['submit_search_loan_investment'] ) ) {
			return;
		}
		
		if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'search_loan_investment' ) ) {
			wp_die( 'Are you cheating?' );
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( 'Are you cheating?' );
		}

		$start_date          = isset( $_POST['start_date'] ) && $_POST['start_date'] ? sanitize_text_field( $_POST['start_date'] ) : '1972-12-30';
		$end_date            = isset( $_POST['end_date'] ) && $_POST['end_date'] ? sanitize_text_field( $_POST['end_date'] ) : date('Y-m-d');
		$loan_investment_id  = isset( $_POST['loan_investment_id'] ) ? sanitize_textarea_field( $_POST['loan_investment_id'] ) : '';
		$trn_type            = isset( $_POST['trn_type'] ) ? sanitize_textarea_field( $_POST['trn_type'] ) : '';
		$page                = $_GET['page'];

		$this->prev_data['loan_investment_id'] = $loan_investment_id;
		$this->prev_data['trn_type'] = $trn_type;

		if ( !empty( $start_date ) ) {
			$this->prev_data['start_date'] = $start_date;
		}

		if ( !empty( $end_date ) ) {
			$this->prev_data['end_date'] = $end_date;
		}

		if ( strtotime( $start_date ) > strtotime( $end_date ) ) {
			$this->errors['greater_start_date'] = __( 'Start date should not be less than end date.', 'wpcodal-pf' );
		}

		if ( ! empty( $this->errors ) ) {
			return;
		}

		$date_range      = "start_date={$_POST['start_date']}&end_date={$_POST['end_date']}";
		$extra_parameter = "$date_range&trn_type={$trn_type}&loan_investment_id={$loan_investment_id}";
		$redirected_to   = admin_url( "admin.php?page={$page}&{$extra_parameter}" );
		wp_redirect( $redirected_to );
		exit;
	}

	/**
	 * Delete loan investment.
	 *
	 * @return void
	 */
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

	/**
	 * Invest or pay loan capability check.
	 * @param string $entry_date
	 * @param int $submit_amount
	 *
	 * @return boolean
	 */
	public function investment_or_loan_pay_capability_check( $entry_date, $submit_amount ) {
		$total_income       = wpcpf_total_income_till_given_date( $entry_date );
		$total_expense      = wpcpf_total_expense_till_given_date( $entry_date );
		$loan_recieve       = wpcpf_total_loan_recieve( $entry_date );
		$investment_earning = wpcpf_total_investment_earning( $entry_date );
		$loan_pay           = wpcpf_total_loan_pay( $entry_date );
		$investment         = wpcpf_total_investment( $entry_date );
		// total in amount
		$total_income             = $total_income ? $total_income->total_income : 0;
		$total_loan_recieve       = $loan_recieve ? $loan_recieve->total_amount : 0;
		$total_investment_earning = $investment_earning ? $investment_earning->total_amount : 0;
		// total out amount
		$total_expense    = $total_expense ? $total_expense->total_expense : 0;
		$total_loan_pay   = $loan_pay ? $loan_pay->total_amount : 0;
		$total_investment = $investment ? $investment->total_amount : 0;

		$total_in_amount  = $total_income + $total_loan_recieve + $total_investment_earning;
		$total_out_amount = $total_expense + $total_loan_pay + $total_investment;

		if ( isset($_POST['amount_by_id'] ) && $_POST['trn_type'] == 2 ) {
			$total_in_amount  = $total_income + $total_loan_recieve + $total_investment_earning - $_POST['amount_by_id'];
		}

		if ( $total_in_amount < $total_out_amount + $submit_amount ) {
			$this->loan_investment_validation_info['total_income']               = $total_income;
			$this->loan_investment_validation_info['loan_recieve_amount']        = $total_loan_recieve;
			$this->loan_investment_validation_info['investment_earning_amount']  = $total_investment_earning;
			$this->loan_investment_validation_info['total_expense_amount']       = $total_expense;
			$this->loan_investment_validation_info['loan_pay_amount']            = $total_loan_pay;
			$this->loan_investment_validation_info['investment_amount']          = $total_investment;
			$this->loan_investment_validation_info['total_in_amount']            = $total_in_amount;
			$this->loan_investment_validation_info['total_out_amount']           = $total_out_amount;
			$this->loan_investment_validation_info['total_in_hand']              = $total_in_amount - $total_out_amount;
			$this->loan_investment_validation_info['submit_amount']              = $submit_amount;

			if ( isset($_POST['amount_by_id'] ) && $_POST['trn_type'] == 2 ) {
				$this->loan_investment_validation_info['loan_recieve_amount']    = $total_loan_recieve - $_POST['amount_by_id'];
			}

			return false;
		}
		
		return true;
	}
}