<?php
session_start();
// Retrieve connection to tadabase settings
require('includes/connection.php');
// Find wich user is trying to login
//$login = substr($_SERVER['REMOTE_USER'], 6);
$login = "pevi5001";
// Find that user in database
$check_user = mysqli_query($conn, "SELECT login FROM users WHERE login = '$login'");
// Find email addsess of administrators
$find_admins = mysqli_query($conn, "SELECT email FROM users WHERE status = '1'");
// Checks if user names match
if(mysqli_num_rows($check_user) > 0) {
  // Asign user name to global variable
  $_SESSION['loggedInUser'] = $login;
  // Redirect user to start page
  header("Location: ideas.php");
  // Give user a message and info
} else {
  echo "Доступ заборонено, зверніться будь ласка до:<br><br>";
	  echo '<a href="mailto:vitalii.pertsovych@leoni.com?subject=Доступ до IdeaTool&amp;body=Прошу надати мені доступ для внесення SeeAndDo ідей від прямих працівників. %0A%0AЛогін: ' . $login . '">vitalii.pertsovych@leoni.com</a><br><br>';
}