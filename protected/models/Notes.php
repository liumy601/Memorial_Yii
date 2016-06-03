<?php

class Notes extends CActiveRecord
{
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'notes';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive customer inputs.
		return array(
			array('id, parent_type, parent_id, subject, body, enteredby, timestamp', 'safe'),
			array('parent_type, parent_id, subject, enteredby, timestamp', 'required'),
			array('subject', 'length', 'max'=>50),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
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
			'enteredby'=>'Entered By',
			'timestamp'=>'Entered Time',
		);
	}
  
  public function save()
  {
    //set the company_id
    if(empty($this->company_id) && Yii::app()->user->hasState('company_id'))
			$this->company_id = Yii::app()->user->company_id;
    return parent::save();
  }
  
  public static function renderSubpanel($parent_type, $parent_id)
  {
    $connection = Yii::app()->db;
    $command = $connection->createCommand("select * from task where parent_type=:parent_type and parent_id=:parent_id order by id DESC");
    $command->bindParam(':parent_type', $parent_type);
    $command->bindParam(':parent_id', $parent_id);
    $taskDR = $command->query();

    $this->renderPartial('ajaxlist',array(
      'taskDR' => $taskDR,
      'parent_type' => $parent_type,
      'parent_id' => $parent_id,
    ));
  }
}