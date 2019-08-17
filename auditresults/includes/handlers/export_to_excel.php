<?php

	include('../connection.php');
	include('../../../libs/phpexcel/PHPExcel.php');

	if (isset($_REQUEST['date'])) {
		$date = $_REQUEST['date'];
		
		$query = "SELECT child, note, parent FROM dashboard_data WHERE `date` = '$date' ORDER BY parent ASC";
		$result = mysqli_query($conn, $query);

		$objPHPExcel = new PHPExcel();

		$objPHPExcel->setActiveSheetIndex(0);
		$active_sheet = $objPHPExcel->getActiveSheet(0);

		$active_sheet->setTitle($date); 
		$active_sheet->getColumnDimension('A')->setWidth(22);
		$active_sheet->mergeCells('A1:A2');
		$active_sheet->getColumnDimension('B')->setWidth(22);
		$active_sheet->mergeCells('B1:B2');
		$active_sheet->getColumnDimension('C')->setWidth(22);
		$active_sheet->mergeCells('C1:C2');

		$active_sheet->setCellValue('A1','Відділ');
		$active_sheet->setCellValue('B1','Дочірній елемент');
		$active_sheet->setCellValue('C1','Оцінка');
		 
		$row_start = 3;
		$i = 0;
		foreach($result as $item) {
			 $row_next = $row_start + $i;
			 
			 $active_sheet->setCellValue('A'.$row_next,$item['parent']);
			 $active_sheet->setCellValue('B'.$row_next,$item['child']);
			 $active_sheet->setCellValue('C'.$row_next,$item['note']);
			 
			 $i++;
		}

		$style_slogan = array(
		//gray color
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
	}
	