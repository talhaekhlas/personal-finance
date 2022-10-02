<?php 
  
  if ( isset( $this->errors ) ) {
      $name_error = isset( $this->errors ) ? $this->errors['name'] : null;
  }
?>
<div class="flex items-center justify-center p-12">
  <div class="mx-auto w-full max-w-[550px]">
    <form action="" method="post">
    <div class="mb-5">
      <label for="name" class="mb-3 block text-base font-medium text-[#07074D]">Component Select</label>
      <select class="w-full rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md">
        <option>Surabaya</option>
        <option>Bandung</option>
        <option>jakarta</option>
      </select>
    </div>
    <div class="mb-5">
        <label
          for="name"
          class="mb-3 block text-base font-medium text-[#07074D]"
        >
          Sector Name budget
        </label>
        <input
          type="text"
          name="name"
          id="name"
          placeholder="<?php echo $placeholder; ?>"
          class="w-full rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
        />
      </div>
      <div class="mb-5">
        <label
          for="name"
          class="mb-3 block text-base font-medium text-[#07074D]"
        >
          Sector Name budget
        </label>
        <input
          type="text"
          name="name"
          id="name"
          placeholder="<?php echo $placeholder; ?>"
          class="w-full rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
        />

        <?php if ( $name_error ) { ?>
            <p class="text-base text-red-600 italic font-bold"><?php echo $name_error; ?></p>
        <?php } ?>
       
        <input type="hidden" name="type" value="<?php echo $type; ?>">
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