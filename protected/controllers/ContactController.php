<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class ContactController extends Controller
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
				'actions'=>array('index', 'create', 'update', 'view', 'delete', 'lookup', 'saverelationship'),
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
    $subMenu[] = array('text'=>'New Contact', 'url'=>'/contact/create');
    Yii::app()->params['subMenu'] = $subMenu;
  }
  
  public function actionIndex()
  {
    $this->_buildShortcuts();
    
    $condition = array();
    $contact = new Contact();
    
    if(isset($_POST)){
     if($_POST['clear'] == 1){
       unset($_POST);
     } else {
        $contact->attributes = $_POST['Contact'];
     }

     if ($_POST['Contact']['full_name']) {
       $condition[] = "name like '%" . $_POST['Contact']['full_name'] . "%'";
     }
   }

   $where = 'WHERE company_id='. Yii::app()->user->company_id;
   if ($condition) {
     $where .= ' and ' . implode(' AND ', $condition);
   }

   $_POST['pageSize'] = (int)$_POST['pageSize'];
   $pageSize = $_POST['pageSize'] ? $_POST['pageSize'] : 10;
   $query = "SELECT * FROM contact " . $where . ' ORDER BY id DESC';
   $dataProvider = CommonFunc::pagerQuery($query, $pageSize, null);

   $this->render('index',array(
     'contact' => $contact,
     'dataProvider' => $dataProvider,
     'pageSize' => $pageSize
   ));
  }
  
   public function actionCreate()
	{
    $this->_buildShortcuts();
    
    if (isset($_POST['Contact'])) {
      $model = new Contact();
      $model->attributes = $_POST['Contact'];
      $model->enteredby = Yii::app()->user->id;
      $model->enteredtm = time();
      $model->save();

      Yii::app()->user->setFlash('', 'Contact is saved.');
      
      if ($model->customerid) {
        $this->redirect('/decedent/view/'.$model->customerid.'#contactlist');
      } else {
        $this->redirect(array('view','id'=>$model->id));
      }
    }
    
    $model = new Contact();
    
    if ($_GET['customerid']) {
      $customer = Customer::model()->findByPk($_GET['customerid']);
//      $model->customer = $customer->full_legal_name_f . ' '. $customer->full_legal_name_m .' '. $customer->full_legal_name_l;
      $model->customer = $customer->full_legal_name;
      $model->customerid = $customer->id;
    }
    
    $this->render('_form', array('model'=>$model));
	}
  
  public function actionUpdate($id)
  {
    $this->_buildShortcuts();
    $model=$this->loadModel($id);

    if(isset($_POST['Contact']))
    {
      if ($_POST['Contact']['button'] == 'Cancel') {
        $this->redirect(array('view','id'=>$model->id));
      } else {
        $model->attributes=$_POST['Contact'];
        
        if($model->save()){
          if ($model->customerid) {
            $this->redirect('/decedent/view/'.$model->customerid.'#contactlist');
          } else {
            $this->redirect(array('view','id'=>$model->id));
          }
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
    
    if ($_POST['from'] == 'customer') {//delete from customer contact subpanel
      echo 'Done';
      exit;
    } else {//from contact detail view
      $this->redirect(array('/decedent//' . $model->customerid . '?ajaxRequest=1'));
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
    $model=Contact::model()->findByPk((int)$id);
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
    $contact = new Contact();

    if(isset($_POST)){
      if($_POST['clear'] == 1){
        unset($_POST);
      } else {
        $contact->attributes = $_POST['Contact'];
      }
      
      if ($_POST['Contact']['name']) {
        $condition[] = "name like '%" . $_POST['Contact']['name'] . "%'";
      }
    }
    
    $where = 'WHERE company_id='. Yii::app()->user->company_id;
    if ($condition) {
      $where .= ' and ' . implode(' AND ', $condition);
    }
    
    $_POST['pageSize'] = (int)$_POST['pageSize'];
    $pageSize = $_POST['pageSize'] ? $_POST['pageSize'] : 10;
    $query = "SELECT * FROM contact " . $where . ' ORDER BY id DESC';
    $dataProvider = CommonFunc::pagerQuery($query, $pageSize, null);
    
		$this->renderPartial('lookup',array(
      'contact' => $contact,
      'dataProvider' => $dataProvider,
      'pageSize' => $pageSize
		));
  }
  
  function actionSaveRelationship($contact_id){
    $model = Contact::model()->findByPk($contact_id);
    $model->relationship = $_POST['data'];
    
    if($model->save()){
      echo $model->relationship;
      exit;
    }else{
      echo'Save failed';
      exit();
    }
  }
  
}
