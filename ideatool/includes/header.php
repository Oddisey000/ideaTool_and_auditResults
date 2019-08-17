<!DOCTYPE html>
<html>
    <head>  
        <meta charset="utf-8">
        <meta charset="cp1251">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Idea Management Tool</title>
        <link rel="stylesheet" href="../libs/bootstrap/bootstrap.min_v3.3.7.css">
        <link rel="stylesheet" href="../libs/jquery-ui/jquery-ui.min_v1.12.1.css">
        <link rel="stylesheet" href="css/styles.css">
        
        <!--<link rel="stylesheet" href="css/datepicker.css">-->

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

    </head>
    <body>            
    <nav class="navbar navbar-default navbar-fixed-top navbar-inverse">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="../index.html">LPSplus<strong>Tools</strong></a>
            </div><!--navbar-header-->
            <div class="collapse navbar-collapse" id="navbar-collapse">
                
                <?php

                if($_SESSION['loggedInUser']) {
                    
                ?>

                <ul class="nav navbar-nav">
                    <li><a href="ideas.php">Ідеї</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="#"><?php echo $_SESSION['loggedInUser'];?></a></li>
                    <?php 

                    if ($status>0) {
                        
                        echo "<li><a href='' data-toggle='modal' data-target='#inputPrice'>"." &euro;"."</a></li>";
                    }

                    ?>
                </ul>
                <?php 
                        if ($status>0) {
                        echo "<ul class='nav navbar-nav'>";
                        echo "<li><a href='report.php' title='Сформувати звіт по всіх ідеях із IDM включо'>Звіт</a></li>";
                        echo "<li><a href='import.php' title='Імпорт даних з Excel'>Імпорт даних</a></li>";
                        //echo "<li><a href='targets.php' title='Внести цілі по сегментах'>Цілі</a></li></ul>";
                    }?>
                <?php } ?>
            </div><!--navbar-collapse-->
        </div><!--container-fluid-->
    </nav>
    <div class="container">