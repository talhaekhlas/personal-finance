<?php 
$trn_head_class = "w-10 py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light";
$trn_amount_class = "w-10 py-4 px-6 bg-grey-lightest text-sm text-black border-b border-grey-light";
?>

<div class="float-left w-[45%] ml-[50px]">
  <div class="bg-white shadow-md rounded mt-20">
    <table class="text-left w-full border-collapse"> <!--Border collapse doesn't work on this site yet but it's available in newer tailwind versions -->
      <caption>
        <p class="text-2xl text-emerald-900 font-bold"><?php _e("Transaction Summary"); ?></p>
      </caption>
      <thead>
        <?php 
            if ( isset( $this->expense_validation_info['budget_amount'] ) ) { ?>
                <tr>
                    <td class="<?php echo $trn_head_class ?>">
                        Expense Source
                    </td>
                    <td class="<?php echo $trn_amount_class ?>">
                        Demo
                    </td>
                </tr>

                <tr>
                    <td class="<?php echo $trn_head_class ?>">
                        Budget Amount
                    </td>
                    <td class="<?php echo $trn_amount_class ?>">
                        <?php echo $this->expense_validation_info['budget_amount']; ?>
                    </td>
                </tr>
                <tr>
                    <td class="<?php echo $trn_head_class ?>">
                        Expense from budget
                    </td>
                    <td class="<?php echo $trn_amount_class ?>">
                        <?php echo $this->expense_validation_info['total_expense_from_budget_amount']; ?>
                    </td>
                </tr>
                <tr>
                    <td class="<?php echo $trn_head_class ?>">
                        Submit Amount
                    </td>
                    <td class="<?php echo $trn_amount_class ?>">
                        <?php echo $this->expense_validation_info['submit_amount']; ?>
                    </td>
                </tr>
                <tr>
                    <td class="<?php echo $trn_head_class ?> text-center text-red-500" colspan="2">
                        You don have enough amount to expense by this sector
                    </td>
                </tr>
            <?php } else { ?>
        <tr>
          <td class="<?php echo $trn_head_class ?> text-rose-700" colspan="2" align="center">Cash in Summary</td>
        </tr>
        <tr>
          <td class="<?php echo $trn_head_class ?>">Total Income</td>
          <td class="<?php echo $trn_amount_class ?>">0</td>
        </tr>
        <tr>
          <td class="<?php echo $trn_head_class ?>">Loan Recieve</td>
          <td class="<?php echo $trn_amount_class ?>">0</td>
        </tr>
        <tr>
          <td class="<?php echo $trn_head_class ?>">Investment Earning</td>
          <td class="<?php echo $trn_amount_class ?>">0</td>
        </tr>
        <tr>
          <td class="<?php echo $trn_head_class ?> text-rose-700" colspan="2" align="center">Cash Out Summary</td>
        </tr>
        <tr>
          <td class="<?php echo $trn_head_class ?>">Expense From Budget</td>
          <td class="<?php echo $trn_amount_class ?>">0</td>
        </tr>
        <tr>
          <td class="<?php echo $trn_head_class ?>">Expense Out From Budget</td>
          <td class="<?php echo $trn_amount_class ?>">0</td>
        </tr>
        <tr>
          <td class="<?php echo $trn_head_class ?>">Loan Pay</td>
          <td class="<?php echo $trn_amount_class ?>">0</td>
        </tr>
        <tr>
          <td class="<?php echo $trn_head_class ?>">Investment</td>
          <td class="<?php echo $trn_amount_class ?>">0</td>
        </tr>
        <?php } ?>
      </thead>
      <tbody>
       
      </tbody>
    </table>
  </div>
</div>