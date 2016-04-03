<?php $this->renderFile('protected/views/layouts/header.php'); ?>

<?php
$model->password = '';
?>

<h1>My account</h1>

<?php if(Yii::app()->user->hasFlash('createuser')): ?>

<div class="flash-success">
	<?php echo Yii::app()->user->getFlash('createuser'); ?>
</div>

<?php else: ?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm'); ?>

	<?php echo $form->errorSummary($model); ?>

  <table border="0">
    <tr>
      <td class="label"><?php echo $form->labelEx($model,'username'); ?></td>
      <td class="element"><?php echo $model->username; ?></td>
    </tr>
    <tr>
      <td class="label"><?php echo $form->labelEx($model,'password'); ?></td>
      <td class="element"><?php echo $form->passwordField($model,'password'); ?></td>
    </tr>
    <tr>
      <td class="label"><?php echo $form->labelEx($model,'email'); ?></td>
      <td class="element"><?php echo $form->textField($model,'email'); ?></td>
    </tr>
    <tr>
      <td class="label"><?php echo $form->labelEx($model,'firstname'); ?></td>
      <td class="element"><?php echo $form->textField($model,'firstname'); ?></td>
    </tr>
    <tr>
      <td class="label"><?php echo $form->labelEx($model,'lastname'); ?></td>
      <td class="element"><?php echo $form->textField($model,'lastname'); ?></td>
    </tr>
    <tr>
      <td class="label"> </td>
      <td class="element"><?php echo CHtml::submitButton('Update'); ?></td>
    </tr>
  </table>

<?php $this->endWidget(); ?>

</div><!-- form -->

<?php endif; ?>


<?php $this->renderFile('protected/views/layouts/footer.php'); ?>
