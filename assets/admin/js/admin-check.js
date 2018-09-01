function enableDisableMail(bEnable)
{
    document.getElementById('travel_tour_propietario_data_mail').disabled = !bEnable
}
var validatePassword=function() {


    if (document.getElementById('propietario_data_value_password').value ==document.getElementById('propietario_data_value_password_check').value) {
        document.getElementById('message').style.color = 'green';
        document.getElementById('message').innerHTML = 'Matching';
        document.getElementById('secure-value').value=1;
    } else {
        document.getElementById('message').style.color = 'red';
        document.getElementById('message').innerHTML = 'Not matching';
        document.getElementById('secure-value').value=0;
    }
}

var validateUser =function divlightbox() {
    // define the $ as jQuery for multiple uses
    jQuery(function($) {
        /*Add loading image*/
        var $el = $("#message-user");

        $('#loading-image').show();
        $.ajax({
            type: 'POST',
            url: load.ajaxurl,
            //contentType: "application/json; charset=utf-8",
            dataType: "json",
            data: {
                'action': 'user_search',
                'nonce': load.nonce,
                'username' : $('#propietario_data_value_username').val()
            },
            success: function(data) {
                //console.log(data);

                //console.log(data.result);
                if(data.result){
                    document.getElementById('message-user').style.color = 'red';
                    document.getElementById('message-user').innerHTML = '!El usuario existe!';
                    document.getElementById('secure-value').value=1;
                }else{
                    document.getElementById('message-user').style.color = 'green';
                    document.getElementById('message-user').innerHTML = 'Disponible';
                    document.getElementById('secure-value').value=0;
                }


            },
            complete: function(){
                //$el.empty();
                $('#loading-image').hide();
            }
        });
    });
}

var changeVisibility=function visibilityChangeOnPassword(){
    jQuery(function($){
        var passwordType=$('#propietario_data_value_password').attr('type');
        if(passwordType==='password'){
            $('#propietario_data_value_password').attr('type', 'text');
            $('#show_password .dashicons')
                .removeClass("dashicons-visibility")
                .addClass("dashicons-hidden");
        }else{
            $('#propietario_data_value_password').attr('type', 'password');
            $('#show_password .dashicons')
                .removeClass("dashicons-hidden")
                .addClass("dashicons-visibility");
        }
    });
}

var changeVisibilityPswChange=function visibilityOnChangePassword(){
    jQuery(function($){
        var passwordType=$('#propietario_data_value_password_change').attr('type');
        if(passwordType==='password'){
            $('#propietario_data_value_password_change').attr('type', 'text');
            $('#show_password_chang .dashicons')
                .removeClass("dashicons-visibility")
                .addClass("dashicons-hidden");
        }else{
            $('#propietario_data_value_password_change').attr('type', 'password');
            $('#show_password_chang .dashicons')
                .removeClass("dashicons-hidden")
                .addClass("dashicons-visibility");
        }
    });
}


var onlyselectOne=function onleSelectOneUser(){
    jQuery(function($){
        $('.propietario-search-exits-users').on('change', function() {
            $('.propietario-search-exits-users').not(this).prop('checked', false);

        });
    });
}

jQuery(document).ready(function ($) {
    $( '#travel_tour_propietario_data_mail' ).on('keyup',function(e) {
        e.preventDefault();
        var mail= $("input[name=\"email\"]").val();

        if(mail==""){
            document.getElementById('message-mail').innerHTML="";
            document.getElementById('message-mail').style.color="none";
            document.getElementById('secure-value').value=0;
        }else{
            // call ajax
            $.ajax({
                type: 'POST',
                url: load.ajaxurl,
                //contentType: "application/json; charset=utf-8",
                dataType: "json",
                data: {
                    'action': 'email_check',
                    'nonce': load.nonce,
                    'email': mail
                },
                success: function(data) {

                    if(data.result){
                        document.getElementById('message-mail').style.color = 'red';
                        document.getElementById('message-mail').innerHTML = '!El correo ya está en uso!';
                        document.getElementById('secure-value').value=0;
                    }else{
                        if ($('#checkBox').length > 0) {
                            var email_value=$('input[name="email"]').val();
                            $('#travel_tour_propietario_data_mail').attr("value", email_value);
                            //$('#travel_tour_propietario_data_mail').attr("value", $('input[name="email"]').val());
                        }

                        document.getElementById('message-mail').style.color = 'green';
                        document.getElementById('message-mail').innerHTML = 'Disponible';
                        document.getElementById('secure-value').value=1;
                    }
                }
            });
        }

    });

    $("#propietario_data_value_carnet,#travel_tour_propietario_data_cp")
        .keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
            // Allow: Ctrl+A, Command+A
            (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) ||
            // Allow: home, end, left, right, down, up
            (e.keyCode >= 35 && e.keyCode <= 40)) {
            // let it happen, don't do anything
            return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });
    $( 'input[name="email_nauta"]' ).change(function(e) {
        e.preventDefault();
        var user= $("input[name='email_nauta']").val();
        var reg = /^[a-z.]+@nauta.com.cu$/;
        if (!reg.test(user)) {
            cons
        }
        // call ajax

    });

});


///^[^@\s]+@yahoo.com$/i.test(email)