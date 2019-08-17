<?php
	include "includes/header.php";
    include "includes/connection.php";
	
	session_start();
    if($_SESSION['loggedInUser'])
    {
      $userLogin = $_SESSION['loggedInUser'];

      $query        = "SELECT status AS status FROM users WHERE login = '$userLogin'";
      $request      = mysqli_query($conn, $query);
      $row          = mysqli_fetch_assoc($request);
      $permissions  = $row['status'];
      if ($permissions < 1) {
        header("Location: dashboard.php");
      }
    }
	
    $lastmonth = date("Y-m", strtotime("-1 months"));
?>
<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
<script src="../libs/jquery/jquery-1.12.4.min.js"></script>
<link href="../libs/jsplugin/datepicker/datepicker.min.css" rel="stylesheet">
<script src="../libs/jsplugin/datepicker/datepicker.min.js"></script>
<script src="../libs/jsplugin/datepicker/datepicker.en.js"></script>
  <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Експорт даних в Excel</h1>
  </div>
<br><br>
<div style="min-width: 900px;">
    <div id="right" class="float-right"">
        <div
            placeholder="Оберіть дату"
            id="date"
            class="datepicker-here text-center" data-language='en'
            data-min-view="months"
            data-view="months"
            data-date-format="yyyy-mm">
        </div>
    </div>
    <div id="left" style="width: 70%;">
        <table class="table table-striped table-bordered text-center table-hover">
            <tr>
				<th class="text-center">Відділ</th>
                <th class="text-center">Дочірній елемент</th>
                <th class="text-center">Оцінка</th>
                <?php
                    $query = mysqli_query($conn, "SELECT child, parent, note FROM dashboard_data WHERE `date` = '$lastmonth' ORDER BY parent ASC");
                    while($row = mysqli_fetch_assoc($query)) {
                        echo "<tr><td>" . $row['parent'] . "</td><td>" . $row['child'] . "</td><td>" . $row['note'] . "</td>";
                    }
                    
                    $query = mysqli_query($conn, "SELECT child, parent FROM org_units WHERE child NOT IN (( SELECT element FROM result WHERE `current_date` = '$lastmonth')) ORDER BY parent ASC");
                    while($row = mysqli_fetch_assoc($query)) {
                        echo "<tr style='background: tomato; color: white;'><td>" . $row['parent'] . "</td><td>" . $row['child'] . "</td><td>" . " - " . "</td>";
                    }
                ?>
                </tbody>
            </tr>
        </table>
		<br><hr>
		<button class="btn btn-success float-right" id="export">Завантажити Excel</button>
    </div>
</div>
<script>
	$("#export").click(function(){
		if (variable == null) {
			var variable = '<?php echo $lastmonth; ?>';
		}
		window.location.href = "includes/handlers/export_to_excel.php?date=" + variable;
	});
</script>
</main>
<input type="text" id="variable" />
<script>
		$('.datepicker-here').datepicker({
			language: 'en',
			minDate: new Date("2019-01"),
			maxDate: new Date("<?php echo $lastmonth; ?>")
		});

        $('.datepicker-here').datepicker({
                onSelect: function(formattedDate, date, inst) {
                var variable = $('#date').val();
					if (variable !== '') {
						$("#variable").val(variable);
						$.ajax({
							url:"includes/handlers/export_data.php",
							data:{variable:variable},
							type:"POST",
							cache:false,
							async: false,
							success:function(respond){
								$("#left").html(respond);
							}
						});
						$("#export").click(function(){
							window.location.href = "includes/handlers/export_to_excel.php?date=" + variable;
						});
					}
				}
        })
    </script>
<?php
    include "includes/footer.php";
	