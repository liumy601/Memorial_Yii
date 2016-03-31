<?php

/**
 * This is the model class for table "letter_templates".
 *
 * The followings are the available columns in table 'letter_templates':
 * @property integer $id
 * @property integer $hoaid
 * @property string $name
 * @property string $templates
 */
class LetterTemplates extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return LetterTemplates the static model class
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
		return 'letter_templates';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('hoaid', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>255),
			array('templates', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, hoaid, name, templates', 'safe', 'on'=>'search'),
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
			'id' => 'ID',
			'hoaid' => 'Hoaid',
			'name' => 'Name',
			'templates' => 'Templates',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('hoaid',$this->hoaid);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('templates',$this->templates,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
  
  public static function loadByHOALPID($hoaid, $letterTemplateID)
  {
    $connection = Yii::app()->db;
    $command = $connection->createCommand("select * from letter_templates where hoaid=:hoaid and name=:name");
    $command->bindParam(':hoaid', $hoaid);
    $letterTemplateID = 'Letter templates '.$letterTemplateID;
    $command->bindParam(':name', $letterTemplateID);
    return $command->queryRow();
  }

  public function save()
  {
    $this->company_id = Yii::app()->user->company_id;
    
    return parent::save();
  }
}