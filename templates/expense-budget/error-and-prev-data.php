<?php 
$pre_data = isset( $this->prev_data ) ? $this->prev_data : null;
$errorss  = isset( $this->errors ) ? $this->errors : null;
//Nofication error message.
$expense_sector_error = $errorss && isset( $errorss['expense_sector_id'] ) ? $errorss['expense_sector_id'] : null;
$amount_error         = $errorss && isset( $errorss['amount'] ) ? $errorss['amount'] : null;
$start_date_error     = $errorss && isset( $errorss['start_date'] ) ? $errorss['start_date'] : null;
$end_date_error       = $errorss && isset( $errorss['end_date'] ) ? $errorss['end_date'] : null;
$remarks_error        = $errorss && isset( $errorss['remarks'] ) ? $errorss['remarks'] : null;
$greater_startd_error = $errorss && isset( $errorss['greater_start_date'] ) ? $errorss['greater_start_date'] : null;
$budget_exist_error   = $errorss && isset( $errorss['already_exist_budget'] ) ? $errorss['already_exist_budget'] : null;
//Previous form data.
$prev_expense_sector_id = $pre_data && isset( $pre_data['expense_sector_id'] ) ? $pre_data['expense_sector_id'] : null;
$prev_amount            = $pre_data && isset( $pre_data['amount'] ) ? $pre_data['amount'] : null;
$prev_start_date        = $pre_data && isset( $pre_data['start_date'] ) ? $pre_data['start_date'] : null;
$prev_end_date          = $pre_data && isset( $pre_data['end_date'] ) ? $pre_data['end_date'] : null;
$prev_remarks           = $pre_data && isset( $pre_data['remarks'] ) ? $pre_data['remarks'] : null;
?>