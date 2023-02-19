<?php
date_default_timezone_set('Europe/Kiev');


$server     = "";
$username   = "";
$password   = "";
$db         = "";

$conn=mysqli_connect($server,$username,$password,$db);

if(!$conn){
    die("Помилка з'єднання: ".mysqli_connect_error());
}

?>
