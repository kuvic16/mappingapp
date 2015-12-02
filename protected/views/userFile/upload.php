<?php
/* @var $this UserFileController */
/* @var $model UserFile */

$this->breadcrumbs=array(
	'My Files'=>array('manage'),
	'File upload',
);

$this->menu=array(
	array('label'=>'My Files', 'url'=>array('manage')),
);
?>

<h1>Upload a File</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>