<?php

/**
 * This is the model class for table "email_config".
 *
 * The followings are the available columns in table 'email_config':
 * @property string $id
 * @property string $company_id
 * @property string $from_name
 * @property string $from_address
 * @property string $send_type
 * @property string $smtp_server
 * @property integer $smtp_port
 * @property integer $smtp_auth
 * @property integer $smtp_ssl
 * @property string $smtp_user
 * @property string $smtp_pass
 */
class EmailConfig extends CActiveRecord
{
  public static function load($company_id=null) {
    if (!$company_id) {
      $company_id = Yii::app()->user->company_id;
    }
    
    $connection = Yii::app()->db;
    $command = $connection->createCommand("SELECT id FROM email_config WHERE company_id = :company_id ");
    $command->bindParam(':company_id', $company_id);
    $row = $command->queryRow();
    if($row['id']){
      return self::model()->findByPk($row['id']);
    }
    else{
      return new EmailConfig();
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
		return 'email_config';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('company_id, smtp_port, smtp_auth, smtp_ssl', 'numerical', 'integerOnly'=>true),
			array('from_name, from_address, smtp_server', 'length', 'max'=>100),
			array('send_type', 'length', 'max'=>8),
			array('smtp_user, smtp_pass', 'length', 'max'=>50),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('from_name, from_address, send_type, smtp_server, smtp_port, smtp_auth, smtp_ssl, smtp_user, smtp_pass', 'safe', 'on'=>'search'),
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
			'from_name' => '"From" Name',
			'from_address' => '"From" Address',
			'send_type' => 'Mail Transfer Agent',
			'smtp_server' => 'SMTP Server',
			'smtp_port' => 'SMTP Port',
			'smtp_auth' => 'Use SMTP Authentication?',
			'smtp_ssl' => 'Enable SMTP over SSL',
			'smtp_user' => 'SMTP Username',
			'smtp_pass' => 'SMTP Password',
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

		$criteria->compare('from_name',$this->from_name,true);
		$criteria->compare('from_address',$this->from_address,true);
		$criteria->compare('send_type',$this->send_type,true);
		$criteria->compare('smtp_server',$this->smtp_server,true);
		$criteria->compare('smtp_port',$this->smtp_port);
		$criteria->compare('smtp_auth',$this->smtp_auth);
		$criteria->compare('smtp_ssl',$this->smtp_ssl);
		$criteria->compare('smtp_user',$this->smtp_user,true);
		$criteria->compare('smtp_pass',$this->smtp_pass,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
}