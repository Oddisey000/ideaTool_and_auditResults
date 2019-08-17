<?php
session_start();
// Retrieve connection to tadabase settings
require('includes/connection.php');
// Find wich user is trying to login
//$login = substr($_SERVER['REMOTE_USER'], 6);
$login = "admin";
// Find that user in database
$check_user = mysqli_query($conn, "SELECT login FROM users WHERE login = '$login'");
// Find email addsess of administrators
$find_admins = mysqli_query($conn, "SELECT email FROM users WHERE status = '1'");
// Asign user name to global variable
$_SESSION['loggedInUser'] = $login;
// Redirect user to start page
header("Location: dashboard.php");