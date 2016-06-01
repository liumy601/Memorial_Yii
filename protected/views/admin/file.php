<?php $this->renderFile('protected/views/layouts/header_subpanel.php', array('title'=>'Control Panel')); ?>


<h1>Files</h1>

<?php

echo CHtml::link('Create new file', '/admin/file/op/create');

$files = new Files();
$fields = array(
    'id'=>'10%', 
    'name'=>'20%',
    'file'=>'30',
    'enteredby'=>'15%',
    'timestamp'=>'',
    );
?>
<br/><br/>

<!--List-->
<table cellpadding="0" cellspacing="0" width="100%" border="0" class="listview">
  <tbody>
    <tr height="20">
      <?php
      foreach ($fields as $field=>$width) {
     ?>
      
      <td scope="col" width="<?php echo $width; ?>" class="listViewThS1" nowrap="">
        <div style="white-space: nowrap;" width="100%" align="left"><?php echo CHtml::encode($files->getAttributeLabel($field)); ?>
        </div>
      </td>
      
      <?php
      }
     ?>
    
      <td scope="col" class="listViewThS1" nowrap="" width="1%">&nbsp;</td>
    </tr>

    <?php
      while($data = $dataProvider->read()){
        $data = (object)$data;
    ?>
    
    
    <tr height="20">
<?php
$i = 0;
foreach ($fields as $field => $width) {
  $i++;
?>

  <td scope="row" align="left" valign="top" class="<?php echo ($i % 2) ? 'oddListRowS1' : 'evenListRowS1'; ?>" bgcolor="#ffffff">
  <?php if ($i == 1) { ?>
    <a style="display:block;" href="/admin/file/op/update/id/<?php echo $data->id; ?>" class="listViewTdLinkS1"><?php echo CHtml::encode($data->$field); ?></a>
  <?php } else if ($i == 3) { ?>
    <?php echo CHtml::link($data->{$field.'name'}, '/'.$data->file, array('target'=>'_blank', 'class'=>'sl noajax')); ?>
  <?php } else if ($i == 5) { ?>
    <?php echo date('m/d/Y', $data->$field); ?>
  <?php } else {
    echo CHtml::encode($data->$field); 
    } 
  ?>
  </td>

<?php } ?>

  <td width="1%" class="oddListRowS1" bgcolor="#ffffff" nowrap="">
    <a title="Edit" href="/admin/file/op/update/id/<?php echo $data->id; ?>"><img border="0" src="/themes/Sugar/images/edit_inline.gif">edit</a>&nbsp;
    <a href="#" class="listViewTdToolsS1" url="/admin/file/op/delete/id/<?php echo $data->id ?>" onclick="if(confirm('Are you sure delete this record?')){ ajaxRequest(this); } return false;"><img src="/themes/Sugar/images/delete_inline.gif" width="12" height="12" align="absmiddle" alt="rem" border="0">rem</a>
  </td>
</tr>

<?php } ?>

</tbody></table>


<?php $this->renderFile('protected/views/layouts/footer_subpanel.php'); ?>
