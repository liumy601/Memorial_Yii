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
            'cron', 'copycompanydata', 'appappCheckEmailDuplicate','resetPassword','resetVerify','setpwd'),
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
    if($_REQUEST['ajaxRequest']){
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
    
    if (!appapp_uid) {
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

  public function actionResetPassword() {
	$message = '';

	if(isset($_POST['email_addr'])) {
		//check if user exists
		$email_addr = $_POST['email_addr'];
		$user = Users::model()->find('email="'. $email_addr .'"');
		
		if(!empty($user)) {
			//send email
			$token = base64_encode(time());

			$mail = new PHPMailer();
			$mail->IsSMTP();
			$mail->Port = 587;
			$mail->SMTPSecure = 'tls';
			$mail->Host = 'smtp.sendgrid.net';
			$mail->SMTPAuth = true;
			$mail->Username = 'funeralappmail'; 
			$mail->Password = 'memorial1@#';
			$mail->CharSet = "utf-8";
			$mail->Encoding = "base64"; 
			$mail->SetFrom('Success@memorialdirector.com', 'Memorial Director');
			$mail->AddAddress($email_addr);
			$mail->AddBCC('ives.matthew@gmail.com');
			$mail->Subject = 'Reset Your Password';
			$mail->Body = '<html><body>';
			$mail->Body .= $user->lastname .' '. $user->firstname . ',<br/><br/>';
			$mail->Body .= "We've received a request to change your password for app.memorialdirectory.com.<br/><br/>";
			$mail->Body .= 'Please click here to reset your password: <br/>';
			$mail->Body .= '<a href="http://app.memorialdirector.com/site/resetVerify/email/'. $email_addr .'/token/'. $token .'">http://app.memorialdirector.com/site/resetVerify/email/'. $email_addr .'/token/'. $token .'</a><br/>';
			$mail->Body .= '</body></html>';
			$mail->IsHTML(true);
			$mail->Send();

			$message = 'Password reset instructions will be mailed to '. $email_addr .'. You must log out to use the password reset link in the email.';
		} else {
			$message = 'That email address does not match our records. Please contact us for assistance.';
		}
	}
	
	$this->render('reset_password', array('message'=>$message));
  }

  public function actionResetVerify($email, $token) {
	$illegal = '';
	$success = '';
	$model = new ResetPassword();

	$user = Users::model()->find('email="'. $email .'"');
	$time_diff = time()-base64_decode($token);
	if(empty($user)) {
		$illegal = 'This is a invalid user.';
	} else if($time_diff > 24*60*60) {
		$illegal = 'This password reset link has expired, please reset again';
	}

	if(isset($_POST['ResetPassword'])) {
		$model->attributes = $_POST['ResetPassword'];

		if($model->validate()) {
			$user->password = md5($model->password);
			$user->save(false);
			$success = 'Your password is reset. <a href="http://app.memorialdirector.com/site/login">Login</a>';
		}
	}

	$this->render('reset_verify', array('model'=>$model, 'illegal'=>$illegal, 'success'=>$success));
  }

  public function actionSetpwd($token) {
	$params = json_decode(base64_decode($token), true);
	if(count($params)<6) {
		$illegal = true;
		$model = new Users;
	} else {
		$illegal = false;
		$model = Users::model()->findByPk($params[5]);
	}
	
	$model->password = '';

	if(isset($_POST['Users'])) {
		$model->attributes = $_POST['Users'];

		if($model->validate()) {
			$userName = $params[0];
			$yourName = $params[1];
			$yourCompany = $params[2];
			$yourEmail = $params[3];
			$yourPhone = $params[4];
			
			$currentCompany = Company::model()->find('name="Your Funeral Home"');

			//create new company
			$newCompany = new Company();
			$newCompany->name = !empty($yourCompany) ? $yourCompany : $yourName;
			$newCompany->phone = $yourPhone;
			$newCompany->save(false);
			$company_id = $newCompany->id;
			
			$password = $model->password;
			$model->password = md5($model->password);
			$model->company_id = $company_id;
			$model->save(false);

			//copy all records to the new company
			if(!empty($currentCompany)) {
				//copy all decedents
				$customers = Customer::model()->findAll('company_id='. $currentCompany->id);
				foreach($customers as $cust) {
					$newCust = new Customer();
					foreach($cust->metaData->columns as $col) {
						$colName = $col->name;
						if(!in_array($colName, array('id','company_id')))
								$newCust->{$colName} = $cust->{$colName};
					}
					$newCust->company_id = $company_id;

					//autopopulate case_number
					$command = Yii::app()->db->createCommand("select distinct case_number from customer where company_id=". $company_id ."  order by case_number");
					$records = $command->queryAll();
					//search next available case_number starting from 1000
					$case_number_list = array();
					foreach($records as $record) {
						$case_number_list[] = $record['case_number'];
					}
					$case_number_list = array_unique($case_number_list);
					$next_case_num = 1000;
					while(in_array($next_case_num, $case_number_list)) {
						$next_case_num++;
					}
					$newCust->case_number = $next_case_num;
					$newCust->save(false);

					$newCustId = $newCust->id;

					//copy products
					$products = Product::model()->findAll('company_id='. $currentCompany->id .' and customer_id='. $cust->id);
					foreach($products as $prod) {
						$newProd = new Product();
						foreach($prod->metaData->columns as $col) {
							$colName = $col->name;
							if(!in_array($colName, array('id','customer_id','company_id')))
									$newProd->{$colName} = $prod->{$colName};
						}
						$newProd->customer_id = $newCustId;
						$newProd->company_id = $company_id;
						$newProd->save(false);
					}

					//copy payments
					$payments = Payment::model()->findAll('customer_id='. $cust->id);
					foreach($payments as $pmt) {
						$newPmt = new Payment();
						foreach($pmt->metaData->columns as $col) {
							$colName = $col->name;
							if(!in_array($colName, array('id','customer_id')))
									$newPmt->{$colName} = $pmt->{$colName};
						}
						$newPmt->customer_id = $newCustId;
						$newPmt->save(false);
					}
					
					//copy documents
					$documents = Document::model()->findAll('customer_id='. $cust->id);
					foreach($documents as $doc) {
						$newDoc = new Document();
						foreach($doc->metaData->columns as $col) {
							$colName = $col->name;
							if(!in_array($colName, array('id','customer_id')))
									$newDoc->{$colName} = $doc->{$colName};
						}
						$newDoc->customer_id = $newCustId;
						$newDoc->save(false);
					}

					//copy contacts
					$contacts = Contact::model()->findAll('company_id='. $currentCompany->id .' and customerid='. $cust->id);
					foreach($contacts as $cnt) {
						$newCnt = new Contact();
						foreach($cnt->metaData->columns as $col) {
							$colName = $col->name;
							if(!in_array($colName, array('id','customerid','company_id')))
									$newCnt->{$colName} = $cnt->{$colName};
						}
						$newCnt->customerid = $newCustId;
						$newCnt->company_id = $company_id;
						$newCnt->save(false);
					}

					//copy notes
					$notes = Notes::model()->findAll('parent_type="customer" and parent_id='. $cust->id);
					foreach($notes as $note) {
						$newNotes = new Notes();
						foreach($note->metaData->columns as $col) {
							$colName = $col->name;
							if(!in_array($colName, array('id')))
									$newNotes->{$colName} = $note->{$colName};
						}
						$newNotes->parent_id = $newCustId;
						$newNotes->save(false);
					}
				}

				//copy all templates
				$templates = Template::model()->findAll('company_id='. $currentCompany->id);
				foreach($templates as $tpl) {
					$newTpl = new Template();
					foreach($tpl->metaData->columns as $col) {
						$colName = $col->name;
						if(!in_array($colName, array('id','company_id')))
							$newTpl->{$colName} = $tpl->{$colName};
					}
					$newTpl->company_id = $company_id;
					$newTpl->save(false);
					
					//update template_id in templates
					Document::model()->updateAll(array('template_id'=>$newTpl->id), 'template_id='. $tpl->id);
				}

				//copy all inventory
				$inventories = Inventory::model()->findAll('company_id='. $currentCompany->id);
				foreach($inventories as $invt) {
					$newInvt = new Inventory();
					foreach($invt->metaData->columns as $col) {
						$colName = $col->name;
						if(!in_array($colName, array('id','company_id')))
								$newInvt->{$colName} = $invt->{$colName};
					}
					$newInvt->company_id = $company_id;
					$newInvt->save(false);

					//update inventory_id in products
					Product::model()->updateAll(array('inventory_id'=>$newInvt->id), 'inventory_id='. $invt->id .' and company_id=' .$company_id);
				}

				//copy all packages
				$packages = Package::model()->findAll('company_id='. $currentCompany->id);
				foreach($packages as $pkg) {
					$newPkg = new Package();
					foreach($pkg->metaData->columns as $col) {
						$colName = $col->name;
						if(!in_array($colName, array('id','company_id')))
								$newPkg->{$colName} = $pkg->{$colName};
					}
					$newPkg->company_id = $company_id;
					$newPkg->save(false);
					//bypass the Save function in Package model
					$newPkg->saveAttributes(array('company_id'=>$company_id));

					//copy package products
					$products = PackageProduct::model()->findAll('package_id='. $pkg->id);
					foreach($products as $prod) {
						$newProd = new PackageProduct();
						$newProd->package_id = $newPkg->id;
						$newProd->inventory_id = $prod->inventory_id;
						$newProd->save(false);
					}
				}

				//copy all tasks
				$tasks = Task::model()->findAll('company_id='. $currentCompany->id);
				foreach($tasks as $task) {
					$newTask = new Task();
					foreach($task->metaData->columns as $col) {
						$colName = $col->name;
						if(!in_array($colName, array('id','company_id')))
								$newTask->{$colName} = $task->{$colName};
					}
					$newTask->company_id = $company_id;
					$newTask->save(false);
				}
			}

			//login and redirect
			$loginForm = new LoginForm;
			$loginForm->username = $model->email;
			$loginForm->password = $password;
			if($loginForm->validate() && $loginForm->login()){
				//authenticate to AppApp
				$this->_authenticateToAppApp($loginForm);

				//first time to login
				Yii::app()->user->setFlash('', 'This is your first login, please change your passoword.');
				$connection = Yii::app()->db;
				$command = $connection->createCommand("update users set access=". time() ." where id=" . Yii::app()->user->uid);
				$command->execute();

				$this->redirect('site/index');
			}
		}
	}

	$this->render('set_pwd', array('model'=>$model, 'illegal'=>$illegal, 'success'=>$success));
  }

  private function _authenticateToAppApp($model){
		//get appapp_uid
		$connection = Yii::app()->db;
		$command = $connection->createCommand("select appapp_uid from users where email=:email");
		$command->bindParam(':email', $model->username);
		$appapp_uid = $command->queryScalar();
		
		if (!appapp_uid) {
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

}