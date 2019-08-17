<?php
    include("../connection.php");
    include("../header.php");
    $count_number = 1;

        $identificator = $_POST['identificator'];
        $level = $_POST['delete_level'];
        $tab_id = $_POST['delete_tab_id'];
        $type  = $_POST['delete_type'];

        $sql = "DELETE FROM questionnaire WHERE id='$identificator'";
        $res = mysqli_query($conn, $sql);

        $query = mysqli_query($conn, "SELECT * FROM questionnaire WHERE type='$type' AND element='$level'");
        
        echo "<table>";
        while ($row = mysqli_fetch_array($query)) {
            echo '<tr>
                    <td style="min-width: 30px;"><ins>' . $count_number++ . '.</ins></td>
                    <td style="width: 100%;">
                    <textarea class="form-control" disabled>' . $row['body'] . '</textarea>
                    </td>
                    <td><a href="#" class="delete_question" identificator="' . $row['id'] . '" type="'. $type . '" level="' . $level . '" tab_id="' . $tab_id . '"><span style="margin:0 5px;" data-feather="trash-2"></span></a></td>
                </tr>';     
        }
            echo    '<tr>
                        <td style="min-width: 30px;"><ins>' . $count_number++ . '.</ins></td>
                        <td style="width: 100%;">
                        <textarea class="form-control" id="questionnaire_data" placeholder="Введіть запитання"></textarea>
                        </td>
                        <td><a href="#" id="save_question" type="'. $type . '" level="' . $level . '" tab_id="' . $tab_id . '"><span style="margin:0 5px;" data-feather="save"></span></a></td>
                    </tr></table>';

    include("../footer.php");