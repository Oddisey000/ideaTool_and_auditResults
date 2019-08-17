<?php

  include('connection.php');
  include('../../libs/phpexcel/PHPExcel.php');

?>

<?php 
  
  $today = date("m-Y", strtotime("-1 months"));

  $date_start = $_GET['start'];
  $date_end   = $_GET['end'];

  $query = "SELECT initiator, COUNT(initiator) AS ctn, SUM(benefit_calc) + SUM(benefit_noncalc) AS sum FROM import_data WHERE (month BETWEEN '$date_start' AND '$date_end') GROUP BY initiator";
  $result = mysqli_query( $conn, $query );

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

$active_sheet->setCellValue('A1','OrgUnit');
$active_sheet->setCellValue('B1','M1A');
$active_sheet->setCellValue('C1','M2A');
 
$row_start = 3;
$i = 0;
foreach($result as $item) {
 $row_next = $row_start + $i;
 
 $active_sheet->setCellValue('A'.$row_next,$item['initiator']);
 $active_sheet->setCellValue('B'.$row_next,$item['ctn']);
 $active_sheet->setCellValue('C'.$row_next, round($item['sum'],2));
 
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