<div style="font-size:12pt;font-weight: bold;"><?php echo $title; ?></div>
<!--<table width="100%" border="1" class="t1" cellpadding="3" cellspacing="0" align="center">-->
<table width="100%" border="1" class="t1" cellpadding="3" cellspacing="0" align="center">
<tbody><tr class="tableHeadBg">
<th width="40%" nowrap>Name</th>
<th width="30%" nowrap>Vendor</th>
<th width="30%" nowrap>Retail</th>
</tr>
<?php
$total = 0;
while ($row = $productDataProvider->read()) {
  $total += $row['retail'];
  
  $productNameNotes = $row['name'];
  $invoice_notes = $row['invoice_notes_product'] ? $row['invoice_notes_product'] : $row['invoice_notes'];
  if ($invoice_notes != '') {
    $productNameNotes .= ': <span style="font-size:8pt;">' . $invoice_notes . '</span>';
  }
?>
<tr>
  <td class="tableData" width="40%" style="font-size:8pt; text-align: center;"> <?php echo $productNameNotes; ?> </td>
  <td class="tableData" width="30%" style="font-size:8pt; text-align: center;"> <?php echo $row['vendor'] ?> </td>
  <td class="tableData" width="30%" style="font-size:8pt; text-align: center;" nowrap>
    $<?php echo number_format($row['retail'], 2); ?>
  </td>
</tr>
<?php
}
?>

<tr>
  <td class="tableData" width="40%" style="font-size:8pt;"> </td>
  <td class="tableData" width="30%" style="font-size:8pt;" nowrap> </td>
  <td class="tableData" width="30%" style="font-size:8pt;" nowrap> <b>SubTotal:</b> <b>$<?php echo number_format($total, 2) ?></b> </td>
</tr>
</tbody></table>