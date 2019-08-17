<?php
session_start();
include('includes/connection.php');
include('includes/functions.php');

$loggedInUser = $_SESSION['loggedInUser'];

$querySegment  = "SELECT status, segment FROM users WHERE login='$loggedInUser'";
$resultSegment = mysqli_query($conn, $querySegment);
$rowSegment    = mysqli_fetch_assoc($resultSegment);

$login   = $rowSegment['login'];
$segment = $rowSegment['segment'];
$Segment = $segment;
$status  = $rowSegment['status'];
$today   = date("Y-m" . "%");

if (isset($_POST['selected_segment']) && $_POST['selected_segment']) {
    $foo    = $_POST['selected_segment'];
    $query  = "SELECT segment, team, quantity, id, date, user FROM team_quantity WHERE date LIKE '$today' AND segment='$foo' ORDER BY team";
    $result = mysqli_query($conn, $query);
    
    echo '<br><table class="table table-striped table-bordered">
            <tr class="info">
                <th class="text-center">Сегмент</th>
                <th class="text-center">Команда</th>
                <th class="text-center">Кількість працівників</th>
                <th class="text-center">Дата</th>
                <th class="text-center">Додав користувач</th>';
       echo '<th></th>';

    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr><td>" . $row['segment'] . "</td><td>" . $row['team'] . "</td><td>" . $row['quantity'] . "</td><td>" . $row['date'] . "</td><td>" . $row['user'] . "</td>";
        if ($status > 0) {
            echo '<td><a href="editteams.php?id=' . $row['id'] . '" type="button" class="btn btn-default btn-sm">
                    <span class="glyphicon glyphicon-pencil"></span>
                    </a></td>';
        }
        if ($status < 1) {
            echo '<td><a href="editBySegment.php?id=' . $row['id'] . '" type="button" class="btn btn-default btn-sm">
                    <span class="glyphicon glyphicon-pencil"></span>
                    </a></td>';
        }
        
        echo "</tr>";
        
    }
}


?>