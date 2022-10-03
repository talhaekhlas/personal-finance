<?php 
$noficaiton_message = null;
    if ( isset( $_GET['inserted'] ) ) {
      $noficaiton_message = $_GET['page'] == 'income_sector' ? 'Income sector added successfully' : 'Expense sector added successfully';
    }
    if ( isset( $_GET['updateee'] ) ) {
      $noficaiton_message = $_GET['page'] == 'income_sector' ? 'Income sector updated successfully' : 'Expense sector updated successfully';
    }
    if ( isset( $_REQUEST['sector-deleted']  ) ) {
      $noficaiton_message = $_GET['page'] == 'income_sector' ? 'Income sector deleted successfully' : 'Expense sector deleted successfully';
    }

    if ( isset( $_GET['inserted_expense_budget'] ) ) {
      $noficaiton_message = __("Expense Budget Saved Successfully");
    }

    if ( isset( $_GET['updateee_expense_budget'] ) ) {
      $noficaiton_message = __("Expense Budget Updated Successfully");
    }

    if ( $noficaiton_message && $_SESSION && $_SESSION['alert_message'] ) { ?>
        <div class="success-message notice notice-success">
          <p class="text-base text-emerald-900 italic font-bold"><?php echo $noficaiton_message; ?></p>
        </div>
<?php } $_SESSION["alert_message"] = false;?>