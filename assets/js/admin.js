function JSconfirm(){
    console.log('hamba')
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
        } else {
          swal("Your imaginary file is safe!",{
            timer: 3000,
          });
          
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

})(jQuery);