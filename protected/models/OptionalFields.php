<?php

/**
 * This is the model class for table "optional_fields".
 *
 * The followings are the available columns in table 'optional_fields':
 * @property integer $id
 * @property integer $company_id
 * @property integer $full_legal_name_f
 * @property integer $full_legal_name_m
 * @property integer $full_legal_name_l
 * @property integer $full_legal_prefix
 * @property integer $city_of_birth
 * @property integer $state_of_birth
 * @property integer $pod_facility_name
 * @property integer $pod_facility_street
 * @property integer $pod_facility_city
 * @property integer $pod_facility_state
 * @property integer $pod_facility_zip
 * @property integer $interment_street
 * @property integer $interment_zip
 * @property integer $veteran_serial_number
 * @property integer $doctor_street
 * @property integer $doctor_city
 * @property integer $doctor_state
 * @property integer $doctor_zip
 */
class OptionalFields extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'optional_fields';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('company_id, full_legal_name_f, full_legal_name_m, full_legal_name_l, full_legal_prefix, city_of_birth, state_of_birth, pod_facility_name, pod_facility_street, pod_facility_city, pod_facility_state, pod_facility_zip, interment_street, interment_zip, veteran_serial_number, doctor_street, doctor_city, doctor_state, doctor_zip', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, company_id, full_legal_name_f, full_legal_name_m, full_legal_name_l, full_legal_prefix, city_of_birth, state_of_birth, pod_facility_name, pod_facility_street, pod_facility_city, pod_facility_state, pod_facility_zip, interment_street, interment_zip, veteran_serial_number, doctor_street, doctor_city, doctor_state, doctor_zip', 'safe', 'on'=>'search'),
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
			'full_legal_name_f' => 'Legal First Name',
			'full_legal_name_m' => 'Legal Middle Name',
			'full_legal_name_l' => 'Legal Last Name',
			'full_legal_prefix' => 'Legal Prefix',
			'city_of_birth' => 'City Of Birth',
			'state_of_birth' => 'State Of Birth',
			'pod_facility_name' => 'Pod Facility Name',
			'pod_facility_street' => 'Pod Facility Street',
			'pod_facility_city' => 'Pod Facility City',
			'pod_facility_state' => 'Pod Facility State',
			'pod_facility_zip' => 'Pod Facility Zip',
			'interment_street' => 'Interment Street',
			'interment_zip' => 'Interment Zip',
			'veteran_serial_number' => 'Veteran Serial Number',
			'doctor_street' => 'Doctor Street',
			'doctor_city' => 'Doctor City',
			'doctor_state' => 'Doctor State',
			'doctor_zip' => 'Doctor Zip',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('company_id',$this->company_id);
		$criteria->compare('full_legal_name_f',$this->full_legal_name_f);
		$criteria->compare('full_legal_name_m',$this->full_legal_name_m);
		$criteria->compare('full_legal_name_l',$this->full_legal_name_l);
		$criteria->compare('full_legal_prefix',$this->full_legal_prefix);
		$criteria->compare('city_of_birth',$this->city_of_birth);
		$criteria->compare('state_of_birth',$this->state_of_birth);
		$criteria->compare('pod_facility_name',$this->pod_facility_name);
		$criteria->compare('pod_facility_street',$this->pod_facility_street);
		$criteria->compare('pod_facility_city',$this->pod_facility_city);
		$criteria->compare('pod_facility_state',$this->pod_facility_state);
		$criteria->compare('pod_facility_zip',$this->pod_facility_zip);
		$criteria->compare('interment_street',$this->interment_street);
		$criteria->compare('interment_zip',$this->interment_zip);
		$criteria->compare('veteran_serial_number',$this->veteran_serial_number);
		$criteria->compare('doctor_street',$this->doctor_street);
		$criteria->compare('doctor_city',$this->doctor_city);
		$criteria->compare('doctor_state',$this->doctor_state);
		$criteria->compare('doctor_zip',$this->doctor_zip);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return OptionalFields the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
