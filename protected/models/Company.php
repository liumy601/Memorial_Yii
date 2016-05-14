<?php

class Company extends CActiveRecord
{
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return 'company';
	}

	public function rules()
	{
		return array(
			array('id, name, logo, address, city, state, zip, phone', 'safe'),
			array('name', 'required'),
//			array('name', 'unique'),//for company may create from appapp, the company name maybe same.
		);
	}

	public function attributeLabels()
	{
    return array(
      'id' => 'ID',
      'logo' => 'Company logo',
      'name' => 'Company name',
      'address' => 'Company address',
      'city' => 'Company city',
      'state' => 'Company state',
      'zip' => 'Company zip',
	  'phone' => 'Company phone',
		);
	}
  
  public static function getAll()
	{
    $companys = array(''=>'');
    $connection = Yii::app()->db;
    $command = $connection->createCommand("SELECT * FROM company ORDER BY id");
    $dt = $command->query();
    while($row = $dt->read()){
      $companys[$row['id']] = $row['name'];
    }
    
    return $companys;
	}
  
}