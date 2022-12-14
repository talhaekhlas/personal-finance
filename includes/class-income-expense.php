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
 * Income expense class.
 */
class Income_Expense {

	use Singleton;

	/**
	 * Constructor of Bootstrap class.
	 */
	private function __construct() {
		session_start();
		$this->income_expense_form_handler();
		$this->search_income_expense_form_handler();
		$this->delete_income();
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
		$start_date              = isset( $_GET['start_date'] ) ? $_GET['start_date'] : null;
		$end_date                = isset( $_GET['end_date'] ) ? $_GET['end_date'] : null;
		$income_sector_id        = isset( $_GET['income_sector_id'] ) ? $_GET['income_sector_id'] : null;
		$expense_sector_id       = isset( $_GET['expense_sector_id'] ) ? $_GET['expense_sector_id'] : null;
		$income_sectors          = wpcpf_get_income_expense_sector( 1 ); // 1 means income sector.
		$budget_list_for_expense = wpcpf_get_budget_list_for_expense();

		$active_expense_ids = [];
		foreach ($budget_list_for_expense as $value) {
			$active_expense_ids[$value->expense_sector_id] = $value->expense_sector_name;
		}

		$active_expense_ids = array_unique( $active_expense_ids );

		$income_sector_by_id     = [];

		foreach ($income_sectors as $value) {
			$income_sector_by_id[$value->id] = $value->name;
		}

		switch ( $action ) {
			case 'new':
				$template        = WPCPF_PLUGIN_DIR . '/templates/income-expense/create.php';
				break;
			case 'edit':
				$id            = isset( $_GET['id'] ) ? $_GET['id'] : null;
				$single_income_expense = wpcpf_get_single_income_expense( $id );
				if ( $page == 'expense' ) {
					$expense_budget_id_by_date = wpcpf_expense_budget_id_by_date( $single_income_expense->entry_date );
				}
				$template = WPCPF_PLUGIN_DIR . '/templates/income-expense/edit.php';
				break;
			case 'view':
				$template = __DIR__ . '/views/income-expense-sector/view.php';
				break;
			default:
				$data = $page == 'income' ? wpcpf_get_income() : wpcpf_get_expense();
				if ( $page == 'income' ) {
					$data = wpcpf_get_income_data( $start_date, $end_date, $income_sector_id );
				}
				if ( $page == 'expense' ) {
					$budgets_by_expense_sector = wpcpf_budgets_by_expense_sector($expense_sector_id);
					$budget_ids_by_expense_sector = [];
					foreach ( $budgets_by_expense_sector as $value) {
						$budget_ids_by_expense_sector[] = $value->id; 
					}
					
					$data = wpcpf_get_expense_data( $start_date, $end_date, $budget_ids_by_expense_sector, $expense_sector_id );
				}
				
				$template = WPCPF_PLUGIN_DIR . '/templates/income-expense/list.php';
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
	public function income_expense_form_handler() {
		
		if ( ! isset( $_POST['submit_income_expense'] ) ) {
			return;
		}
		
		if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'income_expense' ) ) {
			wp_die( 'Are you cheating?' );
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( 'Are you cheating?' );
		}

		$income_sector_id       = isset( $_POST['income_sector_id'] ) ? sanitize_text_field( $_POST['income_sector_id'] ) : '';
		$budget_for_expense_id  = isset( $_POST['budget_for_expense_id'] ) ? sanitize_text_field( $_POST['budget_for_expense_id'] ) : '';
		$amount                 = isset( $_POST['amount'] ) ? sanitize_textarea_field( $_POST['amount'] ) : '';
		$entry_date             = isset( $_POST['entry_date'] ) ? sanitize_textarea_field( $_POST['entry_date'] ) : '';
		$remarks                = isset( $_POST['remarks'] ) ? sanitize_textarea_field( $_POST['remarks'] ) : '';
		$id                     = isset( $_POST['id'] ) ? sanitize_textarea_field( $_POST['id'] ) : null;
		$page                   = $_GET['page'];
		
		if ( $page == 'income' && empty( $income_sector_id ) ) {
			$this->errors['income_sector_id'] = __( 'Please Provide Income Sector', 'wpcodal-pf' );
		} else {
			$this->prev_data['income_sector_id'] = $income_sector_id;
		}

		if ( $page == 'expense' && empty( $budget_for_expense_id ) ) {
			$this->errors['budget_for_expense_id'] = __( 'Please Provide Expense Sector', 'wpcodal-pf' );
		} else {
			$this->prev_data['budget_for_expense_id'] = $budget_for_expense_id;
		}

		if ( empty( $amount ) ) {
			$this->errors['amount'] = __( 'Please Provide Amount', 'wpcodal-pf' );
		} else {
			$this->prev_data['amount'] = $amount;
		}

		if ( empty( $entry_date ) ) {
			$this->errors['entry_date'] = __( 'Please Provide Date', 'wpcodal-pf' );
		} else {
			$this->prev_data['entry_date'] = $entry_date;
		}

		if ( empty( $remarks ) ) {
			$this->errors['remarks'] = __( 'Please Provide Remarks', 'wpcodal-pf' );
		} else {
			$this->prev_data['remarks'] = $remarks;
		}

		if ( strtotime( $entry_date ) > strtotime("now") ) {
			$this->errors['greater_entry_date'] = __( 'Entry date should not be greater than present date.', 'wpcodal-pf' );
		}

		if ( ! empty( $this->errors ) ) {
			return;
		}

		if ( $page =='income' ) {
			$data['income_sector_id'] = $income_sector_id;
		} else {
			$expense_validation = $this->expense_validation( $budget_for_expense_id, $entry_date, $amount );
			if ( ! $expense_validation ) {
				return;
			}
			$data['budget_for_expense_id'] = $budget_for_expense_id;
		}

		$data['amount']     = $amount;
		$data['entry_date'] = $entry_date;
		$data['remarks']    = $remarks;

		if ( ! $id ) {
			$insert_id = wpcpf_insert_income_expense( $data, $page );
	
			if ( is_wp_error( $insert_id ) ) {
				wp_die( $insert_id->get_error_message() );
			}

			$redirected_to = admin_url( "admin.php?page={$page}&inserted_{$page}=true" );
		} else {
			$update_data = wpcpf_update_income_expense( $data, $id, $page );
	
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
	 * Handle income expense search form.
	 *
	 * @return void
	 */
	public function search_income_expense_form_handler() {
		
		if ( ! isset( $_POST['submit_search_income_expense'] ) ) {
			return;
		}
		
		if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'search_income_expense' ) ) {
			wp_die( 'Are you cheating?' );
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( 'Are you cheating?' );
		}

		$start_date        = isset( $_POST['start_date'] ) && $_POST['start_date'] ? sanitize_text_field( $_POST['start_date'] ) : '1972-12-30';
		$end_date          = isset( $_POST['end_date'] ) && $_POST['end_date'] ? sanitize_text_field( $_POST['end_date'] ) : date('Y-m-d');
		$expense_sector_id = isset( $_POST['expense_sector_id'] ) ? sanitize_textarea_field( $_POST['expense_sector_id'] ) : '';
		$income_sector_id  = isset( $_POST['income_sector_id'] ) ? sanitize_textarea_field( $_POST['income_sector_id'] ) : '';
		$page              = $_GET['page'];

		if ( $page == 'income' ) {
			$this->prev_data['income_sector_id'] = $income_sector_id;
		}

		if ( $page == 'expense' ) {
			$this->prev_data['expense_sector_id'] = $expense_sector_id;
		}

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

		$date_range = "start_date={$_POST['start_date']}&end_date={$_POST['end_date']}";
		if ( $page == 'income' ) {
			$extra_parameter = "$date_range&income_sector_id={$income_sector_id}";
		} else {
			$extra_parameter = "$date_range&expense_sector_id={$expense_sector_id}";
		}

		$redirected_to = admin_url( "admin.php?page={$page}&{$extra_parameter}" );
		wp_redirect( $redirected_to );
		exit;
	}

	/**
	 * Delete income.
	 *
	 * @return void
	 */
	public function delete_income() {

		if ( !isset( $_REQUEST['delete_expense_action'] ) &&  !isset( $_REQUEST['delete_income_action'] ) ) {
			return;
		}

		if ( !wp_verify_nonce( $_REQUEST['_wpnonce'], 'wpcpf-delete-income' ) && !wp_verify_nonce( $_REQUEST['_wpnonce'], 'wpcpf-delete-expense' ) ) {
			wp_die( 'Are you cheating?' );
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( 'Are you cheating?' );
		}

		$id   = isset( $_REQUEST['id'] ) ? intval( $_REQUEST['id'] ) : 0;
		$page = isset( $_REQUEST['page'] ) ? $_REQUEST['page'] : 'income_sector';

		if ( delete_income( $id ) ) {
			$redirected_to = admin_url( "admin.php?page={$page}&income_deleted=true" );
		} else {
			$redirected_to = admin_url( "admin.php?page={$page}&{$page}_deleted_failed=true" );
		}
		$_SESSION["alert_message"] = true;
		wp_redirect( $redirected_to );
		exit;
	}

	/**
	 * Expense validation.
	 * @param int $budget_for_expense_id
	 * @param string $entry_date
	 * @param int $amount
	 *
	 * @return boolean
	 */
	public function expense_validation( $budget_for_expense_id, $entry_date, $amount ) {
		$expense_by_budget_id = wpcpf_expense_by_budget_id( $budget_for_expense_id );
		$single_budget        = wpcpf_get_single_expense_budget( $budget_for_expense_id );
		$loan_recieve         = wpcpf_total_loan_recieve( $entry_date );
		$loan_pay             = wpcpf_total_loan_pay( $entry_date );
		$investment           = wpcpf_total_investment( $entry_date );
		$investment_earning   = wpcpf_total_investment_earning( $entry_date );
		$total_income         = wpcpf_total_income_till_given_date( $entry_date );
		$total_expense        = wpcpf_total_expense_till_given_date( $entry_date, $budget_for_expense_id);
		$budget_ids_by_date   = wpcpf_expense_budget_id_by_date($entry_date);

		$total_expense_from_budget_amount = $expense_by_budget_id ? $expense_by_budget_id->total_expense : 0;
		$total_income_amount              = $total_income ? $total_income->total_income : 0;
		$budget_amount                    = $single_budget ? $single_budget->amount : 0;
		$loan_recieve_amount              = $loan_recieve ? $loan_recieve->total_amount : 0;
		$loan_pay_amount                  = $loan_pay ? $loan_pay->total_amount : 0;
		$investment_amount                = $investment ? $investment->total_amount : 0;
		$investment_earning_amount        = $investment_earning ? $investment_earning->total_amount : 0;
		$total_expense_amount             = $total_expense ? $total_expense->total_expense : 0;
		$total_in_amount                  = $total_income_amount + $loan_recieve_amount + $investment_earning_amount;
		$total_out_amount                 = $total_expense_amount + $loan_pay_amount + $investment_amount;

		if ( $budget_amount < ($total_expense_from_budget_amount + $amount)) {
			$this->expense_validation_info['budget_amount']                    = $budget_amount;
			$this->expense_validation_info['total_expense_from_budget_amount'] = $total_expense_from_budget_amount;
			$this->expense_validation_info['submit_amount']                    = $amount;
			$this->expense_validation_info['expense_sector_name']              = $single_budget->expense_sector_name;
			$this->expense_validation_info['budgets_by_date']                  = $budget_ids_by_date;
			$this->expense_validation_info['prev_budget_id']                   = $budget_for_expense_id;
			return false;
		}

		if( $total_in_amount < $total_out_amount + $amount ) {
			$this->expense_validation_info['total_income']               = $total_income_amount;
			$this->expense_validation_info['loan_recieve_amount']        = $loan_recieve_amount;
			$this->expense_validation_info['investment_earning_amount']  = $investment_earning_amount;
			$this->expense_validation_info['total_expense_amount']       = $total_expense_amount;
			$this->expense_validation_info['loan_pay_amount']            = $loan_pay_amount;
			$this->expense_validation_info['investment_amount']          = $investment_amount;
			$this->expense_validation_info['total_in_amount']            = $total_in_amount;
			$this->expense_validation_info['total_out_amount']           = $total_out_amount;
			$this->expense_validation_info['total_in_hand']              = $total_in_amount - $total_out_amount;
			$this->expense_validation_info['submit_amount']              = $amount;
			$this->expense_validation_info['budgets_by_date']            = $budget_ids_by_date;
			$this->expense_validation_info['prev_budget_id']             = $budget_for_expense_id;
			return false;
		}
		return true;
	}
}