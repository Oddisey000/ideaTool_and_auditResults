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
$today = date("Y-m"."%");
$anothertoday = date("Y-m-%");

$status=$row['status'];

if ($status<1) {
    
    header("Location: index.php");
}

$teamID = $_GET['id'];

$queryEdit = "SELECT * FROM team_quantity WHERE id='$teamID'";
$resultEdit = mysqli_query( $conn, $queryEdit );

if( mysqli_num_rows($resultEdit) > 0 ) {
    
    while( $row = mysqli_fetch_assoc($resultEdit) ) {
        
        $date        = $row['date'];
        $segment     = $row['segment'];
        $team        = $row['team'];
        $quantity    = $row['quantity'];
    }
}

$teamBefore_update = $team;

if( isset($_POST['update']) ) {
    
    $date           = ( $_POST["date"] );
    $segment        = ( $_POST["segment"] );
    $teamBefore     = ( $_POST["team"] );
    $quantity       = ( $_POST["quantity"] );

if ($_POST["team"]) {
    
    $a = array("о","o","м","с","з","н","р","в","к"," ","O","О","М","С","З","Н","Р","В","К");
    $b = array("0","0","M","C","3","H","P","B","K","","0","0","M","C","3","H","P","B","K");
    $team = str_replace($a, $b, $teamBefore);
}
    
    $queryEdit = "UPDATE team_quantity
            SET date='$date',
            segment='$segment',
            team='$team',
            quantity='$quantity'
            WHERE id='$teamID'";

    $sql = "UPDATE idea_direct SET quantity='$quantity' WHERE team='$team' AND date LIKE '$today' limit 1";
        $sqlResult=mysqli_query($conn,$sql);

    $sql = "UPDATE idea_direct SET team='$team' WHERE team='$teamBefore_update' AND date LIKE '$anothertoday'";
     $sqlResult=mysqli_query($conn,$sql);


    $resultEdit = mysqli_query( $conn, $queryEdit );
    
    if( $resultEdit ) {
        
        header("Location: teams.php?alert=updatesuccess");
    } 
    else {
        
        echo "Error updating record: " . mysqli_error($conn); 
    }
}

if( isset($_POST['delete']) ) {
    
    $alertMessage = "<div class='alert alert-danger'>
                        <p>Ви справді хочете видалити цю команду?</p><br>
                        <form action='". htmlspecialchars( $_SERVER["PHP_SELF"] ) ."?id=$teamID' method='post'>
                            <input type='submit' class='btn btn-danger btn-sm' name='confirm-delete' value='Так, хочу видалити!'>
                            <a type='button' class='btn btn-default btn-sm' data-dismiss='alert'>Ні, не хочу видаляти!</a>
                        </form>
                    </div>";
    
}

if( isset($_POST['confirm-delete']) ) {
    $query  = "SELECT team AS team FROM team_quantity WHERE id='$teamID'";
    $result = mysqli_query($conn, $query);
    $row    = mysqli_fetch_assoc($result);
    $i      = $row['team'];
    
    $query  = "DELETE FROM team_quantity WHERE id='$teamID'";
    $result = mysqli_query( $conn, $query );

    $query  = "DELETE FROM idea_direct WHERE team ='$i' AND date LIKE '$anothertoday'";
    $result = mysqli_query($conn, $query);

    $query  = "DELETE FROM import_data WHERE result = '$i' AND month LIKE '$anothertoday'";
    $result = mysqli_query($conn, $query);

    if( $result ) {
        
        header("Location: teams.php?alert=deleted");
    } 
    else {
        
        echo "Error updating record: " . mysqli_error($conn);
    }
    
    mysqli_close($conn);
}


include('includes/header.php');

?>

<h1>Редагування Команд</h1>

<?php echo $alertMessage; ?>

<form action="<?php echo htmlspecialchars( $_SERVER['PHP_SELF'] ); ?>?id=<?php echo $teamID; ?>" method="post" class="row">
    <div class="form-group col-sm-6">
        <label for="client-name">Команда</label>
        <input type="text" class="form-control input-lg" id="team" name="team" value="<?php echo $team; ?>">
    </div>
    <div class="form-group col-sm-6">
        <label for="client-email">Дата</label>
        <input type="text" class="form-control input-lg" id="date" name="date" value="<?php echo $date; ?>">
    </div>
    <div class="form-group col-sm-6">
        <label for="client-notes">Сегмент</label>
        <input type="text" class="form-control input-lg" id="idea" name="segment" value="<?php echo $segment;?>">
    </div>
    <div class="form-group col-sm-6">
        <label for="client-email">Кількість працівників</label>
        <input type="text" class="form-control input-lg" id="date" name="quantity" value="<?php echo $quantity; ?>">
    </div>
    <div class="col-sm-12">
        <hr>
        <button type="submit" class="btn btn-lg btn-danger pull-left" name="delete">Видалити</button>
        <div class="pull-right">
            <a href="ideas.php" type="button" class="btn btn-lg btn-default">Повернутися</a>
            <button type="submit" class="btn btn-lg btn-success" name="update">Оновити дані</button>
        </div>
    </div>
</form>

<?php

include('includes/footer.php');

?>