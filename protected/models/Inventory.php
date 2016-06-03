<?php

/**
 * This is the model class for table "inventory".
 *
 * The followings are the available columns in table 'inventory':
 * @property integer $id
 * @property integer $company_id
 * @property string $vendor
 * @property string $sku
 * @property double $retail
 * @property double $cost
 * @property integer $template_id
 * @property integer $taxable
 * @property string $category
 * @property string $internal_notes
 * @property string $invoice_notes
 */
class Inventory extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Inventory the static model class
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
		return 'inventory';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('company_id, template_id, taxable, enteredtm', 'numerical', 'integerOnly'=>true),
			array('retail, cost', 'numerical'),
			array('name', 'length', 'max'=>100),
			array('vendor', 'length', 'max'=>50),
			array('sku', 'length', 'max'=>20),
			array('taxable', 'length', 'max'=>2),
			array('category', 'length', 'max'=>13),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, company_id, vendor, sku, retail, cost, taxable, category, internal_notes, invoice_notes, enteredby, enteredtm', 'safe'),
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
			'company_id' => 'Company',
			'vendor' => 'Vendor',
			'sku' => 'SKU',
			'retail' => 'Retail',
			'cost' => 'Cost',
			'taxable' => 'Taxable',
			'template_id' => 'Template',
			'category' => 'Category',
			'internal_notes' => 'Internal Notes',
			'invoice_notes' => 'Invoice Notes',
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
		$criteria->compare('vendor',$this->vendor,true);
		$criteria->compare('sku',$this->sku,true);
		$criteria->compare('retail',$this->retail);
		$criteria->compare('cost',$this->cost);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
  
  public function save()
  {
    if(empty($this->company_id) && Yii::app()->user->hasState('company_id'))
			$this->company_id = Yii::app()->user->company_id;
    $ret = parent::save();
    
    return $ret;
  }
  
  public static function getAllForPackage($packageID = 0)
	{
		$products = array();
    
    $connection = Yii::app()->db;
    
    if ($packageID) {
      $sql = 'SELECT * FROM inventory WHERE id not in (select inventory_id from package_product where package_id='.$packageID.') and company_id=:company_id ORDER BY name';
    } else {
      $sql = 'SELECT * FROM inventory WHERE company_id=:company_id ORDER BY name';
    }
    
    $command = $connection->createCommand($sql);
    $command->bindParam(':company_id', Yii::app()->user->company_id);
    $dr = $command->query();
    
    while ($row = $dr->read()) {
      $products[$row['id']] = $row['name'];
    }
    return $products;
	}
  
  public static function getAllForCustomer($customerID = 0, $hasEmptyOption=true, $category='')
	{
    if ($hasEmptyOption) {
      $products = array(''=>'');
    } else {
      $products = array();
    }
    
    $categorySQL1 = $categorySQL2 = '';
    if ($category != '') {
      $categorySQL1 = " and category='". $category ."'";
      $categorySQL2 = " where category='". $category ."'";
    }
    
    $connection = Yii::app()->db;
    
    
    
    if ($customerID) {
//      $sql = 'SELECT * FROM inventory WHERE id not in (select inventory_id from product where customer_id='.$customerID.') '. $categorySQL1 .' ORDER BY name';
      $sql = 'SELECT * FROM inventory WHERE id not in (select inventory_id from product where customer_id='.$customerID.') '. $categorySQL1 .' AND company_id=:company_id ORDER BY name';
    } else {
//      $sql = 'SELECT * FROM inventory '. $categorySQL2 .' ORDER BY name';
      $sql = 'SELECT * FROM inventory '. $categorySQL2 .' AND company_id=:company_id ORDER BY name';
    }
    
    $command = $connection->createCommand($sql);
    $command->bindParam(':company_id', Yii::app()->user->company_id);
    $dr = $command->query();
    while ($row = $dr->read()) {
      $products[$row['id']] = $row['name'];
    }
    return $products;
  }
  
}