<?php

class SiteController extends Controller
{
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
			array('allow',
				'actions'=>array('setup'),
				'roles'=>array('admin'),//TODO
			),
      array('allow',
				'actions'=>array('login'),
				'users'=>array('?'),
			),
      array('allow',
				'actions'=>array('logout', 'downloadFiles', 'search', 'iphoneuploadfile', 'logintoappapp', 'appappplans', 'updateprofile'),
				'users'=>array('@'),
			),
      array('allow',
				'actions'=>array('index', 'error', 'contact', 'unautorized', 
            'appappCreateTrialAccount', 'appappCreateNormalAccount', 'appappCancelSubscription',
            'cron', 'copycompanydata', 'appappCheckEmailDuplicate'),
				'users'=>array('*'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
  
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

  /**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
   if(Yii::app()->user->id){
     $model = Company::model()->findByPk(Yii::app()->user->company_id);
  		$this->render('index', array('model'=>$model));
   } else {
     $this->actionLogin();
   }
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
	    if($error=Yii::app()->errorHandler->error)
	    {
	    	if(Yii::app()->request->isAjaxRequest)
	    		echo $error['message'];
	    	else
	        	$this->render('error', $error);
	    }
	}

	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$headers="From: {$model->email}\r\nReply-To: {$model->email}";
				mail(Yii::app()->params['adminEmail'],$model->subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$model=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login()){
        //authenticate to AppApp
        $this->_authenticateToAppApp($model);
        
        
        if(Yii::app()->user->access == 0){
          //first time to login
          Yii::app()->user->setFlash('', 'This is your first login, please change your passoword.');
          
          $connection = Yii::app()->db;
          $command = $connection->createCommand("update users set access=". time() ." where id=" . Yii::app()->user->uid);
          $command->execute();
          
          $this->redirect('/users');
        } else {
          $this->redirect(Yii::app()->user->returnUrl);
        }
      }
		}
		// display the login form
    if(!empty($_REQUEST['ajaxRequest'])){
      $this->renderPartial('login',array('model'=>$model));
    } else {
      $this->render('login',array('model'=>$model));
    }
	}
  
  private function _authenticateToAppApp($model){
    //get appapp_uid
    $connection = Yii::app()->db;
    $command = $connection->createCommand("select appapp_uid from users where email=:email");
    $command->bindParam(':email', $model->username);
    $appapp_uid = $command->queryScalar();
    
    if (!$appapp_uid) {
      return;
    }
    if (!Yii::app()->params['appappToken']) {
      return;
    }
    
    setcookie('appapp_mail', $model->username, time()+86400, '/');
    setcookie('appapp_token', Yii::app()->params['appappToken'], time()+86400, '/');
    setcookie('appapp_authed', '', time()-86400, '/');
    return;
  }

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}

  public function actionSetup()
  {
    $this->render('setup');
  }
  
  /**
   * DB: files $id
   */
  public function actionDownloadFiles($id)
  {
    $files = Files::model()->findByPk($id);
    
    if(!$files->file){
      $this->renderText('The file doesn\'t exist.');
      exit;
    }

    //check access
    $visible_users = unserialize($files->visible_users);
    $visible_depart = unserialize($files->visible_depart);

    $visible = false;
    if(is_array($visible_users) && Yii::app()->user->uid != '' && in_array(Yii::app()->user->uid, $visible_users)){
      $visible = true;
    }
    if(is_array($visible_depart) && Role::userInRole(Yii::app()->user->uid, $visible_depart)){
      $visible = true;
    }

    if(!$visible) {
      $this->renderText('You are not authorized to download this file.');
      exit;
    } else {
      $fileName = $files->file;
      header('Content-Type: application/force-download');
      header('Content-Disposition: attachment; filename='. basename($fileName));
      readfile($fileName);
      exit;
    }
  }
  
  public function actionSearch()
  {
    $searchword = trim($_POST['searchword']);
    if($_POST['searchword'] == ''){
      $this->render('search_noresult');
      exit;
    }
    
    $connection = Yii::app()->db;
    if (!isset($_POST['sModule']) || $_POST['sModule'][0] == 'AllEntities') {
      $sModules = array('Customer', 'Task', 'Inventory', 'Package', 'Template');
    } else {
      $sModules = $_POST['sModule'];
    }

    $searchwordLike = '%'.$searchword.'%';
    foreach ($sModules as $mod) {
      if($mod == 'Customer'){
//        $command = $connection->createCommand("select * from customer where full_legal_name_f like :full_legal_name_f OR full_legal_name_l like :full_legal_name_l order by id desc");
        $command = $connection->createCommand("select * from customer where full_legal_name like :full_legal_name order by id desc");
        $command->bindParam(':full_legal_name', $searchwordLike);
//        $command->bindParam(':full_legal_name_f', $searchwordLike);
//        $command->bindParam(':full_legal_name_l', $searchwordLike);
        $customerDR = $command->query();
        
      } else if ($mod == 'Task') {
        $command = $connection->createCommand("select * from task where subject like :subject");
        $command->bindParam(':subject', $searchwordLike);
        $taskDR = $command->query();
        
      } else if ($mod == 'Inventory') {
        $command = $connection->createCommand("select * from inventory where 
            name like :name 
            or vendor like :vendor");
        $command->bindParam(':name', $searchwordLike);
        $command->bindParam(':vendor', $searchwordLike);
        $inventoryDR = $command->query();
        
      } else if ($mod == 'Package') {
        $command = $connection->createCommand("select * from package where name like :name");
        $command->bindParam(':name', $searchwordLike);
        $packageDR = $command->query();
        
      } else if ($mod == 'Template') {
        $command = $connection->createCommand("select * from template where name like :name");
        $command->bindParam(':name', $searchwordLike);
        $templateDR = $command->query();
      }
    }
    
    
    $this->render('search', array(
        'customerDR'=>$customerDR,
        'taskDR'=>$taskDR,
        'inventoryDR'=>$inventoryDR,
        'packageDR'=>$packageDR,
        'templateDR'=>$templateDR,
    ));
  }
  
  /**
   * Array
    (
        [Action] => Array
            (
                [name] => Array
                    (
                        [photo] => export_1.png
                        [image] => export_2.png
                    )

                [type] => Array
                    (
                        [photo] => image/png
                        [image] => image/png
                    )

                [tmp_name] => Array
                    (
                        [photo] => E:\tools\wamp\tmp\php96EC.tmp
                        [image] => E:\tools\wamp\tmp\php96ED.tmp
                    )

                [error] => Array
                    (
                        [photo] => 0
                        [image] => 0
                    )

                [size] => Array
                    (
                        [photo] => 7418
                        [image] => 3354
                    )

            )

        [idcard] => Array
            (
                [name] => erytrrtyty.png
                [type] => image/png
                [tmp_name] => E:\tools\wamp\tmp\php96EE.tmp
                [error] => 0
                [size] => 117702
            )
    )
   */
  public function actionIphoneUploadFile()
  {
    $connection = Yii::app()->db;
    if($_FILES){
      $command = $connection->createCommand("INSERT INTO iphone (files) VALUES (:files)");
      $command->bindParam(':files', serialize($_FILES));
      $command->execute();
    }
    if($_REQUEST){
      $command = $connection->createCommand("INSERT INTO iphone (files) VALUES (:files)");
      $command->bindParam(':files', serialize($_REQUEST));
      $command->execute();
    }
    
    
    if($_FILES){
//      echo '<pre>';
//      print_r($_FILES);
//      echo '</pre>';
//      exit;
      
      foreach($_FILES as $filekey=>$v)
      {
        if(is_array($v['name']))//multiple
        {
          foreach($_FILES[$filekey]['name'] as $filefield=>$filename){

            if($_FILES[$filekey]['error'][$filefield] > 0) continue;

            //save file
            $fileext = substr($filename, strpos($filename, '.'));
            $serverName = uniqid(time() . '_') . $fileext;
            $destination = 'files/iphone_images/' . $serverName;

            if(move_uploaded_file($_FILES[$filekey]['tmp_name'][$filefield], $destination)){
              $file = new File();
              $file->relate_id = 'iphone##' . $_REQUEST['iPhoneImgCacheID'];
              $file->name = $filename;
              $file->server_name = $serverName;
              $file->timestamp = time();
              $file->save();
            }
          }
        } else {//single
          if($_FILES[$filekey]['error'] > 0) continue;

          //save file
          $fileext = substr($_FILES[$filekey]['name'], strpos($_FILES[$filekey]['name'], '.'));
          $serverName = uniqid(time() . '_') . $fileext;
          $destination = 'files/iphone_images/' . $serverName;

          if(move_uploaded_file($_FILES[$filekey]['tmp_name'], $destination)){
            $file = new File();
            $file->relate_id = 'iphone##' . $_REQUEST['iPhoneImgCacheID'];
            $file->name = $_FILES[$filekey]['name'];
            $file->server_name = $serverName;
            $file->timestamp = time();
            $file->save();
          }
        }
      }
      
      echo 'finished';
      exit;
    }
    
    $this->render('IphoneUploadFile');
  }
  
  public function actionUnautorized(){
    $this->render('access_deny');
  }
  
  public function actionLogintoAppapp()
  {
//    $connection = Yii::app()->db;
//    $command = $connection->createCommand("udpate users set ");
//    $command->bindParam(':total', $totalRecords);
//    $command->execute();
    echo Yii::app()->params['appappSiteURL'] .'/appapp/authenticate_user/?uid='. Yii::app()->user->uid .'&appappToken=' . Yii::app()->params['appappToken'];exit;
    $this->redirect(Yii::app()->params['appappSiteURL'] .'/appapp/authenticate_user/?uid='. Yii::app()->user->uid .'&appappToken=' . Yii::app()->params['appappToken']);
    exit;
  }
  
  /**
   * $data = array(
          'email' => $account->mail,
          'token' => $app->token
      );
   */
  public function actionAppappCheckEmailDuplicate()
  {
    $connection = Yii::app()->db;
    
    $params = serialize($_REQUEST);
    $command = $connection->createCommand("INSERT INTO log_param (params) VALUES (:params)");
    $command->bindParam(':params', $params);
    $command->execute();
    
    
    if ($_POST['token'] != Yii::app()->params['appappToken']) {
      echo 'You are not allowed to access this page.';
      exit;
    }
    
    $command = $connection->createCommand("select count(*) from users where email='yaaman198066@gmail.com'");
    $command->bindParam(':email', $_POST['email']);
    $exists = $command->queryScalar();
    if ($exists) {
      echo json_encode(array('exists'=>1));
    } else {
      echo json_encode(array('exists'=>0));
    }
    
    exit;
  }
  
  public function actionAppappCreateTrialAccount()
  {
    $connection = Yii::app()->db;
    
    $params = serialize($_REQUEST);
    $command = $connection->createCommand("INSERT INTO log_param (params) VALUES (:params)");
    $command->bindParam(':params', $params);
    $command->execute();
    
    
    if ($_POST['token'] != Yii::app()->params['appappToken']) {
      echo 'You are not allowed to access this page.';
      exit;
    }
    
    //create company
    $company = new Company();
    $company->name = $_POST['company'];
    $company->save();
    
    //copy data from model company
    $this->actionCopyCompanyData(16, $company->id);
    
    //create user
    $now = time();
    $user = new Users;
    $user->appapp_uid = $_POST['appapp_uid'];
    $user->username = $this->generateUsername($_POST['username']);
    $user->password = md5($_POST['password']);
    $user->email = $_POST['email'];
    $user->company_id = $company->id;
    $user->firstname = $_POST['firstname'];
    $user->lastname = $_POST['lastname'];
    $user->type = 'admin';
    $user->trial = 1;
    $user->trial_start = $now;
    if($_POST['trial_type'] == 2){//90 days
      $user->trial_end = $now + 86400*90;
    } else {
      $user->trial_end = $now + 86400*30;
    }
    
    if ($user->save()) {
      //save to pre_auth_assignment
      $command = $connection->createCommand('INSERT INTO pre_auth_assignment (itemname, userid) VALUES (\'' . $user->type . '\', :userid)');
      $command->bindParam(':userid', $user->username, PDO::PARAM_STR);
      $command->execute();
    } else {
      //
    }
    
    echo json_encode(array('uid'=>$user->id, 'username'=>$user->email));
    exit;
  }
  
  private function generateUsername($initName) {
    define('USERNAME_MAX_LENGTH', 20);
    
    // Remove possible illegal characters.
    $initName = preg_replace('/[^A-Za-z0-9_.-]/', '', $initName);

    // Trim that value for spaces and length.
    $initName = trim(substr($initName, 0, USERNAME_MAX_LENGTH - 4));
    $name = $initName;
    
    // Make sure we don't hand out a duplicate username.
    $i=1;
    $connection = Yii::app()->db;
    while ($connection->createCommand("select id from users where username = '". $name ."'")->queryScalar() > 0) {
      // If the username got too long, trim it back down.
      if (strlen($name) == USERNAME_MAX_LENGTH) {
        $name = substr($name, 0, USERNAME_MAX_LENGTH - 4);
      }

      // Append a random integer to the name.
//      $name .= rand(0, 9);
      $name =  $initName . $i++;
    }

    return $name;
  }
  
  public function actionAppappplans()
  {
    $this->render('appappplans');
  }
  public function actionUpdateprofile()
  {
    $this->render('app-account-billing');
  }
  
  /**
   * This is not need to run, because in main_normal_zoho.php, there is a calculate to the trial, if trial is expired.
   */
  public function actionCron()
  {
    //user trial for 15 days
//    $connection = Yii::app()->db;
//    $now = time();
//    
//    $connection->createCommand("update users set status=0, trial_end=". $now ."  
//      where trial=1 and trial_end=0 and trial_start+1296000<=". $now)->execute();
//    
//    echo 'Done.';
//    exit;
  }
  
  /**
   * User already have a trial account, here log the user_subscription
   */
  public function actionAppappCreateNormalAccount()
  {
    if ($_POST['token'] != Yii::app()->params['appappToken']) {
      echo 'You are not allowed to access this page.';
      exit;
    }
    
    $connection = Yii::app()->db;
    $now = time();
    
    //user_subscription
    $command = $connection->createCommand("INSERT INTO user_subscription (uid, plan_nid, plan_title, rec_fee, first_start, period_start) 
      VALUES (:uid, :plan_nid, :plan_title, :rec_fee, :first_start, :period_start)");
    $command->bindParam(':uid', $_POST['uid']);
    $command->bindParam(':plan_nid', $_POST['plan_nid']);
    $command->bindParam(':plan_title', $_POST['plan_title']);
    $command->bindParam(':rec_fee', $_POST['rec_fee']);
    $command->bindParam(':first_start', $now);
    $command->bindParam(':period_start', $now);
    $command->execute();
    
    //users
    $command = $connection->createCommand("update users set status=1, trial=0, trial_end=:trial_end where id=:id");
    $command->bindParam(':trial_end', $now);
    $command->bindParam(':id', $_POST['uid']);
    $command->execute();
    
    echo 'Done';
    exit;
  }
  
  
  /**
   * User already have a trial account, here log the user_subscription
   */
  public function actionAppappCancelSubscription()
  {
    if ($_POST['token'] != Yii::app()->params['appappToken']) {
      echo 'You are not allowed to access this page.';
      exit;
    }
    
    $connection = Yii::app()->db;
    $now = time();
    
    //user_subscription
    $command = $connection->createCommand("update user_subscription SET cancel=1, cancel_timestamp=:cancel_timestamp 
      where uid=:uid");
    $command->bindParam(':cancel_timestamp', $now);
    $command->bindParam(':uid', $_POST['uid']);
    $command->execute();
    
    //users
    $command = $connection->createCommand("update users set status=0 where id=:id");
    $command->bindParam(':id', $_POST['uid']);
    $command->execute();
    
    echo 'Done';
    exit;
  }
  
  public function actionCopyCompanyData($modelCompanyID=0, $newCompanyID=0)
  {
    //TODO: set this to private function after golive.
    $connection = Yii::app()->db;
    
    //inventory
    $oldNewInventory= array();
    $command = $connection->createCommand("select * from inventory where company_id=".$modelCompanyID);
    $dr = $command->query();
    while ($row = $dr->read()) {
      $oldID = $row['id'];
      unset($row['id']);
      $row['company_id'] = $newCompanyID;
      
      $obj = new Inventory;
      foreach ($row as $key=>$value) {
        $obj->$key = $value;
      }
      $obj->save();
      $oldNewInventory['old_' . $oldID] = $obj->id;
    }
    
    //template
    $oldNewTemplate = array();
    $command = $connection->createCommand("select * from template where company_id=".$modelCompanyID);
    $dr = $command->query();
    while ($row = $dr->read()) {
      $oldID = $row['id'];
      unset($row['id']);
      $row['company_id'] = $newCompanyID;
      
      $obj = new Template;
      foreach ($row as $key=>$value) {
        $obj->$key = $value;
      }
      $obj->save();
      
      $oldNewTemplate['old_' . $oldID] = $obj->id;
    }
    
    
    
    //customer
    $command = $connection->createCommand("select * from customer where company_id=".$modelCompanyID);
    $dr = $command->query();
    $oldNewProduct = array();
    while ($row = $dr->read()) {
      $customer_id = $row['id'];
      unset($row['id']);
      $row['company_id'] = $newCompanyID;
      
      $obj = new Customer;
      foreach ($row as $key=>$value) {
        $obj->$key = $value;
      }
      $obj->save();
      
      if ($obj->id) {
        //product
        $command2 = $connection->createCommand("select * from product where customer_id=".$customer_id);
        $dr2 = $command2->query();
        while ($row2 = $dr2->read()) {
          $oldID = $row2['id'];
          unset($row2['id']);
          $row2['company_id'] = $newCompanyID;
          $row2['customer_id'] = $obj->id;
          $row2['inventory_id'] = $oldNewInventory['old_' . $row2['inventory_id']];
      
          $obj2 = new Product();
          foreach ($row2 as $key=>$value) {
            $obj2->$key = $value;
          }
          $obj2->save();
          $oldNewProduct['old_' . $oldID] = $obj->id;
        }
        
        
        //document
        $command2 = $connection->createCommand("select * from document where customer_id=".$customer_id);
        $dr2 = $command2->query();
        while ($row2 = $dr2->read()) {
          unset($row2['id']);
          $row2['customer_id'] = $obj->id;
          $row2['template_id'] = $oldNewTemplate['old_' . $row2['template_id']];
          $row2['product_id'] = $oldNewProduct['old_' . $row2['product_id']];
      
          $obj2 = new Document();
          foreach ($row2 as $key=>$value) {
            $obj2->$key = $value;
          }
          $obj2->save();
        }
        
        //payment
        $command2 = $connection->createCommand("select * from payment where customer_id=".$customer_id);
        $dr2 = $command2->query();
        while ($row2 = $dr2->read()) {
          unset($row2['id']);
          $row2['customer_id'] = $obj->id;
      
          $obj2 = new Payment();
          foreach ($row2 as $key=>$value) {
            $obj2->$key = $value;
          }
          $obj2->save();
        }
      }
    }
    
    //task
    $command = $connection->createCommand("select * from task where company_id=".$modelCompanyID);
    $dr = $command->query();
    while ($row = $dr->read()) {
      unset($row['id']);
      $row['company_id'] = $newCompanyID;
      
      $obj = new Task;
      foreach ($row as $key=>$value) {
        $obj->$key = $value;
      }
      $obj->save();
    }
    
    
    
    //no contacts
    
    //no notes
    
//    echo 'done';
  }
  
}