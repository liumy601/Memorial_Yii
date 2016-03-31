<div id="rwb" class="rwb">
  
<?php
if ($model->id > 0) {
  $title = 'Update note';
} else {
  $title = 'Create note';
}
?>
       

<?php
$this->pageTitle = Yii::app()->name . ' - ' . $title;
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
    'id'=>'editview_notes',
    'onSubmit'=>"return(dialogCreateNotesSubmit(this, '". $_REQUEST['parent_type'] ."', ". $_REQUEST['parent_id'] ."))",
    'pagetitle'=>'New note',
    'class' => 'noajax'
    ))); 
} else {
  $form=$this->beginWidget('CActiveForm', array('htmlOptions' => array(
    'name'=>'EditView', 
    'id'=>'editview_notes', 
    'pagetitle'=>'New note',
    ))); 
}
?>

	<?php echo $form->errorSummary($model); ?>

  <table width="100%" border="0" cellspacing="0" cellpadding="0"><tbody><tr><td class="Leads-titleIcon">&nbsp;</td> 
  <td height="30" class="title hline" style="padding-left:10px"> <?php echo $title; ?> </td></tr></tbody></table>
  <p></p>
  <div class="bodyText mandatory"></div>

  <table width="95%" border="0" cellpadding="0" cellspacing="0"><tbody><tr><td align="center"><div style="float:right;" class="bodyText mandatory">* Required Field(s)</div>
    <input title="Save" class="button" onclick="" type="submit" name="button" value="Save"/>
    <?php if(!$_REQUEST['ajaxRequest']){//click the New note link from the subpanel, this form will show in dialog ?>  
    <input title="Cancel" class="button" type="button" name="button" value="Cancel" onclick="$('#create_new_notes').dialog('close');"/>
    <?php } else { ?>
    <input title="Cancel" class="button buttonurl" type="button" name="button" value="Cancel" url="/<?php echo $model->parent_type ?>/view/<?php echo $model->parent_id ?>"/>
    <?php } ?>
  </td></tr></tbody></table>

  <div id="Information">
    <table id="secHeadLead Information" width="95%" cellspacing="0" cellpadding="0"><tbody width="100%"><tr><td class="secHead">Information</td></tr></tbody></table>
    <table width="100%" border="0" cellspacing="1" cellpadding="0" class="tabForm">
      <tbody>
        <tr>
          <td width="30%" class="label mandatory">*<?php echo $form->labelEx($model, 'subject'); ?>:</td>
          <td width="70%" class="element"><?php echo $form->textField($model, 'subject'); ?></td>
        </tr>
        <tr>
          <td width="30%" class="label"><?php echo $form->labelEx($model, 'body'); ?>:</td>
          <td width="70%" class="element"><?php echo $form->textArea($model, 'body', array('style'=>'width:250px;height:110px;')); ?></td>
        </tr>
      </tbody>
    </table>
  </div>

	<table width="95%" border="0" cellpadding="0" cellspacing="0"><tbody><tr><td align="center"><div style="float:right;" class="bodyText mandatory">* Required Field(s)</div>
    <input title="Save" class="button" onclick="" type="submit" name="button" value="Save"/>
    <?php if(!$_REQUEST['ajaxRequest']){//click the New note link from the subpanel, this form will show in dialog ?>  
    <input title="Cancel" class="button" type="button" name="button" value="Cancel" onclick="$('#create_new_notes').dialog('close');"/>
    <?php } else { ?>
    <input title="Cancel" class="button buttonurl" type="button" name="button" value="Cancel" url="/<?php echo $model->parent_type ?>/view/<?php echo $model->parent_id ?>"/>
    <?php } ?>
  </td></tr></tbody></table>
  
  
<?php $this->endWidget(); ?>

<?php endif; ?>

</div>