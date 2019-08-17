<?php
    include("../connection.php");

    if(isset($_REQUEST['user']))
    {
        $userName   = $_REQUEST['user'];
        $userDep    = $_REQUEST['user_org_unit'];

        if($_REQUEST['user_right'] == 'true')
        {
            $rightCheck = 1;
        }
        else
        {
            $rightCheck = 0;
        }

        $query = mysqli_query($conn, "SELECT login FROM users WHERE login = '$userName'");

        if(mysqli_num_rows($query) > 0)
        {
            echo "<div class='alert alert-danger alert-box'>Такий користувач існує!</div>";
        }
        else
        {
            $password = password_hash("lpsplus", PASSWORD_DEFAULT);
            $query      = "INSERT INTO users (segment, login, status) VALUES ('$userDep', '$userName', '$rightCheck')";
            $request    = mysqli_query($conn, $query);

            if($request)
            {
                echo "<div class='alert alert-success alert-box'>Успішно!</div>";
            }
        }
	}