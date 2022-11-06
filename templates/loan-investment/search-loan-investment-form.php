<form action="" method="post">
	<div class="-mx-3 flex flex-wrap">
		<div class="w-full px-3 sm:w-1/5 mt-10">
			<div>
				<label
					for   = "date"
					class = "mb-3 block text-base font-medium text-[#07074D]"
				>
					<?php _e("Start Date"); ?>
				</label>
				<input
					type  = "date"
					name  = "start_date"
					value = "<?php echo isset( $this->prev_data['start_date'] ) ? $this->prev_data['start_date']: $start_date ?>"
					id    = "<?php echo $page ?>_start_date"
					class = "w-[100%] rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
				/>
			</div>
		</div>
		<div class="w-full px-3 sm:w-1/5 mt-10">
			<div>
				<label
					for   = "date"
					class = "mb-3 block text-base font-medium text-[#07074D]"
				>
				<?php _e("End Date"); ?>
				</label>
				<input
					type  = "date"
					name  = "end_date"
					value = "<?php echo isset( $this->prev_data['end_date'] ) ? $this->prev_data['end_date']: $end_date ?>"
					id    = "<?php echo $page ?>_end_date"
					class = "w-[100%] rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
				/>
			</div>
		</div>
		<div class="w-full px-3 sm:w-1/5 mt-10">
			<div>
				<label 
					for   = "name" 
					class = "mb-3 block text-base font-medium text-[#07074D]"
					>
					<?php _e("Transaction type"); ?>
				</label>
				<select 
					name  = "trn_type" id="trn_type" 
					class = "w-[100%] rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
				>
					<option value="All" <?php echo isset( $this->prev_data ) && $this->prev_data['budget_for_expense_id'] == 'All' ? 'selected' : null; ?> ><?php echo _e("All Type");; ?></option>
					<?php 
					$trn_types = $page == 'loan' ? [1=>'Loan Recieve','Loan Pay'] : [3=>'Investment','Earning from investment'];
					foreach ( $trn_types as $trn_type_id => $trn_type_name ) { 
						$selected = null;
						if ( isset( $this->prev_data) &&  $this->prev_data['trn_type'] == $trn_type_id ) {
						$selected = 'selected';
						}
						if ( $trn_type_id == $trn_type ) {
						$selected = 'selected';
						}
					?>
					<option value="<?php echo $trn_type_id; ?>" <?php echo $selected; ?> >
						<?php echo $trn_type_name; ?>
					</option>
					<?php } ?>
				</select>
			</div>
		</div>
		<div class="w-full px-3 sm:w-1/5 mt-10">
			<div>
				<label 
					for   = "name" 
					class = "mb-3 block text-base font-medium text-[#07074D]"
				>
					<?php _e("Source Name"); ?>
				</label>
				<select name="loan_investment_id" id="loan_investment_id" class="w-[100%] rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md">
				<option value="All" <?php echo isset( $this->prev_data ) && $this->prev_data['budget_for_expense_id'] == 'All' ? 'selected' : null; ?> >
					<?php echo _e("All Source");; ?>
				</option>
					<?php 
					foreach ( $parent_loan_investment as $value ) { 
						$selected = null;
						if ( isset( $this->prev_data) &&  $this->prev_data['loan_investment_id'] == $value->id ) {
						$selected = 'selected';
						}
						if ( $loan_investment_id == $value->id ) {
						$selected = 'selected';
						}
					?>
					<option value="<?php echo $value->id; ?>" <?php echo $selected; ?> >
						<?php echo $value->source_name; ?>
					</option>
					<?php } ?>
				</select>
			</div>
		</div>
		<div class="w-full px-3 sm:w-1/5 mt-11">
			<div>
				<?php 
					$button = $page == 'loan' ? 'Search Loan':'Search Investment';
					wp_nonce_field( 'search_loan_investment' ); 
					submit_button( __( $button, 'wpcodal-pf' ), 'primary hover:shadow-form rounded-md bg-[#6A64F1] px-8 text-base font-semibold text-white outline-none mt-[12px]', 'submit_search_loan_investment' );
				?>
			</div>
		</div>
	</div>
</form>
<?php 
if ( isset( $this->errors ) ) {
	if ( isset( $this->errors['greater_start_date'] ) ) {
	$error = $this->errors['greater_start_date'];
	}

	if ( isset( $this->errors['end_date'] ) ) {
	$error = $this->errors['end_date'];
	}

	if ( isset( $this->errors['start_date'] ) ) {
	$error = $this->errors['start_date'];
	}
?>
<div class="w-full mb-2">
	<p class="text-lg text-red-600 italic font-bold text-center"><?php echo $error; ?></p>
</div>
<?php } ?>