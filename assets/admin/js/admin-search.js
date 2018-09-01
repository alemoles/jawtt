var searchUser =function searchuser() {
    // define the $ as jQuery for multiple uses
    jQuery(function($) {
        /*Add loading image*/
        //var $el = $("#message-user");

        //$('#loading-image').show();
        $.ajax({
            type: 'POST',
            url: load.ajaxurl,
            //contentType: "application/json; charset=utf-8",
            dataType: "json",
            data: {
                'action': 'data_fetch',
                'nonce': load.nonce,
                'username' : $('#user-field-search').val()
            },
            success: function(data) {

                if(data.message){
                    var $el = $("#propietario-data-search");
                    $el.empty(); // remove old options

                    var count = Object.keys(data.result).length;
                    var headerList=["Seleccionar", "Nombre", "Email","Usuario"];

                    var table = document.createElement('table');
                    table.className = "wp-list-table widefat fixed striped posts";
                    var thead=document.createElement('thead');
                    var tr = document.createElement('tr'); // Header row
                    for (var j = 0; j < 4; j++) {

                        var th = document.createElement('th'); //column
                        th.scope="col";
                        th.className="manage-column column-"+headerList[j].toLowerCase();
                        var text = document.createTextNode(headerList[j]); //cell
                        th.appendChild(text);
                        tr.appendChild(th);
                    }
                    table.appendChild(thead);
                    thead.appendChild(tr);

                    var tbody=document.createElement('tbody');
                    for (var i = 0; i < count; i++) {

                        var tr = document.createElement('tr'); // row
                        for (var j = 0; j < 4; j++) {
                            var td;
                            if(j==0){
                                 td = document.createElement('th'); //first column
                            }else{
                                 td = document.createElement('td'); //column
                            }

                            var text;
                            switch(j){
                                case 0:
                                    td.scope="row";
                                    td.className="check-column";
                                    text= document.createElement("input");
                                    text.setAttribute('type', 'checkbox');
                                    text.setAttribute('class', 'propietario-search-exits-users');
                                    text.setAttribute('onclick','onlyselectOne();');
                                    text.setAttribute('name','userValue[]');
                                    text.setAttribute('id', 'user-select-'+data.result[i].id);
                                    text.setAttribute('value', data.result[i].id);
                                    break;
                                case 1:
                                    td.className="column-"+headerList[j].toLowerCase();
                                    text = document.createTextNode(data.result[i].full_name);
                                    break;
                                case 2:
                                    td.className="column-"+headerList[j].toLowerCase();
                                    text = document.createTextNode(data.result[i].email);
                                    break;
                                case 3:
                                    td.className="column-"+headerList[j].toLowerCase();
                                    text = document.createTextNode(data.result[i].name);
                                    break;
                                default:
                                    break;
                            }
                             //cell
                            td.appendChild(text);
                            tr.appendChild(td);
                        }
                        tbody.appendChild(tr);

                    }
                    table.appendChild(tbody);
                    document.getElementById('propietario-data-search').appendChild(table);
                }else{
                    var $el = $("#propietario-data-search");
                    $el.empty(); // remove old options
                    var user = $.trim( $('input#user-field-search').val() ); // get the value of the input field
                    if(user != "") {
                        var nofound=document.getElementById('propietario-data-search');

                        var p = document.createElement('p');
                        p.style.color='red';
                        p.innerHTML='No hay resultados que mostrar';
                        nofound.appendChild(p);
                    }





                    //console.log("No hay resultados");
                }
                //console.log(data.result);
                // if(data.result){
                //     document.getElementById('message-user').style.color = 'red';
                //     document.getElementById('message-user').innerHTML = '!El usuario existe!';
                // }else{
                //     document.getElementById('message-user').style.color = 'green';
                //     document.getElementById('message-user').innerHTML = 'Disponible';
                // }


            }
        });
    });
}