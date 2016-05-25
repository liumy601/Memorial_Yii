<div id="rwb" class="rwb">
  
<?php
  $title = 'Add Credits to Customer: ' . $customer->name;
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
  
<?php
//if ($credits) {
?>
<form method="post" action="/customer/addcredit/<?php echo $customer->id; ?>" class="noajax">
   
  
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
          <td width="20%" class="label">Name</td>
          <td width="30%" class="element">
           <?php  echo CHtml::textField('name', $_POST['name']); ?>
          </td>
        </tr>
         <tr> 
          <td width="20%" class="label">Amount</td>
          <td width="30%" class="element">
           <?php  echo CHtml::textField('amount', $_POST['amount']); ?>
          </td>
        </tr>
         <tr> 
          <td width="20%" class="label">Reason</td>
          <td width="30%" class="element">
           <?php echo CHtml::textArea('reason', $_POST['reason'], array('style' => 'width:130px; height:50px;')); ?>
          </td>
        </tr>
         <tr> 
          <td width="30%" class="element">
           <?php  echo CHtml::hiddenField('type', $_POST['type']); ?>
          </td>
        </tr>
      </tbody>
    </table>
  </div>

	<table width="95%" border="0" cellpadding="0" cellspacing="0"><tbody><tr><td align="center"></div>
    <input title="Save" class="button" onclick="" type="submit" name="button" value="Save"/>
    <input title="Cancel" class="button buttonurl" url="/customer/view/<?php echo $customer->id; ?>" pageTitle="Package" type="button" name="button" value="Cancel"/>
  </td></tr></tbody></table>
  
  
</form>
  <?php // } else { ?>
  <!--No new credits to select.-->
  <?php // } ?>
  

<?php endif; ?>

</div>