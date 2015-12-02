<?php
/* @var $this UserController */
/* @var $model User */
?>

<h1>User Registration</h1>

<?php 
if($model->id == NULL)
{
    $this->renderPartial('_form', array('model'=>$model)); 
}  else {
?>
<br/><br/><br/>
<h3 style="color: green">Registration complete successfully</h3>
<?php 
}

?>