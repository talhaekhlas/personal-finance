<?php 
$error = isset( $this->errors ) ? $this->errors : null;
//Error messages. 
$source_name_error                       = $error && isset( $error['source_name'] ) ? $error['source_name'] : null;
$amount_error                            = $error && isset( $error['amount'] ) ? $error['amount'] : null;
$entry_date_error                        = $error && isset( $error['entry_date'] ) ? $error['entry_date'] : null;
$remarks_error                           = $error && isset( $error['remarks'] ) ? $error['remarks'] : null;
$greater_entry_date_error                = $error && isset( $error['greater_entry_date'] ) ? $error['greater_entry_date'] : null; 
$missing_parent_investment_earning_error = $error && isset( $error['missing_parent_investment_earning'] ) ? $error['missing_parent_investment_earning'] : null; 
$amount_validation_failed_error          = $error && isset( $error['amount_validation_failed'] ) ? $error['amount_validation_failed'] : null;  

//previous form data.
$prev_source_name      = isset( $this->prev_data ) && isset( $this->prev_data['source_name'] ) ? $this->prev_data['source_name'] : null;
$prev_amount           = isset( $this->prev_data ) && isset( $this->prev_data['amount'] ) ? $this->prev_data['amount'] : null;
$prev_entry_date       = isset( $this->prev_data ) && isset( $this->prev_data['entry_date'] ) ? $this->prev_data['entry_date'] : null;
$prev_remarks          = isset( $this->prev_data ) && isset( $this->prev_data['remarks'] ) ? $this->prev_data['remarks'] : null;
$prev_parent_source_id = isset( $this->prev_data ) && isset( $this->prev_data['parent_source_id'] ) ? $this->prev_data['parent_source_id'] : null;
$prev_trn_type         = isset( $this->prev_data ) && isset( $this->prev_data['trn_type'] ) ? $this->prev_data['trn_type'] : null;
$form_align            = isset( $this->loan_investment_validation_info ) ? 'float-left' : 'mx-auto';

?>