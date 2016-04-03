<?php

/**
 * This is the model class for table "hoa".
 *
 * The followings are the available columns in table 'hoa':
 * @property integer $id
 * @property string $name
 * @property string $enteredby
 * @property integer $enteredtm
 */
class HOA extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return HOA the static model class
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
		return 'hoa';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name', 'length', 'max'=>100),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, enteredby, enteredtm', 'safe', 'on'=>'search'),
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
			'name' => 'Name',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('enteredby',$this->enteredby,true);
		$criteria->compare('enteredtm',$this->enteredtm);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
  
  public static function getAll()
	{
		$users = array();
    
    $connection = Yii::app()->db;
    $command = $connection->createCommand("SELECT * FROM hoa WHERE company_id=". Yii::app()->user->company_id ." ORDER BY id");
    $dr = $command->query();
    while ($row = $dr->read()) {
      $users[$row['id']] = $row['name'];
    }
    return $users;
	}
  
  public function save()
  {
    $new = !$this->id;
    
    $this->company_id = Yii::app()->user->company_id;
    $ret = parent::save();
    
    if ($new) {
      $connection = Yii::app()->db;
      $connection->createCommand("insert into letter_templates (hoaid, name) values (".$this->id.", 'Letter templates 1')")->execute();
      $connection->createCommand("insert into letter_templates (hoaid, name) values (".$this->id.", 'Letter templates 2')")->execute();
      $connection->createCommand("insert into letter_templates (hoaid, name) values (".$this->id.", 'Letter templates 3')")->execute();
    }
    
    return $ret;
  }
  
  public function delete()
  {
    $connection = Yii::app()->db;
    $connection->createCommand("delete from letter_templates where hoaid=".$this->id)->execute();
    
    return parent::delete();
  }
}