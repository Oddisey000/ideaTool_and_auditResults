<?php 
    include("../connection.php");

    if (isset($_REQUEST['id'])) {
        $comment      = $_REQUEST['comment'];
        $id           = $_REQUEST['id'];
        $note_value   = $_REQUEST['checkbox'];
        $dateToUpdate = $_REQUEST['dateToUpdate'];
        $element      = $_REQUEST['element'];
        $data         = $_REQUEST['data'];

        if ($note_value != 0) {
            $note = 'yes';
        } else {
            $note = 'no';
        }
        
       
        $query = mysqli_query($conn, "UPDATE result SET `note` = '$note', `comment` = '$comment', `note_value` = '$note_value' WHERE `id` = '$id'");

        $query = mysqli_query($conn, "UPDATE dashboard_data SET `note` = '$data' WHERE `date` = '$dateToUpdate' AND `child` = '$element'");
    }
	
	if (isset($_REQUEST['people'])) {
		$people 	  = $_REQUEST['people'];
		$people 	  = mysql_escape_string($people);
		$dateToUpdate = $_REQUEST['dateToUpdate'];
		$element      = $_REQUEST['element'];
		
		$query = mysqli_query($conn, "UPDATE dashboard_data SET `people` = '$people' WHERE `date` = '$dateToUpdate' AND `child` = '$element'");
	}
	
	if (isset($_REQUEST['actionPlan'])) {
		$actionPlan 	  = $_REQUEST['actionPlan'];
		$actionPlan 	  = mysql_escape_string($actionPlan);
		$id_toUpdate 	  = $_REQUEST['id_toUpdate'];
		
		$query = mysqli_query($conn, "UPDATE result SET `action` = '$actionPlan' WHERE `id` = '$id_toUpdate'");
	}
