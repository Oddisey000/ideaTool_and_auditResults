<?php
  require('includes/header.php');
  require('includes/connection.php');
  
  $lastmonth = date("Y-m", strtotime("first day of previous month"));
  $lastmonth_2 = date("Y-m", strtotime("- 2 month"));
  $userLogin = $_SESSION['loggedInUser'];
  
  $query 	   = mysqli_query($conn, "SELECT `segment` FROM users WHERE `login` = '$userLogin'");
  $parent 	   = mysqli_fetch_assoc($query);
  $parent 	   = $parent['segment'];
  $query_check = mysqli_query($conn, 
	"SELECT `note_value` FROM result WHERE `note_value` BETWEEN 1 AND 4 AND `current_date` BETWEEN '$lastmonth_2' AND '$lastmonth' AND `parent` = '$parent' AND `action` = '' OR `action` = 'Зарадча дія'");
	
  $dateTranslated = date("F Y", strtotime($lastmonth));
  $queryParent = mysqli_query($conn, "SELECT * FROM dashboard_data GROUP BY parent ASC");

  $query = mysqli_query($conn, "SELECT 5s_level FROM 5s_level");
  $result = mysqli_fetch_array($query);
  $s_level = $result['5s_level']; 
  if ($s_level == "1S") {
    $s_level = 5;
  } elseif ($s_level == "2S") {
    $s_level = 10;
  } elseif ($s_level == "3S") {
    $s_level = 15;
  } elseif ($s_level == "4S") {
    $s_level = 20;
  } elseif ($s_level == "5S") {
    $s_level = 25;
  }

?>
<script src="../libs/jquery/jquery-1.12.4.min.js"></script>
<link href="../libs/jsplugin/datepicker/datepicker.min.css" rel="stylesheet">
<script src="../libs/jsplugin/datepicker/datepicker.min.js"></script>
<script src="../libs/jsplugin/datepicker/datepicker.en.js"></script>
<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
  <div id="event"></div>
  <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
    <h1>Результати</h1>
	<?php 
		if (mysqli_num_rows($query_check) > 0) {
			echo '<h3 id="animations">Увага у Вас відсутні зарадчі заходи</h3>';
		}
	?>
    <span id="title" style="display: none;"><h1 class="h2">5S Audit Results <strong id="fullDate"><?php echo $dateTranslated; ?></strong></h1></span>
    <div class="btn-toolbar mb-2 mb-md-0">
      <div class="btn-group mr-2"">
        <div>
          <button class="btn btn-sm btn-outline-secondary plant" title='Відобразити загальні результати аудиту заводу'>WUAKM</button>
          <button class="btn btn-sm btn-outline-secondary" href="#" id="results" data-toggle="modal" data-target="#chooseDate" role="button" aria-haspopup="true" title='Відобразити результати за обраний період часу'>
          <span data-feather="calendar"></span>Результати</button>
          <button class="btn btn-sm btn-outline-secondary" id="print"><span data-feather="printer"></span>Друк</button>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="chooseDate">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-body">
          <div class="form-group">
            <label class="lead">Початкова дата</label>
            <input
              type="text" 
              id="startDate" 
              placeholder="Початкова дата"
              data-language='en'
              data-min-view="months"
              data-view="months"
              data-date-format="yyyy-mm" 
              class="form-control datepicker-here-first" /><br />
            <label class="lead">Кінцева дата</label>
            <input 
              type="text" 
              id="endDate"
              placeholder="Кінцева дата"
              data-language='en'
              data-min-view="months"
              data-view="months"
              data-date-format="yyyy-mm"
              class="form-control datepicker-here-second" /><br />
          </div>
        </div>
        <div class="modal-footer">
        <button class="btn btn-success btn-sm" id="allTimeData" style="margin-right: 14%;" onclick="allTime()">За весь період</button>
        <button class="btn btn-info btn-sm" id="updateData">Оновити дані</button>
        </div>
      </div>
    </div>
  </div>
  <div id="graph-container">
    <canvas class="my-4 w-100" id="myChart" width="100%" height="40%"></canvas>
  </div>
</main>
<script src="../libs/jsplugin/Chart.min.js"></script>
<script>
	$('.datepicker-here-first').datepicker({
		language: 'en',
		minDate: new Date("2019-01"),
		maxDate: new Date("<?php echo $lastmonth; ?>")
	});
	$('.datepicker-here-second').datepicker({
		language: 'en',
		minDate: new Date("2019-01"),
		maxDate: new Date("<?php echo $lastmonth; ?>")
	});
  $('.datepicker-here-first').datepicker({
    autoClose: true,
    position: 'right top',
    onSelect: function(formattedDate, date, inst) {
      var variable = $('#startDate').val();
      $("#endDate").val(variable);
    }
  })
  
  $('.datepicker-here-second').datepicker({
    autoClose: true,
    position: 'right top',
    onSelect: function(formattedDate, date, inst) {
		var variable = $('#endDate').val();
		$("#endDate").val();
		$("#endDate").val(variable);
		$('#updateData').trigger('click');
    }
  })
</script>
<script>
    $('.datepicker').css("z-index", "999999999");
    $('#startDate').val("<?php echo $lastmonth; ?>");
    $('#endDate').val("<?php echo $lastmonth; ?>");

//Variables need for creating a chart
    var onLoadDep    = "";
    var firstDate    = '';
    var secondDate   = '';
    var allTime      = '';
    var title        = $("#title").text();
    var onLoadLabel  = "";
    var chartLabels  = [];
    var onLoadData   = [];
    var date_to_send = "<?php echo $lastmonth; ?>";
    var dashboard    = 'dashboard';
	var bgColor 	 = [];
	var hrColor 	 = [];
</script>
<?php 
	if (isset($_REQUEST['element'])) {
		$element 			= $_REQUEST['element'];
		$firstAndSecondDate = $_REQUEST['date'];
		
		$query = mysqli_query($conn, "SELECT parent FROM dashboard_data WHERE child = '$element' AND date = '$firstAndSecondDate'");
		$query = mysqli_fetch_assoc($query);
		$element = $query['parent'];
		$checker = 1;
	}
?>
<script>
  var checker = '<?php echo $checker; ?>';
  if (checker === '1') {
	  onLoadDep  = '<?php echo $element; ?>';
	  firstDate  = '<?php echo $firstAndSecondDate; ?>';
	  secondDate = '<?php echo $firstAndSecondDate; ?>';
	  var place_date_first = moment(firstDate).format("MMMM YYYY");
	  var place_date_second = moment(secondDate).format("MMMM YYYY");
	  $('#fullDate').empty();
      $('#fullDate').append(place_date_first);
	  title= $("#title").text();
	  date_to_send = firstDate;
	  
	  $.ajax({
		url:"includes/handlers/dashboard_handler.php",
		data:{ onLoadDep:onLoadDep, firstDate:firstDate,  secondDate:secondDate, allTime:allTime},
		cache:false,
		success:function(respond) {
		  chartLabels = [];
		  onLoadData  = [];
		  bgColor 	  = [];
		  hrColor 	  = [];
		  for (var x in respond) {
			chartLabels.push(respond[x].child);
			onLoadData.push(respond[x].note);
			bgColor.push('rgba(0, 120, 215, 0.5)');
			hrColor.push('rgba(0, 120, 215, 0.8)');
		  }
		  chart.data.datasets[0].data = onLoadData;
		  chart.data.datasets[0].backgroundColor = bgColor;
		  chart.data.datasets[0].hoverBackgroundColor = hrColor;
		  chart.data.labels = chartLabels;
		  chart.options.onClick = linkToResults;
		  chart.options.title.text = [title];
		  chart.update();
		}
	  });
  } else {
	  $.ajax({
		url:"includes/handlers/dashboard_handler.php",
		async: false,
		cache:false,
		data:{ onLoadDep:onLoadDep },
		success:function(respond) {
		  for (var x in respond) {
			onLoadLabel = respond[x].s_level;
			chartLabels.push(respond[x].parent);
			onLoadData.push(respond[x].value);
			bgColor.push('rgba(0, 120, 215, 0.5)');
			hrColor.push('rgba(0, 120, 215, 0.8)');
		  }
		  bgColor.pop();
		  hrColor.pop();
		  bgColor.push('rgba(0, 210, 215, 0.5)');
		  hrColor.push('rgba(0, 210, 215, 0.8)');
		}
	  });
  }
  var ctx = document.getElementById("myChart").getContext("2d");
  var chart = new Chart(ctx, {
    type: "bar",
    data: {
      labels: chartLabels,
      datasets: [{
        label: onLoadLabel,
        data: onLoadData,
        backgroundColor: bgColor,
        hoverBackgroundColor: hrColor
      }]
    },
    options: {
      onClick: depDetails,
      scales: {
        yAxes: [{
          ticks: {
            beginAtZero: true,
            max: <?php echo $s_level; ?>
          }
        }],
        xAxes: [{
          maxBarThickness: 300,
          gridLines: {
            color: "rgba(0, 0, 0, 0)"
          },
		  ticks: {
                //fontStyle: "bold",
				fontColor: '#000'
            }
        }]
      },
      legend: {
        display: false
      },
      tooltips: {
		enabled: false,
        //titleFontStyle: "oblique",
        //titleFontSize: 14
      },
      title: {
        text: [title],
        fontSize: 25,
        padding: 45,
        lineHeight: 1.2,
        display: true,
		fontColor: '#000'
      },
	  hover: {
			animationDuration: 0
		},
	  animation: {
		duration: 1000,
		onComplete: function() {
		  var chartInstance = this.chart,
          ctx = chartInstance.ctx;
		  ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontSize, Chart.defaults.global.defaultFontStyle, Chart.defaults.global.defaultFontFamily);
          ctx.textAlign = 'center';
          ctx.textBaseline = 'bottom';
		  ctx.fillStyle = '#000';
          this.data.datasets.forEach(function(dataset, i) {
            var meta = chartInstance.controller.getDatasetMeta(i);
            meta.data.forEach(function(bar, index) {
              var data = dataset.data[index];
              ctx.fillText(data, bar._model.x, bar._model.y - 5);
            });
          });
        }
      }
    }
  });

  function depDetails(click, onLoadData) {
    if (onLoadData[0]) {
      var x = onLoadData[0]['_index'];
      if (x !== '') {
        onLoadDep = chartLabels[x];
        if (onLoadDep !== 'WUAKM') {
          $.ajax({
            url:"includes/handlers/dashboard_handler.php",
            data:{ onLoadDep:onLoadDep, firstDate:firstDate,  secondDate:secondDate, allTime:allTime},
            cache:false,
            success:function(respond) {
              chartLabels = [];
              onLoadData  = [];
			  bgColor 	  = [];
			  hrColor 	  = [];
              for (var x in respond) {
                chartLabels.push(respond[x].child);
                onLoadData.push(respond[x].note);
				bgColor.push('rgba(0, 120, 215, 0.5)');
				hrColor.push('rgba(0, 120, 215, 0.8)');
              }
              chart.data.datasets[0].data = onLoadData;
			  chart.data.datasets[0].backgroundColor = bgColor;
			  chart.data.datasets[0].hoverBackgroundColor = hrColor;
              chart.data.labels = chartLabels;
              chart.options.onClick = linkToResults;
              chart.update();
            }
          });
        } 
      }    
    }
  }
  
  $('.plant').click(function(){
    onLoadDep = '';
    $.ajax({
      url:"includes/handlers/dashboard_handler.php",
      data:{ onLoadDep:onLoadDep, firstDate:firstDate,  secondDate:secondDate, allTime:allTime},
      cache:false,
      success:function(respond) {
        chartLabels = [];
        onLoadData  = [];
		bgColor 	= [];
		hrColor 	= [];
        for (var x in respond) {
          chartLabels.push(respond[x].parent);
          onLoadData.push(respond[x].value);
		  bgColor.push('rgba(0, 120, 215, 0.5)');
		  hrColor.push('rgba(0, 120, 215, 0.8)');
        }
		bgColor.pop();
	    hrColor.pop();
	    bgColor.push('rgba(0, 210, 215, 0.5)');
	    hrColor.push('rgba(0, 210, 215, 0.8)');
		chart.data.datasets[0].backgroundColor = bgColor;
		chart.data.datasets[0].hoverBackgroundColor = hrColor;
        chart.data.datasets[0].data = onLoadData;
        chart.data.labels = chartLabels;
        chart.options.onClick = depDetails;
        chart.update();
      }
    });
  });

  $('#allTimeData').click(function(){
    firstDate = '';
    secondDate = '';
    allTime = '1';
    chartLabels = [];
    onLoadData = [];
	bgColor 	= [];
	hrColor 	= [];
    $.ajax({
      url:"includes/handlers/dashboard_handler.php",
      data:{ onLoadDep:onLoadDep, firstDate:firstDate,  secondDate:secondDate, allTime:allTime},
      cache:false,
      success:function(respond) {
        if (onLoadDep === '') {
          for (var x in respond) {
            chartLabels.push(respond[x].parent);
            onLoadData.push(respond[x].value);
			bgColor.push('rgba(0, 120, 215, 0.5)');
			hrColor.push('rgba(0, 120, 215, 0.8)');
          }
		  bgColor.pop();
	      hrColor.pop();
	      bgColor.push('rgba(0, 210, 215, 0.5)');
	      hrColor.push('rgba(0, 210, 215, 0.8)');
        } else {
          for (var x in respond) {
            chartLabels.push(respond[x].child);
            onLoadData.push(respond[x].note);
			bgColor.push('rgba(0, 120, 215, 0.5)');
			hrColor.push('rgba(0, 120, 215, 0.8)');
          }
        }
        $('#chooseDate').modal('hide');
        $('#fullDate').empty();
        $('#fullDate').append('All results data');
        title= $("#title").text();
		chart.data.datasets[0].backgroundColor = bgColor;
		chart.data.datasets[0].hoverBackgroundColor = hrColor;
        chart.data.datasets[0].data = onLoadData;
        chart.data.labels = chartLabels;
        chart.options.title.text = [title];
        chart.update();
      }
    });
  });

  $('#updateData').click(function(){
    firstDate = $('#startDate').val();
    secondDate = $('#endDate').val();
    chartLabels = [];
    onLoadData  = [];
	bgColor 	= [];
	hrColor 	= [];
    date_to_send = firstDate;
    allTime = '';
    var place_date_first = moment(firstDate).format("MMMM YYYY");
    var place_date_second = moment(secondDate).format("MMMM YYYY");
    $.ajax({
      url:"includes/handlers/dashboard_handler.php",
      data:{ onLoadDep:onLoadDep, firstDate:firstDate,  secondDate:secondDate, allTime:allTime},
      cache:false,
      success:function(respond) {
        if (onLoadDep === '') {
          for (var x in respond) {
            chartLabels.push(respond[x].parent);
            onLoadData.push(respond[x].value);
			bgColor.push('rgba(0, 120, 215, 0.5)');
			hrColor.push('rgba(0, 120, 215, 0.8)');
          }
		  bgColor.pop();
	      hrColor.pop();
	      bgColor.push('rgba(0, 210, 215, 0.5)');
	      hrColor.push('rgba(0, 210, 215, 0.8)');
        } else {
          for (var x in respond) {
            chartLabels.push(respond[x].child);
            onLoadData.push(respond[x].note);
			bgColor.push('rgba(0, 120, 215, 0.5)');
			hrColor.push('rgba(0, 120, 215, 0.8)');
          }
        }
        if (place_date_first === place_date_second) {
          $('#fullDate').empty();
          $('#fullDate').append(place_date_first);
        } else {
          $('#fullDate').empty();
          $('#fullDate').append(place_date_first + ' - ' + place_date_second);
        }
        $('#chooseDate').modal('hide');
        title= $("#title").text();
		chart.data.datasets[0].backgroundColor = bgColor;
		chart.data.datasets[0].hoverBackgroundColor = hrColor;
        chart.data.datasets[0].data = onLoadData;
        chart.data.labels = chartLabels;
        chart.options.title.text = [title];
        chart.update();
      }
    });
  });

  function linkToResults(click, onLoadData) {
    if (onLoadData[0]) {
      var x = onLoadData[0]['_index'];
      if (x !== '') {
        if (firstDate == secondDate && allTime === '') {
          window.location.href = "questionnaire_public.php?element=" + chartLabels[x] + "&date=" + date_to_send + "&dashboard=" + dashboard;
        } else {
          alert("Перегляд результатів можливий тільки в межах одного місяця");
        }
      }
    }
  }

  $("#print").click(function(){
    /*var canvas = document.getElementById("myChart");
    var win = window.open();
    win.document.write("<br><img src='" + canvas.toDataURL() + "'/>");
    win.location.reload();*/
	var canvas = document.getElementById("myChart");
    var win = window.open();
    win.document.write("<img src='" + canvas.toDataURL() + "'/>");
    win.print();
    win.location.reload();
  });

</script>
<?php
  require('includes/footer.php');
