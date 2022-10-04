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
 * Insert a new expense budget.
 *
 * @param  array  $args
 *
 * @return int|WP_Error
 */
function wpcpf_insert_income( $args = [] ) {
    global $wpdb;

    if ( empty( $args['income_sector_id'] ) ) {
        return new \WP_Error( 'no-income-sector', __( 'You must provide income sector name.', 'wpcodal-pf' ) );
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
        'expense_sector_id'     => null,
        'budget_for_expense_id' => null,
        'amount'            => 0,
        'entry_date'        => null,
        'remarks'           => '',
        'created_by'        => get_current_user_id(),
    ];

    $data = wp_parse_args( $args, $defaults );
   

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
function wpcpf_update_income( $args = [], $id ) {
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
function wpcpf_get_single_income( $id ) {
    global $wpdb;

    return $wpdb->get_row(
        $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}budget_for_expenses WHERE id = %d", $id )
    );
}

/**
 * Fetch a single expense budget from the DB
 *
 * @param  int $id
 *
 * @return object
 */
function wpcpf_check_income_data_in_this_range( $expense_sector_id, $start_date, $id ) {
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
function delete_income( $id ) {
    global $wpdb;

    return $wpdb->delete(
        $wpdb->prefix . 'budget_for_expenses',
        [ 'id' => $id ],
        [ '%d' ]
    );
}