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
 * Fetch Addresses
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
            WHERE type = $sector_type 
            ORDER BY {$order_by} {$order}",
    );

    $items = $wpdb->get_results( $sql );

    return $items;
}