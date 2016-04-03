<div style="font-size:12pt;">


<!--Products-->
<br/><br/>
<div id="product_<?php echo $model->id; ?>" style="display:block">
  <h3>(invoice notes only)</h3>
<table border="0" width="95%" cellspacing="0" cellpadding="0" align="center">
<tbody><tr><td colspan="2"> 
<table width="680" border="1" class="t1" cellpadding="3">
<tbody><tr class="tableHeadBg">
<th width="100" nowrap>Name</th>
<th nowrap>Vendor</th>
<th nowrap>Retail</th>
</tr>
<?php
while ($row = $productDataProvider->read()) {
?>
<tr>
<td class="tableData" width="100" style="font-size:8pt;"> <?php echo $row['name'] ?> </td>
<td class="tableData" style="font-size:8pt;"> <?php echo $row['vendor'] ?> </td>
<td class="tableData" style="font-size:8pt;" nowrap> <?php echo $row['retail'] ?> </td>
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