<?php

	include('connection.php');
	include('../../libs/phpexcel/PHPExcel.php');

	$today = date("d-m-Y");
	$ThisYear = date ('Y');

  	$date_start = $_GET['start'];
  	$date_end   = $_GET['end'];

?>

<?php 
 
     $sql = "SELECT price FROM service_table WHERE year='$ThisYear'";
     $price = mysqli_query($conn, $sql);
     $priceResult = mysqli_fetch_array($price);
     $pricecomplete = $priceResult['price'];

     $sql  = "SELECT idea,segment, COUNT(idea) AS ctn FROM idea_direct WHERE (date BETWEEN '$date_start' AND '$date_end') GROUP BY segment";
     $result = mysqli_query($conn,$sql);
 ?>

<?php 

	$objPHPExcel = new PHPExcel();

	$objPHPExcel->setActiveSheetIndex(0);
	$active_sheet = $objPHPExcel->getActiveSheet(0);

$active_sheet->setTitle($today); 
$active_sheet->getColumnDimension('A')->setWidth(22);
$active_sheet->mergeCells('A1:A2');
$active_sheet->getColumnDimension('B')->setWidth(22);
$active_sheet->mergeCells('B1:B2');
$active_sheet->getColumnDimension('C')->setWidth(22);
$active_sheet->mergeCells('C1:C2');

$active_sheet->setCellValue('A1','Сегмент');
$active_sheet->setCellValue('B1','Ідеї');
$active_sheet->setCellValue('C1','Заощадження');
 
$row_start = 3;
$i = 0;
foreach($result as $item) {
 $row_next = $row_start + $i;
 
 $active_sheet->setCellValue('A'.$row_next,$item['segment']);
 $active_sheet->setCellValue('B'.$row_next,$item['ctn']);
 $active_sheet->setCellValue('C'.$row_next,$item['ctn'] * $pricecomplete);
 
 $i++;
}

$style_slogan = array(
//заполнение цветом
 'fill' => array(
  'type' => PHPExcel_STYLE_FILL::FILL_SOLID,
  'color'=>array(
 'rgb' => 'CFCFCF'
  ),
),
  'alignment' => array(
  'horizontal' => PHPExcel_STYLE_ALIGNMENT::HORIZONTAL_CENTER,
  'vertical' => PHPExcel_STYLE_ALIGNMENT::VERTICAL_CENTER,
 )

);
$active_sheet->getStyle('A1:C1')->applyFromArray($style_slogan);

	header("Content-Type:application/vnd.ms-excel");
	header("Content-Disposition:attachment;filename='Report_direct.xls'");

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	ob_end_clean();
	$objWriter->save('php://output');

exit();

?>