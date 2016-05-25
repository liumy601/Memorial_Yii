<?php


class RuleController extends Controller
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
				'actions'=>array('index', 'create', 'update', 'view', 'delete', 'getInfo', 'getbyproperty'),
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
    $subMenu[] = array('text'=>'New Rule', 'url'=>'/rule/create');
    Yii::app()->params['subMenu'] = $subMenu;
	}
  
	public function actionIndex()
	{
    $this->_buildShortcuts();
    
    $condition = array();
    $rule = new Rule();

    if(isset($_POST)){
      if($_POST['clear'] == 1){
        unset($_POST);
      } else {
        $rule->attributes = $_POST['Rule'];
      }
      
      if ($_POST['Rule']['name']) {
        $condition[] = "name like '%" . $_POST['Rule']['name'] . "%'";
      }
    }
    
    $where = 'WHERE company_id='. Yii::app()->user->company_id;
    if ($condition) {
      $where .= ' and ' . implode(' AND ', $condition);
    }
    
    $_POST['pageSize'] = (int)$_POST['pageSize'];
    $pageSize = $_POST['pageSize'] ? $_POST['pageSize'] : 10;
    $query = "SELECT * FROM rule " . $where . ' ORDER BY id DESC';
    $dataProvider = CommonFunc::pagerQuery($query, $pageSize, null);
    
		$this->render('index',array(
			'rule' => $rule,
			'dataProvider' => $dataProvider,
      'searchForm' => $searchForm,
      'pageSize' => $pageSize
		));
	}
  
  public function actionCreate()
	{
    $this->_buildShortcuts();
    
    if (isset($_POST['Rule'])) {
      $model = new Rule();
      $model->attributes = $_POST['Rule'];
      $model->enteredby = Yii::app()->user->id;
      $model->enteredtm = time();
      $model->save();
      
      Yii::app()->user->setFlash('', 'Rule is saved.');
      $this->redirect(array('view','id'=>$model->id));
    }
    
    $model = new Rule();
    $this->render('_form', array('model'=>$model));
	}
  
  public function actionUpdate($id)
  {
    $this->_buildShortcuts();
    $model=$this->loadModel($id);

    if(isset($_POST['Rule']))
    {
      if ($_POST['Rule']['button'] == 'Cancel') {
        $this->redirect(array('view','id'=>$model->id));
      } else {
        $model->attributes=$_POST['Rule'];
        
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
    
    $this->render('view',array(
        'model'=>$this->loadModel($id),
    ));
  }
  
  public function loadModel($id)
  {
    $model=Rule::model()->findByPk((int)$id);
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
  
  public function actionGetInfo($id)
  {
    $model=$this->loadModel($id);
    
    $info = array(
        'hoa' => $model->hoa,
        'hoaid' => $model->hoaid,
        'name' => $model->name,
        'hoa_description' => $model->hoa_description,
        'letter_text' => $model->letter_text,
        'enteredby' => $model->enteredby,
        'enteredtm' => $model->enteredtm
    );
    
    echo json_encode($info);
    exit;
  }

  public function actionGetByProperty($id)
  {
    $connection = Yii::app()->db;
    $command = $connection->createCommand("select r.id, r.name from rule r, property p 
      where p.company_id=". Yii::app()->user->company_id ." and r.hoaid=p.hoaid and p.id=:id");
    $command->bindParam(':id', $id);
    $dr = $command->query();
    $options = '';
    while ($row = $dr->read()) {
      $options .= '<option value="'. $row['id'] .'">'. CHtml::encode($row['name']) .'</option>';
    }
    
    echo $options;
    exit;
  }
  
}


?>
