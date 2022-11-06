<?php 
$errorss  = isset( $this->errors ) ? $this->errors : null;
$pre_data = isset( $this->prev_data ) ? $this->prev_data : null;
//Nofication error message.
$income_sector_error      = $errorss && isset( $errorss['income_sector_id'] ) ? $errorss['income_sector_id'] : null;
$expense_sector_error     = $errorss && isset( $errorss['budget_for_expense_id'] ) ? $errorss['budget_for_expense_id'] : null;
$amount_error             = $errorss && isset( $errorss['amount'] ) ? $errorss['amount'] : null;
$entry_date_error         = $errorss && isset( $errorss['entry_date'] ) ? $errorss['entry_date'] : null;
$remarks_error            = $errorss && isset( $errorss['remarks'] ) ? $errorss['remarks'] : null;
$greater_entry_date_error = $errorss && isset( $errorss['greater_entry_date'] ) ? $errorss['greater_entry_date'] : null;  

//Previous form data.
$prev_income_sector_id = $pre_data && $pre_data['income_sector_id'] ? $pre_data['income_sector_id'] : null;
$prev_amount           = $pre_data && isset( $pre_data['amount'] ) ? $pre_data['amount'] : null;
$prev_entry_date       = $pre_data && isset( $pre_data['entry_date'] ) ? $pre_data['entry_date'] : null;
$prev_remarks          = $pre_data && isset( $pre_data['remarks'] ) ? $pre_data['remarks'] : null;

$form_align = isset( $this->expense_validation_info ) ? 'float-left' : 'mx-auto';
?>