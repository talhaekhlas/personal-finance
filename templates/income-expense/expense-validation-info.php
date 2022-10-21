<?php 
$trn_head_class = "w-10 py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light";
$trn_amount_class = "w-10 py-4 px-6 bg-grey-lightest text-sm text-black border-b border-grey-light";
?>

<div class="float-left w-[45%]">
  <div class="bg-white shadow-md rounded mt-20">
    <table class="text-left w-full border-collapse"> <!--Border collapse doesn't work on this site yet but it's available in newer tailwind versions -->
      <caption>
        <p class="text-2xl text-emerald-900 font-bold"><?php _e("Transaction Summary"); ?></p>
      </caption>
      <thead>
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
      </thead>
      <tbody>
       
      </tbody>
    </table>
  </div>
</div>