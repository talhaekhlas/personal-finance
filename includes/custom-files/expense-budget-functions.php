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

    echo '<pre>';
    print_r($data);

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

    if ( empty( $args['name'] ) ) {
        return new \WP_Error( 'no-name', __( 'You must provide a name.', 'wpcodal-pf' ) );
    }

    $defaults = [
        'name'       => '',
        'type'    => 1,
        'created_by' => get_current_user_id(),
    ];

    $data = wp_parse_args( $args, $defaults );

    $updated = $wpdb->update(
        $wpdb->prefix . 'income_expense_sectors',
        $data,
        [ 'id' => $id ],
        [
            '%s',
            '%d',
            '%d'
        ],
        [ '%d' ]
    );

    return $updated;
}