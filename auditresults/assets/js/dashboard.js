$(function(){
    var onLoadNote  = "";
    var onLoadDep   = "";
    $.ajax({
        url: "includes/handlers/dashboard_handler.php",
        data:{onLoadNote:onLoadNote},
        type:"POST",
        success: function(e){
            $("#onLoadMonth").html(e);
        }
    });
     $.ajax({
        url: "includes/handlers/dashboard_handler.php",
        data:{onLoadDep:onLoadDep},
        type:"POST",
        success: function(e){
            $("#onLoadDep").html(e);
        }
    });
});