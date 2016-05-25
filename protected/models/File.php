<?php

class File extends CActiveRecord
{
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return 'file';
	}

	public function rules()
	{
		return array(
			array('id, relate_id, field, form_uniqid, name, server_name, timestamp', 'safe'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels(){
    return array();
	}
}