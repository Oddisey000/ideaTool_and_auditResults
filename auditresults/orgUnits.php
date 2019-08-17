<?php
	require('includes/header.php');
    require('includes/connection.php');
    
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

	if(isset($_GET['id']))
	{
		$trash = ($_GET['id']);
		
		$query 		= "DELETE FROM org_units WHERE id = '$trash'";
		$request 	= mysqli_query($conn, $query);

		if($request)
		{
			$success = "<div class='alert alert-danger alert-box'>Видалено!</div>";
            header("Refresh: 1; URL=orgUnits.php");
		}
	}
?>
<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
  <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
  <h1 class="h2">Структура відділів</h1>
  <button class="btn btn-sm btn-outline-secondary" data-toggle="modal" data-target="#org_units_registration">Додати новий елемент</button>
  </div>
<div class="modal fade" id="org_units_registration">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-body">
                <div class="form-group">
                    <input type="text" id="place_name" placeholder="Дочірній елемент" class="form-control"><br>
                    <input type="text" id="org_unit_name" placeholder="Відділ" class="form-control"><br>
                    <label class="lead">Оберіть опитувальник</label>
                    <select class="form-control" id="questionnaire_type">
                        <option>Стандарт</option>
                        <option>Офіс</option>
                        <option>Продукція</option>
                        <option>Автопарк</option>
                        <option>Склад</option>
                        <option>Технічна дільниця</option>
                        <option>Нарадчі кімнати</option>
                        <option>Кухня</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-info" id="add_org_unit">Додати елемент</button>
            </div>
        </div>
    </div>
</div>
  	<input type="text" class="form-control input-lg filter" placeholder="Пошук відділу"><br>
    <span id="alert_message"></span>
    <?php
        echo $success;
    ?>
	<table class="table table-striped table-bordered text-center table-hover">
		<tr>
			<th class="text-center">Відділ</th>
		    <th class="text-center">Дочірній елемент</th>
            <th class="text-center">Опитувальник</th>
		    <th class="text-center">Видалити</th>
		    <tbody class="filterData">
		    <?php
		    	$query 		= "SELECT child, parent, id, questionnaire FROM org_units ORDER BY parent, child";
		    	$request 	= mysqli_query($conn, $query);

		    	while($row = mysqli_fetch_assoc($request))
		    	{
		    		echo "<tr><td>" . $row['parent'] . "</td><td>" . $row['child'] . "</td><td>" . $row['questionnaire'] . "</td>";
		    		echo "<td><a href='orgUnits.php?id=" . $row['id'] . "' name='trash'><span data-feather='trash-2'></span></a></td>";
		    	}
		    ?>
		    </tbody>
		</tr>
	</table>
</main>
<?php
	require('includes/footer.php');
	