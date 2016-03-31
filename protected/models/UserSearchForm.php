<?php

class UserSearchForm extends CFormModel
{
	public $firstname;
	public $lastname;
	public $email;
	public $school;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			array('firstname,lastname,email,school', 'safe')
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
    return array(
      'firstname' => 'First Name',
      'lastname' => 'Last Name',
    );
	}

}
