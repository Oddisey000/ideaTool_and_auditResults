<?php

    session_start();

    if(!$_SESSION['loggedInUser']) {
    
    header("Location: index.php");
}

    include('includes/connection.php');
    include('includes/header.php');

    $loginGet=$_SESSION['loggedInUser'];

    $queryS="SELECT status, segment FROM users WHERE login='$loginGet'";
    $resultS=mysqli_query($conn,$queryS);
    $rowS=mysqli_fetch_assoc($resultS);

    $status     = $rowS['status'];
    $Segment    = $rowS['segment'];
    $segment=$Segment;

    $today = date("Y-m");

    if ($status>0) {

    if (isset ($_POST['makeRequest'])) {

    $date_start     = ( $_POST["date_start"] );
    $date_end       = ( $_POST["date_end"]." 23:59:59.999" );

    }

    $temp = array();
    $temp[0]=$date_start;
    $temp[1]=$date_end;

    include('includes/header.php');

    echo "<h1>Зведений розрахунок</h1>";

    if ($status>0) {
        echo '<br><br><div class="text-left" id="accordion_special">
      
      <h4></h4>
      
      <div>
      <form action="';

      echo'" method="post" class="row">
    <div class="form-group col-sm-4 col-sm-offset-2">
        <input type="text" placeholder="Початкова дата" class="date form-control input-sm text-center" id="date_start" name="date_start">
    </div>
    <div class="form-group col-sm-4">
        <input type="text" placeholder="Кінцева дата" class="date form-control input-sm text-center" id="date_end" name="date_end">
    </div>
    <div class="col-sm-8 text-center col-sm-offset-2">
            <button type="submit" class="btn btn-lg btn-success " name="makeRequest">Сформувати запит</button>
          </div>
    </div>
</form></div><br>';
      
}

if (isset ($_POST['makeRequest'])) {

echo '<br><br>
<table class="table table-hover">
    <thead>
      <tr class="success">
        <th class="text-center">Орг. одиниця</th>
        <th class="text-center">ідеї</th>
        <th class="text-center">Заощадження</th>
      </tr>
    </thead>
    <tbody>';
} /*else 

{echo '<br><br>
<table class="table table-hover">
    <thead>
      <tr class="success">
        <th>Орг. одиниця</th>
        <th class="text-left">ідеї</th>
        <th class="text-center">Заощадження</th>
      </tr>
    </thead>
    <tbody>';}*/

if (isset ($_POST['makeRequest'])) {


            $namecount = "SELECT initiator, COUNT(initiator) AS ctn, SUM(benefit_calc) + SUM(benefit_noncalc) AS sum FROM import_data WHERE (month BETWEEN '$date_start' AND '$date_end') GROUP BY initiator";
            $resultcount = mysqli_query( $conn, $namecount );

            while ($row = mysqli_fetch_array($resultcount)) {

            echo "<tr class='warning'><td>".$row['initiator']."</td><td class='text-left'>".$row['ctn']."</td><td class='text-center'>".round($row['sum'],0)." &euro;"."</td></tr>";
              
          }   

            $sum_euro = "SELECT SUM(benefit_calc) + SUM(benefit_noncalc) AS sum FROM import_data WHERE (month BETWEEN '$date_start' AND '$date_end')";
            $result = mysqli_query( $conn, $sum_euro );
            $sum = mysqli_fetch_assoc($result);
            $sum_result = $sum['sum'];


            $sum_ideas = "SELECT COUNT(initiator) AS ctn FROM import_data WHERE (month BETWEEN '$date_start' AND '$date_end')";
            $result = mysqli_query( $conn, $sum_ideas );
            $ideas = mysqli_fetch_assoc($result);
            $ideas_result = $ideas['ctn'];

            echo "<tr class='success'><td>"."Total"."</td><td class='text-left'>".$ideas_result."</td><td class='text-center'>".round($sum_result,0)." &euro;"."</td></tr>"; 
    
    echo "</tbody></table><br>";
  
   
   } /*else {

            $namecount = "SELECT initiator, COUNT(initiator) AS ctn, SUM(benefit_calc) + SUM(benefit_noncalc) AS sum FROM import_data WHERE month LIKE '$today-%' GROUP BY initiator";
            $resultcount = mysqli_query( $conn, $namecount );
            

            while ($row = mysqli_fetch_array($resultcount)) {

            echo "<tr class='warning'><td>".$row['initiator']."</td><td class='text-left'>".$row['ctn']."</td><td class='text-center'>".$row['sum']." &euro;"."</td></tr>";
              
          }   

            $sum_euro = "SELECT SUM(benefit_calc) + SUM(benefit_noncalc) AS sum FROM import_data WHERE month LIKE '$today-%'";
            $result = mysqli_query( $conn, $sum_euro );
            $sum = mysqli_fetch_assoc($result);
            $sum_result = $sum['sum'];


            $sum_ideas = "SELECT COUNT(initiator) AS ctn FROM import_data WHERE month LIKE '$today-%'";
            $result = mysqli_query( $conn, $sum_ideas );
            $ideas = mysqli_fetch_assoc($result);
            $ideas_result = $ideas['ctn'];

            echo "<tr class='success'><td>"."Total"."</td><td class='text-left'>".$ideas_result."</td><td class='text-center'>".$sum_result." &euro;"."</td></tr>"; 
        
            echo "</tbody></table><br>";

}*/
}

?>
<br><br>
<?php 
    if (isset ($_POST['makeRequest'])) {
        echo '<a href="includes/export_for_lpi.php?start='.$date_start.'&end='.$date_end.'" type="button" class="btn btn-lg btn-success pull-right">Завантажити Excel</a>';}?>

<?php

include('includes/footer.php');

?>