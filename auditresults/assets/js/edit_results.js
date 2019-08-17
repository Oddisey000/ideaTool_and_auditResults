$(function(){
    $(".S_data").on('change',function(){
		var id           = $(this).attr("data");
        var comment      = $("#t" + id).val();
		var checkbox     = $("#n" + id).val();
        var dateToUpdate = $("#date").text();
        var element      = $("#element").text();
        var data         = $("#Results_data").text();
        $.ajax({
            url:"includes/handlers/update_audit_results.php",
            data:{id:id, checkbox:checkbox, comment:comment, dateToUpdate:dateToUpdate, element:element, data:data},
            type:"POST",
            cache:false,
        });
    });
	
	$("#people").on('change',function(){
		var people 		 = $(this).val();
		var dateToUpdate = $("#date").text();
		var element      = $("#element").text();
        $.ajax({
            url:"includes/handlers/update_audit_results.php",
            data:{people:people, dateToUpdate:dateToUpdate, element:element},
            type:"POST",
            cache:false,
        });
    });
	
	$(".actionPlan").on('change',function(){
		var actionPlan 		 = $(this).val();
		var id_toUpdate 	 = $(this).attr("id");
        $.ajax({
            url:"includes/handlers/update_audit_results.php",
            data:{actionPlan:actionPlan, id_toUpdate:id_toUpdate},
            type:"POST",
            cache:false,
        });
    });
});