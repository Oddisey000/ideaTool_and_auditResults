<?php

session_start();

if (!$_SESSION['loggedInUser']) {
    header("Location: index.php");
}

$alertMessage[3] = "<div class='alert alert-success' id='alert'>Дані успішно внесено в базу! <a class='close' data-dismiss='alert'>&times;</a></div>";
$alertMessage[1] = "<div class='alert alert-danger' id='alert'> Будь ласка введіть команду! <a class='close' data-dismiss='alert'>&times;</a></div>";
$alertMessage[2] = "<div class='alert alert-danger' id='alert'>Будь ласка вкажіть кількість людей!<a class='close' data-dismiss='alert'>&times;</a></div>";
$alertMessage[4] = "<div class='alert alert-danger' id='alert'>Дані цієї команди за поточний місяць вже наявні в базі!<a class='close' data-dismiss='alert'>&times;</a></div>";
$alertMessage[0] = "<div class='alert alert-danger' id='alert'>Оберіть будь ласка сегмент!<a class='close' data-dismiss='alert'>&times;</a></div>";

$loggedInUser = $_SESSION['loggedInUser'];

include('includes/connection.php');
include('includes/functions.php');

$querySegment  = "SELECT status, segment FROM users WHERE login='$loggedInUser'";
$resultSegment = mysqli_query($conn, $querySegment);
$rowSegment    = mysqli_fetch_assoc($resultSegment);

$login   = $rowSegment['login'];
$segment = $rowSegment['segment'];
$Segment = $segment;
$status  = $rowSegment['status'];
$today   = date("Y-m" . "%");

$query     = "SELECT team FROM team_quantity WHERE team='$team' AND date LIKE '$today'";
$result    = mysqli_query($conn, $query);
$row       = mysqli_fetch_assoc($result);
$dublicate = $row['team'];
$Dublicate = $dublicate;


if (isset($_POST['add'])) {
    $team = $quantity = "";
    
    if (!$_POST["team"]) {
        echo $alertMessage[1];
    } else {
        $team = validateFormData($_POST["team"]);
    }
    
    if (!$_POST["quantity"]) {
        echo $alertMessage[2];
    } elseif (!$_POST["segment"]) {
        echo $alertMessage[0];
    } else {
        $quantity = ($_POST["quantity"]);
        
        $segment_admin = ($_POST["segment"]);
    }
    
    $segment_admin = ($_POST["segment"]);
    $quantity      = ($_POST["quantity"]);
    $teamBefore    = ($_POST["team"]);
    
    if ($_POST["team"]) {
        $a    = array(
            "о",
            "o",
            "м",
            "с",
            "з",
            "н",
            "р",
            "в",
            "к",
            " ",
            "O",
            "О",
            "М",
            "С",
            "З",
            "Н",
            "Р",
            "В",
            "К"
        );
        $b    = array(
            "0",
            "0",
            "M",
            "C",
            "3",
            "H",
            "P",
            "B",
            "K",
            "",
            "0",
            "0",
            "M",
            "C",
            "3",
            "H",
            "P",
            "B",
            "K"
        );
        $team = str_replace($a, $b, $teamBefore);
    }
    
    
    if ($quantity && $team) {
        $query     = "SELECT team FROM team_quantity WHERE team='$team' AND date LIKE '$today'";
        $result    = mysqli_query($conn, $query);
        $row       = mysqli_fetch_assoc($result);
        $dublicate = $row['team'];
        $Dublicate = $dublicate;
    }
}

if ($team != $Dublicate) {
    if ($quantity > 0) {
        if ($segment_admin > '') {
            $insertData = "INSERT INTO team_quantity (id, segment, team, quantity, date, user) VALUES (NULL, '$segment_admin', '$team', '$quantity', CURRENT_TIMESTAMP, '$loggedInUser')";
            $result     = mysqli_query($conn, $insertData);
            
            $sql       = "UPDATE idea_direct SET quantity='$quantity' WHERE team='$team' AND date LIKE '$today' limit 1";
            $sqlResult = mysqli_query($conn, $sql);
        }
    }
    
    if ($result && $sqlResult) {
        echo $alertMessage[3];
        
    }
}

if ($team = $Dublicate) {
    echo $alertMessage[4];
}


include('includes/header.php');

?>

<h1>Команди та кількість працівників<a href="add.php" type="button" class="btn btn-lg btn-success pull-right">Внести ідею</a></h1>
<br><br>

<?php

$querySegmentFromSegments  = "SELECT segment FROM segments";
$resultSegmentFromSegments = mysqli_query($conn, $querySegmentFromSegments);
?>


<form form class="form-inline text-center" action="<?php
echo htmlspecialchars($_SERVER['PHP_SELF']);
?>" method="post" class="row">
    <div class="form-group">
        <label for="sel1">Оберіть сегмент</label>
      <select class="form-control" id="lastTeam" name="segment">
        <?php
while ($row = mysqli_fetch_array($resultSegmentFromSegments)) {
    if ($row['segment'] == $_POST['segment']) {
        echo "<option selected>" . $row['segment'] . "</option>";
    } else {
        echo "<option>" . $row['segment'] . "</option>";
    }
}
?>
        </select>
        </div>
    <div class="form-group">
        <input type="text" class="form-control text-center" pattern="^[A-Z0-9]+$" title="Тільки великі латинські літери та цифри без пробілів" id="login" placeholder="Команда" name="team" value="">
    </div>
    <div class="form-group">
        <input type="text" class="form-control text-center" pattern="^[0-9]+$" title="Тільки цифри" id="password" placeholder="Кількість працівників" name="quantity">
    </div>
    <button type="submit" class="btn btn-warning" name="add">Внести дані</button>
</form><br><br>

<div id="response"></div>


<?php
echo '<script src="../libs/jquery/jquery-2.1.4.min.js"></script>
        <script src="../libs/bootstrap/bootstrap.min_v3.3.7.js"></script>
        <script src="../libs/jquery-ui/jquery-ui.min_v1.12.1.js"></script>
        <script src="js/script.js"></script>';
?>
