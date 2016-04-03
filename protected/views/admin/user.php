<?php $this->renderFile('protected/views/layouts/header_subpanel.php', array('title'=>'Control Panel')); ?>

<?php if (Yii::app()->user->type == 'super admin') { ?>
<h1>Company Admin</h1>
<?php } else { ?>
<h1><span appapptagid="31"></span> User</h1>
<?php } ?>

<?php

echo CHtml::link('Create new user', '/admin/user/op/create');

$user = new Users();
$fields = array(
    'id'=>'16%', 
    'username'=>'16%',
    'firstname'=>'12%',
    'lastname'=>'12%',
    'email'=>'16%',
);

if (Yii::app()->user->type == 'super admin') {
  $fields['company_id'] = '';
}
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
        <div style="white-space: nowrap;" width="100%" align="left"><?php echo CHtml::encode($user->getAttributeLabel($field)); ?>
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
    <a style="display:block;" href="/admin/user/op/update/id/<?php echo $data->id; ?>" class="listViewTdLinkS1"><?php echo CHtml::encode($data->$field); ?></a>
  <?php } else {
      if ($field == 'company_id') {
        $company = Company::model()->findByPk($data->company_id);
        echo CHtml::encode($company->name); 
      } else {
        echo CHtml::encode($data->$field); 
      }
    } 
  ?>
  </td>

<?php } ?>

  <td width="1%" class="oddListRowS1" bgcolor="#ffffff" nowrap="">
    <a title="Edit" href="/admin/user/op/update/id/<?php echo $data->id; ?>"><img border="0" src="/themes/Sugar/images/edit_inline.gif">edit</a>&nbsp;
    <a href="#" class="listViewTdToolsS1" url="/admin/user/op/delete/id/<?php echo $data->id ?>" onclick="if(confirm('Are you sure delete this record?')){ ajaxRequest(this); } return false;"><img src="/themes/Sugar/images/delete_inline.gif" width="12" height="12" align="absmiddle" alt="rem" border="0">rem</a>
  </td>
</tr>

<?php } ?>

  

</tbody></table>


<?php $this->renderFile('protected/views/layouts/footer_subpanel.php'); ?>