<div id="rwb" class="rwb">
  
<?php
if ($model->id > 0) {
  $title = 'Update Package';
} else {
  $title = 'Create Package';
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


<?php $form=$this->beginWidget('CActiveForm', array('htmlOptions' => 
    array('name'=>'EditView', 'id'=>'EditView', 
        'class'=>'noajax', 
        'enctype'=>'multipart/form-data',
        'onsubmit'=>"return(check_form('EditView'));"
    ))); ?>

	<?php echo $form->errorSummary($model); ?>

   
  <table width="100%" border="0" cellspacing="0" cellpadding="0"><tbody><tr><td class="Leads-titleIcon">&nbsp;</td> 
  <td height="30" class="title hline" style="padding-left:10px"> <?php echo $title; ?> </td></tr></tbody></table>
  <p></p>
  <div class="bodyText mandatory"><div style="float:right;" class="bodyText mandatory">* Required Field(s)</div></div>

  <table width="95%" border="0" cellpadding="0" cellspacing="0"><tbody><tr><td align="center">
    <input title="Save" class="button" onclick="" type="submit" name="button" value="Save"/>
    <input title="Cancel" class="button buttonurl" url="/inventory" pageTitle="Package" type="button" name="button" value="Cancel"/>
  </td></tr></tbody></table>

  <div id="Information">
    <table id="secHeadLead Information" width="95%" cellspacing="0" cellpadding="0"><tbody width="100%"><tr><td class="secHead">Information</td></tr></tbody></table>
    <table width="100%" border="0" cellspacing="1" cellpadding="0" class="tabForm">
      <tbody>
        <tr> 
          <td width="20%" class="label mandatory">*<?php echo $form->labelEx($model, 'name'); ?>:</td>
          <td width="30%" class="element"><?php echo $form->textField($model, 'name'); ?></td>
        </tr>
      </tbody>
    </table>
  </div>

	<table width="95%" border="0" cellpadding="0" cellspacing="0"><tbody><tr><td align="center"></div>
    <input title="Save" class="button" onclick="" type="submit" name="button" value="Save"/>
    <input title="Cancel" class="button buttonurl" url="/inventory" pageTitle="Package" type="button" name="button" value="Cancel"/>
  </td></tr></tbody></table>
  
  
<?php $this->endWidget(); ?>

<?php endif; ?>

</div>