<?php $this->renderFile('protected/views/layouts/header_subpanel.php', array('title'=>'Control Panel')); ?>


<?php

if ($model->id > 0) {
  $title = 'Update file';
} else {
  $title = 'Create file';
}

$this->pageTitle=Yii::app()->name . ' - '.$title;
?>

<h1><?php echo $title; ?></h1>

<?php if(Yii::app()->user->hasFlash('createuser')): ?>

<div class="flash-success">
	<?php echo Yii::app()->user->getFlash('createuser'); ?>
</div>

<?php else: ?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array('htmlOptions' => array(
    'name'=>'EditView', 
    'id'=>'detailview_student_'.$model->id, 
    'onsubmit'=>"return(check_form('EditView'));",
    'pagetitle'=>'Students & Contacts',
    'class' => 'noajax',
    'enctype'=>'multipart/form-data',//can't upload by ajax
    'target' => 'hiddenifm'
))); ?>

	<?php echo $form->errorSummary($model); ?>

  <table border="0">
    <tr>
      <td class="label"><?php echo $form->labelEx($model,'name'); ?></td>
      <td class="element"><?php echo $form->textField($model,'name'); ?></td>
    </tr>
    <tr>
      <td class="label"><?php echo $form->labelEx($model,'file'); ?></td>
      <td class="element"><?php echo $form->fileField($model,'file'); 
      if(!empty($model->file)){
        echo '&nbsp;&nbsp;&nbsp;' . CHtml::link('View', '/'.$model->file, array('target'=>'_blank', 'class'=>'sl noajax'));
      }
      ?></td>
    </tr>
    <tr>
      <td class="label">Visible Users</td>
      <td class="element"><?php echo $form->dropDownList($model, 'visible_users', DropDown::defaultAssignedTo(), array('size'=>5, 'multiple'=>true)); ?></td>
    </tr>
    <tr>
      <td class="label"> </td>
      <td class="element"><?php echo CHtml::submitButton('Submit'); ?></td>
    </tr>

<?php $this->endWidget(); ?>

</div><!-- form -->

<iframe src="" name="hiddenifm" id="hiddenifm" style="display:none;"></iframe>

<?php endif; ?>


<?php $this->renderFile('protected/views/layouts/footer_subpanel.php'); ?>
