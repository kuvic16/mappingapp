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
<h3 style="color: green; text-align: center">Registration complete successfully</h3>
<div style="text-align: center; margin-top: 50px">
<a style="border: 1px solid black; background-color: whitesmoke; text-align: center; padding: 10px 40px 10px 40px; font-size: 25px; text-decoration: none" href="index.php?r=site/login">Login</a>
</div>
<?php 
}

?>