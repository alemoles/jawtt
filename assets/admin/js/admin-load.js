jQuery(document).ready(function($) {
    $( '#travel_tour_propietario_data_provincia' ).change(function(e) {
        e.preventDefault();
        var provincia= $("#travel_tour_propietario_data_provincia option:selected").val();
        // call ajax
        $.ajax({
            type: 'POST',
            url: load.ajaxurl,
            //contentType: "application/json; charset=utf-8",
            dataType: "json",
            data: {
                'action': 'update_municipios',
                'nonce': load.nonce,
                'provincia': provincia
            },
            success: function(data) {
                var newOptions = data;
                var $el = $("#travel_tour_propietario_data_municipio");
                 $el.empty(); // remove old options
                 $.each(newOptions, function(key,value) {
                     $el.append($("<option></option>")
                         .attr("value", key).text(value));
                 });
            }
        });
    });
    var provincia=$.trim($('input:hidden[name=provincia-value]').val());
    if(provincia==""){
        provincia= $("#travel_tour_propietario_data_provincia option:selected").val();
    }

    var id=$('input:hidden[name=municipio-value]').val();
    var id_municipio=parseInt(id, 10);
    $.ajax({
        type: 'POST',
        url: load.ajaxurl,
        //contentType: "application/json; charset=utf-8",
        dataType: "json",
        data: {
            'action': 'update_municipios',
            'nonce': load.nonce,
            'provincia': provincia
        },
        success: function(data) {
            var newOptions = data;

            var $el = $("#travel_tour_propietario_data_municipio");
            $el.empty(); // remove old options
            $.each(newOptions, function(key,value) {
                if(id_municipio===key){
                    $el.append($("<option></option>")
                        .attr("value", key)
                        .attr("selected",'selected')
                        .text(value));
                }else{
                    $el.append($("<option></option>")
                        .attr("value", key).text(value));
                }

            });


        }
    });

    $( 'input[name="user"]' ).change(function(e) {
        e.preventDefault();
        var user= $("input[name='user']:checked").val();
        // call ajax
        $.ajax({
            type: 'POST',
            url: load.ajaxurl,
            data: {
                'action': 'persona_search',
                'nonce': load.nonce,
                'user': user
            },
            success: function(data) {
                var newOptions = data;
                var $el = $("#propietario-data-search");
                $el.empty();
                $el.append(newOptions);

            }
        });
    });

    $('input[name="user"]').change(function(e) {
        e.preventDefault();
        $("#user-field-search").attr("disabled", true);
        if ($("input[name='user']:checked").val() == "existe") {
            $("#user-field-search").attr("disabled", false);
            // var $el = $("#propietario-data-search");
            // $el.empty(); // remove old options
        }else{
            var doc_val_check = $.trim( $('#user-field-search').val() ); // take value of text
            // field using .val()
            if (doc_val_check.length) {
                doc_val_check = ""; // this will not update your text field
                $('#user-field-search').attr('value', doc_val_check);

            }
        }
    });

    $('button[id="btn_cancel_chg_pw"]').on('click', function(e){
        e.preventDefault();
        document.getElementById('pass-data-block').style.display="none";
        document.getElementById('propietario-data-btn-value-pw-change').style.display="inline-block";
        document.getElementById('propietario_data_value_password_change').setAttribute('value', "");
    });

    $('button[id="propietario-data-btn-value-pw-change"]').on('click', function(e){
        e.preventDefault();
        $('show_password_chang').prop("disabled",false);
        document.getElementById('pass-data-block').style.display="inline-block";
        document.getElementById('propietario-data-btn-value-pw-change').style.display="none";
        $.ajax({
            type: 'POST',
            url: load.ajaxurl,
            dataType: 'json',
            data: {
                'action': 'generate_password',
                'nonce': load.nonce
            },
            success: function(data) {

                document.getElementById('propietario_data_value_password_change').setAttribute('value', data.pass);

            },
            complete: function(){
                //$el.empty();
                $('show_password_chang').prop("disabled",true);
            }
        });

    });

});
jQuery(function($){
    $("input[id^='user-select-']").on('change', function() {
         $("input[name='post[]']").each( function () {
             alert( $(this).val() );
         });
        $("input[id^='user-select-']").not(this).prop('checked', false);
    });

    // $("input[name='post[]']").each( function () {
    //     alert( $(this).val() );
    // });
});