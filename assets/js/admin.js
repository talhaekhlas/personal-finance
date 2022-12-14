function deleteConfirmation( delete_url ){
    swal({
        title: "Are you sure?",
        text: "Once deleted, you will not be able to recover this imaginary file!",
        icon: "warning",
        buttons: true,
        dangerMode: true,
        
      })
      .then(( willDelete ) => {
        if ( willDelete ) {
          swal("Poof! Your imaginary file has been deleted!", {
            icon: "success",
            timer: 3000,
          });
          window.location.href = delete_url;
        } else {
          swal("Your imaginary file is safe!",{
            timer: 3000,
          }); 
        }
      });
}

//Recent date as max date set for income and expense
if ( document.querySelector("#expense_entry_date") ) {
  expense_entry_date.max = new Date().toISOString().split("T")[0];
}

if ( document.querySelector("#income_entry_date") ) {
  income_entry_date.max = new Date().toISOString().split("T")[0];
}

//Start date and end date for expense sector id
if ( document.querySelector("#expense_start_date") ) {
  expense_start_date.max = new Date().toISOString().split("T")[0];
}
if ( document.querySelector("#expense_end_date") ) {
  expense_end_date.max = new Date().toISOString().split("T")[0];
}

// Recent date as max date for searching income
if ( document.querySelector("#income_start_date") ) {
  income_start_date.max = new Date().toISOString().split("T")[0];
}
if ( document.querySelector("#income_end_date") ) {
  income_end_date.max = new Date().toISOString().split("T")[0];
}

//Recent date as max date set for loan and investment
if ( document.querySelector("#loan_investment_entry_date") ) {
  loan_investment_entry_date.max = new Date().toISOString().split("T")[0];
}

;(function($) {
    var success_message = document.querySelector('.success-message');
    setTimeout(function(){
        if ( success_message ) {
            success_message.style.display = 'none'
        }  
    }, 3000)

    $( "#expense_entry_date" ).change(function() {
      var entry_date = $('#expense_entry_date').val();
      $.post( some_localize_info.ajax_url, { 'action': 'expense_budget_id_by_date', 'data': {entry_date}, '_ajax_nonce' : some_localize_info.ajd_nonce }, function ( response ) {
        var str = "<option value='' disabled selected>" + "Select Expense Sector" + "</option>";
        for (var item of response.data) {
          str += "<option value=" + item.budget_for_expense_id + ">" + item.expense_sector_name + "</option>"
        }
        document.getElementById("budget_for_expense_id").innerHTML = str;
      });
    });

    $( "#parent_source_id" ).change(function() {
      var parentId = $('#parent_source_id').val();
      var trnType  = $('#trn_type').val();
      if ( parentId == 'no_parent' && trnType != 4 ) {
        $("#loan_investment_source").css("display", "block");
      } else {
        $("#loan_investment_source").css("display", "none");
      }
    });

    $( "#trn_type" ).change(function() {
      var trnType  = $('#trn_type').val();
      var parentId = $('#parent_source_id').val();
      if ( trnType != 4 && parentId == 'no_parent' ) { //trn type 4 means earning from investment.
        $("#loan_investment_source").css("display", "block"); 
      } else {
        $("#loan_investment_source").css("display", "none");
      }
    });
})(jQuery);
