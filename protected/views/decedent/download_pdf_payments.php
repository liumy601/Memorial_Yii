<div style="font-size:10pt;">Payments</div>
<!--Payments-->
<table width="100%" border="1" class="t1" cellpadding="3" align="center" style="margin-left:20px;">
<tbody><tr class="tableHeadBg">
<th width="15%" nowrap>Payer</th>
<th width="16%" nowrap>Type</th>
<th width="16.66%" nowrap>Address</th>
<th width="16.66%" nowrap>Date</th>
<th width="18%" nowrap>Method</th>
<th width="16.66%" nowrap>Amount</th>
</tr>
<?php
$total = 0;
while ($row = $paymentsDataProvider->read()) {
$total += $row['amount'];
?>
<tr>
<td class="tableData" width="15%" style="font-size:8pt;"> <?php echo $row['payer'] ?> </td>
<td class="tableData" width="16%" style="font-size:8pt;"> <?php echo $row['type'] ?> </td>
<td class="tableData" width="16.66%" style="font-size:8pt;"><?php echo $row['address'] ?> </td>
<td class="tableData" width="16.66%" style="font-size:8pt;"><?php echo $row['date'] ?> </td>
<td class="tableData" width="18%" style="font-size:8pt;"> <?php echo $row['payment_method'] ?> </td>
<td class="tableData" width="16.66%" style="font-size:8pt;"> $<?php echo $row['amount'] ?> </td>
</tr>
<?php
}
?>

<tr>
<td class="tableData" width="15%" style="font-size:8pt;"> </td>
<td class="tableData" width="16%" style="font-size:8pt;"> </td>
<td class="tableData" width="16.66%" style="font-size:8pt;"> </td>
<td class="tableData" width="16.66%" style="font-size:8pt;"> </td>
<td class="tableData" width="18%" style="font-size:8pt;"><b>SubTotal:</b></td>
<td class="tableData" width="16.66%" style="font-size:8pt;"><b>$<?php echo number_format($total, 2) ?></b></td>
</tr>

</tbody></table>