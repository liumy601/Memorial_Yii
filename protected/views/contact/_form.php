<div id="rwb" class="rwb">
  
<?php
if ($model->id > 0) {
  $title = 'Update Contact';
} else {
  $title = 'Create Contact';
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
    <?php if($model->id){//edit ?>
    <input title="Cancel" class="button buttonurl" url="/contact/<?php echo $model->id; ?>" pageTitle="Contact" type="button" name="button" value="Cancel"/>
    <?php } elseif ($_GET['customerid']){//create from customer view page ?>
    <input title="Cancel" class="button buttonurl" url="/customer/<?php echo $model->customerid; ?>" pageTitle="Contact" type="button" name="button" value="Cancel"/>
    <?php } ?>
  </td></tr></tbody></table>

  <div id="Information">
    <table id="secHeadLead Information" width="95%" cellspacing="0" cellpadding="0"><tbody width="100%"><tr><td class="secHead">Information</td></tr></tbody></table>
    <table width="100%" border="0" cellspacing="1" cellpadding="0" class="tabForm">
      <tbody>
       <tr>
        <td width="20%" class="label mandatory">*<?php echo $form->labelEx($model,'customer'); ?></td>
        <td width="30%" class="element"><?php echo $form->textField($model, 'customer') . $form->hiddenField($model, 'customerid', array('readonly'=>true)); ?> <input title="Select" class="button" onclick="lookup('customer', 'Contact_customer');" type="button" name="button" value="Select"/></td>
        <td width="20%" class="label mandatory">*<?php echo $form->labelEx($model,'full_name'); ?></td>
        <td width="30%" class="element"><?php echo $form->textField($model,'full_name',array('size'=>50,'maxlength'=>50)); ?></td>
        
       </tr>

       <tr>
        <td width="20%" class="label"><?php echo $form->labelEx($model,'address'); ?></td>
        <td width="30%" class="element"><?php echo $form->textField($model,'address',array('size'=>50,'maxlength'=>50)); ?></td>
        <td width="20%" class="label"><?php echo $form->labelEx($model,'phone'); ?></td>
        <td width="30%" class="element"><?php echo $form->textField($model,'phone',array('size'=>50,'maxlength'=>50)); ?></td>
       </tr>
        
       <tr>
        <td width="20%" class="label"><?php echo $form->labelEx($model,'email'); ?></td>
        <td width="30%" class="element"><?php echo $form->textField($model,'email',array('size'=>50,'maxlength'=>50)); ?></td>
        <td width="20%" class="label"><?php echo $form->labelEx($model,'relationship'); ?></td>
        <td width="30%" class="element" id="dropdown"><?php echo $form->dropdownlist($model,'relationship',  DropDown::getSelfDefineOptions('contact', 'relationship')); ?></td>
       </tr>
        
       <tr>
        <td width="20%" class="label"><?php echo $form->labelEx($model,'note'); ?></td>
        <td width="30%" class="element"><?php echo $form->textArea($model,'note',array('rows'=>5,'cols'=>50)); ?></td>
       </tr>
      </tbody>  
    </table>
  </div>
  
	<table width="95%" border="0" cellpadding="0" cellspacing="0"><tbody><tr><td align="center"></div>
    <input title="Save" class="button" onclick="" type="submit" name="button" value="Save"/>
    <?php if($model->id){//edit ?>
    <input title="Cancel" class="button buttonurl" url="/contact/<?php echo $model->id; ?>" pageTitle="Contact" type="button" name="button" value="Cancel"/>
    <?php } elseif ($_GET['customerid']){//create from customer view page ?>
    <input title="Cancel" class="button buttonurl" url="/customer/<?php echo $model->customerid; ?>" pageTitle="Contact" type="button" name="button" value="Cancel"/>
    <?php } ?>
  </td></tr></tbody></table>
  
  
<?php $this->endWidget(); ?>

<?php endif; ?>

</div>


<script type="text/javascript">
  
</script>