<div style="font-size:12pt;">


<!--Products-->
<br/><br/>
<div id="product_<?php echo $model->id; ?>" style="display:block">
  <h1>Products</h1><h3>(with internal notes)</h3>
<table border="0" width="95%" cellspacing="0" cellpadding="0" align="center">
<tbody><tr><td colspan="2"> 
<table width="680" border="1" class="t1" cellpadding="3">
<tbody><tr class="tableHeadBg">
<th width="100" nowrap>Name</th>
<th nowrap>Vendor</th>
<th width="100" nowrap>SKU</th>
<th nowrap>Retail</th>
<th nowrap>Cost</th>
</tr>
<?php
while ($row = $productDataProvider->read()) {
?>
<tr>
<td class="tableData" width="100" style="font-size:8pt;"> <?php echo $row['name'] ?> </td>
<td class="tableData" style="font-size:8pt;"> <?php echo $row['vendor'] ?> </td>
<td class="tableData" width="100" style="font-size:8pt;"><?php echo $row['sku'] ?> </td>
<td class="tableData" style="font-size:8pt;" nowrap> <?php echo $row['retail'] ?> </td>
<td class="tableData" style="font-size:8pt;" nowrap> <?php echo $row['cost'] ?> </td>
</tr>
<?php
}
?>

</tbody></table>
</td></tr>
<!-- Field Data goes here --></tbody></table>
</div><br>
<!--Products --------end-->

</div>