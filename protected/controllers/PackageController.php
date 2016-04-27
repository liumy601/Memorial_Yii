<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class PackageController extends Controller {

  public function filters() {
    return array(
        'accessControl'
    );
  }

  public function accessRules() {
    return array(
        array('allow',
            'actions' => array('index', 'create', 'update', 'view', 'delete', 'lookup', 'addproduct', 'deleteproduct'),
            'roles' => array('admin', 'staff'),
        ),
        array('deny',
            'users' => array('*'),
        ),
    );
  }

  private function _buildShortcuts() {
    $subMenu = array();
    $subMenu[] = array('text' => 'New Package', 'url' => '/package/create');
    Yii::app()->params['subMenu'] = $subMenu;
  }

  public function actionCreate() {
    $this->_buildShortcuts();

    if (isset($_POST['Package'])) {
      $model = new Package();
      $model->attributes = $_POST['Package'];
      $model->company_id = Yii::app()->user->company_id;
      $model->save();

      Yii::app()->user->setFlash('', 'Package is saved.');
      $this->redirect(array('view', 'id' => $model->id));
    }

    $model = new Package();
    $this->render('_form', array('model' => $model));
  }

  public function actionUpdate($id) {
    $this->_buildShortcuts();
    $model = $this->loadModel($id);

    if (isset($_POST['Package'])) {
      if ($_POST['Package']['button'] == 'Cancel') {
        $this->redirect(array('view', 'id' => $model->id));
      } else {
        $model->attributes = $_POST['Package'];
        $model->company_id = Yii::app()->user->company_id;
        
        if ($model->save())
          $this->redirect(array('view', 'id' => $model->id));
      }
    }

    $this->render('_form', array(
        'model' => $model,
    ));
  }

  public function actionDelete($id) {
    $this->loadModel($id)->delete();
    $this->redirect(array('/inventory?ajaxRequest=1'));
  }

  public function actionView($id, $print = false) {
    if ($print) {
      Yii::app()->params['print'] = true;
    }
    $this->_buildShortcuts();
    
    $connection = Yii::app()->db;
    $command = $connection->createCommand("select i.* from package_product pp, inventory i
      where pp.inventory_id=i.id and pp.package_id=:package_id and i.company_id=:company_id order by i.name");
    $command->bindParam(':package_id', $id);
    $command->bindParam(':company_id', Yii::app()->user->company_id);
    $productDataProvider = $command->query();
	
	$config = Config::model()->find('name="tax_by_company" and company_id='. Yii::app()->user->company_id);
	$tax = unserialize($config->value);

    $this->render('view', array(
        'model' => $this->loadModel($id),
        'productDataProvider' => $productDataProvider,
		'tax'=>$tax,
    ));
  }

  public function loadModel($id) {
    $model = Package::model()->findByPk((int) $id);
    if ($model === null)
      throw new CHttpException(404, 'The requested page does not exist.');
    return $model;
  }

  public function actionFollowUp($return = 0) {
    $connection = Yii::app()->db;

    $pageSize = 5;
    $query = "SELECT * FROM action WHERE company_id=" . Yii::app()->user->company_id . " AND assigned_to=" . Yii::app()->user->uid . " AND follow_up=1 AND resolved=0 ORDER BY subject";
    $followupDR = CommonFunc::pagerQuery($query, $pageSize, null);

    $list = $this->renderPartial('//action/followup', array(
        'followupDR' => $followupDR
            ), $return); //ajax request to refresh, this will output directly

    if ($return) {//show for first time
      return $list;
    }
  }

  public function actionLookup() {
    $condition = array();
    $package = new Package();

    if (isset($_POST)) {
      if ($_POST['clear'] == 1) {
        unset($_POST);
      } else {
        $package->attributes = $_POST['Package'];
      }

      if ($_POST['Package']['name']) {
        $condition[] = "name like '%" . $_POST['Package']['name'] . "%'";
      }
    }

    $where = 'WHERE company_id=' . Yii::app()->user->company_id;
    if ($condition) {
      $where .= ' and ' . implode(' AND ', $condition);
    }

    $_POST['pageSize'] = (int) $_POST['pageSize'];
    $pageSize = $_POST['pageSize'] ? $_POST['pageSize'] : 10;
    $query = "SELECT * FROM package " . $where . ' ORDER BY id DESC';
    $dataProvider = CommonFunc::pagerQuery($query, $pageSize, null);

    $this->renderPartial('lookup', array(
        'package' => $package,
        'dataProvider' => $dataProvider,
        'pageSize' => $pageSize
    ));
  }
  
  public function actionAddProduct($id)
  {
    $package = $this->loadModel($id);
    
    if ($_POST['products']) {
      foreach ($_POST['products'] as $inventory_id) {
        $packageProduct = new PackageProduct();
        $packageProduct->package_id = $id;
        $packageProduct->inventory_id = $inventory_id;
        $packageProduct->save();
      }
      $this->redirect('/package/view/'.$id.'?ajaxRequest=1');
    }
    
    $this->render('addproduct', array(
        'package'=>$package
    ));
  }
  
  public function actionDeleteProduct($package_id, $inventory_id)
  {
    $package = $this->loadModel($package_id);
    
    if ($package->company_id == Yii::app()->user->company_id) {
      $connection = Yii::app()->db;
      $command = $connection->createCommand("delete from package_product where package_id=:package_id and inventory_id=:inventory_id");
      $command->bindParam(':package_id', $package_id);
      $command->bindParam(':inventory_id', $inventory_id);
      $command->execute();
    }
    
    $this->actionView($package_id);
  }

}
