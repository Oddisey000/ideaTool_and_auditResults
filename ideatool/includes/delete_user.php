<?php

    session_start();

    include('connection.php');

    $loginGet=$_SESSION['loggedInUser'];

    if(!$_SESSION['loggedInUser']) {
    
    header("Location: index.php");}

    $queryS="SELECT status, segment FROM users WHERE login='$loginGet'";
    $resultS=mysqli_query($conn,$queryS);
    $rowS=mysqli_fetch_assoc($resultS);

    $status     = $rowS['status'];
    $Segment    = $rowS['segment'];
    $segment=$Segment;

    $today = date("Y-m");

    if ($status<1) {

    header("Location: index.php");}

    $login = $_GET['login'];

    $query = "DELETE FROM users WHERE login='$login'";
    $result = mysqli_query( $conn, $query ); header( "Location: ../new.php?alert=delete" );