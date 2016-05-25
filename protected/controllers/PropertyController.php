<?php


class PropertyController extends Controller
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
				'actions'=>array('index', 'create', 'update', 'view', 'delete', 'lookup'),
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
    $subMenu[] = array('text'=>'New Property', 'url'=>'/property/create');
    Yii::app()->params['subMenu'] = $subMenu;
	}
  
	public function actionIndex()
	{
    $this->_buildShortcuts();
    
    $condition = array();
    $property = new Property();

    if(isset($_POST)){
      if($_POST['clear'] == 1){
        unset($_POST);
      } else {
        $property->attributes = $_POST['Property'];
      }
      
      if ($_POST['Property']['firstname']) {
        $condition[] = "firstname like '%" . $_POST['Property']['firstname'] . "%'";
      }
      if ($_POST['Property']['lastname']) {
        $condition[] = "lastname like '%" . $_POST['Property']['lastname'] . "%'";
      }
      if ($_POST['Property']['hoa']) {
        $condition[] = "hoa like '%" . $_POST['Property']['hoa'] . "%'";
      }
      if ($_POST['Property']['address1']) {
        $condition[] = "address1 like '%" . $_POST['Property']['address1'] . "%'";
      }
      if ($_POST['Property']['city']) {
        $condition[] = "city like '%" . $_POST['Property']['city'] . "%'";
      }
      if ($_POST['Property']['state']) {
        $condition[] = "state = '" . $_POST['Property']['state'] . "'";
      }
      if ($_POST['Property']['zip']) {
        $condition[] = "zip like '%" . $_POST['Property']['zip'] . "%'";
      }
    }
    
    $where = 'WHERE company_id='. Yii::app()->user->company_id;
    if ($condition) {
      $where .= ' and ' . implode(' AND ', $condition);
    }

    $_POST['pageSize'] = (int)$_POST['pageSize'];
    $pageSize = $_POST['pageSize'] ? $_POST['pageSize'] : 10;
    $query = "SELECT * FROM property " . $where . ' ORDER BY id DESC';
    $dataProvider = CommonFunc::pagerQuery($query, $pageSize, null);
    
		$this->render('index',array(
			'property' => $property,
			'dataProvider' => $dataProvider,
      'pageSize' => $pageSize
		));
	}
  
  public function actionCreate()
	{
    $this->_buildShortcuts();
    
    if (isset($_POST['Property'])) {
      $model = new Property();
      $model->attributes = $_POST['Property'];
      $model->enteredby = Yii::app()->user->id;
      $model->enteredtm = time();
      $model->save();
      
      Yii::app()->user->setFlash('', 'Property is saved.');
      $this->redirect(array('view','id'=>$model->id));
    }
    
    $model = new Property();
    $model->state = 'Washington';
    $this->render('_form', array('model'=>$model));
	}
  
  public function actionUpdate($id)
  {
    $this->_buildShortcuts();
    $model=$this->loadModel($id);

    if(isset($_POST['Property']))
    {
      if ($_POST['Property']['button'] == 'Cancel') {
        $this->redirect(array('view','id'=>$model->id));
      } else {
        $model->attributes=$_POST['Property'];
        
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
    $command = $connection->createCommand("select * from violation where propertyid=:propertyid order by enteredtm desc");
    $command->bindParam(':propertyid', $id);
    $violationDataProvider = $command->query();
    
    $this->render('view',array(
        'model'=>$this->loadModel($id),
        'violationDataProvider'=>$violationDataProvider,
    ));
  }
  
  public function loadModel($id)
  {
    $model=Property::model()->findByPk((int)$id);
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
    $property = new Property();

    if(isset($_POST)){
      if($_POST['clear'] == 1){
        unset($_POST);
      } else {
        $property->attributes = $_POST['Property'];
      }
      
      if ($_POST['Property']['firstname']) {
        $condition[] = "firstname like '%" . $_POST['Property']['firstname'] . "%'";
      }
      if ($_POST['Property']['lastname']) {
        $condition[] = "lastname like '%" . $_POST['Property']['lastname'] . "%'";
      }
      if ($_POST['Property']['hoa']) {
        $condition[] = "hoa like '%" . $_POST['Property']['hoa'] . "%'";
      }
      if ($_POST['Property']['address1']) {
        $condition[] = "address1 like '%" . $_POST['Property']['address1'] . "%'";
      }
      if ($_POST['Property']['city']) {
        $condition[] = "city like '%" . $_POST['Property']['city'] . "%'";
      }
      if ($_POST['Property']['state']) {
        $condition[] = "state = '" . $_POST['Property']['state'] . "'";
      }
      if ($_POST['Property']['zip']) {
        $condition[] = "zip like '%" . $_POST['Property']['zip'] . "%'";
      }
    }
    
    $where = 'WHERE company_id='. Yii::app()->user->company_id;
    if ($condition) {
      $where .= ' and ' . implode(' AND ', $condition);
    }
    
    $_POST['pageSize'] = (int)$_POST['pageSize'];
    $pageSize = $_POST['pageSize'] ? $_POST['pageSize'] : 10;
    $query = "SELECT * FROM property " . $where . ' ORDER BY id DESC';
    $dataProvider = CommonFunc::pagerQuery($query, $pageSize, null);
    
		$this->renderPartial('lookup',array(
      'property' => $property,
			'dataProvider' => $dataProvider,
      'pageSize' => $pageSize
		));
  }

  
}


?>
