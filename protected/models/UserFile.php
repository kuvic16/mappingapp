<?php

/**
 * This is the model class for table "user_file".
 *
 * The followings are the available columns in table 'user_file':
 * @property string $id
 * @property string $username
 * @property string $file_name
 * @property string $physical_file_name
 * @property string $creation_date
 * @property string $last_modified_date
 */
class UserFile extends CActiveRecord
{   
        public $csv_file;
        public $csv_data;
        public $is_new;
        public $column_name;
        public $column_value;
        public $row_id;
        public $column_id;
        
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'user_file';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
                        
                        //array ('csv_file', 'file', 'types' => 'csv'),
			//array('user_id, file_name', 'required'),
			//array('user_id', 'length', 'max'=>10),
			//array('file_name', 'length', 'max'=>250),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, username, file_name', 'safe', 'on'=>'search'),
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
			'username' => 'Username',
			'file_name' => 'File Name',
                        'csv_file' => 'Choose CSV/Excel file',
                        'column_name' => 'Column Name',
                        'column_value' => 'Column Value',
                        'column_id' => 'Column Id',
                        'row_id' => 'Row Id',
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

		$criteria->compare('id',$this->id,true);
		//$criteria->compare('username',$this->username,true);
		$criteria->compare('file_name',$this->file_name,true);
                $criteria->compare('creation_date',$this->creation_date,true);
                $criteria->compare('last_modified_date',$this->last_modified_date,true);
                
                if(Yii::app()->user->name != 'admin'){
                    $criteria->condition ="username='". Yii::app()->user->name . "'";
                }

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
        
        
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return UserFile the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
