<?php $this->renderFile('protected/views/layouts/header.php'); ?>

<?php
$this->pageTitle=Yii::app()->name . ' - Reset Password';
?>

<?php if(!empty($illegal) || !empty($success)): ?>
	<?php if(!empty($illegal) echo $illegal; ?>
	<?php if(!empty($success) echo $success; ?>
<?php else: ?>
	<div class="form">
	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'reset-password-form',
		'enableAjaxValidation'=>true,
	  'htmlOptions' => array('class'=>'noajax')
	));
	?>

		<?php if($model->hasErrors()): ?>
		  <div class="errorMSG">
			<?php echo $form->error($model,'password'); ?>
		  </div>
		<?php endif; ?>

	  <h1>Password Reset</h1>

	  <table>
		<tr>
			<td class="label"></td>
			<td class="element">New password*<br/><?php echo $form->passwordField($model,'password'); ?></td>
		</tr>

		<tr>
			<td class="label"></td>
			<td class="element">New password again*<br/><?php echo $form->passwordField($model,'password_repeat'); ?></td>
		</tr>

		<tr>
		<td class="label"> </td>
			<td class="element"><?php echo CHtml::submitButton('Submit', array('class'=>'button')); ?></td>
		</tr>
	  </table>

	<?php $this->endWidget(); ?>
	</div><!-- form -->
<?php endif; ?>

<?php $this->renderFile('protected/views/layouts/footer.php'); ?>
