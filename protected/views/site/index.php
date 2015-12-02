<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>

<h1 style="margin-top: 50px; font-size: 40px; text-align: center">Welcome to <i><?php echo CHtml::encode(Yii::app()->name); ?></i></h1>

<div style="text-align: center; margin-top: 30px">
<h2>Do you want to map your data in google map? Just upload your csv and edit right here. Google map will show according your csv data</h2>
</div>

<?php
if(Yii::app()->user->isGuest)
{
?>
<div style="text-align: center; margin-top: 50px">
<a style="border: 1px solid black; background-color: whitesmoke; text-align: center; padding: 10px 40px 10px 40px; font-size: 25px; text-decoration: none" href="index.php?r=site/login">Login</a>
<p style="text-align: center; margin-top: 30px">-OR-</p>
<a style="margin-top: 30px; background-color: wheat; border: 1px solid black; text-align: center; padding: 10px 40px 10px 40px; font-size: 25px; text-decoration: none" href="index.php?r=user/registration">Registration</a>

</div>

<?php }else {?>
<div style="text-align: center; margin-top: 50px">
<a style="border: 1px solid black; background-color: whitesmoke; text-align: center; padding: 10px 40px 10px 40px; font-size: 25px; text-decoration: none" href="index.php?r=userFile/upload">File Upload</a>
<p style="text-align: center; margin-top: 30px"></p>
<a style="margin-top: 30px; background-color: wheat; border: 1px solid black; text-align: center; padding: 10px 40px 10px 40px; font-size: 25px; text-decoration: none" href="index.php?r=userFile/manage">Manage Files</a>

</div>

<?php } ?>