<?php 
  //Nofication error message.
  $expense_sector_error = isset( $this->errors ) && isset( $this->errors['expense_sector_id'] ) ? $this->errors['expense_sector_id'] : null;
  $amount_error         = isset( $this->errors ) && isset( $this->errors['amount'] ) ? $this->errors['amount'] : null;
  $start_date_error     = isset( $this->errors ) && isset( $this->errors['start_date'] ) ? $this->errors['start_date'] : null;
  $end_date_error       = isset( $this->errors ) && isset( $this->errors['end_date'] ) ? $this->errors['end_date'] : null;
  $remarks_error        = isset( $this->errors ) && isset( $this->errors['remarks'] ) ? $this->errors['remarks'] : null;
  $greater_startd_error = isset( $this->errors ) && isset( $this->errors['greater_start_date'] ) ? $this->errors['greater_start_date'] : null;
  $budget_exist_error   = isset( $this->errors ) && isset( $this->errors['already_exist_budget'] ) ? $this->errors['already_exist_budget'] : null;
  

  //Previous form data.
  $prev_expense_sector_id = isset( $this->prev_data ) && isset( $this->prev_data['expense_sector_id'] ) ? $this->prev_data['expense_sector_id'] : null;
  $prev_amount            = isset( $this->prev_data ) && isset( $this->prev_data['amount'] ) ? $this->prev_data['amount'] : null;
  $prev_start_date        = isset( $this->prev_data ) && isset( $this->prev_data['start_date'] ) ? $this->prev_data['start_date'] : null;
  $prev_end_date          = isset( $this->prev_data ) && isset($this->prev_data['end_date'] ) ? $this->prev_data['end_date'] : null;
  $prev_remarks           = isset( $this->prev_data ) && isset( $this->prev_data['remarks'] ) ? $this->prev_data['remarks'] : null;

?>
<div class="flex items-center justify-center p-12">
  <div class="mx-auto w-full max-w-[550px]">
    <?php if ( $budget_exist_error ) { ?>
            <p class="text-base text-red-600 italic font-bold"><?php echo $budget_exist_error; ?></p>
    <?php } ?>
    <form action="" method="post">
    <div class="mb-5">
      <label for="name" class="mb-3 block text-base font-medium text-[#07074D]">Expense Sector Name</label>
      <select name="expense_sector_id" class="w-[100%] rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md">
        <?php foreach ( $expense_sectors as $value) { ?>
        <option value="<?php echo $value->id; ?>"><?php echo $value->name; ?></option>
        <?php } ?>
      </select>
      <?php if ( $expense_sector_error ) { ?>
            <p class="text-base text-red-600 italic font-bold"><?php echo $expense_sector_error; ?></p>
      <?php } ?>
    </div>

    <div class="mb-5">
        <label
          for="name"
          class="mb-3 block text-base font-medium text-[#07074D]"
        >
          Budget Amount
        </label>
        <input
          type="text"
          name="amount"
          value="<?php echo $prev_amount; ?>"
          id="amount"
          placeholder="<?php _e("Enter Budget Amount", "wpcodal-pf"); ?>"
          class="w-[100%] rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
        />
        <?php if ( $amount_error ) { ?>
            <p class="text-base text-red-600 italic font-bold"><?php echo $amount_error; ?></p>
        <?php } ?>
      </div>
      <div class="mb-5">
      <label
              for="date"
              class="mb-3 block text-base font-medium text-[#07074D]"
            >
              Start Date
            </label>
            <input
              type="date"
              name="start_date"
              value="<?php echo $prev_start_date; ?>"
              id="start_date"
              class="w-[100%] rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
            />
            <?php if ( $start_date_error ) { ?>
            <p class="text-base text-red-600 italic font-bold"><?php echo $start_date_error; ?></p>
            <?php } ?>

            <?php if ( $greater_startd_error ) { ?>
            <p class="text-base text-red-600 italic font-bold"><?php echo $greater_startd_error; ?></p>
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
              type="date"
              name="end_date"
              value="<?php echo $prev_start_date; ?>"
              id="end_date"
              class="w-[100%] rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
            />
            <?php if ( $end_date_error ) { ?>
            <p class="text-base text-red-600 italic font-bold"><?php echo $end_date_error; ?></p>
            <?php } ?>
      </div>

      <div class="mb-5">
      <label for="message" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-400">
        Remarks
      </label>
      <textarea id="remarks" name="remarks" rows="4" class="w-[100%] block p-2.5 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Your message..."><?php echo $prev_remarks; ?></textarea>
      <?php if ( $remarks_error ) { ?>
            <p class="text-base text-red-600 italic font-bold"><?php echo $remarks_error; ?></p>
      <?php } ?>
      </div>
      <div>
            <?php 
                wp_nonce_field( 'expense-budget' ); 
                submit_button( __( 'Add Expense Budget', 'wpcodal-pf' ), 'primary hover:shadow-form rounded-md bg-[#6A64F1] py-2 px-8 text-base font-semibold text-white outline-none', 'submit_expense_budget' );
            ?>
      </div>
    </form>
  </div>
</div>