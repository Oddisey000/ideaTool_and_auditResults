$('#accordion').accordion({
      
      collapsible:true,active: false,heightStyle:'content'
    });

$('#accord').accordion({
      
      collapsible:true,active: false,heightStyle:'content'
    });


        $('.date').datepicker({
            showOtherMonths: true,
            selectOtherMonths: true,
            changeMonth: true,
            changeYear:true,
            dateFormat: 'yy-mm-dd'
        });



$('#accordion_special').accordion({
      
      collapsible:false,active: false,heightStyle:'content'
    });





       $(document).ready(function(){
	 $('#inputPrice').on('click', '.btn-primary', function(e){
     var vfname = $('#fname').val();
   
			$.post("header.php", //Required URL of the page on server
               { // Data Sending With Request To Server
                  fname:vfname,
               },
			function(response,status){ // Required Callback Function
             $("#result").html(response);//"response" receives - whatever written in echo of above PHP script.
            
          });
		  
     $('#inputPrice').modal('hide');
   });

     $('#sel1').on('change', function(e){
       var segment = $('#sel1 :selected').val();
     
        $.post("value.php", //Required URL of the page on server
                 { // Data Sending With Request To Server
                    selected_segment:segment,
                 },
        function(response){ // Required Callback Function
               $("#sel2").html(response);//"response" receives - whatever written in echo of above PHP script.
              
            });
     });

      var segment = $('#sel1 :selected').val();
   
      $.post("value.php", //Required URL of the page on server
               { // Data Sending With Request To Server
                  selected_segment:segment,
               },
      function(response){ // Required Callback Function
             $("#sel2").html(response);//"response" receives - whatever written in echo of above PHP script.
            
          });
   });

       $('#lastTeam').on('change', function(e){
       var segment = $('#lastTeam :selected').val();
     
        $.post("test.php", //Required URL of the page on server
                 { // Data Sending With Request To Server
                    selected_segment:segment,
                 },
        function(response){ // Required Callback Function
               $("#response").html(response);//"response" receives - whatever written in echo of above PHP script.
            
     });
         });

      var segment = $('#lastTeam :selected').val();
   
      $.post("test.php", //Required URL of the page on server
               { // Data Sending With Request To Server
                  selected_segment:segment,
               },
      function(response){ // Required Callback Function
             $("#response").html(response);//"response" receives - whatever written in echo of above PHP script.
            
         
   });

      $('#ideaSegment').on('change', function(e){
       var segment = $('#ideaSegment :selected').val();
     
        $.post("includes/datausers.php", //Required URL of the page on server
                 { // Data Sending With Request To Server
                    selected_ideaSegment:segment,
                 },
        function(response){ // Required Callback Function
               $("#ideaSegmentResponseByMonth").html(response);//"response" receives - whatever written in echo of above PHP script.
            
     });
         });

      var segment = $('#ideaSegment :selected').val();
   
      $.post("includes/datausers.php", //Required URL of the page on server
               { // Data Sending With Request To Server
                  selected_ideaSegment:segment,
               },
      function(response){ // Required Callback Function
             $("#ideaSegmentResponseByMonth").html(response);//"response" receives - whatever written in echo of above PHP script.
            
         
   });

      $('#ideaSegment').on('change', function(e){
       var segment = $('#ideaSegment :selected').val();
     
        $.post("includes/datausers.php", //Required URL of the page on server
                 { // Data Sending With Request To Server
                    selected_ideaSegmentElse:segment,
                 },
        function(response){ // Required Callback Function
               $("#ideaSegmentResponseElse").html(response);//"response" receives - whatever written in echo of above PHP script.
            
     });
         });

      var segment = $('#ideaSegment :selected').val();
   
      $.post("includes/datausers.php", //Required URL of the page on server
               { // Data Sending With Request To Server
                  selected_ideaSegmentElse:segment,
               },
      function(response){ // Required Callback Function
             $("#ideaSegmentResponseElse").html(response);//"response" receives - whatever written in echo of above PHP script.
            
         
   });



       $(document).ready(function() {
   $('#txtDate').datepicker({
     changeMonth: true,
     changeYear: true,
     dateFormat: 'mm yy',
       
     onClose: function() {
        var iMonth = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
        var iYear = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
        $(this).datepicker('setDate', new Date(iYear, iMonth, 1));
     },
       
     beforeShow: function() {
       if ((selDate = $(this).val()).length > 0) 
       {
          iYear = selDate.substring(selDate.length - 4, selDate.length);
          iMonth = jQuery.inArray(selDate.substring(0, selDate.length - 5), $(this).datepicker('option', 'monthNames'));
          $(this).datepicker('option', 'defaultDate', new Date(iYear, iMonth, 1));
           $(this).datepicker('setDate', new Date(iYear, iMonth, 1));
       }
    }
  });
});

       $(document).ready(function(){
  $("#myInput").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $(".myTable tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
});