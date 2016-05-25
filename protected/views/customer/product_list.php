<table border="0" width="95%" align="center" cellspacing="0" cellpadding="0" class="relCenterBg" id="productlist"><tbody><tr height="25">
<td width="140" nowrap=""> 
<a href="javascript:toggleRelatedList('relationship_product_<?php echo $model->id; ?>');" 
   name="relationship_product_<?php echo $model->id; ?>" id="relationship_product_<?php echo $model->id; ?>" class="relListHead">
Products</a> <span appapptagid="17"></span>
</td>
<td align="right"> <div id="ract_<?php echo $model->id; ?>" name="raction"> </td>
</tr></tbody>
</table>
<div id="product_<?php echo $model->id; ?>" style="display:block">
<table border="0" width="95%" cellspacing="0" cellpadding="0" align="center">
<tbody><tr><td colspan="7"> 
<table border="0" width="100%" cellspacing="0" cellpadding="0" class="rellist">
<tbody><tr class="tableHeadBg"><td>&nbsp;</td> 
<td nowrap>Name</td>
<td nowrap>Vendor</td>
<td nowrap>SKU</td>
<td nowrap>Retail</td>
<!--<td nowrap>Cost</td>-->
<td nowrap>Category</td>
<td nowrap>Taxable</td>
</tr>
<?php
$totalTax = 0;
$totalPrice = 0;
while ($row = $productDataProvider->read()) {
  $totalPrice += $row['retail'];
  if($row['taxable'] == 1){
    $totalTax += $row['retail'];
    $taxRate = Config::load('tax')->value;
    $tax = $totalTax * $taxRate;
  }
?>
<tr>
<td nowrap="" width="12%" class="tableData"> <div align="left">&nbsp;
  <a class="sl" href="/customer/editproduct/<?php echo $row['product_id']; ?>?from=customer">Edit</a>
  <span class="sep">|</span> 
  <a href="#" class="listViewTdToolsS1 noajax" onclick="if(confirm('Are you sure delete this product?')){ deleteProduct(this, <?php echo $row['product_id'] ?>); } return false;">Remove</a>
</div>
</td>
<td class="tableData"> <?php echo $row['name'] ?> </td>
<td class="tableData"> <?php echo $row['vendor'] ?> </td>
<td class="tableData"> <?php echo $row['sku'] ?> </td>
<td class="tableData" id="retail_<?php echo $row['product_id'];?>">$<span id="product_retail1_<?php echo $row['product_id']; ?>" style="color:red;" onclick="alterInput(<?php echo $row['product_id'];?>,<?php echo $model->id;?>);"> <?php 
     echo number_format($row['retail'], 2);
  ?></span>
</td>
<!--<td class="tableData"> <?php // echo $row['cost'] ? '$'.$row['cost'] : '' ?> </td>-->
<td class="tableData"> <?php echo $row['category'] ?> </td>
<td class="tableData"><?php echo CHtml::checkBox('taxable', $row['taxable'], array('disabled'=>true))?></td>
</tr>
<?php
}
?>

</tbody></table>
</td></tr>

 <tr class="rellistbL">
   <td colspan="5"></td>
   <td style="text-align: right;" width="10%">Total:</td>  
   <td width="7%">$<span id="span_total_price"><?php echo number_format($totalPrice + $tax - $discount, 2); ?></span></td>
 </tr>
 <tr class="rellistbL">
  <td align="left" colspan="0">
    <div id="ra">
     <a href="#" class="rellistNew" class="noajax" onclick="dialogAddProduct();return false;">Add product</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
     <a href="#" class="rellistNew" class="noajax" onclick="dialogAddPackage();return false;">Add package</a>
    </div>
  </td>
  <td colspan="4"></td>
  <td align="right">Received:</td>
  <td> $<span id="span_payment"><?php echo number_format($total_payments, 2); ?></span></td>
 </tr>
 <tr class="rellistbL">
   <td colspan="5"></td><td><span style="text-align: right;">Balance Due:</span></td>  <td>  $<span id="span_total_price"><?php echo number_format($totalPrice + $tax - $total_payments - $tatal_balances, 2); ?></span></td>
 </tr>
 

<!-- Field Data goes here --></tbody></table>
</div>