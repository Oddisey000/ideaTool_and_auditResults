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

	if($_GET['trash'])
	{
		$trash = ($_GET['trash']);
		$query = mysqli_query($conn, "DELETE FROM users WHERE id = '$trash'");

        if ($query) {
            $message = "<div class='alert alert-danger alert-box'>Користувача видалено!</div>";
            header("Refresh: 1; URL=users.php");
        }
	}
    $query = mysqli_query($conn, "SELECT DISTINCT parent FROM org_units");

?>
<script src="../libs/jquery/jquery-1.12.4.min.js"></script>
<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
  <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Зареєстровані користувачі</h1>
    <button class="btn btn-sm btn-outline-secondary" data-toggle="modal" data-target="#user_registration">Додати користувача</button>
  </div>
<input type="text" class="form-control input-lg filter" placeholder="Пошук користувача"><br>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
</div>
<span id="alert_message"></span>
<div class="modal fade" id="user_registration">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="form-group">
                            <input type="text" id="user_name" placeholder="Логін користувача" class="form-control"><br>
                            <select class="form-control" id="user_org_unit">
                            	<?php
                                    while ($row = mysqli_fetch_array($query)) {
                                        echo "<option>" . $row['parent'] . "</option>";
                                    }
                                ?>
                            </select>
                            <div class="form-inline form-group">
                                <input type="checkbox" id="user_right" style="margin: 0 0 -5px 0;"><p style="margin: 10px 10px 10px 10px;" class="lead">Адміністратор</p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-info" id="add_user">Додати користувача</button>
                    </div>
                </div>
            </div>
        </div>
        <?php echo $message; ?>
	<table class="table table-striped table-bordered text-center table-hover">
		<tr>
		    <th class="text-center">Сегмент</th>
		    <th class="text-center">Логін</th>
		    <th class="text-center">Видалити</th>
		    <tbody class="filterData">
		    <?php
		    	$query 		= "SELECT segment, login, id FROM users ORDER BY created DESC";
		    	$request 	= mysqli_query($conn, $query);

		    	while($row = mysqli_fetch_assoc($request))
		    	{
		    		echo "<tr><td>" . $row['segment'] . "</td><td>" . $row['login'] . "</td>";
		    		echo "<td><a href='users.php?trash=" . $row['id'] . "'" . "name='trash'><span data-feather='trash-2'></span></a></td>";
		    	}
		    ?>
		    </tbody>
		</tr>
	</table>
</main>
<?php
	require('includes/footer.php');
	
