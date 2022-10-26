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
              value="<?php echo isset( $this->prev_data['start_date'] ) ? $this->prev_data['start_date']: $start_date ?>"
              id="<?php echo $page ?>_start_date"
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
              value="<?php echo isset( $this->prev_data['end_date'] ) ? $this->prev_data['end_date']: $end_date ?>"
              id="<?php echo $page ?>_end_date"
              class="w-[100%] rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
            />
          </div>
        </div>

        <div class="w-full px-3 sm:w-1/4 mt-10">
          <div>
          <label for="name" class="mb-3 block text-base font-medium text-[#07074D]">Sector Name</label>
          <select name="expense_sector_id" id="expense_sector_id" class="w-[100%] rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md">
            <option value="All" <?php echo isset( $this->prev_data ) && $this->prev_data['expense_sector_id'] == 'All' ? 'selected' : null; ?> ><?php echo _e("All Sector");; ?></option>
            <?php 
            
            foreach ( $data_for_dropdown as $value ) { 
              $selected = null;
              if ( isset( $this->prev_data) &&  $this->prev_data['expense_sector_id'] == $value->expense_sector_id ) {
                $selected = 'selected';
              }
              if ( $expense_sector_id == $value->expense_sector_id ) {
                $selected = 'selected';
              }
            ?>
            <option value="<?php echo $value->expense_sector_id; ?>" <?php echo $selected; ?> >
              <?php echo $expense_sector_by_id[ $value->expense_sector_id ]; ?>
            </option>
            <?php } ?>
          </select>
          </div>
        </div>
        <div class="w-full px-3 sm:w-1/4 mt-11">
          <div>
          <?php 
                wp_nonce_field( 'search_budget' ); 
                submit_button( __( 'Search Budget', 'wpcodal-pf' ), 'primary hover:shadow-form rounded-md bg-[#6A64F1] px-8 text-base font-semibold text-white outline-none mt-[12px]', 'submit_search_budget' );
            ?>
          </div>
        </div>
  </div>
  </form>
  <?php 
    if ( isset( $this->errors ) ) {
      if ( isset( $this->errors['greater_start_date'] ) ) {
        $error = $this->errors['greater_start_date'];
      }

      if ( isset( $this->errors['end_date'] ) ) {
        $error = $this->errors['end_date'];
      }

      if ( isset( $this->errors['start_date'] ) ) {
        $error = $this->errors['start_date'];
      }
  ?>
  <div class="w-full mb-2">
    <p class="text-lg text-red-600 italic font-bold text-center"><?php echo $error; ?></p>
  </div>
  <?php } ?>