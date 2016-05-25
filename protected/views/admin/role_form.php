<?php $this->renderFile('protected/views/layouts/header_subpanel.php', array('title'=>'Control Panel')); ?>


<?php
$this->pageTitle=Yii::app()->name . ' - Create department';

if ($model->id > 0) {
  $title = 'Update department';
} else {
  $title = 'Create department';
}
?>

<h1><?php echo $title; ?></h1>

<?php if(Yii::app()->user->hasFlash('createdepartment')): ?>

<div class="flash-success">
	<?php echo Yii::app()->user->getFlash('createdepartment'); ?>
</div>

<?php else: ?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
    'htmlOptions' => array(
    )
)); ?>

	<?php echo $form->errorSummary($model); ?>

  <table border="0">
    <tr>
      <td class="label mandatory"><?php echo $form->labelEx($model,'name'); ?></td>
      <td class="element"><?php echo $form->textField($model,'name'); ?></td>
    </tr>
    <tr>
      <td class="label"><?php echo $form->labelEx($model,'description'); ?></td>
      <td class="element"><?php echo $form->textField($model,'description'); ?></td>
    </tr>
    <tr>
      <td class="label"> </td>
      <td class="element"><?php echo CHtml::submitButton('Submit'); ?></td>
    </tr>
  </table>	
  
<?php $this->endWidget(); ?>

</div><!-- form -->

<?php endif; ?>


<?php $this->renderFile('protected/views/layouts/footer_subpanel.php'); ?>