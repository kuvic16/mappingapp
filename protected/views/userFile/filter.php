<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-1.10.2.js"></script>
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
        <?php echo $form->dropDownList($model, 'filter_column', $model->columns, array('prompt'=>'Select column', 'onchange'=>'columnChangeRequest()'));?>
        
        <?php echo $form->labelEx($model,'default_color'); ?>
        <?php echo $form->colorField($model,'default_color',array('type'=>'color','size'=>20,'maxlength'=>45)); ?>`
        
        <?php //echo $form->labelEx($model,'filter'); ?>
        <?php //echo $form->textArea($model,'filter',array('rows'=>3, 'cols'=>36)); ?>
        
        <label>Filters</label>
        <button onclick="addNewFilter(); return false">Add</button>
        
        <div id="filters"></div>
        <input id="count" name="UserFile[count]" type="hidden" />
        <input id="selected_value" name="UserFile[selected_value]" type="hidden" />
        
        <div style="clear: both">
            <?php echo CHtml::submitButton('Save', array('class'=>'new_btn basic')); ?>
        </div>
    </div>
</div>
<script>
    var filterText = <?php echo "'".$model->filter."'" ?>;
    loadFilterElement(filterText);
    
    function loadFilterElement(filterText){
        if(filterText){
            var filters = filterText.split(",");
            for (f = 0; f < filters.length; f++) {
                var items = filters[f].split(":");
                var html = '<input name="UserFile[f'+ f +']" type="text" value="'+items[0]+'" /><input name="UserFile[c'+ f +']" type="color" value="'+items[1]+'" /><br/>';
                $("#filters").append(html);
            }
            $("#count").val(filters.length);
        }
        var selectedColumn = $("#UserFile_filter_column :selected").text();
        $("#selected_value").val(selectedColumn);
    }
    
    function addNewFilter(){
        var c = $("#count").val();
        var html = '<input name="UserFile[f'+ c +']" type="text" value="" /><input name="UserFile[c'+ c +']" type="color" /><br/>';
        $("#filters").append(html);
        $("#count").val(parseInt(c) + 1);
    }
    
    function columnChangeRequest() {
        //console.log($("#UserFile_filter_column :selected").text());
        var selectedColumn = $("#UserFile_filter_column :selected").text();
        $("#selected_value").val(selectedColumn);
        //var selectedColumn = $("#UserFile_filter_column").val();
        if(selectedColumn){
            $.ajax({
                type: "POST",
                url: "<?php echo Yii::app()->createUrl('userFile/columnFiltering'); ?>",
                data: {
                    id: "<?php echo $model->id; ?>",
                    file_name: "<?php echo $model->physical_file_name; ?>",
                    column_value: selectedColumn
                },
                success: function (msg) {
                    var jsonArray= $.parseJSON(msg);
                    
        
                    $("#filters").html("");
                    var i = 0;
                    $.each(jsonArray, function(index,jsonObject){
                        $.each(jsonObject, function(key,val){
                            var html = '<input name="UserFile[f'+ i+']" type="text" value="'+key+'" /><input name="UserFile[c'+ i+']" type="color" value="'+val+'" /><br/>';
                            $("#filters").append(html);
                            //console.log("key : "+key+" ; value : "+val);
                            i= i + 1;
                        });
                    });
                    $("#count").val(i)
                },
                error: function (xhr) {
                    console.log("failure" + xhr.readyState + this.url);
                }
            });
        }
    }
</script>
<?php $this->endWidget(); ?>
