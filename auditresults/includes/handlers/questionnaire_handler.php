<?php
    include("../connection.php");
    $count_number = 1;

    if (isset($_REQUEST['input_type'])) {
        $type = $_REQUEST['input_type'];
        $level = $_REQUEST['input_level'];
        $data =  mysql_escape_string($_REQUEST['input_data']);
        $tab_id = $_REQUEST['input_tab_id'];
		if ($data !== '') {
			$sql = "INSERT INTO questionnaire (body, type, element) VALUES ('$data', '$type', '$level')";
			$res = mysqli_query($conn, $sql);
		}
        $query = mysqli_query($conn, "SELECT * FROM questionnaire WHERE type='$type' AND element='$level'");
    
        echo "<table>";
        while ($row = mysqli_fetch_array($query)) {
            echo '<tr>
                    <td style="min-width: 30px;"><ins>' . $count_number++ . '.</ins></td>
                    <td style="width: 100%;">
                    <textarea class="form-control update_question" id="' . $row['id'] . '">' . $row['body'] . '</textarea>
                    </td>
                    <td><a href="#" class="delete_question" identificator="' . $row['id'] . '" type="'. $type . '" level="' . $level . '" tab_id="' . $tab_id . '"><span style="margin:0 5px;" data-feather="trash-2"></span></a></td>
                </tr>';     
        }
        echo    '</table><br><table><tr>
                    <td style="min-width: 30px;"><ins>' . $count_number++ . '.</ins></td>
                    <td style="width: 100%;">
                    <textarea class="form-control" id="questionnaire_data" placeholder="Введіть запитання"></textarea>
                    </td>
                    <td><a href="#" id="save_question" type="'. $type . '" level="' . $level . '" tab_id="' . $tab_id . '"><span style="margin:0 5px;" data-feather="save"></span></a></td>
                </tr></table>';
    }
    
    if (isset($_REQUEST['type'])) {
        $type  = $_REQUEST['type'];
        $level = $_REQUEST['level'];
        $tab_id = $_REQUEST['tab_id'];
        $query = mysqli_query($conn, "SELECT * FROM questionnaire WHERE type='$type' AND element='$level'");
        
        echo "<table>";
        while ($row = mysqli_fetch_array($query)) {
            echo '<tr>
                    <td style="min-width: 30px;"><ins>' . $count_number++ . '.</ins></td>
                    <td style="width: 100%;">
                    <textarea class="form-control update_question" id="' . $row['id'] . '">' . $row['body'] . '</textarea>
                    </td>
                    <td><a href="#" class="delete_question" identificator="' . $row['id'] . '" type="'. $type . '" level="' . $level . '" tab_id="' . $tab_id . '"><span style="margin:0 5px;" data-feather="trash-2"></span></a></td>
                </tr>';     
        }
        echo    '</table><br><table><tr>
                    <td style="min-width: 30px;"><ins>' . $count_number++ . '.</ins></td>
                    <td style="width: 100%;">
                    <textarea class="form-control" id="questionnaire_data" placeholder="Введіть запитання"></textarea>
                    </td>
                    <td><a href="#" id="save_question" type="'. $type . '" level="' . $level . '" tab_id="' . $tab_id . '"><span style="margin:0 5px;" data-feather="save"></span></a></td>
                </tr></table>';
    }

    if (isset($_REQUEST['identificator'])) {
        $identificator = $_REQUEST['identificator'];
        $level = $_REQUEST['delete_level'];
        $tab_id = $_REQUEST['delete_tab_id'];
        $type  = $_REQUEST['delete_type'];

        $sql = "DELETE FROM questionnaire WHERE id='$identificator'";
        $res = mysqli_query($conn, $sql);

        $query = mysqli_query($conn, "SELECT * FROM questionnaire WHERE type='$type' AND element='$level'");
        
        echo "<table>";
        while ($row = mysqli_fetch_array($query)) {
            echo '<tr>
                    <td style="min-width: 30px;"><ins>' . $count_number++ . '.</ins></td>
                    <td style="width: 100%;">
                    <textarea class="form-control update_question" id="' . $row['id'] . '">' . $row['body'] . '</textarea>
                    </td>
                    <td><a href="#" class="delete_question" identificator="' . $row['id'] . '" type="'. $type . '" level="' . $level . '" tab_id="' . $tab_id . '"><span style="margin:0 5px;" data-feather="trash-2"></span></a></td>
                </tr>';     
        }
            echo    '</table><br><table><tr>
                        <td style="min-width: 30px;"><ins>' . $count_number++ . '.</ins></td>
                        <td style="width: 100%;">
                        <textarea class="form-control" id="questionnaire_data" placeholder="Введіть запитання"></textarea>
                        </td>
                        <td><a href="#" id="save_question" type="'. $type . '" level="' . $level . '" tab_id="' . $tab_id . '"><span style="margin:0 5px;" data-feather="save"></span></a></td>
                    </tr></table>';
    }
    if (isset($_REQUEST['update_id'])) {
        $identificator = $_REQUEST['update_id'];
        $data 		   = $_REQUEST['input_data'];
		$data 	   	   = mysql_escape_string($data);

        $sql = "UPDATE questionnaire SET body = '$data' WHERE id='$identificator'";
        $res = mysqli_query($conn, $sql);
        
        /*echo "<table>";
        while ($row = mysqli_fetch_array($query)) {
            echo '<tr>
                    <td style="min-width: 30px;"><ins>' . $count_number++ . '.</ins></td>
                    <td style="width: 100%;">
                    <textarea class="form-control update_question" id="' . $row['id'] . '">' . $row['body'] . '</textarea>
                    </td>
                    <td><a href="#" class="delete_question" identificator="' . $row['id'] . '" type="'. $type . '" level="' . $level . '" tab_id="' . $tab_id . '"><span style="margin:0 5px;" data-feather="trash-2"></span></a></td>
                </tr>';     
        }
            echo    '</table><br><table><tr>
                        <td style="min-width: 30px;"><ins>' . $count_number++ . '.</ins></td>
                        <td style="width: 100%;">
                        <textarea class="form-control" id="questionnaire_data" placeholder="Введіть запитання"></textarea>
                        </td>
                        <td><a href="#" id="save_question" type="'. $type . '" level="' . $level . '" tab_id="' . $tab_id . '"><span style="margin:0 5px;" data-feather="save"></span></a></td>
                    </tr></table>';*/
    }
include("../footer.php");