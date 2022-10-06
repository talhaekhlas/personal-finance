<?php 
  $income_sector_error      = null;
  $amount_error             = null;
  $entry_date_error         = null;
  $remarks_error            = null;
  $greater_entry_date_error = null;

  if ( isset( $this->errors ) ) {
      $income_sector_error = isset( $this->errors['income_sector_id'] ) ? $this->errors['income_sector_id'] : null;
      $amount_error        = isset( $this->errors['amount'] ) ? $this->errors['amount'] : null;
      $entry_date_error    = isset( $this->errors['entry_date'] ) ? $this->errors['entry_date'] : null;
      $remarks_error       = isset( $this->errors['remarks'] ) ? $this->errors['remarks'] : null;
      $greater_entry_date_error       = isset( $this->errors['greater_entry_date'] ) ? $this->errors['greater_entry_date'] : null;  
  }

  
  $prev_expense_sector_id = isset( $this->prev_data ) && $this->prev_data['income_sector_id'] ? $this->prev_data['income_sector_id'] : null;
  $prev_amount            = isset( $this->prev_data ) && isset( $this->prev_data['amount'] ) ? $this->prev_data['amount'] : null;
  $prev_entry_date        = isset( $this->prev_data ) && isset( $this->prev_data['entry_date'] ) ? $this->prev_data['entry_date'] : null;
  $prev_remarks           = isset( $this->prev_data ) && isset( $this->prev_data['remarks'] ) ? $this->prev_data['remarks'] : null;

?>
<div class="flex items-center justify-center p-12">
  <div class="mx-auto w-full max-w-[550px]">
    <!-- <?php if ( $budget_exist_error ) { ?>
            <p class="text-base text-red-600 italic font-bold"><?php echo $budget_exist_error; ?></p>
    <?php } ?> -->
    <form action="" method="post">
    <div class="mb-5">
      <label for="name" class="mb-3 block text-base font-medium text-[#07074D]"><?php $page == 'income'? _e("Income") : _e("Expense"); ?> Sector Name</label>
      <select name="income_sector_id" class="w-96 rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md">
        <?php foreach ( $income_sectors as $value) { ?>
        <option value="<?php echo $value->id; ?>"><?php echo $value->name; ?></option>
        <?php } ?>
      </select>
      <?php if ( $income_sector_error ) { ?>
            <p class="text-base text-red-600 italic font-bold"><?php echo $income_sector_error; ?></p>
      <?php } ?>
    </div>

    <div class="mb-5">
        <label
          for="name"
          class="mb-3 block text-base font-medium text-[#07074D]"
        >
          Amount
        </label>
        <input
          type="text"
          name="amount"
          value="<?php echo $prev_amount; ?>"
          id="amount"
          placeholder="<?php _e("Enter Income Amount", "wpcodal-pf"); ?>"
          class="w-96 rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
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
              Income Date
            </label>
            <input
              type="date"
              name="entry_date"
              value="<?php echo $prev_entry_date; ?>"
              id="entry_date"
              class="w-96 rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
            />
            <?php if ( $entry_date_error ) { ?>
            <p class="text-base text-red-600 italic font-bold"><?php echo $entry_date_error; ?></p>
            <?php } ?>

            <?php if ( $greater_entry_date_error ) { ?>
            <p class="text-base text-red-600 italic font-bold"><?php echo $greater_entry_date_error; ?></p>
            <?php } ?>
      </div>

      <div class="mb-5">
      <label for="message" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-400">
        Remarks
      </label>
      <textarea id="remarks" name="remarks" rows="4" class="block p-2.5 w-96 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Your message..."><?php echo $prev_remarks; ?></textarea>
      <?php if ( $remarks_error ) { ?>
            <p class="text-base text-red-600 italic font-bold"><?php echo $remarks_error; ?></p>
      <?php } ?>
      </div>

      
      
      <div>
            <?php 
                wp_nonce_field( 'income' ); 
                submit_button( __( 'Add Income', 'wpcodal-pf' ), 'primary hover:shadow-form rounded-md bg-[#6A64F1] py-2 px-8 text-base font-semibold text-white outline-none', 'submit_income' );
            ?>
      </div>
    </form>
  </div>
</div>