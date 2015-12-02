<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class FileUploadForm extends CFormModel
{
	public $csv_file;
        public $id;
        public $username;
        public $file_name;
        public $physical_file_name;
        public $creation_date;
        public $last_modified_date;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'csv_file'=>'Upload csv file',
		);
	}
}
