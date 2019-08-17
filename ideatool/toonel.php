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
        $idea        = $row['idea'];
        $team        = $row['team'];
    }
}

if( isset($_POST['update']) ) {
    
    $date     = ( $_POST["date"] );
    $idea     = ( $_POST["idea"] );
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
    
    if( $resultEdit ) {
        
        header("Location: edit.php?alert=updatesuccess");
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
    
    if( $result ) {
        
        header("Location: edit.php?alert=deleted");
    } 
    else {
        
        echo "Error updating record: " . mysqli_error($conn);
    }
    
    mysqli_close($conn);
}


include('includes/header.php');

?>

<h1>Редагування Ідей</h1>
<br><br>

<?php echo $alertMessage; ?>

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