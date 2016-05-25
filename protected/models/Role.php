<?php

class Role extends CActiveRecord
{
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return 'role';
	}

	public function rules()
	{
		return array(
			array('id,name,description', 'safe'),
			array('name', 'required'),
			array('name', 'unique'),
		);
	}

	public function attributeLabels()
	{
    return array(
		);
	}
  
  public static function getName($_id) {
    if (!$_id || !is_numeric($_id)) {
      return '';
    }
    
    $connection = Yii::app()->db;
    $name = $connection->createCommand("SELECT name FROM role where id=".$_id)->queryScalar();
    return $name;
  }
  
  public static function getAll($hasEmpty=false)
	{
    if($hasEmpty){
      $roles = array(''=>'');
    } else {
      $roles = array();
    }
    $connection = Yii::app()->db;
    $command = $connection->createCommand("SELECT * FROM role ORDER BY id");
    $dt = $command->query();
    while($row = $dt->read()){
      $roles[$row['id']] = $row['name'];
    }
    
    return $roles;
	}
  
  public static function userInRole($uid, $roles) {
    if(is_array($roles) && $roles){
      $connection = Yii::app()->db;
      
      foreach($roles as $rid){
        if($rid == '') continue;
        
        $command = $connection->createCommand("select count(*) from role_user where rid=:rid and uid=:uid");
        $command->bindParam(':rid', $rid);
        $command->bindParam(':uid', $uid);
        $exists = $command->queryScalar();
        
        if($exists){
          return true;
        }
      }
    }
    
    return false;
  }
  
}