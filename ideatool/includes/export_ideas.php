<?php

	include('connection.php');
	include('../../libs/phpexcel/PHPExcel.php');

?>

<?php 

	$result=$_SESSION['segmentData'];

	$objPHPExcel = new PHPExcel();

	$objPHPExcel->setActiveSheetIndex(0);
	$active_sheet = $objPHPExcel->getActiveSheet(0);

$active_sheet->setTitle("Report"); 
$active_sheet->getColumnDimension('A')->setWidth(22);
$active_sheet->getColumnDimension('B')->setWidth(22);
$active_sheet->getColumnDimension('C')->setWidth(22);
$active_sheet->getColumnDimension('D')->setWidth(22);
$active_sheet->getColumnDimension('E')->setWidth(22);
$active_sheet->getColumnDimension('F')->setWidth(22);
$active_sheet->getColumnDimension('G')->setWidth(22);

$active_sheet->setCellValue('A1','Сегмент');
$active_sheet->setCellValue('B1','Команда');
$active_sheet->setCellValue('C1','Ідеї');
$active_sheet->setCellValue('D1','Дата');
$active_sheet->setCellValue('E1','Логін подавача');
$active_sheet->setCellValue('F1','Кі-сть людей в команді');
$active_sheet->setCellValue('G1','Орг. одиниця');
 
$row_start = 2;
$i = 0;
foreach($result as $item) {
 $row_next = $row_start + $i;
 
 $active_sheet->setCellValue('A'.$row_next,$item['segment']);
 $active_sheet->setCellValue('B'.$row_next,$item['team']);
 $active_sheet->setCellValue('C'.$row_next,$item['idea']);
 $active_sheet->setCellValue('D'.$row_next,$item['date']);
 $active_sheet->setCellValue('E'.$row_next,$item['user']);
 $active_sheet->setCellValue('F'.$row_next,$item['quantity']);
 $active_sheet->setCellValue('G'.$row_next,$item['initiator']);
 
 $i++;
}

$style_slogan = array(
//заполнение цветом
 'fill' => array(
  'type' => PHPExcel_STYLE_FILL::FILL_SOLID,
  'color'=>array(
 'rgb' => 'CFCFCF'
  )
 )

);
$active_sheet->getStyle('A1:G1')->applyFromArray($style_slogan);

	header("Content-Type:application/vnd.ms-excel");
	header("Content-Disposition:attachment;filename='simple.xls'");

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	ob_end_clean();
	$objWriter->save('php://output');

exit();

?>