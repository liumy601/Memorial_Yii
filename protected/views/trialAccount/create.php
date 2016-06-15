<?php
/* @var $this TrialAccountController */
/* @var $model TrialAccount */
/* @var $form CActiveForm */
?>
<style>
input { 
	padding: 10px;
	height: 40px; 
}
.errorMessage {
	text-align: center;
	color: #FF0000;
}
</style>

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'trial-account-form',
	'enableAjaxValidation'=>false,
)); ?>
	
	<?php if($created) : ?>
		<p class="note" style="text-align:center">Thank you for signing up! Please check your email for new login information.</p>
	<?php endif; ?>
	<p class="note" style="text-align:center">Fields with <span class="required">*</span> are required.</p>

	<p style="text-align:center">
		<?php echo $form->labelEx($model,'username'); ?><br/>
		<?php echo $form->textField($model,'username',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'username'); ?>
	</p>

	<p style="text-align:center">
		<?php echo $form->labelEx($model,'firstname'); ?><br/>
		<?php echo $form->textField($model,'firstname',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'firstname'); ?>
	</p>

	<p style="text-align:center">
		<?php echo $form->labelEx($model,'lastname'); ?><br/>
		<?php echo $form->textField($model,'lastname',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'lastname'); ?>
	</p>

	<p style="text-align:center">
		<?php echo $form->labelEx($model,'company_name'); ?><br/>
		<?php echo $form->textField($model,'company_name',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'company_name'); ?>
	</p>

	<p style="text-align:center">
		<?php echo $form->labelEx($model,'email'); ?><br/>
		<?php echo $form->textField($model,'email',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'email'); ?>
	</p>

	<p style="text-align:center">
		<?php echo $form->labelEx($model,'phone'); ?><br/>
		<?php echo $form->textField($model,'phone',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'phone'); ?>
	</p>

	<div class="row buttons" style="text-align:center">
		<?php echo CHtml::submitButton('Start My Trial'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->