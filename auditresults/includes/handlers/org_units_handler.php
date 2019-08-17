<?php
include("../connection.php");

if(isset($_REQUEST['place_name']))
    {
        $child  = ($_REQUEST['place_name']);
        $parent = ($_REQUEST['org_unit_name']);
        $questionnaire = ($_POST['questionnaire_type']);

        $query = mysqli_query($conn, "SELECT child FROM org_units WHERE child = '$child'");

        if(mysqli_num_rows($query) > 0)
        {
            echo "<div class='alert alert-danger alert-box'>Така одиниця вже існує!</div>";
        }
        else
        {
            $query = "INSERT INTO org_units (child, parent, questionnaire) VALUES ('$child', '$parent', '$questionnaire')";
            $request = mysqli_query($conn, $query);
        
            if($request)
            {
                echo "<div class='alert alert-success alert-box'>Успішно!</div>";
            }
        }
    }