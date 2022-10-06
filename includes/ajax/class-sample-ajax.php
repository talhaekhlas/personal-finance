<?php
/**
 * Sample_Ajax class.
 *
 * @package WPCPF
 */

namespace WPCPF\Ajax;

use WPCPF\Traits\Singleton;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Load sample ajax functionality inside this class.
 */
class Sample_Ajax {

	use Singleton;

	/**
	 * Constructor of Bootstrap class.
	 */
	private function __construct() {
		add_action( 'wp_ajax_sample_action', array( $this, 'sample_action' ) );
		add_action( 'wp_ajax_nopriv_sample_action', array( $this, 'sample_action' ) );

		add_action( 'wp_ajax_expense_budget_id_by_date', array( $this, 'expense_budget_id_by_date' ) );
		add_action( 'wp_ajax_nopriv_expense_budget_id_by_date', array( $this, 'expense_budget_id_by_date' ) );
	}

	/**
	 * Run a sample action.
	 */
	public function sample_action() {
		echo 'This is a sample action.';

		wp_die();
	}

	/**
	 * Expense budget data by date.
	 */
	public function expense_budget_id_by_date() {
		$data                      = isset( $_POST['data'] ) ? wp_unslash( $_POST['data'] ) : null;
		$entry_date                = $data['entry_date'];
		$expense_budget_id_by_date = wpcpf_expense_budget_id_by_date( $entry_date );
		wp_send_json_success( $expense_budget_id_by_date );

		wp_die();
	}
}
