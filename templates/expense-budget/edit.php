<?php 
    include WPCPF_PLUGIN_DIR . '/templates/expense-budget/error-and-prev-data.php'; 
?>
<div class="flex items-center justify-center p-12">
	<div class="mx-auto w-full max-w-[550px]">
		<?php if ( $budget_exist_error ) { ?>
						<p class="text-base text-red-600 italic font-bold"><?php echo $budget_exist_error; ?></p>
		<?php } ?>
		<form action="" method="post">
			<div class="mb-5">
				<label 
				for   = "name" 
				class = "mb-3 block text-base font-medium text-[#07074D]"
				>
				Expense Sector Name
				</label>
				<select 
					name  = "expense_sector_id" 
					class = "w-[100%] rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
				>
					<?php foreach ( $expense_sectors as $value) {
						$selected   = $single_expense_budget->expense_sector_id == $value->id ? 'selected' : null;
						$expense_id = $prev_expense_sector_id ? $prev_expense_sector_id : $value->id;
						$selected   = $single_expense_budget->expense_sector_id == $expense_id ? 'selected' : null;
					?>
					<option value = "<?php echo $expense_id; ?>" <?php echo $selected; ?> ><?php echo $value->name; ?></option>
					<?php } ?>
				</select>
				<?php if ( $expense_sector_error ) { ?>
							<p class="text-base text-red-600 italic font-bold"><?php echo $expense_sector_error; ?></p>
				<?php } ?>
			</div>

			<div class="mb-5">
				<label
					for   = "name"
					class = "mb-3 block text-base font-medium text-[#07074D]"
				>
					Budget Amount
				</label>
				<input
					type        = "text"
					name        = "amount"
					id          = "amount"
					value       = "<?php echo $prev_amount ? $prev_amount : $single_expense_budget->amount; ?>"
					placeholder = "<?php _e("Enter Budget Amount", "wpcodal-pf"); ?>"
					class       = "w-[100%] rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
				/>
				<input 
					type  = "hidden" 
					name  = "id" 
					value = "<?php echo $single_expense_budget->id; ?>"
				>
				<?php if ( $amount_error ) { ?>
						<p class="text-base text-red-600 italic font-bold"><?php echo $amount_error; ?></p>
				<?php } ?>
			</div>
			<div class="mb-5">
				<label
					for   = "date"
					class = "mb-3 block text-base font-medium text-[#07074D]"
				>
					Start Date
				</label>
				<input
					type  = "date"
					name  = "start_date"
					id    = "start_date"
					value = "<?php echo $prev_start_date ? $prev_start_date : $single_expense_budget->start_date; ?>"
					class = "w-[100%] rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
				/>
				<?php if ( $start_date_error ) { ?>
				<p class = "text-base text-red-600 italic font-bold"><?php echo $start_date_error; ?></p>
				<?php } ?>

				<?php if ( $greater_startd_error ) { ?>
				<p class = "text-base text-red-600 italic font-bold"><?php echo $greater_startd_error; ?></p>
				<?php } ?>
			</div>

			<div class="mb-5">
				<label
					for="date"
					class="mb-3 block text-base font-medium text-[#07074D]"
				>
					End Date
				</label>
				<input
					type  = "date"
					name  = "end_date"
					id    = "end_date"
					value = "<?php echo $prev_end_date ? $prev_end_date : $single_expense_budget->end_date; ?>"
					class = "w-[100%] rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
				/>
				<?php if ( $end_date_error ) { ?>
				<p class="text-base text-red-600 italic font-bold"><?php echo $end_date_error; ?></p>
				<?php } ?>
			</div>

			<div class="mb-5">
				<label 
					for   = "message" 
					class = "block mb-2 text-sm font-medium text-gray-900 dark:text-gray-400"
				>
					Remarks
				</label>
				<textarea 
				id          = "remarks" 
				name        = "remarks"  
				rows        = "4" 
				class       = "w-[100%] block p-2.5 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
				placeholder = "Your message..."
				>
					<?php echo $prev_remarks ? $prev_remarks : $single_expense_budget->remarks; ?>
				</textarea>
				<?php if ( $remarks_error ) { ?>
							<p class="text-base text-red-600 italic font-bold"><?php echo $remarks_error; ?></p>
				<?php } ?>
			</div>

			<div>
				<?php 
						wp_nonce_field( 'expense-budget' ); 
						submit_button( __( 'Update Expense Budget', 'wpcodal-pf' ), 'primary hover:shadow-form rounded-md bg-[#6A64F1] py-2 px-8 text-base font-semibold text-white outline-none', 'submit_expense_budget' );
				?>
			</div>
		</form>
	</div>
</div>