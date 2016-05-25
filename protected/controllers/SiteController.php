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
				'actions'=>array('logout', 'downloadFiles', 'search', 'iphoneuploadfile', 'logintoappapp'),
				'users'=>array('@'),
			),
      array('allow',
				'actions'=>array('index', 'error', 'contact', 'unautorized'),
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
    
    $newUser = array(
      'uid' => $appapp_uid,
      'appappToken' => Yii::app()->params['appappToken']
    );
    
    
    $gacookie = "curl-1.txt";
    @touch($gacookie);
    @chmod($gacookie, 0666);
    if ($fp = tmpfile()) {
      $ch1 = curl_init(Yii::app()->params['appappSiteURL'] . "/appapp/authenticate_user");
      curl_setopt ($ch1, CURLOPT_STDERR, $fp);
      curl_setopt($ch1,CURLOPT_HEADER, 1); 
      curl_setopt ($ch1, CURLOPT_VERBOSE, 2);
      curl_setopt ($ch1, CURLOPT_ENCODING, 0);
      curl_setopt ($ch1, CURLOPT_USERAGENT, 'Mozilla/5.0');
      curl_setopt ($ch1, CURLOPT_COOKIEJAR, $gacookie);
      curl_setopt ($ch1, CURLOPT_COOKIEFILE, $gacookie);
      curl_setopt ($ch1, CURLOPT_POSTFIELDS, http_build_query($newUser));
      curl_setopt ($ch1, CURLOPT_POST, 1);
      curl_setopt ($ch1, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt ($ch1, CURLOPT_FAILONERROR, 1);
      curl_setopt ($ch1, CURLOPT_CONNECTTIMEOUT, 30);
      $r = curl_exec ($ch1);
        
      if(!curl_errno($ch1) && strpos($r, 'Set-Cookie: SESS') !== false){
        $setcookiePos = strpos($r, 'Set-Cookie');
        $fPos = strpos($r, ';', $setcookiePos);
        $cookie = substr($r, $setcookiePos+12, $fPos-$setcookiePos-12);
        
        list($sess, $sessval) = explode('=', $cookie);
        if (substr($sess, 0, 4) == 'SESS') {
          setcookie('appapp_sess_id', $sess, time()+86400, '/');
          setcookie('appapp_sess_val', $sessval, time()+86400, '/');
        }
      }
      curl_close($ch1);
      
    }
//    @unlink($gacookie);
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
  
}