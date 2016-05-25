<tr height="20">

<?php
$fields = array(
  'department' => '16%',
  'type' => '16%',
  'subject' => '20%',
  'studentid' => '16%',
  'date_entered' => '16%'
);
?>

<?php
$i = 0;
foreach ($fields as $field => $width) {
  $i++;
?>

  <td scope="row" align="left" valign="top" class="<?php echo ($i % 2) ? 'oddListRowS1' : 'evenListRowS1'; ?>" bgcolor="#ffffff">
  <?php if ($i == 3) { ?>
    <a href="/task/view/<?php echo $data->id; ?>" class="listViewTdLinkS1"><?php echo CHtml::encode($data->$field); ?></a>
  <?php } else { 
    echo CHtml::encode($data->$field); } 
  ?>
  </td>

<?php } ?>

  <td width="1%" class="oddListRowS1" bgcolor="#ffffff" nowrap="">
    <a title="Edit" href="/task/update/<?php echo $data->id; ?>">
      <img border="0" src="/themes/Sugar/images/edit_inline.gif">
    </a>
  </td>
</tr>