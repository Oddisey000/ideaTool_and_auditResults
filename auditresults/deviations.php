<?php
	include "includes/header.php";
    include "includes/connection.php";
    // Find current user
    $loggedInUser = $_SESSION['loggedInUser'];
    $lastmonth = date("Y-m", strtotime("first day of previous month"));
	$lastmonth_2 = date("Y-m", strtotime("- 2 month"));
    $today = date("Y-m");
    // Looking which segment this user is represent
    $query = mysqli_query($conn, "SELECT segment FROM users WHERE login = '$loggedInUser' LIMIT 1");
    $row = mysqli_fetch_array($query);
    $segment = $row['segment'];
    // Storing all element related to this segment
    $query = mysqli_query($conn, "SELECT child FROM org_units WHERE parent = '$segment'");
    //Checking if there is more than one and back user direct to questionnaire, else give him a elements
    if (mysqli_num_rows($query) < 1) {
        header("Location: questionnaire.php");
    } 

    $query = mysqli_query($conn, "SELECT `status` FROM users WHERE `login` = '$loggedInUser'");
    $row = mysqli_fetch_array($query);
    $status = $row['status'];
    
    if ($status == 1) {
        $query = mysqli_query($conn, "SELECT DISTINCT `element`, `parent`, `current_date` FROM result WHERE `note_value` BETWEEN 1 AND 4 AND `current_date` = '$lastmonth_2' AND `action` = '' OR `action` = 'Зарадча дія' ORDER BY `parent` ASC");
    } else {
        header("Location: questionnaire.php");
    }

?>
<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
  <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Оберіть ділянку та внесіть результат</h1>
  </div>
  <h6>
        <?php
				echo '<input type="text" class="form-control input-lg filter" placeholder="Пошук даних"><br>';
				echo '<table class="table table-striped table-bordered text-center">
						<tr>
							<th class="text-center">Відділ</th>
							<th class="text-center">Дочірній елемент</th>
							<th class="text-center">Статус</th>
							<th class="text-center">Дата</th>
							<tbody class="filterData">';
                while ($row = mysqli_fetch_array($query)) {
                    echo '<tr>
								<td>' . $row['parent'] . '</td><td><a href="questionnaire_public.php?element=' . $row['element'] . '&date=' . $lastmonth_2 . '">' . $row['element'] . '</a></td><td><p style="color: tomato;">Незакриті пункти відхилень</p></td><td>' . $row['current_date'] . '</td>
							</tr>';
                }
                $query = mysqli_query($conn, "SELECT DISTINCT `element`, `parent`, `current_date` FROM result WHERE `note_value` BETWEEN 1 AND 4 AND `current_date` = '$lastmonth' AND `action` = '' OR `action` = 'Зарадча дія' ORDER BY `parent` ASC");
                while ($row = mysqli_fetch_array($query)) {
					echo '<tr>
							<td>' . $row['parent'] . '</td><td><a href="questionnaire_public.php?element=' . $row['element'] . '&date=' . $lastmonth . '">' . $row['element'] . '</a></td><td><p style="color: tomato;">Незакриті пункти відхилень</p></td><td>' . $row['current_date'] . '</td>
						</tr>';
                }
				echo '</tr></tbody></table>';
        ?>
    </h6>
</main>
<?php
    include "includes/footer.php";
	