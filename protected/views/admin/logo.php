<?php $this->renderFile('protected/views/layouts/header_subpanel.php', array('title'=>'Control Panel')); ?>

<?php if (Yii::app()->user->type == 'super admin') { ?>
<h1>Company Admin</h1>
<?php } else { ?>
<h1><span appapptagid="31"></span> Logo</h1>
<?php } ?>

<?php
//echo '<pre>';
//print_r($companyRow);
//echo '</pre>';
//exit;

if (Yii::app()->user->type == 'admin') {
  $company_id = Yii::app()->user->company_id;
}
?>
<br/><br/>

<!--List-->
<table cellpadding="0" cellspacing="0" width="100%" border="0" class="listview">
  <tbody>
    <tr height="20">
      <td style="font-weight: bold; width: 25%; text-align: center;" >Company ID</td>
      <td style="font-weight: bold; width: 25%; text-align: center;">Company Name</td>
      <td style="font-weight: bold; width: 30%; text-align: center;">Logo</td>
      <td style="width: 20%;">&nbsp;</td>
    </tr>
    
    <tr height="65">
      <td style="width: 25%; text-align: center;"><?php echo $company_id;?></td>
      <td style="width: 25%; text-align: center;"><?php echo $companyRow['name']?></td>
      <td style="width: 30%; text-align: center;">
        <?php 
          if(file_exists($companyRow['logo'])){
            echo '<img src="/'.$companyRow['logo'].'" width="80" height="50"/>';
          }else{
            echo '<a href="/admin/logoadd/company_id/ '.$company_id.'?from=admin" >Add logo</a>';
          }
        ?>
      </td>
      <td style="width: 20%; text-align: center;">
       <!--<a href="javascript:void window.open('/admin/logoupdate/company_id/<?php // echo $company_id;?>?from=admin')" >Edit</a>-->
       <a href="/admin/logoupdate/company_id/<?php echo $company_id;?>?from=admin" >Edit</a>
       <span>|</span> 
       <!--<a href="javascript:void window.open('/admin/logodelete/company_id/<?php // echo $company_id;?>')" onclick="if(confirm('Are you sure delete logo?')){return true;} return false;">Delete</a>-->
       <a href="/admin/logodelete/company_id/<?php echo $company_id;?>?from=admin" onclick="if(confirm('Are you sure delete logo?')){return true;} return false;">Delete</a>
      </td>
    </tr>
    
  </tbody>
</table>


<?php $this->renderFile('protected/views/layouts/footer_subpanel.php'); ?>