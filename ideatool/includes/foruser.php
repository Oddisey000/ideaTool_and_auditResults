<?php
  
  include('includes/connection.php');

  $loginGet=$_SESSION['loggedInUser'];

  $queryS="SELECT status, segment FROM users WHERE login='$loginGet'";
  $resultS=mysqli_query($conn,$queryS);
  $rowS=mysqli_fetch_assoc($resultS);

  $status     = $rowS['status'];
  $segment    = $rowS['segment'];
  $currentmonth = date("Y-m");
  
  if (isset ($_POST['january'])) {  $today = date("Y"."-01");}
  elseif (isset ($_POST['february'])) {  $today = date("Y"."-02");}
  elseif (isset ($_POST['march'])) {  $today = date("Y"."-03");}
  elseif (isset ($_POST['april'])) {  $today = date("Y"."-04");}
  elseif (isset ($_POST['may'])) {  $today = date("Y"."-05");}
  elseif (isset ($_POST['june'])) {  $today = date("Y"."-06");}
  elseif (isset ($_POST['july'])) {  $today = date("Y"."-07");}
  elseif (isset ($_POST['august'])) {  $today = date("Y"."-08");}
  elseif (isset ($_POST['september'])) {  $today = date("Y"."-09");}
  elseif (isset ($_POST['october'])) {  $today = date("Y"."-10");}
  elseif (isset ($_POST['november'])) {  $today = date("Y"."-11");}
  elseif (isset ($_POST['december'])) {  $today = date("Y"."-12");}
  elseif (isset ($_POST['last_year_december'])) {  $today = date("Y"."-12", strtotime("-1 years")); $month = "12";}
  else {$query = "SELECT * FROM idea_direct WHERE date LIKE '$currentmonth-%'";
  $result = mysqli_query( $conn, $query );}
  
if (isset($_POST['january'])||isset($_POST['february'])||isset($_POST['march'])||isset($_POST['april'])||isset($_POST['may'])||isset($_POST['june'])||isset($_POST['july'])||isset($_POST['august'])||isset($_POST['september'])||isset($_POST['october'])||isset($_POST['november'])||isset($_POST['december'])||isset($_POST['last_year_december'])) {
  $query = "SELECT * FROM idea_direct WHERE date LIKE '$today%' ORDER BY date DESC";
  $result = mysqli_query( $conn, $query );}

  ?>