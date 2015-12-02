<link type='text/css' href='<?php echo Yii::app()->request->baseUrl; ?>/css/demo.css' rel='stylesheet' media='screen' />
<link type='text/css' href='<?php echo Yii::app()->request->baseUrl; ?>/css/basic.css' rel='stylesheet' media='screen' />
<?php
/* @var $this UserFileController */
/* @var $model UserFile */

$this->breadcrumbs = array(
    'My Files' => array('manage'),
    $model->id,
);
//$this->menu=array(
//	array('label'=>'My Files', 'url'=>array('manage')),
//	array('label'=>'File Upload', 'url'=>array('upload')),
//	array('label'=>'Google Map', 'url'=>array('view', 'id'=>$model->id)),	
//);
?>

<h1><?php echo $model->file_name; ?></h1>
<div id='basic-modal'>
    <button class="new_btn basic"  onclick="newColumn()">Add New Column</button>
    <button class="new_btn basic"  onclick="addRow()">Add New Row</button>
    <a class="new_btn" style="border: 2px outset gray; padding: 4px;" href="index.php?r=userFile/map&id=<?php echo $model->id ?>">Show Map</a>
    <table class="file-data-table" style="margin-top: 10px;">
        <thead>
            <?php
            $row = 1;
            $length = count($model->csv_data); 
            if($length > 0){
                $data = $model->csv_data[0];
                for ($c = 0; $c < count($data); $c++) {
                    echo '<th class="basic" onclick="editColumn('.$row.','.$c.',\''.$data[$c] .'\')">' . $data[$c] . '</th>';
                }
            }
            ?>
        </thead>
        <tbody>
            <?php
            $row = 1;
            if($length > 1){
                foreach ($model->csv_data as $data) {
                    if ($row !== 1) {
                        echo '<tr' . ' class="' . $row . '">';
                        for ($c = 0; $c < count($data); $c++) {
                            echo '<td class="basic" onclick="editColumn('.$row.','.$c.',\''.$data[$c] .'\')">' . $data[$c] . '</td>';
                        }
                        echo '</tr>';
                    }
                    $row++;
                }
            }
            ?>
        </tbody>
    </table>
</div>
<?php
?>

<div id="basic-modal-content" class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'table-form',
	'enableAjaxValidation'=>false,
)); ?>
        <div class="row">
            <h2 id="headline">New Column</h2>
	</div>
	<div class="row column_name">
		<?php echo $form->labelEx($model,'column_name'); ?>
		<?php echo $form->textField($model,'column_name',array('size'=>45,'maxlength'=>45, 'style'=> 'width: 100%')); ?>
		<?php echo $form->error($model,'column_name'); ?>
	</div>

	<div class="row column_value">
		<?php echo $form->labelEx($model,'column_value'); ?>
		<?php echo $form->textField($model,'column_value',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'column_value'); ?>
	</div>
         <div class="row row_id">
		<?php echo $form->labelEx($model,'row_id'); ?>
		<?php echo $form->textField($model,'row_id',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'row_id'); ?>
	</div>
        <div class="row column_id">
		<?php echo $form->labelEx($model,'column_id'); ?>
		<?php echo $form->textField($model,'column_id',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'column_id'); ?>
	</div>
        <?php echo $form->hiddenField($model, 'is_new'); ?>
        
        <div class="row buttons">
		<?php echo CHtml::submitButton('Save', array('class'=>'submit_btn')); ?>
	</div>

<?php $this->endWidget(); ?>

</div>

<!-- preload the images -->
<div style='display:none'>
    <img src='img/basic/x.png' alt='' />
</div>


<script type='text/javascript' src='<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.js'></script>
<script type='text/javascript' src='<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.simplemodal.js'></script>
<script type='text/javascript' src='<?php echo Yii::app()->request->baseUrl; ?>/js/basic.js'></script>

<script type="text/javascript">
    
    function newColumn(){
        $("#headline").text("New Column");
        $(".column_name").show();
        $(".column_value, .row_id, .column_id").hide();
        $("#UserFile_is_new").val("1");
    }
    
    function editColumn(row_id, column_id, column_value){
        $("#headline").text("Edit Column");
        $(".column_value").show();
        $(".column_name, .row_id, .column_id").hide();
        
        $("#UserFile_column_value").val(column_value);
        $("#UserFile_row_id").val(row_id);
        $("#UserFile_column_id").val(column_id);
        $("#UserFile_is_new").val("0");
        $(".submit_btn").val("Save");
    }
    
    function addRow(){
        $("#headline").text("New Row: Are you sure?");
        $(".column_value, .column_name, .row_id, .column_id").hide();
        $("#UserFile_is_new").val("2");
        $(".submit_btn").val("Yes");
    }

</script>