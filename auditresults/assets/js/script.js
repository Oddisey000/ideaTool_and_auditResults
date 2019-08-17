$(function()
{
	$(".filter").on("keyup", function()
	{
		var value = $(this).val().toLowerCase();
		$(".filterData tr").filter(function()
		{
			$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
		});
	});
	
	$(".alert-box").delay(2000).fadeOut(1000);
	/* 
    Questionnaire block start*/
	$("#save_question").click(function(){
		var input_type = $(this).attr("type");
		var input_level = $(this).attr("level");
		var input_data = $("#questionnaire_data").val();
		var input_tab_id = $(this).attr("tab_id");
        $("#" + input_tab_id).empty();

		$.ajax({
            url:"includes/handlers/questionnaire_handler.php",
            data:{input_type:input_type, input_level:input_level, input_data:input_data, input_tab_id:input_tab_id},
            type:"POST",
            cache:false,
            success:function(respond){
                $("#" + input_tab_id).html(respond);
            }
        });
	});
	$(".delete_question").click(function(){
		var identificator = $(this).attr("identificator");
		var delete_tab_id = $(this).attr("tab_id");
		var delete_type = $(this).attr("type");
		var delete_level = $(this).attr("level");
        $("#" + delete_tab_id).empty();
		
        $.ajax({
			url:"includes/handlers/questionnaire_handler.php",
			data:{identificator:identificator, delete_tab_id:delete_tab_id, delete_type:delete_type, delete_level:delete_level},
			type:"POST",
            cache:false,
            success:function(respond){
                $("#" + delete_tab_id).html(respond);
            }
		});
	});
	$(".update_question").on('change',function(){
		var update_id = $(this).attr("id");
		var input_data = $(this).val();
		$.ajax({
			url:"includes/handlers/questionnaire_handler.php",
			data:{update_id:update_id, input_data:input_data},
			type:"POST",
            cache:false
		});
	});
	/* 
    Questionnaire block end*/

    //User registration modal window
    $("#add_user").click(function(){
        var user = $("#user_name").val();
		var email = $("#user_email").val();
        var user_org_unit = $("#user_org_unit option:selected").val();
        var user_right = $("#user_right").prop("checked");
        $.ajax({
            url:"includes/handlers/user_handler.php",
            data:{user:user, user_org_unit:user_org_unit, user_right:user_right, email:email},
            type:"POST",
            cache:false,
            success:function(respond){
                $("#alert_message").html(respond);
                $('#user_registration').modal('hide');
                $("#alert_message").delay(2000).fadeOut(1000);
                window.setTimeout(function(){location.reload()},1000);
            }
        });
    });

    //Org units registration modal window
    $("#add_org_unit").click(function(){
        var place_name = $("#place_name").val();
        var questionnaire_type = $("#questionnaire_type option:selected").val();
        var org_unit_name = $("#org_unit_name").val();
        $.ajax({
            url:"includes/handlers/org_units_handler.php",
            data:{place_name:place_name, questionnaire_type:questionnaire_type, org_unit_name:org_unit_name},
            type:"POST",
            cache:false,
            success:function(respond){
                $("#alert_message").html(respond);
                $('#org_units_registration').modal('hide');
                $("#alert_message").delay(2000).fadeOut(1000);
                window.setTimeout(function(){location.reload()},1000);
            }
        });
    });
	//Questionnaire calculation
	/**
	* Тут зараз буде багато неоптимізованого коду.
	* Поки я думатиму як його оптимізувати нехай буде так :-)
	*/
	// Data for 1S
	$(".oneS_data").change(function(){
		var id = $(this).attr("data");
		var checked_boxes = $('input[type=checkbox].oneS:checked').length;
		var num_rows = $(this).attr("num_rows");
		var note = $("#n" + id + " option:selected").val();
		var comment = $("#t" + id).val();
		var sum = 0;
		$(".sum-oneS").each(function() {
			sum += Number($(this).val());
		});
		var result = (sum / (num_rows - checked_boxes)).toFixed(1);
		$("#firstS_results").html(result);
		var sum = 0;
		$(".results").each(function() {
			sum += Number($(this).text());
		});
		var result = sum.toFixed(1);
		$("#Results_data").html(result);
	});
	$(".note_checkbox_one").change(function(){
		var id = $(this).attr("val");
		var checked_boxes = $('input[type=checkbox].oneS:checked').length;
		var num_rows = $(this).attr("num_rows");
		if (this.checked) {
			$("#n" + id).prop("disabled", true);
			$("#n" + id).prop("required", false);
			$("#n" + id).append("<option class='zero'>0</option>");
			$("#n" + id).css('visibility', 'hidden');
			$("#n" + id).val("0");
		} else {
			$("#n" + id).prop("disabled", false);
			$("#n" + id).find(".zero").remove();
			$("#n" + id).css('visibility', 'visible');
			$("#n" + id).val("");
			$("#n" + id).prop("required", true);
		}
		var sum = 0;
		$(".sum-oneS").each(function() {
			sum += Number($(this).val());
		});
		var result = (sum / (num_rows - checked_boxes)).toFixed(1);
		$("#firstS_results").html(result);
		var sum = 0;
		$(".results").each(function() {
			sum += Number($(this).text());
		});
		var result = sum.toFixed(1);
		$("#Results_data").html(result);
		if (result == "NaN") {
		alert("Помилка, всі пункти не можуть мати значення N\A");
		window.setTimeout(function(){location.reload()});	
	  }
	});
	
	
	// Data for 2S
	$(".twoS_data").change(function(){
		var id = $(this).attr("data");
		var checked_boxes = $('input[type=checkbox].twoS:checked').length;
		var num_rows = $(this).attr("num_rows");
		var note = $("#n" + id + " option:selected").val();
		var comment = $("#t" + id).val();
		var sum = 0;
		$(".sum-twoS").each(function() {
			sum += Number($(this).val());
		});
		var result = (sum / (num_rows - checked_boxes)).toFixed(1);
		$("#secondS_results").html(result);
		var sum = 0;
		$(".results").each(function() {
			sum += Number($(this).text());
		});
		var result = sum.toFixed(1);
		$("#Results_data").html(result);
	});
	
	$(".note_checkbox_two").change(function(){
		var id = $(this).attr("val");
		var checked_boxes = $('input[type=checkbox].twoS:checked').length;
		var num_rows = $(this).attr("num_rows");
		if (this.checked) {
			$("#n" + id).prop("required", false);
			$("#n" + id).prop("disabled", true);
			$("#n" + id).append("<option class='zero'>0</option>");
			$("#n" + id).css('visibility', 'hidden');
			$("#n" + id).val("0");
		} else {
			$("#n" + id).prop("disabled", false);
			$("#n" + id).find(".zero").remove();
			$("#n" + id).css('visibility', 'visible');
			$("#n" + id).val("");
			$("#n" + id).prop("required", true);
		}
		var sum = 0;
		$(".sum-twoS").each(function() {
			sum += Number($(this).val());
		});
		var result = (sum / (num_rows - checked_boxes)).toFixed(1);
		$("#secondS_results").html(result);
		var sum = 0;
		$(".results").each(function() {
			sum += Number($(this).text());
		});
		var result = sum.toFixed(1);
		$("#Results_data").html(result);
		if (result == "NaN") {
		alert("Помилка, всі пункти не можуть мати значення N\A");
		window.setTimeout(function(){location.reload()});	
	  }
	});
	
	// Data for 3S
	$(".threeS_data").change(function(){
		var id = $(this).attr("data");
		var checked_boxes = $('input[type=checkbox].threeS:checked').length;
		var num_rows = $(this).attr("num_rows");
		var note = $("#n" + id + " option:selected").val();
		var comment = $("#t" + id).val();
		var sum = 0;
		$(".sum-threeS").each(function() {
			sum += Number($(this).val());
		});
		var result = (sum / (num_rows - checked_boxes)).toFixed(1);
		$("#thirdS_results").html(result);
		var sum = 0;
		$(".results").each(function() {
			sum += Number($(this).text());
		});
		var result = sum.toFixed(1);
		$("#Results_data").html(result);
	});
	
	$(".note_checkbox_three").change(function(){
		var id = $(this).attr("val");
		var checked_boxes = $('input[type=checkbox].threeS:checked').length;
		var num_rows = $(this).attr("num_rows");
		if (this.checked) {
			$("#n" + id).prop("required", false);
			$("#n" + id).prop("disabled", true);
			$("#n" + id).append("<option class='zero'>0</option>");
			$("#n" + id).css('visibility', 'hidden');
			$("#n" + id).val("0");
		} else {
			$("#n" + id).prop("disabled", false);
			$("#n" + id).find(".zero").remove();
			$("#n" + id).css('visibility', 'visible');
			$("#n" + id).val("");
			$("#n" + id).prop("required", true);
		}
		var sum = 0;
		$(".sum-threeS").each(function() {
			sum += Number($(this).val());
		});
		var result = (sum / (num_rows - checked_boxes)).toFixed(1);
		$("#thirdS_results").html(result);
		var sum = 0;
		$(".results").each(function() {
			sum += Number($(this).text());
		});
		var result = sum.toFixed(1);
		$("#Results_data").html(result);
		if (result == "NaN") {
		alert("Помилка, всі пункти не можуть мати значення N\A");
		window.setTimeout(function(){location.reload()});	
	  }
	});
	
	// Data for 4S
	$(".fourS_data").change(function(){
		var id = $(this).attr("data");
		var checked_boxes = $('input[type=checkbox].fourS:checked').length;
		var num_rows = $(this).attr("num_rows");
		var note = $("#n" + id + " option:selected").val();
		var comment = $("#t" + id).val();
		var sum = 0;
		$(".sum-fourS").each(function() {
			sum += Number($(this).val());
		});
		var result = (sum / (num_rows - checked_boxes)).toFixed(1);
		$("#fourthS_results").html(result);
		var sum = 0;
		$(".results").each(function() {
			sum += Number($(this).text());
		});
		var result = sum.toFixed(1);
		$("#Results_data").html(result);
	});
	
	$(".note_checkbox_four").change(function(){
		var id = $(this).attr("val");
		var checked_boxes = $('input[type=checkbox].fourS:checked').length;
		var num_rows = $(this).attr("num_rows");
		if (this.checked) {
			$("#n" + id).prop("required", false);
			$("#n" + id).prop("disabled", true);
			$("#n" + id).append("<option class='zero'>0</option>");
			$("#n" + id).css('visibility', 'hidden');
			$("#n" + id).val("0");
		} else {
			$("#n" + id).prop("disabled", false);
			$("#n" + id).find(".zero").remove();
			$("#n" + id).css('visibility', 'visible');
			$("#n" + id).val("");
			$("#n" + id).prop("required", true);
		}
		var sum = 0;
		$(".sum-fourS").each(function() {
			sum += Number($(this).val());
		});
		var result = (sum / (num_rows - checked_boxes)).toFixed(1);
		$("#fourthS_results").html(result);
		var sum = 0;
		$(".results").each(function() {
			sum += Number($(this).text());
		});
		var result = sum.toFixed(1);
		$("#Results_data").html(result);
		if (result == "NaN") {
		alert("Помилка, всі пункти не можуть мати значення N\A");
		window.setTimeout(function(){location.reload()});	
	  }
	});
	
	// Data for 5S
	$(".fifthS_data").change(function(){
		var id = $(this).attr("data");
		var checked_boxes = $('input[type=checkbox].fifthS:checked').length;
		var num_rows = $(this).attr("num_rows");
		var note = $("#n" + id + " option:selected").val();
		var comment = $("#t" + id).val();
		var sum = 0;
		$(".sum-fifthS").each(function() {
			sum += Number($(this).val());
		});
		var result = (sum / (num_rows - checked_boxes)).toFixed(1);
		$("#fifthS_results").html(result);
		var sum = 0;
		$(".results").each(function() {
			sum += Number($(this).text());
		});
		var result = sum.toFixed(1);
		$("#Results_data").html(result);
	});
	
	$(".note_checkbox_fifth").change(function(){
		var id = $(this).attr("val");
		var checked_boxes = $('input[type=checkbox].fifthS:checked').length;
		var num_rows = $(this).attr("num_rows");
		if (this.checked) {
			$("#n" + id).prop("required", false);
			$("#n" + id).prop("disabled", true);
			$("#n" + id).append("<option class='zero'>0</option>");
			$("#n" + id).css('visibility', 'hidden');
			$("#n" + id).val("0");
		} else {
			$("#n" + id).prop("disabled", false);
			$("#n" + id).find(".zero").remove();
			$("#n" + id).css('visibility', 'visible');
			$("#n" + id).val("");
			$("#n" + id).prop("required", true);
		}
		var sum = 0;
		$(".sum-fifthS").each(function() {
			sum += Number($(this).val());
		});
		var result = (sum / (num_rows - checked_boxes)).toFixed(1);
		$("#fifthS_results").html(result);
		var sum = 0;
		$(".results").each(function() {
			sum += Number($(this).text());
		});
		var result = sum.toFixed(1);
		$("#Results_data").html(result);
		var sum = 0;
		$(".results").each(function() {
			sum += Number($(this).text());
		});
		var result = sum.toFixed(1);
		$("#Results_data").html(result);
		if (result == "NaN") {
		alert("Помилка, всі пункти не можуть мати значення N\A");
		window.setTimeout(function(){location.reload()});	
	  }
	});
	
	$("form").submit(function(e){
	  var selectedValues = $('.note').map(function() {
		return $(this).val();
	  }).get();
	  var text_data = $('.text-settings').map(function() {
		return $(this).val();
	  }).get();
	  var body = $('.body').map(function() {
		return $(this).text();
	  }).get();
	  var level = $('.body').map(function() {
		return $(this).attr("level");
	  }).get();
	  var sum = 0;
	  $(".results").each(function() {
		sum += Number($(this).text());
	  });
		var element = $("#element").text();
		var result = sum.toFixed(2);
		var loadedDate = $("#date").text();
		var dashboard = $("#dashboard").text();
		var people = $("#people").val();
		
		$("#Results_data").html(result);
		$.ajax({
		  url:"includes/handlers/save_audit_results.php",
		  data:{note_vS:selectedValues, comment_vS:text_data, result_vS:result, element_vS:element, body_vS:body, level_vS:level, people:people},
		  type:"POST",
		  success:function(respond){
			  $(window).scrollTop(0);
			  $("#alert_message").html(respond);
		  }
		});
			e.preventDefault();
			$("#alert_message").delay(2000).fadeOut(1000);
			if (dashboard !== '') {
				window.setTimeout(function(){location.href="dashboard.php?element=" + element + "&date=" + loadedDate},1000);
			} else {
				window.setTimeout(function(){location.href="link_to_questionnaire.php"},1000);
			}
	});
	//Questionnaire calculation End
});