<div id="my-file-dashlet">
<!--list-->
<div class="idForCVpadding" style="padding-top:20;">
  <div class="listviewBorder">
  <table border="0" width="100%" cellspacing="0" cellpadding="0" class="listview">
<tbody>
<tr>
<td style="font-weight:bold;">My Files <?php echo $GLOBALS['itemCount'] ? '('.$GLOBALS['itemCount'].')' : '' ?></td>
<td style="font-weight:bold;">

<!--pager-->
<table height="25" border="0" cellpadding="0" cellspacing="1" style="float: right;"><tbody><tr>
<td class="viewbg" align="right">
 <?php if($GLOBALS['page'] == 1){ ?>
  <div class="previousDisabled"></div>
  <?php } else { ?>
  <div class="previous" onclick="dashletMyFilesChangePage(<?php echo $GLOBALS['page']-1; ?>, 'my-file-dashlet')"></div>
  <?php } ?>
</td>

<td>
  <?php if($GLOBALS['page'] == $GLOBALS['pageCount'] || $GLOBALS['pageCount'] == 0){ ?>
  <div class="nextDisabled"></div>
  <?php } else { ?>
  <div class="next" onclick="dashletMyFilesChangePage(<?php echo $GLOBALS['page']+1; ?>, 'my-file-dashlet')"></div>
  <?php } ?>
</td>
</tr></tbody></table>

</td>
</tr>
<tr class="tableHeadBg">
<td class="listViewThS1" nowrap>Name</td>
<td class="listViewThS1" nowrap>Last Updated</td>
</tr>

<?php
$i=0;
while ($row = $filesDR->read()) {
  $i++;
?>
<tr>
<td class="tableData" nowrap> <a class="f12 noajax" title="<?php echo $row['name']; ?>" href="/site/downloadFiles/<?php echo $row['id'] ?>" target="_blank"><?php echo (strlen($row['name']) > 25) ? substr($row['name'], 0, 25) : $row['name'] ?></a> </td>
<td class="tableData" nowrap> <?php echo date('m/d/Y H:i', $row['timestamp']) ?></a> </td>
</tr>
<?php } ?>


<?php
//piece out to reach 5 lines
if($i<5){
  $addon = 5-$i;
  for($k=0;$k<$addon;$k++){
?>
<tr>
<td class="tableData" nowrap colspan="2"> </td>
</tr>
<?php
  }
}?>

</tbody></table>
</div><!--listviewBorder-->
</div><!--idForCV-->
</div><!--ShowCustomViewDetails-->