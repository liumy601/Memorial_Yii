<?php

/**
 * This is the model class for table "rule".
 *
 * The followings are the available columns in table 'rule':
 * @property integer $id
 * @property string $hoa
 * @property integer $hoaid
 * @property string $name
 * @property string $hoa_description
 * @property string $letter_text
 * @property string $enteredby
 * @property integer $enteredtm
 */
class Rule extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Rule the static model class
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
		return 'rule';
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
			array('hoa, name', 'length', 'max'=>100),
			array('enteredby', 'length', 'max'=>15),
			array('hoa_description, letter_text, rule', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, hoa, hoaid, name, hoa_description, letter_text, enteredby, enteredtm', 'safe', 'on'=>'search'),
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
			'hoa' => 'HOA',
			'hoaid' => 'Hoaid',
			'name' => 'Rule Name',
			'hoa_description' => 'Rule HOA Default Description ',
			'letter_text' => 'Rule Letter Default Text ',
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
		$criteria->compare('hoa',$this->hoa,true);
		$criteria->compare('hoaid',$this->hoaid);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('hoa_description',$this->hoa_description,true);
		$criteria->compare('letter_text',$this->letter_text,true);
		$criteria->compare('enteredby',$this->enteredby,true);
		$criteria->compare('enteredtm',$this->enteredtm);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
  
  public static function getAll()
	{
		$rules = array(''=>'');
    
    $connection = Yii::app()->db;
    $command = $connection->createCommand("SELECT * FROM rule WHERE company_id=". Yii::app()->user->company_id ." ORDER BY name");
    $dr = $command->query();
    while ($row = $dr->read()) {
      $rules[$row['id']] = $row['name'];
    }
    return $rules;
	}

  public function save()
  {
    $this->company_id = Yii::app()->user->company_id;
    
    return parent::save();
  }
  
}