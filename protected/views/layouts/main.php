<?php
  if (Yii::app()->params['print']) {
    include('main_print.php');
  } else {
    include('main_normal_zoho.php');
  }
?>