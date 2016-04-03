<?php $this->renderFile('protected/views/layouts/header.php'); ?>

<?php $this->pageTitle=Yii::app()->name; ?>

<table border="0" cellpadding="8" style="width: 100%;">
  <tr>
    <!--<td valign="top">Home page</td>-->
    <td valign="top">
      <div style="float:right;"><span appapptagid="35"></span></div>
      <?php echo $model->homepage; ?>
    </td>
    <td valign="top" style="width:290px;">
      <!--  Dashlets  -->
      
      <!---My Files --->
      <?php
      
      require_once('protected/controllers/AdminController.php');
      $adminController = new AdminController('admin');
      echo $adminController->actionMyFiles(true);
      ?>
      
      <!--   My Tasks   -->
      <?php
      require_once('protected/controllers/TaskController.php');
      $taskController = new TaskController('task');
      echo $taskController->actionMytask(true);
      ?>
      
    </td>
  </tr>
</table>

<?php $this->renderFile('protected/views/layouts/footer.php'); ?>
