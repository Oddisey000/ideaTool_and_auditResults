<?php
    include("../connection.php");

    $level = $_POST['level'];
    $sql = "UPDATE 5s_level SET 5s_level='$level'";
    $res = mysqli_query($conn, $sql);
    if ($res) {
        echo $level;
    }