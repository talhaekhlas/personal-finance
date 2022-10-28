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

/**
 * Fetch budget for expense.
 *
 * @param  array  $args
 *
 * @return array
 */
function wpcpf_get_expense_budget( $start_date, $end_date, $expense_sector_id ) {
    global $wpdb;
    $order_by = 'id';
    $order    = 'DESC';
    $start_date = $start_date ? $start_date : '1972-12-30';
    $end_date   = $end_date ? $end_date : '2072-12-30';

    if ( !$expense_sector_id || $expense_sector_id == 'All' ) {
        $sql = $wpdb->prepare( "SELECT {$wpdb->prefix}budget_for_expenses.id as budget_id,
        {$wpdb->prefix}budget_for_expenses.expense_sector_id,
         {$wpdb->prefix}budget_for_expenses.amount, 
         {$wpdb->prefix}budget_for_expenses.start_date,
         {$wpdb->prefix}budget_for_expenses.end_date,
         {$wpdb->prefix}budget_for_expenses.remarks,
         {$wpdb->prefix}income_expense_sectors.name as expense_sector_name,
         sum({$wpdb->prefix}income_expenses.amount) as total_expense
         FROM {$wpdb->prefix}budget_for_expenses 
        INNER JOIN {$wpdb->prefix}income_expense_sectors
            ON {$wpdb->prefix}budget_for_expenses.expense_sector_id = {$wpdb->prefix}income_expense_sectors.id
        LEFT JOIN {$wpdb->prefix}income_expenses
            ON {$wpdb->prefix}budget_for_expenses.id = {$wpdb->prefix}income_expenses.budget_for_expense_id
        WHERE {$wpdb->prefix}budget_for_expenses.start_date >= %s
        AND  {$wpdb->prefix}budget_for_expenses.end_date <= %s
        GROUP BY {$wpdb->prefix}budget_for_expenses.id", $start_date, $end_date);
    }

    if ( $expense_sector_id && $expense_sector_id != 'All' ) {
        $sql = $wpdb->prepare( "SELECT {$wpdb->prefix}budget_for_expenses.id as budget_id,
        {$wpdb->prefix}budget_for_expenses.expense_sector_id,
         {$wpdb->prefix}budget_for_expenses.amount, 
         {$wpdb->prefix}budget_for_expenses.start_date,
         {$wpdb->prefix}budget_for_expenses.end_date,
         {$wpdb->prefix}budget_for_expenses.remarks,
         {$wpdb->prefix}income_expense_sectors.name as expense_sector_name,
         sum({$wpdb->prefix}income_expenses.amount) as total_expense
         FROM {$wpdb->prefix}budget_for_expenses 
        INNER JOIN {$wpdb->prefix}income_expense_sectors
            ON {$wpdb->prefix}budget_for_expenses.expense_sector_id = {$wpdb->prefix}income_expense_sectors.id
        LEFT JOIN {$wpdb->prefix}income_expenses
            ON {$wpdb->prefix}budget_for_expenses.id = {$wpdb->prefix}income_expenses.budget_for_expense_id
        
        WHERE {$wpdb->prefix}budget_for_expenses.start_date >= %s
        AND  {$wpdb->prefix}budget_for_expenses.end_date <= %s
        AND  {$wpdb->prefix}budget_for_expenses.expense_sector_id <= %d
        GROUP BY {$wpdb->prefix}budget_for_expenses.id", $start_date, $end_date, $expense_sector_id);
    }

    // {$wpdb->prefix}budget_for_expenses.expense_sector_id,
    //      {$wpdb->prefix}budget_for_expenses.amount, 
    //      {$wpdb->prefix}budget_for_expenses.start_date,
    //      {$wpdb->prefix}budget_for_expenses.end_date,
    //      {$wpdb->prefix}budget_for_expenses.remarks,
    //      {$wpdb->prefix}income_expense_sectors.name as expense_sector_name
    //      FROM {$wpdb->prefix}budget_for_expenses 
    //     INNER JOIN {$wpdb->prefix}income_expense_sectors
    //         ON {$wpdb->prefix}budget_for_expenses.expense_sector_id = {$wpdb->prefix}income_expense_sectors.id
    //     WHERE {$wpdb->prefix}budget_for_expenses.id = %d", $id)

    // if ( $start_date && $end_date && $expense_sector_id =='All' ) {
        
    //     $sql = $wpdb->prepare(
    //         "SELECT * FROM {$wpdb->prefix}budget_for_expenses 
    //         WHERE start_date >= %s 
    //         AND end_date <= %s
    //         ORDER BY expense_sector_id ASC, id DESC", $start_date, $end_date
    //     );
    // }

    // if ( $start_date && $end_date && $expense_sector_id !='All' ) {
    //     $sql = $wpdb->prepare(
    //         "SELECT * FROM {$wpdb->prefix}budget_for_expenses
    //         WHERE start_date >= %s 
    //         AND end_date <= %s
    //         AND expense_sector_id = %d 
    //         ORDER BY %s %s ", $start_date, $end_date, $expense_sector_id, $order_by, $order
    //     );

    //     $sql = $wpdb->prepare( "SELECT {$wpdb->prefix}budget_for_expenses.id as budget_id,
    //     {$wpdb->prefix}budget_for_expenses.expense_sector_id,
    //      {$wpdb->prefix}budget_for_expenses.amount, 
    //      {$wpdb->prefix}budget_for_expenses.start_date,
    //      {$wpdb->prefix}budget_for_expenses.end_date,
    //      {$wpdb->prefix}budget_for_expenses.remarks,
    //      {$wpdb->prefix}income_expense_sectors.name as expense_sector_name,
    //      sum({$wpdb->prefix}income_expenses.amount) as total_expense
    //      FROM {$wpdb->prefix}budget_for_expenses 
    //     INNER JOIN {$wpdb->prefix}income_expense_sectors
    //         ON {$wpdb->prefix}budget_for_expenses.expense_sector_id = {$wpdb->prefix}income_expense_sectors.id
    //     LEFT JOIN {$wpdb->prefix}income_expenses
    //         ON {$wpdb->prefix}budget_for_expenses.id = {$wpdb->prefix}income_expenses.budget_for_expense_id
        
    //     WHERE {$wpdb->prefix}budget_for_expenses.expense_sector_id = %d GROUP BY {$wpdb->prefix}budget_for_expenses.id", $expense_sector_id);

    // }
    

    $items = $wpdb->get_results( $sql );

    return $items;
}

/**
 * Insert a new expense budget.
 *
 * @param  array  $args
 *
 * @return int|WP_Error
 */
function wpcpf_insert_expense_budget( $args = [] ) {
    global $wpdb;

    if ( empty( $args['expense_sector_id'] ) ) {
        return new \WP_Error( 'no-expense-sector', __( 'You must expense sector name.', 'wpcodal-pf' ) );
    }

    if ( empty( $args['amount'] ) ) {
        return new \WP_Error( 'no-budget-amount', __( 'You must provide budget amount.', 'wpcodal-pf' ) );
    }

    if ( empty( $args['start_date'] ) ) {
        return new \WP_Error( 'no-start-date', __( 'You must provide start date.', 'wpcodal-pf' ) );
    }

    if ( empty( $args['end_date'] ) ) {
        return new \WP_Error( 'no-end-date', __( 'You must provide end date.', 'wpcodal-pf' ) );
    }

    if ( empty( $args['remarks'] ) ) {
        return new \WP_Error( 'no-expense-budget-remarks', __( 'You must provide remarks.', 'wpcodal-pf' ) );
    }


    $defaults = [
        'expense_sector_id' => 0,
        'amount'            => 0,
        'start_date'        => null,
        'end_date'          => null,
        'remarks'           => '',
        'created_by'        => get_current_user_id(),
    ];

    $data = wp_parse_args( $args, $defaults );
   

    $inserted = $wpdb->insert(
        $wpdb->prefix . 'budget_for_expenses',
        $data,
        [
            '%d',
            '%d',
            '%s',
            '%s',
            '%s',
            '%d',
            
        ]
    );

    if ( ! $inserted ) {
        return new \WP_Error( 'failed-to-insert', __( 'Failed to insert data', 'wpcodal-pf' ) );
    }

    return $wpdb->insert_id;
}

/**
 * Insert a new address
 *
 * @param  array  $args
 *
 * @return int|WP_Error
 */
function wpcpf_update_expense_budget( $args = [], $id ) {
    global $wpdb;

    if ( empty( $args['expense_sector_id'] ) ) {
        return new \WP_Error( 'no-expense-sector', __( 'You must expense sector name.', 'wpcodal-pf' ) );
    }

    if ( empty( $args['amount'] ) ) {
        return new \WP_Error( 'no-budget-amount', __( 'You must provide budget amount.', 'wpcodal-pf' ) );
    }

    if ( empty( $args['start_date'] ) ) {
        return new \WP_Error( 'no-start-date', __( 'You must provide start date.', 'wpcodal-pf' ) );
    }

    if ( empty( $args['end_date'] ) ) {
        return new \WP_Error( 'no-end-date', __( 'You must provide end date.', 'wpcodal-pf' ) );
    }

    if ( empty( $args['remarks'] ) ) {
        return new \WP_Error( 'no-expense-budget-remarks', __( 'You must provide remarks.', 'wpcodal-pf' ) );
    }


    $defaults = [
        'expense_sector_id' => 0,
        'amount'            => 0,
        'start_date'        => null,
        'end_date'          => null,
        'remarks'           => '',
        'created_by'        => get_current_user_id(),
    ];

    $data = wp_parse_args( $args, $defaults );

    $updated = $wpdb->update(
        $wpdb->prefix . 'budget_for_expenses',
        $data,
        [ 'id' => $id ],
        [
            '%d',
            '%d',
            '%s',
            '%s',
            '%s',
            '%d',
        ],
        [ '%d' ]
    );

    return $updated;
}

/**
 * Fetch a single expense budget from the DB
 *
 * @param  int $id
 *
 * @return object
 */
function wpcpf_get_single_expense_budget( $id ) {
    global $wpdb;

    return $wpdb->get_row(
        $wpdb->prepare( "SELECT {$wpdb->prefix}budget_for_expenses.id,
        {$wpdb->prefix}budget_for_expenses.expense_sector_id,
         {$wpdb->prefix}budget_for_expenses.amount, 
         {$wpdb->prefix}budget_for_expenses.start_date,
         {$wpdb->prefix}budget_for_expenses.end_date,
         {$wpdb->prefix}budget_for_expenses.remarks,
         {$wpdb->prefix}income_expense_sectors.name as expense_sector_name
         FROM {$wpdb->prefix}budget_for_expenses 
        INNER JOIN {$wpdb->prefix}income_expense_sectors
            ON {$wpdb->prefix}budget_for_expenses.expense_sector_id = {$wpdb->prefix}income_expense_sectors.id
        WHERE {$wpdb->prefix}budget_for_expenses.id = %d", $id));
}

/**
 * Fetch a single expense budget from the DB
 *
 * @param  int $id
 *
 * @return object
 */
function wpcpf_check_data_in_this_range( $expense_sector_id, $start_date, $id ) {
    global $wpdb;
    $date    = new DateTime($start_date);
    
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
 * Delete expense budget
 *
 * @param  int $id
 *
 * @return int|boolean
 */
function delete_expense_budget( $id ) {
    global $wpdb;

    return $wpdb->delete(
        $wpdb->prefix . 'budget_for_expenses',
        [ 'id' => $id ],
        [ '%d' ]
    );
}

/**
 * Expense budget id by date
 *
 * @param  string $entry_date
 *
 * @return object
 */
function wpcpf_expense_budget_id_by_date( $entry_date ) {
    global $wpdb;

    return $wpdb->get_results(
        $wpdb->prepare( "SELECT {$wpdb->prefix}budget_for_expenses.id as budget_for_expense_id,
         {$wpdb->prefix}income_expense_sectors.name as expense_sector_name
         FROM {$wpdb->prefix}budget_for_expenses 
        INNER JOIN {$wpdb->prefix}income_expense_sectors
            ON {$wpdb->prefix}budget_for_expenses.expense_sector_id = {$wpdb->prefix}income_expense_sectors.id
        WHERE {$wpdb->prefix}budget_for_expenses.start_date <= %s AND {$wpdb->prefix}budget_for_expenses.end_date >=%s", $entry_date, $entry_date
    ));
}

/**
 * Expense budget id by date
 *
 * @param  string $entry_date
 *
 * @return object
 */
function wpcpf_budgets_by_expense_sector( $expense_sector_id ) {
    global $wpdb;

    return $wpdb->get_results(
        $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}budget_for_expenses WHERE expense_sector_id=%d", $expense_sector_id
    ));
}


function test(){
    // global $wpdb;
    // return $wpdb->get_results(
    //     $wpdb->prepare( "SELECT {$wpdb->prefix}budget_for_expenses.id as budget_for_expense_id,
    //      {$wpdb->prefix}income_expense_sectors.name as expense_sector_name,
         
    //      FROM {$wpdb->prefix}budget_for_expenses 
    //      INNER JOIN {$wpdb->prefix}income_expense_sectors
    //         ON {$wpdb->prefix}budget_for_expenses.expense_sector_id = {$wpdb->prefix}income_expense_sectors.id
    //     WHERE {$wpdb->prefix}budget_for_expenses.start_date <= %s AND {$wpdb->prefix}budget_for_expenses.end_date >=%s", $entry_date, $entry_date
    // ));
}