<?php


class TemplateController extends Controller
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
				'actions'=>array('index', 'create', 'update', 'view', 'delete', 'lookup', 'repairlettertemplates'),
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
    
    $subMenu[] = array('text'=>'New Template', 'url'=>'/template/create');
    
    Yii::app()->params['subMenu'] = $subMenu;
	}
  
	public function actionIndex($hoaid=null)
	{
    $connection = Yii::app()->db;
    $this->_buildShortcuts();

//    $command = $connection->createCommand("select * from template order by name");
    $command = $connection->createCommand("select * from template where company_id=:company_id and deleted=0 order by name");
    $command->bindParam(':company_id', Yii::app()->user->company_id);
    $dr = $command->query();
    
		$this->render('index', array('dr'=>$dr));
	}
  
  public function actionCreate()
	{
    $this->_buildShortcuts();
    
    if (isset($_POST['Template'])) {
      $model = new Template();
      $model->attributes = $_POST['Template'];
      $model->enteredby = Yii::app()->user->id;
      $model->enteredtm = time();
      $model->company_id = Yii::app()->user->company_id;
      $model->deleted = 0;
      $model->save();
      
      Yii::app()->user->setFlash('', 'Template is saved.');
      $this->redirect(array('view','id'=>$model->id));
    }
    
    $model = new Template();
    $this->render('_form', array('model'=>$model));
	}
  
  public function actionUpdate($id)
  {
    $this->_buildShortcuts();
    $model=$this->loadModel($id);

    if(isset($_POST['Template']))
    {
      $model->attributes = $_POST['Template'];
      $model->company_id = Yii::app()->user->company_id;
      $model->deleted = 0;
      if($model->save())
        $this->redirect(array('view','id'=>$model->id));
    }
    
    $this->render('_form',array(
        'model'=>$model,
    ));
  }
  
  public function actionDelete($id)
  {
    $connection = Yii::app()->db;
    $command = $connection->createCommand("update template set deleted = 1 where id = :id");
    $command->bindParam(':id', $id);
    $command->execute();
    
    $this->actionIndex();
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
    $model=  Template::model()->findByPk((int)$id);
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
    $hoa = new Template();

    if(isset($_POST)){
      if($_POST['clear'] == 1){
        unset($_POST);
      } else {
        $hoa->attributes = $_POST['Template'];
      }
      
      if ($_POST['Template']['name']) {
        $condition[] = "name like '%" . $_POST['Template']['name'] . "%'";
      }
    }
    
    if ($condition) {
      $condition = ' WHERE ' . implode(' AND ', $condition);
    } else {
      $condition = '';
    }
    
    $_POST['pageSize'] = (int)$_POST['pageSize'];
    $pageSize = $_POST['pageSize'] ? $_POST['pageSize'] : 10;
    $query = "SELECT * FROM hoa " . $condition . ' ORDER BY id DESC';
    $dataProvider = CommonFunc::pagerQuery($query, $pageSize, null);
    
		$this->renderPartial('lookup',array(
      'hoa' => $hoa,
      'dataProvider' => $dataProvider,
      'pageSize' => $pageSize
		));
  }

}


?>
