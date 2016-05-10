<?php

/**
 * This is the model class for table "trial_account".
 *
 * The followings are the available columns in table 'trial_account':
 * @property string $username
 * @property string $yourname
 * @property string $company_name
 * @property string $email
 * @property string $phone
 */
class TrialAccount extends CFormModel
{
	public $username;
	public $firstname;
	public $lastname;
	public $company_name;
	public $email;
	public $phone;

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('firstname, lastname, email', 'required'),
			array('username, company_name, email', 'length', 'max'=>50),
			array('phone', 'length', 'max'=>20),
			array('username', 'checkDuplicateUsername'),
			array('email', 'checkDuplicateEmail'),
			array('username', 'match', 'pattern'=>'/^\w+$/i', 'message'=>'Username can only contain characters,numbers,underscores.'),
			array('username, yourname, company_name, email, phone', 'safe', 'on'=>'search'),
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
			'username' => 'User Name',
			'firstname' => 'First Name',
			'lastname' => 'Last Name',
			'company_name' => 'Company Name',
			'email' => 'Your Email',
			'phone' => 'Phone Number',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('username',$this->username,true);
		$criteria->compare('yourname',$this->yourname,true);
		$criteria->compare('company_name',$this->company_name,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('phone',$this->phone,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TrialAccount the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function checkDuplicateUsername() {
		$users = Users::model()->findAll();
		$usernames = array();
		foreach($users as $user) {
			$usernames[] = $user->username;
		}

		if(in_array($this->username, $usernames))
			$this->addError('username', 'Username "'. $this->username .'" has already been taken');
	}

	public function checkDuplicateEmail() {
		$users = Users::model()->findAll();
		$emails = array();
		foreach($users as $user) {
			$emails[] = $user->email;
		}

		if(in_array($this->email, $emails))
			$this->addError('email', 'Email "'. $this->email .'" has already been taken');
	}
}
