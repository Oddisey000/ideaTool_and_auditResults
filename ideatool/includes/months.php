<?php
include('connection.php');
session_start();
if (isset($_POST['selected_ideaSegment']) && $_POST['selected_ideaSegment']) {
    $foo    = $_POST['selected_ideaSegment'];
  }
?>