<?php

class UsersController extends Controller
{
  /**
	 * @var CActiveRecord the currently loaded data model instance.
	 */
	private $_model;

	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/

  public function filters()
  {
      return array(
          'accessControl',
      );
  }

  /**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
      array('allow',  // deny all users
        'actions'=>array('addperm'),
				'roles'=>array('super admin'),
			),
			array('allow',
				'actions'=>array('index', 'account', 'updateaccount'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

  /**
   * Role:
   *      SysAdmin, CEO, Sales, Ops
   */
  public function actionAddPerm()
  {
    $auth=Yii::app()->authManager;

    //操作
//    $auth->createOperation('searchMonthlyDetails', 'search monthly details');
//    $auth->createOperation('setupUsers', 'setup users');
//    $auth->createOperation('setupUtilities', 'setup utilities');
//    $auth->createOperation('setupGovAg', 'setup GovAg');
//    $auth->createOperation('setupImport', 'setup import');
//    $auth->createOperation('meter', 'setup import');

    //任务
//    $bizRule='return Yii::app()->user->id==$params["post"]->authID;';
//    $task=$auth->createTask('updateOwnPost','update a post by author himself',$bizRule);
//    $task->addChild('updatePost');

//    $role->addChild('setupUsers');
//    $role->addChild('setupUtilities');
//    $role->addChild('setupGovAg');
//    $role->addChild('setupImport');
//
    //创建角色，由低到高

    //Standard users that can use the Meters page
    $auth->createRole('standard');
    $auth->createRole('staff');
    
    //customer can manage staff
    $role = $auth->createRole('customer');
    $role->addChild('staff');

    //Admin user that can manage users and other setup lists.  (Not able to do file imports)
    $role=$auth->createRole('admin');
    $role->addChild('standard');

    //Super user that can manage users and import files.  (Preferati to do all file imports)
    $role=$auth->createRole('super admin');
    $role->addChild('admin');
    $role->addChild('customer');

    //give user role
    $auth->assign('super admin','superadmin');
    $auth->assign('admin','admin');
    $auth->assign('standard','test_user1');

    $this->render('addperm');
  }
  
  /**
 * Creates a new model.
 * If creation is successful, the browser will be redirected to the 'view' page.
 */
////  public function actionCreate()
////  {
////    $model = new Users;
////
////    // Uncomment the following line if AJAX validation is needed
////    // $this->performAjaxValidation($model);
////
////    if(isset($_POST['Users']))
////    {
////      $model->attributes=$_POST['Users'];
////      $model->password = $model->hashPassword($_POST['Users']['password']);
////
////      if($model->save()){
////        //save to pre_auth_assignment
////        $connection = Yii::app()->db;
////        $command = $connection->createCommand('INSERT INTO pre_auth_assignment (itemname, userid) VALUES (:itemname, :userid)');
////        $command->bindParam(':itemname', $_POST['Users']['type'], PDO::PARAM_STR);
////        $command->bindParam(':userid', $_POST['Users']['username'], PDO::PARAM_STR);
////        $command->execute();
//////        $this->redirect(array('view','id'=>$model->id));
////        $this->redirect(array('index'));
////      } else {
////        $model->password = $_POST['Users']['password'];
////      }
////    }
////
////    $this->render('create',array(
////      'model'=>$model,
////    ));
////  }
//
//  public function actionEdit()
//  {
//    $model = Users::model()->findByPk($_GET['id']);
//    if (!$model) {
//      throw new CHttpException('404', 'The user doesn\'t exist.');
//    } else {
//      if (isset($_POST['Users'])) {
//        $model->attributes=$_POST['Users'];
//		$model->password = $model->hashPassword($_POST['Users']['password']);
//		
//        if ($model->save()) {
//          //$this->redirect(array('index'));
//		  $this->redirect(array('/users/view/'.$_GET['id']));
//        } else {
//          $model->addError('', 'Save failed, please try again.');
//        }
//      }
//
//      unset($model->password);
//      $this->render('edit', array('model'=>$model));
//    }
//  }

//  public function actionDelete()
//  {
//    $user = Users::model()->findByPk($_GET['id']);
//    if ($user) {
//      $user->delete();
//      $this->redirect(array('index'));
//    } else {
//      throw new CHttpException(404, 'The user doesn\'t exist.');
//    }
//  }

  /**
   * Show login user self information
   */
  public function actionIndex()
	{
    $model = Users::model()->findByPk(Yii::app()->user->uid);
    $password = $model->password;
    
    if(isset($_POST['Users']))
    {
      $model->email = $_POST['Users']['email'];
      $model->firstname = $_POST['Users']['firstname'];
      $model->lastname = $_POST['Users']['lastname'];

      if($_POST['Users']['password'] != ''){
        $model->password = $model->hashPassword($_POST['Users']['password']);
      } else {
        $model->password = $password;
      }

      if($model->save()){
        $model->password = '';
        Yii::app()->user->setFlash('', 'Saved.');
      } else {
        $model->password = $_POST['Users']['password'];
      }
    }
    
    $this->render('index', array('model' => $model));
	}
  
  public function actionView()
  {
    $model = Users::model()->findByPk(Yii::app()->user->uid);
    
    $this->render('index', array('model' => $model));
  }

  public function loadModel()
	{
		if($this->_model===null)
		{
			if(isset($_GET['id']))
			{
//				if(Yii::app()->user->isGuest)
//					throw new CHttpException(403,'You are not allowed to access this user profile.');
//				else
//					$condition='status='.Post::STATUS_PUBLISHED.' OR status='.Post::STATUS_ARCHIVED;
					$condition='';
				$this->_model=Users::model()->findByPk($_GET['id'], $condition);
			}
			if($this->_model===null)
				throw new CHttpException(404,'The requested page does not exist.');
		}
		return $this->_model;
	}
  
  public function actionAccount()
  {
    $user = Users::model()->findByPk(Yii::app()->user->uid);
    
    $this->render('account', array('user' => $user));
  }
  
  public function actionUpdateAccount()
  {
    $model = Users::model()->findByPk(Yii::app()->user->uid);

    $oldUsername = $model->username;
    if (!$model) {
      throw new CHttpException('404', 'The user doesn\'t exist.');
    } else {
      if (isset($_POST['Users'])) {
        if (!empty($_POST['Users']['password'])) {
          $model->password = $model->hashPassword($_POST['Users']['password']);
        }
        //only below fields can be change by assignee
        $model->email = $_POST['Users']['email'];
        $model->firstname = $_POST['Users']['firstname'];
        $model->lastname = $_POST['Users']['lastname'];
		
        if ($model->save()) {
          //save to pre_auth_assignment
          $connection = Yii::app()->db;
          $command = $connection->createCommand('DELETE FROM pre_auth_assignment where userid=:userid');
          $command->bindParam(':userid', $oldUsername, PDO::PARAM_STR);
          $command->execute();
          
//          $command = $connection->createCommand('INSERT INTO pre_auth_assignment (itemname, userid) VALUES (\'staff\', :userid)');
          $command = $connection->createCommand('INSERT INTO pre_auth_assignment (itemname, userid) VALUES (:itemname, :userid)');
          $command->bindParam(':itemname', Yii::app()->user->type, PDO::PARAM_STR);
          $command->bindParam(':userid', Yii::app()->user->id, PDO::PARAM_STR);
          $command->execute();
          
          //$this->redirect(array('index'));
          $this->redirect(array('/users/account'));
        } else {
          $model->addError('', 'Save failed, please try again.');
        }
      }

      unset($model->password);
      $this->render('updateaccount', array('model'=>$model));
    }
  }

}