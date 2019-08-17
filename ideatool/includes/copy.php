<?php
include('connection.php');
$loginGet  = $_SESSION['loggedInUser'];
$lastmonth = date("Y-m", strtotime("-1 months"));
$today     = date("Y-m");
$queryS    = "SELECT status, segment FROM users WHERE login='$loginGet'";
$resultS   = mysqli_query($conn, $queryS);
$rowS      = mysqli_fetch_assoc($resultS);

$status = $rowS['status'];
if ($status > 0) {
    $a = "SELECT * FROM team_quantity WHERE date LIKE '$today-%'";
    $b = mysqli_query($conn, $a);
    if (mysqli_num_rows($b) < 1) {
        $a = "SELECT * FROM team_quantity WHERE date LIKE '$lastmonth-%'";
        $b = mysqli_query($conn, $a);
        while ($row = mysqli_fetch_assoc($b)) {
            $lastSegment  = $row['segment'];
            $lastTeam     = $row['team'];
            $lastQuantity = $row['quantity'];
            $lastUser     = $row['user'];}
            $a            = "INSERT INTO team_quantity (segment, team, quantity, user, date) VALUES ('$lastSegment', '$lastTeam', '$lastQuantity', '$lastUser', CURRENT_TIMESTAMP)";
            $b            = mysqli_query($conn, $a);

    }
}
?>