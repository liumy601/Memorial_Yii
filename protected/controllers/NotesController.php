<?php


class NotesController extends Controller
{
  public function filters()
  {
      return array(
          'accessControl',
      );
  }
  
	public function accessRules()
	{
		return array(
      array('allow',
				'actions'=>array('index', 'create', 'update', 'view', 'delete', 'popupinfo', 'ajaxlist'),
				'roles'=>array('admin'),
			),
			array('deny',
				'users'=>array('*'),
			),
		);
	}
  
  private function _buildShortcuts()
	{
    Yii::app()->params['subMenu'] = array(
    );
	}
  
	public function actionIndex()
	{
		$this->render('index');
	}
  
  public function actionCreate()
	{
    $this->_buildShortcuts();
    
    if (isset($_POST['Notes'])) {
      $model = new Notes();
      $model->attributes = $_POST['Notes'];
      $model->parent_type = $_REQUEST['parent_type'];
      $model->parent_id = $_REQUEST['parent_id'];
      $model->timestamp = time();
      $model->enteredby = Yii::app()->user->id;
      $model->save();
      
      if ($_GET['from'] == 'diaglog') {
        echo 'Done';
        exit;//direct refresh the tasks list
      } else {
        $this->actionView($model->id);
        exit;
      }
    }
    
    $model = new Notes();
    if ($_GET['from'] == 'diaglog') {
      $this->renderPartial('create', array('model'=>$model, 'parent_type'=>$parent_type, 'parent_id'=>$parent_id));
    } else {
      $this->render('create', array('model'=>$model, 'parent_type'=>$parent_type, 'parent_id'=>$parent_id));
    }
	}
  
  public function actionUpdate($id)
  {
    Users::filterCustomerAccess('notes', $id);
    $this->_buildShortcuts();
    $model=$this->loadModel($id);

    if(isset($_POST['Notes']))
    {
      if ($_POST['Notes']['button'] == 'Cancel') {
        $this->redirect(array('view','id'=>$model->id));
      } else {
        $model->attributes=$_POST['Notes'];
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

    $this->renderPartial('create',array(
        'model'=>$model,
    ));
  }
  
  public function actionDelete($id)
  {
    Users::filterCustomerAccess('notes', $id);
    // we only allow deletion via POST request
    $model = $this->loadModel($id);
    $model->delete();
    
    if ($_POST['from'] == 'customer') {//delete from customer document subpanel
      echo 'Done';
      exit;
    } else {//from contact detail view
      $this->redirect(array('/'.$model->parent_type.'/view/'.$model->parent_id .'?ajaxRequest=1'));
    }
  }
  
  public function actionView($id)
  {
    Users::filterCustomerAccess('notes', $id);
    $this->_buildShortcuts();
    $this->render('view',array(
        'model'=>$this->loadModel($id),
    ));
  }
  
  public function loadModel($id)
  {
    $model=Notes::model()->findByPk((int)$id);
    if($model===null)
        throw new CHttpException(404,'The requested page does not exist.');
    return $model;
  }
  
  
  public function actionAjaxlist($parent_type, $parent_id, $return=0){
    $connection = Yii::app()->db;
    $command = $connection->createCommand("select * from notes where parent_type=:parent_type and parent_id=:parent_id order by id DESC");
    $command->bindParam(':parent_type', $parent_type);
    $command->bindParam(':parent_id', $parent_id);
    $notesDR = $command->query();
    
    
    $list = $this->renderPartial('//notes/ajaxlist',array(
			'notesDR' => $notesDR,
			'parent_type' => $parent_type,
			'parent_id' => $parent_id,
		), $return);
    
    if ($return) {
      return $list;
    }
  }
  
  public function actionPopupInfo()
  {
    $output = array(
        'title' => '',
        'info' => '',
    );
    $model = Notes::model()->findByPk($_GET['notes_id']);
    if ($model->id) {
      $output['title'] = '';
      $output['info'] = $this->renderPartial('popupinfo', array('model'=>$model), true);
    }
    
    echo json_encode($output);
    exit;
  }

}


?>
