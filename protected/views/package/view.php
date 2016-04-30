<div class="bodycontainer"><div id="BodyContent">
<table width="100%" cellspacing="0" cellpadding="0"><tbody><tr><td> 
<div id="leftPanel">
  
<!--buttons-->
<div id="detailViewButtonLayerDiv" class="detailViewButtonLayerDiv" style="z-index: 19; width: 1240px; box-shadow: none; "><table cellpadding="5" cellspacing="0" width="100%" class="dvbtnlayer"><tbody><tr><td align="center" nowrap="" class="pL15"> <a href="javascript:void(0);" onclick="history.back();return false;"><img src="/images/spacer.gif" class="backtoIcon" border="0"></a> </td><td nowrap="" class="pL10 dvmo"> 
<input class="dveditBtn dvcbtn buttonurl" type="button" value="Edit" name="Edit" url="/package/update/<?php echo $model->id; ?>" pagetitle="Edit contact" id="editview_student_<?php echo $model->id; ?>"/>
<input name="Delete2" class="dvdelBtn dvcbtn" type="button" value="Delete" url="/package/delete/<?php echo $model->id ?>" onclick="if(confirm('Are you sure delete this record?')){ ajaxRequest(this); }">
&nbsp;
</td>
<td width="100" class="dvmo"> </td>
<td> </td>
<td width="90%"> <div align="right">

</td>
</tr></tbody></table></div>
  
  
<!--view object-->
<div class="p15 mt5">
<div>
<div class="maininfo"><span id="cutomizebc"></span> 
<span id="headervalue_Account Name" class="dvTitle">Package: <?php echo CHtml::encode($model->name); ?></span> 
<span id="headervalue_Website" class="dvSubTitle"><span id="subvalue1_Website"></span></span> <br>

<?php
 if (!Yii::app()->params['print']) {
?>
<div style="float:right">
  <a href="javascript:void window.open('/package/view/id/<?php echo $model->id ?>/print/true','printwin','menubar=1,status=0,resizable=1,scrollbars=1,toolbar=0,location=1')" class="utilsLink"><img src="/themes/Sugar/images/print.gif" width="13" height="13" alt="Print" border="0" align="absmiddle"></a>
  <a href="javascript:void window.open('/package/view/id/<?php echo $model->id ?>/print/true','printwin','menubar=1,status=0,resizable=1,scrollbars=1,toolbar=0,location=1')" class="utilsLink">Print</a>
</div>
<?php
 }
?>

<table width="100%" style="display:block"><!--detail-->
  <tbody>
    <tr>
      <td width="15%" class="dvgrayTxt"><?php echo CHtml::encode($model->getAttributeLabel('name')); ?></td><td width="5" class="dvgrayTxt">:</td> 
      <td width="35%" class="dvValueTxt"><?php echo CHtml::encode($model->name); ?></td>
      <td width="50%" class="dvgrayTxt" colspan="2"> </td> 
    </tr>
  </tbody>
</table>


<br/>
<br/>
<!--Products-->
<table border="0" width="95%" align="center" cellspacing="0" cellpadding="0" class="relCenterBg"><tbody><tr height="25">
<td width="140" nowrap=""> 
<a href="javascript:toggleRelatedList('relationship_product_<?php echo $model->id; ?>');" 
   name="relationship_product_<?php echo $model->id; ?>" id="relationship_product_<?php echo $model->id; ?>" class="relListHead">
Products</a> 
</td>
<td align="right"> <div id="ract_<?php echo $model->id; ?>" name="raction"> </td>
</tr></tbody>
</table>
<div id="product_<?php echo $model->id; ?>" style="display:block">
<table border="0" width="95%" cellspacing="0" cellpadding="0" align="center">
<tbody><tr><td colspan="2"> 
<table border="0" width="100%" cellspacing="0" cellpadding="0" class="rellist">
<tbody><tr class="tableHeadBg"><td>&nbsp;</td> 
<td nowrap>Name</td>
<td nowrap>Vendor</td>
<td nowrap>SKU</td>
<td nowrap>Retail</td>
<td nowrap>Cost</td>
</tr>
<?php
$totalPrice = 0;
while ($row = $productDataProvider->read()) {
  $totalPrice += (float)$row['retail'];
?>
<tr>
<td nowrap="" width="12%" class="tableData"> <div align="left">&nbsp;
    <a href="#" class="listViewTdToolsS1" url="/package/deleteproduct/package_id/<?php echo $model->id ?>/inventory_id/<?php echo $row['id']; ?>" onclick="if(confirm('Are you sure delete this record?')){ ajaxRequest(this); } return false;">Remove</a>
</div>
</td>
<td class="tableData"> <?php echo $row['name'] ?> </td>
<td class="tableData"> <?php echo $row['vendor'] ?> </td>
<td class="tableData"> <?php echo $row['sku'] ?> </td>
<td class="tableData"> $<?php echo $row['retail'] ?> </td>
<td class="tableData"> $<?php echo $row['cost'] ?> </td>
</tr>
<?php
}
?>

</tbody></table>
</td></tr>
<tr class="rellistbL"><td align="left"><div id="ra">
<a href="/package/addproduct/<?php echo $model->id; ?>" class="rellistNew" class="noajax">Add product</a></div></td> 
<td align="right"><div align="right" class="listNav">Total Price: $<?php echo $totalPrice; ?> &nbsp;</div>
</td></tr> <!-- Field Data goes here --></tbody></table>
</div><br>
<!--Products --------end-->


</div><div class="cb"></div>

</div>
</div>
