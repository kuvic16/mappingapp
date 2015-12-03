<?php
/* @var $this UserController */
/* @var $model User */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'user-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'username'); ?>
		<?php echo $form->textField($model,'username',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'username'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'email'); ?>
		<?php echo $form->textField($model,'email',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'email'); ?>
	</div>

        <div class="row">
                <?php echo $form->labelEx($model,'password_first'); ?>
                <?php echo $form->passwordField($model,'password_first',array('size'=>45,'maxlength'=>500)); ?>
                <?php echo $form->error($model,'password_first'); ?>
        </div>

        <div class="row">
                <?php echo $form->labelEx($model,'password_repeat'); ?>
                <?php echo $form->passwordField($model,'password_repeat',array('size'=>45,'maxlength'=>500)); ?>
                <?php echo $form->error($model,'password_repeat'); ?>
        </div>

	<div class="row">
		<?php echo $form->labelEx($model,'first_name'); ?>
		<?php echo $form->textField($model,'first_name',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'first_name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'middle_name'); ?>
		<?php echo $form->textField($model,'middle_name',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'middle_name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'last_name'); ?>
		<?php echo $form->textField($model,'last_name',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'last_name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'address'); ?>
		<?php echo $form->textField($model,'address',array('size'=>45,'maxlength'=>500)); ?>
		<?php echo $form->error($model,'address'); ?>
	</div>

        <?php if(!Yii::app()->user->isGuest &&  Yii::app()->user->name=='admin'){ ?>
	<div class="row">
		<?php echo $form->labelEx($model,'subscription_level'); ?>
		<?php //echo $form->textField($model,'subscription_level',array('size'=>45,'maxlength'=>45)); ?>
                <?php echo $form->dropDownList($model, 'subscription_level', 
                        array('Free user' => 'Free user', 'Power User' => 'Power user', 'Admin' => 'Admin'));?>
		<?php echo $form->error($model,'subscription_level'); ?>
	</div>
        <?php } ?>

	<div class="row">
		<?php echo $form->labelEx($model,'renewal_date'); ?>
		<?php //echo $form->textField($model,'renewal_date',array('size'=>45,'maxlength'=>45));?>
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
		<?php echo $form->error($model,'renewal_date'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->