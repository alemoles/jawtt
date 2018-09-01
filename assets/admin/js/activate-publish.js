jQuery(document).ready(function($) {
$('#publish').on('click', function ($) {

    $.ajax({
        type: 'POST',
        url: load.ajaxurl,
        //contentType: "application/json; charset=utf-8",
        dataType: "json",
        data: {
            'action': 'change_publish_status',
            'nonce': load.nonce,
            'validation': $('#secure-value').val()
        },
        success: function(data) {
            if(data.message){
                //Completar para desactivar boton "Save"
            }else{

            }


        }
    });
});

});