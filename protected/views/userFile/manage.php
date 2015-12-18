<?php
/* @var $this UserFileController */
/* @var $model UserFile */

$this->breadcrumbs=array(
	'My Files',
);

$this->menu=array(
	array('label'=>'Upload File', 'url'=>array('upload')),
);


?>

<h1>My Files</h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'user-file-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'file_name',
                'creation_date',
                'last_modified_date',
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
