<div id="rwb" class="rwb">
  
<?php
if ($model->id > 0) {
  $title = 'Update task';
} else {
  $title = 'Create task';
}
?>
       

<?php
$this->pageTitle = Yii::app()->name . ' -' . $title;
?>

<?php if(Yii::app()->user->hasFlash('msg')): ?>

<div class="flash-success">
	<?php echo Yii::app()->user->getFlash('msg'); ?>
</div>

<?php else: ?>


<?php 
if ($_REQUEST['from'] == 'diaglog') {//when view application node, can create contact
  $form=$this->beginWidget('CActiveForm', array('htmlOptions' => array(
    'name'=>'EditView', 
    'id'=>'editview_task', 
    'onSubmit'=>"return(dialogCreateTaskSubmit(this, '". $_REQUEST['parent_type'] ."', ". $_REQUEST['parent_id'] ."))",
    'pagetitle'=>'New Task',
    'class' => 'noajax'
    ))); 
} else {
  $form=$this->beginWidget('CActiveForm', array('htmlOptions' => array(
    'name'=>'EditView', 
    'id'=>'editview_task', 
    'pagetitle'=>'New Task',
    ))); 
}
?>

	<?php echo $form->errorSummary($model); ?>

  <table width="100%" border="0" cellspacing="0" cellpadding="0"><tbody><tr><td class="Leads-titleIcon">&nbsp;</td> 
  <td height="30" class="title hline" style="padding-left:10px"> <?php echo $title; ?> <span appapptagid="24"></span></td></tr></tbody></table>
  <p></p>
  <div class="bodyText mandatory"></div>

  <table width="95%" border="0" cellpadding="0" cellspacing="0"><tbody><tr><td align="center"><div style="float:right;" class="bodyText mandatory">* Required Field(s)</div>
    <input title="Save" class="button" onclick="" type="submit" name="button" value="Save"/>
    <?php if(!$_REQUEST['ajaxRequest']){//click the new task link from the subpanel, this form will show in dialog ?>  
    <input title="Cancel" class="button" type="button" name="button" value="Cancel" onclick="$('#create_new_task').dialog('close');"/>
    <?php } else { ?>
    <input title="Cancel" class="button buttonurl" type="button" name="button" value="Cancel" url="/task"/>
    <?php } ?>
  </td></tr></tbody></table>

  <div id="Information">
    <table id="secHeadLead Information" width="95%" cellspacing="0" cellpadding="0"><tbody width="100%"><tr><td class="secHead">Information</td></tr></tbody></table>
    <table width="100%" border="0" cellspacing="1" cellpadding="0" class="tabForm">
      <tbody>
        <tr>
          <td width="20%" class="label mandatory">*<?php echo $form->labelEx($model, 'subject'); ?>:</td>
          <td width="30%" class="element"><?php echo $form->textField($model, 'subject'); ?></td>
          <td width="20%" class="label mandatory">*<?php echo $form->labelEx($model, 'status'); ?>: </td>
          <td width="30%" class="element"><?php echo $form->dropDownList($model, 'status', DropDown::getSelfDefineOptions('task', 'status')); ?></td>
        </tr>
        <tr>
          <td width="20%" class="label"><?php echo $form->labelEx($model, 'date_due'); ?>: </td>
          <td width="30%" class="element"><?php echo $form->textField($model, 'date_due', array('class'=>'datepicker')); ?></td>
          <td width="20%" class="label"><?php echo $form->labelEx($model, 'date_start'); ?>: </td>
          <td width="30%" class="element"><?php echo $form->textField($model, 'date_start', array('class'=>'datepicker')); ?></td>
        </tr>
        <tr>
          <td width="20%" class="label mandatory">*<?php echo $form->labelEx($model, 'priority'); ?>: </td>
          <td width="30%" class="element"><?php echo $form->dropDownList($model, 'priority', array('High'=>'High', 'Medium'=>'Medium', 'Low'=>'Low')); ?></td>
          <td width="20%" class="label"><?php echo $form->labelEx($model, 'assigned_to'); ?>: </td>
          <td width="30%" class="element"><?php echo $form->dropDownList($model, 'assigned_to', DropDown::defaultAssignedTo()); ?></td>
        </tr>
        <tr>
          <td width="20%" class="label"><?php echo $form->labelEx($model, 'description'); ?>:</td>
          <td width="30%" class="element"><?php echo $form->textArea($model, 'description'); ?></td>
        </tr>
      </tbody>
    </table>
  </div>

	<table width="95%" border="0" cellpadding="0" cellspacing="0"><tbody><tr><td align="center"><div style="float:right;" class="bodyText mandatory">* Required Field(s)</div>
    <input title="Save" class="button" onclick="" type="submit" name="button" value="Save"/>
    <?php if(!$_REQUEST['ajaxRequest']){//click the new task link from the subpanel, this form will show in dialog ?>  
    <input title="Cancel" class="button" type="button" name="button" value="Cancel" onclick="$('#create_new_task').dialog('close');"/>
    <?php } else { ?>
    <input title="Cancel" class="button buttonurl" type="button" name="button" value="Cancel" url="/task"/>
    <?php } ?>
  </td></tr></tbody></table>
  
  
<?php $this->endWidget(); ?>

<?php endif; ?>

</div>