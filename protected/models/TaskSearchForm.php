<?php

class TaskSearchForm extends CFormModel
{
	public $subject;
	public $status;
	public $date_due;
	public $assigned_to;
  
	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			array('subject,status,date_due,assigned_to', 'safe')
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
    return array(
      'assigned_to'=>'Assigned To',
    );
	}
}
