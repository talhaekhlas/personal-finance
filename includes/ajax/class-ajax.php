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
	 * Constructor of Sample_Ajax class.
	 */
	private function __construct() {
		add_action( 'wp_ajax_expense_budget_id_by_date', array( $this, 'expense_budget_id_by_date' ) );
		add_action( 'wp_ajax_nopriv_expense_budget_id_by_date', array( $this, 'expense_budget_id_by_date' ) );
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
