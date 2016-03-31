<?php


class ViolationController extends Controller
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
				'actions'=>array('index', 'create', 'update', 'view', 'delete'),
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
    $subMenu[] = array('text'=>'New Violation', 'url'=>'/violation/create');
    Yii::app()->params['subMenu'] = $subMenu;
	}
  
	public function actionIndex()
	{
    $this->_buildShortcuts();
    
    $condition = array();
    $violation = new Violation();

    if(isset($_POST)){
      if($_POST['clear'] == 1){
        unset($_POST);
      } else {
        $violation->attributes = $_POST['Violation'];
      }
      
      if ($_POST['Violation']['firstname']) {
        $condition[] = "firstname like '%" . $_POST['Violation']['firstname'] . "%'";
      }
      if ($_POST['Violation']['lastname']) {
        $condition[] = "lastname like '%" . $_POST['Violation']['lastname'] . "%'";
      }
      if ($_POST['Violation']['hoa']) {
        $condition[] = "hoa like '%" . $_POST['Violation']['hoa'] . "%'";
      }
      if ($_POST['Violation']['address1']) {
        $condition[] = "address1 like '%" . $_POST['Violation']['address1'] . "%'";
      }
      if ($_POST['Violation']['city']) {
        $condition[] = "city like '%" . $_POST['Violation']['city'] . "%'";
      }
      if ($_POST['Violation']['state']) {
        $condition[] = "state = '" . $_POST['Violation']['state'] . "'";
      }
      if ($_POST['Violation']['zip']) {
        $condition[] = "zip like '%" . $_POST['Violation']['zip'] . "%'";
      }
      if ($_POST['Violation']['resolution']) {
        $condition[] = "resolution like '%" . $_POST['Violation']['resolution'] . "%'";
      }
    }
    
    $where = 'WHERE company_id='. Yii::app()->user->company_id;
    if ($condition) {
      $where .= ' and ' . implode(' AND ', $condition);
    }

    $_POST['pageSize'] = (int)$_POST['pageSize'];
    $pageSize = $_POST['pageSize'] ? $_POST['pageSize'] : 10;
    $query = "SELECT * FROM violation " . $where . ' ORDER BY id DESC';
    $dataProvider = CommonFunc::pagerQuery($query, $pageSize, null);
    
		$this->render('index',array(
			'violation' => $violation,
			'dataProvider' => $dataProvider,
      'searchForm' => $searchForm,
      'pageSize' => $pageSize
		));
	}
  
  public function actionCreate()
	{
    $this->_buildShortcuts();
 
    if (isset($_POST['Violation'])) {
      $model = new Violation();
      $model->attributes = $_POST['Violation'];
      $model->date = date('m/d/Y', time());
      $model->letter_sent = 'No';
      //Notice #
//      if($_POST['Violation']['notice'] == ''){
        $connection = Yii::app()->db;
        $command = $connection->createCommand("select count(*) from violation where propertyid=:propertyid AND ruleid=:ruleid");
        $command->bindParam(':propertyid', $model->propertyid);
        $command->bindParam(':ruleid', $model->ruleid);
        $noticeCount = $command->queryScalar();
        if($noticeCount >= 2){
          $model->notice = 'fine';
        } else {
          $model->notice = $noticeCount+1;
        }
//      }
      
      $model->enteredby = Yii::app()->user->id;
      $model->enteredtm = time();
      $model->save();
      
      //upload files
      CommonFunc::fetchIphoneUploadFileMultiple($model, array('photographs'));
      
      Yii::app()->user->setFlash('', 'Violation is saved.');
      
      if ($_GET['propertyid']) {//create from view property, redirect to view property
        $this->redirect(array('/property/view/' . $_GET['propertyid']));
      } else {//view this new violation
        $this->redirect(array('view','id'=>$model->id));
      }
    }
    
    $model = new Violation();
    $model->letter_sent = 'No';
    $this->render('_form', array('model'=>$model));
	}
  
  public function actionUpdate($id)
  {
    $this->_buildShortcuts();
    $model=$this->loadModel($id);

    if(isset($_POST['Violation']))
    {
      if ($_POST['Violation']['button'] == 'Cancel') {
        $this->redirect(array('view','id'=>$model->id));
      } else {
        $model->attributes=$_POST['Violation'];
        if($model->notice != 'fine' && $model->notice >= 2){
          $model->notice = 'fine';
        }
        
        $saveRet = $model->save();
        //upload files
        CommonFunc::fetchIphoneUploadFileMultiple($model, array('photographs'));
          
        if($saveRet){
          if ($_GET['from'] == 'property') {
            $this->redirect(array('/property/view/'.$model->propertyid));
          } else {
            $this->redirect(array('view','id'=>$model->id));
          }
          return;
        }
      }
    }

    $this->render('_form',array(
        'model'=>$model,
    ));
  }
  
  public function actionDelete($id)
  {
    $model = $this->loadModel($id);
    $model->delete();
    
    if ($_GET['from'] == 'property') {
      $this->redirect(array('/property/view/'.$model->propertyid .'?ajaxRequest=1'));
    } else {
      $this->actionIndex();
    }
  }
  
  public function actionView($id, $print=false)
  {
    if ($print) {
      Yii::app()->params['print'] = true;
    }
    $this->_buildShortcuts();
    
    $this->render('view',array(
        'model'=>$this->loadModel($id),
    ));
  }
  
  public function loadModel($id)
  {
    $model=Violation::model()->findByPk((int)$id);
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

  
}


?>
