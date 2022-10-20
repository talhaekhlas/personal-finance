<?php 
  //Error messages. 
  $income_sector_error      = isset( $this->errors ) && isset( $this->errors['income_sector_id'] ) ? $this->errors['income_sector_id'] : null;
  $expense_sector_error     = isset( $this->errors ) && isset( $this->errors['budget_for_expense_id'] ) ? $this->errors['budget_for_expense_id'] : null;
  $amount_error             = isset( $this->errors ) && isset( $this->errors['amount'] ) ? $this->errors['amount'] : null;
  $entry_date_error         = isset( $this->errors ) && isset( $this->errors['entry_date'] ) ? $this->errors['entry_date'] : null;
  $remarks_error            = isset( $this->errors ) && isset( $this->errors['remarks'] ) ? $this->errors['remarks'] : null;
  $greater_entry_date_error = isset( $this->errors ) && isset( $this->errors['greater_entry_date'] ) ? $this->errors['greater_entry_date'] : null;  
  
  //previous form data.
  $prev_expense_sector_id = isset( $this->prev_data ) && isset( $this->prev_data['income_sector_id'] ) ? $this->prev_data['income_sector_id'] : null;
  $prev_amount            = isset( $this->prev_data ) && isset( $this->prev_data['amount'] ) ? $this->prev_data['amount'] : null;
  $prev_entry_date        = isset( $this->prev_data ) && isset( $this->prev_data['entry_date'] ) ? $this->prev_data['entry_date'] : null;
  $prev_remarks           = isset( $this->prev_data ) && isset( $this->prev_data['remarks'] ) ? $this->prev_data['remarks'] : null;

  $income_expense_list_width = isset( $this->expense_validation_info ) ? 'w-3/5' : 'w-3/5';
  $income_expense_list_float = isset( $this->expense_validation_info ) ? 'float-left' : null;


?>
<div class="flex items-center justify-center p-12">
  <div class="mx-auto w-full max-w-[550px]">
    <form action="" method="post">
    <?php
      if ( $page == 'income' ) {?>
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
    <?php } ?>
    
    
    <div class="mb-5">
      <label
              for="date"
              class="mb-3 block text-base font-medium text-[#07074D]"
            >
            <?php $page == 'income'? _e("Income") : _e("Expense"); ?> Date
            </label>
            <input
              type="date"
              name="entry_date"
              value="<?php echo $prev_entry_date; ?>"
              id="<?php echo $page ?>_entry_date"
              class="w-96 rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
            />
            <?php if ( $entry_date_error ) { ?>
            <p class="text-base text-red-600 italic font-bold"><?php echo $entry_date_error; ?></p>
            <?php } ?>

            <?php if ( $greater_entry_date_error ) { ?>
            <p class="text-base text-red-600 italic font-bold"><?php echo $greater_entry_date_error; ?></p>
            <?php } ?>
    </div>

    <?php
      if ( $page == 'expense' ) {?>
      <div class="mb-5">
      <label for="name" class="mb-3 block text-base font-medium text-[#07074D]"><?php _e("Expense Sector Name", "wpcpf-pf"); ?></label>
      
      <select 
        name  = "budget_for_expense_id"
        id    = "budget_for_expense_id" 
        class = "w-96 rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
      >
        <option value="" disabled selected>Select Expense Sector</option>
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
                $button = $page == 'income' ? 'Add Income':'Add Expense';
                wp_nonce_field( 'income_expense' ); 
                submit_button( __( $button, 'wpcodal-pf' ), 'primary hover:shadow-form rounded-md bg-[#6A64F1] py-2 px-8 text-base font-semibold text-white outline-none', 'submit_income_expense' );
            ?>
      </div>
    </form>
  </div>
</div>

<?php 
    if ( isset( $this->expense_validation_info ) ) {
?>
<div class="w-2/6 mx-auto float-left ml-10">
  <div class="bg-white shadow-md rounded mt-20">
    <table class="text-left w-full border-collapse"> <!--Border collapse doesn't work on this site yet but it's available in newer tailwind versions -->
      <caption>
        <p class="text-2xl text-emerald-900 font-bold"><?php $page == 'income'? _e("Income List") : _e("Expense List"); ?></p>
      </caption>
      <thead>
        <tr>
          <th class="w-10 py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light">Sl</th>
          <th class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light"><?php $page == 'income'? _e("Income") : _e("Expense"); ?> Sector</th>
          <th class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light">Amount</th>
          <th class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light">Entry Date</th>
          <th class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light">Remarks</th>
          <th class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php
         $sl = 0;
         foreach([] as $value) { 
        ?>
        <tr class="hover:bg-grey-lighter">
          <td class="py-4 px-6 border-b border-grey-light"><?php echo ++$sl; ?></td>
          <?php if ( $page == 'income' ) { ?>
          <td class="py-4 px-6 border-b border-grey-light"><?php echo $income_sector_by_id[ $value->income_sector_id ]; ?></td>
          <?php } else { ?>
            <td class="py-4 px-6 border-b border-grey-light"><?php echo $value->name; ?></td>
          <?php } ?>  
          <td class="py-4 px-6 border-b border-grey-light"><?php echo $value->amount; ?></td>
          <td class="py-4 px-6 border-b border-grey-light"><?php echo $value->entry_date; ?></td>
          <td class="py-4 px-6 border-b border-grey-light"><?php echo $value->remarks; ?></td>
          <td class="py-4 px-6 border-b border-grey-light">
          <?php 
            $edit_url = admin_url( "admin.php?page={$page}&action=edit&id={$value->id}") ; 
          ?>  
          <a href="<?php echo $edit_url; ?>" class="text-white font-bold py-1 px-3 rounded text-xs bg-blue-500 hover:bg-green-dark">Edit</a>
          <?php $delete_url = wp_nonce_url( admin_url( "admin.php?page=income&delete_income_action=wpcpf-delete-income&id=" . $value->id ), 'wpcpf-delete-income' ); ?>
          <a 
            href="#" 
            onclick="JSconfirm('<?php echo $delete_url;  ?>')" 
            class="text-white font-bold py-1 px-3 rounded text-xs bg-red-500 hover:bg-blue-dark">
              Delete
          </a>
          </td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</div>
<?php } ?>