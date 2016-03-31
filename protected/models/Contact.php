<?php

/**
 * This is the model class for table "contact".
 *
 * The followings are the available columns in table 'contact':
 * @property integer $id
 * @property integer $company_id
 * @property string $full_name
 * @property string $address
 * @property integer $phone
 * @property string $email
 * @property string $relationship
 * @property string $note
 * @property string $enteredby
 * @property integer $enteredtm
 * @property integer $customerid
 */
class Contact extends CActiveRecord
//class Contact extends CModel
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Contact the static model class
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
		return 'contact';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('company_id, enteredtm, customerid', 'numerical', 'integerOnly'=>true),
			array('full_name', 'length', 'max'=>150),
			array('email', 'length', 'max'=>50),
			array('phone', 'length', 'max'=>30),
			array('enteredby', 'length', 'max'=>15),
			array('address, note, relationship, customer', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, company_id, full_name, address, phone, email, note, enteredby, enteredtm, customer', 'safe', 'on'=>'search'),
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
			'company_id' => 'Contact',
			'full_name' => 'Full Name',
			'address' => 'Address',
			'phone' => 'Phone',
			'email' => 'Email',
			'note' => 'Note',
			'enteredby' => 'Entered By',
			'enteredtm' => 'Entered Time',
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
		$criteria->compare('company_id',$this->company_id);
		$criteria->compare('full_name',$this->full_name,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('phone',$this->phone);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('relationship',$this->relationship,true);
		$criteria->compare('note',$this->note,true);
		$criteria->compare('enteredby',$this->enteredby,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
  
  public function save()
  {
    $this->company_id = Yii::app()->user->company_id;
    $ret = parent::save();
    
    return $ret;
  }
}