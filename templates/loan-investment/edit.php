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
  $invalid_parent_source_error             = $error && isset( $error['invalid_parent_source'] ) ? $error['invalid_parent_source'] : null;   
  
  //previous form data.
  $prev_data = isset( $this->prev_data ) ? $this->prev_data : null;
  $prev_source_name      = $prev_data && isset( $prev_data['source_name'] ) ? $prev_data['source_name'] : $single_loan_investment->source_name;
  $prev_amount           = $prev_data && isset( $prev_data['amount'] ) ? $prev_data['amount'] : $single_loan_investment->amount;
  $prev_entry_date       = $prev_data && isset( $prev_data['entry_date'] ) ? $prev_data['entry_date'] : $single_loan_investment->entry_date;
  $prev_remarks          = $prev_data && isset( $prev_data['remarks'] ) ? $prev_data['remarks'] :$single_loan_investment->remarks;
  $prev_parent_source_id = $prev_data && isset( $prev_data['parent_source_id'] ) ? $prev_data['parent_source_id'] : $single_loan_investment->parent_source_id;
  $prev_trn_type         = $prev_data && isset( $prev_data['trn_type'] ) ? $prev_data['trn_type'] : $single_loan_investment->trn_type;

?>
<div class="flex items-center justify-center p-12">
  <div class="mx-auto w-full max-w-[550px]">
    <form action="" method="post">
    <input type="hidden" name="loan_or_investment" value="<?php echo $page == 'loan' ? 1 : 2; ?>">
    <input type="hidden" name="id" value="<?php echo $single_loan_investment->id; ?>">
    <div class="mb-5">
      <label for="name" class="mb-3 block text-base font-medium text-[#07074D]"><?php $page == 'loan' ? _e("Loan Transaction Type") : _e("Investment Transaction Type"); ?></label>
      <select name="trn_type" id="trn_type" class="w-96 rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md">
          <?php 
          $transaction_type = $page == 'loan' ? [1=>'Recieve', 'Pay'] : [3=>'Investment', 'Earning'];
          foreach ( $transaction_type as $key => $value) {
            $selected = isset( $this->prev_data ) && isset( $this->prev_data['trn_type'] ) && $this->prev_data['trn_type'] == $key ? 'selected' : null;
             ?>
          <option value="<?php echo $key; ?>" <?php echo $selected; ?>><?php echo $value; ?></option>
          <?php } ?>
      </select>
    </div>

    <div class="mb-5">
        <label for="name" class="mb-3 block text-base font-medium text-[#07074D]"><?php _e("Parent Source"); ?></label>
        <select name="parent_source_id" id="parent_source_id" class="w-96 rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md">
            <option value="no_parent"><?php _e("No Parent Source"); ?></option>
            <?php 
            if ( count( $is_parent ) == 0 ) {
            foreach ( $parent_data as $key => $value) {
                 $selected = (isset( $this->prev_data ) 
                 && isset( $this->prev_data['parent_source_id'] ) 
                 && $this->prev_data['parent_source_id'] == $value->id) 
                 || ( $single_loan_investment->parent_source_id == $value->id) ? 'selected' : null;

                ?>
                <option value="<?php echo $value->id; ?>" <?php echo $selected; ?>><?php echo $value->source_name;  ?></option>
            <?php } }?>
        </select>

        <?php if ( $invalid_parent_source_error ) { ?>
                <p class="text-base text-red-600 italic font-bold"><?php echo $invalid_parent_source_error; ?></p>
        <?php } ?>

        <?php if ( $missing_parent_investment_earning_error ) { ?>
                <p class="text-base text-red-600 italic font-bold"><?php echo $missing_parent_investment_earning_error; ?></p>
        <?php } ?>
    </div>
    
    <?php 
      if ( $prev_parent_source_id == 'no_parent' || ! $prev_parent_source_id) {
    ?>
    <div class="mb-5" id="loan_investment_source">
        <label for="name" class="mb-3 block text-base font-medium text-[#07074D]"><?php _e("Source Name"); ?></label>
        <input
          type="text"
          name="source_name"
          value="<?php echo $prev_source_name; ?>"
          id="source_name"
          placeholder="<?php _e("Enter Source Name", "wpcodal-pf"); ?>"
          class="w-96 rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
        />
        <?php if ( $source_name_error ) { ?>
                <p class="text-base text-red-600 italic font-bold"><?php echo $source_name_error; ?></p>
        <?php } ?>
    </div>
    <?php } ?>
    
    
    <div class="mb-5">
      <label
              for="date"
              class="mb-3 block text-base font-medium text-[#07074D]"
            >
            <?php $page == 'loan'? _e("Loan") : _e("Investment"); ?> Date
            </label>
            <input
              type="date"
              name="entry_date"
              value="<?php echo $prev_entry_date; ?>"
              id="loan_investment_entry_date"
              class="w-96 rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
            />
            <?php if ( $greater_entry_date_error ) { ?>
            <p class="text-base text-red-600 italic font-bold"><?php echo $greater_entry_date_error; ?></p>
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
          placeholder="<?php _e("Enter Amount", "wpcodal-pf"); ?>"
          class="w-96 rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
        />
        <?php if ( $amount_error ) { ?>
            <p class="text-base text-red-600 italic font-bold"><?php echo $amount_error; ?></p>
        <?php } ?>

        <?php if ( $amount_validation_failed_error ) { ?>
            <p class="text-base text-red-600 italic font-bold"><?php echo $amount_validation_failed_error; ?></p>
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
                $button = $page == 'loan' ? 'Add Loan':'Add Investment';
                wp_nonce_field( 'loan_investment' ); 
                submit_button( __( $button, 'wpcodal-pf' ), 'primary hover:shadow-form rounded-md bg-[#6A64F1] py-2 px-8 text-base font-semibold text-white outline-none', 'submit_loan_investment' );
            ?>
      </div>
    </form>
  </div>
</div>