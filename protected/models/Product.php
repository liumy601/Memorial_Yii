<?php

/**
 * This is the model class for table "product".
 *
 * The followings are the available columns in table 'product':
 * @property integer $id
 * @property integer $company_id
 * @property integer $customer_id
 * @property string $internal_notes
 * @property string $invoice_notes
 * @property string $product_retail
 */
class Product extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Product the static model class
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
		return 'product';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('company_id, customer_id', 'numerical', 'integerOnly'=>true),
//			array('internal_notes, invoice_notes','length', 'max'=>20),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, company_id, customer_id, internal_notes, invoice_notes, product_retail', 'safe', 'on'=>'search'),
			array('id, company_id, customer_id, internal_notes, invoice_notes, enteredby, enteredtm, product_retail', 'safe'),
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
			'customer_id' => 'Customerid',
			'internal_notes' => 'Internal Notes',
			'invoice_notes' => 'Invoice Notes',
			'product_retail' => 'Product Retail',
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
		$criteria->compare('inventory_id',$this->inventory_id);
		$criteria->compare('company_id',$this->company_id);
		$criteria->compare('customer_id',$this->customer_id);
		$criteria->compare('price',$this->price);
		$criteria->compare('internal_notes',$this->internal_notes,true);
		$criteria->compare('invoice_notes',$this->invoice_notes,true);
		$criteria->compare('product_retail',$this->product_retail,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
  
 
}