<div id="my-task-dashlet">
<!--list-->
<div class="idForCVpadding" style="padding-top:0;">
  <div class="listviewBorder">
  <table border="0" width="100%" cellspacing="0" cellpadding="0" class="listview">
<tbody>
<tr>
<td style="font-weight:bold;">My Tasks <?php echo $GLOBALS['itemCount'] ? '('.$GLOBALS['itemCount'].')' : '' ?></td>
<td colspan="2" style="font-weight:bold;">

<!--pager-->
<table height="25" border="0" cellpadding="0" cellspacing="1" style="float: right;"><tbody><tr>
<td class="viewbg" align="right">
 <?php if($GLOBALS['page'] == 1){ ?>
  <div class="previousDisabled"></div>
  <?php } else { ?>
  <div class="previous" onclick="dashletMyTaskChangePage(<?php echo $GLOBALS['page']-1; ?>, 'my-task-dashlet')"></div>
  <?php } ?>
</td>

<td>
  <?php if($GLOBALS['page'] == $GLOBALS['pageCount'] || $GLOBALS['pageCount'] == 0){ ?>
  <div class="nextDisabled"></div>
  <?php } else { ?>
  <div class="next" onclick="dashletMyTaskChangePage(<?php echo $GLOBALS['page']+1; ?>, 'my-task-dashlet')"></div>
  <?php } ?>
</td>
</tr></tbody></table>

</td>
</tr>
<tr class="tableHeadBg">
<td class="listViewThS1" nowrap>Subject</td>
<td class="listViewThS1" nowrap>Status</td>
<td class="listViewThS1" nowrap>Due date</td>
</tr>

<?php
$i=0;
while ($row = $taskDR->read()) {
  $i++;
  if ($row['assigned_to']) {
    $assignedToUser = Users::model()->findByPk($row['assigned_to']);
  }
?>
<tr>
<td class="tableData" nowrap> <a class="f12" title="<?php echo $row['subject']; ?>" href="/task/view/<?php echo $row['id'] ?>"><?php echo (strlen($row['subject']) > 15) ? substr($row['subject'], 0, 15) : $row['subject'] ?></a> </td>
<td class="tableData" nowrap> <?php echo $row['status'] ?> </td>
<td class="tableData" nowrap> <?php echo $row['date_due'] ?> </td>
</tr>
<?php } ?>

<?php
//piece out to reach 5 lines
if($i<5){
  $addon = 5-$i;
  for($k=0;$k<$addon;$k++){
?>
<tr>
<td class="tableData" nowrap colspan="3"> </td>
</tr>
<?php
  }
}?>

</tbody></table>
</div><!--listviewBorder-->
</div><!--idForCV-->
</div><!--ShowCustomViewDetails-->