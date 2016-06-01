<div id="notes_list">
<table border="0" width="95%" align="center" cellspacing="0" cellpadding="0" class="relCenterBg">
<tbody><tr height="25">
<td width="140" nowrap="">
<a href="javascript:toggleRelatedList('relationship_notes_<?php echo $parent_id; ?>');" 
   name="relationship_notes_<?php echo $parent_id; ?>" id="relationship_notes_<?php echo $parent_id; ?>" class="relListHead">Notes</a>
</td>
<td align="right"> <div id="ract_<?php echo $parent_id; ?>" name="raction"> </td>
</tr></tbody>
</table>
<div id="notes_<?php echo $parent_id; ?>" style="display:block">
<table border="0" width="95%" cellspacing="0" cellpadding="0" align="center">
<tbody><tr><td colspan="2"> 
<table border="0" width="100%" cellspacing="0" cellpadding="0" class="rellist">
<tbody><tr class="tableHeadBg"><td>&nbsp;</td> 
<td>Subject</td>
<td width="40%">Body</td>
<td>Entered By</td>
<td>Entered Time</td>
</tr>
<?php
while ($row = $notesDR->read()) {
?>
<tr>
<td nowrap="" width="12%" class="tableData"> <div align="left">&nbsp;
  <a class="sl noajax" onclick="dialogUpdateNotes(<?php echo "'". $parent_type ."', " . $parent_id ?>, <?php echo $row['id']; ?>);return false;">Edit</a>
  <span class="sep">|</span> 
  <a href="#" class="listViewTdToolsS1 noajax" onclick="if(confirm('Are you sure delete this note?')){ deleteNote(this, <?php echo $row['id'] ?>); } return false;">Remove</a>
</div>
</td>
<td class="tableData"> <a class="f12" href="/notes/view/<?php echo $row['id']; ?>"><?php echo $row['subject'] ?></a> </td>
<td class="tableData"> <?php echo nl2br(htmlspecialchars($row['body'])) ?> </td>
<td class="tableData"> <?php echo $row['enteredby'] ?> </td>
<td class="tableData"> <?php echo $row['timestamp'] ? date('m/d/Y H:i', $row['timestamp']) : '' ?> </td>
</tr>
<?php
}
?>

</tbody></table>
</td></tr><tr class="rellistbL"><td align="left"><div id="ra_<?php echo $parent_id; ?>">
<a href="#" class="rellistNew" class="noajax" onclick="dialogCreateNotes(<?php echo "'". $parent_type ."', " . $parent_id ?>);return false;">New note</a></div></td> 
<td align="right">&nbsp;<div align="right" class="listNav"></div></td></tr> <!-- Field Data goes here --></tbody></table>
</div>
</div><br>