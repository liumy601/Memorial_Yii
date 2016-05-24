<?php $this->renderFile('protected/views/layouts/header_subpanel.php', array('title'=>'Control Panel')); ?>


<?php
$this->pageTitle = 'Configuration';
?>

<h1><?php echo $this->pageTitle; ?></h1>

<?php if(Yii::app()->user->hasFlash('config')): ?>

<div class="flash-success">
	<?php echo Yii::app()->user->getFlash('config'); ?>
</div>

<?php else: ?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
    'htmlOptions' => array(
//      'class' => 'noajax',
//      'enctype'=>'multipart/form-data',//can't upload by ajax
//      'target' => 'hiddenifm'
    )
)); ?>

	<?php echo $form->errorSummary($model); ?>

  <table border="0">
    <tr>
      <td class="label">Tax:</td>
      <td class="element"><?php echo $form->textField($model,'value'); ?></td>
    </tr>
    <tr>
      <td class="label"> </td>
      <td class="element"><?php echo CHtml::submitButton('Save'); ?></td>
    </tr>
  </table>	
  
<?php $this->endWidget(); ?>

</div><!-- form -->

<?php endif; ?>

<?php $this->renderFile('protected/views/layouts/footer_subpanel.php'); ?>