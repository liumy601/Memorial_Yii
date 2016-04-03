<?php $this->renderFile('protected/views/layouts/header_subpanel.php', array('title'=>'Control Panel')); ?>


<h1>Department</h1>
<table cellpadding="0" cellspacing="0" width="100%" border="0" class="listview">
  <tbody>
    <tr>
      <td width="10%"><b>Name</b></td>
      <td width=""><?php echo $model->name; ?></td>
    </tr>
    <tr>
      <td width="10%"><b>Description</b></td>
      <td width=""><?php echo $model->description; ?></td>
    </tr>
  </tbody>
</table>

<br/>
<h3>Users</h3><a href="javascript:roleAddUser(<?php echo $model->id; ?>);"><img src="/images/add_user.png"/>Add</a>
<table cellpadding="0" cellspacing="0" width="100%" border="0" class="listview"id="role_user_list">
  <thead>
    <tr>
      <td width="10%" nowrap="">Username</td>
      <td width="15%" nowrap="">Name</td>
      <td width="15%" nowrap="">Email</td>
      <td width="15%" nowrap="">School</td>
      <td width="" nowrap=""></td>
    </tr>
  </thead>
  <tbody>
    <?php
    if($users){
      foreach($users as $user){
    ?>
    <tr>
      <td><?php echo $user['username'] ?></td>
      <td><?php echo $user['firstname'] . ' ' . $user['lastname'] ?></td>
      <td><?php echo $user['email'] ?></td>
      <td><?php echo $user['school'] ?></td>
      <td>
        <a href="#" url="/admin/role/op/deleteUser?rid=<?php echo $model->id ?>&uid=<?php echo $user['id'] ?>" onclick="if(confirm('Are you sure delete this record?')){ ajaxRequest(this); } return false;"><img src="/themes/Sugar/images/delete_inline.gif" width="12" height="12" align="absmiddle" alt="rem" border="0">rem</a>
      </td>
    </tr>
    <?php
      }
    }
    ?>
  </tbody>
</table>

<br/>
<h3>Permissions</h3>
<form class="noajax" method="post" id="perm_form" action="/admin/role/op/savePermission" target="hiddenifm">
<input type="hidden" name="rid" value="<?php echo $model->id; ?>"/>
<table cellpadding="0" cellspacing="0" width="100%" border="0" class="listview">
  <thead>
    <tr>
      <td width="10%" nowrap="">Section</td>
      <td style="width:95px">View</td>
      <td style="width:95px">Edit</td>
      <td>Delete</td>
    </tr>
  </thead>
  <tbody>
    <?php
    $sections = array(
        'Action_Log' => 'Action Log',
        'Student' => 'Student',
        'Guest_Pass' => 'Guest Pass',
        'Inventory' => 'Inventory',
        'Parking_Ticket' => 'Parking Ticket',
        'Room' => 'Room',
        'Duty_Log' => 'Duty Log',
        'Generic' => 'Generic',
        'Housing' => 'Housing',
        'Actions' => 'Actions',
        'Task' => 'Task',
    );
      foreach($sections as $section=>$sectionText){
    ?>
    <tr>
      <td width="10%" nowrap=""><?php echo $sectionText ?></td>
      <td><input type="checkbox" name="<?php echo $section ?>[view]" value="1"<?php echo $perm[$section]['view'] ? ' checked' : ''; ?>/></td>
      <td><input type="checkbox" name="<?php echo $section ?>[edit]" value="1"<?php echo $perm[$section]['edit'] ? ' checked' : ''; ?>/></td>
      <td><input type="checkbox" name="<?php echo $section ?>[delete]" value="1"<?php echo $perm[$section]['delete'] ? ' checked' : ''; ?>/></td>
    </tr>
    <?php
      }
    ?>
  </tbody>
</table>
  <input type="button" class="button" value="Save" onclick="showTip('Loading...');$('#perm_form').submit()"/>
</form>
<iframe src="" name="hiddenifm" id="hiddenifm" style="display:none;"></iframe>


<?php $this->renderFile('protected/views/layouts/footer_subpanel.php'); ?>