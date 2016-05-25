<?php

/**
 * This is the model class for table "users".
 *
 * The followings are the available columns in table 'users':
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $company_id
 * @property string $email
 * @property string $firstname
 * @property string $lastname
 * @property string $department
 * @property string $type
 * @property string $status
 */
class Users extends CActiveRecord
{
  const SALT_LENGTH = 10;

	/**
	 * Returns the static model of the specified AR class.
	 * @return Users the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'users';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		$rules = array(
			array('username, password, email, firstname, lastname, type', 'required'),
			array('username', 'length', 'max'=>15),
			array('username', 'unique'),
			array('username', 'usernameOnlyWords'),
			array('password', 'passwordOnlyWords'),
			array('email', 'email'),
			array('password', 'length', 'max'=>32),
			array('email, firstname, lastname', 'length', 'max'=>50),
     array('company_id, department', 'safe'),
		);
    
    //when super admin create customer, the school id is required
    if(Yii::app()->controller->id == 'admin' 
            && Yii::app()->controller->action->id == 'user' 
            && ($_GET['op'] == 'create' || $_GET['op'] == 'update')
    ){
      $rules[] = array('company_id', 'required');
    }
    
    return $rules;
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'username' => 'Username',
			'password' => 'Password',
			'email' => 'Email',
			'company_id' => 'Company',
		);
	}
  
  public function usernameOnlyWords()
  {
    if(preg_match('/[^a-zA-Z0-9_]/', $this->username)){
      $this->addError('username','username allow: a-z, 0-9, _');
    }
  }
  
  public function passwordOnlyWords()
  {
    if(preg_match('/[^a-zA-Z0-9]/', $this->password)){
      $this->addError('password','password allow: a-z, 0-9');
    }
  }

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('email',$this->email,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}

  /**
 * @return boolean validate user
 */
  public function validatePassword($password)
  {
    return self::hashPassword($password) === $this->password;
  }
  
  /**
   * @return hashed value
   */
  public static function hashPassword($password)
  {
    return md5($password);
//    $key = 'Gf;B&yXL|beJUf-K*PPiU{wf|@9K9j5?d+YW}?VAZOS%e2c -:11ii<}ZM?PO!96';
//    if($salt == '')
//        $salt = substr(hash('sha512', $key), 0, 10);
//    else
//        $salt = substr($salt, 0, self::SALT_LENGTH);
//    return hash('sha512', $salt . $key . $phrase);
  }

  public function getTypes(){
    return array(
      'super admin' => 'super admin',
      'admin' => 'admin',
      'standard' => 'standard'
    );
  }

  public function getRoles(){
    return array(
      'SysAdmin' => 'SysAdmin',
      'CEO' => 'CEO',
      'Sales' => 'Sales',
      'Ops' => 'Ops'
    );
  }

  public function getStatus(){
    return array('Inactive', 'Active');
  }
  
  public static function getAll()
	{
		$users = array();
    
    $connection = Yii::app()->db;
    $command = $connection->createCommand("SELECT * FROM users ORDER BY id");
    $dr = $command->query();
    while ($row = $dr->read()) {
      $users[$row['id']] = $row['username'];
    }
    return $users;
	}
  
  /**
   * no school can see another school's data
   * customers can only access own objects, can't view other customers' objects.
   */
  public static function filterCustomerAccess($tableName, $objectID)
  {
    $connection = Yii::app()->db;
    $command = $connection->createCommand("SELECT company_id from $tableName WHERE id=:id");
    $command->bindParam(':id', $objectID);
    $company_id = $command->queryScalar();
    
    if ($company_id != Yii::app()->user->company_id) {
      echo 'Access denied.';
      exit;
    }
  }
  
  public static function access($op, $action, $return=false, $uid=0) {
    $connection = Yii::app()->db;

    if($op == 'application') return true;
    
    if (!$uid) {
      if(Yii::app()->user->name == 'Guest'){
        $uid = 0;
      } else {
        $uid = Yii::app()->user->uid;
      }
    }
    
    if(($op == 'Duty_Log' || $op == 'Generic' || $op == 'Housing') && ($action == 'view' || $action == 'edit')){
      /**
       * For all form types, create permission is same as view permissions. So if you have rights to create a form of a certain type, 
       * you can also view all records of that form type, even if created by other users.
       */
      $command = $connection->createCommand("select count(*) from role_perm rp, role_user ru 
            where rp.rid=ru.rid and op=:op and (rp.view=1 OR rp.edit=1) and ru.uid=:uid");
      $command->bindParam(':op', $op);
      $command->bindParam(':uid', $uid);
      $allow = $command->queryScalar();
      
    } else {
      $command = $connection->createCommand("select count(*) from role_perm rp, role_user ru 
              where rp.rid=ru.rid and op=:op and rp.$action=1 and ru.uid=:uid");
      $command->bindParam(':op', $op);
      $command->bindParam(':uid', $uid);
      $allow = $command->queryScalar();
    }
    
    if ($return) {
      return  $allow ? true : false;
    } else {
      if(!$allow){
        require_once('protected/controllers/SiteController.php');
        $siteController = new SiteController('site');
        echo $siteController->actionUnautorized();
        exit;
      }
    }
  }
  
  public function save()
  {
//    if (Yii::app()->user->type == 'admin') {//company admin
//      $this->company_id = Yii::app()->user->company_id;
//    }
    
    return parent::save();
  }
  
  public function delete()
  {
    $connection = Yii::app()->db;
    $command = $connection->createCommand("delete from pre_auth_assignment where userid=:userid");
    $command->bindParam(':userid', $this->username);
    $command->execute();
    
    return parent::delete();
  }
  
}