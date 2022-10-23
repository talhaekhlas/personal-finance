<form action="" method="post">
  <div class="-mx-3 flex flex-wrap">
      
        <div class="w-full px-3 sm:w-1/4 mt-10">
          <div>
          <label
              for="date"
              class="mb-3 block text-base font-medium text-[#07074D]"
            >
            <?php _e("Start Date"); ?>
          </label>
            <input
              type="date"
              name="start_date"
              value=""
              id="<?php echo $page ?>_entry_date"
              class="w-[100%] rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
            />
          </div>
        </div>

        <div class="w-full px-3 sm:w-1/4 mt-10">
          <div>
          <label
              for="date"
              class="mb-3 block text-base font-medium text-[#07074D]"
            >
            <?php _e("End Date"); ?>
          </label>
            <input
              type="date"
              name="end_date"
              value=""
              id="<?php echo $page ?>_entry_date"
              class="w-[100%] rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
            />
          </div>
        </div>

        <div class="w-full px-3 sm:w-1/4 mt-10">
          <div>
          <?php
          if ( $page == 'expense' ) {
          ?> 
          <label for="name" class="mb-3 block text-base font-medium text-[#07074D]"><?php $page == 'income'? _e("Income") : _e("Expense"); ?> Sector Name</label>
          <select name="<?php echo $page?>_sector_id" id="budget_for_expense_id" class="w-[100%] rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md">
          </select>
          <?php } else { ?>
            <label for="name" class="mb-3 block text-base font-medium text-[#07074D]"><?php $page == 'income'? _e("Income") : _e("Expense"); ?> Sector Name</label>
          <select name="<?php echo $page?>_sector_id" id="<?php echo $page?>_sector_id" class="w-[100%] rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md">

            <?php 
            foreach ( $income_sector_by_id as $sector_id => $sector_name ) { 
            ?>
            <option value="<?php echo $sector_id; ?>"><?php echo $sector_name; ?></option>
            <?php } ?>
          </select>
          <?php } ?>
          </div>
        </div>
        <div class="w-full px-3 sm:w-1/4 mt-11">
          <div>
          <?php 
                $button = $page == 'income' ? 'Search Income':'Expense Expense';
                wp_nonce_field( 'search_income_expense' ); 
                submit_button( __( $button, 'wpcodal-pf' ), 'primary hover:shadow-form rounded-md bg-[#6A64F1] px-8 text-base font-semibold text-white outline-none mt-[12px]', 'submit_search_income_expense' );
            ?>
          </div>
        </div>
  </div>
  </form>