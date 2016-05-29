<?php

class Task extends CActiveRecord
{
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return 'task';
	}

	public function rules()
	{
		return array(
			array(
      'company_id,
        subject,
        description,
        assigned_to,
        status,
        
        date_due_flag,
        date_due,
        date_start_flag,
        date_start,
        parent_type,
        parent_id,
        contact_id,
        priority,
        enteredby,
        timestamp',

      'safe'),
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