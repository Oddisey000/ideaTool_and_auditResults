<?php

	include('connection.php');
	include('../../libs/phpexcel/PHPExcel.php');

?>

<?php

	 $sql = "SELECT * FROM idea_direct";
	 $result = mysqli_query($conn, $sql);
	 
	 while ($fetch_array = mysqli_fetch_array($result)) {

	 	$segment = $fetch_array['segment'];
	 	$team = $fetch_array['team'];
    $idea = $fetch_array['idea'];
    $date = $fetch_array['date'];
    $user = $fetch_array['user'];
    $quantity = $fetch_array['quantity'];
    $initiator = $fetch_array['initiator'];
	 }

?>

<?php 

	$objPHPExcel = new PHPExcel();

	//$objPHPExcel->createSheet("simple");

	$objPHPExcel->setActiveSheetIndex(0);
	$active_sheet = $objPHPExcel->getActiveSheet(0);


	//Ориентация страницы и  размер листа
$active_sheet->getPageSetup()
 ->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
$active_sheet->getPageSetup()
 ->SetPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
//Поля документа  
$active_sheet->getPageMargins()->setTop(1);
$active_sheet->getPageMargins()->setRight(0.75);
$active_sheet->getPageMargins()->setLeft(0.75);
$active_sheet->getPageMargins()->setBottom(1);
//Название листа
$active_sheet->setTitle("Report"); 
//Шапа и футер 
//$active_sheet->getHeaderFooter()->setOddHeader("&CШапка нашего прайс-листа"); 
//$active_sheet->getHeaderFooter()->setOddFooter('&L&B'.$active_sheet->getTitle().'&RСтраница &P из &N');
//Настройки шрифта
//$objPHPExcel->getDefaultStyle()->getFont()->setName('Arial');
//$objPHPExcel->getDefaultStyle()->getFont()->setSize(8);

/*$active_sheet->getColumnDimension('A')->setWidth(7);
$active_sheet->getColumnDimension('B')->setWidth(80);
$active_sheet->getColumnDimension('C')->setWidth(10);
$active_sheet->getColumnDimension('D')->setWidth(10);*/

/*$active_sheet->mergeCells('A1:D1');
$active_sheet->getRowDimension('1')->setRowHeight(40);
$active_sheet->setCellValue('A1','Техно мир');
 
$active_sheet->mergeCells('A2:D2');
$active_sheet->setCellValue('A2','Компьютеы и комплектующие на любой вкус и цвет');
 
$active_sheet->mergeCells('A4:C4');
$active_sheet->setCellValue('A4','Дата создания прайс-листа');*/

$active_sheet->setCellValue('A6','№п.п');
$active_sheet->setCellValue('B6','Имя');
$active_sheet->setCellValue('C6','Цена');
$active_sheet->setCellValue('D6','кол-во');
 
//В цикле проходимся по элементам
$row_start = 7;
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


//Styles

//массив стилей
$style_wrap = array(
 //рамки
 'borders'=>array(
 //внешняя рамка
 'outline' => array(
 'style'=>PHPExcel_Style_Border::BORDER_THICK
 ),
 //внутренняя
 'allborders'=>array(
 'style'=>PHPExcel_Style_Border::BORDER_THIN,
 'color' => array(
 'rgb'=>'696969'
 )
 )
 )
);
//применяем массив стилей к ячейкам 
$active_sheet->getStyle('A1:D'.($i+6))->applyFromArray($style_wrap);




//Стили для верхней надписи строка 1
$style_header = array(
 //Шрифт
 'font'=>array(
  'bold' => true,
  'name' => 'Times New Roman',
  'size' => 20
 ),
//Выравнивание
 'alignment' => array(
  'horizontal' => PHPExcel_STYLE_ALIGNMENT::HORIZONTAL_CENTER,
  'vertical' => PHPExcel_STYLE_ALIGNMENT::VERTICAL_CENTER,
 ),
//Заполнение цветом
 'fill' => array(
  'type' => PHPExcel_STYLE_FILL::FILL_SOLID,
  'color'=>array(
 'rgb' => 'CFCFCF'
  )
 )


);

$active_sheet->getStyle('A1:D1')->applyFromArray($style_header);

//Стили для слогана компании – вторая строка
$style_slogan = array(
 //шрифт
 'font'=>array(
  'bold' => true,
  'italic' => true,
  'name' => 'Times New Roman',
  'size' => 13,
  'color'=>array(
 'rgb' => '8B8989'
  )
 
 ),
//выравнивание
 'alignment' => array(
  'horizontal' => PHPExcel_STYLE_ALIGNMENT::HORIZONTAL_CENTER,
  'vertical' => PHPExcel_STYLE_ALIGNMENT::VERTICAL_CENTER,
 ),
//заполнение цветом
 'fill' => array(
  'type' => PHPExcel_STYLE_FILL::FILL_SOLID,
  'color'=>array(
 'rgb' => 'CFCFCF'
  )
 ),
//рамки
 'borders' => array(
  'bottom' => array(
  'style'=>PHPExcel_Style_Border::BORDER_THICK
  )
 
 )


);
$active_sheet->getStyle('A2:D2')->applyFromArray($style_slogan);

//Стили для текта возле даты
$style_tdate = array(
//выравнивание
 'alignment' => array(
  'horizontal' => PHPExcel_STYLE_ALIGNMENT::HORIZONTAL_RIGHT,
 ),
//заполнение цветом
 'fill' => array(
  'type' => PHPExcel_STYLE_FILL::FILL_SOLID,
  'color'=>array(
 'rgb' => 'CFCFCF'
  )
 ),
//рамки
 'borders' => array(
  'right' => array(
  'style'=>PHPExcel_Style_Border::BORDER_NONE
  )
 
 )


);
$active_sheet->getStyle('A4:C4')->applyFromArray($style_tdate);

//Стили для даты
$style_date = array(
 //заполнение цветом
 'fill' => array(
  'type' => PHPExcel_STYLE_FILL::FILL_SOLID,
  'color'=>array(
 'rgb' => 'CFCFCF'
  )
 ),
//рамки
 'borders' => array(
  'left' => array(
 'style'=>PHPExcel_Style_Border::BORDER_NONE
  )
 
 ),
 


);
$active_sheet->getStyle('D4')->applyFromArray($style_date);

//Стили для шапочки прайс-листа
$style_hprice = array(
 //выравнивание
 'alignment' => array(
  'horizontal' => PHPExcel_STYLE_ALIGNMENT::HORIZONTAL_CENTER,
 ),
//заполнение цветом
 'fill' => array(
  'type' => PHPExcel_STYLE_FILL::FILL_SOLID,
  'color'=>array(
 'rgb' => 'CFCFCF'
  )
 ),
//Шрифт
 'font'=>array(
  'bold' => true,
  'italic' => true,
  'name' => 'Times New Roman',
  'size' => 10
 ),
 


);
$active_sheet->getStyle('A6:D6')->applyFromArray($style_hprice);
//стили для данных в таблице прайс-листа
$style_price = array(
 'alignment' => array(
  'horizontal' => PHPExcel_STYLE_ALIGNMENT::HORIZONTAL_LEFT,
 )
 

);
$active_sheet->getStyle('A7:D'.($i+6))->applyFromArray($style_price);



	header("Content-Type:application/vnd.ms-excel");
	header("Content-Disposition:attachment;filename='simple.xls'");

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	ob_end_clean();
	$objWriter->save('php://output');

exit();

?>