<?php


class FormController extends Controller
{
  public $permModule;
  public $formType;
  public $urlModule;
  public $titleModule;
  
  
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
				'actions'=>array('index', 'create', 'update', 'view', 'viewform', 'delete', 
           'submitform', 'updateCustomer', 'submitformCustomer', 'duplicate'),
				'roles'=>array('admin'),
			),
			array('deny',
				'users'=>array('*'),
			),
		);
	}
  
  public function buildShortcuts()
	{
    
	}
  
	public function actionIndex()
	{
    $this->buildShortcuts();
    $dataPending = $dataSubmitted = $dataApproved = $ddlist = array();
    $connection = Yii::app()->db;
    $user = Users::model()->findByPk(Yii::app()->user->uid);
    
    if($_POST['clear'] == 1){
      unset($_POST);
    }
    if(isset($_POST['name'])){
      $condition = " AND id in (". implode(',', $_POST['name']) .") ";
    }
    if(isset($_POST['user'])){
      $userCondition = " WHERE enteredby in ('". implode("','", $_POST['user']) ."') ";
    }
    
    $command = $connection->createCommand("select * from form where company_id=:company_id and type='". $this->formType ."' AND deleted=0 order by id");
    $command->bindParam(':company_id', Yii::app()->user->company_id);
    $dataProvider = $command->query();
    while($row = $dataProvider->read()){
      $ddlist[$row['id']] = $row['name'];
    }

    
    //show all submitted duty log record
    $command = $connection->createCommand("select * from form where company_id=:company_id and type='". $this->formType ."' $condition AND deleted=0 order by id");
    $command->bindParam(':company_id', Yii::app()->user->company_id);
    $dataProvider = $command->query();
    while($row = $dataProvider->read()){
      //select record from form_xx

      $command = $connection->createCommand("select * from form_".$row['id']." $userCondition order by id");
      $dr = $command->query();
      $data = array();
      while($row2 = $dr->read()){
        $data = array(
            'form' => $row,
            'form_id' => $row['id'],
            'node_id' => $row2['id'],
            'timestamp' => date("m/d/Y H:i", $row2['timestamp']),
            'enteredby' => $row2['enteredby'],
            'type' => $row['name'],
            'room' => $row2['room'],
            'studentid' => $row2['studentid'],
        );

        if ($row2['status'] == 'pending') {
          if($row2['enteredby'] == Yii::app()->user->id){
            $dataPending[$row2['timestamp']] = $data;
          }
        } else if ($row2['status'] == 'submitted') {
          if($row2['enteredby'] == Yii::app()->user->id || Form::accessCreate($row, $user) || Form::accessApprove($row, $user)){
            $dataSubmitted[$row2['timestamp']] = $data;
          }
        } else if ($row2['status'] == 'approved') {
          if($row2['enteredby'] == Yii::app()->user->id || Form::accessCreate($row, $user) || Form::accessApprove($row, $user)){
            $dataApproved[$row2['timestamp']] = $data;
          }
        }
      }
    }

      
    krsort($dataPending);
    krsort($dataSubmitted);
    krsort($dataApproved);

    $this->render('//form/index', array(
      'user' => $user,
      'ddlist' => $ddlist,
      'dataPending' => $dataPending,
      'dataSubmitted' => $dataSubmitted,
      'dataApproved' => $dataApproved
    ));
	}
  
  public function actionCreate($id=null)
	{
    $this->buildShortcuts();

    if (isset($id)) {
      $form = Form::model()->findByPk($id);
      if($form){
        $this->render('//form/create', array('form'=>$form));
      }
      return;
    }
    
    //duty log form of this school, set perm by assigned user and department
    $forms = array();
    $connection = Yii::app()->db;
    $command = $connection->createCommand("select * from form where company_id=:company_id and type='". $this->formType ."' and deleted=0 order by id");
    $command->bindParam(':company_id', Yii::app()->user->company_id);
    $dr = $command->query();
    $user = Users::model()->findByPk(Yii::app()->user->uid);
    while ($row = $dr->read()){
      if(Form::accessCreate($row, $user)){
        $forms[] = $row;
      }
    }
    
    $this->render('//form/create_assigned_list', array('forms'=>$forms));
	}
  
  
  public function actionsubmitform($id)
  {
    $form_id = $id;
    $connection = Yii::app()->db;

    if($_POST['node_id']){
      $node_id = $_POST['node_id'];
      $formNode = FormNode::load($node_id);
    } else {
      FormNode::$formName = 'form_' . $form_id;
      $formNode = new FormNode();
      $formNode->form_id = $form_id;
    }
    $formNode->enteredby = Yii::app()->user->id;
    $formNode->timestamp = time();
    
    $signatureDraws = array();
    foreach($_POST as $field=>$value){
      if($field == 'node_id' || $field == 'submit' || $field == 'removed_images' || $field == 'ajaxRequest' || $field == 'op_source_place' || $field == 'form_uniqid') continue;
      $formNode->$field = $value;
      
      //collect all sig fields
      if(strpos($field, 'signature_draw') !== false && !empty($value)){
        $signatureDraws[] = $field;
      }
    }
    $formNode->status = $_POST['status'];
    $formNode->save();
    $node_id = $formNode->id;
    if($signatureDraws){
      foreach($signatureDraws as $drawField){
        //save signature to a picture
        $img = CommonFunc::sigJsonToImage($formNode->$drawField);
        imagepng($img, 'files/signature/sig_'. $formNode->form_id . '_' . $formNode->id .'_'. $drawField .'.png');
        imagedestroy($img);
      }
    }
  
    //upload files
    CommonFunc::processFormUpload($formNode);
    
    
    //post from iframe
    if($this->formType == 'Housing Applications'){
      echo 'Saved.';
    } else {
      echo '<script language="javascript">window.parent.ajaxNew("/'. $this->urlModule .'");</script>';
    }
    exit;
  }
  
  
  public function actionSubmitFormCustomer($id)
  {
    $form_id = $id;
    $connection = Yii::app()->db;
    
    if($_POST['node_id']){
      $node_id = $_POST['node_id'];
      $formNode = FormNode::load($node_id);
    } else {
      FormNode::$formName = 'form_' . $form_id;
      $formNode = new FormNode();
      $formNode->form_id = $form_id;
      
      if ($_GET['duplicate']) {//when duplicate
        $formNode->enteredby = Yii::app()->user->id;
        $formNode->timestamp = time();
      }
    }
    $formNode->timestamp = time();
    
    $signatureDraws = array();
    foreach($_POST as $field=>$value){
      if($field == 'node_id' || $field == 'submit' || $field == 'removed_images' || $field == 'ajaxRequest' || $field == 'op_source_place' || $field == 'form_uniqid') continue;
      $formNode->$field = $value;
      
      //collect all sig fields
      if(strpos($field, 'signature_draw') !== false && !empty($value)){
        $signatureDraws[] = $field;
      }
    }
   
    $formNode->status = $_POST['status'];
    $formNode->save();
    $node_id = $formNode->id;
    if($signatureDraws){
      foreach($signatureDraws as $drawField){
        //save signature to a picture
        $img = CommonFunc::sigJsonToImage($formNode->$drawField);
        imagepng($img, 'files/signature/sig_'. $formNode->form_id . '_' . $formNode->id .'_'. $drawField .'.png');
        imagedestroy($img);
      }
    }
    
    //upload files
    CommonFunc::processFormUpload($formNode);
    
    
    //post from iframe
    if($_REQUEST['op_source_place'] == 'tab'){
      echo '<script language="javascript">window.parent.ajaxNew("/'.$this->urlModule.'");</script>';
    } else {
      $form = Form::model()->findByPk($formNode->form_id);
      echo '<script language="javascript">
        window.parent.ajaxNew("/customers/managesubmit'.$this->urlModule.'/'.$form_id.'");
        </script>';
    }
    exit;
  }
  
  public function actionUpdate($id)
  {
    $this->buildShortcuts();
    $formNode = FormNode::load($id);
    $form = Form::model()->findByPk($formNode->form_id);
    
    //On generic forms (not RCR's, etc.) you can't see other peoples submissions unless you are an 'approver' for that form.
    if($form->type == 'Generic' && $formNode->enteredby != Yii::app()->user->id && Form::accessApprove($form)){
      require_once('protected/controllers/SiteController.php');
      $siteController = new SiteController('site');
      echo $siteController->actionUnautorized();
      exit;
    }
    
    if($formNode->status == 'submitted'){
      $_SESSION['popup'] = "This form was submitted, can't edit.";
      $this->redirect(array('/customers/managesubmit'.$this->urlModule.'/'. $formNode->form_id .'?ajaxRequest=1'));
    }
    
    $this->render('//form/edit', array('form'=>$form, 'formNode'=>$formNode));
  }
  
  public function actionUpdateCustomer($id)
  {
    $this->buildShortcuts();
    $formNode = FormNode::load($id);
    $form = Form::model()->findByPk($formNode->form_id);
    
    //On generic forms (not RCR's, etc.) you can't see other peoples submissions unless you are an 'approver' for that form.
    if($form->type == 'Generic' && $formNode->enteredby != Yii::app()->user->id && Form::accessApprove($form)){
      require_once('protected/controllers/SiteController.php');
      $siteController = new SiteController('site');
      echo $siteController->actionUnautorized();
      exit;
    }
    
    $this->render('//form/edit_customer', array('form'=>$form, 'formNode'=>$formNode));
  }
  
  public function actionDelete($id)
  {
    //customer only can delete self school form node
    $formNode = FormNode::load($id);
    $form = Form::model()->findByPk($formNode->form_id);
    $form_id = $formNode->form_id;
    
    if(!$form->id || !$formNode->id){
      $_SESSION['popup'] = 'Error parameters.';
      $this->redirect(array('/customers/managesubmit'.$this->urlModule.'/'. $form_id .'?ajaxRequest=1'));
    }
    
    //if customer
    $allow = false;
    if(Yii::app()->user->type == 'staff' && $formNode->enteredby = Yii::app()->user->id){
      $allow = true;
    }
    if (Yii::app()->user->type == 'customer' && Yii::app()->user->company_id == $form->company_id) {
      $allow = true;
    }
    
    if (!$allow) {
      $_SESSION['popup'] = 'You are not allowed to do this action.';
      $this->redirect(array('/customers/managesubmit'.$this->urlModule.'/'. $form_id .'?ajaxRequest=1'));
    }
    
    $connection = Yii::app()->db;
    $command = $connection->createCommand('delete from form_' . $form_id . ' where id=' . $id);
    $command->execute();
    
    if ($_REQUEST['op_source_place'] == 'tab') {
      $this->redirect(array('/'.$this->urlModule.'?ajaxRequest=1'));
    } else {
      $this->redirect(array('/customers/managesubmit'.$this->urlModule.'/'. $form_id .'?ajaxRequest=1'));
    }
  }
  
  /**
   * view form
   */
  public function actionviewform($id)
  {
    $this->buildShortcuts();
    
    $form = Form::model()->findByPk($id);
    if($form){
      if($this->formType == 'Housing Applications'){
        $this->render('//form/create_application', array('form'=>$form));
      } else {
        $this->render('//form/create', array('form'=>$form));
      }
    }
  }
  
  /**
   *  view form node
   * @param type $id 
   */
  public function actionview($id)
  {
    $formNode = FormNode::load($id);
    $form = Form::model()->findByPk($formNode->form_id);
    
    //On generic forms (not RCR's, etc.) you can't see other peoples submissions unless you are an 'approver' for that form.
    if($form->type == 'Generic' && $formNode->enteredby != Yii::app()->user->id && Form::accessApprove($form)){
      require_once('protected/controllers/SiteController.php');
      $siteController = new SiteController('site');
      echo $siteController->actionUnautorized();
      exit;
    }
    
    $this->buildShortcuts();
    $this->render('//form/view', array('form'=>$form, 'formNode'=>$formNode));
  }
  
  public function actionDuplicate($id){
    $this->buildShortcuts();
    
    $formNode = FormNode::load($id);
    $form = Form::model()->findByPk($formNode->form_id);
    $form_id = $formNode->form_id;
    
    $fields = unserialize($form->fields);
    $fileFields = array();
    $fields = unserialize($form->fields);
    if(is_array($fields) && $fields){
      foreach($fields as $field_id){
        $field = Field::model()->findByPk($field_id);
        if ($field->type == 'Images') {
          $fileFields[] = $field->name;
        }
      }
    }
    
    //create a new formNode
    FormNode::$formName = 'form_' . $formNode->form_id;
    $newFormNode = new FormNode();
    $newFormNode->form_id = $formNode->form_id;
    
    foreach($newFormNode as $field=>$obj){
      if($field == 'id'){ continue; }
      $newFormNode->$field = $formNode->$field;
      
      if(in_array($field, $fileFields)){
        continue;
      }
    }
    $newFormNode->enteredby = Yii::app()->user->id;
    $newFormNode->timestamp = time();
    $newFormNode->save();
    $node_id = $newFormNode->id;
    
    //copy file field images
    $i=0;
    if($fileFields){
      foreach($fileFields as $field){
        if($formNode->$field){
          $fileIDs = unserialize($formNode->$field);

          if($fileIDs){
            $fieldFileNewIDs = array();
            foreach($fileIDs as $fid){
              $file = File::model()->findByPk($fid);
              $newFile = new File();
              $newFile->relate_id = 'form_' . $form_id .'##' . $node_id;
              $newFile->field = $field;
              $newFile->name = $file->name;

              $fileext = substr($file->server_name, strpos($file->server_name, '.'));
              $filename = uniqid(time() . '_');
              $serverName = $filename . $fileext;
              $newFile->server_name = $serverName;
              $newFile->timestamp = time();
              $newFile->save();
              $fieldFileNewIDs[] = $newFile->id;
              
//              echo '/files/form_images/'.$file->server_name . '-----/files/form_images/'.$newFile->server_name;
//                      exit;
                      
              copy('files/form_images/'.$file->server_name, 'files/form_images/'.$newFile->server_name);
            }

            $i++;
            $newFormNode->$field = serialize($fieldFileNewIDs);
          }
        }
      }
      
      if($i){
        $newFormNode->save();
      }
    }
    
      
      
    $this->render('//form/duplicate', array('form'=>$form, 'formNode'=>$newFormNode));
  }
  

}


?>
