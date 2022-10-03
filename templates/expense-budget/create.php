<?php 
  
  if ( isset( $this->errors ) ) {
      $name_error = isset( $this->errors ) ? $this->errors['name'] : null;
  }
?>
<div class="flex items-center justify-center p-12">
  <div class="mx-auto w-full max-w-[550px]">
    <form action="" method="post">
    <div class="mb-5">
      <label for="name" class="mb-3 block text-base font-medium text-[#07074D]">Expense Sector Name</label>
      <select name="sector_for_expense_id" class="w-96 rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md">
        <?php foreach ( $expense_sectors as $value) { ?>
        <option value="<?php echo $value->id; ?>"><?php echo $value->name; ?></option>
        <?php } ?>
      </select>
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
          id="amount"
          placeholder="<?php _e("Enter Budget Amount", "wpcodal-pf"); ?>"
          class="w-96 rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
        />
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
              id="start_date"
              class="w-96 rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
            />
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
              id="end_date"
              class="w-96 rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
            />

        <?php if ( $name_error ) { ?>
            <p class="text-base text-red-600 italic font-bold"><?php echo $name_error; ?></p>
        <?php } ?>
      </div>

      <div class="mb-5">
      <label for="message" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-400">
        Remarks
      </label>
      <textarea id="remarks" name="remarks" rows="4" class="block p-2.5 w-96 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Your message..."></textarea>
      </div>

      
      
      <div>
            <?php 
                wp_nonce_field( 'expense-budget' ); 
                submit_button( __( $button_title, 'wpcodal-pf' ), 'primary hover:shadow-form rounded-md bg-[#6A64F1] py-2 px-8 text-base font-semibold text-white outline-none', 'submit_expense_budget' );
            ?>
      </div>
    </form>
  </div>
</div>