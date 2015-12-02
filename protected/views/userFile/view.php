<?php
/* @var $this UserFileController */
/* @var $model UserFile */

$this->breadcrumbs=array(
	'My Files'=>array('manage'),
	$model->id,
);


//$this->menu=array(
//	array('label'=>'List UserFile', 'url'=>array('index')),
//	array('label'=>'Create UserFile', 'url'=>array('create')),
//	array('label'=>'Update UserFile', 'url'=>array('update', 'id'=>$model->id)),
//	array('label'=>'Delete UserFile', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
//	array('label'=>'Manage UserFile', 'url'=>array('admin')),
//);
?>

<h1><?php echo $model->file_name; ?></h1>

<table>
    <thead>
        <?php
            $path = Yii::app()->runtimePath.'/temp/'.$model->physical_file_name;
            if(($handle=  fopen($path, "r")) !== FALSE){
                while(($data = fgetcsv($handle, 1000, ",")) !== FALSE){
                    $num = count($data);
                    for ($c=0; $c < $num; $c++){
                        echo '<th style="border: 1px solid lightblue">'. $data[$c].'</th>';
                    }
                    break;
                }
                fclose($handle);
            }
        ?>
    </thead>
    <tbody>
        <?php
            $path = Yii::app()->runtimePath.'/temp/'.$model->physical_file_name;
            $row = 1;
            if(($handle=  fopen($path, "r")) !== FALSE){
                while(($data = fgetcsv($handle, 1000, ",")) !== FALSE){
                    if($row !== 1){
                        $num = count($data);
                        echo '<tr style="border: 1px solid gray">';
                        for ($c=0; $c < $num; $c++){
                            echo '<td style="border: 1px solid lightblue">'. $data[$c].'</td>';
                        }
                        echo '</tr>';
                    }
                    $row = 2;
                }
                fclose($handle);
            }
        ?>
    </tbody>
</table>
<?php 
//$this->widget('zii.widgets.CDetailView', array(
//	'data'=>$model,
//	'attributes'=>array(
//		'id',
//		'username',
//		'file_name',
//	),
//)); 
?>
