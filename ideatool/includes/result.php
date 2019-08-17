<?php

    session_start();

    include('connection.php');

    $loginGet=$_SESSION['loggedInUser'];

    $queryS="SELECT status, segment FROM users WHERE login='$loginGet'";
    $resultS=mysqli_query($conn,$queryS);
    $rowS=mysqli_fetch_assoc($resultS);

    $status     = $rowS['status'];
    $Segment    = $rowS['segment'];
    $segment=$Segment;

    $today = date("Y-m");
    $ThisYear = date ('Y');

    if ($status>0) {

    if (isset ($_POST['makeRequest'])) {

    $date_start     = ( $_POST["date_start"] );
    $date_end       = ( $_POST["date_end"]." 23:59:59.999" );

    }

if (isset ($_POST['makeRequest'])) {

echo '<br><br>
<table class="table table-hover">
    <thead>
      <tr class="info">
        <th>Сегмент</th>
        <th>Команда</th>
        <th>Ідеї</th>
        <th>Заощадження</th>
        <th>Працівників</th>
        <th>Ідеї/працівник</th>
        <th>Заощадження/працівник</th>
      </tr>
    </thead>
    <tbody class="myTable">';
} else 

{echo '<br><br>
<table class="table table-hover">
    <thead>
      <tr class="info">
        <th>Сегмент</th>
        <th>Команда</th>
        <th>Ідеї</th>
        <th>Заощадження</th>
        <th>Працівників</th>
        <th>Ідеї/працівник</th>
        <th>Заощадження/працівник</th>
      </tr>
    </thead>
    <tbody class="myTable">';}

if (isset ($_POST['makeRequest'])) {
     
     $namecount = "SELECT team,idea,quantity,segment,price, COUNT(team)/SUM(quantity) AS res, SUM(price) AS prs, COUNT(team) AS ctn, SUM(quantity) AS qtn FROM idea_direct WHERE (date BETWEEN '$date_start' AND '$date_end') GROUP BY team ORDER BY segment,res DESC";
     $resultcount = mysqli_query( $conn, $namecount );
     
     $querysum = "SELECT id FROM idea_direct WHERE (date BETWEEN '$date_start' AND '$date_end')";
     $resultsum = mysqli_query( $conn, $querysum );

     $query_quantity = "SELECT SUM(quantity) AS total FROM idea_direct WHERE (date BETWEEN '$date_start' AND '$date_end')";
     $result_quantity = mysqli_query( $conn, $query_quantity );
     $quantity = mysqli_fetch_assoc($result_quantity);
     $quantitycomplete = $quantity['total'];


     $query_segment_quantity = "SELECT segment, SUM(quantity) AS total FROM idea_direct WHERE (date BETWEEN '$date_start' AND '$date_end') GROUP BY segment";
     $result_segment_quantity = mysqli_query( $conn, $query_segment_quantity );
     $quantity_segment = mysqli_fetch_assoc($result_segment_quantity);
     $quantitycomplete_segment = $quantity_segment['total'];


     
     $price = "SELECT price FROM service_table WHERE year='$ThisYear'";
     $priceQuery = mysqli_query($conn, $price);
     $priceResult = mysqli_fetch_array($priceQuery);
     $pricecomplete = $priceResult['price'];

     $team = "SELECT team,quantity,segment FROM team_quantity WHERE team NOT IN (( SELECT team FROM idea_direct)) AND (date BETWEEN '$date_start' AND '$date_end') ORDER BY segment";
     $resultteam = mysqli_query($conn, $team);

     $zero_query = "SELECT SUM(quantity) AS total FROM team_quantity WHERE team NOT IN (( SELECT team FROM idea_direct)) AND (date BETWEEN '$date_start' AND '$date_end')";
     $zero_result = mysqli_query($conn,$zero_query);
     $zero = mysqli_fetch_assoc($zero_result);
     $zero_complete = $zero['total'];
     $teamSum = $zero_complete+$quantitycomplete;

     $summbySegment="SELECT idea,segment,price, COUNT(idea) AS ctn, SUM(price) AS prs FROM idea_direct WHERE (date BETWEEN '$date_start' AND '$date_end') GROUP BY segment";
     $summbySegmentquerry=mysqli_query($conn,$summbySegment);
     
     while ($row=mysqli_fetch_assoc($resultsum)) {
       
       $sum++;

     }

     if ($quantitycomplete>0) {
    
     while ($row = mysqli_fetch_array($resultcount)) {

        if ($row['quantity']>0) {

       echo "<tr class='warning'><td>".$row['segment']."</td><td>".$row['team']."</td><td>" .$row['ctn']."</td><td>".round($row['prs'],2)." &euro;"."</td><td>".$row['quantity']."</td><td>".round($row['ctn'] / $row['qtn'],2)."</td><td>".round(($row['price'] * $row['ctn']) / $row['qtn'],2)." &euro;"."</td>";}
       
       elseif ($row['quantity']<1) {

          echo "<tr class='danger'><td id='animations'>".$row['segment']."</td><td id='animations'>".$row['team']."</td><td id='animations'>".$row['ctn']."</td><td id='animations'>"."</td><td id='animations'>"."</td><td id='animations'>"."Відсутня кількість людей"."</td><td id='animations'>";
       }
   }


     /*while ($row=mysqli_fetch_array($resultteam)) {
         
         echo "<tr class='danger'><td>".$row['segment']."</td><td>".$row['team']."</td><td>"."Відсутні ідеї"."</td><td>"."</td><td>".$row['quantity']."</td><td>"."0"."</td><td>"."0"." &euro;"."</td>";
     
     }*/

     
     }

     
     while ($row=mysqli_fetch_array($summbySegmentquerry)) {

        $row_price += $row['prs'];

         
         echo "<tr id='lastrow'><td>".$row['segment']."</td><td>"."</td><td>".$row['ctn']."</td><td>".round($row['prs'],2)." &euro;"."</td><td>"."</td><td>"."</td><td>"."</td></tr>";
     
     }

     if ($sum > 0) {
        echo "</tr><tr class='success'><td>Total</td><td></td><td>".$sum."</td><td>".round($row_price,2)." &euro;"."</td><td></td><td></td><td></td></tr>";
     }

     
    echo "</tbody></table>";

    if ($quantitycomplete>0) {
        echo '<a href="includes/export_ideas_calculation.php?start='.$date_start.'&end='.$date_end.'" type="button" class="btn btn-lg btn-success pull-right">Завантажити Excel</a><br><br><br><br>';}
  
   } else 

   { $namecount = "SELECT team,idea,quantity,segment,price, COUNT(team)/SUM(quantity) AS res, SUM(price) AS prs, COUNT(team) AS ctn FROM idea_direct WHERE date LIKE '$today-%'  GROUP BY team ORDER BY segment,res DESC";
     $resultcount = mysqli_query( $conn, $namecount );
     
     $querysum = "SELECT id FROM idea_direct WHERE date LIKE '$today-%'";
     $resultsum = mysqli_query( $conn, $querysum );

     $query_quantity = "SELECT SUM(quantity) AS total FROM idea_direct WHERE date LIKE '$today-%'";
     $result_quantity = mysqli_query( $conn, $query_quantity );
     $quantity = mysqli_fetch_assoc($result_quantity);
     $quantitycomplete = $quantity['total'];


     $query_segment_quantity = "SELECT segment, SUM(quantity) AS total FROM idea_direct WHERE date LIKE '$today-%' GROUP BY segment";
     $result_segment_quantity = mysqli_query( $conn, $query_segment_quantity );
     $quantity_segment = mysqli_fetch_assoc($result_segment_quantity);
     $quantitycomplete_segment = $quantity_segment['total'];


     
     $price = "SELECT price FROM service_table WHERE year='$ThisYear'";
     $priceQuery = mysqli_query($conn, $price);
     $priceResult = mysqli_fetch_array($priceQuery);
     $pricecomplete = $priceResult['price'];

     $team = "SELECT team,quantity,segment FROM team_quantity WHERE team NOT IN (( SELECT team FROM idea_direct)) AND date LIKE '$today-%' ORDER BY segment";
     $resultteam = mysqli_query($conn, $team);

     $zero_query = "SELECT SUM(quantity) AS total FROM team_quantity WHERE team NOT IN (( SELECT team FROM idea_direct)) AND date LIKE '$today-%'";
     $zero_result = mysqli_query($conn,$zero_query);
     $zero = mysqli_fetch_assoc($zero_result);
     $zero_complete = $zero['total'];
     $teamSum = $zero_complete+$quantitycomplete;

     $summbySegment="SELECT idea,segment,price, COUNT(idea) AS ctn, SUM(price) AS prs FROM idea_direct WHERE date LIKE '$today-%' GROUP BY segment";
     $summbySegmentquerry=mysqli_query($conn,$summbySegment);
     
     while ($row=mysqli_fetch_assoc($resultsum)) {
       
       $sum++;

     }

     if ($quantitycomplete>0) {
    
     while ($row = mysqli_fetch_array($resultcount)) {

        if ($row['quantity']>0) {

       echo "<tr class='warning'><td>".$row['segment']."</td><td>".$row['team']."</td><td>" .$row['ctn']."</td><td>".round($row['prs'],2)." &euro;"."</td><td>".$row['quantity']."</td><td>".round($row['ctn'] / $row['quantity'],2)."</td><td>".round(($row['price'] * $row['ctn']) / $row['quantity'],2)." &euro;"."</td>";}
       
       elseif ($row['quantity']<1) {

          echo "<tr class='danger'><td id='animations'>".$row['segment']."</td><td id='animations'>".$row['team']."</td><td id='animations'>".$row['ctn']."</td><td id='animations'>"."</td><td id='animations'>"."</td><td id='animations'>"."Відсутня кількість людей"."</td><td id='animations'>";
       }
   }


    /* while ($row=mysqli_fetch_array($resultteam)) {
         
         echo "<tr class='danger'><td>".$row['segment']."</td><td>".$row['team']."</td><td>"."Відсутні ідеї"."</td><td>"."</td><td>".$row['quantity']."</td><td>"."0"."</td><td>"."0"." &euro;"."</td>";
     
     }*/

     
     }


     while ($row=mysqli_fetch_array($summbySegmentquerry)) {
        $row_price += $row['prs'];

         
         echo "<tr id='lastrow'><td>".$row['segment']."</td><td>"."</td><td>".$row['ctn']."</td><td>".round($row['prs'],2)." &euro;"."</td><td>"."</td><td>"."</td><td>"."</td></tr>";
     
     }

     if ($sum > 0) {
        echo "</tr><tr class='success'><td>Total</td><td></td><td>".$sum."</td><td>".round($row_price,2)." &euro;"."</td><td></td><td></td><td></td></tr>";
     }
     
    
    echo "</tbody></table>";

    if ($quantitycomplete>0) {
        echo '<a href="includes/export_ideas_calculation_today.php" type="button" class="btn btn-lg btn-success pull-right">Завантажити Excel</a><br><br><br><br>';}
  }
}

?>