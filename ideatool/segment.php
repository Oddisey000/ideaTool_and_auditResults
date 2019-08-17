<?php

session_start();

if(!$_SESSION['loggedInUser']) {
    
    header("Location: index.php");
}

include('includes/connection.php');
include('includes/functions.php');

$loggedInUser=$_SESSION['loggedInUser'];

$query="SELECT status FROM users WHERE login='$loggedInUser'";
$result=mysqli_query($conn,$query);
$row=mysqli_fetch_assoc($result);

$status=$row['status'];

if ($status<1) {
    
    header("Location: index.php");
}

if($_GET['alert']=='error'){
            
            $alertMessage="<div class='alert alert-danger'>Обов'язково потрібно заповнити обидва поля! <a class='close' data-dismiss='alert'>&times;</a></div>";
        }

elseif($_GET['alert']=='success') {
    
    $alertMessage="<div class='alert alert-success' id='alert'>Дані успішно додано! <a class='close' data-dismiss='alert'>&times;</a></div>";
}
elseif($_GET['alert']=='delete'){
            
            $alertMessage="<div class='alert alert-danger'>Сегмент успішно видалено з бази! <a class='close' data-dismiss='alert'>&times;</a></div>";
        }

if(isset($_POST['submit'])){

    $segment=($_POST["segment"]);
    $orgUnit=($_POST["orgUnit"]);

    
    if($segment&&$orgUnit>"") {
        
        $query="INSERT INTO segments (id, segment, orgUnit) VALUES (NULL, '$segment', '$orgUnit')";
        $result=mysqli_query($conn,$query);
            
            header( "Location: segment.php?alert=success" );
        } 
        else {
            
            header( "Location: segment.php?alert=error" );
        }       
}

include('includes/header.php');

?>

<?php echo $alertMessage;?>

<h1>Редагування сегментів</h1>
<br><br><br>

<form class="form-inline text-center" action="<?php echo htmlspecialchars( $_SERVER['PHP_SELF'] ); ?>" method="post">
    <div class="form-group">
        <input type="text" class="form-control" id="segment" title="Потрібно вказати сегмент" placeholder="Вкажіть сегмент" name="segment">
    </div>
    <div class="form-group">
        <input type="text" class="form-control" id="orgUnit" title="Потрібно вказати приналежність сегменту до організаційної одиниці" placeholder="Орг. одиниця" name="orgUnit">
    </div>
    <button type="submit" class="btn btn-success" name="submit">Додати сегмент</button>
</form>

<br><br><br><a href="new.php" type="button" class="btn btn-lg btn-info pull-right">Повернутися</a><br><br><br><br>

<?php
$query = "SELECT segment, orgUnit FROM segments ORDER BY id DESC";
$result = mysqli_query($conn,$query);

echo '<table class="table table-striped table-bordered text-center table-hover">
            <tr class="success">
                <th class="text-center">Сегмент</th>
                <th class="text-center">Орг. одиниця</th>
                <th class="text-center">Видалити</th>';

    while ($row=mysqli_fetch_assoc($result)) {
          
            echo "<tr><td>".$row['segment']."</td><td>".$row['orgUnit']."</td>";

            echo '<td><a title="Видалити дані" href="includes/delete_segment.php?segment=' . $row['segment'] . '" type="button" class="btn btn-default btn-sm">
                    <span class="glyphicon glyphicon-minus"></span>
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