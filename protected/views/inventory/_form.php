<div id="rwb" class="rwb">
  
<?php
if ($model->id > 0) {
  $title = 'Update Inventory';
} else {
  $title = 'Create Inventory';
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
  <td height="30" class="title hline" style="padding-left:10px"> <?php echo $title; ?> <span appapptagid="27"></span></td></tr></tbody></table>
  <p></p>
  <div class="bodyText mandatory"><div style="float:right;" class="bodyText mandatory">* Required Field(s)</div></div>

  <table width="95%" border="0" cellpadding="0" cellspacing="0"><tbody><tr><td align="center">
    <input title="Save" class="button" onclick="" type="submit" name="button" value="Save"/>
    <input title="Cancel" class="button buttonurl" url="/inventory" pageTitle="Inventory" type="button" name="button" value="Cancel"/>
  </td></tr></tbody></table>

  <div id="Information">
    <table id="secHeadLead Information" width="95%" cellspacing="0" cellpadding="0"><tbody width="100%"><tr><td class="secHead">Information</td></tr></tbody></table>
    <table width="100%" border="0" cellspacing="1" cellpadding="0" class="tabForm">
      <tbody>
       <tr>
        <td width="20%" class="label"><?php echo $form->labelEx($model,'name'); ?></td>
        <td width="30%" class="element"><?php echo $form->textField($model,'name',array('size'=>50,'maxlength'=>50)); ?></td>
        <td width="20%" class="label"><?php echo $form->labelEx($model,'vendor'); ?></td>
        <td width="30%" class="element"><?php echo $form->textField($model,'vendor',array('size'=>50,'maxlength'=>50)); ?></td>
       </tr>

       <tr>
        <td width="20%" class="label"><?php echo $form->labelEx($model,'sku'); ?></td>
        <td width="30%" class="element"><?php echo $form->textField($model,'sku',array('size'=>50,'maxlength'=>50)); ?></td>
        <td width="20%" class="label"><?php echo $form->labelEx($model,'retail'); ?></td>
        <td width="30%" class="element"><?php echo $form->textField($model,'retail',array('size'=>50,'maxlength'=>50)); ?></td>
       </tr>

       <tr>
        <td width="20%" class="label"><?php echo $form->labelEx($model,'cost'); ?></td>
        <td width="30%" class="element"><?php echo $form->textField($model,'cost',array('size'=>50,'maxlength'=>50)); ?></td>
        <td width="20%" class="label"><?php echo $form->labelEx($model,'template_id'); ?></td>
        <td width="30%" class="element"><?php echo $form->dropdownlist($model,'template_id', Template::getAll()); ?></td>
       </tr>

       <tr>
        <td width="20%" class="label"><?php echo $form->labelEx($model,'taxable'); ?></td>
        <td width="30%" class="element"><?php echo $form->checkBox($model,'taxable');?></td>
        <td width="20%" class="label mandatory"><?php echo $form->labelEx($model,'category'); ?></td>
        <td width="30%" class="element"><?php echo $form->dropdownlist($model,'category', array(''=>'', 'Services'=>'Services', 'Merchandise'=>'Merchandise', 'Cash Advances'=>'Cash Advances')); ?></td>
       </tr>

       <tr>
        <td width="20%" class="label"><?php echo $form->labelEx($model,'internal_notes'); ?></td>
        <td width="30%" class="element"><?php echo $form->textArea($model,'internal_notes', array('style'=>"width:280px;height:60px;"));?></td>
        <td width="20%" class="label"><?php echo $form->labelEx($model,'invoice_notes'); ?></td>
        <td width="30%" class="element"><?php echo $form->textArea($model,'invoice_notes', array('style'=>"width:280px;height:60px;")); ?></td>
       </tr>
      </tbody>  
    </table>
  </div>
  
	<table width="95%" border="0" cellpadding="0" cellspacing="0"><tbody><tr><td align="center"></div>
    <input title="Save" class="button" onclick="" type="submit" name="button" value="Save"/>
    <input title="Cancel" class="button buttonurl" url="/inventory" pageTitle="Inventory" type="button" name="button" value="Cancel"/>
  </td></tr></tbody></table>
  
  
<?php $this->endWidget(); ?>

<?php endif; ?>

</div>


<script type="text/javascript">
  function calAge(){
    
  }
</script>