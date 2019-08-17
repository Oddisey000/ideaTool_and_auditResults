<?php

session_start();

if( !$_SESSION['loggedInUser'] ) {
    
    header("Location: index.php");
}

include('includes/connection.php');
include('includes/functions.php');

$loggedInUser=$_SESSION['loggedInUser'];

$query="SELECT status FROM users WHERE login='$loggedInUser'";
$result=mysqli_query($conn,$query);
$row=mysqli_fetch_assoc($result);
$today = date("Y-m");

$status=$row['status'];

if(isset($_GET['alert'])) {

if($_GET['alert']=='error') {
    
    $alertMessage="<div class='alert alert-danger' id='alert'>Помилка, дані цієї команди за поточний місяць наявні в базі! <a class='close' data-dismiss='alert'>&times;</a></div>";}
    
    elseif($_GET['alert']=='success') {
    
    $alertMessage="<div class='alert alert-success' id='alert'>Дані успішно внесено в базу! <a class='close' data-dismiss='alert'>&times;</a></div>";
}

    elseif($_GET['alert']=='updatesuccess') {
    
    $alertMessage="<div class='alert alert-success' id='alert'>Дані успішно змінено! <a class='close' data-dismiss='alert'>&times;</a></div>";
}

    elseif($_GET['alert']=='deleted') {
    
    $alertMessage="<div class='alert alert-success' id='alert'>Запис успішно видалено! <a class='close' data-dismiss='alert'>&times;</a></div>";
}

}

if ($status<1) {
    
    header("Location: index.php");
}

$ideaID = $_GET['id'];

$queryEdit = "SELECT * FROM idea_direct WHERE id='$ideaID'";
$resultEdit = mysqli_query( $conn, $queryEdit );

if( mysqli_num_rows($resultEdit) > 0 ) {
    
    while( $row = mysqli_fetch_assoc($resultEdit) ) {
        
        $date        = $row['date'];
        $Date        = $date;
        $idea        = $row['idea'];
        $team        = $row['team'];
        $segment     = $row['segment'];
        $initiator   = $row['initiator'];
    }
}

$queryTeam = "SELECT id AS id, quantity AS quantity, segment AS segment FROM team_quantity WHERE team='$team' AND date LIKE '$today-%'";
$resultTeam = mysqli_query( $conn, $queryTeam );
$rowTeam = mysqli_fetch_array($resultTeam);
$sqlteam=$rowTeam['id'];
$sqlQuantity = $rowTeam['quantity'];
$sqlSegment  = $rowTeam['segment'];

$teamsave=$team;

if( isset($_POST['update']) ) {
    
    $date           = ( $_POST["date"] );
    $idea           = mysql_escape_string($_POST["idea"]);
    $teamBefore     = ( $_POST["team"] );

if ($_POST["team"]) {
    
    $a = array("о","o","м","с","з","н","р","в","к"," ","O","О","М","С","З","Н","Р","В","К");
    $b = array("0","0","M","C","3","H","P","B","K","","0","0","M","C","3","H","P","B","K");
    $team = str_replace($a, $b, $teamBefore);
}
    
    $queryEdit = "UPDATE idea_direct
            SET date='$date',
            idea='$idea',
            team='$team'
            WHERE id='$ideaID'";

    $resultEdit = mysqli_query( $conn, $queryEdit );

    $a = "SELECT team FROM team_quantity WHERE date LIKE '$today-%' AND team='$team' LIMIT 1";
    $b = mysqli_query($conn, $a);

    if (mysqli_num_rows($b) > 0) {

   $query_sync = "UPDATE idea_direct
            SET team='$team'
            WHERE team='$teamsave' AND date LIKE '$today-%' LIMIT 1";

    $result_sync = mysqli_query( $conn, $query_sync );

    $query_update = "UPDATE team_quantity
                    SET date='$date',
                    team='$team'
                    WHERE id=$sqlteam";

    $result_update = mysqli_query( $conn, $query_update);
}
elseif (mysqli_num_rows($b) == 0)
{
    $query_sync = "INSERT INTO team_quantity (segment, team, quantity, date, user) VALUES ('$sqlSegment', '$team', '$sqlQuantity', CURRENT_TIMESTAMP, '$loggedInUser')";

    $result_sync = mysqli_query( $conn, $query_sync );

    $query_update = "UPDATE idea_direct SET quantity='$sqlQuantity' WHERE team='$team' AND date LIKE '$today-%' LIMIT 1";

    $result_update = mysqli_query( $conn, $query_update);
}

    $query_import = "UPDATE import_data SET month='$date' WHERE initiator='$initiator' AND month='$Date' AND result='$team'";
    $result_import = mysqli_query( $conn, $query_import );


    
    if( $resultEdit ) {
        
        header("Location: toonel.php?alert=updatesuccess");
    } 
    else {
        
        echo "Error updating record: " . mysqli_error($conn); 
    }
}

if( isset($_POST['delete']) ) {
    
    $alertMessage = "<div class='alert alert-danger'>
                        <p>Ви справді хочете видалити цю ідею?</p><br>
                        <form action='". htmlspecialchars( $_SERVER["PHP_SELF"] ) ."?id=$ideaID' method='post'>
                            <input type='submit' class='btn btn-danger btn-sm' name='confirm-delete' value='Так, хочу видалити!'>
                            <a type='button' class='btn btn-default btn-sm' data-dismiss='alert'>Ні, не хочу видаляти!</a>
                        </form>
                    </div>";
    
}

if( isset($_POST['confirm-delete']) ) {
    
    $query = "DELETE FROM idea_direct WHERE id='$ideaID'";
    $result = mysqli_query( $conn, $query );

    $query_import = "DELETE FROM import_data WHERE initiator='$initiator' AND month='$Date' AND result='$team'";
    $result_import= mysqli_query( $conn, $query_import );
    
    if( $result ) {
        
        header("Location: toonel.php?alert=deleted");
    } 
    else {
        
        echo "Error updating record: " . mysqli_error($conn);
    }
    
    mysqli_close($conn);
}


include('includes/header.php');

?>

<h1>Редагування Ідей</h1>

<?php echo $alertMessage; ?>

<form action="<?php echo htmlspecialchars( $_SERVER['PHP_SELF'] ); ?>?id=<?php echo $ideaID; ?>" method="post" class="row">
    <div class="form-group col-sm-6">
        <label for="client-name">Команда</label>
        <input type="text" class="form-control input-lg" id="team" name="team" value="<?php echo $team; ?>">
    </div>
    <div class="form-group col-sm-6">
        <label for="client-email">Дата</label>
        <input type="text" class="form-control input-lg" id="date" name="date" value="<?php echo $date; ?>">
    </div>
    <div class="form-group col-sm-6">
        <label for="client-notes">idea</label>
        <textarea type="text" class="form-control input-lg" id="idea" name="idea"><?php echo $idea; ?></textarea>
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
$query = "SELECT * FROM idea_direct WHERE date LIKE '$today-%' ORDER BY date DESC";
$result = mysqli_query($conn,$query);

echo '<table class="table table-striped table-bordered">
            <tr class="info">
                <th class="text-center">Сегмент</th>
                <th class="text-center">Команда</th>
                <th class="text-center">Ідея</th>
                <th class="text-center">Дата</th>
                <th class="text-center">Користувач</th>
                <th></th>';

    while ($row=mysqli_fetch_assoc($result)) {
          
            echo "<tr><td>".$row['segment']."</td><td>".$row['team']."</td><td>".$row['idea']."</td><td>".$row['date']."</td><td>".$row['user']."</td>";

            echo '<td><a href="edit.php?id=' . $row['id'] . '" type="button" class="btn btn-default btn-sm">
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