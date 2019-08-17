<?php
session_start();

require('connection.php');
if($_SESSION['loggedInUser'])
{
  $userLogin = $_SESSION['loggedInUser'];
  
  $query 	   = mysqli_query($conn, "SELECT `segment` FROM users WHERE `login` = '$userLogin'");
  $parent 	   = mysqli_fetch_assoc($query);
  $parent 	   = $parent['segment'];

  $query        = "SELECT status AS status FROM users WHERE login = '$userLogin'";
  $request      = mysqli_query($conn, $query);
  $row          = mysqli_fetch_assoc($request);
  $permissions  = $row['status'];
}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>5S Results Application</title>
    <link href="../libs/bootstrap/bootstrap.min.css" rel="stylesheet">
    
    <!--<link href="assets/css/jquery-ui_theme_1.12.1.css" rel="stylesheet">-->
    <link href="assets/css/styles.css" rel="stylesheet">
    <!--<link href="assets/css/jquery-ui.css" rel="stylesheet">-->
    <script src="../libs/jsplugin/moment.min.js"></script>
  </head>
  <body>
    <nav class="navbar navbar-dark fixed-top bg-dark flex-md-nowrap p-0 shadow">
      <a class="navbar-brand col-sm-3 col-md-2 mr-0 title" href="../index.html">LPSplus Tools <span data-feather="home"></span> </a>
      <div class="col-sm-9">
          <a class="color" href="#">
          <?php
            if($_SESSION['loggedInUser'])
            {
              echo $_SESSION['loggedInUser'];
              echo '<span data-feather="at-sign"></span>';
            }
          ?>  
          </a>
        </div>
      <ul class="navbar-nav px-3"></ul>
    </nav>
    <div class="container-fluid">
      <div class="row">
        <nav class="col-md-2 d-none d-md-block bg-light sidebar">
          <div class="sidebar-sticky">
            <ul class="nav flex-column">
              <?php
              if($_SESSION['loggedInUser'])
              {
                if($permissions > 0)
                {
                echo
                  '<li class="nav-item">
                    <a class="nav-link" href="users.php">
                      <span data-feather="user-check"></span>
                      Реєстр користувачів
                    </a>
                  </li>';
                echo
                  '<li class="nav-item">
                    <a class="nav-link" href="edit_questionnaire.php">
                      <span data-feather="edit"></span>
                      Редагування опитувальників
                    </a>
                  </li>';
                echo
                  '<li class="nav-item">
                    <a class="nav-link" href="export.php">
                      <span data-feather="archive"></span>
                      Дані для LPI
                    </a>
                  </li>';
                echo
                  '<li class="nav-item">
                    <a class="nav-link" href="orgUnits.php">
                      <span data-feather="book-open"></span>
                      Структура відділів
                    </a>
                  </li>';
				  echo
                  '<li class="nav-item">
                    <a class="nav-link" href="deviations.php">
                      <span data-feather="alert-circle"></span>
                      Відхилення
                    </a>
                  </li>';
                }
              }
              ?>
              <?php
                if($_SESSION['loggedInUser'])
                {
                  echo
                    '<li class="nav-item">
                      <a class="nav-link" href="dashboard.php">
                        <span data-feather="bar-chart-2"></span>
                        Статистика аудитів
                      </a>
                    </li>';
                  echo
                    '<li class="nav-item">
                      <a class="nav-link" href="error.php">
                        <span data-feather="feather"></span>
                        Внести результати аудиту
                      </a>
                    </li>';
                  /*echo
                    '<li class="nav-item">
                      <a class="nav-link" href="#">
                        <span data-feather="download"></span>
                        Документація
                      </a>
                    </li>';
                  echo
                    '<li class="nav-item">
                      <a class="nav-link" href="#">
                        <span data-feather="refresh-ccw"></span>
                        Обхід територій
                      </a>
                    </li>';*/
                }
              ?>
            </ul>
          </div>
        </nav>
      </div>