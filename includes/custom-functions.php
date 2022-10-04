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
 * Insert a new address
 *
 * @param  array  $args
 *
 * @return int|WP_Error
 */
function wpcpf_insert_income_expense_sector( $args = [] ) {
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

    $inserted = $wpdb->insert(
        $wpdb->prefix . 'income_expense_sectors',
        $data,
        [
            '%s',
            '%d',
            '%d',
        ]
    );

    if ( ! $inserted ) {
        return new \WP_Error( 'failed-to-insert', __( 'Failed to insert data', 'wedevs-academy' ) );
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
function wpcpf_update_income_expense_sector( $args = [], $id ) {
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

/**
 * Fetch Income Expense sector.
 *
 * @param  array  $args
 *
 * @return array
 */
function wpcpf_get_income_expense_sector( $sector_type = 1 ) {
    global $wpdb;

    // $defaults = [
    //     'number'  => 20,
    //     'offset'  => 0,
    //     'orderby' => 'id',
    //     'order'   => 'ASC'
    // ];

    $order_by = 'id';
    $order    = 'desc';

    // $args = wp_parse_args( $args, $defaults );

    $sql = $wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}income_expense_sectors
            WHERE type = %d 
            ORDER BY {$order_by} {$order}",$sector_type
    );

    $items = $wpdb->get_results( $sql );

    return $items;
}

/**
 * Fetch a single contact from the DB
 *
 * @param  int $id
 *
 * @return object
 */
function wpcpf_get_single_income_expense_sector( $id ) {
    global $wpdb;

    return $wpdb->get_row(
        $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}income_expense_sectors WHERE id = %d", $id )
    );
}

/**
 * Delete an address
 *
 * @param  int $id
 *
 * @return int|boolean
 */
function delete_sector( $id ) {
    global $wpdb;

    return $wpdb->delete(
        $wpdb->prefix . 'income_expense_sectors',
        [ 'id' => $id ],
        [ '%d' ]
    );
}


include WPCPF_PLUGIN_DIR . '/includes/custom-files/expense-budget-functions.php';

