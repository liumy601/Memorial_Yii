<?php

/**
 * This is the model class for table "config".
 *
 * The followings are the available columns in table 'config':
 * @property string $name
 * @property string $value
 */
class Config extends CActiveRecord
{
  public static function load($name) {
    $connection = Yii::app()->db;
    $command = $connection->createCommand("SELECT id FROM config where name=:name");
    $command->bindParam(':name', $name);
    $row = $command->queryRow();
    if($row['id']){
      $model = self::model()->findByPk($row['id']);
      $model->value = unserialize($model->value);
      return $model;
    }
    else{
      $model = new Config();
      $model->name = $name;
      if (!$model->value) {
        $model->value = 0.06;
      }
      return $model;
    }
  }
  
  
	/**
	 * Returns the static model of the specified AR class.
	 * @return EmailConfig the static model class
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
		return 'config';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, value', 'safe'),
		);
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
			'name' => 'Name',
			'value' => 'Value',
		);
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

		$criteria->compare('name',$this->name,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
  
  public function save()
  {
    $this->value = serialize($this->value);
    
    return parent::save($runValidation=true,$attributes=null);
  }
}