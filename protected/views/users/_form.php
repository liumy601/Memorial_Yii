<div id="rwb" class="rwb">

<?php
$this->pageTitle=Yii::app()->name . ' - Create user';
?>

<h1>Create user</h1>

<?php if(Yii::app()->user->hasFlash('createuser')): ?>

<div class="flash-success">
	<?php echo Yii::app()->user->getFlash('createuser'); ?>
</div>

<?php else: ?>


<?php $form=$this->beginWidget('CActiveForm'); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'username'); ?>
		<?php echo $form->textField($model,'username'); ?>
	</div>

  <div class="row">
		<?php echo $form->labelEx($model,'password'); ?>
		<?php echo $form->passwordField($model,'password'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'email'); ?>
		<?php echo $form->textField($model,'email'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'firstname'); ?>
		<?php echo $form->textField($model,'firstname'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'lastname'); ?>
		<?php echo $form->textField($model,'lastname'); ?>
	</div>
	
	<?php if(!empty(Yii::app()->user->type) && Yii::app()->user->type!= 'standard') : ?>
	
	<div class="row">
		<?php echo $form->labelEx($model,'type'); ?>
		<?php echo $form->dropDownList($model,'type', $model->getTypes()); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'role'); ?>
		<?php echo $form->dropDownList($model,'role', $model->getRoles()); ?>
	</div>
	
	<?php endif; ?>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Submit', array('class'=>'button')); ?>
	</div>

<?php $this->endWidget(); ?>


<?php endif; ?>