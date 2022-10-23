<div class="w-4/5 mx-auto">
  <?php 
    include WPCPF_PLUGIN_DIR . '/templates/notification.php'; 
   ?>

  <div>
    <a href="<?php echo admin_url( "admin.php?page={$page}&action=new" ); ?>">
    <button
      class="hover:shadow-form rounded-md bg-[#6A64F1] mt-5 py-2 px-8 text-base font-semibold text-white outline-none"
    >
      Add New
    </button>
    </a>
  </div>

  <div class="-mx-3 flex flex-wrap">
        <div class="w-full px-3 sm:w-1/2">
          <div class="mb-5">
            <label
              for="fName"
              class="mb-3 block text-base font-medium text-[#07074D]"
            >
              First Name
            </label>
            <input
              type="text"
              name="fName"
              id="fName"
              placeholder="First Name"
              class="w-full rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
            />
          </div>
        </div>
        <div class="w-full px-3 sm:w-1/2">
          <div class="mb-5">
            <label
              for="lName"
              class="mb-3 block text-base font-medium text-[#07074D]"
            >
              Last Name
            </label>
            <input
              type="text"
              name="lName"
              id="lName"
              placeholder="Last Name"
              class="w-full rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
            />
          </div>
        </div>
      </div>
  
  <div class="bg-white shadow-md rounded my-6">
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
         $total_amount = 0;
         foreach($data as $value) { 
          $total_amount = $total_amount + $value->amount;
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
        <tr>
          <td class="py-4 px-6 border-b border-grey-light text-lg" colspan="2">Total <?php echo $page; ?></td>
          <td class="py-4 px-6 border-b border-grey-light text-lg" colspan="3"><?php echo $total_amount; ?></td>
        </tr>
      </tbody>
    </table>
  </div>
</div>