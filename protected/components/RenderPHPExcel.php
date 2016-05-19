<?php
class RenderPHPExcel
{
  /**
   *
   * @param type $data 
   *    array(
   *      //sheet 1
   *      array(
   *        array(),//header
   *        array(),//data
   *        ......
   *      ), 
   * 
   *      //sheet 2
   *      array(
   *        array(),//header
   *        array(),//data
   *        ......
   *      ),
   * 
   *      ......
   *    )
   * 
   * @param $sheetName array('sheet 1', 'sheet 2');
   * @param $fileName file name
   * 
   * 
   * 
   * How to use:
   *  $excelData = array();
      $excelData[0] = array();
      $excelData[0][] = array('ID', 'Type');
      $excelData[0][] = array('234', 'xxxxxx');
    
      $excelData[1] = array();
      $excelData[1][] = array('First', 'Last');
      $excelData[1][] = array('aaaa', 'bbbbb');
   * 
   *  ......
   * 
   *  new renderPHPExcel($excelData);
   * 
   * @param $sheetName  array('Sheet 1', 'Sheet 2');
   * @param $fileName  'Export_xxx.xlsx'   $fileName = "CustomersExport_".date("Y-m-d-H-i-s").".xlsx";
   */
  public function __construct($excelData, $sheetName, $fileName) {
    set_include_path(get_include_path() . PATH_SEPARATOR .  __DIR__ . '/PHPExcel/');
//    include_once 'PHPExcel.php';
    include_once 'phpexcel/Classes/PHPExcel.php';
    include_once 'phpexcel/Classes/PHPExcel/IOFactory.php';
    
    $objPHPExcel = new PHPExcel();
//    $objPHPExcel->getProperties()->setCreator("Audigy CRM");
//    $objPHPExcel->getProperties()->setLastModifiedBy("Audigy CRM");
//    $objPHPExcel->getProperties()->setTitle("Audigy Nominations & Lock-outs Report");
//    $objPHPExcel->getProperties()->setSubject("Audigy Nominations & Lock-outs Report - Office 2007");
//    $objPHPExcel->getProperties()->setDescription("Audigy Nominations & Lock-outs Report - Office 2007");
//    $objPHPExcel->getProperties()->setKeywords("Audigy Nominations & Lock-outs Report");
//    $objPHPExcel->getProperties()->setCategory("Audigy Nominations & Lock-outs Report");

    //create the a sheet
    foreach($excelData as $i=>$data){
      if($i > 0){
        $objPHPExcel->createSheet();
      }
      $objPHPExcel->setActiveSheetIndex($i);
      $this->buildASheet($objPHPExcel, $data, $sheetName[$i]);
    }
    
    
    $objPHPExcel->setActiveSheetIndex(0);
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save($fileName);
  }
  
  /**
   * Get excel column index
   */
  public function getColIndex($reset=false)
  {
    static $number;
    static $indexA;
    static $indexB;

    if(!$number) $number = 0;
    if(!$indexA) $indexA = 0;
    if(!$indexB) $indexB = 0;

    if($reset){
      $number = $indexA = $indexB = 0;
      return;
    }

    $colA = $colB = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');

    if($number < 26){
      $ret = $colA[$number];
      $number++;
      return $ret;
    }

    $currentA = $colA[$indexA];
    $currentB = $colB[$indexB];
    $indexB++;

    if($currentB == 'Z'){
      $indexA++;
      $indexB = 0;
    }

    return $currentA . $currentB;    
  }
  
  /**
   * $data:  
   *    Line 1:  header column
   *    Line 2..... real data
   */
  public function buildASheet(&$objPHPExcel, $data, $title)
  {
    $this->getColIndex(true);

    //write the title
    foreach($data[0] as $i=>$header){
      $objPHPExcel->getActiveSheet()->setCellValue($this->getColIndex() . '1', $header);
    }

    //real data
    if(!empty($data))
    {
      foreach($data as $j=>$d)
      {
        if($j == 0){ continue; }//header      
        $this->getColIndex(true);      
        foreach($d as $value){
          $type = '';
          
          if (is_array($value)) {
            /**
             * Type:
             *      int
             *      string
             */
            $type = $value['type'];
            $value = $value['value'];
          }
          $value = str_replace('&#039;', "'", $value);
          
          $colIndex = $this->getColIndex();
          $objPHPExcel->getActiveSheet()->setCellValue($colIndex . ($j+1), $value);
          
          if ($type == 'string') {
            $objPHPExcel->getActiveSheet()->getCell($colIndex . ($j+1))->setDataType(PHPExcel_Cell_DataType::TYPE_STRING);
          }
        }
      }
    }

    $objPHPExcel->getActiveSheet()->setTitle($title);
    $objPHPExcel->setActiveSheetIndex(0);
  }
}


?>