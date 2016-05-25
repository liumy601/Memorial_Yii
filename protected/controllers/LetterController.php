<?php


class LetterController extends Controller
{
  public function filters()
  {
      return array(
          'accessControl'
      );
  }
  
	public function accessRules()
	{
		return array(
      array('allow',
				'actions'=>array('index', 'create', 'update', 'view', 'delete', 'confirmprint'),
				'roles'=>array('admin', 'staff'),
			),
			array('deny',
				'users'=>array('*'),
			),
		);
	}
  
  private function _buildShortcuts()
	{
    $subMenu = array();
    Yii::app()->params['subMenu'] = $subMenu;
	}
  
	public function actionIndex()
	{
    $this->render('seletHOA');
	}
  
  public function actionCreate()
	{
    $this->_buildShortcuts();
    
    if (isset($_GET['hoa'])) {
      $hoa = HOA::model()->findByPk($_GET['hoa']);
      
      /**
          Generate Letters:
            One letter per violation  
            List all the letters on one page which can be printed (open in a new window)
            User must confirm letters printed successfully. If clicks no letters are not logged as sent on the violations. 
            All violations "letter text" is listed in the appropriate space in the template one after the other. 
            After each violation include "Please  open the following url to view photos of this violation: domain.com/77dyy5k (is a blank page with the images for the violation only in a column with some space between them)
       */
      //get all violations of this HOA
      $connection = Yii::app()->db;
      $command = $connection->createCommand("select v.*, v.id as violation_id, v.enteredtm as violation_time, r.* from violation v, property r where v.propertyid=r.id and r.hoaid=:hoa order by v.id");
      $command->bindParam(':hoa', $_GET['hoa']);
      $dataProvider = $command->query();
      
      Yii::app()->params['print'] = true;
      Yii::app()->params['noPrintOnLoad'] = true;//don't print directly, will print after confirm print alert
      $this->render('generateLetters',array(
        'hoa' => $hoa,
        'dataProvider' => $dataProvider,
      ));
      return;
    }
    
    $this->render('seletHOA');
	}
  
  public function actionUpdate($id)
  {
    $this->_buildShortcuts();
    $model=$this->loadModel($id);

    if(isset($_POST['Letter']))
    {
      if ($_POST['Letter']['button'] == 'Cancel') {
        $this->redirect(array('view','id'=>$model->id));
      } else {
        $model->attributes=$_POST['Letter'];
        
        if($model->save())
          $this->redirect(array('view','id'=>$model->id));
      }
    }

    $this->render('_form',array(
        'model'=>$model,
    ));
  }
  
  public function actionDelete($id)
  {
    $this->loadModel($id)->delete();
    $this->actionIndex();
  }
  
  public function actionView($id, $print=false)
  {
    if ($print) {
      Yii::app()->params['print'] = true;
    }
    $this->_buildShortcuts();
    
    $connection = Yii::app()->db;
    $command = $connection->createCommand("select * from violation where letterid=:letterid order by enteredtm desc");
    $command->bindParam(':letterid', $id);
    $violationDataProvider = $command->query();
    
    $this->render('view',array(
        'model'=>$this->loadModel($id),
        'violationDataProvider'=>$violationDataProvider,
    ));
  }
  
  public function loadModel($id)
  {
    $model=Letter::model()->findByPk((int)$id);
    if($model===null)
        throw new CHttpException(404,'The requested page does not exist.');
    return $model;
  }

  public function actionFollowUp($return=0){
    $connection = Yii::app()->db;
    
    $pageSize = 5;
    $query = "SELECT * FROM action WHERE company_id=". Yii::app()->user->company_id ." AND assigned_to=". Yii::app()->user->uid ." AND follow_up=1 AND resolved=0 ORDER BY subject";
    $followupDR = CommonFunc::pagerQuery($query, $pageSize, null);
    
    $list = $this->renderPartial('//action/followup',array(
			'followupDR' => $followupDR
		), $return);//ajax request to refresh, this will output directly
    
    if ($return) {//show for first time
      return $list;
    }
  }
  
  public function actionLookup()
  {
    $condition = array();
    $letter = new Letter();

    if(isset($_POST)){
      if($_POST['clear'] == 1){
        unset($_POST);
      } else {
        $letter->attributes = $_POST['Letter'];
      }
      
      if ($_POST['Letter']['firstname']) {
        $condition[] = "firstname like '%" . $_POST['Letter']['firstname'] . "%'";
      }
      if ($_POST['Letter']['lastname']) {
        $condition[] = "lastname like '%" . $_POST['Letter']['lastname'] . "%'";
      }
      if ($_POST['Letter']['hoa']) {
        $condition[] = "hoa like '%" . $_POST['Letter']['hoa'] . "%'";
      }
      if ($_POST['Letter']['address1']) {
        $condition[] = "address1 like '%" . $_POST['Letter']['address1'] . "%'";
      }
      if ($_POST['Letter']['city']) {
        $condition[] = "city like '%" . $_POST['Letter']['city'] . "%'";
      }
      if ($_POST['Letter']['state']) {
        $condition[] = "state = '" . $_POST['Letter']['state'] . "'";
      }
      if ($_POST['Letter']['zip']) {
        $condition[] = "zip like '%" . $_POST['Letter']['zip'] . "%'";
      }
      if ($_POST['Letter']['resolution']) {
        $condition[] = "resolution like '%" . $_POST['Letter']['resolution'] . "%'";
      }
    }
    
    if ($condition) {
      $condition = ' WHERE ' . implode(' AND ', $condition);
    } else {
      $condition = '';
    }
    
    $_POST['pageSize'] = (int)$_POST['pageSize'];
    $pageSize = $_POST['pageSize'] ? $_POST['pageSize'] : 10;
    $query = "SELECT * FROM letter " . $condition . ' ORDER BY id DESC';
    $dataProvider = CommonFunc::pagerQuery($query, $pageSize, null);
    
		$this->renderPartial('lookup',array(
      'letter' => $letter,
			'dataProvider' => $dataProvider,
      'pageSize' => $pageSize
		));
  }
  
  public function actionConfirmPrint()
  {    
//    $violationIDs = json_decode($_POST['violationIDs']);
//    
//    if ($violationIDs) {
//      $connection = Yii::app()->db;
//      $command = $connection->createCommand("update violation set letter_sent=:letter_sent_date where id in (". implode(',', $violationIDs) .")");
//      $letter_sent_date = date('m/d/Y');
//      $command->bindParam(':letter_sent_date', $letter_sent_date);
//      $command->execute();
//    }
    
    //print to pdf
    require_once(dirname(__FILE__) . '/../components/tcpdf/config/lang/eng.php');
    require_once(dirname(__FILE__) . '/../components/tcpdf/tcpdf.php');

    // create new PDF document
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    // set document information
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Zyp');
    $pdf->SetTitle('Letters');
    $pdf->SetSubject('Letters');
    $pdf->SetKeywords('Letters');

    // set default header data
    $pdf_header_title = 'HOA Pro - Letters';
    $pdf_header_string = "by HOA Pro - hoa.preferati.net\nhoa.preferati.net";

//    $pdf->SetHeaderData('', 0, '', '');

    // set header and footer fonts
//    $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
//    $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

    // remove default header/footer
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);

    // set default monospaced font
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    //set margins
    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

    //set auto page breaks
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

    //set image scale factor
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    //set some language-dependent strings
    $pdf->setLanguageArray($l);

    // ---------------------------------------------------------

    // set font
    $pdf->SetFont('dejavusans', '', 7);
    $pdf->SetTopMargin(10);

    $hoa = HOA::model()->findByPk($_POST['hoaid']);
    /**
        Generate Letters:
          One letter per violation  
          List all the letters on one page which can be printed (open in a new window)
          User must confirm letters printed successfully. If clicks no letters are not logged as sent on the violations. 
          All violations "letter text" is listed in the appropriate space in the template one after the other. 
          After each violation include "Please  open the following url to view photos of this violation: domain.com/77dyy5k (is a blank page with the images for the violation only in a column with some space between them)
     */
    //get all violations of this HOA
    $connection = Yii::app()->db;
    $today = date('m/d/Y', time());
    
    $command = $connection->createCommand("select v.*, v.id as violation_id, v.enteredtm as violation_time, r.* from violation v, property r where v.propertyid=r.id and r.hoaid=:hoa order by v.id");
    $command->bindParam(':hoa', $_POST['hoaid']);
    $dataProvider = $command->query();
    while ($row = $dataProvider->read()) {
      $letterTemplateID = $row['notice'];
      if ($row['notice'] == 'fine') {
        $letterTemplateID = '3';
      }

      //get from hoa and template name
      $letterTemplate = LetterTemplates::loadByHOALPID($hoa->id, $letterTemplateID);
      if ($letterTemplate['templates'] == '') {
        continue;
      }
      //Violations with a letter date should not show to print (this may already be the case)
      if (strlen($row['letter_sent']) == 10 && $row['letter_sent'] != 'No') {
        continue;
      }
      $i++;
      
      $viewPhotos = '<br/>Please open the following url to view photos of this violation: <br/>';
      $images = unserialize($row['photographs']);
      if(is_array($images) && $images){
        $viewPhotosArray = array();
        foreach ($images as $imageId){
          $image = File::model()->findByPk($imageId);
          $url = Yii::app()->params['siteURL'] . '/files/form_images/'.$image->server_name;
          $viewPhotosArray[] = CHtml::link($url, $url, array('target'=>'_blank', 'class'=>'noajax'));
        }
        $viewPhotos .= implode('<br/>', $viewPhotosArray);
      }
      
      $letter = str_replace(
          array(
              '{{Date}}',
              '{{Name}}',
              '{{Address}}',
              '{{City}}',
              '{{State}}',
              '{{Zip}}',
              '{{ViolationDate}}',
              '{{ViolationText}}',
              '{{ViewPhotos}}',
          ), 
          array(
              $today,
              $row['firstname'] .' '. $row['lastname'],
              $row['address1'] .' '. $row['address2'],
              $row['city'],
              $row['state'],
              $row['zip'],
              date('l, F d, Y', $row['violation_time']),
              $row['letter_text'],
              $viewPhotos,
          ), 
          $letterTemplate['templates']);
      
//  $letter = preg_replace('/<p.*>\s+&nbsp;<\/p>/', '', $letter);
//  $letter = str_replace(array('<p>', '</p>', '<p '), array('<span>', '</span><br/>', '<span '), $letter);
      
      $css = '<style>
h1{
  color: #A49262;
  font-size: 18pt;
  font-family: Arial, sans-serif;
  margin-top: 5px;
  margin-bottom: 3px;
}
p{
  margin:0 0 0 0
  padding:0 0 0 0;
  line-height:5px;
}
span{
 margin:0 0 0 0;
 padding:0 0 0 0;
 text-indent:0;
}
</style>';
      
      $letter = $css . $letter;
      
      if ($letter != '') {
        // add a page
        $pdf->AddPage();

        // test some inline CSS

        $pdf->writeHTML($letter, true, false, true, false, '');

        // reset pointer to the last page
        $pdf->lastPage();
      }
      
    }
    
    
    //Close and output PDF document
    $fileName = $_SERVER['DOCUMENT_ROOT'] .  '/files/letter/Letter_'. time() .'.pdf';
    $pdf->Output($fileName, 'FD');
    exit;
  }

  
}


?>
