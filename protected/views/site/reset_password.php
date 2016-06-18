<script>
(function(i,s,o,g,r,a,m)
Unknown macro: {i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){ (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o), m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m) }
)(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
ga('create', 'UA-46600175-2', 'auto');
ga('send', 'pageview');
</script>
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
