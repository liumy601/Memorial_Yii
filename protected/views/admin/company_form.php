<?php $this->renderFile('protected/views/layouts/header_subpanel.php', array('title'=>'Control Panel')); ?>


<?php
$this->pageTitle=Yii::app()->name . ' - Create company';

if ($model->id > 0) {
  $title = 'Update company';
} else {
  $title = 'Create company';
}
?>

<h1><?php echo $title; ?></h1>

<?php if(Yii::app()->user->hasFlash('createcompany')): ?>

<div class="flash-success">
	<?php echo Yii::app()->user->getFlash('createcompany'); ?>
</div>

<?php else: ?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
    'htmlOptions' => array(
      'class' => 'noajax',
      'enctype'=>'multipart/form-data',//can't upload by ajax
      'target' => 'hiddenifm'
    )
)); ?>

	<?php echo $form->errorSummary($model); ?>

  <table border="0">
    <tr>
      <td class="label"><?php echo $form->labelEx($model,'logo'); ?></td>
      <td class="element"><?php echo $form->fileField($model,'logo'); ?>
      <?php
      if($model->logo){
        echo '<br/>' . Chtml::link('<img src="/'. $model->logo .'" width="128px;" height="60px"/>', '/' . $model->logo);
      }
    ?>
      </td>
    </tr>
    
    <tr>
      <td class="label"><?php echo $form->labelEx($model,'name'); ?></td>
      <td class="element"><?php echo $form->textField($model,'name'); ?></td>
    </tr>
    
    <tr>
      <td class="label"><?php echo $form->labelEx($model,'address'); ?></td>
      <td class="element"><?php echo $form->textField($model,'address'); ?></td>
    </tr>
    
    <tr>
      <td class="label"><?php echo $form->labelEx($model,'city'); ?></td>
      <td class="element"><?php echo $form->textField($model,'city'); ?></td>
    </tr>
    
    <tr>
      <td class="label"><?php echo $form->labelEx($model,'state'); ?></td>
      <td class="element"><?php echo $form->textField($model,'state'); ?></td>
    </tr>
    
    <tr>
      <td class="label"><?php echo $form->labelEx($model,'zip'); ?></td>
      <td class="element"><?php echo $form->textField($model,'zip'); ?></td>
    </tr>

	<tr>
      <td class="label"><?php echo $form->labelEx($model,'phone'); ?></td>
      <td class="element"><?php echo $form->textField($model,'phone'); ?></td>
    </tr>
    
    <tr>
      <td class="label"> </td>
      <td class="element"><?php echo CHtml::submitButton('Submit'); ?></td>
    </tr>
  </table>	
  
<?php $this->endWidget(); ?>

</div><!-- form -->

<?php endif; ?>

<iframe src="" name="hiddenifm" id="hiddenifm" style="display:none;"></iframe>



<?php $this->renderFile('protected/views/layouts/footer_subpanel.php'); ?>