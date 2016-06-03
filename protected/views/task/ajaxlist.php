<div id="task_list">
<table border="0" width="95%" align="center" cellspacing="0" cellpadding="0" class="relCenterBg">
<tbody><tr height="25">
<td width="140" nowrap="">
<a href="javascript:toggleRelatedList('relationship_task_<?php echo $parent_id; ?>');" 
   name="relationship_task_<?php echo $parent_id; ?>" id="relationship_task_<?php echo $parent_id; ?>" class="relListHead">Tasks</a>
</td>
<td align="right"> <div id="ract_<?php echo $parent_id; ?>" name="raction"> </td>
</tr></tbody>
</table>
<div id="task_<?php echo $parent_id; ?>" style="display:block">
<table border="0" width="95%" cellspacing="0" cellpadding="0" align="center">
<tbody><tr><td colspan="2"> 
<table border="0" width="100%" cellspacing="0" cellpadding="0" class="rellist">
<tbody><tr class="tableHeadBg"><td>&nbsp;</td> 
<td>Subject</td>
<td>Status</td>
<td>Due date</td>
<td>Assigned User</td>
<td>Entered By</td>
<td>Entered Time</td>
</tr>
<?php
while ($row = $taskDR->read()) {
  if ($row['assigned_to']) {
    $assignedToUser = Users::model()->findByPk($row['assigned_to']);
  }
?>
<tr>
<td nowrap="" width="12%" class="tableData"> <div align="left">&nbsp;
  <a class="sl noajax" onclick="dialogUpdateTask(<?php echo "'". $parent_type ."', " . $parent_id ?>, <?php echo $row['id']; ?>);return false;">Edit</a>
  <span class="sep">|</span> 
  <a href="#" class="listViewTdToolsS1 noajax" onclick="if(confirm('Are you sure delete this task?')){ deleteTask(this, <?php echo $row['id'] ?>); } return false;">Remove</a>
</div>
</td>
<td class="tableData"> <a class="f12" href="/task/view/<?php echo $row['id']; ?>"><?php echo $row['subject'] ?></a> </td>
<td class="tableData"> <?php echo $row['status'] ?> </td>
<td class="tableData"> <?php echo $row['date_due'] != '0000-00-00 00:00:00' ? $row['date_due'] : '' ?> </td>
<td class="tableData"> <?php echo $assignedToUser->username ?> </td>
<td class="tableData"> <?php echo $row['enteredby'] ?> </td>
<td class="tableData"> <?php echo $row['timestamp'] ? date('m/d/Y H:i', $row['timestamp']) : '' ?> </td>
</tr>
<?php
}
?>

</tbody></table>
</td></tr><tr class="rellistbL"><td align="left"><div id="ra_<?php echo $parent_id; ?>">
<a href="#" class="rellistNew" class="noajax" onclick="dialogCreateTask(<?php echo "'". $parent_type ."', " . $parent_id ?>);return false;">New task</a></div></td> 
<td align="right">&nbsp;<div align="right" class="listNav"></div></td></tr> <!-- Field Data goes here --></tbody></table>
</div>
</div><br>