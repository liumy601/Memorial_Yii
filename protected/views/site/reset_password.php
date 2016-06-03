<?php $this->renderFile('protected/views/layouts/header.php'); ?>

<?php
$this->pageTitle=Yii::app()->name . ' - Reset Password';
?>

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'reset-password-form',
	'enableAjaxValidation'=>true,
  'htmlOptions' => array('class'=>'noajax')
));
?>

	<?php if(!empty($message)): ?>
  <div class="errorMSG"><?php echo $message;?></div>
	<?php endif; ?>

  <h1>Password Reset</h1>
  <p>Fill your email address *</p>

  <table>
	<tr>
		<td class="element"><?php echo CHtml::textField('email_addr'); ?></td>
	</tr>

	<tr>
		<td class="element"><?php echo CHtml::submitButton('Submit', array('class'=>'button')); ?></td>
	</tr>
  </table>

<?php $this->endWidget(); ?>
</div><!-- form -->


<?php $this->renderFile('protected/views/layouts/footer.php'); ?>