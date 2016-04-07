<div id="rwb" class="rwb">
  
<?php
  $title = 'Add Document for every product and template to Customer: ' . $customer->name;
  
  $documents = Template::getAll($customer->id);
  
  $altDocuments = $documents;
  $documents = array();
  foreach ($altDocuments as $key=>$val) {
    if ($key > 0) {
      $documents[$key] = $val;
    }
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

  <table width="100%" border="0" cellspacing="0" cellpadding="0"><tbody><tr><td class="Leads-titleIcon">&nbsp;</td> 
  <td height="30" class="title hline" style="padding-left:10px"> <?php echo $title; ?> </td></tr></tbody></table>
  <p></p>
  
<?php
if ($documents) {
?>
<form method="post" action="/customer/adddocument/<?php echo $customer->id; ?>" class="noajax">
   
  
  <div class="bodyText mandatory"><div style="float:right;" class="bodyText mandatory">* Required Field(s)</div></div>

  <table width="95%" border="0" cellpadding="0" cellspacing="0"><tbody><tr><td align="center">
    <input title="Save" class="button" onclick="" type="submit" name="button" value="Save"/>
    <input title="Cancel" class="button" onclick="backToCustomer();" pageTitle="Package" type="button" name="button" value="Cancel"/>
  </td></tr></tbody></table>

  <div id="Information">
    <table id="secHeadLead Information" width="95%" cellspacing="0" cellpadding="0"><tbody width="100%"><tr><td class="secHead">Information</td></tr></tbody></table>
    <table width="100%" border="0" cellspacing="1" cellpadding="0" class="tabForm">
      <tbody>
        <tr> 
          <td width="20%" class="label mandatory">Select document</td>
          <td width="30%" class="element"><?php echo CHtml::checkBoxList('document[]', $_POST['document'], $documents); ?></td>
        </tr>
      </tbody>
    </table>
  </div>

	<table width="95%" border="0" cellpadding="0" cellspacing="0"><tbody><tr><td align="center"></div>
    <input title="Save" class="button" onclick="" type="submit" name="button" value="Save"/>
    <input title="Cancel" class="button" onclick="backToCustomer();" pageTitle="Package" type="button" name="button" value="Cancel"/>
  </td></tr></tbody></table>
  
  
</form>
  <?php } else { ?>
  No new documents to select.  <input title="Cancel" class="button" onclick="backToCustomer();" pageTitle="Package" type="button" name="button" value="&laquo; Back"/>
  <?php } ?>
  

<?php endif; ?>

</div>


<script type="text/javascript">
  function backToCustomer(){
    document.location.href='/customer/view/<?php echo $customer->id; ?>?tm=<?php echo time(); ?>#documentslist';
  }
</script>