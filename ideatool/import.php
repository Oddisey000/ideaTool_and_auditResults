<?php
ini_set('display_errors',1);
error_reporting(E_ALL);


session_start();

if( !$_SESSION['loggedInUser'] ) {
    
    header("Location: index.php");
}

include('includes/connection.php');
include('includes/functions.php');
include('../libs/phpexcel/PHPExcel.php');
include('../libs/phpexcel/PHPExcel/IOFactory.php');



$loggedInUser=$_SESSION['loggedInUser'];

$query="SELECT status FROM users WHERE login='$loggedInUser'";
$result=mysqli_query($conn,$query);
$row=mysqli_fetch_assoc($result);
$today = date("Y-m");

$status=$row['status'];

if(isset($_GET['alert'])) {
	if($_GET['alert']=='error')   
		$alertMessage="<div class='alert alert-danger' id='alert'>Помилка, заборонено імпортувати дані двічі на місяць! <a class='close' data-dismiss='alert'>&times;</a></div>";
	elseif($_GET['alert']=='success')
		$alertMessage="<div class='alert alert-success' id='alert'>Дані успішно внесено в базу! <a class='close' data-dismiss='alert'>&times;</a></div>";
	elseif($_GET['alert']=='updatesuccess')
		$alertMessage="<div class='alert alert-success' id='alert'>Дані успішно оновлено! <a class='close' data-dismiss='alert'>&times;</a></div>";
	elseif($_GET['alert']=='deleted')    
		$alertMessage="<div class='alert alert-danger' id='alert'>Дозволено імпортувати дані тільки 1 раз на місяць. Попередні дані видалено! Імпортуйте будь ласка документ ще раз <a class='close' data-dismiss='alert'>&times;</a></div>";
}
$alertMessage = array();
$alertMessage[0]="";
$alertMessage[1]="<div class='alert alert-success' id='alert'>Дані успішно внесено в базу! <a class='close' data-dismiss='alert'>&times;</a></div>";
$alertMessage[2]="<div class='alert alert-danger' id='alert'>Дозволено імпортувати дані тільки 1 раз на місяць. Попередні дані видалено! Імпортуйте будь ласка документ ще раз <a class='close' data-dismiss='alert'>&times;</a></div>";
$imp_res= 0;

include('includes/header.php');
$var = date("Y-m-d", strtotime("-1 months"));
$lastmonth = date("Y-m", strtotime("-1 months"));

if ($status<1) {
    
    header("Location: index.php");
}

$query = "SELECT month FROM import_data WHERE month LIKE '$lastmonth-%' AND result='Yes' LIMIT 1";
$resultcheck = mysqli_query($conn, $query);

if (isset($_FILES['file'])) {

    $targetPath = 'uploads/'.$_FILES ['file']['name'];
	move_uploaded_file($_FILES['file']['tmp_name'], $targetPath);

	$objPHPExcel = PHPExcel_IOFactory::load($targetPath);
	foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) // all worksheet cicle
	{
		$highestRow = $worksheet->getHighestRow(); // Get the number of worksheet row
		$highestColumn = $worksheet->getHighestColumn(); // Get the number of worksheet column
	 
		for ($row = 3; $row <= $highestRow;  ++ $row) // check all rows
		{
			$initiator = $worksheet->getCellByColumnAndRow(50, $row); //initiator's org unit
			$result = $worksheet->getCellByColumnAndRow(31, $row); //decision yes/no
			$benefit_calc = $worksheet->getCellByColumnAndRow(37, $row); //benefit calculable idea
			$benefit_est = $worksheet->getCellByColumnAndRow(35, $row); //benefit non calculable idea
			
			if($benefit_calc=="")
				$benefit_calc = 0.0;
			if($benefit_est=="")
				$benefit_est = 0.0;
			$check = mb_strlen($result);


			if(mysqli_num_rows($resultcheck) < 1) {
				if($check == 3){
					$sql = "INSERT INTO import_data (initiator, benefit_calc, benefit_noncalc, result, month) VALUES ('$initiator', '$benefit_calc', '$benefit_est', 'Yes', '$var')";
					$action = mysqli_query($conn,$sql);
					$imp_res = 1; 			
				}
			}elseif(mysqli_num_rows($resultcheck) > 0 ) {    						
				$sql = "DELETE FROM import_data WHERE month LIKE '$lastmonth-%' AND result='Yes'";
				$query = mysqli_query($conn, $sql); 
				$imp_res = 2;
			}
		}
	}
//	$sql = "DELETE FROM import_data WHERE result='' OR result='No'";
//	$action = mysqli_query($conn, $sql); 
if($imp_res==0) $imp_res==1;
}

?>


<h1>Імпорт даних</h1>

<?php 
	echo $alertMessage[$imp_res];
?>

<br><br><br><br>
<form action="<?php echo htmlspecialchars( $_SERVER['PHP_SELF'] ); ?>" method="post" class="row" enctype="multipart/form-data">
    <div class="container">
    <label id="largeFile" for="file">
    <input type="file" id="file" <?php echo 'onchange="this.form.submit()"'?> name="file">
</label>
        </div>
</form><br><br><br><br><br><br><br><br><br><br>





<?php

include('includes/footer.php');

?>