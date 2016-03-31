<?php


class LettertemplatesController extends Controller
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
    Yii::app()->params['subMenu'] = $subMenu;
	}
  
  public function actionRepairlettertemplates()
  {
    return;
    
    $connection = Yii::app()->db;
    $command = $connection->createCommand("select id from hoa where id>1");
    $dr = $command->query();
    while ($row = $dr->read()){
      $connection->createCommand("insert into letter_templates (hoaid, name) values (".$row['id'].", 'Letter templates 1')")->execute();
      $connection->createCommand("insert into letter_templates (hoaid, name) values (".$row['id'].", 'Letter templates 2')")->execute();
      $connection->createCommand("insert into letter_templates (hoaid, name) values (".$row['id'].", 'Letter templates 3')")->execute();
    }
    
    echo 'finish';
    exit;
  }
  
	public function actionIndex($hoaid=null)
	{
    $connection = Yii::app()->db;

    if (!$hoaid) {
      //get the first HOA
      $command = $connection->createCommand("select id from hoa order by id limit 1");
      $hoaid = $command->queryScalar();
    }
    
    $command = $connection->createCommand("select * from letter_templates where hoaid=$hoaid");
    $dr = $command->query();
    
		$this->render('index', array('dr'=>$dr));
	}
  
//  public function actionCreate()
//	{
//    $this->_buildShortcuts();
//    
//    if (isset($_POST['HOA'])) {
//      $model = new HOA();
//      $model->attributes = $_POST['HOA'];
//      $model->enteredby = Yii::app()->user->id;
//      $model->enteredtm = time();
//      $model->save();
//      
//      Yii::app()->user->setFlash('', 'HOA is saved.');
//      $this->redirect(array('view','id'=>$model->id));
//    }
//    
//    $model = new HOA();
//    $this->render('_form', array('model'=>$model));
//	}
  
  public function actionUpdate($id)
  {
    $this->_buildShortcuts();
    $model=$this->loadModel($id);

    if(isset($_POST['LetterTemplates']))
    {
      $model->templates = $_POST['LetterTemplates']['templates'];
      if($model->save())
        $this->redirect(array('view','id'=>$model->id));
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
    
    $this->render('view',array(
        'model'=>$this->loadModel($id),
    ));
  }
  
  public function loadModel($id)
  {
    $model=  LetterTemplates::model()->findByPk((int)$id);
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
    $hoa = new HOA();

    if(isset($_POST)){
      if($_POST['clear'] == 1){
        unset($_POST);
      } else {
        $hoa->attributes = $_POST['HOA'];
      }
      
      if ($_POST['HOA']['name']) {
        $condition[] = "name like '%" . $_POST['HOA']['name'] . "%'";
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
