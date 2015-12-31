<?php
/* @var $this UserFileController */
/* @var $model UserFile */

$this->breadcrumbs = array(
    'File upload' => array('upload'),
    'Map Setup',
);

$this->menu = array(
    array('label' => 'My Files', 'url' => array('manage')),
    array('label' => 'File upload', 'url' => array('upload')),
);
?>

<h1 style="margin-bottom: 15px">Filtering: <?php echo $model->file_name; ?></h1>
<a class="new_btn" style="border: 2px outset gray; padding: 4px; text-decoration: none" href="index.php?r=userFile/setup&id=<?php echo $model->id ?>">Map setup</a>    
<a class="new_btn" style="border: 2px outset gray; padding: 4px; text-decoration: none" href="index.php?r=userFile/update&id=<?php echo $model->id ?>">Change this file</a>
<a class="new_btn" style="border: 2px outset gray; padding: 4px; text-decoration: none" href="index.php?r=userFile/upload">Upload another file</a>
<a class="new_btn" style="border: 2px outset gray; padding: 4px; text-decoration: none" href="index.php?r=userFile/map&id=<?php echo $model->id ?>">Show Map</a>


<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'table-form',
	'enableAjaxValidation'=>false,
)); ?>
<div class="form" style="margin-top: 20px">
    <div class="row">
        <?php echo $form->labelEx($model,'filter_column'); ?>
        <?php echo $form->dropDownList($model, 'filter_column', $model->columns, array('prompt'=>'Select column'));?>
        
        <?php echo $form->labelEx($model,'default_color'); ?>
        <?php echo $form->textField($model,'default_color',array('size'=>20,'maxlength'=>45)); ?>`
        
        <?php echo $form->labelEx($model,'filter'); ?>
        <?php echo $form->textArea($model,'filter',array('rows'=>3, 'cols'=>36)); ?>
        
        <div style="clear: both">
            <?php echo CHtml::submitButton('Save', array('class'=>'new_btn basic')); ?>
        </div>
    </div>
</div>
<?php $this->endWidget(); ?>
