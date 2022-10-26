<div class="w-4/5 mx-auto">
  <?php 
    include WPCPF_PLUGIN_DIR . '/templates/notification.php'; 
   ?>

  <div>
    <a href="<?php echo admin_url( "admin.php?page=expense_budget&action=new" ); ?>">
    <button
      class="hover:shadow-form rounded-md bg-[#6A64F1] mt-5 py-2 px-8 text-base font-semibold text-white outline-none"
    >
      Add New
    </button>
    </a>
  </div>
  <?php 
    include WPCPF_PLUGIN_DIR . '/templates/expense-budget/search-budget-form.php'; 
   ?>
  <div class="bg-white shadow-md rounded my-6">
    <table class="text-left w-full border-collapse"> <!--Border collapse doesn't work on this site yet but it's available in newer tailwind versions -->
      <caption>
        <p class="text-2xl text-emerald-900 font-bold"><?php _e("Budget for Expense List"); ?></p>
      </caption>
      <thead>
        <tr>
          <th class="w-10 py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light">Sl</th>
          <th class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light">Expense Sector</th>
          <th class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light">Budget Amount</th>
          <th class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light">Spent</th>
          <th class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light">In Hand</th>
          <th class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light">Start Date</th>
          <th class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light">End Date</th>
          <th class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php
         $sl = 0;
         foreach($data as $value) { 
          $date_range      = "start_date={$value->start_date}&end_date={$value->end_date}";
          $extra_parameter = "$date_range&budget_for_expense_id={$value->budget_id}";
          $redirected_to   = admin_url( "admin.php?page=expense&{$extra_parameter}" );
        ?>
        <tr class="hover:bg-grey-lighter">
          <td class="py-4 px-6 border-b border-grey-light"><?php echo ++$sl; ?></td>
          <td class="py-4 px-6 border-b border-grey-light underline text-blue-500"><a href="<?php echo $redirected_to; ?>"><?php echo $value->expense_sector_name; ?></a></td>
          <td class="py-4 px-6 border-b border-grey-light"><?php echo $value->amount; ?></td>
          <td class="py-4 px-6 border-b border-grey-light"><?php echo $value->total_expense?$value->total_expense:0; ?></td>
          <td class="py-4 px-6 border-b border-grey-light"><?php echo $value->amount - $value->total_expense; ?></td>
          <td class="py-4 px-6 border-b border-grey-light text-red-400 italic font-extrabold"><?php echo $value->start_date; ?></td>
          <td class="py-4 px-6 border-b border-grey-light text-red-400 italic font-extrabold"><?php echo $value->end_date; ?></td>
          <td class="py-4 px-6 border-b border-grey-light">
          <?php $edit_url = admin_url( "admin.php?page=expense_budget&action=edit&id={$value->budget_id}") ; ?>  
          <a href="<?php echo $edit_url; ?>" class="text-white font-bold py-1 px-3 rounded text-xs bg-blue-500 hover:bg-green-dark">Edit</a>
          <?php $delete_url = wp_nonce_url( admin_url( "admin.php?page=expense_budget&delete_expense_budget_action=wpcpf-delete-expense-budget&id=" . $value->budget_id ), 'wpcpf-delete-expense-budget' ); ?>
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