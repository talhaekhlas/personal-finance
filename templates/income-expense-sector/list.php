<div class="w-4/5 mx-auto">
  <?php 
	include WPCPF_PLUGIN_DIR . '/templates/notification.php'; 
   ?>
  <div> 
	<a href="<?php echo admin_url( "admin.php?page={$sector_type}_sector&action=new" ); ?>">
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
		<p class="text-2xl text-emerald-900 font-bold"><?php echo $title; ?></p>
	  </caption>
	  <thead>
		<tr>
		  <th class="w-10 py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light">Sl</th>
		  <th class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light">Name</th>
		  <th class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light">Actions</th>
		</tr>
	  </thead>
	  <tbody>
		<?php
		global $wp;
		$total_amount = array_sum(array_column($data, 'amount'));
		$total_transaction = count($data);
		$page_no = isset( $_GET['test_page'] ) ? $_GET['test_page'] : 1;
		if ( isset( $_GET['test_page'] ) ){
			unset( $_GET['test_page'] );
		}
		$extra_parameter = add_query_arg(array($_GET), $wp->request);         
		$show_limit      = 2;
		$total_link      = ceil( count($data)/$show_limit );
		$showing_data    = array_slice($data, ($page_no-1)*$show_limit, $show_limit);
		$sl = 0;
		foreach($showing_data as $value) { 
			$extra_class   = in_array($value->id, $all_used_income_ids) ? 'text-blue-500 underline':null;
			$date_range    = "start_date=&end_date=";
			$for_income    = "$date_range&income_sector_id={$value->id}";
			$redirected_to = admin_url( "admin.php?page=income&{$for_income}" );
		?>
		<tr class="hover:bg-grey-lighter">
			<td class="py-4 px-6 border-b border-grey-light"><?php echo ++$sl; ?></td>
			<?php if ($extra_class) { ?>
			<td class="py-4 px-6 border-b border-grey-light <?php echo $extra_class; ?>"><a href="<?php echo $redirected_to; ?>"><?php echo $value->name; ?></a></td>
			<?php } else { ?>
			<td class="py-4 px-6 border-b border-grey-light"><?php echo $value->name; ?></td>
			<?php } ?>  
			<td class="py-4 px-6 border-b border-grey-light">
			<?php $edit_url = admin_url( "admin.php?page={$sector_type}_sector&action=edit&id={$value->id}") ; ?>  
			<a href="<?php echo $edit_url; ?>" class="text-white font-bold py-1 px-3 rounded text-xs bg-blue-500 hover:bg-green-dark">Edit</a>
			<?php $delete_url = wp_nonce_url( admin_url( "admin.php?page={$sector_type}_sector&delete_sector_action=wpcpf-delete-sector&id=" . $value->id ), 'wpcpf-delete-sector' ); ?>
			<a 
			href="#" 
			onclick="deleteConfirmation('<?php echo $delete_url;  ?>')" 
			class="text-white font-bold py-1 px-3 rounded text-xs bg-red-500 hover:bg-blue-dark">
				Delete
			</a>
			</td>
		</tr>
		<?php } ?>
	  </tbody>
	</table>
  </div>

  <?php 
	include WPCPF_PLUGIN_DIR . '/templates/pagination.php'; 
  ?>
</div>