<?php

/**
 * This is the model class for table "package".
 *
 * The followings are the available columns in table 'package':
 * @property integer $id
 * @property string $name
 * @property integer $company_id
 */
class Package extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Package the static model class
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
		return 'package';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('company_id', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>100),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, company_id', 'safe', 'on'=>'search'),
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
			'company_id' => 'Company',
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
		$criteria->compare('company_id',$this->company_id);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
  
  public static function getProductNames($packageID) {
		$products = array();
    
    $connection = Yii::app()->db;
    $command = $connection->createCommand("SELECT i.name FROM inventory i, package_product pp where i.id=pp.inventory_id and pp.package_id=". $packageID ." ORDER BY id");
    $dr = $command->query();
    while ($row = $dr->read()) {
      $products[] = $row['name'];
    }
    return implode(', ', $products);
  }
  
  public static function getProductPrices($packageID) {
    $connection = Yii::app()->db;
    $command = $connection->createCommand("SELECT sum(i.retail) FROM inventory i, package_product pp where i.id=pp.inventory_id and pp.package_id=". $packageID ." ORDER BY id");
    return $command->queryScalar();
  }
  
  public static function getAll()
	{
		$package = array();
    
    $connection = Yii::app()->db;
    $command = $connection->createCommand("select * from package where company_id=:company_id order by name");
    $command->bindParam(':company_id', Yii::app()->user->company_id);
    $dr = $command->query();
    while ($row = $dr->read()) {
      $package[$row['id']] = $row['name'];
    }
    
    return $package;
	}

  public function save()
  {
	if( Yii::app()->user->hasState('company_id'))
		$this->company_id = Yii::app()->user->company_id;
    
    return parent::save();
  }

  public function delete()
  {
    //delete products
    $connection = Yii::app()->db;
    $command = $connection->createCommand("delete from package_product where package_id=:package_id");
    $command->bindParam(':package_id', $this->id);
    $command->execute();
    
    return parent::delete();
  }
}