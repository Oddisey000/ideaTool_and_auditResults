<?php
date_default_timezone_set('Europe/Kiev');


$server     = "sql212.epizy.com";
$username   = "epiz_24105416";
$password   = "Qwerty20031988$";
$db         = "epiz_24105416_idm_user";

$conn=mysqli_connect($server,$username,$password,$db);

if(!$conn){
    die("Помилка з'єднання: ".mysqli_connect_error());
}

?>