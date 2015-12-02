<?php

/**
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'user':
 * @property string $id
 * @property string $username
 * @property string $email
 * @property string $password
 * @property string $first_name
 * @property string $middle_name
 * @property string $last_name
 * @property string $address
 * @property string $subscription_level
 * @property string $renewal_date
 */
class User extends CActiveRecord
{
        public $password_first;
        public $password_repeat;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'user';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('username, email, password_first, password_repeat', 'required'),
			array('username, email, first_name, middle_name, last_name, subscription_level, renewal_date', 'length', 'max'=>45),
			array('password_first, password_repeat, address', 'length', 'max'=>500),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('username, email, first_name, middle_name, last_name, subscription_level, renewal_date', 'safe', 'on'=>'search'),
                        array('username', 'unique'),
//                        array('password_first', 'compare'),
//                        array('password_repeat', 'safe'),
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
			'email' => 'Email',
			'password' => 'Password',
                        'password_first' => 'Password',
                        'password_repeat' => 'Password Repeat',
			'first_name' => 'First Name',
			'middle_name' => 'Middle Name',
			'last_name' => 'Last Name',
			'address' => 'Address',
			'subscription_level' => 'Subscription Level',
			'renewal_date' => 'Renewal Date',
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

		//$criteria->compare('id',$this->id,true);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('email',$this->email,true);
		//$criteria->compare('password',$this->password,true);
		$criteria->compare('first_name',$this->first_name,true);
		$criteria->compare('middle_name',$this->middle_name,true);
		$criteria->compare('last_name',$this->last_name,true);
		//$criteria->compare('address',$this->address,true);
		$criteria->compare('subscription_level',$this->subscription_level,true);
		$criteria->compare('renewal_date',$this->renewal_date,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return User the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        
        public function hash($value)
        {
                return crypt($value, '$2a$10$1qAz2wSx3eDc4rFv5tGb5t');
        }
        
        protected function beforeSave()
        {
                if (parent::beforeSave() && ($this->password_first == $this->password_repeat))
                {
                        if($this->password != $this->password_first)
                        {
                                $this->password = $this->hash($this->password_first);
                        }
                        return true;
                }
                return false;
        }
        
        public function check($value)
        {
                $new_hash = crypt($value, '$2a$10$1qAz2wSx3eDc4rFv5tGb5t');
                if ($new_hash == $this->password) 
                {
                        return true;
                }
                return false;
        }
}
