<div class="bodycontainer"><div id="BodyContent">
<table width="100%" cellspacing="0" cellpadding="0"><tbody><tr><td> 
<div id="leftPanel">
  
<!--buttons-->
<div id="detailViewButtonLayerDiv" class="detailViewButtonLayerDiv" style="z-index: 19; width: 1240px; box-shadow: none; "><table cellpadding="5" cellspacing="0" width="100%" class="dvbtnlayer"><tbody><tr><td align="center" nowrap="" class="pL15">  </td><td nowrap="" class="pL10 dvmo"> 
Search Results: <b><?php echo $_POST['searchword'] ?></b>
</td>
<td width="100" class="dvmo"> </td>
<td> </td>
<td width="90%"> <div align="right">

</td>
</tr></tbody></table></div>
  
  
<!--view object-->
<div class="p15 mt5">
<div>
<div class="maininfo">
  
<?php $i=0; ?>

<!-- customer  -->
<?php if($customerDR && $customerDR->count()){ ?>
<div id="customer_list">
<table border="0" width="95%" align="center" cellspacing="0" cellpadding="0" class="relCenterBg"><tbody><tr height="25">
<td width="140" nowrap=""> 
<a href="javascript:toggleRelatedList('relationship_customer_<?php echo $model->id; ?>');" 
   name="relationship_customerlog_<?php echo $model->id; ?>" id="relationship_customerlog_<?php echo $model->id; ?>" 
   class="relListHead">Customer</a> 
</td>
<td align="right"> <div id="ract_<?php echo $model->id; ?>" name="rcustomer"> </td>
</tr></tbody>
</table>
<div id="customer_<?php echo $model->id; ?>" style="display:block">
<table border="0" width="95%" cellspacing="0" cellpadding="0" align="center">
<tbody><tr><td colspan="2"> 
<table border="0" width="100%" cellspacing="0" cellpadding="0" class="rellist">
<tbody><tr class="tableHeadBg">
<td>Name</td>
</tr>
<?php
while ($row = $customerDR->read()) {
$i++;
?>
<tr>
<!--<td class="tableData"> <?php // echo CHtml::link($row['full_legal_name_f']. ' '. $row['full_legal_name_m'] .' '. $row['full_legal_name_l'], '/customer/view/'.$row['id'], array('target'=>'_blank','class'=>'sl noajax')); ?> </td>-->
<td class="tableData"> <?php echo CHtml::link($row['full_legal_name'], '/customer/view/'.$row['id'], array('target'=>'_blank','class'=>'sl noajax')); ?> </td>
</tr>
<?php
}
?>

</tbody></table>
</td></tr><!-- Field Data goes here --></tbody></table>
</div>
</div><br>
<?php } ?>



<!-- task  -->
<?php if($taskDR && $taskDR->count()){ ?>
<br>
<div id="task_list">
<table border="0" width="95%" align="center" cellspacing="0" cellpadding="0" class="relCenterBg"><tbody><tr height="25">
<td width="140" nowrap=""> 
<a href="javascript:toggleRelatedList('relationship_task_<?php echo $model->id; ?>');" 
   name="relationship_task_<?php echo $model->id; ?>" id="relationship_task_<?php echo $model->id; ?>" 
   class="relListHead">Task</a> 
</td>
<td align="right"> <div id="ract_<?php echo $model->id; ?>" name="raction"> </td>
</tr></tbody>
</table>
<div id="task_<?php echo $model->id; ?>" style="display:block">
<table border="0" width="95%" cellspacing="0" cellpadding="0" align="center">
<tbody><tr><td colspan="2"> 
<table border="0" width="100%" cellspacing="0" cellpadding="0" class="rellist">
<tbody><tr class="tableHeadBg">
<td>Name</td>
<td>Status</td>
</tr>
<?php
while ($row = $taskDR->read()) {
$i++;
?>
<tr>
<td class="tableData"> <?php echo CHtml::link($row['subject'], '/task/view/'.$row['id'], array('target'=>'_blank','class'=>'sl noajax')); ?> </td>
<td class="tableData"> <?php echo CHtml::encode($row['status']); ?> </td>
</tr>
<?php
}
?>

</tbody></table>
</td></tr><!-- Field Data goes here --></tbody></table>
</div>
</div><br>
<?php } ?>



<!-- inventorys  -->
<?php if($inventoryDR && $inventoryDR->count()){ ?>
<br>
<div id="inventory_list">
<table border="0" width="95%" align="center" cellspacing="0" cellpadding="0" class="relCenterBg"><tbody><tr height="25">
<td width="140" nowrap=""> 
<a href="javascript:toggleRelatedList('relationship_inventory_<?php echo $model->id; ?>');" 
   name="relationship_inventory_<?php echo $model->id; ?>" id="relationship_inventory_<?php echo $model->id; ?>" 
   class="relListHead">Inventory</a> 
</td>
<td align="right"> <div id="ract_<?php echo $model->id; ?>" name="raction"> </td>
</tr></tbody>
</table>
<div id="inventory_<?php echo $model->id; ?>" style="display:block">
<table border="0" width="95%" cellspacing="0" cellpadding="0" align="center">
<tbody><tr><td colspan="2"> 
<table border="0" width="100%" cellspacing="0" cellpadding="0" class="rellist">
<tbody><tr class="tableHeadBg">
<td>Name</td>
<td>Vendor</td>
<td>SKU</td>
<td>Retail</td>
<td>Cost</td>
</tr>
<?php
while ($row = $inventoryDR->read()) {
$i++;
?>
<tr>
<td class="tableData"> <?php echo CHtml::link($row['name'], '/inventory/view/'.$row['id'], array('target'=>'_blank','class'=>'sl noajax')); ?> </td>
<td class="tableData"> <?php echo CHtml::encode($row['vendor']); ?> </td>
<td class="tableData"> <?php echo CHtml::encode($row['sku']); ?> </td>
<td class="tableData"> <?php echo CHtml::encode($row['retail']); ?> </td>
<td class="tableData"> <?php echo CHtml::encode($row['cost']); ?> </td>
</tr>
<?php
}
?>

</tbody></table>
</td></tr><!-- Field Data goes here --></tbody></table>
</div>
</div><br>
<?php } ?>



<!-- packages  -->
<?php if($packageDR && $packageDR->count()){ ?>
<br>
<div id="package_list">
<table border="0" width="95%" align="center" cellspacing="0" cellpadding="0" class="relCenterBg"><tbody><tr height="25">
<td width="140" nowrap=""> 
<a href="javascript:toggleRelatedList('relationship_package_<?php echo $model->id; ?>');" 
   name="relationship_package_<?php echo $model->id; ?>" id="relationship_package_<?php echo $model->id; ?>" 
   class="relListHead">Package</a> 
</td>
<td align="right"> <div id="ract_<?php echo $model->id; ?>" name="raction"> </td>
</tr></tbody>
</table>
<div id="package_<?php echo $model->id; ?>" style="display:block">
<table border="0" width="95%" cellspacing="0" cellpadding="0" align="center">
<tbody><tr><td colspan="2"> 
<table border="0" width="100%" cellspacing="0" cellpadding="0" class="rellist">
<tbody><tr class="tableHeadBg">
<td nowrap>Name</td>
<td>Products</td>
<td nowrap>Total Prices</td>
</tr>
<?php
while ($row = $packageDR->read()) {
$i++;
?>
<tr>
<td class="tableData"> <?php echo CHtml::link($row['name'], '/package/view/'.$row['id'], array('target'=>'_blank','class'=>'sl noajax')); ?> </td>
<td class="tableData"> <?php echo Package::getProductNames($row['id']) ?> </td>
<td class="tableData"> $<?php echo Package::getProductPrices($row['id']) ?> </td>
</tr>
<?php
}
?>

</tbody></table>
</td></tr><!-- Field Data goes here --></tbody></table>
</div>
</div><br>
<?php } ?>



<!-- templates  -->
<?php if($templateDR && $templateDR->count()){ ?>
<br>
<div id="template_list">
<table border="0" width="95%" align="center" cellspacing="0" cellpadding="0" class="relCenterBg"><tbody><tr height="25">
<td width="140" nowrap=""> 
<a href="javascript:toggleRelatedList('relationship_template_<?php echo $model->id; ?>');" 
   name="relationship_template_<?php echo $model->id; ?>" id="relationship_template_<?php echo $model->id; ?>" 
   class="relListHead">Template</a> 
</td>
<td align="right"> <div id="ract_<?php echo $model->id; ?>" name="raction"> </td>
</tr></tbody>
</table>
<div id="template_<?php echo $model->id; ?>" style="display:block">
<table border="0" width="95%" cellspacing="0" cellpadding="0" align="center">
<tbody><tr><td colspan="2"> 
<table border="0" width="100%" cellspacing="0" cellpadding="0" class="rellist">
<tbody><tr class="tableHeadBg">
<td nowrap>Name</td>
<td nowrap>Default for all customer</td>
</tr>
<?php
while ($row = $templateDR->read()) {
$i++;
?>
<tr>
<td class="tableData"> <?php echo CHtml::link($row['name'], '/template/view/'.$row['id'], array('target'=>'_blank','class'=>'sl noajax')); ?> </td>
<td class="tableData"> <?php echo $row['default_check'] ? 'Yes' : 'No' ?> </td>
</tr>
<?php
}
?>

</tbody></table>
</td></tr><!-- Field Data goes here --></tbody></table>
</div>
</div><br>
<?php } ?>




<?php
if($i==0){
  echo 'No search results match your condition.';
}
?>


</div><div class="cb"></div>

</div>
</div>