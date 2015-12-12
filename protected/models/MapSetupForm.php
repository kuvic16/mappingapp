<?php

/**
 * MapSetupForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class MapSetupForm extends CFormModel {
    
    public $id;
    public $file_name;
    public $columns;
    public $name_index;
    public $address_index;
    public $city_index;
    public $state_index;
    public $zipcode_index;
    public $phone_index;
    public $field1_index;
    public $field1_label;
    public $field2_index;
    public $field2_label;
    public $field3_index;
    public $field3_label;
    public $field4_index;
    public $field4_label;
    public $field5_index;
    public $field5_label;

    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules() {
        return array(
            array('name_index, address_index, city_index, state_index, zipcode_index', 'required'),
        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels() {
        return array(
            'name_index' => 'Name column',
            'address_index' => 'Address column',
            'city_index' => 'City column',
            'state_index' => 'State column',
            'zipcode_index' => 'Zipcode column',
            'phone_index' => 'Phone column',
            'field1_index' => 'Field1 column',
            'field1_label' => 'Misc Field 1 Label',
            'field2_index' => 'Field2 column',
            'field2_label' => 'Misc Field 2 Label',
            'field3_index' => 'Field3 column',
            'field3_label' => 'Misc Field 3 Label',
            'field4_index' => 'Field4 column',
            'field4_label' => 'Misc Field 4 Label',
            'field5_index' => 'Field5 column',
            'field5_label' => 'Misc Field 5 Label',
        );
    }
}
