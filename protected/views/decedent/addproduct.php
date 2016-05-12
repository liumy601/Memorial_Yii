<div id="rwb" class="rwb">
  
<?php
  $title = 'Add Products to Customer: ' . $customer->name;
?>


<?php
$this->pageTitle = Yii::app()->name . ' - ' . $title;2016/5/12
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
if ($products) {
?>
<form method="post" action="/decedent/addproduct/<?php echo $customer->id; ?>">
   
  
  <div class="bodyText mandatory"><div style="float:right;" class="bodyText mandatory">* Required Field(s)</div></div>

  <table width="95%" border="0" cellpadding="0" cellspacing="0"><tbody><tr><td align="center">
    <input title="Save" class="button" onclick="" type="submit" name="button" value="Save"/>
    <input title="Cancel" class="button buttonurl" url="/decedent/view/<?php echo $customer->id; ?>" pageTitle="Package" type="button" name="button" value="Cancel"/>
  </td></tr></tbody></table>

  <div id="Information">
    <table id="secHeadLead Information" width="95%" cellspacing="0" cellpadding="0"><tbody width="100%"><tr><td class="secHead">Information</td></tr></tbody></table>
    <table width="100%" border="0" cellspacing="1" cellpadding="0" class="tabForm">
      <tbody>
        <tr> 
          <td width="20%" class="label mandatory">Select products</td>
          <td width="30%" class="element">
           <!--tree view-->
           <ul id="black" class="treeview-black">
             <li>
               <span>Services</span>
               <ul><li>sfsdfds</li></ul>
             </li>
             <li>
               <span>Merchandise</span>
               <ul><li>hjn34rdfd</li></ul>
             </li>
             <li>
               <span>Cash Advances</span>
               <ul><li>rtm5y432ffdsgdf</li></ul>
               <ul><li>47ubef</li></ul>
             </li>
           </ul>
           
          </td>
        </tr>
         <tr> 
          <td width="20%" class="label">Internal Notes</td>
          <td width="30%" class="element">
           <?php  echo CHtml::textArea('internal_notes', $_POST['internal_notes'], array('style'=>'width:300px;height:80px;')); ?>
          </td>
        </tr>
         <tr> 
          <td width="20%" class="label">Invoice Notes</td>
          <td width="30%" class="element">
           <?php  echo CHtml::textArea('invoice_notes', $_POST['invoice_notes'], array('style'=>'width:300px;height:80px;')); ?>
          </td>
        </tr>
      </tbody>
    </table>
  </div>

	<table width="95%" border="0" cellpadding="0" cellspacing="0"><tbody><tr><td align="center"></div>
    <input title="Save" class="button" onclick="" type="submit" name="button" value="Save"/>
    <input title="Cancel" class="button buttonurl" url="/decedent/view/<?php echo $customer->id; ?>" pageTitle="Package" type="button" name="button" value="Cancel"/>
  </td></tr></tbody></table>
  
  
</form>
  
  <script src="/assets/treeview/jquery.treeview.js" type="text/javascript"></script>
  <script>
$(document).ready(function(){
	// fourth example
	$("#black, #gray").treeview({
		control: "#treecontrol",
		persist: "cookie",
		cookieId: "treeview-black"
	});

});

  </script>
  <?php } else { ?>
  No new products to select.
  <?php } ?>
  

<?php endif; ?>

</div>
