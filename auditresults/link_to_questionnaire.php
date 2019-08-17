<?php
	include "includes/header.php";
    include "includes/connection.php";
    // Find current user
    $loggedInUser = $_SESSION['loggedInUser'];
    $lastmonth = date("Y-m", strtotime("first day of previous month"));
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
        $query = mysqli_query($conn, "SELECT child, parent FROM org_units WHERE child NOT IN (( SELECT element FROM result WHERE `current_date` = '$lastmonth')) ORDER BY parent ASC");
    } else {
        $query = mysqli_query($conn, "SELECT child FROM org_units WHERE child NOT IN (( SELECT element FROM result WHERE `current_date` = '$lastmonth')) AND parent = '$segment' ORDER BY child ASC");
    }

?>
<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
  <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Оберіть ділянку та внесіть результат</h1>
  </div>
  <h6>
        <?php
            if ($status == 1) {
				echo '<input type="text" class="form-control input-lg filter" placeholder="Пошук даних"><br>';
				echo '<table class="table table-striped table-bordered text-center">
						<tr>
							<th class="text-center">Відділ</th>
							<th class="text-center">Дочірній елемент</th>
							<th class="text-center">Статус</th>
							<tbody class="filterData">';
                while ($row = mysqli_fetch_array($query)) {
                    echo '<tr>
								<td>' . $row['parent'] . '</td><td><a href="questionnaire.php?element=' . $row['child'] . '">' . $row['child'] . '</a></td><td><p>Дані відсутні</p></td>
							</tr>';
                }
            } else {
                while ($row = mysqli_fetch_array($query)) {
                    echo "<a href='questionnaire.php?element=" . $row['child'] . "'>" . $row['child'] . "</a><br><br><br><hr>";
                }
            }
            
            if ($status == 1) {
                $query = mysqli_query($conn, "SELECT child, parent FROM org_units WHERE child IN (( SELECT element FROM result WHERE `current_date` = '$lastmonth')) ORDER BY parent ASC");
                while ($row = mysqli_fetch_array($query)) {
					echo '<tr>
							<td>' . $row['parent'] . '</td><td><a style="color: green;" href="questionnaire_public.php?element=' . $row['child'] . '&date=' . $lastmonth . '">' . $row['child'] . '</a></td><td><p>Дані успішно внесено</p></td>
						</tr>';
                }
			echo '</tr></tbody></table>';
            } else {
                $query = mysqli_query($conn, "SELECT child FROM org_units WHERE child IN (( SELECT element FROM result WHERE `current_date` = '$lastmonth')) AND parent = '$segment' ORDER BY child ASC");
            
                while ($row = mysqli_fetch_array($query)) {
                    echo '<a style="color: green;" href="questionnaire_public.php?element=' . $row['child'] . '&date=' . $lastmonth . '">' . $row['child'] . '</a><p class="float-right">Дані успішно внесено</p><br><hr>';
                }
            }
        ?>
    </h6>
</main>
<?php
    include "includes/footer.php";
	