<?php
/* @var $this UserFileController */
/* @var $model UserFile */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'user-file-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
        'htmlOptions' => array('enctype' => 'multipart/form-data')
)); ?>

<!--	<p class="note">Fields with <span class="required">*</span> are required.</p>-->

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
                <?php
                    echo $form->labelEx($model,'csv_file'); 
                    echo CHtml::activeFileField($model, 'csv_file');
                ?>
        </div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Upload'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->