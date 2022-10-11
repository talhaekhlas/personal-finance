<div class="w-4/5 mx-auto">
  <?php 
    include WPCPF_PLUGIN_DIR . '/templates/notification.php'; 

    $source_name_by_id = [];

    foreach ( $data as $value ) {
      $source_name_by_id[ $value->id ] = $value->source_name;
    }

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
  <div class="bg-white shadow-md rounded my-6">
    <table class="text-left w-full border-collapse"> <!--Border collapse doesn't work on this site yet but it's available in newer tailwind versions -->
      <caption>
        <p class="text-2xl text-emerald-900 font-bold"><?php $page == 'loan'? _e("Loan List") : _e("Investment List"); ?></p>
      </caption>
      <thead>
        <tr>
          <th class="w-10 py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light">Sl</th>
          <th class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light"><?php _e("Source Name"); ?></th>
          <th class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light"><?php _e("Transaction Type"); ?></th>
          <th class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light">Amount</th>
          <th class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light">Entry Date</th>
          <th class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light">Remarks</th>
          <th class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php
         $transaction_type = [1=>'Recieve', 'Pay', 'Investment', 'Earning'];
         $sl = 0;
         foreach($data as $value) { 
        ?>
        <tr class="hover:bg-grey-lighter">
          <td class="py-4 px-6 border-b border-grey-light"><?php echo ++$sl; ?></td>
          <td class="py-4 px-6 border-b border-grey-light">
            <?php echo $value->source_name ? $value->source_name : $source_name_by_id[ $value->parent_source_id ]. ' ( <span style="color:red; font-weight:bold">as parent source</span> )'; ?>
          </td>
          <td class="py-4 px-6 border-b border-grey-light"><?php echo $transaction_type[$value->trn_type]; ?></td>
           
          <td class="py-4 px-6 border-b border-grey-light"><?php echo $value->amount; ?></td>
          <td class="py-4 px-6 border-b border-grey-light"><?php echo $value->entry_date; ?></td>
          <td class="py-4 px-6 border-b border-grey-light"><?php echo $value->remarks; ?></td>
          <td class="py-4 px-6 border-b border-grey-light">
          <?php 
            $edit_url = admin_url( "admin.php?page={$page}&action=edit&id={$value->id}") ; 
          ?>  
          <a href="<?php echo $edit_url; ?>" class="text-white font-bold py-1 px-3 rounded text-xs bg-blue-500 hover:bg-green-dark">Edit</a>
          <?php $delete_url = wp_nonce_url( admin_url( "admin.php?page={$page}&delete_loan_invest_action=wpcpf-delete-loan-investment&id=" . $value->id ), "wpcpf-delete-loan-investment" ); ?>
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