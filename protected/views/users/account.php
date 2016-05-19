<?php
  $this->renderFile('protected/views/layouts/header_subpanel.php', array('title'=>'My Account')); 

  $this->menu=array(
    array('label'=>'Update account', 'url'=>array('updateaccount')),
  );
?>



<div class="user">
  <table border="0" width="50%" class="tabForm normalForm">
  <tr><td width="80%">Username: </td><td><?php echo $user->username; ?></td></tr>
  <tr><td>Email: </td><td><?php echo $user->email; ?></td></tr>
  <tr><td>First Name: </td><td><?php echo $user->firstname; ?></td></tr>
  <tr><td>Last Name: </td><td><?php echo $user->lastname; ?></td></tr>
  <?php
    if (Yii::app()->user->type == 'admin') {
      echo '<tr><td>Type: </td><td>'. $user->type .'</td></tr>';
    }
    
    if ($user->type == 'staff') {
      echo '<tr><td>Department: </td><td>'. $user->department .'</td></tr>';
    }
  ?>
  
  <tr><td height="50%"><button name="update_account" id="update_account" onclick="document.location.href='/users/updateaccount'">Update Account</button></td></tr>
  </table>

</div>

<?php $this->renderFile('protected/views/layouts/footer_subpanel.php'); ?>



  

