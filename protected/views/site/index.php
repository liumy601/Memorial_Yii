<script>
(function(i,s,o,g,r,a,m)
Unknown macro: {i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){ (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o), m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m) }
)(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
ga('create', 'UA-46600175-2', 'auto');
ga('send', 'pageview');
</script> 
<?php $this->renderFile('protected/views/layouts/header.php'); ?>

<?php $this->pageTitle=Yii::app()->name; ?>

<table border="0" cellpadding="8" style="width: 100%;">
  <tr>
    <!--<td valign="top">Home page</td>-->
    <td valign="top">
      <div style="float:right;"></div>
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
