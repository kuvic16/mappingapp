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
                'actions' => array('index', 'view', 'map', 'locationUpdate'),
                'users' => array('@'),
            ),
            array('allow', // allow authenticated user to perform 'upload' and 'update' actions
                'actions' => array('upload', 'update'),
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
        $model->csv_data = $this->loadCsvFile($model->physical_file_name);
        $this->render('map', array(
            'model' => $model,
        ));
    }

    public function actionMap($id) {
        $model = $this->loadModel($id);
        $model->csv_data = $this->loadCsvFile($model->physical_file_name);
        $this->render('map', array(
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
                $path = Yii::app()->runtimePath . '/temp/' . $model->physical_file_name;
                $model->csv_file->saveAs($path);
            }

            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
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
            } elseif($model->is_new === "0") {
                $this->editCsvColumn($model);
            } elseif($model->is_new === "2"){
                $this->addCsvRow($model);
            }
            $this->redirect(array('update','id'=>$model->id));
        }

        $this->render('update', array(
            'model' => $model,
        ));
    }

    private function loadCsvFile($fileName) {
        $path = Yii::app()->runtimePath . '/temp/' . $fileName;
        $row = 1; $length=0; $maxLength = -1; $i=0; $needChange=0;
        
        //read csv file and store to array
        $csvData = array();
        if (($handle = fopen($path, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $length = count($data);
                if($row == 1){
                    $maxLength = $length;
                }
                if($length > $maxLength){
                    $needChange=1;
                    $maxLength = $length;
                }elseif ($length < $maxLength) {
                    $needChange=1;
                }
                array_push($csvData, $data);
                $row++;
            }
            fclose($handle);
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
        $path = Yii::app()->runtimePath . '/temp/' . $model->physical_file_name;
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
        $path = Yii::app()->runtimePath . '/temp/' . $model->physical_file_name;

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
        $path = Yii::app()->runtimePath . '/temp/' . $model->physical_file_name;

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
    
    
    private function updateLatLon($file_path, $lat, $lon, $row_id) {
        $path = Yii::app()->runtimePath . '/temp/' . $file_path;

        $row = 1;
        $newCsvData = array();
        var_dump($row_id);
        if (($handle = fopen($path, "a+")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                if ($row == $row_id) {
                    $address = $data[1];
                    $locations = explode(">", $address);
                    var_dump($locations);
                    if(count($locations) <=1){
                        $data[1] = $data[1].">".$lat.">".$lon;
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
        $this->updateLatLon($file_name, $lat, $lon, $row_id);
        echo $row_id;
        Yii::app()->end();
    }

}
