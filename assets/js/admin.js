function JSconfirm( delete_url ){

    swal({
        title: "Are you sure?",
        text: "Once deleted, you will not be able to recover this imaginary file!",
        icon: "warning",
        buttons: true,
        dangerMode: true,
        
      })
      .then((willDelete) => {
        if (willDelete) {
          swal("Poof! Your imaginary file has been deleted!", {
            icon: "success",
            timer: 3000,
          });
          window.location.href = delete_url;
        } else {
          swal("Your imaginary file is safe!",{
            timer: 3000,
          });
          console.log('not saved')
          
        }
      });
}



;(function($) {
    var success_message = document.querySelector('.success-message');
    
    setTimeout(function(){
        if ( success_message ) {
            success_message.style.display = 'none'
        }
        
    }, 3000)

    $( "#entry_date" ).change(function() {
      var entry_date = $('#entry_date').val();
      $.post( some_localize_info.ajax_url, { 'action': 'expense_budget_id_by_date', 'data': {entry_date}, '_ajax_nonce' : some_localize_info.ajd_nonce }, function ( response ) {
        var str = "<option value='' disabled selected>" + "Select your option" + "</option>";
        for (var item of response.data) {
          str += "<option>" + item.name + "</option>"
        }
        document.getElementById("pickone").innerHTML = str;
        console.log('returned ajax response', response.data)
      });
    });

    

})(jQuery);