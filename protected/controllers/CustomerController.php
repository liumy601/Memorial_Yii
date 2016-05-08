<?php
class CustomerController extends Controller
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
				'actions'=>array('index', 'create', 'update', 'view', 'delete', 'lookup', 'createnewcustomer', 'updatenewcustomer', 'viewnewcustomer', 'deletenewcustomer',
    			'addproduct', 'deleteproduct', 'addpayment', 'addcredit', 'deletepayment', 'editpayment', 'addpackage','viewpackage', 'adddocument','editproduct', 'deletedocument', 
         'documentdownload', 'documentdownloadword', 'documentchangeemailaddr', 'documentsendemail', 'documentsendemailconfirm', 'editretail', 'redirectToBaidu','customersexport',
         'getpayment', 'printreceipt', 'refreshProductList'),
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
    $subMenu[] = array('text'=>'New Decedent', 'url'=>'/customer/create');
    $subMenu[] = array('text'=>'New Decedent Form', 'url'=>'/customer/createnewcustomer');
    Yii::app()->params['subMenu'] = $subMenu;
	}
  
  public function __construct($id,$module=null)
	{
    if (Yii::app()->user->name != 'Guest' && in_array($id, array('customer', 'task', 'inventory', 'template'))) {
      $connection = Yii::app()->db;
      $trial = $connection->createCommand("select trial, trial_end from users where id=" . Yii::app()->user->uid)->queryRow();
      if ($trial['trial'] == 1 && $trial['trial_end'] > 0 && time() >= $trial['trial_end']) {
        echo '<div style="margin:30px 0;">Your trial has Expired, can\'t perform this action.</div>';
        exit;
      } 
    }
    
		parent::__construct($id, $module);
	}
  
	public function actionIndex()
	{
    $this->_buildShortcuts();
    
    $condition = array();
    $condition[] = "status like '%Active%'";
    
    $customer = new Customer();

    if(isset($_POST)){
      if($_POST['clear'] == 1){
        unset($_POST);
      } else {
        $customer->attributes = $_POST['Customer'];
      }
      
//      if ($_POST['Customer']['full_legal_name_f']) {
//        $condition[] = "(
//              full_legal_name_f like '%" . $_POST['Customer']['full_legal_name_f'] . "%'
//                OR 
//              full_legal_name_m like '%" . $_POST['Customer']['full_legal_name_f'] . "%'
//                OR 
//              full_legal_name_l like '%" . $_POST['Customer']['full_legal_name_f'] . "%'
//            )";
//      }
      
      if ($_POST['Customer']['full_legal_name']) {
        $condition[] = "full_legal_name like '%" . $_POST['Customer']['full_legal_name'] . "%'";
      }
      
      if ($_POST['Customer']['date_of_death']) {
        $condition[] = "date_of_death = '" . $_POST['Customer']['date_of_death'] . "'";
      }
      
      if ($_POST['Customer']['case_number']) {
        $condition[] = "case_number like '%" . $_POST['Customer']['case_number'] . "%'";
      }
      
      if ($_POST['Customer']['status']) {
        array_shift($condition);
        $condition[] = "status like '%" . $_POST['Customer']['status'] . "%'";
      }
      
    }
    
    $where = 'WHERE company_id='. Yii::app()->user->company_id;
    if ($condition) {
      $where .= ' and ' . implode(' AND ', $condition);
    }
    
    $_POST['pageSize'] = (int)$_POST['pageSize'];
    $pageSize = $_POST['pageSize'] ? $_POST['pageSize'] : 10;
    $query = "SELECT * FROM customer " . $where . ' ORDER BY id DESC';
    $dataProvider = CommonFunc::pagerQuery($query, $pageSize, null);
    
		$this->render('index',array(
			'customer' => $customer,
			'dataProvider' => $dataProvider,
      'pageSize' => $pageSize
		));
	}
  
  public function _checkMembershipCapacity($uid=0)
  {
    if (!$uid) {
      $uid = Yii::app()->user->uid;
    }
    
    $connection = Yii::app()->db;
    $current_plan = $connection->createCommand("select plan_title from user_subscription where uid=" . $uid ." order by id desc limit 1")->queryScalar();
    
    switch ($current_plan) {
      case 'Standard':
        $capacity = 75;
        break;
      case 'Professional':
        $capacity = 200;
        break;
      case 'Enterprise':
        $capacity = 500;
        break;

      default:
        $capacity = 25;
        break;
    }

    return $capacity;
  }
  
  public function _checkAllowCreateNew()
  {
    /**http://chili.preferati.net/issues/2344
     * 
     * Don't lock account for too many customers (Bug #2344)
     * For now we will not enforce the customer limits in the system. 
     * We'll contact the customers directly and ask them to upgrade. 
     * Please suspend this functionality for now, let them have as many customers as they want. 
     * I will contact them directly if they go over and ask for upgrade.
     */
    return true;
    
//    $connection = Yii::app()->db;
//    
//    $thisYear = date('Y', time());
//    $thisYearStart = strtotime($thisYear.'-01-01 00:00:00');
//    $thisYearEnd = strtotime($thisYear.'-12-31 23:59:59');
//    
//    $command = $connection->createCommand("select count(*) from customer where company_id=:company_id and enteredtm>=:start and enteredtm<=:end");
//    $command->bindParam(':company_id', Yii::app()->user->company_id);
//    $command->bindParam(':start', $thisYearStart);
//    $command->bindParam(':end', $thisYearEnd);
//    $total = $command->queryScalar();
//    $capacity = $this->_checkMembershipCapacity();
//    if ($total > $capacity) {
//      echo '<div style="padding:60px 40px;">Your capacity is ' . $capacity .' customers, you can\'t create more. To create more customers, please <a class="noajax" href="/site/updateprofile">upgrade your membership</a>.</div>';
//      exit;
//    }
  }
  
  public function actionCreate()
	{
    $this->_buildShortcuts();
    $this->_checkAllowCreateNew();
    $optionFields = OptionalFields::model()->find('company_id='. Yii::app()->user->company_id);
	$optionFields = empty($optionFields) ? new OptionalFields() : $optionFields;
    $model = new Customer();

	//autopopulate case_number
	$command = Yii::app()->db->createCommand("select distinct case_number_seq from customer order by case_number_seq");
    $records = $command->queryAll();
	//search next available case_number starting from 1000
	$case_number_seq_list = array();
	foreach($records as $record) {
		$case_number_seq_list[] = $record['case_number_seq'];
	}
	$case_number_seq_list = array_unique($case_number_seq_list);
	$next_case_num = 1000;
	while(in_array($next_case_num, $case_number_seq_list)) {
		$next_case_num++;
	}
	$model->case_number = $next_case_num;
	$model->case_number_seq = $next_case_num;

    if (isset($_POST['Customer'])) {
      $model->attributes = $_POST['Customer'];
      $model->form_type = 'old';
      $model->enteredby = Yii::app()->user->id;
      $model->enteredtm = time();
      $model->company_id = Yii::app()->user->company_id;
      
      if($_FILES['Customer']['name']['deceased_photo'] != ''){
        if($_FILES['Customer']['error']['deceased_photo'] > 0) {
          echo '<span style="color: #f00;">Upload file error!</span>';
          return;
        }else{
          move_uploaded_file($_FILES['Customer']['tmp_name']['deceased_photo'], 'files/photo/'.$_FILES['Customer']['name']['deceased_photo']);
          $model->deceased_photo = 'files/photo/'.$_FILES['Customer']['name']['deceased_photo'];
        }
      } 

      if($model->save()){
        Yii::app()->user->setFlash('', 'Customer is saved.');
        $this->redirect(array('view','id'=>$model->id));
      }
    }

    $this->render('_form', array('model'=>$model, 'optionFields'=>$optionFields));
	}
  
  public function actionUpdate($id)
  {
    $this->_buildShortcuts();
	$optionFields = OptionalFields::model()->find('company_id='. Yii::app()->user->company_id);
	$optionFields = empty($optionFields) ? new OptionalFields() : $optionFields;
    $model=$this->loadModel($id);

    if(isset($_POST['Customer']))
    {
      if ($_POST['Customer']['button'] == 'Cancel') {
        $this->redirect(array('view','id'=>$model->id));
      } else {
        unset($_POST['Customer']['deceased_photo']);
        $model->attributes=$_POST['Customer'];
        $model->updatedby = Yii::app()->user->id;
        $model->updatedtm = time();
        $model->company_id = Yii::app()->user->company_id;
        if($_FILES){
          if($_FILES['Customer']['error']['deceased_photo'] == 0) {
            move_uploaded_file($_FILES['Customer']['tmp_name']['deceased_photo'], 'files/photo/'.$_FILES['Customer']['name']['deceased_photo']);
            $model->deceased_photo = 'files/photo/'.$_FILES['Customer']['name']['deceased_photo'];
          }
        }  
        
		//update case_number_seq to be used for create
		if(preg_match('/\d{4,}/i', $model->case_number))
			$model->case_number_seq = $model->case_number;
		else
			$model->case_number_seq = NULL;

        if($model->save()){
          $this->redirect(array('view','id'=>$model->id));
        }
     }
    }
    
    $this->render('_form',array(
        'model'=>$model,
		'optionFields'=>$optionFields,
    ));
  }
  
  public function actionDelete($id)
  {
    $this->loadModel($id)->delete();
    $this->actionIndex();
  }
  
  public function actionView($id, $print=false)          //$id is the customer id
  {
    if ($print) {
      Yii::app()->params['print'] = true;
    }
    $this->_buildShortcuts();
    $optionFields = OptionalFields::model()->find('company_id='. Yii::app()->user->company_id);
	$optionFields = empty($optionFields) ? new OptionalFields() : $optionFields;

    $connection = Yii::app()->db;
    
    //contacts
//    $command = $connection->createCommand("select * from contact where customerid=:customerid order by full_name");
    $command1 = $connection->createCommand("select * from contact where customerid=:customerid and company_id=:company_id order by full_name");
    $command1->bindParam(':customerid', $id);
    $command1->bindParam(':company_id', Yii::app()->user->company_id);
    $contactDataProvider = $command1->query();
    
    //products
    $command2 = $connection->createCommand("select i.*, p.id as product_id, ifnull(p.product_retail, i.retail) as retail from product p, inventory i
      where p.inventory_id=i.id and p.customer_id=:customer_id and p.company_id=i.company_id and p.company_id=:company_id order by i.name");
//    $command2 = $connection->createCommand("select i.*, p.id as product_id, ifnull(p.product_retail, i.retail) as retail from product p, inventory i
//      where p.inventory_id=i.id and p.customer_id=:customer_id and p.company_id=i.company_id and p.company_id=:company_id order by i.name");
    $command2->bindParam(':customer_id', $id);
    $command2->bindParam(':company_id', Yii::app()->user->company_id);
    $productDataProvider = $command2->query();
    
    //$total_payments
    $command3 = $connection->createCommand("select sum(amount) from payment where customer_id=:customer_id and type='payment'");
    $command3->bindParam(':customer_id', $id);
    $total_payments = $command3->queryScalar();
    $total_payments = $total_payments != '' ||  $total_payments != null ? $total_payments : '0';
    
    
        
    //$tatal_balances
    $command4 = $connection->createCommand("select sum(amount) from payment where customer_id=:customer_id and type != 'payment'");
    $command4->bindParam(':customer_id', $id);
    $tatal_balances = $command4->queryScalar();
    $total_balances = $total_balances != '' ||  $total_balances != null ? $total_balances : '0';
    
    //$payments
    $command5 = $connection->createCommand("select * from payment where customer_id=:customer_id order by id desc");
    $command5->bindParam(':customer_id', $id);
    $paymentDataProvider = $command5->query();
    
    //documents
    $command6=$connection->createCommand("select d.id, d.product_id, d.email_address_alt,
          t.id as template_id, t.name,t.email_address
      from template t
      left join document d on (d.template_id=t.id and d.customer_id=:customer_id)
      where default_check=1 and deleted=0 and company_id=:company_id order by t.name");
    $command6->bindParam(':customer_id',$id);  
    $command6->bindParam(':company_id',Yii::app()->user->company_id);  
    $documentDataProvider = $command6->query();
    $documents = array();
    while ($row = $documentDataProvider->read()) {
      $documents[$row['template_id']] = $row;
      
      //check if this exists in {document}
      $existsInDocument = $connection->createCommand("select count(*) from document where template_id=".$row['template_id']." and customer_id=".$id)->queryScalar();
      if (!$existsInDocument) {
        $connection->createCommand("insert into document (template_id, customer_id) values (".$row['template_id'].", $id)")->execute();
      }
    }
    //now find from products related templates
//    $command7 = $connection->createCommand("SELECT t.id as template_id, i.name as product_name, t.name, t.email_address
//      FROM product p, inventory i, template t
//      WHERE p.customer_id=:customer_id AND p.inventory_id=i.id AND i.template_id=t.id AND i.template_id>0 
//      ORDER BY t.name");
    $command7 = $connection->createCommand("SELECT t.id as template_id, i.name as product_name, t.name, t.email_address
      FROM product p, inventory i, template t
      WHERE p.customer_id=:customer_id AND p.inventory_id=i.id AND i.template_id=t.id AND i.template_id>0
            AND t.default_check=1  AND t.company_id = :company_id AND t.deleted = 0
      ORDER BY t.name");
    $command7->bindParam(':customer_id',$id);  
    $command7->bindParam(':company_id', Yii::app()->user->company_id);  
    $productTemplatesDataProvider = $command7->query();
    while ($row = $productTemplatesDataProvider->read()) {
      if (!array_key_exists($row['template_id'], $documents)) {
        $documents[$row['template_id']] = $row;
      }
    }

    //discounts
    $sql = "select sum(amount) from payment where customer_id= :customer_id and type='credit'";
    $command8 = $connection->createCommand($sql);
    $command8->bindParam(':customer_id', $id);
    $discount = $command8->queryScalar();

	$taxRate = Config::loadTaxByCompany(Yii::app()->user->company_id);
    
    $this->render('view',array(
        'model'=>$this->loadModel($id),
        'contactDataProvider'=>$contactDataProvider,
        'productDataProvider'=>$productDataProvider,
        'total_payments'=>$total_payments,
        'discount'=>$discount,
        'tatal_balances'=>$tatal_balances,
//        'productRetail'=>$productRetail,
        'paymentDataProvider'=>$paymentDataProvider,
        'documents'=>$documents,
		'optionFields'=>$optionFields,
		'taxRate'=>$taxRate,
    ));
  }
  
  public function loadModel($id)
  {
    $model=Customer::model()->findByPk((int)$id);
    $model->loadAddon();
    
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
  
  public function actionLookup()
  {
    $condition = array();
    $customer = new Customer();

    if(isset($_POST)){
      if($_POST['clear'] == 1){
        unset($_POST);
      } else {
        $customer->attributes = $_POST['Customer'];
      }
      
//      if ($_POST['Customer']['full_legal_name_f']) {
      if ($_POST['Customer']['full_legal_name']) {
//        $condition[] = "(
//              full_legal_name_f like '%" . $_POST['Customer']['full_legal_name_f'] . "%'
//                OR 
//              full_legal_name_m like '%" . $_POST['Customer']['full_legal_name_f'] . "%'
//                OR 
//              full_legal_name_l like '%" . $_POST['Customer']['full_legal_name_f'] . "%'
//            )";
        $condition[] = "full_legal_name like '%" . $_POST['Customer']['full_legal_name'] . "%'";
      } 
    }
    
    $where = 'WHERE company_id='. Yii::app()->user->company_id;
    if ($condition) {
      $where .= ' and ' . implode(' AND ', $condition);
    }
    
    $_POST['pageSize'] = (int)$_POST['pageSize'];
    $pageSize = $_POST['pageSize'] ? $_POST['pageSize'] : 10;
    $query = "SELECT * FROM customer " . $where . ' ORDER BY id DESC';
    $dataProvider = CommonFunc::pagerQuery($query, $pageSize, null);
    
		$this->renderPartial('lookup',array(
      'customer' => $customer,
      'dataProvider' => $dataProvider,
      'pageSize' => $pageSize
		));
  }
  
  public function actionCreateNewCustomer(){
    $this->_buildShortcuts();
    $model = new Customer();
    $this->_checkAllowCreateNew();

    if(isset($_POST['Customer'])){
      $model->attributes = $_POST['Customer'];
      
      if($model->validate()){
        $model->form_type = 'new';
        $model->enteredby = Yii::app()->user->id;
        $model->enteredtm = time();
        $model->save();
        
        if ($_POST['autosave']) {
          echo '<script type="text/javascript">
                    window.parent.$("#newcustomerform").attr("action", "/customer/updatenewcustomer/'. $model->id .'?ajaxRequest=1");
                    window.parent.$("#button_cancel1").attr("url", "/customer/deletenewcustomer/'. $model->id .'?ajaxRequest=1");
                    window.parent.$("#button_cancel2").attr("url", "/customer/deletenewcustomer/'. $model->id .'?ajaxRequest=1");
                 </script>';
          exit;
        } else {
          Yii::app()->user->setFlash('', 'Customer is saved.');
          $this->redirect(array('viewnewcustomer','id'=>$model->id));
        }
      }else{
        if ($_POST['autosave']) {
          //auto save, don't show anything
          return;
        } else {
          //submit by mannually click 'save' button, in javascript, set the target=_self, so now can't use ajaxRequest
          $_REQUEST['ajaxRequest'] = 0;
        }
      }
     
    }
    
    $this->render('_newcustomerform', array(
        'model'=>$model,
    ));
  }
  
  public function actionUpdateNewCustomer($id){
    $this->_buildShortcuts();
    $model=$this->loadModel($id);

    if(isset($_POST['Customer']))
    {
      if ($_POST['Customer']['button'] == 'Cancel') {
        $this->redirect(array('view','id'=>$model->id));
      } else {
        $model->attributes=$_POST['Customer'];
        $model->form_type='new';
        $model->updatedby = Yii::app()->user->id;
        $model->updatedtm = time();
            
        if($model->save()){
          if ($_POST['autosave']) {
            echo 'autosave done';
            exit;
          } else {
            $this->redirect(array('viewnewcustomer','id'=>$model->id));
          }
        } else {
          if ($_POST['autosave']) {
            //auto save, don't show anything
            return;
          } else {
            //submit by mannually click 'save' button, in javascript, set the target=_self, so now can't use ajaxRequest
            $_REQUEST['ajaxRequest'] = 0;
          }
        }
      }
    }

    $this->render('_form',array(
        'model'=>$model,
    ));
  }
  
  public function actionDeleteNewCustomer($id)
  {
    $this->loadModel($id)->delete();
    $this->actionIndex();
  }
  
  public function actionViewNewCustomer($id, $print=false)          //$id is the customer id
  {
    if ($print) {
      Yii::app()->params['print'] = true;
    }
    $this->_buildShortcuts();
    
    $connection = Yii::app()->db;
    
    //contacts
//    $command = $connection->createCommand("select * from contact where customerid=:customerid order by full_name");
    $command1 = $connection->createCommand("select * from contact where customerid=:customerid and company_id=:company_id order by full_name");
    $command1->bindParam(':customerid', $id);
    $command1->bindParam(':company_id', Yii::app()->user->company_id);
    $contactDataProvider = $command1->query();
    
    //products
    $command2 = $connection->createCommand("select i.*, p.id as product_id, ifnull(p.product_retail, i.retail) as retail from product p, inventory i
      where p.inventory_id=i.id and p.customer_id=:customer_id and p.company_id=i.company_id and p.company_id=:company_id order by i.name");
    $command2->bindParam(':customer_id', $id);
    $command2->bindParam(':company_id', Yii::app()->user->company_id);
    $productDataProvider = $command2->query();
    
    //$total_payments
    $command3 = $connection->createCommand("select sum(amount) from payment where customer_id=:customer_id and type='payment'");
    $command3->bindParam(':customer_id', $id);
    $total_payments = $command3->queryScalar();
    $total_payments = $total_payments != '' ||  $total_payments != null ? $total_payments : '0';
    
    //$tatal_balances
    $command4 = $connection->createCommand("select sum(amount) from payment where customer_id=:customer_id and type != 'payment'");
    $command4->bindParam(':customer_id', $id);
    $tatal_balances = $command4->queryScalar();
    $total_balances = $total_balances != '' ||  $total_balances != null ? $total_balances : '0';
    
    //$payments
    $command5 = $connection->createCommand("select * from payment where customer_id=:customer_id order by id desc");
    $command5->bindParam(':customer_id', $id);
    $paymentDataProvider = $command5->query();
    
    //documents
    $command6=$connection->createCommand("select d.id, d.product_id, d.email_address_alt,
          t.id as template_id, t.name,t.email_address
      from template t
      left join document d on (d.template_id=t.id and d.customer_id=:customer_id)
      where default_check=1 and deleted=0 and company_id=:company_id order by t.name");
    $command6->bindParam(':customer_id',$id);  
    $command6->bindParam(':company_id',Yii::app()->user->company_id);  
    $documentDataProvider = $command6->query();
    $documents = array();
    while ($row = $documentDataProvider->read()) {
      $documents[$row['template_id']] = $row;
      
      //check if this exists in {document}
      $existsInDocument = $connection->createCommand("select count(*) from document where template_id=".$row['template_id']." and customer_id=".$id)->queryScalar();
      if (!$existsInDocument) {
        $connection->createCommand("insert into document (template_id, customer_id) values (".$row['template_id'].", $id)")->execute();
      }
    }
    //not find from products related templates
//    $command = $connection->createCommand("SELECT t.id as template_id, i.name as product_name, t.name, t.email_address
//      FROM product p, inventory i, template t
//      WHERE p.customer_id=:customer_id AND p.inventory_id=i.id AND i.template_id=t.id AND i.template_id>0 
//      ORDER BY t.name");
    $command7 = $connection->createCommand("SELECT t.id as template_id, i.name as product_name, t.name, t.email_address
      FROM product p, inventory i, template t
      WHERE p.customer_id=:customer_id AND p.inventory_id=i.id AND i.template_id=t.id AND i.template_id>0
            AND t.default_check=1  AND t.company_id = :company_id AND t.deleted = 0
      ORDER BY t.name");
    $command7->bindParam(':customer_id',$id);  
    $command7->bindParam(':company_id', Yii::app()->user->company_id);  
    $productTemplatesDataProvider = $command7->query();
    while ($row = $productTemplatesDataProvider->read()) {
      if (!array_key_exists($row['template_id'], $documents)) {
        $documents[$row['template_id']] = $row;
      }
    }

    //discounts
    $sql = "select sum(amount) from payment where customer_id= :customer_id and type='credit'";
    $command8 = $connection->createCommand($sql);
    $command8->bindParam(':customer_id', $id);
    $discount = $command8->queryScalar();
    
    $this->render('viewnewcustomer',array(
        'model'=>$this->loadModel($id),
        'contactDataProvider'=>$contactDataProvider,
        'productDataProvider'=>$productDataProvider,
        'total_payments'=>$total_payments,
        'discount'=>$discount,
        'tatal_balances'=>$tatal_balances,
//        'productRetail'=>$productRetail,
        'paymentDataProvider'=>$paymentDataProvider,
        'documents'=>$documents,
    ));
  }
  
  public function actionAddProduct($id)               //$id is the customer id.
  {
    $customer = $this->loadModel($id);
   
    if ($_POST) {
      if ($_POST['products']) {
        foreach($_POST['products'] as $inventory_id){
          //get inventory object by inventory_id
          $inventory = Inventory::model()->findByPk($inventory_id);
          
          $product = new Product();
          $product->inventory_id = $inventory_id;      //The  table 'product' is asscoiated to table 'inventory' by field 'inventory_id'(p.inventory_id=i.id)
          $product->company_id = Yii::app()->user->company_id;
          $product->customer_id = $id;
          $product->invoice_notes = $_POST['invoice_notes'];
          $product->internal_notes = $_POST['internal_notes'];
          $product->enteredby =  Yii::app()->user->id;
          $product->enteredtm = time();
          $product->company_id = Yii::app()->user->company_id;
          $product->product_retail = $inventory->retail;
          $product->save();

          //add document to custoemr
          if ($inventory->template_id) {
            $document = new Document();
            $document->customer_id = $id;
            $document->template_id = $inventory->template_id;
            $document->product_id = $product->id;
            $document->save();
          }
        } 
      }
      
//      $this->redirect('/customer/view/'.$id.'?ajaxRequest=1');
      $this->redirect('/customer/view/'.$id.'#productlist');
    }
    
    //products
    $products = Inventory::getAllForCustomer($customer->id);
    
    //packages
    $products = Inventory::getAllForCustomer($customer->id);

    $this->render('addproduct', array(
        'customer'=>$customer,
        'products'=>$products
    ));
  }
  
  public function actionAddPackage($id)               
  {
    $customer = $this->loadModel($id);
    
    if($_POST['package'])
    { 
      foreach($_POST['package'] as $package_id){
//        $package=Package::model()->findByPK($package_id);

        $connection = Yii::app()->db;
        $command = $connection->createCommand("select inventory_id from package_product  
                                   where package_id=:package_id and inventory_id not in (select inventory_id from product where customer_id=:customer_id and company_id=:company_id) ");
        $command->bindParam(':package_id', $package_id);
        $command->bindParam(':customer_id', $id);
        $command->bindParam(':company_id', Yii::app()->user->company_id);
        $dr=$command->query();

        while($row=$dr->read()){
          $inventory = Inventory::model()->findByPk($row['inventory_id']);
          
          $product=new Product();
          $product->inventory_id=$row['inventory_id'];
          $product->company_id=Yii::app()->user->company_id;
          $product->customer_id=$id;
          $product->enteredby=Yii::app()->user->id;
          $product->enteredtm=time();
          $product->product_retail=$inventory->retail;
          $product->save();
        }
      }
      
//      $this->redirect('/customer/view/'.$id.'?ajaxRequest=1');
      $this->redirect('/customer/view/'.$id.'#productlist');

    }
    $package=Package::getAll();   
   
    $this->render('addpackage', array(
        'customer' => $customer,
        'package' => $package
        ));
  }
  
  public function actionViewPackage($id, $print = false) {           //$id is the package id
    if ($print) {
      Yii::app()->params['print'] = true;
    }
    $this->_buildShortcuts();
    
//    $connection = Yii::app()->db;
//    $command = $connection->createCommand("select i.* from package_product pp, inventory i
//      where pp.inventory_id=i.id and pp.package_id=:package_id order by i.name");
//    $command->bindParam(':package_id', $id);
//    $productDataProvider = $command->query();
    
     $connection = Yii::app()->db;
    $command = $connection->createCommand("select * from package where company_id=:company_id order by name");
    $command->bindParam(':company_id', Yii::app()->user->company_id);
    $packageDataProvider = $command->query();

    $this->render('viewpackage', array(
        'model' => $this->loadModel($id),
        'productDataProvider' => $productDataProvider,
    ));
  }
  
  public function actionEditProduct($id)             //$id is the product id.
  {
    $product = Product::model()->findByPk($id);
    $inventory = Inventory::model()->findByPk($product->inventory_id);
    $customer = Customer::model()->findByPk($product->customer_id);
    
    if (empty($product->internal_notes)) {
      $product->internal_notes = $inventory->internal_notes;
      $product->invoice_notes = $inventory->invoice_notes;
    }
    
    if ($_POST) {
      $product->internal_notes = $_POST['internal_notes'];
      $product->invoice_notes = $_POST['invoice_notes'];
      $product->save();
//      $this->redirect('/customer/view/' . $product->customer_id . '?ajaxRequest=1');
      $this->redirect('/customer/view/'.$product->customer_id.'#productlist');
    }

    $this->render('editproduct', array(
        'product' => $product,
        'inventory' => $inventory,
        'customer' => $customer,
    ));
  }
  
  public function actionDeleteProduct($id)
  {
    $product = Product::model()->findByPk($id);
            
    if ($product->company_id == Yii::app()->user->company_id) {
      $product->delete();
      
      //delete product related document
      $connection = Yii::app()->db;
      $command = $connection->createCommand("delete from document where product_id=:product_id");
      $command->bindParam(':product_id', $product->id);
      $command->execute();
    }
    
    if ($_POST['from'] == 'customer') {//delete from customer document subpanel
      echo 'Done';
      exit;
    } else {//from contact detail view
      $this->redirect('/customer/view/'. $product->customer_id .'?ajaxRequest=1');
    }
  }
  
  public function actionAddPayment($id, $type='')               //$id is the customer id.
  {
    $customer = $this->loadModel($id);
    $model = new Payment();
    $model->customer_id = $id;

    if ($_POST) {
      $model->attributes = $_POST['Payment'];
      $model->type = ($type == '') ? 'payment' : $type;
//      $model->date = date('m/d/Y H:i:s');
      if ($model->save()) {
//        $this->redirect('/customer/view/'.$id.'?ajaxRequest=1');
        $this->redirect('/customer/view/'.$id.'#paymentlist');
      }
    }
    
    $this->render('addpayment', array(
        'customer'=>$customer,
        'model'=>$model,
    ));
  }
  
  public function actionEditPayment($id)             //$id is the payment id.
  {
    $model = Payment::model()->findByPk($id);
    $customer = Customer::model()->findByPk($model->customer_id);

    if ($_POST) {
      $model->attributes = $_POST['Payment'];
      if ($model->save()) {
//        $this->redirect('/customer/view/' . $model->customer_id . '?ajaxRequest=1');
        $this->redirect('/customer/view/'. $model->customer_id .'#paymentlist');
      }
    }
 
    $this->render('editpayment', array(
        'model' => $model,
        'customer' => $customer,
    ));
  }
  
  public function actionDeletePayment($id)
  {
    $payment = Payment::model()->findByPk($id);
            
    $payment->delete();

    if ($_POST['from'] == 'customer') {//delete from customer document subpanel
      echo 'Done';
      exit;
    } else {//from contact detail view
      $this->redirect('/customer/view/'.$payment->customer_id.'?ajaxRequest=1');
    }
  }
  
  public function actionAddCredit($id)               //$id is the customer id.
  {
     $this->actionAddPayment($id, 'credit');
  }
  
  public function actionAddDocument($id)              //$id is the customer id
  {
    $customer = $this->loadModel($id);
    
    if ($_POST['document']) {
//      echo '<pre>';
//      print_r($_POST['document']);
//      echo '</pre>';
//      exit;
      foreach ($_POST['document'] as $template_id) {
        $document = new Document();
        $document->customer_id = $id;
        $document->template_id = $template_id;
        $document->save();
        
        $connection = Yii::app()->db;
        $command = $connection->createCommand("update template set default_check=1 where id=:id");
        $command->bindParam(':id', $template_id);
        $command->execute();
      }
      
//      $this->redirect('/customer/view/'.$id.'?ajaxRequest=1');    
      $this->redirect('/customer/view/'.$id.'#documentslist');
    }
    
    $this->render('adddocument', array(
        'customer'=>$customer,
    ));
  }
  
  public function actionDeleteDocument($id)       //$id is the document id
  {
    $document = Document::model()->findByPk((int)$id);
    
    $customer= $this->loadModel($document->customer_id);
    
    if ($customer->company_id == Yii::app()->user->company_id) {
      $connection = Yii::app()->db;
      $command = $connection->createCommand("delete from document where id=:id");
      $command->bindParam(':id', $id);
      $command->execute();
      
      $command = $connection->createCommand("update template set default_check=0 where id=:template_id");
      $command->bindParam(':template_id', $document->template_id);
      $command->execute();
    }
    
    if ($_POST['from'] == 'customer') {//delete from customer document subpanel
      echo 'Done';
      exit;
    } else {//from contact detail view
      $this->redirect('/customer/view/'.$customer->id.'?ajaxRequest=1');
    }
    
  }
  
  public function actiondocumentdownload($customer_id, $template_id, $dowload=true)
  {
    $customer = $this->loadModel($customer_id);
    $template = Template::model()->findByPk((int)$template_id);

    //print to pdf
    require_once(dirname(__FILE__) . '/../components/tcpdf/config/lang/eng.php');
    require_once(dirname(__FILE__) . '/../components/tcpdf/tcpdf.php');

    // create new PDF document
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    // set document information
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Zyp');
    $pdf->SetTitle('Documents');
    $pdf->SetSubject('Documents');
    $pdf->SetKeywords('Documents');

    // set default header data
    $pdf_header_title = 'Funeral Home System - Documents';
    $pdf_header_string = "by Funeral Home System - ". $_SERVER['HTTP_HOST'] ."\n.". $_SERVER['HTTP_HOST'];

//    $pdf->SetHeaderData('', 0, '', '');

    // set header and footer fonts
//    $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
//    $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

    // remove default header/footer
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);

    // set default monospaced font
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    //set margins
//    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
//    $pdf->SetMargins(8, 0, 8);
    $pdf->SetMargins(4, 0, 4);
//    $pdf->SetHeaderMargin(0);
    $pdf->SetHeaderMargin(0);
//    $pdf->SetFooterMargin(0);
    $pdf->SetFooterMargin(10);
    
    //set auto page breaks
//    $pdf->SetAutoPageBreak(false, PDF_MARGIN_BOTTOM);
    if($template->id == 2 || $template->id == 3){
      $pdf->SetAutoPageBreak(false, PDF_MARGIN_BOTTOM);
    }else{
      $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
    }
    //set image scale factor
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    //set some language-dependent strings
    $pdf->setLanguageArray($l);

    // ---------------------------------------------------------
     
    if($template->id == 2){
    //reset font stretching
      $pdf->setFontStretching(100);
      
    //reset font spacing
      $pdf->setFontSpacing(0);
    }
    // set font
    $pdf->SetFont('dejavusans', '', 7);
//    $pdf->SetTopMargin(10);
    $pdf->SetTopMargin(5);
    
    $document = $this->_populateTemplateVars($customer, $template);

    $css = '<style>
              h1{
                color: #A49262;
                font-size: 18pt;
                font-family: Arial, sans-serif;
                margin-top: 5px;
                margin-bottom: 3px;
              }
              span{
               margin:0 0 0 0;
               padding:0 0 0 0;
               text-indent:0;
              }
              </style>';
      
    $document = $css . $document;

    // add a page
    $pdf->AddPage();

    // test some inline CSS
    $pdf->writeHTML($document, true, false, true, false, '');

    // reset pointer to the last page
    $pdf->lastPage();
    
    //Close and output PDF document
    $fileName = $_SERVER['DOCUMENT_ROOT'] . '/files/document/Document_' . $full_legal_name . '_' . time() . '.pdf';
    if($dowload){
      $pdf->Output($fileName, 'FD');
      
      //Add notes
//      $note=new Notes();
//      $note->parent_type = 'customer';
//      $note->parent_id = $customer->id;
//      $note->company_id = Yii::app()->user->uid;
//      $note->subject = 'download';
//      $note->body = 'Download';
//      $note->timestamp = time();
//      $note->enteredby = Yii::app()->user->id;
//      $note->save();
      exit;
    }
    else{
      $pdf->Output($fileName, 'F'); 
      return $fileName;
    }
  }
  
  public function actiondocumentdownloadword($customer_id, $template_id, $dowload=true)
  {
    $customer = $this->loadModel($customer_id);
    $template = Template::model()->findByPk((int)$template_id);

    $document = $this->_populateTemplateVars($customer, $template);
    
    $css = '<style>
              h1{
                color: #A49262;
                font-size: 18pt;
                font-family: Arial, sans-serif;
                margin-top: 5px;
                margin-bottom: 3px;
              }
              span{
               margin:0 0 0 0;
               padding:0 0 0 0;
               text-indent:0;
              }
              </style>';
      
    
    $document = $css . $document;
    
    //Close and output WORD document
    $fileName = $_SERVER['DOCUMENT_ROOT'] . '/files/document/Document_' . $full_legal_name . '_' . time() . '.doc';
    if($dowload){
      
      $document = '<html xmlns:o="urn:schemas-microsoft-com:office:office"
                    xmlns:w="urn:schemas-microsoft-com:office:word"
                    xmlns="http://www.w3.org/TR/REC-html40">' . $document . '</html>';
      
      $fp = fopen($fileName, 'w');
      fwrite($fp, $document);
      fclose($fp);
      
      header('Content-Type: application/force-download');
      header('Content-Disposition: attachment; filename='. basename($fileName));
      readfile($fileName);
      exit;
      
      //Add notes
//      $note=new Notes();
//      $note->parent_type = 'customer';
//      $note->parent_id = $customer->id;
//      $note->company_id = Yii::app()->user->uid;
//      $note->subject = 'download';
//      $note->body = 'Download';
//      $note->timestamp = time();
//      $note->enteredby = Yii::app()->user->id;
//      $note->save();
//      exit;
    }
    else{
      $fp = fopen($fileName, 'w');
      fwrite($fp, $document);
      fclose($fp);
      
      return $fileName;
    }
  }
  
  private function _populateTemplateVars($customer, $template){
    $customer_id = $customer->id;
    $connection = Yii::app()->db;
    $countries = CommonFunc::countries();
    $countries[''] = '';
    
    //get template value %Location of Funeral Service%
    if($customer->location_of_funeral_service != 'Other'){
      $location_of_funeral_service_value =  $customer->location_of_funeral_service;
    }else{
      $location_of_funeral_service_value = $customer->location_of_funeral_service_other;
    }
    
    //get template value %Location of Visitation%
    if($customer->location_of_visitation != 'Other'){
      $location_of_visitation_value = $customer->location_of_visitation;
    }else{
      $location_of_visitation_value = $customer->location_of_visitation_other;
    }
    
    //get template value %Newspaper/Radio 1%
    if($customer->newspaper_radio1 != 'Other'){
      $newspaper_radio1_value = $customer->newspaper_radio1;
    }else{
      $newspaper_radio1_value = $customer->newspaper_radio1_other;
    }
    
    //get template value %Newspaper/Radio 2%
    if($customer->newspaper_radio2 != 'Other'){
      $newspaper_radio2_value = $customer->newspaper_radio2;
    }else{
      $newspaper_radio2_value = $customer->newspaper_radio2_other;
    }
    
    //get template value %Newspaper/Radio 3%
    if($customer->newspaper_radio3 != 'Other'){
      $newspaper_radio3_value = $customer->newspaper_radio3;
    }else{
      $newspaper_radio3_value = $customer->newspaper_radio3_other;
    }
    
    //get template value %Newspaper/Radio 4%
    if($customer->newspaper_radio4 != 'Other'){
      $newspaper_radio4_value = $customer->newspaper_radio4;
    }else{
      $newspaper_radio4_value = $customer->newspaper_radio4_other;
    }
    
    //get template value %Newspaper/Radio 5%
    if($customer->newspaper_radio5 != 'Other'){
      $newspaper_radio5_value = $customer->newspaper_radio5;
    }else{
      $newspaper_radio5_value = $customer->newspaper_radio5_other;
    }
    
    //get template value %Newspaper/Radio 6%
    if($customer->newspaper_radio6 != 'Other'){
      $newspaper_radio6_value = $customer->newspaper_radio6;
    }else{
      $newspaper_radio6_value = $customer->newspaper_radio6_other;
    }
    
    
    $full_legal_name = $customer->full_legal_name;
    
    if (stripos($template->templates, 'Date of statement') === false && $template->id != 2) {//contract already have this, 2 is WORKING COPY
        $template->templates = '<div style="text-align: right;">
                                 <span style="font-size:8pt;"><span style="font-family:arial,helvetica,sans-serif;">Date of statement: '. date('m/d/Y') .'</strong></span></div>' . $template->templates;
    }
    
  //get contacts
    if (strpos($template->templates, '%Contacts%') !== false) {
      //contacts
      $command = $connection->createCommand("select * from contact where customerid=:customerid and company_id=:company_id order by full_name");
      $command->bindParam(':customerid', $customer_id);
      $command->bindParam(':company_id', Yii::app()->user->company_id);
      $contactDataProvider = $command->query();
      
      $contacts = $this->renderPartial('download_pdf_contacts', array(
                    'model'=>$customer,
                    'contactDataProvider'=>$contactDataProvider,
                ), true);
    }
        
  //products1
    if (strpos($template->templates, '%Products (with internal notes)%') !== false) {
        $sql = "select i.*, ifnull(p.product_retail, i.retail) as retail, p.internal_notes as internal_notes_product, p.invoice_notes as invoice_notes_product from product p, inventory i
                 where p.inventory_id=i.id and p.customer_id= :customer_id and p.company_id=i.company_id and p.company_id=:company_id and p.internal_notes != '' and p.internal_notes is not null";
        $command = $connection->createCommand($sql);
        $command->bindParam(':customer_id', $customer_id);
        $command->bindParam(':company_id', Yii::app()->user->company_id);
        $productDataProvider = $command->query();
        
        $products1 = $this->renderPartial('download_pdf_products', array(
                      'model'=>$customer,
                      'productDataProvider'=>$productDataProvider,
                      'title'=>'with internal notes',
                  ), true);     
    } 
   
  //products2
    if (strpos($template->templates, '%Products (invoice notes only)%') !== false) {
        $sql = "select i.*, ifnull(p.product_retail, i.retail) as retail, p.internal_notes as internal_notes_product, p.invoice_notes as invoice_notes_product from product p, inventory i
                 where p.inventory_id=i.id and p.customer_id= :customer_id and p.company_id=i.company_id and p.company_id=:company_id and p.invoice_notes != '' and p.invoice_notes is not null";
        $command = $connection->createCommand($sql);
        $command->bindParam(':customer_id', $customer_id);
        $command->bindParam(':company_id', Yii::app()->user->company_id);
        $productDataProvider = $command->query();
        
        $products2 = $this->renderPartial('download_pdf_products', array(
                      'model'=>$customer,
                      'productDataProvider'=>$productDataProvider,
                      'title'=>'invoice notes only',
                  ), true);  
    }
   
  //products3
    if (strpos($template->templates, '%Products (Services)%') !== false) {
        $sql = "select i.*, ifnull(p.product_retail, i.retail) as retail, p.internal_notes as internal_notes_product, p.invoice_notes as invoice_notes_product from product p, inventory i
                 where p.inventory_id=i.id and p.customer_id= :customer_id and p.company_id=i.company_id and p.company_id=:company_id and i.category = 'Services'";
        $command = $connection->createCommand($sql);
        $command->bindParam(':customer_id', $customer_id);
        $command->bindParam(':company_id', Yii::app()->user->company_id);
        $productDataProvider = $command->query();
        
        $products3 = $this->renderPartial('download_pdf_products', array(
                      'model'=>$customer,
                      'productDataProvider'=>$productDataProvider,
                      'title'=>'Services',
                  ), true);  
    }
    
  //products4
    if (strpos($template->templates, '%Products (Merchandise)%') !== false) {
        $sql = "select i.*, ifnull(p.product_retail, i.retail) as retail, p.internal_notes as internal_notes_product, p.invoice_notes as invoice_notes_product from product p, inventory i
                 where p.inventory_id=i.id and p.customer_id= :customer_id and p.company_id=i.company_id and p.company_id=:company_id and i.category = 'Merchandise'";
        $command = $connection->createCommand($sql);
        $command->bindParam(':customer_id', $customer_id);
        $command->bindParam(':company_id', Yii::app()->user->company_id);
        $productDataProvider = $command->query();
        
        $products4 = $this->renderPartial('download_pdf_products', array(
                      'model'=>$customer,
                      'productDataProvider'=>$productDataProvider,
                      'title'=>'Merchandise',
                  ), true);  
    }
    
  //products5
    if (strpos($template->templates, '%Products (Cash Advances)%') !== false) {
        $sql = "select i.*, ifnull(p.product_retail, i.retail) as retail, p.internal_notes as internal_notes_product, p.invoice_notes as invoice_notes_product from product p, inventory i
                 where p.inventory_id=i.id and p.customer_id= :customer_id and p.company_id=i.company_id and p.company_id=:company_id and i.category = 'Cash Advances'";
        $command = $connection->createCommand($sql);
        $command->bindParam(':customer_id', $customer_id);
        $command->bindParam(':company_id', Yii::app()->user->company_id);
        $productDataProvider = $command->query();
        
        $products5 = $this->renderPartial('download_pdf_products', array(
                      'model'=>$customer,
                      'productDataProvider'=>$productDataProvider,
                      'title'=>'Cash Advances',
                  ), true);  
    }
    
  //get payments
    if (strpos($template->templates, '%Payments%') !== false) {
      $command = $connection->createCommand("select * from payment where customer_id=:customerid order by id desc");
      $command->bindParam(':customerid', $customer_id);
      $paymentsDataProvider = $command->query();
      
      $payments = $this->renderPartial('download_pdf_payments', array(
                    'model'=>$customer,
                    'paymentsDataProvider'=>$paymentsDataProvider,
                ), true);
    }
    
    //get products total price
    $sql = "select sum(ifnull(p.product_retail, i.retail)) from product p, inventory i
             where p.inventory_id=i.id and p.customer_id= :customer_id and p.company_id=i.company_id and p.company_id=:company_id";
    $command = $connection->createCommand($sql);
    $command->bindParam(':customer_id', $customer_id);
    $command->bindParam(':company_id', Yii::app()->user->company_id);
    $productTotalPrice = $command->queryScalar();

    
   //$total_funeral_charges
     if (strpos($template->templates, '%Total Funeral Charges%') !== false) {
        $sql = "select sum(ifnull(p.product_retail, i.retail)) from product p, inventory i
                 where p.inventory_id=i.id and p.customer_id= :customer_id and p.company_id=i.company_id and p.company_id=:company_id and i.category = 'Services'";
        $command = $connection->createCommand($sql);
        $command->bindParam(':customer_id', $customer_id);
        $command->bindParam(':company_id', Yii::app()->user->company_id);
        $productPrice_services = $command->queryScalar();


        $sql = "select sum(ifnull(p.product_retail, i.retail)) from product p, inventory i
                 where p.inventory_id=i.id and p.customer_id= :customer_id and p.company_id=i.company_id and p.company_id=:company_id and i.category = 'Merchandise'";
        $command = $connection->createCommand($sql);
        $command->bindParam(':customer_id', $customer_id);
        $command->bindParam(':company_id', Yii::app()->user->company_id);
        $productPrice_merchandise = $command->queryScalar();
    
        $total_funeral_charges = $productPrice_services + $productPrice_merchandise;
    }
    
    //$sales_tax
//     if (strpos($template->templates, '%Sales Tax%') !== false) {
        $taxRate = Config::loadTaxByCompany(Yii::app()->user->company_id);
        $sql = "select (sum(ifnull(p.product_retail, i.retail)) * ". $taxRate .") from product p, inventory i
                 where p.inventory_id=i.id and p.customer_id= :customer_id and p.company_id=i.company_id and p.company_id=:company_id and i.taxable=1";
        echo $sql;
		$command = $connection->createCommand($sql);
        $command->bindParam(':customer_id', $customer_id);
        $command->bindParam(':company_id', Yii::app()->user->company_id);
        $sales_tax = $command->queryScalar();
//    }
    
    //$total_cash_advances
     if (strpos($template->templates, '%Total Cash Advances%') !== false) {
        $sql = "select sum(ifnull(p.product_retail, i.retail)) from product p, inventory i
                 where p.inventory_id=i.id and p.customer_id= :customer_id and p.company_id=i.company_id and p.company_id=:company_id and i.category = 'Cash Advances'";
        $command = $connection->createCommand($sql);
        $command->bindParam(':company_id', Yii::app()->user->company_id);
        $command->bindParam(':customer_id', $customer_id);
        $total_cash_advances = $command->queryScalar();
    }
    
    //$total_payments_received
     if (strpos($template->templates, '%Total Payments Received%') !== false) {
//        $sql = "select sum(amount) from payment where customer_id= :customer_id";
        $sql = "select sum(amount) from payment where customer_id=:customer_id and type='payment'";
        $command = $connection->createCommand($sql);
        $command->bindParam(':customer_id', $customer_id);
        $total_payments_received = $command->queryScalar();
    }
    
    //$credit_amount
//     if (strpos($template->templates, '%Credit Amount%') !== false) {
     if (strpos($template->templates, '%Discount%') !== false) {
        $sql = "select sum(amount) from payment where customer_id= :customer_id and type='credit'";
//        $sql = "select sum(amount) from payment where customer_id= :customer_id";
        $command = $connection->createCommand($sql);
        $command->bindParam(':customer_id', $customer_id);
//        $credit_amount = $command->queryScalar();
        $discount = $command->queryScalar();
    }
    
     //$complete_total
     if (strpos($template->templates, '%Complete Total%') !== false) {
//        $sql = "select sum(ifnull(p.product_retail, i.retail)) from product p, inventory i
//                 where p.inventory_id=i.id and p.customer_id= :customer_id";
//        $command = $connection->createCommand($sql);
//        $command->bindParam(':customer_id', $customer_id);
//        $complete_totalData = $command->queryScalar();
        
        $complete_total = $total_funeral_charges + $total_cash_advances + $sales_tax - $discount;
    }
    
    //$balance_due
     if (strpos($template->templates, '%Balance Due%') !== false) {
//        $sql = "select sum(ifnull(p.product_retail, i.retail)) from product p, inventory i
//                 where p.inventory_id=i.id and p.customer_id= :customer_id and p.company_id=i.company_id and p.company_id=:company_id";
//        $command = $connection->createCommand($sql);
//        $command->bindParam(':customer_id', $customer_id);
//        $command->bindParam(':company_id', Yii::app()->user->company_id);
//        $chargestotal = $command->queryScalar();
//        $balance_due = $chargestotal + $sales_tax - $total_payments_received;
       
        $balance_due = $complete_total - $total_payments_received;
    }
    
    //get notes
    if (strpos($template->templates, '%Notes%') !== false) {
      $sql = "select subject, body, enteredby, timestamp from notes where parent_id = :parent_id";
      $command = $connection->createCommand($sql);
      $command->bindParam(':parent_id', $customer_id);
      $noteData = $command->query();
      
      $notes = '<table border="1" width="100%" cellpadding="3">';
      $notes .= '<tr><td width="20%" style="text-align: center;">Subject</td><td width="40%" style="text-align: center;">Body</td><td width="20%" style="text-align: center;">Entered By</td><td width="20%" style="text-align: center;">Entered Time</td></tr>';
      while($row = $noteData->read()){
        $notes .= '<tr><td width="20%">'.$row["subject"].'</td><td width="40%">'.$row["body"].'</td><td width="20%">'.$row["enteredby"].'</td><td width="20%">'.$row["timestamp"].'</td></tr>';
      } 
      $notes .='</table>';
    }
   //get logo
	if (strpos($template->templates, '%Logo%') !== false) {
		$company = Company::model()->findByPk($customer->company_id);
		$logo = !empty($company) ? '<img border="0" src="'. $company->logo .'" />' : '';
	} 
    //get Summary of Payments
//    if (strpos($template->templates, '%Summary_of_payments%') !== false) {
//      $sql1 = "select date, payer, amount from payment where customer_id = :customer_id order by date";
//      $command1 = $connection->createCommand($sql1);
//      $command1->bindParam(':customer_id', $customer_id);
//      $paymentsData = $command->query();
//      
//      $sql2 = "select sum(ifnull(p.product_retail, i.retail)) from product p, inventory i
//                 where p.inventory_id=i.id and p.customer_id= :customer_id and p.company_id=i.company_id and p.company_id=:company_id and i.category = 'Services'";
//      $command2 = $connection->createCommand($sql2);
//      $command2->bindParam(':customer_id', $customer_id);
//      $command2->bindParam(':company_id', Yii::app()->user->company_id);
//      $productPrice_services2 = $command->queryScalar();
//
//
//      $sql3 = "select sum(ifnull(p.product_retail, i.retail)) from product p, inventory i
//               where p.inventory_id=i.id and p.customer_id= :customer_id and p.company_id=i.company_id and p.company_id=:company_id and i.category = 'Merchandise'";
//      $command3 = $connection->createCommand($sql3);
//      $command3->bindParam(':customer_id', $customer_id);
//      $command3->bindParam(':company_id', Yii::app()->user->company_id);
//      $productPrice_merchandise2 = $command->queryScalar();
//
//      $total_funeral_charges2 = $productPrice_services2 + $productPrice_merchandise2;
//      
//      $payments = '<table border="1" width="100%" cellpadding="3">';
//      $payments .= '<tr><td width="40%" style="text-align: center; font-weight: bold;">Date of Payment</td><td width="20%" style="text-align: center;font-weight: bold;">Payor</td><td width="20%" style="text-align: center;font-weight: bold;">Amount</td><td width="20%" style="text-align: center;font-weight: bold;">Balance</td></tr>';
//      $payments .= '<tr><td width="40%" style="text-align: center;"></td><td width="20%" style="text-align: center;"></td><td width="20%" style="text-align: center;"></td><td width="20%" style="text-align: right;">$'.number_format($total_funeral_charges2, 2).'</td></tr>';
//     
//      $balance = $total_funeral_charges2;
//      while($row = $paymentsData->read()){
//          $balance -= $row["amount"];
////        $payments .= '<tr><td width="40%">'.$row["date"].'</td><td width="20%">'.$row["payer"].'</td><td width="20%">'.$row["amount"].'</td><td width="20%">'.$row["amount"].'</td></tr>';
//        $payments .= '<tr><td width="40%">'.date("m/d/y", strtotime($row["date"])).'</td><td width="20%">'.$row["payer"].'</td><td width="20%" style="text-align: right;">'.$row["amount"].'</td><td width="20%" style="text-align: right;">'.number_format($balance, 2).'</td></tr>';
//      } 
//      $payments .='</table>';
//    }
    
    $placeHolds = array(
        '%DOS%' => date('m/d/Y', time()),
        '%Case Number%' => $customer->case_number,
        '%Full Legal Name%' => $full_legal_name,
        '%Name For Obituary%' => $customer->name_for_obituary,
        '%Funeral Home%' => $customer->skfh_funeral_home,
        '%Date Of Death%' => $customer->formatDateTime('date_of_death'),
        '%Time Of Death%' => $customer->time_of_death_h.' : '.$customer->time_of_death_m.' '.$customer->time_of_death_z,
        '%Place of Death%' => $customer->place_of_death,
        '%City%' => $customer->city,
        '%Age%' => $customer->calAge(),
        '%Sex%' => $customer->sex,
        '%Formerly of%' => $customer->formerly_of,
//        '%Date of Birth%' => $customer->formatDateTimeWithWeekday('date_of_birth', 'time_of_birth', 'zone_of_birth'),
//        '%Date of Birth%' => date('M/d/Y', strtotime($customer->date_of_birth)),
        '%Date of Birth%' =>$customer->formatDateTime('date_of_birth'),
        '%Place of Birth%' => $customer->place_of_birth,
        '%Funeral Service Date and Time%' => $customer->formatDateTime('funeral_service_date', 'funeral_service_time'),
        '%Location of Funeral Service%' => $location_of_funeral_service_value,
        '%Visitation Time%' => $customer->visitation_time_h_start.':'.$customer->visitation_time_m_start.' '.$customer->visitation_time_z_start,
        '%Visitation Time End%' => $customer->visitation_time_h_end.':'.$customer->visitation_time_m_end.' '.$customer->visitation_time_z_end,
//        '%Date of Visitation%' => date('M/d/Y', strtotime($customer->date_of_visitation_start)),
        '%Date of Visitation%' => $customer->formatDateTime('date_of_visitation_start'),
        '%Date of Visitation End%' => $customer->formatDateTime('date_of_visitation_end'),
        '%Disposition Type%' => $customer->disposition_type,
        '%Disposition Date%' => $customer->disposition_date,
        '%Disposition Place%' => $customer->disposition_place,
        '%Location of Visitation%' => $location_of_visitation_value,
        '%Date and Time of Burial%' => $customer->formatDateTime('date_of_burial', 'time_of_burial'),
        '%Burial%' => $customer->burial,
        '%Officiant%' => $customer->officiant,
        '%Officiant2%' => $customer->clergy_full_name2,
        '%Special Rites%' => $customer->special_rites,
        '%Memorials%' => $customer->memorials,
        '%Church Membership%' => $customer->church_membership,
        '%Fathers Name%' => $customer->genFullName('fathers_name'),
        '%Mothers Name%' => $customer->genFullName('mothers_name'),
        '%Occupation%' => $customer->occupation,
        '%Veteran status%' => $customer->veteran_status,
        '%Branch%' => $customer->branch,
        '%Full Military Rites%' => $customer->full_military_rites,
        '%Biography%' => $customer->biography,
        '%Marital Status%' => $customer->marital_status,
        '%Spouse%' => $customer->genFullName('spouse'),
//        '%Date Of Marriage%' => $customer->date_of_marriage,
        '%Date Of Marriage%' => $customer->formatDateTime('date_of_marriage'),
        '%Place Of Marriage%' => $customer->place_of_marriage,
        '%Military Veteran%' => $customer->military_veteran,
//        '%Spouse Date of Death%' => $customer->spouse_date_of_death,
        '%Spouse Date of Death%' => $customer->formatDateTime('spouse_date_of_death'),
        '%Survived By%' => $customer->survived_by,
        '%Preceded in Death By%' => $customer->preceded_in_death_by,
        '%Address%' => $customer->genFullAddress(),
        '%Doctor%' => $customer->doctors_name,
        '%SSN%' => $customer->ssn,
        '%Highest Level of Education%' => $customer->highest_level_of_education,
        '%Informant Name &amp; Address%' => $customer->informant_name_f .' '.$customer->informant_name_l . ' ' . $customer->genFullInformantAddress(),
        '%Informant Name%' => $customer->informant_name_f .' '.$customer->informant_name_l,
        '%Informant Relationship%' => $customer->informant_relationship,
        '%Informant Phone%' => $customer->informant_phone,
        '%Informant Address%' => $customer->informant_name_address,
        '%Newspaper/Radio 1%' => $newspaper_radio1_value,
        '%Newspaper/Radio 2%' => $newspaper_radio2_value,
        '%Newspaper/Radio 3%' => $newspaper_radio3_value,
        '%Newspaper/Radio 4%' => $newspaper_radio4_value,
        '%Newspaper/Radio 5%' => $newspaper_radio5_value,
        '%Newspaper/Radio 6%' => $newspaper_radio6_value,
        '%Submit Pic with Obit%' => $customer->submit_pic_with_obit,
        
        '%Interment City%' => $customer->interment_city,
        '%Interment Country%' => $countries[$customer->interment_country],
        '%Interment State%' => $customer->interment_state,
        
        '%Music Selection 1%' => $customer->music_selection1,
        '%Music Selection 2%' => $customer->music_selection2,
        '%Music Selection 3%' => $customer->music_selection3,
        '%Music Selection 4%' => $customer->music_selection4,
        '%Music Selection 5%' => $customer->music_selection5,
        
        '%Pallbearers%' => $customer->pallbearers,
        '%Pallbearer 2%' => $customer->pallbearer2,
        '%Pallbearer 3%' => $customer->pallbearer3,
        '%Pallbearer 4%' => $customer->pallbearer4,
        '%Pallbearer 5%' => $customer->pallbearer5,
        '%Pallbearer 6%' => $customer->pallbearer6,
        '%Pallbearer 7%' => $customer->pallbearer7,
        '%Pallbearer 8%' => $customer->pallbearer8,
        
        '%Special Music%' => $customer->special_music,
        
        '%Contacts%' => $contacts,
        '%Products (with internal notes)%' => $products1,
        '%Products (invoice notes only)%' => $products2,
        '%Products (Services)%' => $products3,
        '%Products (Merchandise)%' => $products4,
        '%Products (Cash Advances)%' => $products5,
        '%Payments%' => $payments,
        '%Total Funeral Charges%' => number_format($total_funeral_charges, 2),
        '%Sales Tax%' => number_format($sales_tax, 2),
        '%Total Cash Advances%' => number_format($total_cash_advances, 2),
        '%Complete Total%' => number_format($complete_total, 2),
        '%Total Payments Received%' => number_format($total_payments_received, 2),
//        '%Credit Amount%' => number_format($credit_amount, 2),
        '%Discount%' => number_format($discount, 2),
        '%Balance Due%' => number_format($balance_due, 2),
        '%Statement Date%' => date('m/d/Y', time()),
        '%Notes%' => $notes,
//        '%Summary_of_payments%' => $payments,
        '%Logo%'=>$logo,
    );
    
    $document = str_replace(array_keys($placeHolds), array_values($placeHolds), $template->templates);

    if ($template->name == 'Contract') {
//      $document .= '<p>Products Total: $' . number_format($productTotalPrice, 2) .'</p>';
    }
    
    return $document;
  }
  
  public function actionDocumentChangeEmailAddr($id){
    $document = Document::model()->findByPk($id);
    $document->email_address_alt = $_POST['email_address_alt'];
    $document->save();
    
    echo 'Saved.';
    exit;
  }
  
  public function actionDocumentSendEmail($id){
    $document = Document::model()->findByPk($id);
    $template = Template::model()->findByPk($document->template_id);
    
    $data = array();
    $data['email_addr'] = $document->email_address_alt ? $document->email_address_alt : $template->email_address;
    $data['email_text'] = $template->email_text;
    
    echo json_encode($data);
    exit;
  }
  
  function actionDocumentSendEmailConfirm($id){
    $document = Document::model()->findByPk($id);
    $template = Template::model()->findByPk($document->template_id);
    $customer= $this->loadModel($document->customer_id);
//    $toEmailAddress = $document->email_address_alt ? $document->email_address_alt : $template->email_address;
    $toEmailAddress =  $_POST['email_addr'];
    
    $fileName = $this->actiondocumentdownload($document->customer_id, $document->template_id, false);
    
    $company_id = Yii::app()->user->company_id;
    $emailConfig = EmailConfig::load($company_id);
    
    $mail = new PHPMailer();
    if ($emailConfig->send_type == 'SMTP') {//SMTP
      $mail->IsSMTP();
      $mail->Port = $emailConfig->smtp_port ? $emailConfig->smtp_port : 25;
      
      if ($emailConfig->smtp_ssl) {
        $mail->SMTPSecure = 'ssl';
      }
      
      $mail->Host = $emailConfig->smtp_server;
      if ($emailConfig->smtp_auth) {
        $mail->SMTPAuth = true;
        $mail->Username = $emailConfig->smtp_user; 
        $mail->Password = $emailConfig->smtp_pass;
      }
    } else {//sendmail
      $mail->IsMail(); 
    }
    
    $mail->CharSet = "utf-8";
    $mail->Encoding = "base64"; 
    $mail->SetFrom($emailConfig->from_address, $emailConfig->from_name);
    $mail->AddAddress($toEmailAddress);
    $mail->Subject = 'Funeral Document';
    $mail->Body = $_POST['email_text'];
    
    $mail->AddAttachment($fileName, basename($fileName));
     
    $mail->IsHTML(false);
    $ret = $mail->Send();
    
    //Add notes
    $note = new Notes();
    $note->parent_type = 'customer';
    $note->parent_id = $customer->id;
    $note->company_id = Yii::app()->user->company_id;
    $note->subject = 'Send email';
    $note->body = basename($fileName) . ' was sent to Email '. $toEmailAddress .' at '. date('m/d/Y H:i').'.';
    $note->timestamp = time();
    $note->enteredby = Yii::app()->user->id;
    $note->save();
    
    echo $ret ? 'Send.' : 'Failed.';
    exit;
  }
  
  function actionEditRetail($product_id, $customer_id){
    $modelProduct = Product::model()->findByPk($product_id);
    $_POST['data'] = str_replace(',', '', $_POST['data']);
    $modelProduct->product_retail = $_POST['data'];
    $modelProduct->save();

    $totalTax = 0;
    $totalPrice = 0;
    
    $connection = Yii::app()->db;
    $command1 = $connection->createCommand("select ifnull(p.product_retail, i.retail) as retail, i.taxable from product p, inventory i
     where p.inventory_id=i.id and p.customer_id=:customer_id order by i.name");
    $command1->bindParam(':customer_id', $customer_id);
    $productTotalPrice = $command1->query();
    while($row = $productTotalPrice->read()){
      $totalPrice += $row['retail'];
      if($row['taxable'] == 1){
        $totalTax += $row['retail'];
        $taxRate = Config::loadTaxByCompany(Yii::app()->user->company_id);
        $tax = $totalTax * $taxRate;
      }
    }
    
    //$total_payments
    $command2 = $connection->createCommand("select sum(amount) from payment where customer_id=:customer_id and type='payment'");
    $command2->bindParam(':customer_id', $customer_id);
    $total_payments = $command2->queryScalar();
    $total_payments = $total_payments != '' ||  $total_payments != null ? $total_payments : '0';
    
    //$tatal_balances
    $command3 = $connection->createCommand("select sum(amount) from payment where customer_id=:customer_id and type != 'payment'");
    $command3->bindParam(':customer_id', $customer_id);
    $tatal_balances = $command3->queryScalar();
    $total_balances = $tatal_balances != '' ||  $tatal_balances != null ? $tatal_balances : '0';
   
    //count $totalPrice and $tax
//    $totalPrices = number_format($totalPrice + $tax, 2);
    $totalPrices = number_format($totalPrice + $tax - $total_payments - $tatal_balances, 2);
    $tax = number_format($tax, 2);
    
    echo json_encode(array('tax'=>$tax, 'totalPrices'=>$totalPrices));
    exit;
  }
  
  
//  function actionCustomersExport($customer_id){
//    $head = array();
//    
//    //GROUP1
//    $head['A'] = 'ID';
//    $head['B'] = 'CaseNumber';
//    $head['C'] = 'DeceasedsName';
//    $head['D'] = 'DateOfBirth';
//    $head['E'] = 'DateOfDeath';
//    $head['F'] = 'ServiceLocation';
//    $head['G'] = 'ServiceDate';
//    $head['H'] = 'ServiceTime';
//    $head['I'] = 'ClergyFullName';
//    $head['J'] = 'IntermentPlace';
//    
//    $head['K'] = 'PlaceOfBirth';
//    $head['L'] = 'PlaceOfDeath';
//    $head['M'] = 'Age';
//    $head['N'] = 'AgeMonths';
//    $head['O'] = 'AgeYears';
//    $head['P'] = 'AgeDays';
//    $head['Q'] = 'ClergyFullName2';
//    $head['R'] = 'Organization1';
//    $head['S'] = 'Organization2';
//    $head['T'] = 'Organization3';
//    
//    $head['U'] = 'Organization4';
//    $head['V'] = 'Organization5';
//    $head['W'] = 'DeceasedsMother';
//    $head['X'] = 'DeceasedsFather';
//    $head['Y'] = 'Spouse';
//    $head['Z'] = 'Child1';
//    
//    
//    //GROUP2
//    $head['AA'] = 'Child2';
//    $head['AB'] = 'Child3';
//    $head['AC'] = 'Child4';
//    $head['AD'] = 'Child5';
//    $head['AE'] = 'SongType';
//    $head['AF'] = 'MusicSelection1';
//    $head['AG'] = 'MusicSelection2';
//    $head['AH'] = 'MusicSelection3';
//    $head['AI'] = 'MusicSelection4';
//    $head['AJ'] = 'MusicSelection5';
//    
//    $head['AK'] = 'SpecialSong1';
//    $head['AL'] = 'RenderedBy1';
//    $head['AM'] = 'SpecialSong2';
//    $head['AN'] = 'RenderedBy2';
//    $head['AO'] = 'SpecialSong3';
//    $head['AP'] = 'RenderedBy3';
//    $head['AQ'] = 'Pallbearer1';
//    $head['AR'] = 'Pallbearer2';
//    $head['AS'] = 'Pallbearer3';
//    $head['AT'] = 'Pallbearer4';
//    
//    $head['AU'] = 'Pallbearer5';
//    $head['AV'] = 'Pallbearer6';
//    $head['AW'] = 'Pallbearer7';
//    $head['AX'] = 'Pallbearer8';
//    $head['AY'] = 'SocietyAttending1';
//    $head['AZ'] = 'SocietyAttending2';
//    
//    //GROUP3
//    $head['BA'] = 'SocietyAttending3';
//    $head['BB'] = 'SocietyAttending4';
//    $head['BC'] = 'SocietyAttending5';
//    $head['BD'] = 'IntermentSection';
//    $head['BE'] = 'IntermentBlock';
//    $head['BF'] = 'IntermentLot';
//    $head['BG'] = 'IntermentCity';
//    $head['BH'] = 'IntermentCounty';
//    $head['BI'] = 'IntermentState';
//    $head['BJ'] = 'IntermentTime';
//    
//    $head['BK'] = 'IntermentDate';
//    $head['BL'] = 'BranchOfService';
//    $head['BM'] = 'ServiceNumber';
//    $head['BN'] = 'DateEnteredService';
//    $head['BO'] = 'PlaceEnteredService';
//    $head['BP'] = 'DateDischarged';
//    $head['BQ'] = 'PlaceDischarged';
//    $head['BR'] = 'RankDischarged';
//    $head['BS'] = 'StationWithinUS1';
//    $head['BT'] = 'StationWithinUS2';
//    
//    $head['BU'] = 'StationWithinUS3';
//    $head['BV'] = 'StationWithinUS4';
//    $head['BW'] = 'StationWithinUS5';
//    $head['BX'] = 'DepartureDate';
//    $head['BY'] = 'DeparturePlace';
//    $head['BZ'] = 'ReturnDate';
//    
//    //GROUP4
//    $head['CA'] = 'ReturnPlace';
//    $head['CB'] = 'DistinguishedServiceCross';
//    $head['CC'] = 'PurpleHeart';
//    $head['CD'] = 'SilverStar';
//    $head['CE'] = 'MedalOfHonor';
//    $head['CF'] = 'AirForceCross';
//    $head['CG'] = 'NavyCross';
//    $head['CH'] = 'BronzeStar';
//    $head['CI'] = 'MedalOther';
//    $head['CJ'] = 'ProvidingHonors';
//    
//    $head['CK'] = 'FiringParty1';
//    $head['CL'] = 'FiringParty2';
//    $head['CM'] = 'FiringParty3';
//    $head['CN'] = 'FiringParty4';
//    $head['CO'] = 'FiringParty5';
//    $head['CP'] = 'FiringParty6';
//    $head['CQ'] = 'FiringParty7';
//    $head['CR'] = 'FiringParty8';
//    $head['CS'] = 'Chaplain';
//    $head['CT'] = 'NCOIC';
//    
//    $head['CU'] = 'OIC';
//    $head['CV'] = 'BuglarAndOrColorGuard';
//    $head['CW'] = 'Religion';
//    $head['CX'] = 'ResidenceFullAddress';
//    $head['CY'] = 'MaritalStatus';
//    $head['CZ'] = 'Occupation';
//    
//    //GROUP5
//    $head['DA'] = 'Sibling1';
//    $head['DB'] = 'Sibling2';
//    $head['DC'] = 'Sibling3';
//    $head['DD'] = 'Sibling4';
//    $head['DE'] = 'Sibling5';
//    $head['DF'] = 'Sibling6';
//    $head['DG'] = 'Sibling7';
//    $head['DH'] = 'Sibling8';
//    $head['DI'] = 'Sibling9';
//    $head['DJ'] = 'Sibling10';
//    
//    $head['DK'] = 'FamilyContact';
//    $head['DL'] = 'FamilyContactFullAddress';
//    $head['DM'] = 'Obit';
//    $head['DN'] = 'UserDefined1';
//    $head['DO'] = 'UserDefined2';
//    $head['DP'] = 'UserDefined3';
//    $head['DQ'] = 'UserDefined4';
//    $head['DR'] = 'UserDefined5';
//    $head['DS'] = 'UserDefined6';
//    $head['DT'] = 'UserDefined7';
//    
//    $head['DU'] = 'UserDefined8';
//    $head['DV'] = 'UserDefined9';
//    $head['DW'] = 'UserDefined10';
//    
//    $excelData[0][] = $head;
//
//    $connection = Yii::app()->db;
//    $command = $connection->createCommand("select * from customer where id = :id");
//    $command->bindParam(':id', $customer_id);
//    $dataCustomers = $command->query();
//    
//    $totalCount = 0;
//    while($row = $dataCustomers->read())
//    {
//        $datas	= array();
//        
//        //GROUP1
//        $datas['id'] = $row['id'];
//        $datas['case_number'] = array(
//            'value' => $row['case_number'],
//            'type' => 'string',
//        );
//        $datas['deceaseds_name'] = '';
//        $datas['date_of_birth'] = ($row['date_of_birth'] != '') ? date('l, M d, Y', strtotime($row['date_of_birth'])) : '';
//        $datas['date_of_death'] = ($row['date_of_death'] != '') ? date('l, M d, Y', strtotime($row['date_of_death'])) : '';
//        $datas['location_of_funeral_service'] = $row['location_of_funeral_service'];
//        $datas['funeral_service_date'] = ($row['funeral_service_date'] != '') ? date('l, M d, Y', strtotime($row['funeral_service_date'])) : '';
//        $datas['funeral_service_time'] = $row['funeral_service_time_h'].' : '.$row['funeral_service_time_m'].' '.$row['funeral_service_time_z'] ;
//        $datas['full_legal_name'] = $row['full_legal_name'];
//        $datas['interment_place'] = '';
//        
//        $datas['place_of_birth'] = $row['place_of_birth'];
//        $datas['place_of_death'] = $row['place_of_death'];
//        $datas['age'] = $row['age'];
//        $datas['age_months'] = '';
//        $datas['age_years'] = '';
//        $datas['age_days'] = '';
//        $datas['clergy_full_name2'] = '';
//        $datas['organization1'] = '';
//        $datas['organization2'] = '';
//        $datas['organization3'] = '';
//        
//        $datas['organization4'] = '';
//        $datas['organization5'] = '';
//        $datas['deceaseds_mother'] = '';
//        $datas['deceaseds_father'] = '';
//        $datas['spouse'] = $row['spouse_f'].' '.$row['spouse_m'].' '.$row['spouse_l'];
//        $datas['child1'] = '';
//        
//        //GROUP2
//        $datas['child2'] = '';
//        $datas['child3'] = '';
//        $datas['child4'] = '';
//        $datas['child5'] = '';
//        $datas['song_type'] = '';
//        $datas['music_selection1'] = '';
//        $datas['music_selection2'] = '';
//        $datas['music_selection3'] = '';
//        $datas['music_selection4'] = '';
//        $datas['music_selection5'] = '';
//        
//        $datas['special_song1'] = $row['special_music'];
//        $datas['rendered_by1'] = '';
//        $datas['special_song2'] = '';
//        $datas['rendered_by2'] = '';
//        $datas['special_song3'] = '';
//        $datas['rendered_by3'] = '';
//        
//        $pallbearers = explode(',', $row['pallbearers']);
//        if(count($pallbearers) > 7){
//          $datas['pallbearer1'] = trim($pallbearers[0]);
//          $datas['pallbearer2'] = trim($pallbearers[1]);
//          $datas['pallbearer3'] = trim($pallbearers[2]);
//          $datas['pallbearer4'] = trim($pallbearers[3]);
//
//          $datas['pallbearer5'] = trim($pallbearers[4]);
//          $datas['pallbearer6'] = trim($pallbearers[5]);
//          $datas['pallbearer7'] = trim($pallbearers[6]);
//          
//          for($i=0; $i<=6; $i++){
//            array_shift($pallbearers);
//          }
//          
//          $datas['pallbearer8'] = implode(',', $pallbearers);
//        }else{
//          $datas['pallbearer1'] = trim($pallbearers[0]);
//          $datas['pallbearer2'] = trim($pallbearers[1]);
//          $datas['pallbearer3'] = trim($pallbearers[2]);
//          $datas['pallbearer4'] = trim($pallbearers[3]);
//
//          $datas['pallbearer5'] = trim($pallbearers[4]);
//          $datas['pallbearer6'] = trim($pallbearers[5]);
//          $datas['pallbearer7'] = trim($pallbearers[6]);
//          $datas['pallbearer8'] = trim($pallbearers[7]);
//        }
//        
//        $datas['society_attending1'] = '';
//        $datas['society_attending2'] = '';
//        
//        //GROUP 3
//        $datas['society_attending3'] = '';
//        $datas['society_attending4'] = '';
//        $datas['society_attending5'] = '';
//        $datas['interment_section'] = '';
//        $datas['interment_block'] = '';
//        $datas['interment_lot'] = '';
//        $datas['interment_city'] = '';
//        $datas['interment_country'] = '';
//        $datas['interment_state'] = '';
//        $datas['interment_time'] = '';
//        
//        $datas['interment_date'] = '';
//        $datas['branch_of_service'] = '';
//        $datas['service_number'] = '';
////        $datas['date_enter_service'] = $row['funeral_service_date'];
//        $datas['date_enter_service'] = '';
//        $datas['place_enter_service'] = $row['location_of_funeral_service'];
//        $datas['date_discharged'] = '';
//        $datas['place_discharged'] = '';
//        $datas['rank_discharged'] = '';
//        $datas['station_within_US1'] = '';
//        $datas['station_within_US2'] = '';
//        
//        $datas['station_within_US3'] = '';
//        $datas['station_within_US4'] = '';
//        $datas['station_within_US5'] = '';
//        $datas['departure_date'] = '';
//        $datas['departure_place'] = '';
//        $datas['return_date'] = '';
//        
//        //GROUP4
//        $datas['return_place'] = '';
//        $datas['distinguished_service_cross'] = '';
//        $datas['purple_heart'] = '';
//        $datas['silver_star'] = '';
//        $datas['medal_of_honor'] = '';
//        $datas['air_force_cross'] = '';
//        $datas['navy_cross'] = '';
//        $datas['bronze_star'] = '';
//        $datas['medal_other'] = '';
//        $datas['providing_honors'] = '';
//        
//        $datas['firing_party1'] = '';
//        $datas['firing_party2'] = '';
//        $datas['firing_party3'] = '';
//        $datas['firing_party4'] = '';
//        $datas['firing_party5'] = '';
//        $datas['firing_party6'] = '';
//        $datas['firing_party7'] = '';
//        $datas['firing_party8'] = '';
//        $datas['chaplain'] = '';
//        $datas['ncoic'] = '';
//        
//        $datas['oic'] = '';
//        $datas['buglar_and_or_color_guard'] = '';
//        $datas['religion'] = '';
//        $datas['residence_full_address'] = '';
//        $datas['marital_status'] = $row['marital_status'];
//        $datas['occupation'] = $row['occupation'];
//        
//        //GROUP6
//        $datas['sibling1'] = '';
//        $datas['sibling2'] = '';
//        $datas['sibling3'] = '';
//        $datas['sibling4'] = '';
//        $datas['sibling5'] = '';
//        $datas['sibling6'] = '';
//        $datas['sibling7'] = '';
//        $datas['sibling8'] = '';
//        $datas['sibling9'] = '';
//        $datas['sibling10'] = '';
//        
//        $datas['family_contact'] = '';
//        $datas['family_contact_full_address'] = '';
//        $datas['name_for_obituary'] = $row['name_for_obituary'];
//        $datas['user_defined1'] = '';
//        $datas['user_defined2'] = '';
//        $datas['user_defined3'] = '';
//        $datas['user_defined4'] = '';
//        $datas['user_defined5'] = '';
//        $datas['user_defined6'] = '';
//        $datas['user_defined7'] = '';
//        
//        $datas['user_defined8'] = '';
//        $datas['user_defined9'] = '';
//        $datas['user_defined10'] = '';
//        
//        $excelData[0][] = $datas;
//        $totalCount ++;
//    } 
//
//    $excelData[0][] = array('Total: ', $totalCount);
//    
//    
//    $dir = dirname($_SERVER['SCRIPT_FILENAME']);
//    $fileName = $dir.'/files/customers/Guest_Book_'. time() .'.xlsx';
//    
//    include_once  $dir.'/protected/components/RenderPHPExcel.php';
//    
//    new RenderPHPExcel($excelData, array('Sheet1'), $fileName);
//
//    /* 
//      * Download the file.
//      */
//    header('Content-Type: application/force-download');
//    header('Content-Disposition: attachment; filename='. basename($fileName));
//    readfile($fileName);
//    exit;
//  }
      
//  function actionCustomersExport($customer_id){
//    $head = array();
//    $fieldTypeLen = array();
//    
//    //GROUP1
//    $head['A'] = 'Case Number';
//    $head['B'] = 'Deceaseds Name';
//    $head['C'] = 'Date Of Birth';
//    $head['D'] = 'Date Of Death';
//    $head['E'] = 'Service Location';
//    $head['F'] = 'Service Date';
//    $head['G'] = 'Service Time';
//    $head['H'] = 'Clergy Full Name';
//    $head['I'] = 'Interment Place';
//    $head['J'] = 'Place Of Birth';
//    
//    $head['K'] = 'Place Of Death';
//    $head['L'] = 'Age';
//    $head['M'] = 'Age Months';
//    $head['N'] = 'Age Years';
//    $head['O'] = 'Age Days';
//    $head['P'] = 'ClergyFullName2';
//    $head['Q'] = 'Organization1';
//    $head['R'] = 'Organization2';
//    $head['S'] = 'Organization3';
//    $head['T'] = 'Organization4';
//    
//    $head['U'] = 'Organization5';
//    $head['V'] = 'Deceaseds Mother';
//    $head['W'] = 'Deceaseds Father';
//    $head['X'] = 'Spouse';
//    $head['Y'] = 'Child1';
//    $head['Z'] = 'Child2';
//    
//    
//    //GROUP2
//    $head['AA'] = 'Child3';
//    $head['AB'] = 'Child4';
//    $head['AC'] = 'Child5';
//    $head['AD'] = 'Song Type';
//    $head['AE'] = 'MusicSelection1';
//    $head['AF'] = 'MusicSelection2';
//    $head['AG'] = 'MusicSelection3';
//    $head['AH'] = 'MusicSelection4';
//    $head['AI'] = 'MusicSelection5';
//    $head['AJ'] = 'SpecialSong1';
//    
//    $head['AK'] = 'RenderedBy1';
//    $head['AL'] = 'SpecialSong2';
//    $head['AM'] = 'RenderedBy2';
//    $head['AN'] = 'SpecialSong3';
//    $head['AO'] = 'RenderedBy3';
//    $head['AP'] = 'Pallbearer1';
//    $head['AQ'] = 'Pallbearer2';
//    $head['AR'] = 'Pallbearer3';
//    $head['AS'] = 'Pallbearer4';
//    $head['AT'] = 'Pallbearer5';
//    
//    $head['AU'] = 'Pallbearer6';
//    $head['AV'] = 'Pallbearer7';
//    $head['AW'] = 'Pallbearer8';
//    $head['AX'] = 'SocietyAttending1';
//    $head['AY'] = 'SocietyAttending2';
//    $head['AZ'] = 'SocietyAttending3';
//    
//    //GROUP3
//    $head['BA'] = 'SocietyAttending4';
//    $head['BB'] = 'SocietyAttending5';
//    $head['BC'] = 'Interment Section';
//    $head['BD'] = 'Interment Block';
//    $head['BE'] = 'Interment Lot';
//    $head['BF'] = 'Interment City';
//    $head['BG'] = 'Interment County';
//    $head['BH'] = 'Interment State';
//    $head['BI'] = 'Interment Time';
//    $head['BJ'] = 'Interment Date';
//    
//    $head['BK'] = 'Branch Of Service';
//    $head['BL'] = 'Service Number';
//    $head['BM'] = 'Date Entered Service';
//    $head['BN'] = 'Place Entered Service';
//    $head['BO'] = 'Date Discharged';
//    $head['BP'] = 'Place Discharged';
//    $head['BQ'] = 'Rank Discharged';
//    $head['BR'] = 'StationWithinUS1';
//    $head['BS'] = 'StationWithinUS2';
//    $head['BT'] = 'StationWithinUS3';
//    
//    $head['BU'] = 'StationWithinUS4';
//    $head['BV'] = 'StationWithinUS5';
//    $head['BW'] = 'Departure Date';
//    $head['BX'] = 'Departure Place';
//    $head['BY'] = 'Return Date';
//    $head['BZ'] = 'Return Place';
//    
//    //GROUP4
//    $head['CA'] = 'Distinguished Service Cross';
//    $head['CB'] = 'Purple Heart';
//    $head['CC'] = 'Silver Star';
//    $head['CD'] = 'Medal Of Honor';
//    $head['CE'] = 'Air Force Cross';
//    $head['CF'] = 'Navy Cross';
//    $head['CG'] = 'Bronze Star';
//    $head['CH'] = 'Medal Other';
//    $head['CI'] = 'Providing Honors';
//    $head['CJ'] = 'FiringParty1';
//    
//    $head['CK'] = 'FiringParty2';
//    $head['CL'] = 'FiringParty3';
//    $head['CM'] = 'FiringParty4';
//    $head['CN'] = 'FiringParty5';
//    $head['CO'] = 'FiringParty6';
//    $head['CP'] = 'FiringParty7';
//    $head['CQ'] = 'FiringParty8';
//    $head['CR'] = 'Chaplain';
//    $head['CS'] = 'NCOIC';
//    $head['CT'] = 'OIC';
//    
//    $head['CU'] = 'Buglar And Or Color Guard';
//    $head['CV'] = 'Religion';
//    $head['CW'] = 'Residence Full Address';
//    $head['CX'] = 'Marital Status';
//    $head['CY'] = 'Occupation';
//    $head['CZ'] = 'Sibling1';
//    
//    //GROUP5
//    $head['DA'] = 'Sibling2';
//    $head['DB'] = 'Sibling3';
//    $head['DC'] = 'Sibling4';
//    $head['DD'] = 'Sibling5';
//    $head['DE'] = 'Sibling6';
//    $head['DF'] = 'Sibling7';
//    $head['DG'] = 'Sibling8';
//    $head['DH'] = 'Sibling9';
//    $head['DI'] = 'Sibling10';
//    $head['DJ'] = 'Family Contact';
//    
//    $head['DK'] = 'Family Contact Full Address';
//    $head['DL'] = 'Obit';
//    $head['DM'] = 'UserDefined1';
//    $head['DN'] = 'UserDefined2';
//    $head['DO'] = 'UserDefined3';
//    $head['DP'] = 'UserDefined4';
//    $head['DQ'] = 'UserDefined5';
//    $head['DR'] = 'UserDefined6';
//    $head['DS'] = 'UserDefined7';
//    $head['DT'] = 'UserDefined8';
//    
//    $head['DU'] = 'UserDefined9';
//    $head['DV'] = 'UserDefined10';
//    
//    $excelData[0][] = $head;
//    
//    
//    
//    //GROUP1
//    $fieldTypeLen['A'] = 'Text / 255';
//    $fieldTypeLen['B'] = 'Text / 255';
//    $fieldTypeLen['C'] = 'Date/Time / 8';
//    $fieldTypeLen['D'] = 'Date/Time / 8';
//    $fieldTypeLen['E'] = 'Text / 255';
//    $fieldTypeLen['F'] = 'Date/Time / 8';
//    $fieldTypeLen['G'] = 'Text / 255';
//    $fieldTypeLen['H'] = 'Text / 255';
//    $fieldTypeLen['I'] = 'Text / 255';
//    $fieldTypeLen['J'] = 'Text / 255';
//    
//    $fieldTypeLen['K'] = 'Text / 255';
//    $fieldTypeLen['L'] = 'Text / 255';
//    $fieldTypeLen['M'] = 'null';
//    $fieldTypeLen['N'] = 'null';
//    $fieldTypeLen['O'] = 'null';
//    $fieldTypeLen['P'] = 'Text / 255';
//    $fieldTypeLen['Q'] = 'null';
//    $fieldTypeLen['R'] = 'null';
//    $fieldTypeLen['S'] = 'null';
//    $fieldTypeLen['T'] = 'null';
//    
//    $fieldTypeLen['U'] = 'null';
//    $fieldTypeLen['V'] = 'Text / 255';
//    $fieldTypeLen['W'] = 'Text / 255';
//    $fieldTypeLen['X'] = 'Text / 255';
//    $fieldTypeLen['Y'] = 'null';
//    $fieldTypeLen['Z'] = 'null';
//    
//    
//    //GROUP2
//    $fieldTypeLen['AA'] = 'null';
//    $fieldTypeLen['AB'] = 'null';
//    $fieldTypeLen['AC'] = 'null';
//    $fieldTypeLen['AD'] = 'null';
//    $fieldTypeLen['AE'] = 'Text / 255';
//    $fieldTypeLen['AF'] = 'Text / 255';
//    $fieldTypeLen['AG'] = 'Text / 255';
//    $fieldTypeLen['AH'] = 'Text / 255';
//    $fieldTypeLen['AI'] = 'Text / 255';
//    $fieldTypeLen['AJ'] = 'null';
//    
//    $fieldTypeLen['AK'] = 'null';
//    $fieldTypeLen['AL'] = 'null';
//    $fieldTypeLen['AM'] = 'null';
//    $fieldTypeLen['AN'] = 'null';
//    $fieldTypeLen['AO'] = 'null';
//    $fieldTypeLen['AP'] = 'Text / 255';
//    $fieldTypeLen['AQ'] = 'Text / 255';
//    $fieldTypeLen['AR'] = 'Text / 255';
//    $fieldTypeLen['AS'] = 'Text / 255';
//    $fieldTypeLen['AT'] = 'Text / 255';
//    
//    $fieldTypeLen['AU'] = 'Text / 255';
//    $fieldTypeLen['AV'] = 'Text / 255';
//    $fieldTypeLen['AW'] = 'Text / 255';
//    $fieldTypeLen['AX'] = 'null';
//    $fieldTypeLen['AY'] = 'null';
//    $fieldTypeLen['AZ'] = 'null';
//    
//    //GROUP3
//    $fieldTypeLen['BA'] = 'null';
//    $fieldTypeLen['BB'] = 'null';
//    $fieldTypeLen['BC'] = 'null';
//    $fieldTypeLen['BD'] = 'null';
//    $fieldTypeLen['BE'] = 'null';
//    $fieldTypeLen['BF'] = 'Text / 255';
//    $fieldTypeLen['BG'] = 'Text / 255';
//    $fieldTypeLen['BH'] = 'Text / 255';
//    $fieldTypeLen['BI'] = 'Text / 255';
//    $fieldTypeLen['BJ'] = 'Text / 255';
//    
//    $fieldTypeLen['BK'] = 'null';
//    $fieldTypeLen['BL'] = 'null';
//    $fieldTypeLen['BM'] = 'null';
//    $fieldTypeLen['BN'] = 'null';
//    $fieldTypeLen['BO'] = 'null';
//    $fieldTypeLen['BP'] = 'null';
//    $fieldTypeLen['BQ'] = 'null';
//    $fieldTypeLen['BR'] = 'null';
//    $fieldTypeLen['BS'] = 'null';
//    $fieldTypeLen['BT'] = 'null';
//    
//    $fieldTypeLen['BU'] = 'null';
//    $fieldTypeLen['BV'] = 'null';
//    $fieldTypeLen['BW'] = 'null';
//    $fieldTypeLen['BX'] = 'null';
//    $fieldTypeLen['BY'] = 'null';
//    $fieldTypeLen['BZ'] = 'null';
//    
//    //GROUP4
//    $fieldTypeLen['CA'] = 'null';
//    $fieldTypeLen['CB'] = 'null';
//    $fieldTypeLen['CC'] = 'null';
//    $fieldTypeLen['CD'] = 'null';
//    $fieldTypeLen['CE'] = 'null';
//    $fieldTypeLen['CF'] = 'null';
//    $fieldTypeLen['CG'] = 'null';
//    $fieldTypeLen['CH'] = 'null';
//    $fieldTypeLen['CI'] = 'null';
//    $fieldTypeLen['CJ'] = 'null';
//    
//    $fieldTypeLen['CK'] = 'null';
//    $fieldTypeLen['CL'] = 'null';
//    $fieldTypeLen['CM'] = 'null';
//    $fieldTypeLen['CN'] = 'null';
//    $fieldTypeLen['CO'] = 'null';
//    $fieldTypeLen['CP'] = 'null';
//    $fieldTypeLen['CQ'] = 'null';
//    $fieldTypeLen['CR'] = 'null';
//    $fieldTypeLen['CS'] = 'null';
//    $fieldTypeLen['CT'] = 'null';
//    
//    $fieldTypeLen['CU'] = 'null';
//    $fieldTypeLen['CV'] = 'null';
//    $fieldTypeLen['CW'] = 'null';
//    $fieldTypeLen['CX'] = '';
//    $fieldTypeLen['CY'] = 'null';
//    $fieldTypeLen['CZ'] = 'null';
//    
//    //GROUP5
//    $fieldTypeLen['DA'] = 'null';
//    $fieldTypeLen['DB'] = 'null';
//    $fieldTypeLen['DC'] = 'null';
//    $fieldTypeLen['DD'] = 'null';
//    $fieldTypeLen['DE'] = 'null';
//    $fieldTypeLen['DF'] = 'null';
//    $fieldTypeLen['DG'] = 'null';
//    $fieldTypeLen['DH'] = 'null';
//    $fieldTypeLen['DI'] = 'null';
//    $fieldTypeLen['DJ'] = 'null';
//    
//    $fieldTypeLen['DK'] = 'null';
//    $fieldTypeLen['DL'] = 'Memo / No Limit';
//    $fieldTypeLen['DM'] = 'null';
//    $fieldTypeLen['DN'] = 'null';
//    $fieldTypeLen['DO'] = 'null';
//    $fieldTypeLen['DP'] = 'null';
//    $fieldTypeLen['DQ'] = 'null';
//    $fieldTypeLen['DR'] = 'null';
//    $fieldTypeLen['DS'] = 'null';
//    $fieldTypeLen['DT'] = 'null';
//    
//    $fieldTypeLen['DU'] = 'null';
//    $fieldTypeLen['DV'] = 'null';
//    $excelData[0][] = $fieldTypeLen;
//    
//    $connection = Yii::app()->db;
//    $command = $connection->createCommand("select * from customer where id = :id");
//    $command->bindParam(':id', $customer_id);
//    $dataCustomers = $command->query();
//    
//    $totalCount = 0;
//    while($row = $dataCustomers->read())
//    {
//        $datas	= array();
//        
//        //GROUP1
//        $datas['case_number'] = array(
//            'value' => $row['case_number'],
//            'type' => 'string',
//        );
//        $datas['deceaseds_name'] = '';
//        $datas['date_of_birth'] = ($row['date_of_birth'] != '') ? date('l, M d, Y', strtotime($row['date_of_birth'])) : '';
//        $datas['date_of_death'] = ($row['date_of_death'] != '') ? date('l, M d, Y', strtotime($row['date_of_death'])) : '';
//        $datas['location_of_funeral_service'] = $row['location_of_funeral_service'];
//        $datas['funeral_service_date'] = ($row['funeral_service_date'] != '') ? date('l, M d, Y', strtotime($row['funeral_service_date'])) : '';
//        $datas['funeral_service_time'] = $row['funeral_service_time_h'].' : '.$row['funeral_service_time_m'].' '.$row['funeral_service_time_z'] ;
//        $datas['full_legal_name'] = $row['full_legal_name'];
//        $datas['interment_place'] = '';
//        
//        $datas['place_of_birth'] = $row['place_of_birth'];
//        $datas['place_of_death'] = $row['place_of_death'];
//        $datas['age'] = $row['age'];
//        $datas['age_months'] = '';
//        $datas['age_years'] = '';
//        $datas['age_days'] = '';
//        $datas['clergy_full_name2'] = '';
//        $datas['organization1'] = '';
//        $datas['organization2'] = '';
//        $datas['organization3'] = '';
//        
//        $datas['organization4'] = '';
//        $datas['organization5'] = '';
//        $datas['deceaseds_mother'] = '';
//        $datas['deceaseds_father'] = '';
//        $datas['spouse'] = $row['spouse_f'].' '.$row['spouse_m'].' '.$row['spouse_l'];
//        $datas['child1'] = '';
//        
//        //GROUP2
//        $datas['child2'] = '';
//        $datas['child3'] = '';
//        $datas['child4'] = '';
//        $datas['child5'] = '';
//        $datas['song_type'] = '';
//        $datas['music_selection1'] = '';
//        $datas['music_selection2'] = '';
//        $datas['music_selection3'] = '';
//        $datas['music_selection4'] = '';
//        $datas['music_selection5'] = '';
//        
//        $datas['special_song1'] = $row['special_music'];
//        $datas['rendered_by1'] = '';
//        $datas['special_song2'] = '';
//        $datas['rendered_by2'] = '';
//        $datas['special_song3'] = '';
//        $datas['rendered_by3'] = '';
//        
//        $pallbearers = explode(',', $row['pallbearers']);
//        if(count($pallbearers) > 7){
//          $datas['pallbearer1'] = trim($pallbearers[0]);
//          $datas['pallbearer2'] = trim($pallbearers[1]);
//          $datas['pallbearer3'] = trim($pallbearers[2]);
//          $datas['pallbearer4'] = trim($pallbearers[3]);
//
//          $datas['pallbearer5'] = trim($pallbearers[4]);
//          $datas['pallbearer6'] = trim($pallbearers[5]);
//          $datas['pallbearer7'] = trim($pallbearers[6]);
//          
//          for($i=0; $i<=6; $i++){
//            array_shift($pallbearers);
//          }
//          
//          $datas['pallbearer8'] = implode(',', $pallbearers);
//        }else{
//          $datas['pallbearer1'] = trim($pallbearers[0]);
//          $datas['pallbearer2'] = trim($pallbearers[1]);
//          $datas['pallbearer3'] = trim($pallbearers[2]);
//          $datas['pallbearer4'] = trim($pallbearers[3]);
//
//          $datas['pallbearer5'] = trim($pallbearers[4]);
//          $datas['pallbearer6'] = trim($pallbearers[5]);
//          $datas['pallbearer7'] = trim($pallbearers[6]);
//          $datas['pallbearer8'] = trim($pallbearers[7]);
//        }
//        
//        $datas['society_attending1'] = '';
//        $datas['society_attending2'] = '';
//        
//        //GROUP 3
//        $datas['society_attending3'] = '';
//        $datas['society_attending4'] = '';
//        $datas['society_attending5'] = '';
//        $datas['interment_section'] = '';
//        $datas['interment_block'] = '';
//        $datas['interment_lot'] = '';
//        $datas['interment_city'] = '';
//        $datas['interment_country'] = '';
//        $datas['interment_state'] = '';
//        $datas['interment_time'] = '';
//        
//        $datas['interment_date'] = '';
//        $datas['branch_of_service'] = '';
//        $datas['service_number'] = '';
////        $datas['date_enter_service'] = $row['funeral_service_date'];
//        $datas['date_enter_service'] = '';
//        $datas['place_enter_service'] = $row['location_of_funeral_service'];
//        $datas['date_discharged'] = '';
//        $datas['place_discharged'] = '';
//        $datas['rank_discharged'] = '';
//        $datas['station_within_US1'] = '';
//        $datas['station_within_US2'] = '';
//        
//        $datas['station_within_US3'] = '';
//        $datas['station_within_US4'] = '';
//        $datas['station_within_US5'] = '';
//        $datas['departure_date'] = '';
//        $datas['departure_place'] = '';
//        $datas['return_date'] = '';
//        
//        //GROUP4
//        $datas['return_place'] = '';
//        $datas['distinguished_service_cross'] = '';
//        $datas['purple_heart'] = '';
//        $datas['silver_star'] = '';
//        $datas['medal_of_honor'] = '';
//        $datas['air_force_cross'] = '';
//        $datas['navy_cross'] = '';
//        $datas['bronze_star'] = '';
//        $datas['medal_other'] = '';
//        $datas['providing_honors'] = '';
//        
//        $datas['firing_party1'] = '';
//        $datas['firing_party2'] = '';
//        $datas['firing_party3'] = '';
//        $datas['firing_party4'] = '';
//        $datas['firing_party5'] = '';
//        $datas['firing_party6'] = '';
//        $datas['firing_party7'] = '';
//        $datas['firing_party8'] = '';
//        $datas['chaplain'] = '';
//        $datas['ncoic'] = '';
//        
//        $datas['oic'] = '';
//        $datas['buglar_and_or_color_guard'] = '';
//        $datas['religion'] = '';
//        $datas['residence_full_address'] = '';
//        $datas['marital_status'] = $row['marital_status'];
//        $datas['occupation'] = $row['occupation'];
//        
//        //GROUP6
//        $datas['sibling1'] = '';
//        $datas['sibling2'] = '';
//        $datas['sibling3'] = '';
//        $datas['sibling4'] = '';
//        $datas['sibling5'] = '';
//        $datas['sibling6'] = '';
//        $datas['sibling7'] = '';
//        $datas['sibling8'] = '';
//        $datas['sibling9'] = '';
//        $datas['sibling10'] = '';
//        
//        $datas['family_contact'] = '';
//        $datas['family_contact_full_address'] = '';
//        $datas['name_for_obituary'] = $row['name_for_obituary'];
//        $datas['user_defined1'] = '';
//        $datas['user_defined2'] = '';
//        $datas['user_defined3'] = '';
//        $datas['user_defined4'] = '';
//        $datas['user_defined5'] = '';
//        $datas['user_defined6'] = '';
//        $datas['user_defined7'] = '';
//        
//        $datas['user_defined8'] = '';
//        $datas['user_defined9'] = '';
//        $datas['user_defined10'] = '';
//        
//        $excelData[0][] = $datas;
//        $totalCount ++;
//    } 
//
////    $excelData[0][] = array('Total: ', $totalCount);
//    
//    
//    $dir = dirname($_SERVER['SCRIPT_FILENAME']);
//    $fileName = $dir.'/files/customers/Guest_Book_'. time() .'.xlsx';
//    
//    include_once  $dir.'/protected/components/RenderPHPExcel.php';
//    
//    new RenderPHPExcel($excelData, array('Sheet1'), $fileName);
//
//    /* 
//      * Download the file.
//      */
//    header('Content-Type: application/force-download');
//    header('Content-Disposition: attachment; filename='. basename($fileName));
//    readfile($fileName);
//    exit;
//  }
  
  function actionCustomersExport($customer_id){
    $head = array();
    $fieldTypeLen = array();
    $countries = CommonFunc::countries();
    $countries[''] = '';
    
    //GROUP1
    $head['A'] = 'Case Number';
    $head['B'] = 'Deceaseds Name';
    $head['C'] = 'Date Of Birth';
    $head['D'] = 'Date Of Death';
    $head['E'] = 'Service Location';
    $head['F'] = 'Service Date';
    $head['G'] = 'Service Time';
    $head['H'] = 'Clergy Full Name';
    $head['I'] = 'Interment Place';
    $head['J'] = 'Place Of Birth';
    
    $head['K'] = 'Place Of Death';
    $head['L'] = 'Age';
    $head['M'] = 'Age Months';
    $head['N'] = 'Age Years';
    $head['O'] = 'Age Days';
    $head['P'] = 'ClergyFullName2';
    $head['Q'] = 'Organization1';
    $head['R'] = 'Organization2';
    $head['S'] = 'Organization3';
    $head['T'] = 'Organization4';
    
    $head['U'] = 'Organization5';
    $head['V'] = 'Deceaseds Mother';
    $head['W'] = 'Deceaseds Father';
    $head['X'] = 'Spouse';
    $head['Y'] = 'Child1';
    $head['Z'] = 'Child2';
    
    
    //GROUP2
    $head['AA'] = 'Child3';
    $head['AB'] = 'Child4';
    $head['AC'] = 'Child5';
    $head['AD'] = 'Song Type';
    $head['AE'] = 'MusicSelection1';
    $head['AF'] = 'MusicSelection2';
    $head['AG'] = 'MusicSelection3';
    $head['AH'] = 'MusicSelection4';
    $head['AI'] = 'MusicSelection5';
    $head['AJ'] = 'SpecialSong1';
    
    $head['AK'] = 'RenderedBy1';
    $head['AL'] = 'SpecialSong2';
    $head['AM'] = 'RenderedBy2';
    $head['AN'] = 'SpecialSong3';
    $head['AO'] = 'RenderedBy3';
    $head['AP'] = 'Pallbearer1';
    $head['AQ'] = 'Pallbearer2';
    $head['AR'] = 'Pallbearer3';
    $head['AS'] = 'Pallbearer4';
    $head['AT'] = 'Pallbearer5';
    
    $head['AU'] = 'Pallbearer6';
    $head['AV'] = 'Pallbearer7';
    $head['AW'] = 'Pallbearer8';
    $head['AX'] = 'SocietyAttending1';
    $head['AY'] = 'SocietyAttending2';
    $head['AZ'] = 'SocietyAttending3';
    
    //GROUP3
    $head['BA'] = 'SocietyAttending4';
    $head['BB'] = 'SocietyAttending5';
    $head['BC'] = 'Interment Section';
    $head['BD'] = 'Interment Block';
    $head['BE'] = 'Interment Lot';
    $head['BF'] = 'Interment City';
    $head['BG'] = 'Interment County';
    $head['BH'] = 'Interment State';
    $head['BI'] = 'Interment Time';
    $head['BJ'] = 'Interment Date';
    
    $head['BK'] = 'Branch Of Service';
    $head['BL'] = 'Service Number';
    $head['BM'] = 'Date Entered Service';
    $head['BN'] = 'Place Entered Service';
    $head['BO'] = 'Date Discharged';
    $head['BP'] = 'Place Discharged';
    $head['BQ'] = 'Rank Discharged';
    $head['BR'] = 'StationWithinUS1';
    $head['BS'] = 'StationWithinUS2';
    $head['BT'] = 'StationWithinUS3';
    
    $head['BU'] = 'StationWithinUS4';
    $head['BV'] = 'StationWithinUS5';
    $head['BW'] = 'Departure Date';
    $head['BX'] = 'Departure Place';
    $head['BY'] = 'Return Date';
    $head['BZ'] = 'Return Place';
    
    //GROUP4
    $head['CA'] = 'Distinguished Service Cross';
    $head['CB'] = 'Purple Heart';
    $head['CC'] = 'Silver Star';
    $head['CD'] = 'Medal Of Honor';
    $head['CE'] = 'Air Force Cross';
    $head['CF'] = 'Navy Cross';
    $head['CG'] = 'Bronze Star';
    $head['CH'] = 'Medal Other';
    $head['CI'] = 'Providing Honors';
    $head['CJ'] = 'FiringParty1';
    
    $head['CK'] = 'FiringParty2';
    $head['CL'] = 'FiringParty3';
    $head['CM'] = 'FiringParty4';
    $head['CN'] = 'FiringParty5';
    $head['CO'] = 'FiringParty6';
    $head['CP'] = 'FiringParty7';
    $head['CQ'] = 'FiringParty8';
    $head['CR'] = 'Chaplain';
    $head['CS'] = 'NCOIC';
    $head['CT'] = 'OIC';
    
    $head['CU'] = 'Buglar And Or Color Guard';
    $head['CV'] = 'Religion';
    $head['CW'] = 'Residence Full Address';
    $head['CX'] = 'Marital Status';
    $head['CY'] = 'Occupation';
    $head['CZ'] = 'Sibling1';
    
    //GROUP5
    $head['DA'] = 'Sibling2';
    $head['DB'] = 'Sibling3';
    $head['DC'] = 'Sibling4';
    $head['DD'] = 'Sibling5';
    $head['DE'] = 'Sibling6';
    $head['DF'] = 'Sibling7';
    $head['DG'] = 'Sibling8';
    $head['DH'] = 'Sibling9';
    $head['DI'] = 'Sibling10';
    $head['DJ'] = 'Family Contact';
    
    $head['DK'] = 'Family Contact Full Address';
    $head['DL'] = 'Obit';
    $head['DM'] = 'UserDefined1';
    $head['DN'] = 'UserDefined2';
    $head['DO'] = 'UserDefined3';
    $head['DP'] = 'UserDefined4';
    $head['DQ'] = 'UserDefined5';
    $head['DR'] = 'UserDefined6';
    $head['DS'] = 'UserDefined7';
    $head['DT'] = 'UserDefined8';
    
    $head['DU'] = 'UserDefined9';
    $head['DV'] = 'UserDefined10';
    
    $excelData[0][] = $head;
    
    
    
    //GROUP1
    $fieldTypeLen['A'] = 'Text / 255';
    $fieldTypeLen['B'] = 'Text / 255';
    $fieldTypeLen['C'] = 'Date/Time / 8';
    $fieldTypeLen['D'] = 'Date/Time / 8';
    $fieldTypeLen['E'] = 'Text / 255';
    $fieldTypeLen['F'] = 'Date/Time / 8';
    $fieldTypeLen['G'] = 'Text / 255';
    $fieldTypeLen['H'] = 'Text / 255';
    $fieldTypeLen['I'] = 'Text / 255';
    $fieldTypeLen['J'] = 'Text / 255';
    
    $fieldTypeLen['K'] = 'Text / 255';
    $fieldTypeLen['L'] = 'Text / 255';
    $fieldTypeLen['M'] = '';
    $fieldTypeLen['N'] = '';
    $fieldTypeLen['O'] = '';
    $fieldTypeLen['P'] = 'Text / 255';
    $fieldTypeLen['Q'] = '';
    $fieldTypeLen['R'] = '';
    $fieldTypeLen['S'] = '';
    $fieldTypeLen['T'] = '';
    
    $fieldTypeLen['U'] = '';
    $fieldTypeLen['V'] = 'Text / 255';
    $fieldTypeLen['W'] = 'Text / 255';
    $fieldTypeLen['X'] = 'Text / 255';
    $fieldTypeLen['Y'] = '';
    $fieldTypeLen['Z'] = '';
    
    
    //GROUP2
    $fieldTypeLen['AA'] = '';
    $fieldTypeLen['AB'] = '';
    $fieldTypeLen['AC'] = '';
    $fieldTypeLen['AD'] = '';
    $fieldTypeLen['AE'] = 'Text / 255';
    $fieldTypeLen['AF'] = 'Text / 255';
    $fieldTypeLen['AG'] = 'Text / 255';
    $fieldTypeLen['AH'] = 'Text / 255';
    $fieldTypeLen['AI'] = 'Text / 255';
    $fieldTypeLen['AJ'] = '';
    
    $fieldTypeLen['AK'] = '';
    $fieldTypeLen['AL'] = '';
    $fieldTypeLen['AM'] = '';
    $fieldTypeLen['AN'] = '';
    $fieldTypeLen['AO'] = '';
    $fieldTypeLen['AP'] = 'Text / 255';
    $fieldTypeLen['AQ'] = 'Text / 255';
    $fieldTypeLen['AR'] = 'Text / 255';
    $fieldTypeLen['AS'] = 'Text / 255';
    $fieldTypeLen['AT'] = 'Text / 255';
    
    $fieldTypeLen['AU'] = 'Text / 255';
    $fieldTypeLen['AV'] = 'Text / 255';
    $fieldTypeLen['AW'] = 'Text / 255';
    $fieldTypeLen['AX'] = '';
    $fieldTypeLen['AY'] = '';
    $fieldTypeLen['AZ'] = '';
    
    //GROUP3
    $fieldTypeLen['BA'] = '';
    $fieldTypeLen['BB'] = '';
    $fieldTypeLen['BC'] = '';
    $fieldTypeLen['BD'] = '';
    $fieldTypeLen['BE'] = '';
    $fieldTypeLen['BF'] = 'Text / 255';
    $fieldTypeLen['BG'] = 'Text / 255';
    $fieldTypeLen['BH'] = 'Text / 255';
    $fieldTypeLen['BI'] = 'Text / 255';
    $fieldTypeLen['BJ'] = 'Text / 255';
    
    $fieldTypeLen['BK'] = '';
    $fieldTypeLen['BL'] = '';
    $fieldTypeLen['BM'] = '';
    $fieldTypeLen['BN'] = '';
    $fieldTypeLen['BO'] = '';
    $fieldTypeLen['BP'] = '';
    $fieldTypeLen['BQ'] = '';
    $fieldTypeLen['BR'] = '';
    $fieldTypeLen['BS'] = '';
    $fieldTypeLen['BT'] = '';
    
    $fieldTypeLen['BU'] = '';
    $fieldTypeLen['BV'] = '';
    $fieldTypeLen['BW'] = '';
    $fieldTypeLen['BX'] = '';
    $fieldTypeLen['BY'] = '';
    $fieldTypeLen['BZ'] = '';
    
    //GROUP4
    $fieldTypeLen['CA'] = '';
    $fieldTypeLen['CB'] = '';
    $fieldTypeLen['CC'] = '';
    $fieldTypeLen['CD'] = '';
    $fieldTypeLen['CE'] = '';
    $fieldTypeLen['CF'] = '';
    $fieldTypeLen['CG'] = '';
    $fieldTypeLen['CH'] = '';
    $fieldTypeLen['CI'] = '';
    $fieldTypeLen['CJ'] = '';
    
    $fieldTypeLen['CK'] = '';
    $fieldTypeLen['CL'] = '';
    $fieldTypeLen['CM'] = '';
    $fieldTypeLen['CN'] = '';
    $fieldTypeLen['CO'] = '';
    $fieldTypeLen['CP'] = '';
    $fieldTypeLen['CQ'] = '';
    $fieldTypeLen['CR'] = '';
    $fieldTypeLen['CS'] = '';
    $fieldTypeLen['CT'] = '';
    
    $fieldTypeLen['CU'] = '';
    $fieldTypeLen['CV'] = '';
    $fieldTypeLen['CW'] = '';
    $fieldTypeLen['CX'] = '';
    $fieldTypeLen['CY'] = '';
    $fieldTypeLen['CZ'] = '';
    
    //GROUP5
    $fieldTypeLen['DA'] = '';
    $fieldTypeLen['DB'] = '';
    $fieldTypeLen['DC'] = '';
    $fieldTypeLen['DD'] = '';
    $fieldTypeLen['DE'] = '';
    $fieldTypeLen['DF'] = '';
    $fieldTypeLen['DG'] = '';
    $fieldTypeLen['DH'] = '';
    $fieldTypeLen['DI'] = '';
    $fieldTypeLen['DJ'] = '';
    
    $fieldTypeLen['DK'] = '';
    $fieldTypeLen['DL'] = 'Memo / No Limit';
    $fieldTypeLen['DM'] = '';
    $fieldTypeLen['DN'] = '';
    $fieldTypeLen['DO'] = '';
    $fieldTypeLen['DP'] = '';
    $fieldTypeLen['DQ'] = '';
    $fieldTypeLen['DR'] = '';
    $fieldTypeLen['DS'] = '';
    $fieldTypeLen['DT'] = '';
    
    $fieldTypeLen['DU'] = '';
    $fieldTypeLen['DV'] = '';
    $excelData[0][] = $fieldTypeLen;
    
    $connection = Yii::app()->db;
    $command = $connection->createCommand("select * from customer where id = :id");
    $command->bindParam(':id', $customer_id);
    $dataCustomers = $command->query();
    
    $totalCount = 0;
    while($row = $dataCustomers->read())
    {
        $datas	= array();
        
        //GROUP1
        $datas['case_number'] = array(
            'value' => $row['case_number'],
            'type' => 'string',
        );
        $datas['deceaseds_name'] = $row['full_legal_name'];
        $datas['date_of_birth'] = ($row['date_of_birth'] != '') ? date('l, M d, Y', strtotime($row['date_of_birth'])) : '';
        $datas['date_of_death'] = ($row['date_of_death'] != '') ? date('l, M d, Y', strtotime($row['date_of_death'])) : '';
        $datas['location_of_funeral_service'] = $row['location_of_funeral_service'];
        $datas['funeral_service_date'] = ($row['funeral_service_date'] != '') ? date('l, M d, Y', strtotime($row['funeral_service_date'])) : '';
        $datas['funeral_service_time'] = $row['funeral_service_time_h'];
        if ($row['funeral_service_time_m'] != '') {
          $datas['funeral_service_time'] .= ':'. $row['funeral_service_time_m'];
        }
        if ($row['funeral_service_time_z'] != '') {
          $datas['funeral_service_time'] .= ' '. $row['funeral_service_time_z'];
        }
        
        $datas['full_legal_name'] = $row['full_legal_name'];
        $datas['interment_place'] = $row['disposition_place'];
        
        $datas['place_of_birth'] = $row['place_of_birth'];
        $datas['place_of_death'] = $row['place_of_death'];
        $datas['age'] = $row['age'];
        $datas['age_months'] = '';
        $datas['age_years'] = '';
        $datas['age_days'] = '';
        $datas['clergy_full_name2'] = $row['clergy_full_name2'];
        $datas['organization1'] = '';
        $datas['organization2'] = '';
        $datas['organization3'] = '';
        
        $datas['organization4'] = '';
        $datas['organization5'] = '';
        $datas['deceaseds_mother'] = $row['mothers_name_f'] .' '. $row['mothers_name_m'] .' '. $row['mothers_name_l'];
        $datas['deceaseds_father'] = $row['fathers_name_f'] .' '. $row['fathers_name_m'] .' '. $row['fathers_name_l'];
        $datas['spouse'] = $row['spouse_f'].' '.$row['spouse_m'].' '.$row['spouse_l'];
        $datas['child1'] = '';
        
        //GROUP2
        $datas['child2'] = '';
        $datas['child3'] = '';
        $datas['child4'] = '';
        $datas['child5'] = '';
        $datas['song_type'] = '';
        $datas['music_selection1'] = $row['music_selection1'];
        $datas['music_selection2'] = $row['music_selection2'];
        $datas['music_selection3'] = $row['music_selection3'];
        $datas['music_selection4'] = $row['music_selection4'];
        $datas['music_selection5'] = $row['music_selection5'];
        
        $datas['special_song1'] = $row['special_music'];
        $datas['rendered_by1'] = '';
        $datas['special_song2'] = '';
        $datas['rendered_by2'] = '';
        $datas['special_song3'] = '';
        $datas['rendered_by3'] = '';
        
//        $pallbearers = explode(',', $row['pallbearers']);
//        if(count($pallbearers) > 7){
//          $datas['pallbearer1'] = trim($pallbearers[0]);
//          $datas['pallbearer2'] = trim($pallbearers[1]);
//          $datas['pallbearer3'] = trim($pallbearers[2]);
//          $datas['pallbearer4'] = trim($pallbearers[3]);
//
//          $datas['pallbearer5'] = trim($pallbearers[4]);
//          $datas['pallbearer6'] = trim($pallbearers[5]);
//          $datas['pallbearer7'] = trim($pallbearers[6]);
//          
//          for($i=0; $i<=6; $i++){
//            array_shift($pallbearers);
//          }
//          
//          $datas['pallbearer8'] = implode(',', $pallbearers);
//        }else{
//          $datas['pallbearer1'] = trim($pallbearers[0]);
//          $datas['pallbearer2'] = trim($pallbearers[1]);
//          $datas['pallbearer3'] = trim($pallbearers[2]);
//          $datas['pallbearer4'] = trim($pallbearers[3]);
//
//          $datas['pallbearer5'] = trim($pallbearers[4]);
//          $datas['pallbearer6'] = trim($pallbearers[5]);
//          $datas['pallbearer7'] = trim($pallbearers[6]);
//          $datas['pallbearer8'] = trim($pallbearers[7]);
//        }
        $datas['pallbearers'] = $row['pallbearers'];
        $datas['pallbearer2'] = $row['pallbearer2'];
        $datas['pallbearer3'] = $row['pallbearer3'];
        $datas['pallbearer4'] = $row['pallbearer4'];
        $datas['pallbearer5'] = $row['pallbearer5'];
        $datas['pallbearer6'] = $row['pallbearer6'];
        $datas['pallbearer7'] = $row['pallbearer7'];
        $datas['pallbearer8'] = $row['pallbearer8'];
        
        $datas['society_attending1'] = '';
        $datas['society_attending2'] = '';
        
        //GROUP 3
        $datas['society_attending3'] = '';
        $datas['society_attending4'] = '';
        $datas['society_attending5'] = '';
        $datas['interment_section'] = '';
        $datas['interment_block'] = '';
        $datas['interment_lot'] = '';
        $datas['interment_city'] = $row['interment_city'];
        $datas['interment_country'] = $countries[$row['interment_country']];
        $datas['interment_state'] = $row['interment_state'];
        $datas['interment_time'] = ltrim($row['time_of_burial_h'], '0');
        if ($row['time_of_burial_m'] != '') {
          $datas['interment_time'] .= ':'. $row['time_of_burial_m'];
        }
        if ($row['time_of_burial_z'] != '') {
          $datas['interment_time'] .= ' '. $row['time_of_burial_z'];
        }
        
        $datas['interment_date'] = $row['date_of_burial'];
        $datas['branch_of_service'] = '';
        $datas['service_number'] = '';
//        $datas['date_enter_service'] = $row['funeral_service_date'];
        $datas['date_enter_service'] = '';
        $datas['place_enter_service'] = $row['location_of_funeral_service'];
        $datas['date_discharged'] = '';
        $datas['place_discharged'] = '';
        $datas['rank_discharged'] = '';
        $datas['station_within_US1'] = '';
        $datas['station_within_US2'] = '';
        
        $datas['station_within_US3'] = '';
        $datas['station_within_US4'] = '';
        $datas['station_within_US5'] = '';
        $datas['departure_date'] = '';
        $datas['departure_place'] = '';
        $datas['return_date'] = '';
        
        //GROUP4
        $datas['return_place'] = '';
        $datas['distinguished_service_cross'] = '';
        $datas['purple_heart'] = '';
        $datas['silver_star'] = '';
        $datas['medal_of_honor'] = '';
        $datas['air_force_cross'] = '';
        $datas['navy_cross'] = '';
        $datas['bronze_star'] = '';
        $datas['medal_other'] = '';
        $datas['providing_honors'] = '';
        
        $datas['firing_party1'] = '';
        $datas['firing_party2'] = '';
        $datas['firing_party3'] = '';
        $datas['firing_party4'] = '';
        $datas['firing_party5'] = '';
        $datas['firing_party6'] = '';
        $datas['firing_party7'] = '';
        $datas['firing_party8'] = '';
        $datas['chaplain'] = '';
        $datas['ncoic'] = '';
        
        $datas['oic'] = '';
        $datas['buglar_and_or_color_guard'] = '';
        $datas['religion'] = '';
        $datas['residence_full_address'] = '';
        $datas['marital_status'] = $row['marital_status'];
        $datas['occupation'] = $row['occupation'];
        
        //GROUP6
        $datas['sibling1'] = '';
        $datas['sibling2'] = '';
        $datas['sibling3'] = '';
        $datas['sibling4'] = '';
        $datas['sibling5'] = '';
        $datas['sibling6'] = '';
        $datas['sibling7'] = '';
        $datas['sibling8'] = '';
        $datas['sibling9'] = '';
        $datas['sibling10'] = '';
        
        $datas['family_contact'] = '';
        $datas['family_contact_full_address'] = '';
        $datas['name_for_obituary'] = $row['name_for_obituary'];
        $datas['user_defined1'] = '';
        $datas['user_defined2'] = '';
        $datas['user_defined3'] = '';
        $datas['user_defined4'] = '';
        $datas['user_defined5'] = '';
        $datas['user_defined6'] = '';
        $datas['user_defined7'] = '';
        
        $datas['user_defined8'] = '';
        $datas['user_defined9'] = '';
        $datas['user_defined10'] = '';
        
        $excelData[0][] = $datas;
        $totalCount ++;
    } 

//    $excelData[0][] = array('Total: ', $totalCount);
    
    
    $dir = dirname($_SERVER['SCRIPT_FILENAME']);
    $fileName = $dir.'/files/customers/Guest_Book_'. time() .'.xlsx';
    
    include_once  $dir.'/protected/components/RenderPHPExcel.php';
    
    new RenderPHPExcel($excelData, array('Sheet1'), $fileName);

    /* 
      * Download the file.
      */
    header('Content-Type: application/force-download');
    header('Content-Disposition: attachment; filename='. basename($fileName));
    readfile($fileName);
    exit;
  }

  private function _getPaymentData($customer_id, $payment_id, $type){
    $connection = Yii::app()->db;
    
    //get $
    $sql1 = "select * from payment where id=:id and customer_id=:customer_id";
    $command1 = $connection->createCommand($sql1);
    $command1->bindParam(':id', $payment_id);
    $command1->bindParam(':customer_id', $customer_id);
    $paymentData = $command1->queryRow();
    
    //get $productPrice_services
    $sql2 = "select sum(ifnull(p.product_retail, i.retail)) from product p, inventory i
             where p.inventory_id=i.id and p.customer_id= :customer_id and p.company_id=i.company_id and p.company_id=:company_id and i.category = 'Services'";
    $command2 = $connection->createCommand($sql2);
    $command2->bindParam(':customer_id', $customer_id);
    $command2->bindParam(':company_id', Yii::app()->user->company_id);
    $productPrice_services = $command2->queryScalar();

    //get $productPrice_merchandise
    $sql3 = "select sum(ifnull(p.product_retail, i.retail)) from product p, inventory i
             where p.inventory_id=i.id and p.customer_id= :customer_id and p.company_id=i.company_id and p.company_id=:company_id and i.category = 'Merchandise'";
    $command3 = $connection->createCommand($sql3);
    $command3->bindParam(':customer_id', $customer_id);
    $command3->bindParam(':company_id', Yii::app()->user->company_id);
    $productPrice_merchandise = $command3->queryScalar();
    
    //get $total_funeral_charges
    $total_funeral_charges = $productPrice_services + $productPrice_merchandise;
        
    //get $total_cash_advances
    $sql4 = "select sum(ifnull(p.product_retail, i.retail)) from product p, inventory i
             where p.inventory_id=i.id and p.customer_id= :customer_id and p.company_id=i.company_id and p.company_id=:company_id and i.category = 'Cash Advances'";
    $command4 = $connection->createCommand($sql4);
    $command4->bindParam(':company_id', Yii::app()->user->company_id);
    $command4->bindParam(':customer_id', $customer_id);
    $total_cash_advances = $command4->queryScalar();
        
    //get sales_tax
    $taxRate = Config::loadTaxByCompany(Yii::app()->user->company_id);
    $sql5 = "select (sum(ifnull(p.product_retail, i.retail)) * ". $taxRate .") from product p, inventory i
             where p.inventory_id=i.id and p.customer_id= :customer_id and p.company_id=i.company_id and p.company_id=:company_id and i.taxable=1";
    $command5 = $connection->createCommand($sql5);
    $command5->bindParam(':customer_id', $customer_id);
    $command5->bindParam(':company_id', Yii::app()->user->company_id);
    $sales_tax = $command5->queryScalar();
        
    //get discount
//    $sql6 = "select sum(amount) from payment where customer_id= :customer_id and type='credit'";
//    $command6 = $connection->createCommand($sql6);
//    $command6->bindParam(':customer_id', $customer_id);
//    $discount = $command6->queryScalar();
//    $paymentData['discount'] = number_format($discount, 2);
        
        //get $total_payments_received
//        $sql7 = "select sum(amount) from payment where customer_id=:customer_id and type='payment'";
//        $command7 = $connection->createCommand($sql7);
//        $command7->bindParam(':customer_id', $customer_id);
//        $total_payments_received = $command7->queryScalar();
        
        
  //get $balance_before_payment and $balance_due
//      $balnce_due = $total_funeral_charges + $total_cash_advances + $sales_tax - $discount - $total_payments_received;
//    $balance_before_payment = $total_funeral_charges + $total_cash_advances + $sales_tax - $discount;
//    $balance_due_account = $total_funeral_charges + $total_cash_advances + $sales_tax - $discount - $paymentData['amount'];
//    $paymentData['balance_before_payment'] = $balance_before_payment;
//    $paymentData['balance_due_account'] = $balance_due_account;
    
    $total_taxs = $total_funeral_charges + $total_cash_advances + $sales_tax;
//    
//    if($type != 'discount'){
//      $sql6 = "select amount from payment where customer_id= :customer_id and id=:payment_id";
//      $command6 = $connection->createCommand($sql6);
//      $command6->bindParam(':customer_id', $customer_id);
//      $command6->bindParam(':payment_id', $payment_id);
//      $amount_current = $command6->queryScalar();
//      
//      $paymentData['amount'] = number_format($amount, 2);
//      
//      $balance_before_payment =$total_taxs - $discount;
//      $balance_due_account = $total_taxs - $discount - $paymentData['amount'];
//      $paymentData['balance_before_payment'] = $balance_before_payment;
//      $paymentData['balance_due_account'] = $balance_due_account;
//    }
//    echo $paymentData['balance_before_payment'].'<br/>';
//    echo $paymentData['balance_due_account'].'<br/>';
//    exit;
    //get total id by the same customer
    $sql6 = "select id from payment where customer_id=:customer_id";
    $command6 = $connection->createCommand($sql6);
    $command6->bindParam(':customer_id', $customer_id);
    $total_id = $command6->queryColumn();
    
    //judge $payment_id is maxed in $total_id, if max
    $compare = $this->_compareMinValue($payment_id, $total_id);
//        var_dump($compare);
//    exit;
    
   
    if($compare == 'true'){
      if($type != 'discount'){
        $sql7 = "select amount from payment where id = :payment_id and customer_id=:customer_id";
        $command7 = $connection->createCommand($sql7);
        $command7->bindParam(':customer_id', $customer_id);
        $command7->bindParam(':payment_id', $payment_id);
        $paymentData['payment'] = $command7->queryScalar();

        $paymentData['balance_before_payment'] =  number_format($total_taxs, 2);
        $paymentData['balance_due_account'] =  number_format($total_taxs - $paymentData['payment'], 2);
      }else{
        $sql8 = "select amount from payment where id = :payment_id and customer_id=:customer_id";
        $command8 = $connection->createCommand($sql8);
        $command8->bindParam(':customer_id', $customer_id);
        $command8->bindParam(':payment_id', $payment_id);
        $paymentData['discount'] = $command8->queryScalar();

        $paymentData['balance_before_payment'] =  number_format($total_taxs, 2);
        $paymentData['balance_due_account'] =  number_format($total_taxs - $paymentData['discount'], 2);
      }
    }else{
      if($type != 'discount'){
//        $sql9 = "select sum(amount) from payment where id > :payment_id and customer_id=:customer_id";
        $sql9 = "select sum(amount) from payment where id < :payment_id and customer_id=:customer_id";
        $command9 = $connection->createCommand($sql9);
        $command9->bindParam(':customer_id', $customer_id);
        $command9->bindParam(':payment_id', $payment_id);
        $payments_total = $command9->queryScalar();

        $sql10 = "select amount from payment where id = :payment_id and customer_id=:customer_id";
        $command10 = $connection->createCommand($sql10);
        $command10->bindParam(':customer_id', $customer_id);
        $command10->bindParam(':payment_id', $payment_id);
        $paymentData['payment'] = $command10->queryScalar();

        $paymentData['balance_before_payment'] = number_format($total_taxs - $payments_total, 2);
        $paymentData['balance_due_account'] = number_format($total_taxs - $payments_total - $paymentData['payment'], 2);
      }else{
//        $sql11 = "select sum(amount) from payment where id > :payment_id and customer_id=:customer_id";
        $sql11 = "select sum(amount) from payment where id < :payment_id and customer_id=:customer_id";
        $command11 = $connection->createCommand($sql11);
        $command11->bindParam(':customer_id', $customer_id);
        $command11->bindParam(':payment_id', $payment_id);
        $payments_total = $command11->queryScalar();

        $sql12 = "select amount from payment where id = :payment_id and customer_id=:customer_id";
        $command12 = $connection->createCommand($sql12);
        $command12->bindParam(':customer_id', $customer_id);
        $command12->bindParam(':payment_id', $payment_id);
        $paymentData['discount'] = $command12->queryScalar();

        $paymentData['balance_before_payment'] = number_format($total_taxs - $payments_total, 2);
        $paymentData['balance_due_account'] = number_format($total_taxs - $payments_total - $paymentData['discount'], 2);
      }
    }
    
    //get company logo
//    $command13 = $connection->createCommand("select logo from company where id=:id");
//    $command13->bindParam(':id', Yii::app()->user->company_id);
//    $logo = $command13->queryScalar();
//    $paymentData['logo'] = $logo;
    
    //get customer name  
    $command13 = $connection->createCommand("select full_legal_name, company_id from customer where id=:id");
    $command13->bindParam(':id', $customer_id);
    $customerData = $command13->queryRow();
    
    $paymentData['customer_name'] = ucwords($customerData['full_legal_name']);
    
    //get company infomation 
    $command14 = $connection->createCommand("select * from company where id=:id");
    $command14->bindParam(':id', $customerData['company_id']);
    $companyData = $command14->queryRow();
    
    
    $paymentData['company_logo'] = $companyData['logo'];
    $paymentData['company_name'] = ucwords($companyData['name']);
    $paymentData['company_address'] = $companyData['address'];
    $paymentData['company_zip'] = $companyData['zip'];
    $paymentData['company_state'] = $companyData['state'];
    $paymentData['company_city'] = $companyData['city'];
    
    return $paymentData;
  }
  
  
  public function actionGetPayment($customer_id, $payment_id, $type){
    $paymentData = $this->_getPaymentData($customer_id, $payment_id, $type);
    
    echo json_encode($paymentData);
    exit;
  }

  public function actionPrintReceipt($customer_id, $payment_id, $type, $dowload=true){
//    echo $customer_id.'<br/>';
//    echo $payment_id.'<br/>';
//    echo $type.'<br/>';
//    exit;
    //print to pdf
    require_once(dirname(__FILE__) . '/../components/tcpdf/config/lang/eng.php');
    require_once(dirname(__FILE__) . '/../components/tcpdf/tcpdf.php');

    // create new PDF document
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    // set document information
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Zyp');
    $pdf->SetTitle('Documents');
    $pdf->SetSubject('Documents');
    $pdf->SetKeywords('Documents');

    // set default header data
    $pdf_header_title = 'Rceipt';
    $pdf_header_string = "by Funeral Home System - ". $_SERVER['HTTP_HOST'] ."\n.". $_SERVER['HTTP_HOST'];

//    $pdf->SetHeaderData('', 0, '', '');

    // set header and footer fonts
//    $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
//    $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

    // remove default header/footer
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);

    // set default monospaced font
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    //set margins
//    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
//    $pdf->SetMargins(10, 8, 10);
    $pdf->SetMargins(20, 8, 20);
//    $pdf->SetMargins(4, 0, 4);
//    $pdf->SetHeaderMargin(0);
//    $pdf->SetHeaderMargin(19);
//    $pdf->SetFooterMargin(0);
    $pdf->SetFooterMargin(10);
    
    //set auto page breaks
//    $pdf->SetAutoPageBreak(false, PDF_MARGIN_BOTTOM);
   
    //set image scale factor
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    //set some language-dependent strings
    $pdf->setLanguageArray($l);

    // ---------------------------------------------------------
     
  // set font
    $pdf->SetFont('dejavusans', '', 7);
//    $pdf->SetTopMargin(10);
    $pdf->SetTopMargin(8);
    
//    $document = $this->_populateTemplateVars($customer, $template);
    
    $paymentData = $this->_getPaymentData($customer_id, $payment_id, $type);
    
    if($type == 'discount'){
       $document='<table class="fontSize">
                    <tbody>
                      <tr>
                        <td colspan="2">'.ucwords($paymentData['company_name']).'<br/>
                          '.$paymentData['company_address'].'<br/>
                          '.($paymentData['company_city'] != '' ? $paymentData['company_city'].', ' : '').$paymentData['company_state'].' '.$paymentData['company_zip'].'
                        </td>
                        <td rowspan="2" colspan="2" style="text-align: center;">
                         '.(file_exists($paymentData['company_logo']) ? '<img id="img_logo" src="'.$paymentData['company_logo'].'" width="160" height="108"/>' : '<img id="img_logo" width="160" height="108" style="display: none;"/>').' 
                        </td>
                      </tr>
                      <tr>
                        <td colspan="2"></td>
                      </tr>
                      <tr>
                         <td colspan="2">Payer:<br/></td>
                      </tr>
                      <tr>
                         <td colspan="4">'.ucwords($paymentData['payer']).'<br/>
                                 '.$paymentData['address'].'<br/>
                                 '.$paymentData['city'].' '. $paymentData['state'].' '.$paymentData['zip'].'
                         </td>
                      </tr>
                      <tr>
                        <td style="height: 80px;"></td>
                      </tr>
                    </tbody>
                    
                    <tbody>
                      <tr><td colspan="4">-----------------------------------------------------------------------------------------------------------------------------------</td></tr>
                      <tr><td colspan="4"><br/><br/></td></tr>
                    </tbody> 
                   
                    <tbody>
                      <tr>
                        <td colspan="4" style="text-align: center;" border="0" width="100%">
                          <table class="tab" cellpadding="5">
                            <tr>
                              <td colspan="4" style="font-weight: bold; nowrap: nowrap; text-align: center;"> 
                                Receipt of payment for the services of '.ucwords($paymentData['customer_name']).'
                              </td>
                            </tr>
                            <tr>
                              <td  class="align1" width="18%" nowrap="nowrap">Payment Date:</td><td class="align2" width="33%" nowrap="nowrap">'.$paymentData['date'].'</td>
                              <td  class="align1" width="33.5%" nowrap="nowrap">Balance due before payment:</td><td class="align2" width="16.5%" nowrap="nowrap">$'.$paymentData['balance_before_payment'].'</td>
                            </tr>
                            <tr>
                              <td  class="align1" nowrap="nowrap">Method:</td><td class="align2"  nowrap="nowrap">'.$paymentData['payment_method'].'</td> 
                              <td  class="align1" nowrap="nowrap">Discount:</td><td class="align2"  nowrap="nowrap">'.$paymentData['discount'].'</td>
                            </tr>
                            <tr>
                              <td  class="align1" nowrap="nowrap">Check No.</td><td class="align2"  nowrap="nowrap">'.$paymentData['check_number'].'</td>
                              <td  class="align1" nowrap="nowrap">Balance due on account:</td><td class="align2"  nowrap="nowrap">'.$paymentData['balance_due_account'].'</td>
                            </tr>         
                          </table>    
                        </td>
                      </tr>
                    </tbody>
                    
                    <tbody>
                      <tr><td><br/></td></tr>
                      <tr><td colspan="4" style="text-align: center;">Thank you</td></tr>
                      <tr><td><br/></td></tr>
                    </tbody>
             </table>';
    }else{
      $document='<table class="fontSize">
                    <tbody>
                      <tr>
                        <td colspan="2">'.ucwords($paymentData['company_name']).'<br/>
                          '.$paymentData['company_address'].'<br/>
                          '.($paymentData['company_city'] != '' ? $paymentData['company_city'].', ' : '').$paymentData['company_state'].' '.$paymentData['company_zip'].'
                        </td>
                        <td rowspan="2" colspan="2" style="text-align: center;">
                         '.(file_exists($paymentData['company_logo']) ? '<img id="img_logo" src="'.$paymentData['company_logo'].'" width="160" height="108"/>' : '<img id="img_logo" width="160" height="108" style="display: none;"/>').' 
                        </td>
                      </tr>
                      <tr>
                         <td colspan="2"></td>
                      </tr>
                      <tr>
                         <td colspan="2">Payer:<br/></td>
                      </tr>
                      <tr>
                         <td colspan="4">'.ucwords($paymentData['payer']).'<br/>
                                 '.$paymentData['address'].'<br/>
                                 '.$paymentData['city'].' '. $paymentData['state'].' '.$paymentData['zip'].'
                         </td>
                      </tr>
                      <tr>
                        <td style="height: 80px;"></td>
                      </tr>
                    </tbody>
                    
                    <tbody>
                      <tr><td colspan="4">-----------------------------------------------------------------------------------------------------------------------------------</td></tr>
                      <tr><td colspan="4"><br/><br/></td></tr>
                    </tbody> 
                   
                    <tbody>
                      <tr>
                        <td colspan="4" style="text-align: center;" border="0" width="100%">
                          <table class="tab" cellpadding="5">
                            <tr>
                              <td colspan="4" style="font-weight: bold; nowrap: nowrap; text-align: center;"> 
                                Receipt of payment for the services of '.ucwords($paymentData['customer_name']).'
                              </td>
                            </tr>
                            <tr>
                              <td  class="align1" width="18%" nowrap="nowrap">Payment Date:</td><td class="align2" width="33%" nowrap="nowrap">'.$paymentData['date'].'</td>
                              <td  class="align1" width="33.5%" nowrap="nowrap">Balance due before payment:</td><td class="align2" width="16.5%" nowrap="nowrap">$'.$paymentData['balance_before_payment'].'</td>
                            </tr>
                            <tr>
                              <td class="align1" nowrap="nowrap">Method:</td><td class="align2" nowrap="nowrap">'.$paymentData['payment_method'].'</td> 
                              <td class="align1" nowrap="nowrap">Payment:</td><td class="align2" nowrap="nowrap">'.$paymentData['amount'].'</td>
                            </tr>
                            <tr>
                              <td  class="align1" nowrap="nowrap">Check No.</td><td class="align2"  nowrap="nowrap">'.$paymentData['check_number'].'</td>
                              <td  class="align1" nowrap="nowrap">Balance due on account:</td><td class="align2"  nowrap="nowrap">'.$paymentData['balance_due_account'].'</td>
                            </tr>         
                          </table>    
                        </td>
                      </tr>
                    </tbody>
                    
                    <tbody>
                      <tr><td><br/></td></tr>
                      <tr><td colspan="4" style="text-align: center;">Thank you</td></tr>
                      <tr><td><br/></td></tr>
                    </tbody>
             </table>';
    }
    

    $css = '<style>
              h1{
                color: #A49262;
                font-size: 18pt;
                font-family: Arial, sans-serif;
                margin-top: 5px;
                margin-bottom: 3px;
              }
              span{
               margin:0 0 0 0;
               padding:0 0 0 0;
               text-indent:0;
              }
              .fontSize{
               font-size: 10pt;
              }
              .align1{
               text-align: right;
               nowrap: nowrap;
              }
              .align2{
               text-align: left;
               nowrap: nowrap;
              }
//              .tab{
//                border-top:1px solid #000000;
//                border-left:1px solid #000000;
//              }
//              .tab td{
//                border-right:1px solid #000000;
//                border-bottom:1px solid #000000;
//              }
              </style>';
      
    $document = $css . $document;

    // add a page
    $pdf->AddPage();

    // test some inline CSS
    $pdf->writeHTML($document, true, false, true, false, '');

    // reset pointer to the last page
    $pdf->lastPage();
    
    //Close and output PDF document
    $fileName = $_SERVER['DOCUMENT_ROOT'] . '/files/payment_receipt/payment_receipt_' . time() . '.pdf';
    if($dowload){
      $pdf->Output($fileName, 'FD');
      exit;
    }
    else{
      $pdf->Output($fileName, 'F'); 
      return $fileName;
    }
  }

//private function _compareMaxValue($value, $dataArray){
//    $counts = count($dataArray);
//    
//    if($counts){
//      if($counts == 1){
//        $maxValue = $dataArray[0];
////        return $maxValue;
//        return true;
//      }else{
//        $maxValue = $dataArray[0];
//        for($i=0; $i <= $counts-1; $i++){
//          $maxValue = $maxValue > $dataArray[$i+1] ? $maxValue : $dataArray[$i+1];
//        }
//        
//        if($value == $maxValue){
//          return true;
//        }else{
//          return false;
//        }
//      }
//    }
//    
//  }
  
private function _compareMinValue($value, $dataArray){
    $counts = count($dataArray);
    
    if($counts){
      if($counts == 1){
        $minValue = $dataArray[0];
//        return $minValue;
        return true;
      }else{
        $minValue = $dataArray[0];
//        return $minValue.'aaa';
        for($i=$counts-1; $i == 0; $i--){
          $minValue = $minValue < $dataArray[$i+1] ? $minValue : $dataArray[$i+1];
        }
        
//        return $minValue;exit;
        if($value == $minValue){
          return true;
        }else{
          return false;
        }
      }
    }
    
  }
  
  public function actionRefreshProductList($id)
  {
    $model=$this->loadModel($id);
    $connection = Yii::app()->db;

    //products
    $command2 = $connection->createCommand("select i.*, p.id as product_id, ifnull(p.product_retail, i.retail) as retail from product p, inventory i
      where p.inventory_id=i.id and p.customer_id=:customer_id and p.company_id=i.company_id and p.company_id=:company_id order by i.name");
    $command2->bindParam(':customer_id', $id);
    $command2->bindParam(':company_id', Yii::app()->user->company_id);
    $productDataProvider = $command2->query();
    
    //$total_payments
    $command3 = $connection->createCommand("select sum(amount) from payment where customer_id=:customer_id and type='payment'");
    $command3->bindParam(':customer_id', $id);
    $total_payments = $command3->queryScalar();
    $total_payments = $total_payments != '' ||  $total_payments != null ? $total_payments : '0';
    
    //discounts
    $sql = "select sum(amount) from payment where customer_id= :customer_id and type='credit'";
    $command8 = $connection->createCommand($sql);
    $command8->bindParam(':customer_id', $id);
    $discount = $command8->queryScalar();
    
    //$tatal_balances
    $command4 = $connection->createCommand("select sum(amount) from payment where customer_id=:customer_id and type != 'payment'");
    $command4->bindParam(':customer_id', $id);
    $tatal_balances = $command4->queryScalar();
    $total_balances = $total_balances != '' ||  $total_balances != null ? $total_balances : '0';
    
    $this->renderPartial('product_list', array(
        'model'=>$model,
        'productDataProvider'=>$productDataProvider,
        'total_payments'=>$total_payments,
        'discount'=>$discount,
        'tatal_balances'=>$tatal_balances,
    ));
  }
}

?>
