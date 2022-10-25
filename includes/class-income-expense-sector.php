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
 * Add styles of scripts files inside this class.
 */
class Income_Expense_Sector {

	use Singleton;

	/**
	 * Constructor of Bootstrap class.
	 */
	private function __construct( $sector_type ) {
        session_start();
		$this->form_handler();
        // add_action( 'admin_post_wpcpf-delete-sector', [ $this, 'delete_income_expense_sector' ] );
        $this->delete_income_expense_sector();

		$this->income_expense_page( $sector_type );
		
	}

	/**
     * Income expense page handler.
     *
     * @return void
     */
    public function income_expense_page( $sector_type ) {
        $action = isset( $_GET['action'] ) ? $_GET['action'] : 'list';
        $url    = isset( $_GET['page'] ) ? $_GET['page'] : 'income_sector';
        $sector_type_id = $sector_type == 'income' ? 1 : 2;

        switch ( $action ) {
            case 'new':
                $template = WPCPF_PLUGIN_DIR . '/templates/income-expense-sector/create.php';
                break;

            case 'edit':
                $id       = isset( $_GET['id'] ) ? $_GET['id'] : null;
                $single_income_expense = wpcpf_get_single_income_expense_sector( $id );
                $template = WPCPF_PLUGIN_DIR . '/templates/income-expense-sector/edit.php';
                break;

            case 'view':
                $template = __DIR__ . '/views/income-expense-sector/view.php';
                break;

            default:
                $data  = wpcpf_get_income_expense_sector( $sector_type_id );
                $title = $sector_type == 'income' ? 'List of Income Sectors' : 'List of Expense Sectors';
                $template = WPCPF_PLUGIN_DIR . '/templates/income-expense-sector/list.php';
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
    public function form_handler() {
        if ( ! isset( $_POST['submit_income_expense_sector'] ) ) {
            return;
        }
		

        if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'new-income-expense-sector' ) ) {
            wp_die( 'Are you cheating?' );
        }

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( 'Are you cheating?' );
        }

        $name     = isset( $_POST['name'] ) ? sanitize_text_field( $_POST['name'] ) : '';
        $type     = isset( $_POST['type'] ) ? sanitize_textarea_field( $_POST['type'] ) : '';
        $id       = isset( $_POST['id'] ) ? sanitize_textarea_field( $_POST['id'] ) : null;
        $page_url = $type == 1 ? 'income_sector' : 'expense_sector';

        if ( empty( $name ) ) {
            $this->errors['name'] = __( 'Please provide a name', 'wpcodal-pf' );
        }


        if ( ! empty( $this->errors ) ) {
            return;
        }
        $check_exist = wpcpf_income_expense_sector_check( $name, $type );
        $name = trim($name);
        if ( ! $id ) {
            if ( $check_exist ) {
                $this->errors['duplicate_name'] = __( 'Duplicate name', 'wpcodal-pf' );
                $this->priv_data['name'] = $name;
                return;
            }
            $insert_id   = wpcpf_insert_income_expense_sector( [
                'name'    => $name,
                'type'    => (int) $type,
            ] );
    
            if ( is_wp_error( $insert_id ) ) {
                wp_die( $insert_id->get_error_message() );
            }

            $redirected_to = admin_url( "admin.php?page={$page_url}&inserted=true" );
        } else {
            if ( $check_exist && $check_exist->id != $id ) {
                $this->errors['duplicate_name'] = __( 'Duplicate name', 'wpcodal-pf' );
                $this->priv_data['name'] = $name;
                return;
            }

            $update_data = wpcpf_update_income_expense_sector( [
                'name'    => $name,
                'type'    => (int) $type,
            ], $id );
    
            if ( is_wp_error( $update_data ) ) {
                wp_die( $update_data->get_error_message() );
            }
            $redirected_to = admin_url( "admin.php?page={$page_url}&updateee=true" );
        }

        $_SESSION["alert_message"] = true;

        wp_redirect( $redirected_to );
        exit;
    }

    public function delete_income_expense_sector() {
        
        if ( ! isset( $_REQUEST['delete_sector_action'] ) ) {
            return;
        }
        if ( ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'wpcpf-delete-sector' ) ) {
            wp_die( 'Are you cheating?' );
        }

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( 'Are you cheating?' );
        }

        $id   = isset( $_REQUEST['id'] ) ? intval( $_REQUEST['id'] ) : 0;
        $page = isset( $_REQUEST['page'] ) ? $_REQUEST['page'] : 'income_sector';

        if ( delete_sector( $id ) ) {
            $redirected_to = admin_url( "admin.php?page={$page}&sector-deleted=true" );
        } else {
            $redirected_to = admin_url( "admin.php?page={$page}&sector-deleted-failed=true" );
        }
        $_SESSION["alert_message"] = true;
        wp_redirect( $redirected_to );
        exit;
    }
}
