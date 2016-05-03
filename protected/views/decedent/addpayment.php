<div id="rwb" class="rwb">
  
<?php
  $title = 'Add Payments to Decedent: ' . $customer->name;
?>


<?php
$this->pageTitle = Yii::app()->name . ' - ' . $title;
?>

<?php if(Yii::app()->user->hasFlash('msg')): ?>

<div class="flash-success">
	<?php echo Yii::app()->user->getFlash('msg'); ?>
</div>

<?php else: ?>

  <table width="100%" border="0" cellspacing="0" cellpadding="0"><tbody><tr><td class="Leads-titleIcon">&nbsp;</td> 
  <td height="30" class="title hline" style="padding-left:10px"> <?php echo $title; ?> </td></tr></tbody></table>
  <p></p>
  
<?php $form=$this->beginWidget('CActiveForm', array('htmlOptions' => 
    array('name'=>'EditView', 'id'=>'EditView', 
        'class'=>'noajax', 
//        'enctype'=>'multipart/form-data',
        'class'=>"noajax",
        'onsubmit'=>"return(check_form('EditView'));"
    ))); ?>

	<?php echo $form->errorSummary($model); ?>
   
  
  <div class="bodyText mandatory"><div style="float:right;" class="bodyText mandatory">* Required Field(s)</div></div>

  <table width="95%" border="0" cellpadding="0" cellspacing="0"><tbody><tr><td align="center">
    <input title="Save" class="button" onclick="" type="submit" name="button" value="Save"/>
    <input title="Cancel" class="button buttonurl" url="/customer/view/<?php echo $customer->id; ?>" pageTitle="Package" type="button" name="button" value="Cancel"/>
  </td></tr></tbody></table>

  <div id="Information">
    <table id="secHeadLead Information" width="95%" cellspacing="0" cellpadding="0"><tbody width="100%"><tr><td class="secHead">Information</td></tr></tbody></table>
    <table width="100%" border="0" cellspacing="1" cellpadding="0" class="tabForm">
      <tbody>
        <tr> 
          <td width="20%" class="label">Check No.</td>
          <td width="30%" class="element">
            <?php echo $form->textField($model,'check_number',array('size'=>20,'maxlength'=>50)); ?>
          </td>
        </tr>
         <tr> 
          <td width="20%" class="label">Payer</td>
          <td width="30%" class="element">
            <?php echo $form->textField($model,'payer',array('size'=>20,'maxlength'=>50)); ?>
          </td>
        </tr>
        <tr> 
          <td width="20%" class="label">Amount</td>
          <td width="30%" class="element">
            <?php echo $form->textField($model,'amount',array('size'=>20,'maxlength'=>50)); ?>
          </td>
        </tr>
        <tr> 
          <td width="20%" class="label">Date</td>
          <td width="30%" class="element">
            <?php echo $form->textField($model,'date',array('size'=>20,'maxlength'=>50,'class'=>'datePicker')); ?>
          </td>
        </tr>
        <tr> 
          <td width="20%" class="label">Address</td>
          <td width="30%" class="element">
            <?php echo $form->textField($model,'address',array('size'=>20,'maxlength'=>50)); ?>
          </td>
        </tr>
        <tr> 
          <td width="20%" class="label">City</td>
          <td width="30%" class="element">
            <?php echo $form->textField($model,'city',array('size'=>20,'maxlength'=>50)); ?>
          </td>
        </tr>
        <tr> 
          <td width="20%" class="label">State</td>
          <td width="30%" class="element">
            <?php echo $form->textField($model,'state',array('size'=>20,'maxlength'=>50)); ?>
          </td>
        </tr>
        <tr> 
          <td width="20%" class="label">Zip</td>
          <td width="30%" class="element">
            <?php echo $form->textField($model,'zip',array('size'=>20,'maxlength'=>50)); ?>
          </td>
        </tr>
        <tr> 
          <td width="20%" class="label">Payment Method</td>
          <td width="30%" class="element">
            <?php echo $form->dropDownList($model,'payment_method',array(''=>'', 'credit card'=>'credit card', 'check'=>'check', 'cash'=>'cash', 'Money Order'=>'Money Order'),array('style'=>'width: 130px;')); ?>
          </td>
        </tr>
         <tr> 
          <td width="20%" class="label">Reason</td>
          <td width="30%" class="element">
            <?php echo $form->textArea($model,'reason',array('rows'=>8,'cols'=>50)); ?>
          </td>
        </tr>
        
      </tbody>
    </table>
  </div>

	<table width="95%" border="0" cellpadding="0" cellspacing="0"><tbody><tr><td align="center"></div>
    <input title="Save" class="button" onclick="" type="submit" name="button" value="Save"/>
    <input title="Cancel" class="button buttonurl" url="/customer/view/<?php echo $customer->id; ?>" pageTitle="Package" type="button" name="button" value="Cancel"/>
  </td></tr></tbody></table>
  
  
<?php $this->endWidget(); ?>

<?php endif; ?>

</div>
