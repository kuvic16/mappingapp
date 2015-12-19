<?php

class UserFileController extends Controller {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column2';

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
            array('allow', // allow all users to perform 'index' and 'view' actions
                'actions' => array('index', 'view', 'map', 'locationUpdate', 'dataUpdate'),
                'users' => array('@'),
            ),
            array('allow', // allow authenticated user to perform 'upload' and 'update' actions
                'actions' => array('upload', 'update', 'setup'),
                'users' => array('@'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('manage', 'delete'),
                'users' => array('@'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        $model = $this->loadModel($id);        
        if(strlen($model->name_index) !== 0 && strlen($model->address_index) !== 0 && strlen($model->city_index) !== 0 && strlen($model->state_index) !== 0 && strlen($model->zipcode_index) !== 0){
            $model->csv_data = $this->loadCsvFile($model->physical_file_name);
            $this->render('map', array(
                'model' => $model,
            ));
        }  else {
            $this->redirect(array('setup','id'=>$model->id));
        }
    }

    public function actionMap($id) {
        $model = $this->loadModel($id);        
        if(strlen($model->name_index) !== 0 && strlen($model->address_index) !== 0 && strlen($model->city_index) !== 0 && strlen($model->state_index) !== 0 && strlen($model->zipcode_index) !== 0){
            $model->csv_data = $this->loadCsvFile($model->physical_file_name);
            $this->render('map', array(
                'model' => $model,
            ));
        }  else {
            $this->redirect(array('setup','id'=>$model->id));
        }
    }
    
    public function actionSetup($id) {
        $model = $this->loadModel($id);
        $model->columns = $this->getColumns($model->physical_file_name);
        if (isset($_POST['UserFile'])) {
            $model->name_index = $_POST['UserFile']['name_index'];
            $model->address_index = $_POST['UserFile']['address_index'];
            $model->city_index = $_POST['UserFile']['city_index'];
            $model->state_index = $_POST['UserFile']['state_index'];
            $model->zipcode_index = $_POST['UserFile']['zipcode_index'];
            $model->phone_index = $_POST['UserFile']['phone_index'];
            
            $model->field1_index = $_POST['UserFile']['field1_index'];
            $model->field1_label = $_POST['UserFile']['field1_label'];
            $model->field2_index = $_POST['UserFile']['field2_index'];
            $model->field2_label = $_POST['UserFile']['field2_label'];
            $model->field3_index = $_POST['UserFile']['field3_index'];
            $model->field3_label = $_POST['UserFile']['field3_label'];
            $model->field4_index = $_POST['UserFile']['field4_index'];
            $model->field4_label = $_POST['UserFile']['field4_label'];
            $model->field5_index = $_POST['UserFile']['field5_index'];
            $model->field5_label = $_POST['UserFile']['field5_label'];
            
            //echo '<pre>' . var_export($model, true) . '</pre>';
            if($model->save())
		$this->redirect(array('setup','id'=>$model->id));
        }
        $this->render('setup', array(
            'model' => $model,
        ));
    }

    /**
     * Uploads a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionUpload() {
        $model = new UserFile;
        $model->username = Yii::app()->user->name;
        //echo time();                
        //echo date("Y-m-d h:m:s");
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
        if (isset($_POST['UserFile'])) {
            $model->attributes = $_POST['UserFile'];
            $model->csv_file = CUploadedFile::getInstance($model, 'csv_file');
            if ($model->validate()) {
                //The file is valid, you can save it
                $model->creation_date = date("Y-m-d h:m:s");
                $model->file_name = $model->csv_file;
                $model->physical_file_name = $model->username . "-" . time() . "-" . $model->csv_file;

                // create path
                $app_path = realpath(Yii::app()->basePath . '/../');
                $path = $app_path . '/files/' . $model->physical_file_name;
                //$path = 'C:\Users\craigslist\Desktop\temp\\' . $model->physical_file_name;
                //echo $path;
                $model->csv_file->saveAs($path);
                $ext = pathinfo($model->physical_file_name, PATHINFO_EXTENSION);
                $fileName = pathinfo($model->physical_file_name, PATHINFO_FILENAME);
                if ($ext === 'xls' || $ext === 'xlsx') {
                    $this->excelToCsvFile($model->physical_file_name, $fileName.'.csv');
                } 
            }

            if ($model->save())
                //$this->redirect(array('view', 'id' => $model->id));
                $this->redirect(array('setup', 'id' => $model->id));
        }

        $this->render('upload', array(
            'model' => $model,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $model = $this->loadModel($id);
        $model->csv_data = $this->loadCsvFile($model->physical_file_name);        
        //echo '<pre>' . var_export($model->csv_data, true) . '</pre>';

        if (isset($_POST['UserFile'])) {
            $model->column_name = $_POST['UserFile']['column_name'];
            $model->column_value = $_POST['UserFile']['column_value'];
            $model->row_id = $_POST['UserFile']['row_id'];
            $model->column_id = $_POST['UserFile']['column_id'];
            $model->is_new = $_POST['UserFile']['is_new'];

            if ($model->is_new === "1") {
                $this->addCsvColumn($model);
            } elseif ($model->is_new === "0") {
                $this->editCsvColumn($model);
            } elseif ($model->is_new === "2") {
                $this->addCsvRow($model);
            }
            
            $userFileModel = $this->loadModel($id);
            $userFileModel->last_modified_date = date("Y-m-d h:m:s");
            $userFileModel->save();
            $this->redirect(array('update', 'id' => $model->id));
        }

        $this->render('update', array(
            'model' => $model,
        ));
    }

    /* csv file operation */

    private function loadCsvFile($fileName) {
        $app_path = realpath(Yii::app()->basePath . '/../');
        
        $ext = pathinfo($fileName, PATHINFO_EXTENSION);
        $orgName = pathinfo($fileName, PATHINFO_FILENAME);
        $path = $app_path . '/files/' . $fileName;
        if ($ext === 'xls' || $ext === 'xlsx') {
            $path = $app_path . '/files/' . $orgName.'.csv';
        } 
                
        $row = 1;
        $length = 0;
        $maxLength = -1;
        $i = 0;
        $needChange = 0;

        //read csv file and store to array
        $csvData = array();
        if (($handle = fopen($path, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $length = count($data);
                if ($row == 1) {
                    $maxLength = $length;
                }
                if ($length > $maxLength) {
                    $needChange = 1;
                    $maxLength = $length;
                } elseif ($length < $maxLength) {
                    $needChange = 1;
                }
                array_push($csvData, $data);
                $row++;
            }
            fclose($handle);
        }
        
        // to avoid unexpected error
        if($maxLength < 10){
            $needChange = 1;
            $maxLength = 10;
        }
        //echo '<pre>' . var_export($maxLength, true) . '</pre>';
        //echo '<pre>' . var_export($needChange, true) . '</pre>';

        if ($needChange == 1) {
            //all line have same column
            $prfCsvData = array();
            foreach ($csvData as $line) {
                $length = count($line);
                if ($length < $maxLength) {
                    for ($i = $length; $i < $maxLength; $i++) {
                        array_push($line, "");
                    }
                }
                array_push($prfCsvData, $line);
            }

            //echo '<pre>' . var_export($prfCsvData, true) . '</pre>';
            //save to file
            $handle = fopen($path, 'w');
            foreach ($prfCsvData as $line) {
                fputcsv($handle, $line);
            }
            fclose($handle);
            return $prfCsvData;
        }

        return $csvData;
    }

    public function addCsvColumn($model) {
        $app_path = realpath(Yii::app()->basePath . '/../');
        
        $ext = pathinfo($model->physical_file_name, PATHINFO_EXTENSION);
        $fileName = pathinfo($model->physical_file_name, PATHINFO_FILENAME);
        $path = $app_path . '/files/' . $model->physical_file_name;
        if ($ext === 'xls' || $ext === 'xlsx') {
            $path = $app_path . '/files/' . $fileName.'.csv';
        } 
                
        $row = 1;
        $newCsvData = array();
        if (($handle = fopen($path, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                if ($row == 1) {
                    $data[] = $model->column_name;
                } else {
                    $data[] = "";
                }
                $newCsvData[] = $data;
                $row++;
            }
            fclose($handle);
        }

        $handle = fopen($path, 'w');
        foreach ($newCsvData as $line) {
            fputcsv($handle, $line);
        }
        fclose($handle);
    }

    public function editCsvColumn($model) {
        $app_path = realpath(Yii::app()->basePath . '/../');
        
        $ext = pathinfo($model->physical_file_name, PATHINFO_EXTENSION);
        $fileName = pathinfo($model->physical_file_name, PATHINFO_FILENAME);
        $path = $app_path . '/files/' . $model->physical_file_name;
        if ($ext === 'xls' || $ext === 'xlsx') {
            $path = $app_path . '/files/' . $fileName.'.csv';
        } 
                

        $row = 1;
        $newCsvData = array();
        if (($handle = fopen($path, "a+")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                if ($row == $model->row_id) {
                    $data[$model->column_id] = $model->column_value;
                }
                array_push($newCsvData, $data);
                $row++;
            }
            fclose($handle);
        }

        $handle = fopen($path, 'w');
        foreach ($newCsvData as $line) {
            fputcsv($handle, $line);
        }
        fclose($handle);
    }

    public function addCsvRow($model) {
        $app_path = realpath(Yii::app()->basePath . '/../');
        
        $ext = pathinfo($model->physical_file_name, PATHINFO_EXTENSION);
        $fileName = pathinfo($model->physical_file_name, PATHINFO_FILENAME);
        $path = $app_path . '/files/' . $model->physical_file_name;
        if ($ext === 'xls' || $ext === 'xlsx') {
            $path = $app_path . '/files/' . $fileName.'.csv';
        } 
                

        $newCsvData = array();
        if (($handle = fopen($path, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                array_push($newCsvData, $data);
            }
            array_push($newCsvData, array());
            fclose($handle);
        }

        $handle = fopen($path, 'w');
        foreach ($newCsvData as $line) {
            fputcsv($handle, $line);
        }
        fclose($handle);
    }

    /* excel file operation */

    public function excelToCsvFile($excelFileName, $csvFileName) {
        $app_path = realpath(Yii::app()->basePath . '/../');
        $path = $app_path . '/files/' . $excelFileName;
        $csv_path = $app_path . '/files/' . $csvFileName;
                
        require('spreadsheet-reader-master/php-excel-reader/excel_reader2.php');
	require('spreadsheet-reader-master/SpreadsheetReader.php');
        $reader = new SpreadsheetReader($path);

        $row = 1; $length=0; $maxLength = -1; $i=0; $needChange=0;        
        //read excel file and store to array
        $csvData = array();
        foreach ($reader as $data){
            $length = count($data);
            if ($row == 1) {
                $maxLength = $length;
            }
            if ($length > $maxLength) {
                $needChange = 1;
                $maxLength = $length;
            } elseif ($length < $maxLength) {
                $needChange = 1;
            }
            array_push($csvData, $data);
            $row++;
        }
        //echo '<pre>' . var_export($csvData, true) . '</pre>';
        //echo '<pre>' . var_export($maxLength, true) . '</pre>';
        //echo '<pre>' . var_export($needChange, true) . '</pre>';
        
        if ($needChange == 1) {
            //all line have same column
            $prfCsvData = array();
            foreach ($csvData as $line) {
                $length = count($line);
                if ($length < $maxLength) {
                    for ($i = $length; $i < $maxLength; $i++) {
                        array_push($line, "");
                    }
                }
                array_push($prfCsvData, $line);
            }

            //save to file
            $handle = fopen($csv_path, 'w');
            foreach ($prfCsvData as $line) {
                fputcsv($handle, $line);
            }
            fclose($handle);
        }else{
            //save to file
            $handle = fopen($csv_path, 'w');
            foreach ($csvData as $line) {
                fputcsv($handle, $line);
            }
            fclose($handle);
        }
    }

    /* location update operation */

    private function updateLatLon($file_path, $lat, $lon, $row_id, $column_id) {
        $app_path = realpath(Yii::app()->basePath . '/../');
        
        $ext = pathinfo($file_path, PATHINFO_EXTENSION);
        $fileName = pathinfo($file_path, PATHINFO_FILENAME);
        $path = $app_path . '/files/' . $file_path;
        if ($ext === 'xls' || $ext === 'xlsx') {
            $path = $app_path . '/files/' . $fileName.'.csv';
        } 
                

        $row = 1;
        $newCsvData = array();
        //var_dump($row_id);
        if (($handle = fopen($path, "a+")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                if ($row == $row_id) {
                    $address = $data[$column_id];
                    $locations = explode(">", $address);
                    //var_dump($locations);
                    if (count($locations) <= 1) {
                        $data[$column_id] = $data[$column_id] . ">" . $lat . ">" . $lon;
                    }
                }
                array_push($newCsvData, $data);
                $row++;
            }
            fclose($handle);
        }

        $handle = fopen($path, 'w');
        foreach ($newCsvData as $line) {
            fputcsv($handle, $line);
        }
        fclose($handle);
    }
    
    private function getColumns($fileName) {
        $app_path = realpath(Yii::app()->basePath . '/../');
        
        $ext = pathinfo($fileName, PATHINFO_EXTENSION);
        $orgName = pathinfo($fileName, PATHINFO_FILENAME);
        $path = $app_path . '/files/' . $fileName;
        if ($ext === 'xls' || $ext === 'xlsx') {
            $path = $app_path . '/files/' . $orgName.'.csv';
        } 
                
        $row = 1;
        $length = 0;
        $maxLength = -1;
        $i = 0;
        //read csv file and store to array
        if (($handle = fopen($path, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $length = count($data);
                if ($row == 1) {
                    $maxLength = $length;
                }
                if ($length > $maxLength) {
                    $maxLength = $length;
                }
                $row++;
            }
            fclose($handle);
        }
        
        // to avoid unexpected error
        if($maxLength < 10){
            $maxLength = 10;
        }
        
        $columns = array();
        for($i=0; $i<$maxLength; $i++){
            array_push($columns, "Column ".($i + 1));
        }
        
        //echo '<pre>' . var_export($columns, true) . '</pre>';
        return $columns;
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        $this->loadModel($id)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $model = new UserFile('search');

        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['UserFile']))
            $model->attributes = $_GET['UserFile'];

        $this->render('index', array(
            'model' => $model,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionManage() {
        $model = new UserFile('search');

        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['UserFile']))
            $model->attributes = $_GET['UserFile'];

        $this->render('manage', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return UserFile the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = UserFile::model()->findByPk($id);
        $fileModel = new FileUploadForm();
        $fileModel = $model;

        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $fileModel;
    }

    /**
     * Performs the AJAX validation.
     * @param UserFile $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'user-file-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionLocationUpdate() {
        $file_name = $_POST['file_name'];
        $lat = $_POST['lat'];
        $lon = $_POST['lon'];
        $row_id = $_POST['row_id'];
        $column_id = $_POST['column_id'];
        $this->updateLatLon($file_name, $lat, $lon, $row_id, $column_id);
        Yii::app()->end();
    }

    public function actionDataUpdate() {
//        $file_name = $_POST['file_name'];
//        $row_id = $_POST['row_id'];
//        $column_id = $_POST['column_id'];
//        $column_value = $_POST['column_value'];
//        $userFileId = $_POST['id'];
//
//        $model = new UserFile();
//        $model->physical_file_name = $file_name;
//        $model->row_id = $row_id + 2;
//        $model->column_id = $column_id;
//        $model->column_value = $column_value;
//        //var_dump($model);
//        $this->editCsvColumn($model);
//        
//        $userFileModel = $this->loadModel($userFileId);
//        $userFileModel->last_modified_date = date("Y-m-d h:m:s");
//        $userFileModel->save();
//        
//        Yii::app()->end();
        //var_dump($_POST);
        $rowData = $_POST['data'];
        $fileId = $_POST['id'];
        $rowId = $_POST['row_id'];
        
        $userFileModel = $this->loadModel($fileId);
        
        $model = new UserFile();
        $model->physical_file_name = $userFileModel->physical_file_name;
        $model->row_id = $rowId + 2;

        foreach($rowData as $row){
            $model->column_id = $row['column_id'];
            $model->column_value = $row['column_value'];
            $this->editCsvColumn($model);
            //var_dump($model->column_id . " - " . $model->column_value);
        }
        
        $userFileModel->last_modified_date = date("Y-m-d h:m:s");
        $userFileModel->save();
        Yii::app()->end();
    }

}
