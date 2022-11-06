<?php 
	$button_title    = $sector_type == 'income' ? 'Add Income Sector' : 'Add Expense Sector';
	$placeholder     = $sector_type == 'income' ? 'Please Enter Income Sector' : 'Please Enter Expense Sector';
	$type            = $sector_type == 'income' ? 1 : 2;
	$name_error      = isset( $this->errors ) && isset( $this->errors['name'] ) ? $this->errors['name'] : null;
	$duplicate_error = isset( $this->errors ) && isset( $this->errors['duplicate_name'] ) ? $this->errors['duplicate_name'] : null;
	$priv_name       = isset( $this->priv_data ) && isset( $this->priv_data['name'] ) ? $this->priv_data['name'] : null;
 
?>