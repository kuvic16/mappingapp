<?php
/* @var $this UserController */
/* @var $model User */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'username'); ?>
		<?php echo $form->textField($model,'username',array('size'=>45,'maxlength'=>45)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'email'); ?>
		<?php echo $form->textField($model,'email',array('size'=>45,'maxlength'=>45)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'first_name'); ?>
		<?php echo $form->textField($model,'first_name',array('size'=>45,'maxlength'=>45)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'last_name'); ?>
		<?php echo $form->textField($model,'last_name',array('size'=>45,'maxlength'=>45)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'subscription_level'); ?>
		<?php echo $form->textField($model,'subscription_level',array('size'=>45,'maxlength'=>45)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'renewal_date'); ?>
		<?php //echo $form->textField($model,'renewal_date',array('size'=>45,'maxlength'=>45)); ?>
                <?php $this->widget('zii.widgets.jui.CJuiDatePicker',
                    array(
                    'name' => 'renewal_date',
                    'attribute' => 'renewal_date',
                    'model'=>$model,
                    'options'=> array(
                    'dateFormat' =>'yy-mm-dd',
                    'altFormat' =>'yy-mm-dd',
                    'changeMonth' => true,
                    'changeYear' => true,
                    'appendText' => 'yyyy-mm-dd',
                    ),
                    ));
                    ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->