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

<h1>Map setup: <?php echo $model->file_name; ?></h1>

<a class="new_btn" style="margin-bottom: 10px; border: 2px outset gray; padding: 4px; text-decoration: none" href="index.php?r=userFile/map&id=<?php echo $model->id ?>">Show Map</a>
<a class="new_btn" style="border: 2px outset gray; padding: 4px; text-decoration: none" href="index.php?r=userFile/update&id=<?php echo $model->id ?>">Change this file</a>
<?php $this->renderPartial('_form', array('model' => $model)); ?>