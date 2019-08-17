<?php

session_start();

if (!$_SESSION['loggedInUser']) {

    header("Location: index.php");
}

$alertMessage[3] = "<div class='alert alert-success' id='alert'>Дані успішно внесено в базу! <a class='close' data-dismiss='alert'>&times;</a></div>";
$alertMessage[1] = "<div class='alert alert-danger' id='alert'> Поле для внесення ідеї не може бути порожнє! <a class='close' data-dismiss='alert'>&times;</a></div>";
$alertMessage[2] = "<div class='alert alert-danger' id='alert'> Оберіть будь ласка сегмент! <a class='close' data-dismiss='alert'>&times;</a></div>";
$alertMessage[4] = "<div class='alert alert-danger' id='alert'> Поле для вибору команди не може бути порожнє! <a class='close' data-dismiss='alert'>&times;</a></div>";

$loggedInUser = $_SESSION['loggedInUser'];

include('includes/connection.php');
include('includes/functions.php');

$querySegment = "SELECT segment FROM users WHERE login='$loggedInUser'";
$resultSegment = mysqli_query($conn, $querySegment);
$rowSegment = mysqli_fetch_assoc($resultSegment);

$login = $rowSegment['login'];
$segment = $rowSegment['segment'];
$Segment = $segment;
$today = date("Y-m" . "%");
$current_month = date("Y-m");
$ThisYear = date('Y');


if (isset($_POST['add'])) {

    $teamTemp = ($_POST["team"]);

    if (!$_POST["idea"]) {

        echo $alertMessage[1];
    } elseif (!$_POST["segment"]) {
        echo $alertMessage[2];
    } 
    elseif (!$_POST["team"]) {
        echo $alertMessage[4];
    }
    else {

        $idea = ($_POST["idea"]);

    $idea = mysql_escape_string($_POST["idea"]);
    $team = ($_POST["team"]);
    $SegmentNew = ($_POST["segment"]);
}

    if ($idea && $SegmentNew) {

        $price = "SELECT price FROM service_table WHERE year='$ThisYear'";
        $priceQuery = mysqli_query($conn, $price);
        $priceResult = mysqli_fetch_array($priceQuery);
        $pricecomplete = $priceResult['price'];

        $query = "SELECT orgUnit FROM segments WHERE segment='$SegmentNew'";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($result);
        $orgUnit = $row['orgUnit'];

        $getquantity = "SELECT team, quantity FROM team_quantity WHERE team='$team' AND date LIKE '$today'";
        $result = mysqli_query($conn, $getquantity);
        $row = mysqli_fetch_assoc($result);
        $quantity = $row['quantity'];


        $query = "INSERT INTO idea_direct (segment, date, idea, team, user, initiator, price) VALUES ('$SegmentNew', CURRENT_TIMESTAMP, '$idea', '$team','$loggedInUser', '$orgUnit', '$pricecomplete')";
        $sql = "UPDATE idea_direct SET quantity='$quantity' WHERE team='$team' AND `date` LIKE '$today' LIMIT 1";
        $result = mysqli_query($conn, $query);
        $sqlResult = mysqli_query($conn, $sql);

        $query_for_import = "INSERT INTO import_data (id, initiator, benefit_calc, benefit_noncalc, result, month) VALUES (NULL, '$orgUnit', '0','$pricecomplete','$team', CURRENT_TIMESTAMP)";
        $result = mysqli_query($conn, $query_for_import);

        if ($result) {

            echo $alertMessage[3];
        }
    }

    //mysqli_close($conn);  

}

include('includes/header.php');

?>

<?php   $querySegmentFromSegments="SELECT segment FROM segments";
        $resultSegmentFromSegments=mysqli_query($conn,$querySegmentFromSegments);?>

<h1>Внести дані</h1>

<br>

<form action="<?php echo htmlspecialchars( $_SERVER['PHP_SELF'] ); ?>" method="post" class="row">
    <div class-"form-group col-sm-8">
        <a href="teams.php" type="button" class="btn btn-lg btn-warning pull-right">Внести командy та кількість працівників</a>
    </div>
    <div class="form-group col-sm-4">
        <label for="sel1">Оберіть сегмент</label>
      <select class="form-control" id="sel1" name="segment">
        <?php while ($row=mysqli_fetch_array($resultSegmentFromSegments)) {
            if ($row['segment']==$_POST['segment']) {
            echo "<option selected>".$row['segment']."</option>";}
            else {echo "<option>".$row['segment']."</option>";}}?>
        </select>
        </div>
    <div class="form-group col-sm-4">
        <label for="sel1">Оберіть команду</label>
      <select class="form-control" id="sel2" name="team">
      </select>
    </div>
    <div class="form-group col-sm-10">
        <textarea type="text" class="form-control input-lg" rows="5" id="idea" name="idea" placeholder="Ідея"></textarea>
    </div>
    <div class="col-sm-12">
            <button type="submit" class="btn btn-lg btn-success pull-right" name="add">Внести ідею</button>
    </div>
</form>
<br><br>

<?php

if ($status<1) {

$query = "SELECT * FROM idea_direct WHERE date LIKE '$today' ORDER BY date DESC";
$result = mysqli_query($conn,$query);}

echo '<table class="table table-striped table-bordered">
            <tr class="info">
                <th class="text-center">Сегмент</th>
                <th class="text-center">Команда</th>
                <th class="text-center">Ідея</th>
                <th class="text-center">Дата</th>
                <th class="text-center">Користувач</th>';

    while ($row=mysqli_fetch_assoc($result)) {
          
            echo "<tr><td>".$row['segment']."</td><td>".$row['team']."</td><td>".$row['idea']."</td><td>".$row['date']."</td><td>".$row['user']."</td>";}

?>

<?php

echo '<script src="../libs/jquery/jquery-2.1.4.min.js"></script>
        <script src="../libs/bootstrap/bootstrap.min_v3.3.7.js"></script>
        <script src="../libs/jquery-ui/jquery-ui.min_v1.12.1.js"></script>
        <script src="js/script.js"></script>';

?>