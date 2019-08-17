<?php
session_start();
////////////////////////////////////////////////////////////////////////January///////////////////////////////////////////////////////////////
include('connection.php');
$loginGet=$_SESSION['loggedInUser'];

$queryS="SELECT status, segment FROM users WHERE login='$loginGet'";
$resultS=mysqli_query($conn,$queryS);
$rowS=mysqli_fetch_assoc($resultS);

$status     = $rowS['status'];
$Segment    = $rowS['segment'];
$segment=$Segment;

$today = date("Y");
$currentmonth = date("Y-m");
$onlymonth = date("m");

if ($status<1) {

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

      if (isset ($_POST['january'])) {  $today_new = date("Y"."-01"); $month = "01";}
      elseif (isset ($_POST['february'])) {  $today_new = date("Y"."-02"); $month = "02";}
      elseif (isset ($_POST['march'])) {  $today_new = date("Y"."-03"); $month = "03";}
      elseif (isset ($_POST['april'])) {  $today_new = date("Y"."-04"); $month = "04";}
      elseif (isset ($_POST['may'])) {  $today_new = date("Y"."-05"); $month = "05";}
      elseif (isset ($_POST['june'])) {  $today_new = date("Y"."-06"); $month = "06";}
      elseif (isset ($_POST['july'])) {  $today_new = date("Y"."-07"); $month = "07";}
      elseif (isset ($_POST['august'])) {  $today_new = date("Y"."-08"); $month = "08";}
      elseif (isset ($_POST['september'])) {  $today_new = date("Y"."-09"); $month = "09";}
      elseif (isset ($_POST['october'])) {  $today_new = date("Y"."-10"); $month = "10";}
      elseif (isset ($_POST['november'])) {  $today_new = date("Y"."-11"); $month = "11";}
      elseif (isset ($_POST['december'])) {  $today_new = date("Y"."-12"); $month = "12";}
      elseif (isset ($_POST['last_year_december'])) {  $today_new = date("Y"."-12", strtotime("-1 years")); $month = "12";}

      else {
     
     $namecount = "SELECT team,idea,quantity,segment,price, COUNT(team) AS ctn, SUM(price) AS prs FROM idea_direct WHERE date LIKE '$currentmonth-%' GROUP BY team ORDER BY segment,team";
     $resultcount = mysqli_query( $conn, $namecount );
     
     $querysum = "SELECT id FROM idea_direct WHERE date LIKE '$currentmonth-%'";
     $resultsum = mysqli_query( $conn, $querysum );

     $query_quantity = "SELECT SUM(quantity) AS total FROM idea_direct WHERE date LIKE '$currentmonth-%'";
     $result_quantity = mysqli_query( $conn, $query_quantity );
     $quantity = mysqli_fetch_assoc($result_quantity);
     $quantitycomplete = $quantity['total'];
     
     $price = "SELECT price FROM service_table WHERE year='$today'";
     $priceQuery = mysqli_query($conn, $price);
     $priceResult = mysqli_fetch_array($priceQuery);
     $pricecomplete = $priceResult['price'];

     $team = "SELECT team,quantity,segment FROM team_quantity WHERE team NOT IN (( SELECT team FROM idea_direct)) AND date LIKE '$currentmonth-%' ORDER BY segment,team";
     $resultteam = mysqli_query($conn, $team);

     $zero_query = "SELECT SUM(quantity) AS total FROM team_quantity WHERE team NOT IN (( SELECT team FROM idea_direct)) AND date LIKE '$currentmonth-%'";
     $zero_result = mysqli_query($conn,$zero_query);
     $zero = mysqli_fetch_assoc($zero_result);
     $zero_complete = $zero['total'];
     $teamSum = $zero_complete+$quantitycomplete;

     $summbySegment="SELECT idea,segment,price, COUNT(idea) AS ctn, SUM(price) AS prs FROM idea_direct WHERE date LIKE '$currentmonth-%' GROUP BY segment";
     $summbySegmentquerry=mysqli_query($conn,$summbySegment);

     $target_query = "SELECT * FROM target WHERE date LIKE '$today-%' AND month LIKE '$onlymonth'";
     $target_result = mysqli_query($conn,$target_query);

    while ($target = mysqli_fetch_assoc($target_result)) {
         $quantity = $target['quantity'];
         $ideas = $target['ideas'];
         $cost = $target['cost'];}

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


     /*while ($row=mysqli_fetch_array($resultteam)) {
         
         echo "<tr class='danger'><td>".$row['segment']."</td><td>".$row['team']."</td><td>"."Відсутні ідеї"."</td><td>"."</td><td>".$row['quantity']."</td><td>"."0"."</td><td>"."0"." &euro;"."</td>";
     
     }*/


    //$evsum=$quantity*$ideas; // target ideas for segment
    //$evcost1=$sum*$pricecomplete; // sum of euro depending of number of ideas
    //$evcost2=$quantity*$cost; // target sum of euro for current month
    //$ideas1=$sum/$teamSum; 
    //$cost1=$sum*$pricecomplete/$teamSum;

     /*if ($evsum>$sum||$evcost2>$evcost1) {
       
        echo "</tr><tr class='success' id='animations'><td id='animations'>Targets</td><td id='animations'></td><td id='animations'>".$quantity * $ideas."</td><td id='animations'>".round($quantity * $cost,2)." &euro;"."</td><td id='animations'>"."</td><td id='animations'>"."</td><td id='animations'>"."</td></tr>";
     }
     
     else {

    echo "</tr><tr class='success'><td>Targets</td><td></td><td>".$quantity * $ideas."</td><td>".round($quantity * $cost,2)." &euro;"."</td><td>"."</td><td>"."</td><td>"."</td></tr>";
}*/

  while ($row=mysqli_fetch_array($summbySegmentquerry)) {


         
         echo "<tr id='lastrow'><td>".$row['segment']."</td><td>"."</td><td>".$row['ctn']."</td><td>".round($row['prs'],2)." &euro;"."</td><td>"."</td><td>"."</td><td>"."</td></tr>";
     
     }


    echo "<br><br>";
    echo "</tbody></table>";

     }
 }



if (isset($_POST['january'])||isset($_POST['february'])||isset($_POST['march'])||isset($_POST['april'])||isset($_POST['may'])||isset($_POST['june'])||isset($_POST['july'])||isset($_POST['august'])||isset($_POST['september'])||isset($_POST['october'])||isset($_POST['november'])||isset($_POST['december'])||isset($_POST['last_year_december'])) {

     $namecount = "SELECT team,idea,quantity,segment,price, COUNT(team) AS ctn, SUM(price) AS prs FROM idea_direct WHERE date LIKE '$today_new-%' GROUP BY team ORDER BY segment,team";
     $resultcount = mysqli_query( $conn, $namecount );
     
     $querysum = "SELECT id FROM idea_direct WHERE date LIKE '$today_new-%'";
     $resultsum = mysqli_query( $conn, $querysum );

     $query_quantity = "SELECT SUM(quantity) AS total FROM idea_direct WHERE date LIKE '$today_new-%'";
     $result_quantity = mysqli_query( $conn, $query_quantity );
     $quantity = mysqli_fetch_assoc($result_quantity);
     $quantitycomplete = $quantity['total'];
     
     $price = "SELECT price FROM service_table WHERE year='$today'";
     $priceQuery = mysqli_query($conn, $price);
     $priceResult = mysqli_fetch_array($priceQuery);
     $pricecomplete = $priceResult['price'];

     $team = "SELECT team,quantity,segment FROM team_quantity WHERE team NOT IN (( SELECT team FROM idea_direct)) AND date LIKE '$today_new-%' ORDER BY segment,team";
     $resultteam = mysqli_query($conn, $team);

     $zero_query = "SELECT SUM(quantity) AS total FROM team_quantity WHERE team NOT IN (( SELECT team FROM idea_direct)) AND date LIKE '$today_new-%'";
     $zero_result = mysqli_query($conn,$zero_query);
     $zero = mysqli_fetch_assoc($zero_result);
     $zero_complete = $zero['total'];
     $teamSum = $zero_complete+$quantitycomplete;

     $target_query = "SELECT * FROM target WHERE date LIKE '$today-%' AND month LIKE '$month'";
     $target_result = mysqli_query($conn,$target_query);

     $summbySegment="SELECT idea,segment,price, COUNT(idea) AS ctn, SUM(price) AS prs FROM idea_direct WHERE date LIKE '$today_new-%' GROUP BY segment";
     $summbySegmentquerry=mysqli_query($conn,$summbySegment);

     while ($target = mysqli_fetch_assoc($target_result)) {
         $quantity = $target['quantity'];
         $ideas = $target['ideas'];
         $cost = $target['cost'];}

     while ($row=mysqli_fetch_assoc($resultsum)) {
       
       $sum++;

     }

     if ($quantitycomplete>0) {
    
     while ($row = mysqli_fetch_array($resultcount)) {

        if ($row['quantity']>0) {

       echo "<tr class='warning'><td>".$row['segment']."</td><td>".$row['team']."</td><td>" .$row['ctn']."</td><td>".round($row['prs'],2)." &euro;"."</td><td>".$row['quantity']."</td><td>".round($row['ctn'] / $row['quantity'],2)."</td><td>".round(($row['price'] * $row['ctn']) / $row['quantity'],2)." &euro;"."</td>";}
       
       elseif ($row['quantity']<1) {

          echo "<tr class='danger'><td id='animations'>".$segment."</td><td id='animations'>".$row['team']."</td><td id='animations'>".$row['ctn']."</td><td id='animations'>"."</td><td id='animations'>"."</td><td id='animations'>"."Відсутня кількість людей"."</td><td id='animations'>";
       }
   }


     /*while ($row=mysqli_fetch_array($resultteam)) {
         
         echo "<tr class='danger'><td>".$row['segment']."</td><td>".$row['team']."</td><td>"."Відсутні ідеї"."</td><td>"."</td><td>".$row['quantity']."</td><td>"."0"."</td><td>"."0"." &euro;"."</td>";
     
     }*/

    //$evsum= $quantity * $ideas; // target ideas for segment
    //$evcost1= $sum * $pricecomplete; // sum of euro depending of number of ideas
    //$evcost2= $quantity * $cost; // target sum of euro for current month
    //$ideas1=$sum/$teamSum; 
    //$cost1=$sum*$pricecomplete/$teamSum;

     /*if ($evsum>$sum||$evcost2>$evcost1) {
       
        echo "</tr><tr class='success' id='animations'><td id='animations'>Targets</td><td id='animations'></td><td id='animations'>".$quantity * $ideas."</td><td id='animations'>".round($quantity * $cost,2)." &euro;"."</td><td id='animations'>"."</td><td id='animations'>"."</td><td id='animations'>"."</td></tr>";
     }
     
     else {

    echo "</tr><tr class='success'><td>Targets</td><td></td><td>".$quantity * $ideas."</td><td>".round($quantity * $cost,2)." &euro;"."</td><td>"."</td><td>"."</td><td>"."</td></tr>";
}*/

while ($row=mysqli_fetch_array($summbySegmentquerry)) {


         
         echo "<tr id='lastrow'><td>".$row['segment']."</td><td>"."</td><td>".$row['ctn']."</td><td>".round($row['prs'],2)." &euro;"."</td><td>"."</td><td>"."</td><td>"."</td></tr>";
     
     }

     

    echo "<br><br>";
    echo "</tbody></table>";

     }
 }
}

?>