<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class InventoryController extends Controller {

  public function filters() {
    return array(
        'accessControl'
    );
  }

  public function accessRules() {
    return array(
        array('allow',
            'actions' => array('index', 'create', 'update', 'view', 'delete', 'lookup'),
            'roles' => array('admin', 'staff'),
        ),
        array('deny',
            'users' => array('*'),
        ),
    );
  }

  private function _buildShortcuts() {
    $subMenu = array();
    $subMenu[] = array('text' => 'New Inventory', 'url' => '/inventory/create');
    Yii::app()->params['subMenu'] = $subMenu;
  }

  public function actionIndex() {
    $this->_buildShortcuts();

    $condition = array();
    $inventory = new Inventory();

    if (isset($_POST)) {
      if ($_POST['clear'] == 1) {
        unset($_POST);
      } else {
        $inventory->attributes = $_POST['Inventory'];
      }

     // if ($_POST['Inventory']['full_name']) {
      //  $condition[] = "name like '%" . $_POST['Inventory']['full_name'] . "%'";
      //}

	  if ($_POST['Inventory']['vendor']) {
        $condition[] = "vendor like '%" . $_POST['Inventory']['vendor'] . "%'";
      }
    }

    $where = 'WHERE company_id=' . Yii::app()->user->company_id;
    if ($condition) {
      $where .= ' and ' . implode(' AND ', $condition);
    }

    $_POST['pageSize'] = (int) $_POST['pageSize'];
    $pageSize = $_POST['pageSize'] ? $_POST['pageSize'] : 10;
    $query = "SELECT * FROM inventory " . $where . ' ORDER BY id DESC';
    $dataProvider = CommonFunc::pagerQuery($query, $pageSize, null);


    $connection = Yii::app()->db;
    $command = $connection->createCommand("select * from package where company_id=:company_id order by name");
    $command->bindParam(':company_id', Yii::app()->user->company_id);
    $packageDataProvider = $command->query();


    $this->render('index', array(
        'inventory' => $inventory,
        'dataProvider' => $dataProvider,
        'pageSize' => $pageSize,
        'packageDataProvider' => $packageDataProvider,
    ));
  }

  public function actionCreate() {
    $this->_buildShortcuts();

    if (isset($_POST['Inventory'])) {
      $model = new Inventory();
      $model->attributes = $_POST['Inventory'];
      $model->enteredby = Yii::app()->user->id;
      $model->enteredtm = time();
      $model->save();

      Yii::app()->user->setFlash('', 'Inventory is saved.');
      $this->redirect(array('view', 'id' => $model->id));
    }

    $model = new Inventory();
    $this->render('_form', array('model' => $model));
  }

  public function actionUpdate($id) {
    $this->_buildShortcuts();
    $model = $this->loadModel($id);

    if (isset($_POST['Inventory'])) {
      if ($_POST['Inventory']['button'] == 'Cancel') {
        $this->redirect(array('view', 'id' => $model->id));
      } else {
        $model->attributes = $_POST['Inventory'];

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
    $this->actionIndex();
  }

  public function actionView($id, $print = false) {
    if ($print) {
      Yii::app()->params['print'] = true;
    }
    $this->_buildShortcuts();

    $this->render('view', array(
        'model' => $this->loadModel($id),
    ));
  }

  public function loadModel($id) {
    $model = Inventory::model()->findByPk((int) $id);
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
    $inventory = new Inventory();

    if (isset($_POST)) {
      if ($_POST['clear'] == 1) {
        unset($_POST);
      } else {
        $inventory->attributes = $_POST['Inventory'];
      }

      if ($_POST['Inventory']['name']) {
        $condition[] = "name like '%" . $_POST['Inventory']['name'] . "%'";
      }
    }

    $where = 'WHERE company_id=' . Yii::app()->user->company_id;
    if ($condition) {
      $where .= ' and ' . implode(' AND ', $condition);
    }

    $_POST['pageSize'] = (int) $_POST['pageSize'];
    $pageSize = $_POST['pageSize'] ? $_POST['pageSize'] : 10;
    $query = "SELECT * FROM inventory " . $where . ' ORDER BY id DESC';
    $dataProvider = CommonFunc::pagerQuery($query, $pageSize, null);

    $this->renderPartial('lookup', array(
        'inventory' => $inventory,
        'dataProvider' => $dataProvider,
        'pageSize' => $pageSize
    ));
  }

}
