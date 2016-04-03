<?php $this->renderFile('protected/views/layouts/header_subpanel.php', array('title'=>'Control Panel')); ?>


<h1>Company Staff</h1>

<?php

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
    <?php echo CHtml::encode($data->$field); ?>
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
</tr>

<?php } ?>

  

</tbody></table>


<?php $this->renderFile('protected/views/layouts/footer_subpanel.php'); ?>