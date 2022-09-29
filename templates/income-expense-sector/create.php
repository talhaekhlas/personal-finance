<?php 
    $button_title = $sector_type == 'income_sector' ? 'Add Income Sector' : 'Add Expense Sector';
    $placeholder = $sector_type == 'income_sector' ? 'Please Enter Income Sector' : 'Please Enter Expense Sector';
?>
<div class="flex items-center justify-center p-12">
  <!-- Author: FormBold Team -->
  <!-- Learn More: https://formbold.com -->
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
          id="name"
          placeholder="<?php echo $placeholder; ?>"
          class="w-full rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
        />
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