<?php 
    $button_title    = $sector_type == 'income' ? 'Update Income Sector' : 'Update Expense Sector';
    $placeholder     = $sector_type == 'income' ? 'Please Enter Income Sector' : 'Please Enter Expense Sector';
    $type            = $sector_type == 'income' ? 1 : 2;
    $name_error      = isset( $this->errors ) && isset($this->errors['name']) ? $this->errors['name'] : null;
    $duplicate_error = isset( $this->errors ) && isset( $this->errors['duplicate_name'] ) ? $this->errors['duplicate_name'] : null;
    $priv_name       = isset( $this->priv_data ) && isset( $this->priv_data['name'] ) ? $this->priv_data['name'] : $single_income_expense->name;
    
?>
<div class="flex items-center justify-center p-12">
  <div class="mx-auto w-full max-w-[550px]">
    <form action="" method="post">
      <div class="mb-5">
        <label
          for="name"
          class="mb-3 block text-base font-medium text-[#07074D]"
        >
          Sector Name
        </label>
        <input
          type="text"
          name="name"
          value="<?php echo $priv_name; ?>"
          id="name"
          placeholder="<?php echo $placeholder; ?>"
          class="w-[100%] rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
        />

        <?php if ( $name_error ) { ?>
            <p class="text-base text-red-600 italic font-bold"><?php echo $name_error; ?></p>
        <?php } ?>

        <?php if ( $duplicate_error ) { ?>
            <p class="text-base text-red-600 italic font-bold"><?php echo $duplicate_error; ?></p>
        <?php } ?>
       
        <input type="hidden" name="type" value="<?php echo $type; ?>">
        <input type="hidden" name="id" value="<?php echo $single_income_expense->id; ?>">
      </div>
      
      <div>
            <?php 
                wp_nonce_field( 'new-income-expense-sector' ); 
                submit_button( __( $button_title, 'wpcodal-pf' ), 'primary hover:shadow-form rounded-md bg-[#6A64F1] py-2 px-8 text-base font-semibold text-white outline-none', 'submit_income_expense_sector' );
            ?>
        
      </div>
    </form>
  </div>
</div>