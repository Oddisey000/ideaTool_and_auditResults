$(document).ready(function(){
    $('#s_slider').on('change', function(){
        var data = $(this).val();
        var level = (data + "S");
        $("#s_slider_data").empty();
        $("#s_slider_data").html(level);
        $.ajax({
            url:"includes/handlers/s_level.php",
            data:{level:level},
            type:"POST",
            cache:false,
        });
    });
    
    /////////////////////////Standard//////////////////////////

    $(".questionnaire").find("#standard").click(function(){
        $(".questionnaire").find("button").removeClass("btn-info");
        $(".questionnaire").find("#standard").addClass("btn-info");
        $("#s_questionnaire").attr("style", "display: none");
        $("#s_questionnaire").attr("style", "display: inline");
        $("#one_s, #second_s, #third_s, #fourth_s, #fifth_s").remove();
        $(".tab-content").append('<div id="one_s" class="tab-pane active"><br></div>');
        $("#one_s, #second_s, #third_s, #fourth_s, #fifth_s").empty();
        var type = $(".questionnaire").find(".btn-info").attr("val");
        var level = "1S";
        var tab_id = "one_s";      
        $.ajax({
            url:"includes/handlers/questionnaire_handler.php",
            data:{type:type, level:level, tab_id:tab_id},
            type:"POST",
            cache:false,
            success:function(respond){
                $("#one_s").html(respond);
            }
        });
        $("#first").trigger("click");
    });
    /////////////////////////Office//////////////////////////  
    
    $(".questionnaire").find("#office").click(function(){
        $(".questionnaire").find("button").removeClass("btn-info");
        $(".questionnaire").find("#office").addClass("btn-info");
        $("#s_questionnaire").attr("style", "display: none");
        $("#s_questionnaire").attr("style", "display: inline");
        $("#one_s, #second_s, #third_s, #fourth_s, #fifth_s").remove();
        $(".tab-content").append('<div id="one_s" class="tab-pane active"><br></div>');
        $("#one_s, #second_s, #third_s, #fourth_s, #fifth_s").empty();
        var type = $(".questionnaire").find(".btn-info").attr("val");
        var level = "1S";
        var tab_id = "one_s";      
        $.ajax({
            url:"includes/handlers/questionnaire_handler.php",
            data:{type:type, level:level, tab_id:tab_id},
            type:"POST",
            cache:false,
            success:function(respond){
                $("#one_s").html(respond);
            }
        });
        $("#first").trigger("click");
    });
    /////////////////////////Production//////////////////////////

    $(".questionnaire").find("#production").click(function(){
        $(".questionnaire").find("button").removeClass("btn-info");
        $(".questionnaire").find("#production").addClass("btn-info");
        $("#s_questionnaire").attr("style", "display: none");
        $("#s_questionnaire").attr("style", "display: inline");
        $("#one_s, #second_s, #third_s, #fourth_s, #fifth_s").remove();
        $(".tab-content").append('<div id="one_s" class="tab-pane active"><br></div>');
        $("#one_s, #second_s, #third_s, #fourth_s, #fifth_s").empty();
        var type = $(".questionnaire").find(".btn-info").attr("val");
        var level = "1S";
        var tab_id = "one_s";      
        $.ajax({
            url:"includes/handlers/questionnaire_handler.php",
            data:{type:type, level:level, tab_id:tab_id},
            type:"POST",
            cache:false,
            success:function(respond){
                $("#one_s").html(respond);
            }
        });
        $("#first").trigger("click");
    });
    /////////////////////////Auto//////////////////////////

    $(".questionnaire").find("#auto").click(function(){
        $(".questionnaire").find("button").removeClass("btn-info");
        $(".questionnaire").find("#auto").addClass("btn-info");
        $("#s_questionnaire").attr("style", "display: none");
        $("#s_questionnaire").attr("style", "display: inline");
        $("#one_s, #second_s, #third_s, #fourth_s, #fifth_s").remove();
        $(".tab-content").append('<div id="one_s" class="tab-pane active"><br></div>');
        $("#one_s, #second_s, #third_s, #fourth_s, #fifth_s").empty();
        var type = $(".questionnaire").find(".btn-info").attr("val");
        var level = "1S";
        var tab_id = "one_s";      
        $.ajax({
            url:"includes/handlers/questionnaire_handler.php",
            data:{type:type, level:level, tab_id:tab_id},
            type:"POST",
            cache:false,
            success:function(respond){
                $("#one_s").html(respond);
            }
        });
        $("#first").trigger("click");
    });
    /////////////////////////Warehouse//////////////////////////

    $(".questionnaire").find("#warehouse").click(function(){
        $(".questionnaire").find("button").removeClass("btn-info");
        $(".questionnaire").find("#warehouse").addClass("btn-info");
        $("#s_questionnaire").attr("style", "display: none");
        $("#s_questionnaire").attr("style", "display: inline");
        $("#one_s, #second_s, #third_s, #fourth_s, #fifth_s").remove();
        $(".tab-content").append('<div id="one_s" class="tab-pane active"><br></div>');
        $("#one_s, #second_s, #third_s, #fourth_s, #fifth_s").empty();
        var type = $(".questionnaire").find(".btn-info").attr("val");
        var level = "1S";
        var tab_id = "one_s";      
        $.ajax({
            url:"includes/handlers/questionnaire_handler.php",
            data:{type:type, level:level, tab_id:tab_id},
            type:"POST",
            cache:false,
            success:function(respond){
                $("#one_s").html(respond);
            }
        });
        $("#first").trigger("click");
    });
    /////////////////////////Warehouse//////////////////////////

    $(".questionnaire").find("#tech").click(function(){
        $(".questionnaire").find("button").removeClass("btn-info");
        $(".questionnaire").find("#tech").addClass("btn-info");
        $("#s_questionnaire").attr("style", "display: none");
        $("#s_questionnaire").attr("style", "display: inline");
        $("#one_s, #second_s, #third_s, #fourth_s, #fifth_s").remove();
        $(".tab-content").append('<div id="one_s" class="tab-pane active"><br></div>');
        $("#one_s, #second_s, #third_s, #fourth_s, #fifth_s").empty();
        var type = $(".questionnaire").find(".btn-info").attr("val");
        var level = "1S";
        var tab_id = "one_s";      
        $.ajax({
            url:"includes/handlers/questionnaire_handler.php",
            data:{type:type, level:level, tab_id:tab_id},
            type:"POST",
            cache:false,
            success:function(respond){
                $("#one_s").html(respond);
            }
        });
        $("#first").trigger("click");
    });
    /////////////////////////Rooms//////////////////////////

    $(".questionnaire").find("#rooms").click(function(){
        $(".questionnaire").find("button").removeClass("btn-info");
        $(".questionnaire").find("#rooms").addClass("btn-info");
        $("#s_questionnaire").attr("style", "display: none");
        $("#s_questionnaire").attr("style", "display: inline");
        $("#one_s, #second_s, #third_s, #fourth_s, #fifth_s").remove();
        $(".tab-content").append('<div id="one_s" class="tab-pane active"><br></div>');
        $("#one_s, #second_s, #third_s, #fourth_s, #fifth_s").empty();
        var type = $(".questionnaire").find(".btn-info").attr("val");
        var level = "1S";
        var tab_id = "one_s";      
        $.ajax({
            url:"includes/handlers/questionnaire_handler.php",
            data:{type:type, level:level, tab_id:tab_id},
            type:"POST",
            cache:false,
            success:function(respond){
                $("#one_s").html(respond);
            }
        });
        $("#first").trigger("click");
    });
    /////////////////////////Kitchen//////////////////////////

    $(".questionnaire").find("#kitchen").click(function(){
        $(".questionnaire").find("button").removeClass("btn-info");
        $(".questionnaire").find("#kitchen").addClass("btn-info");
        $("#s_questionnaire").attr("style", "display: none");
        $("#s_questionnaire").attr("style", "display: inline");
        $("#one_s, #second_s, #third_s, #fourth_s, #fifth_s").remove();
        $(".tab-content").append('<div id="one_s" class="tab-pane active"><br></div>');
        $("#one_s, #second_s, #third_s, #fourth_s, #fifth_s").empty();
        var type = $(".questionnaire").find(".btn-info").attr("val");
        var level = "1S";
        var tab_id = "one_s";      
        $.ajax({
            url:"includes/handlers/questionnaire_handler.php",
            data:{type:type, level:level, tab_id:tab_id},
            type:"POST",
            cache:false,
            success:function(respond){
                $("#one_s").html(respond);
            }
        });
        $("#first").trigger("click");
    });
////////////////////////////////////////////////////////////////////////////////////////////
    $("#first").click(function(){
        $("#one_s, #second_s, #third_s, #fourth_s, #fifth_s").remove();
        $(".tab-content").append('<div id="one_s" class="tab-pane active"><br></div>');
        $("#one_s").empty();
        var type = $(".questionnaire").find(".btn-info").attr("val");
        var level = "1S";
        var tab_id = "one_s";      
        $.ajax({
            url:"includes/handlers/questionnaire_handler.php",
            data:{type:type, level:level, tab_id:tab_id},
            type:"POST",
            cache:false,
            success:function(respond){
                $("#one_s").html(respond);
            }
        });
    });
    $("#second").click(function(){
        $("#one_s, #second_s, #third_s, #fourth_s, #fifth_s").remove();
        $(".tab-content").append('<div id="second_s" class="tab-pane active"><br></div>');
        $("#second_s").empty();
        var type = $(".questionnaire").find(".btn-info").attr("val");
        var level = "2S";
        var tab_id = "second_s";      
        $.ajax({
            url:"includes/handlers/questionnaire_handler.php",
            data:{type:type, level:level, tab_id:tab_id},
            type:"POST",
            cache:false,
            success:function(respond){
                $("#second_s").html(respond);
            }
        });
    });
    $("#third").click(function(){
        $("#one_s, #second_s, #third_s, #fourth_s, #fifth_s").remove();
        $(".tab-content").append('<div id="third_s" class="tab-pane active"><br></div>');
        $("#third_s").empty();
        var type = $(".questionnaire").find(".btn-info").attr("val");
        var level = "3S";
        var tab_id = "third_s";
        $.ajax({
            url:"includes/handlers/questionnaire_handler.php",
            data:{type:type, level:level, tab_id:tab_id},
            type:"POST",
            cache:false,
            success:function(respond){
                $("#third_s").html(respond);
            }
        });
    });
    $("#fourth").click(function(){
        $("#one_s, #second_s, #third_s, #fourth_s, #fifth_s").remove();
        $(".tab-content").append('<div id="fourth_s" class="tab-pane active"><br></div>');
        $("#fourth_s").empty();
        var type = $(".questionnaire").find(".btn-info").attr("val");
        var level = "4S";
        var tab_id = "fourth_s";
        $.ajax({
            url:"includes/handlers/questionnaire_handler.php",
            data:{type:type, level:level, tab_id:tab_id},
            type:"POST",
            cache:false,
            success:function(respond){
                $("#fourth_s").html(respond);
            }
        });
    });
    $("#fifth").click(function(){
        $("#one_s, #second_s, #third_s, #fourth_s, #fifth_s").remove();
        $(".tab-content").append('<div id="fifth_s" class="tab-pane active"><br></div>');
        $("#fifth_s").empty();
        var type = $(".questionnaire").find(".btn-info").attr("val");
        var level = "5S";
        var tab_id = "fifth_s";
        $.ajax({
            url:"includes/handlers/questionnaire_handler.php",
            data:{type:type, level:level, tab_id:tab_id},
            type:"POST",
            cache:false,
            success:function(respond){
                $("#fifth_s").html(respond);
            }
        });
    });
});
/////////////////////////////////////////////////////////////////////////////////////////