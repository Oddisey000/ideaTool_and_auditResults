<?php

  include('connection.php');
  include('../../libs/phpexcel/PHPExcel.php');

?>

<?php 
  
  $date = date("Y-m", strtotime("-1 months"));

  $query = "SELECT idea FROM idea_direct WHERE `date` LIKE '$date-%'";
  $result = mysqli_query( $conn, $query );

  $objPHPExcel = new PHPExcel();

  $objPHPExcel->setActiveSheetIndex(0);
  $active_sheet = $objPHPExcel->getActiveSheet(0);

$active_sheet->setTitle("Ідеї"); 
$active_sheet->getColumnDimension('A')->setWidth(22);
$active_sheet->setCellValue('A1', $date);
$active_sheet->setCellValue('B1','Ідеї');
 
$row_start = 2;
$i = 0;
foreach($result as $item) {
 $row_next = $row_start + $i;
 
 $active_sheet->setCellValue('B'.$row_next,$item['idea']);
 
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
  header("Content-Disposition:attachment;filename='ReportForLPI.xls'");

  $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
  ob_end_clean();
  $objWriter->save('php://output');

exit();

?>