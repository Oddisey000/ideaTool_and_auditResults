<?php


include('includes/connection.php');

if (isset($_POST['selected_segment']) && $_POST['selected_segment']) {
    $today = date("Y-m" . "%");
    
    $var    = $_POST['selected_segment'];
    $query  = "SELECT team FROM team_quantity WHERE date LIKE '$today' AND segment='$var'";
    $result = mysqli_query($conn, $query);
    
    while ($row = mysqli_fetch_array($result)) {
        if ($row['team'] == $_POST['team']) {
            echo "<option selected>" . $_row['team'] . "</option>";
        } else {
            echo "<option>" . $row['team'] . "</option>";
        }
    }
}


?>