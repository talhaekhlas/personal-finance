<div class="w-2/3 mx-auto">
  <?php 
    if ( isset( $_GET['inserted'] ) ) {
      $success_message = $_GET['page'] == 'income_sector' ? 'Income sector added successfully' : 'Expense sector added successfully'
  ?>
        <div class="success-message notice notice-success">
        <p class="text-base text-emerald-900 italic font-bold"><?php echo $success_message; ?></p>
        </div>
  <?php } ?>

  <?php 
    if ( isset( $_GET['updateee'] ) ) {
      $updated_message = $_GET['page'] == 'income_sector' ? 'Income sector updated successfully' : 'Expense sector updated successfully'
  ?>
        <div class="success-message notice notice-success">
        <p class="text-base text-emerald-900 italic font-bold"><?php echo $updated_message; ?></p>
        </div>
  <?php } ?>

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
         $sl = 0;
         foreach($data as $value) { 
        ?>
        <tr class="hover:bg-grey-lighter">
          <td class="py-4 px-6 border-b border-grey-light"><?php echo ++$sl; ?></td>
          <td class="py-4 px-6 border-b border-grey-light"><?php echo $value->name; ?></td>
          <td class="py-4 px-6 border-b border-grey-light">
          <?php $edit_url = admin_url( "admin.php?page={$sector_type}_sector&action=edit&id={$value->id}") ; ?>  
          <a href="<?php echo $edit_url; ?>" class="text-white font-bold py-1 px-3 rounded text-xs bg-blue-500 hover:bg-green-dark">Edit</a>
          <a href="#" class="text-white font-bold py-1 px-3 rounded text-xs bg-red-500 hover:bg-blue-dark">Delete</a>
          <?php printf( '<a href="%s" class="submitdelete" onclick="return confirm(\'Are you sure?\');" title="%s">%s</a>', wp_nonce_url( admin_url( 'admin-post.php?action=wd-ac-delete-address&id=' . $value->id ), 'wd-ac-delete-address' ), $value->id, __( 'Delete', 'wedevs-academy' ), __( 'Delete', 'wedevs-academy' ) ); ?>
          </td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</div>