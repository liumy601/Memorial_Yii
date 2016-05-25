<?php $this->renderFile('protected/views/layouts/header_subpanel.php', array('title'=>'Control Panel')); ?>

<h1>User: <?php echo $user->firstname . ' ' . $user->lastname; ?></h1>

<div class="user">
  <table border="0" width="50%" class="tabForm normalForm">
  <tr><td>ID: </td><td><?php echo $user->id; ?></td></tr>
  <tr><td>Username: </td><td><?php echo $user->username; ?></td></tr>
  <tr><td>Email: </td><td><?php echo $user->email; ?></td></tr>
  <tr><td>First name: </td><td><?php echo $user->firstname; ?></td></tr>
  <tr><td>Last name: </td><td><?php echo $user->lastname; ?></td></tr>
  <?php
    if (Yii::app()->user->type == 'admin') {
      echo '<tr><td>Type: </td><td>'. $user->type .'></td></tr>';
    }
    
    if ($user->type == 'staff') {
      echo '<tr><td>Department: </td><td>'. $user->department .'</td></tr>';
    }
  ?>
  
  <tr><td>Status: </td><td><?php echo $user->status ? 'Active' : 'Inactive'; ?></td></tr>
  </table>

</div>

<?php $this->renderFile('protected/views/layouts/footer_subpanel.php'); ?>
