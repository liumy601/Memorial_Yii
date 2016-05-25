<?php


class TaskController extends Controller
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
				'actions'=>array('index', 'create', 'update', 'view', 'delete', 'popupinfo', 'ajaxlist', 'mytask', 'close'),
				'roles'=>array('customer', 'staff'),
			),
			array('deny',
				'users'=>array('*'),
			),
		);
	}
  
  private function _buildShortcuts()
	{
    Yii::app()->params['subMenu'] = array(
        array('text'=>'New task', 'url'=>'/task/create'),
    );
	}
  
	public function actionIndex()
	{
    $this->_buildShortcuts();
    
    $searchForm = new TaskSearchForm();
    $condition = array();
    if(isset($_POST['TaskSearchForm'])){
      if($_POST['clear'] == 1){
        unset($_POST['TaskSearchForm']);
      } else {
        $searchForm->attributes = $_POST['TaskSearchForm'];
      }
               
      if ($_POST['TaskSearchForm']['subject']) {
        $condition[] = "subject like '%" . $_POST['TaskSearchForm']['subject'] . "%'";
      }
      if ($_POST['TaskSearchForm']['status']) {
        $condition[] = "status='" . $_POST['TaskSearchForm']['status'] . "'";
      }
      if ($_POST['TaskSearchForm']['date_due'] != '') {
        $condition[] = "date_due='" . $_POST['TaskSearchForm']['date_due'] . "'";
      }
      if ($_POST['TaskSearchForm']['assigned_to'] != '') {
        $condition[] = "assigned_to='" . $_POST['TaskSearchForm']['assigned_to'] . "'";
      }
    }
    
    if ($condition) {
      $condition = ' AND ' . implode(' AND ', $condition);
    } else {
      $condition = '';
    }
    
    $_POST['pageSize'] = (int)$_POST['pageSize'];
    $pageSize = $_POST['pageSize'] ? $_POST['pageSize'] : 10;
    $query = "SELECT * FROM task WHERE company_id=". Yii::app()->user->company_id . $condition . ' ORDER BY id DESC';
    $dataProvider = CommonFunc::pagerQuery($query, $pageSize, null);
    
		$this->render('index',array(
			'dataProvider' => $dataProvider,
      'searchForm' => $searchForm,
      'pageSize' => $pageSize
		));
	}
  
  public function actionAjaxlist($parent_type, $parent_id, $return=0){
    $connection = Yii::app()->db;
    $command = $connection->createCommand("select * from task where parent_type=:parent_type and parent_id=:parent_id and company_id=:company_id order by id DESC");
    $command->bindParam(':parent_type', $parent_type);
    $command->bindParam(':parent_id', $parent_id);
    $command->bindParam(':company_id', Yii::app()->user->company_id);
    $taskDR = $command->query();
    
    $list = $this->renderPartial('//task/ajaxlist',array(
			'taskDR' => $taskDR,
			'parent_type' => $parent_type,
			'parent_id' => $parent_id,
		), $return);
    
    if ($return) {
      return $list;
    }
  }
  
  public function actionCreate()
	{
    $this->_buildShortcuts();
    
    if (isset($_POST['Task'])) {
      $model = new Task();
      $model->attributes = $_POST['Task'];
      $model->parent_type = $_REQUEST['parent_type'];
      $model->parent_id = $_REQUEST['parent_id'];
      $model->timestamp = time();
      $model->enteredby = Yii::app()->user->id;
      $model->company_id = Yii::app()->user->company_id;
      $model->save();

      if ($_GET['from'] == 'diaglog') {
        echo 'Done';
        exit;//direct refresh the tasks list
      } else {
        $this->actionView($model->id);
        exit;
      }
    }
    
    $model = new Task();
    
    if ($_GET['from'] == 'diaglog') {
      $this->renderPartial('create', array('model'=>$model, 'parent_type'=>$parent_type, 'parent_id'=>$parent_id));
    } else {
      $this->render('create', array('model'=>$model, 'parent_type'=>$parent_type, 'parent_id'=>$parent_id));
    }
	}
  
  public function actionUpdate($id)
  {
    $this->_buildShortcuts();
    $model=$this->loadModel($id);

    if(isset($_POST['Task']))
    {
      if ($_POST['Task']['button'] == 'Cancel') {
        $this->redirect(array('view','id'=>$model->id));
      } else {
        $model->attributes=$_POST['Task'];
        $model->company_id=Yii::app()->user->company_id;
        if($model->save()){
//          $this->redirect(array('/'.$model->parent_type.'/view/'.$model->parent_id .'?ajaxRequest=1'));
          if ($_GET['from'] == 'diaglog') {
            echo 'Done';
            exit;//direct refresh the tasks list
          } else {
            $this->actionView($model->id);
            exit;
          }
        }
      }
    }

    if ($_GET['from'] == 'diaglog') {
      $this->renderPartial('create', array('model'=>$model, 'parent_type'=>$parent_type, 'parent_id'=>$parent_id));
    } else {
      $this->render('create', array('model'=>$model, 'parent_type'=>$parent_type, 'parent_id'=>$parent_id));
    }
  }
  
  public function actionDelete($id)
  {
    $model = $this->loadModel($id);
    $model->delete();
    
    if ($_POST['from'] == 'customer') {//delete from customer document subpanel
      echo 'Done';
      exit;
    } else {//from contact detail view
      if ($model->parent_id > 0) {
        $this->redirect(array('/'.$model->parent_type.'/view/'.$model->parent_id .'?ajaxRequest=1'));
      } else {
        $this->redirect(array('/task?ajaxRequest=1'));
      }
    }
  }
  
  public function actionView($id, $print=false)
  {
    $this->_buildShortcuts();
    
    $this->render('view',array(
        'model'=>$this->loadModel($id),
    ));
  }
  
  public function loadModel($id)
  {
    $model=Task::model()->findByPk((int)$id);
    if($model===null)
        throw new CHttpException(404,'The requested page does not exist.');
    return $model;
  }
  
  public function actionPopupInfo()
  {
    $output = array(
        'title' => '',
        'info' => '',
    );
    $model = Task::model()->findByPk($_GET['task_id']);
    if ($model->id) {
      $output['title'] = '';
      $output['info'] = $this->renderPartial('popupinfo', array('model'=>$model), true);
    }
    
    echo json_encode($output);
    exit;
  }

  public function actionMytask($return=0){
    $connection = Yii::app()->db;
    
    $pageSize = 5;
    $query = "SELECT * FROM task WHERE assigned_to=". Yii::app()->user->uid . " and company_id=" . Yii::app()->user->company_id ." ORDER BY id DESC";
//    $query = "SELECT * FROM task WHERE assigned_to= 80 ";
    $taskDR = CommonFunc::pagerQuery($query, $pageSize, null);
    
    $list = $this->renderPartial('//task/mytask',array(
			'taskDR' => $taskDR
		), $return);//ajax request to refresh, this will output directly
    
    if ($return) {//show for first time
      return $list;
    }
  }
  
  public function actionClose($id)
  {
    $model = Task::model()->findByPk($id);
    if ($model->id) {
      $model->status = 'Closed';
    }
    $model->save();
    
    $this->actionView($id);
    exit;
  }
  
}

?>
