<?php $this->renderFile('protected/views/layouts/header.php'); ?>


<?php
$this->pageTitle=Yii::app()->name . ' - Login';
$this->breadcrumbs=array(
	'Login',
);
?>


<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'login-form',
	'enableAjaxValidation'=>true,
  'htmlOptions' => array('class'=>'noajax')
));
$usernameError = $form->error($model,'username');
$passwordError = $form->error($model,'password');
?>
  
  <?php if (strpos($usernameError, 'none') === false || strpos($passwordError, 'none') === false) { ?>
  <div class="errorMSG">
  <?php echo $form->error($model,'username'); ?>
  <?php echo $form->error($model,'password'); ?>
  </div>
  <?php } ?>
  
  <h1>Login</h1>
  <p>Please fill out the following form with your login credentials:</p>


  <table>
	<tr>
    <td class="label mandatory"><?php echo $form->labelEx($model,'username'); ?></td>
		<td class="element"><?php echo $form->textField($model,'username'); ?></td>
	</tr>

	<tr>
    <td class="label mandatory"><?php echo $form->labelEx($model,'password'); ?></td>
		<td class="element"><?php echo $form->passwordField($model,'password'); ?></td>
	</tr>

	<tr>
		<td class="label"> </td>
    <td class="element"><?php echo CHtml::link('Reset Password', '/site/resetPassword', array('class'=>'noajax'));?><br/><?php echo $form->checkBox($model,'rememberMe') . $form->label($model,'rememberMe') . $form->error($model,'rememberMe'); ?></td>
	</tr>

	<tr>
    <td class="label"> </td>
		<td class="element"><?php echo CHtml::submitButton('Login', array('class'=>'button')); ?></td>
	</tr>
  </table>

<?php $this->endWidget(); ?>
</div><!-- form -->


<?php $this->renderFile('protected/views/layouts/footer.php'); ?>
