;(function($) {

    var success_message = document.querySelector('.success-message');
    
    setTimeout(function(){
        if ( success_message ) {
            success_message.style.display = 'none'
        }
        
    }, 3000)
    console.log('talha', success_message);

})(jQuery);