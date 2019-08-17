<?php

session_start();

if (!$_SESSION['loggedInUser']) {
    header("Location: index.php");
}

include('includes/connection.php');

$loginGet = $_SESSION['loggedInUser'];

$queryS  = "SELECT status, segment FROM users WHERE login='$loginGet'";
$resultS = mysqli_query($conn, $queryS);
$rowS    = mysqli_fetch_assoc($resultS);

$status   = $rowS['status'];
$Segment  = $rowS['segment'];
$segment  = $Segment;
$ThisYear = date('Y');
$last_year_december = date("Y", strtotime("-1 years"));

$query       = "SELECT price FROM service_table WHERE year='$ThisYear'";
$result      = mysqli_query($conn, $query);
$priceBefore = mysqli_fetch_assoc($resultS);
$price       = $priceBefore['price'];

$query  = "SELECT * FROM idea_direct WHERE segment='$segment'";
$result = mysqli_query($conn, $query);

$today     = date("Y-m");
$lastmonth = date("Y-m", strtotime("-1 months"));

//if ($status > 0) {
    $a = "SELECT id FROM team_quantity WHERE date LIKE '$today-%' LIMIT 1";
    $b = mysqli_query($conn, $a);
    if (mysqli_num_rows($b) < 1) {
        $a = "SELECT * FROM team_quantity WHERE date LIKE '$lastmonth-%'";
        $b = mysqli_query($conn, $a);
        foreach ($b as $row) {
            $lastSegment  = $row['segment'];
            $lastTeam     = $row['team'];
            $lastQuantity = $row['quantity'];
            $lastUser     = $row['user'];
            $a            = "INSERT INTO team_quantity (segment, team, quantity, user, date) VALUES ('$lastSegment', '$lastTeam', '$lastQuantity', '$lastUser', CURRENT_TIMESTAMP)";
            $b            = mysqli_query($conn, $a);
        }
    }
//}

if (isset($_GET['alert'])) {
    if ($_GET['alert'] == 'success') {
        $alertMessage = "<div class='alert alert-success' id='alert'>Ідею успішно внесено в базу! <a class='close' data-dismiss='alert'>&times;</a></div>";
    } elseif ($_GET['alert'] == 'updatesuccess') {
        $alertMessage = "<div class='alert alert-success' id='alert'>Дані успішно оновлено! <a class='close' data-dismiss='alert'>&times;</a></div>";
    } elseif ($_GET['alert'] == 'deleted') {
        $alertMessage = "<div class='alert alert-success' id='alert'>Ідею видалено! <a class='close' data-dismiss='alert'>&times;</a></div>";
    }
    
    mysqli_close($conn);
}

include('includes/header.php');

?>

<?php
echo $alertMessage;
?>
<?php
if ($status < 1) {
    echo '<h1>Засіб управління ідеями постійного покращення<a href="add.php" type="button" class="btn btn-lg btn-success pull-right">Внести дані</a>';
} elseif ($status > 0) {
    echo '<h1>Засіб управління ідеями постійного покращення</h1>';
}
?>


<?php

session_start();

?>

</h1>

<br><br>

<?php

if ($status > 0) {
    echo '<br><br><a href="teams.php" type="button" class="btn btn-lg btn-info pull-right">Редагувати команди</a>';
    
    echo "<a href='new.php' type='button' class='btn btn-lg btn-warning pull-left'>Редагувати користувачів</a><br><br><br><br>";
}
?>





<?php

if ($status > 0) {
    echo '<div class="text-left" id="accordion">
      
      <h4>Сформувати запит</h4>
      
      <div>
      <form action="';
    
}

?> 

      <?php

if ($status > 0) {
    echo htmlspecialchars($_SERVER['PHP_SELF']);
}
?>

      <?php

if ($status > 0) {
    echo '?id=';
}
?>
        
        <?php

if ($status > 0) {
    echo $ideaID;
}
?>

      <?php

if ($status > 0) {
    echo '" method="post" class="row">
    <div class="form-group col-sm-4 col-sm-offset-2">
        <input type="text" placeholder="Початкова дата" class="date form-control input-sm text-center" id="date_start" name="date_start">
    </div>
    <div class="form-group col-sm-4">
        <input type="text" placeholder="Кінцева дата" class="date form-control input-sm text-center" id="date_end" name="date_end">
    </div>
    <div class="col-sm-8 text-center col-sm-offset-2">
            <button type="submit" class="btn btn-lg btn-success " name="makeRequest">Сформувати запит</button>
          </div>
    </div>
</form>';
    
}

?>


<!----------------------------------------------------------------------------------------------------------------------------------------->

<?php

if ($status < 1) {
    echo '<br><div class="text-left" id="accord">
      
      <h4>Відобразити дані по місяцях</h4>
      
      <div>
      <form action="';
    
}

?> 

      <?php

if ($status < 1) {
    echo htmlspecialchars($_SERVER['PHP_SELF']);
}
?>

      <?php

if ($status < 1) {
    echo '?id=';
}
?>
        
        <?php

if ($status < 1) {
    echo $ideaID;
}
?>

      <?php

if ($status < 1) {
    echo '" method="post" class="row">
    <div class="form-group col-sm-12 text-center">
        <button class="submit btn btn-sm" name="last_year_december">Грудень '.$last_year_december.'</button>
        <button class="submit btn btn-sm" name="january">Січень</button>
        <button class="submit btn btn-sm" name="february">Лютий</button>
        <button class="submit btn btn-sm" name="march">Березень</button>
        <button class="submit btn btn-sm" name="april">Квітень</button>
        <button class="submit btn btn-sm" name="may">Травень</button>
        <button class="submit btn btn-sm" name="june">Червень</button>
        <button class="submit btn btn-sm" name="july">Липень</button>
        <button class="submit btn btn-sm" name="august">Серпень</button>
        <button class="submit btn btn-sm" name="september">Вересень</button>
        <button class="submit btn btn-sm" name="october">Жовтень</button>
        <button class="submit btn btn-sm" name="november">Листопад</button>
        <button class="submit btn btn-sm" name="december">Грудень</button>
    </div>
    </div>
</form>';
    
}

echo "</div>
    
    <br>";

?>
<input id="myInput" type="text" class="form-control input-lg" placeholder="Фільтр за сегментом чи командою...">
<?php
if ($status > 0) {
include('includes/result.php');
}
elseif ($status < 1) {
include('includes/datausers.php');
}
?>

<?php

if ($status > 0) {
    if (isset($_POST['makeRequest'])) {
        echo '<div class="text-left" id="accord">
            <h4>Переглянути ідеї</h4>
            <table class="table table-striped table-bordered">
            <tr class="info">
                <th class="text-center">Сегмент</th>
                <th class="text-center">Команда</th>
                <th class="text-center">Ідея</th>
                <th class="text-center">Дата</th>
                <th class="text-center">Користувач</th>
                <th></th>
                <tbody class="myTable">
                </div>';
    }
    
    else {
        echo '<div class="text-left" id="accord">
            <h4>Переглянути ідеї</h4>
            <table class="table table-striped table-bordered">
            <tr class="info">
                <th class="text-center">Сегмент</th>
                <th class="text-center">Команда</th>
                <th class="text-center">Ідея</th>
                <th class="text-center">Дата</th>
                <th class="text-center">Користувач</th>
                <th></th>
                <tbody class="myTable">
                </div>';
    }
}
?>
<!------------------------------------------------------------------------------------------------------------------------------------------>
<?php

if ($status < 1) {
    if (isset($_POST['january'])||isset($_POST['february'])||isset($_POST['march'])||isset($_POST['april'])||isset($_POST['may'])||isset($_POST['june'])||isset($_POST['july'])||isset($_POST['august'])||isset($_POST['september'])||isset($_POST['october'])||isset($_POST['november'])||isset($_POST['december'])||isset($_POST['last_year_december'])) {
        echo '<div class="text-left" id="accordion">
            <h4>Переглянути ідеї</h4>
            <table class="table table-striped table-bordered">
            <tr class="info">
                <th class="text-center">Сегмент</th>
                <th class="text-center">Команда</th>
                <th class="text-center">Ідея</th>
                <th class="text-center">Дата</th>
                <th class="text-center">Користувач</th>
                <tbody class="myTable">';
            } else {
        echo '<div class="text-left" id="accordion">
            <h4>Переглянути ідеї</h4>
            <table class="table table-striped table-bordered">
            <tr class="info">
                <th class="text-center">Сегмент</th>
                <th class="text-center">Команда</th>
                <th class="text-center">Ідея</th>
                <th class="text-center">Дата</th>
                <th class="text-center">Користувач</th>
                <tbody class="myTable">';
    }
}
?>


<!------------------------------------------------------------------------------------------------------------------------------------------>
<?php
if ($status < 1) {
include('includes/foruser.php');
}
?>
        
       
<!------------------------------------------------------------------------------------------------------------------------------------------>
        <?php

if ($status < 1) {

    if (isset($_POST['january'])||isset($_POST['february'])||isset($_POST['march'])||isset($_POST['april'])||isset($_POST['may'])||isset($_POST['june'])||isset($_POST['july'])||isset($_POST['august'])||isset($_POST['september'])||isset($_POST['october'])||isset($_POST['november'])||isset($_POST['december'])||isset($_POST['last_year_december'])) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row['segment'] . "</td><td>" . $row['team'] . "</td><td>" . $row['idea'] . "</td><td>" . $row['date'] . "</td><td>" . $row['user'] . "</td></tr>";
            
        }
        
    } else {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row['segment'] . "</td><td>" . $row['team'] . "</td><td>" . $row['idea'] . "</td><td>" . $row['date'] . "</td><td>" . $row['user'] . "</td></tr>";
            
        }
        
    }
    echo "</div>";
}



?>
<!------------------------------------------------------------------------------------------------------------------------------------------>
   
    </tr>

    <?php

session_start();
$today = date("Y-m");

if ($status > 0) {
    if (isset($_POST['makeRequest'])) {
        $date_start = ($_POST["date_start"]);
        $date_end   = ($_POST["date_end"] . " 23:59:59.999");
        
        $query  = "SELECT * FROM idea_direct WHERE date BETWEEN '$date_start' AND '$date_end' ORDER BY date DESC";
        $result = mysqli_query($conn, $query);
        
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row['segment'] . "</td><td>" . $row['team'] . "</td><td>" . $row['idea'] . "</td><td>" . $row['date'] . "</td><td>" . $row['user'] . "</td>";
            echo '<td><a href="edit.php?id=' . $row['id'] . '" type="button" class="btn btn-default btn-sm">
                    <span class="glyphicon glyphicon-pencil"></span>
                    </a></td>';
            echo "</tr>";
            
        }
    }
    
    else {
        $query  = "SELECT * FROM idea_direct WHERE date LIKE '$today-%' ORDER BY date DESC";
        $result = mysqli_query($conn, $query);
        
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row['segment'] . "</td><td>" . $row['team'] . "</td><td>" . $row['idea'] . "</td><td>" . $row['date'] . "</td><td>" . $row['user'] . "</td>";
            echo '<td><a href="edit.php?id=' . $row['id'] . '" type="button" class="btn btn-default btn-sm">
                    <span class="glyphicon glyphicon-pencil"></span>
                    </a></td>';
            echo "</tr>";
        }
    }
}

?>


   
</tbody>
</table>


<?php

include('includes/footer.php');

?>