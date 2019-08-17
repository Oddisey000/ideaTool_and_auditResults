<?php

    session_start();

    include('connection.php');

    if(!$_SESSION['loggedInUser']) {
    
    header("Location: index.php");}

    $id = $_GET['id'];
    $today = date('Y-m');

    $query = "SELECT team AS team FROM team_quantity WHERE id='$id'";
    $result = mysqli_query( $conn, $query );
    $query = mysqli_fetch_assoc($result);
    $team = $query['team'];

    $query = "DELETE FROM idea_direct WHERE team='$team' AND date LIKE '$today-%'";
    $result = mysqli_query($conn, $query);

    $query = "DELETE FROM import_data WHERE result='$team' AND date LIKE '$today-%'";
    $result = mysqli_query($conn, $query);

    $query = "DELETE FROM team_quantity WHERE id='$id'";
    $result = mysqli_query( $conn, $query ); header( "Location: ../teams.php?alert=delete" );
?>