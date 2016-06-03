<?php

/**
 * This is the model class for table "template".
 *
 * The followings are the available columns in table 'template':
 * @property integer $id
 * @property string $name
 * @property string $case_number
 * @property string $email_address
 * @property string $email_text
 * @property string $templates
 * @property integer $default_check
 * @property integer $company_id
 * @property string $enteredby
 * @property integer $enteredtm
 * @property integer $deleted
 */
class Template extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Template the static model class
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
		return 'template';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
      array('name', 'required'),
			array('default_check, company_id, enteredtm, deleted', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>255),
			array('case_number', 'length', 'max'=>20),
			array('enteredby', 'length', 'max'=>15),
			array('deleted', 'length', 'max'=>1),
			array('email_address', 'length', 'max'=>100),
			array('email_text, templates, is_super_admin, active', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, case_number, email_address, email_text, templates, default_check, company_id, deleted', 'safe', 'on'=>'search'),
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
			'case_number' => 'Case Number',
			'email_address' => 'Email Address',
			'email_text' => 'Email Text',
			'templates' => 'Templates',
			'default_check' => 'Default Check',
			'company_id' => 'Company',
			'enteredby' => 'Entered By',
			'enteredtm' => 'Entered Time',
			'deleted' => 'Deleted',
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
		$criteria->compare('case_number',$this->case_number,true);
		$criteria->compare('email_address',$this->email_address,true);
		$criteria->compare('email_text',$this->email_text,true);
		$criteria->compare('templates',$this->templates,true);
		$criteria->compare('default_check',$this->default_check);
		$criteria->compare('company_id',$this->company_id);
		$criteria->compare('enteredby',$this->enteredby,true);
		$criteria->compare('deleted',$this->deleted,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
  
  public static function getAll($customerID=0)
	{
		$templates = array(''=>'----None----');
    
    $connection = Yii::app()->db;
    
    if ($customerID) {
//      $sql = 'SELECT * FROM template WHERE id not in (select template_id from document where customer_id='.$customerID.') ORDER BY name';
      $sql = 'SELECT * FROM template WHERE id not in (select template_id from document where customer_id='.$customerID.' and template_id is not null) and deleted=0 and company_id=:company_id ORDER BY name';
    } else {
//      $sql = 'SELECT * FROM template ORDER BY name';
//      $sql = 'SELECT * FROM template where default_check=0 and deleted=0 and company_id=:company_id ORDER BY name';
      $sql = 'SELECT * FROM template where deleted=0 and company_id=:company_id ORDER BY name';
    }
    
    $command = $connection->createCommand($sql);
    $command->bindParam(':company_id', Yii::app()->user->company_id);
    $dr = $command->query();
    
    while($row = $dr->read()) {
      $templates[$row['id']] = $row['name'];
    }
    return $templates;
	}
  
//  public static function getAll($customerID = 0)
//  {
//    $templates = array(''=>'---None---');
//    
//    $connection = Yii::app()->db;
//    
//    if($customerID){
//      $sql = "SELECT * FROM template WHERE id NOT IN (SELECT template_id FROM document WHERE customer_id='".$custpmerID."') ORDER BY name";
//    }else{
//      $sql = "SELECT * FR0M template OREDR BY name";
//    }
//    
//    $command=$connection->createCommand($sql);
//    $dr = $command->query();
//    while($row = $dr->read()){
//      $templates[$row['id']] = $row['name'];
//    }
//    return $templates;
//  }
  
  public function save()
  {
    if(empty($this->company_id) && Yii::app()->user->hasState('company_id'))
			$this->company_id = Yii::app()->user->company_id;
    return parent::save();
  }

  public static function loadSampleTemplates() {
	$sample_templates = Template::model()->findAll('is_super_admin=1 and active=1 and deleted=0 order by name');
	$sample_templates = array_merge(array(''=>'---select---'), CHtml::listData($sample_templates, 'templates', 'name'));
	return $sample_templates;
  }
}