<?php
date_default_timezone_set('Europe/Kiev');
header("Content-Type: text/html; charset=utf-8");

$server     = "localhost";
$username   = "root";
$password   = "root";
$db         = "5s_results";

$conn=mysqli_connect($server,$username,$password,$db);
mysqli_query($conn,"SET NAMES 'utf8'");
mysqli_query($conn,"SET collation_connection = 'utf8_general_ci'");

if(!$conn){
    die("Помилка з'єднання: ".mysqli_connect_error());
}


?>