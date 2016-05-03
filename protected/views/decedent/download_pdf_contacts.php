<div style="font-size:12pt;">
<!--Contacts-->
<br/>
<div id="contact_<?php echo $model->id; ?>" style="display:block"><strong>Contacts</strong><br/>
<table border="0" width="100%" cellspacing="0" cellpadding="0" align="center">
<tbody><tr><td colspan="2"> 
<table width="680" border="1" class="t1" cellpadding="3">
<tbody><tr class="tableHeadBg">
<th width="100" nowrap>Full Name</th>
<th nowrap>Address</th>
<th width="100" nowrap>Phone</th>
<th nowrap>Email</th>
<th nowrap>Note</th>
</tr>
<?php
while ($row = $contactDataProvider->read()) {
?>
<tr>
<td class="tableData" width="100" style="font-size:8pt;"> <?php echo $row['full_name'] ?> </td>
<td class="tableData" style="font-size:8pt;"> <?php echo $row['address'] ?> </td>
<td class="tableData" width="100" style="font-size:8pt;"><?php echo $row['phone'] ?> </td>
<td class="tableData" style="font-size:8pt;" nowrap> <?php echo $row['email'] ?> </td>
<td class="tableData" style="font-size:8pt;" nowrap> <?php echo $row['note'] ?> </td>
</tr>
<?php
}
?>

</tbody></table>
</td></tr>
<!-- Field Data goes here --></tbody></table>
</div>
<!--Contacts --------end-->

</div>