$(document).ready( function(){ 
    $(".boutonModal").on('click', function(event) { 
    //ATTENTION: le event.preventDefault() est nécessaire sinon la 
    event.preventDefault(); 
    $.get($(this).attr('href'), function(data) { 
    $("#modalProduit").html(data).dialog({ 
    height: "auto", 
    width: 700, 
    modal: true 
    }); 
    }); 
    }); 
   }); 