<?php
class RenderPHPExcelHeavy
{
  public $objPHPExcel;
  public $sheetIndex = -1;
  public $line = 0;
  
  /**
   *  ob_clean();
      $dir = dirname($_SERVER['SCRIPT_FILENAME']);
      include_once $dir . '/Classes/RenderPHPExcelHeavy.php';
      $excel = new RenderPHPExcelHeavy('Sheet1');

      $excel->addLine($header);
      $excel->addLine(array('safdsdf', 'g3545yrewf', 'sag32w4'));
      $excel->addLine(array('45yrthrth', '5j56j5u6u', '45jgedfdsf'));
      $excel->addLine(array('hj42fb', 'enhn', 'ergg5ryg'));

      $excel->addSheet('Sheet2');
      $excel->addLine(array('234234safdsdf', '345345g3545yrewf', '2525sag32w4'));
      $excel->addLine(array('25244325yrthrth', '353455j56j5u6u', '23523445jgedfdsf'));
      $excel->addLine(array('543hj42fb', 'enhn', '345346ergg5ryg'));
   
      $excel->save($fileName);
   */
  public function __construct($firstSheetTitle) {
    set_include_path(get_include_path() . PATH_SEPARATOR .  dirname(__FILE__));
    include_once 'phpexcel/Classes/PHPExcel.php';
    include_once 'phpexcel/Classes/PHPExcel/IOFactory.php';
    
    $this->objPHPExcel = new PHPExcel();
//    $this->objPHPExcel->getProperties()->setCreator("Audigy CRM");
//    $this->objPHPExcel->getProperties()->setLastModifiedBy("Audigy CRM");
//    $this->objPHPExcel->getProperties()->setTitle("Audigy Nominations & Lock-outs Report");
//    $this->objPHPExcel->getProperties()->setSubject("Audigy Nominations & Lock-outs Report - Office 2007");
//    $this->objPHPExcel->getProperties()->setDescription("Audigy Nominations & Lock-outs Report - Office 2007");
//    $this->objPHPExcel->getProperties()->setKeywords("Audigy Nominations & Lock-outs Report");
//    $this->objPHPExcel->getProperties()->setCategory("Audigy Nominations & Lock-outs Report");
    
    //when new PHPExcel(); already have a sheet
    $this->sheetIndex++;
    $this->objPHPExcel->setActiveSheetIndex($this->sheetIndex);
    $this->objPHPExcel->getActiveSheet()->setTitle($firstSheetTitle);
  }
  
  public function addSheet($sheetTitle)
  {
    $this->objPHPExcel->createSheet();
    $this->sheetIndex++;
    $this->objPHPExcel->setActiveSheetIndex($this->sheetIndex);
    $this->objPHPExcel->getActiveSheet()->setTitle($sheetTitle);
    $this->line = 0;//reset line number to 0
  }
  
  /**
   * $data:  
   *    Line 1:  header column
   *    Line 2..... real data
   */
  public function addLine($row)
  {
    $this->getColIndex(true);

    //real data
    if(!empty($row))
    {
      $this->line++;
      foreach($row as $value)
      {
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
        $this->objPHPExcel->getActiveSheet()->setCellValue($colIndex . $this->line, $value);

        if ($type == 'string') {
          $this->objPHPExcel->getActiveSheet()->getCell($colIndex . $this->line)->setDataType(PHPExcel_Cell_DataType::TYPE_STRING);
        }
      }
    }
    
//    $this->objPHPExcel->setActiveSheetIndex($this->sheetIndex);
  }
  
  public function save($fileName)
  {
    $this->objPHPExcel->setActiveSheetIndex(0);
    $objWriter = PHPExcel_IOFactory::createWriter($this->objPHPExcel, 'Excel2007');
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
  
}


?>