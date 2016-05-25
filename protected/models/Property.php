<?php

/**
 * This is the model class for table "property".
 *
 * The followings are the available columns in table 'property':
 * @property integer $id
 * @property string $firstname
 * @property string $lastname
 * @property string $hoa
 * @property integer $hoaid
 * @property string $address1
 * @property string $address2
 * @property string $city
 * @property string $state
 * @property string $zip
 * @property string $enteredby
 * @property integer $enteredtm
 */
class Property extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Property the static model class
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
		return 'property';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('hoaid, enteredtm', 'numerical', 'integerOnly'=>true),
			array('firstname, lastname, city', 'length', 'max'=>50),
			array('hoa', 'length', 'max'=>100),
			array('state', 'length', 'max'=>30),
			array('zip', 'length', 'max'=>20),
			array('enteredby', 'length', 'max'=>15),
			array('address1, address2, rule', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, firstname, lastname, hoa, hoaid, address1, address2, city, state, zip, enteredby, enteredtm', 'safe', 'on'=>'search'),
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
			'firstname' => 'First Name',
			'lastname' => 'Last Name',
			'hoa' => 'HOA',
			'hoaid' => 'Hoaid',
			'address1' => 'Address 1',
			'address2' => 'Address 2',
			'city' => 'City',
			'state' => 'State',
			'zip' => 'Zip',
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
		$criteria->compare('firstname',$this->firstname,true);
		$criteria->compare('lastname',$this->lastname,true);
		$criteria->compare('hoa',$this->hoa,true);
		$criteria->compare('hoaid',$this->hoaid);
		$criteria->compare('address1',$this->address1,true);
		$criteria->compare('address2',$this->address2,true);
		$criteria->compare('city',$this->city,true);
		$criteria->compare('state',$this->state,true);
		$criteria->compare('zip',$this->zip,true);
		$criteria->compare('enteredby',$this->enteredby,true);
		$criteria->compare('enteredtm',$this->enteredtm);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}

  public function save()
  {
    $this->company_id = Yii::app()->user->company_id;
    
    return parent::save();
  }
}