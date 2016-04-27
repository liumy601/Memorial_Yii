<?php

class AdminController extends Controller {

  public function filters() {
    return array('accessControl');
  }

  public function accessRules() {
    return array(
        array('allow',
            'actions' => array('index', 'homepage', 'file', 'user', 'emailconfig', 'dropdown', 'logo', 'getdropdownfirstval', 'savedropdownlist','optionalfields', 'taxByCompany'),
            'roles' => array('admin'),
        ),
        array('allow',
            'actions' => array('company', 'companystaff', 'emailconfig', 'taxconfig'),
            'roles' => array('super admin'),
        ),
        array('deny', // deny all userss
            'users' => array('*'),
        ),
    );
  }

  private function _buildShortcuts() {
    if (Yii::app()->user->type == 'super admin') {
      Yii::app()->params['subMenu'] = array(
      );
    } else if (Yii::app()->user->type == 'admin') {//company admin
      Yii::app()->params['subMenu'] = array(
          array('text' => 'Home Page', 'url' => '/admin/homepage', 'noajax' => true),
          array('text' => 'User', 'url' => '/admin/user'),
          array('text' => 'Drop Downs', 'url' => '/admin/dropdown'),
          array('text' => 'Logo', 'url' => '/admin/logo'),
          array('text' => 'Files', 'url' => '/admin/file'),
          array('text' => 'Email Configuration', 'url' => '/admin/emailconfig'),
		  array('text' => 'Optional Fields', 'url' => '/admin/optionalFields'),
		   array('text' => 'Tax', 'url' => '/admin/taxByCompany'),
      );
    }
  }

  public function actionIndex() {
    $this->_buildShortcuts();
    $this->actionCompany();
  }

  public function actionCompany($op = '', $id = '') {
    $this->_buildShortcuts();

    switch ($op) {
      case 'create':
        return $this->_companyCreate();
        break;

      case 'update':
        return $this->_companyUpdate($id);
        break;

      case 'delete':
        return $this->_companyDelete($id);
        break;

      default:
        $this->_companyIndex();

        break;
    }
  }

  private function _companyIndex() {
    $connection = Yii::app()->db;
    $command = $connection->createCommand("SELECT * FROM company ORDER BY id");
    $dataProvider = $command->query();

    $this->render('company', array(
        'dataProvider' => $dataProvider,
    ));
  }

  private function _companyCreate() {
    $model = new Company();

    if (isset($_POST['Company'])) {
      $model->attributes = $_POST['Company'];

      //check company name exists
      $connection = Yii::app()->db;
      $command = $connection->createCommand("select count(*) from company where name=:name");
      $command->bindParam(':name', $model->name);
      $exists = $command->queryScalar();
      if ($exists) {
        echo '<script language="javascript">
            window.parent.showTip("Company name already exists, please fill in a different one.", true);
            </script>';
        exit;
      }

      $file = CUploadedFile::getInstance($model, 'logo');
      if ($file) {
        $fileType = $file->getType();
        $allFileTypes = array('image/jpeg', 'image/gif', 'image/png');
        if (!in_array($fileType, $allFileTypes)) {
          echo '<script language="javascript">
              window.parent.showTip("The logo format should be png, gif, jpg.", true);
              </script>';
          exit;
        }
        $filename = $file->getName();
        $filepath = CommonFunc::getUploadFileSavePath($filename);
        $file->saveAs($filepath);
        $model->logo = $filepath;
      }

      if ($model->save()) {
        echo '<script language="javascript">
            window.parent.ajaxNew("/admin/company");
            </script>';
        exit;
      }
    }

    $this->render('company_form', array('model' => $model));
  }

  private function _companyUpdate($id) {
    $model = $this->loadModel('Company', $id);

    if (isset($_POST['Company'])) {
      if ($_POST['Company']['button'] == 'Cancel') {
        $this->redirect(array('company'));
      } else {
        $logo = $model->logo;
        $model->attributes = $_POST['Company'];
        $model->logo = $logo;

        //check company name exists
        $connection = Yii::app()->db;
        $command = $connection->createCommand("select id from company where name=:name");
        $command->bindParam(':name', $model->name);
        $exists = $command->queryScalar();
        if ($exists && $exists != $model->id) {//other company has this name
          echo '<script language="javascript">
              window.parent.showTip("Company name already exists, please fill in a different one.", true);
              </script>';
          exit;
        }

        $file = CUploadedFile::getInstance($model, 'logo');
        if ($file) {
          $fileType = $file->getType();
          $allFileTypes = array('image/jpeg', 'image/gif', 'image/png');
          if (!in_array($fileType, $allFileTypes)) {
            Yii::app()->user->setFlash('', 'The logo format should be png, gif, jpg.');
            $this->render('company_create', array('model' => $model));
            return;
          }
          $filename = $file->getName();
          $filepath = CommonFunc::getUploadFileSavePath($filename);
          $file->saveAs($filepath);
          $model->logo = $filepath;
        }

        if ($model->save()) {
          echo '<script language="javascript">
              window.parent.ajaxNew("/admin/company");
              </script>';
          exit;
        }
      }
    }

    $this->render('company_form', array(
        'model' => $model,
    ));
  }

  private function _companyDelete($id) {
    $this->loadModel('Company', $id)->delete();
    $this->_companyIndex();
  }

  /**
   * super admin view all company staffs
   */
  public function actionCompanyStaff() {
    $this->_buildShortcuts();

    $connection = Yii::app()->db;
    $command = $connection->createCommand("SELECT * FROM users WHERE type='staff' ORDER BY lastname, firstname");
    $dataProvider = $command->query();

    $this->render('company_staff', array(
        'dataProvider' => $dataProvider,
    ));
  }

  /**
   *  Config homepage
   */
  public function actionHomepage() {
    $this->_buildShortcuts();

    $model = Company::model()->findByPk(Yii::app()->user->company_id);
    if ($_POST['homepage']) {
      $model->homepage = $_POST['homepage'];
      $model->save();
//      Yii::app()->user->setFlash('', 'Saved.');
      echo '<script language="javascript">
            showTip("Saved.");
            </script>';
    }
    $this->render('homepage', array('model' => $model));
  }

  public function actionUser($op = '', $id = '') {
    $this->_buildShortcuts();
    switch ($op) {
      case 'create':
        return $this->_userCreate();
        break;

      case 'update':
        return $this->_userUpdate($id);
        break;

      case 'delete':
        return $this->_userDelete($id);
        break;

      default:
        $this->_userIndex();

        break;
    }
  }

  private function _userIndex() {
    $connection = Yii::app()->db;

    if (Yii::app()->user->type == 'super admin') {
      $command = $connection->createCommand("SELECT * FROM users WHERE type='admin' ORDER BY id");
    } else if (Yii::app()->user->type == 'admin') {
      $command = $connection->createCommand("SELECT * FROM users WHERE type='staff' and company_id='" . Yii::app()->user->company_id . "' ORDER BY id");
    }
    $dataProvider = $command->query();

    $this->render('user', array(
        'dataProvider' => $dataProvider,
    ));
  }

  private function _userCreate() {
    $connection = Yii::app()->db;
    $model = new Users;
    $now = time();

    if (isset($_POST['Users'])) {
      $model->attributes = $_POST['Users'];
      $model->password = $model->hashPassword($_POST['Users']['password']);

      if (Yii::app()->user->type == 'super admin') {
        $model->type = 'admin';
      } else if (Yii::app()->user->type == 'admin') {
        $model->company_id = Yii::app()->user->company_id;
        $model->type = 'staff';
      }

      if ($model->save()) {
        //save to pre_auth_assignment
        $connection = Yii::app()->db;
        $command = $connection->createCommand('INSERT INTO pre_auth_assignment (itemname, userid) VALUES (\'' . $model->type . '\', :userid)');
        $command->bindParam(':userid', $_POST['Users']['username'], PDO::PARAM_STR);
        $command->execute();

        //create a user in AppApp
        $newUser = array(
            'name' => $model->username,
            'pass' => $_POST['Users']['password'],
            'mail' => $model->email,
            'site' => Yii::app()->params['siteURL'],
            'appappToken' => Yii::app()->params['appappToken'],
            'app_uid' => $model->id,
        );
        $ch1 = curl_init();
        curl_setopt($ch1, CURLOPT_URL, Yii::app()->params['appappSiteURL'] . "/appapp/create_user");
        curl_setopt($ch1, CURLOPT_POST, 1);
        curl_setopt($ch1, CURLOPT_POSTFIELDS, http_build_query($newUser));
        curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
        $r = curl_exec($ch1);
        if (!curl_errno($ch1)) {
          $connection->createCommand('update users set appapp_uid=' . (int) $r . ', trial=1, trial_start=' . $now . ', trial_end=' . ($now + 86400 * 30) . ' where id=' . $model->id)->execute();
        } else {
          //log error
        }
        curl_close($ch1);

        $this->_userIndex();
      } else {
        $model->password = $_POST['Users']['password'];
      }
    }

    $this->render('user_form', array(
        'model' => $model,
    ));
  }

  private function _userUpdate($id) {
    $connection = Yii::app()->db;
    $model = $this->loadModel('Users', $id);
    $password = $model->password;
    $model->password = ''; //can't show the password on edit page
    $now = time();

    if (isset($_POST['Users'])) {
      if ($_POST['Users']['button'] == 'Cancel') {
        $this->_userIndex();
      } else {
        $model->attributes = $_POST['Users'];

        if ($_POST['Users']['password'] != '') {
          $model->password = $model->hashPassword($_POST['Users']['password']);
        } else {
          $model->password = $password;
        }

//        $model->firstname = $_POST['Users']['firstname'];
        if ($model->save()) {
          $connection = Yii::app()->db;
          $connection->createCommand("delete from pre_auth_assignment where userid='". $model->username ."'")->execute();
          $command = $connection->createCommand('INSERT INTO pre_auth_assignment (itemname, userid) VALUES (\'' . $model->type . '\', :userid)');
          $command->bindParam(':userid', $_POST['Users']['username'], PDO::PARAM_STR);
          $command->execute();
        
          if (!$model->appapp_uid) {
            //create a user in AppApp
            $newUser = array(
                'name' => $model->username,
                'pass' => $model->username,
                'mail' => $model->email,
                'site' => Yii::app()->params['siteURL'],
                'appappToken' => Yii::app()->params['appappToken'],
                'app_uid' => $model->id,
            );
            $ch1 = curl_init();
            curl_setopt($ch1, CURLOPT_URL, Yii::app()->params['appappSiteURL'] . "/appapp/create_user");
            curl_setopt($ch1, CURLOPT_POST, 1);
            curl_setopt($ch1, CURLOPT_POSTFIELDS, http_build_query($newUser));
            curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
            $r = curl_exec($ch1);

            if (!curl_errno($ch1)) {
              $connection->createCommand('update users set appapp_uid=' . (int) $r . ', trial=1, trial_start=' . $now . ', trial_end=' . ($now + 86400 * 30) . ' where id=' . $model->id)->execute();
            } else {
              
            }
            curl_close($ch1);
          }

          $this->_userIndex();
        } else {
          $model->password = $_POST['Users']['password'];
        }
      }
    }

    //get all roles
    $roles = array();
//    $command = $connection->createCommand("SELECT * FROM role_user WHERE uid=".$id." ORDER BY rid");
//    $dt = $command->query();
//    while($row = $dt->read()){
//      $roles[$row['rid']] = $row['rid'];
//    }

    $this->render('user_form', array(
        'model' => $model,
        'roles' => $roles,
    ));
  }

  private function _userDelete($id) {
    $this->loadModel('Users', $id)->delete();
    $this->_userIndex();
  }

  public function loadModel($type, $id) {
    if ($type == 'Company') {
      $model = Company::model()->findByPk((int) $id);
    } else if ($type == 'Users') {
      $model = Users::model()->findByPk((int) $id);
    } else if ($type == 'customer') {
      $model = Customer::model()->findByPk((int) $id);
    } else if ($type == 'Role') {
      $model = Role::model()->findByPk((int) $id);
    } else {
      $model = null;
    }

    if ($model === null)
      throw new CHttpException(404, 'The requested object does not exist.');
    return $model;
  }

  public function actionRole($op = '', $id = '') {
    $this->_buildShortcuts();

    switch ($op) {
      case 'view':
        return $this->_roleView($id);
        break;

      case 'create':
        return $this->_roleCreate();
        break;

      case 'update':
        return $this->_roleUpdate($id);
        break;

      case 'delete':
        return $this->_roleDelete($id);
        break;

      case 'addUser':
        return $this->_roleAddUser();
        break;

      case 'saveUserRel':
        return $this->_roleSaveUserRel();
        break;

      case 'deleteUser':
        return $this->_roleDeleteUser();
        break;

      case 'savePermission':
        return $this->_roleSavePermission();
        break;

      default:
        $this->_roleIndex();

        break;
    }
  }

  private function _roleIndex() {
    $connection = Yii::app()->db;
    $command = $connection->createCommand("SELECT * FROM role ORDER BY id");
    $dataProvider = $command->query();

    $this->render('role', array(
        'dataProvider' => $dataProvider,
    ));
  }

  private function _roleView($id) {
    $model = $this->loadModel('Role', $id);

    //users
    $connection = Yii::app()->db;
    $command = $connection->createCommand("select u.*, s.name as company from users u, role_user ru, company s where u.id=ru.uid and u.company_id=s.id and ru.rid=:rid order by u.username");
    $command->bindParam(':rid', $id);
    $dr = $command->query();
    $users = array();
    while ($row = $dr->read()) {
      $users[] = $row;
    }

    //perm
    $command = $connection->createCommand("select * from role_perm where rid=:rid");
    $command->bindParam(':rid', $id);
    $dr = $command->query();
    $perm = array();
    while ($row = $dr->read()) {
      $perm[$row['op']] = array('view' => $row['view'], 'edit' => $row['edit'], 'delete' => $row['delete']);
    }

    $this->render('role_view', array(
        'model' => $model,
        'users' => $users,
        'perm' => $perm
    ));
  }

  private function _roleCreate() {
    $model = new Role();

    if (isset($_POST['Role'])) {
      $model->attributes = $_POST['Role'];

      //check role name exists
      $connection = Yii::app()->db;
      $command = $connection->createCommand("select count(*) from role where name=:name");
      $command->bindParam(':name', $model->name);
      $exists = $command->queryScalar();
      if ($exists) {
        Yii::app()->user->setFlash('', 'Role name already exists, please fill in a different one.');
        $this->render('role_form', array('model' => $model));
        return;
      }

      if ($model->save()) {
        echo $this->redirect(array('/admin/role?ajaxRequest=1'));
        exit;
      }
    }

    $this->render('role_form', array('model' => $model));
  }

  private function _roleUpdate($id) {
    $model = $this->loadModel('Role', $id);

    if (isset($_POST['Role'])) {
      $model->attributes = $_POST['Role'];

      //check role name exists
      $connection = Yii::app()->db;
      $command = $connection->createCommand("select id from role where name=:name");
      $command->bindParam(':name', $model->name);
      $exists = $command->queryScalar();
      if ($exists && $exists != $model->id) {
        Yii::app()->user->setFlash('', 'Role name already exists, please fill in a different one.');
        $this->render('role_form', array('model' => $model));
        return;
      }

      if ($model->save()) {
        echo $this->redirect(array('/admin/role?ajaxRequest=1'));
        exit;
      }
    }

    $this->render('role_form', array(
        'model' => $model,
    ));
  }

  private function _roleDelete($id) {
    $this->loadModel('Role', $id)->delete();
    $this->_roleIndex();
  }

  public function _roleSavePermission() {
    $connection = Yii::app()->db;
    $rid = $_POST['rid'];

    if ($_POST) {
      $connection->createCommand("delete from role_perm where rid=" . $_POST['rid'])->execute();

      foreach ($_POST as $op => $chkValues) {
        if ($op == 'rid')
          continue;

        $view = (int) $chkValues['view'];
        $edit = (int) $chkValues['edit'];
        $delete = (int) $chkValues['delete'];

        //check exists
        $command = $connection->createCommand("select * from role_perm where rid=:rid and op=:op");
        $command->bindParam(':rid', $rid);
        $command->bindParam(':op', $op);
        $exists = $command->queryRow();

        if (!$exists['rid']) {
          $command = $connection->createCommand("insert into role_perm values (:rid, :op, :view, :edit, :delete)");
          $command->bindParam(':rid', $rid);
          $command->bindParam(':op', $op);
          $command->bindParam(':view', $view);
          $command->bindParam(':edit', $edit);
          $command->bindParam(':delete', $delete);
          $command->execute();
        } else if ($view != $exists['view'] || $edit != $exists['edit'] || $delete != $exists['delete']) {
          $command = $connection->createCommand("update role_perm set view=:view, edit=:edit, delete=:delete where rid=:rid and op=:op");
          $command->bindParam(':view', $view);
          $command->bindParam(':edit', $edit);
          $command->bindParam(':delete', $delete);
          $command->bindParam(':rid', $rid);
          $command->bindParam(':op', $op);
          $command->execute();
        }
      }
    }

    echo '<script language="javascript">
        window.parent.showTip("Saved.", true);
        </script>';
    exit;
  }

  private function _roleAddUser() {
    $searchForm = new UserSearchForm();
    $condition = array();
    $rid = $_GET['rid'];

    if (isset($_POST['UserSearchForm'])) {
      if ($_POST['clear'] == '1') {
        unset($_POST['UserSearchForm']);
      } else {
        $searchForm->attributes = $_POST['UserSearchForm'];
      }
      if ($_POST['UserSearchForm']['firstname']) {
        $condition[] = "firstname like '%" . $_POST['UserSearchForm']['firstname'] . "%'";
      }
      if ($_POST['UserSearchForm']['lastname']) {
        $condition[] = "lastname like '%" . $_POST['UserSearchForm']['lastname'] . "%'";
      }
      if ($_POST['UserSearchForm']['email'] != '') {
        $condition[] = "email like '%" . $_POST['UserSearchForm']['email'] . "%'";
      }
      if ($_POST['UserSearchForm']['company'] != '') {
        $condition[] = "s.id='" . $_POST['UserSearchForm']['company'] . "'";
      }
    }

    if ($condition) {
      $condition = ' AND ' . implode(' AND ', $condition);
    } else {
      $condition = '';
    }

    $_POST['pageSize'] = (int) $_POST['pageSize'];
    $pageSize = $_POST['pageSize'] ? $_POST['pageSize'] : 10;
    $query = "SELECT u.*, s.name as company FROM users u, company s WHERE u.company_id=s.id " . $condition . '
      and not exists (select * from role_user ru where u.id=ru.uid and ru.rid=' . $rid . ') 
      ORDER BY u.username';

    $dataProvider = CommonFunc::pagerQuery($query, $pageSize, null);

    $this->renderPartial('role_adduser', array(
        'dataProvider' => $dataProvider,
        'searchForm' => $searchForm,
        'pageSize' => $pageSize,
        'rid' => $rid
    ));
  }

  private function _roleSaveUserRel() {
    if (!$_GET['rid'] || !$_GET['uid']) {
      exit;
    }
    $connection = Yii::app()->db;
    $command = $connection->createCommand("REPLACE INTO role_user VALUES (:rid, :uid)");
    $command->bindParam(':rid', $_GET['rid']);
    $command->bindParam(':uid', $_GET['uid']);
    $command->execute();
    echo 'Saved.';
    exit;
  }

  private function _roleDeleteUser() {
    if (!$_GET['rid'] || !$_GET['uid']) {
      exit;
    }
    $connection = Yii::app()->db;
    $command = $connection->createCommand("DELETE FROM role_user WHERE rid=:rid and uid=:uid");
    $command->bindParam(':rid', $_GET['rid']);
    $command->bindParam(':uid', $_GET['uid']);
    $command->execute();

    $this->_roleView($_GET['rid']);
    exit;
  }

  /**
   *  Config dropdown
   */
  public function actionDropDown($op = '', $module = '', $dropdownname = '', $label = '') {
    $this->_buildShortcuts();

    if ($op == 'update') {
      return $this->_dropdownUpdate($module, $dropdownname, $label);
    } else {
      $this->render('dropdown', array(
          'company_id' => Yii::app()->user->company_id,
      ));
    }
  }

  /**
    Action
    Department     Type     Building

    Student
    Department     Type

    Product
    Type     Location     Permissions     Condition     Status

    Product
    State     Make     Model     Location
   */
  private function _dropdownUpdate($module, $dropdownname, $label) {
    if ($_POST) {
      $validOptions = array();
      if ($_POST['newdropdown']) {
        $newDropDowns = explode("\n", $_POST['newdropdown']);
        if ($newDropDowns) {
          foreach ($newDropDowns as $opt) {
            if ($opt != '') {
              $validOptions[] = $opt;
            }
          }
        }
      }

      /**
       * http://chili.preferati.net/issues/1790
        client defined dropdown
        removed the 'other' option.
        Matthew  11:05:10
        oh
        I think other should be always there, not part of the client defined options
       */
      if (strpos($dropdownname, 'newspaperradio') !== false && !in_array('Other', $validOptions)) {
        $validOptions[] = 'Other';
      }

      if ($validOptions) {
        $options = implode("\n", $validOptions);
      } else {
        $options = '';
      }

      $connection = Yii::app()->db;
      $command = $connection->createCommand("SELECT id FROM dropdown WHERE module=:module AND name=:name AND company_id=:company_id");
      $command->bindParam(':module', $module);
      $command->bindParam(':name', $dropdownname);
      $command->bindParam(':company_id', Yii::app()->user->company_id);
      $id = $command->queryScalar();

//            echo $id; exit;
      if ($id) {
        $command2 = $connection->createCommand("UPDATE dropdown SET options=:options WHERE id=:id");
        $command2->bindParam(':options', $options);
        $command2->bindParam(':id', $id);
        $command2->query();
      } else {
        $command3 = $connection->createCommand("INSERT INTO dropdown (module, name, company_id, options) VALUES (:module, :name, :company_id, :options)");
        $command3->bindParam(':module', $module);
        $command3->bindParam(':name', $dropdownname);
        $command3->bindParam(':company_id', Yii::app()->user->company_id);
        $command3->bindParam(':options', $options);
        $command3->query();
      }

//      Yii::app()->user->setFlash('form', 'Saved.');
      echo '<script language="javascript">
            showTip("Saved.");
            </script>';
    }

//        $dropDown = DropDown::getSelfDefineOptions($module, $dropdownname);
    $dropDown = DropDown::getSelfDefineOptions($module, $dropdownname);
    $defaultFunction = 'default' . $module . $dropdownname;
    $dropDownDefault = DropDown::$defaultFunction();

    $this->render('dropdown_form', array('module' => $module, 'dropdownname' => $dropdownname, 'label' => $label, 'dropDown' => $dropDown, 'dropDownDefault' => $dropDownDefault));
  }

  public function actionLogo() {
    $this->_buildShortcuts();
    $company_id = Yii::app()->user->company_id;
    $model = Company::model()->findByPk($company_id);

    if (isset($_FILES['Company'])) {
      $file = CUploadedFile::getInstance($model, 'logo');
      if ($file) {
        $fileType = $file->getType();
        $allFileTypes = array('image/jpeg', 'image/gif', 'image/png');
        if (!in_array($fileType, $allFileTypes)) {
          Yii::app()->user->setFlash('', 'The logo format should be png, gif, jpg.');
          $this->render('logo_form', array('model' => $model));
          return;
        }
        $filename = $file->getName();
        $filepath = CommonFunc::getUploadFileSavePath($filename);
        $file->saveAs($filepath);
        $model->logo = $filepath;
      }

      if ($model->save()) {
        echo '<script language="javascript">
                  window.parent.ajaxNew("/admin/logo");
                  </script>';
        exit;
      }
    }

    $this->render('logo_form', array(
        'model' => $model
    ));
  }

  public function actionEmailConfig() {
    $this->_buildShortcuts();

    $company_id = Yii::app()->user->company_id;
    $emailConfig = EmailConfig::load($company_id);


    if ($_POST) {
      $emailConfig->attributes = $_POST['EmailConfig'];
      $emailConfig->company_id = $company_id;
      $emailConfig->save();
      Yii::app()->user->setFlash('', 'Saved.');
    }

    if (!$emailConfig->smtp_port) {
      $emailConfig->smtp_port = 25;
    }

    $this->render('email_config', array('model' => $emailConfig));
  }

  public function actionOptionalFields() {
    $this->_buildShortcuts();

    $company_id = Yii::app()->user->company_id;
    $model = OptionalFields::model()->find('company_id='. $company_id);
	$model = empty($model) ? new OptionalFields() : $model;

    if ($_POST) {
      $model->attributes = $_POST['OptionalFields'];
      $model->company_id = $company_id;
      $model->save();
      Yii::app()->user->setFlash('', 'Saved.');
    }

    $this->render('optional_fields', array('model' => $model));
  }

  public function actionTaxByCompany() {
    $this->_buildShortcuts();

    $company_id = Yii::app()->user->company_id;
    $model = Config::model()->find('name="tax_by_company" and company_id='. $company_id);
	$model = empty($model) ? new Config() : $model;
	$model->name = 'tax_by_company';
	$model->value  = unserialize($model->value);

    if ($_POST) {
      $model->attributes = $_POST['Config'];
      $model->company_id = $company_id;
      $model->save();
	  $model->value = unserialize($model->value);
      Yii::app()->user->setFlash('', 'Saved.');
    }

    $this->render('tax_by_company', array('model' => $model));
  }

  public function actionTaxConfig() {
    $this->_buildShortcuts();

    $config = Config::load('tax');

    if ($_POST) {
      $config->attributes = $_POST['Config'];
      $config->save();
      $config->value = unserialize($config->value);
      Yii::app()->user->setFlash('', 'Saved.');
    }

    $this->render('config', array('model' => $config));
  }

  /**
   * customer can only update/delete own staff
   */
//  public function actionMyFile(){
//    $this->render('file', array('dataProvider' => $dataProvider);
//  }

  private function _fileAccessFilter(Files $model) {
    if ($model->company_id != Yii::app()->user->company_id) {
      Yii::app()->file->setFlash('', 'This file isn\'t your school file.');
      $this->redirect(array('/admin/file?ajaxRequest=1'));
    }
  }

  private function _fileUpdate($id) {
    $connection = Yii::app()->db;
    $model = Files::model()->findByPk((int) $id);
    $this->_fileAccessFilter($model);
    $model->visible_users = unserialize($model->visible_users);
    $model->visible_depart = unserialize($model->visible_depart);

    if (isset($_POST['Files'])) {
      $model->name = $_POST['Files']['name'];
      $model->visible_users = serialize($_POST['Files']['visible_users']);
      $model->visible_depart = serialize($_POST['Files']['visible_depart']);

      //save file
      $file = CUploadedFile::getInstance($model, 'file');
      if ($file) {
        $filename = $file->getName();
        $filepath = CommonFunc::getUploadFileSavePath($filename);
        $file->saveAs($filepath);
        $model->filename = $filename;
        $model->file = $filepath;
      }

      if ($model->save()) {
        //save departments to files_role
        if (isset($_POST['Files']['visible_depart']) && $_POST['Files']['visible_depart']) {
          $connection->createCommand("delete from files_role where fid=" . $model->id)->execute();

          foreach ($_POST['Files']['visible_depart'] as $rid) {
            if ($rid == '') {
              continue;
            }
            $command = $connection->createCommand("INSERT INTO files_role (fid, rid) VALUES (:fid, :rid)");
            $command->bindParam(':fid', $model->id);
            $command->bindParam(':rid', $rid);
            $command->execute();
          }
        }

        echo '<script language="javascript">
            window.parent.ajaxNew("/admin/file");
            </script>';
        exit;
      }
    }

    $this->render('file_form', array(
        'model' => $model
    ));
  }

  private function _fileDelete($id) {
    Files::model()->findByPk((int) $id)->delete();
    $this->redirect(array('/admin/file?ajaxRequest=1'));
  }

  public function actionMyFiles($return = 0) {
    $connection = Yii::app()->db;

    $pageSize = 5;
    $query = "SELECT f.* FROM files f WHERE f.company_id=" . Yii::app()->user->company_id . " AND (f.visible_users like '%\"" . Yii::app()->user->uid . "\"%' 
      OR (exists (select * from files_role fr, role_user ru where f.id=fr.fid and fr.rid=ru.rid and ru.uid=" . Yii::app()->user->uid . "))) ORDER BY f.name";
    $filesDR = CommonFunc::pagerQuery($query, $pageSize, null);

    $list = $this->renderPartial('//admin/myfiles', array(
        'filesDR' => $filesDR
            ), $return); //ajax request to refresh, this will output directly

    if ($return) {//show for first time
      return $list;
    }
  }

  /*   * ** files module *** */

  /**
   * Files list
   */
  public function actionFile($op = '', $id = '') {
    $this->_buildShortcuts();
    switch ($op) {
      case 'create':
        return $this->_fileCreate();
        break;

      case 'update':
        return $this->_fileUpdate($id);
        break;

      case 'delete':
        return $this->_fileDelete($id);
        break;

      default:
        $this->_fileIndex();

        break;
    }
  }

  private function _fileIndex() {
    $connection = Yii::app()->db;
    $command = $connection->createCommand("SELECT * FROM files WHERE company_id=:company_id ORDER BY id");
    $command->bindParam(':company_id', Yii::app()->user->company_id);
    $dataProvider = $command->query();

    $this->render('file', array(
        'dataProvider' => $dataProvider,
    ));
  }

  private function _fileCreate() {
    $connection = Yii::app()->db;
    $model = new Files;

    if (isset($_POST['Files'])) {
      $model->name = $_POST['Files']['name'];
      $model->visible_users = serialize($_POST['Files']['visible_users']);
      $model->visible_depart = serialize($_POST['Files']['visible_depart']);
      $model->enteredby = Yii::app()->user->id;
      $model->timestamp = time();

      //save file
      $file = CUploadedFile::getInstance($model, 'file');
      if ($file) {
        $filename = $file->getName();
        $filepath = CommonFunc::getUploadFileSavePath($filename);
        $file->saveAs($filepath);
        $model->filename = $filename;
        $model->file = $filepath;
      }

      if ($model->save()) {
        //save departments to files_role
        if (isset($_POST['Files']['visible_depart']) && $_POST['Files']['visible_depart']) {
          foreach ($_POST['Files']['visible_depart'] as $rid) {
            if ($rid == '') {
              continue;
            }
            $command = $connection->createCommand("INSERT INTO files_role (fid, rid) VALUES (:fid, :rid)");
            $command->bindParam(':fid', $model->id);
            $command->bindParam(':rid', $rid);
            $command->execute();
          }
        }

        echo '<script language="javascript">
            window.parent.ajaxNew("/admin/file");
            </script>';
        exit;
      }
    }

    $this->render('file_form', array(
        'model' => $model,
    ));
  }

}
