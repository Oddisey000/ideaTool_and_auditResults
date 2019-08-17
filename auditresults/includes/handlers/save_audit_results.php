<?php
include("../connection.php");
session_start();
$login = $_SESSION['loggedInUser'];
$lastmonth = date("Y-m", strtotime("first day of previous month"));
$sql = "SELECT * FROM 5s_level";
$res = mysqli_query($conn, $sql);
$arr = mysqli_fetch_array($res);
$substracted = $arr['5s_level'];

if (isset($_REQUEST['note'])) {
    $note    = $_REQUEST['note'];
    $notes_value = $_REQUEST['notes_value'];
    $comment = $_REQUEST['comment'];
    $result  = $_REQUEST['result'];
    $child   = $_REQUEST['element'];
    $body    = $_REQUEST['body'];
    $level   = $_REQUEST['level'];

    foreach ($notes_value as $i) {
        $summary += $i;
    }
    $summary = round($summary,2);

    $array = new MultipleIterator();
    $array->attachIterator(new ArrayIterator($note));
    $array->attachIterator(new ArrayIterator($body));
    $array->attachIterator(new ArrayIterator($level));
    $array->attachIterator(new ArrayIterator($notes_value));
    $array->attachIterator(new ArrayIterator($comment));

    $sql = "SELECT parent FROM org_units WHERE child = '$child'";
    $req = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($req);
    $parent = $row['parent'];
    
    if ($parent == '') {
        $parent = $child;
    }

    $query = mysqli_query($conn, "SELECT DISTINCT questionnaire FROM org_units WHERE child = '$child'");
    $row = mysqli_fetch_array($query);
    $type = $row['questionnaire'];

    foreach ($array as $i) {
        list($note, $body, $level, $notes_value, $comment) = $i;
        $body = mysql_escape_string($body);
        $sql = "INSERT INTO result (note, note_value, comment, `current_date`, element, parent, added_by, question, type, S) VALUES ('$note', '$notes_value', '$comment', '$lastmonth', '$child', '$parent', '$login', '$body', '$type', '$level')";
        $req = mysqli_query($conn, $sql);
    }

    $query = mysqli_query($conn, "INSERT INTO dashboard_data (parent, child, note, `date`, s_level) VALUES ('$parent', '$child', '$summary', '$lastmonth', '$level')");
    
    echo "<div class='alert alert-success alert-box'>Успішно!</div>";
} else {
    //echo "<div class='alert alert-success alert-box'>Повернення на попередню сторінку!</div>";
}

if (isset($_REQUEST['note_vS'])) {
    $notes_value = $_REQUEST['note_vS'];
    $comment 	 = $_REQUEST['comment_vS'];
    $result  	 = $_REQUEST['result_vS'];
    $child   	 = $_REQUEST['element_vS'];
    $body    	 = $_REQUEST['body_vS'];
    $level   	 = $_REQUEST['level_vS'];
	$people 	 = $_REQUEST['people'];
	
	$people = mysql_escape_string($people);
    if ($body != "") {
        $array = new MultipleIterator();
        $array->attachIterator(new ArrayIterator($body));
        $array->attachIterator(new ArrayIterator($level));
        $array->attachIterator(new ArrayIterator($notes_value));
        $array->attachIterator(new ArrayIterator($comment));
    }

    $sql = "SELECT parent FROM org_units WHERE child = '$child'";
    $req = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($req);
    $parent = $row['parent'];
    
    if ($parent == '') {
        $parent = $child;
    }

    $query = mysqli_query($conn, "SELECT DISTINCT questionnaire FROM org_units WHERE child = '$child'");
    $row = mysqli_fetch_array($query);
    $type = $row['questionnaire'];
	
	$check_before_insert = mysqli_query($conn, "SELECT * FROM result WHERE `current_date` = '$lastmonth' AND `element` = '$child'");
	if (mysqli_num_rows($check_before_insert) < 1) {
		foreach ($array as $i) {
			list($body, $level, $notes_value, $comment) = $i;
			$body = mysql_escape_string($body);
			$sql = "INSERT INTO result (note, note_value, comment, `current_date`, element, parent, added_by, question, type, S, action) VALUES ('yes', '$notes_value', '$comment', '$lastmonth', '$child', '$parent', '$login', '$body', '$type', '$level', '')";
			$req = mysqli_query($conn, $sql);
		}
		$query = mysqli_query($conn, "UPDATE result SET `note` = 'no' WHERE `note_value` = 0 AND `current_date` = '$lastmonth'");
		$query = mysqli_query($conn, "INSERT INTO dashboard_data (parent, child, note, `date`, s_level, people) VALUES ('$parent', '$child', '$result', '$lastmonth', '$substracted', '$people')");
	}
    
    echo "<div class='alert alert-success alert-box'>Успішно!</div>";
} else {
    echo "<div class='alert alert-success alert-box'>Повернення на попередню сторінку!</div>";
}
