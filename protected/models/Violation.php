<?php

/**
 * This is the model class for table "violation".
 *
 * The followings are the available columns in table 'violation':
 * @property integer $id
 * @property string $date
 * @property string $photographs
 * @property string $property
 * @property integer $propertyid
 * @property integer $ruleid
 * @property string $letter_text
 * @property string $hoa_description
 * @property string $letter_sent
 * @property string $notice
 */
class Violation extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Violation the static model class
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
		return 'violation';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('propertyid, ruleid', 'numerical', 'integerOnly'=>true),
			array('date, letter_sent', 'length', 'max'=>10),
			array('property', 'length', 'max'=>100),
			array('notice', 'length', 'max'=>4),
			array('photographs, letter_text, hoa_description, resolution', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, date, photographs, property, propertyid, ruleid, letter_text, hoa_description, letter_sent, notice, resolution', 'safe', 'on'=>'search'),
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
			'date' => 'Date',
			'photographs' => 'Photographs',
			'property' => 'Property',
			'propertyid' => 'Propertyid',
			'ruleid' => 'Rule',
			'letter_text' => 'Letter Text',
			'hoa_description' => 'HOA Report Text',
			'letter_sent' => 'Letter Sent',
			'notice' => 'Notice #',
			'resolution' => 'Resolution',
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
		$criteria->compare('date',$this->date,true);
		$criteria->compare('photographs',$this->photographs,true);
		$criteria->compare('property',$this->property,true);
		$criteria->compare('propertyid',$this->propertyid);
		$criteria->compare('ruleid',$this->ruleid);
		$criteria->compare('letter_text',$this->letter_text,true);
		$criteria->compare('hoa_description',$this->hoa_description,true);
		$criteria->compare('letter_sent',$this->letter_sent,true);
		$criteria->compare('notice',$this->notice,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
  
  public function save()
  {
    /**
     * Letter Sent (no or date, read only)
          If a letter has been sent for this violation this is yes, otherwise no.  
     */
    if (!preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $this->letter_sent)) {
      $this->letter_sent = 'No';
    }
    
    $this->company_id = Yii::app()->user->company_id;
    
    return parent::save();
  }
}