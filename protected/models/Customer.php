<?php

/**
 * This is the model class for table "customer".
 *
 * The followings are the available columns in table 'customer':
 * @property string $name
 * @property integer $id
 * @property string $deceased_photo
 * @property string $form_type
 * @property string $case_number
 * @property string $skfh_funeral_home
 * @property string $full_legal_name
 * @property string $name_for_obituary
 * @property string $address
 * @property string $city
 * @property string $state
 * @property string $zip
 * @property string $country
 * @property string $age
 * @property string $sex
 * @property string $formerly_of
 * @property string $date_of_birth
 * @property string $place_of_birth
 * @property string $date_of_death
 * @property string $time_of_death_h
 * @property string $time_of_death_m
 * @property string $time_of_death_z
 * @property string $zone_of_death
 * @property string $place_of_death
 * @property string $officiant
 * @property string $location_of_funeral_service
 * @property string $location_of_funeral_service_other
 * @property string $funeral_service_date
 * @property string $location_of_visitation
 * @property string $location_of_visitation_other
 * @property string $date_of_visitation_start
 * @property string $date_of_visitation_end
 * @property string $disposition_type
 * @property string $disposition_date
 * @property string $disposition_place
 * @property string $date_of_burial
 * @property string $burial
 * @property string $special_rites
 * @property string $church_membership
 * @property string $memorials
 * @property string $fathers_name_f
 * @property string $fathers_name_m
 * @property string $fathers_name_l
 * @property string $mothers_name_f
 * @property string $mothers_name_m
 * @property string $mothers_name_l
 * @property string $highest_level_of_education
 * @property string $occupation
 * @property string $biography
 * @property string $veteran_status
 * @property string $branch
 * @property string $full_military_rites
 * @property string $marital_status
 * @property string $spouse_f
 * @property string $spouse_m
 * @property string $spouse_l
 * @property string $date_of_marriage
 * @property string $place_of_marriage
 * @property string $previous_marriage
 * @property string $date_of_previous_marriage
 * @property string $place_of_previous_marriage
 * @property string $spouse_date_of_death
 * @property string $newspaper_radio1
 * @property string $newspaper_radio2
 * @property string $newspaper_radio3
 * @property string $newspaper_radio4
 * @property string $newspaper_radio5
 * @property string $newspaper_radio6
 * @property string $newspaper_radio1_other
 * @property string $newspaper_radio2_other
 * @property string $newspaper_radio3_other
 * @property string $newspaper_radio4_other
 * @property string $newspaper_radio5_other
 * @property string $newspaper_radio6_other
 * @property string $submit_pic_with_obit
 * @property string $pallbearers
 * @property string $special_music
 * @property string $doctors_name
 * @property string $ssn
 * @property string $informant_name_f
 * @property string $informant_relationship
 * @property string $informant_phone
 * @property string $military_veteran
 * @property integer $assigned_to
 * @property string $status
 * @property string $enteredby
 * @property integer $enteredtm
 * @property integer $updatedby
 * @property integer $updatedtm
 * @property integer $company_id
 */
class Customer extends CActiveRecord
{
  public $name;
  public $case_number_seq;
  
	/**
	 * Returns the static model of the specified AR class.
	 * @return Customer the static model class
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
		return 'customer';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('full_legal_name', 'required'),
			array('assigned_to, enteredtm, company_id', 'numerical', 'integerOnly'=>true),
			array('case_number, skfh_funeral_home, name_for_obituary, address, city, state, zip, country, age, sex, formerly_of, date_of_birth, place_of_birth, date_of_death, time_of_death_h, time_of_death_m, time_of_death_z, place_of_death, officiant, location_of_funeral_service, funeral_service_date, location_of_funeral_service_other, location_of_visitation, location_of_visitation_other, date_of_visitation_start, date_of_visitation_end, disposition_type, disposition_date, disposition_place, burial, special_rites, church_membership, fathers_name_f, fathers_name_m, fathers_name_l, mothers_name_f, mothers_name_m, mothers_name_l, highest_level_of_education, marital_status, spouse_f, spouse_m, spouse_l, date_of_marriage, place_of_marriage, date_of_previous_marriage, place_of_previous_marriage, spouse_date_of_death, submit_pic_with_obit, doctors_name, ssn, informant_name_f, informant_name_l, informant_relationship, informant_phone, informant_name_address, informant_name_city, informant_name_state, informant_name_zip, informant_name_country, military_veteran', 'length', 'max'=>50),
			array('clergy_full_name2', 'length', 'max'=>255),
			array('form_type', 'length', 'max'=>20),
			array('veteran_status', 'length', 'max'=>20),
			array('branch', 'length', 'max'=>20),
			array('full_military_rites', 'length', 'max'=>2),
			array('previous_marriage', 'length', 'max'=>30),
			array('full_legal_name', 'length', 'max'=>150),
			array('status', 'length', 'max'=>15),
			array('enteredby', 'length', 'max'=>15),
			array('company_id', 'length', 'max'=>11),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, form_type, case_number, skfh_funeral_home, full_legal_name, name_for_obituary, address, city, state, zip, country, age, sex, clergy_full_name2, formerly_of, date_of_birth, place_of_birth, date_of_death, time_of_death_h, time_of_death_m, time_of_death_z, place_of_death, officiant, location_of_funeral_service, location_of_funeral_service_other, funeral_service_date, location_of_visitation, location_of_visitation_other, date_of_visitation_start, date_of_visitation_end, date_of_visitation_end, disposition_type, disposition_date, disposition_place, burial, special_rites, church_membership, memorials, fathers_name_f, fathers_name_m, fathers_name_l, mothers_name_f, mothers_name_m, mothers_name_l, highest_level_of_education, marital_status, spouse_f, spouse_m, spouse_l, date_of_marriage, place_of_marriage, previous_marriage, date_of_previous_marriage, place_of_previous_marriage,spouse_date_of_death, spouse_date_of_death, submit_pic_with_obit, pallbearers, special_music, doctors_name, ssn, informant_name_f, informant_name_l, informant_relationship, informant_phone, informant_name_address, informant_name_city, informant_name_state, informant_name_zip, informant_name_country, military_veteran, assigned_to, status, enteredby, enteredtm, company_id', 'safe', 'on'=>'search'),
			array('deceased_photo, sex, form_type, occupation, biography, veteran_status, branch, full_military_rites, memorials, updatedby, updatedtm, zone_of_death, time_of_birth_h, time_of_birth_m, time_of_birth_z, zone_of_birth,
          funeral_service_time_h, funeral_service_time_m, funeral_service_time_z,
          visitation_time_h_start, visitation_time_m_start, visitation_time_z_start,
          visitation_time_h_end, visitation_time_m_end, visitation_time_z_end,
          date_of_burial, time_of_burial_h, time_of_burial_m, time_of_burial_z,
          survived_by, preceded_in_death_by, newspaper_radio1, newspaper_radio2, newspaper_radio3, newspaper_radio4, newspaper_radio5, newspaper_radio6, newspaper_radio1_other, newspaper_radio2_other, newspaper_radio3_other, newspaper_radio4_other, newspaper_radio5_other, newspaper_radio6_other, full_legal_name, 
          music_selection1, music_selection2, music_selection3, music_selection4, music_selection5, pallbearers, pallbearer2, pallbearer3, pallbearer4, pallbearer5, pallbearer6, pallbearer7, pallbearer8, special_music, company_id, 
          interment_city, interment_country, interment_state, case_number_seq, full_legal_name_f, full_legal_name_m, full_legal_name_l, full_legal_prefix, city_of_birth, state_of_birth, pod_facility_name, pod_facility_street, pod_facility_city, pod_facility_state, pod_facility_zip, interment_street, interment_zip, veteran_serial_number, doctor_street, doctor_city, doctor_state, doctor_zip',  'safe'),
			array('case_number', 'uniqueCaseNumByCompany'),
		);
	}
  
  public function loadAddon()
  {
//    $this->name = $this->full_legal_name_f . ' '. $this->full_legal_name_m .' '. $this->full_legal_name_l;
    $this->name = $this->full_legal_name;
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
			'deceased_photo' => 'Deceased Photo',
			'form_type' => 'Form Type',
			'case_number' => 'Case Number',
			'skfh_funeral_home' => 'Location',
			'full_legal_name' => 'Full Legal Name',
			'full_legal_name_f' => 'Legal First Name',
			'full_legal_name_m' => 'Legal Middle Name',
			'full_legal_name_l' => 'Legal Last Name',
			'full_legal_prefix' => 'Legal Prefix',
			'name_for_obituary' => 'Name For Obituary',
			'address' => 'Address',
			'age' => 'Age',
			'sex' => 'Sex',
			'clergy_full_name2' => 'Officiant2',
			'formerly_of' => 'Formerly Of',
			'date_of_birth' => 'Date Of Birth',
			'place_of_birth' => 'Place Of Birth',
			'date_of_death' => 'Date Time Of Death',
			'place_of_death' => 'Place Of Death',
			'officiant' => 'Officiant',
			'location_of_funeral_service' => 'Location Of Funeral Service',
			'location_of_funeral_service_other' => 'Location Of Funeral Service',
			'funeral_service_date' => 'Funeral Service Date And Time',
			'location_of_visitation' => 'Location Of Visitation',
			'location_of_visitation_other' => 'Location Of Visitation',
			'date_of_visitation_start' => 'Date And Time Of Start Visitation',
			'date_of_visitation_end' => 'Date And Time Of End Visitation',
			'disposition_type' => 'Disposition Type',
			'disposition_date' => 'Disposition Date',
			'disposition_place' => 'Disposition Place',
			'date_of_burial' => 'Date And Time Of Burial',
			'burial' => 'Burial',
			'special_rites' => 'Special Rites',
			'church_membership' => 'Church Membership',
			'memorials' => 'Memorials',
			'fathers_name_f' => 'Fathers Name',
			'mothers_name_f' => 'Mothers Name',
			'highest_level_of_education' => 'Highest Level Of Education',
			'occupation' => 'Occupation',
			'biography' => 'Biography',
			'veteran_status' => 'Veteran status',
			'branch' => 'Branch',
			'full_military_rites' => 'Full Military Rites',
			'marital_status' => 'Marital Status',
			'spouse_f' => 'Spouse',
			'date_of_marriage' => 'Date Of Marriage',
			'place_of_marriage' => 'Place Of Marriage',
			'previous_marriage' => 'Previous Marriage',
			'date_of_previous_marriage' => 'Date of Previous Marriage',
			'place_of_previous_marriage' => 'Place of Previous Marriage',
			'spouse_date_of_death' => 'Spouse Date Of Death',
			'newspaper_radio1' => 'Newspaper/Radio 1',
			'newspaper_radio2' => 'Newspaper/Radio 2',
			'newspaper_radio3' => 'Newspaper/Radio 3',
			'newspaper_radio4' => 'Newspaper/Radio 4',
			'newspaper_radio5' => 'Newspaper/Radio 5',
			'newspaper_radio6' => 'Newspaper/Radio 6',
			'newspaper_radio1_other, ' => 'Newspaper/Radio 1 Other',
			'newspaper_radio2_other, ' => 'Newspaper/Radio 2 Other',
			'newspaper_radio3_other, ' => 'Newspaper/Radio 3 Other',
			'newspaper_radio4_other, ' => 'Newspaper/Radio 4 Other',
			'newspaper_radio5_other, ' => 'Newspaper/Radio 5 Other',
			'newspaper_radio6_other, ' => 'Newspaper/Radio 6 Other',
      'submit_pic_with_obit' => 'Submit Pic With Obit',
        
			'music_selection1' => 'MusicSelection1',
			'music_selection2' => 'MusicSelection2',
			'music_selection3' => 'MusicSelection3',
			'music_selection4' => 'MusicSelection4',
			'music_selection5' => 'MusicSelection5',
        
			'pallbearers' => 'Pallbearer1',
			'pallbearer2' => 'Pallbearer2',
			'pallbearer3' => 'Pallbearer3',
			'pallbearer4' => 'Pallbearer4',
			'pallbearer5' => 'Pallbearer5',
			'pallbearer6' => 'Pallbearer6',
			'pallbearer7' => 'Pallbearer7',
			'pallbearer8' => 'Pallbearer8',
			'special_music' => 'Special Music',
			'doctors_name' => 'Doctors Name',
			'ssn' => 'SSN',
			'informant_name_f' => 'Informant Name',
			'informant_name_l' => 'Informant Name',
			'informant_relationship' => 'Informant Relationship',
			'informant_phone' => 'Informant Phone',
			'informant_name_address' => 'Informant Address',
			'informant_name_city' => 'Informant city',
			'informant_name_state' => 'Informant state',
			'informant_name_zip' => 'Informant zip',
			'informant_name_country' => 'Informant country',
			'military_veteran' => 'Military Veteran',
			'survived_by' => 'Survived By',
			'preceded_in_death_by' => 'Preceded in Death By',
			'assigned_to' => 'Assigned',
			'status' => 'Status',
			'enteredby' => 'Created By',
			'enteredtm' => 'Created Time',
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
		$criteria->compare('deceased_photo',$this->deceased_photo);
		$criteria->compare('form_type',$this->form_type);
		$criteria->compare('case_number',$this->case_number,true);
		$criteria->compare('skfh_funeral_home',$this->skfh_funeral_home,true);
		$criteria->compare('full_legal_name',$this->full_legal_name,true);
//		$criteria->compare('full_legal_name_f',$this->full_legal_name,true);
//		$criteria->compare('full_legal_name_m',$this->full_legal_name,true);
//		$criteria->compare('full_legal_name_l',$this->full_legal_name,true);
		$criteria->compare('name_for_obituary',$this->name_for_obituary,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('age',$this->age,true);
		$criteria->compare('sex',$this->sex,true);
		$criteria->compare('clergy_full_name2',$this->clergy_full_name2,true);
		$criteria->compare('formerly_of',$this->formerly_of,true);
		$criteria->compare('date_of_birth',$this->date_of_birth,true);
		$criteria->compare('place_of_birth',$this->place_of_birth,true);
		$criteria->compare('date_of_death',$this->date_of_death,true);
		$criteria->compare('place_of_death',$this->place_of_death,true);
		$criteria->compare('officiant',$this->officiant,true);
		$criteria->compare('location_of_funeral_service',$this->location_of_funeral_service,true);
		$criteria->compare('location_of_funeral_service_other',$this->location_of_funeral_service_other,true);
		$criteria->compare('funeral_service_date',$this->funeral_service_date,true);
		$criteria->compare('location_of_visitation',$this->location_of_visitation,true);
		$criteria->compare('location_of_visitation_other',$this->location_of_visitation_other,true);
		$criteria->compare('date_of_visitation_start',$this->date_of_visitation_start,true);
		$criteria->compare('date_of_visitation_end',$this->date_of_visitation_end,true);
		$criteria->compare('burial',$this->burial,true);
		$criteria->compare('special_rites',$this->special_rites,true);
		$criteria->compare('church_membership',$this->church_membership,true);
		$criteria->compare('memorials',$this->memorials,true);
		$criteria->compare('fathers_name_f',$this->fathers_name_f,true);
		$criteria->compare('mothers_name_f',$this->mothers_name_f,true);
		$criteria->compare('highest_level_of_education',$this->highest_level_of_education,true);
		$criteria->compare('occupation',$this->occupation,true);
		$criteria->compare('biography',$this->biography,true);
		$criteria->compare('veteran_status',$this->veteran_status,true);
		$criteria->compare('branch',$this->branch,true);
		$criteria->compare('full_military_rites',$this->full_military_rites,true);
		$criteria->compare('marital_status',$this->marital_status,true);
		$criteria->compare('spouse_f',$this->spouse_f,true);
		$criteria->compare('date_of_marriage',$this->date_of_marriage,true);
		$criteria->compare('place_of_marriage',$this->place_of_marriage,true);
		$criteria->compare('previous_marriage',$this->previous_marriage,true);
		$criteria->compare('date_of_previous_marriage',$this->date_of_previous_marriage,true);
		$criteria->compare('place_of_previous_marriage',$this->place_of_previous_marriage,true);
		$criteria->compare('spouse_date_of_death',$this->spouse_date_of_death,true);
		$criteria->compare('newspaper_radio1',$this->newspaper_radio1,true);
		$criteria->compare('newspaper_radio2',$this->newspaper_radio2,true);
		$criteria->compare('newspaper_radio3',$this->newspaper_radio3,true);
		$criteria->compare('newspaper_radio4',$this->newspaper_radio4,true);
		$criteria->compare('newspaper_radio5',$this->newspaper_radio5,true);
		$criteria->compare('newspaper_radio6',$this->newspaper_radio6,true);
		$criteria->compare('newspaper_radio1_other',$this->newspaper_radio1_other,true);
		$criteria->compare('newspaper_radio2_other',$this->newspaper_radio2_other,true);
		$criteria->compare('newspaper_radio3_other',$this->newspaper_radio3_other,true);
		$criteria->compare('newspaper_radio4_other',$this->newspaper_radio4_other,true);
		$criteria->compare('newspaper_radio5_other',$this->newspaper_radio5_other,true);
		$criteria->compare('newspaper_radio6_other',$this->newspaper_radio6_other,true);
		$criteria->compare('submit_pic_with_obit',$this->submit_pic_with_obit,true);
    
		$criteria->compare('music_selection1',$this->music_selection1,true);
		$criteria->compare('music_selection2',$this->music_selection2,true);
		$criteria->compare('music_selection3',$this->music_selection3,true);
		$criteria->compare('music_selection4',$this->music_selection4,true);
		$criteria->compare('music_selection5',$this->music_selection5,true);
    
		$criteria->compare('pallbearers',$this->pallbearers,true);
		$criteria->compare('pallbearer2',$this->pallbearer2,true);
		$criteria->compare('pallbearer3',$this->pallbearer3,true);
		$criteria->compare('pallbearer4',$this->pallbearer4,true);
		$criteria->compare('pallbearer5',$this->pallbearer5,true);
		$criteria->compare('pallbearer6',$this->pallbearer6,true);
		$criteria->compare('pallbearer7',$this->pallbearer7,true);
		$criteria->compare('pallbearer8',$this->pallbearer8,true);
		$criteria->compare('special_music',$this->special_music,true);
		$criteria->compare('doctors_name',$this->doctors_name,true);
		$criteria->compare('ssn',$this->ssn,true);
		$criteria->compare('informant_name_f',$this->informant_name_f,true);
		$criteria->compare('informant_relationship',$this->informant_relationship,true);
		$criteria->compare('informant_phone',$this->informant_phone,true);
		$criteria->compare('military_veteran',$this->military_veteran,true);
		$criteria->compare('assigned_to',$this->assigned_to);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('enteredby',$this->enteredby,true);
		$criteria->compare('enteredtm',$this->enteredtm);
		$criteria->compare('company_id',$this->company_id);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
  
  public function calAge()
  {
    if ($this->date_of_death && $this->date_of_birth) {
      /** 
        $birthArr = array( 
        'year' => '2000', 
        'month' => '11', 
        'day' => '3' 
        ); 
        $birthStr = '2000-11-03'; 
        */ 
      list($month, $day, $year) = explode('/', $this->date_of_birth);
      list($monthD, $dayD, $yearD) = explode('/', $this->date_of_death);
      
      $age = $yearD - $year;
      if ($monthD < $month || ($monthD == $month && $dayD < $day)){//if month less or month same but day less
        $age--;
      }
      
      return $age;
    } else {
      return '';
    }
  }
  
  public function genFullName($field)
  {
    return $this->{$field . '_f'} .' '. $this->{$field . '_m'} .' '. $this->{$field . '_l'};
  }
  
  public function genFullAddress()
  {
    $fullAddress = $this->address;
    
    if ($this->city) {
      $fullAddress .= ', ' . $this->city;
    }
    if ($this->state) {
      $fullAddress .= ', ' . $this->state;
    }
    if ($this->zip) {
      $fullAddress .= ' ' . $this->zip;
    }
    if ($this->country) {
      $fullAddress .= ' ' . $this->country;
    }
    
    return $fullAddress;
  }
  
  public function genFullInformantAddress()
  {
    $fullAddress = $this->informant_name_address;
    
    if ($this->informant_name_city) {
      $fullAddress .= ', ' . $this->informant_name_city;
    }
    if ($this->informant_name_state) {
      $fullAddress .= ', ' . $this->informant_name_state;
    }
    if ($this->informant_name_zip) {
      $fullAddress .= ' ' . $this->informant_name_zip;
    }
    if ($this->informant_name_country) {
      $fullAddress .= ' ' . $this->informant_name_country;
    }
    
    return $fullAddress;
  }
  
//  public function formatDateTime($dateField, $timeField)
//  { 
//    if($this->{$dateField} == ''){
//      return ;
//    }else{
//      return $this->{$timeField .'_h'} . ':'. $this->{$timeField .'_m'} .' '. $this->{$timeField .'_z'} .' on ' . date('M d, Y', strtotime($this->{$dateField}));
//    }
//  }
  
  public function formatDateTime($dateField, $timeField='')
  { 
    if($this->{$dateField} == ''){
      return ;
    }else{
      if($timeField != ''){
        if($this->{$timeField .'_h'} || $this->{$timeField .'_m'} || $this->{$timeField .'_z'}){
          return ltrim($this->{$timeField .'_h'}, '0') . ':'. $this->{$timeField .'_m'} .' '. $this->{$timeField .'_z'} .' on ' . date('F d, Y', strtotime($this->{$dateField}));
        }else{
          return date('F d, Y', strtotime($this->{$dateField}));
        }
      }else{
        return date('F d, Y', strtotime($this->{$dateField}));
      }
    }
  }
  
  public function formatDateTimeFuneral($dateField, $timeField='')
  { 
    if($this->{$dateField} == ''){
      return ;
    }else{
      if($timeField != ''){
        if($this->{$timeField .'_h'} || $this->{$timeField .'_m'} || $this->{$timeField .'_z'}){
          return ltrim($this->{$timeField .'_h'}, '0') . ':'. $this->{$timeField .'_m'} .' '. $this->{$timeField .'_z'} .' on ' . date('l, F d, Y', strtotime($this->{$dateField}));
        }else{
          return date('F d, Y', strtotime($this->{$dateField}));
        }
      }else{
        return date('F d, Y', strtotime($this->{$dateField}));
      }
    }
  }
  
  public function formatDateTimeStart($dateField, $timeField)
  {
    if($this->{$dateField} == ''){
      return ;
    }else{
    return ltrim($this->{$timeField .'_h_start'}, '0') . ':'. $this->{$timeField .'_m_start'} .' '. $this->{$timeField .'_z_start'} .' on ' . date('F d, Y', strtotime($this->{$dateField}));
    }
  }
  
  public function formatDateTimeEnd($dateField, $timeField)
  {
    if($this->{$dateField} == ''){
      return ;
    }else{
      return ltrim($this->{$timeField .'_h_end'}, '0') . ':'. $this->{$timeField .'_m_end'} .' '. $this->{$timeField .'_z_end'} .' on ' . date('F d, Y', strtotime($this->{$dateField}));
    }
  }
  
  public function formatDateTimeWithWeekday($dateField, $timeField, $zoneField='')
  {
    if($this->{$dateField} == ''){
      return ;
    }else{
      $ret = date('D F d Y', strtotime($this->{$dateField})) . ' ' . ltrim($this->{$timeField .'_h'}, '0') . ':'. $this->{$timeField .'_m'} .' '. $this->{$timeField .'_z'};
    
      if ($zoneField != '') {
        $ret .= ' '. $this->{$zoneField};
      }

      $ret .= ' (CST) ';

      return $ret;
    }
    
  }
  
  public function formatDateTimeWithWeekday1($dateField, $timeField='', $zoneField='')
  {
    if($this->{$dateField} == ''){
      return ;
    }else{
      if($timeField != ''){
        $ret = date('D F d Y', strtotime($this->{$dateField})) . ' ' . ltrim($this->{$timeField .'_h'}, '0') . ':'. $this->{$timeField .'_m'} .' '. $this->{$timeField .'_z'};
       
        if($zoneField != '') {
          $ret .= ' '. $this->{$zoneField};
        }
        
        $ret .= ' (CST) ';
      }else{
        $ret = date('D F d Y', strtotime($this->{$dateField})) ;
        
        if($zoneField != '') {
           $ret .= ' '. $this->{$zoneField};
        }
      }
//      $ret .= ' (CST) ';

      return $ret;
    }
    
  }
  
  public function save()
  {
    if (!$this->company_id) {
      $this->company_id = Yii::app()->user->company_id;
    }
    $ret = parent::save();
    
    return $ret;
  }

  public function uniqueCaseNumByCompany() {
	$count = Customer::count('company_id='. $this->company_id .' and case_number='. $this->case_number);
	if($count > 0) {
		$this->addError('case_number', 'Case number "'. $this->case_number .'" has already been taken');
	}
  }
  
}