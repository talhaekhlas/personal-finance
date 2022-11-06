<?php
/**
 * All necessary income expense functions will be here.
 *
 * @package WPCPF
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Fetch income list.
 *
 * @return object
 */
function wpcpf_get_income() {
	global $wpdb;
	$order_by = 'id';
	$order    = 'desc';

	$sql = $wpdb->prepare(
		"SELECT * FROM {$wpdb->prefix}income_expenses
		WHERE income_sector_id IS NOT NULL 
		ORDER BY %s %s ", $order_by, $order
	);

	$items = $wpdb->get_results( $sql );

	return $items;
}

/**
 * Fetch expene list.
 *
 * @return object
 */
function wpcpf_get_expense() {
	global $wpdb;
	$order_by = 'id';
	$order    = 'desc';

	$sql = $wpdb->prepare(
		"SELECT {$wpdb->prefix}income_expenses.id,
		{$wpdb->prefix}income_expenses.amount,
		{$wpdb->prefix}income_expenses.entry_date,
		{$wpdb->prefix}income_expenses.remarks,
		{$wpdb->prefix}income_expense_sectors.name
		FROM {$wpdb->prefix}income_expenses
		INNER JOIN {$wpdb->prefix}budget_for_expenses
			ON {$wpdb->prefix}income_expenses.budget_for_expense_id = {$wpdb->prefix}budget_for_expenses.id
		INNER JOIN {$wpdb->prefix}income_expense_sectors
			ON {$wpdb->prefix}budget_for_expenses.expense_sector_id = {$wpdb->prefix}income_expense_sectors.id
		WHERE {$wpdb->prefix}income_expenses.budget_for_expense_id IS NOT NULL 
		ORDER BY %s %s ", $order_by, $order
	);

	$items = $wpdb->get_results( $sql );

	return $items;
}


/**
 * Fetch budget list for expense.
 *
 * @return object
 */
function wpcpf_get_budget_list_for_expense() {
	global $wpdb;
	$order_by = 'id';
	$order    = 'desc';

	return $wpdb->get_results(
		$wpdb->prepare( "SELECT {$wpdb->prefix}budget_for_expenses.id as budget_for_expense_id,
		{$wpdb->prefix}income_expense_sectors.name as expense_sector_name,
		{$wpdb->prefix}budget_for_expenses.expense_sector_id
		FROM {$wpdb->prefix}budget_for_expenses 
		INNER JOIN {$wpdb->prefix}income_expense_sectors
			ON {$wpdb->prefix}budget_for_expenses.expense_sector_id = {$wpdb->prefix}income_expense_sectors.id
			ORDER BY %s %s ", $order_by, $order
	) );
}


/**
 * Insert a new expense budget.
 *
 * @param  array  $args
 * @param string $page
 *
 * @return int|WP_Error
 */
function wpcpf_insert_income_expense( $args = [], $page ) {
	global $wpdb;

	$validation = mandatory_field_validation( $args, $page );
	if ( !$validation ) {
		return;
	}

	$data = default_format( $args );

	$inserted = $wpdb->insert(
		$wpdb->prefix . 'income_expenses',
		$data,
		[
			'%d',
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
 * Update expense budget.
 *
 * @param  array  $args
 * @param int $id
 * @param string $page
 *
 * @return int|WP_Error
 */
function wpcpf_update_income_expense( $args = [], $id, $page ) {
	global $wpdb;

	$validation = mandatory_field_validation( $args, $page );
	if ( !$validation ) {
		return;
	}

	$data = default_format( $args );

	$updated = $wpdb->update(
		$wpdb->prefix . 'income_expenses',
		$data,
		[ 'id' => $id ],
		[
			'%d',
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
 * Fetch a single income expense
 *
 * @param  int $id
 *
 * @return object
 */
function wpcpf_get_single_income_expense( $id ) {
	global $wpdb;

	return $wpdb->get_row(
		$wpdb->prepare( "SELECT * FROM {$wpdb->prefix}income_expenses WHERE id = %d", $id )
	);
}

/**
 * Not Clear. 
 *
 * @param  int $id
 *
 * @return object
 */
function wpcpf_check_income_data_in_this_range( $expense_sector_id, $start_date, $id ) {
	global $wpdb;
	
	if ( ! $id ) {
		return $wpdb->get_row(
			$wpdb->prepare( "SELECT * FROM {$wpdb->prefix}budget_for_expenses WHERE end_date >= %s AND expense_sector_id=%d", $start_date, $expense_sector_id
		));
	}

	return $wpdb->get_row(
		$wpdb->prepare( "SELECT * FROM {$wpdb->prefix}budget_for_expenses WHERE end_date >= %s AND expense_sector_id=%d AND id!=%d", $start_date, $expense_sector_id, $id
	));
	
}

/**
 * Fetch total income till given date.
 *
 * @param  string $date
 *
 * @return object
 */
function wpcpf_total_income_till_given_date( $date ) {
	global $wpdb;
	return $wpdb->get_row(
		$wpdb->prepare( "SELECT sum(amount) as total_income FROM {$wpdb->prefix}income_expenses WHERE entry_date <= %s AND income_sector_id is not null", $date
	));
	
}

/**
 * Fetch total expense till given date.
 *
 * @param  string $date
 *
 * @return object
 */
function wpcpf_total_expense_till_given_date( $date ) {
	global $wpdb;
	return $wpdb->get_row(
		$wpdb->prepare( "SELECT sum(amount) as total_expense FROM {$wpdb->prefix}income_expenses WHERE entry_date <= %s AND budget_for_expense_id is not null", $date
	));
	
}

/**
 * Delete income.
 *
 * @param  int $id
 *
 * @return int|boolean
 */
function delete_income( $id ) {
	global $wpdb;
	return $wpdb->delete(
		$wpdb->prefix . 'income_expenses',
		[ 'id' => $id ],
		[ '%d' ]
	);
}

/**
 * Expense by budget id.
 *
 * @param  int $budget_id.
 *
 * @return object
 */
function wpcpf_expense_by_budget_id( $budget_id ) {
	global $wpdb;
	return $wpdb->get_row(
		$wpdb->prepare( "SELECT sum(amount) as total_expense FROM {$wpdb->prefix}income_expenses WHERE budget_for_expense_id = %d", $budget_id
	));
	
}

/**
 * Fetch income data.
 *
 * @param  int $start_date.
 * @param  int $end_date.
 * @param  int $income_sector_id.
 * @return object
 */
function wpcpf_get_income_data( $start_date = null, $end_date = null, $income_sector_id = null ) {
	global $wpdb;

	$start_date = $start_date ? $start_date : '1972-12-30';
	$end_date = $end_date ? $end_date : date('Y-m-d');

	$common_sql = "SELECT {$wpdb->prefix}income_expenses.id,
	{$wpdb->prefix}income_expenses.amount,
	{$wpdb->prefix}income_expenses.entry_date,
	{$wpdb->prefix}income_expenses.remarks,
	{$wpdb->prefix}income_expense_sectors.name,
	{$wpdb->prefix}income_expense_sectors.id as income_expense_sector_id
	FROM {$wpdb->prefix}income_expenses
	INNER JOIN {$wpdb->prefix}income_expense_sectors
		ON {$wpdb->prefix}income_expenses.income_sector_id = {$wpdb->prefix}income_expense_sectors.id";

	if ( $income_sector_id == 'All' || !$income_sector_id ) {
		$sql = $wpdb->prepare(
			"{$common_sql}
			WHERE {$wpdb->prefix}income_expenses.entry_date >= %s 
			AND {$wpdb->prefix}income_expenses.entry_date <= %s", 
			$start_date, $end_date
		);
	}

	if ( $income_sector_id != 'All' && $income_sector_id ) {
		$sql = $wpdb->prepare(
			"{$common_sql}
			WHERE {$wpdb->prefix}income_expenses.entry_date >= %s 
			AND {$wpdb->prefix}income_expenses.entry_date <= %s
			AND {$wpdb->prefix}income_expenses.income_sector_id = %d", 
			$start_date, $end_date, $income_sector_id
		);
	}
	
	$items = $wpdb->get_results( $sql );

	return $items;
	
}

/**
 * Fetch expene list.
 *
 * @param  string $start_date
 * @param string $end_date
 * @param array $budget_ids_by_expense_sector
 *
 * @return array
 */
function wpcpf_get_expense_data( $start_date = null, $end_date = null, $budget_ids_by_expense_sector, $expense_sector_id ) {
	global $wpdb;
	$start_date = $start_date ? $start_date : '1972-12-30';
	$end_date   = $end_date ? $end_date : date('Y-m-d');

	$common_sql = "SELECT {$wpdb->prefix}income_expenses.id,
	{$wpdb->prefix}income_expenses.amount,
	{$wpdb->prefix}income_expenses.entry_date,
	{$wpdb->prefix}income_expenses.remarks,
	{$wpdb->prefix}income_expense_sectors.name
	FROM {$wpdb->prefix}income_expenses
	INNER JOIN {$wpdb->prefix}budget_for_expenses
		ON {$wpdb->prefix}income_expenses.budget_for_expense_id = {$wpdb->prefix}budget_for_expenses.id
	INNER JOIN {$wpdb->prefix}income_expense_sectors
		ON {$wpdb->prefix}budget_for_expenses.expense_sector_id = {$wpdb->prefix}income_expense_sectors.id";

	if ( !count($budget_ids_by_expense_sector)) {
		$sql = $wpdb->prepare(
			"{$common_sql}
			WHERE {$wpdb->prefix}income_expenses.entry_date >= %s 
			AND {$wpdb->prefix}income_expenses.entry_date <= %s
			AND {$wpdb->prefix}income_expenses.budget_for_expense_id IS NOT NULL", 
			$start_date, $end_date
		);
	}

	if ( count($budget_ids_by_expense_sector)) {
		$budget_ids = implode(",",$budget_ids_by_expense_sector);
		$sql = $wpdb->prepare(
			"{$common_sql}
			WHERE {$wpdb->prefix}income_expenses.entry_date >= %s 
			AND {$wpdb->prefix}income_expenses.entry_date <= %s
			AND {$wpdb->prefix}income_expenses.budget_for_expense_id IS NOT NULL
			AND {$wpdb->prefix}income_expenses.budget_for_expense_id IN ($budget_ids)", 
			$start_date, $end_date
		);
	}

	$items = $wpdb->get_results( $sql );

	return $items;
}

/**
 * Mandatory field validation.
 *
 * @param  array $args
 * @param string $page
 *
 * @return boolean
 */
function mandatory_field_validation( $args, $page ) {
	if ( $page == 'income' && empty( $args['income_sector_id'] ) ) {
		new \WP_Error( 'no-income-sector', __( 'You must provide income sector name.', 'wpcodal-pf' ) );
		return false;
	}

	if ( $page == 'expense' && empty( $args['budget_for_expense_id'] ) ) {
		new \WP_Error( 'no-expense-sector', __( 'You must provide expense sector name.', 'wpcodal-pf' ) );
		return false;
	}

	if ( empty( $args['amount'] ) ) {
		new \WP_Error( 'no-income-amount', __( 'You must provide income amount.', 'wpcodal-pf' ) );
		return false;
	}

	if ( empty( $args['entry_date'] ) ) {
		new \WP_Error( 'no-entry-date', __( 'You must provide entry date.', 'wpcodal-pf' ) );
		return false;
	}

	if ( empty( $args['remarks'] ) ) {
		new \WP_Error( 'no-expense-budget-remarks', __( 'You must provide remarks.', 'wpcodal-pf' ) );
		return false;
	}
	return true;
}

/**
 * Default Format.
 *
 * @param  array $args
 *
 * @return array
 */
function default_format( $args ) {
	$defaults = [
		'income_sector_id'      => null,
		'budget_for_expense_id' => null,
		'amount'                => 0,
		'entry_date'            => null,
		'remarks'               => '',
		'created_by'            => get_current_user_id(),
	];

	return wp_parse_args( $args, $defaults );
}