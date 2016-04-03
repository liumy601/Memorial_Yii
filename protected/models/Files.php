<?php

class Files extends CActiveRecord
{
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return 'files';
	}

	public function rules()
	{
		return array(
			array('id,name,filename,file,visible_users,visible_depart,enteredby,timestamp', 'safe'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
        'enteredby'=>'Entered By',
        'timestamp'=>'Entered Date',
		);
	}
  
  public function save()
  {
    //set the company_id
    $this->company_id = Yii::app()->user->company_id;
    return parent::save();
  }
  
  
}