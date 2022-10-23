<?php 
 //Error messages. 
 $income_sector_error      = isset( $this->errors ) && isset( $this->errors['income_sector_id'] ) ? $this->errors['income_sector_id'] : null;
 $expense_sector_error     = isset( $this->errors ) && isset( $this->errors['budget_for_expense_id'] ) ? $this->errors['budget_for_expense_id'] : null;
 $amount_error             = isset( $this->errors ) && isset( $this->errors['amount'] ) ? $this->errors['amount'] : null;
 $entry_date_error         = isset( $this->errors ) && isset( $this->errors['entry_date'] ) ? $this->errors['entry_date'] : null;
 $remarks_error            = isset( $this->errors ) && isset( $this->errors['remarks'] ) ? $this->errors['remarks'] : null;
 $greater_entry_date_error = isset( $this->errors ) && isset( $this->errors['greater_entry_date'] ) ? $this->errors['greater_entry_date'] : null;  

  
  $prev_income_sector_id = isset( $this->prev_data ) && $this->prev_data['income_sector_id'] ? $this->prev_data['income_sector_id'] : null;
  $prev_amount           = isset( $this->prev_data ) && isset( $this->prev_data['amount'] ) ? $this->prev_data['amount'] : null;
  $prev_entry_date       = isset( $this->prev_data ) && isset( $this->prev_data['entry_date'] ) ? $this->prev_data['entry_date'] : null;
  $prev_remarks          = isset( $this->prev_data ) && isset( $this->prev_data['remarks'] ) ? $this->prev_data['remarks'] : null;

  $income_expense_list_float = isset( $this->expense_validation_info ) ? 'float-left' : 'mx-auto';

?>

<div class="p-12 w-[50%]  <?php echo $income_expense_list_float; ?>">
  <div class="mx-auto w-full max-w-[550px]">
    <form action="" method="post">
    <?php if ( $page == 'income' ) {?>
    <div class="mb-5">
      <label for="name" class="mb-3 block text-base font-medium text-[#07074D]">Income Sector Name</label>
      <select name="income_sector_id" class="w-[100%] rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md">
        <?php foreach ( $income_sectors as $value) {
          $income_id = $prev_income_sector_id ? $prev_income_sector_id : $value->id;
          $selected  = $single_income_expense->income_sector_id == $income_id ? 'selected' : null;
        ?>
        <option value="<?php echo $income_id; ?>" <?php echo $selected; ?> ><?php echo $value->name; ?></option>
        <?php } ?>
      </select>
      <?php if ( $income_sector_error ) { ?>
            <p class="text-base text-red-600 italic font-bold"><?php echo $income_sector_error; ?></p>
      <?php } ?>
    </div>
    <?php } ?>

    <div class="mb-5">
      <label
              for="date"
              class="mb-3 block text-base font-medium text-[#07074D]"
            >
              Entry Date
            </label>
            <input
              type="date"
              name="entry_date"
              id="<?php echo $page ?>_entry_date"
              value="<?php echo $prev_entry_date ? $prev_entry_date : $single_income_expense->entry_date; ?>"
              class="w-[100%] rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
            />
            <?php if ( $entry_date_error ) { ?>
            <p class="text-base text-red-600 italic font-bold"><?php echo $entry_date_error; ?></p>
            <?php } ?>
            <?php if ( $greater_entry_date_error ) { ?>
            <p class="text-base text-red-600 italic font-bold"><?php echo $greater_entry_date_error; ?></p>
            <?php } ?>
            
    </div>

    <?php if ( $page == 'expense' ) {?>
    <div class="mb-5">
      <label for="name" class="mb-3 block text-base font-medium text-[#07074D]">Expense Sector Name</label>
      <select 
        name  = "budget_for_expense_id"
        id    = "budget_for_expense_id"  
        class="w-[100%] rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md">
        <?php foreach ( $expense_budget_id_by_date as $value) {
          $selected  = $single_income_expense->budget_for_expense_id == $value->budget_for_expense_id ? 'selected' : null;
        ?>
        <option value="<?php echo $value->budget_for_expense_id; ?>" <?php echo $selected; ?> ><?php echo $value->expense_sector_name; ?></option>
        <?php } ?>
      </select>
      <?php if ( $expense_sector_error ) { ?>
            <p class="text-base text-red-600 italic font-bold"><?php echo $expense_sector_error; ?></p>
      <?php } ?>
    </div>
    <?php } ?>

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
          id="amount"
          value="<?php echo $prev_amount ? $prev_amount : $single_income_expense->amount; ?>"
          placeholder="<?php _e("Enter Budget Amount", "wpcodal-pf"); ?>"
          class="w-[100%] rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
        />
        <input type="hidden" name="id" value="<?php echo $single_income_expense->id; ?>">
        <?php if ( $amount_error ) { ?>
            <p class="text-base text-red-600 italic font-bold"><?php echo $amount_error; ?></p>
        <?php } ?>
      </div>
      

      <div class="mb-5">
      <label for="message" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-400">
        Remarks
      </label>
      <textarea id="remarks" name="remarks"  rows="4" class="block p-2.5 w-[100%] text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Your message..."><?php echo $prev_remarks ? $prev_remarks : $single_income_expense->remarks; ?></textarea>
      <?php if ( $remarks_error ) { ?>
            <p class="text-base text-red-600 italic font-bold"><?php echo $remarks_error; ?></p>
      <?php } ?>
      </div>

      
      
      <div>
            <?php 
                $button = $page == 'income' ? 'Update Income':'Update Expense';
                wp_nonce_field( 'income_expense' ); 
                submit_button( __( $button, 'wpcodal-pf' ), 'primary hover:shadow-form rounded-md bg-[#6A64F1] py-2 px-8 text-base font-semibold text-white outline-none', 'submit_income_expense' );
            ?>
      </div>
    </form>
  </div>
</div>

<?php 
    if ( isset( $this->expense_validation_info ) ) {
      include WPCPF_PLUGIN_DIR . '/templates/income-expense/expense-validation-info.php'; 
    }  
?>