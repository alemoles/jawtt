jQuery(document).ready(function($){
    var div_bar =$('div .traveltour-top-bar-left');
    div_bar.empty();

    var main_menu=$('div .traveltour-main-menu');
    //main_menu.attr('style', "color", "balck");

    var search=$("input[class^='search-field']");
    search.attr('placeholder','Buscar');

    // var logo=$('.traveltour-logo-inner');
    // logo.empty();
    // logo.append($("<a></a>")
    //     .attr("href", "#")
    //     .append($("<img>")
    //         .attr("src","'.get_stylesheet_directory_uri().'/images/tp-logo.png")
    //         .attr("alt", "Una imagen")));
    // $.ajax({
    //     type: 'POST',
    //     url: ui.ajaxurl,
    //     data: {
    //         'action': 'logo_load',
    //         'nonce' : ui.nonce
    //     },
    //     success: function(data){
    //
    //         // var logo=$('.traveltour-logo-inner');
    //         // var option=data;
    //         // logo.empty();
    //         // logo.append(option);
    //     }
    // });
});




