<?php
/**
 * All necessary custom functions will be here.
 *
 * @package WPCPF
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// /**
//  * Fetch expense list.
//  *
//  * @param  array  $args
//  *
//  * @return array
//  */
// function wpcpf_get_income1() {
//     global $wpdb;
//     $order_by = 'id';
//     $order    = 'desc';

//     $sql = $wpdb->prepare(
//             "SELECT * FROM {$wpdb->prefix}income_expenses
//             WHERE income_sector_id IS NOT NULL 
//             ORDER BY %s %s ", $order_by, $order
//     );

//     $items = $wpdb->get_results( $sql );

//     return $items;
// }

// /**
//  * Fetch expene list.
//  *
//  * @param  array  $args
//  *
//  * @return array
//  */
// function wpcpf_get_expense1() {
//     global $wpdb;
//     $order_by = 'id';
//     $order    = 'desc';

//     $sql = $wpdb->prepare(
//             "SELECT {$wpdb->prefix}income_expenses.id,
//             {$wpdb->prefix}income_expenses.amount,
//             {$wpdb->prefix}income_expenses.entry_date,
//             {$wpdb->prefix}income_expenses.remarks,
//             {$wpdb->prefix}income_expense_sectors.name
//             FROM {$wpdb->prefix}income_expenses
//             INNER JOIN {$wpdb->prefix}budget_for_expenses
//                 ON {$wpdb->prefix}income_expenses.budget_for_expense_id = {$wpdb->prefix}budget_for_expenses.id
//             INNER JOIN {$wpdb->prefix}income_expense_sectors
//                 ON {$wpdb->prefix}budget_for_expenses.expense_sector_id = {$wpdb->prefix}income_expense_sectors.id
//             WHERE {$wpdb->prefix}income_expenses.budget_for_expense_id IS NOT NULL 
//             ORDER BY %s %s ", $order_by, $order
//     );

//     $items = $wpdb->get_results( $sql );

//     return $items;
// }

// /**
//  * Fetch expense list.
//  *
//  * @param  array  $args
//  *
//  * @return array
//  */
// function wpcpf_get_budget_list_for_expense1() {
//     global $wpdb;
//     $order_by = 'id';
//     $order    = 'desc';

//     $sql = $wpdb->prepare(
//             "SELECT * FROM {$wpdb->prefix}budget_for_expenses
//             INNER JOIN {$wpdb->prefix}income_expense_sectors
//             ON {$wpdb->prefix}budget_for_expenses.expense_sector_id = {$wpdb->prefix}income_expense_sectors.id
//             ORDER BY %s %s ", $order_by, $order
//     );

//     $items = $wpdb->get_results( $sql );

//     return $items;
// }


/**
 * Insert a new loand investment.
 *
 * @param  array  $args
 * @param string $page
 *
 * @return int|WP_Error
 */
function wpcpf_insert_loan_investment( $args = [], $page ) {
	global $wpdb;

	if ( $page == 'income' && empty( $args['income_sector_id'] ) ) {
		return new \WP_Error( 'no-income-sector', __( 'You must provide income sector name.', 'wpcodal-pf' ) );
	}

	if ( $page == 'expense' && empty( $args['budget_for_expense_id'] ) ) {
		return new \WP_Error( 'no-income-sector', __( 'You must provide expense sector name.', 'wpcodal-pf' ) );
	}

	if ( empty( $args['amount'] ) ) {
		return new \WP_Error( 'no-income-amount', __( 'You must provide income amount.', 'wpcodal-pf' ) );
	}

	if ( empty( $args['entry_date'] ) ) {
		return new \WP_Error( 'no-entry-date', __( 'You must provide entry date.', 'wpcodal-pf' ) );
	}

	if ( empty( $args['remarks'] ) ) {
		return new \WP_Error( 'no-expense-budget-remarks', __( 'You must provide remarks.', 'wpcodal-pf' ) );
	}

	$defaults = [
		'trn_type'           => null,
		'parent_source_id'   => null,
		'source_name'        => null,
		'loan_or_investment' => null,
		'amount'             => 0,
		'entry_date'         => null,
		'remarks'            => '',
		'created_by'         => get_current_user_id(),
	];

	$data = wp_parse_args( $args, $defaults );

	$inserted = $wpdb->insert(
		$wpdb->prefix . 'loan_investments',
		$data,
		[
			'%d',
			'%d',
			'%s',
			'%d',
			'%d',
			'%s',
			'%s',
			'%d',
		]
	);

	if ( ! $inserted ) {
		return new \WP_Error( 'failed-to-insert', __( 'Failed to insert data1', 'wpcodal-pf' ) );
	}

	return $wpdb->insert_id;
}

/**
 * Update loan investment.
 *
 * @param  array  $args
 * @param string $page
 * @param int $id
 *
 * @return int|WP_Error
 */
function wpcpf_update_loan_investment( $args = [], $page, $id ) {
	global $wpdb;

	if ( $page == 'income' && empty( $args['income_sector_id'] ) ) {
		return new \WP_Error( 'no-income-sector', __( 'You must provide income sector name.', 'wpcodal-pf' ) );
	}

	if ( $page == 'expense' && empty( $args['budget_for_expense_id'] ) ) {
		return new \WP_Error( 'no-income-sector', __( 'You must provide expense sector name.', 'wpcodal-pf' ) );
	}

	if ( empty( $args['amount'] ) ) {
		return new \WP_Error( 'no-income-amount', __( 'You must provide income amount.', 'wpcodal-pf' ) );
	}

	if ( empty( $args['entry_date'] ) ) {
		return new \WP_Error( 'no-entry-date', __( 'You must provide entry date.', 'wpcodal-pf' ) );
	}

	if ( empty( $args['remarks'] ) ) {
		return new \WP_Error( 'no-expense-budget-remarks', __( 'You must provide remarks.', 'wpcodal-pf' ) );
	}

	$defaults = [
		'trn_type'           => null,
		'parent_source_id'   => null,
		'source_name'        => null,
		'loan_or_investment' => null,
		'amount'             => 0,
		'entry_date'         => null,
		'remarks'            => '',
		'created_by'         => get_current_user_id(),
	];

	$data = wp_parse_args( $args, $defaults );

	$updated = $wpdb->update(
		$wpdb->prefix . 'loan_investments',
		$data,
		[ 'id' => $id ],
		[
			'%d',
			'%d',
			'%s',
			'%d',
			'%d',
			'%s',
			'%s',
			'%d',
		],
		[ '%d' ]
	);

	return $updated;
}

/**
 * Fetch a single loan investment from the DB
 *
 * @param  int $id
 *
 * @return object
 */
function wpcpf_get_single_loan_investment( $id ) {
	global $wpdb;

	return $wpdb->get_row(
		$wpdb->prepare( "SELECT * FROM {$wpdb->prefix}loan_investments WHERE id = %d", $id )
	);
}

/**
 * Check parent or not
 *
 * @param  int $id
 *
 * @return object
 */
function wpcpf_get_is_parent_check( $id ) {
	global $wpdb;

	return $wpdb->get_results(
		$wpdb->prepare( "SELECT * FROM {$wpdb->prefix}loan_investments WHERE parent_source_id = %d", $id )
	);
}

/**
 * Fetch a single expense budget from the DB
 *
 * @param  int $id
 *
 * @return object
 */
// function wpcpf_check_income_data_in_this_range1( $expense_sector_id, $start_date, $id ) {
//     global $wpdb;
//     $date    = new DateTime($start_date);
	
//     if ( ! $id ) {
//         return $wpdb->get_row(
//             $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}budget_for_expenses WHERE end_date >= %s AND expense_sector_id=%d", $start_date, $expense_sector_id
//         ));
//     }

//     return $wpdb->get_row(
//         $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}budget_for_expenses WHERE end_date >= %s AND expense_sector_id=%d AND id!=%d", $start_date, $expense_sector_id, $id
//     ));
	
// }

/**
 * Delete loan investment
 *
 * @param  int $id
 *
 * @return int|boolean
 */
function delete_loan_investment( $id ) {
	global $wpdb;

	return $wpdb->delete(
		$wpdb->prefix . 'loan_investments',
		[ 'id' => $id ],
		[ '%d' ]
	);
}

/**
 * Fetch loan investment list
 *
 * @param  int $type
 * @param int $trn_type
 * @param int $loan_investment_id
 * @param string $start_date
 * @param string $end_date
 *
 * @return array
 */
function wpcpf_get_loan_investment_data( $type, $trn_type, $loan_investment_id, $start_date, $end_date ) {
	global $wpdb;
	$start_date         = $start_date ? $start_date : '1972-12-30';
	$end_date           = $end_date ? $end_date : date('Y-m-d');
	$trn_type           = $trn_type ? $trn_type : 'All';
	$loan_investment_id = $loan_investment_id ? $loan_investment_id : 'All';

	if ( $trn_type == 'All' && $loan_investment_id == 'All' ) {
		return $wpdb->get_results(
			$wpdb->prepare( "SELECT * FROM {$wpdb->prefix}loan_investments 
			WHERE loan_or_investment = %d
			AND entry_date >= %s
			AND entry_date <= %s
			ORDER BY entry_date DESC", $type, $start_date, $end_date )
		);
	} else if ( $trn_type == 'All' && $loan_investment_id !='All' ) {
		return $wpdb->get_results(
			$wpdb->prepare( "SELECT * FROM {$wpdb->prefix}loan_investments 
			WHERE loan_or_investment = %d
			AND entry_date >= %s
			AND entry_date <= %s
			AND id = %d
			ORDER BY entry_date DESC", $type, $start_date, $end_date, $loan_investment_id )
		);
	} else if ( $trn_type != 'All' && $loan_investment_id =='All' ) {
		return $wpdb->get_results(
			$wpdb->prepare( "SELECT * FROM {$wpdb->prefix}loan_investments 
			WHERE loan_or_investment = %d
			AND entry_date >= %s
			AND entry_date <= %s
			AND trn_type = %d
			ORDER BY entry_date DESC", $type, $start_date, $end_date, $trn_type )
		);
	} else {
		return $wpdb->get_results(
			$wpdb->prepare( "SELECT * FROM {$wpdb->prefix}loan_investments 
			WHERE loan_or_investment = %d
			AND entry_date >= %s
			AND entry_date <= %s
			AND trn_type = %d
			AND id = %d
			ORDER BY entry_date DESC", $type, $start_date, $end_date, $trn_type, $loan_investment_id )
		);
	} 
}

/**
 * Fetch parent loan investment data
 *
 * @param  int $type_id
 *
 * @return array
 */
function wpcpf_get_parent_loan_investment_data( $type_id ) {
	global $wpdb;

	return $wpdb->get_results(
		$wpdb->prepare( "SELECT * FROM {$wpdb->prefix}loan_investments 
		WHERE loan_or_investment = %d
		AND source_name IS NOT NULL
		ORDER BY entry_date DESC", $type_id )
	);
}

/**
 * Fetch total loan recieve by till date.
 *
 * @param  string $date
 *
 * @return object
 */
function wpcpf_total_loan_recieve( $date ) {
	global $wpdb;
	return $wpdb->get_row(
		$wpdb->prepare( "SELECT sum(amount) as total_amount FROM {$wpdb->prefix}loan_investments WHERE entry_date <= %s AND trn_type = 1", $date
	));
	
}

/**
 * Fetch total loan pay by till date.
 *
 * @param  string $date
 *
 * @return object
 */
function wpcpf_total_loan_pay( $date ) {
	global $wpdb;
	return $wpdb->get_row(
		$wpdb->prepare( "SELECT sum(amount) as total_amount FROM {$wpdb->prefix}loan_investments WHERE entry_date <= %s AND trn_type = 2", $date
	));
	
}

/**
 * Fetch total investment by till date.
 *
 * @param  string $date
 *
 * @return object
 */
function wpcpf_total_investment( $date ) {
	global $wpdb;
	return $wpdb->get_row(
		$wpdb->prepare( "SELECT sum(amount) as total_amount FROM {$wpdb->prefix}loan_investments WHERE entry_date <= %s AND trn_type = 3", $date
	));
	
}

/**
 * Fetch total investment earning by till date.
 *
 * @param  string $date
 *
 * @return object
 */
function wpcpf_total_investment_earning( $date ) {
	global $wpdb;
	return $wpdb->get_row(
		$wpdb->prepare( "SELECT sum(amount) as total_amount FROM {$wpdb->prefix}loan_investments WHERE entry_date <= %s AND trn_type = 4", $date
	));
	
}

/**
 * Fetch a loan pay and investment
 *
 * @param  string $date
 *
 * @return object
 */
function wpcpf_total_loan_pay_and_investment( $date ) {
	global $wpdb;
	return $wpdb->get_row(
		$wpdb->prepare( "SELECT sum(amount) as total_amount FROM {$wpdb->prefix}loan_investments WHERE entry_date <= %s AND trn_type IN(2,3)", $date
	));
	
}