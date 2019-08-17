<?php

session_start();

if( !$_SESSION['loggedInUser'] ) {
    
    header("Location: index.php");
}

include('includes/connection.php');
include('includes/functions.php');

$alertMessage[0] = "<div class='alert alert-success' id='alert'>Дані успішно внесено в базу! <a class='close' data-dismiss='alert'>&times;</a></div>";
$alertMessage[1] = "<div class='alert alert-danger' id='alert'> Команду видалено! <a class='close' data-dismiss='alert'>&times;</a></div>";

$loggedInUser=$_SESSION['loggedInUser'];
$today = date("Y-m");
$id = $_GET['id'];
$query = "SELECT segment AS segment FROM team_quantity WHERE id='$id'";
$result = mysqli_query($conn, $query);
$temp=mysqli_fetch_assoc($result);
$TeamSegment     = $temp['segment'];
$query = "SELECT * FROM team_quantity WHERE id='$id' AND segment='$TeamSegment'";
$result = mysqli_query( $conn, $query );

if( mysqli_num_rows($result) > 0 ) { 
    while( $row = mysqli_fetch_assoc($result) ) {
        $team        = $row['team'];
        $quantity     = $row['quantity'];
    }
}
if( isset($_POST['update']) ) {
    
    $team           = ($_POST["team"]);
    $quantity       = ($_POST["quantity"]);

    if ($team&&$quantity >'') {

    $query = "UPDATE team_quantity SET quantity='$quantity', user='$loggedInUser' WHERE team='$team' AND date LIKE '$today-%'";
    $result = mysqli_query($conn,$query);
    $query = "UPDATE idea_direct SET quantity='$quantity' WHERE team='$team' AND date LIKE '$today-%' LIMIT 1";
    $resultend = mysqli_query($conn, $query);
}
    $team = '';
    $quantity = '';

    if( $resultend ) {
        
        echo $alertMessage[0];
    } 
    
}

if( isset($_POST['delete']) ) {
    
    $query = "DELETE FROM team_quantity WHERE id='$id'";
    $result = mysqli_query( $conn, $query );
    $query = "DELETE FROM idea_direct WHERE team='$team' AND date LIKE '$today-%'";
    $result = mysqli_query( $conn, $query );
    $query = "DELETE FROM import_data WHERE result='$team' AND month LIKE '$today-%'";
    $resultend = mysqli_query( $conn, $query );

    $team = '';
    $quantity = '';
    
    if( $resultend ) {
        
        echo $alertMessage[1];
    } 
    
}


include('includes/header.php');

?>

<h1>Редагування команд</h1>

<form action="<?php echo htmlspecialchars( $_SERVER['PHP_SELF'] ); ?>?id=<?php echo $id; ?>" method="post" class="row">
    <div class="form-group col-sm-6">
        <label for="client-name">Команда</label>
        <input type="text" class="form-control input-lg" id="team" name="team" value="<?php echo $team; ?>">
    </div>
    <div class="form-group col-sm-6">
        <label for="client-notes">Кількість</label>
        <input type="text" class="form-control input-lg" id="idea" name="quantity" pattern="^[0-9]+$" title="Тільки цифри" value="<?php echo $quantity; ?>"></input>
    </div>
    <div class="col-sm-12">
        <hr>
        <button type="submit" class="btn btn-lg btn-danger pull-left" name="delete">Видалити</button>
        <div class="pull-right">
            <a href="ideas.php" type="button" class="btn btn-lg btn-default">Повернутися</a>
            <button type="submit" class="btn btn-lg btn-success" name="update">Оновити дані</button>
        </div>
    </div>
</form><br><br>

<?php
$query = "SELECT * FROM team_quantity WHERE date LIKE '$today-%' AND segment='$TeamSegment'";
$result = mysqli_query($conn,$query);

echo '<table class="table table-striped table-bordered">
            <tr class="info">
                <th class="text-center">Сегмент</th>
                <th class="text-center">Команда</th>
                <th class="text-center">Кількість</th>
                <th class="text-center">Користувач</th>
                <th></th>';

    while ($row=mysqli_fetch_assoc($result)) {
          
            echo "<tr><td>".$row['segment']."</td><td>".$row['team']."</td><td>".$row['quantity']."</td><td>".$row['user']."</td>";

            echo '<td><a href="editBySegment.php?id=' . $row['id'] . '" type="button" class="btn btn-default btn-sm">
                    <span class="glyphicon glyphicon-pencil"></span>
                    </a></td>';
             echo "</tr>";

        }

?>

<?php

echo '<script src="../libs/jquery/jquery-2.1.4.min.js"></script>
        <script src="../libs/bootstrap/bootstrap.min_v3.3.7.js"></script>
        <script src="../libs/jquery-ui/jquery-ui.min_v1.12.1.js"></script>
        <script src="js/script.js"></script>';

?>