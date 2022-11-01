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

  <?php 
    include WPCPF_PLUGIN_DIR . '/templates/income-expense/search-income-expense-form.php'; 
    $th_class = "py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light";
    $td_class = "py-2 px-6 border-b border-grey-light";
   ?>
  
  <div class="bg-white shadow-md rounded mt-[1px] mb-[5px]">
    <table class="text-left w-full border-collapse"> <!--Border collapse doesn't work on this site yet but it's available in newer tailwind versions -->
      <caption>
        <p class="text-2xl text-emerald-900 font-bold"><?php $page == 'income'? _e("Income List") : _e("Expense List"); ?></p>
      </caption>
      <thead>
        <tr>
          <th class="w-10 <?php echo $th_class; ?>">Sl</th>
          <th class="<?php echo $th_class; ?>"><?php $page == 'income'? _e("Income") : _e("Expense"); ?> Sector</th>
          <th class="<?php echo $th_class; ?>">Amount</th>
          <th class="<?php echo $th_class; ?>">Entry Date</th>
          <th class="<?php echo $th_class; ?>">Remarks</th>
          <th class="<?php echo $th_class; ?>">Actions</th>
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
          <td class="<?php echo $td_class; ?>"><?php echo ++$sl; ?></td>
          <td class="<?php echo $td_class; ?>"><?php echo $value->name; ?></td> 
          <td class="<?php echo $td_class; ?>"><?php echo $value->amount; ?></td>
          <td class="<?php echo $td_class; ?> text-red-400 italic font-extrabold"><?php echo $value->entry_date; ?></td>
          <td class="<?php echo $td_class; ?>">
          <button data-tooltip-target="tooltip-light" data-tooltip-style="light" type="button" class="text-white font-bold py-1 px-3 rounded text-xs bg-blue-500 hover:bg-blue-dark">Remarks</button>
          <div id="tooltip-light" role="tooltip" class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-black rounded-lg border border-gray-200 shadow-sm opacity-0 tooltip">
              <?php echo $value->remarks; ?>
          </div>
          </td>
          <td class="<?php echo $td_class; ?>">
          <?php 
            $edit_url = admin_url( "admin.php?page={$page}&action=edit&id={$value->id}") ; 
          ?>  
          <a href="<?php echo $edit_url; ?>" class="text-white font-bold py-1 px-3 rounded text-xs bg-blue-500 hover:bg-green-dark">Edit</a>
          <?php $delete_url = wp_nonce_url( admin_url( "admin.php?page={$page}&delete_{$page}_action=wpcpf-delete-{$page}&id=" . $value->id ), "wpcpf-delete-{$page}" ); ?>
          <a 
            href="#" 
            onclick="JSconfirm('<?php echo $delete_url;  ?>')" 
            class="text-white font-bold py-1 px-3 rounded text-xs bg-red-500 hover:bg-blue-dark">
              Delete
          </a>
          </td>
        </tr>
        <?php }
        if ( $total_amount ) {
        ?>
        <tr>
          <td class="py-4 px-6 border-b border-grey-light text-lg" colspan="2">Total <?php echo $page; ?></td>
          <td class="py-4 px-6 border-b border-grey-light text-lg" colspan="3"><?php echo $total_amount; ?></td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
  <div class="float-right">
      <nav aria-label="Page navigation example">
      <ul class="inline-flex items-center -space-x-px">
        <li>
          <a href="#" class="block py-2 px-3 ml-0 leading-tight text-gray-500 bg-white rounded-l-lg border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
            <span class="sr-only">Previous</span>
            <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
          </a>
        </li>
        <?php for ( $i=1; $i<= count($data); $i = $i + 2) { ?>
        <li>
          <a href="#" class="py-2 px-3 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"><?php echo ceil($i/2); ?></a>
        </li>
        <?php } ?>
        
        <li>
          <a href="#" class="block py-2 px-3 leading-tight text-gray-500 bg-white rounded-r-lg border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
            <span class="sr-only">Next</span>
            <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
          </a>
        </li>
      </ul>
    </nav>   
  </div>
  
</div>