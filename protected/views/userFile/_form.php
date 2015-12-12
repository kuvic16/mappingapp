<?php
/* @var $this UserFileController */
/* @var $model UserFile */
/* @var $form CActiveForm */
?>

<div class="form" style="margin-top: 10px">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'user-file-form',
	'enableAjaxValidation'=>false,
        'htmlOptions' => array('enctype' => 'multipart/form-data')
)); ?>

        <?php if($model->id !== NULL && (strlen($model->name_index) === 0 || strlen($model->address_index) === 0 || strlen($model->city_index) === 0 || strlen($model->state_index) === 0 || strlen($model->zipcode_index) === 0)){ ?>
        <p class="note">
            Please fillup following fields to show google map 
            
            <?php if(strlen($model->name_index) === 0){ echo $form->labelEx($model,'name_index');} ?>
            <?php if(strlen($model->address_index) === 0) echo $form->labelEx($model,'address_index'); ?>
            <?php if(strlen($model->city_index) === 0) echo $form->labelEx($model,'city_index'); ?>
            <?php if(strlen($model->state_index) === 0) echo $form->labelEx($model,'state_index'); ?>
            <?php if(strlen($model->zipcode_index) === 0) echo $form->labelEx($model,'zipcode_index'); ?>
<!--            Google map will not show properly until setup is complete. Your file has <?php echo count($model->columns); ?> columns. Please setup the map window fields by choosing uploaded file column.
         Map window information will show according this. Atleast 5 column need to setup to show in google map. Required fields are marked as <span class="required">*</span></p>-->
        <?php } ?>

	<?php echo $form->errorSummary($model); ?>
        
        <?php if($model->id === NULL){ ?>
        <div class="row">
                <?php
                    echo $form->labelEx($model,'csv_file'); 
                    echo CHtml::activeFileField($model, 'csv_file');
                ?>
        </div>
	<div class="row buttons">
		<?php echo CHtml::submitButton('Upload'); ?>
	</div>
        <?php }  else{ ?>
        <div class="">
            <div class="setuprow">
                    <?php echo $form->labelEx($model,'name_index'); ?><span class="setuprequired">*</span>
                    <?php echo $form->dropDownList($model, 'name_index', $model->columns, array('prompt'=>'Select column'));?>
                    <?php echo $form->error($model,'name_index'); ?>
            </div>

            <div class="setuprow">
                    <?php echo $form->labelEx($model,'address_index'); ?><span class="setuprequired">*</span>
                    <?php echo $form->dropDownList($model, 'address_index', $model->columns, array('prompt'=>'Select column'));?>
                    <?php echo $form->error($model,'address_index'); ?>
            </div>

            <div class="setuprow">
                    <?php echo $form->labelEx($model,'city_index'); ?><span class="setuprequired">*</span>
                    <?php echo $form->dropDownList($model, 'city_index', $model->columns, array('prompt'=>'Select column'));?>
                    <?php echo $form->error($model,'city_index'); ?>
            </div>

            <div class="setuprow">
                    <?php echo $form->labelEx($model,'state_index'); ?><span class="setuprequired">*</span>
                    <?php echo $form->dropDownList($model, 'state_index', $model->columns, array('prompt'=>'Select column'));?>
                    <?php echo $form->error($model,'state_index'); ?>
            </div>

            <div class="setuprow">
                    <?php echo $form->labelEx($model,'zipcode_index'); ?><span class="setuprequired">*</span>
                    <?php echo $form->dropDownList($model, 'zipcode_index', $model->columns, array('prompt'=>'Select column'));?>
                    <?php echo $form->error($model,'zipcode_index'); ?>
            </div>

            <div class="setuprow">
                    <?php echo $form->labelEx($model,'phone_index'); ?>
                    <?php echo $form->dropDownList($model, 'phone_index', $model->columns, array('prompt'=>'Select column'));?>
                    <?php echo $form->error($model,'phone_index'); ?>
            </div>
            
            <div class="setuprow">
                    <?php echo $form->labelEx($model,'field1_label'); ?>
                    <?php echo $form->textField($model,'field1_label',array('size'=>20,'maxlength'=>45)); ?>
                    <?php echo $form->labelEx($model,'field1_index'); ?>
                    <?php echo $form->dropDownList($model, 'field1_index', $model->columns, array('prompt'=>'Select column'));?>
                    <?php echo $form->error($model,'field1_index'); ?>
            </div>
            
            <div class="setuprow">
                    <?php echo $form->labelEx($model,'field2_label'); ?>
                    <?php echo $form->textField($model,'field2_label',array('size'=>20,'maxlength'=>45)); ?>
                    <?php echo $form->labelEx($model,'field2_index'); ?>
                    <?php echo $form->dropDownList($model, 'field2_index', $model->columns, array('prompt'=>'Select column'));?>
                    <?php echo $form->error($model,'field2_index'); ?>
            </div>
            
            <div class="setuprow">
                    <?php echo $form->labelEx($model,'field3_label'); ?>
                    <?php echo $form->textField($model,'field3_label',array('size'=>20,'maxlength'=>45)); ?>
                    <?php echo $form->labelEx($model,'field3_index'); ?>
                    <?php echo $form->dropDownList($model, 'field3_index', $model->columns, array('prompt'=>'Select column'));?>
                    <?php echo $form->error($model,'field3_index'); ?>
            </div>
            
            <div class="setuprow">
                    <?php echo $form->labelEx($model,'field4_label'); ?>
                    <?php echo $form->textField($model,'field4_label',array('size'=>20,'maxlength'=>45)); ?>
                    <?php echo $form->labelEx($model,'field4_index'); ?>
                    <?php echo $form->dropDownList($model, 'field4_index', $model->columns, array('prompt'=>'Select column'));?>
                    <?php echo $form->error($model,'field4_index'); ?>
            </div>
            
            <div class="setuprow">
                    <?php echo $form->labelEx($model,'field5_label'); ?>
                    <?php echo $form->textField($model,'field5_label',array('size'=>20,'maxlength'=>45)); ?>
                    <?php echo $form->labelEx($model,'field5_index'); ?>
                    <?php echo $form->dropDownList($model, 'field5_index', $model->columns, array('prompt'=>'Select column'));?>
                    <?php echo $form->error($model,'field5_index'); ?>
            </div>            
        </div>
        <div style="clear: both">
            <?php echo CHtml::submitButton('Save', array('class'=>'new_btn basic')); ?>
        </div>
        <?php } ?>
        
<?php $this->endWidget(); ?>

</div><!-- form -->